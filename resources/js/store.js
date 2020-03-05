import Vue from 'vue';
import Vuex from 'vuex'
import * as Cookies from 'js-cookie';
import storeLoading from '@/modules/loading/store';
import storeAuth from '@/modules/auth/store';
import storeUser from '@/modules/users/store';
import storeExtDomain from '@/modules/ext_domain/store';
import storeIntDomain from '@/modules/int_domain/store';
import storeBackLink from '@/modules/backlink/store';
import storeHosting from '@/modules/hosting/store';
import storeLog from '@/modules/logs/store';
import storeDomain from '@/modules/domain/store';
import storeSystem from '@/modules/system/store';
import emailTemplateSystem from '@/modules/template_email/store';
import createPersistedState from 'vuex-persistedstate';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        storeAuth,
        storeUser,
        storeLoading,
        storeExtDomain,
        storeIntDomain,
        storeBackLink,
        storeHosting,
        storeLog,
        storeDomain,
        storeSystem,
        emailTemplateSystem
    },
    plugins: [createPersistedState({
        paths: [
            'storeAuth.token',
            'storeAuth.currentUser'
        ],
    })],
});
