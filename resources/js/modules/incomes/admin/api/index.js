import axios from 'axios';

export default class IncomeAdminService {

    static getList(params){
        return axios.get('api/incomes-admin', params)
            .then(response => response)
            .catch(error => error)
    }
}