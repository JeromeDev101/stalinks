import axios from 'axios';

export default class PublisherService {

    // static getList(params) {
    //     return axios.get(`/api/publisher?page=${params.page}`, params)
    //         .then(response => response)
    //         .catch(error => error);
    // }

    static getPublisherSummary() {
        return axios.get('/api/publisher/summary')
            .then(response => response)
            .catch(error => error);
    }

    static getList(params) {
        return axios.get('/api/publisher', params)
            .then(response => response)
            .catch(error => error);
    }

    static getListSeller(params) {
        return axios.get('/api/wallet-user-seller', params)
            .then(response => response)
            .catch(error => error);
    }

    static getListSellerIncharge(params) {
        return axios.get('/api/seller-incharge/' + params)
            .then(response => response)
            .catch(error => error);
    }

    static addUrl(params) {
        return axios.post('/api/publisher', params)
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
        .then(response => response)
        .catch(error => error);
    }

    static validData(params) {
        return axios.post('/api/publisher/valid', params)
            .then(response => response)
            .catch(error => error);
    }

    static updatePublisher(params) {
        return axios.put('/api/publisher', params)
            .then(response => response)
            .catch(error => error);
    }

    static getListCountries(params) {
        return axios.get('/api/country-list', params)
            .then(response => response)
            .catch(error => error);
    }

    static getListContinents(params) {
        return axios.get('/api/continent-list', params)
            .then(response => response)
            .catch(error => error);
    }

    static getListLanguages(params) {
        return axios.get('/api/languages-list', params)
            .then(response => response)
            .catch(error => error);
    }


    static deletePublisher(params) {
        return axios.delete('/api/publisher', params)
            .then(response => response)
            .catch(error => error);
    }

    static getListAhrefsPublisher(params) {
        return axios.put('/api/publisher/ahrefs', params)
            .then(response => response)
            .catch(error => error);
    }

    static generateBestPrices() {
        return axios.post('/api/generate-best-price')
            .then(response => response)
            .catch(error => error);
    }

    static getGeneratorLogsApi() {
        return axios.get('/api/publisher/best-prices/log')
            .then(response => response)
            .catch(error => error);
    }

}
