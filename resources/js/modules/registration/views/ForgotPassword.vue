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
                        <h4>{{ $t('message.forgot.title') }}</h4>
                        <hr class="mb-4"/>

                        <div class="alert alert-info" >
                            {{ $t('message.forgot.info') }}
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ $t('message.forgot.lb') }}</label>
                                    <input
                                        v-model="email"
                                        type="text"
                                        class="form-control"
                                        placeholder="e.g. email@domain.com"
                                        aria-describedby="helpId">
                                </div>
                            </div>

                            <div class="col-md-12 mt-4">
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-flat btn-block"

                                    @click="submitResetPassword">
                                    {{ $t('message.forgot.b1') }}
                                </button>
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
            // this.getListCountry();
        },

        computed: {
            ...mapState({

            }),
        },

        methods: {
           submitResetPassword() {
               if( this.email != '' ) {
                   axios.post('/api/forgot-password',{
                        email: this.email,
                    })
                    .then((res) => {
                        swal.fire(
                            'Success',
                            'Reset password link has been sent. Please check your email.',
                            'success'
                        );

                        this.email = '';
                    })
                    .catch(err => {
                        swal.fire(
                            'Invalid',
                            err.response.data.error,
                            'error'
                        );
                    })
               } else {
                   swal.fire(
                       'Error',
                       'Please provide Email Address',
                       'error'
                   );
               }
           },
        }
    }
</script>
