<template>
    <div class="row">
        <div class="col-sm-12">

            <section class="content-header col-sm-12">
                <h1>Publisher URL List</h1>
            </section>

            <div class="box">
                

                <div class="box-body table-responsive">
                    <table class="table table-hover table-bordered table-striped rlink-table">
                        <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>Domain</th>
                                <th>URL rating</th>
                                <th>Domain rating</th>
                                <th>No Backlinks</th>
                                <th>Ref Domains</th>
                                <th>Organic Keywords</th>
                                <th>Organic Traffic</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(publish, index) in listPublish.data" :key="index">
                                <td>{{ index + 1}}</td>
                                <td>{{ publish.domain }}</td>
                                <td>{{ publish.url_rating }}</td>
                                <td>{{ publish.domain_rating }}</td>
                                <td>{{ publish.no_backlinks }}</td>
                                <td>{{ publish.ref_domains }}</td>
                                <td>{{ publish.organic_keywords }}</td>
                                <td>{{ publish.organic_traffic }}</td>
                                <td></td>
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