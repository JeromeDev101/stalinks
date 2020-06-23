<template>
    <div class="row">
        <div class="col-sm-12">

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Filter</h3>
                </div>

                <div class="box-body m-3">

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Search Company and User</label>
                                <input type="text" class="form-control" v-model="filterModel.search" name="" aria-describedby="helpId" placeholder="Type here">
                            </div>
                        </div>

                        <!-- <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Language</label>
                                <select name="" class="form-control" v-model="filterModel.language_id">
                                    <option value="">All</option>
                                    <option v-for="option in listCountries.data" v-bind:value="option.id">
                                        {{ option.name }}
                                    </option>
                                </select>
                            </div>
                        </div> -->

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <button class="btn btn-default" @click="clearSearch" >Clear</button>
                            <button class="btn btn-default" @click="doSearch">Search <i v-if="searchLoading" class="fa fa-refresh fa-spin" ></i></button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="box">

                <div class="box-header">
                    <h3 class="box-title">Publisher URL List</h3>

                    <div class="form-row">
                        <div class="col-md-4 my-3">
                            <div class="input-group">
                                <input type="file" class="form-control" v-on:change="checkData" enctype="multipart/form-data" ref="excel" name="file">
                                <div class="input-group-btn">
                                    <button title="Upload CSV File" @click="submitUpload" :disabled="isEnableBtn" class="btn btn-primary btn-flat"><i class="fa fa-upload"></i></button>
                                </div>
                            </div>
                            <span v-if="messageForms.errors.file" v-for="err in messageForms.errors.file" class="text-danger">{{ err }}</span>
                            <span v-if="messageForms.action == 'uploaded'" class="text-success">{{ messageForms.message }}</span>
                        </div>

                        <div class="col-md-2 my-3">
                            <div class="input-group">
                                <div class="dropdown">
                                    <button class="btn btn-default dropdown-toggle" :disabled="isDisabled" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Selected Action
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" @click="doMultipleDelete" href="#">Delete</a>
                                        <a class="dropdown-item" href="#">Get Ahref</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="box-body table-responsive no-padding relative">
                    <table class="table table-hover table-bordered table-striped rlink-table">
                        <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>Select</th>
                                <th>Company</th>
                                <th>User</th>
                                <th>Language</th>
                                <th>URL</th>
                                <th>UR</th>
                                <th>DR</th>
                                <th>Backlinks</th>
                                <th>Ref Domains</th>
                                <th>Organic Keywords</th>
                                <th>Organic Traffic</th>
                                <th>Price</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="listPublish.data.length == 0">
                                <td colspan="12" class="text-center">No record</td>
                            </tr>
                            <tr v-for="(publish, index) in listPublish.data" :key="index">
                                <td>{{ index + 1}}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-default">
                                            <input type="checkbox" v-on:change="checkSelected" :id="publish.id" :value="publish.id" v-model="checkIds">
                                        </button>
                                    </div>
                                </td>
                                <td>{{ publish.isOurs == '0' ? 'Stalinks':publish.company_name}}</td>
                                <td>{{ publish.name }}</td>
                                <td>{{ publish.language }}</td>
                                <td>{{ publish.url }}</td>
                                <td>{{ publish.ur }}</td>
                                <td>{{ publish.dr }}</td>
                                <td>{{ publish.backlinks }}</td>
                                <td>{{ publish.ref_domain }}</td>
                                <td>{{ publish.org_keywords }}</td>
                                <td>{{ publish.org_traffic }}</td>
                                <td>{{ publish.price == '' || publish.price == null ? '':'$'}} {{ publish.price }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button data-toggle="modal" @click="doUpdate(publish)" data-target="#modal-update-publisher" title="Edit" class="btn btn-default"><i class="fa fa-fw fa-edit"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- <pagination :data="listPublish" @pagination-change-page="getPublisherList"></pagination> -->
                </div>
            </div>

        </div>
        
        <!-- Modal Update Publisher-->
        <div class="modal fade" id="modal-update-publisher" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Information</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Company Name</label>
                                    <input type="text" v-model="updateModel.company_name" class="form-control" name="" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <input type="text" v-model="updateModel.name" class="form-control" name="" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">URL</label>
                                    <input type="text" v-model="updateModel.url" class="form-control" name="" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Ref Domains</label>
                                    <input type="number" v-model="updateModel.ref_domain" class="form-control" name="" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">No Backlinks</label>
                                    <input type="text" v-model="updateModel.backlinks" class="form-control" name="" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">URL Rating</label>
                                    <input type="number" v-model="updateModel.ur" class="form-control" name="" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Domain Rating</label>
                                    <input type="number" v-model="updateModel.dr" class="form-control" name="" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Organic Keywords</label>
                                    <input type="text" v-model="updateModel.org_keywords" class="form-control" name="" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Organic traffic</label>
                                    <input type="text" v-model="updateModel.org_traffic" class="form-control" name="" placeholder="">
                                </div>
                            </div>

                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Language</label>
                                    <select name="" v-model="updateModel.language_id" class="form-control">
                                        <option value=""></option>
                                        <option v-for="option in listCountries.data" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                </div>
                            </div> -->

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Language</label>
                                    <input type="text" v-model="updateModel.language" class="form-control" name="" placeholder="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Price</label>
                                    <input type="number" v-model="updateModel.price" class="form-control" name="" placeholder="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitUpdate" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<script>

    import { mapState } from 'vuex';

    export default {
        name: '',
        data(){
            return {
                updateModel: {
                    id: '',
                    company_name: '',
                    name: '',
                    url: '',
                    ur: '',
                    dr: '',
                    backlinks: '',
                    ref_domain: '',
                    org_keywords: '',
                    org_traffic: '',
                    price: '',
                    language: '',
                },
                isEnableBtn: true,
                isPopupLoading: false,
                filterModel: {
                    search: this.$route.query.search || '',
                },
                searchLoading: false,
                checkIds: [],
                isDisabled: true,
            }
        },

        async created() {
            this.getPublisherList();
            this.getListCountries();
        },

        computed:{
            ...mapState({
                listPublish: state => state.storePublisher.listPublish,
                messageForms: state => state.storePublisher.messageForms,
                listCountries: state => state.storePublisher.listCountries,
            })
        },

        methods: {
            // async getPublisherList(page = 1) {
            //     this.searchLoading = true;
            //     await this.$store.dispatch('getListPublisher', {
            //         params: {
            //             search: this.filterModel.search,
            //             page: page
            //         }
            //     });
            //     this.searchLoading = false;
            // },
            
            async getPublisherList(params) {
                this.searchLoading = true;
                await this.$store.dispatch('getListPublisher', {
                    params: {
                        search: this.filterModel.search,
                    }
                });
                this.searchLoading = false;
            },

            checkSelected() {
                this.isDisabled = true;
                if( this.checkIds.length > 0 ){
                    this.isDisabled = false;
                }
            },

            async doMultipleDelete(){
                this.clearMessageform()
                if( confirm("Are you sure you want to delete selected records?") ){
                    await this.$store.dispatch('actionDeletePublisher', {
                        params: {
                            id: this.checkIds,
                        }
                    });

                    this.getPublisherList();
                    this.checkIds = []
                    this.isDisabled = true;
                }
            },

            async submitUpload() {
                this.formData = new FormData();
                this.formData.append('file', this.$refs.excel.files[0]);

                await this.$store.dispatch('actionUploadCsv', this.formData);

                if (this.messageForms.action === 'uploaded') {
                    this.getPublisherList();
                    this.$refs.excel.value = '';
                    this.isEnableBtn = true;
                }
            },

            clearSearch() {
                this.filterModel = {
                    search: '',
                }

                this.getPublisherList({
                    params: this.filterModel
                });

                this.$router.replace({'query': null});
            },

            async submitUpdate(params) {
                this.isPopupLoading = true;
                await this.$store.dispatch('actionUpdatePublisher', this.updateModel);
                this.isPopupLoading = false;

                if (this.messageForms.action === 'updated_publisher') {
                    this.getPublisherList();
                }
            },

            async getListCountries(params) {
                await this.$store.dispatch('actionGetListCountries', params);
            },

            doUpdate(publish) {
                this.clearMessageform()
                let that = JSON.parse(JSON.stringify(publish))

                this.updateModel = {
                    id: that.id,
                    name: that.name,
                    url: that.url,
                    language: that.language,
                    ur: that.ur,
                    dr: that.dr,
                    backlinks: that.backlinks,
                    ref_domain: that.ref_domain,
                    org_keywords: that.org_keywords,
                    org_traffic: that.org_traffic,
                    price: that.price,
                    anchor_text: that.anchor_text,
                    link: that.link,
                }
                
                this.updateModel.company_name = that.isOurs == '0' ? 'Stalinks':that.company_name;
            },

            async doDelete(id){
                this.clearMessageform()
                if( confirm("Do you want to delete these record?") ){
                    await this.$store.dispatch('actionDeletePublisher', {
                        params: {
                            id: id,
                        }
                    });

                    this.getPublisherList();
                }
            },

            doSearch() {
                this.$router.push({
                    query: this.filterModel,
                });

                this.getPublisherList({
                    params: {
                        search: this.filterModel.search,
                    }
                });
            },

            checkData() {
                this.isEnableBtn = true;
                if( this.$refs.excel.value ){
                    this.isEnableBtn = false;
                }
            },

            clearModel () {
                this.updateModel = {
                    id: '',
                    company_name: '',
                    name: '',
                    url: '',
                    ur: '',
                    dr: '',
                    backlinks: '',
                    ref_domain: '',
                    org_keywords: '',
                    org_traffic: '',
                    price: '',
                }
            },

            clearMessageform() {
                this.$store.dispatch('clearMessageform');
            },
        }
    }
</script>