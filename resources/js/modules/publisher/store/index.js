import PublisherService from '@/modules/publisher/api';

const PUBLISHER_LIST = 'PUBLISHER_LIST';
const PUBLISHER_ERROR = 'PUBLISHER_ERROR';

const state = {
    listPublish: { data:[], total: 0 }
}

const mutations = {

    [PUBLISHER_ERROR](state, error) {
        state.error = error;
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
    }
}

const storePublisher = {
    state,
    actions,
    mutations,
};
export default storePublisher;