import IntDomainService from '@/modules/int_domain/api';
import HostingService from '@/modules/hosting/api';
import DomainService from '@/modules/domain/api';

const INT_DOMAIN_SET_TOTAL = 'INT_DOMAIN_SET_TOTAL';
const INT_DOMAIN_SET_ERROR = 'INT_DOMAIN_SET_ERROR';
const INT_DOMAIN_SET_LIST_INT = 'INT_DOMAIN_SET_LIST_INT'
const MESSAGE_FORMS = 'MESSAGE_FORMS';
const INT_DOMAIN_BY_HOSTING = 'INT_DOMAIN_BY_HOSTING'
const INT_DOMAIN_SET_HOSTING_LIST = 'INT_DOMAIN_SET_HOSTING_LIST'
const INT_DOMAIN_SET_DOMAIN_LIST = 'INT_DOMAIN_SET_DOMAIN_LIST'
const INT_DOMAIN_BY_DOMAIN = 'INT_DOMAIN_BY_DOMAIN'

const state = {
    totalIntDomain: 0,
    error: {},
    listInt: { data: [], total: 0, pagination: ''},
    hostingList: { data: [], total: 0 },
    domainList: { data: [], total: 0 },
    messageForms: { action: '', message: '', errors: {} },
    intByHosting: { data: [], total: 0},
    intByDomain: { data: [], total: 0}
};

const mutations = {

    [INT_DOMAIN_SET_TOTAL](state, totalIntDomain) {
        state.totalIntDomain = totalIntDomain;
    },

    [INT_DOMAIN_SET_HOSTING_LIST](state, hostingList) {
        state.hostingList = hostingList;
    },

    [INT_DOMAIN_SET_DOMAIN_LIST](state, domainList) {
        state.domainList = domainList;
    },

    [INT_DOMAIN_SET_ERROR](state, error) {
        state.error = error;
    },

    [INT_DOMAIN_SET_LIST_INT](state, dataSet) {
        if (dataSet.isOnlyData) {
            state.listInt.data = dataSet.list;
            return;
        }

        state.listInt = dataSet.list;
    },

    [MESSAGE_FORMS] (state, payload) {
        state.messageForms = payload;
    },

    [INT_DOMAIN_BY_HOSTING](state, intByHosting) {
        state.intByHosting = intByHosting;
    },

    [INT_DOMAIN_BY_DOMAIN](state, intByDomain) {
        state.intByDomain = intByDomain;
    },
};

const actions = {
    async filterIntDomain({ commit }, {vue, params}) {
        try {
            let response = await IntDomainService.getTotalInt(params);
            commit(INT_DOMAIN_SET_TOTAL, response.data)
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(INT_DOMAIN_SET_ERROR, errors);
            } else {
                commit(INT_DOMAIN_SET_ERROR, e.response.data);
            }
        }
    },

    async getListInt({ commit }, params) {
        try {
            let response = await IntDomainService.actionGetIntDomain(params);
            commit(INT_DOMAIN_SET_LIST_INT, { list: response.data, isOnlyData: false });
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(INT_DOMAIN_SET_ERROR, errors);
            } else {
                commit(INT_DOMAIN_SET_ERROR, e.response.data);
            }
        }
    },

    async getFullDomainProviderList({ commit }) {
        try {
            let response = await DomainService.actionGetFullDomain();
            commit(INT_DOMAIN_SET_DOMAIN_LIST, response.data );
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(INT_DOMAIN_SET_ERROR, errors);
            } else {
                commit(INT_DOMAIN_SET_ERROR, e.response.data);
            }
        }
    },

    async getFullHostingList({ commit }) {
        try {
            let response = await HostingService.actionGetFullHosting();
            commit(INT_DOMAIN_SET_HOSTING_LIST, response.data);
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(INT_DOMAIN_SET_ERROR, errors);
            } else {
                commit(INT_DOMAIN_SET_ERROR, e.response.data);
            }
        }
    },

    async getIntByHosting({commit}, {vue, id, page}) {
        try {
            let response = await IntDomainService.getIntByHosting(id, page);
            commit(INT_DOMAIN_BY_HOSTING, response.data);
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(INT_DOMAIN_SET_ERROR, errors);
            } else {
                commit(INT_DOMAIN_SET_ERROR, e.response.data);
            }
        }
    },

    clearMessageFormInt({commit}) {
        commit(MESSAGE_FORMS, { action: '', message: '', errors: {}});
    },

    async addInt({commit}, params) {
        try {
            let response = await IntDomainService.addInt(params);
            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'saved_int', message: '#' + response.data.data.id + ' Saved !', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(INT_DOMAIN_SET_ERROR, errors);
            } else {
                commit(INT_DOMAIN_SET_ERROR, e.response.data);
            }
        }
    },
    
    async getintByDomain({commit}, {vue, id, page}) {
        try {
            let response = await IntDomainService.getintByDomain(id, page);
            commit(INT_DOMAIN_BY_DOMAIN, response.data);
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(INT_DOMAIN_SET_ERROR, errors);
            } else {
                commit(INT_DOMAIN_SET_ERROR, e.response.data);
            }
        }
    },

    async updateInt({commit}, params) {
        try {
            let response = await IntDomainService.updateInt(params);
            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'updated_int', message: 'Saved !', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(INT_DOMAIN_SET_ERROR, errors);
            } else {
                commit(INT_DOMAIN_SET_ERROR, e.response.data);
            }
        }
    },

};
const storeIntDomain = {
    state,
    actions,
    mutations,
};
export default storeIntDomain;
