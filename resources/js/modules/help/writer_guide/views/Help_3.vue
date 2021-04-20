
  
<template>

  
  <div>
    <div class='row'>
      <div class='col-sm-12'>
        <div class='box'>
          <div class='box-header'>
            <div class='row'>
              <div class='col-md-12'>
                <h1 class='bg-primary text-white p-2 mt-3 text-uppercase with-arrow '>
                  <router-link :to="{ path: '/help/writer-guide-1' }">
                      <img
                      class=''
                      src='../../../../../images/writer-guide/back.png'
                    />
                  </router-link>
                Follow up your income
                   <router-link :to="{ path: '/help' }"> 
                      <img
                      class=''
                      src='../../../../../images/writer-guide/next.png'
                    />
                   </router-link> 
                 
                </h1>
               
               <p>Here a resume of all your articles and how much you made</p>
                <img
                      class='up'
                      src='../../../../../images/seller-guide/income-guide-1.png'/>
               <ol>
                   <li>Use the filter </li>
                    <li>Total amount of your incomes</li>
                     <li>You also can check the proof of payment</li>
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
        .writing-img{
            max-width: 50%;
            padding: 20px;
        }
        .content-style li {
            font-size:17px;
            line-height:2em;
            padding:20px;
            font-weight:600;
        }

          .with-arrow{
          display:flex;
          justify-content:space-between;
          align-items:center;
        }
        
    </style>
