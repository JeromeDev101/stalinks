<template>
    <div class="homepage-login">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card mt-5 pb-3">
                    <div class="card-body mx-5">

                        <!-- Order Confimation -->
                        <div>
                            <center class="my-3">
                                <i class="far fa-check-circle fa-10x text-success"></i>
                            </center>
                            <h3 class="text-center my-5">Successfully Cancelled ordered!</h3>

                            <button class="btn btn-default btn-lg btn-block" @click="redirectToLogin">Go to Login</button>
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
                //
            }
        },

        beforeMount() {
            this.verifyId();
        },

        created() {
            //
        },

        computed: {
            ...mapState({
                messageForms: state => state.storeAccount.messageForms,
            }),
        },

        methods: {
            verifyId() {
                axios.get('/api/cancel-order-confirmation-get-info',{
                    params: {
                        code: this.$route.params.id
                    }
                }).then((res) => {
                    if(!res.data.success) {
                        this.redirectToLogin();
                    }
                })
            },

            redirectToLogin() {
                window.location.assign('http://' + window.location.hostname + '/login');
            },
        }
    }
</script>