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

    static reUploadBillingDoc(params){
        return axios.post('api/seller-billing-reupload-doc', params)
            .then(response => response)
            .catch(error => error);
    }

    static getPaymentType(params){
        return axios.get('api/payment-list', params)
            .then(response => response)
            .catch(error => error);
    }

    static getListSeller(params) {
        return axios.get('api/wallet-user-seller', params)
            .then(response => response)
            .catch(error => error);
    }

    static getSellerInfo(params) {
        return axios.post('api/get-seller-info', params)
            .then(response => response)
            .catch(error => error);
    }

}
