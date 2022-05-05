<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"></h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">{{ $t('message.writer_billing.filter_title') }}</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.writer_billing.filter_search_id_backlink') }}</label>
                                    <input type="text"
                                           class="form-control"
                                           name=""
                                           v-model="filterModel.search_backlink"
                                           aria-describedby="helpId"
                                           :placeholder="$t('message.writer_billing.type')">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.writer_billing.filter_writer') }}</label>
                                    <select name="" class="form-control" v-model="filterModel.writer">
                                        <option value="">{{ $t('message.writer_billing.all') }}</option>
                                        <option v-for="option in listWriter.data" v-bind:value="option.id">
                                            {{ option.username == null ? option.name : option.username }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.writer_billing.filter_date_completed') }}</label>
                                    <div class="input-group">
                                        <date-range-picker
                                            ref="picker"
                                            v-model="filterModel.date_completed"
                                            :ranges="generateDefaultDateRange()"
                                            :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                            :dateRange="filterModel.date_completed"
                                            :linkedCalendars="true"
                                            opens="left"
                                            style="width: 100%"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.writer_billing.filter_date_created') }}</label>
                                    <div class="input-group">
                                        <date-range-picker
                                            ref="picker"
                                            v-model="filterModel.date_created"
                                            :ranges="generateDefaultDateRange()"
                                            :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                            :dateRange="filterModel.date_created"
                                            :linkedCalendars="true"
                                            opens="left"
                                            style="width: 100%"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-2">
                                <button class="btn btn-default" @click="clearSearch" :disabled="isSearching">
                                    {{ $t('message.writer_billing.clear') }}
                                </button>
                                <button class="btn btn-default" @click="doSearch" :disabled="isSearching">
                                    {{ $t('message.writer_billing.search') }}
                                    <i v-if="searchLoading" class="fa fa-refresh fa-spin"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">{{ $t('message.writer_billing.wb_title') }}</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="input-group">
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle"
                                        :disabled="isDisabled"
                                        type="button"
                                        id="dropdownMenuButton"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false">
                                    {{ $t('message.writer_billing.wb_selected_action') }}
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" @click="doUpdatePay" href="#">
                                        {{ $t('message.writer_billing.wb_pay') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="tbl_writer_billing"
                                   class="table table-hover table-bordered table-striped rlink-table">
                                <thead>
                                <tr class="label-primary">
                                    <th>#</th>
                                    <th>{{ $t('message.writer_billing.wb_select') }}</th>
                                    <th>{{ $t('message.writer_billing.filter_date_completed') }}</th>
                                    <th>{{ $t('message.writer_billing.filter_date_created') }}</th>
                                    <th>{{ $t('message.writer_billing.wb_id_backlink') }}</th>
                                    <th>{{ $t('message.writer_billing.wb_id_article') }}</th>
                                    <th>{{ $t('message.writer_billing.filter_writer') }}</th>
                                    <th>{{ $t('message.writer_billing.wb_price_writer') }}</th>
                                    <th>{{ $t('message.writer_billing.wb_payment_status') }}</th>
                                    <th>{{ $t('message.writer_billing.wb_proof_doc') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(article, index) in listArticle.data" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-default">
                                                <input type="checkbox"
                                                       :disabled="article.payment_status == 'Paid'"
                                                       v-model="checkIds"
                                                       v-on:change="checkSelected"
                                                       :id="article.id"
                                                       :value="article">
                                            </button>
                                        </div>
                                    </td>
                                    <td>{{ article.date_complete }}</td>
                                    <td>{{ article.created_at }}</td>
                                    <td>{{ article.id_backlink }}</td>
                                    <td>{{ article.id }}</td>
                                    <td>{{ article.user.username == null ? article.user.name : article.user.username }}</td>
                                    <td>{{ '$ ' + computeWriterPrice(article) }}</td>
                                    <td>{{ article.payment_status == null ? $t('message.writer_billing.wb_not_paid') : article.payment_status }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button
                                                v-if="article.proof_doc_path == null || !isFilePdf(article.proof_doc_path)"
                                                :title="$t('message.writer_billing.wb_view_proof_billing')"
                                                :disabled="article.proof_doc_path == null"
                                                data-target="#modal-view-docs"
                                                data-toggle="modal"
                                                class="btn btn-default"

                                                @click="doShow(article.proof_doc_path)">
                                                <i class="fa fa-fw fa-eye"></i>
                                            </button>
                                            <button
                                                v-else
                                                :title="$t('message.writer_billing.wb_download_proof')"
                                                class="btn btn-default"

                                                @click="downloadProof(article.billing_writer_id)">
                                                <i class="fa fa-fw fa-download"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Payment -->
        <div class="modal fade"
             id="modal-payment"
             tabindex="-1"
             role="dialog"
             ref="modal_payment"
             aria-labelledby="modelTitleId"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.writer_billing.p_title') }}</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading"
                              :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12 mb-4">
                                <table class="table">
                                    <tr>
                                        <td style="border-top: 0px;">{{ $t('message.writer_billing.filter_writer') }}: <b>{{ info.writer }}</b></td>
                                        <td style="border-top: 0px;">{{ $t('message.writer_billing.p_amount_to_pay') }} <b>$ {{ info.amount }}</b></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            {{ $t('message.writer_billing.p_payment_type') }}
                                            <b :class="{ 'text-danger' : info.payment_type == 'Not yet setup' }">
                                                {{ info.payment_type }}
                                            </b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            {{ $t('message.writer_billing.p_account') }}
                                            <b :class="{ 'text-danger' : info.account == 'Not yet setup' }">
                                                {{ info.account }}
                                            </b>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div
                                class="col-md-12"
                                v-if="info.payment_type_id != 1">

                                <div :class="{'form-group': true, 'has-error': messageForms.errors.file}">
                                    <label>{{ $t('message.writer_billing.p_proof_documents') }}</label>
                                    <input
                                        type="file"
                                       class="form-control"
                                       enctype="multipart/form-data"
                                       ref="proof"
                                       name="file">

                                    <small class="text-muted">
                                        {{ $t('message.writer_billing.p_proof_note') }}
                                    </small>

                                    <br/>

                                    <span v-if="messageForms.errors.file"
                                          v-for="err in messageForms.errors.file"
                                          class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <!-- <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.payment_type}">
                                    <label>Payment Type</label>
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
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.writer_billing.close') }}
                        </button>
                        <button type="button" @click="doPay" class="btn btn-primary" :disabled="isDisabledPay">
                            {{ $t('message.writer_billing.pay') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Payment -->

        <!-- Modal Proof Doc -->
        <div class="modal fade"
             id="modal-view-docs"
             tabindex="-1"
             role="dialog"
             aria-labelledby="modelTitleId"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.writer_billing.pd_title') }}</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <img class="img-fluid" :src="proof_doc" :atl="$t('message.writer_billing.pd_proof_billing')">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.writer_billing.close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Proof Doc -->
    </div>
</template>

<script>
import {mapState} from 'vuex';
import _ from "lodash";
import {dateRange} from "../../../../mixins/dateRange";

export default {
    mixins: [dateRange],
    data() {
        return {
            isDisabled : true,
            checkIds : [],
            isPopupLoading : false,
            updateModel : {
                payment_type : '',
            },
            filterModel : {
                search_backlink : this.$route.query.search_backlink || '',
                writer : this.$route.query.writer || '',
                date_completed : {
                    startDate : null,
                    endDate : null
                },
                date_created : {
                    startDate : null,
                    endDate : null
                }
            },
            searchLoading : false,
            info : {
                writer : '',
                payment_type : '',
                payment_type_id : '',
                account : '',
                amount : '',
                payment_types: null
            },
            isDisabledPay : true,
            proof_doc : '',
            isSearching : false,
        }
    },

    async created() {
        this.getListArticles();
        this.getPaymentTypeList();
        this.getListWriter();
    },

    computed : {
        ...mapState({
            listArticle : state => state.storeBillingWriter.listArticle,
            messageForms : state => state.storeBillingWriter.messageForms,
            listPayment : state => state.storeBillingWriter.listPayment,
            listWriter : state => state.storeArticles.listWriter,
            writerInfo : state => state.storeBillingWriter.writerInfo,
        }),
    },

    methods : {

        computeWriterPrice(article) {
            var rate_type = (article.rate_type == null || article.rate_type == '') ? 'ppw' : (article.rate_type).toLowerCase();
            var content = article.contentnohtml
            var writer_price = parseFloat(article.writer_price);
            var price = 0;

            if (rate_type == 'ppw') {
                price = writer_price * this.countWords(content)
            } else {
                price = writer_price
            }

            return price;
        },

        countWords(str) {
            str = str.replace(/(^\s*)|(\s*$)/gi, "");
            str = str.replace(/[ ]{2,}/gi, " ");
            str = str.replace(/\n /, "\n");
            return str.split(' ').length;
        },

        async doUpdatePay() {
            let self = this;
            await this.$store.dispatch('actionGetWriterInfo', {ids : this.checkIds});

            if (this.writerInfo.success) {
                let data = this.writerInfo.data[0]
                let modal = this.$refs.modal_payment;
                let account = 'Not yet setup';
                let total_amount = [];

                this.info.writer = data.username;
                this.info.payment_types = data.user_payment_types;
                this.info.payment_type = data.payment_type == null ? 'Not yet setup' : data.payment_type.type;
                this.info.payment_type_id = data.payment_type == null ? null : data.payment_type.id;

                if (data.payment_type != null && data.registration != null) {
                    this.isDisabledPay = false;

                    switch (data.payment_type.id) {
                        case 1:
                            account = data.registration.paypal_account == null ? 'Not yet setup' : data.registration.paypal_account;
                            break;
                        case 2:
                            account = data.registration.skrill_account == null ? 'Not yet setup' : data.registration.skrill_account;
                            break;
                        case 3:
                            account = data.registration.btc_account == null ? 'Not yet setup' : data.registration.btc_account;
                            break;
                        default:
                            account = "Not yet setup";
                    }
                }

                if (this.info.payment_types) {
                    this.isDisabledPay = false;

                    let paymentType = _.find(this.info.payment_types, {'payment_id' : data.id_payment_type} );

                    if (paymentType) {
                        account = paymentType.account;
                    }
                }

                if (account == 'Not yet setup') {
                    this.isDisabledPay = true;
                }

                this.info.account = account;

                for (var index in this.checkIds) {
                    var price = this.computeWriterPrice(this.checkIds[index])
                    total_amount.push(price)
                }

                this.info.amount = total_amount.reduce((a, b) => a + b, 0);

                $(modal).modal('show')
            } else {
                swal.fire(
                    self.$t('message.writer_billing.alert_invalid'),
                    self.$t('message.writer_billing.alert_multiple_different_writer'),
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
            this.isSearching = true;
            await this.$store.dispatch('actionGetListArticle', params);
            this.searchLoading = false;
            this.isSearching = false;

            $("#tbl_writer_billing").DataTable({
                paging : false,
                searching : false,
                columnDefs : [
                    {orderable : true, targets : 0},
                    {orderable : true, targets : 2},
                    {orderable : true, targets : 3},
                    {orderable : true, targets : 4},
                    {orderable : true, targets : 5},
                    {orderable : false, targets : '_all'}
                ],
            });
        },

        async getListWriter(params) {
            await this.$store.dispatch('actionGetListWriter');
        },

        async doPay() {
            let ids = this.checkIds
            this.formData = new FormData();
            this.formData.append('id_payment_type', this.info.payment_type_id);
            this.formData.append('price', this.info.amount);
            this.formData.append('ids', JSON.stringify(ids));

            if (this.info.payment_type_id != 1) {
                this.formData.append('file', this.$refs.proof.files[0]);
            }

            this.isPopupLoading = true;
            await this.$store.dispatch('actionPayWriter', this.formData)
            this.isPopupLoading = false;

            if (this.messageForms.action == 'success') {
                this.getListArticles();
                this.$refs.proof.value = '';
            }

            this.$root.$refs.AppHeader.liveGetWallet()
        },

        doSearch() {
            // change the format of date
            this.filterModel.date_completed = this.formatFilterDates(this.filterModel.date_completed)
            this.filterModel.date_created = this.formatFilterDates(this.filterModel.date_created)

            this.$router.push({
                query : this.filterModel,
            });

            this.getListArticles({
                params : {
                    search_backlink : this.filterModel.search_backlink,
                    writer : this.filterModel.writer,
                    date_completed : this.filterModel.date_completed,
                    date_created : this.filterModel.date_created,
                }
            });
        },

        clearSearch() {
            this.filterModel = {
                search_backlink : '',
                writer : '',
                date_completed : {
                    startDate : null,
                    endDate : null
                },
                date_created : {
                    startDate : null,
                    endDate : null
                }
            }

            this.getListArticles({
                params : this.filterModel
            });

            this.$router.replace({'query' : null});
        },

        async getPaymentTypeList(params) {
            await this.$store.dispatch('actionGetListPayentType', params);
        },

        checkSelected() {
            this.isDisabled = true;
            if (this.checkIds.length > 0) {
                this.isDisabled = false;
            }
        },

        isFilePdf(path) {
            var arr = path.split('.');

            return arr[1] === 'pdf';
        },

        downloadProof(id) {
            axios({
                url : '/api/files/proof/paypal/writer/' +
                    id,
                method : 'GET',
                responseType : 'blob',
            }).then((response) => {
                var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                var fileLink = document.createElement('a');

                fileLink.href = fileURL;
                fileLink.setAttribute('download',
                    'STAL-WRITER-' + id + '.pdf');
                document.body.appendChild(fileLink);

                fileLink.click();
            });
        }
    },
}
</script>
