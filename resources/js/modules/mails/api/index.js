import axios from 'axios';

export default class MailServices {

    static sendEmail(param){
        return axios.post('/api/mail/send', param, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        .then(response => response)
        .catch(error => error);
    }

    static getEmailSignatureList(params) {
        return axios.get('/api/mail/get-signature-list', params)
            .then(response => response)
            .catch(error => error);
    }

    static addEmailSignature(params) {
        return axios.post('/api/mail/add-signature', params)
            .then(response => response)
            .catch(error => error);
    }

    static updateEmailSignature(params) {
        return axios.put('/api/mail/update-signature', params)
            .then(response => response)
            .catch(error => error);
    }

    static getAutoReply(params) {
        return axios.post('/api/mail/get-auto-reply', params)
            .then(response => response)
            .catch(error => error);
    }

    static addAutoReply(params) {
        return axios.post('/api/mail/add-auto-reply', params)
            .then(response => response)
            .catch(error => error);
    }

    static updateAutoReply(params) {
        return axios.put('/api/mail/update-auto-reply', params)
            .then(response => response)
            .catch(error => error);
    }

    static getAutoReplyState() {
        return axios.get('/api/mail/get-auto-reply-state')
            .then(response => response)
            .catch(error => error);
    }
}
