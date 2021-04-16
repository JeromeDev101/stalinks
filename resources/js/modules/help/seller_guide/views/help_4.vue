<template>
  <div>
    <div class='row'>
      <div class='col-sm-12'>
        <div class='box'>
          <div class='box-header'>
            <div class='row'>
              <div class='col-md-12'>
                <h1 class='bg-primary text-white p-2 mt-3 with-arrow'>
                  <router-link :to="{ path: '/help/seller-guide-3' }">
                      <img
                      class=''
                      src='../../../../../images/writer-guide/back.png'
                    />
                  </router-link>
                 HELP 4 : GET PAY
                </h1>
                   
              
               
                <p>
               How to use Incomes 
Here a resume of all your sells and how much you made 


                </p>
                <ol class="content-style">
                    <li>	Use the filter to search </li>
                    <li>	Total amount of your incomes</li>
                    <li>	You also can check the proof of payment </li>
                </ol>
                  <img class="income-img"src="../../../../../images/seller-guide/income-guide-1.png"/>
            
 <p>


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
  .income-img{
    width:100%;
    padding: 20px;
  }
   .content-style li {
            font-size:17px;
            line-height:2em;
            padding:20px;
            font-weight:600;
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