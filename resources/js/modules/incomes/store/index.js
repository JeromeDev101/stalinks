import IncomeService from '@/modules/incomes/api';

const LIST_INCOMES = 'LIST_INCOMES';
const MESSAGE_FORMS = 'INCOMES_MESSAGE_FORMS';
const INCOMES_ERROR = 'INCOMES_ERROR';

const state = {
    listIncomes: { data:[] },
    messageForms: { action: '', message: '', errors: {} },
}

const mutations = {
    [LIST_INCOMES](state, dataSet){
        state.listIncomes = dataSet.listIncomes;
    },

    [MESSAGE_FORMS] (state, message) {
        state.messageForms = message;
    },

    [INCOMES_ERROR](state, error) {
        state.error = error;
    },
}


const actions = {
    async actionGetListIncomes({ commit }, params){
        try {
            let response = await IncomeService.getList(params)
            commit(LIST_INCOMES , { listIncomes: response.data });
        }catch(e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(INCOMES_ERROR, errors);
            } else {
                commit(INCOMES_ERROR, e.response.data);
            }
        }
    },

    async actionUpdateIncomes({ commit }, params){
        try {
            let response = await IncomeService.updateIncomes(params)

            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'updated', message: 'Saved !', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        }catch(e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(INCOMES_ERROR, errors);
            } else {
                commit(INCOMES_ERROR, e.response.data);
            }
        }
    },

    clearMessageform({commit}) {
        commit(MESSAGE_FORMS, { action: '', message: '', errors: {}});
    },
}

const storeIncomes = {
    state,
    mutations,
    actions,
};

export default storeIncomes;