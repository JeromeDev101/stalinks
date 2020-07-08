import axios from 'axios';

export default class BackLinkService {

    /**
     * Return total backlink
     *
     * @returns {AxiosPromise}
     *
     */
    static getTotalBackLink(params) {
        return axios.post('/api/backlink/reports', params)
            .then(response => response)
            .catch(error => error);
    }

    /**
     * Return total price
     *
     * @returns {AxiosPromise}
     *
     */
    static getTotalPrice(params) {
        return axios.post('/api/backlink/reports-price', params)
            .then(response => response)
            .catch(error => error);
    }

    /**
     * Return list backlink
     *
     * @returns {AxiosPromise}
     *
     */
    static actionGetBackLink(page, query) {
        return axios.get(`/api/backlinks?page=${page}`, {params:{'params': query}})
            .then(response => response)
            .catch(error => error);
    }

    static actionSaveBacklink (params) {
        if (params.id) {
            return axios.put(`/api/backlinks/${params.id}`, params)
            .then(response => response)
            .catch(error => error);
        }
            return axios.post('/api/backlinks', params)
            .then(response => response)
            .catch(error => error);
    }

    static deleteBacklink(params) {
        return axios.post('/api/delete-backlinks', params)
            .then(response => response)
            .catch(error => error);
    }

}