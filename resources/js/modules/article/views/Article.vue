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
                                <label for="">Search ID article</label>
                                <input type="text" class="form-control" v-model="filterModel.search_article" name="" aria-describedby="helpId" placeholder="Type here">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Search ID backlink</label>
                                <input type="text" class="form-control" v-model="filterModel.search_backlink" name="" aria-describedby="helpId" placeholder="Type here">
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
                    <h3 class="box-title">Articles</h3>
                    <button data-toggle="modal" @click="clearModels" data-target="#modal-add-article" class="btn btn-success float-right"><i class="fa fa-plus"></i> Create Article</button>
                </div>

                <div class="box-body table-responsive no-padding relative">
                    <table class="table table-hover table-bordered table-striped rlink-table">
                        <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>ID Article</th>
                                <th>ID Backlink</th>
                                <th>Language</th>
                                <th>Date Start</th>
                                <th>Date Completed</th>
                                <th>Content</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="listArticles.data.length == 0">
                                <td colspan="7" class="text-center">No record</td>
                            </tr>
                            <tr v-for="(article, index) in listArticles.data" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ article.id }}</td>
                                <td>{{ article.id_backlink }}</td>
                                <td>{{ article.country.name }}</td>
                                <td>{{ article.date_start == null ? '-':article.date_start }}</td>
                                <td>{{ article.date_complete == null ? '-':article.date_complete}}</td>
                                <td>
                                    <div class="btn-group">
                                        <!-- <router-link class="btn btn-default" :to="{ path: 'articles/'+article.id, params: { id: article.id }}"><i class="fa fa-fw fa-pencil"></i></router-link> -->
                                        <button @click="doUpdate(article.backlinks, article)" data-toggle="modal" data-target="#modal-content-edit" class="btn btn-default"><i class="fa fa-fw fa-pencil"></i></button>
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
                        <h5 class="modal-title">Edit Content</h5>
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
                                    <label for="">Writer Price</label>
                                    <input type="number" v-model="contentModel.price" class="form-control" name="" aria-describedby="helpId" placeholder="0.00">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Status</label>
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
                                <tinymce id="d1" v-model="data" :other_options="options"></tinymce>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <span class="text-primary float-left">Press 'Ctrl + Shift + F' for full screen</span>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                                    <input type="text" class="form-control" v-model="addModel.link_to" :disabled="true">
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
    export default {
        data() {
            return {
                writer_status: ['Content writing', 'Content sent'],
                viewModel: {
                    backlink: '',
                },
                data: '',
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
                options: {
                    height: 500
                },

                contentModel: {
                    id: '',
                    title: '',
                    anchor_text: '',
                    price: '',
                    status: '',
                },
                filterModel: {
                    search_article: this.$route.query.search_article || '',
                    search_backlink: this.$route.query.search_backlink || '',
                    language_id: this.$route.query.language_id || '',
                },

                searchLoading: false,
            }
        },

        async created() {
            this.getListArticles();
            this.getListBacklinks();
            this.getListWriter();
            this.getListCountries();
        },

        computed: {
            ...mapState({
                listArticles: state => state.storeArticles.listArticles,
                listBacklinks: state => state.storeArticles.listBacklinks,
                listWriter: state => state.storeArticles.listWriter,
                messageForms: state => state.storeArticles.messageForms,
                listCountries: state => state.storeArticles.listCountries,
            })
        },

        methods: {
            async getListArticles(params){
                this.searchLoading = true;
                await this.$store.dispatch('actionGetListArticle', params);
                this.searchLoading = false;
            },

            async getListBacklinks(params){
                await this.$store.dispatch('actionGetListBacklinks', params);
            },

            async getListWriter(params){
                await this.$store.dispatch('actionGetListWriter', params);
            },

            doUpdate(backlink, article){
                this.clearMessageform()
                this.data = article.content == null ? '':article.content;
                this.contentModel.price = article.price;
                this.contentModel.id = article.id;
                this.contentModel.title = backlink == null ? '':backlink.title;
                this.contentModel.anchor_text = backlink == null ? '':backlink.anchor_text;
                this.contentModel.status = backlink == null ? '':backlink.status;
            },

            doSearch() {
                this.$router.push({
                    query: this.filterModel,
                });

                this.getListArticles({
                    params: {
                        search_backlink: this.filterModel.search_backlink,
                        search_article: this.filterModel.search_article,
                        language_id: this.filterModel.language_id,
                    }
                });
            },

            clearSearch() {
                this.filterModel = {
                    search_article: '',
                    search_backlink: '',
                    language_id: '',
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