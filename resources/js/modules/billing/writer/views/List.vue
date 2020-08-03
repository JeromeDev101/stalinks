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
                                        {{ option.name }}
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
                                        <a class="dropdown-item" @click="clearMessageform()" href="#" data-toggle="modal" data-target="#modal-payment">Pay</a>
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
                                <th>ID Backlink</th>
                                <th>Writer</th>
                                <th>Price for writer</th>
                                <th>Payment Status</th>
                                <th>Proof Documents</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- <tr v-if="listArticle.data.length == 0">
                                <td colspan="7" class="text-center">No record</td>
                            </tr> -->
                            <tr v-for="(article, index) in listArticle.data" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-default">
                                            <input type="checkbox" v-model="checkIds" v-on:change="checkSelected" :id="article.id" :value="article.id">
                                        </button>
                                    </div>
                                </td>
                                <td>{{ article.id_backlink }}</td>
                                <td>{{ article.user.name }}</td>
                                <td>{{ article.price == null ? '-':article.price.price == null ? '-': '$ ' + article.price.price }}</td>
                                <td></td>
                                <td>
                                    <div class="btn-group">
                                        <button title="View Proof of Billing" data-target="#modal-view-docs" data-toggle="modal" class="btn btn-default"><i class="fa fa-fw fa-eye"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        
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

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.file}">
                                    <label for="">Proof of Documents</label>
                                    <input type="file" class="form-control" enctype="multipart/form-data" ref="proof" name="file">
                                    <small class="text-muted">Note: It must be image type. ( jpg, jpeg, gif and png )</small><br/>
                                    <span v-if="messageForms.errors.file" v-for="err in messageForms.errors.file" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.payment_type}">
                                    <label for="">Payment Type</label>
                                    <select name="" class="form-control" v-model="updateModel.payment_type">
                                        <option v-for="option in listPayment.data" v-bind:value="option.id">
                                            {{ option.type }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors.payment_type" v-for="err in messageForms.errors.payment_type" class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                                
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="doPay" class="btn btn-primary">Pay</button>
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
            }),
        },

        methods: {
            async getListArticles(params) {
                this.searchLoading = true;
                await this.$store.dispatch('actionGetListArticle', params);
                this.searchLoading = false;
            },

            async getListWriter(params){
                await this.$store.dispatch('actionGetListWriter');
            },

            async doPay() {
                let ids = this.checkIds
                this.formData = new FormData();
                this.formData.append('file', this.$refs.proof.files[0]);
                this.formData.append('payment_type', this.updateModel.payment_type);
                this.formData.append('ids', ids );

                this.isPopupLoading = true;
                await this.$store.dispatch('actionPay', this.formData)
                this.isPopupLoading = false;

                if( this.messageForms.action == 'success' ){
                    this.getListArticles();
                    this.$refs.proof.value = '';
                    this.updateModel.payment_type = '';
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