<template>
    <div>
        <div class="row">
            
            <!-- Side menu section -->
            <div class="col-md-2">
                <button class="btn btn-success btn-lg btn-block mb-3" data-target="#modal-compose-email" data-toggle="modal">Compose</button>

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
                                <router-link to="/mails/inbox">
                                    <i class="fa fa-fw fa-inbox"></i>
                                    Inbox
                                    <span class="label label-primary pull-right">{{inboxCount}}</span>
                                </router-link>
                            </li>
                            <li :class="{ 'list-group-item':true, 'active': $route.name == 'Sent'}">
                                <router-link to="/mails/sent">
                                    <i class="fa fa-fw fa-mail-reply"></i>
                                    Sent
                                </router-link> 
                            </li>
                            <li :class="{ 'list-group-item':true, 'active': $route.name == 'Starred'}">
                                <router-link to="/mails/starred">                   
                                    <i class="fa fa-fw fa-star"></i>
                                    Starred
                                </router-link>
                            </li>
                            <li :class="{ 'list-group-item':true, 'active': $route.name == 'mail-template'}">
                                <router-link to="/mails/template">                   
                                    <i class="fa fa-fw fa-envelope-o"></i>
                                    Mail Template
                                </router-link>
                            </li>
                            <li :class="{ 'list-group-item':true, 'active': $route.name == 'Trash'}">
                                <router-link to="/mails/trash">
                                    <i class="fa fa-fw fa-trash"></i>
                                    Trash
                                </router-link>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Labels</h3>

                        <!-- <div class="box-tools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div> -->
                        <div class="box-tools">
                            <button type="button" class="btn btn-box-tool btn-sucess" title="Add Label" data-toggle="modal" data-target="#modal-add-label"><i class="fa fa-plus"></i></button>
                        </div>

                    </div>
                    <div class="box-body no-padding">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <a href="#">
                                    <i class="fa fa-circle-o"></i> Important
                                </a>
                            </li>
                            <li class="list-group-item">
                                <a href="#">
                                    <i class="fa fa-circle-o"></i> Promotions
                                </a>
                            </li>
                            <li class="list-group-item">
                                <a href="#">
                                    <i class="fa fa-circle-o"></i> Social
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

            <div class="col-md-10">
                <router-view></router-view>
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
                                <div class="form-group">
                                    <label for="">Label Name</label>
                                    <input type="text" class="form-control">
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
        <!-- End of Add Label -->

    </div>
</template>

<script>
import axios from 'axios';
import { mapState } from 'vuex';
export default {
    data() {
        return {
            inboxCount: 0,
        }
    },

    created() {
    //    console.log(this.$children)
    },

    computed: {
        ...mapState({
            user: state => state.storeAuth.currentUser,
        })
    },

    mounted() {
        console.log(this.$children[5]._data.inboxCount)
    },

    methods: {
        //
    }
}
</script>