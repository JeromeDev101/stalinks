import CountryService from '@/modules/country/api';

const COUNTRY_SET_COUNTRY = 'COUNTRY_SET_COUNTRY';
const COUNTRY_SET_ERROR = 'COUNTRY_SET_ERROR';

const state = {
    country: {},
    error: {},
};

const mutations = {

    [COUNTRY_SET_COUNTRY](state, country) {
        state.country = country;
    },

    [COUNTRY_SET_ERROR](state, error) {
        state.error = error;
    },
};

const actions = {
};
const storeCountry = {
    state,
    actions,
    mutations,
};
export default storeCountry;