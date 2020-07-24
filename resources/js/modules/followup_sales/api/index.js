import axios from 'axios';

export default class followupSalesService {

    static getList(params) {
        return axios.get('api/sales', params)
            .then(response => response)
            .catch(error => error);
    }

    static getListBuyer(params) {
        return axios.get('api/wallet-user-buyer', params)
            .then(response => response)
            .catch(error => error);
    }

    static getListSeller(params) {
        return axios.get('api/wallet-user-seller', params)
            .then(response => response)
            .catch(error => error);
    }

    static updateSales(params) {
        return axios.put('api/sales', params)
            .then(response => response)
            .catch(error => error)
    }

}