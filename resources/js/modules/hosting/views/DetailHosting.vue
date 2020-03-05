<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary table-user">
                <div class="box-header">
                    <h3 class="box-title">Hosting details</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <div class="table-responsive">
                        <table class="table no-margin">
                            <tbody>
                                <tr>
                                    <td><i class="fa fa-ioxhost"></i><b> Name</b></td>
                                    <td>{{ hostingDetail.name }}</td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-link"></i><b> Link</b></td>
                                    <td><a v-bind:href="hostingDetail.link">{{ hostingDetail.link }}</a></td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-user"></i><b> Username</b></td>
                                    <td>{{ hostingDetail.username }}</td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-compass"></i><b>Password</b></td>
                                    <td>{{ hostingDetail.password }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Internal Domain List</h3>
                </div>
                <div class="box-body table-responsive no-padding relative">
                    <table class="table table-hover table-bordered table-striped rlink-table">
                        <tbody>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>Country</th>
                                <th>Domain</th>
                                <th>Hosting Provider</th>
                                <th>Domain Provider</th>
                                <th>User</th>
                                <th>Total Spent</th>
                            </tr>
                            <tr v-for="(item, index) in intByHosting.data" :key="index">
                                <td class="center-content">{{ index + 1 }}</td>
                                <td>{{ item.country.name }}</td>
                                <td><a :href="'http://' + item.domain" target="_blank">{{ item.domain }}</a></td>
                                <td><a href="#">{{ item.hosting_provider.name }}</a></td>
                                <td><a href="#">{{ item.domain_provider.name }}</a></td>
                                <td>{{ item.user.name }}</td>
                                <td>{{ calculate(item.backlinks) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <pagination :data="intByHosting" @pagination-change-page="getIntByHosting($event)"></pagination>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    name: 'HostingList',
    data() {
        return {
            id: window.location.href.substring(window.location.href .lastIndexOf('/') + 1),
            page: this.$route.query.page || 1
        }
    },

    async created() {
        await this.$store.dispatch('actionCheckAdminCurrentUser', { vue: this });
        this.getHostingDetail()
        this.getIntByHosting()
    },

    computed: {
        ...mapState({
            hostingDetail: state => state.storeHosting.hostingDetail,
            intByHosting: state => state.storeIntDomain.intByHosting,
        }),
    },

    methods: {
        async getHostingDetail() {
            await this.$store.dispatch('getHostingDetail', {
                vue: this,
                id: this.id,
            });
        },
        async getIntByHosting(page) {
            if (page) {
                this.page = page
            }
            this.$router.push({
                query: {
                page: this.page,
                },
            })
            if (this.id.indexOf('?') > 0) {
                this.id = this.id.substring(0, this.id.indexOf('?'))
            }
            await this.$store.dispatch('getIntByHosting', {
                vue: this,
                id: this.id,
                page: this.page
            });
        },

        calculate(backlinks) {
            let totalSpent = 0
            if(backlinks) {
                backlinks.forEach(backlink => {
                    totalSpent += parseFloat(backlink.price)
                })
            }
            
            return totalSpent
        }
    }
}
</script>