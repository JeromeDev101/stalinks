<template>
    <div class="row">
        <div class="col-sm-12">

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Filter</h3>
                    <button class="btn btn-primary ml-5" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        <i class="fa fa-plus"></i> Show Filter
                    </button>
                </div>

                <div class="box-body m-3 collapse" id="collapseExample">

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Search URL Publisher</label>
                                <input type="text" class="form-control" v-model="filterModel.search" name="" aria-describedby="helpId" placeholder="Type here">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Search ID Backlink</label>
                                <input type="text" class="form-control" v-model="filterModel.backlink_id" name="" aria-describedby="helpId" placeholder="Type here">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Status</label>
                                <v-select multiple
                                          v-model="filterModel.status" :options="statusBaclink" :searchable="false" placeholder="All"/>
<!--                                <select name="" class="form-control" v-model="filterModel.status">-->
<!--                                    <option value="">All</option>-->
<!--                                    <option v-for="status in statusBaclink" v-bind:value="status">{{ status }}</option>-->
<!--                                </select>-->
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

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Country</label>
                                <select name="" class="form-control" v-model="filterModel.country_id">
                                    <option value="">All</option>
                                    <option v-for="option in listCountryAll.data" v-bind:value="option.id">
                                        {{ option.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Article</label>
                                <select name="" class="form-control" v-model="filterModel.article">
                                    <option value="">All</option>
                                    <option value="With">With</option>
                                    <option value="Without">Without</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">In charge</label>
                                <select name="" class="form-control" v-model="filterModel.in_charge">
                                    <option value="">All</option>
                                    <option v-for="option in listIncharge.data" v-bind:value="option.id">
                                        {{ option.username == null ? option.name:option.username}}
                                    </option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <button class="btn btn-default" @click="clearSearch" :disabled="isSearching">Clear</button>
                            <button class="btn btn-default" @click="doSearch" :disabled="isSearching">Search <i v-if="searchLoading" class="fa fa-refresh fa-spin" ></i></button>
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
                                <button data-toggle="modal" data-target="#modal-setting" class="btn btn-default float-right"><i class="fa fa-cog"></i></button>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="box-body no-padding">
                    <span class="pagination-custom-footer-text">
                        <b>Showing {{ listSales.from }} to {{ listSales.to }} of {{ listSales.total }} entries.</b>
                    </span>

                    <table id="tbl-followupsales"
                           class="table table-hover table-bordered table-striped rlink-table table-responsive" style="height: 650px;">
                        <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th v-show="tblOptions.pub_id">Url Pub</th>
                                <th v-show="tblOptions.blink_id">Blink</th>
                                <th v-show="tblOptions.arc_id">Artc</th>
                                <th v-show="tblOptions.country">Country</th>
                                <th v-show="tblOptions.in_charge">In-charge</th>
                                <th v-show="tblOptions.seller" v-if="user.isOurs != 1">Seller</th>
                                <th v-show="tblOptions.buyer" v-if="user.isOurs != 1">Buyer</th>
                                <th v-show="tblOptions.url">URL Publisher</th>
                                <th v-show="tblOptions.price">Price</th>
                                <th v-show="tblOptions.link_from">Link From</th>
                                <th v-show="tblOptions.link_to">Link To</th>
                                <th v-show="tblOptions.anchor_text">Anchor Text</th>
                                <th v-show="tblOptions.date_process">Date for Proccess</th>
                                <th v-show="tblOptions.date_complete">Date Completed</th>
                                <th v-show="tblOptions.status">Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(sales, index) in listSales.data" :key="index">
                                <td>{{ index + 1}}</td>
                                <td v-show="tblOptions.pub_id">{{ sales.publisher.id }}</td>
                                <td v-show="tblOptions.blink_id">{{ sales.id }}</td>
                                <td v-show="tblOptions.arc_id">{{ sales.article_id == null ? 'N/A':'' }} <a href="#" @click="redirectToArticle(sales.article_id)" v-if="sales.article_id != null" title="Go to Article">{{ sales.article_id }}</a></td>
                                <td v-show="tblOptions.country">{{ sales.publisher.country == null ? 'N/A' : sales.publisher.country.name }}</td>
                                <td v-show="tblOptions.in_charge">{{ sales.in_charge == null ? 'N/A':sales.in_charge }}</td>
                                <td v-show="tblOptions.seller" v-if="user.isOurs != 1">{{ sales.publisher.user.username == null ? sales.publisher.user.name : sales.publisher.user.username }}</td>
                                <td v-show="tblOptions.buyer" v-if="user.isOurs != 1">{{ sales.user.username == null ? sales.user.name : sales.user.username }}</td>
                                <td v-show="tblOptions.url">{{ replaceCharacters(sales.publisher.url) }}</td>
                                <td v-show="tblOptions.price">$ {{ sales.publisher.price }}</td>
                                <td v-show="tblOptions.link_from">
                                    <div class="dont-break-out">
                                        {{ sales.link_from }}
                                    </div>
                                </td>
                                <td v-show="tblOptions.link_to">
                                    <div class="dont-break-out">
                                        {{ sales.link }}
                                    </div>
                                </td>
                                <td v-show="tblOptions.anchor_text">{{ sales.anchor_text }}</td>
                                <td v-show="tblOptions.date_process">{{ sales.date_process }}</td>
                                <td v-show="tblOptions.date_complete">{{ sales.live_date }}</td>
                                <td v-show="tblOptions.status">{{ sales.status }}</td>
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

        <!-- Modal Update Sales -->
        <div class="modal fade" id="modal-update-sales" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Sales Information | ID Backlink: <b class="text-primary">{{ updateModel.id }}</b> </h5>
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
                                        <select  class="form-control pull-right" v-model="updateModel.status" style="height: 37px;" :disabled="isLive && user.role_id != 8" @change="checkStatus()">
                                            <option v-show="user.role_id != 8 && user.role_id != 3 && user.role_id != 1" v-for="status in statusBaclink" v-bind:value="status">{{ status }}</option>
                                            <option v-show="user.role_id == 8 || user.role_id == 3 || user.role_id == 1" v-for="status in statusBaclinkQc" v-bind:value="status">{{ status }}</option>
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
                                        <input type="date" class="form-control" v-model="updateModel.live_date" :disabled="isLive || user.role_id === 6">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" v-show="showReason">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">Reason</label>
                                        <select  class="form-control pull-right" v-model="updateModel.reason" style="height: 37px;" @change="checkReason()">
                                            <option v-for="reason in listReason" v-bind:value="reason">{{ reason }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" v-show="showReasonText">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">Details of Issue/Cancelled</label>
                                        <textarea class="form-control" v-model="updateModel.reason_detailed"></textarea>
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
        <!-- End of Modal Update Sales -->

        <!-- Modal Settings -->
        <div class="modal fade" id="modal-setting" style="display: none;">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Setting Default</h4>
                        <div class="modal-load overlay float-right">
                        </div>
                    </div>
                    <div class="modal-body relative">
                        <div class="form-group row">
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblOptions.pub_id ? 'checked':''" v-model="tblOptions.pub_id">URL Publisher ID</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblOptions.blink_id ? 'checked':''" v-model="tblOptions.blink_id">Backlink ID</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblOptions.arc_id ? 'checked':''" v-model="tblOptions.arc_id">Article ID</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblOptions.country ? 'checked':''" v-model="tblOptions.country">Country</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblOptions.in_charge ? 'checked':''" v-model="tblOptions.in_charge">In-charge</label>
                            </div>
                            <div class="checkbox col-md-4" v-if="user.isOurs != 1">
                                <label><input type="checkbox" :checked="tblOptions.seller ? 'checked':''" v-model="tblOptions.seller">Seller</label>
                            </div>
                            <div class="checkbox col-md-4" v-if="user.isOurs != 1">
                                <label><input type="checkbox" :checked="tblOptions.buyer ? 'checked':''" v-model="tblOptions.buyer">Buyer</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblOptions.url ? 'checked':''" v-model="tblOptions.url">URL Publisher</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblOptions.price ? 'checked':''" v-model="tblOptions.price">Price</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblOptions.link_from ? 'checked':''" v-model="tblOptions.link_from">Link From</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblOptions.link_to ? 'checked':''" v-model="tblOptions.link_to">Link To</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblOptions.anchor_text ? 'checked':''" v-model="tblOptions.anchor_text">Anchor Text</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblOptions.date_process ? 'checked':''" v-model="tblOptions.date_process">Date process</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblOptions.date_complete ? 'checked':''" v-model="tblOptions.date_complete">Date Completed</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblOptions.status ? 'checked':''" v-model="tblOptions.status">Status</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitUpdate" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Settings -->

    </div>
</template>

<style scoped>
.dont-break-out {
    /* These are technically the same, but use both */
    overflow-wrap: break-word;
    word-wrap: break-word;

    -ms-word-break: break-all;
    /* This is the dangerous one in WebKit, as it breaks things wherever */
    word-break: break-all;
    /* Instead use this non-standard one: */
    word-break: break-word;

    /* Adds a hyphen where the word breaks, if supported (No Blink) */
    -ms-hyphens: auto;
    -moz-hyphens: auto;
    -webkit-hyphens: auto;
    hyphens: auto;
}
</style>

<script>
    import { mapState } from 'vuex';

    export default {
        data() {
            return {
                paginate: [50,150,250,350,'All'],
                statusBaclink: ['Processing', 'Content In Writing', 'Content Done', 'Content sent', 'Live in Process', 'Issue', 'Canceled'],
                statusBaclinkQc: ['Processing', 'Content In Writing', 'Content Done', 'Content sent', 'Live', 'Live in Process', 'Issue', 'Canceled'],
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
                    reason: '',
                    reason_detailed: '',
                },
                isPopupLoading: false,
                filterModel: {
                    backlink_id: this.$route.query.backlink_id || '',
                    search: this.$route.query.search || '',
                    status: this.$route.query.status || '',
                    seller: this.$route.query.seller || '',
                    buyer: this.$route.query.buyer || '',
                    paginate: this.$route.query.paginate || '50',
                    article: this.$route.query.article || '',
                    in_charge: this.$route.query.in_charge || '',
                    country_id: this.$route.query.country_id || '',
                },
                searchLoading: false,
                isLive: false,
                totalAmount: 0,
                isSearching: false,
                showReason: false,
                showReasonText: false,
                reasonIssue: [
                    'URL link to cannot be found',
                    'One-click ads URL',
                    'I am having technical issue',
                    'I am in vacation',
                    'Articles are no good',
                    'Other'
                ],
                reasonCancelled: [
                    'URL not available',
                    'URL sold',
                    'Not selling backlink',
                    'Other'
                ],
                listReason: '',
            }
        },

        async created() {
            this.getListSales();
            this.getListSeller();
            this.getListBuyer();

            let countries = this.listCountryAll.data;
            if( countries.length === 0 ){
                this.getListCountries();
            }

            let in_charge = this.listIncharge.data;
            if( in_charge.length === 0 ){
                this.getTeamInCharge();
            }
        },

        computed: {
            ...mapState({
                tblOptions: state => state.storeFollowupSales.tblFollowupSalesOpt,
                listSales: state => state.storeFollowupSales.listSales,
                listBuyer: state => state.storeFollowupSales.listBuyer,
                listSeller: state => state.storeFollowupSales.listSeller,
                messageForms: state => state.storeFollowupSales.messageForms,
                user: state => state.storeAuth.currentUser,
                listCountryAll: state => state.storePublisher.listCountryAll,
                listIncharge: state => state.storeAccount.listIncharge,

            }),
        },

        methods: {

            checkReason() {
                if(this.updateModel.reason == 'Other'){
                    this.showReasonText = true;
                } else {
                    this.showReasonText = false;
                }
            },

            checkStatus() {
                this.updateModel.reason = ''
                if(this.updateModel.status === 'Issue'){
                    this.showReason = true;
                    this.listReason = this.reasonIssue
                } else if(this.updateModel.status === 'Canceled'){
                    this.showReason = true;
                    this.listReason = this.reasonCancelled
                } else {
                    this.showReason = false;
                    this.showReasonText = false;
                }
            },

            async getTeamInCharge(){
                await this.$store.dispatch('actionGetTeamInCharge');
            },

            async getListCountries(params) {
                await this.$store.dispatch('actionGetListCountries', params);
            },

            async getListSales(params){

                $('#tbl-followupsales').DataTable().destroy();

                this.searchLoading = true;
                this.isSearching = true;
                if(this.filterModel.paginate == 'All')
                {
                  await this.$store.dispatch('actionGetListSales', {
                        params: {
                            backlink_id: this.filterModel.backlink_id,
                            search: this.filterModel.search,
                            status: this.filterModel.status,
                            seller: this.filterModel.seller,
                            buyer: this.filterModel.buyer,
                            paginate: 1000000,
                            article: this.filterModel.article,
                            in_charge: this.filterModel.in_charge,
                            country_id: this.filterModel.country_id,
                        }

                    });

                }else
                {
                    await this.$store.dispatch('actionGetListSales', {
                        params: {
                            backlink_id: this.filterModel.backlink_id,
                            search: this.filterModel.search,
                            status: this.filterModel.status,
                            seller: this.filterModel.seller,
                            buyer: this.filterModel.buyer,
                            paginate: this.filterModel.paginate,
                            article: this.filterModel.article,
                            in_charge: this.filterModel.in_charge,
                            country_id: this.filterModel.country_id,
                        }

                    });
                }

                    console.log(this.filterModel.paginate);
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
                        { orderable: true, targets: 10, width: "200px" },
                        { orderable: true, targets: 11, width: "200px" },
                        { orderable: true, targets: 12 },
                        { orderable: true, targets: 13 },
                        { orderable: true, targets: 14 },
                        { orderable: true, targets: 15 },
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
                        { orderable: true, targets: 8, width: "200px"},
                        { orderable: true, targets: 9, width: "200px" },
                        { orderable: true, targets: 10 },
                        { orderable: true, targets: 11 },
                        { orderable: true, targets: 12 },
                        { orderable: true, targets: 13 },
                        { orderable: false, targets: '_all' }
                    ]
                }

                $('#tbl-followupsales').DataTable({
                    autoWidth: false,
                    paging: false,
                    searching: false,
                    columnDefs: columnDefs,
                });

                this.searchLoading = false;
                this.isSearching = false;

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
                        backlink_id: this.filterModel.backlink_id,
                        search: this.filterModel.search,
                        status: this.filterModel.status,
                        seller: this.filterModel.seller,
                        buyer: this.filterModel.buyer,
                        paginate: this.filterModel.paginate,
                        article: this.filterModel.article,
                        country_id: this.filterModel.country_id,
                        in_charge: this.filterModel.in_charge,
                    }
                });
            },

            clearSearch() {
                $('#tbl-followupsales').DataTable().destroy();

                this.filterModel = {
                    backlink_id: '',
                    search: '',
                    status: '',
                    seller: '',
                    buyer: '',
                    paginate: '50',
                    article: '',
                    in_charge: '',
                    country_id: '',
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

                // to display the Details of Issue/Cancelled field
                if(this.updateModel.reason == 'Other' && this.updateModel.reason != null ){
                    this.showReasonText = true;
                } else {
                    this.showReasonText = false;
                }

                // to display the Reason field
                if(this.updateModel.status === 'Issue'){
                    this.showReason = true;
                    this.listReason = this.reasonIssue
                } else if(this.updateModel.status === 'Canceled'){
                    this.showReason = true;
                    this.listReason = this.reasonCancelled
                } else {
                    this.showReason = false;
                    this.showReasonText = false;
                }


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
