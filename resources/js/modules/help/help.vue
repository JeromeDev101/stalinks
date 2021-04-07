<template>
    <div>
        <div v-if="$route.name == 'help'">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-header">

                            <div class="row">
                                <div class="col-md-12">

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

                                    <!-- Buyer -->
                                    <h4 class="bg-primary text-white p-2 mt-3">Buyer's Guide</h4>
                                    <ul>
                                        <li>How to buy links from stalinks</li>
                                        <li>How to track your progress</li>
                                        <li>Stalinks statuses</li>
                                    </ul>

                                    <!-- Sellers -->
                                    <h4 class="bg-primary text-white p-2 mt-3">Seller's Guide</h4>
                                    <p> 
                                        STALinks is a link building desk designed to optimize and follow up all your orders for links. We provide perfect and reliable solutions for SEO Linkbuilder. We connect thousands of different professionals from bloggers, SEO professionals, and webmasters in one reliable and easy-to-use platform. Whether you are interested in buying or selling backlink from your website, STA links has all the features you need. 
                                        How does STALinks can help you? 
                                        Backlink remains to be the highest and most important ranking factor. This the main reasons why many website owners buy backlinks. However, not easy to be reach out to sale backlinks and difficult to organize all your sales and finally get pay. That is where STA Links steps in. 
                                    </p>
                                    <ul>
                                        <li>
                                            <router-link :to="{ path: '/help/seller-guide-1' }">
                                                HELP 1. Learn about the dashboard
                                            </router-link>
                                        </li>
                                        <li>
                                            <router-link :to="{ path: '/help/seller-guide-2' }">
                                                HELP 2. Upload your list of urls with price and other criteria
                                            </router-link>
                                        </li>
                                        <li>
                                            <router-link :to="{ path: '/help/seller-guide-3' }">
                                                HELP 3. Follow up your orders
                                            </router-link>
                                        </li>
                                        <li>
                                            <router-link :to="{ path: '/help/seller-guide-4' }">
                                                HELP 4. GET Pay
                                            </router-link>
                                        </li>
                                    </ul>

                                    <!-- Writer -->
                                    <h4 class="bg-primary text-white p-2 mt-3">Writer's Guide</h4>
                                    <ul>
                                        <li>Stalinks writers’ guide</li>
                                    </ul>
                                        

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="$route.name != 'help'">
            <router-view></router-view>
        </div>
            
    </div>
</template>