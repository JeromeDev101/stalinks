<template>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="box">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-12">
                                <h1 class="bg-primary text-white p-2 mt-3 text-uppercase with-arrow ">
                                    <router-link :to="{ path: '/help/writer-guide-1' }">
                                        <img
                                            class=""
                                            src="../../../../../images/writer-guide/back.png"
                                        />
                                    </router-link>
                                    {{ $t('message.writer_3.h1') }}
                                    <router-link :to="{ path: '/help' }">
                                        <img
                                            class=""
                                            src="../../../../../images/writer-guide/next.png"
                                        />
                                    </router-link>

                                </h1>

                                <p>{{ $t('message.writer_3.p1') }}</p>
                                <img
                                    class="up"
                                    src="../../../../../images/seller-guide/income-guide-1.png"/>
                                <ol>
                                    <li>{{ $t('message.writer_3.ol1') }}</li>
                                    <li>{{ $t('message.writer_3.ol2') }}</li>
                                    <li>{{ $t('message.writer_3.ol3') }}</li>
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
.writing-img {
    max-width: 50%;
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
