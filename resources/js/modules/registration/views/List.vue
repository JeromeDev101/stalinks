<template>
    <div class="row">
        <div class="col-sm-12">
            <!-- <section class="content-header col-sm-12">
                <h1>Accounts</h1>
            </section> -->

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Filter</h3>
                    <button
                        type="button"
                        aria-expanded="false"
                        data-toggle="collapse"
                        class="btn btn-primary ml-5"
                        data-target="#collapseFilterRegistration"
                        aria-controls="collapseFilterRegistration">

                        <i class="fa fa-plus"></i> Show Filter
                    </button>
                </div>

                <div class="box-body m-3 collapse" id="collapseFilterRegistration">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Search Name, Email & Username</label>
                                <input type="text" class="form-control" v-model="filterModel.search" aria-describedby="helpId" placeholder="Type here">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" v-model="filterModel.status">
                                    <option value="">Select Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Account type</label>
                                <select class="form-control" v-model="filterModel.type">
                                    <option value="">Select Type</option>
                                    <option value="seller">Seller</option>
                                    <option value="buyer">Buyer</option>
                                    <option value="writer">Writer</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Country</label>
                                <select class="form-control" name="" v-model="filterModel.country">
                                    <option value="">Select Country</option>
                                    <option v-for="option in listCountryAll.data" :value="option.id" :key="option.id">
                                        {{ option.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Commission</label>
                                <select class="form-control" name="" v-model="filterModel.commission">
                                    <option value="">Select Commission</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Credit Authorization</label>
                                <select class="form-control" name="" v-model="filterModel.credit_auth">
                                    <option value="">Select Credit Authorization</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3" v-if="isTeamSeller" v-show="user.role_id == 8 || user.isAdmin">
                            <div class="form-group">
                                <label>Team In-charge</label>
                                <select class="form-control" v-model="filterModel.team_in_charge">
                                    <option value="">All</option>
                                    <option value="none">None</option>
                                    <option v-for="option in listIncharge.data" v-bind:value="option.id">
                                        {{ option.username == null ? option.name:option.username}}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Company Type</label>
                                <select class="form-control" v-model="filterModel.company_type">
                                    <option value="">All</option>
                                    <option value="1">Freelancer</option>
                                    <option value="0">Company</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Company Name</label>
                                <input
                                    v-model="filterModel.company_name"
                                    type="text"
                                    class="form-control"
                                    placeholder="Type here">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Company URL</label>
                                <input
                                    v-model="filterModel.company_url"
                                    type="text"
                                    class="form-control"
                                    placeholder="Type here">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Account Validation</label>
                                <select class="form-control" v-model="filterModel.account_validation">
                                    <option value="">All</option>
                                    <option value="valid">Valid</option>
                                    <option value="invalid">Invalid</option>
                                    <option value="processing">Processing</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date of Registration</label>
                                <div class="input-group">
                                    <date-range-picker
                                        v-model="filterModel.created_at"
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
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <button class="btn btn-default" @click="clearSearch" :disabled="isSearching">Clear</button>
                            <button class="btn btn-default" @click="doSearch" :disabled="isSearching">Search <i class="fa fa-refresh fa-spin" v-if="isSearchLoading"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Accounts</h3>

                    <div class="input-group input-group-sm float-right" style="width: 100px">
                        <select name="" class="form-control float-right" @change="getAccountList" v-model="filterModel.paginate" style="height: 37px;">
                            <option v-for="option in paginate" v-bind:value="option">
                                {{ option }}
                            </option>
                        </select>
                    </div>

                    <button data-toggle="modal" data-target="#modal-setting" class="btn btn-default float-right"><i class="fa fa-cog"></i></button>
                    <button class="btn btn-success pull-right" @click="clearMessageform" data-toggle="modal" data-target="#modal-registration">Register</button>
                </div>
                <div class="box-body no-padding relative">

                    <span v-if="listAccount.total > 10" class="pagination-custom-footer-text">
                        <b>Showing {{ listAccount.from }} to {{ listAccount.to }} of {{ listAccount.total }} entries.</b>
                    </span>

                    <table id="tbl_account" class="table table-striped table-bordered" style="font-size: 0.75rem">
                        <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>Action</th>
                                <th v-show="tblAccountsOpt.date_registered">Date Reg</th>
                                <th v-show="tblAccountsOpt.payment_account_email && user.isAdmin">Payment Info</th>
                                <th v-show="tblAccountsOpt.email && user.isAdmin">Email</th>
                                <th v-show="tblAccountsOpt.in_charge">In-charge</th>
                                <th v-show="tblAccountsOpt.user_id">User ID</th>
                                <th v-show="tblAccountsOpt.username">Username</th>
                                <th v-show="tblAccountsOpt.name">Name</th>
                                <th v-show="tblAccountsOpt.company_type">Company Type</th>
                                <th v-show="tblAccountsOpt.company_name">Company Name</th>
                                <th v-show="tblAccountsOpt.company_url">Company URL</th>
                                <th v-show="tblAccountsOpt.type">Type</th>
                                <th v-show="tblAccountsOpt.sub_account">Sub Account</th>
                                <th v-show="tblAccountsOpt.under_of_main_buyer">Under of Main Buyer</th>
                                <th v-show="tblAccountsOpt.account_validation">Account Validation</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(account, index) in listAccount.data" :key="index">
                                <td>{{ index + 1 }}</td>
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
                                            type="submit"
                                            title="Send Email"
                                            data-toggle="modal"
                                            class="btn btn-default"

                                            @click="doSendEmail(account)">

                                            <i class="fa fa-fw fa-envelope-o"></i>
                                        </button>
                                    </div>
                                </td>
                                <td v-show="tblAccountsOpt.date_registered">{{ account.created_at }}</td>
                                <td v-show="tblAccountsOpt.payment_account_email && user.isAdmin" v-html="displayEmailPayment(account)"></td>
                                <td v-show="tblAccountsOpt.email && user.isAdmin">{{ account.email }}</td>
                                <td v-show="tblAccountsOpt.in_charge">
                                    {{
                                        account.team_in_charge == null
                                            ? 'N/A'
                                            : account.is_sub_account == 1
                                                ? 'Sub'
                                                :account.team_in_charge.username
                                    }}

                                    <span
                                        v-if="account.team_in_charge !== null && account.team_in_charge.status === 'inactive'"
                                        class="badge badge-danger">

                                        Inactive
                                    </span>
                                </td>
                                <td v-show="tblAccountsOpt.user_id">{{ account.user == null ? 'Not yet Verified' : account.user.id }}</td>
                                <td v-show="tblAccountsOpt.username">{{ account.username }}</td>
                                <td v-show="tblAccountsOpt.name">{{ account.name }}</td>
                                <td v-show="tblAccountsOpt.company_type">{{ account.is_freelance == 1 ? 'Freelancer':'Company' }}</td>
                                <td v-show="tblAccountsOpt.company_name">{{ account.company_name }}</td>
                                <td v-show="tblAccountsOpt.company_url">
                                    <a
                                        :href="'http://' + account.company_url"
                                        target="_blank">
                                        {{ account.company_url }}
                                    </a>
                                </td>
                                <td v-show="tblAccountsOpt.type">{{ account.type }}</td>
                                <td v-show="tblAccountsOpt.sub_account">{{ account.is_sub_account == 0 ? 'No':'Yes' }}</td>
                                <td v-show="tblAccountsOpt.under_of_main_buyer">{{ account.is_sub_account == 0 ?  '':account.team_in_charge.username }}</td>
                                <td v-show="tblAccountsOpt.account_validation">{{ account.account_validation }}</td>
                                <td>{{ account.status }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <pagination :data="listAccount" @pagination-change-page="getAccountList" :limit="8"></pagination>
                </div>

            </div>

        </div>

        <!-- Modal Update Registration -->
        <div class="modal fade" id="modal-update-registration" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Account</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">

                        <h4 class="text-primary">Account Information</h4>
                        <hr/>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="alert alert-warning" v-show="!isVerified">
                                    <p>
                                        This account is not yet verified. Please click 'Verified Account' to proceed.
                                        <button class="btn btn-default pull-right" @click="verifiedAccount()">Verified Account</button>
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Account Type <span class="text-danger">*</span></label>
                                    <select class="form-control" name="" v-model="accountUpdate.type" :disabled="isDisabled" @change="checkTeamIncharge('update')">
                                        <option value="Seller">Seller</option>
                                        <option value="Buyer">Buyer</option>
                                        <option value="Writer">Writer</option>
                                    </select>
                                    <span v-if="messageForms.errors.type" v-for="err in messageForms.errors.type" class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Username <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" :disabled="user.isOurs != 0" v-model="accountUpdate.username" name="" aria-describedby="helpId" placeholder="">
                                    <span v-if="messageForms.errors.username" v-for="err in messageForms.errors.username" class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" :disabled="user.isOurs != 0" v-model="accountUpdate.name" name="" aria-describedby="helpId" placeholder="">
                                    <span v-if="messageForms.errors.name" v-for="err in messageForms.errors.name" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" :disabled="user.isOurs != 0" v-model="accountUpdate.email" name="" aria-describedby="helpId" placeholder="">
                                    <span v-if="messageForms.errors.email" v-for="err in messageForms.errors.email" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-12" v-show="updateDisplayWriterPrice">
                                <div class="form-group">
                                    <label>Writer Price <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" v-model="accountUpdate.writer_price" name="" aria-describedby="helpId" placeholder="">
                                    <span v-if="messageForms.errors.writer_price" v-for="err in messageForms.errors.writer_price" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" class="form-control" v-model="accountUpdate.phone" name="" aria-describedby="helpId" placeholder="">
                                    <span v-if="messageForms.errors.phone" v-for="err in messageForms.errors.phone" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Skype</label>
                                    <input type="text" class="form-control" v-model="accountUpdate.skype">
                                    <span v-if="messageForms.errors.skype" v-for="err in messageForms.errors.skype" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" class="form-control" :disabled="!user.isAdmin" v-model="accountUpdate.password" name="" aria-describedby="helpId" placeholder="">
                                    <span v-if="messageForms.errors.password" v-for="err in messageForms.errors.password" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input type="password" class="form-control" :disabled="!user.isAdmin" v-model="accountUpdate.c_password" name="" aria-describedby="helpId" placeholder="">
                                    <span v-if="messageForms.errors.c_password" v-for="err in messageForms.errors.c_password" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Company Type <span class="text-danger">*</span></label>
                                    <select class="form-control"  v-model="accountUpdate.company_type" @click="checkCompanyType()">
                                        <option value="Company">Company</option>
                                        <option value="Freelancer">Freelancer</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-12" v-show="updateCompanyName">
                                <div class="form-group">
                                    <label>Company Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" v-model="accountUpdate.company_name">
                                    <span v-if="messageForms.errors.company_name" v-for="err in messageForms.errors.company_name" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-12" v-show="updateCompanyName">
                                <div class="form-group">
                                    <label>Company URL</label>
                                    <input type="text" class="form-control" v-model="accountUpdate.company_url">
                                    <span v-if="messageForms.errors.company_url" v-for="err in messageForms.errors.company_url" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Country</label>
                                    <select class="form-control" v-model="accountUpdate.country_id">
                                        <option v-for="option in listCountryAll.data" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Address</label>
                                    <textarea class="form-control" v-model="accountUpdate.address"></textarea>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Info</label>
                                    <textarea class="form-control" v-model="accountUpdate.info"></textarea>
                                </div>
                            </div>

                        </div>

                        <hr/>
                        <h4 class="text-primary">Payment Information</h4>
                        <span v-if="messageForms.errors.id_payment_type" v-for="err in messageForms.errors.id_payment_type" class="text-danger">Please provide one payment type</span>

                        <table class="table">
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label>Paypal Account</label>
                                        <input type="text" class="form-control" v-model="accountUpdate.paypal_account">
                                        <span v-if="messageForms.errors.paypal_account" v-for="err in messageForms.errors.paypal_account" class="text-danger">{{ err }}</span>
                                    </div>
                                </td>
                                <td style="width: 50px;vertical-align:middle;">
                                    <input type="radio" name="payment_default" v-bind:value="1" v-model="accountUpdate.id_payment_type">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label>BTC Account</label>
                                        <input type="text" class="form-control" v-model="accountUpdate.btc_account">
                                        <span v-if="messageForms.errors.btc_account" v-for="err in messageForms.errors.btc_account" class="text-danger">{{ err }}</span>
                                    </div>
                                </td>
                                <td style="width: 50px;vertical-align:middle;">
                                    <input type="radio" name="payment_default" v-bind:value="3" v-model="accountUpdate.id_payment_type">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label>Skril Account</label>
                                        <input type="text" class="form-control" v-model="accountUpdate.skrill_account">
                                        <span v-if="messageForms.errors.skrill_account" v-for="err in messageForms.errors.skrill_account" class="text-danger">{{ err }}</span>
                                    </div>
                                </td>
                                <td style="width: 50px;vertical-align:middle;">
                                    <input type="radio" name="payment_default" v-bind:value="2" v-model="accountUpdate.id_payment_type">
                                </td>
                            </tr>
                        </table>

                        <hr/>
                        <h4 class="text-primary">Internal Information</h4>
                        <hr/>

                        <div class="row">

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Commission <span class="text-danger">*</span></label>
                                    <select class="form-control" name="" v-model="accountUpdate.commission">
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                    <span v-if="messageForms.errors.commission" v-for="err in messageForms.errors.commission" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <label>Status</label>
                                <select class="form-control" name="" v-model="accountUpdate.status" :disabled="isDisabled">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                            <div class="col-sm-6">
                                <label>Credit Authorization</label>
                                <select class="form-control" name="" v-model="accountUpdate.credit_auth" :disabled="isDisabled">
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>

                            <div class="col-sm-6">
                                <label>Team In-charge</label>
                                <select class="form-control" name="" v-model="accountUpdate.team_in_charge">
                                    <option value="">N/A</option>
                                    <option v-for="option in listTeamIncharge" v-bind:value="option.id">
                                        {{ option.username == null || option.username == '' ? option.name:option.username}}
                                    </option>
                                </select>
                            </div>

                            <div class="col-sm-6" v-show="user.role_id === 8 || user.isAdmin">
                                <label>Account Validation <span class="text-danger">*</span></label>
                                <select class="form-control" name="" v-model="accountUpdate.account_validation">
                                    <option value="valid">Valid</option>
                                    <option value="invalid">Invalid</option>
                                    <option value="processing">Processing</option>
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitUpdate" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Update Registration -->

        <!-- Modal Registration -->
        <div class="modal fade" id="modal-registration" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Registration</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <h4 class="text-primary">Account Information</h4>
                        <hr/>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Account Type <span class="text-danger">*</span></label>
                                    <select class="form-control" name="" v-model="accountModel.type" @change="checkTeamIncharge('add')">
                                        <option value="">Select Type</option>
                                        <option value="Seller">Seller</option>
                                        <option value="Buyer">Buyer</option>
                                        <option value="Writer">Writer</option>
                                    </select>
                                    <span v-if="messageForms.errors.type" v-for="err in messageForms.errors.type" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Username <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" v-model="accountModel.username" name="" aria-describedby="helpId" placeholder="">
                                    <span v-if="messageForms.errors.username" v-for="err in messageForms.errors.username" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" v-model="accountModel.name" name="" aria-describedby="helpId" placeholder="">
                                    <span v-if="messageForms.errors.name" v-for="err in messageForms.errors.name" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" v-model="accountModel.email" name="" aria-describedby="helpId" placeholder="">
                                    <span v-if="messageForms.errors.email" v-for="err in messageForms.errors.email" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-12" v-show="addDisplayWriterPrice">
                                <div class="form-group">
                                    <label>Writer Price <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" v-model="accountModel.writer_price" name="" aria-describedby="helpId" placeholder="">
                                    <span v-if="messageForms.errors.writer_price" v-for="err in messageForms.errors.writer_price" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" class="form-control" v-model="accountModel.phone" name="" aria-describedby="helpId" placeholder="">
                                    <span v-if="messageForms.errors.phone" v-for="err in messageForms.errors.phone" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Skype</label>
                                    <input type="text" class="form-control" v-model="accountModel.skype">
                                    <span v-if="messageForms.errors.skype" v-for="err in messageForms.errors.skype" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" v-model="accountModel.password" name="" aria-describedby="helpId" placeholder="">
                                    <span v-if="messageForms.errors.password" v-for="err in messageForms.errors.password" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" v-model="accountModel.c_password" name="" aria-describedby="helpId" placeholder="">
                                    <span v-if="messageForms.errors.c_password" v-for="err in messageForms.errors.c_password" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label>Company Type <span class="text-danger">*</span></label>
                                    <select class="form-control" v-model="accountModel.company_type" @click="checkCompanyType()">
                                        <option value="Company">Company</option>
                                        <option value="Freelancer">Freelancer</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-12" v-show="addCompanyName">
                                <div class="form-group">
                                    <label>Company Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" v-model="accountModel.company_name">
                                    <span v-if="messageForms.errors.company_name" v-for="err in messageForms.errors.company_name" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-12" v-show="addCompanyName">
                                <div class="form-group">
                                    <label>Company URL</label>
                                    <input type="text" class="form-control" v-model="accountModel.company_url">
                                    <span v-if="messageForms.errors.company_url" v-for="err in messageForms.errors.company_url" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Country</label>
                                    <select class="form-control" v-model="accountModel.country_id">
                                        <option value="">All</option>
                                        <option v-for="option in listCountryAll.data" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Address</label>
                                    <textarea class="form-control" v-model="accountModel.address"></textarea>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Info</label>
                                    <textarea class="form-control" v-model="accountModel.info"></textarea>
                                </div>
                            </div>

                        </div>

                        <hr/>
                        <h4 class="text-primary">Payment Information</h4>
                        <span v-if="messageForms.errors.id_payment_type" v-for="err in messageForms.errors.id_payment_type" class="text-danger">Please provide one payment type</span>

                        <table class="table">
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label>Paypal Account</label>
                                        <input type="text" class="form-control" v-model="accountModel.paypal_account">
                                        <span v-if="messageForms.errors.paypal_account" v-for="err in messageForms.errors.paypal_account" class="text-danger">{{ err }}</span>
                                    </div>
                                </td>
                                <td style="width: 50px;vertical-align:middle;">
                                    <input type="radio" name="payment_default" v-bind:value="1" v-model="accountModel.id_payment_type">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label>BTC Account</label>
                                        <input type="text" class="form-control" v-model="accountModel.btc_account">
                                        <span v-if="messageForms.errors.btc_account" v-for="err in messageForms.errors.btc_account" class="text-danger">{{ err }}</span>
                                    </div>
                                </td>
                                <td style="width: 50px;vertical-align:middle;">
                                    <input type="radio" name="payment_default" v-bind:value="3" v-model="accountModel.id_payment_type">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label>Skril Account</label>
                                        <input type="text" class="form-control" v-model="accountModel.skrill_account">
                                        <span v-if="messageForms.errors.skrill_account" v-for="err in messageForms.errors.skrill_account" class="text-danger">{{ err }}</span>
                                    </div>
                                </td>
                                <td style="width: 50px;vertical-align:middle;">
                                    <input type="radio" name="payment_default" v-bind:value="2" v-model="accountModel.id_payment_type">
                                </td>
                            </tr>
                        </table>

                        <hr/>
                        <h4 class="text-primary">Internal Information</h4>
                        <hr/>

                        <div class="row">

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Commission <span class="text-danger">*</span></label>
                                    <select class="form-control" name="" v-model="accountModel.commission">
                                        <option value="" disabled>Select Commission</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                    <span v-if="messageForms.errors.commission" v-for="err in messageForms.errors.commission" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6" v-if="isTeamSeller">
                                <div class="form-group">
                                    <label>Team In-charge</label>
                                    <select class="form-control" v-model="accountModel.team_in_charge">
                                        <option value="">Select Team In-charge</option>
                                        <option v-for="option in listTeamIncharge" v-bind:value="option.id">
                                            {{ option.username == null || option.username == '' ? option.name:option.username}}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6" v-show="user.role_id === 8 || user.isAdmin">
                                <label>Account Validation <span class="text-danger">*</span></label>
                                <select class="form-control" name="" v-model="accountModel.account_validation">
                                    <option value="" disabled>Select Account Validation</option>
                                    <option value="valid">Valid</option>
                                    <option value="invalid">Invalid</option>
                                    <option value="processing">Processing</option>
                                </select>
                                <span v-if="messageForms.errors.account_validation" v-for="err in messageForms.errors.account_validation" class="text-danger">{{ err }}</span>
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
                                I've read and accept the

                                <a
                                    href="#"
                                    data-toggle="modal"
                                    data-target="#modalTermsAndCondition">
                                    Terms and Condition
                                </a>
                            </p>
                        </div>

                        <div>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button :disabled="isDisableSubmit" type="button" @click="submitAdd" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Registration -->

        <!-- Modal Settings -->
        <div class="modal fade" id="modal-setting" style="display: none;">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Setting Default</h4>
                        <div class="modal-load overlay float-right">
                        </div>
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
        <div id="modal-email-registration" class="modal fade" ref="modalEmailRegistration" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Send email</h4>
                        <div class="modal-load overlay float-right">
                            <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>
                            <span
                                v-if="messageForms.message != ''"
                                :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                                {{ messageForms.message }}
                            </span>
                        </div>
                    </div>

                    <div class="modal-body relative">
                        <form class="row" action="">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label style="color: #333">Language</label>
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

                                    <div class="col-md-9">
                                        <div  class="form-group">
                                            <label style="color: #333">Select template {{ mailInfo.country.name }}</label>
                                            <div>
                                                <select
                                                    v-model="mailInfo.tpl"
                                                    class="form-control pull-right"

                                                    @change="doChangeEmailTemplate">

                                                    <option  v-for="option in listMailTemplate.data" v-bind:value="option.id">
                                                        {{ option.mail_name }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" style="margin-top: 15px;">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.email}" class="form-group">
                                    <label style="color: #333">Email</label>

                                    <vue-tags-input
                                        v-model="email_to"
                                        :disabled="true"
                                        :separators="separators"
                                        :tags="registrationEmails"
                                        :class="{'vue-tag-error': messageForms.errors.email}"
                                        ref="registrationTag"
                                        placeholder=""

                                        @tags-changed="newTags => registrationEmails = newTags"
                                    />

                                    <span
                                        v-if="messageForms.errors.email"
                                        v-for="err in messageForms.errors.email"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-12" style="margin-top: 15px;">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.title}" class="form-group">
                                    <label style="color: #333">Title</label>

                                    <input type="text" v-model="modelMail.title" class="form-control" value="" required="required">

                                    <span
                                        v-if="messageForms.errors.title"
                                        v-for="err in messageForms.errors.title"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.content}" class="form-group">
                                    <label style="color: #333">Content</label>

                                    <textarea
                                        v-model="modelMail.content"
                                        value=""
                                        rows="10"
                                        type="text"
                                        required="required"
                                        class="form-control">

                                    </textarea>

                                    <span
                                        v-if="messageForms.errors.content"
                                        v-for="err in messageForms.errors.content"
                                        class="text-danger">

                                        { err }}
                                    </span>
                                </div>
                            </div>
                        </form>
                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>

                        <button
                            type="button"
                            class="btn btn-primary"
                            :disabled="!allowSending"

                            @click="submitSendMail">

                            Send
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Send Email -->

        <terms-and-conditions></terms-and-conditions>
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import axios from 'axios';
    import TermsAndConditions from "../../../components/terms/TermsAndConditions";
    import {createTags} from "@johmun/vue-tags-input";

    export default {
        components: {TermsAndConditions},
        data() {
            return {
                paginate: [15,25,50,100,200,250,'All'],
                accountModel: {
                    name: '',
                    email: '',
                    phone: '',
                    password: '',
                    c_password: '',
                    type: '',
                    company_name: '',
                    company_url: '',
                    skype: '',
                    id_payment_type: '',
                    payment_email: '',
                    payment_account: '',
                    skrill_account:'',
                    paypal_account:'',
                    btc_account:'',
                    commission: '',
                    team_in_charge: '',
                    account_validation: '',
                    address: '',
                    info: '',
                    country_id: '',
                    company_type: 'Company',
                    writer_price: '',
                },

                filterModel: {
                    type: this.$route.query.type || '',
                    search: this.$route.query.search || '',
                    status: this.$route.query.status || '',
                    paginate: this.$route.query.paginate || '15',
                    team_in_charge: this.$route.query.team_in_charge || '',
                    country: this.$route.query.country || '',
                    commission: this.$route.query.commission || '',
                    credit_auth: this.$route.query.credit_auth || '',
                    company_type: this.$route.query.company_type || '',
                    company_name: this.$route.query.company_name || '',
                    company_url: this.$route.query.company_url || '',
                    account_validation: this.$route.query.account_validation || '',
                    created_at: {
                        startDate: null,
                        endDate: null
                    }
                },

                accountUpdate: {
                    id: '',
                    name: '',
                    email: '',
                    phone: '',
                    password: '',
                    c_password: '',
                    type: '',
                    company_name: '',
                    company_url: '',
                    skype: '',
                    id_payment_type: '',
                    payment_email: '',
                    payment_account: '',
                    commission: '',
                    status: '',
                    skrill_account:'',
                    paypal_account:'',
                    btc_account:'',
                    username:'',
                    team_in_charge:'',
                    account_validation: '',
                    address: '',
                    info: '',
                    country_id: '',
                    company_type: '',
                    writer_price: '',
                },

                isPopupLoading: false,
                isSearchLoading: false,
                isDisabled: true,
                isTeamSeller: true,
                isSearching: false,
                addCompanyName: true,
                updateCompanyName: true,
                listTeamIncharge: [],
                isVerified: true,
                btnTermsAndConditions: false,
                isDisableSubmit: true,
                allowSending: false,

                // for email sending
                mailInfo: {
                    tpl: 0,
                    ids: '',
                    receiver_text: '',
                    country: {
                        id: 0,
                        name: '',
                        code: ''
                    }
                },

                modelMail: {
                    title: '',
                    content: '',
                    mail_name: '',
                },
                email_to: '',
                registrationEmails: [],
                separators: [';', ',', '|', ' '],
                addDisplayWriterPrice: false,
                updateDisplayWriterPrice: false,
            }
        },

        async created() {
            this.getAccountList();
            this.getPaymentTypeList();
            this.checkAccessRole();
            this.getTeamInCharge();
            this.checkTeamSeller();
            this.getListCountries();
            this.getListLanguages();
        },

        computed: {
            ...mapState({
                tblAccountsOpt: state => state.storeAccount.tblAccountsOpt,
                messageForms: state => state.storeAccount.messageForms,
                messageFormsExt: state => state.storeExtDomain.messageForms,
                listAccount: state => state.storeAccount.listAccount,
                listPayment: state => state.storeAccount.listPayment,
                listIncharge: state => state.storeAccount.listIncharge,
                user: state => state.storeAuth.currentUser,
                listCountryAll: state => state.storePublisher.listCountryAll,
                listLanguages: state => state.storePublisher.listLanguages,
                listMailTemplate: state => state.storeExtDomain.listMailTemplate,
            }),
        },

        methods: {
            doSendEmail(data) {
                console.log(data)
                let emails = [];

                if (typeof(data.email) === "string") {
                    emails = data.email.split('|')
                } else {
                    emails = data.email.map(a => a.text);
                }

                this.registrationEmails = emails ? createTags(emails) : [];

                this.openSendEmailModal();
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
                await this.$store.dispatch('getListMails', {params: {country_id: countryId, full_page: 1}});
            },

            async doChangeEmailTemplate() {
                let that = this;
                this.modelMail = this.listMailTemplate.data.filter(item => item.id === that.mailInfo.tpl)[0];
            },

            async submitSendMail() {
                this.allowSending = false;

                await this.$store.dispatch('sendMailWithMailgun', {
                    cc: '',
                    email: this.registrationEmails,
                    title: this.modelMail.title,
                    content: this.modelMail.content,
                    attachment: 'undefined',
                })

                if (this.messageFormsExt.action === 'send_mail') {
                    this.modelMail = {
                        title: '',
                        content: '',
                        mail_name: '',
                    }

                    $("#modal-email-registration").modal('hide')

                    await swal.fire(
                        'Success',
                        'Email successfully sent',
                        'success'
                    )
                    this.allowSending = true;
                } else {
                    await swal.fire(
                        'Error',
                        'There are some errors while sending the email',
                        'error'
                    )
                    this.allowSending = true;
                }
            },

            async getListLanguages() {
                await this.$store.dispatch('actionGetListLanguages');
            },

            displayEmailPayment(account) {
                let paypal_email = account.paypal_account == null || account.paypal_account == '' ? '':account.paypal_account + ' <span class="badge badge-success">(Paypal)</span> <br/>';
                let btc_email = account.btc_account == null || account.btc_account == '' ? '':account.btc_account + ' <span class="badge badge-success">(BTC)</span> <br/>';
                let skrill_email = account.skrill_account == null || account.skrill_account == '' ? '':account.skrill_account + ' <span class="badge badge-success">(Skrill)</span> <br/>';
                let email = '';

                email = paypal_email + btc_email + skrill_email;

                return email;
            },

            async verifiedAccount() {

                this.isPopupLoading = true;
                await this.$store.dispatch('actionVerifyAccount', this.accountUpdate);

                if (this.messageForms.action === 'verified_account') {
                    this.isVerified = true;

                    this.getAccountList();

                    swal.fire(
                        'Success',
                        'Account Successfully Verified!',
                        'success'
                    );
                } else {
                    swal.fire(
                        'Error',
                        'Account Not Verified!',
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
                axios.get('/api/get-verified-account',{
                    params: {
                        email: this.accountUpdate.email
                    }
                })
                .then((res) => {
                    if( res.data.success === true ) {
                        this.isVerified = res.data.success
                    }
                })
                .catch(res => {
                    if( res.response.data.success === false ) {
                        this.isVerified = res.response.data.success
                    }
                })
            },

            checkTeamIncharge(method) {
                let role = (method == 'add') ? this.accountModel.type : this.accountUpdate.type;

                if(role == 'Writer') {
                    this.addDisplayWriterPrice = true;
                    this.updateDisplayWriterPrice = true;
                } else{
                    this.addDisplayWriterPrice = false;
                    this.updateDisplayWriterPrice = false;
                }

                axios.get('/api/team-in-charge-per-role',{
                    params: {
                        role: role
                    }
                })
                .then((res)=> {
                    this.listTeamIncharge = res.data
                })
            },

            checkCompanyType() {
                if(this.accountModel.company_type == 'Company') {
                    this.addCompanyName = true;
                } else {
                    this.addCompanyName = false;
                }

                if(this.accountUpdate.company_type == 'Company') {
                    this.updateCompanyName = true;
                } else {
                    this.updateCompanyName = false;
                }
            },

            async getListCountries(params) {
                await this.$store.dispatch('actionGetListCountries', params);
            },

            async submitAdd(){
                this.isPopupLoading = true;
                await this.$store.dispatch('actionAddAccount', this.accountModel);
                this.isPopupLoading = false;

                if (this.messageForms.action === 'saved_account') {
                    this.clearAccountModel();
                    this.getAccountList({
                        params: this.filterModel
                    });

                    swal.fire(
                        'Saved',
                        'Successfully Added!',
                        'success'
                    );

                    this.isDisableSubmit = true;
                    this.btnTermsAndConditions = false;
                }
            },

            async submitUpdate(){
                this.isPopupLoading = true;
                await this.$store.dispatch('actionUpdateAccount', this.accountUpdate);
                this.isPopupLoading = false;

                if (this.messageForms.action === 'updated_account') {
                    this.getAccountList();

                    swal.fire(
                        'Updated',
                        'Successfully Updated!',
                        'success'
                    );
                }
            },

            async getTeamInCharge(){
                await this.$store.dispatch('actionGetTeamInCharge');
            },

            checkTeamSeller() {
                if( this.user.isOurs == 0 && this.user.role_id == 6 ){
                    this.isTeamSeller = false;
                }
            },

            checkAccessRole() {
                this.isDisabled = true;
                if( this.user.isAdmin || (this.user.role_id == 7 && this.user.isOurs == 0)){
                    this.isDisabled = false;
                }
            },

            clearMessageform() {
                this.$store.dispatch('clearMessageFormAccount');
            },

            doUpdateAccount(account){
                this.clearMessageform();
                let that = JSON.parse(JSON.stringify(account))
                this.accountUpdate = that
                this.accountUpdate.team_in_charge = that.team_in_charge == null ? '':that.team_in_charge.id;
                this.accountUpdate.password = '';
                this.accountUpdate.c_password = '';
                this.accountUpdate.writer_price = that.writer_price == null || that.writer_price == '' ? '':that.writer_price;
                this.accountUpdate.company_type = that.is_freelance == '0' ? 'Company':'Freelancer';

                this.checkTeamIncharge('update');
                this.checkVerified();
            },

            async getPaymentTypeList(params) {
                await this.$store.dispatch('actionGetListPayentType', params);
            },

            async getAccountList(page = 1) {

                $("#tbl_account").DataTable().destroy();
                this.isLoadingTable = true;
                this.isSearchLoading = true;
                this.isSearching = true;
                await this.$store.dispatch('actionGetAccount', {
                    params: {
                        type: this.filterModel.type,
                        status: this.filterModel.status,
                        search: this.filterModel.search,
                        paginate: this.filterModel.paginate,
                        team_in_charge: this.filterModel.team_in_charge,
                        country: this.filterModel.country,
                        commission: this.filterModel.commission,
                        credit_auth: this.filterModel.credit_auth,
                        company_type: this.filterModel.company_type,
                        company_name: this.filterModel.company_name,
                        company_url: this.filterModel.company_url,
                        account_validation: this.filterModel.account_validation,
                        created_at: this.filterModel.created_at,
                        page: page
                    }
                });
                this.isLoadingTable = false;
                this.isSearchLoading = false;
                this.isSearching = false;

                $("#tbl_account").DataTable({
                    paging: false,
                    searching: false,
                    columnDefs: [
                        { orderable: true, targets: 0 },
                        { orderable: true, targets: 1 },
                        { orderable: true, targets: 2 },
                        { orderable: true, targets: 3 },
                        { orderable: true, targets: 4 },
                        { orderable: true, targets: 5 },
                        { orderable: true, targets: 6 },
                        { orderable: true, targets: 7 },
                        { orderable: true, targets: 8 },
                        { orderable: true, targets: 9 },
                        { orderable: true, targets: 10 },
                        { orderable: true, targets: 11 },
                        { orderable: true, targets: 12 },
                        { orderable: true, targets: 13 },
                        { orderable: false, targets: '_all' }
                    ],
                });
            },

            clearSearch() {
                this.filterModel = {
                    type: '',
                    status: '',
                    search: '',
                    paginate: '15',
                    team_in_charge: '',
                    company_type: '',
                    commission: '',
                    credit_auth: '',
                    company_name: '',
                    company_url: '',
                    account_validation: '',
                    created_at: {
                        startDate: null,
                        endDate: null
                    }
                }

                this.getAccountList({
                    params: this.filterModel
                });

                this.$router.replace({'query': null});
            },

            async doSearch(){
                this.$router.push({
                    query: this.filterModel,
                });

                this.getAccountList({
                    params: {
                        status: this.filterModel.status,
                        search: this.filterModel.search,
                        type: this.filterModel.type,
                        paginate: this.filterModel.paginate,
                        team_in_charge: this.filterModel.team_in_charge,
                        country: this.filterModel.country,
                        company_type: this.filterModel.company_type,
                        commission: this.filterModel.commission,
                        credit_auth: this.filterModel.credit_auth,
                        company_name: this.filterModel.company_name,
                        company_url: this.filterModel.company_url,
                        account_validation: this.filterModel.account_validation,
                        created_at: this.filterModel.created_at,
                    }
                });
            },

            clearAccountModel() {
                this.accountModel = {
                    name: '',
                    email: '',
                    phone: '',
                    password: '',
                    c_password: '',
                    type: '',
                    company_name: '',
                    company_url: '',
                    skype: '',
                    id_payment_type: '',
                    payment_email: '',
                    payment_account: '',
                    commission: '',
                    team_in_charge: '',
                    info: '',
                    address: '',
                    country_id: '',
                };
            },

            columnAdjust(){
                this.$nextTick(() => {
                    $('#tbl_account').DataTable().columns.adjust()
                });
            },

            humanize(string) {
                let i, frags = string.split('_');

                for (i=0; i < frags.length; i++) {
                    frags[i] = frags[i].charAt(0).toUpperCase() + frags[i].slice(1);
                }

                return frags.join(' ');
            }
        }
    }
</script>
