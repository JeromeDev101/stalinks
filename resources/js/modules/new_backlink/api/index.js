import axios from 'axios';

export default class BacklinkService {

    static getBacklink(params){
        return axios.get('api/backlinks', params)
            .then(response => response)
            .catch(error => error)
    }

}