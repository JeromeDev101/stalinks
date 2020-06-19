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
                                <input type="text" name="search" class="form-control" placeholder="Search by external domains or internal domain">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Status</label>
                                <select class="form-control" name="" id="">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <button class="btn btn-default">Clear</button>
                            <button type="submit" name="submit" class="btn btn-default">Search</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box">

                <div class="box-header">
                    <h3 class="box-title">Backlinks</h3>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-bordered table-striped rlink-table">
                        <tbody>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>URL Publisher</th>
                                <th>Link</th>
                                <th>Price</th>
                                <th>Anchor Text</th>
                                <th>Date Completed</th>
                                <th>Status</th>
                                <th>Employee</th>
                                <th>Action</th>
                            </tr>
                            {{ objectNotEmpty(listBacklink) }}
                            <!-- <tr v-for="(publish, index) in listBacklink.data" :key="index">
                                <td>{{ index + 1 }}</td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div>
</template>
<script>
    import { mapState } from 'vuex';
    import { axios } from 'axios';
    import DownloadCsv from '@/components/export-csv/Csv.vue'
    
    export default {
        data() {
            return {
                listBacklink: {},
            }
        },

        created() {
            // this.getListBacklink();
            this.getList();
        },

        // computed: {
        //     ...mapState({
        //         listBacklink: state => state.storeNewBacklink.listBacklink,
        //     })
        // },

        methods: {
            getList(params) {
                axios.get('api/backlinks', params)
                    .then(response => this.listBacklink = response)
            },
            // async getListBacklink(params){
            //     await this.$store.dispatch('actionGetBacklinkList', params);
            // },

        },

        
    }
</script>