import followupSalesService from '@/modules/followup_sales/api';

const LIST_SALES = 'LIST_SALES';
const MESSAGE_FORMS = 'SALES_MESSAGE_FORMS';
const SALES_ERROR = 'SALES_ERROR';

const state = {
    listSales: { data:[] },
    messageForms: { action: '', message: '', errors: {} },
}

const mutations = {
    [LIST_SALES](state, dataSet){
        state.listSales = dataSet.listSales;
    },

    [MESSAGE_FORMS] (state, message) {
        state.messageForms = message;
    },

    [SALES_ERROR](state, error) {
        state.error = error;
    },
}

const actions = {
    async actionGetListSales({ commit }, params){
        try {
            let response = await followupSalesService.getList(params)
            commit(LIST_SALES , { listSales: response.data });
        }catch(e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SALES_ERROR, errors);
            } else {
                commit(SALES_ERROR, e.response.data);
            }
        }
    },
    async actionUpdateSales({ commit }, params){
        try {
            let response = await followupSalesService.updateSales(params)

            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'updated', message: 'Sucessfully Updated!', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        }catch(e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SALES_ERROR, errors);
            } else {
                commit(SALES_ERROR, e.response.data);
            }
        }
    },

    clearMessageform({commit}) {
        commit(MESSAGE_FORMS, { action: '', message: '', errors: {}});
    },
}


const storeFollowupSales = {
    state,
    actions,
    mutations,
};

export default storeFollowupSales;