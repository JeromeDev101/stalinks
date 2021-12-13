import MailServices from '@/modules/mails/api';

const MESSAGE_FORMS = 'MESSAGE_FORMS_MAILGUN';
// const LIST_COUNTRY = 'LIST_COUNTRY';
const SET_ERROR = 'SET_ERROR_MAILGUN';
const EMAIL_SIGNATURE_LIST = 'EMAIL_SIGNATURE_LIST';
const AUTO_REPLY_LIST = 'AUTO_REPLY_LIST';

// for auto reply
const MESSAGE_FORMS_AUTO_REPLY = 'MESSAGE_FORMS_AUTO_REPLY';
const AUTO_REPLY_STATE = 'AUTO_REPLY_STATE';

const state = {
    messageForms: { action: '', message: '', errors: {} },
    messageFormsAutoReply: { action: '', message: '', errors: {} },
    listEmailSignature: { data: [], total: 0, pagination: ''},
    listAutoReply: { data: [], total: 0, pagination: ''},
    autoReplyState: false,
    // listCountries: { data: [], total: 0 },
}

const mutations = {

    [MESSAGE_FORMS] (state, payload) {
        state.messageForms = payload;
    },

    [SET_ERROR](state, error) {
        state.error = error;
    },

    [EMAIL_SIGNATURE_LIST](state, listEmailSignature) {
        state.listEmailSignature = listEmailSignature;
    },

    [AUTO_REPLY_LIST](state, listAutoReply) {
        state.listAutoReply = listAutoReply;
    },


    // [LIST_COUNTRY](state, listCountries) {
    //     state.listCountries = listCountries;
    // },

    [MESSAGE_FORMS_AUTO_REPLY] (state, payload) {
        state.messageFormsAutoReply = payload;
    },

    [AUTO_REPLY_STATE] (state, payload) {
        state.autoReplyState = payload;
    },
}

const actions = {


    // async actionGetListCountries({ commit }, params) {
    //     try {
    //         let response = await BuyService.getListCountries(params);
    //         commit(LIST_COUNTRY, response.data );
    //     } catch (e) {
    //         let errors = e.response.data.errors;
    //         if (errors) {
    //             commit(SET_ERROR, errors);
    //         } else {
    //             commit(SET_ERROR, e.response.data);
    //         }
    //     }
    // },

    async actionSendMailgun({ commit }, params) {
        try{
            let response = await MailServices.sendEmail(params);

            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'success', message: 'successfully send', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        }catch( e ) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SET_ERROR, errors);
            } else {
                commit(SET_ERROR, e.response.data);
            }
        }
    },

    clearMessageform({commit}) {
        commit(MESSAGE_FORMS, { action: '', message: '', errors: {}});
    },

    clearMessageFormAutoReply({commit}) {
        commit(MESSAGE_FORMS_AUTO_REPLY, { action: '', message: '', errors: {}});
    },

    async actionGetEmailSignatureList({ commit }, params) {
        try {
            let response = await MailServices.getEmailSignatureList(params);
            commit(EMAIL_SIGNATURE_LIST, response.data)
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SET_ERROR, errors);
            } else {
                commit(SET_ERROR, e.response.data);
            }
        }
    },

    async actionAddEmailSignature({commit}, params) {
        try {
            let response = await MailServices.addEmailSignature(params);
            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'saved_signature', message: '#' + response.data.data.id + ' Saved !', errors: {} });
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

    async actionUpdateEmailSignature({commit}, params) {
        try {
            let response = await MailServices.updateEmailSignature(params);
            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'updated_signature', message: 'Saved !', errors: {} });
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

    async actionGetAutoReply({ commit }, params) {
        try {
            let response = await MailServices.getAutoReply(params);
            commit(AUTO_REPLY_LIST, response.data)
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SET_ERROR, errors);
            } else {
                commit(SET_ERROR, e.response.data);
            }
        }
    },

    async actionAddAutoReply({commit}, params) {
        try {
            let response = await MailServices.addAutoReply(params);
            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS_AUTO_REPLY, { action: 'saved_auto_reply', message: '#' + response.data.data.id + ' Saved !', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS_AUTO_REPLY, response.response.data);
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

    async actionUpdateAutoReply({commit}, params) {
        try {
            let response = await MailServices.updateAutoReply(params);
            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS_AUTO_REPLY, { action: 'updated_auto_reply', message: 'Saved !', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS_AUTO_REPLY, response.response.data);
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

    async getAutoReplyState({commit}) {
        let response = await MailServices.getAutoReplyState();

        if (response.status === 200) {
            commit(AUTO_REPLY_STATE, response.data.active);
        }
    },
}

const storeMailgun = {
    state,
    actions,
    mutations,
};
export default storeMailgun;
