<template>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="box">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-12">
                                <h1 class="bg-primary text-white p-2 mt-3 with-arrow">
                                    <router-link :to="{ path: '/help/seller-guide-3' }">
                                        <img
                                            class=""
                                            src="../../../../../images/writer-guide/back.png"
                                        />
                                    </router-link>
                                    {{ $t('message.seller_4.h1') }}
                                    <router-link :to="{ path: '/help' }">
                                        <img
                                            class=""
                                            src="../../../../../images/writer-guide/next.png"
                                        />
                                    </router-link>
                                </h1>

                                <p>{{ $t('message.seller_4.p1') }}</p>
                                <ol class="content-style">
                                    <li>{{ $t('message.seller_4.ol1') }}</li>
                                    <li>{{ $t('message.seller_4.ol2') }}</li>
                                    <li>{{ $t('message.seller_4.ol3') }}</li>
                                </ol>
                                <img class="income-img" src="../../../../../images/seller-guide/income-guide-1.png"/>

                                <p>{{ $t('message.seller_4.p2') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.income-img {
    width: 100%;
    padding: 20px;
}

.content-style li {
    font-size: 17px;
    line-height: 2em;
    padding: 20px;
    font-weight: 600;
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
