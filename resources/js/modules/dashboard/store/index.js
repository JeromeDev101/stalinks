import DashboardService from '@/modules/dashboard/api';

const MESSAGE_FORMS = 'DASHBOARD_MESSAGE_FORMS';
const DASHBOARD_ERROR = 'DASHBOARD_ERROR';
const DASHBOARD_DATA = 'DASHBOARD_DATA';

const state = {
    messageForms: { action: '', message: '', errors: {} },
    listData: {},
}

const mutations = {
    [MESSAGE_FORMS] (state, message) {
        state.messageForms = message;
    },

    [DASHBOARD_DATA](state, listData) {
        state.listData = listData;
    },

    [DASHBOARD_ERROR](state, error) {
        state.error = error;
    },
}

const actions = {

    async actionGetData({ commit }, params) {
        try {
            let response = await DashboardService.getData(params);
            commit(DASHBOARD_DATA, response.data );
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(DASHBOARD_ERROR, errors);
            } else {
                commit(DASHBOARD_ERROR, e.response.data);
            }
        }
    },

    clearMessageForm({commit}) {
        commit(MESSAGE_FORMS, { action: '', message: '', errors: {}});
    },
}

const storeDashboard = {
    state,
    actions,
    mutations,
};
export default storeDashboard;