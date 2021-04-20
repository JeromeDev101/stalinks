<template>
   <div>
      <div v-if="$route.name == 'help'">
         <div class="row">
            <div class="col-sm-12">
               <div class="box">
                  <div class="box-header">
                     <div class="row">
                        <div class="col-md-12 help-content">
                           <h4 class="bg-primary text-white p-3 mt-3 text-bold">Welcome on Stalinks</h4>
                           <p>StaLinks is a link building company designed to cater to all business niche. We provide perfect and reliable solutions for both publishers and link advertisers. We connect thousands of different professionals from bloggers, SEO professionals and webmasters in one reliable and easy-to-use platform. Whether you are interested in buying or selling backlink from your website, Stalinks has all the features you need. </p>
                           <p style="font-weight: 600;color: #497EB1; font-size:20px;">How does StaLinks work?</p>
                           <p>Backlink remains to be the highest and most important ranking factor of Google. This the main reasons why many website owners buy or sell backlinks. However, not all backlinks are safe. In fact, low quality links can do more harm than good to your website. That is where StaLinks steps in.</p>
                           <p> We are using reliable tools to generate SEO scores. We don’t allow any advertisers to post any pages that resemble spam links. All the domains and pages on our platforms follow s industry practices. These pages were hand-picked and verified by our team. We also provide you in advance urls and generate SEO scores from ahref.</p>
                           <!-- Buyer -->
                           <h4 class="bg-primary text-white p-2 mt-3 text-bold">Buyer's Guide</h4>
                           <p>
                              Take your site's ranking & traffic to the next level with us! Click on the required
                              guide to learn more. To climb up the Google's ladder and choose relevant backlink pages
                              for your links, benefit from Backlink Inventory.
                           </p>
                           <ul class="no-dot">
                              <li class="flex" >
                                 <div class="circle">
                                    <div class="image-overlay "> </div>
                              <span class="image" style="background-image:url(../../../images/seller-guide/url-guide-1.png"></span> 

                                  
                                    <div class="caption">
                                                STEP 1
                                    </div>
                                 
                                 </div>
                                
                                 <router-link :to="{ path: '/help/buyer-guide-1' }">
                                    How to navigate the dashboard
                                 </router-link>
                                 
                              </li>
                              <li class="flex" >
                                 <div class="circle">  
                                  <div class="image-overlay "> </div>
                                   <span class="image" style="background-image:url(../../../images/seller-guide/url-guide-1.png"></span> 

                                 
                                    <div class="caption">
                                                STEP 2
                                    </div> 
                                 </div>
                                 <router-link :to="{ path: '/help/buyer-guide-2' }"> 
                                    How to Purchase backlink that fits your website
                                 </router-link>
                              </li>
                              <li class="flex" >
                                 <div class="circle"> 
                                  <div class="image-overlay "> </div>
                                    <span class="image" style="background-image:url(../../../images/seller-guide/url-guide-1.png"></span> 

                                 
                                    <div class="caption">
                                                STEP 3
                                    </div>
                                 </div>
                                 <router-link :to="{ path: '/help/buyer-guide-3' }">
                                    How to Follow up your orders
                                 </router-link>
                              </li>
                           </ul>
                           <h4 class="bg-primary text-white p-3 mt-3 text-bold">Seller's Guide</h4>
                           <p>As a StaLinks publisher, you will earn monthly reoccurring revenue for each link that an advertiser chooses to purchase on your site(s). From there, you will continue to earn credits for each link every month, as long as the link(s) are active.</p>
                           <ul class="no-dot">
                              <li class="flex" >
                                 <div class="circle"> 
                                  <div class="image-overlay "> </div>
                                    <span class="image" style="background-image:url(../../../images/seller-guide/url-guide-1.png"></span> 

                                 
                                    <div class="caption">
                                                STEP 1
                                    </div>
                                    </div>
                                 <router-link :to="{ path: '/help/seller-guide-1' }">
                                    Learn about the dashboard
                                 </router-link>
                                 </li>
                              <li class="flex" >
                                 <div class="circle">  <div class="image-overlay "> </div>
                                     <span class="image" style="background-image:url(../../../images/seller-guide/url-guide-1.png"></span> 

                                 
                                    <div class="caption">
                                                STEP 2
                                    </div>
                                 </div>
                                 <router-link :to="{ path: '/help/seller-guide-2' }">
                                    How to Upload your list of urls 
                                 </router-link> </li>
                              <li class="flex" >
                                 <div class="circle">  <div class="image-overlay "> </div>
                                     <span class="image" style="background-image:url(../../../images/seller-guide/url-guide-1.png"></span> 

                                 
                                    <div class="caption">
                                                STEP 3
                                    </div> 
                                 </div>
                                 <router-link :to="{ path: '/help/seller-guide-3' }">
                                    How to Follow up your orders
                                 </router-link>  </li>
                              <li class="flex" >
                                 <div class="circle">  <div class="image-overlay "> </div>
                                     <span class="image" style="background-image:url(../../../images/seller-guide/url-guide-1.png"></span> 

                                 
                                    <div class="caption">
                                                STEP 4
                                    </div>
                                 </div>
                                 <router-link :to="{ path: '/help/seller-guide-4' }">
                                    How to get Pay
                                 </router-link>
                              </li>
                           </ul>
                           <h4 class="bg-primary text-white p-3 mt-3 text-bold">Writer's Guide</h4>
                           <p>             Writers are responsible for writing quality content that will become the backbone in building backlinks. The writer team will need to collaborate with the SEO team, Customer Service Sellers, and country managers to create the content for StaLinks clients. The writer must know how to research and write content based on various topics and industry trends that match the target website.</p>
                           <ul class="no-dot">
                              <li class="flex" >
                                 <div class="circle">   <div class="image-overlay "> </div>   <span class="image" style="background-image:url(../../../images/seller-guide/url-guide-1.png"></span> 

                                 
                                    <div class="caption">
                                                STEP 1
                                    </div>  </div>
                                 <router-link :to="{ path: '/help/writer-guide-1' }">
                                    How to Register and setup your price
                                 </router-link>
                              </li>
                              <li class="flex" >
                                 <div class="circle">  <div class="image-overlay "> </div>
                                     <span class="image" style="background-image:url(../../../images/seller-guide/url-guide-1.png"></span> 

                                 
                                    <div class="caption">
                                                STEP 2
                                    </div>
                                 </div>
                                 <router-link :to="{ path: '/help/writer-guide-2' }">
                                    How to start writing content
                                 </router-link>
                              </li>
                              <li class="flex" >
                                 <div class="circle">  <div class="image-overlay "> </div>
                                     <span class="image" style="background-image:url(../../../images/seller-guide/url-guide-1.png"></span> 

                                 
                                    <div class="caption">
                                                STEP 3
                                    </div>
                                 </div>
                                 <router-link :to="{ path: '/help/writer-guide-3' }">
                                    How to  Follow up your income
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
<style>
  .circle {
     position: relative;
    height: 80px;
    width: 95px;
    border-radius: 50%;
    display: inline-flex;
    vertical-align: top;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    text-decoration: none;
    border: 0;
    margin: 10px;
    color: #fff;
   
 
}

.circle .image {
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 100%;
  background-position:center;
  background-size:cover;
  background-repeat:no-repeat;
 
  transition: .25s;
}

.caption{
   display: inline-block;
  margin: 10px;
  z-index:99;
  font-weight:600;
}
   .flex{
   display:flex;
   align-items:center;
   position: relative;
   }
   .no-dot{
   list-style-type:none;
   text-transform:capitalize;
   }
   .no-dot a{
   text-decoration: none;
    
    font-weight: 600;
    font-size: 16px;
    position: relative;
    top: 50%;
    left: 2%;
    transform: translateY( -50%);
   }
 
   .help-content p{
   font-size:16px;
   line-height:30px;
   margin: 20px 0;
   }
 
   .image-overlay{
   position:absolute;
   border-radius:50%;
 
   position: absolute;
   z-index: 1;
   top: 0;
   left: 0;
   width: 100%;
 max-width: 95px;
    height: 100%;
    max-height: 80px;
   background-image: linear-gradient( 
   45deg
   , #000, #497eb1);
   opacity: .8;
   }
.box-header{
   padding:25px!important;
}
    
   
</style>