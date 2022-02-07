<template>
    <div>
        <tinymce
            v-model="editorData"
            ref="tinyVueEditor"
            :id="editorId"
            :value="value"
            :other_options="options"

            @editorChange="changeData"
            @editorInit="editorInit">

        </tinymce>
    </div>
</template>

<script>
export default {
    props: ['value', 'editorId'],
    data() {
        return {
            editorData: '',
            addImages: [],
            options: {
                height: 300,
                branding: false,
                image_title: true,
                convert_urls: false,
                automatic_uploads: true,
                allow_script_urls: false,
                forced_root_block : false,
                file_picker_types: 'image',
                extended_valid_elements: 'doctype',
                menubar: 'file edit insert format tools table',
                toolbar1: 'fullscreen  preview print | formatselect | bold italic  strikethrough  forecolor backcolor | link image | charmap | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
                images_upload_handler: function (blobInfo, success, failure) {
                    let xhr, formData;
                    let token = document.head.querySelector('meta[name="csrf-token"]');
                    let auth = localStorage.hasOwnProperty('vuex')
                        ? 'Bearer ' + JSON.parse(localStorage.getItem("vuex")).storeAuth.token.access_token
                        : '';
                    xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', '/api/mail/post-signature-image');
                    // manually set header
                    xhr.setRequestHeader('X-CSRF-TOKEN', token.content);
                    xhr.setRequestHeader('Accept', 'application/json');
                    xhr.setRequestHeader('Authorization', auth);
                    xhr.onload = function() {
                        let json;
                        if (xhr.status !== 200) {
                            failure('HTTP Error: ' + xhr.status);
                            return;
                        }
                        json = JSON.parse(xhr.responseText);
                        if (!json || typeof json.location != 'string') {
                            failure('Invalid JSON: ' + xhr.responseText);
                            return;
                        }
                        success(json.location);
                    };
                    formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    xhr.send(formData);
                }
            },
        }
    },
    watch: {
        editorData: function(newVal) {
            this.handleInput()
            this.getAddedImages(newVal)
        },
        value: function(newVal) {
            // this.editorData = this.convertLineBreaks(newVal);
            this.editorData = newVal;
        }
    },
    methods: {
        changeData() {
        },

        wordCount() {
            let content = this.$refs.tinyVueEditor.editor.plugins.wordcount;
            return content.getCount();
        },

        editorInit() {
            let content = this.value

            // if (content.includes('<!doctype html>')) {
            //     content = content.replace('<!doctype html>','');
            // }

            this.$refs.tinyVueEditor.editor.setContent(content);
        },

        handleInput (e) {
            this.$emit('input', this.editorData)
        },

        convertLineBreaks(str) {
            return str.replace(/(?:\r\n|\r|\n)/g, '<br />');
        },

        // get images on current content
        getImages(string) {
            const imgRex = /<img.*?src="(.*?)"[^>]+>/g;
            const images = [];
            let img;
            while ((img = imgRex.exec(string))) {
                images.push(img[1]);
            }

            return images;
        },

        // array of all added images
        getAddedImages(string) {
            let self = this
            let images = self.getImages(string)

            images.forEach(function (img) {
                if (!self.addImages.includes(img)) {
                    self.addImages.push(img)
                }
            })
        },

        // get removed images from current content
        getRemovedImages(temp = null) {
            let images = temp ? this.getImages(temp) : this.getImages(this.editorData);

            return this.addImages.filter(img => !images.includes(img))
        },

        clearAddImages() {
            this.addImages = [];
        },

        // delete images
        async deleteRemovedImages(images) {
            if (images.length !== 0) {
                await axios.post('/api/mail/delete-signature-image', {
                    images: images
                })

                this.addImages = [];
            }
        },

        // delete images function
        deleteImages(mode, temp = null) {
            if (mode === 'All') {
                this.deleteRemovedImages(this.addImages)
            } else {
                this.deleteRemovedImages(this.getRemovedImages(temp))
            }
        }
    },
}
</script>
