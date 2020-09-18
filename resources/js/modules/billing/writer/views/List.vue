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
                                <label for="">Search ID Backlink</label>
                                <input type="text" class="form-control" name="" v-model="filterModel.search_backlink" aria-describedby="helpId" placeholder="Type here">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Writer</label>
                                <select name="" class="form-control" v-model="filterModel.writer">
                                    <option value="">All</option>
                                    <option v-for="option in listWriter.data" v-bind:value="option.id">
                                        {{ option.username == null ? option.name : option.username }}
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
                    <h3 class="box-title">Writer Billing</h3>

                    <div class="row">
                        <div class="col-md-2 my-3">

                            <div class="input-group">
                                <div class="dropdown">
                                    <button class="btn btn-default dropdown-toggle" :disabled="isDisabled" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Selected Action
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" @click="doUpdatePay" href="#">Pay</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="box-body no-padding relative">
                    <table id="tbl_writer_billing" class="table table-hover table-bordered table-striped rlink-table">
                        <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>Select</th>
                                <th>ID Backlink</th>
                                <th>Writer</th>
                                <th>Price for writer</th>
                                <th>Payment Status</th>
                                <th>Proof Documents</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(article, index) in listArticle.data" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-default">
                                            <input type="checkbox" :disabled="article.payment_status == 'Paid'" v-model="checkIds" v-on:change="checkSelected" :id="article.id" :value="article">
                                        </button>
                                    </div>
                                </td>
                                <td>{{ article.id_backlink }}</td>
                                <td>{{ article.user.username == null ? article.user.name : article.user.username }}</td>
                                <td>$ 15</td>
                                <td>{{ article.payment_status == null ? 'Not Paid':article.payment_status }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button title="View Proof of Billing" :disabled="article.proof_doc_path == null" @click="doShow(article.proof_doc_path)" data-target="#modal-view-docs" data-toggle="modal" class="btn btn-default"><i class="fa fa-fw fa-eye"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        
        <!-- Modal Payment -->
        <div class="modal fade" id="modal-payment" tabindex="-1" role="dialog" ref="modal_payment" aria-labelledby="modelTitleId" aria-hidden="true">
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
                                        <td style="border-top: 0px;">Writer: <b>{{ info.writer }}</b></td>
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

                            <!-- <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.payment_type}">
                                    <label for="">Payment Type</label>
                                    <select name="" class="form-control" v-model="updateModel.payment_type">
                                        <option v-for="option in listPayment.data" v-bind:value="option.id">
                                            {{ option.type }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors.payment_type" v-for="err in messageForms.errors.payment_type" class="text-danger">{{ err }}</span>
                                </div>
                            </div> -->
                                
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
        
        <!-- Modal Proof Doc -->
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
        <!-- End of Modal Proof Doc -->


    </div>
</template>

<script>
    import { mapState } from 'vuex';
    export default {
        data() {
            return {
                isDisabled: true,
                checkIds: [],
                isPopupLoading: false,
                updateModel: {
                    payment_type: '',
                },
                filterModel: {
                    search_backlink: this.$route.query.search_backlink || '',
                    writer: this.$route.query.writer || '',
                },
                searchLoading: false,
                info: {
                    writer: '',
                    payment_type: '',
                    payment_type_id: '',
                    account: '',
                    amount: '',
                },
                isDisabledPay: true,
                proof_doc: '',
            }
        },

        async created() {
            this.getListArticles();
            this.getPaymentTypeList();
            this.getListWriter();
        },

        computed: {
            ...mapState({
                listArticle: state => state.storeBillingWriter.listArticle,
                messageForms: state => state.storeBillingWriter.messageForms,
                listPayment: state => state.storeBillingWriter.listPayment,
                listWriter: state => state.storeArticles.listWriter,
                writerInfo: state => state.storeBillingWriter.writerInfo,
            }),
        },

        methods: {

            async doUpdatePay() {
                await this.$store.dispatch('actionGetWriterInfo', { ids: this.checkIds });

                if(this.writerInfo.success){
                    let data = this.writerInfo.data[0]
                    let modal = this.$refs.modal_payment;
                    let account = 'Not yet setup';

                    this.info.writer = data.username;
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

                    if( account == 'Not yet setup' ){
                        this.isDisabledPay = true;
                    }

                    this.info.account = account;
                    this.info.amount = 15;

                    $(modal).modal('show')
                }else{
                    swal.fire(
                        'Invalid',
                        'Multiple Payment in different Writer is invalid.',
                        'error'
                    )
                }
   
            },

            doShow(src) {
                this.proof_doc = src;
            },

            async getListArticles(params) {
               $("#tbl_writer_billing").DataTable().destroy();
               
                this.searchLoading = true;
                await this.$store.dispatch('actionGetListArticle', params);
                this.searchLoading = false;

                $("#tbl_writer_billing").DataTable({
                    paging: false,
                    searching: false,
                    columnDefs: [
                        { orderable: true, targets: 0 },
                        { orderable: true, targets: 2 },
                        { orderable: true, targets: 3 },
                        { orderable: true, targets: 4 },
                        { orderable: true, targets: 5 },
                        { orderable: false, targets: '_all' }
                    ],
                });
            },

            async getListWriter(params){
                await this.$store.dispatch('actionGetListWriter');
            },

            async doPay() {
                let ids = this.checkIds
                this.formData = new FormData();
                this.formData.append('file', this.$refs.proof.files[0]);
                this.formData.append('id_payment_type', this.info.payment_type_id);
                this.formData.append('price', this.info.amount);
                this.formData.append('ids', JSON.stringify(ids) );

                this.isPopupLoading = true;
                await this.$store.dispatch('actionPayWriter', this.formData)
                this.isPopupLoading = false;

                if( this.messageForms.action == 'success' ){
                    this.getListArticles();
                    this.$refs.proof.value = '';
                }

                this.$root.$refs.AppHeader.liveGetWallet()
            },

            doSearch() {
                this.$router.push({
                    query: this.filterModel,
                });

                this.getListArticles({
                    params: {
                        search_backlink: this.filterModel.search_backlink,
                        writer: this.filterModel.writer,
                    }
                });
            },

            clearSearch() {
                this.filterModel = {
                    search_backlink: '',
                    writer: '',
                }

                this.getListArticles({
                    params: this.filterModel
                });

                this.$router.replace({'query': null});
            },

            async getPaymentTypeList(params) {
                await this.$store.dispatch('actionGetListPayentType', params);
            },

            checkSelected() {
                this.isDisabled = true;
                if( this.checkIds.length > 0 ){
                    this.isDisabled = false;
                }
            },
        },
    }
</script>