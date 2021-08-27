import ToolService from '@/modules/tools/api';

const LIST_TOOLS = 'LIST_TOOLS';
const TOOLS_ERROR = 'TOOLS_ERROR';
const TOOLS_MESSAGE_FORMS = 'TOOLS_MESSAGE_FORMS';

const state = {
    listTools: { data:[] },
    messageFormsTools: { action: '', message: '', errors: {} },
};

const mutations = {
    [LIST_TOOLS](state, { listTools }) {
        return state.listTools = listTools;
    },

    [TOOLS_ERROR](state, error) {
        state.error = error;
    },

    [TOOLS_MESSAGE_FORMS] (state, payload) {
        state.messageFormsTools = payload;
    },
};

const actions = {
    async actionGetListTools({ commit }, params){
        try {
            let response = await ToolService.getList(params)
            commit(LIST_TOOLS , { listTools: response.data });
        }catch(e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(TOOLS_ERROR, errors);
            } else {
                commit(TOOLS_ERROR, e.response.data);
            }
        }
    },

    async actionAddTool({ commit }, params) {
        try {
            let response = await ToolService.addTool(params);

            if (response.status === 200 && response.data.success === true) {
                commit(TOOLS_MESSAGE_FORMS, { action: 'added', message: 'Tool successfully added!', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(TOOLS_MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(TOOLS_ERROR, errors);
            } else {
                commit(TOOLS_ERROR, e.response.data);
            }
        }
    },

    async actionUpdateTool({commit}, params) {
        try {
            let response = await ToolService.updateTool(params);

            if (response.status === 200 && response.data.success === true) {
                commit(TOOLS_MESSAGE_FORMS, { action: 'updated', message: 'Tool successfully updated!', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(TOOLS_MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(TOOLS_ERROR, errors);
            } else {
                commit(TOOLS_ERROR, e.response.data);
            }
        }
    },

    clearMessageFormTools({commit}) {
        commit(TOOLS_MESSAGE_FORMS, { action: '', message: '', errors: {}});
    },
};

const storeTools = {
    state,
    actions,
    mutations,
};

export default storeTools;
