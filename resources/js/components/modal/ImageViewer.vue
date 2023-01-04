<template>
    <!-- Modal Image Preview -->
    <div
        class="modal fade"
        style="display: none;"
        id="modal-image-viewer"
        ref="modalImageViewer">

        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div
                        ref="imageArea"
                        class="source-holder"
                        :style="'background-image: url(' + filePreview + ')'"

                        @mousemove="mouseMoveEvent">
                        <img
                            ref="sourceImage"
                            alt="Help page image"
                            class="img-thumbnail rounded mx-auto d-block"
                            :src="filePreview">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [],

    data() {
        return {
            filePreview: '',
        }
    },

    methods: {
        initializeModal (src) {
            this.filePreview = src;

            this.modalOpener();
        },

        modalOpener () {
            let element = this.$refs.modalImageViewer
            $(element).modal('show')
        },

        mouseMoveEvent (event) {
            let zoomer = event.currentTarget;
            let offsetX;
            let offsetY;

            event.offsetX ? offsetX = event.offsetX : offsetX = 0
            event.offsetY ? offsetY = event.offsetY : offsetX = 0

            let x = offsetX / zoomer.offsetWidth * 100
            let y = offsetY / zoomer.offsetHeight * 100

            zoomer.style.backgroundPosition = x + '% ' + y + '%';
        },
    },
}
</script>

<style scoped>
    .source-holder {
        background-position: 50% 50%;
        position: relative;
        overflow: hidden;
        cursor: zoom-in;
        margin: 0;
    }

    .source-holder img{
        padding: 0;
        transition: opacity 0.5s;
        display: block;
        width: 100%;
    }

    .source-holder img:hover{
        opacity: 0;
    }
</style>
