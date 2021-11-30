import PublisherService from '@/modules/publisher/api';

const PUBLISHER_SUMMARY_LIST = 'PUBLISHER_SUMMARY_LIST';
const PUBLISHER_LIST = 'PUBLISHER_LIST';
const PUBLISHER_ERROR = 'PUBLISHER_ERROR';
const MESSAGE_FORMS = 'PUBLISHER_MESSAGE_FORMS';
const LIST_COUNTRY_ALL = 'LIST_COUNTRY_ALL';
const LIST_COUNTRY_CONTINENT = 'LIST_COUNTRY_CONTINENT';
const LIST_CONTINENT = 'LIST_CONTINENT';
const LIST_LANGUAGES = 'LIST_LANGUAGES';
const LIST_DOMAIN_ZONES = 'LIST_DOMAIN_ZONES';
const LIST_SELLER = 'LIST_SELLER';
const LIST_SELLER_INCHARGE = 'LIST_SELLER_INCHARGE';
const PUBLISHER_DOMAIN_SET_LIST_AHERFS = 'PUBLISHER_DOMAIN_SET_LIST_AHERFS';
const LIST_BEST_PRICE_LOG = 'LIST_BEST_PRICE_LOG';

const state = {
    totalPublish: 0,
    listPublish: { data:[], total: 0 },
    summaryPublish:{ total: 0, data:[] },
    messageForms: { action: '', message: '', errors: {} },
    listCountryAll: { data: [], total: 0 },
    listCountryContinent: { data: [], total: 0 },
    listContinent: { data: [], total: 0 },
    listLanguages: { data: [], total: 0 },
    listDomainZones: { data: [], total: 0 },
    listSeller: { data:[] },
    listSellerIncharge: { data:[] },
    tblPublisherOpt: {
        continent: true,
        country: true,
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
        prices: true,
        price_basis: true,
        inc_article: true,
        kw_anchor: true,
        ur: true,
        dr: true,
        backlinks: true,
        ref_domain: true,
        org_keywords: true,
        org_traffic: true,
        qc_validation: true,
        code_price: true,
    },
    bestPriceGeneratorOn: false,
    bestPriceLogs: {}
}

const getters = {
    getCountriesByContinentId: (state) => (continent_id) => {

        let countries = state.listCountryAll.data

        return continent_id
            ? countries.filter(country => country.continent_id === continent_id)
            : state.listCountryAll.data;
    }
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

    [LIST_COUNTRY_CONTINENT](state, listCountryContinent) {
        state.listCountryContinent = listCountryContinent;
    },

    [LIST_CONTINENT](state, listContinent) {
        const data = {
            id : 0,
            code : 'N/A',
            name: 'N/A',
            created_at : null,
            updated_at: null
        };

        listContinent.data.push(data);

        state.listContinent = listContinent;
    },

    [LIST_LANGUAGES](state, listLanguages) {
        state.listLanguages = listLanguages;
    },

    [LIST_SELLER](state, dataSet) {
        state.listSeller = dataSet.listSeller;
    },

    [LIST_SELLER_INCHARGE](state, dataSet) {
        state.listSellerIncharge = dataSet.listSellerIncharge;
    },

    [PUBLISHER_DOMAIN_SET_LIST_AHERFS](state, listAhrefsPublisher) {
        state.listAhrefsPublisher = listAhrefsPublisher;
    },

    [LIST_BEST_PRICE_LOG](state, listLog) {
        state.bestPriceLogs = listLog;
        state.bestPriceGeneratorOn = listLog[0].status === 'start';
    },

    [LIST_DOMAIN_ZONES](state, listDomainZones) {
        state.listDomainZones = listDomainZones;
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

            commit(MESSAGE_FORMS, { action: 'get_list', message: 'Get List !', errors: {} });
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
                commit(MESSAGE_FORMS, { action: 'uploaded', message: '', errors: response.data.data });
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
                commit(MESSAGE_FORMS, { action: 'saved', message: 'Sucessfully Saved', errors: response.data.data });
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

    async actionQcValidationUpdate({ commit }, params) {
        try {
            let response = await PublisherService.qcValidationUpdate(params);

            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'saved', message: 'Sucessfully Saved', errors: response.data.data });
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

    async actionGetListSellerIncharge({ commit }, params) {
        try {
            let response = await PublisherService.getListSellerIncharge(params);
            commit(LIST_SELLER_INCHARGE, { listSellerIncharge: response.data } );
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
            commit(LIST_COUNTRY_CONTINENT, response.data );
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(PUBLISHER_ERROR, errors);
            } else {
                commit(PUBLISHER_ERROR, e.response.data);
            }
        }
    },

    async actionGetListContinents({ commit }, params) {
        try {
            let response = await PublisherService.getListContinents(params);
            commit(LIST_CONTINENT, response.data );
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

    async actionGetListDomainZones({ commit }, params) {
        try {
            let response = await PublisherService.getListDomainZones(params);
            commit(LIST_DOMAIN_ZONES, response.data );
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

    actionGetCountriesByContinentId({commit, getters}, params){
        let countries = getters.getCountriesByContinentId(params.continent_id);

        commit(LIST_COUNTRY_CONTINENT, {data: countries, count: countries.length});
    },

    clearMessageform({commit}) {
        commit(MESSAGE_FORMS, { action: '', message: '', errors: {}});
    },

    async generateBestPrices({commit}) {
        await PublisherService.generateBestPrices();
    },

    async getGeneratorLogs({commit}) {
        try {
            let response = await PublisherService.getGeneratorLogsApi();
            commit(LIST_BEST_PRICE_LOG, response.data);
        } catch (e) {
        }
    }
}

const storePublisher = {
    state,
    getters,
    actions,
    mutations,
};
export default storePublisher;
