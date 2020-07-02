import axios from 'axios';

export default class SellerBillingService {

    static getList(params){
        return axios.get('api/seller-billing', params)
            .then(response => response)
            .catch(error => error);
    }

    static payBilling(params){
        return axios.post('api/seller-billing', params)
            .then(response => response)
            .catch(error => error);
    }


    static getPaymentType(params){
        return axios.get('api/payment-list', params)
            .then(response => response)
            .catch(error => error);
    }

}