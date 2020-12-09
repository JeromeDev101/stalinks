<template>
    <div>
        <div class="row">

            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ $route.name }}</h3>

                        <div class="box-tools pull-right">
                            <!-- <div class="has-feedback">
                                <input type="text" class="form-control input-sm" placeholder="Search Mail">
                            </div>
                            <button class="btn btn-default">S</button> -->
                            <div class="input-group">
                                <input type="text" class="form-control input-sm" placeholder="Search Mail" v-model="search_mail">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default" title="Search" @click="getInbox">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <button type="button" class="btn btn-default" title="Clear" @click="clearSearchMail">
                                        <i class="fa fa-close"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="box-body no-padding">
                        <div class="mailbox-controls">

                            <button type="button" class="btn btn-default">
                                <input type="checkbox" @click="selectAll" v-model="allSelected">
                            </button>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-default" title="Starred" :disabled="btnEnable" @click="submitStarred(0,0,true, '')">
                                    <i class="fa fa-fw fa-star"></i>
                                </button>
                                <button type="button" class="btn btn-default" title="Label" :disabled="btnEnable" data-toggle="modal" data-target="#modal-label-selection">
                                    <i class="fa fa-fw fa-tag"></i>
                                </button>
                                <button type="button" class="btn btn-default" title="Refesh inbox" @click="refeshInbox">
                                    <i class="fa fa-fw fa-refresh"></i>
                                </button>
                                <button type="button" class="btn btn-default" title="Trash" @click="deleteMessage(0,0,true)" :disabled="btnEnable">
                                    <i class="fa fa-fw fa-trash"></i>
                                </button>
                            </div>

                            <!-- <div class="pull-right">
                                1-50/200
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                                </div>
                            </div> -->
                            
                        </div>

                        <div class="p-5 text-center text-muted" v-show="loadingMessage">
                            <i class="fa fa-refresh fa-spin mr-3"></i> loading messages
                        </div>
                        <table class="table table-condensed table-hover tbl-custom">
                            <tbody>
                                <tr v-show="records.length == 0">
                                    <td class="text-muted text-center">No {{ $route.name }} record</td>
                                </tr>
                                <tr v-for="(inbox, index) in records" :key="index" @click="viewMessage(inbox, index)" :class="{'active': viewContent.id == inbox.id,'font-weight-bold': inbox.is_viewed == 0, 'text-muted': inbox.is_viewed == 1}">
                                    <td>
                                        <input type="checkbox" v-on:change="checkSelected" :id="inbox.id" :value="inbox" v-model="checkIds">
                                    </td>
                                    <td>
                                        <i v-show="inbox.label_id != 0" class="fa fa-tag mr-3" :style="{'color': inbox.label_color}"></i>
                                        {{ inbox.is_sent == 1 ? 'To: ' + checkEmail(inbox.received) : inbox.from_mail}}
                                    </td>
                                    <td>{{inbox.subject}}</td>
                                    <td class="text-right">
                                        <i :class="{'orange': true,'fa': true,'fa-fw': true, 'pointer': true, 'fa-star-o': inbox.is_starred == 0, 'fa-star': inbox.is_starred == 1}" 
                                            title="Starred"
                                            @click="submitStarred(inbox.id, inbox.is_starred, false, index)">
                                        </i>
                                    </td>
                                    <td><i class="fa fa-fw fa-paperclip" v-show="inbox.attachment != ''"></i></td>
                                    <td class="text-right">{{inbox.created_at}}</td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="box box-primary">
                    <div v-show="selectedMessage" class="text-center text-muted p-5 h-50">
                        No items Selected
                    </div>

                    <div v-show="MessageDisplay" class="box-header with-border">
                        <h3 class="box-title">Read Mail</h3>

                        <!-- <div class="box-tools pull-right">
                            <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Previous"><i class="fa fa-chevron-left"></i></a>
                            <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Next"><i class="fa fa-chevron-right"></i></a>
                        </div> -->

                    </div>

                    <div v-show="MessageDisplay" class="box-body no-padding">
                        <div class="mailbox-read-info">
                            <h3>{{ viewContent.subject }}</h3>
                            <h5 v-show="viewContent.is_sent == 0">From: {{ viewContent.from }} <span class="mailbox-read-time pull-right">{{ viewContent.date }}</span></h5>
                            <h5 v-show="viewContent.is_sent == 1">To: {{ checkEmail(viewContent.received) }} <span class="mailbox-read-time pull-right">{{ viewContent.date }}</span></h5>
                        </div>

                        <div class="mailbox-read-message">
                            <textarea class="form-control message-content" readonly>{{ viewContent.strippedHtml }}</textarea>
                        </div>
                    </div>

                    <!-- For Attachment -->

                    <div v-show="MessageDisplay && viewContent.attachment.url != ''" class="box-footer">
                        <ul class="mailbox-attachments clearfix">
                            <li>
                                <!-- <span class="mailbox-attachment-icon">
                                    <i class="fa fa-file-pdf-o"></i>
                                    <img class="img-attachment" id="img-read-mail-attach">
                                </span> -->
                                    
                                <div v-if="viewContent.attachment[0]" v-show="viewContent.is_sent == 0" class="mailbox-attachment-info">
                                    <a href="#" id="link-download-href" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> {{ viewContent.attachment[0]['name'] }}</a>
                                    <span class="mailbox-attachment-size">{{ bytesToSize(viewContent.attachment.size) }}</span>
                                </div>

                                <div v-show="viewContent.is_sent == 1" class="mailbox-attachment-info">
                                    <a :href="'/attachment/'+viewContent.attachment.filename" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> {{ viewContent.attachment.display_name }}</a>
                                    <span class="mailbox-attachment-size">{{ bytesToSize(viewContent.attachment.size) }}</span>
                                </div>

                            </li>
                        </ul>
                    </div>

                    <div v-show="MessageDisplay" class="box-footer">
                        <div class="pull-right">
                            <button type="button" class="btn btn-default" data-toggle="modal" @click="doReply" data-target="#modal-email-reply"><i class="fa fa-reply"></i> Reply</button>
                        </div>
                        <button type="button" class="btn btn-default" v-show="viewContent.deleted_at == null" @click="deleteMessage(viewContent.id, viewContent.index, false)"><i class="fa fa-trash-o"></i> Delete</button>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- Modal Send Email -->
        <div id="modal-compose-email" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Compose Message</h4>
                    </div>
                    <div class="modal-body relative">

                        <blockquote class="primary">
                            <p>Note: You can send multiple email 'contact01|contact02|contact03'</p>
                        </blockquote>

                        <form class="row" action="">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" v-model="withBcc" @click="toggleBcc">
                                        With Bcc
                                    </label>
                                </div>
                                <hr>
                            </div>

                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label style="color: #333">Country</label>
                                        <div>
                                            <select class="form-control pull-right" v-model="countryMailId" v-on:change="getTemplateList">
                                                <option value=""></option>
                                                <option v-for="option in listCountryAll.data" v-bind:value="option.id">
                                                    {{ option.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div  class="form-group">
                                            <label style="color: #333">Select template</label>
                                            <div>
                                                <select v-on:change="getTemplate" class="form-control pull-right" v-model="mailInfo">
                                                    <option  v-for="option in listMailTemplate.data" v-bind:value="option.id">
                                                        {{ option.title }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div :class="{'col-md-6': withBcc, 'col-md-12': !withBcc}" style="margin-top: 15px;">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.email}" class="form-group">
                                    <label style="color: #333" >To:</label>
                                    <input type="text" class="form-control" required="required" v-model="emailContent.email">
                                    <span v-if="messageForms.errors.email" v-for="err in messageForms.errors.email" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div :class="{'col-md-6': true}" v-show="withBcc" style="margin-top: 15px;">
                                <div class="form-group">
                                    <label style="color: #333" >Bcc:</label>
                                    <input type="text" class="form-control" required="required" v-model="emailContent.cc">
                                </div>
                            </div>


                            <div class="col-md-12" style="margin-top: 15px;">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.title}" class="form-group">
                                    <label style="color: #333">Titles</label>
                                    <input type="text" class="form-control" required="required" v-model="emailContent.title">
                                    <span v-if="messageForms.errors.title" v-for="err in messageForms.errors.title" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.content}" class="form-group">
                                    <label style="color: #333">Contents</label>
                                    <textarea rows="10" type="text" class="form-control" required="required" v-model="emailContent.content"></textarea>
                                    <span v-if="messageForms.errors.content" v-for="err in messageForms.errors.content" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label style="color: #333">Attachment</label>
                                    <input type="file" class="form-control" id="file_send" ref="file_send">
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" :disabled="sendBtn" @click="sendEmail('compose')">Send <i class="fa fa-send fa-fw"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Send Email -->


        <!-- Modal Send Reply Email -->
        <div id="modal-email-reply" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Reply</h4>
                    </div>
                    <div class="modal-body relative">
                        <form class="row" action="">

                            <div class="col-md-12">
                                <blockquote class="default">
                                    <textarea class="form-control message-content text-muted font-italic">{{ viewContent.strippedHtml }}</textarea>
                                </blockquote>
                            </div>


                            <div class="col-md-6">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" v-model="withTemplate" @click="toggleTemplate">
                                        With Template
                                    </label>
                                </div>
                                <hr>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" v-model="withBcc" @click="toggleBcc">
                                        With Bcc
                                    </label>
                                </div>
                                <hr>
                            </div>

                            <div class="col-md-12" v-show="withTemplate">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label style="color: #333">Country</label>
                                        <div>
                                            <select class="form-control pull-right" v-model="countryMailId" v-on:change="getTemplateList">
                                                <option value=""></option>
                                                <option v-for="option in listCountryAll.data" v-bind:value="option.id">
                                                    {{ option.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div  class="form-group">
                                            <label style="color: #333">Select template</label>
                                            <div>
                                                <select v-on:change="getTemplate" class="form-control pull-right" v-model="mailInfo">
                                                    <option  v-for="option in listMailTemplate.data" v-bind:value="option.id">
                                                        {{ option.title }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div :class="{'col-md-6': withBcc, 'col-md-12': !withBcc}" style="margin-top: 15px;">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.email}" class="form-group">
                                    <label style="color: #333" >To:</label>
                                    <input type="text" class="form-control" required="required" v-model="replyContent.email" :disabled="true">
                                    <span v-if="messageForms.errors.email" v-for="err in messageForms.errors.email" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6" style="margin-top: 15px;" v-show="withBcc">
                                <div class="form-group">
                                    <label style="color: #333" >Bcc:</label>
                                    <input type="text" class="form-control" required="required" v-model="replyContent.cc">
                                </div>
                            </div>

                            <div class="col-md-12" style="margin-top: 15px;">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.title}" class="form-group">
                                    <label style="color: #333">Titles</label>
                                    <input type="text" class="form-control" required="required" v-model="replyContent.title" :disabled="true">
                                    <span v-if="messageForms.errors.title" v-for="err in messageForms.errors.title" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.content}" class="form-group">
                                    <label style="color: #333">Contents</label>
                                    <textarea rows="10" type="text" class="form-control" required="required" v-model="replyContent.content"></textarea>
                                    <span v-if="messageForms.errors.content" v-for="err in messageForms.errors.content" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label style="color: #333">Attachment</label>
                                    <input type="file" id="file_reply" ref="file_reply" class="form-control" :disabled="true">
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" :disabled="sendBtn" @click="sendEmail('reply')">Send <i class="fa fa-send fa-fw"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Send Reply Email -->


        <!-- Modal Label -->
        <div id="modal-label-selection" class="modal fade">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Label</h4>
                    </div>
                    <div class="modal-body relative">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Label Name</label>
                                    <select class="form-control" v-model="updateModel.label_id">
                                        <option value=""></option>
                                        <option v-for="(label, index) in $parent._data.listLabel" v-bind:value="label.id" :key="index">
                                            {{ label.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                                
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="submitLabel">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Label -->

    </div>
</template>

<script>
import axios from 'axios';
import { mapState } from 'vuex';
export default {
    name: 'AppInbox',
    data() {
        return {
            search_mail: '',
            emailContent : {
                cc: '',
                email: '',
                title: '',
                content: ''
            },
            replyContent : {
                cc: '',
                email: '',
                title: '',
                content: ''
            },
            viewContent : {
                received: '',
                is_sent: '',
                from_mail: '',
                index: '',
                id: '',
                date: '',
                from: '',
                subject: '',
                strippedHtml: '',
                deleted_at: '',
                attachment: {
                    url: '',
                    size: '',
                    type: '',
                    filename: '',
                    display_name: '',
                },
            },
            records: [],
            loadingMessage: false,
            selectedMessage: true,
            MessageDisplay: false,
            checkIds: [],
            btnEnable: true,
            inboxCount : 0,
            mailInfo: {},
            countryMailId: '',
            updateModel: {
                label_id: '',
            },
            allSelected: false,
            withTemplate: false,
            withBcc: false,
            sendBtn: false,
        }
    },

    created() {
        this.getListCountries();
        this.$root.$refs.AppInbox = this;
    },

    computed: {
        ...mapState({
            user: state => state.storeAuth.currentUser,
            listCountryAll: state => state.storePublisher.listCountryAll,
            listMailTemplate: state => state.storeExtDomain.listMailTemplate,
            messageForms: state => state.storeMailgun.messageForms,
        })
    },

    watch:{
        $route (to, from){
            this.getInbox();
        }
    },

    mounted() {
        this.getInbox();
    },

    methods: {
        clearSearchMail() {
            this.search_mail = '';
            this.getInbox();
        },

        checkEmail(email) {
            let result = '';
            // console.log(email.indexOf("|"))
            if (email.indexOf("|") > 0) {
                var spl = email.split("|")

                for (var index in spl) {
                    result += '<'+spl[index]+'> ';
                }
            } else {
                result = '<'+email+'>';
            }

            return result;
        },

        doReply() {
            this.clearMessageform();
            this.replyContent.email = this.viewContent.from_mail;
            this.replyContent.title = this.viewContent.subject;

            axios.post('/api/mail/get-reply', {
                email: this.viewContent.from_mail,
            })
            .then((res) => {
                console.log(res.data)
            })
        },

        toggleTemplate() {
            if (this.withTemplate) {
                this.withTemplate = false;
            } else {
                this.withTemplate = true;
            }
        },

        toggleBcc() {
            if (this.withBcc) {
                this.withBcc = false;
            } else {
                this.withBcc = true;
            }
        },

        deleteMessage(id, index, is_all) {
            swal.fire({
                title: "Are you sure ?",
                html: "Delete these message?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it'
            })
            .then((result) => {
                if (result.isConfirmed) {

                    let ids = [];
                    for (var chk in this.checkIds) {
                        ids.push(this.checkIds[chk].id);
                    } 

                    axios.get('/api/mail/delete-message',{ params: { id: is_all ? ids : id } })

                    if (is_all) {
                        this.getInbox();
                    }else{
                        this.records.splice(index, 1);
                    }

                    this.selectedMessage = true;
                    this.MessageDisplay = false;
                    this.viewContent = {
                        received: '',
                        is_sent: '',
                        from_mail: '',
                        index: '',
                        id: '',
                        date: '',
                        from: '',
                        subject: '',
                        strippedHtml: '',
                        deleted_at: '',
                        attachment: {
                            url: '',
                            size: '',
                            type: '',
                            filename: '',
                            display_name: '',
                        },  
                    }

                    this.checkIds = [];

                    swal.fire(
                        'Deleted!',
                        'Messages is move to Trash.',
                        'success'
                    )
                }
            });
        },

        selectAll() {
            this.checkIds = [];
            if (!this.allSelected) {
                for (var index in this.records) {
                    this.checkIds.push(this.records[index]);
                }
                this.btnEnable = false;
            } else {
                this.btnEnable = true;
            }
        },

        submitLabel() {
            let ids = [];
            for (var chk in this.checkIds) {
                ids.push(this.checkIds[chk].id);
            } 

            axios.post('/api/mail/labeling', {
                ids: ids,
                label_id: this.updateModel.label_id
            })
            .then((res) => {
                // console.log(res.data)

                for (var rec in this.records) {
                    for (var chk in this.checkIds) {
                        if (this.checkIds[chk].id == this.records[rec].id) {
                            this.records[rec].label_id = this.updateModel.label_id;
                        }
                    }     
                }

            })
        },

        async getTemplateList() {
            await this.$store.dispatch('getListMails', { params: { country_id: this.countryMailId, full_page: 1} });
        },

        getTemplate() {
            this.emailContent = this.listMailTemplate.data.filter(item => item.id === this.mailInfo)[0];
            this.replyContent = this.listMailTemplate.data.filter(item => item.id === this.mailInfo)[0];
        },

        async getListCountries(params) {
            await this.$store.dispatch('actionGetListCountries', params);
        },

        submitStarred(id, is_starred, is_all, position) {
            let id_mail = is_all ? this.checkIds : id;

            if (!is_all) {
                this.records[position].is_starred = is_starred == 1 ? 0:1;
            } 
            else {
                for (var rec in this.records) {
                    for (var chk in this.checkIds) {
                        if (this.checkIds[chk].id == this.records[rec].id) {
                            this.records[rec].is_starred = 1;
                        }
                    }     
                }
            }

            axios.get('/api/mail/starred', {
                params: {
                    id: id_mail,
                    is_starred: is_starred,
                }
            })
            .then((res) => {
                // console.log(res.data)
                if (is_all){
                    this.getInbox();
                } 
                // this.btnEnable = true;
            })
        },

        checkSelected() {
            this.btnEnable = true;
            if( this.checkIds.length > 0 ){
                this.btnEnable = false;
            }
        },

        refeshInbox() {
            this.getInbox();
        },

        bytesToSize(bytes) {
            var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
            if (bytes == 0) return '0 Byte';
            var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
            return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
        },

        viewMessage(inbox, index) {
            console.log(inbox);
            console.log(JSON.parse(inbox.attachment));
            let content = JSON.parse(inbox.body);
            let from_mail = inbox.from_mail;
            let is_sent = inbox.is_sent;
            let reply_to = '';
            this.viewContent.attachment  = JSON.parse(inbox.attachment);

            // if(inbox.attachment != '') {
            //     let url = JSON.parse(inbox.attachment).url;

            //     if (is_sent == 0) { // For receiver
            //         axios.post('/api/mail/show-attachment', {
            //             url: url
            //         },{ responseType: 'arraybuffer' })
            //         .then((res) => {
            //             let blob = new Blob( [ res.data ] );
            //             let link = document.getElementById( 'link-download-href' );
            //             link.href = URL.createObjectURL( blob );
            //             link.download = url;
            //             this.viewContent.attachment  = JSON.parse(inbox.attachment);
            //         })
            //     } else { // For Sender
            //         this.viewContent.attachment = JSON.parse(inbox.attachment);
            //     }

            // } else {
            //     this.viewContent.attachment = {
            //         url: '',
            //         size: '',
            //         type: '',
            //         filename: '',
            //         display_name: '',
            //     }
            // }
                

            if (from_mail.search("<") > 0) {
                var spl = from_mail.split("<")[1]
                reply_to = spl.slice(0, -1);
            }

            this.selectedMessage = false;
            this.MessageDisplay = true;
            this.viewContent.from = inbox.from_mail;
            this.viewContent.strippedHtml = content['body-plain'];
            this.viewContent.date = inbox.created_at;
            this.viewContent.subject = inbox.subject;
            this.viewContent.index = index;
            this.viewContent.id = inbox.id;
            this.viewContent.deleted_at = inbox.deleted_at;
            this.viewContent.from_mail = reply_to == '' ? inbox.from_mail : reply_to;
            this.viewContent.is_sent = is_sent;
            this.viewContent.received = inbox.received;

            if (inbox.is_viewed == 0){
                axios.get('/api/mail/is-viewed',{ params: { id: inbox.id } })
                this.records[index].is_viewed = 1
            }
            
        },

        async sendEmail(type) {
            let cc = '';
            if (type == 'reply') {
                cc = (typeof(this.replyContent.cc) == "undefined" ) ? "": this.replyContent.cc;
            } else {
                cc = (typeof(this.emailContent.cc) == "undefined" ) ? "": this.emailContent.cc;
            }

            this.sendBtn = true;
            this.formData = new FormData();
            this.formData.append('cc', cc);
            this.formData.append('email', type == 'reply' ? this.replyContent.email : this.emailContent.email);
            this.formData.append('title', type == 'reply' ? this.replyContent.title : this.emailContent.title);
            this.formData.append('content', type == 'reply' ? this.replyContent.content : this.emailContent.content);
            this.formData.append('attachment', type == 'reply' ? this.$refs.file_reply.files[0] : this.$refs.file_send.files[0]);

            await this.$store.dispatch('actionSendMailgun', this.formData);

            if (this.messageForms.action == 'success') {
                $("#modal-compose-email").modal('hide');
                
                swal.fire(
                    'Send',
                    'Successfully Send Email',
                    'success'
                )

                this.emailContent = {
                    cc: '',
                    email: '',
                    title: '',
                    content: ''
                }

                this.replyContent = {
                    cc: '',
                    email: '',
                    title: '',
                    content: ''
                }
                
                //this.getStatus();
                this.getInbox();
                this.sendBtn = false;
                this.countryMailId = '';
                this.mailInfo = {};
            }
        },

        getStatus() {
            axios.get('/api/mail/status')
                .then((res) => {
                    console.log(res.data)
                })
        },

        getInbox(){
        //    this.loadingMessage = true;
            axios.post('/api/mail/filter-recipient',{
                'email': this.user.work_mail,
                'param': this.$route.name,
                'search_mail': this.search_mail,
                'label_id': this.$route.query.label_id,
            })
            .then((response) => {
                this.records = response.data.inbox;
                // this.loadingMessage = false;
                this.inboxCount = response.data.count;
                // console.log(this.inboxCount)
            })
            .catch((error) => {
                console.log(error);
                error => error;
            });
        },

        clearMessageform() {
            this.$store.dispatch('clearMessageform');
        },
    }
}
</script>