import axios from 'axios';

export default class ToolService {

    static getList(params) {
        return axios.get('/api/tools', params)
            .then(response => response)
            .catch(error => error);
    }

    static addTool(params) {
        return axios.post('/api/tools', params)
            .then(response => response)
            .catch(error => error);
    }

    static updateTool(params) {
        return axios.put('/api/tools', params)
            .then(response => response)
            .catch(error => error);
    }
}
