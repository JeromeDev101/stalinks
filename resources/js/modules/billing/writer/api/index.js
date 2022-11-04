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

    static getWriterInfo(params) {
        return axios.post('api/get-writer-info', params)
            .then(response => response)
            .catch(error => error);
    }

    static reUploadBillingDocWriter(params){
        return axios.post('api/writer-billing-reupload-doc', params)
            .then(response => response)
            .catch(error => error);
    }
}
