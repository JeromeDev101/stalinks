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
                        <h3 class="card-title text-primary">{{ $t('message.registration_accounts.filter_title') }}</h3>
                        <div class="card-tools" style="float: left!important;">
                            <button
                                type="button"
                                aria-expanded="false"
                                data-toggle="collapse"
                                class="btn btn-primary ml-5"
                                data-target="#collapseFilterRegistration"
                                aria-controls="collapseFilterRegistration">

                                <i class="fa fa-plus"></i> {{ $t('message.registration_accounts.filter_show') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body collapse" id="collapseFilterRegistration">
                        <form action="" autocomplete="off" @submit.prevent="">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_search') }}</label>
                                        <input type="text"
                                               class="form-control"
                                               v-model="filterModel.search"
                                               aria-describedby="helpId"
                                               :placeholder="$t('message.registration_accounts.type')">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_status') }}</label>
                                        <select class="form-control" v-model="filterModel.status">
                                            <option value="">{{ $t('message.registration_accounts.filter_select_status') }}</option>
                                            <option value="active">{{ $t('message.registration_accounts.filter_active') }}</option>
                                            <option value="inactive">{{ $t('message.registration_accounts.filter_inactive') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_account_type') }}</label>
                                        <select class="form-control" v-model="filterModel.type">
                                            <option value="">{{ $t('message.registration_accounts.filter_select_type') }}</option>
                                            <option value="seller">{{ $t('message.registration_accounts.filter_seller') }}</option>
                                            <option value="buyer">{{ $t('message.registration_accounts.filter_buyer') }}</option>
                                            <option value="writer">{{ $t('message.registration_accounts.filter_writer') }}</option>
                                            <option value="affiliate">{{ $t('message.registration_accounts.filter_affiliate') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_country') }}</label>
                                        <select class="form-control" name="" v-model="filterModel.country">
                                            <option value="">{{ $t('message.registration_accounts.filter_select_country') }}</option>
                                            <option value="none">{{ $t('message.registration_accounts.none') }}</option>
                                            <option v-for="option in listCountryAll.data"
                                                    :value="option.id"
                                                    :key="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_lang') }}</label>
                                        <select class="form-control" name="" v-model="filterModel.language_id">
                                            <option value="">{{ $t('message.registration_accounts.filter_select_lang') }}</option>
                                            <option value="none">{{ $t('message.registration_accounts.none') }}</option>
                                            <option v-for="option in listLanguages.data"
                                                    :value="option.id"
                                                    :key="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_commission') }}</label>
                                        <select class="form-control" name="" v-model="filterModel.commission">
                                            <option value="">{{ $t('message.registration_accounts.filter_select_answer') }}</option>
                                            <option value="yes">{{ $t('message.registration_accounts.yes') }}</option>
                                            <option value="no">{{ $t('message.registration_accounts.no') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_credit_auth') }}</label>
                                        <select class="form-control" name="" v-model="filterModel.credit_auth">
                                            <option value="">{{ $t('message.registration_accounts.filter_select_credit_auth') }}</option>
                                            <option value="Yes">{{ $t('message.registration_accounts.yes') }}</option>
                                            <option value="No">{{ $t('message.registration_accounts.no') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3" v-show="user.role_id == 6 || user.role_id == 8 || user.isAdmin">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_in_charge') }}</label>
                                        <select class="form-control" v-model="filterModel.team_in_charge">
                                            <option value="">{{ $t('message.registration_accounts.all') }}</option>
                                            <option value="none">{{ $t('message.registration_accounts.none') }}</option>
                                            <option v-for="option in listIncharge.data" v-bind:value="option.id">
                                                {{ option.username == null ? option.name : option.username }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3" v-show="user.role_id == 6 || user.role_id == 8 || user.isAdmin">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_affiliate') }}</label>
                                        <select class="form-control" v-model="filterModel.affiliate">
                                            <option value="">{{ $t('message.registration_accounts.all') }}</option>
                                            <option v-for="option in listAffiliate.data" v-bind:value="option.id">
                                                {{ option.username == null ? option.name : option.username }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_company_type') }}</label>
                                        <select class="form-control" v-model="filterModel.company_type">
                                            <option value="">{{ $t('message.registration_accounts.all') }}</option>
                                            <option value="1">{{ $t('message.registration_accounts.filter_freelance') }}</option>
                                            <option value="0">{{ $t('message.registration_accounts.filter_company') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_company_name') }}</label>
                                        <input
                                            v-model="filterModel.company_name"
                                            type="text"
                                            class="form-control"
                                            :placeholder="$t('message.registration_accounts.type')">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_company_url') }}</label>
                                        <input
                                            v-model="filterModel.company_url"
                                            type="text"
                                            class="form-control"
                                            :placeholder="$t('message.registration_accounts.type')">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_account_validation') }}</label>
                                        <select class="form-control" v-model="filterModel.account_validation">
                                            <option value="">{{ $t('message.registration_accounts.all') }}</option>
                                            <option value="valid">{{ $t('message.registration_accounts.filter_valid') }}</option>
                                            <option value="invalid">{{ $t('message.registration_accounts.filter_invalid') }}</option>
                                            <option value="processing">{{ $t('message.registration_accounts.filter_processing') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_date_reg') }}</label>
                                        <div class="input-group">
                                            <date-range-picker
                                                v-model="filterModel.created_at"
                                                :ranges="generateDefaultDateRange()"
                                                :linkedCalendars="true"
                                                :dateRange="filterModel.created_at"
                                                :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                                ref="picker"
                                                opens="left"
                                                style="width: 100%"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_account_verified') }}</label>
                                        <select class="form-control"
                                                v-model="filterModel.account_verification">
                                            <option value="">{{ $t('message.registration_accounts.all') }}</option>
                                            <option value="Yes">{{ $t('message.registration_accounts.yes') }}</option>
                                            <option value="No">{{ $t('message.registration_accounts.no') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_sub_account') }}</label>
                                        <select class="form-control" v-model="filterModel.is_sub_account">
                                            <option value="">{{ $t('message.registration_accounts.all') }}</option>
                                            <option value="1">{{ $t('message.registration_accounts.yes') }}</option>
                                            <option value="0">{{ $t('message.registration_accounts.no') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_payment_info') }}</label>

                                        <select class="form-control" v-model="filterModel.payment_info">
                                            <option value="">{{ $t('message.registration_accounts.filter_default') }}</option>
                                            <option v-for="(option, index) in paymentMethodList" v-bind:value="option.id" :key="index">
                                                {{ option.type }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Payment Info</label>

                                        <input
                                            v-model="filterModel.payment_info_data"
                                            type="text"
                                            class="form-control"
                                            role="presentation"
                                            autocomplete="off"
                                            id="filterPaymentInfoData"
                                            name="filterPaymentInfoData"
                                            :placeholder="$t('message.registration_accounts.type')">
                                    </div>
                                </div>

                                <div class="col-md-3" v-if="user.role_id == 8 || user.isAdmin">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_buyer_trans') }}</label>
                                        <select class="form-control" v-model="filterModel.buyer_transaction">
                                            <option value="">{{ $t('message.registration_accounts.all') }}</option>
                                            <option value="with">{{ $t('message.registration_accounts.filter_with') }}</option>
                                            <option value="none">{{ $t('message.registration_accounts.filter_without') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <button class="btn btn-default" @click="clearSearch" :disabled="isSearching">
                                        {{ $t('message.registration_accounts.clear') }}
                                    </button>
                                    <button class="btn btn-default" @click="doSearch" :disabled="isSearching">
                                        {{ $t('message.registration_accounts.search') }}
                                        <i class="fa fa-refresh fa-spin" v-if="isSearchLoading"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">{{ $t('message.registration_accounts.ra_title') }}</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-6 mt-2">
                                <div class="input-group input-group-sm float-left">
                                    <div class="btn-group">
                                        <button
                                            class="btn btn-default"
                                            @click="selectAll">

                                            {{ !allSelected ? $t('message.registration_accounts.ra_select_all') : $t('message.registration_accounts.ra_deselect_all') }}
                                        </button>

                                        <button
                                            type="submit"
                                            :title="$t('message.registration_accounts.ra_send_email')"
                                            class="btn btn-default"
                                            :disabled="isDisabledAction"

                                            @click="doSendEmail(null)">

                                            <i class="far fa-envelope"></i>
                                        </button>

                                        <button
                                            type="submit"
                                            :title="$t('message.registration_accounts.ra_update_multiple_in_charge')"
                                            class="btn btn-default"
                                            :disabled="isDisabledAction"

                                            @click="multipleUpdateInCharge">

                                            <i class="fa fa-fw fa-user"></i>
                                        </button>

                                        <button
                                            v-if="user.role_id == 8 || user.isAdmin"
                                            type="button"
                                            :title="$t('message.registration_accounts.ra_send_multiple_validation_email')"
                                            class="btn btn-default"
                                            :disabled="isDisabledAction"

                                            @click="sendMultipleValidationEmail()">

                                            <i class="fa fa-exclamation-circle"></i>
                                        </button>

                                        <button
                                            v-if="user.role_id == 8 || user.isAdmin"
                                            type="button"
                                            :title="$t('message.registration_accounts.ra_send_multiple_deposit_email')"
                                            class="btn btn-default"
                                            :disabled="isDisabledAction"

                                            @click="sendMultipleDepositEmail()">

                                            <i class="fas fa-coins"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mt-2">
                                <div class="input-group input-group-sm float-right" style="width: 100px">
                                    <select
                                        v-model="filterModel.paginate"
                                        style="height: 37px;"
                                        class="form-control float-right"

                                        @change="getAccountList">

                                        <option v-for="option in paginate" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select>
                                </div>

                                <button
                                    data-toggle="modal"
                                    data-target="#modal-setting"
                                    class="btn btn-default float-right mr-2">

                                    <i class="fa fa-cog"></i>
                                </button>

                                <button
                                    data-toggle="modal"
                                    data-target="#modal-registration"
                                    class="btn btn-success float-right mr-2"

                                    @click="clearMessageform">

                                    {{ $t('message.registration_accounts.ra_register') }}
                                </button>

                                <form action="" autocomplete="off" @submit.prevent="">
                                    <div class="form-group w-50 float-right mr-2">
                                        <input
                                            v-model="filterModel.advance_search"
                                            type="text"
                                            class="form-control"
                                            role="presentation"
                                            autocomplete="off"
                                            id="filterAdvanceSearch"
                                            name="filterAdvanceSearch"
                                            placeholder="Search here..."
                                            :disabled="isAdvanceSearching"

                                            @input="advanceSearch">
                                    </div>
                                </form>
                            </div>
                        </div>

                        <span v-if="listAccount.total > 10" class="pagination-custom-footer-text m-0 mt-2">
                            <b v-if="filterModel.paginate !== 'All'">
                                {{ $t('message.others.table_entries', { from: listAccount.from, to: listAccount.to, end: listAccount.total }) }}
                            </b>

                            <b v-else>
                                {{ $t('message.others.table_all_entries', { total: listAccount.total }) }}
                            </b>
                        </span>

                        <div class="table-responsive">
                            <table id="tbl_account" class="table table-striped table-bordered" style="font-size: 0.75rem">
                                <thead>
                                    <tr class="label-primary">
                                        <th>#</th>
                                        <th></th>
                                        <th>{{ $t('message.registration_accounts.ra_action') }}</th>
                                        <th v-show="tblAccountsOpt.user_id">
                                            {{ $t('message.registration_accounts.ra_user_id') }}
                                        </th>
                                        <th v-show="tblAccountsOpt.date_registered">
                                            {{ $t('message.registration_accounts.ra_date_reg') }}
                                        </th>
                                        <th v-show="tblAccountsOpt.payment_account_email && user.isAdmin">
                                            {{ $t('message.registration_accounts.ra_payment_info') }}
                                        </th>
                                        <th v-show="tblAccountsOpt.email && user.isAdmin">
                                            {{ $t('message.registration_accounts.ra_email') }}
                                        </th>
                                        <th v-show="tblAccountsOpt.in_charge">
                                            {{ $t('message.registration_accounts.ra_in_charge') }}
                                        </th>
                                        <th v-show="tblAccountsOpt.affiliate">
                                            {{ $t('message.registration_accounts.ra_affiliate') }}
                                        </th>
                                        <th v-show="tblAccountsOpt.username">
                                            {{ $t('message.registration_accounts.ra_username') }}
                                        </th>
                                        <th v-show="tblAccountsOpt.name">
                                            {{ $t('message.registration_accounts.ra_name') }}
                                        </th>
                                        <th v-show="tblAccountsOpt.country">
                                            {{ $t('message.registration_accounts.filter_country') }}
                                        </th>
                                        <th v-show="tblAccountsOpt.language">
                                            {{ $t('message.registration_accounts.filter_lang') }}
                                        </th>
                                        <th v-show="tblAccountsOpt.company_type">
                                            {{ $t('message.registration_accounts.filter_company_type') }}
                                        </th>
                                        <th v-show="tblAccountsOpt.company_name">
                                            {{ $t('message.registration_accounts.filter_company_name') }}
                                        </th>
                                        <th v-show="tblAccountsOpt.company_url">
                                            {{ $t('message.registration_accounts.filter_company_url') }}
                                        </th>
                                        <th v-show="tblAccountsOpt.type">
                                            {{ $t('message.registration_accounts.ra_type') }}
                                        </th>
                                        <th v-show="tblAccountsOpt.sub_account">
                                            {{ $t('message.registration_accounts.filter_sub_account') }}
                                        </th>
                                        <th v-show="tblAccountsOpt.under_of_main_buyer">
                                            {{ $t('message.registration_accounts.ra_under_main_buyer') }}
                                        </th>
                                        <th v-show="tblAccountsOpt.account_validation">
                                            {{ $t('message.registration_accounts.filter_account_validation') }}
                                        </th>
                                        <th v-show="tblAccountsOpt.is_show_price_basis">
                                            {{ $t('message.registration_accounts.ra_show_price_basis') }}
                                        </th>
                                        <th v-show="tblAccountsOpt.credit_authorization">
                                            {{ $t('message.registration_accounts.ra_credit_auth') }}
                                        </th>
                                        <th v-show="tblAccountsOpt.commission">
                                            {{ $t('message.registration_accounts.filter_commission') }}
                                        </th>
                                        <th v-show="tblAccountsOpt.status">
                                            {{ $t('message.registration_accounts.filter_status') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(account, index) in listAccount.data" :key="index">
                                        <td>{{ index + 1 }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-default">
                                                    <input
                                                        v-model="checkIds"
                                                        type="checkbox"
                                                        class="custom-checkbox"
                                                        :id="account.id"
                                                        :value="account"

                                                        @change="checkSelected">
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button
                                                    title="Edit"
                                                    data-toggle="modal"
                                                    class="btn btn-default"
                                                    data-target="#modal-update-registration"

                                                    @click="doUpdateAccount(account)">

                                                    <i class="fa fa-fw fa-edit"></i>
                                                </button>

                                                <button
                                                    type="button"
                                                    :title="$t('message.registration_accounts.ra_send_email')"
                                                    data-toggle="modal"
                                                    class="btn btn-default"

                                                    @click="doSendEmail(account)">

                                                    <i class="far fa-envelope"></i>
                                                </button>

                                                <button
                                                    v-if="user.role_id == 8 || user.isAdmin"
                                                    :disabled="account.account_validation !== 'processing'
                                                    || account.email_via === 'validation_email'
                                                    || account.verification_code !== null"
                                                    type="button"
                                                    class="btn btn-default"
                                                    :title="$t('message.registration_accounts.ra_send_validation_email')"

                                                    @click="sendValidationEmail(account)">

                                                    <i class="fa fa-exclamation-circle"></i>
                                                </button>

                                                <button
                                                    v-if="user.role_id == 8 || user.isAdmin"
                                                    :disabled="!account.user
                                                    || account.type != 'Buyer'
                                                    || account.email_via_others === 'deposit_email'
                                                    || account.account_validation !== 'valid'
                                                    || account.user.wallet_transactions_count !== 0"
                                                    type="button"
                                                    class="btn btn-default"
                                                    :title="$t('message.registration_accounts.ra_send_credit_email')"

                                                    @click="sendDepositEmail(account)">

                                                    <i class="fas fa-coins"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td v-show="tblAccountsOpt.user_id">
                                            {{ account.user == null ? 'Not yet Verified' : account.user.id }}
                                        </td>
                                        <td v-show="tblAccountsOpt.date_registered">{{ account.created_at }}</td>
                                        <td v-show="tblAccountsOpt.payment_account_email && user.isAdmin"
                                            v-html="displayEmailPayment(account)"></td>
                                        <td v-show="tblAccountsOpt.email && user.isAdmin">{{ account.email }}</td>
                                        <td v-show="tblAccountsOpt.in_charge">
                                            {{
                                                account.team_in_charge == null
                                                    ? 'N/A'
                                                    : account.is_sub_account == 1
                                                        ? account.team_in_charge.registration.team_in_charge.username
                                                        : account.team_in_charge.username
                                            }}

                                            <span
                                                v-if="account.team_in_charge !== null && account.team_in_charge.status === 'inactive'"
                                                class="badge badge-danger">

                                                {{ $t('message.registration_accounts.filter_inactive') }}
                                            </span>
                                        </td>
                                        <td v-show="tblAccountsOpt.affiliate">
                                            {{
                                                account.affiliate_id == null
                                                    ? 'N/A'
                                                    : account.affiliate
                                                        ? account.affiliate.username
                                                        : 'N/A'
                                            }}
                                        </td>
                                        <td v-show="tblAccountsOpt.username">
                                            <span :class="{'badge badge-pill badge-success': account.is_recommended === 'yes'}">
                                                {{ account.username }}
                                            </span>
                                        </td>
                                        <td v-show="tblAccountsOpt.name">{{ account.name }}</td>
                                        <td v-show="tblAccountsOpt.country">
                                            {{ account.country === null ? 'N/A' : account.country.name }}
                                        </td>
                                        <td v-show="tblAccountsOpt.language">
                                            {{ account.language === null ? 'N/A' : account.language.name }}
                                        </td>
                                        <td v-show="tblAccountsOpt.company_type">
                                            {{ account.is_freelance == 1 ? 'Freelancer' : 'Company' }}
                                        </td>
                                        <td v-show="tblAccountsOpt.company_name">{{ account.company_name }}</td>
                                        <td v-show="tblAccountsOpt.company_url">
                                            <a
                                                :href="'http://' + account.company_url"
                                                target="_blank">
                                                {{ account.company_url }}
                                            </a>
                                        </td>
                                        <td v-show="tblAccountsOpt.type">
                                            {{ account.type }}

                                            <!-- show with/without transaction -->

                                            <div v-if="(user.role_id == 8 || user.isAdmin) && account.type == 'Buyer'">
                                                <span v-if="account.user">
                                                    <span
                                                        v-if="account.user.wallet_transactions_count"
                                                        class="badge badge-success">
                                                        {{ $t('message.registration_accounts.ra_with_trans') }}
                                                    </span>

                                                    <span v-else class="badge badge-danger">
                                                        {{ $t('message.registration_accounts.ra_no_trans') }}
                                                    </span>
                                                </span>
                                            </div>

                                            <div v-if="(user.role_id == 8 || user.isAdmin) && account.type == 'Buyer'">
                                                <span v-if="account.email_via_others === 'deposit_email'">
                                                    <span class="badge badge-warning">
                                                        {{ $t('message.registration_accounts.ra_deposit_email_sent') }}

                                                        <span>
                                                            {{
                                                                account.reminded_days === 11
                                                                    ? '- 24h'
                                                                    : account.reminded_days === 21
                                                                    ? '- 48h'
                                                                    : account.reminded_days === 31
                                                                        ? '- 72h'
                                                                        : account.reminded_days === 41
                                                                            ? '- 96h'
                                                                            : ''
                                                            }}
                                                        </span>
                                                    </span>
                                                </span>
                                            </div>
                                        </td>
                                        <td v-show="tblAccountsOpt.sub_account">{{
                                                account.is_sub_account == 0 ? 'No' : 'Yes'
                                            }}
                                        </td>
                                        <td v-show="tblAccountsOpt.under_of_main_buyer">
                                            {{ account.is_sub_account == 0 ? '' : account.team_in_charge.username }}
                                        </td>
                                        <td v-show="tblAccountsOpt.account_validation">
                                            {{ account.account_validation }}

                                            <span v-if="account.account_validation === 'processing' && account.email_via === 'validation_email'">
                                                <span class="badge badge-warning">
                                                    {{ $t('message.registration_accounts.ra_validation_email_sent') }}

                                                    <span>
                                                        {{
                                                            account.reminded_days === 10
                                                            ? '- 24h'
                                                            : account.reminded_days === 20
                                                                ? '- 48h'
                                                                : account.reminded_days === 30
                                                                    ? '- 72h'
                                                                    : ''
                                                        }}
                                                    </span>
                                                </span>
                                            </span>
                                        </td>
                                        <td v-show="tblAccountsOpt.is_show_price_basis">
                                            {{ account.is_show_price_basis == 0 ? 'no':'yes' }}
                                        </td>
                                        <td v-show="tblAccountsOpt.credit_authorization">{{ account.credit_auth }}</td>
                                        <td v-show="tblAccountsOpt.commission">{{ account.commission }}</td>
                                        <td v-show="tblAccountsOpt.status">{{ account.status }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <pagination
                            :limit="8"
                            :data="listAccount"

                            @pagination-change-page="getAccountList">

                        </pagination>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Registration -->
        <div class="modal fade" id="modal-registration" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form action="" autocomplete="off" @submit.prevent="">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $t('message.registration_accounts.r_title') }}</h5>
                            <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                            <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                        </div>
                        <div class="modal-body">
                            <h4 class="text-primary">{{ $t('message.registration_accounts.r_account_info') }}</h4>
                            <hr/>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_account_type') }} <span class="text-danger">*</span></label>
                                        <select class="form-control" name="" v-model="accountModel.type" @change="checkTeamIncharge('add')">
                                            <option value="">{{ $t('message.registration_accounts.filter_select_type') }}</option>
                                            <option value="Seller">{{ $t('message.registration_accounts.filter_seller') }}</option>
                                            <option value="Buyer">{{ $t('message.registration_accounts.filter_buyer') }}</option>
                                            <option value="Writer">{{ $t('message.registration_accounts.filter_writer') }}</option>
                                            <option value="Affiliate">{{ $t('message.registration_accounts.filter_affiliate') }}</option>
                                        </select>
                                        <span v-if="messageForms.errors.type" v-for="err in messageForms.errors.type" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.ra_username') }} <span class="text-danger">*</span></label>
                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountModel.username" name="" aria-describedby="helpId" placeholder="">
                                        <span v-if="messageForms.errors.username" v-for="err in messageForms.errors.username" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.ra_name') }} <span class="text-danger">*</span></label>
                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountModel.name" name="" aria-describedby="helpId" placeholder="">
                                        <span v-if="messageForms.errors.name" v-for="err in messageForms.errors.name" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.ra_email') }} <span class="text-danger">*</span></label>
                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountModel.email" name="" aria-describedby="helpId" placeholder="">
                                        <span v-if="messageForms.errors.email" v-for="err in messageForms.errors.email" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.r_phone') }}</label>
                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountModel.phone" name="" aria-describedby="helpId" placeholder="">
                                        <span v-if="messageForms.errors.phone" v-for="err in messageForms.errors.phone" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.r_skype') }}</label>
                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountModel.skype">
                                        <span v-if="messageForms.errors.skype" v-for="err in messageForms.errors.skype" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.r_password') }} <span class="text-danger">*</span></label>
                                        <input type="password" role="presentation" autocomplete="off" class="form-control" v-model="accountModel.password" name="" aria-describedby="helpId" placeholder="">
                                        <span v-if="messageForms.errors.password" v-for="err in messageForms.errors.password" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.r_confirm_password') }} <span class="text-danger">*</span></label>
                                        <input type="password" role="presentation" autocomplete="off" class="form-control" v-model="accountModel.c_password" name="" aria-describedby="helpId" placeholder="">
                                        <span v-if="messageForms.errors.c_password" v-for="err in messageForms.errors.c_password" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                                <div class="col-md-12" >
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_company_type') }} <span class="text-danger">*</span></label>
                                        <select class="form-control" v-model="accountModel.company_type" @click="checkCompanyType()">
                                            <option value="Company">{{ $t('message.registration_accounts.filter_company') }}</option>
                                            <option value="Freelancer">{{ $t('message.registration_accounts.filter_freelance') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12" v-show="addCompanyName">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_company_name') }} <span class="text-danger">*</span></label>
                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountModel.company_name">
                                        <span v-if="messageForms.errors.company_name" v-for="err in messageForms.errors.company_name" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                                <div class="col-sm-12" v-show="addCompanyName">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_company_url') }}</label>
                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountModel.company_url">
                                        <span v-if="messageForms.errors.company_url" v-for="err in messageForms.errors.company_url" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_country') }}</label>
                                        <select class="form-control" v-model="accountModel.country_id">
                                            <option value="">{{ $t('message.registration_accounts.all') }}</option>
                                            <option v-for="option in listCountryAll.data" v-bind:value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>
                                            {{ $t('message.registration_accounts.filter_lang') }}
                                        </label>
                                        <small>
                                            <i class="text-danger">
                                                {{ $t('message.registration_accounts.r_required_writer') }}
                                            </i>
                                        </small>
                                        <v-select
                                            multiple
                                            v-model="accountModel.language_id"
                                            label="name"
                                            :options="listLanguages.data"
                                            :reduce="name => name.id"
                                            :searchable="true"
                                            :placeholder="$t('message.registration_accounts.filter_select_lang')"/>

                                        <span class="text-danger" v-if="isErrorLang">
                                            {{ $t('message.registration_accounts.r_required_lang') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.r_address') }}</label>
                                        <textarea class="form-control" v-model="accountModel.address"></textarea>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.r_info') }}</label>
                                        <textarea class="form-control" v-model="accountModel.info"></textarea>
                                    </div>
                                </div>

                            </div>

                            <hr v-show="addDisplayWriterPrice"/>
                            <h4 class="text-primary" v-show="addDisplayWriterPrice">Writer Pricing</h4>
                            <div v-show="addDisplayWriterPrice && accountModel.rate_type && accountModel.language_id.length" class="alert alert-info">
                                {{ $t('message.others.possible_earnings_writer', { from: writerEarningsRegistration.from, to: writerEarningsRegistration.to}) }}
                            </div>
                            <hr v-show="addDisplayWriterPrice"/>

                            <div class="row" v-show="addDisplayWriterPrice">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.r_pricing_type') }}</label>
                                        <select class="form-control" v-model="accountModel.rate_type">
                                            <option value="ppw">{{ $t('message.registration_accounts.r_ppw') }}</option>
                                            <option value="ppa">{{ $t('message.registration_accounts.r_ppa') }}</option>
                                        </select>
                                        <span v-if="messageForms.errors.rate_type" v-for="err in messageForms.errors.rate_type" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                            </div>

                            <div v-if="accountModel.type !== 'Affiliate'">
                                <hr/>
                                <h4 class="text-primary">{{ $t('message.registration_accounts.r_payment_info') }}</h4>
                                <span v-if="messageForms.errors.id_payment_type" class="text-danger">
                                {{ $t('message.registration_accounts.r_select_default') }}
                            </span>
                                <span v-if="validate_error_type" class="text-danger">
                                {{ $t('message.registration_accounts.r_input_selected') }}
                            </span>

                                <!-- empty account type -->
                                <table class="table" v-if="accountModel.type == ''">
                                    <tr>
                                        <td class="text-center text-danger">
                                            {{ $t('message.registration_accounts.r_select_account') }}
                                        </td>
                                    </tr>
                                </table>

                                <!-- payment for seller and writer -->
                                <table class="table" v-if="accountModel.type === 'Seller' || accountModel.type === 'Writer'">
                                    <tr>
                                        <td></td>
                                        <td>{{ $t('message.registration_accounts.filter_default') }}</td>
                                    </tr>
                                    <tr v-for="(payment_method, index) in paymentMethodListSendPayment" :key="index">
                                        <td>
                                            <div class="form-group">
                                                <label>{{ payment_method.type }} {{ $t('message.registration_accounts.r_account') }}</label>
                                                <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountModel.add_method_payment_type[payment_method.id]">
                                            </div>

                                            <span
                                                    v-if="messageForms.errors.hasOwnProperty('add_method_payment_type.'+ payment_method.id)"
                                                    v-for="err in messageForms.errors['add_method_payment_type.'+ payment_method.id]"
                                                    class="text-danger">

                                                {{ err }}
                                            </span>


                                            <div class="px-5"
                                                 v-show="payment_method.account_name ||
                                                payment_method.bank_name ||
                                                payment_method.swift_code ||
                                                payment_method.beneficiary_add ||
                                                payment_method.account_iban ||
                                                payment_method.account_holder ||
                                                payment_method.account_type ||
                                                payment_method.routing_num ||
                                                payment_method.wire_routing_num
                                            "
                                            >
                                                <h6 class="text-primary">Other Details:</h6>
                                                <hr/>

                                                <div class="row">
                                                    <div class="col-sm-12" v-show="payment_method.bank_name">
                                                        <label>Bank Name:</label>
                                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountModel.bank_name[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.account_name">
                                                        <label>Account Name:</label>
                                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountModel.account_name[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.account_iban">
                                                        <label>Account IBAN:</label>
                                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountModel.account_iban[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.swift_code">
                                                        <label>SWIFT Code:</label>
                                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountModel.swift_code[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.beneficiary_add">
                                                        <label>Beneficiary Address:</label>
                                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountModel.beneficiary_add[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.account_holder">
                                                        <label>Account Holder:</label>
                                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountModel.account_holder[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.account_type">
                                                        <label>Account Type:</label>
                                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountModel.account_type[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.routing_num">
                                                        <label>Routing Number:</label>
                                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountModel.routing_num[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.wire_routing_num">
                                                        <label>Wire Routing Number:</label>
                                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountModel.wire_routing_num[payment_method.id]">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="width: 50px;vertical-align:middle;" class="text-center">
                                            <input type="radio" class="btn-radio-custom" name="payment_default" v-bind:value="payment_method.id" v-model="accountModel.id_payment_type">
                                        </td>
                                    </tr>
                                </table>
                                <!-- end of payment for seller and writer -->

                                <!-- payment for buyer -->
                                <table class="table" v-if="accountModel.type === 'Buyer'">
                                    <tr>
                                        <td></td>
                                        <td>{{ $t('message.registration_accounts.filter_default') }}</td>
                                    </tr>
                                    <tr v-for="(payment_method, index) in paymentMethodListReceivePayment" :key="index">
                                        <td>
                                            <div class="form-group">
                                                <label>{{ payment_method.type }} {{ $t('message.registration_accounts.r_account') }}</label>
                                                <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountModel.add_method_payment_type[payment_method.id]">
                                            </div>

                                            <span
                                                v-if="messageForms.errors.hasOwnProperty('add_method_payment_type.'+ payment_method.id)"
                                                v-for="err in messageForms.errors['add_method_payment_type.'+ payment_method.id]"
                                                class="text-danger">

                                            {{ err }}
                                        </span>
                                        </td>
                                        <td style="width: 50px;vertical-align:middle;" class="text-center">
                                            <input type="radio" class="btn-radio-custom" name="payment_default" v-bind:value="payment_method.id" v-model="accountModel.id_payment_type">
                                        </td>
                                    </tr>
                                </table>
                                <!-- end of payment for buyer -->
                            </div>

                            <hr/>
                            <h4 class="text-primary">{{ $t('message.registration_accounts.r_internal_info') }}</h4>
                            <hr/>

                            <div class="row">

                                <!-- <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Commission <span class="text-danger">*</span></label>
                                        <select class="form-control" name="" v-model="accountModel.commission">
                                            <option value="" disabled>Select Commission</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                        <span v-if="messageForms.errors.commission" v-for="err in messageForms.errors.commission" class="text-danger">{{ err }}</span>
                                    </div>
                                </div> -->

                                <div class="col-sm-6" v-if="isTeamSeller">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_in_charge') }}</label>
                                        <select class="form-control" v-model="accountModel.team_in_charge">
                                            <option value="">{{ $t('message.registration_accounts.r_select_in_charge') }}</option>
                                            <option v-for="option in listTeamIncharge" v-bind:value="option.id">
                                                {{ option.username == null || option.username == '' ? option.name:option.username}}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6" v-show="user.role_id === 8 || user.isAdmin">
                                    <label>{{ $t('message.registration_accounts.filter_account_validation') }} <span class="text-danger">*</span></label>
                                    <select class="form-control" name="" v-model="accountModel.account_validation">
                                        <option value="" disabled>{{ $t('message.registration_accounts.r_select_account_validation') }}</option>
                                        <option value="valid">{{ $t('message.registration_accounts.filter_valid') }}</option>
                                        <option value="invalid">{{ $t('message.registration_accounts.filter_invalid') }}</option>
                                        <option value="processing">{{ $t('message.registration_accounts.filter_processing') }}</option>
                                    </select>
                                    <span v-if="messageForms.errors.account_validation" v-for="err in messageForms.errors.account_validation" class="text-danger">{{ err }}</span>
                                </div>

                                <div class="col-sm-6" v-if="accountModel.type == 'Buyer' && user.isAdmin">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.r_affiliates') }}</label>
                                        <select class="form-control" v-model="accountModel.affiliate">
                                            <option value="">{{ $t('message.registration_accounts.none') }}</option>
                                            <option v-for="option in listAffiliate.data" v-bind:value="option.id">
                                                {{ option.username == null ? option.name : option.username }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6" v-show="accountModel.type == 'Buyer' && user.isAdmin">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.r_type_of_buyer') }}</label>
                                        <select class="form-control" name="" v-model="accountModel.buyer_type">
                                            <option value="Basic">Basic</option>
                                            <option value="Medium">Medium</option>
                                            <option value="Premium">Premium</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6" v-show="accountModel.type == 'Seller'">
                                    <div class="form-group">
                                        <label>Recommended</label>
                                        <select class="form-control" name="" v-model="accountModel.is_recommended">
                                            <option value="no">No</option>
                                            <option value="yes">Yes</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-0">
                                    <input
                                        type="checkbox"
                                        v-model="btnTermsAndConditions"
                                        @change="isDisableSubmit =  !isDisableSubmit">
                                    {{ $t('message.registration_accounts.r_ive_read') }}

                                    <a
                                        href="#"
                                        data-toggle="modal"
                                        data-target="#modalTermsAndCondition">
                                        {{ $t('message.registration_accounts.r_terms') }}
                                    </a>
                                </p>
                            </div>

                            <div>
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    {{ $t('message.registration_accounts.close') }}
                                </button>
                                <button :disabled="isDisableSubmit" type="button" @click="submitAdd" class="btn btn-primary">
                                    {{ $t('message.registration_accounts.save') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End of Modal Registration -->

        <!-- Modal Settings -->
        <div class="modal fade" id="modal-setting" style="display: none;">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('message.registration_accounts.sd_title') }}</h4>
                    </div>

                    <div class="modal-body relative">
                        <div class="form-group row">
                            <div class="checkbox col-md-6" v-for="(opt, key) in tblAccountsOpt" :key="key">
                                <label>
                                    <input
                                        v-model="tblAccountsOpt[key]"
                                        type="checkbox"
                                        :checked="key ? 'checked':''"

                                        @change="columnAdjust">

                                    {{ humanize(key) }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Settings -->

        <!-- Modal Send Email -->
        <div id="modal-email-registration" class="modal fade" ref="modalEmailRegistration" style="display: none;" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('message.registration_accounts.se_title') }}</h4>
                    </div>

                    <div class="modal-body relative">
                        <form class="row" action="">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div v-if="user.isAdmin">
                                            <div class="form-group">
                                                <label>{{ $t('message.registration_accounts.se_login') }}</label>

                                                <select class="form-control" v-model="user.work_mail">
                                                    <option value="all">{{ $t('message.registration_accounts.all') }}</option>
                                                    <option v-for="option in adminAccessOptions" v-bind:value="option.work_mail">
                                                        [{{ option.role.name }}] {{ option.work_mail }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label style="color: #333">{{ $t('message.url_prospect.et_cat') }}</label>

                                        <div>
                                            <select
                                                v-model="templateTypeAndCategory.category"
                                                class="form-control">

                                                <option value="none">N/A</option>
                                                <option v-for="category in templateCategories" :value="category.value">
                                                    {{ category.label }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label style="color: #333">{{ $t('message.url_prospect.et_type') }}</label>

                                        <div>
                                            <select
                                                v-model="templateTypeAndCategory.type"
                                                class="form-control pull-right">

                                                <option value="none">N/A</option>
                                                <option v-for="type in templateTypes" :value="type.value">
                                                    {{ type.label }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label style="color: #333">{{ $t('message.registration_accounts.filter_lang') }}</label>
                                        <div>
                                            <select
                                                v-model="mailInfo.country.id"
                                                class="form-control pull-right"

                                                @change="doChangeEmailCountry">

                                                <option v-for="option in listLanguages.data" v-bind:value="option.id">
                                                    {{ option.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div  class="form-group">
                                            <label style="color: #333">{{ $t('message.registration_accounts.se_select_template') }} {{ mailInfo.country.name }}</label>
                                            <div>
                                                <select
                                                    v-model="mailInfo.tpl"
                                                    class="form-control pull-right"

                                                    @change="doChangeEmailTemplate">

                                                    <option
                                                        v-for="option in templateFiltered"
                                                        v-bind:value="option.id">
                                                        {{ option.mail_name }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" style="margin-top: 15px;">
                                <div :class="{'form-group': true, 'has-error': messageFormsMail.errors.email}" class="form-group">
<!--                                    <label style="color: #333">{{ $t('message.registration_accounts.ra_email') }}</label>-->
                                    <label style="color: #333">
                                        {{ registrationEmails.length > 1 ? 'Email Automatic Individual' : $t('message.registration_accounts.ra_email') }}
                                    </label>

                                    <vue-tags-input
                                        v-model="email_to"
                                        :disabled="true"
                                        :separators="separators"
                                        :tags="registrationEmails"
                                        :class="{'vue-tag-error': messageFormsMail.errors.email}"
                                        ref="registrationTag"
                                        placeholder=""

                                        @tags-changed="newTags => registrationEmails = newTags"
                                    />

                                    <span
                                        v-if="messageFormsMail.errors.email"
                                        v-for="err in messageFormsMail.errors.email"
                                        class="text-danger">

                                        {{ err }}
                                    </span>

                                    <span v-if="checkCcBccValidationError(messageFormsMail.errors, 'email.')" class="text-danger">
                                        {{ $t('message.inbox.cm_valid') }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input
                                                    v-model="withCcRegistration"
                                                    type="checkbox"
                                                    class="form-check-input"

                                                    @click="withCcRegistration = !withCcRegistration">
                                                With Cc
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input
                                                    v-model="withBccRegistration"
                                                    type="checkbox"
                                                    class="form-check-input"

                                                    @click="withBccRegistration = !withBccRegistration">
                                                {{ $t('message.inbox.cm_bcc') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            </div>

                            <div class="col-md-12" v-show="withCcRegistration">
                                <div class="form-group">
                                    <small
                                        v-if="modelMail.cc.length"
                                        class="text-primary small"
                                        style="cursor: pointer"

                                        @click="modelMail.cc = []">
                                        {{ $t('message.inbox.cm_remove_all_emails') }}
                                    </small>

                                    <vue-tags-input
                                        v-model="registrationCc"
                                        placeholder="Cc"
                                        :max-tags="10"
                                        :allow-edit-tags="true"
                                        :separators="separators"
                                        :tags="modelMail.cc"

                                        @tags-changed="newTagsCc => modelMail.cc = newTagsCc"
                                    />

                                    <span
                                        v-if="messageFormsMail.errors.cc"
                                        v-for="err in messageFormsMail.errors.cc"
                                        class="text-danger">
                                        {{ err }}
                                    </span>

                                    <span v-if="checkCcBccValidationError(messageFormsMail.errors, 'cc.')" class="text-danger">
                                        The cc field must contain valid emails
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-12" v-show="withBccRegistration">
                                <div class="form-group">
                                    <small
                                        v-if="modelMail.bcc.length"
                                        class="text-primary small"
                                        style="cursor: pointer"

                                        @click="modelMail.bcc = []">
                                        {{ $t('message.inbox.cm_remove_all_emails') }}
                                    </small>

                                    <vue-tags-input
                                        v-model="registrationBcc"
                                        placeholder="Bcc"
                                        :max-tags="10"
                                        :allow-edit-tags="true"
                                        :separators="separators"
                                        :tags="modelMail.bcc"

                                        @tags-changed="newTagsBcc => modelMail.bcc = newTagsBcc"
                                    />

                                    <span
                                        v-if="messageFormsMail.errors.bcc"
                                        v-for="err in messageFormsMail.errors.bcc"
                                        class="text-danger">
                                        {{ err }}
                                    </span>

                                    <span v-if="checkCcBccValidationError(messageFormsMail.errors, 'bcc.')" class="text-danger">
                                        The bcc field must contain valid emails
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-12" style="margin-top: 15px;">
                                <div :class="{'form-group': true, 'has-error': messageFormsMail.errors.title}" class="form-group">
                                    <label style="color: #333">{{ $t('message.registration_accounts.se_title_text') }}</label>

                                    <input type="text" v-model="modelMail.title" class="form-control" value="" required="required">

                                    <span
                                        v-if="messageFormsMail.errors.title"
                                        v-for="err in messageFormsMail.errors.title"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageFormsMail.errors.content}" class="form-group">
                                    <label style="color: #333">{{ $t('message.registration_accounts.se_content') }}</label>

                                    <tiny-editor
                                        v-model="modelMail.content"
                                        ref="registrationEmailEditor"
                                        editor-id="registrationEmailEditor">

                                    </tiny-editor>

                                    <span
                                        v-if="messageFormsMail.errors.content"
                                        v-for="err in messageFormsMail.errors.content"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.registration_accounts.se_attachment') }}</label>
                                    <input
                                        multiple
                                        type="file"
                                        class="form-control"
                                        id="file_send_registration"
                                        ref="file_send_registration">
                                </div>
                            </div>
                        </form>
                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" @click="modalCloser()">
                            {{ $t('message.registration_accounts.close') }}
                        </button>

                        <button
                            type="button"
                            class="btn btn-primary"
                            :disabled="!allowSending"

                            @click="submitSendMail">

                            {{ $t('message.registration_accounts.send') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Send Email -->

        <!-- Modal Update Registration -->
        <div class="modal fade" id="modal-update-registration" ref="modalUpdateAccount" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form action="" autocomplete="off" @submit.prevent="">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $t('message.registration_accounts.ur_title') }}</h5>
                            <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                            <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                        </div>
                        <div class="modal-body">

                            <h4 class="text-primary">{{ $t('message.registration_accounts.r_account_info') }}</h4>
                            <hr/>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="alert alert-warning" v-show="!isVerified">
                                        <p>
                                            {{ $t('message.registration_accounts.ur_account_not_verified') }}
                                            <button class="btn btn-default pull-right" @click="verifiedAccount()">
                                                {{ $t('message.registration_accounts.ur_verify') }}
                                            </button>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_account_type') }} <span class="text-danger">*</span></label>
                                        <select class="form-control" name="" v-model="accountUpdate.type" :disabled="isDisabled" @change="checkTeamIncharge('update')">
                                            <option value="Seller">{{ $t('message.registration_accounts.filter_seller') }}</option>
                                            <option value="Buyer">{{ $t('message.registration_accounts.filter_buyer') }}</option>
                                            <option value="Writer">{{ $t('message.registration_accounts.filter_writer') }}</option>
                                            <option value="Affiliate">{{ $t('message.registration_accounts.filter_affiliate') }}</option>
                                        </select>
                                        <span v-if="messageForms.errors.type" v-for="err in messageForms.errors.type" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.ra_username') }} <span class="text-danger">*</span></label>
                                        <input type="text" role="presentation" autocomplete="off" class="form-control" :disabled="true" v-model="accountUpdate.username" name="" aria-describedby="helpId" placeholder="">
                                        <span v-if="messageForms.errors.username" v-for="err in messageForms.errors.username" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.ra_name') }} <span class="text-danger">*</span></label>
                                        <input type="text" role="presentation" autocomplete="off" class="form-control" :disabled="user.isOurs != 0" v-model="accountUpdate.name" name="" aria-describedby="helpId" placeholder="">
                                        <span v-if="messageForms.errors.name" v-for="err in messageForms.errors.name" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.ra_email') }} <span class="text-danger">*</span></label>
                                        <input type="text" role="presentation" autocomplete="off" class="form-control" :disabled="true" v-model="accountUpdate.email" name="" aria-describedby="helpId" placeholder="">
                                        <span v-if="messageForms.errors.email" v-for="err in messageForms.errors.email" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.r_phone') }}</label>
                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountUpdate.phone" name="" aria-describedby="helpId" placeholder="">
                                        <span v-if="messageForms.errors.phone" v-for="err in messageForms.errors.phone" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.r_skype') }}</label>
                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountUpdate.skype">
                                        <span v-if="messageForms.errors.skype" v-for="err in messageForms.errors.skype" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.r_password') }}</label>
                                        <input type="password" role="presentation" autocomplete="off" class="form-control" :disabled="!user.isAdmin" v-model="accountUpdate.password" name="" aria-describedby="helpId" placeholder="">
                                        <span v-if="messageForms.errors.password" v-for="err in messageForms.errors.password" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.r_confirm_password') }}</label>
                                        <input type="password" role="presentation" autocomplete="off" class="form-control" :disabled="!user.isAdmin" v-model="accountUpdate.c_password" name="" aria-describedby="helpId" placeholder="">
                                        <span v-if="messageForms.errors.c_password" v-for="err in messageForms.errors.c_password" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_company_type') }} <span class="text-danger">*</span></label>
                                        <select class="form-control"  v-model="accountUpdate.company_type" @click="checkCompanyType()">
                                            <option value="Company">{{ $t('message.registration_accounts.filter_company') }}</option>
                                            <option value="Freelancer">{{ $t('message.registration_accounts.filter_freelance') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12" v-show="updateCompanyName">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_company_name') }} <span class="text-danger">*</span></label>
                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountUpdate.company_name">
                                        <span v-if="messageForms.errors.company_name" v-for="err in messageForms.errors.company_name" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                                <div class="col-sm-12" v-show="updateCompanyName">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_company_url') }}</label>
                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountUpdate.company_url">
                                        <span v-if="messageForms.errors.company_url" v-for="err in messageForms.errors.company_url" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_country') }}</label>
                                        <select class="form-control" v-model="accountUpdate.country_id">
                                            <option v-for="option in listCountryAll.data" v-bind:value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>
                                            {{ $t('message.registration_accounts.filter_lang') }}
                                        </label>
                                        <small>
                                            <i class="text-danger">* {{ $t('message.registration_accounts.r_required_writer') }}</i>
                                        </small>
                                        <v-select
                                            multiple
                                            v-model="accountUpdate.language_id"
                                            label="name"
                                            :options="listLanguages.data"
                                            :reduce="name => name.id"
                                            :searchable="true"
                                            :placeholder="$t('message.registration_accounts.filter_select_lang')"

                                            @input="checkWriterLanguageChange"/>

                                        <span v-if="messageForms.errors.language_id" v-for="err in messageForms.errors.language_id" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.r_address') }}</label>
                                        <textarea class="form-control" v-model="accountUpdate.address"></textarea>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.r_info') }}</label>
                                        <textarea class="form-control" v-model="accountUpdate.info"></textarea>
                                    </div>
                                </div>

                            </div>

                            <hr v-show="updateDisplayWriterPrice"/>
                            <h4 class="text-primary" v-show="updateDisplayWriterPrice">Writer Pricing</h4>
<!--                            <div v-show="updateDisplayWriterPrice" class="alert alert-info">-->
<!--                                <p>{{ $t('message.registration_accounts.r_reminder_ppw') }}</p>-->
<!--                            </div>-->
                            <hr v-show="updateDisplayWriterPrice"/>

                            <div class="row" v-show="updateDisplayWriterPrice">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.r_pricing_type') }}</label>
                                        <select class="form-control" v-model="accountUpdate.rate_type" @change="checkPriceTypeChange()">
                                            <option value="ppw">{{ $t('message.registration_accounts.r_ppw') }}</option>
                                            <option value="ppa">{{ $t('message.registration_accounts.r_ppa') }}</option>
                                        </select>
                                        <span v-if="messageForms.errors.rate_type" v-for="err in messageForms.errors.rate_type" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row" v-if="updateDisplayWriterPrice">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Price Rate Per Language</label>
                                    </div>

                                    <div
                                        v-for="(language_rates , index) in accountUpdate.writer_language_price_rates"
                                        class="form-group row"
                                        :key="index">

                                        <label
                                            class="col-sm-2 col-form-label"
                                            :for="'writerLanguageRate' + language_rates.id">
                                            {{ language_rates.name }}
                                        </label>
                                        <div class="col-sm-10">
                                            <input
                                                v-model="language_rates.rate"
                                                type="number"
                                                role="presentation"
                                                autocomplete="off"
                                                class="form-control"
                                                :id="'writerLanguageRate' + language_rates.id"

                                                @wheel="$event.target.blur()">
                                        </div>

                                        <span
                                            v-if="messageForms.errors.hasOwnProperty('writer_language_price_rates.'+ index + '.rate')"
                                            v-for="err in messageForms.errors['writer_language_price_rates.'+ index + '.rate']"
                                            class="text-danger">

                                            {{ err }}
                                        </span>
                                    </div>

                                    <small class="text-primary" v-if="accountUpdate.rate_type !== writerPriceTypeTemp">
                                        The price rates has been changed to the default rate according to the
                                        selected pricing type.
                                    </small>
                                </div>
                            </div>

                            <div v-if="accountUpdate.type !== 'Affiliate'">
                                <hr v-if="accountUpdate.is_sub_account === 0"/>

                                <h4 v-if="accountUpdate.is_sub_account === 0" class="text-primary">
                                    {{ $t('message.registration_accounts.r_payment_info') }}
                                </h4>
                                <span v-if="messageForms.errors.id_payment_type" class="text-danger">
                                {{ $t('message.registration_accounts.r_select_default') }}
                            </span>
                                <span v-if="validate_error_type_update" class="text-danger">
                                {{ $t('message.registration_accounts.r_input_selected') }}
                            </span>

                                <!-- payment for seller and writer -->
                                <table class="table table-hover" v-if="accountUpdate.type === 'Seller' || accountUpdate.type === 'Writer'">
                                    <tr>
                                        <td>
                                            <button
                                                v-if="user.isOurs === 0 && selectedUserID !== 0"
                                                class="btn btn-primary btn-sm"

                                                @click="getPaymentSolutionHistory">

                                                <i class="fas fa-history"></i>
                                                Show history
                                            </button>
                                        </td>
                                        <td>{{ $t('message.registration_accounts.filter_default') }}</td>
                                    </tr>
                                    <tr v-for="(payment_method, index) in paymentMethodListSendPayment" :key="index" >
                                        <td>
                                            <div class="form-group">
                                                <label>{{ payment_method.type }} {{ $t('message.registration_accounts.r_account') }}</label>
                                                <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountUpdate.update_method_payment_type[payment_method.id]">
                                            </div>

                                            <span
                                                v-if="messageForms.errors.hasOwnProperty('update_method_payment_type.'+ payment_method.id)"
                                                v-for="err in messageForms.errors['update_method_payment_type.'+ payment_method.id]"
                                                class="text-danger">

                                            {{ err }}
                                        </span>

                                            <div class="px-5"
                                                 v-show="payment_method.account_name ||
                                                payment_method.bank_name ||
                                                payment_method.swift_code ||
                                                payment_method.beneficiary_add ||
                                                payment_method.account_iban ||
                                                payment_method.account_holder ||
                                                payment_method.account_type ||
                                                payment_method.routing_num ||
                                                payment_method.wire_routing_num
                                            "
                                            >
                                                <h6 class="text-primary">Other Details:</h6>
                                                <hr/>

                                                <div class="row">
                                                    <div class="col-sm-12" v-show="payment_method.bank_name">
                                                        <label>Bank Name:</label>
                                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountUpdate.bank_name[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.account_name">
                                                        <label>Account Name:</label>
                                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountUpdate.account_name[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.account_iban">
                                                        <label>Account IBAN:</label>
                                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountUpdate.account_iban[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.swift_code">
                                                        <label>SWIFT Code:</label>
                                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountUpdate.swift_code[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.beneficiary_add">
                                                        <label>Beneficiary Address:</label>
                                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountUpdate.beneficiary_add[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.account_holder">
                                                        <label>Account Holder:</label>
                                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountUpdate.account_holder[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.account_type">
                                                        <label>Account Type:</label>
                                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountUpdate.account_type[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.routing_num">
                                                        <label>Routing Number:</label>
                                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountUpdate.routing_num[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.wire_routing_num">
                                                        <label>Wire Routing Number:</label>
                                                        <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountUpdate.wire_routing_num[payment_method.id]">
                                                    </div>
                                                </div>
                                            </div>

                                        </td>
                                        <td style="width: 50px;vertical-align:middle;" class="text-center">
                                            <input type="radio" class="btn-radio-custom" name="payment_default" v-bind:value="payment_method.id" v-model="accountUpdate.id_payment_type">
                                        </td>
                                    </tr>
                                </table>
                                <!-- end of payment for seller and writer -->

                                <!-- payment for buyer -->
                                <table class="table" v-if="accountUpdate.type === 'Buyer' && accountUpdate.is_sub_account === 0">
                                    <tr>
                                        <td></td>
                                        <td>{{ $t('message.registration_accounts.filter_default') }}</td>
                                    </tr>
                                    <tr v-for="(payment_method, index) in paymentMethodListReceivePayment" :key="index" >
                                        <td>
                                            <div class="form-group">
                                                <label>{{ payment_method.type }} {{ $t('message.registration_accounts.r_account') }}</label>
                                                <input type="text" role="presentation" autocomplete="off" class="form-control" v-model="accountUpdate.update_method_payment_type[payment_method.id]">
                                            </div>

                                            <span
                                                v-if="messageForms.errors.hasOwnProperty('update_method_payment_type.'+ payment_method.id)"
                                                v-for="err in messageForms.errors['update_method_payment_type.'+ payment_method.id]"
                                                class="text-danger">

                                            {{ err }}
                                        </span>
                                        </td>
                                        <td style="width: 50px;vertical-align:middle;" class="text-center">
                                            <input type="radio" class="btn-radio-custom" name="payment_default" v-bind:value="payment_method.id" v-model="accountUpdate.id_payment_type">
                                        </td>
                                    </tr>
                                </table>
                                <!-- end of payment for buyer -->
                            </div>

                            <hr/>
                            <h4 class="text-primary">{{ $t('message.registration_accounts.r_internal_info') }}</h4>
                            <hr/>

                            <div class="row">

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.ur_is_show_price_basis') }} <span class="text-danger">*</span></label>
                                        <select class="form-control" name="" v-model="accountUpdate.is_show_price_basis" :disabled="!user.isAdmin">
                                            <option value="yes">{{ $t('message.registration_accounts.yes') }}</option>
                                            <option value="no">{{ $t('message.registration_accounts.no') }}</option>
                                        </select>
                                        <span v-if="messageForms.errors.is_show_price_basis" v-for="err in messageForms.errors.is_show_price_basis" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_status') }}</label>

                                        <select class="form-control" name="" v-model="accountUpdate.status" :disabled="isDisabled">
                                            <option value="active">{{ $t('message.registration_accounts.filter_active') }}</option>
                                            <option value="inactive">{{ $t('message.registration_accounts.filter_inactive') }}</option>
                                        </select>

                                        <small class="text-primary" v-if="accountUpdate.status == 'active' && accountUpdate.account_validation == 'invalid'">
                                            {{ $t('message.registration_accounts.ur_account_invalid') }}
                                        </small>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <label>{{ $t('message.registration_accounts.filter_credit_auth') }}</label>
                                    <select class="form-control" name="" v-model="accountUpdate.credit_auth" :disabled="!user.isAdmin">
                                        <option value="Yes">{{ $t('message.registration_accounts.yes') }}</option>
                                        <option value="No">{{ $t('message.registration_accounts.no') }}</option>
                                    </select>
                                </div>

                                <div class="col-sm-6">
                                    <label>{{ $t('message.registration_accounts.filter_in_charge') }}</label>
                                    <select class="form-control" name="" v-model="accountUpdate.team_in_charge">
                                        <option value="">N/A</option>
                                        <option v-for="option in listTeamIncharge" v-bind:value="option.id">
                                            {{ option.username == null || option.username == '' ? option.name:option.username}}
                                        </option>
                                    </select>
                                </div>

                                <div class="col-sm-6" v-show="user.role_id === 8 || user.isAdmin">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_account_validation') }} <span class="text-danger">*</span></label>
                                        <select class="form-control" name="" v-model="accountUpdate.account_validation">
                                            <option value="valid">{{ $t('message.registration_accounts.filter_valid') }}</option>
                                            <option value="invalid">{{ $t('message.registration_accounts.filter_invalid') }}</option>
                                            <option value="processing">{{ $t('message.registration_accounts.filter_processing') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.filter_commission') }} <span class="text-danger">*</span></label>
                                        <select class="form-control" name="" v-model="accountUpdate.commission" :disabled="!user.isAdmin">
                                            <option value="yes">{{ $t('message.registration_accounts.yes') }}</option>
                                            <option value="no">{{ $t('message.registration_accounts.no') }}</option>
                                        </select>
                                        <span v-if="messageForms.errors.commission" v-for="err in messageForms.errors.commission" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                                <div class="col-sm-6" v-if="accountUpdate.type == 'Buyer' && user.isAdmin">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.r_affiliates') }}</label>
                                        <select class="form-control" v-model="accountUpdate.affiliate">
                                            <option value="">{{ $t('message.registration_accounts.none') }}</option>
                                            <option v-for="option in listAffiliate.data" v-bind:value="option.id">
                                                {{ option.username == null ? option.name : option.username }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6" v-show="accountUpdate.type == 'Buyer' && user.isAdmin">
                                    <div class="form-group">
                                        <label>{{ $t('message.registration_accounts.r_type_of_buyer') }}</label>
                                        <select class="form-control" name="" v-model="accountUpdate.buyer_type">
                                            <option value="Basic">Basic</option>
                                            <option value="Medium">Medium</option>
                                            <option value="Premium">Premium</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6" v-show="accountUpdate.type == 'Seller'">
                                    <div class="form-group">
                                        <label>Recommended</label>
                                        <select class="form-control" name="" v-model="accountUpdate.is_recommended">
                                            <option value="no">No</option>
                                            <option value="yes">Yes</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal" @click="selectedUserID = 0; writerPriceTypeTemp = null;">
                                {{ $t('message.registration_accounts.close') }}
                            </button>
                            <button type="button" @click="submitUpdate" class="btn btn-primary">
                                {{ $t('message.registration_accounts.update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End of Modal Update Registration -->

        <!-- Modal Multiple Update In Charge -->
        <div
            role="dialog"
            tabindex="-1"
            class="modal fade"
            ref="modalMultipleUpdateIncharge"
            aria-hidden="true"
            aria-labelledby="modelTitleId">

            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.registration_accounts.uc_title') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select
                                        v-model="updateMultipleInCharge"
                                        value=""
                                        type="text"
                                        required="required"
                                        class="form-control">

                                        <option value="">N/A</option>
                                        <option v-for="option in listTeamIncharge" v-bind:value="option.id">
                                            {{
                                                option.username == null || option.username === ''
                                                    ? option.name
                                                    :option.username
                                            }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.registration_accounts.close') }}
                        </button>
                        <button type="button" class="btn btn-primary" @click="submitUpdateMultipleInCharge()">
                            {{ $t('message.registration_accounts.update') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Multiple Update In Charge -->

        <!-- Modal Payment Solution History -->
        <div
            class="modal fade"
            ref="modalPaymentSolutionHistory"
            style="display: none;"
            id="modalPaymentSolutionHistory">

            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            Payment Information History
                        </h4>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table
                                        id="tbl_payment_solutions"
                                        style="font-size: 0.75rem"
                                        class="table table-bordered">

                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Account</th>
                                                <th>Default</th>
                                                <th>Status</th>
                                                <th>Dates</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <template v-for="solution in paymentSolutionHistory">
                                                <tr>
                                                    <td>{{ solution.id }}</td>
                                                    <td>
                                                        <span class="font-weight-bold mb-2">{{ solution.account }}</span>
                                                        <br>
                                                        <small>{{ solution.payment_type.type }}</small>

                                                        <small
                                                            v-if="solution.payment_type.id === 10
                                                            || solution.payment_type.type === 'Bank Transfer'"
                                                            style="cursor: pointer;"
                                                            class="text-primary font-italic accordion-toggle"
                                                            aria-expanded="true"
                                                            data-toggle="collapse"
                                                            :ref="'solution-collapse-' + solution.id"
                                                            :aria-controls="'solution-collapse' + solution.id"

                                                            @click="viewBankTransferDetails(solution)">

                                                            <u>View Details</u>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <span :class="solution.is_default === 1
                                                        ? 'badge badge-primary'
                                                        : 'badge badge-secondary'">

                                                            {{ solution.is_default === 1 ? 'YES' : 'NO' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span :class="solution.deleted_at === null
                                                        ? 'badge badge-success'
                                                        : 'badge badge-danger'">

                                                            {{ solution.deleted_at === null ? 'ACTIVE' : 'DELETED' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span>
                                                            <i
                                                                class="fas fa-calendar-plus text-success"
                                                                title="Date created">
                                                            </i>
                                                            {{ solution.created_at }}
                                                        </span>
                                                        <br>
                                                        <span v-if="solution.deleted_at">
                                                            <i
                                                                class="fas fa-calendar-times text-danger"
                                                                title="Date deleted">
                                                            </i>
                                                            {{ solution.deleted_at }}
                                                        </span>
                                                    </td>
                                                </tr>

                                                <tr
                                                    v-if="solution.payment_type.id === 10
                                                    || solution.payment_type.type === 'Bank Transfer'">

                                                    <td colspan="12" class="p-0" style="background: #629aa957">
                                                        <div
                                                            class="accordion-body collapse m-3"
                                                            :id="'solution-collapse' + solution.id">

                                                            <div class="card">
                                                                <div v-if="returnSolutionDetailsObject(paymentSolutionDetailValues, solution.id) !== undefined" class="card-body">
                                                                    <table
                                                                        style="background: #f5f8fa"
                                                                        class="table table-hover"
                                                                        :id="'tbl-solution-details-' + solution.id">
                                                                        <tbody>
                                                                            <template v-for="(detail, key) in returnSolutionDetailsObject(paymentSolutionDetailValues, solution.id)">
                                                                                <tr v-if="key !== 'id'">
                                                                                    <th>{{ key.replaceAll('_', ' ') }}</th>
                                                                                    <td>
                                                                                        <span v-if="detail === undefined || detail === null" class="badge badge-danger">
                                                                                            N/A
                                                                                        </span>
                                                                                        <span v-else>{{ detail }}</span>
                                                                                    </td>
                                                                                </tr>
                                                                            </template>

                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Payment Solution History -->

        <terms-and-conditions></terms-and-conditions>
    </div>
</template>

<style scoped>
    .btn-radio-custom {
        transform: scale(2);
    }
</style>

<script>
import {mapActions, mapState} from 'vuex';
import __ from "lodash";
import axios from 'axios';
import TermsAndConditions from "../../../components/terms/TermsAndConditions";
import {createTags} from "@johmun/vue-tags-input";
import TinyEditor from "../../../components/editor/TinyEditor";
import {dateRange} from "../../../mixins/dateRange";

export default {
    components : {TermsAndConditions, TinyEditor},
    mixins: [dateRange],
    data() {
        return {
            paginate : [
                15,
                25,
                50,
                100,
                200,
                250,
                'All'
            ],
            accountModel : {
                name : '',
                email : '',
                phone : '',
                password : '',
                c_password : '',
                type : '',
                company_name : '',
                company_url : '',
                skype : '',
                id_payment_type : '',
                // payment_email : '',
                payment_account : '',
                skrill_account : '',
                paypal_account : '',
                btc_account : '',
                commission : '',
                team_in_charge : '',
                account_validation : '',
                address : '',
                info : '',
                country_id : '',
                language_id : '',
                company_type : 'Company',
                writer_price : '',
                rate_type : '',
                add_method_payment_type: [],
                bank_name: [],
                account_name: [],
                account_iban: [],
                swift_code: [],
                beneficiary_add: [],
                account_holder: [],
                account_type: [],
                routing_num: [],
                wire_routing_num: [],
                buyer_type: 'Basic',
                is_recommended: 'no',
            },

            filterModel : {
                type : this.$route.query.type || '',
                search : this.$route.query.search || '',
                status : this.$route.query.status || '',
                paginate : this.$route.query.paginate || '15',
                team_in_charge : this.$route.query.team_in_charge || '',
                affiliate : this.$route.query.affiliate || '',
                country : this.$route.query.country || '',
                language_id : this.$route.query.language_id || '',
                commission : this.$route.query.commission || '',
                credit_auth : this.$route.query.credit_auth || '',
                company_type : this.$route.query.company_type || '',
                company_name : this.$route.query.company_name || '',
                company_url : this.$route.query.company_url || '',
                account_validation : this.$route.query.account_validation || '',
                payment_info : this.$route.query.payment_info || '',
                payment_info_data : this.$route.query.payment_info_data || '',
                created_at : {
                    startDate : null,
                    endDate : null
                },
                account_verification :
                    this.$route.query.account_verification || '',
                is_sub_account : this.$route.query.is_sub_account || '',
                buyer_transaction : this.$route.query.buyer_transaction || '',
                advance_search: this.$route.query.advance_search || ''
            },

            accountUpdate : {
                id : '',
                name : '',
                email : '',
                phone : '',
                password : '',
                c_password : '',
                type : '',
                company_name : '',
                company_url : '',
                skype : '',
                id_payment_type : '',
                // payment_email : '',
                payment_account : '',
                commission : '',
                status : '',
                skrill_account : '',
                paypal_account : '',
                btc_account : '',
                username : '',
                team_in_charge : '',
                account_validation : '',
                address : '',
                info : '',
                country_id : '',
                language_id : '',
                company_type : '',
                writer_price : '',
                rate_type : '',
                is_show_price_basis : '',
                update_method_payment_type: [],
                is_sub_account: '',
                bank_name: [],
                account_name: [],
                account_iban: [],
                swift_code: [],
                beneficiary_add: [],
                account_holder: [],
                account_type: [],
                routing_num: [],
                wire_routing_num: [],
                buyer_type: '',
                writer_language_price_rates: [],
            },

            isPopupLoading : false,
            isSearchLoading : false,
            isDisabled : true,
            isTeamSeller : true,
            isSearching : false,
            addCompanyName : true,
            updateCompanyName : true,
            listTeamIncharge : [],
            isVerified : true,
            btnTermsAndConditions : false,
            isDisableSubmit : true,
            allowSending : false,
            isErrorLang: false,

            // for email sending
            mailInfo : {
                tpl : 0,
                ids : '',
                receiver_text : '',
                country : {
                    id : 0,
                    name : '',
                    code : ''
                }
            },

            modelMail : {
                cc: [],
                bcc: [],
                mode: 'send',
                title : '',
                content : '',
                mail_name : '',
            },
            email_to : '',
            registrationEmails : [],
            separators : [
                ';',
                ',',
                '|',
                ' '
            ],
            addDisplayWriterPrice : false,
            updateDisplayWriterPrice : false,

            checkIds : [],
            isDisabledAction : true,
            allSelected : false,
            updateMultipleInCharge : '',
            listUserEmail : [],
            paymentMethodList:[],
            validate_error_type: false,
            validate_error_type_update: false,

            templateTypeAndCategory: {
                type: '',
                category: ''
            },

            isAdvanceSearching: false,

            registrationCc: '',
            registrationBcc: '',
            withCcRegistration: false,
            withBccRegistration: false,

            // payment solution history
            selectedUserID: 0,
            paymentSolutionHistory: [],
            paymentSolutionDetails: {
                id: '',
                bank_name: '',
                account_name: '',
                account_iban: '',
                swift_code: '',
                beneficiary_add: '',
                account_holder: '',
                account_type: '',
                routing_num: '',
                wire_routing_num: '',
            },
            paymentSolutionDetailValues: [],

            writerPriceTypeTemp: null,
            writerPriceRatesTemp: [],
            writerPriceRatesTemp2: [],
        }
    },

    async created() {
        this.getAccountList();
        this.getPaymentTypeList();
        this.checkAccessRole();
        this.getTeamInCharge();
        this.getAffiliates();
        this.checkTeamSeller();
        this.getListCountries();
        this.getListLanguages();
        this.getListEmails();
        this.getListPaymentMethod();
    },

    computed : {
        ...mapState({
            tblAccountsOpt : state => state.storeAccount.tblAccountsOpt,
            messageForms : state => state.storeAccount.messageForms,
            messageFormsExt : state => state.storeExtDomain.messageForms,
            messageFormsMail : state => state.storeMailgun.messageForms,
            listAccount : state => state.storeAccount.listAccount,
            listPayment : state => state.storeAccount.listPayment,
            listIncharge : state => state.storeAccount.listIncharge,
            listAffiliate : state => state.storeAccount.listAffiliate,
            user : state => state.storeAuth.currentUser,
            listCountryAll : state => state.storePublisher.listCountryAll,
            listLanguages : state => state.storePublisher.listLanguages,
            listMailTemplate : state => state.storeExtDomain.listMailTemplate,
        }),

        paymentMethodListSendPayment: function() {
            return this.paymentMethodList.filter(i => i.send_payment === 'yes')
        },

        paymentMethodListReceivePayment: function() {
            return this.paymentMethodList.filter(i => i.receive_payment === 'yes')
        },

        adminAccessOptions() {
            let self = this;
            const emails = self.listUserEmail;

            emails.forEach(item => {
                if (item.work_mail === self.user.work_mail_orig) {
                    item.role.name = 'Me';
                }
            });

            return emails;
        },

        templateCategories () {
            return [
                {
                    label: this.$t('message.template.prospect'),
                    value: 'prospect'
                },
                {
                    label: this.$t('message.template.follow'),
                    value: 'follow'
                },
            ]
        },

        templateTypes () {
            return [
                {
                    label: this.$t('message.template.corporate'),
                    value: 'corporate'
                },
                {
                    label: this.$t('message.template.straight'),
                    value: 'straight'
                },
            ]
        },

        templateFiltered() {
            let self = this;

            return (self.templateTypeAndCategory.category === '' && self.templateTypeAndCategory.type === '')
                ? self.listMailTemplate.data
                : self.listMailTemplate.data.filter(function(item) {
                    if (self.templateTypeAndCategory.category && self.templateTypeAndCategory.type === '') {

                        if (self.templateTypeAndCategory.category === 'none') {
                            return (item['category'] === null);
                        } else {
                            return (item['category'] === self.templateTypeAndCategory.category);
                        }

                    } else if (self.templateTypeAndCategory.type && self.templateTypeAndCategory.category === '') {

                        if (self.templateTypeAndCategory.type === 'none') {
                            return (item['type'] === null);
                        } else {
                            return (item['type'] === self.templateTypeAndCategory.type);
                        }

                    } else if (self.templateTypeAndCategory.type && self.templateTypeAndCategory.category) {

                        if (self.templateTypeAndCategory.type === 'none' && self.templateTypeAndCategory.category !== 'none') {
                            return (item['type'] === null && item['category'] === self.templateTypeAndCategory.category);
                        } else if (self.templateTypeAndCategory.category === 'none' && self.templateTypeAndCategory.type !== 'none') {
                            return (item['category'] === null && item['type'] === self.templateTypeAndCategory.type);
                        } else if (self.templateTypeAndCategory.category === 'none' && self.templateTypeAndCategory.type === 'none') {
                            return (item['category'] === null && item['type'] === null);
                        } else {
                            return (item['type'] === self.templateTypeAndCategory.type && item['category'] === self.templateTypeAndCategory.category);
                        }

                    }
                })
        },

        writerEarningsRegistration () {
            let self = this;

            if (self.accountModel.language_id && self.accountModel.language_id.length) {
                let languages = self.listLanguages.data.filter(obj => {
                    return self.accountModel.language_id.includes(obj.id);
                });

                let newArray =  languages.map(a => { return Object.values({
                    ppa_rate: Math.floor(a.ppa_rate),
                    ppw_rate: Math.floor((a.ppw_rate * 1000)),
                    ppw_rate_max: Math.floor((a.ppw_rate * 1500))
                })
                }).flat();

                return {
                    from: Math.min(...newArray),
                    to: Math.max(...newArray)
                }
            } else {
                return {
                    from: 0,
                    to: 0
                }
            }
        }
    },

    methods : {
        ...mapActions({
            clearMessageFormEmail : "clearMessageform",
        }),

        getListPaymentMethod() {
            axios.get('/api/payment-list-registration')
                .then((res) => {
                    this.paymentMethodList = res.data.data
                })
        },

        saveMessageAsDraft(data) {
            let self = this;
            axios.post('/api/mail/save-draft', data).then((response) => {
                swal.fire({
                    title: self.$t('message.registration_accounts.alert_save_draft'),
                    icon: 'success'
                });
            });
        },

        sendDepositEmail (account) {
            let self = this;
            let accounts = [];

            if (this.checkUserWorkMail()) {

                if (account) {

                    if (account.user) {

                        if (account.account_validation === 'valid') {

                            if (account.email_via_others !== 'deposit_email') {

                                if (account.type === 'Buyer') {

                                    if (account.user.wallet_transactions_count === 0) {

                                        accounts.push({
                                            id: account.id,
                                            email: account.email
                                        })

                                        this.sendDepositEmailFunction(accounts)

                                    } else {

                                        swal.fire(
                                            self.$t('message.registration_accounts.alert_invalid'),
                                            self.$t('message.registration_accounts.alert_no_wallet'),
                                            'error'
                                        )

                                    }

                                } else {

                                    swal.fire(
                                        self.$t('message.registration_accounts.alert_invalid'),
                                        self.$t('message.registration_accounts.alert_type_buyer'),
                                        'error'
                                    )

                                }

                            } else {

                                swal.fire(
                                    self.$t('message.registration_accounts.alert_invalid'),
                                    self.$t('message.registration_accounts.alert_deposit_sent'),
                                    'error'
                                )

                            }

                        } else {

                            swal.fire(
                                self.$t('message.registration_accounts.alert_invalid'),
                                self.$t('message.registration_accounts.alert_should_valid'),
                                'error'
                            )

                        }

                    } else {

                        swal.fire(
                            self.$t('message.registration_accounts.alert_invalid'),
                            self.$t('message.registration_accounts.alert_account_not_verified'),
                            'error'
                        )

                    }
                }

            } else {

                swal.fire(
                    self.$t('message.registration_accounts.alert_invalid'),
                    self.$t('message.registration_accounts.alert_work_mail_not_set'),
                    'error'
                );

            }
        },

        sendMultipleDepositEmail () {
            let self = this;

            if (self.checkUserWorkMail()) {

                if (self.checkIds.length) {

                    // check number of selected accounts
                    if (self.checkIds.length <= 10) {

                        // check if some accounts are not yet verified
                        let isAllVerified = self.checkIds.every(account => account.user !== null)

                        if (isAllVerified) {

                            // check if some accounts are not yet validated
                            let isAllValid = self.checkIds.every(account => account.account_validation === 'valid')

                            if (isAllValid) {

                                // check if some accounts has already been sent a validation email
                                let isNotAlreadySent = self.checkIds.every(account => account.email_via_others !== 'deposit_email')

                                if (isNotAlreadySent) {

                                    // check if selected accounts are all buyers
                                    let buyers = self.checkIds.every(account => account.type === 'Buyer')

                                    if (buyers) {

                                        // check if selected accounts are all without wallet transactions
                                        let isAllWithoutTransactions = self.checkIds.every(account => account.user.wallet_transactions_count === 0)

                                        if (isAllWithoutTransactions) {

                                            let accounts = self.checkIds.map(function(account) {
                                                return {
                                                    id: account.id,
                                                    email: account.email
                                                }
                                            });

                                            self.sendDepositEmailFunction(accounts)
                                            self.checkIds = [];

                                        } else {

                                            swal.fire(
                                                self.$t('message.registration_accounts.alert_invalid'),
                                                self.$t('message.registration_accounts.alert_buyers_without_wallet'),
                                                'error'
                                            );

                                        }

                                    } else {

                                        swal.fire(
                                            self.$t('message.registration_accounts.alert_invalid'),
                                            self.$t('message.registration_accounts.alert_selected_buyer'),
                                            'error'
                                        );

                                    }

                                } else {

                                    swal.fire(
                                        self.$t('message.registration_accounts.alert_invalid'),
                                        self.$t('message.registration_accounts.alert_selected_deposit_sent'),
                                        'error'
                                    );

                                }

                            } else {

                                swal.fire(
                                    self.$t('message.registration_accounts.alert_invalid'),
                                    self.$t('message.registration_accounts.alert_selected_should_valid'),
                                    'error'
                                );

                            }

                        } else {

                            swal.fire(
                                self.$t('message.registration_accounts.alert_invalid'),
                                self.$t('message.registration_accounts.alert_selected_not_verified'),
                                'error'
                            );

                        }

                    } else {

                        swal.fire(
                            self.$t('message.registration_accounts.alert_invalid'),
                            self.$t('message.registration_accounts.alert_maximum_10'),
                            'error'
                        );

                    }

                } else {

                    swal.fire(
                        self.$t('message.registration_accounts.alert_invalid'),
                        self.$t('message.registration_accounts.alert_selection_empty'),
                        'error'
                    );

                }

            } else {

                swal.fire(
                    self.$t('message.registration_accounts.alert_invalid'),
                    self.$t('message.registration_accounts.alert_work_mail_not_set'),
                    'error'
                );

            }
        },

        sendValidationEmail (account) {
            let self = this;
            let accounts = [];

            if (this.checkUserWorkMail()) {

                if (account) {

                    if (account.verification_code === null) {

                        if (account.email_via !== 'validation_email') {

                            if (account.account_validation === 'processing') {

                                accounts.push({
                                    id: account.id,
                                    email: account.email
                                })

                                this.sendValidationEmailFunction(accounts)

                            } else {

                                swal.fire(
                                    self.$t('message.registration_accounts.alert_invalid'),
                                    self.$t('message.registration_accounts.alert_account_should_processing'),
                                    'error'
                                )

                            }

                        } else {

                            swal.fire(
                                self.$t('message.registration_accounts.alert_invalid'),
                                self.$t('message.registration_accounts.alert_validation_already_sent'),
                                'error'
                            )

                        }

                    } else {

                        swal.fire(
                            self.$t('message.registration_accounts.alert_invalid'),
                            self.$t('message.registration_accounts.alert_not_verified'),
                            'error'
                        )

                    }
                }

            } else {

                swal.fire(
                    self.$t('message.registration_accounts.alert_invalid'),
                    self.$t('message.registration_accounts.alert_work_mail_not_set'),
                    'error'
                );

            }
        },

        sendMultipleValidationEmail() {
            let self = this;

            if (self.checkUserWorkMail()) {

                if (self.checkIds.length) {

                    // check number of selected accounts
                    if (self.checkIds.length <= 10) {

                        // check if some accounts are not yet verified
                        let isAllVerified = self.checkIds.every(account => account.verification_code === null)

                        if (isAllVerified) {

                            // check if some accounts has already been sent a validation email
                            let isNotAlreadySent = self.checkIds.every(account => account.email_via !== 'validation_email')

                            if (isNotAlreadySent) {

                                // check if selected accounts are all processing
                                let processing = self.checkIds.every(account => account.account_validation === 'processing')

                                if (processing) {

                                    let accounts = self.checkIds.map(function(account) {
                                        return {
                                            id: account.id,
                                            email: account.email
                                        }
                                    });

                                    self.sendValidationEmailFunction(accounts)
                                    self.checkIds = [];

                                } else {

                                    swal.fire(
                                        self.$t('message.registration_accounts.alert_invalid'),
                                        self.$t('message.registration_accounts.alert_selected_processing'),
                                        'error'
                                    );

                                }

                            } else {
                                swal.fire(
                                    self.$t('message.registration_accounts.alert_invalid'),
                                    self.$t('message.registration_accounts.alert_selected_validation_sent'),
                                    'error'
                                );
                            }

                        } else {
                            swal.fire(
                                self.$t('message.registration_accounts.alert_invalid'),
                                self.$t('message.registration_accounts.alert_selected_must_be_verified'),
                                'error'
                            );
                        }

                    } else {

                        swal.fire(
                            self.$t('message.registration_accounts.alert_invalid'),
                            self.$t('message.registration_accounts.alert_maximum_10'),
                            'error'
                        );

                    }

                } else {

                    swal.fire(
                        self.$t('message.registration_accounts.alert_invalid'),
                        self.$t('message.registration_accounts.alert_selection_empty'),
                        'error'
                    );

                }

            } else {

                swal.fire(
                    self.$t('message.registration_accounts.alert_invalid'),
                    self.$t('message.registration_accounts.alert_work_mail_not_set'),
                    'error'
                );

            }
        },

        sendValidationEmailFunction (accounts) {
            let self = this;
            let loader = this.$loading.show();

            axios.post('/api/mail/send-validation-email', {
                accounts : accounts,
            }).then((res) => {
                loader.hide();

                this.getAccountList();

                swal.fire(
                    self.$t('message.registration_accounts.alert_success'),
                    self.$t('message.registration_accounts.alert_validation_sent'),
                    'success'
                )
            }).catch((err) => {
                loader.hide();
                console.log(err)
            })
        },

        sendDepositEmailFunction (accounts) {
            let self = this;

            let loader = this.$loading.show();

            axios.post('/api/mail/send-deposit-email', {
                accounts : accounts,
            }).then((res) => {
                loader.hide();

                this.getAccountList();

                swal.fire(
                    self.$t('message.registration_accounts.alert_success'),
                    self.$t('message.registration_accounts.alert_deposit_sent_successfully'),
                    'success'
                )
            }).catch((err) => {
                loader.hide();
                console.log(err)
            })
        },

        checkUserWorkMail() {
            return !!this.user.work_mail_orig;
        },

        modalCloser() {
            let self = this;
            if (this.modelMail.title || this.modelMail.content || this.modelMail.cc.length !== 0 || this.modelMail.bcc.length !== 0) {

                swal.fire({
                    title : self.$t('message.registration_accounts.alert_save_as_draft'),
                    text : self.$t('message.registration_accounts.alert_save_draft_note'),
                    icon : "question",
                    showCloseButton: true,
                    showCancelButton: true,
                    showConfirmButton: true,
                    cancelButtonText: self.$t('message.registration_accounts.no'),
                    confirmButtonText: self.$t('message.registration_accounts.yes'),
                    cancelButtonColor: 'red',
                    confirmButtonColor: 'green',
                })
                    .then((result) => {
                        if (result.isConfirmed) {

                            // save message content as draft

                            // add email array
                            this.modelMail.email = this.registrationEmails

                            this.saveMessageAsDraft(this.modelMail);

                            this.closeModal()
                            this.clearMailModel()

                        } else if (result.dismiss == 'cancel') {

                            // remove all images inserted on editor
                            this.$refs.registrationEmailEditor.deleteImages('All');

                            this.closeModal()
                            this.clearMailModel()

                        }
                    });

            } else {
                this.$refs.registrationEmailEditor.deleteImages('All');
                this.clearMailModel()
                this.closeModal()
            }

            this.withCcProspect = false;
            this.withBccProspect = false;
        },

        closeModal() {
            let element = this.$refs.modalEmailRegistration
            $(element).modal('hide')

            this.templateTypeAndCategory = {
                type: '',
                category: ''
            };

            this.mailInfo = {
                tpl : 0,
                ids : '',
                receiver_text : '',
                country : {
                    id : 0,
                    name : '',
                    code : ''
                }
            };
        },

        clearMailModel() {
            this.modelMail = {
                cc: [],
                bcc: [],
                title : '',
                content : '',
                mail_name : '',
            }
        },

        getListEmails() {
            axios.get('/api/mail/get-mail-list').then((response) => {
                this.listUserEmail = response.data;
            });
        },

        selectAll() {
            this.checkIds = [];
            if (!this.allSelected) {
                for (let account in this.listAccount.data) {
                    this.checkIds.push(this.listAccount.data[account]);
                }
            }
            this.allSelected = !this.allSelected;
            this.checkSelected()
        },

        checkSelected() {
            this.isDisabledAction = this.checkIds.length <= 0;
        },

        doSendEmail(data) {
            let self = this;
            this.registrationEmails = [];
            let emails = [];

            if (this.user.work_mail) {

                if (data === null) {

                    if (this.checkIds.length === 0) {
                        swal.fire(
                            self.$t('message.registration_accounts.alert_invalid'),
                            self.$t('message.registration_accounts.alert_selection_empty'),
                            'error'
                        );
                    } else if (this.checkIds.length > 10) {
                        swal.fire(
                            self.$t('message.registration_accounts.alert_invalid'),
                            self.$t('message.registration_accounts.alert_10_recipients'),
                            'error'
                        )
                    } else {
                        this.checkIds.forEach(function (item) {
                            if (item.email !== "" || item.email != null) {
                                if (typeof (item.email) === "string") {
                                    emails.push(item.email.split('|'))
                                } else {
                                    emails.push(item.email.map(a => a.text))
                                }
                            }
                        })

                        this.registrationEmails = createTags(emails.flat())

                        this.openSendEmailModal();
                    }

                } else {

                    if (typeof (data.email) === "string") {
                        emails = data.email.split('|')
                    } else {
                        emails = data.email.map(a => a.text);
                    }

                    this.registrationEmails = emails ? createTags(emails) : [];

                    this.openSendEmailModal();

                }
            } else {
                swal.fire(
                    self.$t('message.registration_accounts.alert_error'),
                    self.$t('message.registration_accounts.alert_work_mail'),
                    'error'
                )
            }
        },

        openSendEmailModal() {
            let element = this.$refs.modalEmailRegistration;
            $(element).modal('show');
            this.allowSending = true;
        },

        doChangeEmailCountry() {
            let that = this, data = {};
            let list = this.listLanguages.data

            list.forEach(function (item) {
                if (item.id === that.mailInfo.country.id) {
                    data = item;
                }
            });

            this.mailInfo.country.id = data.id;
            this.mailInfo.country.name = data.name;
            this.mailInfo.country.code = data.code;

            this.fetchTemplateMail(this.mailInfo.country.id);
        },

        async fetchTemplateMail(countryId) {
            await this.$store.dispatch('getListMails', {params : {country_id : countryId, full_page : 1}});
        },

        async doChangeEmailTemplate() {
            let that = this;
            let temp = this.listMailTemplate.data.filter(item => item.id === that.mailInfo.tpl)[0];

            if (temp) {
                this.modelMail.mail_name = temp.mail_name;
                this.modelMail.title = temp.title;
                this.modelMail.content = this.convertLineBreaks(temp.content);
            }
        },

        convertLineBreaks(str) {
            return str.replace(/(?:\r\n|\r|\n)/g, '<br />');
        },

        async submitSendMail() {
            let self = this;
            this.allowSending = false;
            let loader = this.$loading.show();

            // create form data

            let formData = new FormData();
            formData.append('cc', JSON.stringify(this.modelMail.cc));
            formData.append('bcc', JSON.stringify(this.modelMail.bcc));
            formData.append('email', JSON.stringify(this.registrationEmails));
            formData.append('title', this.modelMail.title);
            formData.append('content', this.modelMail.content);
            formData.append('work_mail', this.user.work_mail);
            formData.append('from', 'registration');

            // get attachments

            let attachments = this.$refs.file_send_registration.files;

            if (!attachments.length) {
                formData.append('attachment', 'undefined');
            } else {
                for (let i = 0; i < attachments.length; i++) {
                    formData.append('attachment[]', attachments[i]);
                }
            }

            // await this.$store.dispatch('sendMailWithMailgun', {
            //     cc: '',
            //     email: this.registrationEmails,
            //     title: this.modelMail.title,
            //     content: this.modelMail.content,
            //     attachment: 'undefined',
            // })

            await this.$store.dispatch('actionSendMailgun', formData);

            if (this.messageFormsMail.action === 'success') {
                // remove all images inserted on editor
                this.$refs.registrationEmailEditor.deleteImages('Removed');

                if (this.mailInfo.tpl === 0) {
                    this.modelMail = {
                        cc: [],
                        bcc: [],
                        title : '',
                        content : '',
                        mail_name : '',
                    }
                } else {
                    this.modelMail.cc = [];
                    this.modelMail.bcc = [];
                    this.modelMail.mode = 'send';
                }

                this.withBccRegistration = false;
                this.withCcRegistration = false;

                $("#modal-email-registration").modal('hide')

                loader.hide();

                await swal.fire(
                    self.$t('message.registration_accounts.alert_success'),
                    self.$t('message.registration_accounts.alert_email_sent'),
                    'success'
                )
                this.allowSending = true;

                this.checkIds = [];
                this.checkSelected();
                this.allSelected = false;

                this.$refs.file_send_registration.value = "";

                // clear message forms

                this.clearMessageFormMail()
            } else {
                loader.hide();

                await swal.fire(
                    self.$t('message.registration_accounts.alert_error'),
                    self.$t('message.registration_accounts.alert_error_send_email'),
                    'error'
                )
                this.allowSending = true;
            }
        },

        async getListLanguages() {
            await this.$store.dispatch('actionGetListLanguages');
        },

        displayEmailPayment(account) {
            let email = '';

            if(typeof account.user != "undefined" && account.user != null){
                if(account.user.user_payment_types.length > 0) {
                    for(let index in account.user.user_payment_types) {
                        if(account.user.user_payment_types[index].is_default === 1 ) {
                            let _type = '';
                            if(account.user.user_payment_types[index].payment_type) {
                                _type = account.user.user_payment_types[index].payment_type.type
                            }
                            email = account.user.user_payment_types[index].account + ' <span class="badge badge-success">'+ _type +'</span>';
                        }
                    }
                }
            }

            return email;
        },

        async verifiedAccount() {
            let self = this;
            this.isPopupLoading = true;
            await this.$store.dispatch('actionVerifyAccount', this.accountUpdate);

            if (this.messageForms.action === 'verified_account') {
                this.isVerified = true;

                this.getAccountList();

                swal.fire(
                    self.$t('message.registration_accounts.alert_success'),
                    self.$t('message.registration_accounts.alert_account_verified'),
                    'success'
                );
            } else {
                swal.fire(
                    self.$t('message.registration_accounts.alert_error'),
                    self.$t('message.registration_accounts.alert_account_not_verified_error'),
                    'error'
                );
            }

            this.isPopupLoading = false;

            //  axios.post('/api/verify-account', this.accountUpdate)
            // .then((res) => {
            //     if( res.data.success === true ) {
            //         this.isVerified = true;
            //
            //         swal.fire(
            //             'Verify',
            //             'Successfully Verified!',
            //             'success'
            //         );
            //     }
            // })
        },

        checkVerified() {
            axios.get('/api/get-verified-account', {
                params : {
                    email : this.accountUpdate.email
                }
            })
                .then((res) => {
                    if (res.data.success === true) {
                        this.isVerified = res.data.success
                    }
                })
                .catch(res => {
                    if (res.response.data.success === false) {
                        this.isVerified = res.response.data.success
                    }
                })
        },

        checkTeamIncharge(method) {
            let role = (method == 'add') ? this.accountModel.type : this.accountUpdate.type;

            if (role == 'Writer') {
                this.addDisplayWriterPrice = true;
                this.updateDisplayWriterPrice = true;

                if (method === 'update') {
                    this.writerPriceTypeTemp = this.accountUpdate.rate_type;
                }
            } else {
                this.addDisplayWriterPrice = false;
                this.updateDisplayWriterPrice = false;
            }

            axios.get('/api/team-in-charge-per-role', {
                params : {
                    role : role
                }
            })
                .then((res) => {
                    this.listTeamIncharge = res.data
                })
        },

        checkPriceRate (rate) {
            return rate % 1 !== 0 ? rate * 1 : Math.trunc(rate);
        },

        checkPriceTypeChange () {
            let self = this;

            self.accountUpdate.writer_price = self.accountUpdate.rate_type === 'ppa' ? 10 : 0.0085;

            if (self.accountUpdate.rate_type !== self.writerPriceTypeTemp) {
                const newArray = [];

                self.accountUpdate.writer_language_price_rates.forEach((o) => {
                    let languageRate = self.listLanguages.data.find(p => p.id === o.id);

                    newArray.push({
                        id: o.id,
                        name: o.name,
                        type: self.accountUpdate.rate_type,
                        rate: self.accountUpdate.rate_type === 'ppa'
                            ? self.checkPriceRate(languageRate.ppa_rate)
                            : self.checkPriceRate(languageRate.ppw_rate)
                    })
                });

                self.accountUpdate.writer_language_price_rates = newArray;
            } else {
                self.accountUpdate.writer_language_price_rates = self.writerPriceRatesTemp2;

                self.checkWriterLanguageChange();
            }

            // extract removed languages
            self.accountUpdate.writer_language_price_rates = self.accountUpdate.writer_language_price_rates.filter(function(obj) {
                return self.accountUpdate.language_id.includes(obj.id);
            })
        },

        checkWriterLanguageChange () {
            let self = this;

            if (self.accountUpdate.type === 'Writer') {
                if (self.accountUpdate.language_id.length) {
                    let rates = self.accountUpdate.writer_language_price_rates.map(a => a.id);

                    // include added languages
                    self.accountUpdate.language_id.forEach((id) => {
                        if (!rates.includes(id)) {
                            let extract = self.listLanguages.data.find(o => o.id === id);
                            let existed = self.writerPriceRatesTemp.find(o => o.id === id);

                            // if added language is already added but removed and added again
                            if (existed) {
                                self.accountUpdate.writer_language_price_rates.push({
                                    id: existed.id,
                                    name: existed.name,
                                    rate: self.writerPriceTypeTemp !== self.accountUpdate.rate_type
                                        ? self.accountUpdate.rate_type === 'ppw'
                                            ? self.checkPriceRate(extract.ppw_rate)
                                            : self.checkPriceRate(extract.ppa_rate)
                                        : self.checkPriceRate(existed.rate),
                                    type: existed.type
                                })
                            } else {
                                self.accountUpdate.writer_language_price_rates.push({
                                    id: extract.id,
                                    name: extract.name,
                                    rate: self.accountUpdate.rate_type === 'ppw'
                                        ? self.checkPriceRate(extract.ppw_rate)
                                        : self.checkPriceRate(extract.ppa_rate),
                                    type: self.accountUpdate.rate_type
                                })
                            }
                        }
                    })

                    // extract removed languages
                    self.accountUpdate.writer_language_price_rates = self.accountUpdate.writer_language_price_rates.filter(function(obj) {
                        return self.accountUpdate.language_id.includes(obj.id);
                    })
                } else {
                    self.accountUpdate.writer_language_price_rates = [];
                }

                if (self.writerPriceTypeTemp === self.accountUpdate.rate_type) {
                    self.writerPriceRatesTemp2 = self.accountUpdate.writer_language_price_rates
                }
            }
        },

        checkTeamInchargeMultiple(role) {
            axios.get('/api/team-in-charge-per-role', {
                params : {
                    role : role
                }
            })
                .then((res) => {
                    this.listTeamIncharge = res.data
                })
        },

        multipleUpdateInCharge() {

            let self = this;

            // check if account types are all the same

            let same = self.checkIds.every(id => id.type === self.checkIds[0].type)

            if (same) {

                this.checkTeamInchargeMultiple(this.checkIds[0].type)

                let element = this.$refs.modalMultipleUpdateIncharge
                $(element).modal('show')

            } else {
                swal.fire(
                    self.$t('message.registration_accounts.alert_invalid'),
                    self.$t('message.registration_accounts.alert_selected_same_type'),
                    'error'
                );
            }

        },

        submitUpdateMultipleInCharge() {
            let self = this;
            let ids = [];
            let user_ids = [];
            for (let index in this.checkIds) {
                ids.push(this.checkIds[index].id)
                if(this.checkIds[index].user) {
                    user_ids.push(this.checkIds[index].user.id)
                }

            }

            axios.post('/api/update-multiple-in-charge', {
                ids : ids,
                user_ids : user_ids,
                emp_id : this.updateMultipleInCharge
            }).then((res) => {
                if (res.data.success === true) {

                    let element = this.$refs.modalMultipleUpdateIncharge
                    $(element).modal('hide')

                    swal.fire(
                        self.$t('message.registration_accounts.alert_success'),
                        self.$t('message.registration_accounts.alert_updated_successfully'),
                        'success'
                    )

                    this.updateMultipleInCharge = '';
                    this.doSearch()
                }
            })
        },

        checkCompanyType() {
            if (this.accountModel.company_type == 'Company') {
                this.addCompanyName = true;
            } else {
                this.addCompanyName = false;
            }

            if (this.accountUpdate.company_type == 'Company') {
                this.updateCompanyName = true;
            } else {
                this.updateCompanyName = false;
            }
        },

        async getListCountries(params) {
            await this.$store.dispatch('actionGetListCountries', params);
        },

        async submitAdd() {
            let self = this;
            let id_payment_type = this.accountModel.id_payment_type;

            if(!this.accountModel.add_method_payment_type[id_payment_type] && (this.accountModel.type !== 'Affiliate' && this.accountModel.type !== 'Buyer')) {
                this.validate_error_type = true;

                await swal.fire(
                    self.$t('message.registration_accounts.alert_error'),
                    self.$t('message.registration_accounts.alert_error_saving'),
                    'error'
                );

                return false;
            } else {
                this.validate_error_type = false;
            }

            // validate if writer type need to select language
            if(this.accountModel.language_id == "" && this.accountModel.type == "Writer") {

                swal.fire(
                    self.$t('message.registration_accounts.alert_invalid'),
                    self.$t('message.registration_accounts.alert_fill_up'),
                    'error'
                );

                this.isErrorLang = true;
                return false;
            }

            this.isPopupLoading = true;
            await this.$store.dispatch('actionAddAccount', this.accountModel);
            this.isPopupLoading = false;

            if (this.messageForms.action === 'saved_account') {

                this.getAccountList({
                    params : this.filterModel
                });

                swal.fire(
                    self.$t('message.registration_accounts.alert_success'),
                    self.$t('message.registration_accounts.alert_added_successfully'),
                    'success'
                );

                this.isDisableSubmit = true;
                this.btnTermsAndConditions = false;
                this.validate_error_type = false;
                this.isErrorLang = false;

                // empty model
                this.accountModel = {
                    name : '',
                    email : '',
                    phone : '',
                    password : '',
                    c_password : '',
                    type : '',
                    company_name : '',
                    company_url : '',
                    skype : '',
                    id_payment_type : '',
                    // payment_email : '',
                    payment_account : '',
                    commission : '',
                    team_in_charge : '',
                    info : '',
                    address : '',
                    is_recommended : 'no',
                    country_id : '',
                    add_method_payment_type: []
                };
            } else {
                await swal.fire(
                    self.$t('message.registration_accounts.alert_error'),
                    self.$t('message.registration_accounts.alert_error_saving'),
                    'error'
                );
            }
        },

        async submitUpdate() {
            let self = this

            swal.fire({
                title : self.$t('message.registration_accounts.ur_title'),
                html : self.$t('message.registration_accounts.alert_confirm_update'),
                icon : "question",
                showCancelButton : true,
                confirmButtonText : self.$t('message.registration_accounts.yes'),
                cancelButtonText : self.$t('message.registration_accounts.no')
            })
            .then((result) => {
                if (result.isConfirmed) {
                    self.saveUpdate()
                }
            });
        },

        async saveUpdate() {
            let self = this;
            let loader = this.$loading.show();

            let id_payment_type = this.accountUpdate.id_payment_type;

            if(this.accountUpdate.is_sub_account === 0
                && !this.accountUpdate.update_method_payment_type[id_payment_type]
                && this.accountUpdate.type !== 'Affiliate'
                && this.accountUpdate.type !== 'Buyer'
                && this.isVerified) {

                this.validate_error_type_update = true;
                loader.hide();

                swal.fire(
                    self.$t('message.registration_accounts.alert_error'),
                    self.$t('message.registration_accounts.alert_error_update'),
                    'error'
                );

                return false;
            }

            this.accountUpdate.isVerified = this.isVerified;

            this.isPopupLoading = true;
            await this.$store.dispatch('actionUpdateAccount', this.accountUpdate);
            this.isPopupLoading = false;

            if (this.messageForms.action === 'updated_account') {
                swal.fire(
                    self.$t('message.registration_accounts.alert_updated'),
                    self.$t('message.registration_accounts.alert_successfully_updated'),
                    'success'
                );

                let element = this.$refs.modalUpdateAccount;
                this.validate_error_type_update = false;
                $(element).modal('hide');

                await this.getAccountList();

                loader.hide();
            } else {
                swal.fire(
                    self.$t('message.registration_accounts.alert_error'),
                    self.$t('message.registration_accounts.alert_error_update'),
                    'error'
                );

                loader.hide();
            }

            this.selectedUserID = 0;
        },

        async getTeamInCharge() {
            await this.$store.dispatch('actionGetTeamInCharge');
        },

        async getAffiliates() {
            await this.$store.dispatch('actionGetAffiliateList');
        },

        checkTeamSeller() {
            if (this.user.isOurs == 0 && this.user.role_id == 6) {
                this.isTeamSeller = false;
            }
        },

        checkAccessRole() {
            this.isDisabled = true;
            if (this.user.isAdmin || (this.user.role_id == 7 && this.user.isOurs == 0) || (this.user.role_id == 8 && this.user.isOurs == 0)) {
                this.isDisabled = false;
            }
        },

        clearMessageform() {
            this.$store.dispatch('clearMessageFormAccount');
        },

        clearMessageFormMail() {
            this.clearMessageFormEmail()
        },

        doUpdateAccount(account) {
            this.clearMessageform();
            let self = this;
            let that = JSON.parse(JSON.stringify(account))

            this.accountUpdate.id = that.id
            this.accountUpdate.type = that.type
            this.accountUpdate.username = that.username
            this.accountUpdate.name = that.name
            this.accountUpdate.email = that.email
            this.accountUpdate.phone = that.phone
            this.accountUpdate.skype = that.skype
            this.accountUpdate.password = '';
            this.accountUpdate.c_password = '';
            this.accountUpdate.company_type = that.is_freelance == '0' ? 'Company' : 'Freelancer';
            this.accountUpdate.company_name = that.is_freelance == '0' ? that.company_name : '';
            this.accountUpdate.country_id = that.country_id
            this.accountUpdate.language_id = that.language_id == "" ? "":JSON.parse(that.language_id)
            this.accountUpdate.address = that.address
            this.accountUpdate.info = that.info
            this.accountUpdate.is_recommended = that.is_recommended
            this.accountUpdate.id_payment_type = that.id_payment_type
            this.accountUpdate.commission = that.commission
            this.accountUpdate.is_show_price_basis = that.is_show_price_basis == 0 ? 'no':'yes';
            this.accountUpdate.is_sub_account = that.is_sub_account;
            this.accountUpdate.status = that.status
            this.accountUpdate.credit_auth = that.credit_auth
            this.accountUpdate.affiliate = that.affiliate_id;
            this.accountUpdate.buyer_type = that.buyer_type;
            this.accountUpdate.team_in_charge = that.team_in_charge == null ? '' : that.team_in_charge.id;
            this.accountUpdate.account_validation = that.account_validation
            this.accountUpdate.rate_type = that.rate_type == null || that.rate_type == '' ? '':that.rate_type;
            this.accountUpdate.writer_price = that.writer_price == null || that.writer_price == '' ? '' : that.writer_price;

            // console.log(that)

            if(typeof account.user != "undefined" && account.user){
                this.selectedUserID = account.user.id

                if(account.user.user_payment_types.length > 0) {
                    this.accountUpdate.update_method_payment_type = [];

                    for(let index in account.user.user_payment_types) {
                        var payment_id = account.user.user_payment_types[index].payment_id;
                        var acc_bank_name = JSON.parse(account.user.user_payment_types[index].bank_name)
                        var acc_account_name = JSON.parse(account.user.user_payment_types[index].account_name)
                        var acc_account_iban = JSON.parse(account.user.user_payment_types[index].account_iban)
                        var acc_swift_code = JSON.parse(account.user.user_payment_types[index].swift_code)
                        var acc_beneficiary_add = JSON.parse(account.user.user_payment_types[index].beneficiary_add)
                        var acc_account_holder = JSON.parse(account.user.user_payment_types[index].account_holder)
                        var acc_account_type = JSON.parse(account.user.user_payment_types[index].account_type)
                        var acc_routing_num = JSON.parse(account.user.user_payment_types[index].routing_num)
                        var acc_wire_routing_num = JSON.parse(account.user.user_payment_types[index].wire_routing_num)

                        this.accountUpdate.update_method_payment_type[payment_id] = account.user.user_payment_types[index].account
                        this.accountUpdate.bank_name[payment_id] = account.user.user_payment_types[index].bank_name == null ? '':acc_bank_name[payment_id]
                        this.accountUpdate.account_name[payment_id] = account.user.user_payment_types[index].account_name == null ? '':acc_account_name[payment_id]
                        this.accountUpdate.account_iban[payment_id] = account.user.user_payment_types[index].account_iban == null ? '':acc_account_iban[payment_id]
                        this.accountUpdate.swift_code[payment_id] = account.user.user_payment_types[index].swift_code == null ? '':acc_swift_code[payment_id]
                        this.accountUpdate.beneficiary_add[payment_id] = account.user.user_payment_types[index].beneficiary_add == null ? '':acc_beneficiary_add[payment_id]
                        this.accountUpdate.account_holder[payment_id] = account.user.user_payment_types[index].account_holder == null ? '':acc_account_holder[payment_id]
                        this.accountUpdate.account_type[payment_id] = account.user.user_payment_types[index].account_type == null ? '':acc_account_type[payment_id]
                        this.accountUpdate.routing_num[payment_id] = account.user.user_payment_types[index].routing_num == null ? '':acc_routing_num[payment_id]
                        this.accountUpdate.wire_routing_num[payment_id] = account.user.user_payment_types[index].wire_routing_num == null ? '':acc_wire_routing_num[payment_id]
                    }
                } else {
                    this.accountUpdate.update_method_payment_type = [];
                    this.accountUpdate.id_payment_type = ''
                }

                // writer language price rates
                if (account.type === 'Writer') {
                    let writerLanguages = [];

                    if (account.user.languages.length > 0) {
                        account.user.languages.forEach((language) => {
                            writerLanguages.push({
                                id: language.id,
                                name: language.name,
                                type: language.pivot.type,
                                rate: self.checkPriceRate(language.pivot.rate),
                            })
                        })
                    }

                    this.accountUpdate.writer_language_price_rates = writerLanguages;
                    this.writerPriceRatesTemp = writerLanguages;
                    self.writerPriceRatesTemp2 = writerLanguages;
                }
            }

            this.checkTeamIncharge('update');
            this.checkVerified();
            this.checkCompanyType();
        },

        async getPaymentTypeList(params) {
            await this.$store.dispatch('actionGetListPayentType', params);
        },

        async getAccountList(page = 1) {
            let loader = this.$loading.show();
            // change the format of date
            this.filterModel.created_at = this.formatFilterDates(this.filterModel.created_at)

            $("#tbl_account").DataTable().destroy();
            this.isLoadingTable = true;
            this.isSearchLoading = true;
            this.isSearching = true;
            this.isAdvanceSearching = true;

            await this.$store.dispatch('actionGetAccount', {
                params : {
                    type : this.filterModel.type,
                    status : this.filterModel.status,
                    search : this.filterModel.search,
                    paginate : this.filterModel.paginate,
                    team_in_charge : this.filterModel.team_in_charge,
                    affiliate : this.filterModel.affiliate,
                    country : this.filterModel.country,
                    language_id : this.filterModel.language_id,
                    commission : this.filterModel.commission,
                    credit_auth : this.filterModel.credit_auth,
                    company_type : this.filterModel.company_type,
                    company_name : this.filterModel.company_name,
                    company_url : this.filterModel.company_url,
                    account_validation : this.filterModel.account_validation,
                    payment_info : this.filterModel.payment_info,
                    payment_info_data : this.filterModel.payment_info_data,
                    created_at : this.filterModel.created_at,
                    account_verification :
                    this.filterModel.account_verification,
                    is_sub_account : this.filterModel.is_sub_account,
                    buyer_transaction : this.filterModel.buyer_transaction,
                    advance_search : this.filterModel.advance_search,
                    page : page
                }
            });
            this.isLoadingTable = false;
            this.isSearchLoading = false;
            this.isSearching = false;
            this.isAdvanceSearching = false;

            $("#tbl_account").DataTable({
                paging : false,
                searching : false,
                info: false,
                columnDefs : [
                    {orderable : true, targets : 0},
                    {orderable : true, targets : 3},
                    {orderable : true, targets : 4},
                    {orderable : true, targets : 5},
                    {orderable : true, targets : 6},
                    {orderable : true, targets : 7},
                    {orderable : true, targets : 8},
                    {orderable : true, targets : 9},
                    {orderable : true, targets : 10},
                    {orderable : true, targets : 11},
                    {orderable : true, targets : 12},
                    {orderable : true, targets : 13},
                    {orderable : true, targets : 14},
                    {orderable : true, targets : 15},
                    {orderable : true, targets : 16},
                    {orderable : true, targets : 17},
                    {orderable : true, targets : 18},
                    {orderable : true, targets : 19},
                    {orderable : false, targets : '_all'}
                ],
            });
            loader.hide();
        },

        clearSearch() {
            this.filterModel = {
                type : '',
                status : '',
                search : '',
                paginate : '15',
                team_in_charge : '',
                affiliate : '',
                company_type : '',
                commission : '',
                credit_auth : '',
                company_name : '',
                company_url : '',
                account_validation : '',
                payment_info : '',
                payment_info_data : '',
                country : '',
                language_id : '',
                created_at : {
                    startDate : null,
                    endDate : null
                },
                account_verification : '',
                is_sub_account: '',
                buyer_transaction: '',
                advance_search: this.filterModel.advance_search,
            }

            this.getAccountList({
                params : this.filterModel
            });

            this.$router.replace({'query' : null});

            this.resetSelectAll()
        },

        async doSearch() {
            // change the format of date
            this.filterModel.created_at = this.formatFilterDates(this.filterModel.created_at)

            this.$router.push({
                query : this.filterModel,
            });

            this.getAccountList({
                params : {
                    status : this.filterModel.status,
                    search : this.filterModel.search,
                    type : this.filterModel.type,
                    paginate : this.filterModel.paginate,
                    team_in_charge : this.filterModel.team_in_charge,
                    affiliate : this.filterModel.affiliate,
                    country : this.filterModel.country,
                    language_id : this.filterModel.language_id,
                    company_type : this.filterModel.company_type,
                    commission : this.filterModel.commission,
                    credit_auth : this.filterModel.credit_auth,
                    company_name : this.filterModel.company_name,
                    company_url : this.filterModel.company_url,
                    account_validation : this.filterModel.account_validation,
                    payment_info : this.filterModel.payment_info,
                    payment_info_data : this.filterModel.payment_info_data,
                    created_at : this.filterModel.created_at,
                    account_verification :
                    this.filterModel.account_verification,
                    is_sub_account : this.filterModel.is_sub_account,
                    buyer_transaction : this.filterModel.buyer_transaction,
                    advance_search : this.filterModel.advance_search,
                }
            });

            this.resetSelectAll()
        },

        resetSelectAll() {
            this.checkIds = [];
            this.allSelected = true;
            this.selectAll();
        },

        columnAdjust() {
            this.$nextTick(() => {
                $('#tbl_account').DataTable().columns.adjust()
            });
        },

        humanize(string) {
            let i, frags = string.split('_');

            for (i = 0; i < frags.length; i++) {
                frags[i] = frags[i].charAt(0).toUpperCase() + frags[i].slice(1);
            }

            return frags.join(' ');
        },

        checkCcBccValidationError(error, mod) {
            return Object.keys(error).some(function (err) {
                return ~err.indexOf(mod)
            })
        },

        getPaymentSolutionHistory () {
            let loader = this.$loading.show();

            axios.get('/api/users-payment-type/history/' + this.selectedUserID)
            .then((res) => {
                this.paymentSolutionHistory = res.data

                let element = this.$refs.modalPaymentSolutionHistory
                $(element).modal('show')

                loader.hide();
            }).catch((err) => {
                console.log(err)
                loader.hide();
            })

        },

        viewBankTransferDetails (details) {
            let self = this;
            const keys = {
                id: '',
                bank_name: '',
                account_name: '',
                account_iban: '',
                swift_code: '',
                beneficiary_add: '',
                account_holder: '',
                account_type: '',
                routing_num: '',
                wire_routing_num: '',
            };

            let temp = {};

            for (let key of Object.keys(keys)) {

                if (key === 'id') {
                    temp[key] = details[key];
                } else {
                    temp[key] = Array.isArray(JSON.parse(details[key]))
                        ? self.returnNotNullInArray(JSON.parse(details[key]))
                        : '';
                }
            }

            this.paymentSolutionDetailValues.push(temp);

            this.paymentSolutionDetailValues = this.paymentSolutionDetailValues.filter((value, index, self) =>
                index === self.findIndex((t) => (
                    t.id === value.id
                ))
            )

            let collapseId = '#solution-collapse' + details.id
            $(collapseId).collapse('toggle');
        },

        returnNotNullInArray (arr) {
            return arr.find(element => {
                return element !== null && element !== '';
            });
        },

        returnSolutionDetailsObject (data, id) {
            let values = data.find(element => {
                return element.id === id;
            });

            return values;
        },

        advanceSearch: __.debounce(function () {
            this.getAccountList();
        }, 1000)
    }
}
</script>
