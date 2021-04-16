<template>
    <div>
        <div v-if="$route.name == 'help'">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-header">

                            <div class="row">
                                <div class="col-md-12">

                                  <p>StaLinks is a link building company designed to cater to all business niche. We provide perfect and reliable solutions for both publishers and link advertisers. We connect thousands of different professionals from bloggers, SEO professionals and webmasters in one reliable and easy-to-use platform. Whether you are interested in buying or selling backlink from your website, STAlinks has all the features you need. </p>
                                
                                  <h4>How does StaLinks work?</h4>
                                <p>Backlink remains to be the highest and most important ranking factor of Google. This the main reasons why many website owners buy or sell backlinks. However, not all backlinks are safe. In fact, low quality links can do more harm than good to your website. That is where StaLinks steps in.</p>
<p>All the domains and pages on our platforms follow s industry practices. These pages were hand-picked and verified by our team. We are using reliable tools to generate SEO scores. We don’t allow any advertisers to post any pages that resemble spam links.</p>
                                    <!-- Buyer -->
                                    <h4 class="bg-primary text-white p-2 mt-3">Buyer's Guide</h4>
                                    <ul>
                                        <li>How to buy links from stalinks</li>
                                        <li>How to track your progress</li>
                                        <li>Stalinks statuses</li>
                                    </ul>

                                    <!-- Sellers -->
                                    <h4 class="bg-primary text-white p-2 mt-3">Seller's Guide</h4>
                                    
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
                                    <p>
                                        Writers are responsible for writing quality content that will become the backbone in building backlinks. The writer team will need to collaborate with the SEO team, Customer Service Sellers, and country managers to create the content for StaLinks clients. The writer must know how to research and write content based on various topics and industry trends that match the target website.
                                    </p>
                                   
                                    <ul>
                                        <li>
                                            <router-link :to="{ path: '/help/writer-guide-1' }">
                                                STEP 1. Register to StaLinks and setup your price
                                            </router-link>
                                        </li>
                                        <li>
                                            <router-link :to="{ path: '/help/writer-guide-2' }">
                                                STEP 2. Start writing content
                                            </router-link>
                                        </li>
                                        <li>
                                            <router-link :to="{ path: '/help/writer-guide-3' }">
                                                STEP 3. Follow up your income
                                            </router-link>
                                        </li>
                                        
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