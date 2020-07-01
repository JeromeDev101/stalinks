import axios from 'axios';
export default class PurchaseService {

    static getList(params) {
        return axios.get('api/purchase', params)
            .then(response => response)
            .catch(error => error)
    }
}