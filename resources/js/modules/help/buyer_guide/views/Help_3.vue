<template>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="box">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-12">
                                <h1 class="bg-primary text-white p-2 mt-3 text-uppercase with-arrow ">
                                    <router-link :to="{ path: '/help/buyer-guide-1' }">
                                        <img
                                            class=""
                                            src="../../../../../images/writer-guide/back.png"
                                        />
                                    </router-link>
                                    {{ $t('message.buyer_3.h1') }}
                                    <router-link :to="{ path: '/help'  }">
                                        <img

                                            src="../../../../../images/writer-guide/next.png"
                                        />
                                    </router-link>

                                </h1>

                                <p class="text-underline">{{ $t('message.buyer_3.p1') }}</p>

                                <p>{{ $t('message.buyer_3.p2') }}</p>
                                <img class="up" src="../../../../../images/buyer-guide/buyer-guide-5.png"/> <br/>

                                <p>{{ $t('message.buyer_3.p3') }}</p>

                                <ul class="list-type-none">
                                    <li>{{ $t('message.buyer_3.ul1') }}</li>
                                    <li>{{ $t('message.buyer_3.ul2') }}</li>
                                    <li>{{ $t('message.buyer_3.ul3') }}</li>
                                    <li>{{ $t('message.buyer_3.ul4') }}</li>
                                    <li>{{ $t('message.buyer_3.ul5') }}</li>
                                    <li>{{ $t('message.buyer_3.ul6') }}</li>
                                    <li>{{ $t('message.buyer_3.ul7') }}</li>
                                    <li>{{ $t('message.buyer_3.ul8') }}</li>
                                    <li>{{ $t('message.buyer_3.ul9') }}</li>
                                    <li>{{ $t('message.buyer_3.ul10') }}</li>
                                    <li>{{ $t('message.buyer_3.ul11') }}</li>
                                    <li>{{ $t('message.buyer_3.ul12') }}</li>
                                </ul>
                                {{ $t('message.buyer_3.p4') }}<br/>
                                <img class="w-25" src="../../../../../images/buyer-guide/buyer-guide-6.png"/> <br/>

                                {{ $t('message.buyer_3.p5') }}
                                <ul class="list-type-none">
                                    <li>{{ $t('message.buyer_3.ul13') }}</li>
                                    <li>{{ $t('message.buyer_3.ul14') }}</li>
                                    <li>{{ $t('message.buyer_3.ul15') }}</li>
                                </ul>
                                <img class="up" src="../../../../../images/buyer-guide/buyer-guide-7.png"/> <br/>

                                <p>{{ $t('message.buyer_3.p6') }}</p>
                                <ol>
                                    <li>{{ $t('message.buyer_3.ol1') }}</li>
                                    <li>{{ $t('message.buyer_3.ol2') }}</li>
                                    <li>{{ $t('message.buyer_3.ol3') }}</li>
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

</style>
