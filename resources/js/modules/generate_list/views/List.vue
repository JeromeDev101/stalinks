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
                                <label for="">Search URL</label>
                                <input type="text" class="form-control" name="" v-model="filterModel.url" aria-describedby="helpId" placeholder="Type here">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">UR</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-secondary" type="button" @click="buttonState.ur = buttonState.ur === 'Above' ? 'Below' : 'Above'">{{ buttonState.ur }}</button>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Type here" aria-label="" aria-describedby="basic-addon1" v-model="filterModel.ur">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">DR</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-secondary" type="button" @click="buttonState.dr = buttonState.dr === 'Above' ? 'Below' : 'Above'">{{ buttonState.dr }}</button>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Type here" aria-label="" aria-describedby="basic-addon1" v-model="filterModel.dr">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Org Kw</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-secondary" type="button" @click="buttonState.org_kw = buttonState.org_kw === 'Above' ? 'Below' : 'Above'">{{ buttonState.org_kw }}</button>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Type here" aria-label="" aria-describedby="basic-addon1" v-model="filterModel.org_kw">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Org Traffic</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-secondary" type="button" @click="buttonState.org_traffic = buttonState.org_traffic === 'Above' ? 'Below' : 'Above'">{{ buttonState.org_traffic }}</button>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Type here" aria-label="" aria-describedby="basic-addon1" v-model="filterModel.org_traffic">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2" >
                            <div class="form-group">
                                <label for="">Code</label>

                                <v-select
                                    v-model="filterModel.code"
                                    multiple
                                    placeholder="All"
                                    :options="listCode"
                                    :searchable="false"
                                />
                            </div>
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <button class="btn btn-default" @click="clearSearch()">Clear</button>
                            <button class="btn btn-default" @click="getGenerateList()">Search <i v-if="false" class="fa fa-refresh fa-spin" ></i></button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Generate List</h3>

                    <table width="100%" class="mt-3">
                        <tr>
                            <td>
                                <div class="input-group">
                                    <input type="file" class="form-control" @change="checkData()" enctype="multipart/form-data" ref="excel" name="file">
                                    <div class="input-group-btn">
                                        <button
                                            title="Upload CSV File"
                                            class="btn btn-primary btn-flat"
                                            :disabled="btnUpload"
                                            @click="submitUpload">
                                            <i class="fa fa-upload"></i>
                                        </button>

                                        <export-excel
                                            class   = "btn btn-primary btn-flat"
                                            :data   = generateList.data
                                            :fields = "json_fields"
                                            worksheet = "My Worksheet"
                                            name    = "generate_list.xls">
                                        
                                            <i class="fa fa-download"></i>
                                        
                                        </export-excel>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm float-right" style="width: 100px">
                                    <select name="" class="form-control float-right" @change="getGenerateList" v-model="filterModel.paginate" style="height: 37px;">
                                        <option v-for="option in paginate" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group mt-3">
                                    <button class="btn btn-default mr-2"
                                        @click="selectAll">{{
                                                        allSelected
                                                        ?
                                                        "Deselect"
                                                        : "Select"
                                                        }} All
                                    </button>

                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Selected Action
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" @click="deleteData();">Delete</a>
                                            <a class="dropdown-item" @click="getAhref()">Get Ahref</a>
                                            <a class="dropdown-item" @click="computePrice()">Compute Price</a>
                                        </div>
                                    </div>

                                </div>
                            </td>
                        </tr>
                    </table>

                </div>

                <div class="box-body no-padding">
                    <span class="pagination-custom-footer-text">
                        <b>Showing {{ generateList.from }} to {{ generateList.to }} of {{ generateList.total }} entries.</b>
                    </span>

                    <table id="tbl_generate_list" class="table table-hover table-bordered table-striped rlink-table" >
                        <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th></th>
                                <th>URL</th>
                                <th>UR</th>
                                <th>DR</th>
                                <th>Value 1</th>
                                <th>Code 1</th>
                                <th>Backlinks</th>
                                <th>Ref Domains</th>
                                <th>Value 2</th>
                                <th>Code 2</th>
                                <th>Org Kw</th>
                                <th>Code 3</th>
                                <th>Org Traffic</th>
                                <th>Code 4</th>
                                <th>Code Comb</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(data, index) in generateList.data" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>
                                    <input type="checkbox" class="custom-checkbox" :value="data.id" :id="data.id" v-model="checkIds" >
                                </td>
                                <td>{{ data.url }}</td>
                                <td>{{ data.ur }}</td>
                                <td>{{ data.dr }}</td>
                                <td>{{ data.dr - data.ur }}</td>
                                <td :class="'color-coding-'+ (data.code_1).toLowerCase()">{{ data.code_1 }}</td>
                                <td>{{ data.backlinks }}</td>
                                <td>{{ data.ref_domain }}</td>
                                <td>{{ (data.backlinks == 0 || data.ref_domain == 0) ? 0:(data.backlinks/data.ref_domain).toFixed(2)}}</td>
                                <td :class="'color-coding-'+ (data.code_2).toLowerCase()">{{ data.code_2 }}</td>
                                <td>{{ data.org_kw }}</td>
                                <td :class="'color-coding-'+ (data.code_3).toLowerCase()">{{ data.code_3 }}</td>
                                <td>{{ data.org_traffic }}</td>
                                <td :class="'color-coding-'+ (data.code_4).toLowerCase()">{{ data.code_4 }}</td>
                                <td>{{ data.code_comb }}</td>
                                <td>{{ '$' + data.price }}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>

            </div>

        </div>

    </div>
</template>

<style scoped>
    .color-coding-a{ background: #f2a035; color: #fff; }
    .color-coding-b{ background: #ffda6b; color: #000; }
    .color-coding-c{ background: #2dc22b; color: #fff; }
    .color-coding-d{ background: #4878e0; color: #fff; }
    .color-coding-e{ background: #ffcccc; color: #b30000; }

</style>

<script>
    import { mapState } from 'vuex';
    import axios from 'axios';

    export default {
        data() {
            return {
                filterModel: {
                    paginate: 50,
                    url: '',
                    ur: '',
                    dr: '',
                    org_kw: '',
                    org_traffic: '',
                    code: '',
                },
                paginate: [50,150,250,350,'All'],
                buttonState: {
                    ur : 'Above',
                    dr : 'Above',
                    org_kw : 'Above',
                    org_traffic : 'Above'
                },
                checkIds: [],
                generateList: [],
                btnUpload: true,
                listCode: ['4A', '3A', '2A', '1A', '0A'],
                allSelected: false,
            }
        },

        async created() {
            this.getGenerateList();
        },

        computed: {
            ...mapState({
                user: state => state.storeAuth.currentUser,

            }),
        },

        methods: {
            getGenerateList() {
                axios.get('/api/generate-list',{
                    params: {
                        url: this.filterModel.url,
                        ur: this.filterModel.ur,
                        dr: this.filterModel.dr,
                        org_kw: this.filterModel.org_kw,
                        org_traffic: this.filterModel.org_traffic,
                        code: this.filterModel.code,
                        ur_direction: this.buttonState.ur,
                        dr_direction: this.buttonState.dr,
                        org_kw_direction: this.buttonState.org_kw,
                        org_traffic_direction: this.buttonState.org_traffic,
                    }
                }).then((res) => {
                    this.generateList = res.data
                })
            },

            submitUpload() {
                this.formData = new FormData();
                this.formData.append('file', this.$refs.excel.files[0]);

                axios.post('/api/generate-list-upload-csv', this.formData)
                    .then((res) => {

                        if(res.data.success === true) {
                            swal.fire(
                                'Uploaded!',
                                'Successfully Uploaded.',
                                'success'
                            )

                            this.$refs.excel.value = '';
                            this.btnUpload = true;
                            this.getGenerateList()

                        } else {

                        }
                        console.log(res)
                    });

            },

            deleteData() {

                axios.post('/api/generate-list-delete',{
                    ids: this.checkIds
                }).then((res) => {
                    swal.fire(
                        'Deleted',
                        'Successfully Deleted.',
                        'success'

                    )

                    this.allSelected = true;
                    this.checkIds = [];
                    this.getGenerateList();
                })
            },

            checkData() {
                this.btnUpload = true;
                if (this.$refs.excel.value){
                    this.btnUpload = false;
                }
            },

            clearSearch() {
                this.filterModel = {
                    paginate: 50,
                    url: '',
                    ur: '',
                    dr: '',
                    org_kw: '',
                    org_traffic: '',
                    code: '',
                }

                this.$router.replace({'query': null});
                this.getGenerateList();
            },

            selectAll: function() {
                this.checkIds = [];
                if (!this.allSelected) {
                    for (var index in this.generateList.data) {
                        this.checkIds.push(this.generateList.data[index].id);
                    }
                    this.allSelected = true;
                } else {
                    this.allSelected = false;
                }
            },

            getAhref() {

                swal.fire({
                    title: "Getting Ahrefs...",
                    text: "Please wait",
                    timerProgressBar: true,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        swal.showLoading()
                    },
                });

                axios.post('/api/generate-list-ahref', {
                    ids: this.checkIds
                }).then((res) => {

                    swal.fire(
                        'Success',
                        'Successfully Updated Ahref.',
                        'success'

                    )

                    this.allSelected = true;
                    this.checkIds = [];
                    this.getGenerateList();
                })
            },

            computePrice() {

                swal.fire({
                    title: "Computing Price...",
                    text: "Please wait",
                    timerProgressBar: true,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        swal.showLoading()
                    },
                });

                axios.post('/api/generate-list-compute-price', {
                    ids: this.checkIds
                }).then((res) => {

                    swal.fire(
                        'Success',
                        'Successfully Computed.',
                        'success'

                    )

                    this.allSelected = true;
                    this.checkIds = [];
                    this.getGenerateList();
                })
            },
        }
    }
</script>
