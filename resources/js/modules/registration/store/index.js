import AccountService from '@/modules/registration/api';

const MESSAGE_FORMS = 'ACCOUNT_MESSAGE_FORMS';
const SET_ERROR = 'ACCOUNT_SET_ERROR';
const LIST_ACCOUNTS = 'LIST_ACCOUNTS';
const LIST_PAYMENT_TYPE = 'LIST_PAYMENT_TYPE';

const state = {
    messageForms: { action: '', message: '', errors: {} },
    listAccount: { data: [] },
    listPayment: { data: [] },
}

const mutations = {
    [MESSAGE_FORMS] (state, payload) {
        state.messageForms = payload;
    },

    [LIST_ACCOUNTS] (state, dataSet) {
        state.listAccount = dataSet.listAccount;
    },

    [LIST_PAYMENT_TYPE] (state, list) {
        state.listPayment = list.listPayment;
    },

    [SET_ERROR](state, error) {
        state.error = error;
    },
}

const actions= {
    async actionAddAccount({commit},  user) {
        try {
            let response = await AccountService.addAccount(user);

            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'saved_account', message: '#' + response.data.data.id + ' Saved !', errors: {} });
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

    async actionGetAccount({ commit }, params) {
        try {
            let response = await AccountService.getAccount(params);
            commit(LIST_ACCOUNTS, { listAccount: response.data });
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
            let response = await AccountService.getPaymentType(params);
            commit(LIST_PAYMENT_TYPE, { listPayment: response.data });
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SET_ERROR, errors);
            } else {
                commit(SET_ERROR, e.response.data);
            }
        }
    },

    async actionUpdateAccount({commit}, params) {
        try {
            let response = await AccountService.updateAccount(params);
            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'updated_account', message: 'Saved !', errors: {} });
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

    clearMessageFormAccount({commit}) {
        commit(MESSAGE_FORMS, { action: '', message: '', errors: {}});
    },
}

const storeAccount = {
    state,
    actions,
    mutations,
};

export default storeAccount;