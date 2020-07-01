import SellerBillingService from '@/modules/billing/seller/api';

const SELLER_BILLING_LIST = 'SELLER_BILLING_LIST';
const SET_ERROR = 'SELLER_BILLING_SET_ERROR';
const MESSAGE_FORMS = 'MESSAGE_FORMS';

const state = {
    listSellerBilling: { data:[] },
    messageForms: { action: '', message: '', errors: {} },
}

const mutations = {
    [MESSAGE_FORMS] (state, payload) {
        state.messageForms = payload;
    },

    [SELLER_BILLING_LIST] (state , data) {
        state.listSellerBilling = data.listSellerBilling;
    },

    [SET_ERROR](state, error) {
        state.error = error;
    },
}

const actions = {
    async actionGetSellerBilling({ commit }, params){
        try{
            let response = await SellerBillingService.getList(params);
            commit(SELLER_BILLING_LIST, { listSellerBilling: response.data });
        }catch( e ) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SET_ERROR, errors);
            } else {
                commit(SET_ERROR, e.response.data);
            }
        }
    },
}

const storeBillingSeller = {
    state,
    mutations,
    actions,
};

export default storeBillingSeller;