import axios from 'axios';

export default class followupSalesService {

    static getList(params) {
        return axios.get('api/sales', params)
            .then(response => response)
            .catch(error => error);
    }

    static updateSales(params) {
        return axios.put('api/sales', params)
            .then(response => response)
            .catch(error => error)
    }

}