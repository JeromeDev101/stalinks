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

                <!-- If auto reply is set to on -->
                <div v-if="autoReplyState" class="alert alert-primary m-2 mb-0" role="alert">
                    <i class="fa fa-info-circle"></i>
                    AUTO REPLY is active. All inbound emails will be automatically replied. Click

                    <router-link :to="{path:'/mails/auto', query: {label_id : $route.query.label_id ? $route.query.label_id : null } }">
                        here
                    </router-link>

                    to go to auto replies page.
                </div>

                <!-- If affiliate and code is not set -->
                <div v-if="!isAffiliateCodeSet && user.role_id === 11" class="alert alert-info m-2 mb-0" role="alert">
                    <i class="fa fa-info-circle"></i>
                    AFFILIATE CODE is not set. Generate your code now and start inviting buyers by clicking

                    <router-link :to="{ path:'/campaign-setup'}">
                        here
                    </router-link>

                    .
                </div>

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
import {mapState, mapGetters, mapActions} from 'vuex';
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
            user : state => state.storeAuth.currentUser,
            token: state => state.storeAuth.token,
            mainLoading: state => state.storeLoading.mainLoading,
            errorPage: state => state.storeLoading.errorPage,
            autoReplyState: state => state.storeMailgun.autoReplyState,
            isAffiliateCodeSet: state => state.storeAuth.isAffiliateCodeSet,
        })
    },

    async created() {
        if (this.token) {
            const tokenType = this.token.token_type;
            const accessToken = this.token.access_token;

            ConfigAxios.setAuthorizationHeader(tokenType, accessToken);

            this.getAutoReplyState();
            this.getAffiliateCodeSet();
        }
    },

    methods: {
        ...mapActions({
            getAutoReplyState : "getAutoReplyState",
            getAffiliateCodeSet : "getAffiliateCodeSet",
        }),
    }

};
</script>

