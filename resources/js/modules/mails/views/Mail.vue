<template>
    <div>
        <div class="row">
            
            <!-- Side menu section -->
            <div class="col-md-2">
                <button class="btn btn-success btn-lg btn-block mb-3" data-target="#modal-compose-email" data-toggle="modal">Compose</button>

                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Folders</h3>

                        <div class="box-tools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body no-padding">
                        <ul class="list-group">
                            <li class="list-group-item active">
                                <i class="fa fa-fw fa-inbox"></i> Inbox <span class="label label-primary pull-right">{{inboxCount}}</span>
                            </li>
                            <li class="list-group-item">
                                <i class="fa fa-fw fa-mail-reply"></i> Sent
                            </li>
                            <li class="list-group-item">
                                <i class="fa fa-fw fa-star"></i> Starred
                            </li>
                            <li class="list-group-item">
                                <i class="fa fa-fw fa-trash"></i> Trash
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Labels</h3>

                        <div class="box-tools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body no-padding">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <i class="fa fa-circle-o red"></i> Important
                            </li>
                            <li class="list-group-item">
                                <i class="fa fa-circle-o orange"></i> Promotions
                            </li>
                            <li class="list-group-item">
                                <i class="fa fa-circle-o green"></i> Social
                            </li>
                        </ul>
                    </div>
                </div>

                        
            </div>


            <!-- Middle Content -->
            <div class="col-md-5">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Inbox</h3>

                        <div class="box-tools pull-right">
                            <div class="has-feedback">
                                <input type="text" class="form-control input-sm" placeholder="Search Mail">
                            </div>
                        </div>
                    </div>
                    <div class="box-body no-padding">
                        <div class="mailbox-controls">

                            <button type="button" class="btn btn-default">
                                <input type="checkbox">
                            </button>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-default" title="Starred" :disabled="btnEnable" @click="submitStarred(0,0,true)">
                                    <i class="fa fa-fw fa-star"></i>
                                </button>
                                <button type="button" class="btn btn-default" title="Label" :disabled="btnEnable" data-toggle="modal" data-target="#modal-label">
                                    <i class="fa fa-fw fa-tag"></i>
                                </button>
                                <button type="button" class="btn btn-default" title="Refesh inbox" @click="refeshInbox">
                                    <i class="fa fa-fw fa-refresh"></i>
                                </button>
                                <button type="button" class="btn btn-default" title="Trash" :disabled="btnEnable">
                                    <i class="fa fa-fw fa-trash"></i>
                                </button>
                            </div>

                            <div class="pull-right">
                                1-50/200
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                                </div>
                            </div>
                            
                        </div>

                        <div class="p-5 text-center text-muted" v-show="loadingMessage">
                            <i class="fa fa-refresh fa-spin mr-3"></i> loading messages
                        </div>
                        <table class="table table-condensed table-hover">
                            <tbody>
                                <tr v-for="(inbox, index) in records" :key="index" @click="viewMessage(inbox)">
                                    <td>
                                        <input type="checkbox" v-on:change="checkSelected" :id="inbox.id" :value="inbox" v-model="checkIds">
                                    </td>
                                    <td>{{inbox.from_mail}}</td>
                                    <td>{{inbox.subject}}</td>
                                    <td class="text-right">
                                        <i :class="{'orange': true,'fa': true,'fa-fw': true, 'pointer': true, 'fa-star-o': inbox.is_starred == 0, 'fa-star': inbox.is_starred == 1}" 
                                            title="Starred"
                                            @click="submitStarred(inbox.id, inbox.is_starred, false)">
                                        </i>
                                    </td>
                                    <td><i class="fa fa-fw fa-paperclip"></i></td>
                                    <td class="text-right">{{inbox.created_at}}</td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!-- Right Content  -->
            <div class="col-md-5">
                <div class="box box-primary">
                    <div v-show="selectedMessage" class="text-center text-muted m-5 p-5">
                        No items Selected
                    </div>

                    <div v-show="MessageDisplay" class="box-header with-border">
                        <h3 class="box-title">Read Mail</h3>

                        <div class="box-tools pull-right">
                            <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Previous"><i class="fa fa-chevron-left"></i></a>
                            <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Next"><i class="fa fa-chevron-right"></i></a>
                        </div>
                    </div>

                    <div v-show="MessageDisplay" class="box-body no-padding">
                        <div class="mailbox-read-info">
                            <h3>{{ viewContent.subject }}</h3>
                            <h5>From: {{ viewContent.from }} <span class="mailbox-read-time pull-right">{{ viewContent.date }}</span></h5>
                        </div>

                        <div class="mailbox-read-message">
                            {{ viewContent.strippedHtml }}
                        </div>
                    </div>

                    <!-- For Attachment -->

                    <!-- <div v-show="MessageDisplay" class="box-footer">
                        <ul class="mailbox-attachments clearfix">
                            <li>
                                <span class="mailbox-attachment-icon"><i class="fa fa-file-pdf-o"></i></span>

                                <div class="mailbox-attachment-info">
                                <a href="#" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> Sep2014-report.pdf</a>
                                    <span class="mailbox-attachment-size">
                                        1,245 KB
                                        <a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                                    </span>
                                </div>
                            </li>
                        </ul>
                    </div> -->

                    <div v-show="MessageDisplay" class="box-footer">
                        <div class="pull-right">
                            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-reply"><i class="fa fa-reply"></i> Reply</button>
                        </div>
                        <button type="button" class="btn btn-default"><i class="fa fa-trash-o"></i> Delete</button>
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
                        <form class="row" action="">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label style="color: #333">Country</label>
                                        <div>
                                            <select class="form-control pull-right">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div  class="form-group">
                                            <label style="color: #333">Select template</label>
                                            <div>
                                                <select class="form-control pull-right">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" style="margin-top: 15px;">
                                <div class="form-group">
                                    <label style="color: #333" >Email Name</label>
                                    <input type="text" class="form-control" value="" required="required" v-model="emailContent.email">
                                </div>
                            </div>


                            <div class="col-md-12" style="margin-top: 15px;">
                                <div class="form-group">
                                    <label style="color: #333">Titles</label>
                                    <input type="text" class="form-control" value="" required="required" v-model="emailContent.title">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label style="color: #333">Contents</label>
                                    <textarea rows="10" type="text" class="form-control" value="" required="required" v-model="emailContent.content"></textarea>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="sendEmail">Send</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Send Email -->


        <!-- Modal Label -->
        <div id="modal-label" class="modal fade">
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
                                    <select class="form-control">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                                
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" >Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Label -->


        <!-- Modal Reply -->
        <div id="modal-reply" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Reply</h4>
                    </div>
                    <div class="modal-body relative">
                        <div class="row">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" >Send</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Reply -->

    </div>
</template>

<script>
import axios from 'axios';
import { mapState } from 'vuex';
export default {


    data() {
        return {
            emailContent : {
                email: '',
                title: '',
                content: ''
            },
            viewContent : {
                date: '',
                from: '',
                subject: '',
                strippedHtml: '',
            },
            records: [],
            loadingMessage: false,
            selectedMessage: true,
            MessageDisplay: false,
            checkIds: [],
            btnEnable: true,
            inboxCount : 0,
        }
    },

    created() {
        // console.log(this.user)
    },

    computed: {
        ...mapState({
            user: state => state.storeAuth.currentUser,
        })
    },

    mounted() {
        this.getInbox();
    },

    methods: {
        submitStarred(id, is_starred, is_all) {
            let id_mail = is_all ? this.checkIds : id;
            axios.get('api/mail/starred', {
                params: {
                    id: id_mail,
                    is_starred: is_starred,
                }
            })
            .then((res) => {
                console.log(res)
                this.getInbox();
                this.checkIds = [];
                this.btnEnable = true;
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

        viewMessage(inbox) {
            this.selectedMessage = false;
            this.MessageDisplay = true;
            this.viewContent.from = inbox.from_mail;
            this.viewContent.strippedHtml = inbox.body;
            this.viewContent.date = inbox.created_at;
            this.viewContent.subject = inbox.subject;
            
        },

        sendEmail() {
           
           console.log(this.$data.emailContent);
            axios.post('/api/mail/send', this.$data.emailContent)
            .then((response) => {
                console.log(response);
                response => response;
                this.$data.emailContent.email = '';
                this.$data.emailContent.title = '';
                this.$data.emailContent.content = '';
            })
            .catch((error) => {
                console.log(error);
                error => error;
            });
       },
       getInbox(){
           this.loadingMessage = true;
           axios.post('/api/mail/filter-recipient',{'email': this.user.work_mail})
            .then((response) => {
                console.log(response);
                this.records = response.data.inbox;
                this.loadingMessage = false;
                this.inboxCount = response.data.count;
            })
            .catch((error) => {
                console.log(error);
                error => error;
            });
       }
    }
}
</script>