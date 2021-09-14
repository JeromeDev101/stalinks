<template>
    <div>
        <div class="wrapper" v-if="!errorPage.status">
            <div class="splash-screen" v-show="mainLoading.show">
                <div class="splash-overlay"></div>
                <div class="splash-spinner"></div>
            </div>
            <app-header></app-header>
            <app-aside></app-aside>
            <div class="content-wrapper">
                <app-breadcrumd></app-breadcrumd>
                <section class="content">
                    <router-view></router-view>
                </section>
            </div>
            <app-footer></app-footer>
<!--            <app-control-siderbar></app-control-siderbar>-->
<!--            <div class="control-sidebar-bg"></div>-->
        </div>
        <div v-else>
            <error-page></error-page>
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex';
import ConfigAxios from '@/library/ConfigAxios';
import AppHeader from '@/components/header/Header';
import AppAside from '@/components/aside/views/Aside';
import AppBreadcrumd from '@/components/breadcrumb/Breadcrumb';
import AppFooter from '@/components/footer/Footer';
import AppControlSiderbar from '@/components/control_siderbar/ControlSiderbar';
import ErrorPage from '@/components/errors/ErrorPage';

export default {
    name: 'Admin',
    components: {
        AppHeader,
        AppAside,
        AppBreadcrumd,
        AppControlSiderbar,
        AppFooter,
        ErrorPage
    },

    computed: {
        ...mapState({
            token: state => state.storeAuth.token,
            mainLoading: state => state.storeLoading.mainLoading,
            errorPage: state => state.storeLoading.errorPage,
        })
    },

    async created() {
        if (this.token) {
            const tokenType = this.token.token_type;
            const accessToken = this.token.access_token;

            ConfigAxios.setAuthorizationHeader(tokenType, accessToken);
        }
    },

};
</script>

