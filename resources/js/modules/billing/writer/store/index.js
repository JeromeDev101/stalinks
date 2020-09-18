import WriterBillingService from '@/modules/billing/writer/api';

const MESSAGE_FORMS = 'MESSAGE_FORMS';
const SET_ERROR = 'WRITER_BILLING_SET_ERROR';
const LIST_ARTICLE = 'LIST_ARTICLE';
const LIST_PAYMENT = 'LIST_PAYMENT_ARTICLE';
const WRITER_INFO = 'WRITER_INFO';

const state = {
    messageForms: { action: '', message: '', errors: {} },
    listArticle: { data: [] },
    listPayment: { data: [] },
    writerInfo: {},
}

const mutations = {
    [MESSAGE_FORMS] (state, payload) {
        state.messageForms = payload;
    },

    [LIST_ARTICLE] (state, list) {
        state.listArticle = list.listArticle;
    },

    [SET_ERROR](state, error) {
        state.error = error;
    },

    [LIST_PAYMENT] (state, list) {
        state.listPayment = list.listPayment;
    },

    [WRITER_INFO] (state, data) {
        state.writerInfo = data.writerInfo;
    },
}

const actions = {

    async actionGetWriterInfo({ commit }, params){
        try {
            let response = await WriterBillingService.getWriterInfo(params)
            commit(WRITER_INFO , { writerInfo: response.data });
        }catch(e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SET_ERROR, errors);
            } else {
                commit(SET_ERROR, e.response.data);
            }
        }
    },

    async actionGetListArticle({ commit }, params) {
        try {
            let response = await WriterBillingService.getListArticle(params);
            commit(LIST_ARTICLE, { listArticle: response.data });
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SET_ERROR, errors);
            } else {
                commit(SET_ERROR, e.response.data);
            }
        }
    },

    async actionPayWriter({ commit }, params) {
        try {
            let response = await WriterBillingService.payBilling(params);

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

    async actionGetListPayentType({ commit }, params) {
        try {
            let response = await WriterBillingService.getPaymentType(params);
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

    clearMessageForm({commit}) {
        commit(MESSAGE_FORMS, { action: '', message: '', errors: {}});
    },
}

const storeBillingWriter = {
    state,
    mutations,
    actions,
};

export default storeBillingWriter;

