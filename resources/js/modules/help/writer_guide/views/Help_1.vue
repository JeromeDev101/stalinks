<template>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="box">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-12">

                                <h1 class="bg-primary text-white p-2 mt-3 with-arrow">
                                    <router-link :to="{ path: '/help/seller-guide-4' }">
                                        <img
                                            class=""
                                            src="../../../../../images/writer-guide/back.png"
                                        />
                                    </router-link>
                                    {{ $t('message.writer_1.h1') }}
                                    <router-link :to="{ path: '/help/writer-guide-2' }">
                                        <img
                                            class=""
                                            src="../../../../../images/writer-guide/next.png"
                                        />
                                    </router-link>
                                </h1>
                                <p>{{ $t('message.writer_1.p1') }}</p>
                                <p>{{ $t('message.writer_1.p2') }}</p>
                                <ol class="content-styles">
                                    <li>{{ $t('message.writer_1.ol1') }}</li>
                                    <ul class="list-style-none">
                                        <li>{{ $t('message.writer_1.ul1') }}</li>

                                        <li>{{ $t('message.writer_1.ul2') }}</li>
                                    </ul>
                                    <ol type="a">
                                        <li>{{ $t('message.writer_1.ul3') }}</li>
                                        <li>{{ $t('message.writer_1.ul4') }}</li>
                                        <li>{{ $t('message.writer_1.ul5') }}</li>
                                        <li>{{ $t('message.writer_1.ul6') }}</li>
                                        <li>{{ $t('message.writer_1.ul7') }}</li>
                                        <li>{{ $t('message.writer_1.ul8') }}</li>
                                        <li>{{ $t('message.writer_1.ul9') }}</li>
                                        <li>{{ $t('message.writer_1.ul10') }}</li>
                                    </ol>
                                </ol>
                                <img class="up" src="../../../../../images/writer-guide/up.png"/>

                                <p>{{ $t('message.writer_1.p3') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.up {
    width: 100%;
    padding: 20px;
}

.content-styles li {
    font-size: 17px;
    font-weight: 600;
}


.with-arrow {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

</style>
