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
}
