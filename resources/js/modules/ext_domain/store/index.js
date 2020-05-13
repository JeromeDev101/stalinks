import ExtDomainService from '@/modules/ext_domain/api';

const EXT_DOMAIN_SET_TOTAL = 'EXT_DOMAIN_SET_TOTAL';
const EXT_DOMAIN_SET_ERROR = 'EXT_DOMAIN_SET_ERROR';
const EXT_DOMAIN_SET_LIST_EXT = 'EXT_DOMAIN_SET_LIST_EXT';
const EXT_DOMAIN_SET_LIST_ALEXA = 'EXT_DOMAIN_SET_LIST_ALEXA';
const EXT_DOMAIN_SET_LIST_COUNTRY_INT = 'EXT_DOMAIN_SET_LIST_COUNTRY_INT';
const EXT_DOMAIN_SET_LIST_COUNTRY_EXT = 'EXT_DOMAIN_SET_LIST_COUNTRY_EXT';
const EXT_DOMAIN_SET_LIST_AHERFS = 'EXT_DOMAIN_SET_LIST_AHERFS';
const EXT_DOMAIN_SET_LIST_MAIL = 'EXT_DOMAIN_SET_LIST_MAIL';
const MESSAGE_FORMS = 'MESSAGE_FORMS';

//status external domain
const EXT_STATUS_NEW = "New";
const EXT_STATUS_CRAWL_FAILED = "CrawlFailed";
const EXT_STATUS_CONTACTS_NULL = "ContactNull";
const EXT_STATUS_GOT_CONTACTS = "GotContacts";
const EXT_STATUS_AHREAFED = "Ahrefed";
const EXT_STATUS_CONTACTED = "Contacted";
const EXT_STATUS_REFUSED = "Refused";
const EXT_STATUS_IN_TOUCHED = "InTouched";
const UNQUALIFIED = "Unqualified"

const state = {
    totalExtDomain: 0,
    error: {},
    listExt: { data: [], total: 0, pagination: '' },
    listAlexa: {},
    listCountriesInt: { data: [], total: 0 },
    listCountriesExt: { data: [], total: 0 },
    listAhrefs: [],
    listMailTemplate: { data: [], total: 0 },
    messageForms: { obj: {}, action: '', message: '', errors: {} },
    listStatusText : {
        0:  { text: EXT_STATUS_NEW, label: "default" },
        10: { text: EXT_STATUS_CRAWL_FAILED, label: "danger"},
        20: { text: EXT_STATUS_CONTACTS_NULL, label: "warning" },
        30: { text: EXT_STATUS_GOT_CONTACTS, label: "success" },
        40: { text: EXT_STATUS_AHREAFED, label: "primary" },
        50: { text: EXT_STATUS_CONTACTED, label: "success" },
        60: { text: EXT_STATUS_REFUSED, label: "warning" },
        70: { text: EXT_STATUS_IN_TOUCHED, label: "success" },
        90: { text: UNQUALIFIED, label: "success" }
    },
    tableExtShowOptions: {
        id: true,
        country: true,
        domain: true,
        email: true,
        facebook: true,
        phone: true,
        rank: true,
        status: true,
        total_spent: true,
        ahrefs_rank: true,
        no_backlinks: true,
        url_rating: true,
        domain_rating: true,
        ref_domains: true,
        organic_keywords: true,
        organic_traffic: true,
    },
};

const mutations = {

    [EXT_DOMAIN_SET_TOTAL](state, totalExtDomain) {
        state.totalExtDomain = totalExtDomain;
    },

    [EXT_DOMAIN_SET_LIST_MAIL](state, listMailTemplate) {
        state.listMailTemplate = listMailTemplate;
    },

    [EXT_DOMAIN_SET_LIST_AHERFS](state, listAhrefs) {
        state.listAhrefs = listAhrefs;
    },

    [EXT_DOMAIN_SET_LIST_COUNTRY_INT](state, listCountriesInt) {
        state.listCountriesInt = listCountriesInt;
    },

    [EXT_DOMAIN_SET_LIST_COUNTRY_EXT](state, listCountriesExt) {
        state.listCountriesExt = listCountriesExt;
    },

    [EXT_DOMAIN_SET_ERROR](state, error) {
        state.error = error;
    },

    [EXT_DOMAIN_SET_LIST_EXT](state, dataSet) {
        if (dataSet.isOnlyData) {
            state.listExt.data = dataSet.listExt;
            return;
        }

        state.listExt = dataSet.listExt;
    },

    [MESSAGE_FORMS] (state, payload) {
        state.messageForms = payload;
    },

    [EXT_DOMAIN_SET_LIST_ALEXA](state, response) {
        if (response.hasOwnProperty('extDomains')) {
            state.listAlexa = response.extDomains;
        } else {
            state.listAlexa = [];
        }

        console.log("listAlexa", state.listAlexa);
    }
};

const actions = {
    async filterExtDomain({ commit }, {vue, params}) {
        try {
            let response = await ExtDomainService.getTotalExt(params);
            commit(EXT_DOMAIN_SET_TOTAL, response.data);
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(EXT_DOMAIN_SET_ERROR, errors);
            } else {
                commit(EXT_DOMAIN_SET_ERROR, e.response.data);
            }
        }
    },

    async getListExt({ commit }, params) {
        try {
            let response = await ExtDomainService.getListExt(params);
            commit(EXT_DOMAIN_SET_LIST_EXT, { listExt: response.data, isOnlyData: false });
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(EXT_DOMAIN_SET_ERROR, errors);
            } else {
                commit(EXT_DOMAIN_SET_ERROR, e.response.data);
            }
        }
    },

    async getListAhrefs({ commit }, params) {
        try {
            let response = await ExtDomainService.getListAhrefs(params);
            commit(EXT_DOMAIN_SET_LIST_AHERFS, response.data);
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(EXT_DOMAIN_SET_ERROR, errors);
            } else {
                commit(EXT_DOMAIN_SET_ERROR, e.response.data);
            }
        }
    },

    async getListMails({ commit }, params) {
        try {
            let response = await ExtDomainService.getListMails(params);
            commit(EXT_DOMAIN_SET_LIST_MAIL, response.data);
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(EXT_DOMAIN_SET_ERROR, errors);
            } else {
                commit(EXT_DOMAIN_SET_ERROR, e.response.data);
            }
        }
    },

    async getListCountriesInt({ commit }) {
        try {
            let response = await ExtDomainService.getListCountriesInt();
            commit(EXT_DOMAIN_SET_LIST_COUNTRY_INT, response.data );
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(EXT_DOMAIN_SET_ERROR, errors);
            } else {
                commit(EXT_DOMAIN_SET_ERROR, e.response.data);
            }
        }
    },

    async getListCountriesExt({ commit }, params) {
        try {
            let response = await ExtDomainService.getListCountries(params);
            commit(EXT_DOMAIN_SET_LIST_COUNTRY_EXT, response.data );
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(EXT_DOMAIN_SET_ERROR, errors);
            } else {
                commit(EXT_DOMAIN_SET_ERROR, e.response.data);
            }
        }
    },

    async getAlexaList({ commit }, params) {
        try {
            let response = await ExtDomainService.getAlexaRankList(params);
            if (response.status === 200) {
                commit(EXT_DOMAIN_SET_LIST_ALEXA, response.data);
            } else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            alert(e);
            let errors = e.response.data.errors;
            if (errors) {
                commit(EXT_DOMAIN_SET_ERROR, errors);
            } else {
                commit(EXT_DOMAIN_SET_ERROR, e.response.data);
            }
        }
    },

    async crawlExtList({ commit }, params) {
        try {
            let response = await ExtDomainService.crawlExtList(params);
            if (response.status === 200) {
                commit(EXT_DOMAIN_SET_LIST_EXT, { listExt: response.data, isOnlyData: true });
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(EXT_DOMAIN_SET_ERROR, errors);
            } else {
                commit(EXT_DOMAIN_SET_ERROR, e.response.data);
            }
        }
    },

    async addExt({commit}, params) {
        try {
            let response = await ExtDomainService.addExt(params);
            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { obj: response.data.data, action: 'saved_ext', message: '#' + response.data.data.id + ' Saved !', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(EXT_DOMAIN_SET_ERROR, errors);
            } else {
                commit(EXT_DOMAIN_SET_ERROR, e.response.data);
            }
        }
    },

    async sendMail({commit}, params) {
        try {
            let response = await ExtDomainService.sendEmails(params);
            if (response.status === 200) {
                if (response.data.success === true) {
                    commit(MESSAGE_FORMS, { obj: response.data.data, action: 'send_mail', message: 'Sent !', errors: {} });
                } else {
                    commit(MESSAGE_FORMS, { obj: response.data.data, action: 'send_mail', message: 'Please check config work email !', errors: { message: 'Please check config work email !'} });
                }
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(EXT_DOMAIN_SET_ERROR, errors);
            } else {
                commit(EXT_DOMAIN_SET_ERROR, e.response.data);
            }
        }
    },

    async updateExt({commit}, params) {
        try {
            let response = await ExtDomainService.updateExt(params);
            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'updated_ext', message: 'Saved !', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(EXT_DOMAIN_SET_ERROR, errors);
            } else {
                commit(EXT_DOMAIN_SET_ERROR, e.response.data);
            }
        }
    },

    clearMessageForm({commit}) {
        commit(MESSAGE_FORMS, { action: '', message: '', errors: {}});
    },

};
const storeExtDomain = {
    state,
    actions,
    mutations,
};
export default storeExtDomain;
