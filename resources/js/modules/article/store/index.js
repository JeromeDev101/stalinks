import ArticleService from '@/modules/article/api';

const LIST_BACKLINKS = 'LIST_BACKLINKS';
const LIST_ARTICLES = 'LIST_ARTICLES';
const LIST_ARTICLES_ADMIN = 'LIST_ARTICLES_ADMIN';
const INFO_ARTICLE = 'INFO_ARTICLE';
const LIST_WRITER = 'LIST_WRITER';
const SET_ERROR = 'SELLER_BILLING_SET_ERROR';
const MESSAGE_FORMS = 'MESSAGE_FORMS';
const LIST_COUNTRY = 'LIST_COUNTRY';

const state = {
    infoArticles: { data: [] },
    listArticles: { data: [] },
    listArticlesAdmin: { data: [] },
    listBacklinks: { data: [] },
    listWriter: { data: [] },
    messageForms: { action: '', message: '', errors: {} },
    listCountries: { data: [], total: 0 },
}

const mutations = {
    [INFO_ARTICLE] (state, data) {
        state.infoArticles = data.infoArticles
    },

    [LIST_ARTICLES] (state, data) {
        state.listArticles = data.listArticles
    },

    [LIST_ARTICLES_ADMIN] (state, data) {
        state.listArticlesAdmin = data.listArticlesAdmin
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
    },

    [LIST_COUNTRY](state, listCountries) {
        state.listCountries = listCountries;
    },
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

    async actionGetListArticleAdmin({commit}, params){
        try {
            let response = await ArticleService.getListArticleAdmin(params)
            commit(LIST_ARTICLES_ADMIN, { listArticlesAdmin: response.data });
        }catch(e){
            let errors = e.response.data.errors;
            if (errors) {
                commit(SET_ERROR, errors);
            } else {
                commit(SET_ERROR, e.response.data);
            }
        }
    },

/*    DUPLICATE METHOD
    async actionGetListCountries({ commit }, params) {
        try {
            let response = await ArticleService.getListCountries(params);
            commit(LIST_COUNTRY, response.data );
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SET_ERROR, errors);
            } else {
                commit(SET_ERROR, e.response.data);
            }
        }
    },*/

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

    async actionSaveContent({commit}, params){
        try {
            let response = await ArticleService.updateContent(params)
            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'success', message: 'Sucessfully Updated', errors: {} });
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
