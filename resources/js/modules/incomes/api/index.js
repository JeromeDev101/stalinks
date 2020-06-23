import axios from 'axios';

export default class IncomeService {

    static getList(params){
        return axios.get('api/incomes', params)
            .then(response => response)
            .catch(error => error)
    }

    static updateIncomes(params){
        return axios.put('api/incomes', params)
            .then(response => response)
            .catch(error => error)
    }
}