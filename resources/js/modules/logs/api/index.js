import axios from 'axios';

export default class LogService {

    /**
     * Return list Logs
     *
     * @returns {AxiosPromise}
     *
     */
    static getLogs(params) {
        return axios.get('/api/admin/logs', params)
            .then(response => response)
            .catch(error => error);
    }

    /**
     * Return list MailLogs
     *
     * @returns {AxiosPromise}
     *
     */
    static getLogsMail(params) {
        return axios.get('/api/mail-logs', params)
            .then(response => response)
            .catch(error => error);
    }

    /**
     *
     * @returns {AxiosPromise<any>}
     */
    static getActions() {
        return axios.get('/api/admin/logs/actions')
            .then(response => response)
            .catch(error => error);
    }

    /**
     *
     * @returns {AxiosPromise<any>}
     */
    static getTables() {
        return axios.get('/api/admin/logs/tables')
            .then(response => response)
            .catch(error => error);
    }
}
