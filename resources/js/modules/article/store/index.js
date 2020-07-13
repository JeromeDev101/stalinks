import ArticleService from '@/modules/article/api';

const LIST_BACKLINKS = 'LIST_BACKLINKS';
const LIST_ARTICLES = 'LIST_ARTICLES';
const LIST_WRITER = 'LIST_WRITER';
const SET_ERROR = 'SELLER_BILLING_SET_ERROR';
const MESSAGE_FORMS = 'MESSAGE_FORMS';

const state = {
    listArticles: { data: [] },
    listBacklinks: { data: [] },
    listWriter: { data: [] },
    messageForms: { action: '', message: '', errors: {} },
}

const mutations = {
    [LIST_ARTICLES] (state, data) {
        state.listArticles = data.listArticles
    },

    [LIST_BACKLINKS] (state, data) {
        state.listBacklinks = data.listBacklinks
    }, 

    [LIST_WRITER] (state, data) {
        state.listWriter = data.listWriter
    },

    [MESSAGE_FORMS] (state, payload) {
        state.messageForms = payload;
    },

    [SET_ERROR](state, error) {
        state.error = error;
    }
}

const actions = {

    async actionGetListBacklinks({commit}, params){
        try {
            let response = await ArticleService.getListBacklinks(params)
            commit(LIST_BACKLINKS, { listBacklinks: response.data });
        }catch(e){
            let errors = e.response.data.errors;
            if (errors) {
                commit(SET_ERROR, errors);
            } else {
                commit(SET_ERROR, e.response.data);
            }
        }
    },

    async actionGetListArticle({commit}, params){
        try {
            let response = await ArticleService.getListArticle(params)
            commit(LIST_ARTICLES, { listArticles: response.data });
        }catch(e){
            let errors = e.response.data.errors;
            if (errors) {
                commit(SET_ERROR, errors);
            } else {
                commit(SET_ERROR, e.response.data);
            }
        }
    },

    async actionGetListWriter({commit}, params){
        try {
            let response = await ArticleService.getListWriter(params)
            commit(LIST_WRITER, { listWriter: response.data });
        }catch(e){
            let errors = e.response.data.errors;
            if (errors) {
                commit(SET_ERROR, errors);
            } else {
                commit(SET_ERROR, e.response.data);
            }
        }
    },

    async actionAddArticle({commit}, params){
        try {
            let response = await ArticleService.addArticle(params)
            console.log(response)
            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'success', message: 'Sucessfully Added', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        }catch(e){
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

const storeArticles = {
    state,
    mutations,
    actions,
};

export default storeArticles;