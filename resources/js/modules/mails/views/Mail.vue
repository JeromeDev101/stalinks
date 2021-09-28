<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"></h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <div class="row">
            <div class="col-sm-3">
                <button class="btn btn-success btn-lg btn-block mb-3" @click="checkWorkMail" >Compose</button>

                <div v-if="user.isAdmin">
                    <div class="form-group">
                        <label>Login As:</label>
                        <!--                        <select class="form-control" v-model="user.work_mail" @change="selectWorkMail">-->
                        <!--                            <option value="all">All</option>-->
                        <!--                            <option v-for="option in adminAccessOptions" v-bind:value="option.work_mail">-->
                        <!--                                {{ option.work_mail }} [{{ option.role.name }}]-->
                        <!--                            </option>-->
                        <!--                        </select>-->

                        <v-select
                            v-model="user.work_mail"
                            label="work_mail_position"
                            :clearable="false"
                            :searchable="true"
                            :options="adminAccessOptions"
                            :reduce="access => access.work_mail"

                            @input="selectWorkMail"/>
                    </div>
                </div>

                <div v-if="!user.isAdmin && user.access.length !== 0">
                    <div class="form-group">
                        <label>Login As:</label>
                        <!--                        <select class="form-control" v-model="user.work_mail" @change="selectWorkMail">-->
                        <!--                            <option v-for="option in emailAccessOptions" v-bind:value="option.work_mail">-->
                        <!--                                {{ option.work_mail }} [{{ option.user.role.name }}]-->
                        <!--                            </option>-->
                        <!--                        </select>-->

                        <v-select
                            v-model="user.work_mail"
                            label="work_mail_position"
                            :clearable="false"
                            :searchable="true"
                            :options="emailAccessOptions"
                            :reduce="access => access.work_mail"

                            @input="selectWorkMail"/>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title text-primary">Folders</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
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
                                    <i class="fas fa-reply"></i>
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
                                    <i class="fas fa-envelope"></i>
                                    Mail Template
                                </router-link>
                            </li>
                            <li :class="{ 'list-group-item':true, 'active': $route.name == 'mail-signature'}">
                                <router-link :to="{path:'/mails/signature', query: {label_id : $route.query.label_id ? $route.query.label_id : null } }">
                                    <i class="far fa-id-card"></i>
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

                <div class="card" v-show="$route.name != 'mail-template'">
                    <div class="card-header">
                        <h3 class="card-title text-primary">Default Labels</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-group" v-for="(label, index) in generalLabels" :key="index">
                            <li :class="{'list-group-item': true, 'active': label.id == $route.query.label_id}">
                                <a href="#" @click="setQueryLabel(label.id)" >
                                    <i class="fa fa-circle" :style="{'color': label.color}"></i> {{ label.name }}
                                </a>
                                <i style="margin: -29px 21px 0px 0px;" @click="clearQueryLabel" v-show="label.id == $route.query.label_id" class="fa fa-close pull-right text-muted" title="Clear"></i>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card" v-show="$route.name != 'mail-template'">
                    <div class="card-header">
                        <h3 class="card-title text-primary">User Labels</h3>
                        <div class="card-tools">
                            <i
                                class="fa fa-plus"
                                style="cursor: pointer"
                                data-toggle="modal"
                                data-target="#modal-add-label">
                            </i>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-group" v-for="(label, index) in userLabels" :key="index">
                            <li :class="{'list-group-item': true, 'active': label.id == $route.query.label_id}">
                                <a href="#" @click="setQueryLabel(label.id)" >
                                    <i class="fa fa-circle" :style="{'color': label.color}"></i> {{ label.name }}
                                </a>
                                <i style="margin: -29px 21px 0px 0px;" @click="clearQueryLabel" v-show="label.id == $route.query.label_id" class="fa fa-close pull-right text-muted" title="Clear"></i>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
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

        adminAccessOptions() {
            let self = this;
            const emails = self.listUserEmail;

            emails.forEach(item => {
                if (item.work_mail === self.user.work_mail_orig) {
                    item.role.name = 'Me';
                }

                item.work_mail_position = item.work_mail + ' [' + item.role.name + ']'
            });

            return emails;
        },

        emailAccessOptions() {
            let obj = {
                user_id: this.user.id,
                work_mail: this.user.work_mail_orig,
                user: {
                    role: {
                        name: 'Me'
                    }
                }
            };

            const emails = this.user.access;

            if (!emails.some(obj => obj.work_mail === this.user.work_mail_orig)) {
                emails.push(obj)
            }

            emails.forEach(item => {
                item.work_mail_position = item.work_mail + ' [' + item.user.role.name + ']'
            });

            return emails;
        },

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
            this.$children[7].getInbox()
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
