import IncomeAdminService from '@/modules/incomes/admin/api';

const LIST_INCOMES_ADMIN = 'LIST_INCOMES_ADMIN';
const MESSAGE_FORMS = 'INCOMES_ADMIN_MESSAGE_FORMS';
const INCOMES_ADMIN_ERROR = 'INCOMES_ADMIN_ERROR';

const state = {
    listIncomesAdmin: {
        affiliate_commission_sum: 0,
        billing_count_sum: 0,
        buyer_price_sum: 0,
        content_charges_sum: 0,
        fee_charges_sum: 0,
        seller_price_sum: 0,
        net_incomes_sum: 0,
        data: [],
    },
    messageForms: { action: '', message: '', errors: {} },
    tblOptIncomesAdmin: {
        buyer_id: true,
        buyer: true,
        backlink_id: true,
        selling_price: true,
        price: true,
        gross_income: true,
        fee_charges: true,
        content_charges: true,
        net_incomes: true,
        live_date: true,
        affiliate_commission: true,
    },
}

const mutations = {
    [LIST_INCOMES_ADMIN](state, dataSet){
        state.listIncomesAdmin = dataSet.listIncomesAdmin;
    },

    [MESSAGE_FORMS] (state, message) {
        state.messageForms = message;
    },

    [INCOMES_ADMIN_ERROR](state, error) {
        state.error = error;
    },
}


const actions = {
    async actionGetListIncomesAdmin({ commit }, params){
        try {
            let response = await IncomeAdminService.getList(params)

            commit(LIST_INCOMES_ADMIN , { listIncomesAdmin: response.data });
        }catch(e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(INCOMES_ADMIN_ERROR, errors);
            } else {
                commit(INCOMES_ADMIN_ERROR, e.response.data);
            }
        }
    },

    clearMessageformIncomesAdmin({commit}) {
        commit(MESSAGE_FORMS, { action: '', message: '', errors: {}});
    },
}

const storeIncomesAdmin = {
    state,
    mutations,
    actions,
};

export default storeIncomesAdmin;
