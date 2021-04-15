<template>
    <div>
        <div class="row">

            <div class="col-md-12" v-show="cardInbox">
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

                            <div class="pull-right">
                                {{paginate.from + '-' + paginate.to + " / " + paginate.total}}
                                <div class="btn-group">
                                    <button type="button" :disabled="records.current_page == 1" class="btn btn-default btn-sm" @click="getInbox(page = paginate.prev)"><i class="fa fa-chevron-left"></i></button>
                                    <button type="button" :disabled="records.next_page_url == null" class="btn btn-default btn-sm" @click="getInbox(page = paginate.next)"><i class="fa fa-chevron-right"></i></button>
                                </div>
                            </div>

                        </div>

                        <div class="p-5 text-center text-muted" v-show="loadingMessage">
                            <i class="fa fa-refresh fa-spin mr-3"></i> loading messages
                        </div>
                        <div class="table-responsive">
                            <table class="table table-condensed table-hover" style="table-layout: auto; width: 100%">
                                <tbody>
                                <tr v-show="paginate.total == 0">
                                    <td class="text-muted text-center">No {{ $route.name }} record</td>
                                </tr>

                                <tr
                                    v-for="(inbox, index) in records.data"
                                    :key="index"
                                    :class="{
                                        'text-muted': $route.name == 'Inbox'
                                            ? inbox.thread
                                                ? !checkIsViewedForThreads(inbox.thread)
                                                : false
                                            : inbox.is_viewed == 1,
                                        'font-weight-bold': $route.name == 'Inbox'
                                            ? inbox.thread
                                                ? checkIsViewedForThreads(inbox.thread)
                                                : false
                                            : inbox.is_viewed == 0,
                                        'active': viewContent.id == inbox.id,
                                    }">
                                    <td style="width:30px;">
                                        <input
                                            v-model="checkIds"
                                            :id="inbox.id"
                                            :value="inbox"
                                            type="checkbox"

                                            @change="checkSelected">
                                    </td>

                                    <td @click="viewMessage(inbox, index)">
                                        <i
                                            v-show="inbox.label_id != 0"
                                            :style="{'color': inbox.label_color}"
                                            class="fa fa-tag mr-3">
                                        </i>
                                        {{
                                            $route.name == 'Inbox'
                                                ? inbox.thread
                                                    ? displayInboxNameAndThreadCount(inbox.thread)
                                                    : inbox.sender
                                                : inbox.is_sent == 1
                                                    ? 'To: ' + checkEmail(inbox.received)
                                                    : inbox.from_mail
                                        }}

                                        <span
                                            v-if="inbox.thread && $route.name == 'Inbox'"
                                            class="badge badge-pill badge-secondary">

                                            {{ inbox.thread.length }}
                                        </span>
                                    </td>

                                    <td @click="viewMessage(inbox, index)">{{inbox.subject}}</td>

                                    <td style="width:30px;" class="text-right">
                                        <i
                                            :class="{
                                                'fa': true,
                                                'fa-fw': true,
                                                'orange': true,
                                                'pointer': true,
                                                'fa-star': inbox.is_starred == 1,
                                                'fa-star-o': inbox.is_starred == 0
                                            }"
                                            title="Starred"

                                            @click="submitStarred(inbox.id, inbox.is_starred, false, index)">
                                        </i>
                                    </td>

                                    <td @click="viewMessage(inbox, index)">
                                        <i
                                            v-show="inbox.attachment != '' && inbox.attachment != '[]'"
                                            class="fa fa-fw fa-paperclip">
                                        </i>
                                    </td>

                                    <td @click="viewMessage(inbox, index)" class="text-right">{{inbox.clean_date}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12" v-show="cardReadMessage">
                <div class="box box-primary">
                    <div v-show="selectedMessage" class="text-center text-muted p-5 h-50">
                        No items Selected
                    </div>

                    <div v-show="MessageDisplay" class="box-header with-border">
                        <h3 class="box-title">
                            <button type="button" title="Back" class="btn btn-default btn-sm mr-3" @click="clearViewing()"><i class="fa fa-chevron-left"></i></button>
                            Read Mail
                        </h3>

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
<!--                            <textarea class="form-control message-content" readonly>{{ viewContent.strippedHtml }}</textarea>-->
<!--                            <div id="htmlDiv" v-html="viewContent.strippedHtml" style="white-space: pre-line"></div>-->
                            <div>
                                <iframe
                                    ref="iframe"
                                    width="100%"
                                    frameborder="0">

                                </iframe>
                            </div>
                        </div>
                    </div>

                    <!-- For Attachment -->

                    <div v-show="MessageDisplay && viewContent.attachment.url != ''" class="box-footer">
                        <ul class="mailbox-attachments clearfix">

                                <!-- <span class="mailbox-attachment-icon">
                                    <i class="fa fa-file-pdf-o"></i>
                                    <img class="img-attachment" id="img-read-mail-attach">
                                </span> -->
                            <li v-show="viewContent.is_sent == 0 && viewContent.attachment.length != 0" v-for="(attach, index) in viewContent.attachment" :key="index">
                                <div class="mailbox-attachment-info mailbox-attachment-info-custom">
                                    <a href="#" :title="attach.name" :id="'link-download-href-'+index" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> {{ attach.name }}</a>
                                    <span class="mailbox-attachment-size">{{ bytesToSize(attach.size) }}</span>
                                </div>
                            </li>

                            <li v-show="viewContent.is_sent == 1">
                                <div class="mailbox-attachment-info mailbox-attachment-info-custom">
                                    <a :href="'/attachment/'+viewContent.attachment.filename" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> {{ viewContent.attachment.display_name }}</a>
                                    <span class="mailbox-attachment-size">{{ bytesToSize(viewContent.attachment.size) }}</span>
                                </div>

                            </li>
                        </ul>
                    </div>

                    <div v-show="MessageDisplay" class="box-footer">
                        <div class="pull-right">
                            <button type="button" class="btn btn-default" data-toggle="modal" @click="doReply" data-target="#modal-email-reply">
                                <i class="fa fa-reply"></i>
                                {{ viewContent.is_sent === 1 ? 'Follow up' : 'Reply' }}
                            </button>
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
                            <p>Note</p>
                            <ul>
                                <li>You can send an email to multiple recipients <strong>'contact01|contact02|contact03' or 'contact01,contact02,contact03'</strong></li>
                                <li>For bulk sending, <strong>only 10 recipients are allowed per email</strong></li>
                                <li>You can edit the tags by clicking it and pressing <strong>enter key</strong> afterwards</li>
                            </ul>
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
                                        <label style="color: #333">Language</label>
                                        <div>
                                            <select class="form-control pull-right" v-model="countryMailId" v-on:change="getTemplateList">
                                                <option value=""></option>
                                                <option v-for="option in listLanguages.data" v-bind:value="option.id">
                                                    {{ option.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div  class="form-group">
                                            <label style="color: #333">Select template</label>
                                            <div>
                                                <select v-on:change="getTemplate('send')" class="form-control pull-right" v-model="mailInfo">
                                                    <option  v-for="option in listMailTemplate.data" v-bind:value="option.id">
                                                        {{ option.mail_name }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" style="margin-top: 15px;">
                                <div
                                    :class="{
                                        'has-error': messageForms.errors.email
                                        || checkEmailValidationError(messageForms.errors)
                                    }"
                                    class="form-group">

                                    <label style="color: #333" >To:</label>
<!--                                    <input type="text" class="form-control" required="required" v-model="emailContent.email">-->

                                    <p
                                        v-if="emailContent.email.length"
                                        class="text-primary small"
                                        style="cursor: pointer"

                                        @click="emailContent.email = []">
                                        Remove all emails
                                    </p>

                                    <vue-tags-input
                                        v-model="tag"
                                        placeholder=""
                                        :max-tags="10"
                                        :allow-edit-tags="true"
                                        :separators="separators"
                                        :tags="emailContent.email"
                                        :class="{
                                            'vue-tag-error': messageForms.errors.email
                                            || checkEmailValidationError(messageForms.errors)
                                        }"

                                        @tags-changed="newTags => emailContent.email = newTags"
                                    />

                                    <span
                                        v-if="messageForms.errors.email"
                                        v-for="err in messageForms.errors.email"
                                        class="text-danger">
                                        {{ err }}
                                    </span>

                                    <span v-if="checkEmailValidationError(messageForms.errors)" class="text-danger">
                                        The email field must contain valid emails.
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-12" v-show="withBcc" style="margin-top: 15px;">
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
                                        <label style="color: #333">Language</label>
                                        <div>
                                            <select class="form-control pull-right" v-model="countryMailId" v-on:change="getTemplateList">
                                                <option value=""></option>
                                                <option v-for="option in listLanguages.data" v-bind:value="option.id">
                                                    {{ option.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div  class="form-group">
                                            <label style="color: #333">Select template</label>
                                            <div>
                                                <select v-on:change="getTemplate('reply')" class="form-control pull-right" v-model="mailInfo">
                                                    <option  v-for="option in listMailTemplate.data" v-bind:value="option.id">
                                                        {{ option.mail_name }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" style="margin-top: 15px;">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.email}" class="form-group">
                                    <label style="color: #333" >To:</label>
<!--                                    <input type="text" class="form-control" required="required" v-model="replyContent.email" :disabled="true">-->

                                    <vue-tags-input
                                        v-model="tag"
                                        ref="replyTag"
                                        placeholder=""
                                        :allow-edit-tags="true"
                                        :separators="separators"
                                        :tags="replyContent.email"
                                        :disabled="viewContent.is_sent !== 1"
                                        :class="{'vue-tag-error': messageForms.errors.email}"

                                        @tags-changed="newTags => replyContent.email = newTags"
                                    />

                                    <span v-if="messageForms.errors.email" v-for="err in messageForms.errors.email" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-12" style="margin-top: 15px;" v-show="withBcc">
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
                                    <input type="file" id="file_reply" ref="file_reply" class="form-control">
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
import { createTag, createTags } from '@johmun/vue-tags-input';

export default {
    name: 'AppInbox',
    data() {
        return {
            search_mail: '',
            emailContent : {
                cc: '',
                email: [],
                title: '',
                content: ''
            },
            replyContent : {
                cc: '',
                email: [],
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
                    name: '',
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
            paginate: {
                next: 0,
                prev: 0,
                from: 0,
                to: 0,
                total: 0,
            },
            cardInbox: true,
            cardReadMessage: false,

            // for tag input component
            tag: '',
            separators: [';', ',', '|', ' ']
        }
    },

    created() {
        this.getListCountries();
        this.$root.$refs.AppInbox = this;

        let language = this.listLanguages.data;
        if( language.length === 0 ){
            this.getListLanguages();
        }
    },

    computed: {
        ...mapState({
            user: state => state.storeAuth.currentUser,
            listCountryAll: state => state.storePublisher.listCountryAll,
            listMailTemplate: state => state.storeExtDomain.listMailTemplate,
            messageForms: state => state.storeMailgun.messageForms,
            listLanguages: state => state.storePublisher.listLanguages,
        })
    },

    watch:{
        $route (to, from){
            this.getInbox();
            this.clearViewing();
        }
    },

    mounted() {
        this.getInbox();
    },

    methods: {
        checkIsViewedForThreads(thread) {
            return thread.some(el => el.is_viewed === 0)
        },

        displayInboxNameAndThreadCount(thread) {
            let senders = thread.map(a => a.sender === this.user.work_mail ? 'me' : a.sender);
            senders = senders.filter((item, index) => senders.indexOf(item) === index);
            return senders.join(', ');
        },

        clearViewing() {
            this.cardInbox = true;
            this.cardReadMessage = false;
        },

        async getListLanguages() {
            await this.$store.dispatch('actionGetListLanguages');
        },

        clearSearchMail() {
            this.search_mail = '';
            this.getInbox();
        },

        checkEmail(email) {
            let result = '';
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
            // this.replyContent.email.push(this.viewContent.from_mail);
            this.replyContent.email = [];
            this.replyContent.title = this.viewContent.subject;

            // add email

            if (this.viewContent.is_sent === 1) {
                this.replyContent.email = createTags(this.viewContent.received.replace(/\s/g, '')
                    .split(/[|,]/g)
                    .filter(function (email) {
                        return email !== '';
                    }))
            } else {
                let tag = createTag(this.viewContent.from_mail, [this.viewContent.from_mail]);
                this.$refs.replyTag.addTag(tag);
            }

            axios.post('/api/mail/get-reply', {
                email: this.viewContent.from_mail,
            })
            .then((res) => {
                // console.log(res.data)
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
                    .then((res) => {
                        if (is_all) {
                            this.getInbox();
                            this.paginate.total -= ids.length;
                        }else{
                            this.records.data.splice(index, 1);
                            this.paginate.total--;
                        }
                    })

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
                            name: '',
                        },
                    }

                    this.checkIds = [];

                    this.clearViewing()

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
                for (var index in this.records.data) {
                    this.checkIds.push(this.records.data[index]);
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

                for (var rec in this.records.data) {
                    for (var chk in this.checkIds) {
                        if (this.checkIds[chk].id == this.records.data[rec].id) {
                            this.records.data[rec].label_id = this.updateModel.label_id;
                        }
                    }
                }

            })
        },

        async getTemplateList() {
            await this.$store.dispatch('getListMails', { params: { country_id: this.countryMailId, full_page: 1} });
        },

        getTemplate(mod) {
            // this.emailContent = this.listMailTemplate.data.filter(item => item.id === this.mailInfo)[0];
            // this.replyContent = this.listMailTemplate.data.filter(item => item.id === this.mailInfo)[0];
            let content = mod === 'send' ? this.emailContent : this.replyContent;

            let template = this.listMailTemplate.data.filter(item => item.id === this.mailInfo)[0]

            for (let key in template) {
                if(template.hasOwnProperty(key)){
                    content[key] = template[key]
                }
            }
        },

        async getListCountries(params) {
            await this.$store.dispatch('actionGetListCountries', params);
        },

        submitStarred(id, is_starred, is_all, position) {
            let id_mail = is_all ? this.checkIds : id;

            if (!is_all) {
                this.records.data[position].is_starred = is_starred == 1 ? 0:1;
            }
            else {
                for (var rec in this.records.data) {
                    for (var chk in this.checkIds) {
                        if (this.checkIds[chk].id == this.records.data[rec].id) {
                            this.records.data[rec].is_starred = 1;
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

            let body = JSON.parse(inbox.body);
            let body_html = JSON.parse(inbox.body_html);
            let stripped_html = JSON.parse(inbox.stripped_html);

            let content = body_html
                ? body_html['body-html']
                : stripped_html
                    ? stripped_html['stripped-html']
                    : body['body-plain']
            let from_mail = inbox.from_mail;
            let is_sent = inbox.is_sent;
            let reply_to = '';

            if(inbox.attachment != '' && inbox.attachment != '[]') {
                let attach = JSON.parse(inbox.attachment);
                this.viewContent.attachment  = attach;

                if (is_sent == 0) {
                    for(var index in attach) {
                        axios.post('/api/mail/show-attachment', {
                            url: attach[index]['url']
                        },{ responseType: 'arraybuffer' })
                        .then((res) => {

                            let blob = new Blob([res.data], { type: res.headers['content-type'] })
                            var res = res.headers['content-type'].split("/");

                            let link = document.getElementById( 'link-download-href-' + index );
                            link.href = window.URL.createObjectURL(blob);
                            link.download = attach[index]['name'];
                        })
                    }
                }

            } else {
                this.viewContent.attachment = {
                    url: '',
                    size: '',
                    name: '',
                }
            }


            if (from_mail.search("<") > 0) {
                var spl = from_mail.split("<")[1]
                reply_to = spl.slice(0, -1);
            }

            content = '<pre style="white-space: pre-line;"> <base target="_blank">' + content + '</pre>'

            this.selectedMessage = false;
            this.MessageDisplay = true;
            this.viewContent.from = inbox.from_mail;
            this.viewContent.strippedHtml = content;
            this.viewContent.date = inbox.full_clean_date;
            this.viewContent.subject = inbox.subject;
            this.viewContent.index = index;
            this.viewContent.id = inbox.id;
            this.viewContent.deleted_at = inbox.deleted_at;
            this.viewContent.from_mail = reply_to == '' ? inbox.from_mail : reply_to;
            this.viewContent.is_sent = is_sent;
            this.viewContent.received = inbox.received;

            if (inbox.is_viewed == 0){
                axios.get('/api/mail/is-viewed',{ params: { id: inbox.id } })
                this.records.data[index].is_viewed = 1
            }

            this.cardInbox = false;
            this.cardReadMessage = true;

            this.iFrameLoader(this.viewContent.strippedHtml)
        },

        iFrameLoader(iframeBody){
            const iFrameEl = this.$refs.iframe
            const doc = iFrameEl.contentWindow.document
            doc.open()
            doc.write(iframeBody)
            doc.close()
            iFrameEl.onload = () => {
                iFrameEl.style.height = "0"
                this.$nextTick(() => {
                    iFrameEl.style.height = doc.body.scrollHeight + 'px';
                });
            }
        },

        async sendEmail(type) {
            let cc = '';
            if (type == 'reply') {
                cc = (typeof(this.replyContent.cc) == "undefined" ) ? "": this.replyContent.cc;
            } else {
                cc = (typeof(this.emailContent.cc) == "undefined" ) ? "": this.emailContent.cc;
            }

            // this.sendBtn = true;
            this.formData = new FormData();
            this.formData.append('cc', cc);
            this.formData.append('email', type == 'reply' ? JSON.stringify(this.replyContent.email) : JSON.stringify(this.emailContent.email));
            // this.formData.append('email', type == 'reply' ? this.replyContent.email : this.emailContent.email);
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
                    email: [],
                    title: '',
                    content: ''
                }

                this.replyContent = {
                    cc: '',
                    email: [],
                    title: '',
                    content: ''
                }

                //this.getStatus();
                this.getInbox();
                // this.sendBtn = false;
                this.countryMailId = '';
                this.mailInfo = {};
            }
        },

        getStatus() {
            axios.get('/api/mail/status')
                .then((res) => {
                    // console.log(res.data)
                })
        },

        getInbox(page = 1){
        //    this.loadingMessage = true;
            if(this.user.work_mail) {
                axios.get('/api/mail/filter-recipient?page=' + page, {
                    params : {
                        'email': this.user.work_mail,
                        'param': this.$route.name,
                        'search_mail': this.search_mail,
                        'label_id': this.$route.query.label_id,
                    }
                })
                .then((response) => {
                    this.records = response.data.inbox;
                    this.inboxCount = response.data.count;

                    this.paginate.to = this.records.to == null ? 0:this.records.to;
                    this.paginate.total = this.records.total == null ? 0:this.records.total;
                    this.paginate.from = this.records.from == null ? 0:this.records.from;

                    if(this.records.next_page_url != null) {
                        let page = this.records.next_page_url.split("=")[1];
                        this.paginate.next = page;
                    }

                    if(this.records.prev_page_url != null) {
                        let page = this.records.prev_page_url.split("=")[1];
                        this.paginate.prev = page;
                    }

                })
                .catch((error) => {
                    console.log(error);
                    error => error;
                });
            }

        },

        clearMessageform() {
            this.$store.dispatch('clearMessageform');
        },

        checkEmailValidationError(error){
            return Object.keys(error).some(function(err){ return ~err.indexOf("email.") })
        }
    }
}
</script>

<style>

.vue-tags-input {
    width: 100% !important;
    max-width: 100% !important;
    color: #495057;
    display: block;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    margin-top: 10px;
    border-radius: .25rem;
    background-color: #fff;
    border: 1px solid #ced4da;
    background-clip: padding-box;
    /*height: calc(1.5em + .75rem + 2px);*/
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}

.vue-tags-input .ti-tag{
    background-color: floralwhite !important ;
    color: #495057;
    border: 1px solid grey;
}

.ti-tag.ti-valid {
    color: #495057 !important
}

.ti-input {
    border: none !important;
}

.ti-tag .ti-actions {
    margin-left: 7px !important;
    border-radius: 50%;
    border: 1px solid #495057;
}

.vue-tags-input.ti-focus .ti-input {
    outline: 0 none;
    border-color: rgba(2, 117, 216, 0.3);
    box-shadow: 0 2px 2px rgba(0, 0, 0, 0.075) inset, 0 0 10px rgba(2, 117, 216, 0.4);
}

.vue-tag-error {
    border-color: red !important;
}
</style>
