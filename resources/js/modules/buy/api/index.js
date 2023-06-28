import axios from 'axios';

export default class BuyService {

    static getBuyList(params){
        return axios.get('api/buy', params)
            .then(response => response)
            .catch(error => error);
    }

    static checkCreditAuth(params){
        return axios.get('api/check-credit-auth', params)
            .then(response => response)
            .catch(error => error);
    }

    static getListCountries(params) {
        return axios.get('/api/countries', params)
            .then(response => response)
            .catch(error => error);
    }

    static getListContinents(params) {
        return axios.get('/api/continent-list', params)
            .then(response => response)
            .catch(error => error);
    }

    static updateBuy(params) {
        return axios.put('api/buy', params)
            .then(response => response)
            .catch(error => error);
    }

    static updateUninterested(params) {
        return axios.post('api/buy-uninterested-new', params)
            .then(response => response)
            .catch(error => error);
    }

    static updateInterested(params) {
        return axios.post('api/buy-interested-new', params)
            .then(response => response)
            .catch(error => error);
    }

    static saveInterestedDetails(params) {
        return axios.post('api/save-interested-details', params)
            .then(response => response)
            .catch(error => error);
    }

    static updateDislike(params) {
        return axios.post('api/buy-dislike', params)
            .then(response => response)
            .catch(error => error);
    }

    static updateLike(params) {
        return axios.post('api/buy-like', params)
            .then(response => response)
            .catch(error => error);
    }

    static getListSeller(params) {
        return axios.get('api/wallet-user-seller', params)
            .then(response => response)
            .catch(error => error);
    }

}
