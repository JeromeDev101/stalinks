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
                                <label for="">Search </label>
                                <input type="text" class="form-control" name="" aria-describedby="helpId" placeholder="Type here">
                            </div>
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <button class="btn btn-default">Clear</button>
                            <button class="btn btn-default">Search <i v-if="false" class="fa fa-refresh fa-spin" ></i></button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="box">

                <div class="box-header">
                    <h3 class="box-title">Seller Billing</h3>

                    <div class="row">
                        <div class="col-md-2 my-3">

                            <div class="input-group">
                                <div class="dropdown">
                                    <button class="btn btn-default dropdown-toggle" :disabled="isDisabled" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Selected Action
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modal-payment">Pay</a>
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
                                <th>User Seller</th>
                                <th>Seller Price</th>
                                <th>Date Completed</th>
                                <th>Status Billing</th>
                                <th>Status Payment</th>
                                <th>Proof Documents</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="listSellerBilling.data.length == 0">
                                <td colspan="9" class="text-center">No record</td>
                            </tr>
                            <tr v-for="(seller, index) in listSellerBilling.data" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-default">
                                            <input type="checkbox" v-on:change="checkSelected" :id="seller.id" :value="seller.id" v-model="checkIds">
                                        </button>
                                    </div>
                                </td>
                                <td>{{ seller.id }}</td>
                                <td>{{ seller.publisher.user.name }}</td>
                                <td>{{ seller.publisher.price }}</td>
                                <td>{{ seller.live_date }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
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
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Proof of Documents</label>
                                    <input type="file" class="form-control" enctype="multipart/form-data" ref="proof" name="file">
                                    <small class="text-muted">Note: It must be image type. ( jpg, jpeg, gif and png )</small>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Payment Type</label>
                                    <select name="" class="form-control" v-model="updateModel.payment_type">
                                        <option v-for="option in listPayment.data" v-bind:value="option.id">
                                            {{ option.type }}
                                        </option>
                                    </select>
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
            }
        },

        async created() {
            this.getSellerBilling();
            this.getPaymentTypeList();
        },

        computed: {
            ...mapState({
                listSellerBilling: state => state.storeBillingSeller.listSellerBilling,
                messageForms: state => state.storeBillingSeller.messageForms,
                listPayment: state => state.storeBillingSeller.listPayment,
            }),
        },

        methods: {
            async getSellerBilling(params){
                await this.$store.dispatch('actionGetSellerBilling', params);
            },

            checkSelected() {
                this.isDisabled = true;
                if( this.checkIds.length > 0 ){
                    this.isDisabled = false;
                }
            },

            async doPay() {
                this.formData = new FormData();
                this.formData.append('file', this.$refs.proof.files[0]);
                this.formData.append('payment_type', this.updateModel.payment_type);
                this.formData.append('ids[]', this.checkIds);


                await this.$store.dispatch('actionPay', this.formData)
            },

            async getPaymentTypeList(params) {
                await this.$store.dispatch('actionGetListPayentType', params);
            },
        },
    }
</script>