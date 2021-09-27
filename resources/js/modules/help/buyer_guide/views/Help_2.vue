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
                                    {{ $t('message.buyer_2.h1') }}
                                    <router-link :to="{ path: '/help/buyer-guide-3' }">
                                        <img
                                            class=""
                                            src="../../../../../images/writer-guide/next.png"
                                        />
                                    </router-link>

                                </h1>

                                <p class="text-underline">{{ $t('message.buyer_2.p1') }}</p>

                                <p>{{ $t('message.buyer_2.p2') }}</p>

                                <ol>
                                    <li>
                                        <p>
                                            {{ $t('message.buyer_2.ol1') }}
                                        </p>
                                        <img src="../../../../../images/buyer-guide/buyer-guide-3.png" width="750"/>
                                        <br/>
                                    </li>
                                    <li>
                                        <p>{{ $t('message.buyer_2.ol2') }}</p>
                                        <img src="../../../../../images/buyer-guide/buyer-guide-4.png" width="850"/>
                                        <img src="../../../../../images/buyer-guide/buyer-guide-5.png" width="850"/>
                                        <p>{{ $t('message.buyer_2.p3') }}</p>
                                    </li>
                                </ol>

                                <p>{{ $t('message.buyer_2.p4') }}</p>

                                <p>{{ $t('message.buyer_2.p5') }}</p>

                                <p>{{ $t('message.buyer_2.p6') }}</p>

                                <p>{{ $t('message.buyer_2.p7') }}</p>
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
