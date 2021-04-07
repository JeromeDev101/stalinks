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
                            <p>STALINKS 
STALinks is a link building desk designed to optimize and follow up all your orders for links. We provide perfect and reliable solutions for SEO Linkbuilder. We connect thousands of different professionals from bloggers, SEO professionals, and webmasters in one reliable and easy-to-use platform. Whether you are interested in buying or selling backlink from your website, STA links has all the features you need. 
How does STALinks can help you? 
Backlink remains to be the highest and most important ranking factor. This the main reasons why many website owners buy backlinks. However, not easy to be reach out to sale backlinks and difficult to organize all your sales and finally get pay. That is where STA Links steps in. 
</p>
                            <ul>
                                <li>HELP 1. Learn about the dashboard</li>
                                <li>HELP 2. Upload your list of urls with price and other criteria</li>
                                <li>HELP 3. Follow up your orders</li>
                                <li>HELP 4. GET Pay</li>
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
