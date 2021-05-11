<template>
    <div>
        <div class="row">

            <!-- Side menu section -->
            <div class="col-md-2">
                <button class="btn btn-success btn-lg btn-block mb-3" @click="checkWorkMail" >Compose</button>
                <div v-if="user.isAdmin">
                    <div class="form-group">
                        <label for="">Login As:</label>
                        <select class="form-control" v-model="user.work_mail" @change="selectWorkMail">
                            <option value="all">All</option>
                            <option v-for="option in listUserEmail" v-bind:value="option.work_mail">
                                {{ option.work_mail }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Folders</h3>

                        <!-- <div class="box-tools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div> -->
                    </div>
                    <div class="box-body no-padding">
                        <ul class="list-group">
                            <li :class="{ 'list-group-item':true, 'active': $route.name == 'Inbox'}">
                                <router-link :to="{path:'/mails/inbox', query: {label_id : $route.query.label_id ? $route.query.label_id : null } }" >
                                    <i class="fa fa-fw fa-inbox"></i>
                                    Inbox
                                    <span class="label label-primary pull-right" v-show="displayInboxCnt != 0">{{displayInboxCnt}}</span>
                                </router-link>
                            </li>
                            <li :class="{ 'list-group-item':true, 'active': $route.name == 'Sent'}">
                                <router-link :to="{path:'/mails/sent', query: {label_id : $route.query.label_id ? $route.query.label_id : null } }">
                                    <i class="fa fa-fw fa-mail-reply"></i>
                                    Sent
                                </router-link>
                            </li>
                            <li :class="{ 'list-group-item':true, 'active': $route.name == 'Starred'}">
                                <router-link :to="{path:'/mails/starred', query: {label_id : $route.query.label_id ? $route.query.label_id : null } }">
                                    <i class="fa fa-fw fa-star"></i>
                                    Starred
                                </router-link>
                            </li>
                            <li :class="{ 'list-group-item':true, 'active': $route.name == 'mail-template'}">
                                <router-link :to="{path:'/mails/template', query: {label_id : $route.query.label_id ? $route.query.label_id : null } }">
                                    <i class="fa fa-fw fa-envelope-o"></i>
                                    Mail Template
                                </router-link>
                            </li>
                            <li :class="{ 'list-group-item':true, 'active': $route.name == 'mail-signature'}">
                                <router-link :to="{path:'/mails/signature', query: {label_id : $route.query.label_id ? $route.query.label_id : null } }">
                                    <i class="fa fa-fw fa-id-card-o"></i>
                                    Signatures
                                </router-link>
                            </li>
                            <li :class="{ 'list-group-item':true, 'active': $route.name == 'Trash'}">
                                <router-link :to="{path:'/mails/trash', query: {label_id : $route.query.label_id ? $route.query.label_id : null } }">
                                    <i class="fa fa-fw fa-trash"></i>
                                    Trash
                                </router-link>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="box box-solid" v-show="$route.name != 'mail-template'">
                    <div class="box-header with-border">
                        <h5 class="box-title">Default Labels</h5>

                        <!-- <div class="box-tools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div> -->
<!--                        <div class="box-tools">-->
<!--                            <button type="button" class="btn btn-box-tool btn-sucess" title="Add Label" @click="clearLabelInputs" data-toggle="modal" data-target="#modal-add-label"><i class="fa fa-plus"></i></button>-->
<!--                        </div>-->
                    </div>

                    <div class="box-body no-padding">
                        <ul class="list-group" v-for="(label, index) in generalLabels" :key="index">
                            <li :class="{'list-group-item': true, 'active': label.id == $route.query.label_id}">
                                <a href="#" @click="setQueryLabel(label.id)" >
                                    <i class="fa fa-circle-o" :style="{'color': label.color}"></i> {{ label.name }}
                                </a>
                                <i style="margin: -29px 21px 0px 0px;" @click="clearQueryLabel" v-show="label.id == $route.query.label_id" class="fa fa-close pull-right text-muted" title="Clear"></i>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="box box-solid" v-show="$route.name != 'mail-template'">
                    <div class="box-header with-border">
                        <h5 class="box-title">User Labels</h5>

                        <!-- <div class="box-tools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div> -->
                        <div class="box-tools">
                            <button type="button" class="btn btn-box-tool btn-sucess" title="Add Label" @click="clearLabelInputs" data-toggle="modal" data-target="#modal-add-label"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="box-body no-padding">
                        <ul class="list-group" v-for="(label, index) in userLabels" :key="index">
                            <li :class="{'list-group-item': true, 'active': label.id == $route.query.label_id}">
                                <a href="#" @click="setQueryLabel(label.id)" >
                                    <i class="fa fa-circle-o" :style="{'color': label.color}"></i> {{ label.name }}
                                </a>
                                <i style="margin: -29px 21px 0px 0px;" @click="clearQueryLabel" v-show="label.id == $route.query.label_id" class="fa fa-close pull-right text-muted" title="Clear"></i>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-10">
                <router-view @updateInboxUnreadCount="updateUnreadInboxCount"></router-view>
            </div>

        </div>

        <!-- Modal Add Label -->
        <div id="modal-add-label" class="modal fade">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Label</h4>
                    </div>
                    <div class="modal-body relative">
                        <div class="row">
                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageError.name != ''}" class="form-group">
                                    <label for="">Label Name</label>
                                    <input type="text" class="form-control" v-model="labelModel.name">
                                    <span v-show="messageError.name != ''" class="text-danger">{{ messageError.name }}</span>
                                 </div>
                            </div>
                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageError.color != ''}" class="form-group">
                                    <label for="">Color</label>
                                    <input type="text" class="form-control" v-model="labelModel.color" readonly>
                                    <span v-show="messageError.color != ''" class="text-danger">{{ messageError.color }}</span>
                                    <compact-picker v-model="colors" @input="updateValue" />
                                 </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="saveLabel">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Add Label -->

    </div>
</template>

<script>
import axios from 'axios';
import { mapState } from 'vuex';
import { Compact } from 'vue-color';
export default {
    data() {
        return {
            colors: '#194d33',
            labelModel: {
                name: '',
                color: '',
            },
            messageError: {
                color: '',
                name: '',
            },
            listLabel: [],
            displayInboxCnt: 0,
            listUserEmail: [],
            filterModel: {
                user_email: this.$route.query.user_email || ''

            },
        }
    },

    created() {
    //    console.log(this.$route.query.label_id)
    },

    computed: {
        ...mapState({
            user: state => state.storeAuth.currentUser,
            messageForms: state => state.storeMailgun.messageForms,
        }),

        generalLabels() {
            return this.listLabel.filter(label => label.user_id === null)
        },

        userLabels() {
            return this.listLabel.filter(label => label.user_id !== null)
        }
    },

    mounted() {
        this.displayInboxCnt = this.$children[5]._data.inboxCount;
        this.setQueryLabel(null)
        // console.log(this.$children[5]._data.inboxCount)
        this.getListLabels();
        this.getListEmails();
    },

    methods: {
        selectWorkMail() {
            this.setQueryLabel(null)
            this.$children[5].getInbox()
            this.getListLabels()
        },

        clearQueryLabel() {
            this.$router.replace({ query: null})
        },

        setQueryLabel(id) {
            this.$router.replace({query: {'label_id': id} })
        },

        checkWorkMail() {
            if (this.user.work_mail) {
                $("#modal-compose-email").modal('show')
            } else {
                swal.fire(
                    'Error',
                    'Please setup first your Work mail',
                    'error'
                )
            }
        },

        updateValue (value) {
            this.colors = value
            this.labelModel.color = value.hex;
        },

        getListLabels() {
            axios.get('/api/label_list/' + this.user.work_mail)
                .then((res) => {
                    this.listLabel = res.data.labels
                })
        },

        saveLabel() {
            axios.post('/api/label',{
                name: this.labelModel.name,
                color: this.labelModel.color,
            })
            .then((res) => {
                var result = res.data;

                if (result.hasOwnProperty('name') || result.hasOwnProperty('color')) {
                    this.messageError.name = result.hasOwnProperty('name') ? result.name[0] : '';
                    this.messageError.color = result.hasOwnProperty('color') ? result.color[0] : '';
                } else {
                    $("#modal-add-label").modal('hide');

                    swal.fire(
                        'Save',
                        'Successfully Saved',
                        'success'
                    )

                    this.getListLabels();
                }

            })
        },


        // clearMessageform() {
        //     this.$store.dispatch('clearMessageForm');
        // },


        clearLabelInputs() {
            this.messageError = {
                name: '',
                color: '',
            }

            this.labelModel = {
                name: '',
                color: '',
            }
        },
        getListEmails() {
            axios.get('/api/mail/get-mail-list').then((response) => {

                this.listUserEmail = response.data;
            });
        },

        updateUnreadInboxCount(count) {
            this.displayInboxCnt = count
        }
    }
}
</script>
