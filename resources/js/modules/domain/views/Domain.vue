<template>
   <div class="row">
    <section class="content-header col-sm-12" id="section-domain">
      <h1>Domain Provider List</h1>
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
            <input v-on:keyup="getDomainList()" v-on:keyup.enter="getDomainList()" type="text" name="search" class="form-control" v-model="querySearch" placeholder="Search by name or username">
            <div class="input-group-btn">
                <button @click="getDomainList()" type="button" name="submit" class="btn btn-primary btn-flat"><i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <label class="int-domain">Add domain</label>
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
                        <th>Username</th>
                        <th>Action</th>
                     </tr>
                     <tr v-for="(domain, index) in listDomain.data" :key="index">
                        <td class="center-content">{{ index + 1 }}</td>
                        <td>{{ domain.name }}</td>
                        <td>{{ domain.username }}</td>
                        <td>
                           <div class="btn-group">
                                <router-link :to="{ path: `/domains/${domain.id}` }" class="btn btn-default" title="View"><i class="fa fa-fw fa-eye"></i></router-link >
                                <button @click="doShowIntDomaint(domain.internal_domains)" data-toggle="modal" title="Intdomain Link" class="btn btn-default"><i class="fa fa-fw fa-link"></i></button>
                                <button @click="editHosting(domain)" class="btn btn-default"><i class="fa fa-fw fa-edit"></i></button>
                                <button  @click="copyPassword(domain.password)" class="btn btn-default"><i class="fa fa-fw  fa-copy"></i></button>
                           </div>
                        </td>
                     </tr>
                  </tbody>
               </table>
                <div class="overlay" v-if="isLoadingTable">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
            </div>
            <pagination :data="listDomain" @pagination-change-page="getDomainList($event)"></pagination>
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
                            <td><a href="#">{{ item.domain_provider.name }}</a></td>
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

    <div class="modal fade" id="modal-add-domain" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 v-if="domainModel.id" class="modal-title">Edit Domain Provider</h4>
                    <h4 v-else class="modal-title">Add New Domain Provider</h4>
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
                                <input type="text" v-model="domainModel.name" class="form-control" value="" required="required" placeholder="Enter Name">
                                <span v-if="messageForms.errors.name" v-for="err in messageForms.errors.name" class="text-danger">{{ err }}</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div :class="{'form-group': true, 'has-error': messageForms.errors.username}" class="form-group">
                                <label style="color: #333">Username</label>
                                <input type="text" v-model="domainModel.username" class="form-control" value="" required="required" placeholder="Enter Username">
                                <span v-if="messageForms.errors.username" v-for="err in messageForms.errors.username" class="text-danger">{{ err }}</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div :class="{'form-group': true, 'has-error': messageForms.errors.password}" class="form-group">
                                <label style="color: #333">Password</label>
                                <input autocomplete="off" type="text" v-model="domainModel.password" class="form-control" value="" required="required" placeholder="Enter Password">
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
import _ from 'lodash'

export default {
    name: 'DomainList',
    data() {
        return {
            listInt: {},
            page: this.$route.query.page || 1,
            isPopupLoading: false,
            isLoadingTable: false,
            domainModel: {
                id: '',
                name: '',
                username: '',
                password: ''
            },
            querySearch: ''
        }
    },

    async created() {
        await this.$store.dispatch('actionCheckAdminCurrentUser', { vue: this });
        this.getDomainList();
    },

    computed: {
        ...mapState({
            listDomain: state => state.storeDomain.listDomain,
            messageForms: state => state.storeDomain.messageForms,
        }),
    },

    methods: {
        getDomainList: _.debounce(async function(page) {
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
            await this.$store.dispatch('getDomainList', {
                vue: this,
                params: {
                    page: this.page,
                    q: this.querySearch
                }
            });
            this.isLoadingTable = false
        }, 200),

        doShowIntDomaint(data) {
            this.listInt = data
            $('#modalIntdomain').modal('show');
        },

        doShowAdd() {
            this.cleardomainModel()
            $('#modal-add-domain').modal('show');
        },

        async submitAdd() {
            let that = this;
            this.isPopupLoading = true;
            if (this.domainModel.id) {
                await this.$store.dispatch('actionEditDomain', that.domainModel)
            } else {
                await this.$store.dispatch('actionAddDomain', that.domainModel)
            }
            this.isPopupLoading = false;

            if (this.messageForms.action === 'saved_domain') {
                this.getDomainList();
                this.cleardomainModel();
                this.closeModal();
            }
        },

        editHosting(domain) {
            this.cleardomainModel()
            this.domainModel = {
                id: domain.id,
                name: domain.name,
                username: domain.username,
                password: domain.password
            }
            $('#modal-add-domain').modal('show');
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
        
        cleardomainModel()
        {
            this.domainModel = {
                id: '',
                name: '',
                username: '',
                password: ''
            }
            this.$store.dispatch('clearMessageForm')
        },
        copyPassword(password) {
            var inputTemp = document.createElement("input");
            inputTemp.value = password;
            inputTemp.select();
            document.getElementById('section-domain').appendChild(inputTemp);
            inputTemp.select();
            document.execCommand("copy");
            document.getElementById('section-domain').removeChild(inputTemp);
            alert("Copied password");
        },

        closeModal() {
            $('#modal-add-domain').modal('hide');
        }
        
    },
}
</script>
