<template>
    <div class="homepage-login">

        <!-- language -->
        <div class="row p-2">
            <div class="col-12 d-flex justify-content-end">
                <div>
                    <button
                        type="button"
                        class="btn btn-default btn-sm dropdown-toggle"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false">

                        <i
                            class="flag-icon"
                            :class="pageLanguage === 'en'
                                ? 'flag-icon-us'
                                    : pageLanguage === 'ind'
                                    ? 'flag-icon-in'
                                        : 'flag-icon-' + pageLanguage">
                        </i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right p-0">
                        <a
                            href="#"
                            class="dropdown-item"
                            :class="{'active' : pageLanguage === 'en'}"

                            @click="pageLanguage = 'en'">

                            <i class="flag-icon flag-icon-us mr-2"></i>
                            English
                        </a>
                        <a
                            href="#"
                            class="dropdown-item"
                            :class="{'active' : pageLanguage === 'jp'}"

                            @click="pageLanguage = 'jp'">
                            <i class="flag-icon flag-icon-jp mr-2"></i>
                            Japanese
                        </a>
                        <a
                            href="#"
                            class="dropdown-item"
                            :class="{'active' : pageLanguage === 'th'}"

                            @click="pageLanguage = 'th'">

                            <i class="flag-icon flag-icon-th mr-2"></i>
                            Thai
                        </a>
                        <a
                            href="#"
                            class="dropdown-item"
                            :class="{'active' : pageLanguage === 'vn'}"

                            @click="pageLanguage = 'vn'">

                            <i class="flag-icon flag-icon-vn mr-2"></i>
                            Vietnamese
                        </a>
                        <a
                            href="#"
                            class="dropdown-item"
                            :class="{'active' : pageLanguage === 'ind'}"

                            @click="pageLanguage = 'ind'">

                            <i class="flag-icon flag-icon-in mr-2"></i>
                            Hindi
                        </a>
                        <a
                            href="#"
                            class="dropdown-item"
                            :class="{'active' : pageLanguage === 'id'}"

                            @click="pageLanguage = 'id'">

                            <i class="flag-icon flag-icon-id mr-2"></i>
                            Indonesian
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">

            <div class="col-md-5">
                <div class="card mt-5 pb-3">
                    <div class="card-body mx-5">
                        <div class="row">
                            <div class="col-12 text-center">
                                <h1 class="mb-4"><i class="fas fa-check-circle fa-3x text-success"></i></h1>
                                <h1 class="text-success">Registration Successful</h1>
                            </div>
                        </div>
                        <hr class="mb-4"/>

                        <div class="alert alert-success" >
                            Thank you for registering to StaLinks. To complete the registration process, please check your email inbox for the
                            confirmation link. If you do not see our confirmation email on your inbox, please check the 'All Mail' section or
                            the spam section.
                        </div>

                        <div class="row">
                            <div class="col-md-12 mt-4">
                                <router-link class="btn btn-primary btn-block" :to="{ path: '/login' }">
                                    Go to Login Page
                                </router-link>
                            </div>

                        </div>

                    </div>

                </div>
            </div>

        </div>

    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import axios from 'axios';

    export default {
        data() {
            return {
                email: '',
                pageLanguage : this.$i18n.locale ? this.$i18n.locale : 'en',
            }
        },

        watch : {
            pageLanguage(newvalue, oldValue) {
                this.$i18n.locale = newvalue;
            },
        },

        created() {
            if (this.$route.query.type && this.$route.query.code) {
                if (this.$route.query.type === 'Buyer') {
                    window.dataLayer = window.dataLayer || [];
                    window.dataLayer.push({
                        'event': 'buyerRegistered',
                        'affiliateCode': this.$route.query.code,
                        'pageUrl': window.location.href
                    });
                }
            }
        },

        computed: {
            ...mapState({

            }),
        },

        methods: {
            
        }
    }
</script>
