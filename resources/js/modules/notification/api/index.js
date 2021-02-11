import axios from 'axios';

export default class NotificationService {
    static getNotifications(id) {
        return axios.get('/api/notifications/' + id)
            .then(response => response)
            .catch(error => error);
    }

    static seenNotifications(id) {
        return axios.put('/api/notifications/' + id)
            .then(response => response)
            .catch(error => error);
    }
}
