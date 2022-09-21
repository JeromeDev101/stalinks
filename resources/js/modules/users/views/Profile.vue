<template>
    <div>
        <div class="row">
            <div class="col-sm-3">
                <!-- USERS LIST -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ $t('message.profile.p_avatar') }}</h3>
                    </div>
                    <div class="box-body no-padding">
                        <ul class="users-list avatar clearfix">
                            <li>
                                <img v-bind:src="user.avatar ? user.avatar : defaultAvatar" alt="User Image" class="img-fluid avatar-img">
                            </li>
                            <li>
                                <label>{{ $t('message.profile.p_username') }}</label>
                                <h3>{{ user.username }}</h3>
                            </li>
                            <li>
                                <input type="file"
                                           class="form-control mb-2"
                                           enctype="multipart/form-data"
                                           ref="photo"
                                           accept="image/png, image/gif, image/jpeg"
                                           name="photo">
                                <button class="btn btn-block btn-default btn-sm" @click="submitUpload">
                                    {{ $t('message.profile.p_upload_photo') }}
                                </button>
                            </li>
                            <li>
                                <p>
                                    {{ $t('message.profile.p_terms_1') }}
                                    <a
                                        href="#"
                                        data-toggle="modal"
                                        data-target="#modalTermsAndCondition">
                                        {{ $t('message.profile.p_terms_2') }}
                                    </a>
                                </p>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>

            <div class="col-sm-9">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">{{ $t('message.profile.p_info') }}</h3>
                        <h3 class="box-title"  v-if="currentUser.isOurs == 0">[{{ $t('message.profile.p_team') }}]</h3>
                        <h3 class="box-title"  v-if="currentUser.isOurs == 1">[{{ $t('message.profile.p_registration') }}]</h3>
                    </div>
                    <div class="box-body no-padding">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <tbody>
                                <tr>
                                    <td><b>{{ $t('message.profile.p_name') }}</b></td>
                                    <td>
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.name}" class="form-group">
                                            <input type="text" v-model="user.name" class="form-control" value="" required="required" :placeholder="$t('message.registration.ph4')">
                                            <span v-if="messageForms.errors.name" v-for="err in messageForms.errors.name" class="text-danger">{{ err }}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>{{ $t('message.profile.p_email') }}</b></td>
                                    <td>
                                        <input type="text" class="form-control" v-model="user.email" :disabled="true">
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>{{ $t('message.profile.p_phone') }}</b></td>
                                    <td>
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.phone}" class="form-group">
                                            <input type="text" v-model="user.phone" class="form-control" value="" required="required" :placeholder="$t('message.profile.p_enter_phone')">
                                            <span v-if="messageForms.errors.phone" v-for="err in messageForms.errors.phone" class="text-danger">{{ err }}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="currentUser.isOurs == 0">
                                    <td><b>{{ $t('message.profile.p_role') }}</b></td>
                                    <td>{{ user.role ? user.role.name : null }}</td>
                                </tr>
                                <tr v-if="currentUser.isOurs == 1">
                                    <td><b>{{ $t('message.profile.p_type') }}</b></td>
                                    <td>{{ user.user_type ? user.user_type.type: '' }}</td>
                                </tr>
                                <tr v-if="currentUser.isOurs == 1 && (user.user_type != null && user.user_type.type == 'Writer')">
                                    <td><b>{{ $t('message.profile.p_pricing_type') }}</b></td>
                                    <td>
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.rate_type}">
                                            <div class="alert alert-info">
                                                <p>{{ $t('message.registration_accounts.r_reminder_ppw') }}</p>
                                            </div>

                                            <select v-model="user.user_type.rate_type" class="form-control">
                                                <option value="ppw">{{ $t('message.profile.p_ppw') }}</option>
                                                <option value="ppa">{{ $t('message.profile.p_ppa') }}</option>
                                            </select>
                                            <span v-if="messageForms.errors.rate_type" class="text-danger">
                                                {{ $t('message.profile.p_pt_req') }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
<!--                                <tr v-if="currentUser.isOurs == 1 && (user.user_type != null && user.user_type.type == 'Writer')">-->
<!--                                    <td><b>{{ $t('message.profile.p_writer_price') }}</b></td>-->
<!--                                    <td>-->
<!--                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.writer_price}" class="form-group">-->
<!--                                            <input type="number" class="form-control" disabled value="" required="required" :placeholder="$t('message.profile.p_enter_price')">-->
<!--                                            <span-->
<!--                                                v-if="messageForms.errors.writer_price"-->
<!--                                                class="text-danger">-->
<!--                                                {{ $t('message.profile.p_price_error') }}-->
<!--                                            </span>-->
<!--                                        </div>-->
<!--                                    </td>-->
<!--                                </tr>-->
                                <tr v-if="currentUser.isOurs == 1">
                                    <td><b>{{ $t('message.profile.p_company_type') }}</b></td>
                                    <td v-if="user.user_type">
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.company_type}" class="form-group">
                                            <select class="form-control"  v-model="company_type" @click="checkCompanyType()">
                                                <option value="Company">{{ $t('message.profile.p_company') }}</option>
                                                <option value="Freelancer">{{ $t('message.profile.p_freelancer') }}</option>
                                            </select>
                                            <span v-if="messageForms.errors.company_type" v-for="err in messageForms.errors.company_type" class="text-danger">{{ err }}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="currentUser.isOurs == 1" v-show="CompanyName">
                                    <td><b>{{ $t('message.profile.p_company_name') }}</b></td>
                                    <td v-if="user.user_type">
                                        <div :class="{'has-error': messageForms.errors.hasOwnProperty('user_type.company_name')}" class="form-group">
                                            <input type="text" v-model="user.user_type.company_name" class="form-control" value="" required="required" :placeholder="$t('message.profile.p_enter_cn')">
                                            <span
                                                v-if="messageForms.errors.hasOwnProperty('user_type.company_name')"
                                                class="text-danger">
                                                {{ $t('message.profile.p_cn_error') }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="currentUser.isOurs == 1" v-show="CompanyName">
                                    <td><b>{{ $t('message.profile.p_company_url') }}</b></td>
                                    <td v-if="user.user_type">
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.company_url}" class="form-group">
                                            <input type="text" v-model="user.user_type.company_url" class="form-control" value="" required="required" :placeholder="$t('message.profile.p_enter_cu')">
                                            <span v-if="messageForms.errors.company_url" v-for="err in messageForms.errors.company_url" class="text-danger">{{ err }}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>{{ $t('message.profile.p_skype') }}</b></td>
                                    <td>
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.skype}" class="form-group">
                                            <input type="text" v-model="user.skype" class="form-control" value="" required="required" :placeholder="$t('message.profile.p_enter_skype')">
                                            <span
                                                v-if="messageForms.errors.skype"
                                                class="text-danger">
                                                {{ $t('message.profile.p_skype_error') }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="currentUser.isOurs == 1">
                                    <td><b>{{ $t('message.profile.p_country') }}</b></td>
                                    <td v-if="user.user_type">
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.country_id}" class="form-group">
                                            <select name="" class="form-control" v-model="country_id">
                                                <option v-for="option in countryList" v-bind:value="option.id">
                                                    {{ option.name }}
                                                </option>
                                            </select>
                                            <span v-if="messageForms.errors.country_id" v-for="err in messageForms.errors.country_id" class="text-danger">{{ err }}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>{{ $t('message.profile.p_new_pass') }}</b></td>
                                    <td>
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.password}" class="form-group">
                                            <input type="password" class="form-control" v-model="new_password" :placeholder="$t('message.profile.p_enter_np')">
                                            <span
                                                v-if="messageForms.errors.password"
                                                v-for="err in messageForms.errors.password"
                                                class="text-danger">

                                                {{ err }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>{{ $t('message.profile.p_confirm_pass') }}</b></td>
                                    <td>
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.c_password}" class="form-group">
                                            <input type="password" class="form-control" v-model="c_password" :placeholder="$t('message.profile.p_enter_cp')">
                                            <span v-if="messageForms.errors.c_password" v-for="err in messageForms.errors.c_password" class="text-danger">{{ err }}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <button type="button" @click="submitUpdate" class="btn btn-primary">
                                            {{ $t('message.profile.save') }}
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" v-if="currentUser.isOurs === 1 && currentUser.role.id === 11">

            <!-- Generate Affiliate Code -->
            <div class="col-12">
                <div class="box box-primary">
                    <div class="box-header" style="padding-left: 0.7rem !important; padding-bottom: 0.7rem !important;">
                        <h3 class="box-title" style="margin-bottom: 0 !important;">{{ $t('message.profile.ac_title') }}</h3>
                    </div>
                </div>

                <div class="box-body no-padding">
                    <div class="card">
                        <div class="card-header">
                            <div class="alert alert-info mb-0">
                                <i class="fa fa-info-circle"></i>
                                {{ $t('message.profile.ac_note') }}
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row" v-if="user.user_type">
                                <div class="col-12">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button
                                                type="button"
                                                class="btn btn-outline-success"

                                                @click="generateAffiliateCode">
                                                {{ $t('message.profile.ac_generate') }}
                                            </button>

                                            <button
                                                type="button"
                                                :title="$t('message.profile.ac_copy')"
                                                class="btn btn-outline-secondary"
                                                :disabled="!user.user_type.affiliate_code"

                                                @click="copyAffiliateCode">

                                                <i class="fa fa-copy"></i>
                                            </button>
                                        </div>

                                        <input
                                            v-model="user.user_type.affiliate_code"
                                            readonly
                                            type="text"
                                            ref="affiliateCode"
                                            class="form-control"
                                            :placeholder="$t('message.profile.ac_code_empty')"

                                            @focus="$event.target.select()">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mb-3">
                <div class="box box-primary">
                    <div class="box-header" style="padding-left: 0.7rem !important; padding-bottom: 0.7rem !important;">
                        <h3 class="box-title" style="margin-bottom: 0 !important;">{{ $t('message.profile.ac_affiliate_buyers') }}</h3>
                    </div>
                </div>

                <div class="box-body no-padding">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="tbl_affiliate_buyers" class="table table-condensed table-hover table-bordered">
                                <thead>
                                    <tr class="label-primary">
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

        <!-- Refund Section -->
        <div class="row mb-5" v-if="currentUser.isOurs == 1 && currentUser.role_id == 5 && (currentUser.registration != null && currentUser.registration.is_sub_account == 0)">
            <div class="col-sm-12 mx-3">
                <hr/>
                <button class="btn btn-lg btn-info" data-target="#modalRefundReq" data-toggle="modal" :disabled="!canRefund">
                    {{ $t('message.profile.rr_title') }}
                </button>
                <small class="text-muted ml-5"><i>{{ $t('message.profile.rr_note') }}</i></small>
                <hr/>
            </div>
        </div>

        <div class="row" v-if="currentUser.isOurs === 1 && currentUser.role.id !== 11">
            <div class="col-sm-12">
                <div class="box box-primary">
                    <div class="box-header" style="padding-left: 0.7rem !important; padding-bottom: 0.7rem !important;">
                        <h3 class="box-title" style="margin-bottom: 0 !important;">{{ $t('message.profile.b_title') }}</h3>
                    </div>

                    <div class="box-body no-padding">

                        <div class="alert alert-info m-2">
                            <i class="fa fa-info-circle"></i>
                            {{ $t('message.profile.b_note_1') }}
                        </div>

                        <span
                            v-if="messageForms.errors.id_payment_type"
                            class="text-danger mx-2">
                            {{ $t('message.profile.b_note_2') }}
                        </span>

                        <span v-if="validate_error_type" class="text-danger mx-2">
                            {{ $t('message.registration_accounts.r_input_selected') }}
                        </span>

                        <div class="table-responsive">

                            <!-- payment for seller and writer -->
                            <table class="table no-margin" v-if="user.user_type != null && (user.user_type.type === 'Seller' || user.user_type.type === 'Writer')">
                                <tbody>
                                    <tr v-for="(payment_method, index) in paymentMethodListSendPayment" :key="index">
                                        <td>
                                            <div class="form-group">
                                                <label>{{ payment_method.type }} {{ $t('message.profile.b_account') }}</label>
                                            </div>
                                        </td>
                                        <td style="width: 50px;vertical-align:middle;" class="text-center">
                                            <input type="radio" class="btn-radio-custom" name="payment_default" v-bind:value="payment_method.id" v-model="billing.payment_default">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" v-model="billing.payment_type[payment_method.id]">

                                            <span
                                                v-if="messageForms.errors.hasOwnProperty('payment_type.'+ payment_method.id)"
                                                v-for="err in messageForms.errors['payment_type.'+ payment_method.id]"
                                                class="text-danger">

                                                {{ err }}
                                            </span>

                                            <div class="px-5 pt-3"
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
                                                        <label for="">Bank Name:</label>
                                                        <input type="text" class="form-control" v-model="billing.bank_name[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.account_name">
                                                        <label for="">Account Name:</label>
                                                        <input type="text" class="form-control" v-model="billing.account_name[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.account_iban">
                                                        <label for="">Account IBAN:</label>
                                                        <input type="text" class="form-control" v-model="billing.account_iban[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.swift_code">
                                                        <label for="">SWIFT Code:</label>
                                                        <input type="text" class="form-control" v-model="billing.swift_code[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.beneficiary_add">
                                                        <label for="">Beneficiary Address:</label>
                                                        <input type="text" class="form-control" v-model="billing.beneficiary_add[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.account_holder">
                                                        <label for="">Account Holder:</label>
                                                        <input type="text" class="form-control" v-model="billing.account_holder[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.account_type">
                                                        <label for="">Account Type:</label>
                                                        <input type="text" class="form-control" v-model="billing.account_type[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.routing_num">
                                                        <label for="">Routing Number:</label>
                                                        <input type="text" class="form-control" v-model="billing.routing_num[payment_method.id]">
                                                    </div>
                                                    <div class="col-sm-12" v-show="payment_method.wire_routing_num">
                                                        <label for="">Wire Routing Number:</label>
                                                        <input type="text" class="form-control" v-model="billing.wire_routing_num[payment_method.id]">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- end of payment for seller and writer -->

                            <!-- payment for buyer -->
                            <table class="table no-margin" v-if="user.user_type != null && user.user_type.type === 'Buyer'">
                                <tbody>
                                    <tr v-for="(payment_method, index) in paymentMethodListReceivePayment" :key="index">
                                        <td>
                                            <div class="form-group">
                                                <label>{{ payment_method.type }} {{ $t('message.profile.b_account') }}</label>
                                            </div>
                                        </td>
                                        <td style="width: 50px;vertical-align:middle;" class="text-center">
                                            <input type="radio" class="btn-radio-custom" name="payment_default" v-bind:value="payment_method.id" v-model="billing.payment_default">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" v-model="billing.payment_type[payment_method.id]">
                                            <span
                                                v-if="messageForms.errors.hasOwnProperty('payment_type.'+ payment_method.id)"
                                                v-for="err in messageForms.errors['payment_type.'+ payment_method.id]"
                                                class="text-danger">

                                                {{ err }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- end of payment for buyer -->

                        </div>
                    </div>

                    <div class="row mb-2 pl-3">
                        <div>
                            <button type="button" @click="submitUpdate" class="btn btn-primary">
                                {{ $t('message.profile.save') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" v-if="currentUser.isOurs == 1 && currentUser.role_id == 5 && (currentUser.registration != null && currentUser.registration.is_sub_account == 0)">
            <div class="col-sm-12">
                <div class="box box-primary">
                    <div class="box-header"  >
                        <h3 class="box-title">{{ $t('message.profile.sa_title') }}</h3>
                    </div>

                    <div class="box-body no-padding" >
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <tbody>
                                <tr>
                                    <td><b>{{ $t('message.profile.p_username') }}</b></td>
                                    <td>
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.username}" class="form-group">
                                            <input type="text" v-model="modelAddSubAccount.username" class="form-control" required="required" >
                                            <span v-if="messageForms.errors.username" v-for="err in messageForms.errors.username" class="text-danger">{{ err }}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>{{ $t('message.profile.p_email') }}</b></td>
                                    <td>
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.email}" class="form-group">
                                            <input type="text" v-model="modelAddSubAccount.email" class="form-control" required="required" >
                                            <span v-if="messageForms.errors.email" v-for="err in messageForms.errors.email" class="text-danger">{{ err }}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>{{ $t('message.profile.sa_password') }}</b></td>
                                    <td>
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.password}" class="form-group">
                                            <input type="password" v-model="modelAddSubAccount.password" class="form-control" required="required">
                                            <span v-if="messageForms.errors.password" v-for="err in messageForms.errors.password" class="text-danger">{{ err }}</span>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <button type="button" @click="addSubAccount" class="btn btn-primary">
                            {{ $t('message.profile.sa_title') }}
                        </button>
                    </div>

                    <div class="col-md-12">
                        <table class="table table-condensed table-hover table-bordered mt-4">
                            <thead>
                            <tr class="label-primary">
                                <th>{{ $t('message.profile.p_username') }}</th>
                                <th>{{ $t('message.profile.p_email') }}</th>
                                <th>{{ $t('message.profile.sa_credit_auth') }}</th>
                                <th>{{ $t('message.profile.sa_show_price_basis') }}</th>
                                <th>{{ $t('message.profile.sa_can_validate') }}</th>
                                <th>{{ $t('message.profile.ac_status') }}</th>
                                <th>{{ $t('message.profile.sa_action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(account, index) in ListSubAccounts" :key="index">
                                <td>{{ account.username }}</td>
                                <td>{{ account.email }}</td>
                                <td>{{ account.credit_auth }}</td>
                                <td>{{ account.is_show_price_basis === 1 ? $t('message.profile.yes') : $t('message.profile.no') }}</td>
                                <td>{{ account.can_validate_backlink === 1 ? $t('message.profile.yes') : $t('message.profile.no') }}</td>
                                <td>{{ account.status }}</td>
                                <td>
                                    <button class="btn btn-default" :title="$t('message.profile.edit')" @click="doUpdateSubAccounts(account)" data-toggle="modal" data-target="#modal-edit-sub-account">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <button class="btn btn-default" :title="$t('message.profile.delete')" @click="deleteSubAccount(account.id)"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Refund request -->
        <div class="modal fade" id="modalRefundReq" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.profile.rr_title') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ $t('message.profile.rr_amount') }}</label>
                                    <input type="number" class="form-control" v-model="refundModel.amount" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-sm-12" v-if="user.user_payment_types">
                                <div class="form-group" v-for="type in user.user_payment_types" :key="type.id" v-if="type.is_default == 1">
                                    <label v-for="option in paymentMethodList" :key="option.id" v-if="option.id === type.payment_id">
                                        {{ option.type }}
                                    </label>
                                    <input type="text" class="form-control" placeholder="0.00" :value="type.account" :disabled="true">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="submitRefund()">
                            {{ $t('message.profile.rr_refund') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Refund request -->

        <!-- Modal Edit Sub Account -->
        <div class="modal fade" id="modal-edit-sub-account" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.profile.ea_title') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div v-if="!isShowPriceBasis(currentUser)" class="row">
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <strong>
                                    <i class="fa fa-info-circle"></i>
                                    {{ $t('message.profile.ea_note') }}
                                </strong>

                                <span>
                                    {{ $t('message.profile.ea_note_long') }}
                                </span>

                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.profile.p_username') }}</label>
                                    <input type="text" class="form-control" v-model="updateModelSubAccount.username" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.profile.p_name') }}</label>
                                    <input type="text" class="form-control" v-model="updateModelSubAccount.name" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.profile.sa_password') }}</label>
                                    <input type="password" class="form-control" v-model="updateModelSubAccount.password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageErrors.c_password != ''}" class="form-group">
                                    <label>{{ $t('message.profile.p_confirm_pass') }}</label>
                                    <input type="password" class="form-control" v-model="updateModelSubAccount.c_password">
                                    <span v-show="messageErrors.c_password != ''" class="text-danger">{{ messageErrors.c_password }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.profile.p_email') }}</label>
                                    <input type="text" class="form-control" v-model="updateModelSubAccount.email" :disabled="true">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.profile.ac_status') }}</label>
                                    <select class="form-control" v-model="updateModelSubAccount.status">
                                        <option value="active">{{ $t('message.profile.active') }}</option>
                                        <option value="inactive">{{ $t('message.profile.inactive') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.profile.sa_credit_auth') }}</label>
                                    <select class="form-control" v-model="updateModelSubAccount.credit_auth">
                                        <option value="Yes">{{ $t('message.profile.yes') }}</option>
                                        <option value="No">{{ $t('message.profile.no') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.profile.ea_is_show_price_basis') }}</label>
                                    <select
                                        v-model="updateModelSubAccount.is_show_price_basis"
                                        class="form-control"
                                        :disabled="!isShowPriceBasis(currentUser)">

                                        <option value="yes">{{ $t('message.profile.yes') }}</option>
                                        <option value="no">{{ $t('message.profile.no') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.profile.sa_can_validate') }}</label>
                                    <select
                                        v-model="updateModelSubAccount.can_validate_backlink"
                                        class="form-control">

                                        <option value="1">{{ $t('message.profile.yes') }}</option>
                                        <option value="0">{{ $t('message.profile.no') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.profile.close') }}
                        </button>
                        <button type="button" class="btn btn-primary" @click="submitUpdateSubAccount">
                            {{ $t('message.profile.update') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Edit Sub Account -->

        <terms-and-conditions></terms-and-conditions>
    </div>
</template>

<style scoped>
    .avatar-img {
        height: 300px;
        width: 300px;
        object-fit: cover;
        border: 8px solid silver;
    }

    .btn-radio-custom {
        transform: scale(2);
    }
</style>

<script>
import axios from 'axios';
import {mapActions, mapState} from 'vuex';
import config from '@/config';
import Hepler from '@/library/Helper';
import TermsAndConditions from "../../../components/terms/TermsAndConditions";
import {buyerAccess} from "../../../mixins/buyerAccess";

export default {
    name: 'Profile',
    mixins: [buyerAccess],
    components: {TermsAndConditions},
    data() {
        return {
            modelAddSubAccount: {
                username: '',
                email: '',
                password: '',
            },
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
            ],
            publisher:{
                country_id: 0,
                country_id_of_user: [],
            },
            isBuyer: false,
            isSeller: false,
            billing: {
                btc_account: '',
                paypal_account: '',
                skrill_account: '',
                payment_default: '',
                payment_type: [],
                bank_name: [],
                account_name: [],
                account_iban: [],
                swift_code: [],
                beneficiary_add: [],
                account_holder: [],
                account_type: [],
                routing_num: [],
                wire_routing_num: [],
            },
            new_password: '',
            c_password: '',
            ListSubAccounts: [],
            updateModelSubAccount: {
                name: '',
                username: '',
                email: '',
                status: '',
                password: '',
                c_password: '',
                credit_auth : '',
                is_show_price_basis: '',
                can_validate_backlink: 0
            },
            messageErrors:{
                c_password: '',
            },
            company_type: '',
            CompanyName: true,
            countryList: [],
            country_id: '',
            refundModel: {
                amount: ''
            },
            paymentMethodList: [],
            canRefund: false,
            isWithonProcess: false,
            // affiliates

            affiliateBuyers: {
                data: []
            },

            validate_error_type: false,
        };
    },

    computed: {
        ...mapState({
            currentUser: state => state.storeAuth.currentUser,
            user: state => state.storeUser.userInfor,
            totalExt: state => state.storeExtDomain.totalExtDomain,
            totalInt: state => state.storeIntDomain.totalIntDomain,
            totalBackLink: state => state.storeBackLink.totalBackLink,
            totalPrice: state => state.storeBackLink.totalPrice,
            messageForms: state => state.storeUser.messageForms,
            listUser: state => state.storeUser.listUser,
            summaryPublish: state => state.storePublisher.summaryPublish,
        }),

        paymentMethodListSendPayment: function() {
            return this.paymentMethodList.filter(i => i.send_payment === 'yes')
        },

        paymentMethodListReceivePayment: function() {
            return this.paymentMethodList.filter(i => i.receive_payment === 'yes')
        },
    },

    async created() {
        await this.$store.dispatch('actionCheckAdminCurrentUser', { vue: this });
        let userId = this.$route.params.id || null;
        await this.$store.dispatch('actionGetUserInformation', {
            vue: this,
            id: userId,
        });

        this.checkAccountType();
        this.getPublisherSummaryCountry();
        this.bindPayment();
        this.getListPaymentMethod();
        this.checkCanRefund();
        this.checkOnProcessRefund();

        // console.log(this.user)

        if(this.user.user_type) {
            this.company_type = this.user.user_type.is_freelance == '0' ? 'Company':'Freelancer';
            this.CompanyName = this.user.user_type.is_freelance == '0' ? true:false;
            this.country_id = this.user.user_type.country_id;
        }

        this.getListCountry();

        this.$root.$refs.AppHeader.liveGetWallet()

        this.getAffiliateBuyers();
    },

    mounted() {
        this.getListSubAccount();
    },

    methods: {
        ...mapActions({
            getAffiliateCodeSet : "getAffiliateCodeSet",
        }),

        getListPaymentMethod() {
            axios.get('/api/payment-list-registration')
                .then((res) => {
                    this.paymentMethodList = res.data.data
                })
        },

        checkCanRefund() {
            // check if buyer
            if(this.user.isOurs == 1 && this.user.role_id == 5) {
                let wallet = JSON.parse(localStorage.getItem('wallet'))
                let credit = wallet.credit;

                if(credit <= 0) {
                    this.canRefund = false;
                } else {
                    this.canRefund = true;
                }
            }
        },

        checkOnProcessRefund() {
            axios.get('/api/check-on-process-refund')
                .then((res) => {
                    this.isWithonProcess = res.data.result;
                })
        },

        submitRefund() {
            let self = this;
            let wallet = JSON.parse(localStorage.getItem('wallet'))
            let credit = wallet.credit;
            let payment_id = 0;

            let loader = this.$loading.show();

            if(this.isWithonProcess) {
                swal.fire('Error', self.$t('message.profile.rr_error_on_process'), 'error');
                loader.hide();
                return false;
            }

            if(this.user.user_payment_types) {
                for(let i in this.user.user_payment_types) {
                    if(this.user.user_payment_types[i].is_default === 1) {
                        payment_id = this.user.user_payment_types[i].payment_id;
                    }
                }
            }

            if(payment_id == 0) {
                swal.fire(self.$t('message.profile.alert_error'), self.$t('message.profile.rr_error_payment_method'), 'error');
                loader.hide();
                return false;
            }

            if(credit <= 0) {
                swal.fire(self.$t('message.profile.alert_error'), self.$t('message.profile.rr_error_credits'), 'error');
                loader.hide();
                return false;
            }

            if(this.refundModel.amount == "" || this.refundModel.amount <= 0) {
                swal.fire(self.$t('message.profile.alert_error'), self.$t('message.profile.rr_error_amount'), 'error');
                loader.hide();
                return false;
            }

            if(this.refundModel.amount > credit) {
                swal.fire(self.$t('message.profile.alert_error'), self.$t('message.profile.rr_error_credits_greater'), 'error');
                loader.hide();
                return false;
            }

            this.refundModel.payment_id = payment_id;

            axios.post('/api/refund-wallet', this.refundModel)
                .then((res) => {
                    loader.hide();

                    if(res.data.success) {
                        swal.fire(self.$t('message.profile.alert_error'), self.$t('message.profile.rr_error_on_process'), 'error');
                    } else {
                        swal.fire('Done', self.$t('message.profile.rr_refund_submitted'), 'success');

                        this.refundModel.amount = '';
                        $("#modalRefundReq").modal('hide')
                    }
                })

            console.log(this.user)
        },

        submitUpload() {
            this.formData = new FormData();
            this.formData.append('photo', this.$refs.photo.files[0]);

            if( this.$refs.photo.value != '' ) {
                axios.post('/api/user-upload-avatar', this.formData)
                .then((res) => {
                    location.reload();
                })
            }

        },

        getListCountry() {
            axios.get('/api/registration-country-list')
                .then((res) => {
                    this.countryList = res.data.data;
                })
        },

        checkCompanyType() {
            if(this.company_type == 'Company') {
                this.CompanyName = true;
            } else {
                this.CompanyName = false;
            }
        },

        submitUpdateSubAccount() {
            let self = this;

            axios.get('/api/update-sub-account', {
                params: {
                    id: this.updateModelSubAccount.id,
                    email: this.updateModelSubAccount.email,
                    name: this.updateModelSubAccount.name,
                    username: this.updateModelSubAccount.username,
                    status: this.updateModelSubAccount.status,
                    password: this.updateModelSubAccount.password,
                    c_password: this.updateModelSubAccount.c_password,
                    credit_auth: this.updateModelSubAccount.credit_auth,
                    is_show_price_basis: this.updateModelSubAccount.is_show_price_basis,
                    can_validate_backlink: this.updateModelSubAccount.can_validate_backlink
                }
            })
            .then((res) => {
                // console.log(res.data)
                this.getListSubAccount();
                $("#modal-edit-sub-account").modal('hide')

                swal.fire(
                    self.$t('message.profile.alert_update'),
                    self.$t('message.profile.alert_success_update'),
                    'success'
                )

                this.messageErrors = {
                    c_password: '',
                }
            })
            .catch(error => {
                if (error.response) {
                    this.messageErrors.c_password = error.response.data.errors.c_password[0]
                }
            })
        },

        deleteSubAccount(id) {
            let self = this;

            swal.fire({
                title: self.$t('message.profile.alert_confirm'),
                html: self.$t('message.profile.alert_delete_account'),
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: self.$t('message.profile.delete_yes'),
                cancelButtonText: self.$t('message.profile.keep')
            })
            .then((result) => {
                if (result.isConfirmed) {

                    axios.get('/api/delete-sub-account',{ params: { id: id } })

                    swal.fire(
                        self.$t('message.profile.alert_deleted'),
                        self.$t('message.profile.alert_deleted_sub_account'),
                        'success'
                    )

                    this.getListSubAccount();
                }
            });
        },

        doUpdateSubAccounts(account) {
            this.messageErrors = {
                c_password: '',
            }

            this.updateModelSubAccount.id = account.id;
            this.updateModelSubAccount.name = account.name;
            this.updateModelSubAccount.username = account.username;
            this.updateModelSubAccount.status = account.status;
            this.updateModelSubAccount.email = account.email;
            this.updateModelSubAccount.credit_auth = account.credit_auth;
            this.updateModelSubAccount.is_show_price_basis = account.is_show_price_basis === 1 ? 'yes' : 'no'
        },

        getListSubAccount() {
            axios.get('/api/sub-account')
                .then((res) => {
                    this.ListSubAccounts = res.data;
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
                    });
                });
            })
        },

        bindPayment() {
            let that = this.user.user_type

            console.log(this.currentUser, that)
            if( this.currentUser.isOurs == 1 ) {
                // this.billing.paypal_account = that.paypal_account;
                // this.billing.skrill_account = that.skrill_account;
                // this.billing.btc_account = that.btc_account;
                this.billing.payment_default = this.currentUser.id_payment_type;

                if(typeof this.currentUser.user_payment_types != "undefined" && this.currentUser.user_payment_types != null){
                    if(this.currentUser.user_payment_types.length > 0) {
                        for(let index in this.currentUser.user_payment_types) {
                            var payment_id = this.currentUser.user_payment_types[index].payment_id;
                            var bank_name = this.currentUser.user_payment_types[index].bank_name == null ? '':JSON.parse(this.currentUser.user_payment_types[index].bank_name)
                            var account_name = this.currentUser.user_payment_types[index].account_name == null ? '':JSON.parse(this.currentUser.user_payment_types[index].account_name)
                            var account_iban = this.currentUser.user_payment_types[index].account_iban == null ? '':JSON.parse(this.currentUser.user_payment_types[index].account_iban)
                            var swift_code = this.currentUser.user_payment_types[index].swift_code == null ? '':JSON.parse(this.currentUser.user_payment_types[index].swift_code)
                            var beneficiary_add = this.currentUser.user_payment_types[index].beneficiary_add == null ? '':JSON.parse(this.currentUser.user_payment_types[index].beneficiary_add)
                            var account_holder = this.currentUser.user_payment_types[index].account_holder == null ? '':JSON.parse(this.currentUser.user_payment_types[index].account_holder)
                            var account_type = this.currentUser.user_payment_types[index].account_type == null ? '':JSON.parse(this.currentUser.user_payment_types[index].account_type)
                            var routing_num = this.currentUser.user_payment_types[index].routing_num == null ? '':JSON.parse(this.currentUser.user_payment_types[index].routing_num)
                            var wire_routing_num = this.currentUser.user_payment_types[index].wire_routing_num == null ? '':JSON.parse(this.currentUser.user_payment_types[index].wire_routing_num)

                            this.billing.payment_type[payment_id] = this.currentUser.user_payment_types[index].account

                            this.billing.bank_name[payment_id] = bank_name[payment_id] == null ? '':bank_name[payment_id];
                            this.billing.account_name[payment_id] = account_name[payment_id] == null ? '':account_name[payment_id];
                            this.billing.account_iban[payment_id] = account_iban[payment_id] == null ? '':account_iban[payment_id];
                            this.billing.swift_code[payment_id] = swift_code[payment_id] == null ? '':swift_code[payment_id];
                            this.billing.beneficiary_add[payment_id] = beneficiary_add[payment_id] == null ? '':beneficiary_add[payment_id];
                            this.billing.account_holder[payment_id] = account_holder[payment_id] == null ? '':account_holder[payment_id];
                            this.billing.account_type[payment_id] = account_type[payment_id] == null ? '':account_type[payment_id];
                            this.billing.routing_num[payment_id] = routing_num[payment_id] == null ? '':routing_num[payment_id];
                            this.billing.wire_routing_num[payment_id] = wire_routing_num[payment_id] == null ? '':wire_routing_num[payment_id];
                        }
                    }
                }
            }
        },

        async addSubAccount() {
            let self = this;

            await this.$store.dispatch('actionAddSubAccount', this.modelAddSubAccount);

            if (this.messageForms.action === 'saved_account') {
                swal.fire(
                    self.$t('message.profile.alert_success'),
                    self.$t('message.profile.alert_success_created'),
                    'success'
                )

                this.modelAddSubAccount = {
                    username: '',
                    email: '',
                    password: '',
                }

                this.getListSubAccount();
            }

        },

        async getPublisherSummaryCountry() {
            let that = this;
            that.publisher.country_id_of_user = [],

            that.publisher.country_id = that.user.id;
            await this.$store.dispatch('getSummaryPublisher', {
                vue: this,
                params: this.publisher,
            });
        },

        async submitUpdate() {
            let self = this;
            this.isPopupLoading = true;

            if( this.currentUser.isOurs == 1 ) {
                // this.user.user_type.paypal_account = this.billing.paypal_account;
                // this.user.user_type.skrill_account = this.billing.skrill_account;
                // this.user.user_type.btc_account = this.billing.btc_account;

                this.user.payment_type = this.billing.payment_type;
                this.user.bank_name = this.billing.bank_name;
                this.user.account_name = this.billing.account_name;
                this.user.account_iban = this.billing.account_iban;
                this.user.swift_code = this.billing.swift_code;
                this.user.beneficiary_add = this.billing.beneficiary_add;
                this.user.account_holder = this.billing.account_holder;
                this.user.account_type = this.billing.account_type;
                this.user.routing_num = this.billing.routing_num;
                this.user.wire_routing_num = this.billing.wire_routing_num;
                this.user.id_payment_type = this.billing.payment_default;
                this.user.user_type.company_type = this.company_type;
                this.user.user_type.country_id = this.country_id;

                if (this.billing.payment_default) {
                    if(!this.user.payment_type[this.billing.payment_default]) {

                        this.validate_error_type = true;

                        await swal.fire(
                            self.$t('message.registration_accounts.alert_error'),
                            self.$t('message.registration_accounts.alert_error_update'),
                            'error'
                        );

                        return false;
                    }
                }

            }

            this.user.password = this.new_password;
            this.user.c_password = this.c_password;

            await this.$store.dispatch('actionUpdateUser', this.user);
            this.isPopupLoading = false;

            if (this.messageForms.action === 'updated_user') {
                for (var index in this.listUser.data) {
                    if (this.listUser.data[index].id === this.user.id) {
                        this.listUser.data[index] = this.user;
                        break;
                    }
                }

                swal.fire(
                    self.$t('message.profile.alert_update'),
                    self.$t('message.profile.alert_info_updated'),
                        'success'
                        )

                this.new_password = '';
                this.c_password = '';
                this.validate_error_type = false;
            } else {
                swal.fire(
                    self.$t('message.profile.alert_failed'),
                    self.$t('message.profile.alert_info_not_updated'),
                    'error'
                )
            }
        },

        checkAccountType() {
            let that = this.currentUser
            if( that.user_type ){
                if( that.user_type.type == 'Buyer' ){
                    this.isBuyer = true;
                }
            }
        },

        checkArray(array) {
            return Hepler.arrayNotEmpty(array);
        },

        convertPrice(price) {
            return parseFloat(price).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        },

        generateAffiliateCode() {
            let self = this;
            let loader = this.$loading.show();

            this.user.user_type.affiliate_code = this.generateRandomString(10);

            axios.post('/api/profile/add-affiliate-code', {
                code: this.user.user_type.affiliate_code,
                registration_id: this.user.user_type.id
            })
            .then((res) => {
                loader.hide();

                this.getAffiliateCodeSet();

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
                    self.$t('message.profile.alert_error_affiliate_code'),
                    'error'
                )
            })
        },

        copyAffiliateCode() {
            let self = this;
            this.$refs.affiliateCode.focus();
            document.execCommand('copy');

            swal.fire(
                self.$t('message.profile.alert_copied'),
                self.$t('message.profile.alert_success_copied'),
                'success'
            )
        },

        generateRandomString(length) {
            let randomChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let result = '';

            for ( let i = 0; i < length; i++ ) {
                result += randomChars.charAt(Math.floor(Math.random() * randomChars.length));
            }
            return result;
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
