import PurchaseService from '@/modules/purchase/api';

const PURCHASE_LIST = 'PURCHASE_LIST';
const SET_ERROR = 'PURCHASE_SET_ERROR';
const MESSAGE_FORMS = 'MESSAGE_FORMS';

const state = {
    listPurchase: { data:[] },
    messageForms: { action: '', message: '', errors: {} },
}

const mutations = {
    [MESSAGE_FORMS] (state, payload) {
        state.messageForms = payload;
    },

    [PURCHASE_LIST] (state , data) {
        state.listPurchase = data.listPurchase;
    },

    [SET_ERROR](state, error) {
        state.error = error;
    },
}

const actions = {

    async actionGetPurchaseList({ commit }, params){
        try{
            let response = await PurchaseService.getList(params);
            commit(PURCHASE_LIST, { listPurchase: response.data });
        }catch( e ) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SET_ERROR, errors);
            } else {
                commit(SET_ERROR, e.response.data);
            }
        }
    },

    clearMessageform({commit}) {
        commit(MESSAGE_FORMS, { action: '', message: '', errors: {}});
    },
}

const storePurchase = {
    state,
    mutations,
    actions
};

export default storePurchase;