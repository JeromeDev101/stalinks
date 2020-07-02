import PublisherService from '@/modules/publisher/api';

const PUBLISHER_LIST = 'PUBLISHER_LIST';
const PUBLISHER_ERROR = 'PUBLISHER_ERROR';
const MESSAGE_FORMS = 'PUBLISHER_MESSAGE_FORMS';
const LIST_COUNTRY = 'LIST_COUNTRY';
const PUBLISHER_DOMAIN_SET_LIST_AHERFS = 'PUBLISHER_DOMAIN_SET_LIST_AHERFS';

const state = {
    listPublish: { data:[], total: 0 },
    messageForms: { action: '', message: '', errors: {} },
    listCountries: { data: [], total: 0 },
}

const mutations = {

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

    [LIST_COUNTRY](state, listCountries) {
        state.listCountries = listCountries;
    },

    [PUBLISHER_DOMAIN_SET_LIST_AHERFS](state, listAhrefsPublisher) {
        state.listAhrefsPublisher = listAhrefsPublisher;
    },
}

const actions = {
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

    async actionUploadCsv({ commit }, params) {
        try {
            let response = await PublisherService.uploadCsv(params);

            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'uploaded', message: 'Sucessfully Uploaded', errors: {} });
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

    async actionGetListCountries({ commit }, params) {
        try {
            let response = await PublisherService.getListCountries(params);
            commit(LIST_COUNTRY, response.data );
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