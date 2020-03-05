import axios from 'axios';

export default class ExtDomainService {

    /**
     * Return total ext_domain
     *
     * @returns {AxiosPromise}
     *
     */
    static getTotalExt(params) {
        return axios.post('/api/ext/reports', params)
            .then(response => response)
            .catch(error => error);
    }

    static getListExt(params) {
        return axios.get('/api/ext', params)
            .then(response => response)
            .catch(error => error);
    }

    static getAlexaRankList(params) {
        return axios.post('/api/ext/alexa', params)
            .then(response => response)
            .catch(error => error);
    }

    static crawlExtList(params) {
        return axios.get('/api/ext/get-contacts', params)
            .then(response => response)
            .catch(error => error);
    }

    static addExt(params) {
        return axios.post('/api/ext', params)
            .then(response => response)
            .catch(error => error);
    }

    static updateExt(params) {
        return axios.put('/api/ext', params)
            .then(response => response)
            .catch(error => error);
    }

    static getListCountriesInt() {
        return axios.get('/api/countries-int')
            .then(response => response)
            .catch(error => error);
    }

    static getListCountries() {
        return axios.get('/api/countries')
            .then(response => response)
            .catch(error => error);
    }

    static getListAhrefs(params) {
        return axios.get('/api/ext/ahrefs', params)
            .then(response => response)
            .catch(error => error);
    }

    static getListMails(params) {
        return axios.get('/api/mails', params)
            .then(response => response)
            .catch(error => error);
    }

    static sendEmails(params) {
        return axios.post('/api/send-mails', params)
            .then(response => response)
            .catch(error => error);
    }
}
