import axios from 'axios';

export default class IntDomainService {

    /**
     * Return total int_domain
     *
     * @returns {AxiosPromise}
     *
     */
    static getTotalInt(params) {
        return axios.post('/api/int/reports', params)
            .then(response => response)
            .catch(error => error);
    }

    /**
     * Return list int domain
     *
     * @returns {AxiosPromise}
     *
     */
    static actionGetIntDomain(params) {
        return axios.get('/api/int', params)
            .then(response => response)
            .catch(error => error);
    }

    static addInt(params) {
        return axios.post('/api/int', params)
            .then(response => response)
            .catch(error => error);
    }

    static updateInt(params) {
        return axios.put('/api/int', params)
            .then(response => response)
            .catch(error => error);
    }

    static getIntByHosting(id, page) {
        let options = {
            method: 'GET',
            url: `/api/list-by-hosting/${id}?page=${page}`,
            json: true,
        };

        return axios(options)
            .then(response => response)
            .catch(error => error);
    }

    static getintByDomain(id, page) {
        let options = {
            method: 'GET',
            url: `/api/list-by-domain/${id}?page=${page}`,
            json: true,
        };

        return axios(options)
            .then(response => response)
            .catch(error => error);
    }
}