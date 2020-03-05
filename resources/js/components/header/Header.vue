<template>
    <header class="main-header">
        <!-- Logo -->
        <a href="#" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>A</b>LT</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>RLink </b>System</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img :src="user.avatar" class="user-image" alt="User Image">
                        <span class="hidden-xs">{{ user.name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img :src="user.avatar" class="img-circle" alt="User Image">
                                <p>{{ user.name }}</p>
                            </li>
                            <!-- Menu Body -->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <router-link  :to="{ path: `/profile/${user.id}` }">
                                    <a href="#" class="btn btn-default btn-flat">Profile</a>
                                    </router-link>
                                </div>
                                <div class="pull-right">
                                    <a href="#" @click="logoutAndRedirect" class="btn btn-default btn-flat">Sign out</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <!-- <li class="dropdown user user-menu">
                        <a href="#">
                        <img :src="user.avatar" class="user-image" alt="User Image">
                            <span class="hidden-xs">{{ user.name }}</span>
                        </a>
                        </li> -->
                </ul>
            </div>
        </nav>
    </header>
</template>

<script>
import { mapActions, mapState } from 'vuex';
import Cookies from 'js-cookie';
export default {
    name: 'AppHeader',

    computed: {
        ...mapState({
            user: state => state.storeAuth.currentUser,
        })
    },

    methods: {
        ...mapActions({
            logout: 'auth/logout',
        }),

        async logoutAndRedirect() {
            await this.$store.dispatch('logout')
            localStorage.clear();
            Cookies.remove('vuex');
            this.$router.push({ name: 'login' });
        },
    }
}
</script>
