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

        <div class="row px-5">
            <div class="col-md-8 d-none d-lg-block d-xl-block align-middle pl-5">
                <div class="pr-5 mb-4">
                    <img style="height: auto; width: 50%;" src="../../../../images/stalinks2.png" alt="User Image"></img>
                </div>

                <div class="pr-5" v-if="RegisterModel.type == '' || RegisterModel.type == 'Affiliate'">
                    <h1 class="display-4 font-weight-bold" style="color: #FEA02F">{{ $t('message.registration.banner_1') }}</h1>
                    <br>
                    <p class="text-white lead custom-text">
                        {{ $t('message.registration.banner1_text1') }}
                    </p>
                    <p class="text-white lead custom-text">
                        {{ $t('message.registration.banner1_text2') }}
                    </p>
                </div>

                <div class="pr-5" v-if="RegisterModel.type == 'Seller'">
                    <h1 class="display-4 font-weight-bold" style="color: #FEA02F">{{ $t('message.registration.banner_2') }}</h1>
                    <br>
                    <p class="text-white lead custom-text">
                        <img src="/images/icon_registration/register-new.png" alt="">
                        {{ $t('message.registration.banner2_text1') }}
                    </p>
                    <br>
                    <p class="text-white lead custom-text">
                        <img src="/images/icon_registration/upload-new.png" alt="">
                        {{ $t('message.registration.banner2_text2') }}
                    </p>
                    <br>
                    <p class="text-white lead custom-text">
                        <img src="/images/icon_registration/follow-up-1-1.png" alt="">
                        {{ $t('message.registration.banner2_text3') }}
                    </p>
                    <br>
                    <p class="text-white lead custom-text">
                        <img src="/images/icon_registration/earning-new.png" alt="">
                        {{ $t('message.registration.banner2_text4') }}
                    </p>
                    <br>
                </div>

                <div class="pr-5" v-if="RegisterModel.type == 'Buyer'">
                    <!-- <h1 class="text-yellow display-4 font-weight-bold">
                        {{ $t('message.registration.u_banner_3') }}
                    </h1> -->

                    <h1 class="display-4 font-weight-bold" style="color: #FEA02F">
                        {{ $t('message.registration.new_buyer_banner') }}
                    </h1>

                    <!-- <h5 class="mt-4" style="color: #E6E6E6">
                        {{ $t('message.registration.banner_3') }}
                    </h5> -->

                    <h5 class="mt-4" style="color: #E6E6E6">
                        {{ $t('message.registration.new_buyer_sub_banner') }}
                    </h5>

<!--                    <br>-->
<!--                    <p class="text-white lead custom-text">-->
<!--                        <img src="/images/icon_registration/register-new.png" alt="">-->
<!--                        {{ $t('message.registration.banner3_text1') }}-->
<!--                    </p>-->
<!--                    <br>-->
<!--                    <p class="text-white lead custom-text">-->
<!--                        <img src="/images/icon_registration/order-1.png" alt="">-->
<!--                        {{ $t('message.registration.banner3_text2') }}-->
<!--                    </p>-->
<!--                    <br>-->
<!--                    <p class="text-white lead custom-text">-->
<!--                        <img src="/images/icon_registration/follow-up-1-1.png" alt="">-->
<!--                        {{ $t('message.registration.banner3_text3') }}-->
<!--                    </p>-->
<!--                    <br>-->

                    <div class="row my-5">
                        <div style="width: 85% !important;">
                            <div class="subtitle-container">
                                <div class="box-icon-wrapper">
                                    <div class="icon-box">
                                        <span class="icon-box-wrapper">
                                            <i aria-hidden="true" class="fas fa-trophy"></i>
                                        </span>
                                    </div>

                                    <div class="text-box">
                                        <div class="font-weight-bold">
                                            <h2 style="color: #FEA02F !important; font-weight: 700 !important;">
                                                {{ $t('message.registration.u_banner3_text1') }}
                                            </h2>
                                        </div>

                                        <div class="text-box-content">
                                            <h5>
                                                {{ $t('message.registration.u_banner3_sub1') }}
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-5 justify-content-end">
                        <div style="width: 85% !important;">
                            <div class="subtitle-container">
                                <div class="box-icon-wrapper-right">
                                    <div class="icon-box">
                                        <span class="icon-box-wrapper">
                                            <i aria-hidden="true" class="fas fa-dollar-sign"></i>
                                        </span>
                                    </div>

                                    <div class="text-box">
                                        <div class="text-box-title">
                                            <h2 style="color: #FEA02F !important; font-weight: 700 !important;">
                                                {{ $t('message.registration.u_banner3_text2') }}
                                            </h2>
                                        </div>

                                        <div class="text-box-content">
                                            <h5>
                                                {{ $t('message.registration.u_banner3_sub2') }}
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div style="width: 85% !important;">
                            <div class="subtitle-container">
                                <div class="box-icon-wrapper">
                                    <div class="icon-box">
                                        <span class="icon-box-wrapper">
                                            <i aria-hidden="true" class="fas fa-lock"></i>
                                        </span>
                                    </div>

                                    <div class="text-box">
                                        <div class="text-box-title">
                                            <h2 style="color: #FEA02F !important; font-weight: 700 !important;">
                                                {{ $t('message.registration.u_banner3_text3') }}
                                            </h2>
                                        </div>

                                        <div class="text-box-content">
                                            <h5>
                                                {{ $t('message.registration.u_banner3_sub3') }}
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-5 justify-content-end">
                        <div style="width: 85% !important;">
                            <div class="subtitle-container">
                                <div class="box-icon-wrapper-right">
                                    <div class="icon-box">
                                        <span class="icon-box-wrapper">
                                            <i aria-hidden="true" class="fas fa-link"></i>
                                        </span>
                                    </div>

                                    <div class="text-box">
                                        <div class="text-box-title">
                                            <h2 style="color: #FEA02F !important; font-weight: 700 !important;">
                                                {{ $t('message.registration.u_banner3_text4') }}
                                            </h2>
                                        </div>

                                        <div class="text-box-content">
                                            <h5>
                                                {{ $t('message.registration.u_banner3_sub4') }}
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div style="width: 85% !important;">
                            <div class="subtitle-container">
                                <div class="box-icon-wrapper">
                                    <div class="icon-box">
                                        <span class="icon-box-wrapper">
                                            <i aria-hidden="true" class="fas fa-globe"></i>
                                        </span>
                                    </div>

                                    <div class="text-box">
                                        <div class="text-box-title">
                                            <h2 style="color: #FEA02F !important; font-weight: 700 !important;">
                                                {{ $t('message.registration.u_banner3_text5') }}
                                            </h2>
                                        </div>

                                        <div class="text-box-content">
                                            <h5>
                                                {{ $t('message.registration.u_banner3_sub5') }}
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pr-5" v-if="RegisterModel.type == 'Writer'">
                    <h1 class="display-4 font-weight-bold" style="color: #FEA02F">{{ $t('message.registration.banner_4') }}</h1>
                    <br>
                    <p class="text-white lead custom-text">
                        <img src="/images/icon_registration/register-new.png" alt="">
                        {{ $t('message.registration.banner4_text1') }}
                    </p>
                    <br>
                    <p class="text-white lead custom-text">
                        <img src="/images/icon_registration/start-writing.png" alt="">
                        {{ $t('message.registration.banner4_text2') }}
                    </p>
                    <br>
                    <p class="text-white lead custom-text">
                        <img src="/images/icon_registration/follow-up-1-1.png" alt="">
                        {{ $t('message.registration.banner4_text3') }}
                    </p>
                    <br>
                    <p class="text-white lead custom-text">
                        <img src="/images/icon_registration/earning-new.png" alt="">
                        {{ $t('message.registration.banner4_text4') }}
                    </p>
                    <br>
                </div>
            </div>

            <div class="col-md-4 pr-5 sticky-form">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card pb-3 px-2 custom-card">
                            <div class="card-body">
                                <div v-if="RegisterModel.type == 'Buyer'">
                                    <div class="text-center">
                                        <h3 style="color: #006A82; font-weight: 800;">
                                            {{ $t('message.registration.new_buyer_form_banner') }}
                                        </h3>
                                    </div>

                                    <div class="mt-3 p-2">
                                        <p style="color: #6A6B6F; font-size: 20px; font-weight: 600; text-align: left;">
                                            {{ $t('message.registration.new_buyer_form_sub_banner') }}
                                        </p>
                                    </div>
                                </div>

                                <h4>{{ $t('message.registration.title') }}</h4>
                                <hr class="mb-4"/>

                                <div class="alert alert-success" v-if="isVerifiedEmail">
                                    {{ $t('message.registration.alert') }}
                                </div>

                                <div class="row">

                                    <div class="col-md-12">
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.type}">
                                            <label>{{ $t('message.registration.l1') }} <span class="text-danger">*</span></label>
                                            <select name="" class="form-control" v-model="RegisterModel.type" :disabled="isTypeDisabled">
                                                <option value="">{{ $t('message.registration.ph1') }}</option>

                                                <option value="Seller">
                                                    {{ $t('message.registration.seller') }}
                                                </option>

                                                <option value="Buyer">
                                                    {{ $t('message.registration.buyer') }}
                                                </option>

                                                <option value="Writer">
                                                    {{ $t('message.registration.writer') }}
                                                </option>

                                                <option value="Affiliate">
                                                    {{ $t('message.registration.affiliate') }}
                                                </option>
                                            </select>
                                            <span v-if="messageForms.errors.type" v-for="err in messageForms.errors.type" class="text-danger">{{ err }}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.email}">
                                            <label>{{ $t('message.registration.l3') }} <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" v-model="RegisterModel.email" name="" aria-describedby="helpId" :placeholder="$t('message.registration.ph3')">
                                            <span v-if="messageForms.errors.email" v-for="err in messageForms.errors.email" class="text-danger">{{ err }}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.password}">
                                            <label>{{ $t('message.registration.l5') }} <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" v-model="RegisterModel.password" name="" aria-describedby="helpId" :placeholder="$t('message.registration.ph5')">
                                            <span v-if="messageForms.errors.password" v-for="err in messageForms.errors.password" class="text-danger">{{ err }}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.c_password}">
                                            <label>{{ $t('message.registration.l6') }} <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" v-model="RegisterModel.c_password" name="" aria-describedby="helpId" :placeholder="$t('message.registration.ph6')">
                                            <span v-if="messageForms.errors.c_password" v-for="err in messageForms.errors.c_password" class="text-danger">{{ err }}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.username}">
                                            <label>{{ $t('message.registration.l2') }} <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" v-model="RegisterModel.username" name="" aria-describedby="helpId" :placeholder="$t('message.registration.ph2')">
                                            <span v-if="messageForms.errors.username" v-for="err in messageForms.errors.username" class="text-danger">{{ err }}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.name}">
                                            <label>{{ $t('message.registration.l4') }} <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" v-model="RegisterModel.name" name="" aria-describedby="helpId" :placeholder="$t('message.registration.ph4')">
                                            <span v-if="messageForms.errors.name" v-for="err in messageForms.errors.name" class="text-danger">{{ err }}</span>
                                        </div>
                                    </div>

                                    <div class="col-12" v-if="RegisterModel.type === 'Buyer' && !isAffiliateCodeHidden">
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.affiliate_code}">
                                            <label class="mb-0">{{ $t('message.registration.l7') }}</label>
                                            <br>
                                            <small class="font-italic text-secondary">
                                                <i class="fa fa-info-circle"></i>
                                                {{ $t('message.registration.info1') }}
                                            </small>
                                            <input
                                                v-model="RegisterModel.affiliate_code"
                                                type="text"
                                                class="form-control mt-2"
                                                :placeholder="$t('message.registration.ph7')">

                                            <span
                                                v-if="messageForms.errors.affiliate_code"
                                                v-for="err in messageForms.errors.affiliate_code"
                                                class="text-danger">
                                                {{ err }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-4">
                                        <p>
                                            <input type="checkbox" v-model="BtnAccept" @change="isEnableSubmit =  isEnableSubmit ? false:true">
                                            {{ $t('message.registration.term1') }}
                                            <a href="#" data-toggle="modal" data-target="#modalTermsAndCondition">
                                                {{ $t('message.registration.term2') }}
                                            </a>
                                        </p>
                                    </div>

                                    <div class="col-md-12">
                                        <span>{{ $t('message.registration.login1') }} </span>
                                        <router-link :to="{ path: '/login' }">
                                            {{ $t('message.registration.login2') }}
                                        </router-link>
                                    </div>

                                    <div class="col-md-12 mt-4">
                                        <button @click="submitForm" :disabled="isEnableSubmit" type="submit" class="btn btn-primary btn-lg btn-block">{{ $t('message.registration.b') }} <i class="fa fa-refresh fa-spin" v-if="isPopupLoading" ></i></button>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <terms-and-conditions></terms-and-conditions>
    </div>
</template>


<style scoped>
    .custom-card {
        box-shadow: -3px 41px 40px -13px rgba(0,0,0,0.53);
        -webkit-box-shadow: -3px 41px 40px -13px rgba(0,0,0,0.53);
        -moz-box-shadow: -3px 41px 40px -13px rgba(0,0,0,0.53);
    }

    .text-white { text-shadow: -3px 3px 4px rgba(0,0,0,0.71); }

    .text-yellow {
        color: FEA02F;
    }

    .custom-text { font-size: 2.5em; }

    h1 { text-shadow: -3px 3px 4px rgba(0,0,0,0.71); }

    .subtitle-container {
        background: linear-gradient(to right, rgba(33, 130, 150, 0.1), rgba(33, 130, 150,0.2)) !important;
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);

        padding: 20px 20px 20px 20px;
        background-color: #2B6ABA;
        border-radius: 99px 99px 99px 99px;
        box-shadow: 0px 0px 20px 0px rgb(0 0 0 / 50%);
    }

    .subtitle-container .box-icon-wrapper {
        display: flex;
        text-align: left;
        flex-direction: row;
        align-items: center;
    }

    .subtitle-container .box-icon-wrapper-right {
        display: flex;
        text-align: right;
        flex-direction: row-reverse;
        align-items: center;
    }

    .subtitle-container .icon-box {
        margin-right: var(--icon-box-icon-margin,15px);
        margin-left: 0;
        margin-bottom: unset;
    }

    .subtitle-container .box-icon-wrapper-right .icon-box {
        margin-left: var(--icon-box-icon-margin,15px);
        margin-right: 0;
        margin-bottom: unset;
    }

    .icon-box .icon-box-wrapper {
        border-radius: 50%;
        background-color: #FFFFFF;
        fill: #218296;
        color: #218296;
        padding: .4em;

        display: inline-block;
        line-height: 1;
        transition: all .3s;
        font-size: 50px;
        text-align: center;
    }

    .icon-box .icon-box-wrapper i {
        width: 1em;
        height: 1em;
        position: relative;
    }

    .text-box-title {
        color: white;
    }

    .text-box-content {
        color: #E6E6E6;
        font-weight: 400;
    }

    .text-box-content p {
        margin-bottom: 0;
    }
    .sticky-form {
        position: sticky;
        top: 20px;
        bottom: 20px;
        height: calc(100% - 40px);
        margin-bottom: 20px;
    }
</style>

<script>
    import { mapState } from 'vuex';
    import axios from 'axios';
    import TermsAndConditions from "../../../components/terms/TermsAndConditions";

    export default {
        components: {TermsAndConditions},
        data() {
            return {
                accountType: ['Seller', 'Buyer', 'Writer', 'Affiliate'],
                RegisterModel: {
                    username: '',
                    name: '',
                    email: '',
                    phone: '',
                    company_name: '',
                    type: '',
                    // company_type: 'Company',
                    country_id: '',
                    affiliate_code: '',
                },

                isPopupLoading: false,
                isVerifiedEmail: false,
                isCompany: true,
                isCompanySelected: false,
                isEnableSubmit: true,
                // countryList: [],
                BtnAccept: false,
                pageLanguage : this.$i18n.locale ? this.$i18n.locale : 'en',
                isTypeDisabled: false,
                isAffiliateCodeHidden: false,
            }
        },

        created() {
            if(this.$route.query.type === 'Seller' ||
            this.$route.query.type === 'Buyer' ||
            this.$route.query.type === 'Writer' ||
            this.$route.query.type === 'Affiliate'
            ) {
                this.RegisterModel.type = this.$route.query.type
                this.isTypeDisabled = true
            } else {
                this.RegisterModel.type = ''
                this.isTypeDisabled = false
            }

            this.RegisterModel.affiliate_code = this.$route.query.code ? this.$route.query.code : ''
            this.isAffiliateCodeHidden = !!this.$route.query.code

            if (this.$route.query.type && this.$route.query.code) {
                if (this.$route.query.type === 'Buyer') {
                    window.dataLayer = window.dataLayer || [];
                    window.dataLayer.push({
                        'event': 'buyerLanded',
                        'affiliateCode': this.$route.query.code,
                        'pageUrl': window.location.href
                    });
                }
            }
        },

        computed: {
            ...mapState({
                messageForms: state => state.storeAccount.messageForms,
            }),
        },

        watch : {
            pageLanguage(newvalue, oldValue) {
                this.$i18n.locale = newvalue;
            },
        },

        methods: {
            loginPage() {
                this.$router.push('login')
            },

            async submitForm() {
                this.isPopupLoading = true;
                await this.$store.dispatch('actionRegister', this.RegisterModel);
                this.isPopupLoading = false;

                if (this.messageForms.action === 'registration_success') {

                    // Send Email Validation
                    axios.post('/api/registration-email-validation',{
                        email: this.RegisterModel.email,
                        title: 'Email Validation'
                    });

                    this.clearRegistrationModel();
                    this.isVerifiedEmail = true;
                    this.isEnableSubmit = true;
                    this.BtnAccept = false;

                    if (this.$route.query.type && this.$route.query.code) {
                        if (this.$route.query.type === 'Buyer') {
                            this.$router.push({ path: '/registration-successful', query: { type: 'Buyer', code: this.$route.query.code } })
                        } else {
                            this.$router.push({ path: '/registration-successful'})
                        }
                    } else {
                        this.$router.push({ path: '/registration-successful'})
                    }
                }
            },

            clearRegistrationModel() {
                this.RegisterModel = {
                    username: '',
                    name: '',
                    email: '',
                    phone: '',
                    company_name: '',
                    // company_type: 'Company',
                    type: '',
                    country_id: '',
                    affiliate_code: ''
                }
            },
        }
    }
</script>
