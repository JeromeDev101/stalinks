<template>
    <div>
        <tinymce
            v-model="editorData"
            :id="editorId"
            :value="value"
            :other_options="options"

            @editorChange="changeData">

        </tinymce>
    </div>
</template>

<script>
export default {
    props: ['value', 'editorId'],
    data() {
        return {
            editorData: '',
            options: {
                height: 300,
                branding: false,
                image_title: true,
                convert_urls: false,
                automatic_uploads: true,
                allow_script_urls: false,
                forced_root_block : false,
                file_picker_types: 'image',
                menubar: 'edit insert format tools table',
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
        },
        value: function(newVal) {
            // this.editorData = this.convertLineBreaks(newVal);
            this.editorData = newVal;
        }
    },
    methods: {
        changeData() {
        },
        handleInput (e) {
            this.$emit('input', this.editorData)
        },
        convertLineBreaks(str) {
            return str.replace(/(?:\r\n|\r|\n)/g, '<br />');
        },
    },
}
</script>
