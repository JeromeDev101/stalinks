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
                                <label for="">Search URL</label>
                                <input type="text" class="form-control" v-model="filterModel.search" name="" aria-describedby="helpId" placeholder="Type here">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Language</label>
                                <select name="" class="form-control" v-model="filterModel.language_id">
                                    <option value="">All</option>
                                    <option v-for="option in listCountries.data" v-bind:value="option.id">
                                        {{ option.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2" v-if="user.isAdmin || user.isOurs == 0">
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

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Include Article</label>
                                <select name="" class="form-control" v-model="filterModel.inc_article">
                                    <option value="">All</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Got Ahref</label>
                                <select name="" class="form-control" v-model="filterModel.got_ahref">
                                    <option value="">All</option>
                                    <option value="With">With</option>
                                    <option value="Without">Without</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Date</label>
                                <input type="date" class="form-control" v-model="filterModel.date">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Valid</label>
                                <v-select multiple v-model="filterModel.valid" :options="['valid','invalid','unchecked']" />
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
                    <h3 class="box-title">Publisher URL List</h3>

                    <div class="input-group input-group-sm float-right" style="width: 100px">
                        <select name="" class="form-control float-right" @change="getPublisherList" v-model="filterModel.paginate" style="height: 37px;">
                            <option v-for="option in paginate" v-bind:value="option">
                                {{ option }}
                            </option>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4 my-3">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input type="file" class="form-control" v-on:change="checkDataExcel" enctype="multipart/form-data" ref="excel" name="file">
                                        <div class="input-group-btn">
                                            <button title="Upload CSV File" @click="submitUpload" :disabled="isEnableBtn" class="btn btn-primary btn-flat"><i class="fa fa-upload"></i></button>
                                        </div>
                                    </div>
                                    <span v-if="messageForms.errors.file" v-for="err in messageForms.errors.file" class="text-danger">{{ err }}</span>
                                    <span v-if="messageForms.action == 'uploaded'" class="text-success">{{ messageForms.message }}</span>
                                </div>
                            </div>

                            <div class="row mt-3" v-show="showLang">
                                <div class="col-sm-12">
                                    <select class="form-control" name="language" ref="language" v-on:change="checkDataLanguage">
                                        <option value="">Select language</option>
                                        <option v-for="option in listCountries.data" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 my-3">
                            <div class="input-group">
                                <div class="dropdown">
                                    <button class="btn btn-default dropdown-toggle" :disabled="isDisabled" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Selected Action
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" @click="doMultipleDelete" href="#">Delete</a>
                                        <a class="dropdown-item " @click="getAhrefs()" v-if="user.isAdmin || user.isOurs == 0">Get Ahref</a>
                                        <a class="dropdown-item " @click="validData('valid')" v-if="user.isAdmin || user.isOurs == 0">Valid</a>
                                        <a class="dropdown-item " @click="validData('invalid')" v-if="user.isAdmin || user.isOurs == 0">Invalid</a>
                                        <a class="dropdown-item " @click="validData('unchecked')" v-if="user.isAdmin || user.isOurs == 0">Unchecked</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <small class="text-secondary">Reminder: The uploaded data is for Seller -List Publisher. The columns for the CSV file are URL, Price and Inc Article. The columns should be separated using comma. (,) If you only have URL and Price is fine too. Price are in USD. Inc Article value is Yes /No . Do not forget to select the language of the site.</small>
                        </div>
                    </div>

                </div>

                <div class="box-body no-padding">
                    <span v-if="listPublish.total > 10" class="pagination-custom-footer-text">
                        <b>Showing {{ listPublish.from }} to {{ listPublish.to }} of {{ listPublish.total }} entries.</b>
                    </span>

                    <table id="tbl-publisher" class="table table-hover table-bordered table-striped rlink-table">
                        <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>
                                    <input type="checkbox" @click="selectAll" v-model="allSelected">
                                </th>
                                <!-- <th v-if="user.isAdmin">Company</th> -->
                                <th>In-charge</th>
                                <th v-if="user.isAdmin || user.isOurs == 0">Seller</th>
                                <th v-if="user.isAdmin || user.isOurs == 0">Uploaded</th>
                                <th>Language</th>
                                <th>Valid</th>
                                <th>URL</th>
                                <th>Price</th>
                                <th>Inc Article</th>
                                <th>UR</th>
                                <th>DR</th>
                                <th>Backlinks</th>
                                <th>Ref Domains</th>
                                <th>Organic Keywords</th>
                                <th>Organic Traffic</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(publish, index) in listPublish.data" :key="index">
                                <td>{{ index + 1}}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-default">
                                            <!-- <input type="checkbox" :disabled="checkAhref(publish)" v-on:change="checkSelected" :id="publish.id" :value="publish.id" v-model="checkIds"> -->
                                            <input type="checkbox" v-on:change="checkSelected" :id="publish.id" :value="publish.id" v-model="checkIds">
                                        </button>
                                    </div>
                                </td>
                                <td>{{ publish.in_charge == null ? 'N/A':publish.in_charge }}</td>
                                <!-- <td v-if="user.isAdmin">{{ publish.isOurs == '0' ? 'Stalinks':publish.company_name}}</td> -->
                                <td v-if="user.isAdmin || user.isOurs == 0">{{ publish.username ? publish.username : publish.user_name   }}</td>
                                <td v-if="user.isAdmin || user.isOurs == 0">{{ displayDate(publish.updated_at) }}</td>
                                <td>{{ publish.country_name }}</td>
                                <td>{{ publish.valid }}</td>
                                <td>{{ replaceCharacters(publish.url) }}</td>
                                <td>{{ publish.price == '' || publish.price == null ? '':'$'}} {{ computePrice(publish.price, publish.inc_article) }}</td>
                                <td>{{ publish.inc_article }}</td>
                                <td>{{ publish.ur }}</td>
                                <td>{{ publish.dr }}</td>
                                <td>{{ publish.backlinks }}</td>
                                <td>{{ publish.ref_domain }}</td>
                                <td>{{ formatPrice(publish.org_keywords) }}</td>
                                <td>{{ formatPrice(publish.org_traffic) }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button data-toggle="modal" @click="doUpdate(publish)" data-target="#modal-update-publisher" title="Edit" class="btn btn-default"><i class="fa fa-fw fa-edit"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- <pagination :data="listPublish" @pagination-change-page="getPublisherList" :limit="8"></pagination> -->

                </div>
            </div>

        </div>

        <!-- Modal Update Publisher-->
        <div class="modal fade" id="modal-update-publisher" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
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
                                    <label for="">Company Name</label>
                                    <input type="text" v-model="updateModel.company_name" class="form-control" name="" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Username</label>
                                    <input type="text" v-model="updateModel.username" class="form-control" name="" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">URL</label>
                                    <input type="text" v-model="updateModel.url" class="form-control" name="" placeholder="" disabled>
                                </div>
                            </div>


                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Ref Domains</label>
                                    <input type="number" v-model="updateModel.ref_domain" :disabled="isSeller" class="form-control" name="" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">No Backlinks</label>
                                    <input type="text" v-model="updateModel.backlinks" :disabled="isSeller" class="form-control" name="" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">URL Rating</label>
                                    <input type="number" v-model="updateModel.ur" :disabled="isSeller" class="form-control" name="" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Domain Rating</label>
                                    <input type="number" v-model="updateModel.dr" :disabled="isSeller" class="form-control" name="" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Organic Keywords</label>
                                    <input type="text" v-model="updateModel.org_keywords" :disabled="isSeller" class="form-control" name="" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Organic traffic</label>
                                    <input type="text" v-model="updateModel.org_traffic" :disabled="isSeller" class="form-control" name="" placeholder="">
                                </div>
                            </div> -->


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Language</label>
                                    <select name="" class="form-control" v-model="updateModel.language_id">
                                        <option value="">Select Language</option>
                                        <option v-for="option in listCountries.data" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Price</label>
                                    <input type="number" v-model="updateModel.price" class="form-control" name="" placeholder="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Include Article</label>
                                    <select name="" id="" class="form-control" v-model="updateModel.inc_article">
                                        <option value=""></option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitUpdate" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<style>
    .pagination-custom-footer-text {
        margin: 20px;
        margin-top: -40px;
    }
    #vs1__combobox {
        height: 38px;
    }
</style>

<script>

    import { mapState } from 'vuex';

    export default {
        name: '',
        data(){
            return {
                paginate: [25,50,100,200,250, 'All'],
                updateModel: {
                    id: '',
                    company_name: '',
                    name: '',
                    username:'',
                    url: '',
                    ur: '',
                    dr: '',
                    backlinks: '',
                    ref_domain: '',
                    org_keywords: '',
                    org_traffic: '',
                    price: '',
                    language: '',
                    inc_article: '',
                    language_id: '',
                },
                isEnableBtn: true,
                isPopupLoading: false,
                filterModel: {
                    search: this.$route.query.search || '',
                    language_id: this.$route.query.language_id || '',
                    inc_article: this.$route.query.inc_article || '',
                    seller: this.$route.query.seller || '',
                    paginate: this.$route.query.paginate || 25,
                    got_ahref: this.$route.query.got_ahref || '',
                    date: this.$route.query.date || '',
                    valid: this.$route.query.valid || '',
                },
                searchLoading: false,
                checkIds: [],
                isDisabled: true,
                showLang: false,
                isSeller: false,
                allSelected: false,
                isSearching: false,
            }
        },

        async created() {
            this.getPublisherList();
            this.checkAccountType();
            this.getListSeller();

            let countries = this.listCountries.data;
            if( countries.length === 0 ){
                this.getListCountries();
            }
        },

        computed:{
            ...mapState({
                listPublish: state => state.storePublisher.listPublish,
                messageForms: state => state.storePublisher.messageForms,
                listCountries: state => state.storePublisher.listCountries,
                user: state => state.storeAuth.currentUser,
                listSeller: state => state.storePublisher.listSeller,
                listAhrefsPublisher: state => state.storePublisher.listAhrefsPublisher,
            })
        },

        methods: {
            async getPublisherList(page = 1) {

                $('#tbl-publisher').DataTable().destroy();

                this.searchLoading = true;
                this.isSearching = true;
                await this.$store.dispatch('getListPublisher', {
                    params: {
                        search: this.filterModel.search,
                        language_id: this.filterModel.language_id,
                        inc_article: this.filterModel.inc_article,
                        seller: this.filterModel.seller,
                        paginate: this.filterModel.paginate,
                        got_ahref: this.filterModel.got_ahref,
                        date: this.filterModel.date,
                        valid: this.filterModel.valid,
                        page: page
                    }
                });

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
                        { orderable: true, targets: 10 },
                        { orderable: true, targets: 11 },
                        { orderable: true, targets: 12 },
                        { orderable: true, targets: 13 },
                        { orderable: true, targets: 14 },
                        { orderable: true, targets: 15 },
                        { orderable: false, targets: '_all' }
                    ];

                if( this.user.isOurs == 0 && !this.user.isAdmin){
                    columnDefs = [
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
                        { orderable: true, targets: 10 },
                        { orderable: true, targets: 11 },
                        { orderable: true, targets: 12 },
                        { orderable: true, targets: 13 },
                        { orderable: true, targets: 14 },
                        { orderable: true, targets: 15 },
                        { orderable: false, targets: '_all' }
                    ]
                }

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
                        { orderable: true, targets: 8 },
                        { orderable: true, targets: 9 },
                        { orderable: true, targets: 10 },
                        { orderable: true, targets: 11 },
                        { orderable: true, targets: 12 },
                        { orderable: true, targets: 13 },
                        { orderable: false, targets: '_all' }
                    ]
                }

                $('#tbl-publisher').DataTable({
                    paging: false,
                    searching: false,
                    columnDefs: columnDefs,
                });

                this.searchLoading = false;
                this.isSearching = false;
            },

            async getListSeller(params) {
                await this.$store.dispatch('actionGetListSeller', params);
            },

            checkAhref( publish ) {
                let check = false;

                if( publish.ur != 0 ){
                    check = true;
                }

                return check;
            },

            selectAll: function() {
                this.checkIds = [];
                if (!this.allSelected) {
                    for (var publisher in this.listPublish.data) {
                        // let ahref = this.checkAhref(this.listPublish.data[publisher])
                        // if( !ahref ){
                            this.checkIds.push(this.listPublish.data[publisher].id);
                        // }
                        
                    }
                    this.isDisabled = false;
                }
            },

            async validData(valid) {
                await this.$store.dispatch('actionValidData', {
                    valid: valid,
                    ids: this.checkIds,
                });


                if( this.messageForms.action === 'saved' ){
                    swal.fire(
                        'Saved!',
                        'Successfully Updated.',
                        'success'
                        )

                    this.getPublisherList();

                    this.checkIds = [];
                }
            },
            
            select: function() {
                this.allSelected = false;
            },

            formatPrice(value) {
                let val = (value/1).toFixed(0)
                return val;
            },

            replaceCharacters(str) {
                let char1 = str.replace("http://", "");
                let char2 = char1.replace("https://", "");
                let char3 = char2.replace("www.", "");

                return char3;
            },

            displayDate(date) {
                return date.split(" ")[0];
            },

            checkSelected() {
                this.isDisabled = true;
                if( this.checkIds.length > 0 ){
                    this.isDisabled = false;
                }
            },

            async doMultipleDelete(){
                $('#tbl-publisher').DataTable().destroy();

                this.clearMessageform()
                if( confirm("Are you sure you want to delete selected records?") ){
                    await this.$store.dispatch('actionDeletePublisher', {
                        params: {
                            id: this.checkIds,
                        }
                    });

                    this.getPublisherList();
                    this.checkIds = []
                    this.isDisabled = true;
                }
            },

            computePrice( price, article ) {
                return price;
            },

            async submitUpload() {
                $('#tbl-publisher').DataTable().destroy();

                this.formData = new FormData();
                this.formData.append('file', this.$refs.excel.files[0]);
                this.formData.append('language', this.$refs.language.value);

                await this.$store.dispatch('actionUploadCsv', this.formData);

                if (this.messageForms.action === 'uploaded') {
                    this.getPublisherList();
                    this.$refs.excel.value = '';
                    this.$refs.language.value = '';
                    this.isEnableBtn = true;
                    this.showLang = false;
                }
            },

            checkAccountType() {
                let that = this.user
                if( that.user_type ){
                    if( that.user_type.type == 'Seller' ){
                        this.isSeller = true;
                    }
                }
            },

            clearSearch() {
                $('#tbl-publisher').DataTable().destroy();

                this.filterModel = {
                    search: '',
                    language_id: '',
                    inc_article: '',
                    seller: '',
                    paginate: 25,
                    got_ahref: '',
                    date: '',
                    valid: '',
                }

                this.getPublisherList({
                    params: this.filterModel
                });

                this.$router.replace({'query': null});
            },

            async submitUpdate(params) {
                $('#tbl-publisher').DataTable().destroy();

                this.isPopupLoading = true;
                await this.$store.dispatch('actionUpdatePublisher', this.updateModel);
                this.isPopupLoading = false;

                if (this.messageForms.action === 'updated_publisher') {
                    this.getPublisherList();
                }
            },

            async getListCountries(params) {
                await this.$store.dispatch('actionGetListCountries', params);
            },

            async getAhrefs() {
                $('#tbl-publisher').DataTable().destroy();
                /*var listInvalid = this.checkIds.some(ext => ext.status != 30);
                if (listInvalid === true) {
                    alert('List invalid: status diff with GotContacts');
                    return;
                }*/

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

                var listIds = (this.checkIds).join(',');
                this.isLoadingTable = true;
                await this.$store.dispatch('getListAhrefsPublisher', { params: { domain_ids: listIds } });
                this.isLoadingTable = false;

                swal.fire(
                        'Updated!',
                        'Ahrefs has been updated.',
                        'success'
                        )

                this.getPublisherList();
            },

            doUpdate(publish) {
                this.clearMessageform()
                let that = JSON.parse(JSON.stringify(publish))

                this.updateModel = {
                    id: that.id,
                    name: that.name,
                    username: that.username,
                    url: that.url,
                    language_id: that.language_id,
                    ur: that.ur,
                    dr: that.dr,
                    backlinks: that.backlinks,
                    ref_domain: that.ref_domain,
                    org_keywords: that.org_keywords,
                    org_traffic: that.org_traffic,
                    price: that.price,
                    anchor_text: that.anchor_text,
                    link: that.link,
                    inc_article: that.inc_article,
                }

                this.updateModel.company_name = that.isOurs == '0' ? 'Stalinks':that.company_name;
            },

            async doDelete(id){
                $('#tbl-publisher').DataTable().destroy();

                this.clearMessageform()
                if( confirm("Do you want to delete these record?") ){
                    await this.$store.dispatch('actionDeletePublisher', {
                        params: {
                            id: id,
                        }
                    });

                    this.getPublisherList();
                }
            },

            doSearch() {
                $('#tbl-publisher').DataTable().destroy();

                this.$router.push({
                    query: this.filterModel,
                });

                this.getPublisherList({
                    params: {
                        search: this.filterModel.search,
                        language_id: this.filterModel.language_id,
                        inc_article: this.filterModel.inc_article,
                        seller: this.filterModel.seller,
                        paginate: this.filterModel.paginate,
                        got_ahref: this.filterModel.got_ahref,
                        date: this.filterModel.date,
                        valid: this.filterModel.valid,
                    }
                });
            },

            checkDataLanguage() {
                this.isEnableBtn = true;
                if( this.$refs.language.value ){
                    this.isEnableBtn = false;
                }
            },

            checkDataExcel() {
                this.showLang = false;
                if( this.$refs.excel.value ){
                    this.showLang = true;
                }
            },

            clearModel () {
                this.updateModel = {
                    id: '',
                    company_name: '',
                    name: '',
                    username:'',
                    url: '',
                    ur: '',
                    dr: '',
                    backlinks: '',
                    ref_domain: '',
                    org_keywords: '',
                    org_traffic: '',
                    price: '',
                }
            },

            clearMessageform() {
                this.$store.dispatch('clearMessageform');
            },
        }

    }
</script>