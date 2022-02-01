
import Vue from 'vue';
import UserService from '@/modules/auth/api/index';
import ConfigAxios from '@/library/ConfigAxios';
import Helper from '@/library/Helper';

const AUTH_SET_USER = 'AUTH_SET_USER';
const AUTH_SET_ERROR = 'AUTH_SET_ERROR';
const AUTH_SET_TOKEN = 'AUTH_SET_TOKEN';
const AUTH_SET_LOADING = 'AUTH_SET_LOADING';
const AUTH_SET_COUNTRIES = 'AUTH_SET_COUNTRIES';
const AUTH_SET_COUNTRIES_EXT = 'AUTH_SET_COUNTRIES_EXT';
const CURRENT_USER = 'CURRENT_USER';
const USER_ADMIN = 'USER_ADMIN';
const USER_UNREAD_EMAILS = 'USER_UNREAD_EMAILS';
const USER_UNREAD_EMAIL_LIST = 'USER_UNREAD_EMAIL_LIST';
const USER_DRAFTS = 'USER_DRAFTS';

// for affiliate

const IS_AFFILIATE_CODE_SET = 'IS_AFFILIATE_CODE_SET';

const state = {
    user: null,
    token: null,
    error: {},
    loading: false,
    currentUser: { id: 0 },
    userUnreadEmails: 0,
    userDrafts: 0,
    userUnreadEmailList: {},
    isAffiliateCodeSet: false,
};

const getters = {
    isLoggedIn(state) {
        return !!state.token;
    },

    getCurrentUser(state) {
        return state.currentUser;
    }
};

const mutations = {
    [AUTH_SET_LOADING](state, isLoading) {
        state.loading = isLoading;
    },

    [AUTH_SET_TOKEN](state, token) {
        state.token = token;
    },

    [AUTH_SET_COUNTRIES](state, currentCountries) {
        state.currentUser.countries_accessable = currentCountries.data;
    },

    [AUTH_SET_COUNTRIES_EXT](state, currentCountries) {
        state.currentUser.countries_ext_accessable = currentCountries.data;
    },

    [AUTH_SET_USER](state, user) {
        state.user = user;
        Vue.prototype.$user = new User(user);
    },

    [AUTH_SET_ERROR](state, error) {
        state.error = error;
    },

    [CURRENT_USER](state, { currentUser }) {
        return state.currentUser = currentUser;
    },

    [USER_ADMIN](state, isAdmin) {
        return state.currentUser.isAdmin = isAdmin;
    },

    [USER_UNREAD_EMAILS](state, mails) {
        state.userUnreadEmails = mails;
    },

    [USER_UNREAD_EMAIL_LIST](state, mails) {
        state.userUnreadEmailList = mails;
    },

    [USER_DRAFTS](state, drafts) {
        state.userDrafts = drafts;
    },

    decrementUserUnreadEmailCount(state, count) {
        state.userUnreadEmails.count = state.userUnreadEmails.count - count;
    },

    [IS_AFFILIATE_CODE_SET] (state, payload) {
        state.isAffiliateCodeSet = payload;
    },
};

const actions = {
    async login({ commit }, params) {
        commit(AUTH_SET_LOADING, true);
        try {
            let loginResponse = await UserService.login(params.params);
            let token = loginResponse.data;
            ConfigAxios.setAuthorizationHeader(token.token_type, token.access_token);
            commit(AUTH_SET_TOKEN, token);
            let response = await UserService.getCurrentUserInfo();
            if (response.status === 200) {
                commit(CURRENT_USER, { currentUser: response.data });

                // redirect affiliates to incomes
                // if (response.data.role.id === 11) {
                //     window.location.href = '/overall-incomes';
                // } else {
                //     window.location.href = '/';
                // }

                window.location.href = '/';
            }


        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(AUTH_SET_ERROR, errors);
            } else {
                commit(AUTH_SET_ERROR, e.response.data);
            }
        }

        commit(AUTH_SET_LOADING, false);
    },

    async logout({ commit }) {
        commit(AUTH_SET_LOADING, true);
        await UserService.logout();
        commit(AUTH_SET_TOKEN, null);
        commit(AUTH_SET_LOADING, false);
    },

    async actionCheckAdminCurrentUser({ commit }, { vue }) {
        let response = await UserService.checkAdmin();

        if (response.status === 200) {
            return commit(USER_ADMIN, response.data );
        }

        let errorResponse = response.response;
        return Helper.handleError(vue, errorResponse.status);
    },

    async actionUpdateCurrentUserCountries({commit}, { vue, userId }) {
        if (userId === 0) return;
        let response = await UserService.getCountryList({ params: { user_id: userId } });

        if (response.status === 200) {
            return commit(AUTH_SET_COUNTRIES, response.data);
        }

        let errorResponse = response.response;
        return Helper.handleError(vue, errorResponse.status);
    },

    async actionUpdateCurrentUserCountriesExt({commit}, { vue, userId }) {
        if (userId === 0) return;
        let response = await UserService.getCountryList({ params: { user_id: userId, for_ext: true } });

        if (response.status === 200) {
            return commit(AUTH_SET_COUNTRIES_EXT, response.data);
        }

        let errorResponse = response.response;
        return Helper.handleError(vue, errorResponse.status);
    },

    async getUserUnreadEmails({commit}, email) {
        let response = await UserService.getUserUnreadEmails(email);

        if (response.status === 200) {
            commit(USER_UNREAD_EMAILS, response.data);
        }
    },

    async getUserUnreadEmailList({commit}, email) {
        let response = await UserService.getUserUnreadEmailList(email);

        if (response.status === 200) {
            commit(USER_UNREAD_EMAIL_LIST, response.data);
        }
    },


    async getUserDrafts({commit}) {
        let response = await UserService.getUserDrafts();

        if (response.status === 200) {
            commit(USER_DRAFTS, response.data);
        }
    },

    updateUnreadEmailsCount({commit}, count) {
        commit('decrementUserUnreadEmailCount', count);
    },

    async getAffiliateCodeSet({commit}) {
        let response = await UserService.getAffiliateCodeSet();

        if (response.status === 200) {
            commit(IS_AFFILIATE_CODE_SET, response.data.set);
        }
    },
};
const storeAuth = {
    state,
    getters,
    mutations,
    actions,
};


export default storeAuth;
