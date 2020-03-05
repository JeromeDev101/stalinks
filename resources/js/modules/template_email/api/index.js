import axios from 'axios';

export default class EmailTemplateService {

    /**
     * Return EmailTemplate total list
     *
     * @returns {AxiosPromise}
     *
     */
    static getEmailTemplateList(params) {
        return axios.get('/api/mails', params)
            .then(response => response)
            .catch(error => error);
    }

    /**
     * Return update status
     *
     * @returns {AxiosPromise}
     *
     */
    static updateEmailTemplate(params) {
        return axios.put('/api/mails', params)
            .then(response => response)
            .catch(error => error);
    }

    static deleteEmailTemplate(params) {
        return axios.delete('/api/mails', params)
            .then(response => response)
            .catch(error => error);
    }

    /**
     * Return list countries
     *
     * @returns {AxiosPromise}
     *
     */
    static getCountryList(params) {
        return axios.get('/api/countries', params)
            .then(response => response)
            .catch(error => error);
    }

    /**
     * Return new template
     *
     * @returns {AxiosPromise}
     *
     */
    static addTemplate(params) {
        return axios.post('/api/mails', params)
            .then(response => response)
            .catch(error => error);
    }
}