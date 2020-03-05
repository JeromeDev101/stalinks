<template>
    <div class="row">
    <section class="content-header col-sm-12">
      <h1>Dashboard</h1>
      <br>
    </section>
        <div class="col-lg-8">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Total External Domain: {{ totalExt.total}}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                                    <thead>
                                        <tr role="row">
                                            <th>Country</th>
                                            <th>GotContacts</th>
                                            <th>Ahrefed</th>
                                            <th>InTouched</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr role="row" class="odd" v-for="(ext, index) in totalExt.data" :key="index">
                                            <td>{{ ext.country.name }}</td>
                                            <td>{{ ext.GotContacts }}</td>
                                            <td>{{ ext.Ahreafed }}</td>
                                            <td>{{ ext.InTouched }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="col-lg-4">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Total Internals Domain: {{ totalInt.total }}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                                    <thead>
                                        <tr role="row">
                                            <th>Country</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(int, index) in totalInt.data" :key="index" role="row" class="odd">
                                            <td>{{ int.country.name }}</td>
                                            <td>{{ int.total }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
        <div class="col-lg-8">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Total Backlink: {{ totalBackLink.total}} </h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                                    <thead>
                                        <tr role="row">
                                            <th>Country</th>
                                            <th>Processing</th>
                                            <th>Live</th>
                                            <th>Content Writing</th>
                                            <th>Content sent</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(backlink, index) in totalBackLink.data" :key="index" role="row" class="odd">
                                            <td>{{ backlink.country.name}}</td>
                                            <td>{{ backlink['Processing'] }}</td>
                                            <td>{{ backlink['Live'] }}</td>
                                            <td>{{ backlink['Content Writing'] }}</td>
                                            <td>{{ backlink['Content sent'] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div class="col-lg-4">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Total Price: {{ price.total }}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                                    <thead>
                                        <tr role="row">
                                            <th>Country</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(price, index) in totalPrice.data" :key="index" role="row" class="odd">
                                            <td>{{ price.country.name }}</td>
                                            <td>{{ convertPrice(price.total) }}$</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import config from '@/config';
import Hepler from '@/library/Helper';

export default {
    name: 'Dashboard',

    data() {
        return {
            defaultAvatar: config.avatar_url,
            extDomain:{
                status: 0,
                country_id: 0,
                country_id_of_user: [],
                status_of_user: [],
                employee_id: 0,
            },
            intDomain:{
                country_id: 0,
                country_id_of_user: [],
                employee_id: 0,
            },
            backLink:{
                country_id: 0,
                country_id_of_user: [],
                status: 0,
                status_of_backlink: [],
                employee_id: 0,
            },
            price:{
                country_id: 0,
                country_id_of_user: [],
                employee_id: 0,
            },
            optionStatus: [
                { text: 'Please select', value: 0 },
                { text: 'New', value: 'new' },
                { text: 'Crawl Failed', value: 'crawlfail' },
                { text: 'Contacts Null', value: 'contactnull' },
            ]
        };
    },

    computed: {
        ...mapState({
            user: state => state.storeAuth.currentUser,
            totalExt: state => state.storeExtDomain.totalExtDomain,
            totalInt: state => state.storeIntDomain.totalIntDomain,
            totalBackLink: state => state.storeBackLink.totalBackLink,
            totalPrice: state => state.storeBackLink.totalPrice,
        }),
    },

    async created() {
        await this.$store.dispatch('actionCheckAdminCurrentUser', { vue: this });
        let userId = this.$route.params.id || '';
        this.filterExtDomain();
        this.filterIntDomain();
        this.filterBacklink();
        this.filterPrice();
    },

methods: {
        async filterExtDomain() {
            let that = this;
            that.extDomain.country_id_of_user = [],
            that.extDomain.status_of_user = [],
            this.user.countries_accessable.forEach(function(country) {
                that.extDomain.country_id_of_user.push(country.id)
            });
            that.extDomain.employee_id = that.user.id;
            await this.$store.dispatch('filterExtDomain', {
                vue: this,
                params: this.extDomain,
            });
        },

        async filterIntDomain() {
            let that = this;
            that.intDomain.country_id_of_user = [],

            this.user.internal_domains_accessable.forEach(function(internal_domain) {
                that.intDomain.country_id_of_user.push(internal_domain.country.id)
            });
            that.intDomain.employee_id = that.user.id;
            await this.$store.dispatch('filterIntDomain', {
                vue: this,
                params: this.intDomain,
            });
        },

        async filterBacklink() {
            let that = this;
            that.backLink.country_id_of_user = [],
            that.backLink.status_of_backlink = [],

            this.user.backlinks.forEach(function(backlink) {
                that.backLink.status_of_backlink.push(backlink.status)
                that.backLink.country_id_of_user.push(backlink.int_domain.country.id)
            });
            that.backLink.employee_id = that.user.id;
            await this.$store.dispatch('filterBackLink', {
                vue: this,
                params: this.backLink,
            });
        },

        async filterPrice() {
            let that = this;
            that.price.country_id_of_user = [],

            this.user.backlinks.forEach(function(backlink) {
                that.price.country_id_of_user.push(backlink.int_domain.country.id)
            });
            that.price.employee_id = that.user.id;
            await this.$store.dispatch('filterPrice', {
                vue: this,
                params: this.price,
            });
        },

        checkArray(array) {
            return Hepler.arrayNotEmpty(array);
        },

        convertPrice(price) {
            return parseFloat(price).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }
    }
}
</script>

<style>
    .avatar li{
        width: 100%;
    }
    .table-user {
        padding-bottom: 73px;
    }
</style>