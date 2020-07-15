import axios from 'axios';

export default class WriterBillingService {

    static getListArticle(params) {
        return axios.get('api/writer-billing', params)
            .then(response => response)
            .catch(error => error);
    }

    static getPaymentType(params){
        return axios.get('api/payment-list', params)
            .then(response => response)
            .catch(error => error);
    }

    static payBilling(params){
        return axios.post('api/writer-billing', params)
            .then(response => response)
            .catch(error => error);
    }
}