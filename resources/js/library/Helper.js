import Cookies from 'js-cookie';

export default {
    handleError(vue, statusCode) {
        if (statusCode === 401) {
            localStorage.clear();
            Cookies.remove('vuex');

            return window.location.href = '/login';
        }
        vue.$store.dispatch('setErrorPage', {
            vue: this,
            error: {
                status: true,
                code: statusCode,
            },
        });
    },

    arrayNotEmpty(array)
    {
        let lengthArray = array ? array.length : null
        if (lengthArray) {
            return true;
        }

        return false;
    }
}