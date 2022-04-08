<template>
    <div class="modal fade" id="modalTermsAndCondition" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">{{ $t('message.terms.title') }}</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body px-5">
                    <!-- h1 -->
                    <div class="terms-header">
                        <h5>{{ $t('message.terms.h1') }}</h5>

                        <ul class="terms-header-list">
                            <li>{{ $t('message.terms.h1p1') }}</li>
                            <li>{{ $t('message.terms.h1p2') }}</li>
                        </ul>
                    </div>

                    <hr/>

                    <!-- h2 -->
                    <div>
                        <h5>{{ $t('message.terms.h2') }}</h5>

                        <ul class="terms-header-list">
                            <li>
                                <span class="font-weight-bold">{{ $t('message.terms.h2p1') }}</span>

                                <ul>
                                    <li>
                                        {{ $t('message.terms.h2p1s1') }}
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <span class="font-weight-bold">{{ $t('message.terms.h2p2') }}</span>

                                <ul>
                                    <li>
                                        {{ $t('message.terms.h2p2s1') }}
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <span class="font-weight-bold">{{ $t('message.terms.h2p3') }}</span>

                                <ul>
                                    <li>
                                        {{ $t('message.terms.h2p3s1') }}
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                    <hr/>

                    <!-- h3 -->
                    <div class="terms-header">
                        <h5>{{ $t('message.terms.h3') }}</h5>

                        <p>
                            {{ $t('message.terms.h3p1') }}
                        </p>
                    </div>

                    <hr/>

                    <!-- h4 -->
                    <div class="terms-header">
                        <h5>{{ $t('message.terms.h4') }}</h5>

                        <p>
                            {{ $t('message.terms.h4p1') }}
                        </p>
                    </div>

                    <hr/>

                    <!-- h5 -->
                    <div class="terms-header">
                        <h5>{{ $t('message.terms.h5') }}</h5>

                        <p>
                            {{ $t('message.terms.h5p1') }}
                        </p>
                    </div>

                    <hr/>

                    <!-- h6 -->
                    <div class="terms-header">
                        <h5>{{ $t('message.terms.h6') }}</h5>

                        <p>
                            {{ $t('message.terms.h6p1') }}
                        </p>
                    </div>

                    <hr/>

                    <!-- h7 -->
                    <div class="terms-header">
                        <h5>{{ $t('message.terms.h7') }}</h5>

                        <p>
                            {{ $t('message.terms.h7p1') }}
                        </p>
                    </div>

                    <hr/>

                    <!-- h8 -->
                    <div class="terms-header">
                        <h5>{{ $t('message.terms.h8') }}</h5>

                        <p>
                            {{ $t('message.terms.h8p1') }}
                        </p>
                    </div>

                    <hr/>

                    <!-- h9 -->
                    <div class="terms-header">
                        <h5>{{ $t('message.terms.h9') }}</h5>

                        <ul class="terms-header-list">
                            <li>{{ $t('message.terms.h9p1') }}</li>
                            <li>{{ $t('message.terms.h9p2') }}</li>
                            <li>{{ $t('message.terms.h9p3') }}</li>
                        </ul>
                    </div>

                    <hr/>

                    <!-- h10 -->
                    <div class="terms-header">
                        <h5>{{ $t('message.terms.h10') }}</h5>

                        <ul class="terms-header-list">
                            <li>{{ $t('message.terms.h10p1') }}</li>
                            <li>{{ $t('message.terms.h10p2') }}</li>
                            <li>{{ $t('message.terms.h10p3') }}</li>
                            <li>{{ $t('message.terms.h10p4') }}</li>
                            <li>{{ $t('message.terms.h10p5') }}</li>
                        </ul>
                    </div>

                    <hr/>

                    <!-- h11 -->
                    <div class="terms-header">
                        <h5>{{ $t('message.terms.h11') }}</h5>

                        <ul class="terms-header-list">
                            <li>{{ $t('message.terms.h11p1') }}</li>
                            <li>{{ $t('message.terms.h11p2') }}</li>
                            <li>{{ $t('message.terms.h11p3') }}</li>
                            <li>{{ $t('message.terms.h11p4') }}</li>
                        </ul>

                        <p>
                            {{ $t('message.terms.h11p5') }}
                        </p>
                    </div>

                    <hr/>

                    <!-- h12 -->
                    <div class="terms-header">
                        <h5>{{ $t('message.terms.h12') }}</h5>

                        <ul class="terms-header-list">
                            <li>{{ $t('message.terms.h12p1') }}</li>
                            <li>{{ $t('message.terms.h12p2') }}</li>
                            <li>{{ $t('message.terms.h12p3') }}</li>
                            <li>{{ $t('message.terms.h12p4') }}</li>
                            <li>{{ $t('message.terms.h12p5') }}</li>
                            <li>{{ $t('message.terms.h12p6') }}</li>
                            <li>{{ $t('message.terms.h12p7') }}</li>
                            <li>{{ $t('message.terms.h12p8') }}</li>
                        </ul>

                        <p>
                            {{ $t('message.terms.h12p9') }}
                        </p>

                        <p>
                            {{ $t('message.terms.h12p10') }}
                        </p>
                    </div>

                    <hr/>

                    <!-- h13 -->
                    <div class="terms-header">
                        <h5>{{ $t('message.terms.h13') }}</h5>

                        <p>
                            {{ $t('message.terms.h13p1') }}
                        </p>

                        <ul class="terms-header-list">
                            <li>{{ $t('message.terms.h13p2') }}</li>
                            <li>{{ $t('message.terms.h13p3') }}</li>
                            <li>{{ $t('message.terms.h13p4') }}</li>
                            <li>{{ $t('message.terms.h13p5') }}</li>
                            <li>{{ $t('message.terms.h13p6') }}</li>
                            <li>{{ $t('message.terms.h13p7') }}</li>
                            <li>{{ $t('message.terms.h13p8') }}</li>
                            <li>{{ $t('message.terms.h13p9') }}</li>
                            <li>{{ $t('message.terms.h13p10') }}</li>
                            <li>{{ $t('message.terms.h13p11') }}</li>
                            <li>{{ $t('message.terms.h13p12') }}</li>
                            <li>{{ $t('message.terms.h13p13') }}</li>
                        </ul>
                    </div>

                    <hr/>

                    <!-- h14 -->
                    <div class="terms-header">
                        <h5>{{ $t('message.terms.h14') }}</h5>

                        <ul class="terms-header-list">
                            <li>{{ $t('message.terms.h14p1') }}</li>
                            <li>{{ $t('message.terms.h14p2') }}</li>
                            <li>{{ $t('message.terms.h14p3') }}</li>
                        </ul>

                        <p>
                            {{ $t('message.terms.h14p4') }}
                        </p>
                    </div>

                    <hr/>

                    <!-- h15 -->
                    <div class="terms-header">
                        <h5>{{ $t('message.terms.h15') }}</h5>

                        <p>
                            {{ $t('message.terms.h15p1') }}
                        </p>
                    </div>

                    <hr/>

                    <!-- h16 -->
                    <div class="terms-header">
                        <h5>{{ $t('message.terms.h16') }}</h5>

                        <p>
                            {{ $t('message.terms.h16p1') }}
                        </p>
                    </div>

                    <hr/>

                    <!-- h17 -->
                    <div class="terms-header">
                        <h5>{{ $t('message.terms.h17') }}</h5>

                        <p>
                            {{ $t('message.terms.h17p1') }}
                        </p>
                    </div>

                    <hr/>

                    <!-- h18 -->
                    <div class="terms-header">
                        <h5>{{ $t('message.terms.h18') }}</h5>

                        <p>
                            {{ $t('message.terms.h18p1') }}
                        </p>

                        <p>
                            {{ $t('message.terms.h18p2') }}
                        </p>
                    </div>

                    <hr/>

                    <!-- h19 -->
                    <div class="terms-header">
                        <h5>{{ $t('message.terms.h19') }}</h5>

                        <p>
                            {{ $t('message.terms.h19p1') }}
                        </p>
                    </div>

                    <hr/>

                    <!-- h20 -->
                    <div class="terms-header">
                        <h5>{{ $t('message.terms.h20') }}</h5>

                        <p>
                            {{ $t('message.terms.h20p1') }}
                        </p>

                        <p>
                            {{ $t('message.terms.h20p2') }}
                        </p>
                    </div>

                    <hr/>

                    <!-- h21 -->
                    <div class="terms-header">
                        <h5>{{ $t('message.terms.h21') }}</h5>

                        <p>
                            {{ $t('message.terms.h21p1') }}
                        </p>

                        <p>
                            {{ $t('message.terms.h21p2') }}
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                        {{ $t('message.terms.b') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [],

    data() {
        return {}
    },

    methods: {},
}
</script>

<style scoped>

    ul {
        padding-left: 3%;
        list-style: none;
    }

    ul li::before {
        content: "\2022";  /* Add content: \2022 is the CSS Code/unicode for a bullet */
        color: #FF9B00; /* Change the color */
        font-weight: bold; /* If you want it to be bold */
        display: inline-block; /* Needed to add space between the bullet and the text */
        width: 1em; /* Also needed for space (tweak if needed) */
        margin-left: -1em; /* Also needed for space (tweak if needed) */
    }

    li > ul {
        padding-left: 3%;
        list-style: none;
    }

    li > ul li::before {
        content: "\2022";  /* Add content: \2022 is the CSS Code/unicode for a bullet */
        color: #e63e49; /* Change the color */
        font-weight: bold; /* If you want it to be bold */
        display: inline-block; /* Needed to add space between the bullet and the text */
        width: 1em; /* Also needed for space (tweak if needed) */
        margin-left: -1em; /* Also needed for space (tweak if needed) */
    }

    li > ul > li {
        font-size: 14px;
    }

    ul li {
        font-size: 15px;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .terms-header p {
        font-size: 15px;
        margin-top: 20px;
    }

    h5 {
        color: #007E93;
    }

    .terms-header {
        margin-bottom: 20px;
    }

    .terms-header-list {
        margin-top: 20px;
    }

</style>
