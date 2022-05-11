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
                        <h3 class="card-title text-primary">{{ $t('message.admin_article.filter_title') }}</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-12 col-lg-2">
                                <div class="form-group">
                                    <label>{{ $t('message.admin_article.filter_id_article') }}</label>
                                    <input type="text"
                                           class="form-control"
                                           v-model="filterModel.search_article"
                                           name=""
                                           aria-describedby="helpId"
                                           :placeholder="$t('message.admin_article.type')">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ $t('message.admin_article.filter_id_backlink') }}</label>
                                    <input type="text"
                                           class="form-control"
                                           v-model="filterModel.search_backlink"
                                           name=""
                                           aria-describedby="helpId"
                                           :placeholder="$t('message.admin_article.type')">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ $t('message.admin_article.filter_language') }}</label>
                                    <select class="form-control" name="" v-model="filterModel.language_id">
                                        <option value="">{{ $t('message.admin_article.filter_select_language') }}</option>
                                        <option value="none">{{ $t('message.admin_article.none') }}</option>
                                        <option v-for="option in listLanguages.data"
                                                :value="option.id"
                                                :key="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ $t('message.admin_article.filter_writer') }}</label>
                                    <select name="" class="form-control" v-model="filterModel.writer">
                                        <option value="">{{ $t('message.admin_article.all') }}</option>
                                        <option v-for="option in listWriter.data" v-bind:value="option.id">
                                            {{ option.username }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ $t('message.admin_article.filter_date') }}</label>
                                    <div class="input-group">
                                        <select name="" class="form-control" v-model="filterModel.date_type">
                                            <option value="Started">{{ $t('message.admin_article.filter_started') }}</option>
                                            <option value="Completed">{{ $t('message.admin_article.filter_completed') }}</option>
                                        </select>
                                        <input type="date"
                                               class="form-control"
                                               v-model="filterModel.date"
                                               name=""
                                               aria-describedby="helpId">
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="col-md-2">
                                <button class="btn btn-default" @click="clearSearch" :disabled="isSearching">
                                    {{ $t('message.admin_article.clear') }}
                                </button>
                                <button class="btn btn-default" @click="doSearch" :disabled="isSearching">
                                    {{ $t('message.admin_article.search') }}
                                    <i v-if="searchLoading" class="fa fa-refresh fa-spin"></i></button>
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
                        <h3 class="card-title text-primary">{{ $t('message.admin_article.aa_title') }}</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="input-group input-group-sm float-right" style="width: 100px">
                            <select name=""
                                    class="form-control float-right"
                                    @change="doSearch"
                                    v-model="filterModel.paginate"
                                    style="height: 37px;">
                                <option v-for="option in paginate" v-bind:value="option">
                                    {{ option }}
                                </option>
                            </select>
                        </div>

                        <span v-if="listArticlesAdmin.total > 10" class="pagination-custom-footer-text">
                            <b>Showing {{ listArticlesAdmin.from }} to {{
                                    listArticlesAdmin.to
                               }} of {{ listArticlesAdmin.total }} entries.</b>
                        </span>

                        <table id="tbl_article_admin"
                               class="table table-hover table-bordered table-striped rlink-table">
                            <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>{{ $t('message.admin_article.aa_id_article') }}</th>
                                <th>{{ $t('message.admin_article.aa_id_backlink') }}</th>
                                <th>{{ $t('message.admin_article.filter_writer') }}</th>
                                <th>{{ $t('message.admin_article.filter_language') }}</th>
                                <th>{{ $t('message.admin_article.aa_date_start') }}</th>
                                <th>{{ $t('message.admin_article.aa_date_completed') }}</th>
                                <th>{{ $t('message.admin_article.aa_writer_price') }}</th>
                                <th>{{ $t('message.admin_article.aa_action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(article, index) in listArticlesAdmin.data" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ article.id }}</td>
                                <td>{{ article.id_backlink }}</td>
                                <td>{{ article.user.username == null ? article.user.name : article.user.username }}</td>
                                <td>{{ article.language ? article.language.name : 'N/A' }}</td>
                                <td>{{ article.date_start }}</td>
                                <td>{{ article.date_complete }}</td>
                                <td>{{ article.isOurs == 1 ? computeWriterPrice(article) : '----' }}</td>
                                <td>
                                    <div :disabled="article.content == null" class="btn-group">
                                        <button :title="$t('message.admin_article.aa_view_content')"
                                                @click="viewContent( article.backlink ,article.content, article)"
                                                data-toggle="modal"
                                                data-target="#modal-view-content"
                                                class="btn btn-default"><i class="fa fa-fw fa-eye"></i></button>
                                    </div>
                                    <div class="btn-group" v-if="user.isAdmin">
                                        <button :title="$t('message.admin_article.delete')"
                                                @click="deleteArticle(article.id)"
                                                class="btn btn-default"><i class="fa fa-fw fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal View Content -->
        <div class="modal fade"
             id="modal-view-content"
             tabindex="-1"
             role="dialog"
             aria-labelledby="modelTitleId"
             aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.admin_article.vc_title') }}</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.admin_article.vc_title_text') }}</label>
                                    <input type="text" class="form-control" v-model="viewModel.title" :disabled="true">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.admin_article.vc_anchor_text') }}</label>
                                    <input type="text"
                                           class="form-control"
                                           v-model="viewModel.anchor_text"
                                           :disabled="true">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.admin_article.vc_url_pub') }}</label>
                                    <input type="text"
                                           class="form-control"
                                           v-model="viewModel.url_publisher"
                                           :disabled="true">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.admin_article.vc_link_to') }}</label>
                                    <input type="text" class="form-control" v-model="viewModel.link" :disabled="true">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.admin_article.vc_seller') }}</label>
                                    <input type="text" class="form-control" v-model="viewModel.seller" :disabled="true">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.admin_article.vc_buyer') }}</label>
                                    <input type="text" class="form-control" v-model="viewModel.buyer" :disabled="true">
                                </div>
                            </div>

                            <div class="col-md-6" v-show="showWriterPrice">
                                <div class="form-group">
                                    <label>{{ $t('message.admin_article.aa_writer_price') }}</label>
                                    <input type="text"
                                           class="form-control"
                                           v-model="editModel.price"
                                           placeholder="0.00"
                                           :disabled="true">
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <tinymce id="content" v-model="data" :other_options="options"></tinymce>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.admin_article.close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal View Content -->
    </div>
</template>

<script>
import {mapState} from 'vuex';

export default {
    data() {
        return {
            paginate : [
                15,
                25,
                50,
                100,
                200,
                250
            ],
            data : '',
            options : {
                height : 500,
                toolbar : false,
                menubar : false,
            },
            viewModel : {
                title : '',
                anchor_text : '',
                seller : '',
                buyer : '',
                url_publisher : '',
                link : ''
            },
            searchLoading : false,
            filterModel : {
                search_article : this.$route.query.search_article || '',
                search_backlink : this.$route.query.search_backlink || '',
                language_id : this.$route.query.language_id || '',
                writer : this.$route.query.writer || '',
                date : this.$route.query.date || '',
                date_type : this.$route.query.date_type || 'Started',
                paginate : this.$route.query.paginate || '15',
            },
            editModel : {
                price : '',
            },
            isSearching : false,
            showWriterPrice : false,
        }
    },

    async created() {
        this.getListArticles();
        this.getListCountries();
        this.getListWriter();
        this.getListLanguages();
    },

    computed : {
        ...mapState({
            listArticlesAdmin : state => state.storeArticles.listArticlesAdmin,
            listCountries : state => state.storeArticles.listCountries,
            listWriter : state => state.storeArticles.listWriter,
            user : state => state.storeAuth.currentUser,
            listLanguages : state => state.storePublisher.listLanguages,
        })
    },

    methods : {
        async getListLanguages() {
            await this.$store.dispatch('actionGetListLanguages');
        },

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

            return '$ ' + price;
        },

        countWords(str) {
            str = str.replace(/(^\s*)|(\s*$)/gi, "");
            str = str.replace(/[ ]{2,}/gi, " ");
            str = str.replace(/\n /, "\n");
            return str.split(' ').length;
        },

        async getListArticles(params) {
            $('#tbl_article_admin').DataTable().destroy();

            this.isSearching = true;
            this.searchLoading = true;
            await this.$store.dispatch('actionGetListArticleAdmin', params);
            this.searchLoading = false;
            this.isSearching = false;

            $('#tbl_article_admin').DataTable({
                paging : false,
                searching : false,
                columnDefs : [
                    {orderable : true, targets : 0},
                    {orderable : true, targets : 1},
                    {orderable : true, targets : 2},
                    {orderable : true, targets : 3},
                    {orderable : true, targets : 4},
                    {orderable : true, targets : 5},
                    {orderable : true, targets : 6},
                    {orderable : true, targets : 7},
                    {orderable : false, targets : '_all'}
                ],
            });

        },

        async getListWriter(params) {
            await this.$store.dispatch('actionGetListWriter', params);
        },

        doSearch() {

            this.$router.push({
                query : this.filterModel,
            });

            this.getListArticles({
                params : {
                    search_backlink : this.filterModel.search_backlink,
                    search_article : this.filterModel.search_article,
                    language_id : this.filterModel.language_id,
                    writer : this.filterModel.writer,
                    date : this.filterModel.date,
                    date_type : this.filterModel.date_type,
                    paginate : this.filterModel.paginate,
                }
            });
        },

        deleteArticle(id) {
            let self = this;

            swal.fire({
                title : self.$t('message.admin_article.alert_confirmation'),
                text : self.$t('message.admin_article.alert_confirmation_delete'),
                icon : "warning",
                showCancelButton : true,
                confirmButtonText : self.$t('message.admin_article.delete_yes'),
                cancelButtonText : self.$t('message.admin_article.no')
            })
            .then((result) => {
                if (result.isConfirmed) {

                    axios.post('/api/delete-article', {
                        id : id
                    })
                        .then(response => {
                            this.getListArticles();

                            swal.fire(
                                self.$t('message.admin_article.alert_deleted'),
                                self.$t('message.admin_article.alert_deleted_successfully'),
                                'success'
                            )
                        })

                }
            });
        },

        clearSearch() {
            this.filterModel = {
                search_article : '',
                search_backlink : '',
                language_id : '',
                writer : '',
                date : '',
                date_type : 'Started',
                paginate : '15',
            }

            this.getListArticles({
                params : this.filterModel
            });

            this.$router.replace({'query' : null});
        },

        viewContent(backlinks, content, article) {
            this.data = content == null ? '' : content;
            this.viewModel = backlinks == null ? this.viewModel : backlinks;
            this.viewModel.url_publisher = backlinks == null ? '' : backlinks.publisher.url;
            this.viewModel.seller = backlinks == null ? '' : backlinks.publisher.user.name;
            this.viewModel.buyer = backlinks == null ? '' : backlinks.user.name;

            this.editModel.price = this.computeWriterPrice(article)

            if (article.isOurs === 1) {
                this.showWriterPrice = true;
            } else {
                this.showWriterPrice = false;
            }
        },

        async getListCountries(params) {
            await this.$store.dispatch('actionGetListCountries', params);
        },
    },
}
</script>
