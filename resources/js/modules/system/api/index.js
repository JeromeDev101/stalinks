import axios from 'axios';

export default class SystemService {

    /**
     * Return config total list
     *
     * @returns {AxiosPromise}
     *
     */
    static getConfigList(params) {
        return axios.get('/api/admin/configs', params)
            .then(response => response)
            .catch(error => error);
    }

    /**
     * Return update status
     *
     * @returns {AxiosPromise}
     *
     */
    static updateConfig(params) {
        return axios.put('/api/admin/configs', params)
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
     * Return list of payment types
     * 
     * @param {AxiosPromise} params 
     */
    static getPaymentList(params) {
        return axios.get('api/admin/payments', params)
            .then(response => response)
            .catch(error => error);
    }

    /**
     * Return new Country
     *
     * @returns {AxiosPromise}
     *
     */
    static addCountry(params) {
        return axios.post('/api/admin/countries', params)
            .then(response => response)
            .catch(error => error);
    }

    /**
     * Return new Payment
     *
     * @returns {AxiosPromise}
     *
     */
    static addPaymentType(params) {
        return axios.post('/api/admin/payments', params)
            .then(response => response)
            .catch(error => error);
    }

    /**
     * Return status success
     *
     * @returns {AxiosPromise}
     *
     */
    static updateCountry(params) {
        return axios.put('/api/admin/countries', params)
            .then(response => response)
            .catch(error => error);
    }

    /**
     * Return status success
     *
     * @returns {AxiosPromise}
     *
     */
    static updatePayment(params) {
        return axios.put('/api/admin/payments', params)
            .then(response => response)
            .catch(error => error);
    }
}