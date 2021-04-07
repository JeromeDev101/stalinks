<template>
  <div>
    <div class='row'>
      <div class='col-sm-12'>
        <div class='box'>
          <div class='box-header'>
            <div class='row'>
              <div class='col-md-12'>
                <h1 class='bg-primary text-white p-2 mt-3'>
                 HELP 4 : Follow up your orders
                </h1>
                   
              
               
                <p>
               How to use Incomes 
Here a resume of all your sells and how much you made 


                </p>
                <ol>
                    <li>	Use the filter to search </li>
                    <li>	Total amount of your incomes</li>
                    <li>	You also can check the proof of payment </li>
                </ol>
                  <img src="../../../../../images/seller-guide/income-guide-1.png"/>
            
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