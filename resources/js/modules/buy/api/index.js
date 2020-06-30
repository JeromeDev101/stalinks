import axios from 'axios';

export default class BuyService {

    static getBuyList(params){
        return axios.get('api/buy', params)
            .then(response => response)
            .catch(error => error);
    } 

    static getListCountries(params) {
        return axios.get('/api/countries', params)
            .then(response => response)
            .catch(error => error);
    }

    static updateBuy(params) {
        return axios.put('api/buy', params)
            .then(response => response)
            .catch(error => error);
    }

    static updateDislike(params) {
        return axios.post('api/buy-dislike', params)
            .then(response => response)
            .catch(error => error);
    }

}