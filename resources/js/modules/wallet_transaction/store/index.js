import WalletTransactionService from '@/modules/wallet_transaction/api';

const LIST_WALLET = 'LIST_WALLET';
const SET_ERROR = 'WALLET_SET_ERROR';
const MESSAGE_FORMS = 'MESSAGE_FORMS';

const state = {
    listWallet: { data:[] },
    messageForms: { action: '', message: '', errors: {} },
}

const mutations = {
    [MESSAGE_FORMS] (state, payload) {
        state.messageForms = payload;
    },

    [LIST_WALLET] (state , data) {
        state.listWallet = data.listWallet;
    },

    [SET_ERROR](state, error) {
        state.error = error;
    },
}

const actions = {
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