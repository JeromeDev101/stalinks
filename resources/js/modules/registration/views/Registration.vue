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

        <div class="container">
        <div class="row">

            <div class="col-md-8 d-none d-lg-block d-xl-block align-middle">
                <div class="pr-5" v-if="RegisterModel.type == '' || RegisterModel.type == 'Affiliate'">
                    <h1 class="text-yellow display-4 font-weight-bold">{{ $t('message.registration.banner_1') }}</h1>
                    <br>
                    <p class="text-white lead custom-text">
                        {{ $t('message.registration.banner1_text1') }}
                    </p>
                    <p class="text-white lead custom-text">
                        {{ $t('message.registration.banner1_text2') }}
                    </p>
                </div>

                <div class="pr-5" v-if="RegisterModel.type == 'Seller'">
                    <h1 class="text-yellow display-4 font-weight-bold">{{ $t('message.registration.banner_2') }}</h1>
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
                    <h1 class="text-yellow display-4 font-weight-bold">{{ $t('message.registration.banner_3') }}</h1>
                    <br>
                    <p class="text-white lead custom-text">
                        <img src="/images/icon_registration/register-new.png" alt="">
                        {{ $t('message.registration.banner3_text1') }}
                    </p>
                    <br>
                    <p class="text-white lead custom-text">
                        <img src="/images/icon_registration/order-1.png" alt="">
                        {{ $t('message.registration.banner3_text2') }}
                    </p>
                    <br>
                    <p class="text-white lead custom-text">
                        <img src="/images/icon_registration/follow-up-1-1.png" alt="">
                        {{ $t('message.registration.banner3_text3') }}
                    </p>
                    <br>
                </div>

                <div class="pr-5" v-if="RegisterModel.type == 'Writer'">
                    <h1 class="text-yellow display-4 font-weight-bold">{{ $t('message.registration.banner_4') }}</h1>
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

            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card pb-3 px-2 custom-card">
                            <div class="card-body">
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

                                    <div class="col-12" v-if="RegisterModel.type === 'Buyer'">
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
            }
        },

        created() {
            this.RegisterModel.type = this.$route.query.type ? this.$route.query.type:''
            this.isTypeDisabled = !!this.$route.query.type
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
