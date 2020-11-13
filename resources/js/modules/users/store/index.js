
import UserService from '@/modules/users/api';
import Helper from '@/library/Helper';

const LIST_USER = 'LIST_USER';
const USER_INFOR = 'USER_INFOR';
const SET_COUNTRY_LIST = 'USER_SET_COUNTRY_LIST';
const SET_COUNTRY_UPDATE_LIST = 'SET_COUNTRY_UPDATE_LIST';
const SET_INT_UPDATE_LIST = 'SET_INT_UPDATE_LIST';
const SET_ROLE_LIST = 'USER_SET_ROLE_LIST';
const SET_USER_TYPE_LIST = 'USER_SET_USER_TYPE_LIST';
const SET_INT_SELECT_LIST = 'SET_INT_SELECT_LIST';
const MESSAGE_FORMS = 'USER_MESSAGE_FORMS';
const SET_ERROR = 'USER_SET_ERROR';
const LIST_PAYMENT = 'LIST_PAYMENT';

const state = {
    listUser: {},
    listPayment: {},
    errors: null,
    userInfor: {},
    countryList: [],
    listCountryUpdate: { data: [], total: 0 },
    listIntUpdate: { data: [], total: 0 },
    listIntSelect: { data: [], total: 0 },
    roleList: [],
    userTypeList: [],
    messageForms: { action: '', message: '', errors: {} },
};

const mutations = {
    [LIST_USER](state, { listUser }) {
        return state.listUser = listUser;
    },

    [LIST_PAYMENT](state, { listPayment }) {
        return state.listPayment = listPayment;
    },

    [SET_ERROR](state, error) {
        state.error = error;
    },

    [SET_COUNTRY_LIST](state, countryList) {
        return state.countryList = countryList;
    },

    [SET_COUNTRY_UPDATE_LIST](state, countryList) {
        return state.listCountryUpdate = countryList;
    },

    [SET_INT_UPDATE_LIST](state, intList) {
        return state.listIntUpdate = intList;
    },

    [SET_INT_SELECT_LIST](state, intList) {
        return state.listIntSelect = intList;
    },

    [SET_USER_TYPE_LIST](state, userTypeList) {
        return state.userTypeList = userTypeList;
    },

    [SET_ROLE_LIST](state, roleList) {
        return state.roleList = roleList;
    },

    [USER_INFOR](state, { userInfor }) {
        return state.userInfor = userInfor;
    },

    [MESSAGE_FORMS] (state, payload) {
        state.messageForms = payload;
    },
};

const actions = {
    async actionGetUser({ commit }, { vue, params, showMainLoading}) {
        let mainLoading = vue.$store.state.storeLoading.mainLoading;

        if (showMainLoading) {
            vue.$store.dispatch('setAdminMainLoading', { ...mainLoading, show: true });
        }

        let response = await UserService.getTeams(params);

        if (showMainLoading) {
            vue.$store.dispatch('setAdminMainLoading', { ...mainLoading, show: false });
        }

        if (response.status === 200) {
            return commit(LIST_USER, { listUser: response.data });
        }

        let errorResponse = response.response;

        return Helper.handleError(vue, errorResponse.status);
    },

    async actionGetUserInformation({ commit }, { vue, id }) {
        let mainLoading = vue.$store.state.storeLoading.mainLoading;
        vue.$store.dispatch('setAdminMainLoading', { ...mainLoading, show: true });
        let response = await UserService.getUserInformation(id);
        vue.$store.dispatch('setAdminMainLoading', { ...mainLoading, show: false });

        if (response.status === 200) {
            return commit(USER_INFOR, { userInfor: response.data });
        }

        let errorResponse = response.response;

        return Helper.handleError(vue, errorResponse.status);
    },

    async actionGetListPayment({commit}, { vue }) {
        let response = await UserService.getListPayment();

        if (response.status === 200) {
            return commit(LIST_PAYMENT, { listPayment: response.data });
        }

        let errorResponse = response.response;
        return Helper.handleError(vue, errorResponse.status);
    },

    async actionGetListCountry({commit}, { vue }) {
        let response = await UserService.getCountryList();

        if (response.status === 200) {
            return commit(SET_COUNTRY_LIST, response.data);
        }

        let errorResponse = response.response;
        return Helper.handleError(vue, errorResponse.status);
    },

    async actionGetListCountryId({commit}, { vue, params }) {
        let response = await UserService.getCountryList({ params: params });

        if (response.status === 200) {
            return commit(SET_COUNTRY_UPDATE_LIST, response.data);
        }

        let errorResponse = response.response;
        return Helper.handleError(vue, errorResponse.status);
    },

    async actionGetListInternalDomain({commit}, { vue, userId }) {
        let response = await UserService.getIntList({ params: { only_int: 1, employee_id: userId, full_page: true, format: 1} });

        if (response.status === 200) {
            return commit(SET_INT_UPDATE_LIST, response.data);
        }

        let errorResponse = response.response;
        return Helper.handleError(vue, errorResponse.status);
    },

    async actionGetListInternalDomainSelect({commit}, { vue, countryId }) {
        let response = await UserService.getIntList({ params: { get_all: 1, country_id: countryId, full_page: true, format: 1 } });

        if (response.status === 200) {
            return commit(SET_INT_SELECT_LIST, response.data);
        }

        let errorResponse = response.response;
        return Helper.handleError(vue, errorResponse.status);
    },

    async actionGetListRole({commit}, { vue }) {
        let response = await UserService.getRoleList();

        if (response.status === 200) {
            return commit(SET_ROLE_LIST, response.data);
        }

        let errorResponse = response.response;
        return Helper.handleError(vue, errorResponse.status);
    },

    async actionGetListUserType({commit}, { vue }) {
        let response = await UserService.getUserTypeList();

        if (response.status === 200) {
            return commit(SET_USER_TYPE_LIST, response.data);
        }

        let errorResponse = response.response;
        return Helper.handleError(vue, errorResponse.status);
    },

    async actionAddUser({commit},  user) {
        try {
            let response = await UserService.addUser(user);

            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'saved_user', message: '#' + response.data.data.id + ' Saved !', errors: {} });
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

    async actionAddSubAccount({commit},  user) {
        try {
            let response = await UserService.addSubAccount(user);

            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'saved_account', message: 'successfully saved', errors: {} });
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

    async actionUpdateUser({commit}, params) {
        try {
            let response = await UserService.updateUser(params);
            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'updated_user', message: 'Saved !', errors: {} });
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

    async actionUpdateUserPermission({commit}, params) {
        try {
            let response = await UserService.updateUserPermission(params);
            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'updated_permission_user', message: 'Saved !', errors: {} });
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

    async actionUpdateUserIntPermission({commit}, params) {
        try {
            let response = await UserService.updateUserIntPermission(params);
            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'updated_int_permission_user', message: 'Saved !', errors: {} });
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

    clearMessageFormUser({commit}) {
        commit(MESSAGE_FORMS, { action: '', message: '', errors: {}});
    },
};

const storeUser = {
    state,
    actions,
    mutations,
};

export default storeUser;
