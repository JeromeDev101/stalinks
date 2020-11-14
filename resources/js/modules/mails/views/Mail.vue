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
                            <li :class="{ 'list-group-item':true, 'active': $route.name == 'inbox'}">
                                <router-link to="/mails/inbox">
                                    <i class="fa fa-fw fa-inbox"></i>
                                    Inbox
                                    <span class="label label-primary pull-right">{{inboxCount}}</span>
                                </router-link>
                            </li>
                            <li :class="{ 'list-group-item':true, 'active': $route.name == 'sent'}">
                                <router-link to="/mails/sent">
                                    <i class="fa fa-fw fa-mail-reply"></i>
                                    Sent
                                </router-link> 
                            </li>
                            <li :class="{ 'list-group-item':true, 'active': $route.name == 'starred'}">
                                <router-link to="/mails/starred">                   
                                    <i class="fa fa-fw fa-star"></i>
                                    Starred
                                </router-link>
                            </li>
                            <li :class="{ 'list-group-item':true, 'active': $route.name == 'trash'}">
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

                        <div class="box-tools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body no-padding">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <a href="#">
                                    <i class="fa fa-circle-o red"></i> Important
                                </a>
                            </li>
                            <li class="list-group-item">
                                <a href="#">
                                    <i class="fa fa-circle-o orange"></i> Promotions
                                </a>
                            </li>
                            <li class="list-group-item">
                                <a href="#">
                                    <i class="fa fa-circle-o green"></i> Social
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
        // console.log(this.user)
    },

    computed: {
        ...mapState({
            user: state => state.storeAuth.currentUser,
        })
    },

    mounted() {
        //
    },

    methods: {
        //
    }
}
</script>