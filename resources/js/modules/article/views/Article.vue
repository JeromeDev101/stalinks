<template>
    <div class="row">
        <div class="col-sm-12">

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Filter</h3>
                </div>

                <div class="box-body m-3">

                    <div class="row">

                        <div class="col-md-12 col-lg-2">
                            <div class="form-group">
                                <label for="">Search ID article</label>
                                <input type="text" class="form-control" v-model="filterModel.search_article" name="" aria-describedby="helpId" placeholder="Type here">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Search ID backlink</label>
                                <input type="text" class="form-control" v-model="filterModel.search_backlink" name="" aria-describedby="helpId" placeholder="Type here">
                            </div>
                        </div>

                        <div class="col-md-3">
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

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Writer</label>
                                <select name="" class="form-control" v-model="filterModel.writer">
                                    <option value="">All</option>
                                    <option v-for="option in listWriter.data" v-bind:value="option.id">
                                        {{ option.username }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Status</label>
                                <select name="" class="form-control" v-model="filterModel.status">
                                    <option value="">All</option>
                                    <option value="Queue">Queue</option>
                                    <option value="In Writing">In Writing</option>
                                    <option value="Done">Done</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Topic</label>
                                <!-- <select name="" class="form-control" v-model="filterModel.topic">
                                    <option value="">All</option>
                                    <option v-for="option in topic" v-bind:value="option">
                                        {{ option }}
                                    </option>
                                </select> -->
                                <v-select multiple v-model="filterModel.topic" :options="topic" :searchable="false" placeholder="All"/>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Accept Casino & Betting Sites</label>
                                <select name="" class="form-control" v-model="filterModel.casino_sites">
                                    <option value="">All</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        </div>

                        

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <button class="btn btn-default" @click="clearSearch" :disabled="isSearching">Clear</button>
                            <button class="btn btn-default" @click="doSearch" :disabled="isSearching">Search <i v-if="searchLoading" class="fa fa-refresh fa-spin" ></i></button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Articles</h3>

                    <div class="input-group input-group-sm float-right" style="width: 100px">
                        <select name="" class="form-control float-right" @change="doSearch" v-model="filterModel.paginate" style="height: 37px;">
                            <option v-for="option in paginate" v-bind:value="option">
                                {{ option }}
                            </option>
                        </select>
                    </div>

                    <button v-if="isTeam" data-toggle="modal" @click="clearModels" data-target="#modal-add-article" class="btn btn-success float-right"><i class="fa fa-plus"></i> Create Article</button>
                </div>

                <div class="box-body no-padding relative">
                    <span class="pagination-custom-footer-text">
                        <b>Showing {{ listArticles.from }} to {{ listArticles.to }} of {{ listArticles.total }} entries.</b>
                    </span>

                    <table id="tbl_articles" class="table table-hover table-bordered table-striped rlink-table">
                        <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>ID Article</th>
                                <th>ID Backlink</th>
                                <th>Language</th>
                                <th>Writer</th>
                                <th>Topic</th>
                                <th>Accept Casino & Betting Sites</th>
                                <th>Date Start</th>
                                <th>Date Completed</th>
                                <th>Status</th>
                                <th>Content</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(article, index) in listArticles.data" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ article.id }}</td>
                                <td>{{ article.id_backlink }}</td>
                                <td>{{ article.country == null ? 'N/A': article.country.name }}</td>
                                <td>{{ article.writer }}</td>
                                <td>{{ article.topic == null ? 'N/A' : article.topic }}</td>
                                <td>{{ article.casino_sites == null ? 'N/A' : article.casino_sites }}</td>
                                <td>{{ article.date_start == null ? '-':article.date_start }}</td>
                                <td>{{ article.date_complete == null ? '-':article.date_complete}}</td>
                                <td>{{ article.status_writer == null ? 'Queue':article.status_writer  }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button :id="'article-' + article.id" @click="doUpdate(article.backlinks, article)" data-toggle="modal" data-target="#modal-content-edit" class="btn btn-default"><i class="fa fa-fw fa-pencil"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        
        <!-- Modal Content -->
        <div class="modal fade" id="modal-content-edit" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Content | Article ID: <b class="text-primary">{{ contentModel.id }}</b></h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Title</label>
                                    <input type="text" v-model="contentModel.title" :disabled="true" class="form-control" name="" aria-describedby="helpId" placeholder="">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Anchor Text</label>
                                    <input type="text" v-model="contentModel.anchor_text" :disabled="true" class="form-control" name="" aria-describedby="helpId" placeholder="">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">URL Publisher</label>
                                    <input type="text" v-model="contentModel.url_publisher" :disabled="true" class="form-control" name="" aria-describedby="helpId" placeholder="">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Link To</label>
                                    <input type="text" v-model="contentModel.link" :disabled="true" class="form-control" name="" aria-describedby="helpId" placeholder="">
                                </div>
                            </div>

                            <div class="col-sm-6" v-if="user.isOurs != '1'">
                                <div class="form-group">
                                    <label for="">Seller</label>
                                    <input type="text" v-model="contentModel.seller" :disabled="true" class="form-control" name="" aria-describedby="helpId" placeholder="">
                                </div>
                            </div>

                            <div class="col-sm-6" v-if="user.isOurs != '1'">
                                <div class="form-group">
                                    <label for="">Buyer</label>
                                    <input type="text" v-model="contentModel.buyer" :disabled="true" class="form-control" name="" aria-describedby="helpId" placeholder="">
                                </div>
                            </div>

                            <!-- <div class="col-sm-6" v-if="user.isOurs != '1'">
                                <div class="form-group">
                                    <label for="">Writer Price</label>
                                    <input type="number" v-model="contentModel.price" class="form-control" name="" aria-describedby="helpId" placeholder="0.00">
                                </div>
                            </div> -->

                            <div class="col-sm-6" v-if="user.isOurs == '0' || user.role_id == 4">
                                <div class="form-group">
                                    <label for="">Status Writer</label>
                                    <select name="" class="form-control" v-model="contentModel.status">
                                        <option value="">Select Status</option>
                                        <option v-for="option in writer_status" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <tinymce id="articleContent" v-model="data" :other_options="options"></tinymce>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <span class="text-primary mr-auto">Press 'Ctrl + Shift + F' for full screen</span>
                        <button @click="clearQuery" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button @click="submitSave"  type="button" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Content -->
        
        <!-- Modal Add Article -->
        <div class="modal fade" id="modal-add-article" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create Article</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.backlink}">
                                    <label for="">Select Backlink</label>
                                    <select name="" class="form-control" v-on:change="displayInfo" v-model="viewModel.backlink">
                                        <option value="">Select Backlinks</option>
                                        <option v-for="option in listBacklinks.data" v-bind:value="option">
                                            ID: {{ option.id }} URL: {{ option.publisher.url }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors.backlink" v-for="err in messageForms.errors.backlink" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Title</label>
                                    <input type="text" class="form-control" v-model="addModel.title" :disabled="true">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Anchor Text</label>
                                    <input type="text" class="form-control" v-model="addModel.anchor_text" :disabled="true">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Link To</label>
                                    <input type="text" class="form-control" v-model="addModel.link" :disabled="true">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Status</label>
                                    <input type="text" class="form-control" v-model="addModel.status" :disabled="true">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.writer}">
                                    <label for="">Writer</label>
                                    <select name="" class="form-control" v-model="addModel.writer">
                                        <option v-for="option in listWriter.data" v-bind:value="option.id">
                                            ID: {{ option.id }} Name: {{ option.name }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors.writer" v-for="err in messageForms.errors.writer" class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                                
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="addArticle">Add Article</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Add Article -->

    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import 'tinymce/skins/lightgray/skin.min.css';

    export default {
        data() {
            return {
                paginate: ['50','150','250','350','All'],
                data: '',
                options: {
                    height: 500,
                    // target_list: false,
                    // relative_urls : false,
                    // remove_script_host : false,
                    // document_base_url : 'http://www.example.com/path1/'
                    allow_script_urls: false,
                },

                writer_status: ['In Writing', 'Done'],
                viewModel: {
                    backlink: '',
                },
                addModel: {
                    backlink: '',
                    title: '',
                    anchor_text: '',
                    link_to: '',
                    status: '',
                    writer: '',
                    language_id: '',
                },
                isPopupLoading: false,
                contentModel: {
                    id: '',
                    title: '',
                    anchor_text: '',
                    price: '',
                    status: '',
                    url_publisher: '',
                    link: '',
                    seller: '',
                    buyer: '',
                },
                filterModel: {
                    paginate: this.$route.query.paginate || '50',
                    search_article: this.$route.query.search_article || '',
                    search_backlink: this.$route.query.search_backlink || '',
                    language_id: this.$route.query.language_id || '',
                    status: this.$route.query.status || '',
                    casino_sites: this.$route.query.casino_sites || '',
                    topic: this.$route.query.topic || '',
                    writer: this.$route.query.writer || '',
                },

                searchLoading: false,
                isTeam: false,
                id_article: this.$route.query.id || '',
                topic: [
                    'Beauty',
                    'Charity',
                    'Cooking',
                    'Crypto',
                    'Education',
                    'Fashion',
                    'Finance',
                    'Games',
                    'Health',
                    'History',
                    'Job',
                    'Movies & Music',
                    'News',
                    'Pet',
                    'Photograph',
                    'Real State',
                    'Religion',
                    'Shopping',
                    'Sports',
                    'Tech',
                    'Travel',
                    'Unlisted',
                ],
                isSearching: false,
            }
        },

        async created() {
            this.getListArticles();
            this.getListBacklinks();
            this.getListWriter();
            this.getListCountries();
            this.checkTeam();
            this.getListWriter();
        },

        computed: {
            ...mapState({
                listArticles: state => state.storeArticles.listArticles,
                listBacklinks: state => state.storeArticles.listBacklinks,
                listWriter: state => state.storeArticles.listWriter,
                messageForms: state => state.storeArticles.messageForms,
                listCountries: state => state.storeArticles.listCountries,
                user: state => state.storeAuth.currentUser,
                listWriter: state => state.storeArticles.listWriter,
            })
        },

        methods: {
            async getListWriter(params) {
                await this.$store.dispatch('actionGetListWriter', params);
            },


            async getListArticles(params){
                $('#tbl_articles').DataTable().destroy();

                this.searchLoading = true;
                this.isSearching = true;
                await this.$store.dispatch('actionGetListArticle', params);
                this.searchLoading = false;
                this.isSearching = false;

                $('#tbl_articles').DataTable({
                    paging: false,
                    searching: false,
                });


                this.viewArticle();
            },

            async getListBacklinks(params){
                await this.$store.dispatch('actionGetListBacklinks', params);
            },

            async getListWriter(params){
                await this.$store.dispatch('actionGetListWriter', params);
            },

            checkTeam() {
                if( this.user.isOurs == 0 ){
                    this.isTeam = true;
                }
            },

            viewArticle() {
                let id = this.id_article;
                let articles = this.listArticles.data;
                let article = '';

                articles.forEach(function(item, index){
                    if( item.id == id){
                        article = item;
                    }
                })

                if( id ){
                    this.doUpdate(article.backlinks, article);
                    $("#modal-content-edit").modal('show')
                }
            },

            clearQuery() {
                let id = this.id_article;
                if( id != '' ){
                    this.$router.replace({'query': null});
                }
            },

            doUpdate(backlink, article){
                this.clearMessageform()
                this.data = article.content == null ? '':article.content;
                this.contentModel.price = article.price == null ? '':article.price.price;
                this.contentModel.id = article.id;
                this.contentModel.title = backlink == null ? '':backlink.title;
                this.contentModel.anchor_text = backlink == null ? '':backlink.anchor_text;
                this.contentModel.status = article.status_writer == null ? '':article.status_writer;
                this.contentModel.link = backlink == null ? '':backlink.link;
                this.contentModel.url_publisher = backlink == null ? '':backlink.publisher.url;
                this.contentModel.seller = backlink == null ? '':backlink.publisher.user.name;
                this.contentModel.buyer = backlink == null ? '':backlink.user.name;
            },

            doSearch() {
                this.$router.push({
                    query: this.filterModel,
                });

                this.getListArticles({
                    params: {
                        paginate: this.filterModel.paginate,
                        search_backlink: this.filterModel.search_backlink,
                        search_article: this.filterModel.search_article,
                        language_id: this.filterModel.language_id,
                        status: this.filterModel.status,
                        casino_sites: this.filterModel.casino_sites,
                        topic: this.filterModel.topic,
                        writer: this.filterModel.writer,
                    }
                });
            },

            clearSearch() {
                this.filterModel = {
                    paginate: '50',
                    search_article: '',
                    search_backlink: '',
                    language_id: '',
                    status: '',
                    casino_sites: '',
                    topic: '',
                    writer: '',
                }

                this.getListArticles({
                    params: this.filterModel
                });

                this.$router.replace({'query': null});
            },

            async getListCountries(params) {
                await this.$store.dispatch('actionGetListCountries', params);
            },

            async submitSave() {
                this.isPopupLoading = true;
                await this.$store.dispatch('actionSaveContent', {
                    data: this.data,
                    content: this.contentModel
                });
                this.isPopupLoading = false;

                this.getListArticles();
            },

            displayInfo() {
                let backlink = this.viewModel.backlink;
                this.addModel.backlink = backlink ? backlink.id:'';
                this.addModel.status = backlink.status;
                this.addModel.anchor_text = backlink.anchor_text;
                this.addModel.title = backlink.title;
                this.addModel.language_id = backlink.publisher.language_id;
                this.addModel.link_to = backlink ? backlink.link:'';
            },

            async addArticle() {
                this.isPopupLoading = true;
                await this.$store.dispatch('actionAddArticle', this.addModel);
                this.isPopupLoading = false;

                if( this.messageForms.action == 'success' ){
                    this.clearModels();
                    this.getListArticles();
                }
            },

            clearModels() {
                this.addModel = {
                    title: '',
                    anchor_text: '',
                    link: '',
                    status: '',
                }

                this.viewModel = {
                    backlink: '',
                }
            },

            clearMessageform() {
                this.$store.dispatch('clearMessageform');
            },
        },
    }
</script>