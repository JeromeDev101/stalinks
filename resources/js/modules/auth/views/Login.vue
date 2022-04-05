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

            <div class="card custom-card px-5 pb-5 pt-3">
                <h2 class="text-center">
                    <img style="height: auto; width: 320px;" src="../../../../images/stalinks2.png" alt="User Image">
                </h2>

                <hr/>

                <div class="row">
                    <div class="col-md-6 d-none d-lg-block d-xl-block">
                        <div class="h-100 p-4 mr-5 bg1 text-white">
                            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <h4 class="text-yellow">The First Backlink Desk Management in South Asia</h4>
                                        <br>
                                        <p>
                                            StaLinks is a Digital Marketplace that provides profitable guest posts, backlinks to increase your Google ranking.
                                        </p>
                                        <p>
                                            We connect verified buyers and publishers worldwide with competitive prices, quality, and safety.
                                        </p>
                                    </div>
                                    <div class="carousel-item">
                                        <h4 class="text-yellow">How does it work</h4>
                                        <p>
                                            Our system takes the hassle out of buying and selling links. We made it for you to be fast, easy and secure for whatever you are buyer or seller!  You are no longer have to worry about managing the links you order or finding the right URL’s.
                                        </p>
                                        <br>
                                        <h4 class="text-yellow">Buy Guest Post</h4>
                                        <p>
                                            Search from our huge inventory of sites using special filters in just a second you will find the best place for your guest-post. Soon we will give you the options to create your project and our team will do the job for you.
                                        </p>
                                    </div>
                                    <div class="carousel-item">
                                        <h4 class="text-yellow">Sell Guest Post</h4>
                                        <p>
                                            Earn a monthly income from your new articles. Add your sites to our list of publishers and our system will automatically create and monitor the orders for you. Just seat back to received your money.
                                        </p>
                                        <br>
                                        <h4 class="text-yellow">Content Writing</h4>
                                        <p>
                                            Writers will deliver your quality “SEO” content. From all around the world we pay them to deliver the best article with native language verified by our quality control team.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a> -->
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mt-3">
                            <form @submit.prevent="submitLogin(credentials)">
                                <h6 class="text-center mb-3">
                                    {{ $t('message.login.l3') }} 
                                    <button type="button" @click="registrationPage" class="btn btn-link pt-0 pl-0">{{ $t('message.login.b1') }}</button>
                                </h6>

                                <div class="input-group mb-4">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-envelope"></i>
                                        </span>
                                    </div>
                                    <input :class="{ 'form-control': true, 'is-invalid': error && objectNotEmpty(error.email)}" :placeholder="$t('message.login.l1')" v-model="credentials.email" autofocus="autofocus" type="email" id="user-email" autocomplete="off">
                                    <span v-if="error && objectNotEmpty(error.email)" class="text-danger">{{ error.email[0] }}</span>
                                </div>
                                

                                <div class="input-group mb-5">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-lock"></i>
                                        </span>
                                    </div>
                                    <input v-bind:class="{ 'is-invalid': error && objectNotEmpty(error.password)}" :placeholder="$t('message.login.l2')" v-model="credentials.password" type="password" id="user-password" class="form-control">
                                    <span v-if="error && objectNotEmpty(error.password)"  class="text-danger">{{ error.password[0] }}</span>
                                    <span v-if="error.message"  class="text-danger">{{ error.message }}</span>
                                </div>


                                <div class="form-group mb-4">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block"><span><b>{{ $t('message.login.b2') }}</b></span></button>
                                </div>

                                <div class="form-group mb-5">
                                    <ul style="padding: 0; margin: 0; text-align: center !important;">
                                        <li style="display: inline-block; margin: 0 10px 0 10px">
                                            <a target="_blank" href="https://www.facebook.com/StaLinksGuestPost">
                                                <span class="fab fa-facebook fa-lg text-default"></span>
                                            </a>
                                        </li>
                                        <li style="display: inline-block; margin: 0 10px 0 10px">
                                            <a target="_blank" href="https://www.linkedin.com/company/stalinks">
                                                <span class="fab fa-linkedin fa-lg text-default"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                            </form>
                            <div class="card-footer text-right">
                                <router-link :to="{ path: '/forgot-password' }">
                                    <span>{{ $t('message.login.forgot') }}</span>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div> 

                
            </div>
        </div>
    </div>
</template>

<style scoped>
    .custom-card {
        width: 1000px;
    }

    .bg1 {
        background-image: url("../../../../images/background-login2.jpg");
        background-color: #cccccc;
    }

    .text-yellow {
        color: FEA02F;
    }
</style>

<script>
import { mapState, mapGetters } from 'vuex';

export default {
    name: 'Login',
    data() {
        return {
            credentials: {
                'email': '',
                'password': '',
            },

            pageLanguage : this.$i18n.locale ? this.$i18n.locale : 'en',
        };
    },

    watch : {
        pageLanguage(newvalue, oldValue) {
            this.$i18n.locale = newvalue;
        },
    },

    computed: {
        ...mapState({
            isLoading: state => state.storeAuth.loading,
            error: state => state.storeAuth.error,
            token: state => state.storeAuth.token,
        }),

        ...mapGetters({
            isLoggedIn: 'isLoggedIn',
        }),
    },

    beforeMount() {
        if (this.isLoggedIn) {
            return this.$router.push({
                path: '/',
            });
        }
    },

    methods: {
        registrationPage() {
            this.$router.push('registration');
        },
        async submitLogin() {
            await this.$store.dispatch('login', {
                vue: this,
                params: this.credentials,
            });
        },

        objectNotEmpty(object) {
            return !_.isEmpty(object);
        },
    },
}
</script>
