<template>
    <div>
        <div class="row">
            <div class="col-md-2">
                <button class="btn btn-success btn-lg btn-block mb-3" data-target="#modal-compose-email" data-toggle="modal">Compose</button>

                <ul class="list-group">
                    <li class="list-group-item active">
                        <i class="fa fa-fw fa-inbox"></i> Inbox
                    </li>
                    <li class="list-group-item">
                        <i class="fa fa-fw fa-mail-reply"></i> Sent
                    </li>
                    <li class="list-group-item">
                        <i class="fa fa-fw fa-star"></i> Starred
                    </li>
                    <li class="list-group-item">
                        <i class="fa fa-fw fa-tag"></i> Label
                    </li>
                    <li class="list-group-item">
                        <i class="fa fa-fw fa-trash"></i> Trash
                    </li>
                </ul>
            </div>
            <div class="col-md-5">
                <button type="button" class="btn btn-default">
                    <input type="checkbox">
                </button>
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-default" title="Starred">
                        <i class="fa fa-fw fa-star"></i>
                    </button>
                    <button type="button" class="btn btn-default" title="Label">
                        <i class="fa fa-fw fa-tag"></i>
                    </button>
                    <button type="button" class="btn btn-default">
                        <i class="fa fa-fw fa-refresh"></i>
                    </button>
                    <button type="button" class="btn btn-default" title="Trash">
                        <i class="fa fa-fw fa-trash"></i>
                    </button>
                </div>

                <div class="my-4 message-list">
                    <table class="table table-condensed table-hover">
                        <tbody>
                            <tr>
                                <td><input type="checkbox"></td>
                                <td>No Name</td>
                                <td>Great you have receive a meesage from our Bot</td>
                                <td class="text-right"><i class="fa fa-fw fa-star-o"></i></td>
                                <td><i class="fa fa-fw fa-paperclip"></i></td>
                                <td class="text-right">Nov 6, 2020</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox"></td>
                                <td>No Name</td>
                                <td>Great you have receive a meesage from our Bot</td>
                                <td class="text-right"><i class="fa fa-fw fa-star-o"></i></td>
                                <td><i class="fa fa-fw fa-paperclip"></i></td>
                                <td class="text-right">Nov 6, 2020</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox"></td>
                                <td>No Name</td>
                                <td>Great you have receive a meesage from our Bot</td>
                                <td class="text-right"><i class="fa fa-fw fa-star-o"></i></td>
                                <td></td>
                                <td class="text-right">Nov 6, 2020</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox"></td>
                                <td>No Name</td>
                                <td>Great you have receive a meesage from our Bot</td>
                                <td class="text-right"><i class="fa fa-fw fa-star green"></i></td>
                                <td><i class="fa fa-fw fa-paperclip"></i></td>
                                <td class="text-right">Nov 6, 2020</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox"></td>
                                <td>No Name</td>
                                <td>Great you have receive a meesage from our Bot</td>
                                <td class="text-right"><i class="fa fa-fw fa-star-o"></i></td>
                                <td></td>
                                <td class="text-right">Nov 6, 2020</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox"></td>
                                <td>No Name</td>
                                <td>Great you have receive a meesage from our Bot</td>
                                <td class="text-right"><i class="fa fa-fw fa-star-o"></i></td>
                                <td><i class="fa fa-fw fa-paperclip"></i></td>
                                <td class="text-right">Nov 6, 2020</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
            </div>
            <div class="col-md-5">
                
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
            }
        }
    },

    created() {
        //
    },

    computed: {
        //
    },

    methods: {
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
       }
    }
}
</script>