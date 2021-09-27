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
                                    {{ $t('message.writer_2.h1') }}
                                    <router-link :to="{ path: '/help/writer-guide-3' }">
                                        <img
                                            class=""
                                            src="../../../../../images/writer-guide/next.png"
                                        />
                                    </router-link>

                                </h1>

                                <ol class="content-style">

                                    <li>{{ $t('message.writer_2.ol1') }}</li>
                                    <img class="writing-img "
                                         src="../../../../../images/writer-guide/writing-process-1.png"/>
                                    <li>{{ $t('message.writer_2.ol2') }}</li>
                                    <li>
                                        <p>{{ $t('message.writer_2.ol3') }}</p>
                                        <p>{{ $t('message.writer_2.ol4') }}</p>
                                    </li>
                                    <img class="writing-img "
                                         src="../../../../../images/writer-guide/writing-process-2.png"/>
                                    <li>{{ $t('message.writer_2.ol5') }}</li>
                                    <img class="writing-img "
                                         src="../../../../../images/writer-guide/writing-process-3.png"/>
                                    <li>{{ $t('message.writer_2.ol6') }}</li>
                                    <img class="writing-img "
                                         src="../../../../../images/writer-guide/writing-process-4.png"/>
                                    <li>{{ $t('message.writer_2.ol7') }}</li>
                                    <img class="writing-img "
                                         src="../../../../../images/writer-guide/writing-process-5.png"/>{' '}
                                    <br/>
                                    <img class="writing-img "
                                         src="../../../../../images/writer-guide/writing-process-6.png"/>
                                    <li>{{ $t('message.writer_2.ol7') }}</li>
                                    <img class="writing-img "
                                         src="../../../../../images/writer-guide/writing-process-7.png"/>
                                    <li>{{ $t('message.writer_2.ol8') }}</li>
                                    <img class="writing-img "
                                         src="../../../../../images/writer-guide/writing-process-8.png"/>
                                    <li>{{ $t('message.writer_2.ol9') }}</li>
                                    <img class="writing-img "
                                         src="../../../../../images/writer-guide/writing-process-9.png"/>
                                    <li>{{ $t('message.writer_2.ol10') }}</li>
                                    <img class="writing-img "
                                         src="../../../../../images/writer-guide/writing-process-10.png"/>
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
