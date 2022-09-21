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

                        <!-- Set password Form -->
                        <div v-if="formSetPassword">
                            <h3>{{ $t('message.verification.title') }}</h3>
                            <hr class="mb-5"/>

                            <h4 class="text-primary">{{ $t('message.verification.sub1') }}</h4>
                            <hr/>
                            <div class="row">

                                <div class="col-12 col-md-12 col-lg-6 col-xl-6">
                                    <div class="form-group">
                                        <label>{{ $t('message.verification.l1') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" :disabled="true" v-model="regModel.type">
                                    </div>
                                </div>

                                <div class="col-12 col-md-12 col-lg-6 col-xl-6">
                                    <div class="form-group">
                                        <label>{{ $t('message.verification.l2') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" :disabled="true" v-model="regModel.username">
                                    </div>
                                </div>

                                <div class="col-12 col-md-12 col-lg-6 col-xl-6">
                                    <div class="form-group">
                                        <label>{{ $t('message.verification.l3') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" :disabled="true" v-model="regModel.name">
                                    </div>
                                </div>

                                <div class="col-12 col-md-12 col-lg-6 col-xl-6">
                                    <div class="form-group">
                                        <label>{{ $t('message.verification.l4') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" :disabled="true" v-model="regModel.email">
                                    </div>
                                </div>

                                <div class="col-12 col-md-12 col-lg-6 col-xl-6">
                                    <div class="form-group">
                                        <label>{{ $t('message.verification.l5') }}</label>
                                        <input type="text" class="form-control" v-model="regModel.phone">
                                    </div>
                                </div>

                                <div class="col-12 col-md-12 col-lg-6 col-xl-6">
                                    <div :class="{'form-group': true, 'has-error': messageForms.errors.phone}">
                                        <label>{{ $t('message.verification.l6') }} <span class="text-danger">*</span></label>
                                        <select class="form-control" v-model="regModel.company_type" @change="checkCompanyType()">
                                            <option value="Company">Company</option>
                                            <option value="Freelancer">Freelancer</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12" v-show="isCompany">
                                    <div :class="{'form-group': true, 'has-error': errorMessage.hasOwnProperty('company_name')}">
                                        <label>{{ $t('message.verification.l7') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" :placeholder="$t('message.verification.ph7')" v-model="regModel.company_name">
                                        <span v-show="errorMessage.hasOwnProperty('company_name')" class="text-danger">
                                            {{ $t('message.verification.e7') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-12 col-md-12 col-lg-6 col-xl-6">
                                    <div class="form-group">
                                        <label>{{ $t('message.verification.l8') }}</label>
                                        <input type="text" class="form-control" :placeholder="$t('message.verification.ph8')" v-model="regModel.skype">
                                    </div>
                                </div>

                                <div class="col-12 col-md-12 col-lg-6 col-xl-6">
                                    <div class="form-group">
                                        <label>{{ $t('message.verification.l11') }}</label>
                                        <input type="text" class="form-control" :placeholder="$t('message.verification.ph11')" v-model="regModel.facebook">
                                    </div>
                                </div>

                                <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                                    <div :class="{'form-group': true, 'has-error': errorMessage.hasOwnProperty('country_id')}">
                                        <label>{{ $t('message.verification.l9') }} <span class="text-danger">*</span></label>
                                        <select name="" class="form-control" v-model="regModel.country_id">
                                            <option value="">{{ $t('message.verification.ph9') }}</option>
                                                <option v-for="option in countryList" v-bind:value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                        <span v-show="errorMessage.hasOwnProperty('country_id')" class="text-danger">
                                            {{ $t('message.verification.e9') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-12" v-if="regModel.type == 'Writer'">
                                    <div class="form-group">
                                        <label>{{ $t('message.verification.l10') }} <span class="text-danger">*</span></label>
                                        <v-select
                                            multiple
                                            v-model="regModel.language_id"
                                            label="name"
                                            :options="listLanguages.data"
                                            :reduce="name => name.id"
                                            :searchable="true"
                                            :placeholder="$t('message.verification.ph10')"/>
                                        <span v-show="errorMessage.hasOwnProperty('language_id')" class="text-danger">
                                            {{ $t('message.verification.e10') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div v-if="regModel.type === 'Writer'" class="mt-4">
                                <h4 class="text-primary">Writer Pricing</h4>
                                <hr/>

                                <div class="alert alert-info">
                                    <p>{{ $t('message.registration_accounts.r_reminder_ppw') }}</p>
                                </div>

                                <div class="row">
                                    <div class="col-md-12" v-if="regModel.type === 'Writer'">
                                        <div class="form-group">
                                            <label>
                                                {{ $t('message.registration_accounts.r_pricing_type') }}
                                                <span class="text-danger">*</span>
                                            </label>

                                            <select class="form-control" v-model="regModel.rate_type">
                                                <option value="ppw">{{ $t('message.registration_accounts.r_ppw') }}</option>
                                                <option value="ppa">{{ $t('message.registration_accounts.r_ppa') }}</option>
                                            </select>

                                            <span
                                                v-if="errorMessage.rate_type"
                                                v-for="err in errorMessage.rate_type"
                                                class="text-danger">
                                                {{ err }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" v-if="regModel.type !== 'Affiliate'">

                                <div class="col-md-12">
                                    <h4 class="text-primary my-3">
                                        {{ $t('message.verification.sub2') }}
                                        <span class="text-danger" v-show="regModel.type != 'Buyer'">*</span>
                                    </h4>
                                </div>

                                <div class="col-md-12">
                                    <span v-show="errorMessage.hasOwnProperty('payment_type')" class="text-danger">
                                        {{ $t('message.verification.e11') }}
                                    </span>
                                </div>

                                <div class="col-md-12">
                                    <span v-show="errorMessage.hasOwnProperty('id_payment_type')" class="text-danger">
                                        {{ $t('message.verification.e12') }}
                                    </span>
                                </div>


                                <!-- payment for seller and writer -->
                                <table class="table" v-if="regModel.type === 'Seller' || regModel.type === 'Writer'">
                                    <tr>
                                        <td></td>
                                        <td>{{ $t('message.verification.sub3') }} </td>
                                    </tr>
                                    <tr v-for="(payment_method, index) in paymentMethodListSendPayment" :key="index">
                                        <td>
                                            <div class="form-group">
                                                <label>{{ payment_method.type }} {{ $t('message.verification.account') }}</label>
                                                <input type="text" class="form-control" v-model="regModel.payment_type[payment_method.id]">
                                            </div>

                                            <span
                                                v-if="errorMessage.hasOwnProperty('payment_type.'+ payment_method.id)"
                                                v-for="err in errorMessage['payment_type.'+ payment_method.id]"
                                                class="text-danger">

                                                {{ err }}
                                            </span>

                                            <div class="px-5"
                                                 v-show="payment_method.account_name ||
                                                payment_method.bank_name ||
                                                payment_method.swift_code ||
                                                payment_method.beneficiary_add ||
                                                payment_method.account_iban ||
                                                payment_method.account_holder ||
                                                payment_method.account_type ||
                                                payment_method.routing_num ||
                                                payment_method.wire_routing_num
                                            "
                                            >
                                                <h6 class="text-primary">Other Details:</h6>
                                                <hr/>

                                                <div class="row">
                                                    <div class="col-sm-12" v-show="payment_method.bank_name">
                                                        <label>Bank Name:</label>
                                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="regModel.bank_name[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.account_name">
                                                        <label>Account Name:</label>
                                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="regModel.account_name[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.account_iban">
                                                        <label>Account IBAN:</label>
                                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="regModel.account_iban[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.swift_code">
                                                        <label>SWIFT Code:</label>
                                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="regModel.swift_code[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.beneficiary_add">
                                                        <label>Beneficiary Address:</label>
                                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="regModel.beneficiary_add[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.account_holder">
                                                        <label>Account Holder:</label>
                                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="regModel.account_holder[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.account_type">
                                                        <label>Account Type:</label>
                                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="regModel.account_type[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.routing_num">
                                                        <label>Routing Number:</label>
                                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="regModel.routing_num[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.wire_routing_num">
                                                        <label>Wire Routing Number:</label>
                                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="regModel.wire_routing_num[payment_method.id]">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="width: 50px;vertical-align:middle;" class="text-center">
                                            <input type="radio" class="btn-radio-custom" name="payment_default" v-bind:value="payment_method.id" v-model="regModel.id_payment_type">
                                        </td>
                                    </tr>
                                </table>
                                <!-- end of payment for seller and writer -->

                                <!-- payment for buyer -->
                                <table class="table" v-if="regModel.type === 'Buyer'">
                                    <tr>
                                        <td></td>
                                        <td>{{ $t('message.verification.sub3') }}</td>
                                    </tr>
                                    <tr v-for="(payment_method, index) in paymentMethodListReceivePayment" :key="index">
                                        <td>
                                            <div class="form-group">
                                                <label>{{ payment_method.type }} {{ $t('message.verification.account') }}</label>
                                                <input type="text" class="form-control" v-model="regModel.payment_type[payment_method.id]">
                                            </div>

                                            <span
                                                v-if="errorMessage.hasOwnProperty('payment_type.'+ payment_method.id)"
                                                v-for="err in errorMessage['payment_type.'+ payment_method.id]"
                                                class="text-danger">

                                                {{ err }}
                                            </span>
                                        </td>
                                        <td style="width: 50px;vertical-align:middle;" class="text-center">
                                            <input type="radio" class="btn-radio-custom" name="payment_default" v-bind:value="payment_method.id" v-model="regModel.id_payment_type">
                                        </td>
                                    </tr>
                                </table>
                                <!-- end of payment for buyer -->
                            </div>

                            <div class="row">
                                <div class="col-md-12 mt-2">
                                    <button class="btn btn-primary btn-lg btn-block btn-flat my-2" @click="submitRegister">{{ $t('message.verification.b') }} <i class="fa fa-refresh fa-spin" v-if="isPopupLoading" ></i></button>
                                </div>
                            </div>
                        </div>

                        <!-- Verification Form -->
                        <div v-if="formVerified">
                            <center class="my-3">
                                <i class="far fa-check-circle fa-10x text-success"></i>
                            </center>
                            <h3 class="text-center my-5">{{ $t('message.verification.sub4') }}</h3>

                            <button class="btn btn-default btn-lg btn-block" @click="redirectToLogin">{{ $t('message.verification.sub5') }}</button>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .btn-radio-custom {
        transform: scale(2);
    }
</style>

<script>
    import { mapState } from 'vuex';
    import axios from 'axios';

    export default {
        data() {
            return {
                // verifyModel: {
                //     password: '',
                //     c_password: '',
                // },
                verificationCode: '' ,
                isPopupLoading: false,
                formVerified: false,
                formSetPassword: true,
                listLanguages: [],
                regModel: {
                    type: '',
                    username: '',
                    name: '',
                    email: '',
                    phone: '',
                    company_type: 'Company',
                    company_name: '',
                    skype: '',
                    facebook: '',
                    country_id: '',
                    paypal_account: '',
                    btc_account: '',
                    skrill_account: '',
                    id_payment_type: '',
                    writer_price: '',
                    rate_type: '',
                    payment_type: [],
                    bank_name: [],
                    account_name: [],
                    account_iban: [],
                    swift_code: [],
                    beneficiary_add: [],
                    account_holder: [],
                    account_type: [],
                    routing_num: [],
                    wire_routing_num: [],
                },
                countryList: [],
                errorMessage: [],
                isCompany: true,
                paymentMethodList: [],
                validate_error_type: false,

                pageLanguage : this.$i18n.locale ? this.$i18n.locale : 'en',
            }
        },

        beforeMount() {
            this.checkVerificationCode();
        },

        created() {
            this.getListCountry();
            this.getListPaymentMethod();
            this.getListLanguage();
        },

        computed: {
            ...mapState({
                messageForms: state => state.storeAccount.messageForms,
            }),

            paymentMethodListSendPayment: function() {
                return this.paymentMethodList.filter(i => i.send_payment === 'yes')
            },

            paymentMethodListReceivePayment: function() {
                return this.paymentMethodList.filter(i => i.receive_payment === 'yes')
            },
        },

        watch : {
            pageLanguage(newvalue, oldValue) {
                this.$i18n.locale = newvalue;
            },
        },

        methods: {
            getListLanguage() {
                axios.get('/api/registration-languages-list')
                    .then((res) => {
                        console.log(res)
                        this.listLanguages = res.data
                    })
            },

            getListPaymentMethod() {
                axios.get('/api/payment-list-registration')
                    .then((res) => {
                        this.paymentMethodList = res.data.data
                    })
            },

            checkCompanyType() {
                if(this.regModel.company_type == 'Company') {
                    this.isCompany = true;
                } else {
                    this.isCompany = false;
                }
            },

            getListCountry() {
                axios.get('/api/registration-country-list')
                    .then((res) => {
                        this.countryList = res.data.data;
                    })
            },

            getInfo() {
                axios.get('/api/registration-get-info',{
                    params: {
                        code: this.$route.params.code
                    }
                }).then((res) => {
                    this.regModel.type = res.data.type
                    this.regModel.username = res.data.username
                    this.regModel.name = res.data.name
                    this.regModel.email = res.data.email
                })
            },

            async checkVerificationCode() {
                await this.$store.dispatch('actionCheckVerificationCode', {
                    code: this.$route.params.code
                });

                // console.log(this.messageForms.action)

                if( !this.messageForms.action ){
                    this.redirectToLogin();
                } else {
                    this.getInfo();
                }
            },

            submitRegister() {
                let self = this;
                let id_payment_type = this.regModel.id_payment_type;

                if (this.regModel.payment_type[id_payment_type]) {
                    this.regModel.payment_type[id_payment_type] = this.regModel.payment_type[id_payment_type].replace(/\s/g,'');
                }

                //TODO: REMOVED VALIDATION FOR PAYMENT INFO - MIGHT CHANGE ACCORDING TO BOSS WHEN WE HAVE BUYERS
                // if(!this.regModel.payment_type[id_payment_type] || this.regModel.payment_type[id_payment_type] == '') {
                //     this.validate_error_type = true;
                //     return false;
                // } else {
                //     this.validate_error_type = false;
                // }

                axios.post('/api/registration-get-update-info', this.regModel)
                    .then((res) => {
                        this.formVerified = true;
                        this.formSetPassword = false;
                    })
                    .catch(err => {
                        swal.fire(
                            self.$t('message.registration_accounts.alert_error'),
                            self.$t('message.registration_accounts.alert_error_update'),
                            'error'
                        );

                        if(err.response.status == 422) {
                            this.errorMessage = err.response.data.errors;
                        }
                    })
            },

            // async submitRegister() {
            //     this.isPopupLoading = true
            //     await this.$store.dispatch('actionSetPassword', {
            //         code: this.$route.params.code,
            //         password: this.verifyModel.password,
            //         c_password: this.verifyModel.c_password,
            //     });
            //     this.isPopupLoading = false

            //     if( this.messageForms.action === 'success'){
            //         this.formVerified = true;
            //         this.formSetPassword = false;
            //     }
            // },

            redirectToLogin() {
                window.location.assign('http://' + window.location.hostname + '/login');
            },

            clearModel() {
                this.verifyModel = {
                    password: '',
                    c_password: '',
                }
            },
        }
    }
</script>
