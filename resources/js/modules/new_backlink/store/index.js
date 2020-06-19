import BacklinkService from '@/modules/new_backlink/api';

const MESSAGE_FORMS = 'MESSAGE_FORMS';
const SET_ERROR = 'BUY_SET_ERROR';
const LIST_NEW_BACKLINK = 'LIST_NEW_BACKLINK';

const state = {
    messageForms: { action: '', message: '', errors: {} },
    listBacklink: { data:[] },
}

const mutations = {
    [MESSAGE_FORMS] (state, payload) {
        state.messageForms = payload;
    },

    [LIST_NEW_BACKLINK] (state , data) {
        state.listBacklink = data.listBacklink;
    },

    [SET_ERROR](state, error) {
        state.error = error;
    },
}

const actions = {
    async actionGetBacklinkList({ commit }, params){
        try{
            let response = await BacklinkService.getBacklink(params);
            commit(LIST_NEW_BACKLINK, { listBacklink: response.data });
        }catch( e ) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SET_ERROR, errors);
            } else {
                commit(SET_ERROR, e.response.data);
            }
        }
    },
}

const storeNewBacklink = {
    state,
    actions,
    mutations,
};
export default storeNewBacklink;