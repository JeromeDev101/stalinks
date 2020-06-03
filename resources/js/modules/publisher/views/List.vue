<template>
    <div class="row">
        <div class="col-sm-12">

            <section class="content-header col-sm-12">
                <h1>Publisher URL List</h1>
            </section>

            <div class="box">

                <div class="box-header">
                    <div class="form-row">
                        <div class="col-md-5 mb-3">
                            <label class="int-domain">Upload CSV</label>
                            <div class="input-group">
                                <input type="file" class="form-control" placeholder="Search by external domains or internal domain">
                                <div class="input-group-btn">
                                    <button title="Upload CSV File" class="btn btn-primary btn-flat"><i class="fa fa-upload"></i></button>
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
                                <th>Domain</th>
                                <th>Seller ID</th>
                                <th>URL rating</th>
                                <th>Domain rating</th>
                                <th>No Backlinks</th>
                                <th>Ref Domains</th>
                                <th>Organic Keywords</th>
                                <th>Organic Traffic</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(publish, index) in listPublish.data" :key="index">
                                <td>{{ index + 1}}</td>
                                <td>{{ publish.domain }}</td>
                                <td></td>
                                <td>{{ publish.url_rating }}</td>
                                <td>{{ publish.domain_rating }}</td>
                                <td>{{ publish.no_backlinks }}</td>
                                <td>{{ publish.ref_domains }}</td>
                                <td>{{ publish.organic_keywords }}</td>
                                <td>{{ publish.organic_traffic }}</td>
                                <td></td>
                                <td>
                                    <div class="btn-group">
                                        <button data-toggle="modal" data-target="#modal-update-publisher" title="Edit" class="btn btn-default"><i class="fa fa-fw fa-edit"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        
        <!-- Modal Update Publisher-->
        <div class="modal fade" id="modal-update-publisher" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Domain Information</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Domain</label>
                                    <input type="text" class="form-control" name="" id="" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Status</label>
                                    <input type="text" class="form-control" name="" id="" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Facebook</label>
                                    <textarea class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <textarea class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Phone</label>
                                    <textarea class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Ahrefs Rank</label>
                                    <input type="number" class="form-control" name="" id="" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Ref Domains</label>
                                    <input type="number" class="form-control" name="" id="" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">No Backlinks</label>
                                    <input type="number" class="form-control" name="" id="" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">URL Rating</label>
                                    <input type="number" class="form-control" name="" id="" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Domain Rating</label>
                                    <input type="number" class="form-control" name="" id="" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Organic Keywords</label>
                                    <input type="text" class="form-control" name="" id="" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Organic traffic</label>
                                    <input type="text" class="form-control" name="" id="" placeholder="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<script>

    import { mapState } from 'vuex';

    export default {
        name: '',
        data(){
            return {
                // code
            }
        },

        async created() {
            this.getPublisherList();
        },

        computed:{
            ...mapState({
                listPublish: state => state.storePublisher.listPublish
            })
        },

        methods: {
            async getPublisherList(params) {
                await this.$store.dispatch('getListPublisher', params);
            }
        }
    }
</script>