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

    static getExtListSeller(params) {
        return axios.get('/api/ext/ext-seller', params)
            .then(response => response)
            .catch(error => error);
    }

    static getListSellerTeam(params) {
        return axios.get('/api/wallet-user-seller-team', params)
            .then(response => response)
            .catch(error => error);
    }

    static deleteExtDomain(params) {
        return axios.delete('/api/ext', params)
            .then(response => response)
            .catch(error => error);
    }

    static addExt(params) {
        return axios.post('/api/ext', params)
            .then(response => response)
            .catch(error => error);
    }

    static updateMultipleStatus(params) {
        return axios.put('/api/ext/update-multiple-status', params)
            .then(response => response)
            .catch(error => error);
    }

    static updateExt(params) {
        return axios.put('/api/ext', params)
            .then(response => response)
            .catch(error => error);
    }

    static uploadCsv(params) {
        return axios.post('/api/ext/upload-csv', params, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
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
        return axios.put('/api/ext/ahrefs', params)

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

    static sendEmailsWithMailgun(params) {
        return axios.post('/api/mail/send', params)
            .then(response => response)
            .catch(error => error);
    }
}
