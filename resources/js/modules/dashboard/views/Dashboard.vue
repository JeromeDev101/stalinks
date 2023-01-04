<template>
    <div>
        <div v-if="!isProcessing">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">{{ $t('message.dashboard.dashboard') }}</h1>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>

            <div class="row" v-if="!isExtWriter && !isBuyer && !isAffiliate && !isExtSeller">
                <div class="col-sm-12">
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title text-primary">{{ $t('message.dashboard.ted_title') }}</h3>
                            <div class="card-tools">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <!-- <div class="custom-box"> -->
                                    <div>
                                        <table class="table table-hover tbl-custom">
                                            <thead>
                                            <tr
                                                class="white text-center">
                                                <th>
                                                    {{ $t('message.dashboard.ted_in_charge') }}
                                                    <span class="text-primary">({{ ext_domain.total }})</span>
                                                </th>
                                                <th>
                                                    {{ $t('message.dashboard.ted_total') }}
                                                    <span class="text-primary">({{ ext_domain.num_total }})</span>
                                                </th>
                                                <th>
                                                    {{ $t('message.dashboard.ted_new') }}
                                                    <span class="text-primary">({{ ext_domain.num_new }})</span>
                                                </th>
                                                <th>
                                                    {{ $t('message.dashboard.ted_got_contacts') }}
                                                    <span class="text-primary">({{ ext_domain.num_got_contact }})</span>
                                                </th>
                                                <th>
                                                    {{ $t('message.dashboard.ted_contacted') }}
                                                    <span class="text-primary">({{ ext_domain.num_contacted }})</span>
                                                </th>
                                                <th>
                                                    {{ $t('message.dashboard.ted_contacted_form') }}
                                                    <span class="text-primary">
                                                        ({{ext_domain.num_contacted_via_form }})
                                                    </span>
                                                </th>
                                                <th>
                                                    {{ $t('message.dashboard.ted_intouch') }}
                                                    <span class="text-primary">({{ ext_domain.num_in_touched }})</span>
                                                </th>
                                                <th>
                                                    {{ $t('message.dashboard.ted_qualified') }}
                                                    <span class="text-primary">({{ ext_domain.num_qualified }})</span>
                                                </th>
                                                <th>
                                                    {{ $t('message.dashboard.ted_no_answer') }}
                                                    <span class="text-primary">({{ ext_domain.num_no_answer }})</span>
                                                </th>
                                                <th>
                                                    {{ $t('message.dashboard.ted_refused') }}
                                                    <span class="text-primary">({{ ext_domain.num_refused }})</span>
                                                </th>
                                                <th>
                                                    {{ $t('message.dashboard.ted_unqualified') }}
                                                    <span class="text-primary">({{ ext_domain.num_unqualified }})</span>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr
                                                v-for="(ext_domain, index) in listData.ext_domain"
                                                :key="index"
                                                class="text-right">
                                                <td class="text-center">
                                                    {{ upperCase(ext_domain.username) }}
                                                </td>
                                                <td>{{ ext_domain.num_total }}</td>
                                                <td>{{ ext_domain.num_new }}</td>
                                                <td>{{ ext_domain.num_got_contact }}</td>
                                                <td>{{ ext_domain.num_contacted }}</td>
                                                <td>{{ ext_domain.num_contacted_via_form }}</td>
                                                <td>{{ ext_domain.num_in_touched }}</td>
                                                <td>{{ ext_domain.num_qualified }}</td>
                                                <td>{{ ext_domain.num_no_answer }}</td>
                                                <td>{{ ext_domain.num_refused }}</td>
                                                <td>{{ ext_domain.num_unqualified }}</td>
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

            <div class="row" v-if="!isExtWriter && !isBuyer && !isAffiliate && !isExtSeller">
                <div class="col-sm-12">
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title text-primary">{{ $t('message.dashboard.ts_title') }}</h3>
                            <div class="card-tools">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mt-3">
                                <!-- <div class="col-md-" v-for="(in_charge, index) in listData.team_in_charge" :key="index" >
                                    <div class="small-box bg-aqua">
                                        <div class="inner">
                                            <p class="mb-0">Team In-charge</p>
                                            <p>{{ in_charge.username }}</p>
                                            <h3>{{ in_charge.total_seller }}</h3>
                                        </div>
                                    </div>
                                </div> -->

                                <div class="col-md-12">
                                    <table class="table table-hover tbl-custom">
                                        <thead>
                                        <tr class="white">
                                            <th>{{ $t('message.dashboard.ts_in_charge') }}</th>
                                            <th>{{ $t('message.dashboard.ts_total_seller') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(in_charge, index) in listData.team_in_charge" :key="index">
                                            <td>{{ in_charge.username }}</td>
                                            <td>{{ in_charge.total_seller }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" v-if="!isExtWriter && !isBuyer && !isAffiliate">
                <div class="col-sm-12">
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title text-primary">{{ $t('message.dashboard.sl_title') }}</h3>
                            <div class="card-tools">
                            </div>
                        </div>
                        <div class="card-body custom-box">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-hover tbl-custom">
                                        <thead>
                                        <tr class="white">
                                            <th>{{ $t('message.dashboard.sl_in_charge') }}</th>
                                            <th>
                                                {{ $t('message.dashboard.sl_seller') }}
                                                <span class="text-primary">({{ total_seller.total }})</span>
                                            </th>
                                            <th>
                                                {{ $t('message.dashboard.sl_sites') }}
                                                <span class="text-primary">({{ total_seller.num_sites }})
                                                </span>
                                            </th>
                                            <th>
                                                {{ $t('message.dashboard.sl_valid') }}
                                                <span class="text-primary">({{ total_seller.num_valid }})</span>
                                            </th>
                                            <th>
                                                {{ $t('message.dashboard.sl_unchecked') }}
                                                <span class="text-primary">({{ total_seller.num_unchecked }})</span></th>
                                            <th>
                                                {{ $t('message.dashboard.sl_invalid') }}
                                                <span class="text-primary">({{ total_seller.num_invalid }})</span>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(seller, index) in listData.total_seller" :key="index">
                                            <td>
                                                {{
                                                    seller.in_charge == null
                                                        ? upperCase(seller.username)
                                                        : upperCase(seller.in_charge)
                                                }}
                                            </td>
                                            <td>{{ upperCase(seller.username) }}</td>
                                            <td>{{ seller.num_sites }}</td>
                                            <td>{{ seller.num_valid }}</td>
                                            <td>{{ seller.num_unchecked }}</td>
                                            <td>{{ seller.num_invalid }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" v-if="!isBuyer && !isAffiliate">
                <div class="col-sm-12">
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title text-primary" v-if="!isExtWriter">
                                {{ $t('message.dashboard.tbs_title') }}
                            </h3>
                            <h3 class="card-title text-primary" v-if="isExtWriter">
                                {{ $t('message.dashboard.tbs_article_sum') }}
                            </h3>
                            <div class="card-tools">
                            </div>
                        </div>
                        <div class="card-body custom-box">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-hover tbl-custom">
                                        <thead>
                                            <tr>
                                                <th v-if="!isExtWriter">
                                                    {{ $t('message.dashboard.tbs_seller') }}
                                                    <span class="text-primary">({{ backlink_seller.total }})</span>
                                                </th>
                                                <th v-if="!isExtWriter">
                                                    {{ $t('message.dashboard.tbs_total') }}
                                                    <span class="text-primary">({{ backlink_seller.num_total }})</span>
                                                </th>
                                                <th>
                                                    {{ $t('message.dashboard.tbs_processing') }}
                                                    <span class="text-primary">({{ backlink_seller.num_processing }})</span>
                                                </th>
                                                <th>
                                                    {{ $t('message.dashboard.tbs_content_writing') }}
                                                    <span class="text-primary">({{ backlink_seller.writing }})</span>
                                                </th>
                                                <th>
                                                    {{ $t('message.dashboard.tbs_content_done') }}
                                                    <span class="text-primary">({{ backlink_seller.num_done }})</span>
                                                </th>
                                                <th>
                                                    {{ $t('message.dashboard.tbs_content_sent') }}
                                                    <span class="text-primary">({{ backlink_seller.num_sent }})</span>
                                                </th>
                                                <th>
                                                    {{ $t('message.dashboard.tbs_issue') }}
                                                    <span class="text-primary">({{ backlink_seller.num_issue }})</span>
                                                </th>
                                                <th>
                                                    {{ $t('message.dashboard.tbs_lip') }}
                                                    <span class="text-primary">
                                                        ({{ backlink_seller.num_live_in_process }})
                                                    </span>
                                                </th>
                                                <th>
                                                    {{ $t('message.dashboard.tbs_live') }}
                                                    <span class="text-primary">({{ backlink_seller.num_live }})</span>
                                                </th>
                                                <th>
                                                    {{ $t('message.dashboard.tbs_cancelled') }}
                                                    <span class="text-primary">({{ backlink_seller.num_canceled }})</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-if="!isExtWriter" v-for="(seller, index) in listData.backlink_seller" :key="index">
                                                <td v-if="!isExtWriter">{{ upperCase(seller.username) }}</td>
                                                <td v-if="!isExtWriter">{{ seller.num_total }}</td>
                                                <td>{{ seller.num_processing }}</td>
                                                <td>{{ seller.writing }}</td>
                                                <td>{{ seller.num_done }}</td>
                                                <td>{{ seller.num_sent }}</td>
                                                <td>{{ seller.num_issue }}</td>
                                                <td>{{ seller.num_live_in_process }}</td>
                                                <td>{{ seller.num_live }}</td>
                                                <td>{{ seller.num_canceled }}</td>
                                            </tr>
                                            <tr v-if="isExtWriter">
                                                <td>{{ extWriterTotals.num_processing }}</td>
                                                <td>{{ extWriterTotals.writing }}</td>
                                                <td>{{ extWriterTotals.num_done }}</td>
                                                <td>{{ extWriterTotals.num_sent }}</td>
                                                <td>{{ extWriterTotals.num_issue }}</td>
                                                <td>{{ extWriterTotals.num_live_in_process }}</td>
                                                <td>{{ extWriterTotals.num_live }}</td>
                                                <td>{{ extWriterTotals.num_canceled }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" v-if="!isExtWriter && !isBuyer && !isAffiliate">
                <div class="col-sm-12">
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title text-primary">{{ $t('message.dashboard.inc_title') }}</h3>
                            <div class="card-tools">
                            </div>
                        </div>
                        <div class="card-body custom-box">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-hover tbl-custom">
                                        <thead>
                                        <tr>
                                            <th>
                                                {{ $t('message.dashboard.inc_seller') }}
                                                <span class="text-primary">({{ incomes.total }})</span>
                                            </th>
                                            <th>
                                                {{ $t('message.dashboard.inc_backlinks') }}
                                                <span class="text-primary">({{ incomes.num_backlink }})</span>
                                            </th>
                                            <th>
                                                {{ $t('message.dashboard.inc_unpaid') }}
                                                <span class="text-primary">({{ incomes.num_unpaid }})</span>
                                            </th>
                                            <th>
                                                {{ $t('message.dashboard.inc_paid') }}
                                                <span class="text-primary">({{ incomes.num_paid }})</span>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(incomes, index) in listData.incomes" :key="index">
                                            <td>{{ upperCase(incomes.username) }}</td>
                                            <td>{{ incomes.num_backlink }}</td>
                                            <td>{{ incomes.num_unpaid }}</td>
                                            <td>{{ incomes.num_paid }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" v-if="!isExtWriter && !isExtSeller">
                <div class="col-sm-12">
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title text-primary">{{ $t('message.dashboard.lb_title') }}</h3>
                            <div class="card-tools">
                            </div>
                        </div>
                        <div class="card-body custom-box">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-hover tbl-custom">
                                        <thead>
                                        <tr>
                                            <th>
                                                {{ $t('message.dashboard.lb_buyer') }}
                                                <span v-if="user.isAdmin" class="text-primary">
                                                    ({{ backlink_to_buy.total }})
                                                </span>
                                            </th>
                                            <!--                                        <th>New <span v-if="user.isAdmin" class="text-primary">({{ backlink_to_buy.num_new }})</span></th>-->
                                            <th>{{ $t('message.dashboard.lb_new') }}</th>
                                            <!--                                        <th>Interested <span v-if="user.isAdmin" class="text-primary">({{ backlink_to_buy.num_interested }})</span></th>-->
                                            <th>{{ $t('message.dashboard.lb_interested') }}</th>
                                            <!--                                        <th>Purchased <span v-if="user.isAdmin" class="text-primary">({{ backlink_to_buy.num_purchased }})</span></th>-->
                                            <th>{{ $t('message.dashboard.lb_purchased') }}</th>
                                            <!--                                        <th>Not Interested <span v-if="user.isAdmin" class="text-primary">({{ backlink_to_buy.num_not_interested }})</span></th>-->
                                            <th>{{ $t('message.dashboard.lb_not_interested') }}</th>
                                            <!--                                        <th>Total <span v-if="user.isAdmin" class="text-primary">({{ backlink_to_buy.num_total }})</span></th>-->
                                            <th>{{ $t('message.dashboard.lb_total') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(buy, index) in listData.backlink_to_buy" :key="index">
                                            <td>
                                                <span v-if="isAffiliate">
                                                    {{ upperCase(buy.name) }}
                                                </span>

                                                <span v-else>
                                                    {{ upperCase(buy.username) }}
                                                </span>
                                            </td>
                                            <td>{{ buy.num_new }}</td>
                                            <td>{{ buy.num_interested }}</td>
                                            <td>{{ buy.num_purchased }}</td>
                                            <td>{{ buy.num_not_interested }}</td>
                                            <td>{{ buy.num_total }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" v-if="!isExtWriter && !isExtSeller">
                <div class="col-sm-12">
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title text-primary">{{ $t('message.dashboard.tbb_title') }}</h3>
                            <div class="card-tools">
                            </div>
                        </div>
                        <div class="card-body custom-box">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-hover tbl-custom">
                                        <thead>
                                        <tr>
                                            <th>
                                                {{ $t('message.dashboard.tbb_buyer') }}
                                                <span class="text-primary">({{ backlink_buyer.total }})</span>
                                            </th>
                                            <th>
                                                {{ $t('message.dashboard.tbs_total') }}
                                                <span class="text-primary">({{ backlink_buyer.num_total }})</span>
                                            </th>
                                            <th>
                                                {{ $t('message.dashboard.tbs_processing') }}
                                                <span class="text-primary">({{ backlink_buyer.num_processing }})</span>
                                            </th>
                                            <th>
                                                {{ $t('message.dashboard.tbs_content_writing') }}
                                                <span class="text-primary">({{ backlink_buyer.writing }})</span>
                                            </th>
                                            <th>
                                                {{ $t('message.dashboard.tbs_content_done') }}
                                                <span class="text-primary">({{ backlink_buyer.num_done }})</span>
                                            </th>
                                            <th>
                                                {{ $t('message.dashboard.tbs_content_sent') }}
                                                <span class="text-primary">({{ backlink_buyer.num_sent }})</span>
                                            </th>
                                            <th>
                                                {{ $t('message.dashboard.tbs_issue') }}
                                                <span class="text-primary">({{ backlink_buyer.num_issue }})</span>
                                            </th>
                                            <th>
                                                {{ $t('message.dashboard.tbs_lip') }}
                                                <span class="text-primary">({{ backlink_buyer.num_live_in_process }})</span>
                                            </th>
                                            <th>
                                                {{ $t('message.dashboard.tbs_live') }}
                                                <span class="text-primary">({{ backlink_buyer.num_live }})</span>
                                            </th>
                                            <th>
                                                {{ $t('message.dashboard.tbs_cancelled') }}
                                                <span class="text-primary">({{ backlink_buyer.num_canceled }})</span>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(buyer, index) in listData.backlink_buyer" :key="index">
                                            <td>
                                                <span v-if="isAffiliate">
                                                    {{ upperCase(buyer.name) }}
                                                </span>

                                                <span v-else>
                                                    <span class="text-primary" v-if="buyer.is_sub_account == 1">
                                                        {{ buyer.under_name != null ?  '['+ upperCase(buyer.under_name)+']':''}}
                                                    </span>
                                                    {{ upperCase(buyer.username) }}
                                                </span>
                                            </td>
                                            <td>{{ buyer.num_total }}</td>
                                            <td>{{ buyer.num_processing }}</td>
                                            <td>{{ buyer.writing }}</td>
                                            <td>{{ buyer.num_done }}</td>
                                            <td>{{ buyer.num_sent }}</td>
                                            <td>{{ buyer.num_issue }}</td>
                                            <td>{{ buyer.num_live_in_process }}</td>
                                            <td>{{ buyer.num_live }}</td>
                                            <td>{{ buyer.num_canceled }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" v-if="!isExtWriter && !isExtSeller">
                <div class="col-sm-12">
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title text-primary">{{ $t('message.dashboard.p_title') }}</h3>
                            <div class="card-tools">
                            </div>
                        </div>
                        <div class="card-body custom-box">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-hover tbl-custom">
                                        <thead>
                                        <tr>
                                            <th>
                                                {{ $t('message.dashboard.p_buyer') }}
                                                <span class="text-primary">({{ purchase.total }})</span>
                                            </th>
                                            <th>
                                                {{ $t('message.dashboard.p_backlinks') }}
                                                <span class="text-primary">({{ purchase.num_backlink }})</span>
                                            </th>
                                            <th>
                                                {{ $t('message.dashboard.p_unpaid') }}
                                                <span class="text-primary">({{ purchase.num_unpaid }})</span>
                                            </th>
                                            <th>
                                                {{ $t('message.dashboard.p_paid') }}
                                                <span class="text-primary">({{ purchase.num_paid }})</span>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(purchase, index) in listData.purchase" :key="index">
                                            <td>
                                                <span v-if="isAffiliate">
                                                    {{ upperCase(purchase.name) }}
                                                </span>

                                                <span v-else>
                                                    <span class="text-primary" v-if="purchase.is_sub_account == 1">
                                                        {{ purchase.under_name != null ?  '['+ upperCase(purchase.under_name)+']':''}}
                                                    </span>
                                                    {{ upperCase(purchase.username) }}
                                                </span>
                                            </td>
                                            <td>{{ purchase.num_backlink }}</td>
                                            <td>{{ purchase.num_unpaid }}</td>
                                            <td>{{ purchase.num_paid }}</td>
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

        <div v-if="isProcessing">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Dashboard</h1>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-info alert-dismissible fade show m-3" role="alert">
                {{ $t('message.dashboard.tp_reminder') }}
            </div>
        </div>

    </div>

</template>

<script>
import {mapState} from 'vuex';
import config from '@/config';
import Hepler from '@/library/Helper';
import LineChart from '@/components/chart/Line.js'

export default {

    name : 'Dashboard',
    components : {
        LineChart
    },
    data() {
        return {
            total_seller : {
                total : 0,
                num_sites : 0,
                num_unchecked : 0,
                num_valid : 0,
                num_invalid : 0,
            },

            incomes : {
                total : 0,
                num_backlink : 0,
                num_unpaid : 0,
                num_paid : 0,
            },

            purchase : {
                total : 0,
                num_backlink : 0,
                num_unpaid : 0,
                num_paid : 0,
            },

            backlink_seller : {
                total : 0,
                num_total : 0,
                num_processing : 0,
                writing : 0,
                num_done : 0,
                num_sent : 0,
                num_live : 0,
                num_live_in_process : 0,
                num_issue : 0,
                num_canceled : 0,
            },

            backlink_buyer : {
                total : 0,
                num_processing : 0,
                writing : 0,
                num_done : 0,
                num_sent : 0,
                num_live : 0,
                num_live_in_process : 0,
                num_total : 0,
                num_issue : 0,
                num_canceled : 0,
            },

            ext_domain : {
                total : 0,
                num_total : 0,
                num_new : 0,
                num_in_touched : 0,
                num_qualified : 0,
                num_unqualified : 0,
                num_got_contact : 0,
                num_contacted : 0,
                num_contacted_via_form : 0,
                num_no_answer : 0,
                num_refused : 0,
            },

            backlink_to_buy : {
                total : 0,
                username : 0,
                num_new : 0,
                num_not_interested : 0,
                num_interested : 0,
                num_purchased : 0,
                num_total : 0,
            },

        };
    },

    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,
            listData : state => state.storeDashboard.listData,
        }),

        isProcessing() {
            let result = false;

            if(this.user.isOurs === 0) {
                result = false;
            } else {
                if(this.user.registration != null) {
                    if(this.user.registration.account_validation === 'processing') {
                        result = true;
                    }
                } else {
                    result = false;
                }
            }

            return result;
        },

        isExtWriter() {
            let result = false;

            if(this.user.isOurs === 1) {
                if(this.user.role_id === 4) {
                    result = true;
                }
            }

            return result;
        },

        isExtSeller () {
            return this.user.isOurs === 1 && this.user.role_id === 6;
        },

        isBuyer() {
            return [5, 14].includes(this.user.role_id)
        },

        isAffiliate() {
            return this.user.role_id === 11;
        },

        extWriterTotals () {
            let newValue = [];

            if (this.user.role_id === 4) {
                if (Object.keys(this.listData).length !== 0) {
                    if (this.listData.backlink_seller.length) {
                        newValue = this.listData.backlink_seller.reduce(function(previousValue, currentValue) {
                            return {
                                num_canceled: Number(previousValue.num_canceled) + Number(currentValue.num_canceled),
                                num_done: Number(previousValue.num_done) + Number(currentValue.num_done),
                                num_issue: Number(previousValue.num_issue) + Number(currentValue.num_issue),
                                num_live: Number(previousValue.num_live) + Number(currentValue.num_live),
                                num_live_in_process: Number(previousValue.num_live_in_process) + Number(currentValue.num_live_in_process),
                                num_processing: Number(previousValue.num_processing) + Number(currentValue.num_processing),
                                num_sent: Number(previousValue.num_sent) + Number(currentValue.num_sent),
                                num_total: Number(previousValue.num_total) + Number(currentValue.num_total),
                                writing: Number(previousValue.writing) + Number(currentValue.writing),
                            }
                        });
                    }
                }
            }

            return newValue;
        },
    },

    async created() {
        await this.$store.dispatch('actionCheckAdminCurrentUser', {vue : this});
        let userId = this.$route.params.id || '';
        this.getData();
    },

    methods : {
        upperCase(string) {
            return (string == null || string == '') ? '-' : string.toUpperCase();
        },

        async getData() {
            await this.$store.dispatch('actionGetData');

            $(".tbl-custom").DataTable({
                "bPaginate" : false,
                "bLengthChange" : false,
                "bFilter" : false,
                "bInfo" : false,
                "bAutoWidth" : false,
            })

            this.displayTotal();
        },

        displayTotal() {
            let total_seller = this.listData.total_seller
            let incomes = this.listData.incomes
            let purchase = this.listData.purchase
            let backlink_seller = this.listData.backlink_seller
            let backlink_buyer = this.listData.backlink_buyer
            let ext_domain = this.listData.ext_domain
            let backlink_to_buy = this.listData.backlink_to_buy
            let total_seller_ctr = 0;
            let incomes_ctr = 0;
            let purchase_ctr = 0;
            let backlink_seller_ctr = 0;
            let backlink_buyer_ctr = 0;
            let ext_domain_ctr = 0;
            let backlink_to_buy_ctr = 0;

            for (var index in total_seller) {
                this.total_seller.num_sites += parseInt(total_seller[index].num_sites);
                this.total_seller.num_unchecked += parseInt(total_seller[index].num_unchecked);
                this.total_seller.num_valid += parseInt(total_seller[index].num_valid);
                this.total_seller.num_invalid += parseInt(total_seller[index].num_invalid);
                total_seller_ctr++;
            }
            this.total_seller.total = total_seller_ctr;

            for (var index in incomes) {
                this.incomes.num_backlink += parseInt(incomes[index].num_backlink);
                this.incomes.num_unpaid += parseInt(incomes[index].num_unpaid);
                this.incomes.num_paid += parseInt(incomes[index].num_paid);
                incomes_ctr++;
            }
            this.incomes.total = incomes_ctr;

            for (var index in purchase) {
                this.purchase.num_backlink += parseInt(purchase[index].num_backlink);
                this.purchase.num_unpaid += parseInt(purchase[index].num_unpaid);
                this.purchase.num_paid += parseInt(purchase[index].num_paid);
                purchase_ctr++;
            }
            this.purchase.total = purchase_ctr;

            for (var index in backlink_seller) {
                this.backlink_seller.num_processing += parseInt(backlink_seller[index].num_processing);
                this.backlink_seller.writing += parseInt(backlink_seller[index].writing);
                this.backlink_seller.num_done += parseInt(backlink_seller[index].num_done);
                this.backlink_seller.num_sent += parseInt(backlink_seller[index].num_sent);
                this.backlink_seller.num_live += parseInt(backlink_seller[index].num_live);
                this.backlink_seller.num_live_in_process += parseInt(backlink_seller[index].num_live_in_process);
                this.backlink_seller.num_issue += parseInt(backlink_seller[index].num_issue);
                this.backlink_seller.num_canceled += parseInt(backlink_seller[index].num_canceled);
                this.backlink_seller.num_total += parseInt(backlink_seller[index].num_total);
                backlink_seller_ctr++;
            }
            this.backlink_seller.total = backlink_seller_ctr;

            for (var index in backlink_buyer) {
                this.backlink_buyer.num_processing += parseInt(backlink_buyer[index].num_processing);
                this.backlink_buyer.writing += parseInt(backlink_buyer[index].writing);
                this.backlink_buyer.num_done += parseInt(backlink_buyer[index].num_done);
                this.backlink_buyer.num_sent += parseInt(backlink_buyer[index].num_sent);
                this.backlink_buyer.num_live += parseInt(backlink_buyer[index].num_live);
                this.backlink_buyer.num_live_in_process += parseInt(backlink_buyer[index].num_live_in_process);
                this.backlink_buyer.num_issue += parseInt(backlink_buyer[index].num_issue);
                this.backlink_buyer.num_canceled += parseInt(backlink_buyer[index].num_canceled);
                this.backlink_buyer.num_total += parseInt(backlink_buyer[index].num_total);
                backlink_buyer_ctr++;
            }
            this.backlink_buyer.total = backlink_buyer_ctr;

            for (var index in ext_domain) {
                this.ext_domain.num_new += parseInt(ext_domain[index].num_new);
                this.ext_domain.num_in_touched += parseInt(ext_domain[index].num_in_touched);
                this.ext_domain.num_qualified += parseInt(ext_domain[index].num_qualified);
                this.ext_domain.num_unqualified += parseInt(ext_domain[index].num_unqualified);
                this.ext_domain.num_total += parseInt(ext_domain[index].num_total);
                this.ext_domain.num_got_contact += parseInt(ext_domain[index].num_got_contact);
                this.ext_domain.num_contacted += parseInt(ext_domain[index].num_contacted);
                this.ext_domain.num_contacted_via_form += parseInt(ext_domain[index].num_contacted_via_form);
                this.ext_domain.num_no_answer += parseInt(ext_domain[index].num_no_answer);
                this.ext_domain.num_refused += parseInt(ext_domain[index].num_refused);
                ext_domain_ctr++;
            }
            this.ext_domain.total = ext_domain_ctr;

            for (var index in backlink_to_buy) {
                this.backlink_to_buy.num_new += parseInt(backlink_to_buy[index].num_new);
                this.backlink_to_buy.num_not_interested += parseInt(backlink_to_buy[index].num_not_interested);
                this.backlink_to_buy.num_interested += parseInt(backlink_to_buy[index].num_interested);
                this.backlink_to_buy.num_purchased += parseInt(backlink_to_buy[index].num_purchased);
                this.backlink_to_buy.num_total += parseInt(backlink_to_buy[index].num_total);
                backlink_to_buy_ctr++;
            }
            this.backlink_to_buy.total = backlink_to_buy_ctr;

        },

        convertPrice(price) {
            return parseFloat(price).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }
    }
}
</script>

<style scoped>
.avatar li {
    width: 100%;
}

.custom-box {
    height: 330px;
    overflow-y: auto;
    overflow-x: hidden;
    padding-top: 0px;
    margin-bottom: 0.5em;
}

.tbl-custom {
    text-align: left;
    position: relative;
    border-collapse: collapse;
}

.tbl-custom thead tr th, .tbl-custom tbody tr td {
    padding: 0.8rem;
}

.tbl-custom thead tr th {
    background: white no-repeat center right;
    position: sticky;
    top: 0;
    box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
}

</style>
