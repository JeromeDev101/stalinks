import EmailTemplateService from '@/modules/template_email/api';

const SYSTEM_SET_ERROR = 'SYSTEM_SET_ERROR';
const EMAIL_SET_LIST = 'EMAIL_SET_LIST'
const MESSAGE_FORMS = 'MESSAGE_FORMS';

const state = {
    totalCountry: 0,
    error: {},
    emailList: { data: [], total: 0, pagination: ''},
    messageForms: { action: '', message: '', errors: {} },
};

const mutations = {

    [SYSTEM_SET_ERROR](state, error) {
        state.error = error;
    },

    [EMAIL_SET_LIST](state, emailList) {
        state.emailList = emailList;
    },

    [MESSAGE_FORMS] (state, payload) {
        state.messageForms = payload;
    },
};

const actions = {
    async actionGetEmailTemplateList({ commit }, params) {
        try {
            let response = await EmailTemplateService.getEmailTemplateList(params);
            commit(EMAIL_SET_LIST, response.data)
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SYSTEM_SET_ERROR, errors);
            } else {
                commit(SYSTEM_SET_ERROR, e.response.data);
            }
        }
    },


    async actionAddTemplate({commit}, params) {
        try {
            let response = await EmailTemplateService.addTemplate(params);
            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'saved_template', message: '#' + response.data.data.id + ' Saved !', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SYSTEM_SET_ERROR, errors);
            } else {
                commit(SYSTEM_SET_ERROR, e.response.data);
            }
        }
    },

    async actionUpdateEmailTemplate({commit}, params) {
        try {
            let response = await EmailTemplateService.updateEmailTemplate(params);
            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'updated_tpl', message: 'Saved !', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SYSTEM_SET_ERROR, errors);
            } else {
                commit(SYSTEM_SET_ERROR, e.response.data);
            }
        }
    },

    async actionDeleteEmailTemplate({commit}, params) {
        try {
            let response = await EmailTemplateService.deleteEmailTemplate(params);
            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'deleted_tpl', message: 'Deleted !', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SYSTEM_SET_ERROR, errors);
            } else {
                commit(SYSTEM_SET_ERROR, e.response.data);
            }
        }
    },

    clearMessageFormEmailTemplate({commit}) {
        commit(MESSAGE_FORMS, { action: '', message: '', errors: {}});
    },
};
const emailTemplateSystem = {
    state,
    actions,
    mutations,
};
export default emailTemplateSystem;
