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
                </div>

                <div class="box-body table-responsive no-padding relative">
                    <table class="table table-hover table-bordered table-striped rlink-table">
                        <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>ID Article</th>
                                <th>ID Backlink</th>
                                <th>ID Writer</th>
                                <th>ID language</th>
                                <th>Date Start</th>
                                <th>Date Completed</th>
                                <th>Content</th>
                                <th>Writer Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="listArticles.data.length == 0">
                                <td colspan="9" class="text-center">No record</td>
                            </tr>
                            <tr v-for="(article, index) in listArticles.data" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ article.id }}</td>
                                <td>{{ article.id_backlink }}</td>
                                <td>{{ article.id_writer }}</td>
                                <td>{{ article.country.name }}</td>
                                <td>{{ article.date_start }}</td>
                                <td>{{ article.date_completed }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button title="View Content" class="btn btn-default"><i class="fa fa-fw fa-eye"></i></button>
                                    </div>
                                </td>
                                <td>{{ article.id_writer_price }}</td>
                            </tr>
                        </tbody>
                    </table>

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
                //
            }
        },

        async created() {
            this.getListArticles();
        },

        computed: {
            ...mapState({
                listArticles: state => state.storeArticles.listArticles,
            })
        },

        methods: {
            async getListArticles(params){
                await this.$store.dispatch('actionGetListArticle',params);
            },
        },
    }
</script>