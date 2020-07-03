import axios from 'axios';

export default class WalletTransactionService {

    static getList(params){
        return axios.get('api/wallet-transaction', params)
            .then(response => response)
            .catch(error => error)
    }

    static listBuyer(params){
        return axios.get('api/wallet-user-buyer', params)
            .then(response => response)
            .catch(error => error)
    }

    static getPaymentType(params){
        return axios.get('api/payment-list', params)
            .then(response => response)
            .catch(error => error);
    }

    static addWallet(params){
        return axios.post('api/add-wallet', params)
            .then(response => response)
            .catch(error => error);
    }
}