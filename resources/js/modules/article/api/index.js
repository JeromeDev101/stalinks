import axios from 'axios';

export default class ArticleService {

    static getListBacklinks(params) {
        return axios.get('api/backlinks-list', params)
            .then(response => response)
            .catch(error => error)
    }

    static getListArticleAdmin(params) {
        return axios.get('api/article-list-admin', params)
            .then(response => response)
            .catch(error => error)
    }

    static getListArticle(params) {
        return axios.get('api/article-list', params)
            .then(response => response)
            .catch(error => error)
    }

    static getListWriter(params) {
        return axios.get('api/writer-list', params)
            .then(response => response)
            .catch(error => error)
    }

    static addArticle(params) {
        return axios.post('api/articles', params)
            .then(response => response)
            .catch(error => error)
    }

    static updateContent(params) {
        return axios.post('api/articles-content', params)
            .then(response => response)
            .catch(error => error)
    }

    static getListCountries(params) {
        return axios.get('/api/countries', params)
            .then(response => response)
            .catch(error => error);
    }

}
