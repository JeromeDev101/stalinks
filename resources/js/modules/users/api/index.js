import axios from 'axios';

export default class UserService {

    /**
     * Return list User
     *
     * @returns {AxiosPromise}
     *
     */
    static getTeams(params) {
        return axios.get('/api/users', params)
            .then(response => response)
            .catch(error => error);
    }

    /**
     *
     * @returns {AxiosPromise<any>}
     */
    static getUserInformation(id) {
        return axios.get(`/api/users/${id}`)
            .then(response => response)
            .catch(error => error);
    }

    static getListPayment(params) {
        return axios.get('api/payment-list', params)
            .then(response => response)
            .catch(error => error);
    }

    static getCountryList(params) {
        return axios.get('/api/countries', params)
            .then(response => response)
            .catch(error => error);
    }

    static getIntList(params) {
        return axios.get('/api/int', params)
            .then(response => response)
            .catch(error => error);
    }

    static getUserTypeList() {
        return axios.get('/api/user/type')
            .then(response => response)
            .catch(error => error);
    }

    static getRoleList() {
        return axios.get('/api/roles')
            .then(response => response)
            .catch(error => error);
    }

    static addUser(user) {
        return axios.post('/api/admin/add-user', user)
            .then(response => response)
            .catch(error => error);
    }

    static updateUser(user) {
        return axios.put('/api/admin/update-user', user)
            .then(response => response)
            .catch(error => error);
    }

    static updateUserPermission(params) {
        return axios.put('/api/admin/update-user-permission', params)
            .then(response => response)
            .catch(error => error);
    }

    static updateUserIntPermission(params) {
        return axios.put('/api/admin/update-user-int-permission', params)
            .then(response => response)
            .catch(error => error);
    }
}
