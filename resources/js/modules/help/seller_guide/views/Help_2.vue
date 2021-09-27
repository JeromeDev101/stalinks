<template>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="box">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-12">
                                <h1 class="bg-primary text-white p-2 mt-3 with-arrow">
                                    <router-link :to="{ path: '/help/seller-guide-1' }">
                                        <img
                                            class=""
                                            src="../../../../../images/writer-guide/back.png"
                                        />
                                    </router-link>
                                    {{ $t('message.seller_2.h1') }}
                                    <router-link :to="{ path: '/help/seller-guide-3' }">
                                        <img
                                            class=""
                                            src="../../../../../images/writer-guide/next.png"
                                        />
                                    </router-link>
                                </h1>

                                <p>{{ $t('message.seller_2.p1') }}</p>

                                <p>{{ $t('message.seller_2.p2') }}</p>
                                <img class="buyer-img " src="../../../../../images/seller-guide/url-guide-1.png"/>
                                <p>{{ $t('message.seller_2.p3') }}</p>
                                <img src="../../../../../images/seller-guide/url-guide-2.png"/>
                                <p>{{ $t('message.seller_2.p4') }}</p>
                                <ol type="a">
                                    <li>{{ $t('message.seller_2.ol1') }}</li>
                                    <li>{{ $t('message.seller_2.ol2') }}</li>
                                    <li>{{ $t('message.seller_2.ol3') }}</li>
                                    <li>{{ $t('message.seller_2.ol4') }}</li>
                                    <li>{{ $t('message.seller_2.ol5') }}</li>
                                    <li>{{ $t('message.seller_2.ol6') }}</li>
                                    <li>{{ $t('message.seller_2.ol7') }}</li>
                                    <li>{{ $t('message.seller_2.ol8') }}</li>
                                </ol>
                                <img class="buyer-img" src="../../../../../images/seller-guide/url-guide-3.png"/>
                                <p>{{ $t('message.seller_2.p5') }}</p>

                                <p>{{ $t('message.seller_2.p6') }}</p>
                                <img class="buyer-img" src="../../../../../images/seller-guide/url-guide-4.png"/>

                                <p>{{ $t('message.seller_2.p7') }}</p>

                                <img class="buyer-img" src="../../../../../images/seller-guide/url-guide-5.png"/>
                                <p>{{ $t('message.seller_2.p8') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.buyer-img {
    width: 100%;
    padding: 20px;
}

.content-style li {
    font-size: 17px;
    line-height: 2em;
    padding: 20px;
    font-weight: 600;
}


.with-arrow {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

</style>

<script>
import {mapState} from 'vuex';

export default {
    data() {
        return {
            //
        }
    },
    async created() {
        //
    },
    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,
        }),
    },
    methods : {
        //
    },
}
</script>
