<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"></h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- VIEW FOR ADMIN -->
        <div v-if="user.isAdmin" class="row">
            <!-- Affiliates -->
            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">
                            Affiliate Users
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row px-3 mb-2">
                            <div class="col-6 px-0 align-self-center">
                                <b>
                                    {{ $t('message.others.table_entries', { from: affiliates.from, to: affiliates.to, end: affiliates.total }) }}
                                </b>
                            </div>
                        </div>

                        <div class="row px-3">
                            <div class="table-responsive mb-2">
                                <table
                                    id="tbl_affiliates"
                                    class="table table-hover rlink-table">

                                    <thead>
                                        <tr>
                                            <th>ID #</th>
                                            <th>User Details</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="(affiliate, index) in affiliates.data" :key="index">
                                            <td>
                                                <div>
                                                    <span class="badge badge-success">
                                                        {{ affiliate.id }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <small>
                                                        <i class="fas fa-calendar-check"></i>
                                                        {{ affiliate.created_at }}
                                                    </small>
                                                </div>
                                            </td>

                                            <td>
                                                <p class="mb-1 font-weight-bold">
                                                    {{ affiliate.username }}
                                                </p>

                                                <div>
                                                    <small class="text-uppercase badge badge-info">
                                                        name:
                                                    </small>
                                                    <small>{{ affiliate.name }}</small>
                                                </div>

                                                <div>
                                                    <small class="text-uppercase badge badge-info">
                                                        email:
                                                    </small>
                                                    <small>{{ affiliate.email }}</small>
                                                </div>
                                            </td>

                                            <td class="align-middle">
                                                <button
                                                    class="btn btn-default"
                                                    
                                                    @click="viewAffiliateUserCampaigns(affiliate.id, 
                                                        affiliate.username 
                                                        ? affiliate.username 
                                                        : affiliate.name
                                                    )">

                                                    <i class="fas fa-bullhorn"></i>
                                                    View Campaigns
                                                    <span class="badge badge-primary">
                                                        {{ affiliate.affiliate_codes.length }}
                                                    </span>
                                                </button>

                                                <button 
                                                    class="btn btn-default" 
                                                    
                                                    @click="viewAffiliateUserBuyers(affiliate.id, 
                                                        affiliate.username 
                                                        ? affiliate.username 
                                                        : affiliate.name
                                                    )">
                                                    
                                                    <i class="fas fa-people-arrows"></i>
                                                    View Buyers
                                                    <span class="badge badge-primary">
                                                        {{ affiliate.affiliate_buyers_count }}
                                                    </span>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <pagination
                                :data="affiliates"
                                :limit="8"

                                @pagination-change-page="getAllAffiliates">

                            </pagination>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Campaigns -->
            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">
                            Affiliate Campaigns
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row px-3 mb-2">
                            <div class="col-6 px-0 align-self-center">
                                <b>
                                    {{ $t('message.others.table_entries', { from: affiliateCampaigns.from, to: affiliateCampaigns.to, end: affiliateCampaigns.total }) }}
                                </b>
                            </div>
                        </div>

                        <div class="row px-3">
                            <div class="table-responsive mb-2">
                                <table
                                    id="tbl_affiliate_campaigns"
                                    class="table table-hover rlink-table">
                                    <thead>
                                        <tr>
                                            <th>Campaign</th>
                                            <th>Affiliate</th>
                                            <th>Code</th>
                                            <th>URL</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="(campaign, index) in affiliateCampaigns.data" :key="index">
                                            <td>
                                                <span v-if="campaign.name" class="badge badge-success">
                                                    {{ campaign.name }}
                                                </span>

                                                <span v-else class="badge badge-secondary">
                                                    N/A
                                                </span>

                                                <br>

                                                <small>ID # {{ campaign.id }}</small>
                                            </td>

                                            <td>
                                                <p class="mb-1 font-weight-bold">
                                                    {{ campaign.user.username }}
                                                </p>

                                                <div>
                                                    <i class="fas fa-id-card-alt text-info"></i>
                                                    <small>{{ campaign.user.name }}</small>
                                                </div>

                                                <div>
                                                    <i class="fas fa-at text-info"></i>
                                                    <small>{{ campaign.user.email }}</small>
                                                </div>

                                                <div>
                                                    <small>
                                                        <i class="fas fa-calendar-plus fa-lg text-info"></i>
                                                        {{ campaign.user.created_at }}
                                                    </small>
                                                </div>
                                            </td>

                                            <td>
                                                <button class="btn btn-default mr-2" @click="copyText('admin-affiliate-code-' + campaign.affiliate_code)">
                                                    <i class="fas fa-clipboard"></i>
                                                </button>

                                                <span :ref="'admin-affiliate-code-' + campaign.affiliate_code">
                                                    {{ campaign.affiliate_code }}
                                                </span>
                                            </td>

                                            <td>
                                                <button class="btn btn-default mr-2" @click="copyText('admin-code-link-' + campaign.affiliate_code)">
                                                    <i class="fas fa-clipboard"></i>
                                                </button>
        
                                                <span :ref="'admin-code-link-' + campaign.affiliate_code">
                                                    {{ 'https://tools.stalinks.com/registration?type=Buyer&code=' + campaign.affiliate_code }}
                                                </span>
                                            </td>

                                            <td>
                                                <button 
                                                    class="btn btn-default"
                                                    
                                                    @click="viewAffiliateCampaignBuyers(campaign.id, 
                                                        campaign.user.username 
                                                        ? campaign.user.username 
                                                        : campaign.user.name
                                                    )">

                                                    <i class="fas fa-users"></i>
                                                    View Buyers
                                                    <span class="badge badge-primary">{{ campaign.buyers_count }}</span>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <pagination
                                :data="affiliateCampaigns"
                                :limit="8"

                                @pagination-change-page="getAllAffiliateCampaigns">

                            </pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- VIEW FOR EXTERNAL AFFILIATES -->
        <div v-if="user.isOurs === 1" class="row">
            <!-- Affiliate Codes -->
            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-2">
                                <h3 class="card-title text-primary">
                                    {{ $t('message.profile.ac_title') }}
                                </h3>
                            </div>
                            <div class="col-10">
                                <div class="alert alert-info mb-0 w-100">
                                    <i class="fa fa-info-circle"></i>
                                    {{ $t('message.profile.ac_note') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2 align-items-center">
                            <div class="col-6">
                                <span v-if="affiliateCodes.total" class="pagination-custom-footer-text m-0 mt-2 font-weight-bold">
                                    {{ $t('message.others.table_entries', {
                                        from: affiliateCodes.from,
                                        to: affiliateCodes.to,
                                        end: affiliateCodes.total
                                    }) }}
                                </span>
                            </div>
                            <div class="col-6">
                                <button
                                    v-if="user.permission_list.includes('create-campaign-setup-campaign-setup')"
                                    type="button"
                                    class="btn btn-success float-right"
                                    data-toggle="modal"
                                    data-target="#modal-generate-affiliate-code">

                                    {{ $t('message.profile.ac_generate') }}
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="tbl_affiliate_codes" class="table table-condensed table-hover table-bordered">
                                <thead>
                                    <tr class="label-primary">
                                        <th>ID</th>
                                        <th>{{ $t('message.profile.p_name') }}</th>
                                        <th>Code</th>
                                        <th>URL</th>
                                    </tr>
                                </thead>

                                <tbody>
                                <tr v-for="(code, index) in affiliateCodes.data" :key="index">
                                    <td>{{ code.id }}</td>
                                    <td>
                                        <span :class="!code.name ? 'badge badge-danger' : ''">
                                            {{ code.name ? code.name : 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-default mr-2" @click="copyText('affiliate-code-' + code.affiliate_code)">
                                            <i class="fas fa-clipboard"></i>
                                        </button>

                                        <span :ref="'affiliate-code-' + code.affiliate_code">
                                            {{ code.affiliate_code }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-default mr-2" @click="copyText('code-link-' + code.affiliate_code)">
                                            <i class="fas fa-clipboard"></i>
                                        </button>

                                        <span :ref="'code-link-' + code.affiliate_code">
                                            {{ 'https://tools.stalinks.com/registration?type=Buyer&code=' + code.affiliate_code }}
                                        </span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <pagination
                                :limit="8"
                                :data="affiliateCodes"

                                @pagination-change-page="getAffiliateCodes">

                            </pagination>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Affiliate Buyers -->
            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">
                            {{ $t('message.profile.ac_affiliate_buyers') }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="tbl_affiliate_buyers" class="table table-condensed table-hover table-bordered">
                                        <thead>
                                        <tr class="label-primary">
                                            <th>Affiliate Code</th>
                                            <th>{{ $t('message.profile.ac_user_id') }}</th>
                                            <th>{{ $t('message.profile.ac_date_registered') }}</th>
                                            <th>{{ $t('message.profile.p_email') }}</th>
                                            <th>{{ $t('message.profile.p_name') }}</th>
                                            <th>{{ $t('message.profile.p_company_type') }}</th>
                                            <th>{{ $t('message.profile.ac_account_validation') }}</th>
                                            <th>{{ $t('message.profile.ac_status') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(account, index) in affiliateBuyers.data" :key="index">
                                            <td>
                                                <div>
                                                <span :class="!account.affiliate_code.name ? 'badge badge-danger' : 'badge badge-success'">
                                                    {{ account.affiliate_code.name ? account.affiliate_code.name : 'N/A' }}
                                                </span>
                                                </div>

                                                <small class="text-secondary">ID # {{ account.affiliate_code_id }}</small>
                                            </td>
                                            <td>{{ account.user == null ? $t('message.profile.ac_not_yet_verified') : account.user.id }}</td>
                                            <td>{{ account.created_at }}</td>
                                            <td>{{ account.email }}</td>
                                            <td>{{ account.name }}</td>
                                            <td>{{ account.is_freelance == 1 ? $t('message.profile.p_freelancer') : $t('message.profile.p_company') }}</td>
                                            <td>{{ account.account_validation }}</td>
                                            <td>{{ account.status }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <pagination
                                    :limit="8"
                                    :data="affiliateBuyers"

                                    @pagination-change-page="getAffiliateBuyers">

                                </pagination>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Generate Affiliate Code -->
        <div
            tabindex="-1"
            role="dialog"
            class="modal fade"
            id="modal-generate-affiliate-code"
            aria-hidden="true"
            aria-labelledby="modelTitleId">

            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.profile.ac_generate') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ $t('message.profile.p_name') }}</label>
                                    <input type="text" class="form-control" v-model="affiliateCodeModel.name" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.profile.close') }}
                        </button>
                        <button type="button" class="btn btn-success" @click="generateAffiliateCodeTwo()">
                            Generate
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Generate Affiliate Code -->

        <!-- Modal View Affiliate User Campaigns -->
        <div class="modal fade" id="modal-view-affiliate-campaigns" ref="affiliateCampaigns">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{ this.affiliateUserModel.name }} 
                            (#{{ this.affiliateUserModel.id }})
                            Campaigns
                        </h4>
                    </div>

                    <div class="modal-body">
                        <div class="row px-3 mb-2">
                            <div class="col-6 px-0 align-self-center">
                                <b>
                                    {{ $t('message.others.table_entries', { from: affiliateUserCampaigns.from, to: affiliateUserCampaigns.to, end: affiliateUserCampaigns.total }) }}
                                </b>
                            </div>
                        </div>

                        <div class="row px-3">
                            <div class="table-responsive mb-2">
                                <table
                                    id="tbl_affiliate_user_campaigns"
                                    class="table table-hover rlink-table">

                                    <thead>
                                        <tr>
                                            <th>Campaign</th>
                                            <th>Code</th>
                                            <th>URL</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="(campaign, index) in affiliateUserCampaigns.data" :key="index">
                                            <td>
                                                <span v-if="campaign.name" class="badge badge-success">
                                                    {{ campaign.name }}
                                                </span>

                                                <span v-else class="badge badge-secondary">
                                                    N/A
                                                </span>

                                                <br>

                                                <small>ID # {{ campaign.id }}</small>
                                            </td>

                                            <td>
                                                <button class="btn btn-default mr-2" @click="copyText('modal-affiliate-code-' + campaign.affiliate_code)">
                                                    <i class="fas fa-clipboard"></i>
                                                </button>
        
                                                <span :ref="'modal-affiliate-code-' + campaign.affiliate_code">
                                                    {{ campaign.affiliate_code }}
                                                </span>
                                            </td>

                                            <td>
                                                <button class="btn btn-default mr-2" @click="copyText('modal-code-link-' + campaign.affiliate_code)">
                                                    <i class="fas fa-clipboard"></i>
                                                </button>
        
                                                <span :ref="'modal-code-link-' + campaign.affiliate_code">
                                                    {{ 'https://tools.stalinks.com/registration?type=Buyer&code=' + campaign.affiliate_code }}
                                                </span>
                                            </td>

                                            <td>
                                                <button 
                                                    class="btn btn-default"
                                                    
                                                    @click="viewAffiliateCampaignBuyers(campaign.id, 
                                                        campaign.user.username 
                                                        ? campaign.user.username 
                                                        : campaign.user.name
                                                    )">

                                                    <i class="fas fa-users"></i>
                                                    View Buyers
                                                    <span class="badge badge-primary">{{ campaign.buyers_count }}</span>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <pagination
                                :data="affiliateUserCampaigns"
                                :limit="8"

                                @pagination-change-page="getAffiliateUserCampaigns">

                            </pagination>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            {{ $t('message.publisher.close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal View Affiliate User Campaigns -->

        <!-- Modal View Affiliate User Buyers -->
        <div class="modal fade" id="modal-view-affiliate-buyers" ref="affiliateBuyers">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{ this.affiliateUserBuyerModel.name }} 
                            (#{{ this.affiliateUserBuyerModel.id }})
                            Buyers
                        </h4>
                    </div>

                    <div class="modal-body">
                        <div class="row px-3 mb-2">
                            <div class="col-6 px-0 align-self-center">
                                <b>
                                    {{ $t('message.others.table_entries', { from: affiliateUserBuyers.from, to: affiliateUserBuyers.to, end: affiliateUserBuyers.total }) }}
                                </b>
                            </div>
                        </div>

                        <div class="row px-3">
                            <div class="table-responsive mb-2">
                                <table
                                    id="tbl_affiliate_user_buyers"
                                    class="table table-hover rlink-table">

                                    <thead>
                                        <tr>
                                            <th>Campaign</th>
                                            <th>User Details</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="(buyer, index) in affiliateUserBuyers.data" :key="index">
                                            <td>
                                                <span v-if="buyer.affiliate_code.name" class="badge badge-primary">
                                                    {{ buyer.affiliate_code.name }}
                                                </span>

                                                <span v-else class="badge badge-secondary">
                                                    N/A
                                                </span>

                                                <br>

                                                <small>ID # {{ buyer.affiliate_code.id }}</small>
                                            </td>
                                            <td>
                                                <p class="mb-1 font-weight-bold">
                                                    {{ buyer.username }}
                                                </p>

                                                <div>
                                                    <i class="fas fa-id-card-alt text-info"></i>
                                                    <small>{{ buyer.name }}</small>
                                                </div>

                                                <div>
                                                    <i class="fas fa-at text-info"></i>
                                                    <small>{{ buyer.email }}</small>
                                                </div>

                                                <div>
                                                    <small>
                                                        <i class="fas fa-calendar-plus fa-lg text-info"></i>
                                                        {{ buyer.created_at }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-success" style="text-transform: capitalize;">
                                                    {{ buyer.status }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <pagination
                                :data="affiliateUserBuyers"
                                :limit="8"

                                @pagination-change-page="getAffiliateUserBuyers">

                            </pagination>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            {{ $t('message.publisher.close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal View Affiliate User Buyers -->

        <!-- Modal View Affiliate Campaign Buyers -->
        <div class="modal fade" id="modal-view-affiliate-campaign-buyers" ref="affiliateCampaignBuyers">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{ this.affiliateCampaignBuyerModel.name }}'s Campaign ID:
                            (#{{ this.affiliateCampaignBuyerModel.campaign_id }})
                            Buyers
                        </h4>
                    </div>

                    <div class="modal-body">
                        <div class="row px-3 mb-2">
                            <div class="col-6 px-0 align-self-center">
                                <b>
                                    {{ $t('message.others.table_entries', { from: affiliateCampaignBuyers.from, to: affiliateCampaignBuyers.to, end: affiliateCampaignBuyers.total }) }}
                                </b>
                            </div>
                        </div>

                        <div class="row px-3">
                            <div class="table-responsive mb-2">
                                <table
                                    id="tbl_affiliate_campaign_buyers"
                                    class="table table-hover rlink-table">

                                    <thead>
                                        <tr>
                                            <th>Campaign</th>
                                            <th>User Details</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="(buyer, index) in affiliateCampaignBuyers.data" :key="index">
                                            <td>
                                                <span v-if="buyer.affiliate_code.name" class="badge badge-primary">
                                                    {{ buyer.affiliate_code.name }}
                                                </span>

                                                <span v-else class="badge badge-secondary">
                                                    N/A
                                                </span>

                                                <br>

                                                <small>ID # {{ buyer.affiliate_code.id }}</small>
                                            </td>
                                            <td>
                                                <p class="mb-1 font-weight-bold">
                                                    {{ buyer.username }}
                                                </p>

                                                <div>
                                                    <i class="fas fa-id-card-alt text-info"></i>
                                                    <small>{{ buyer.name }}</small>
                                                </div>

                                                <div>
                                                    <i class="fas fa-at text-info"></i>
                                                    <small>{{ buyer.email }}</small>
                                                </div>

                                                <div>
                                                    <small>
                                                        <i class="fas fa-calendar-plus fa-lg text-info"></i>
                                                        {{ buyer.created_at }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-success" style="text-transform: capitalize;">
                                                    {{ buyer.status }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <pagination
                                :data="affiliateCampaignBuyers"
                                :limit="8"

                                @pagination-change-page="getAffiliateCampaignBuyers">

                            </pagination>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            {{ $t('message.publisher.close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal View Affiliate Campaign Buyers -->

        <div ref="reference"></div>
    </div>
</template>

<script>
import {mapActions, mapState} from "vuex";
import axios from "axios";

export default {
    props: [],

    data() {
        return {
            affiliates: {
                data: []
            },

            affiliateCampaigns: {
                data: []
            },

            affiliateUserCampaigns: {
                data: []
            },

            affiliateUserBuyers: {
                data: []
            },

            affiliateCampaignBuyers: {
                data: []
            },

            affiliateCodes: {
                data: []
            },

            affiliateBuyers: {
                data: []
            },

            affiliateCodeModel: {
                name: '',
                code: '',
                user_id: '',
            },

            affiliateUserModel: {
                id: '',
                name: '',
            },

            affiliateUserBuyerModel: {
                id: '',
                name: '',
            },

            affiliateCampaignBuyerModel: {
                campaign_id: '',
                name: ''
            }
        }
    },

    async mounted () {
        if (this.user.isOurs === 1 && this.user.role_id === 11) {
            await this.getAffiliateCodes();
            await this.getAffiliateBuyers();
        }

        if (this.user.isAdmin) {
            this.getAllAffiliates();
            this.getAllAffiliateCampaigns();
        }
    },

    computed: {
        ...mapState({
            currentUser: state => state.storeAuth.currentUser,
            user : state => state.storeAuth.currentUser
        }),
    },

    methods: {
        ...mapActions({
            getAffiliateCodeSet : "getAffiliateCodeSet",
        }),

        getAllAffiliates (page = 1) {
            let loader = this.$loading.show();
            let table = $("#tbl_affiliate_buyers");

            table.DataTable().destroy();

            axios.get('/api/get-all-affiliates', {params: {
                    page: page
                }})
                .then((res) => {
                    loader.hide();

                    this.affiliates = res.data;

                    this.$nextTick(() => {
                        table.DataTable({
                            paging: false,
                            searching: false,
                            info: false,
                        });
                    });
                })
        },

        getAllAffiliateCampaigns (page = 1) {
            let loader = this.$loading.show();
            let table = $("#tbl_affiliate_campaigns");

            table.DataTable().destroy();

            axios.get('/api/get-all-affiliate-campaigns', {params: {
                    page: page
                }})
                .then((res) => {
                    loader.hide();

                    this.affiliateCampaigns = res.data;

                    this.$nextTick(() => {
                        table.DataTable({
                            paging: false,
                            searching: false,
                            info: false,
                            "columnDefs": [
                                { "width": "10%", "targets": 0 },
                                { "width": "30%", "targets": 1 },
                                { "width": "10%", "targets": 2 },
                                { "width": "35%", "targets": 3 },
                                { "width": "15%", "targets": 4 },
                            ]
                        });
                    });
                })
        },

        viewAffiliateUserCampaigns (id, name) {
            this.affiliateUserModel.id = id;
            this.affiliateUserModel.name = name;

            this.getAffiliateUserCampaigns();
        },

        getAffiliateUserCampaigns (page = 1) {
            let loader = this.$loading.show();

            axios.get('/api/get-affiliate-user-campaigns/' + this.affiliateUserModel.id, {
                params: {page: page, pagination: 15}
            })
            .then((res) => {
                loader.hide();

                this.affiliateUserCampaigns = res.data

                let table = $('#tbl_affiliate_user_campaigns');

                table.DataTable().destroy();

                this.$nextTick(() => {
                    table.DataTable({
                        paging : false,
                        searching : false,
                        info: false,
                        order: [],
                        columnDefs : {orderable : true, targets : '_all'},
                    });
                });

                // open modal
                let element = this.$refs.affiliateCampaigns
                $(element).modal('show')
            })
        },

        viewAffiliateUserBuyers (id, name) {
            this.affiliateUserBuyerModel.id = id;
            this.affiliateUserBuyerModel.name = name;

            this.getAffiliateUserBuyers();
        },

        getAffiliateUserBuyers (page = 1) {
            let loader = this.$loading.show();

            axios.get('/api/get-affiliate-user-buyers/' + this.affiliateUserBuyerModel.id, {
                params: {page: page, pagination: 15}
            })
            .then((res) => {
                loader.hide();

                this.affiliateUserBuyers = res.data

                let table = $('#tbl_affiliate_user_buyers');

                table.DataTable().destroy();

                this.$nextTick(() => {
                    table.DataTable({
                        paging : false,
                        searching : false,
                        info: false,
                        order: [],
                        columnDefs : {orderable : true, targets : '_all'},
                    });
                });

                // open modal
                let element = this.$refs.affiliateBuyers
                $(element).modal('show')
            })
        },

        viewAffiliateCampaignBuyers (campaign_id, name) {
            this.affiliateCampaignBuyerModel.campaign_id = campaign_id;
            this.affiliateCampaignBuyerModel.name = name;

            this.getAffiliateCampaignBuyers();
        },

        getAffiliateCampaignBuyers (page = 1) {
            let loader = this.$loading.show();

            axios.get('/api/get-affiliate-campaign-buyers/' + this.affiliateCampaignBuyerModel.campaign_id, {
                params: {page: page, pagination: 15}
            })
            .then((res) => {
                loader.hide();

                this.affiliateCampaignBuyers = res.data

                let table = $('#tbl_affiliate_campaign_buyers');

                table.DataTable().destroy();

                this.$nextTick(() => {
                    table.DataTable({
                        paging : false,
                        searching : false,
                        info: false,
                        order: [],
                        columnDefs : {orderable : true, targets : '_all'},
                    });
                });

                // open modal
                let element = this.$refs.affiliateCampaignBuyers
                $(element).modal('show')
            })
        },

        getAffiliateCodes (page = 1) {
            let loader = this.$loading.show();
            let table = $("#tbl_affiliate_codes");

            table.DataTable().destroy();

            axios.get('/api/get-affiliate-codes', {params: {
                    page: page
                }})
                .then((res) => {
                    loader.hide();
                    this.affiliateCodes = res.data;

                    this.$nextTick(() => {
                        table.DataTable({
                            paging: false,
                            searching: false,
                            info: false,
                            "columnDefs": [
                                { "width": "10%", "targets": 0 },
                                { "width": "20%", "targets": 1 },
                                { "width": "20%", "targets": 2 },
                                { "width": "50%", "targets": 3 },
                            ]
                        });
                    });
                })
        },

        getAffiliateBuyers(page = 1) {
            let table = $("#tbl_affiliate_buyers");

            table.DataTable().destroy();

            axios.get('/api/profile/affiliate-buyers', {params: {
                    page: page
                }})
                .then((res) => {
                    this.affiliateBuyers = res.data;

                    this.$nextTick(() => {
                        table.DataTable({
                            paging: false,
                            searching: false,
                            info: false,
                        });
                    });
                })
        },

        generateAffiliateCodeTwo () {
            let self = this;
            let loader = this.$loading.show();

            this.affiliateCodeModel.user_id = this.user.id;
            this.affiliateCodeModel.code = this.generateRandomString(10);

            axios.post('/api/generate-affiliate-code', this.affiliateCodeModel)
                .then((res) => {
                    loader.hide();
                    this.clearModel();
                    this.getAffiliateCodes();
                    this.getAffiliateCodeSet();

                    $("#modal-generate-affiliate-code").modal('hide')

                    swal.fire(
                        self.$t('message.profile.alert_saved'),
                        self.$t('message.profile.alert_affiliate_code_generated'),
                        'success'
                    )
                })
                .catch((err) => {
                    console.log(err)
                    loader.hide();

                    swal.fire(
                        self.$t('message.profile.alert_error'),
                        err.response.data.message,
                        'error'
                    )
                })
        },

        clearModel () {
            this.affiliateCodeModel.name = '';
            this.affiliateCodeModel.code = '';
            this.affiliateCodeModel.user_id = '';
        },

        generateRandomString(length) {
            let randomChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let result = '';

            for ( let i = 0; i < length; i++ ) {
                result += randomChars.charAt(Math.floor(Math.random() * randomChars.length));
            }
            return result;
        },

        copyText (ref) {
            let self = this;

            const storage = document.createElement('textarea');
            storage.value = this.htmlDecode(this.$refs[ref][0].innerHTML.replace(/\s/g, ''));

            self.$refs.reference.appendChild(storage);
            storage.select();
            storage.setSelectionRange(0, 99999);
            document.execCommand('copy');
            self.$refs.reference.removeChild(storage);

            swal.fire(
                self.$t('message.profile.alert_copied'),
                '',
                'success'
            )
        },

        htmlDecode (input) {
            let doc = new DOMParser().parseFromString(input, "text/html");
            return doc.documentElement.textContent;
        }
    },
}
</script>

<style scoped>
    .dataTables_wrapper .dataTables_info {
        clear: both !important;
        display: revert !important;
        padding-top: 0 !important;
        margin-bottom: .5rem;
        font-weight: 700;
    }
</style>
