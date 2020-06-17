import PublisherService from '@/modules/publisher/api';

const PUBLISHER_LIST = 'PUBLISHER_LIST';
const PUBLISHER_ERROR = 'PUBLISHER_ERROR';
const MESSAGE_FORMS = 'PUBLISHER_MESSAGE_FORMS';

const state = {
    listPublish: { data:[], total: 0 },
    messageForms: { action: '', message: '', errors: {} },
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
    }
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