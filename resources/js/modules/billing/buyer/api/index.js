import axios from 'axios';

export default class BuyerBillingService {

    static getList(params){
        return axios.get('api/buyer-billing', params)
            .then(response => response)
            .catch(error => error)
    }

}