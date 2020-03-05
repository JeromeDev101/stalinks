import axios from 'axios';

export default class HostingService {

    /**
     * Return list hosting
     *
     * @returns {AxiosPromise}
     *
     */
    static actionGetHosting(params) {
        let options = {
            method: 'GET',
            url: `/api/hostings?page=${params.page}`,
            params: {
                'params': params.q,
            },
            json: true,
        };
        return axios(options)
            .then(response => response)
            .catch(error => error)
    }

    static actionGetFullHosting() {
        let options = {
            method: 'GET',
            url: `/api/hostings?full_data=true`,
            json: true,
        };
        return axios(options)
            .then(response => response)
            .catch(error => error)
    }

    static getHostingDetail(id) {
        let options = {
            method: 'GET',
            url: `/api/hostings/${id}`,
            json: true,
        };
        return axios(options)
            .then(response => response)
            .catch(error => error);
    }

    static addHosting(hosting) {
        return axios.post('/api/hostings', hosting)
            .then(response => response)
            .catch(error => error);
    }

    static editHosting(hosting) {
        return axios.put(`/api/hostings/${hosting.id}`, hosting)
            .then(response => response)
            .catch(error => error);
    }
}