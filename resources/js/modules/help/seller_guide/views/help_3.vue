<template>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="box">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-12">
                                <h1 class="bg-primary text-white p-2 mt-3 with-arrow">
                                    <router-link :to="{ path: '/help/seller-guide-2' }">
                                        <img
                                            class=""
                                            src="../../../../../images/writer-guide/back.png"
                                        />
                                    </router-link>
                                    {{ $t('message.seller_3.h1') }}
                                    <router-link :to="{ path: '/help/seller-guide-4' }">
                                        <img
                                            class=""
                                            src="../../../../../images/writer-guide/next.png"
                                        />
                                    </router-link>
                                </h1>

                                <p>{{ $t('message.seller_3.p1') }}</p>
                                <img class="follow-img" src="../../../../../images/seller-guide/follow-guide-1.png"/>
                                <p>{{ $t('message.seller_3.p2') }}</p>
                                <ul class="list-style-none">
                                    <li>{{ $t('message.seller_3.ul1') }}</li>

                                    <li>{{ $t('message.seller_3.ul2') }}</li>
                                    <li>{{ $t('message.seller_3.ul3') }}</li>
                                    <li>{{ $t('message.seller_3.ul4') }}</li>
                                    <li>{{ $t('message.seller_3.ul5') }}</li>
                                </ul>

                                <p>{{ $t('message.seller_3.p3') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.follow-img {
    width: 100%;
    padding: 20px;
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
