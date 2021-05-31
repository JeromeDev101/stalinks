<template>
    <div>
        <ckeditor :editor="editor" v-model="editorData" :config="editorConfig"></ckeditor>
    </div>
</template>

<script>

import CKEditor from '@ckeditor/ckeditor5-vue2';
import ClassicEditor from '@ckeditor/ckeditor5-editor-classic/src/classiceditor';

import FontPlugin from '@ckeditor/ckeditor5-font/src/font';
import LinkPlugin from '@ckeditor/ckeditor5-link/src/link';
import IndentPlugin from '@ckeditor/ckeditor5-indent/src/indent';
import BoldPlugin from '@ckeditor/ckeditor5-basic-styles/src/bold';
import HeadingPlugin from "@ckeditor/ckeditor5-heading/src/heading";
import ListStylePlugin from '@ckeditor/ckeditor5-list/src/liststyle';
import ItalicPlugin from '@ckeditor/ckeditor5-basic-styles/src/italic';
import ParagraphPlugin from '@ckeditor/ckeditor5-paragraph/src/paragraph';
import AlignmentPlugin from '@ckeditor/ckeditor5-alignment/src/alignment';
import IndentBlockPlugin from '@ckeditor/ckeditor5-indent/src/indentblock';
import EssentialsPlugin from '@ckeditor/ckeditor5-essentials/src/essentials';
import UnderlinePlugin from '@ckeditor/ckeditor5-basic-styles/src/underline';
import StrikethroughPlugin from '@ckeditor/ckeditor5-basic-styles/src/strikethrough';
import SpecialCharactersPlugin from '@ckeditor/ckeditor5-special-characters/src/specialcharacters';
import SpecialCharactersEssentialsPlugin from '@ckeditor/ckeditor5-special-characters/src/specialcharactersessentials';

import Table from '@ckeditor/ckeditor5-table/src/table';
import TableToolbar from '@ckeditor/ckeditor5-table/src/tabletoolbar';
import TableProperties from '@ckeditor/ckeditor5-table/src/tableproperties';
import TableCellProperties from '@ckeditor/ckeditor5-table/src/tablecellproperties';

import Image from '@ckeditor/ckeditor5-image/src/image';
import LinkImage from '@ckeditor/ckeditor5-link/src/linkimage';
import ImageStyle from '@ckeditor/ckeditor5-image/src/imagestyle';
import ImageResize from '@ckeditor/ckeditor5-image/src/imageresize';
import ImageUpload from "@ckeditor/ckeditor5-image/src/imageupload";
import ImageToolbar from '@ckeditor/ckeditor5-image/src/imagetoolbar';
import SimpleUploadAdapter from '@ckeditor/ckeditor5-upload/src/adapters/simpleuploadadapter';

export default {
    components: {
        // Use the <ckeditor> component in this view.
        ckeditor: CKEditor.component
    },
    data() {
        return {
            editor: ClassicEditor,
            editorData: '',
            editorConfig: {
                plugins: [
                    LinkPlugin,
                    BoldPlugin,
                    FontPlugin,
                    IndentPlugin,
                    ItalicPlugin,
                    HeadingPlugin,
                    AlignmentPlugin,
                    UnderlinePlugin,
                    ParagraphPlugin,
                    ListStylePlugin,
                    EssentialsPlugin,
                    IndentBlockPlugin,
                    StrikethroughPlugin,
                    SpecialCharactersPlugin,
                    SpecialCharactersEssentialsPlugin,

                    Table,
                    TableToolbar,
                    TableProperties,
                    TableCellProperties,

                    Image,
                    LinkImage,
                    ImageStyle,
                    ImageUpload,
                    ImageResize,
                    ImageToolbar,
                    SimpleUploadAdapter
                ],
                toolbar: {
                    items: [
                        'heading',
                        '|',
                        'undo',
                        'redo',
                        '|',
                        'fontFamily',
                        'fontSize',
                        'fontColor',
                        'fontBackgroundColor',
                        '|',
                        'Bold',
                        'Italic',
                        'Underline',
                        'Strikethrough',
                        '|',
                        'Alignment',
                        '|',
                        'numberedList',
                        'bulletedList',
                        '|',
                        'outdent',
                        'indent',
                        '|',
                        'specialCharacters',
                        'link',
                        'insertTable',
                        'uploadImage'
                    ],
                },

                table: {
                    contentToolbar: [
                        'tableColumn', 'tableRow', 'mergeTableCells',
                        'tableProperties', 'tableCellProperties'
                    ],

                    // Configuration of the TableProperties plugin.
                    tableProperties: {
                        // ...
                    },

                    // Configuration of the TableCellProperties plugin.
                    tableCellProperties: {
                        // ...
                    }
                },

                image: {
                    styles: [
                        'alignLeft', 'alignCenter', 'alignRight'
                    ],

                    resizeOptions: [
                        {
                            name: 'resizeImage:original',
                            value: null,
                            icon: 'original'
                        },
                        {
                            name: 'resizeImage:50',
                            value: '50',
                            icon: 'medium'
                        },
                        {
                            name: 'resizeImage:75',
                            value: '75',
                            icon: 'large'
                        }
                    ],

                    toolbar: [
                        'imageStyle:alignLeft', 'imageStyle:alignCenter', 'imageStyle:alignRight',
                        '|',
                        'resizeImage:50', 'resizeImage:75', 'resizeImage:original',
                    ]
                },

                simpleUpload: {
                    // The URL that the images are uploaded to.
                    uploadUrl: '/api/mail/post-signature-image',

                    // Enable the XMLHttpRequest.withCredentials property.
                    withCredentials: true,

                    // Headers sent along with the XMLHttpRequest to the upload server.
                    headers: {
                        'X-CSRF-TOKEN': '',
                        Authorization: '',
                        Accept: 'application/json'
                    }
                }
            }
        }
    },

    created() {
        let token = document.head.querySelector('meta[name="csrf-token"]');

        this.editorConfig.simpleUpload.headers.Authorization = localStorage.hasOwnProperty('vuex')
            ? 'Bearer ' + JSON.parse(localStorage.getItem("vuex")).storeAuth.token.access_token
            : '';
        this.editorConfig.simpleUpload.headers['X-CSRF-TOKEN'] = token.content;
    },

    methods: {
    },
}
</script>

<style>
.ck-editor__editable {
    min-height: 500px;
}

body {
    --ck-z-default: 100;
    --ck-z-modal: calc( var(--ck-z-default) + 999 );
}
</style>
