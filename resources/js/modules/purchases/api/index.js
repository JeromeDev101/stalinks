import axios from 'axios';

export default class PurchaseService {
    // purchase summary

    static getPurchases(params) {
        return axios.get('/api/purchases', params)
            .then(response => response)
            .catch(error => error)
    }

    static getPurchasesBuyerSelection(params) {
        return axios.get('/api/purchases/buyer-selection/' + params)
            .then(response => response)
            .catch(error => error)
    }

    static addPurchase(params) {
        return axios.post('/api/purchases', params)
            .then(response => response)
            .catch(error => error);
    }

    static updatePurchase(params) {
        return axios.post('/api/purchases', params)
            .then(response => response)
            .catch(error => error);
    }

    static deletePurchase(params) {
        return axios.delete('/api/purchases/' + params)
            .then(response => response)
            .catch(error => error);
    }

    // purchase category

    static getPurchaseCategories(params) {
        return axios.get('/api/purchases/category', params)
            .then(response => response)
            .catch(error => error)
    }

    static getPurchaseCategoriesSelection(params) {
        return axios.get('/api/purchases/category/selection', params)
            .then(response => response)
            .catch(error => error)
    }

    static addPurchaseCategory(params) {
        return axios.post('/api/purchases/category', params)
            .then(response => response)
            .catch(error => error);
    }

    static updatePurchaseCategory(params) {
        return axios.put('/api/purchases/category', params)
            .then(response => response)
            .catch(error => error);
    }

    // purchase type

    static getPurchaseTypesSelection(params) {
        return axios.get('/api/purchases/type/selection', params)
            .then(response => response)
            .catch(error => error)
    }

    static addPurchaseType(params) {
        return axios.post('/api/purchases/type', params)
            .then(response => response)
            .catch(error => error);
    }

    static updatePurchaseType(params) {
        return axios.put('/api/purchases/type', params)
            .then(response => response)
            .catch(error => error);
    }

    // graphs

    static getCategoryReportData(params) {
        return axios.get('/api/purchases/graphs/category', params)
            .then(response => response)
            .catch(error => error)
    }

    static getPurchaseTypeReportData(params) {
        return axios.get('/api/purchases/graphs/purchase-type', params)
            .then(response => response)
            .catch(error => error)
    }

    static getPaymentMethodReportData(params) {
        return axios.get('/api/purchases/graphs/payment-method', params)
            .then(response => response)
            .catch(error => error)
    }

    // purchase module

    static getPurchaseModuleSelection(params) {
        return axios.get('/api/purchases/modules/selection', params)
            .then(response => response)
            .catch(error => error)
    }
}
