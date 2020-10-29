import PublisherService from '@/modules/publisher/api';

const PUBLISHER_SUMMARY_LIST = 'PUBLISHER_SUMMARY_LIST';
const PUBLISHER_LIST = 'PUBLISHER_LIST';
const PUBLISHER_ERROR = 'PUBLISHER_ERROR';
const MESSAGE_FORMS = 'PUBLISHER_MESSAGE_FORMS';
const LIST_COUNTRY_ALL = 'LIST_COUNTRY_ALL';
const LIST_LANGUAGES = 'LIST_LANGUAGES';
const LIST_SELLER = 'LIST_SELLER';
const PUBLISHER_DOMAIN_SET_LIST_AHERFS = 'PUBLISHER_DOMAIN_SET_LIST_AHERFS';

const state = {
    totalPublish: 0,
    listPublish: { data:[], total: 0 },
    summaryPublish:{ total: 0, data:[] },
    messageForms: { action: '', message: '', errors: {} },
    listCountryAll: { data: [], total: 0 },
    listLanguages: { data: [], total: 0 },
    listSeller: { data:[] },
    tblPublisherOpt: {
        created: true,
        uploaded: true,
        language: true,
        topic: true,
        casino_sites: true,
        in_charge: true,
        seller: true,
        valid: true,
        url: true,
        price: true,
        price_basis: true,
        inc_article: true,
        kw_anchor: true,
        ur: true,
        dr: true,
        backlinks: true,
        ref_domain: true,
        org_keywords: true,
        org_traffic: true,
    },
}

const mutations = {

    [PUBLISHER_SUMMARY_LIST](state, totalExtDomain) {
        state.totalExtDomain = totalExtDomain;
    },
    [PUBLISHER_SUMMARY_LIST](state, dataSet) {
        if (dataSet.isOnlyData) {
            state.summaryPublish.data = dataSet.summaryPublish;
            return;
        }

        state.summaryPublish = dataSet.summaryPublish;
    },

    [PUBLISHER_ERROR](state, error) {
        state.error = error;
    },

    [MESSAGE_FORMS] (state, message) {
        state.messageForms = message;
    },

    [PUBLISHER_LIST](state, dataSet) {
        if (dataSet.isOnlyData) {
            state.listPublish.data = dataSet.listPublish;
            return;
        }

        state.listPublish = dataSet.listPublish;
    },

    [LIST_COUNTRY_ALL](state, listCountryAll) {
        state.listCountryAll = listCountryAll;
    },

    [LIST_LANGUAGES](state, listLanguages) {
        state.listLanguages = listLanguages;
    },

    [LIST_SELLER](state, dataSet) {
        state.listSeller = dataSet.listSeller;
    },

    [PUBLISHER_DOMAIN_SET_LIST_AHERFS](state, listAhrefsPublisher) {
        state.listAhrefsPublisher = listAhrefsPublisher;
    },
}

const actions = {
    async getSummaryPublisher({ commit }) {
        try {
            let response = await PublisherService.getPublisherSummary();
            commit(PUBLISHER_SUMMARY_LIST, { summaryPublish: response.data });
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(PUBLISHER_ERROR, errors);
            } else {
                commit(PUBLISHER_ERROR, e.response.data);
            }
        }
    },

    async getListPublisher({ commit }, params) {
        try {
            let response = await PublisherService.getList(params);
            commit(PUBLISHER_LIST, { listPublish: response.data });
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(PUBLISHER_ERROR, errors);
            } else {
                commit(PUBLISHER_ERROR, e.response.data);
            }
        }
    },

    async actionDeletePublisher({ commit }, params) {
        try {
            let response = await PublisherService.deletePublisher(params);

            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'uploaded', message: 'Sucessfully Deleted!', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        }catch(e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(PUBLISHER_ERROR, errors);
            } else {
                commit(PUBLISHER_ERROR, e.response.data);
            }
        }
    },

    async actionAddUrl({ commit }, params) {
        try {
            let response = await PublisherService.addUrl(params);

            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'saved', message: 'Sucessfully Saved', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(PUBLISHER_ERROR, errors);
            } else {
                commit(PUBLISHER_ERROR, e.response.data);
            }
        }
    },

    async actionUploadCsv({ commit }, params) {
        try {
            let response = await PublisherService.uploadCsv(params);

            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'uploaded', message: '', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(PUBLISHER_ERROR, errors);
            } else {
                commit(PUBLISHER_ERROR, e.response.data);
            }
        }
    },

    async actionValidData({ commit }, params) {
        try {
            let response = await PublisherService.validData(params);

            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'saved', message: 'Sucessfully Saved', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(PUBLISHER_ERROR, errors);
            } else {
                commit(PUBLISHER_ERROR, e.response.data);
            }
        }
    },

    async actionGetListSeller({ commit }, params) {
        try {
            let response = await PublisherService.getListSeller(params);
            commit(LIST_SELLER, { listSeller: response.data } );
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(PUBLISHER_ERROR, errors);
            } else {
                commit(PUBLISHER_ERROR, e.response.data);
            }
        }
    },

    async actionGetListCountries({ commit }, params) {
        try {
            let response = await PublisherService.getListCountries(params);
            commit(LIST_COUNTRY_ALL, response.data );
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(PUBLISHER_ERROR, errors);
            } else {
                commit(PUBLISHER_ERROR, e.response.data);
            }
        }
    },

    async actionGetListLanguages({ commit }, params) {
        try {
            let response = await PublisherService.getListLanguages(params);
            commit(LIST_LANGUAGES, response.data );
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(PUBLISHER_ERROR, errors);
            } else {
                commit(PUBLISHER_ERROR, e.response.data);
            }
        }
    },

    async actionUpdatePublisher({ commit }, params) {
        try {
            let response = await PublisherService.updatePublisher(params);

            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'updated_publisher', message: 'Saved !', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(PUBLISHER_ERROR, errors);
            } else {
                commit(PUBLISHER_ERROR, e.response.data);
            }
        }
    },

    async getListAhrefsPublisher({ commit }, params) {
        try {
            let response = await PublisherService.getListAhrefsPublisher(params);
            commit(PUBLISHER_DOMAIN_SET_LIST_AHERFS, response.data);
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(EXT_DOMAIN_SET_ERROR, errors);
            } else {
                commit(EXT_DOMAIN_SET_ERROR, e.response.data);
            }
        }
    },

    clearMessageform({commit}) {
        commit(MESSAGE_FORMS, { action: '', message: '', errors: {}});
    },
}

const storePublisher = {
    state,
    actions,
    mutations,
};
export default storePublisher;