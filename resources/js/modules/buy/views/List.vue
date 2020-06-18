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
                                <label for="">Search Company and User</label>
                                <input type="text" class="form-control" name="" aria-describedby="helpId" placeholder="Type here">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Language</label>
                                <select name="" class="form-control">
                                    <option value="">All</option>
                                    <option v-for="option in listCountries.data" v-bind:value="option.id">
                                        {{ option.name }}
                                    </option>
                                </select>
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
                    <h3 class="box-title">Buy Backlinks</h3>
                </div>

                <div class="box-body table-responsive no-padding relative">
                    <table class="table table-hover table-bordered table-striped rlink-table">
                        <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>Company</th>
                                <th>User</th>
                                <th>Language</th>
                                <th>URL</th>
                                <th>UR</th>
                                <th>DR</th>
                                <th>Backlinks</th>
                                <th>Ref Domains</th>
                                <th>Organic Keywords</th>
                                <th>Organic Traffic</th>
                                <th>Price</th>
                                <th>Buy</th>
                            </tr>
                        </thead>
                        <tbody>
                           <tr v-if="listBuy.data.length == 0">
                                <td colspan="12" class="text-center">No record</td>
                            </tr>
                            <tr v-for="(buy, index) in listBuy.data" :key="index">
                                <td>{{ index + 1}}</td>
                                <td>{{ buy.isOurs == '0' ? 'Stalinks':buy.company_name}}</td>
                                <td>{{ buy.name }}</td>
                                <td>{{ buy.language }}</td>
                                <td>{{ buy.url }}</td>
                                <td>{{ buy.ur }}</td>
                                <td>{{ buy.dr }}</td>
                                <td>{{ buy.backlinks }}</td>
                                <td>{{ buy.ref_domain }}</td>
                                <td>{{ buy.org_keywords }}</td>
                                <td>{{ buy.org_traffic }}</td>
                                <td>{{ buy.price == '' || buy.price == null ? '':'$'}} {{ buy.price }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button title="Edit" data-target="#modal-buy-update" @click="doUpdate(buy)" data-toggle="modal" class="btn btn-default"><i class="fa fa-fw fa-edit"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        
        <!-- Modal Update -->
        <div class="modal fade" id="modal-buy-update" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Information</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">URL</label>
                                    <input type="text" class="form-control" v-model="updateModel.url" name="" aria-describedby="helpId" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Language</label>
                                    <select name="" v-model="updateModel.language_id" class="form-control">
                                        <option value=""></option>
                                        <option v-for="option in listCountries.data" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Price</label>
                                    <input type="number" class="form-control" v-model="updateModel.price" name="" aria-describedby="helpId" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Anchor Text</label>
                                    <input type="text" class="form-control" v-model="updateModel.anchor_text" name="" aria-describedby="helpId" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Link</label>
                                    <input type="text" class="form-control" v-model="updateModel.link" name="" aria-describedby="helpId" placeholder="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitBuy" class="btn btn-primary">Buy</button>
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
                updateModel: {
                    id: '',
                    url: '',
                    price: '',
                    anchor_text: '',
                    link: '',
                    language_id: '',
                },
                isPopupLoading: false,
            }
        },

        computed: {
            ...mapState({
                listBuy: state => state.storeBuy.listBuy,
                listCountries: state => state.storeBuy.listCountries,
                messageForms: state => state.storeBuy.messageForms,
            }),
        },

        async created() {
            this.getBuyList();
            this.getListCountries();
        },

        methods: {
            async getBuyList(params) {
                await this.$store.dispatch('actionGetBuyList', params);
            },

            doUpdate(buy) {
                this.clearMessageform();
                let that = JSON.parse(JSON.stringify(buy))

                this.updateModel = {
                    id: that.id,
                    url: that.url,
                    price: that.price,
                    anchor_text: that.anchor_text,
                    link: that.link,
                    language_id: that.language_id,
                }
            },

            async getListCountries(params) {
                await this.$store.dispatch('actionGetListCountries', params);
            },

            async submitBuy(params) {
                this.isPopupLoading = true;
                await this.$store.dispatch('actionUpdateBuy', this.updateModel);
                this.isPopupLoading = false;

                if (this.messageForms.action === 'updated') {
                    this.getBuyList();
                }
            },

            clearMessageform() {
                this.$store.dispatch('clearMessageform');
            },
        },
    }
</script>