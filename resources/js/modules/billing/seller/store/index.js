import SellerBillingService from '@/modules/billing/seller/api';

const SELLER_BILLING_LIST = 'SELLER_BILLING_LIST';
const SET_ERROR = 'SELLER_BILLING_SET_ERROR';
const MESSAGE_FORMS = 'MESSAGE_FORMS';
const LIST_PAYMENT = 'LIST_PAYMENT';
const LIST_SELLER = 'LIST_SELLER_ARTICLE_FILTER';

const state = {
    listSellerBilling: { data:[] },
    messageForms: { action: '', message: '', errors: {} },
    listPayment: { data: [] },
    listSeller: { data: [] },
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

    [LIST_PAYMENT] (state, list) {
        state.listPayment = list.listPayment;
    },

    [LIST_SELLER] (state, list) {
        state.listSeller = list.listSeller;
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

    async actionGetListPayentType({ commit }, params) {
        try {
            let response = await SellerBillingService.getPaymentType(params);
            commit(LIST_PAYMENT, { listPayment: response.data });
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SET_ERROR, errors);
            } else {
                commit(SET_ERROR, e.response.data);
            }
        }
    },

    async actionGetListSeller({ commit }, params){
        try {
            let response = await SellerBillingService.getListSeller(params)
            commit(LIST_SELLER , { listSeller: response.data });
        }catch(e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SET_ERROR, errors);
            } else {
                commit(SET_ERROR, e.response.data);
            }
        }
    },

    async actionPay({ commit }, params) {
        try {
            let response = await SellerBillingService.payBilling(params);

            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'success', message: 'Sucessfully Payed', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
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

const storeBillingSeller = {
    state,
    mutations,
    actions,
};

export default storeBillingSeller;