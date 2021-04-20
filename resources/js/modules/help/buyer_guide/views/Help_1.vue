<template>
  <div>
    <div class='row'>
      <div class='col-sm-12'>
        <div class='box'>
          <div class='box-header'>
            <div class='row'>
              <div class='col-md-12'>
               
                <h1 class='bg-primary text-white p-2 mt-3 with-arrow'>
                    <router-link :to="{ path: '/help' }">
                   <img
                      class=''
                      src='../../../../../images/writer-guide/back.png'
                    />
                    </router-link>
                 STEP 1: Navigating with the dashboard 
                   <router-link :to="{ path: '/help/buyer-guide-2' }">
                      <img
                      class=''
                      src='../../../../../images/writer-guide/next.png'
                    />
                  </router-link>
                </h1>
                <p>
                  Now that you are login into our desk management 
                </p>
                <p>
                 Upon logging in, you will automatically send to the Dashboard page. The Dashboard is the tools you can use to track your progress.
However, if you click on each column, it can rearrange from lower to higher or vice versa. There are 3 sections on the dashboard:

                </p>
                <ol class='content-styles' >
                  <li>Total Backlink (Buyer)</li>
                  <ul class="list-style-none">
                    <li>All the information about to track the orders</li>

                    <li>It has 8 columns:</li>
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
                   <img class="up" src='../../../../../images/buyer-guide/up.png' />
                  <li>List of Backlink to Buy </li>
                   <ul class="list-style-none">
                    <li>This table will help you to overview the list of URLs provided by StaLinks</li>

                    <li>It has 6 columns:</li>
                      </ul>
                    <ol type='a' >
                    <li>	Buyer-  if you have sub account the list of your team will be mentioned below New – the new URLs that StaLinks recently entered in the system that you have not checked yet </li>
                    <li>
                       Interested – Url that you would like to buy backlink in the future  
                    </li>
                    <li>Purchased – Url you already purchased backlink on it  </li>
                    <li>
                      Not interested – This are the one you have no interest to buy backlinks 
                    </li>
                    <li>
                      Total – well you know what is it 
                    </li>
                    
                    
                  </ol>
                    <img class="up" src='../../../../../images/buyer-guide/buyer-guide-1.png' />
                    <li>Purchase </li>
                    <ul class="list-style-none">
                    <li>This table will help you to overview the list of orders you made </li>

                    <li>It has 6 columns:</li>

                      </ul>
                      <ol type='a' >
                    <li>	Buyer-  if you have sub account the list of your team will be below</li>
                    <li>
                       Backlink – How many orders are already live 
                    </li>
                    <li>	Unpaid – Our billing is on the process of paying this order because the article is already live    </li>
                    <li>
                      Paid – Our service has already paid this order.  
                    </li>
                    
                    
                  </ol>
                   <img class="up" src='../../../../../images/buyer-guide/buyer-guide-2.png' />
                <p>Don’t forget that during all the process some push notification will be sent to you automatically  </p>

                 <p>Thanks for using StaLinks</p>
                </ol>
              

               
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