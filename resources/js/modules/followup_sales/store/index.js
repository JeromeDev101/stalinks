import followupSalesService from '@/modules/followup_sales/api';

const LIST_SALES = 'LIST_SALES';
const LIST_BUYER = 'LIST_BUYER';
const LIST_SELLER = 'LIST_SELLER';
const MESSAGE_FORMS = 'SALES_MESSAGE_FORMS';
const SALES_ERROR = 'SALES_ERROR';

const state = {
    listSales: { data:[] },
    listBuyer: { data:[] },
    listSeller: { data:[] },
    messageForms: { action: '', message: '', errors: {} },
    tblFollowupSalesOpt: {
        pub_id: true,
        blink_id: true,
        arc_id: true,
        country: false,
        in_charge: true,
        seller: true,
        inc_art: true,
        buyer: true,
        url: true,
        price: true,
        link_from: true,
        link_to: false,
        anchor_text: false,
        date_process: false,
        date_complete: false,
        status: true,
    },
}

const mutations = {
    [LIST_SALES](state, dataSet){
        state.listSales = dataSet.listSales;
    },

    [LIST_BUYER](state, dataSet){
        state.listBuyer = dataSet.listBuyer;
    },

    [LIST_SELLER](state, dataSet){
        state.listSeller = dataSet.listSeller;
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

/*    async actionGetListBuyer({ commit }, params){
        try {
            let response = await followupSalesService.getListBuyer(params)
            commit(LIST_BUYER , { listBuyer: response.data });
        }catch(e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SALES_ERROR, errors);
            } else {
                commit(SALES_ERROR, e.response.data);
            }
        }
    },*/

/*    async actionGetListSeller({ commit }, params){
        try {
            let response = await followupSalesService.getListSeller(params)
            commit(LIST_SELLER , { listSeller: response.data });
        }catch(e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SALES_ERROR, errors);
            } else {
                commit(SALES_ERROR, e.response.data);
            }
        }
    },*/

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
