
import LogService from '@/modules/logs/api';
import Helper from '@/library/Helper';

const LOG_LIST_LOGS = 'LOG_LIST_LOGS';
const LOG_LIST_ACTION = 'LOG_LIST_ACTION';
const LOG_LIST_TABLE = 'LOG_LIST_TABLE';
const LOG_SET_ERROR = 'LOG_SET_ERROR';
const LOG_MESSAGE_FORMS = 'LOG_MESSAGE_FORMS';
const LOG_LIST_LOGS_MAIL = 'LOG_LIST_LOGS_MAIL';

const state = {
    listLogs: { data: [], total: 0, pagination: '', total_update: 0, total_create: 0, total_delete: 0, total_other: 0 },
    listLogsMail: { data: [], total: 0, pagination: '', total_success: 0 },
    listAction: [],
    listTable: [],
    error: {},
    counter: [],
    counterMail: { success: 0 },
    messageForms: { action: '', message: '', errors: {} },
    listStatusMail: {
        0: {text: 'Failed', label: 'danger'},
        1: {text: 'Success', label: 'success'},
        2: {text: 'Sending', label: 'primary'}
    }
};

const mutations = {
    [LOG_LIST_LOGS](state, listLogs) {
        state.listLogs = listLogs.paginate;
        state.counter = listLogs.counter;
    },

    [LOG_LIST_LOGS_MAIL](state, listLogs) {
        state.listLogsMail = listLogs.paginate;
        state.counterMail = listLogs.counter;
    },

    [LOG_LIST_ACTION](state, listAction) {
        return state.listAction = listAction;
    },

    [LOG_LIST_TABLE](state, listTable) {
        return state.listTable = listTable;
    },

    [LOG_SET_ERROR](state, error) {
        state.error = error;
    },

    [LOG_MESSAGE_FORMS] (state, payload) {
        state.messageForms = payload;
    },
};

const actions = {
    async actionGetLogsList({commit}, { vue, params }) {
        let response = await LogService.getLogs(params);

        if (response.status === 200) {
            return commit(LOG_LIST_LOGS, response.data);
        }

        let errorResponse = response.response;
        return Helper.handleError(vue, errorResponse.status);
    },

    async actionGetLogsListMail({commit}, { vue, params }) {
        let response = await LogService.getLogsMail(params);

        if (response.status === 200) {
            return commit(LOG_LIST_LOGS_MAIL, response.data);
        }

        let errorResponse = response.response;
        return Helper.handleError(vue, errorResponse.status);
    },

    async actionGetActionListLog({commit}, { vue, params }) {
        let response = await LogService.getActions(params);

        if (response.status === 200) {
            return commit(LOG_LIST_ACTION, response.data);
        }

        let errorResponse = response.response;
        return Helper.handleError(vue, errorResponse.status);
    },

    async actionGetTableListLog({commit}, { vue }) {
        let response = await LogService.getTables();

        if (response.status === 200) {
            return commit(LOG_LIST_TABLE, response.data);
        }

        let errorResponse = response.response;
        return Helper.handleError(vue, errorResponse.status);
    },

    clearMessageFormLog({commit}) {
        commit(LOG_MESSAGE_FORMS, { action: '', message: '', errors: {}});
    },
};

const storeLog = {
    state,
    actions,
    mutations,
};

export default storeLog;
