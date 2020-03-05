import axios from 'axios';

export default class DomainService {

    /**
     * Return list domain
     *
     * @returns {AxiosPromise}
     *
     */
    static actionGetDomain(params) {
        let options = {
            method: 'GET',
            url: `/api/domains?page=${params.page}`,
            params: {
                'params': params.q,
            },
            json: true,
        };
        return axios(options)
            .then(response => response)
            .catch(error => error)
    }

    static actionGetFullDomain() {
        let options = {
            method: 'GET',
            url: `/api/domains?full_data=true`,
            json: true,
        };
        return axios(options)
            .then(response => response)
            .catch(error => error)
    }

    static getDomainDetail(id) {
        let options = {
            method: 'GET',
            url: `/api/domains/${id}`,
            json: true,
        };
        return axios(options)
            .then(response => response)
            .catch(error => error);
    }

    static addDomain(hosting) {
        return axios.post('/api/domains', hosting)
            .then(response => response)
            .catch(error => error);
    }

    static editDomain(hosting) {
        return axios.put(`/api/domains/${hosting.id}`, hosting)
            .then(response => response)
            .catch(error => error);
    }
}