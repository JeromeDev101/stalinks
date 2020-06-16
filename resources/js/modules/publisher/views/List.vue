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
                                <input type="file" class="form-control" v-on:change="checkData" enctype="multipart/form-data" ref="excel" name="file">
                                <div class="input-group-btn">
                                    <button title="Upload CSV File" @click="submitUpload" :disabled="isEnableBtn" class="btn btn-primary btn-flat"><i class="fa fa-upload"></i></button>
                                </div>
                            </div>
                            <span v-if="messageForms.errors.file" v-for="err in messageForms.errors.file" class="text-danger">{{ err }}</span>
                            <span v-if="messageForms.action == 'uploaded'" class="text-success">{{ messageForms.message }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="box-body table-responsive no-padding relative">
                    <table class="table table-hover table-bordered table-striped rlink-table">
                        <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>Company Name</th>
                                <th>Name</th>
                                <th>URL</th>
                                <th>UR</th>
                                <th>DR</th>
                                <th>Backlinks</th>
                                <th>Ref Domains</th>
                                <th>Organic Keywords</th>
                                <th>Organic Traffic</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="listPublish.data.length == 0">
                                <td colspan="12" class="text-center">No record</td>
                            </tr>
                            <tr v-for="(publish, index) in listPublish.data" :key="index">
                                <td>{{ index + 1}}</td>
                                <td></td>
                                <td></td>
                                <td>{{ publish.url }}</td>
                                <td>{{ publish.ur }}</td>
                                <td>{{ publish.dr }}</td>
                                <td>{{ publish.backlinks }}</td>
                                <td>{{ publish.ref_domain }}</td>
                                <td>{{ publish.org_keywords }}</td>
                                <td>{{ publish.org_traffic }}</td>
                                <td>{{ publish.price }}</td>
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
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                isEnableBtn: true,
            }
        },

        async created() {
            this.getPublisherList();
        },

        computed:{
            ...mapState({
                listPublish: state => state.storePublisher.listPublish,
                messageForms: state => state.storePublisher.messageForms,
            })
        },

        methods: {
            async getPublisherList(params) {
                await this.$store.dispatch('getListPublisher', params);
            },

            async submitUpload() {
                this.formData = new FormData();
                this.formData.append('file', this.$refs.excel.files[0]);

                await this.$store.dispatch('actionUploadCsv', this.formData);

                if (this.messageForms.action === 'uploaded') {
                    this.getPublisherList();
                    this.$refs.excel.value = '';
                    this.isEnableBtn = true;
                }
            },

            checkData() {
                this.isEnableBtn = true;
                if( this.$refs.excel.value ){
                    this.isEnableBtn = false;
                }
            },

            clearMessageform() {
                this.$store.dispatch('clearMessageform');
            },
        }
    }
</script>