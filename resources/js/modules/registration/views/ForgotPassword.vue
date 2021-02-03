<template>
    <div class="homepage-login">

        <div class="row justify-content-center">

            <div class="col-md-5">
                <div class="card mt-5 pb-3">
                    <div class="card-body mx-5">
                        <h4>Forgot your Password ?</h4> 
                        <hr class="mb-4"/>

                        <div class="alert alert-info" >
                            Enter your email address and we'll send you a link to reset your password.
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Email Address</label>
                                    <input type="text" class="form-control" v-model="email" aria-describedby="helpId" placeholder="e.g. email@domain.com">
                                </div>
                            </div>

                            <div class="col-md-12 mt-4">
                                <button type="submit" class="btn btn-primary btn-flat btn-block" @click="submitResetPassword">Reset Password</button>
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
            }
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
                            'Resetting password has been send, Please check now your email',
                            'success'
                        );

                        this.email = '';
                    })
                    .catch(err => {
                        if(err.response.data.success === false) {
                            swal.fire(
                                'Invalid',
                                'Email Address doesn\'t exist, Please check again your email address',
                                'error'
                            );
                        }
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