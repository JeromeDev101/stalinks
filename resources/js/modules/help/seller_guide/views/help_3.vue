<template>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="box">
                    <div class="box-header" style="padding: 18px !important;">
                        <div class="row">
                            <div class="help-content w-100">
                                <div class="row">
                                    <div class="col">
                                        <stepper :steps="steps"></stepper>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="card card-outline card-secondary">
                                            <div class="card-header text-center">
                                                <h4 class="help-header font-weight-bold text-uppercase">
                                                    STEP 3: {{ $t('message.seller_3.h1') }}
                                                </h4>
                                                <div class="card-tools">
                                                </div>
                                            </div>
                                            <div class="card-body pt-0">
                                                <p>{{ $t('message.seller_3.p1') }}</p>

                                                <ol class="font-weight-bold">
                                                    <!-- guide 1 -->
                                                    <li class="help-primary-li">
                                                        <span>{{ $t('message.seller_3.ol1') }}</span>

                                                        <figure class="figure mt-3 w-100" style="cursor: pointer;">
                                                            <img
                                                                alt="Table Image"
                                                                class="figure-img rounded img-thumbnail img-fluid"
                                                                src="../../../../../images/seller-guide/2022/seller-3-1.jpg"

                                                                @click="viewImage('../../../../../images/seller-guide/2022/seller-3-1.jpg')">
                                                        </figure>
                                                    </li>

                                                    <!-- guide 2 -->
                                                    <li class="help-primary-li">
                                                        <span>{{ $t('message.seller_3.ol2') }}</span>

                                                        <figure class="figure mt-3 w-100" style="cursor: pointer;">
                                                            <img
                                                                alt="Table Image"
                                                                class="figure-img rounded img-thumbnail img-fluid"
                                                                src="../../../../../images/seller-guide/2022/seller-3-2.jpg"

                                                                @click="viewImage('../../../../../images/seller-guide/2022/seller-3-2.jpg')">
                                                        </figure>

                                                        <ul class="help-ul-list">
                                                            <li>{{ $t('message.seller_3.ul1') }}</li>
                                                            <li>{{ $t('message.seller_3.ul2') }}</li>
                                                            <li>{{ $t('message.seller_3.ul3') }}</li>
                                                        </ul>
                                                    </li>

                                                    <!-- guide 3 -->
                                                    <li class="help-primary-li">
                                                        <span>{{ $t('message.seller_3.ol3') }}</span>

                                                        <figure class="figure mt-3 w-100" style="cursor: pointer;">
                                                            <img
                                                                alt="Table Image"
                                                                class="figure-img rounded img-thumbnail img-fluid"
                                                                src="../../../../../images/seller-guide/2022/seller-3-3.jpg"

                                                                @click="viewImage('../../../../../images/seller-guide/2022/seller-3-3.jpg')">
                                                        </figure>
                                                    </li>

                                                    <!-- guide 4 -->
                                                    <li class="help-primary-li">
                                                        <span>{{ $t('message.seller_3.ol4') }}</span>

                                                        <figure class="figure mt-3 w-100" style="cursor: pointer;">
                                                            <img
                                                                alt="Table Image"
                                                                class="figure-img rounded img-thumbnail img-fluid"
                                                                src="../../../../../images/seller-guide/2022/seller-3-4.jpg"

                                                                @click="viewImage('../../../../../images/seller-guide/2022/seller-3-4.jpg')">
                                                        </figure>
                                                    </li>

                                                    <!-- guide 5 -->
                                                    <li class="help-primary-li">
                                                        <span>{{ $t('message.seller_3.ol5') }}</span>
                                                    </li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <image-viewer ref="imageViewerComponent"></image-viewer>
    </div>
</template>

<script>
import {mapState} from 'vuex';
import Stepper from '@/components/stepper/Stepper';
import ImageViewer from '@/components/modal/ImageViewer';

export default {
    components: {
        Stepper,
        ImageViewer
    },

    data() {
        return {
            steps: [
                {
                    title: this.$t('message.seller_1.h1'),
                    route: '/help/seller-guide-1',
                    icon: 'fas fa-chart-line',
                    subtitle: 'Step 1',
                },
                {
                    title: this.$t('message.seller_2.h1'),
                    route: '/help/seller-guide-2',
                    icon: 'fas fa-upload',
                    subtitle: 'Step 2',
                },
                {
                    title: this.$t('message.seller_3.h1'),
                    route: '/help/seller-guide-3',
                    icon: 'fas fa-clipboard-check',
                    subtitle: 'Step 3',
                },
                {
                    title: this.$t('message.seller_4.h1'),
                    route: '/help/seller-guide-4',
                    icon: 'fas fa-hand-holding-usd',
                    subtitle: 'Step 4',
                },
            ]
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
        viewImage(src) {
            this.$refs.imageViewerComponent.initializeModal(src)
        },
    },
}
</script>

<style>
    .follow-img {
        width: 100%;
        padding: 20px;
    }

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

    .help-primary-li {
        margin-bottom: 15px;
    }

    .help-ul-list {
        padding-left: 1%;
        list-style: none;
    }

    .help-ul-list li::before {
        content: "\2022";  /* Add content: \2022 is the CSS Code/unicode for a bullet */
        color: #FF9B00; /* Change the color */
        font-weight: bold; /* If you want it to be bold */
        display: inline-block; /* Needed to add space between the bullet and the text */
        width: 1em; /* Also needed for space (tweak if needed) */
        margin-left: -1em; /* Also needed for space (tweak if needed) */
    }

    .help-ul-list li {
        font-size: 15px;
        margin-top: 10px;
        margin-bottom: 10px;
        font-weight: normal !important;
    }

    .help-ul-list li > ul {
        padding-left: 1%;
        list-style: none;
    }

    .help-ul-list li ul li::before {
        content: "\2022";  /* Add content: \2022 is the CSS Code/unicode for a bullet */
        color: #e63e49; /* Change the color */
        font-weight: bold; /* If you want it to be bold */
        display: inline-block; /* Needed to add space between the bullet and the text */
        width: 1em; /* Also needed for space (tweak if needed) */
        margin-left: -1em; /* Also needed for space (tweak if needed) */
    }

    .help-ul-list-main {
        padding-left: 1%;
        list-style: none;
    }

    .help-ul-list-main li::before {
        content: "\2022";  /* Add content: \2022 is the CSS Code/unicode for a bullet */
        color: #007E93; /* Change the color */
        font-weight: bold; /* If you want it to be bold */
        display: inline-block; /* Needed to add space between the bullet and the text */
        width: 1em; /* Also needed for space (tweak if needed) */
        margin-left: -1em; /* Also needed for space (tweak if needed) */
    }

    .help-ul-list-main li {
        font-size: 15px;
        margin-top: 20px;
        margin-bottom: 20px;
        font-weight: normal !important;
    }
</style>
