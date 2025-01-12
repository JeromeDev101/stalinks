import AccountService from '@/modules/registration/api';

const MESSAGE_FORMS = 'ACCOUNT_MESSAGE_FORMS';
const SET_ERROR = 'ACCOUNT_SET_ERROR';
const LIST_ACCOUNTS = 'LIST_ACCOUNTS';
const LIST_PAYMENT_TYPE = 'LIST_PAYMENT_TYPE';
const LIST_IN_CHARGE = 'LIST_IN_CHARGE';
const LIST_AFFILIATE = 'LIST_AFFILIATE';

const state = {
    messageForms: { action: '', message: '', errors: {} },
    listAccount: { data: [] },
    listPayment: { data: [] },
    listIncharge: { data: [] },
    listAffiliate: { data: [] },
    tblAccountsOpt: {
        date_registered: true,
        in_charge: true,
        affiliate: true,
        user_id: true,
        username: true,
        email: true,
        payment_account_email: true,
        name: true,
        company_type: true,
        company_name: false,
        company_url: false,
        type: true,
        sub_account: true,
        under_of_main_buyer: true,
        account_validation: true,
        is_show_price_basis: true,
        credit_authorization: true,
        commission: true,
        status: true,
        country: false,
        language: false,
    },
}

const mutations = {
    [MESSAGE_FORMS] (state, payload) {
        state.messageForms = payload;
    },

    [LIST_ACCOUNTS] (state, dataSet) {
        state.listAccount = dataSet.listAccount;
    },

    [LIST_IN_CHARGE] (state, dataSet) {
        state.listIncharge = dataSet.listIncharge;
    },

    [LIST_AFFILIATE] (state, dataSet) {
        state.listAffiliate = dataSet.listAffiliate;
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
                commit(MESSAGE_FORMS, { action: 'saved_account', message: 'Saved !', errors: {} });
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

    async actionGetSeller({ commit }) {
        try {
            let response = await AccountService.getSellers();
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

    async actionGetTeamInCharge({ commit }) {
        try {
            let response = await AccountService.getTeamInCharge();
            commit(LIST_IN_CHARGE, { listIncharge: response.data });
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SET_ERROR, errors);
            } else {
                commit(SET_ERROR, e.response.data);
            }
        }
    },

    async actionGetAffiliateList({ commit }) {
        try {
            let response = await AccountService.getAffiliateList();
            commit(LIST_AFFILIATE, { listAffiliate: response.data });
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
                response.response.data.action = 'update_failed';
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

    async actionVerifyAccount({commit}, params) {
        try {
            let response = await AccountService.verifyAccount(params);
            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'verified_account', message: 'Saved !', errors: {} });
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

    async actionRegister({commit}, params) {
        try {
            let response = await AccountService.addRegister(params);
            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'registration_success', message: 'Saved !', errors: {} });
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

    async actionCheckVerificationCode({commit}, params) {
        try {
            let response = await AccountService.checkVerificationCode(params);

            if (response.status === 200 ) {
                commit(MESSAGE_FORMS, { action: response.data.success , message: '', errors: {} });
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

    async actionSetPassword({commit}, params) {
        try {
            let response = await AccountService.updatePassword(params);

            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'success', message: 'Saved', errors: {} });
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
