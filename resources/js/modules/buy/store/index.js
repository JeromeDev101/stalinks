import BuyService from '@/modules/buy/api';

const BUY_LIST = 'BUY_LIST';
const SET_ERROR = 'BUY_SET_ERROR';
const MESSAGE_FORMS = 'MESSAGE_FORMS';
const LIST_COUNTRY = 'LIST_COUNTRY';
const LIST_SELLER = 'LIST_SELLER';

const state = {
    listBuy: { data:[] },
    listSeller: { data:[] },
    messageForms: { action: '', message: '', errors: {} },
    listCountries: { data: [], total: 0 },
}

const mutations = {
    [MESSAGE_FORMS] (state, payload) {
        state.messageForms = payload;
    },

    [BUY_LIST] (state , data) {
        state.listBuy = data.listBuy;
    },
    
    [LIST_SELLER] (state , data) {
        state.listSeller = data.listSeller;
    },

    [SET_ERROR](state, error) {
        state.error = error;
    },

    [LIST_COUNTRY](state, listCountries) {
        state.listCountries = listCountries;
    },
} 

const actions = {
    async actionGetBuyList({ commit }, params){
        try{
            let response = await BuyService.getBuyList(params);
            commit(BUY_LIST, { listBuy: response.data });
        }catch( e ) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SET_ERROR, errors);
            } else {
                commit(SET_ERROR, e.response.data);
            }
        }
    },

    async actionGetListSeller({ commit }, params){
        try {
            let response = await BuyService.getListSeller(params)
            commit(LIST_SELLER , { listSeller: response.data });
        }catch(e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SET_ERROR, errors);
            } else {
                commit(SET_ERROR, e.response.data);
            }
        }
    },

    async actionGetListCountries({ commit }, params) {
        try {
            let response = await BuyService.getListCountries(params);
            commit(LIST_COUNTRY, response.data );
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SET_ERROR, errors);
            } else {
                commit(SET_ERROR, e.response.data);
            }
        }
    },

    async actionDislike({ commit }, params) { 
        try{
            let response = await BuyService.updateDislike(params);

            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'updated', message: 'Saved !', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        }catch( e ) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SET_ERROR, errors);
            } else {
                commit(SET_ERROR, e.response.data);
            }
        }
    },

    async actionLike({ commit }, params) { 
        try{
            let response = await BuyService.updateLike(params);

            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'updated', message: 'Saved !', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        }catch( e ) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SET_ERROR, errors);
            } else {
                commit(SET_ERROR, e.response.data);
            }
        }
    },

    async actionCheckCreditAuth({ commit }, params) { 
        try{
            let response = await BuyService.checkCreditAuth(params);

            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'success', message: response.data.data, errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        }catch( e ) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SET_ERROR, errors);
            } else {
                commit(SET_ERROR, e.response.data);
            }
        }
    },

    async actionUpdateBuy({ commit }, params) { 
        try{
            let response = await BuyService.updateBuy(params);

            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'updated', message: 'Buyed Successfully!', errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        }catch( e ) {
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

const storeBuy = {
    state,
    actions,
    mutations,
};
export default storeBuy;