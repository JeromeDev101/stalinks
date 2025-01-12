import axios from 'axios';

export default class AccountService {

    static addAccount(user){
        return axios.post('api/accounts', user)
            .then(response => response)
            .catch(error => error);
    }

    static addRegister(params){
        return axios.post('api/register', params)
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

    static verifyAccount(params) {
        return axios.post('/api/verify-account', params)
            .then(response => response)
            .catch(error => error);
    }

    static checkVerificationCode(params) {
        return axios.get(`/api/check-verification-code?code=${params.code}`, params)
            .then(response => response)
            .catch(error => error);
    }

    static updatePassword(params) {
        return axios.post('/api/verification', params)
            .then(response => response)
            .catch(error => error);
    }

    static getSellers(){
        return axios.get('api/accounts/get-sellers')
            .then(response => response)
            .catch(error => error);
    }

    static getTeamInCharge(){
        return axios.get('api/team-in-charge')
            .then(response => response)
            .catch(error => error);
    }

    static getAffiliateList(){
        return axios.get('api/get-affiliate-list')
            .then(response => response)
            .catch(error => error);
    }
}
