import HostingService from '@/modules/hosting/api';

const HOSTING_ERROR = 'HOSTING_ERROR'
const LIST_HOSTING = 'LIST_HOSTING'
const HOSTING_DETAIL = 'HOSTING_DETAIL'
const MESSAGE_FORMS = 'MESSAGE_FORMS';


const state = {
    error: {},
    listHosting: {},
    hostingDetail: {},
    messageForms: { action: '', message: '', errors: {} },
};

const mutations = {

    [HOSTING_ERROR](state, error) {
        state.error = error;
    },

    [LIST_HOSTING](state, listHosting) {
        state.listHosting = listHosting
    },

    [HOSTING_DETAIL](state, hostingDetail) {
        state.hostingDetail = hostingDetail
    },

    [MESSAGE_FORMS] (state, payload) {
        state.messageForms = payload;
    },
};

const actions = {
    async getHostingList({ commit }, {params}) {
        try {
            let response = await HostingService.actionGetHosting(params);
            commit(LIST_HOSTING, response.data)
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(HOSTING_ERROR, errors);
            } else {
                commit(HOSTING_ERROR, e.response.data);
            }
        }
    },

    async getHostingDetail({ commit }, {id}) {
        try {
            let response = await HostingService.getHostingDetail(id);
            commit(HOSTING_DETAIL, response.data)   
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(HOSTING_ERROR, errors);
            } else {
                commit(HOSTING_ERROR, e.response.data);
            }
        }
    },

    async actionAddHosting({commit},  hosting) {
        try {
            let response = await HostingService.addHosting(hosting);

            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'saved_hosting', message: '#' + response.data.data.id + ' Saved !', errors: {} });
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

    async actionEditHosting({commit},  hosting) {
        try {
            let response = await HostingService.editHosting(hosting);

            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'saved_hosting', message: '# Saved !', errors: {} });
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

    clearMessageForm({commit}) {
        commit(MESSAGE_FORMS, { action: '', message: '', errors: {}});
    },
};
const storeHosting = {
    state,
    actions,
    mutations,
};
export default storeHosting;