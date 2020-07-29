import BuyerBillingService from '@/modules/billing/buyer/api';

const BUYER_BILLING_LIST = 'BUYER_BILLING_LIST';
const SET_ERROR = 'BUYER_BILLING_SET_ERROR';
const MESSAGE_FORMS = 'MESSAGE_FORMS';

const state = {
    listBuyerBilling: { data:[] },
    messageForms: { action: '', message: '', errors: {} },
}

const mutations = {
    [MESSAGE_FORMS] (state, payload) {
        state.messageForms = payload;
    },

    [BUYER_BILLING_LIST] (state , data) {
        state.listBuyerBilling = data.listBuyerBilling;
    },

    [SET_ERROR](state, error) {
        state.error = error;
    },
}

const actions = {
    async actionGetBuyerBilling({ commit }, params){
        try{
            let response = await BuyerBillingService.getList(params);
            commit(BUYER_BILLING_LIST, { listBuyerBilling: response.data });
        }catch( e ) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SET_ERROR, errors);
            } else {
                commit(SET_ERROR, e.response.data);
            }
        }
    },

    clearMessageForm({commit}) {
        commit(MESSAGE_FORMS, { action: '', message: '', errors: {}});
    },
}

const storeBillingBuyer = {
    state,
    mutations,
    actions,
};

export default storeBillingBuyer;