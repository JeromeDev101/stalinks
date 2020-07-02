import axios from 'axios';

export default class PublisherService {

    // static getList(params) {
    //     return axios.get(`/api/publisher?page=${params.page}`, params)
    //         .then(response => response)
    //         .catch(error => error);
    // }

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

    static updatePublisher(params) {
        return axios.put('/api/publisher', params)
            .then(response => response)
            .catch(error => error);
    }

    static getListCountries(params) {
        return axios.get('/api/countries', params)
            .then(response => response)
            .catch(error => error);
    }


    static deletePublisher(params) {
        return axios.delete('/api/publisher', params)
            .then(response => response)
            .catch(error => error);
    }

    static getListAhrefsPublisher(params) {
        return axios.get('/api/publisher/ahrefs', params)
            .then(response => response)
            .catch(error => error);
    }

}