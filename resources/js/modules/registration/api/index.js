import axios from 'axios';

export default class AccountService {

    static addAccount(user){
        return axios.post('api/accounts', user)
            .then(response => response)
            .catch(error => error);
    }


    static getAccount(params){
        return axios.get('api/accounts', params)
            .then(response => response)
            .catch(error => error);
    }

    static getPaymentType(params){
        return axios.get('api/payment-list', params)
            .then(response => response)
            .catch(error => error);
    }

    static updateAccount(params) {
        return axios.put('/api/accounts', params)
            .then(response => response)
            .catch(error => error);
    }

}