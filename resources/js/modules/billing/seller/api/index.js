import axios from 'axios';

export default class SellerBillingService {

    static getList(params){
        return axios.get('api/seller-billing', params)
            .then(response => response)
            .catch(error => error);
    }

}