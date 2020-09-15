import axios from 'axios';

export default class DashboardService {

    static getData(params) {
        return axios.get('/api/dashboard', params)
            .then(response => response)
            .catch(error => error);
    }
}