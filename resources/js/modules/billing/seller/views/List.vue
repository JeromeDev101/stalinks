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
                                <label for="">Search ID Backlink</label>
                                <input type="text" class="form-control" v-model="filterModel.search" name="" aria-describedby="helpId" placeholder="Type here">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Status Billing </label>
                                <select name="" class="form-control" v-model="filterModel.status_billing">
                                    <option value="">All</option>
                                    <option value="Done">Done</option>
                                    <option value="Not Yet">Not Yet</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Seller </label>
                                <select name="" class="form-control" v-model="filterModel.seller">
                                    <option value="">All</option>
                                    <option v-for="option in listSeller.data" v-bind:value="option.id">
                                        {{ option.username == null ? option.name:option.username }}
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
                    <h3 class="box-title">Seller Billing</h3>

                    <h5 class="d-inline pull-right">Amount: $ {{ totalAmount }}</h5>

                    <div class="row">
                        <div class="col-md-2 my-3">

                            <div class="input-group">
                                <div class="dropdown">
                                    <button class="btn btn-default dropdown-toggle" :disabled="isDisabled" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Selected Action
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" @click="doUpdatePayment" href="#" >Pay</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="box-body no-padding relative">
                    <table id="tbl_seller_billing" class="table table-hover table-bordered table-striped rlink-table">
                        <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>Select</th>
                                <th>ID Backlink</th>
                                <th>Seller</th>
                                <th>Seller Price</th>
                                <th>Date Completed</th>
                                <th>Status Billing</th>
                                <th>Status Payment</th>
                                <th>Proof Documents</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(seller, index) in listSellerBilling.data" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-default">
                                            <input type="checkbox" :disabled="seller.proof_doc_path != null" v-on:change="checkSelected" :id="seller.id" :value="seller" v-model="checkIds">
                                        </button>
                                    </div>
                                </td>
                                <td>{{ seller.id }}</td>
                                <td>{{ seller.publisher.user.username == null ? seller.publisher.user.name : seller.publisher.user.username }}</td>
                                <td>$ {{ formatPrice(seller.publisher.price) }}</td>
                                <td>{{ seller.live_date }}</td>
                                <td>{{ seller.admin_confirmation == null ? 'Not Yet':'Done' }}</td>
                                <td>{{ seller.admin_confirmation == null ? 'Not Paid':'Paid' }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button @click="doShow(seller.proof_doc_path)" :disabled="seller.proof_doc_path == null" title="View Proof of Billing" data-target="#modal-view-docs" data-toggle="modal" class="btn btn-default"><i class="fa fa-fw fa-eye"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
        
        <!-- Modal View Docs -->
        <div class="modal fade" id="modal-view-docs" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Proof of Document</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <img class="img-fluid" :src="proof_doc" atl="Proof of Billing" >
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal View Docs -->
        
        <!-- Modal Payment -->
        <div class="modal fade" id="modal-payment" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Payment</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <table class="table">
                                    <tr>
                                        <td style="border-top: 0px;">Seller: <b>{{ info.seller }}</b></td>
                                        <td style="border-top: 0px;">Amount to Pay: <b>$ {{ info.amount }}</b></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            Payment Type: <b :class="{ 'text-danger' : info.payment_type == 'Not yet setup' }">{{ info.payment_type }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            Account: <b :class="{ 'text-danger' : info.account == 'Not yet setup' }">{{ info.account }}</b>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.file}">
                                    <label for="">Proof of Documents</label>
                                    <input type="file" class="form-control" enctype="multipart/form-data" ref="proof" name="file">
                                    <small class="text-muted">Note: It must be image type. ( jpg, jpeg, gif and png )</small><br/>
                                    <span v-if="messageForms.errors.file" v-for="err in messageForms.errors.file" class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                                
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="doPay" class="btn btn-primary" :disabled="isDisabledPay">Pay</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Payment -->
        
    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        data() {
            return {
                checkIds: [],
                isDisabled: true,
                updateModel: {
                    payment_type: ''
                },
                proof_doc: '',
                isPopupLoading: false,
                filterModel: {
                    search: this.$route.query.search || '',
                    status_billing: this.$route.query.status_billing || '',
                    seller: this.$route.query.seller || '',
                },
                searchLoading: false,
                totalAmount: 0,
                info: {
                    seller: '',
                    payment_type: '',
                    payment_type_id: '',
                    account: '',
                    amount: '',
                },
                isDisabledPay: true,
                isSearching: false,
            }
        },

        async created() {
            this.getSellerBilling();
            this.getPaymentTypeList();
            this.getListSeller();
        },

        computed: {
            ...mapState({
                listSellerBilling: state => state.storeBillingSeller.listSellerBilling,
                messageForms: state => state.storeBillingSeller.messageForms,
                listPayment: state => state.storeBillingSeller.listPayment,
                listSeller: state => state.storeBillingSeller.listSeller,
                sellerInfo: state => state.storeBillingSeller.sellerInfo,
            }),
        },

        methods: {
            async getSellerBilling(params){
                this.searchLoading = true;
                this.isSearching = true;
                await this.$store.dispatch('actionGetSellerBilling', params);
                this.searchLoading = false;
                this.isSearching = false;

                $('#tbl_seller_billing').DataTable({
                    paging: false,
                    searching: false,
                    columnDefs: [
                        { orderable: true, targets: 0 },
                        { orderable: true, targets: 2 },
                        { orderable: true, targets: 3 },
                        { orderable: true, targets: 4 },
                        { orderable: true, targets: 5 },
                        { orderable: true, targets: 6 },
                        { orderable: true, targets: 7 },
                        { orderable: false, targets: '_all' }
                    ],
                });

                this.getTotalAmount()
            },

            async doUpdatePayment() {
                await this.$store.dispatch('actionGetSellerInfo', { ids: this.checkIds });

                if(this.sellerInfo.success){
                    let data = this.sellerInfo.data[0]
                    let account = 'Not yet setup';
                    let total = 0;
                    $("#modal-payment").modal('show');

                    this.info.seller = data.username;
                    this.info.payment_type = data.payment_type == null ? 'Not yet setup':data.payment_type.type;
                    this.info.payment_type_id = data.payment_type == null ? null:data.payment_type.id;

                    if( data.payment_type != null && data.registration != null ){
                        this.isDisabledPay = false;

                        switch ( data.payment_type.id ) {
                        case 1:
                            account = data.registration.paypal_account == null ? 'Not yet setup':data.registration.paypal_account;
                            break;
                        case 2:
                            account = data.registration.skrill_account == null ? 'Not yet setup':data.registration.skrill_account;
                            break;
                        case 3:
                            account = data.registration.btc_account  == null ? 'Not yet setup':data.registration.btc_account;
                            break;
                        default:
                            account = "Not yet setup";
                        }
                    }

                    for( var index in this.checkIds ){
                        if( this.checkIds[index].publisher != null ){
                            if( this.checkIds[index].publisher.price != null ){
                                total += parseInt(this.checkIds[index].publisher.price);
                            }
                        }
                    }

                    if( account == 'Not yet setup' ){
                        this.isDisabledPay = true;
                    }

                    this.info.account = account;
                    this.info.amount = total

                }else{
                    swal.fire(
                        'Invalid',
                        'Multiple Payment in different seller is invalid.',
                        'error'
                    )
                }
                    
                this.clearMessageform();
            },

            doShow(src) {
                this.proof_doc = src;
            },

            async getListSeller(params) {
                await this.$store.dispatch('actionGetListSeller', params);
            },

            getTotalAmount() {
                let seller_billing = this.listSellerBilling.data
                let total_price = [];
                let total = 0;
                
                seller_billing.forEach(function(item, index){
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

            formatPrice(price) {
                price = parseFloat(price).toFixed(0);
                return price;
            },

            doSearch() {

                $('#tbl_seller_billing').DataTable().destroy();

                this.$router.push({
                    query: this.filterModel,
                });

                this.getSellerBilling({
                    params: {
                        search: this.filterModel.search,
                        status_billing: this.filterModel.status_billing,
                        seller: this.filterModel.seller,
                    }
                });
            },

            checkSelected() {
                this.isDisabled = true;
                if( this.checkIds.length > 0 ){
                    this.isDisabled = false;
                }
            },

            clearSearch() {
                $('#tbl_seller_billing').DataTable().destroy();

                this.filterModel = {
                    search: '',
                    status_billing: '',
                    seller: '',
                }

                this.getSellerBilling({
                    params: this.filterModel
                });

                this.$router.replace({'query': null});

            },

            async doPay() {

                $('#tbl_seller_billing').DataTable().destroy();

                let ids = this.checkIds
                this.formData = new FormData();
                this.formData.append('file', this.$refs.proof.files[0]);
                this.formData.append('payment_type', this.updateModel.payment_type);
                this.formData.append('ids', JSON.stringify(ids) );
                this.formData.append('payment_id', this.info.payment_type_id );

                this.isPopupLoading = true;
                await this.$store.dispatch('actionPay', this.formData)
                this.isPopupLoading = false;

                if( this.messageForms.action == 'success' ){
                    $("#modal-payment").modal('hide')
                    this.getSellerBilling();
                    this.$refs.proof.value = '';
                    this.updateModel.payment_type = '';

                    this.checkIds = [];

                    swal.fire(
                        'Success',
                        'Paid Successfully',
                        'success'
                    )

                }

                this.$root.$refs.AppHeader.liveGetWallet()
            },

            async getPaymentTypeList(params) {
                await this.$store.dispatch('actionGetListPayentType', params);
            },

            clearMessageform() {
                this.$store.dispatch('clearMessageform');
            },
        },
    }
</script>