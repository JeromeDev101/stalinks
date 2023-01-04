<template>
   <div>
      <div v-if="$route.name === 'help'">
         <div class="row">
            <div class="col">
               <div class="box">
                  <div class="box-header" style="padding: 18px !important;">
                     <div class="row">
                        <div class="help-content w-100">
                            <div v-if="user.isOurs === 0 || user.role_id === 5" class="row" ref="welcome">
                                <div class="col">
                                    <div class="card card-outline card-secondary">
                                        <div class="card-header">
                                            <h4 class="help-header font-weight-bold">
                                                {{ $t('message.help.h1') }}
                                            </h4>
                                            <div class="card-tools">
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <p class="help-paragraph">{{ $t('message.help.p1') }}</p>

                                            <h5 class="help-sub-header">{{ $t('message.help.h2') }}</h5>

                                            <p class="help-paragraph">{{ $t('message.help.p2') }}</p>
                                            <p class="help-paragraph">{{ $t('message.help.p3') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="user.isOurs === 0 || user.role_id === 5" class="row" ref="buyer">
                                <div class="col">
                                    <div class="card card-outline card-secondary">
                                        <div class="card-header py-3">
                                            <div class="card-title">
                                                <h4 class="help-header font-weight-bold m-0">
                                                    {{ $t('message.help.h3') }}
                                                </h4>
                                            </div>
                                            <div class="card-tools">
                                                <a href="../../../images/buyer-guide/stalinks-buyer-guide.docx" title="Download Guide" download>
                                                    <button
                                                        class="btn btn-primary btn-sm"
                                                        style="background-color: #007E93 !important; border-color: #007E93 !important;">

                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <p class="help-paragraph">{{ $t('message.help.p4') }}</p>

                                            <ul class="no-dot">
                                                <li class="mb-3">
                                                    <img src="../../../images/step-icons/Seller_Dashboard.png" alt="">

                                                    <router-link :to="{ path: $t('message.help.b1.url') }">
                                                        {{ $t('message.help.b1.text') }}
                                                    </router-link>
                                                </li>

                                                <li class="mb-3">
                                                    <img src="../../../images/step-icons/Sellet_Paid.png" alt="">

                                                    <router-link :to="{ path: $t('message.help.b2.url') }">
                                                        {{ $t('message.help.b2.text') }}
                                                    </router-link>
                                                </li>

                                                <li class="mb-3">
                                                    <img src="../../../images/step-icons/Buyer_Backlink.png" alt="">

                                                    <router-link :to="{ path: $t('message.help.b22.url') }">
                                                        {{ $t('message.help.b22.text') }}
                                                    </router-link>
                                                </li>

                                                <li class="mb-3">
                                                    <img src="../../../images/step-icons/Buyer_Follow Up.png" alt="">

                                                    <router-link :to="{ path: $t('message.help.b3.url') }">
                                                        {{ $t('message.help.b3.text') }}
                                                    </router-link>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="user.isOurs === 0 || user.role_id === 6" class="row" ref="seller">
                                <div class="col">
                                    <div class="card card-outline card-secondary">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h4 class="help-header font-weight-bold m-0">
                                                    {{ $t('message.help.h4') }}
                                                </h4>
                                            </div>
                                            <div class="card-tools">
                                                <a href="../../../images/seller-guide/stalinks-seller-guide.docx" title="Download Guide" download>
                                                    <button
                                                        class="btn btn-primary btn-sm"
                                                        style="background-color: #007E93 !important; border-color: #007E93 !important;">

                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <p class="help-paragraph">{{ $t('message.help.p5') }}</p>

                                            <ul class="no-dot">
                                                <li class="mb-3">
                                                    <img src="../../../images/step-icons/Seller_Dashboard.png" alt="">

                                                    <router-link :to="{ path: $t('message.help.b4.url') }">
                                                        {{ $t('message.help.b4.text') }}
                                                    </router-link>
                                                </li>

                                                <li class="mb-3">
                                                    <img src="../../../images/step-icons/Seller_Upload.png" alt="">

                                                    <router-link :to="{ path: $t('message.help.b5.url') }">
                                                        {{ $t('message.help.b5.text') }}
                                                    </router-link>
                                                </li>

                                                <li class="mb-3">
                                                    <img src="../../../images/step-icons/Seller_Follow Up.png" alt="">

                                                    <router-link :to="{ path: $t('message.help.b6.url') }">
                                                        {{ $t('message.help.b6.text') }}
                                                    </router-link>
                                                </li>

                                                <li class="mb-3">
                                                    <img src="../../../images/step-icons/Sellet_Paid.png" alt="">

                                                    <router-link :to="{ path: $t('message.help.b7.url') }">
                                                        {{ $t('message.help.b7.text') }}
                                                    </router-link>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="user.isOurs === 0 || user.role_id === 4" class="row" ref="writer">
                                <div class="col">
                                    <div class="card card-outline card-secondary">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h4 class="help-header font-weight-bold m-0">
                                                    {{ $t('message.help.h5') }}
                                                </h4>
                                            </div>
                                            <div class="card-tools">
                                                <a href="../../../images/writer-guide/stalinks-writer-guide.docx" title="Download Guide" download>
                                                    <button
                                                        class="btn btn-primary btn-sm"
                                                        style="background-color: #007E93 !important; border-color: #007E93 !important;">

                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <p class="help-paragraph">{{ $t('message.help.p6') }}</p>

                                            <ul class="no-dot">
                                                <li class="mb-3">
                                                    <img src="../../../images/step-icons/Seller_Dashboard.png" alt="">

                                                    <router-link :to="{ path: $t('message.help.b8.url') }">
                                                        {{ $t('message.help.b8.text') }}
                                                    </router-link>
                                                </li>

                                                <li class="flex mb-3">
                                                    <img src="../../../images/step-icons/Writer_Content.png" alt="">

                                                    <router-link :to="{ path: $t('message.help.b9.url') }">
                                                        {{ $t('message.help.b9.text') }}
                                                    </router-link>
                                                </li>

                                                <li class="flex mb-3">
                                                    <img src="../../../images/step-icons/Writer_Register.png" alt="">

                                                    <router-link :to="{ path: $t('message.help.b99.url') }">
                                                        {{ $t('message.help.b99.text') }}
                                                    </router-link>
                                                </li>

                                                <li class="flex mb-3">
                                                    <img src="../../../images/step-icons/Sellet_Paid.png" alt="">

                                                    <router-link :to="{ path: $t('message.help.b10.url') }">
                                                        {{ $t('message.help.b10.text') }}
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
               </div>
            </div>
         </div>
      </div>
      <div v-if="$route.name != 'help'">
         <router-view></router-view>
      </div>
   </div>
</template>

<script>
import {mapState} from "vuex";
import Stepper from '@/components/stepper/Stepper';

export default {
    components: {
        Stepper
    },

    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,
        }),
    },

    mounted() {
    }
}
</script>

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

    .help-header {
        margin-top: 15px;
        color: #007E93;
    }

    .help-paragraph {
    }

    .help-sub-header {
        color: #FF9B00;
    }

    .help-separator {
        margin-top: 30px;
        margin-bottom: 10px;
        border-bottom: 3px solid #ced4da;
    }
</style>
