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
                                <label for="">Search</label>
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
                                <th>ID language</th>
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
                                <td>{{ article.date_start }}</td>
                                <td>{{ article.date_completed }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a title="Add Content" class="btn btn-default" :href="'articles/'+article.id"><i class="fa fa-fw fa-pencil"></i></a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        
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
            }
        },

        async created() {
            this.getListArticles();
            this.getListBacklinks();
            this.getListWriter();
        },

        computed: {
            ...mapState({
                listArticles: state => state.storeArticles.listArticles,
                listBacklinks: state => state.storeArticles.listBacklinks,
                listWriter: state => state.storeArticles.listWriter,
                messageForms: state => state.storeArticles.messageForms,
            })
        },

        methods: {
            async getListArticles(params){
                await this.$store.dispatch('actionGetListArticle', params);
            },

            async getListBacklinks(params){
                await this.$store.dispatch('actionGetListBacklinks', params);
            },

            async getListWriter(params){
                await this.$store.dispatch('actionGetListWriter', params);
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