import axios from 'axios';

export default class PublisherService {

    static getList(params) {
        return axios.get('/api/publisher', params)
            .then(response => response)
            .catch(error => error);
    }

    static uploadCsv(params) {
        return axios.post('/api/publisher/upload-csv', params, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        .then(response => response)
        .catch(error => error);
    }
}