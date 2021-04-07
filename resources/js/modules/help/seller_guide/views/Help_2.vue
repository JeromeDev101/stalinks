<template>
  <div>
    <div class='row'>
      <div class='col-sm-12'>
        <div class='box'>
          <div class='box-header'>
            <div class='row'>
              <div class='col-md-12'>
                <h1 class='bg-primary text-white p-2 mt-3'>
                 HELP 2 : Upload your list of URLs with price and other criteria
                </h1>
                   
                <p>Now is time to upload your URLs</p>
               
                <p>
                 In the menu look for LIST Publisher:
Add more url There are two ways to upload URLs to the publisher list. You can do it manually or by uploading the CSV files.
At the left corner of the screen, go to the “Seller” menu and “List Publisher List” tab.
On the menu bar, click on Seller → List Publisher

                </p>
                  <img src="../../../../../images/seller-guide/url-guide-1.png"/>
              <p>
                  If you choose to do it manually:
                On the right side, click on the add URL button

              </p>
               <img src="../../../../../images/seller-guide/url-guide-2.png"/>
                <p>
                A pop-up window will show and fill out the information

              </p>
                  <ol type='a'>
                    <li>Sellcer</li>
                    <li>
                      Include article: yes / no
                    </li>
                    <li>URL</li>
                    <li>
                    Language
                    </li>
                    <li>
                     Country
                    </li>
                    <li>
                     Price in ( USD )
                    </li>
                    <li>Accept Casino / Betting sites : yes / no </li>
                    <li>
                     Topic 
                    </li>
                  </ol>
<img src="../../../../../images/seller-guide/seller-guide-3.png"/>
                 <p>Click Add</p>
                    

                  <p>
                   If you choose to upload the CSV File:
you must prepare a CSV Excel file first containing the details of the URL’s (URL, Price, Inc Article, Accept C&B, KW Anchor and Language).
We have made a template for you 

                  </p>
                  <img src="../../../../../images/seller-guide/seller-guide-4.png"/>
              

                <p>When your CSV is ready 
Click “Choose File” then click the upload icon.
</p>

 <img src="../../../../../images/seller-guide/seller-guide-5.png"/>
 <p>
     You always can edit or delete urls info 
We are the on to generate ahref score for you 
You can also check the price of URL the indicator is based on the color of the start 
Red…price to high 
Orange … average price
Green … Price is good you will have more chance to get orders soon 
Just wait for some buyer to order you some backlinks 


Thanks for using Stalinks 

 </p>
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