<template>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="box">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-12">
                                <h1 class="bg-primary text-white p-2 mt-3 with-arrow">
                                    <router-link :to="{ path: '/help/buyer-guide-3' }">
                                        <img
                                            class=""
                                            src="../../../../../images/writer-guide/back.png"
                                        />
                                    </router-link>
                                    {{ $t('message.seller_1.h1') }}

                                    <router-link :to="{ path: '/help/seller-guide-2' }">
                                        <img
                                            class=""
                                            src="../../../../../images/writer-guide/next.png"
                                        />
                                    </router-link>

                                </h1>
                                <p class=" text-uppercase">{{ $t('message.seller_1.p1') }}</p>
                                <p>{{ $t('message.seller_1.p2') }}</p>

                                <p>{{ $t('message.seller_1.p3') }}</p>
                                <ol class="content-style">
                                    <li>{{ $t('message.seller_1.ol1') }}</li>
                                    <ul class="list-style-none">
                                        <li>{{ $t('message.seller_1.ul1') }}</li>

                                        <li>{{ $t('message.seller_1.ul2') }}</li>
                                    </ul>
                                    <ol class="content-style" type="a">
                                        <li>{{ $t('message.seller_1.ul3') }}</li>
                                        <li>{{ $t('message.seller_1.ul4') }}</li>
                                        <li>{{ $t('message.seller_1.ul5') }}</li>
                                        <li>{{ $t('message.seller_1.ul6') }}</li>
                                        <li>{{ $t('message.seller_1.ul7') }}</li>
                                        <li>{{ $t('message.seller_1.ul8') }}</li>
                                    </ol>
                                    <img class="buyer-img" src="../../../../../images/seller-guide/seller-guide-1.png"/>

                                    <li>{{ $t('message.seller_1.ol2') }}</li>
                                    <ul class="list-style-none">
                                        <li>{{ $t('message.seller_1.ul9') }}</li>

                                        <li>{{ $t('message.seller_1.ul10') }}</li>
                                    </ul>
                                    <ol class="content-style" type="a">
                                        <li>{{ $t('message.seller_1.ul11') }}</li>
                                        <li>{{ $t('message.seller_1.ul12') }}</li>
                                        <li>{{ $t('message.seller_1.ul13') }}</li>
                                        <li>{{ $t('message.seller_1.ul14') }}</li>
                                        <li>{{ $t('message.seller_1.ul15') }}</li>
                                        <li>{{ $t('message.seller_1.ul16') }}</li>
                                        <li>{{ $t('message.seller_1.ul17') }}</li>
                                        <li>{{ $t('message.seller_1.ul18') }}</li>
                                    </ol>
                                    <img class="buyer-img "
                                         src="../../../../../images/seller-guide/seller-guide-2.png"/>
                                    <li>{{ $t('message.seller_1.ol3') }}</li>
                                </ol>

                                <p>{{ $t('message.seller_1.p4') }}</p>
                                <img class="buyer-img " src="../../../../../images/seller-guide/seller-guide-3.png"/>

                                <p>{{ $t('message.seller_1.p5') }}</p>
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
