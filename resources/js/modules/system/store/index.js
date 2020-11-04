import SystemService from '@/modules/system/api';

const SYSTEM_SET_ERROR = 'SYSTEM_SET_ERROR';
const COUNTRY_SET_LIST = 'COUNTRY_SET_LIST';
const LANGUAGE_SET_LIST = 'LANGUAGE_SET_LIST';
const CONFIG_SET_LIST = 'CONFIG_SET_LIST';
const MESSAGE_FORMS = 'MESSAGE_FORMS';
const PAYMENT_LIST = 'PAYMENT_LIST';
const SUBSCRIPTION_LIST = 'SUBSCRIPTION_LIST';
const FORMULA = 'FORMULA';

const state = {
    totalCountry: 0,
    error: {},
    paymentList: { data:[] },
    countryList: { data: [], total: 0 },
    langaugeList: { data: [], total: 0 },
    configList: { data: [], total: 0 },
    messageForms: { action: '', message: '', errors: {} },
    Info: {},
    formula: {},
};

const mutations = {
    [SUBSCRIPTION_LIST](state, dataSet) {
        state.Info = dataSet;
    },

    [FORMULA](state, formula) {
        state.formula = formula;
    },

    [SYSTEM_SET_ERROR](state, error) {
        state.error = error;
    },

    [COUNTRY_SET_LIST](state, countryList) {
        state.countryList = countryList;
    },

    [LANGUAGE_SET_LIST](state, langaugeList) {
        state.langaugeList = langaugeList;
    },

    [PAYMENT_LIST](state, paymentList) {
        state.paymentList = paymentList;
    },

    [CONFIG_SET_LIST](state, configList) {
        state.configList = configList;
    },

    [MESSAGE_FORMS] (state, payload) {
        state.messageForms = payload;
    },
};

const actions = {
    async actionGetConfigList({ commit }, params) {
        try {
            let response = await SystemService.getConfigList(params);
            commit(CONFIG_SET_LIST, response.data)
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SYSTEM_SET_ERROR, errors);
            } else {
                commit(SYSTEM_SET_ERROR, e.response.data);
            }
        }
    },

    async actionGetFormula({ commit }, params) {
        try {
            let response = await SystemService.getFormula(params);
            commit(FORMULA, response.data)
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SYSTEM_SET_ERROR, errors);
            } else {
                commit(SYSTEM_SET_ERROR, e.response.data);
            }
        }
    },

    async actionGetCountryList({ commit }, params) {
        try {
            let response = await SystemService.getCountryList(params);
            commit(COUNTRY_SET_LIST, response.data);
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SYSTEM_SET_ERROR, errors);
            } else {
                commit(SYSTEM_SET_ERROR, e.response.data);
            }
        }
    },

    async actionGetLanguageList({ commit }, params) {
        try {
            let response = await SystemService.getLanguageList(params);
            commit(LANGUAGE_SET_LIST, response.data);
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SYSTEM_SET_ERROR, errors);
            } else {
                commit(SYSTEM_SET_ERROR, e.response.data);
            }
        }
    },

    async actionGetPaymentList({ commit }, params) {
        try {
            let response = await SystemService.getPaymentList(params);
            commit(PAYMENT_LIST, response.data);
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SYSTEM_SET_ERROR, errors);
            } else {
                commit(SYSTEM_SET_ERROR, e.response.data);
            }
        }
    },

    async actionAddPayment({commit}, params) {
        try {
            let response = await SystemService.addPaymentType(params);
            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'saved_payment', message: '#' + response.data.data.id + ' Saved !', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SYSTEM_SET_ERROR, errors);
            } else {
                commit(SYSTEM_SET_ERROR, e.response.data);
            }
        }
    },

    async actionAddCountry({commit}, params) {
        try {
            let response = await SystemService.addCountry(params);
            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'saved_country', message: '#' + response.data.data.id + ' Saved !', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SYSTEM_SET_ERROR, errors);
            } else {
                commit(SYSTEM_SET_ERROR, e.response.data);
            }
        }
    },

    async actionAddLanguage({commit}, params) {
        try {
            let response = await SystemService.addLanguage(params);
            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'saved_language', message: 'Successfully Saved', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SYSTEM_SET_ERROR, errors);
            } else {
                commit(SYSTEM_SET_ERROR, e.response.data);
            }
        }
    },

    async actionUpdateFormula({commit}, params) {
        try {
            let response = await SystemService.updateFormula(params);
            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'save_formula', message: '', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SYSTEM_SET_ERROR, errors);
            } else {
                commit(SYSTEM_SET_ERROR, e.response.data);
            }
        }
    },

    async actionUpdateLanguage({commit}, params) {
        try {
            let response = await SystemService.updateLanguage(params);
            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'update_language', message: 'Successfully Updated', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SYSTEM_SET_ERROR, errors);
            } else {
                commit(SYSTEM_SET_ERROR, e.response.data);
            }
        }
    },

    async actionUpdatePayment({commit}, params) {
        try {
            let response = await SystemService.updatePayment(params);
            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'updated_payment', message: 'Saved !', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SYSTEM_SET_ERROR, errors);
            } else {
                commit(SYSTEM_SET_ERROR, e.response.data);
            }
        }
    },

    async actionUpdateCountry({commit}, params) {
        try {
            let response = await SystemService.updateCountry(params);
            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'updated_country', message: 'Saved !', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SYSTEM_SET_ERROR, errors);
            } else {
                commit(SYSTEM_SET_ERROR, e.response.data);
            }
        }
    },

    async actionGetSubscriptionInfo({ commit }, params) {
        try {
            let response = await SystemService.getSubscriptionInfo(params);
            commit(SUBSCRIPTION_LIST, response.data );
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SYSTEM_SET_ERROR, errors);
            } else {
                commit(SYSTEM_SET_ERROR, e.response.data);
            }
        }
    },

    async actionUpdateConfig({commit}, params) {
        try {
            let response = await SystemService.updateConfig(params);
            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'updated_conf', message: 'Saved !', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SYSTEM_SET_ERROR, errors);
            } else {
                commit(SYSTEM_SET_ERROR, e.response.data);
            }
        }
    },

    clearMessageFormSystem({commit}) {
        commit(MESSAGE_FORMS, { action: '', message: '', errors: {}});
    },
};
const storeSystem = {
    state,
    actions,
    mutations,
};
export default storeSystem;
