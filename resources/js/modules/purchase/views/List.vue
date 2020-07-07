<template>
    <div class="row">
        <div class="col-md-12">

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Filter</h3>
                </div>

                <div class="box-body m-3">

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Search Company and User</label>
                                <input type="text" class="form-control" name="" aria-describedby="helpId" placeholder="Type here">
                            </div>
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <button class="btn btn-default">Clear</button>
                            <button class="btn btn-default" >Search <i v-if="false" class="fa fa-refresh fa-spin" ></i></button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Purchase</h3>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-bordered table-striped rlink-table">
                        <tbody>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>ID</th>
                                <th>User Seller</th>
                                <th>User Buyer</th>
                                <th>URL Publisher</th>
                                <th>Price</th>
                                <th>Date Completed</th>
                                <th>Status</th>
                                <th>Status Payment</th>
                                <!-- <th>Action</th> -->
                            </tr>
                            <tr v-for="(purchase, index) in listPurchase.data" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ purchase.id }}</td>
                                <td>{{ purchase.publisher.user.name }}</td>
                                <td>{{ purchase.user.name }}</td>
                                <td>{{ purchase.publisher.url }}</td>
                                <td>$ {{ purchase.price }}</td>
                                <td>{{ purchase.live_date }}</td>
                                <td>{{ purchase.status }}</td>
                                <td>{{ purchase.payment_status }}</td>
                                <!-- <td>
                                    <div class="btn-group">
                                        <button data-toggle="modal" @click="doUpdate(purchase)" data-target="#modal-update-purchase" title="Edit" class="btn btn-default"><i class="fa fa-fw fa-edit"></i></button>
                                    </div>
                                </td> -->
                            </tr>
                        </tbody>
                    </table>
                </div>
                
            </div>

        </div>
        
        <!-- Modal Update -->
        <div class="modal fade" id="modal-update-purchase" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Purchase Information</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label style="color: #333">User Seller</label>
                                    <input type="text" v-model="updateModel.seller" :disabled="true" class="form-control" required="required" >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label style="color: #333">User Buyer</label>
                                    <input type="text" v-model="updateModel.buyer" :disabled="true" class="form-control" required="required" >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label style="color: #333">Price</label>
                                    <input type="text" v-model="updateModel.price" :disabled="true" class="form-control" required="required" >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label style="color: #333">Payment Status</label>
                                    <select name="" id="" v-model="updateModel.payment_status" class="form-control">
                                        <option value="Paid">Paid</option>
                                        <option value="Not Paid">Not Paid</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Update -->

    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        data() {
            return {
                updateModel: {
                    seller: '',
                    buyer: '',
                    price: '',
                    payment_status: '',
                },
                isPopupLoading: false,
            }
        },

        async created() {
            this.getPurchaseList()
        },

        computed: {
             ...mapState({
                listPurchase: state => state.storePurchase.listPurchase,
                messageForms: state => state.storePurchase.messageForms,
            })
        },

        methods: {
            async getPurchaseList(params){
                await this.$store.dispatch('actionGetPurchaseList', params)
            },

            doUpdate(purchase) {
                let that = JSON.parse( JSON.stringify(purchase) )

                this.updateModel = that
                this.updateModel.seller = that.publisher.user.name
                this.updateModel.buyer = that.user.name
            },
        }
    }
</script>
