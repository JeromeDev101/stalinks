import MailServices from '@/modules/mails/api';

const MESSAGE_FORMS = 'MESSAGE_FORMS_MAILGUN';
// const LIST_COUNTRY = 'LIST_COUNTRY';
const SET_ERROR = 'SET_ERROR_MAILGUN';

const state = {
    messageForms: { action: '', message: '', errors: {} },
    // listCountries: { data: [], total: 0 },
}

const mutations = {
    [MESSAGE_FORMS] (state, payload) {
        state.messageForms = payload;
    },

    [SET_ERROR](state, error) {
        state.error = error;
    },

    // [LIST_COUNTRY](state, listCountries) {
    //     state.listCountries = listCountries;
    // },
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
}

const storeMailgun = {
    state,
    actions,
    mutations,
};
export default storeMailgun;