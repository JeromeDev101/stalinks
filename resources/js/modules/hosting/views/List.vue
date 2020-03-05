<template>
   <div class="row">
    <section class="content-header col-sm-12" id="section-hosting">
      <h1>Hosting List</h1>
      <br>
    </section>
      <div class="col-sm-12">
         <div class="box">
            <div class="box-header">
            <div class="box box-primary direct-chat direct-chat-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Action</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
        <div class="form-row">
    <div class="col-md-8 mb-8">
        <label class="int-domain">Search</label>
        <div class="input-group">
            <input v-on:keyup="getHostingList()" v-model="querySearch" v-on:keyup.enter="getHostingList()" type="text" name="search" class="form-control" placeholder="Search by user, username or link">
            <div class="input-group-btn">
                <button @click="getHostingList()" type="submit" name="submit" class="btn btn-primary btn-flat"><i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <label class="int-domain">Add Hosting</label>
        <br>
        <div class="btn-group">
            <button @click="doShowAdd()" class="btn btn-success" title="View"><i class="fa fa-fw fa-plus"></i></button>
        </div>
    </div>
  </div>
        </div>
        <!-- /.box-body -->
        <!-- /.box-footer-->
    </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding relative">
               <table class="table table-hover table-bordered table-striped rlink-table">
                  <tbody>
                     <tr class="label-primary">
                        <th>#</th>
                        <th>Name</th>
                        <th>Link</th>
                        <th>Username</th>
                        <th>Action</th>
                     </tr>
                     <tr v-for="(hosting, index) in listHosting.data" :key="index">
                        <td class="center-content">{{ index + 1 }}</td>
                        <td>{{ hosting.name }}</td>
                        <td><a :href="hosting.link">{{ hosting.link }}</a></td>
                        <td>{{ hosting.username }}</td>
                        <td>
                           <div class="btn-group">
                                <router-link :to="{ path: `/hostings/${hosting.id}` }" class="btn btn-default" title="View"><i class="fa fa-fw fa-eye"></i></router-link >
                                <button @click="doShowIntDomaint(hosting.internal_domains)" data-toggle="modal" title="Intdomain Link" class="btn btn-default"><i class="fa fa-fw fa-link"></i></button>
                                <button  @click="editHosting(hosting)" class="btn btn-default"><i class="fa fa-fw fa-edit"></i></button>
                                <button  @click="copyPassword(hosting.password)" class="btn btn-default"><i class="fa fa-fw  fa-copy"></i></button>
                           </div>
                        </td>
                     </tr>
                  </tbody>
               </table>
                <div class="overlay" v-if="isLoadingTable">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
            </div>
            <pagination :data="listHosting" @pagination-change-page="getHostingList($event)"></pagination>
            <!-- /.box-body -->
         </div>
         <!-- /.box -->
      </div>
        <!--    Modal intdomain-->
        <div class="modal fade" id="modalIntdomain" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                       <h1>Int domain</h1>
                    </div>
                    <div class="modal-body relative">
                        <table class="table table-responsive table-hover table-bordered table-striped rlink-table">
                            <tbody>
                            <tr class="label-primary">
                            <th>#</th>
                            <th>Country</th>
                            <th>Domain</th>
                            <th>Hosting Provider</th>
                            <th>Domain Provider</th>
                            <th>User</th>
                            <th>Total Spent</th>
                            </tr>
                        <tr v-for="(item, index) in listInt" :key="index">
                            <td class="center-content">{{ index + 1 }}</td>
                            <td>{{ item.country.name }}</td>
                            <td><a :href="'http://' + item.domain" target="_blank">{{ item.domain }}</a></td>
                            <td><a href="#">{{ item.hosting_provider.name }}</a></td>
                            <td><a href="#">{{ item.domain_provider.name }}</a></td>
                            <td>{{ item.user.name }}</td>
                            <td>{{ calculate(item.backlinks) }}</td>
                        </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--    End Modal intdomain-->
    <!-- Modal Add Hosting -->

    <div class="modal fade" id="modal-add-hosting" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 v-if="hostingModel.id" class="modal-title">Edit Hosting</h4>
                    <h4 v-else class="modal-title">Add New Hosting</h4>
                    <div class="modal-load overlay float-right">
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                </div>
                <div class="modal-body relative">
                    <form class="row" action="" autocomplete="off">
                        <input autocomplete="off" name="hidden" type="text" style="display:none;">
                        <div class="col-md-6">
                            <div :class="{'form-group': true, 'has-error': messageForms.errors.name}" class="form-group">
                                <label style="color: #333">Name</label>
                                <input type="text" v-model="hostingModel.name" class="form-control" value="" required="required" placeholder="Enter Name">
                                <span v-if="messageForms.errors.name" v-for="err in messageForms.errors.name" class="text-danger">{{ err }}</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div :class="{'form-group': true, 'has-error': messageForms.errors.username}" class="form-group">
                                <label style="color: #333">Username</label>
                                <input type="text" v-model="hostingModel.username" class="form-control" value="" required="required" placeholder="Enter Username or Email">
                                <span v-if="messageForms.errors.username" v-for="err in messageForms.errors.username" class="text-danger">{{ err }}</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div :class="{'form-group': true, 'has-error': messageForms.errors.link}" class="form-group">
                                <label style="color: #333">Link</label>
                                <input type="text" v-model="hostingModel.link" class="form-control" value="" required="required" placeholder="Enter Link">
                                <span v-if="messageForms.errors.link" v-for="err in messageForms.errors.link" class="text-danger">{{ err }}</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div :class="{'form-group': true, 'has-error': messageForms.errors.password}" class="form-group">
                                <label style="color: #333">Password</label>
                                <input autocomplete="off" type="text" v-model="hostingModel.password" class="form-control" value="" required="required" placeholder="Enter Password">
                                <span v-if="messageForms.errors.password" v-for="err in messageForms.errors.password" class="text-danger">{{ err }}</span>
                            </div>
                        </div>
                    </form>
                    <div class="overlay" v-if="isPopupLoading"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" @click="submitAdd" class="btn btn-primary">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
            <!-- End Add Hosting -->
   </div>
</template>
<script>
import { mapState } from 'vuex';

export default {
    name: 'HostingList',
    data() {
        return {
            listInt: {},
            page: this.$route.query.page || 1,
            isPopupLoading: false,
            isLoadingTable: false,
            hostingModel: {
                id: '',
                name: '',
                username: '',
                link: '',
                password: ''
            },
            querySearch: ''
        }
    },

    async created() {
        await this.$store.dispatch('actionCheckAdminCurrentUser', { vue: this });
        this.getHostingList();
    },

    computed: {
        ...mapState({
            listHosting: state => state.storeHosting.listHosting,
            messageForms: state => state.storeHosting.messageForms,
        }),
    },

    methods: {
        async getHostingList(page) {
            if (page) {
                this.page = page
            }
            this.$router.push({
                query: {
                    page: this.page,
                    q: this.querySearch
                },
            })
            this.isLoadingTable = true
            await this.$store.dispatch('getHostingList', {
                params: {
                    page: this.page,
                    q: this.querySearch
                }
            });
            this.isLoadingTable = false
        },

        doShowIntDomaint(data) {
            this.listInt = data
            $('#modalIntdomain').modal('show');
        },

        doShowAdd() {
            this.clearHostingModel()
            $('#modal-add-hosting').modal('show');
        },

        async submitAdd() {
            let that = this;
            this.isPopupLoading = true;
            if (this.hostingModel.id) {
                await this.$store.dispatch('actionEditHosting', that.hostingModel)
            } else {
                await this.$store.dispatch('actionAddHosting', that.hostingModel)
            }
            this.isPopupLoading = false;

            if (this.messageForms.action === 'saved_hosting') {
                this.getHostingList();
                this.clearHostingModel();
                this.closeModal();
            }
        },

        editHosting(hosting) {
            this.clearHostingModel()
            this.hostingModel = {
                id: hosting.id,
                name: hosting.name,
                username: hosting.username,
                link: hosting.link,
                password: hosting.password
            }
            $('#modal-add-hosting').modal('show');
        },

        calculate(backlinks) {
            let totalSpent = 0
            if(backlinks) {
                backlinks.forEach(backlink => {
                    totalSpent += parseFloat(backlink.price)
                })
            }
            
            return totalSpent
        },
        
        clearHostingModel()
        {
            this.hostingModel = {
                id: '',
                name: '',
                username: '',
                link: '',
                password: ''
            }
            this.$store.dispatch('clearMessageForm')
        },

        closeModal() {
            $('#modal-add-hosting').modal('hide');
        },
        
        copyPassword(password) {
            var inputTemp = document.createElement("input");
            inputTemp.value = password;
            inputTemp.select();
            document.getElementById('section-hosting').appendChild(inputTemp);
            inputTemp.select();
            document.execCommand("copy");
            document.getElementById('section-hosting').removeChild(inputTemp);
            alert("Copied password");
        }
    },
}
</script>
