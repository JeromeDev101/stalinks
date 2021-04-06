<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">

                    <div class="row">
                        <div class="col-md-6">

                            <!-- Guide -->
                            <h4 class="bg-primary text-white p-2 mt-3">Guide Manual</h4>
                            <ul>
                                <li>Loging into your account</li>
                                <li>Creating email templates </li>
                                <li>Adding new urls to prospects list</li>
                                <li>Contacting url’s prospects</li>
                                <li>Registering an account</li>
                                <li>Navigating the dashboard</li>
                                <li>Uploading url’s to publisher list</li>
                            </ul>
                            <button class="btn btn-sm btn-primary">Download</button>

                            <!-- Buyer -->
                            <h4 class="bg-primary text-white p-2 mt-3">Buyer's Guide</h4>
                            <ul>
                                <li>How to buy links from stalinks</li>
                                <li>How to track your progress</li>
                                <li>Stalinks statuses</li>
                            </ul>
                            <button class="btn btn-sm btn-primary">Download</button>

                            <!-- Sellers -->
                            <h4 class="bg-primary text-white p-2 mt-3">Seller's Guide</h4>
                            <ul>
                                <li>Learn about the dashboard</li>
                                <li>Upload your list of urls with price and other criteria</li>
                                <li>Follow up your orders</li>
                                <li>GET Pay</li>
                            </ul>
                            <button class="btn btn-sm btn-primary ">Download</button>

                            <!-- Writer -->
                            <h4 class="bg-primary text-white p-2 mt-3">Writer's Guide</h4>
                            <ul>
                                <li>Stalinks writers’ guide</li>
                            </ul>
                            <button class="btn btn-sm btn-primary">Download</button>
                                

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    export default {
        data() {
            return {
                //
            }
        },

        async created() {
            //
        },

        computed: {
            ...mapState({
                user: state => state.storeAuth.currentUser,
            }),

        },

        methods: {
            //
        },
    }
</script>
