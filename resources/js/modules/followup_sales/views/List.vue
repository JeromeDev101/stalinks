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
                                <label for="">Search URL Publisher</label>
                                <input type="text" class="form-control" v-model="filterModel.search" name="" aria-describedby="helpId" placeholder="Type here">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Status</label>
                                <select name="" class="form-control" v-model="filterModel.status">
                                    <option value="">All</option>
                                    <option v-for="status in statusBaclink" v-bind:value="status">{{ status }}</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <button class="btn btn-default" @click="clearSearch">Clear</button>
                            <button class="btn btn-default" @click="doSearch">Search <i v-if="searchLoading" class="fa fa-refresh fa-spin" ></i></button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Follow up Sales</h3>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-bordered table-striped rlink-table">
                        <tbody>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>ID</th>
                                <th>URL Publisher</th>
                                <th>Price</th>
                                <th>Link From</th>
                                <th>Link To</th>
                                <th>Anchor Text</th>
                                <th>Date for Proccessing</th>
                                <th>Date Completed</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            <tr v-if="listSales.data.length == 0">
                                <td colspan="11" class="text-center">No record</td>
                            </tr>
                            <tr v-for="(sales, index) in listSales.data" :key="index">
                                <td>{{ index + 1}}</td>
                                <td>{{ sales.publisher.id }}</td>
                                <td>{{ sales.publisher.url }}</td>
                                <td>$ {{ sales.publisher.price }}</td>
                                <td>{{ sales.link_from }}</td>
                                <td><a href="sales.link">{{ sales.link }}</a></td>
                                <td>{{ sales.anchor_text }}</td>
                                <td>{{ sales.date_process }}</td>
                                <td>{{ sales.live_date }}</td>
                                <td>{{ sales.status }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button data-toggle="modal" @click="doUpdate(sales)" data-target="#modal-update-sales" title="Edit" class="btn btn-default"><i class="fa fa-fw fa-edit"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="modal-update-sales" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Sales Information</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">Date Processed</label>
                                        <input type="date" class="form-control" :disabled="true" v-model="updateModel.date_process">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: #333">Buyers name</label>
                                    <input type="text" :disabled="true" v-model="updateModel.user.name" class="form-control" required="required" >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: #333">URL Publisher</label>
                                    <input type="text" v-model="updateModel.url_publisher" :disabled="true" class="form-control" required="required" >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: #333">URL Advertiser</label>
                                    <input type="text" :disabled="true" v-model="updateModel.url_advertiser" class="form-control" required="required" >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">Price</label>
                                        <input type="number" class="form-control" v-model="updateModel.price" :disabled="true" value="" required="required" >
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">Anchor text</label>
                                        <input type="text" class="form-control" :disabled="true" v-model="updateModel.anchor_text" required="required" >
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">Link From</label>
                                        <input type="text" class="form-control" v-model="updateModel.link_from" required="required" >
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">Link To</label>
                                        <input type="text" class="form-control" :disabled="true" v-model="updateModel.link" required="required" >
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">URL From</label>
                                        <input type="text" class="form-control" v-model="updateModel.url_from" required="required" >
                                    </div>
                                </div>
                            </div> -->

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">Status</label>
                                        <select  class="form-control pull-right" v-model="updateModel.status" style="height: 37px;">
                                            <option v-for="status in statusBaclink" v-bind:value="status">{{ status }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">Article ID</label>
                                        <input type="text" class="form-control" v-model="updateModel.article_id">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">Date Completed</label>
                                        <input type="date" class="form-control" v-model="updateModel.live_date">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitUpdate" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        data() {
            return {
                statusBaclink: ['Processing', 'Content writing', 'Content sent', 'Live'],
                updateModel: {
                    id: '',
                    url_publisher: '',
                    url_advertiser: '',
                    anchor_text: '',
                    link: '',
                    live_date: '',
                    status: '',
                    article_id: '',
                    date_process: '',
                    user: {
                        name: ''
                    },
                    link_from: '',
                    url_from: '',
                },
                isPopupLoading: false,
                filterModel: {
                    search: this.$route.query.search || '',
                    status: this.$route.query.status || '',
                },
                searchLoading: false,
            }
        },

        async created() {
            this.getListSales();
        },

        computed: {
            ...mapState({
                listSales: state => state.storeFollowupSales.listSales,
                messageForms: state => state.storeFollowupSales.messageForms,
            })
        },

        methods: {
            async getListSales(params){
                this.searchLoading = true;
                await this.$store.dispatch('actionGetListSales', {
                    params: {
                        search: this.filterModel.search,
                        status: this.filterModel.status,
                    }
                });
                this.searchLoading = false;
            },

            doSearch() {
                this.$router.push({
                    query: this.filterModel,
                });

                this.getListSales({
                    params: {
                        search: this.filterModel.search,
                        status: this.filterModel.status,
                    }
                });
            },

            clearSearch() {
                this.filterModel = {
                    search: '',
                    status: '',
                }

                this.getListSales({
                    params: this.filterModel
                });

                this.$router.replace({'query': null});
            },

            doUpdate(sales) {
                this.clearMessageform()
                let that = JSON.parse( JSON.stringify(sales) )

                this.updateModel = that
                this.updateModel.url_publisher = that.publisher == null ? that.ext_domain.domain:that.publisher.url
            },

            async submitUpdate(params) {
                this.isPopupLoading = true;
                await this.$store.dispatch('actionUpdateSales', this.updateModel)
                this.isPopupLoading = false;
                this.getListSales()
            },

            clearMessageform() {
                this.$store.dispatch('clearMessageform');
            },
        }
    }
</script>