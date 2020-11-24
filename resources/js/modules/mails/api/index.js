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

}