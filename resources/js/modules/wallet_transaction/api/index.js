import axios from 'axios';

export default class WalletTransactionService {

    static getList(params){
        return axios.get('api/wallet-transaction', params)
            .then(respnse => response)
            .catch(error => error)
    }
}