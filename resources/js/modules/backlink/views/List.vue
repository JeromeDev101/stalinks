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
                                <input v-model="fillter.querySearch" type="text" name="search" class="form-control" placeholder="Type here">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Search URL Advertiser</label>
                                <input v-model="fillter.url_advertiser" type="text" name="search" class="form-control" placeholder="Type here">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Search ID Backlink</label>
                                <input v-model="fillter.backlink_id" type="text" class="form-control" placeholder="Type here">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Status</label>
                                <select class="form-control" v-model="fillter.status">
                                    <option value="">All</option>
                                    <option v-for="status in statusBaclink" v-bind:value="status">{{ status }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2" v-if="(user.isOurs == 0 && !user.isAdmin) || user.isAdmin">
                            <div class="form-group">
                                <label for="">Seller</label>
                                <select class="form-control" v-model="fillter.seller">
                                    <option value="">All</option>
                                    <option v-for="seller in listSeller.data" v-bind:value="seller.id">{{ seller.username }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2" v-if="(user.isOurs == 0 && !user.isAdmin) || user.isAdmin">
                            <div class="form-group">
                                <label for="">Buyer</label>
                                <select class="form-control" v-model="fillter.buyer">
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
                            <button class="btn btn-default" @click="clearSearch" :disabled="isSearching">Clear</button>
                            <button @click="getBackLinkList()" type="submit" name="submit" class="btn btn-default" :disabled="isSearching">Search <i v-if="searchLoading" class="fa fa-refresh fa-spin" ></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box">

                <div class="box-header">
                    <h3 class="box-title">Follow up Backlinks</h3>

                    <h5 class="d-inline float-right">Amount: $ {{ totalAmount }}</h5>

                    <table width="100%">
                        <tr>
                            <td>
                                <div class="input-group input-group-sm float-right" style="width: 100px">
                                    <select class="form-control float-right" @change="getBackLinkList" v-model="fillter.paginate" style="height: 37px;">
                                        <option v-for="option in paginate" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select>
                                </div>

                                <div v-if="Object.keys(listBackLink).length !== 0" class="pull-right">
                                    <download-csv
                                        :data = "listBackLink.data"
                                        :fileds = "data_filed"
                                        :nameFile = "file_csv">
                                    </download-csv>
                                </div>
                            </td>
                        </tr>
                    </table>    
                        
                </div>

                <div class="box-body no-padding">

                    <span v-if="listBackLink.total > 15" class="pagination-custom-footer-text">
                        <b>Showing {{ listBackLink.from }} to {{ listBackLink.to }} of {{ listBackLink.total }} entries.</b>
                    </span>

                    <table id="tbl_backlink" class="table table-hover table-bordered table-striped rlink-table">
                        <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th v-if="user.isOurs == 0">ID Bck</th>
                                <th v-if="(user.isOurs == 0 && !user.isAdmin) || user.isAdmin">Seller</th>
                                <th v-if="(user.isOurs == 0 && !user.isAdmin) || user.isAdmin">Buyer</th>
                                <th>URL Publisher</th>
                                <th v-if="user.isAdmin || (user.isOurs == 0 && user.role_id == 5)">URL Advertiser</th>
                                <th>Link From</th>
                                <th v-if="(user.isOurs == 1 && !user.isAdmin)">Link To</th>
                                <th>Price</th>
                                <th v-if="(user.isOurs == 1 && !user.isAdmin) ">Anchor Text</th>
                                <th>Date for Proccess</th>
                                <th>Date Completed</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(backLink, index) in listBackLink.data" :key="index">
                                <td class="center-content">{{ index + 1 }}</td>
                                <td v-if="user.isOurs == 0">{{ backLink.id }}</td>
                                <td v-if="(user.isOurs == 0 && !user.isAdmin) || user.isAdmin">{{backLink.publisher.user.username == null ? backLink.publisher.user.name : backLink.publisher.user.username }}</td>
                                <td v-if="(user.isOurs == 0 && !user.isAdmin) || user.isAdmin">{{backLink.user.username == null ? backLink.user.name : backLink.user.username}}</td>
                                <td>{{ replaceCharacters(backLink.publisher.url) }}</td>
                                <td v-if="user.isAdmin || (user.isOurs == 0 && user.role_id == 5)">{{ backLink.url_advertiser }}</td>
                                <td>
                                    <div class="dont-break-out">
                                        {{ backLink.link_from }}
                                    </div>
                                </td>
                                <td v-if="(user.isOurs == 1 && !user.isAdmin)">
                                    <div class="dont-break-out">
                                        <a href="backLink.link">{{ backLink.link }}</a>
                                    </div>
                                </td>
                                <td>$ {{ convertPrice(backLink.price) }}</td>
                                <td v-if="(user.isOurs == 1 && !user.isAdmin)">{{ backLink.anchor_text }}</td>
                                <td>{{ backLink.date_process }}</td>
                                <td>{{ backLink.live_date }}</td>
                                <td>{{ backLink.status }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-default" @click="editBackLink(backLink)" title="Edit"><i class="fa fa-fw fa-edit"></i></button>
                                    </div>
                                    <div v-if="user.isAdmin" class="btn-group">
                                        <button class="btn btn-default" @click="deleteBackLink(backLink.id, backLink.publisher.user.username, backLink.user.username)" title="Delete"><i class="fa fa-fw fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- <pagination :data="listBackLink" @pagination-change-page="getBackLinkList($event)"></pagination> -->
            </div>
        </div>

        <!--   Modal Edit Followup Backlink -->
        <div v-if="openModalBackLink" class="modal fade"  ref="modalEditBacklink" >
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Follow up Backlinks Information</h4>
                        <div class="modal-load overlay float-right">
                            <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>
                            <span v-if="messageBacklinkForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageBacklinkForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageBacklinkForms.message }}
                            </span>
                        </div>
                    </div>
                    <div class="modal-body relative">
                        <form class="row" action="">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Seller name</label>
                                    <input type="text" v-model="modelBaclink.username" :disabled="true" class="form-control" required="required" >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label>Date Processed</label>
                                        <input type="date" :disabled="isBuyer || isPostingWriter || user.role_id == 8" v-model="modelBaclink.date_process" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.ext_domain_id}" class="form-group">
                                    <label>URL Publisher</label>
                                    <input type="text" v-model="modelBaclink.ext_domain.domain" :disabled="true" class="form-control" required="required" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>URL Advertiser</label>
                                    <input type="text" v-model="modelBaclink.url_advertiser"  :disabled="isPostingWriter || user.role_id == 8" class="form-control" required="required" >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.price}" class="form-group">
                                    <div>
                                        <label>Price</label>
                                        <input type="number" v-model="modelBaclink.price" :disabled="isBuyer || isPostingWriter || modelBaclink.status == 'Live' || user.role_id == 8" class="form-control" value="" required="required" >
                                        <span v-if="messageBacklinkForms.errors.price" v-for="err in messageBacklinkForms.errors.price" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.anchor_text}" class="form-group">
                                    <div>
                                        <label>Anchor text</label>
                                        <input type="text" v-model="modelBaclink.anchor_text" :disabled="user.role_id == 8" class="form-control" required="required" >
                                        <span v-if="messageBacklinkForms.errors.anchor_text" v-for="err in messageBacklinkForms.errors.anchor_text" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.title}" class="form-group">
                                    <div>
                                        <label>Title</label>

                                        <input type="text" v-model="modelBaclink.title" class="form-control"  :disabled="user.role_id == 8" required="required" >
                                        <span v-if="messageBacklinkForms.errors.title" v-for="err in messageBacklinkForms.errors.title" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.link}" class="form-group">
                                    <div>
                                        <label>Link To</label>

                                        <input type="text" v-model="modelBaclink.link" class="form-control" :disabled="isPostingWriter || user.role_id == 8" required="required" >
                                        <span v-if="messageBacklinkForms.errors.link" v-for="err in messageBacklinkForms.errors.link" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.link_from}" class="form-group">
                                    <div>
                                        <label>Link From</label>
                                        <input type="text" v-model="modelBaclink.link_from" class="form-control" :disabled="true">
                                        <span v-if="messageBacklinkForms.errors.link_from" v-for="err in messageBacklinkForms.errors.link_from" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.live_date}" class="form-group">
                                    <div>
                                        <label>Date Completed</label>
                                        <input type="date" v-model="modelBaclink.live_date" class="form-control" :disabled="isBuyer || user.role_id == 8">
                                        <span v-if="messageBacklinkForms.errors.live_date" v-for="err in messageBacklinkForms.errors.live_date" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.status}" class="form-group">
                                    <div>
                                        <label>Status</label>
                                        <select  class="form-control pull-right" v-model="modelBaclink.status" style="height: 37px;" :disabled="isBuyer || user.role_id == 8">
                                          <option v-for="status in statusBaclink" v-bind:value="status">{{ status }}</option>
                                        </select>
                                        <span v-if="messageBacklinkForms.errors.status" v-for="err in messageBacklinkForms.errors.status" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" v-if="withArticle">
                                <div class="form-group">
                                    <div>
                                        <label>Article ID</label>
                                        <input type="text" class="form-control" v-model="modelBaclink.id_article" :disabled="true ">
                                        <!-- <input type="text" class="form-control" :disabled="isBuyer || isPostingWriter"> -->
                                    </div>
                                </div>
                            </div>


                        </form>
                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" @click="closeModalBacklink">Close</button>
                        <button type="button" :disabled="checkSelectIntDomain" @click="submitEditBacklink" class="btn btn-primary">Save</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--    End Modal Add-->
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
    import Hepler from '@/library/Helper';
    import { mapState, mapSetter } from 'vuex';
    import { Domain } from 'domain';
    import DownloadCsv from '@/components/export-csv/Csv.vue'
    import _ from 'lodash'
    import axios from 'axios';

    export default {
        name: 'BackLinkList',
        data() {
            return {
                paginate: [50,150,250,350,500,'All'],
                file_csv: 'baclink.xls',
                statusBaclink: ['Processing', 'Content In Writing', 'Content Done', 'Content sent', 'Live', 'Issue', 'Canceled'],
                data_filed: {
                    'URL Publisher': 'publisher.url',
                    'URL Advertiser': 'url_advertiser',
                    'Link From': 'link_from',
                    'Link To': 'link',
                    'Price': 'price',
                    'Anchor Text': 'anchor_text',
                    'Date Completed': 'live_date',
                    'Status': 'status'
                },
                json_meta: [
                    [{
                    'key': 'charset',
                    'value': 'utf-8'
                    }]
                ],
                page: this.$route.query.page || 1,
                modalAddBackLink: false,
                modelBaclink: {
                    ext_domain: {
                        domain: ''
                    },
                    int_domain: {
                        domain: ''
                    },
                    user: {
                        name: ''
                    }
                },
                loadIntDomain: false,
                isPopupLoading: false,
                isBuyer: false,
                isPostingWriter: false,
                searchLoading: false,
                withArticle: true,
                totalAmount: 0,
                isSearching: false,
            }
        },
        async created() {
            await this.$store.dispatch('actionCheckAdminCurrentUser', { vue: this });
            await this.getBackLinkList();
            this.checkAccountType();
            this.getSellerList();
            this.getBuyerList();
        },

        computed: {

            ...mapState({
                listBackLink: state => state.storeBackLink.listBackLink,
                fillter: state => state.storeBackLink.fillter,
                user: state => state.storeAuth.currentUser,
                messageBacklinkForms: state => state.storeBackLink.messageBacklinkForms,
                listSeller: state => state.storeAccount.listAccount,
                listBuyer: state => state.storeFollowupSales.listBuyer,
            }),

            openModalBackLink() {
            if (this.modalAddBackLink = true) {
                return true
            }

            return false
            },

            checkSelectIntDomain () {
            if (this.modelBaclink.int_domain_id == 0) {
                return true
            }

            return false
            },
        },

        mounted() {
            $(this.$refs.modalEditBacklink).on("hidden.bs.modal", this.handleCloseBacklinkModal)
        },

        methods: {
            getBackLinkList: _.debounce(async function(page) {
                $("#tbl_backlink").DataTable().destroy();
                
                if (page) {
                    this.page = page
                }
                this.$router.push({
                        query: {
                        page: this.page,
                    },
                })

                this.searchLoading = true;
                this.isSearching = true;
                await this.$store.dispatch('actionGetBackLink', {
                    vue: this,
                    page: this.page,
                    params: this.fillter,
                });
                this.searchLoading = false;
                this.isSearching = false;

                let columnDefs = [
                    { orderable: true, targets: 0 },
                    { orderable: true, targets: 1 },
                    { orderable: true, targets: 2 },
                    { orderable: true, targets: 3 },
                    { orderable: true, targets: 4 },
                    { orderable: true, targets: 5 },
                    { orderable: true, targets: 6, width: "200px" },
                    { orderable: true, targets: 7, width: "200px" },
                    { orderable: true, targets: 8 },
                    { orderable: true, targets: 9 },
                    { orderable: true, targets: 10 },
                    { orderable: false, targets: '_all' }
                ];

                if (this.user.isOurs == 1){
                    columnDefs = [
                        { orderable: true, targets: 0 },
                        { orderable: true, targets: 1 },
                        { orderable: true, targets: 2, width: "200px" },
                        { orderable: true, targets: 3, width: "200px" },
                        { orderable: true, targets: 4 },
                        { orderable: true, targets: 5 },
                        { orderable: true, targets: 6 },
                        { orderable: true, targets: 7 },
                        { orderable: true, targets: 8 },
                        { orderable: false, targets: '_all' }
                    ];
                }

                $("#tbl_backlink").DataTable({
                    paging: false,
                    searching: false,
                    columnDefs: columnDefs,
                });

                this.getTotalAmount()

            }, 200),

            deleteBackLink(id, seller, buyer) {
                swal.fire({
                    title: "Are you sure ?",
                    html: "<b>ID Backlink: </b>" + id + " <br> <b>Seller: </b>" + seller + " <br> <b>Buyer: </b>" + buyer + "<br><br> Articles is also included to delete, Do you want to continue? <br>",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, keep it'
                })
                .then((result) => {
                    if (result.isConfirmed) {

                        axios.post('/api/delete-backlinks', {
                            id:id
                        })
                        .then(response => response)
                        .catch(error => error);

                        this.getBackLinkList();

                        swal.fire(
                            'Deleted!',
                            'Backlinks is already deleted.',
                            'success'
                        )
                    }
                });

            },

            async getSellerList(params) {
                await this.$store.dispatch('actionGetSeller');
            },

            async getBuyerList(params) {
                await this.$store.dispatch('actionGetListBuyer');
            },

            async clearSearch() {
                await this.$store.dispatch('actionResetFillterBacklink');
                this.fillter.status = ''
                this.fillter.paginate = '50'
                this.getBackLinkList();
            },

            checkArray(array) {
                return Hepler.arrayNotEmpty(array);
            },

            replaceCharacters(str) {
                let char1 = str.replace("http://", "");
                let char2 = char1.replace("https://", "");
                let char3 = char2.replace("www.", "");

                return char3;
            },

            getTotalAmount() {
                let incomes = this.listBackLink.data
                let total_price = [];
                let total = 0;
                incomes.forEach(function(item, index){
                    total_price.push( parseFloat(item.price))
                })

                if( total_price.length > 0 ){
                    total = total_price.reduce(this.calcSum)
                }
                this.totalAmount = total.toFixed(2);
            },

            calcSum(total, num) {
                return total + num
            },

            checkAccountType() {
                let that = this.user

                if( that.user_type ){
                    if( that.user_type.type == 'Buyer' ){
                        this.isBuyer = true;
                    }
                }

                if( that.role.id == 4 ){
                    this.isPostingWriter = true;
                }
            },

            editBackLink(baclink) {
                this.modalAddBackLink = true
                let that = Object.assign({}, baclink)
                this.withArticle = that.publisher.inc_article == "No" ? true:false; 
                this.modelBaclink.id = that.id
                this.modelBaclink.publisher_id = that.publisher.id
                this.modelBaclink.ext_domain.domain = that.publisher == null ? that.ext_domain.domain:that.publisher.url
                this.modelBaclink.int_domain.domain = that.int_domain == null ? '':that.int_domain.domain
                this.modelBaclink.username = that.publisher.user.username
                this.modelBaclink.anchor_text = that.anchor_text
                this.modelBaclink.price = that.price
                this.modelBaclink.link = that.link
                this.modelBaclink.link_from = that.link_from
                this.modelBaclink.live_date = that.live_date
                this.modelBaclink.title = that.title
                this.modelBaclink.status = that.status
                this.modelBaclink.user_id = that.user_id
                this.modelBaclink.date_process = that.date_process
                this.modelBaclink.url_advertiser = that.url_advertiser

                this.modelBaclink.seller = that.publisher.user.name
                this.modelBaclink.id_article = that.article == null ? '':that.article.id

                let element = this.$refs.modalEditBacklink
                $(element).modal('show')
            },

            closeModalBacklink() {
                this.modalAddBackLink = false
                let element = this.$refs.modalEditBacklink
                $(element).modal('hide')
            },

            async submitEditBacklink () {
                await this.$store.dispatch('actionSaveBacklink', {
                    params: this.modelBaclink
                })

                if (this.messageBacklinkForms.action === 'saved_backlink') {
                    this.closeModalBacklink()
                    this.getBackLinkList()
                }
            },

            handleCloseBacklinkModal () {
                this.modalAddBackLink =  false
                this.$store.dispatch('clearMessageBacklinkForm')
            },

            convertPrice(price) {
                return parseFloat(price).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            }
        },

        components: {
            DownloadCsv
        }
}
</script>