<template>
  <div>
    <div class='row'>
      <div class='col-sm-12'>
        <div class='box'>
          <div class='box-header'>
            <div class='row'>
              <div class='col-md-12'>
               
                <h1 class='bg-primary text-white p-2 mt-3 with-arrow'>
                    <router-link :to="{ path: '/help/seller-guide-4' }">
                      <img
                      class=''
                      src='../../../../../images/writer-guide/back.png'
                    />
                  </router-link>
                  STEP 1: REGISTER TO STALINKS AND SETUP YOUR PRICE
                   <router-link :to="{ path: '/help/writer-guide-2' }">
                      <img
                      class=''
                      src='../../../../../images/writer-guide/next.png'
                    />
                  </router-link>
                </h1>
                <p>
                  After you have validated your account, just logging in to your
                  account
                </p>
                <p>
                  Upon logging in, you will automatically send to the Dashboard
                  page. The Dashboard is the tools you can use to track your
                  progress. However, if you click on each column, it can
                  rearrange from lower to higher or vice versa. There are four
                  sections on the dashboard:
                </p>
                <ol class='content-styles' >
                  <li>Total Backlink (Buyer)</li>
                  <ul class="list-style-none">
                    <li>All the information about to track the orders</li>

                    <li>It has 6 columns:</li>
                  </ul>
                  <ol type='a' >
                    <li>Total of orders</li>
                    <li>
                      Processing – order is made the writer will have to start
                      writing
                    </li>
                    <li>Content Writing – At this moment writers are on it</li>
                    <li>
                      Content Done – This content is ready to be post be sure
                      your seller go it or you sent it
                    </li>
                    <li>
                      Content Sent – CS has sent the content and are expecting
                      the seller to put in in live process
                    </li>
                    <li>
                      Live - checked by QC and approve now we can pay the seller
                    </li>
                    <li>Issue – the seller has a problem of timing</li>
                    <li>
                      Canceled – the seller is not able to proceed anymore
                    </li>
                  </ol>
                </ol>
                <img class="up" src='../../../../../images/writer-guide/up.png' />

                <p>Thanks for using Stalinks</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


</template>
  <style>
        .up{
            width: 100%;
            padding: 20px;
        }
        .content-styles li {
            font-size:17px;
            font-weight:600;
        }

     

        .with-arrow{
          display:flex;
          justify-content:space-between;
          align-items:center;
        }
        
    </style>