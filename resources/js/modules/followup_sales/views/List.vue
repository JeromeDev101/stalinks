<template>
    <div class="row">
        <div class="col-sm-12">

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Filter</h3>
                </div>

                <div class="box-body m-3">

                    <div class="row">
                        <div class="col-md-2">
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

                        <div class="col-md-2" v-if="user.isOurs != 1">
                            <div class="form-group">
                                <label for="">Seller</label>
                                <select name="" class="form-control" v-model="filterModel.seller">
                                    <option value="">All</option>
                                    <option v-for="option in listSeller.data" v-bind:value="option.id">
                                        {{ option.username == null ? option.name:option.username }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2" v-if="user.isOurs != 1">
                            <div class="form-group">
                                <label for="">Buyer</label>
                                <select name="" class="form-control" v-model="filterModel.buyer">
                                    <option value="">All</option>
                                    <option v-for="option in listBuyer.data" v-bind:value="option.id">
                                        {{ option.username == null ? option.name:option.username }}
                                    </option>
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

                    <h5 class="d-inline pull-right">Amount: $ {{ totalAmount }}</h5>

                    <table width="100%">
                        <tr>
                            <td>
                                <div class="input-group input-group-sm float-right" style="width: 100px">
                                    <select name="" class="form-control float-right" @change="getListSales" v-model="filterModel.paginate" style="height: 37px;">
                                        <option v-for="option in paginate" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                    </table>  
                </div>

                <div class="box-body table-responsive no-padding">
                    <table id="tbl-followupsales" class="table table-hover table-bordered table-striped rlink-table">
                        <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>ID URL publisher</th>
                                <th>ID Backlink</th>
                                <th>ID Article</th>
                                <th v-if="user.isOurs != 1">Seller</th>
                                <th v-if="user.isOurs != 1">Buyer</th>
                                <th>URL Publisher</th>
                                <th>Price</th>
                                <th width="106">Link From</th>
                                <th>Link To</th>
                                <th>Anchor Text</th>
                                <th>Date for Proccess</th>
                                <th>Date Completed</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(sales, index) in listSales.data" :key="index">
                                <td>{{ index + 1}}</td>
                                <td>{{ sales.publisher.id }}</td>
                                <td>{{ sales.id }}</td>
                                <td>{{ sales.article == null ? 'N/A':'' }} <a href="#" @click="redirectToArticle(sales.article.id)" v-if="sales.article != null" title="Go to Article">{{ sales.article.id }}</a></td>
                                <td v-if="user.isOurs != 1">{{ sales.publisher.user.name }}</td>
                                <td v-if="user.isOurs != 1">{{ sales.user.name }}</td>
                                <td>{{ replaceCharacters(sales.publisher.url) }}</td>
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


                    <div style="height:50px;"></div>
                    <span v-if="listSales.total > 10" class="pagination-custom-footer-text float-right">
                        <b>Showing {{ listSales.from }} to {{ listSales.to }} of {{ listSales.total }} entries.</b>
                    </span>

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
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.title}" class="form-group">
                                    <div>
                                        <label style="color: #333">Title</label>
                                        <input type="text" class="form-control" v-model="updateModel.title" required="required" :disabled="isLive">
                                        <span v-if="messageForms.errors.title" v-for="err in messageForms.errors.title" class="text-danger">{{ err }}</span>
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

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.link_from}" class="form-group">
                                    <div>
                                        <label style="color: #333">Link From</label>
                                        <input type="text" class="form-control" v-model="updateModel.link_from" required="required" :disabled="isLive">
                                        <span v-if="messageForms.errors.link_from" v-for="err in messageForms.errors.link_from" class="text-danger">{{ err }}</span>
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

                            <div class="col-sm-6" v-if="updateModel.article_id != ''">
                                <div class="form-group">
                                    <label for="">Status Writer</label>
                                    <select name="" class="form-control" v-model="updateModel.status_writer" :disabled="isLive || user.role_id == 6" >
                                        <option value="">Select Status</option>
                                        <option v-for="option in writer_status" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">Status Sales</label>
                                        <select  class="form-control pull-right" v-model="updateModel.status" style="height: 37px;" :disabled="isLive">
                                            <option v-for="status in statusBaclink" v-bind:value="status">{{ status }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            

                            <div class="col-md-6" v-if="updateModel.article_id != ''">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">Article ID</label>
                                        <input type="text" class="form-control" v-model="updateModel.article_id" :disabled="true">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">Date Completed</label>
                                        <input type="date" class="form-control" v-model="updateModel.live_date" :disabled="isLive">
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
                paginate: [25,50,100,200,250],
                statusBaclink: ['Processing', 'Content In Writing', 'Content Done', 'Content sent', 'Live'],
                writer_status: ['In Writing', 'Done'],
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
                    status_writer: '',
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
                    seller: this.$route.query.seller || '',
                    buyer: this.$route.query.buyer || '',
                    paginate: this.$route.query.paginate || '25',
                },
                searchLoading: false,
                isLive: false,
                totalAmount: 0,
            }
        },

        async created() {
            this.getListSales();
            this.getListSeller();
            this.getListBuyer();
        },

        computed: {
            ...mapState({
                listSales: state => state.storeFollowupSales.listSales,
                listBuyer: state => state.storeFollowupSales.listBuyer,
                listSeller: state => state.storeFollowupSales.listSeller,
                messageForms: state => state.storeFollowupSales.messageForms,
                user: state => state.storeAuth.currentUser,
            })
        },

        methods: {
            async getListSales(params){
                
                $('#tbl-followupsales').DataTable().destroy();

                this.searchLoading = true;
                await this.$store.dispatch('actionGetListSales', {
                    params: {
                        search: this.filterModel.search,
                        status: this.filterModel.status,
                        seller: this.filterModel.seller,
                        buyer: this.filterModel.buyer,
                        paginate: this.filterModel.paginate,
                    }
                });

                let columnDefs = [
                        { orderable: true, targets: 0 },
                        { orderable: true, targets: 1 },
                        { orderable: true, targets: 2 },
                        { orderable: true, targets: 3 },
                        { orderable: true, targets: 4 },
                        { orderable: true, targets: 5 },
                        { orderable: true, targets: 6 },
                        { orderable: true, targets: 7 },
                        { orderable: true, targets: 8 },
                        { orderable: true, targets: 9 },
                        { orderable: true, targets: 10 },
                        { orderable: true, targets: 11 },
                        { orderable: true, targets: 12 },
                        { orderable: true, targets: 13 },
                        { orderable: false, targets: '_all' }
                    ];

                if( this.user.isOurs == 1 ){
                    columnDefs = [
                        { orderable: true, targets: 0 },
                        { orderable: true, targets: 1 },
                        { orderable: true, targets: 2 },
                        { orderable: true, targets: 3 },
                        { orderable: true, targets: 4 },
                        { orderable: true, targets: 5 },
                        { orderable: true, targets: 6 },
                        { orderable: true, targets: 7 },
                        { orderable: true, targets: 8 },
                        { orderable: true, targets: 9 },
                        { orderable: true, targets: 10 },
                        { orderable: true, targets: 11 },
                        { orderable: false, targets: '_all' }
                    ]
                }

                $('#tbl-followupsales').DataTable({
                    paging: false,
                    searching: false,
                    columnDefs: columnDefs,
                });

                this.searchLoading = false;

                this.getTotalAmount();
            },

            replaceCharacters(str) {
                let char1 = str.replace("http://", "");
                let char2 = char1.replace("https://", "");
                let char3 = char2.replace("www.", "");

                return char3;
            },

            getTotalAmount() {
                let sales = this.listSales.data
                let total_price = [];
                let total = 0;
                sales.forEach(function(item, index){
                    if (typeof item.publisher.price !== 'undefined') {
                        total_price.push( parseFloat(item.publisher.price))
                    }
                })

                if( total_price.length > 0 ){
                    total = total_price.reduce(this.calcSum)
                }
                this.totalAmount = total.toFixed(2);
            },

            calcSum(total, num) {
                return total + num
            },

            async getListBuyer(params) {
                await this.$store.dispatch('actionGetListBuyer', params);
            }, 

            async getListSeller(params) {
                await this.$store.dispatch('actionGetListSeller', params);
            }, 

            redirectToArticle(id) {
                this.$router.push({
                    mode: 'history',
                    name: 'articles',
                    query: {
                        id: id,
                    },
                });
            },

            doSearch() {
                $('#tbl-followupsales').DataTable().destroy();

                this.$router.push({
                    query: this.filterModel,
                });

                this.getListSales({
                    params: {
                        search: this.filterModel.search,
                        status: this.filterModel.status,
                        seller: this.filterModel.seller,
                        buyer: this.filterModel.buyer,
                        paginate: this.filterModel.paginate,
                    }
                });
            },

            clearSearch() {
                $('#tbl-followupsales').DataTable().destroy();

                this.filterModel = {
                    search: '',
                    status: '',
                    seller: '',
                    buyer: '',
                    paginate: '25',
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
                this.updateModel.article_id = that.article == null ? '':that.article.id
                this.updateModel.status_writer = that.article == null ? '':that.article.status_writer; 

                this.isLive = false;
                if( that.status == 'Live' && !this.user.isAdmin){
                    this.isLive = true;
                }
            },

            async submitUpdate(params) {
                $('#tbl-followupsales').DataTable().destroy();
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