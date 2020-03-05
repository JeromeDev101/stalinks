import DomainService from '@/modules/domain/api';

const DOMAIN_ERROR = 'DOMAIN_ERROR'
const LIST_DOMAIN = 'LIST_DOMAIN'
const DOMAIN_DETAIL = 'DOMAIN_DETAIL'
const MESSAGE_FORMS = 'MESSAGE_FORMS';


const state = {
    error: {},
    listDomain: {},
    domainDetail: {},
    messageForms: { action: '', message: '', errors: {} },
};

const mutations = {

    [DOMAIN_ERROR](state, error) {
        state.error = error;
    },

    [LIST_DOMAIN](state, listDomain) {
        state.listDomain = listDomain
    },

    [DOMAIN_DETAIL](state, domainDetail) {
        state.domainDetail = domainDetail
    },

    [MESSAGE_FORMS] (state, payload) {
        state.messageForms = payload;
    },
};

const actions = {
    async getDomainList({ commit }, {params}) {
        try {
            let response = await DomainService.actionGetDomain(params);
            commit(LIST_DOMAIN, response.data)
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(DOMAIN_ERROR, errors);
            } else {
                commit(DOMAIN_ERROR, e.response.data);
            }
        }
    },

    async getDomainDetail({ commit }, {id}) {
        try {
            let response = await DomainService.getDomainDetail(id);
            commit(DOMAIN_DETAIL, response.data)   
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(DOMAIN_ERROR, errors);
            } else {
                commit(DOMAIN_ERROR, e.response.data);
            }
        }
    },

    async actionAddDomain({commit},  domain) {
        try {
            let response = await DomainService.addDomain(domain);

            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'saved_domain', message: '#' + response.data.data.id + ' Saved !', errors: {} });
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

    async actionEditDomain({commit},  domain) {
        try {
            let response = await DomainService.editDomain(domain);

            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'saved_domain', message: '# Saved !', errors: {} });
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
const storeDomain = {
    state,
    actions,
    mutations,
};
export default storeDomain;