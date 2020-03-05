import axios from 'axios';

export default class UserService {
    /**
     * @param { object } user
     *
     * @returns {AxiosPromise<any>}
     */
    static login(credentials) {
        return axios.post('/api/login', {
            'email': credentials.email,
            'password': credentials.password,
        });
    }
    /**
     * @returns {AxiosPromise<any>}
     */
    static logout() {
        return axios.post('/api/logout')
            .then(response => response)
            .catch(error => error.response);
    }

    /**
     * List current user info
     * @returns {AxiosPromise<any>}
     */
    static getCurrentUserInfo() {
        return axios.get('/api/current-user')
            .then(response => response)
            .catch(error => error);
    }

    static checkAdmin() {
        return axios.get('/api/is_admin')
            .then(response => response)
            .catch(error => error);
    }

    static getCountryList(params) {
        return axios.get('/api/countries', params)
            .then(response => response)
            .catch(error => error);
    }
}
