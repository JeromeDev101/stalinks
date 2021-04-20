<template>
  <div>
    <div class='row'>
      <div class='col-sm-12'>
        <div class='box'>
          <div class='box-header'>
            <div class='row'>
              <div class='col-md-12'>
                <h1 class='bg-primary text-white p-2 mt-3 with-arrow'>
                   <router-link :to="{ path: '/help/seller-guide-2' }">
                      <img
                      class=''
                      src='../../../../../images/writer-guide/back.png'
                    />
                  </router-link>
                 STEP 3: Follow up your orders
                  <router-link :to="{ path: '/help/seller-guide-4' }">
                      <img
                      class=''
                      src='../../../../../images/writer-guide/next.png'
                    />
                  </router-link>
                </h1>
                   
              
               
                <p>
                How to use Follow up Sale 
Now that you got some order is time to be sure delivery is done on time 

                </p>
                  <img class="follow-img"src="../../../../../images/seller-guide/follow-guide-1.png"/>
              <p>
                Use the filter to look for what you need
please update the status via edit button 
If you don’t provide the article our writer team will send it to you 
Here the different status 

              </p>
                 <ul class="list-style-none">
                    <li>
                    Processing: When you see this status, this means that a buyer is interested in buying links from your website. 
                    </li>

                    <li>Content Writing: Our in-house writers are writing content for your backlink or you. </li>
                    <li>Content Done: The writer finishes writing the content. </li>
                    <li>Content Sent: our team has sent you the content. </li>
                    <li>Live process: you have approved the content and published.</li>
                  </ul>
             
                 
 <p>
Once you make the status live process our QC will validate the order status [ live] will appear.
you will get paid automatically on your given payment info within 48 hours working days.



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
<style>
  .follow-img{
    width:100%;
    padding: 20px;
  }
</style>

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