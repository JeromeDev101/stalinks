import WalletTransactionService from '@/modules/wallet_transaction/api';

const LIST_BUYER = 'LIST_BUYER';
const LIST_BUYER_TRANSACTIONS = 'LIST_BUYER_TRANSACTIONS';
const LIST_WALLET = 'LIST_WALLET';
const SET_ERROR = 'WALLET_SET_ERROR';
const MESSAGE_FORMS = 'MESSAGE_FORMS';
const LIST_PAYMENT = 'LIST_PAYMENT';

const state = {
    listWallet: { data:[] },
    listBuyer: { data:[] },
    listBuyerTransactions: { data:[] },
    messageForms: { action: '', message: '', errors: {} },
    listPayment: { data: [] },
}

const mutations = {
    [MESSAGE_FORMS] (state, payload) {
        state.messageForms = payload;
    },

    [LIST_WALLET] (state , data) {
        state.listWallet = data.listWallet;
    },

    [LIST_BUYER] (state , data) {
        state.listBuyer = data.listBuyer;
    },

    [LIST_BUYER_TRANSACTIONS] (state , data) {
        state.listBuyerTransactions = data.listBuyerTransactions;
    },

    [SET_ERROR](state, error) {
        state.error = error;
    },

    [LIST_PAYMENT] (state, list) {
        state.listPayment = list.listPayment;
    },
}

const actions = {

    async actionGetListBuyer({ commit }, params){
        try{
            let response = await WalletTransactionService.listBuyer(params);
            commit(LIST_BUYER, { listBuyer: response.data });
        }catch( e ) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SET_ERROR, errors);
            } else {
                commit(SET_ERROR, e.response.data);
            }
        }
    },

    async actionGetListBuyerWithTransaction({commit}, params) {
        try{
            let response = await WalletTransactionService.listBuyerWithTransactions(params);
            commit(LIST_BUYER_TRANSACTIONS, { listBuyerTransactions: response.data });
        }catch( e ) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SET_ERROR, errors);
            } else {
                commit(SET_ERROR, e.response.data);
            }
        }
    },

    async actionUpdateWallet({ commit }, params){
        try{
            let response = await WalletTransactionService.updateWallet(params);

            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'success', message: 'Sucessfully Updated!', errors: {} });
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

    async actionAddWallet({ commit }, params){
        try{
            let response = await WalletTransactionService.addWallet(params);

            if (response.status === 200 && response.data.success === true) {
                commit(MESSAGE_FORMS, { action: 'success', message: 'Sucessfully Added!', errors: {} });
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

    async actionGetListPaymentType({ commit }, params) {
        try {
            let response = await WalletTransactionService.getPaymentType(params);
            commit(LIST_PAYMENT, { listPayment: response.data });
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(SET_ERROR, errors);
            } else {
                commit(SET_ERROR, e.response.data);
            }
        }
    },

    async actionGetWalletList({ commit }, params){
        try{
            let response = await WalletTransactionService.getList(params);
            commit(LIST_WALLET, { listWallet: response.data });
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

const storeWalletTransaction = {
    state,
    actions,
    mutations,
};
export default storeWalletTransaction;
