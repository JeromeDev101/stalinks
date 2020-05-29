import axios from 'axios';

export default class PublisherService {

    static getList(params) {
        return axios.get('/api/publisher', params)
            .then(response => response)
            .catch(error => error);
    }
}