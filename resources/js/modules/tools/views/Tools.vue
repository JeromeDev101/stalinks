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
                        <h3 class="card-title text-primary">{{ $t('message.tools.filter_title') }}</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.tools.filter_tool') }}</label>

                                    <input
                                        v-model="filterModel.name"
                                        type="text"
                                        :placeholder="$t('message.tools.filter_search_tool')"
                                        class="form-control pull-right">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.tools.filter_url') }}</label>

                                    <input
                                        v-model="filterModel.url"
                                        type="text"
                                        :placeholder="$t('message.tools.filter_search_url')"
                                        class="form-control pull-right">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.tools.filter_username') }}</label>

                                    <input
                                        v-model="filterModel.username"
                                        type="text"
                                        :placeholder="$t('message.tools.filter_search_username')"
                                        class="form-control pull-right">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.tools.filter_details') }}</label>

                                    <input
                                        v-model="filterModel.details"
                                        type="text"
                                        id="filterDetails"
                                        name="filterDetails"
                                        autocomplete="disabled"
                                        class="form-control pull-right"
                                        :placeholder="$t('message.tools.filter_search_details')">
                                </div>
                            </div>
                        </div>

                        <div class="row my-3">
                            <div class="col-md-4">
                                <button class="btn btn-default" @click="clearFilter">
                                    {{ $t('message.tools.clear') }}
                                </button>

                                <button class="btn btn-default" @click="filterToolList">
                                    {{ $t('message.tools.search') }}
                                    <i class="fa fa-refresh fa-spin" v-if="isSearchLoading"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">{{ $t('message.tools.t_title') }}</h3>
                        <div class="card-tools">
                            <button
                                v-if="user.permission_list.includes('create-management-tools')"
                                class="btn btn-success btn-tool"
                                data-toggle="modal"
                                data-target="#modal-add-tool"

                                @click="modalOpener('add')">

                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <span class="pagination-custom-footer-text m-0">
                            <b v-if="filterModel.paginate !== 'All'">
                                {{ $t('message.others.table_entries', { from: listTools.from, to: listTools.to, end: listTools.total }) }}
                            </b>

                            <b v-else>
                                {{ $t('message.others.table_all_entries', { total: listTools.total }) }}
                            </b>
                        </span>

                        <div class="input-group input-group-sm float-right mb-2" style="width: 65px">
                            <select
                                v-model="filterModel.paginate"
                                style="height: 37px;"
                                class="form-control pull-right"

                                @change="getToolList">

                                <option v-for="value in listPageOptions" :value="value">{{ value }}</option>
                            </select>
                        </div>

                        <div class="table-responsive">
                            <table id="tbl-tools" class="table table-hover table-bordered table-striped rlink-table">
                                <thead>
                                    <tr class="label-primary">
                                        <th>#ID</th>
                                        <th>{{ $t('message.tools.filter_tool') }}</th>
                                        <th>{{ $t('message.tools.filter_url') }}</th>
                                        <th>{{ $t('message.tools.filter_username') }}</th>
                                        <th>{{ $t('message.tools.t_password') }}</th>
                                        <th>{{ $t('message.tools.filter_details') }}</th>
                                        <th>{{ $t('message.tools.table_reg_date') }}</th>
                                        <th>{{ $t('message.tools.table_exp_date') }}</th>
                                        <th>{{ $t('message.tools.t_action') }}</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <tr v-for="(tool, index) in listTools.data" :key="index">
                                        <td class="center-content">{{ tool.id }}</td>
                                        <td>{{ tool.name }}</td>
                                        <td>
                                            <a v-if="tool.url" :href="'//' + tool.url" target="_blank">
                                                {{ tool.url }}
                                            </a>
                                            <span v-else>N/A</span>
                                        </td>
                                        <td>{{ tool.username }}</td>
                                        <td>
                                        <span>
                                            <i :ref="'eyes' + index" class="fa fa-eye" @click="togglePassword(index)"></i>
                                        </span>

                                            <span :ref="'badge' + index" class="badge badge-secondary">
                                            {{ $t('message.tools.t_hidden') }}
                                        </span>

                                            <span :ref="'pass' + index" style="display: none">{{ tool.password }}</span>
                                        </td>
                                        <td>
                                            <div style="white-space: pre-line;">{{ tool.details }}</div>
                                        </td>
                                        <td>
                                        <span v-if="tool.registered_at">
                                            {{ tool.registered_at }}
                                        </span>

                                            <span v-else>N/A</span>
                                        </td>
                                        <td>
                                            <div v-if="tool.expired_at">
                                            <span>
                                                {{ tool.expired_at }}
                                            </span>

                                                <span v-if="tool.expiring_days !== null" :class="expireBadgeColor(tool.expiring_days)">
                                                {{ tool.expiring_days > 0 ? tool.expiring_days + $t('message.tools.days_left') : $t('message.tools.expired') }}
                                            </span>
                                            </div>

                                            <div v-else>
                                                <span>N/A</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <button
                                                    v-if="user.permission_list.includes('update-management-tools')"
                                                    type="button"
                                                    class="btn btn-default"
                                                    :title="$t('message.tools.t_edit_tool')"

                                                    @click="updateTool(tool); modalOpener('update')">

                                                    <i class="fa fa-edit"></i>
                                                </button>

                                                <button
                                                    v-if="user.permission_list.includes('delete-management-tools')"
                                                    type="button"
                                                    class="btn btn-default"
                                                    :title="$t('message.tools.t_delete_tool')"

                                                    @click="deleteTool(tool.id)">

                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <pagination :data="listTools" @pagination-change-page="getToolList" :limit="8"></pagination>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-add-tool" ref="modalTool" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title">{{ modalMode === 'add' ? $t('message.tools.au_add') : $t('message.tools.update') }} Tool</h4>
                    </div>

                    <div class="modal-body relative">

                        <div v-if="modalMode === 'update' && updateToolModel.expiring_days <= 30" class="row">
                            <div class="col-md-12">
                                <div class="alert alert-danger" role="alert">
                                    {{ $t('message.tools.expired_reminder') }}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageFormsTools.errors.name}">
                                    <label>{{ $t('message.tools.filter_tool') }}</label>
                                    <input
                                        v-model="modelName"
                                        type="text"
                                        class="form-control"
                                        :placeholder="$t('message.tools.au_enter_tool_name')">

                                    <span
                                        v-if="messageFormsTools.errors.name"
                                        v-for="err in messageFormsTools.errors.name"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageFormsTools.errors.url}">
                                    <label>{{ $t('message.tools.filter_url') }}</label>
                                    <input
                                        v-model="modelUrl"
                                        type="text"
                                        class="form-control"
                                        :placeholder="$t('message.tools.au_enter_url')">

                                    <span
                                        v-if="messageFormsTools.errors.url"
                                        v-for="err in messageFormsTools.errors.url"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageFormsTools.errors.username}">
                                    <label>{{ $t('message.tools.filter_username') }}</label>
                                    <input
                                        v-model="modelUsername"
                                        type="text"
                                        class="form-control"
                                        :placeholder="$t('message.tools.au_enter_username')">

                                    <span
                                        v-if="messageFormsTools.errors.username"
                                        v-for="err in messageFormsTools.errors.username"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label>{{ $t('message.tools.t_password') }}</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <button
                                            type="button"
                                            class="btn btn-info"

                                            @click="showPassword()">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>

                                    <input
                                        v-model="modelPassword"
                                        type="password"
                                        ref="toolPassword"
                                        class="form-control"
                                        id="toolPasswordField"
                                        aria-label="Amount (to the nearest dollar)"

                                        @focus="$event.target.select()">

                                    <div class="input-group-append">
                                        <button
                                            type="button"
                                            class="btn btn-success"
                                            @click="copyPassword()">

                                            <i class="fa fa-copy"></i>
                                        </button>
                                    </div>
                                </div>

                                <span
                                    v-if="messageFormsTools.errors.password"
                                    v-for="err in messageFormsTools.errors.password"
                                    class="text-danger">
                                    {{ err }}
                                </span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageFormsTools.errors.registered_at}">
                                    <label>{{ $t('message.tools.registration_date') }}</label>
                                    <input
                                        v-model="modelRegisteredAt"
                                        type="date"
                                        class="form-control"
                                        :disabled="modalMode === 'update'">

                                    <span
                                        v-if="messageFormsTools.errors.registered_at"
                                        v-for="err in messageFormsTools.errors.registered_at"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageFormsTools.errors.expired_at}">
                                    <label>{{ $t('message.tools.expiration_date') }}</label>

                                    <div class="input-group mb-3">
                                        <input
                                            v-model="modelExpiredAt"
                                            type="date"
                                            class="form-control"
                                            :disabled="modalMode === 'update'">

                                        <div
                                            v-if="updateToolModel.expiring_days <= 30 && modalMode === 'update'"
                                            class="input-group-append">

                                            <button
                                                type="button"
                                                title="Renew Tool"
                                                class="btn btn-info"
                                                @click="isShowRenewModal = true">

                                                <i class="fas fa-sync"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <span
                                        v-if="messageFormsTools.errors.expired_at"
                                        v-for="err in messageFormsTools.errors.expired_at"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageFormsTools.errors.details}">
                                    <label>{{ $t('message.tools.filter_details') }}</label>
                                    <textarea
                                        v-model="modelDetails"
                                        rows="5"
                                        class="form-control">

                                        </textarea>

                                    <span
                                        v-if="messageFormsTools.errors.details"
                                        v-for="err in messageFormsTools.errors.details"
                                        class="text-danger">

                                            {{ err }}
                                        </span>
                                </div>
                            </div>
                        </div>

                        <div v-if="modalMode === 'add'" class="row">
                            <div class="col-md-12">
                                <div class="form-group form-check">
                                    <input
                                        v-model="modelIsPurchased"
                                        type="checkbox"
                                        id="isPurchased"
                                        class="form-check-input">

                                    <label class="form-check-label" for="isPurchased">{{ $t('message.tools.is_purchased') }}</label>
                                </div>
                            </div>
                        </div>

                        <div v-if="modelIsPurchased && modalMode === 'add'">
                            <purchase-details v-model="purchaseDetails" :errors="messageFormsTools"/>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            {{ $t('message.tools.close') }}
                        </button>

                        <button
                            v-if="modalMode === 'add'"
                            type="button"
                            class="btn btn-primary"

                            @click="submitAddTool">

                            {{ $t('message.tools.save') }}
                        </button>

                        <button
                            v-else
                            type="button"
                            class="btn btn-primary"

                            @click="submitUpdateTool">

                            {{ $t('message.tools.update') }}
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <renew
            :mode="'Tool'"
            :purchaseable="true"
            :refs="'renewToolModal'"
            :model="'App\\Models\\Tool'"
            :details="updateToolModel"
            :visible="isShowRenewModal"

            @renew="toolRenewed()"
            @close="isShowRenewModal = false"/>
    </div>

    <!--    <div class="row">
            <div class="col-sm-12">

                &lt;!&ndash; FILTERS &ndash;&gt;
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Filter</h3>
                    </div>

                    <div class="box-body my-2 mx-3">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tool</label>

                                    <input
                                        v-model="filterModel.name"
                                        type="text"
                                        placeholder="Search Tool Name"
                                        class="form-control pull-right">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>URL</label>

                                    <input
                                        v-model="filterModel.url"
                                        type="text"
                                        placeholder="Search URL"
                                        class="form-control pull-right">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Username</label>

                                    <input
                                        v-model="filterModel.username"
                                        type="text"
                                        placeholder="Search Username"
                                        class="form-control pull-right">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Details</label>

                                    <input
                                        v-model="filterModel.details"
                                        type="text"
                                        placeholder="Search Details"
                                        class="form-control pull-right">
                                </div>
                            </div>
                        </div>

                        <div class="row my-3">
                            <div class="col-md-4">
                                <button class="btn btn-default" @click="clearFilter">Clear</button>

                                <button class="btn btn-default" @click="filterToolList">Search
                                    <i class="fa fa-refresh fa-spin" v-if="isSearchLoading"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                &lt;!&ndash; TABLE &ndash;&gt;
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Tools</h3>

                        <button
                            class="btn btn-success float-right"
                            data-toggle="modal"
                            data-target="#modal-add-tool"

                            @click="modalOpener('add')">

                            <i class="fa fa-plus"></i>
                        </button>

                        <div class="input-group input-group-sm float-right" style="width: 65px">
                            <select
                                v-model="filterModel.paginate"
                                style="height: 37px;"
                                class="form-control pull-right"

                                @change="getToolList">

                                <option v-for="value in listPageOptions" :value="value">{{ value }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="box-body no-padding relative">
                        <span class="pagination-custom-footer-text m-0 pl-2">
                            <b>Showing {{ listTools.from }} to {{ listTools.to }} of {{ listTools.total }} entries.</b>
                        </span>

                        <table id="tbl-tools" class="table table-hover table-bordered table-striped rlink-table">
                            <thead>
                            <tr class="label-primary">
                                <th>#ID</th>
                                <th>Tool</th>
                                <th>URL</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Details</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                                <tr v-for="(tool, index) in listTools.data" :key="index">
                                    <td class="center-content">{{ tool.id }}</td>
                                    <td>{{ tool.name }}</td>
                                    <td>
                                        <a v-if="tool.url" :href="'//' + tool.url" target="_blank">
                                            {{ tool.url }}
                                        </a>
                                        <span v-else>N/A</span>
                                    </td>
                                    <td>{{ tool.username }}</td>
                                    <td>
                                        <span>
                                            <i :ref="'eyes' + index" class="fa fa-eye" @click="togglePassword(index)"></i>
                                        </span>

                                        <span :ref="'badge' + index" class="badge badge-secondary">hidden</span>

                                        <span :ref="'pass' + index" style="display: none">{{ tool.password }}</span>
                                    </td>
                                    <td>
                                        <div style="white-space: pre-line;">{{ tool.details }}</div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            &lt;!&ndash; edit tool &ndash;&gt;
                                            <button
                                                type="button"
                                                title="Edit Tool"
                                                class="btn btn-default"

                                                @click="updateTool(tool); modalOpener('update')">

                                                <i class="fa fa-edit"></i>
                                            </button>

                                            &lt;!&ndash; delete tool &ndash;&gt;
                                            <button
                                                type="button"
                                                title="Delete Tool"
                                                class="btn btn-default"

                                                @click="deleteTool(tool.id)">

                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <pagination :data="listTools" @pagination-change-page="getToolList" :limit="8"></pagination>
                    </div>
                </div>

            </div>

            &lt;!&ndash; Modal Add/Update Tool&ndash;&gt;
            <div class="modal fade" id="modal-add-tool" ref="modalTool" style="display: none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h4 class="modal-title">{{ modalMode === 'add' ? 'Add' : 'Update' }} Tool</h4>
                            <div class="modal-load overlay float-right">
                                <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>
                            </div>
                        </div>

                        <div class="modal-body relative">

                            <div class="row">
                                <div class="col-md-6">
                                    <div :class="{'form-group': true, 'has-error': messageFormsTools.errors.name}">
                                        <label>Tool</label>
                                        <input
                                            v-model="modelName"
                                            type="text"
                                            class="form-control"
                                            placeholder="Enter Tool Name">

                                        <span
                                            v-if="messageFormsTools.errors.name"
                                            v-for="err in messageFormsTools.errors.name"
                                            class="text-danger">

                                        {{ err }}
                                    </span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div :class="{'form-group': true, 'has-error': messageFormsTools.errors.url}">
                                        <label>URL</label>
                                        <input
                                            v-model="modelUrl"
                                            type="text"
                                            class="form-control"
                                            placeholder="Enter Url">

                                        <span
                                            v-if="messageFormsTools.errors.url"
                                            v-for="err in messageFormsTools.errors.url"
                                            class="text-danger">

                                        {{ err }}
                                    </span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div :class="{'form-group': true, 'has-error': messageFormsTools.errors.username}">
                                        <label>Username</label>
                                        <input
                                            v-model="modelUsername"
                                            type="text"
                                            class="form-control"
                                            placeholder="Enter Username">

                                        <span
                                            v-if="messageFormsTools.errors.username"
                                            v-for="err in messageFormsTools.errors.username"
                                            class="text-danger">

                                        {{ err }}
                                    </span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div :class="{'form-group': true, 'has-error': messageFormsTools.errors.password}">
                                        <label>Password</label>
                                        <input
                                            v-model="modelPassword"
                                            type="password"
                                            class="form-control"
                                            placeholder="Enter Password">

                                        <span
                                            v-if="messageFormsTools.errors.password"
                                            v-for="err in messageFormsTools.errors.password"
                                            class="text-danger">

                                            {{ err }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div :class="{'form-group': true, 'has-error': messageFormsTools.errors.details}">
                                        <label>Details</label>
                                        <textarea
                                            v-model="modelDetails"
                                            rows="5"
                                            class="form-control">

                                        </textarea>

                                        <span
                                            v-if="messageFormsTools.errors.details"
                                            v-for="err in messageFormsTools.errors.details"
                                            class="text-danger">

                                            {{ err }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>

                            <button
                                v-if="modalMode === 'add'"
                                type="button"
                                class="btn btn-primary"

                                @click="submitAddTool">

                                Save
                            </button>

                            <button
                                v-else
                                type="button"
                                class="btn btn-primary"

                                @click="submitUpdateTool">

                                Update
                            </button>
                        </div>

                    </div>
                </div>
            </div>
            &lt;!&ndash; Modal Add/Update Tool End &ndash;&gt;

        </div>-->
</template>

<script>
import {mapState} from 'vuex';
import {filterModel} from "../../../mixins/filterModel";
import {copyPassword} from "../../../mixins/copyPassword";
import PurchaseDetails from '@/components/purchase/PurchaseDetails';
import Renew from '@/components/renew/Renew';

export default {
    props : [],
    mixins: [copyPassword, filterModel],
    components: { PurchaseDetails, Renew },
    data() {
        return {
            // misc
            modalMode : '',
            isPopupLoading : false,
            isSearchLoading : false,
            listPageOptions : [
                5,
                10,
                25,
                50,
                100,
                'All'
            ],

            // filter model
            filterModel : {
                url : this.$route.query.url || '',
                page : this.$route.query.page || 0,
                name : this.$route.query.name || '',
                details : this.$route.query.details || '',
                paginate : this.$route.query.paginate || 10,
                username : this.$route.query.username || '',
            },

            // tool model
            toolModel : {
                url : '',
                name : '',
                details : '',
                username : '',
                password : '',
                expired_at: '',
                registered_at: '',
                is_purchased: false,
            },

            updateToolModel : {
                id : '',
                url : '',
                name : '',
                details : '',
                username : '',
                password : '',
                expired_at: '',
                registered_at: '',
                is_purchased: false,
                expiring_days: '',
            },

            purchaseDetails: {
                notes: '',
                amount: '',
                type_id: '',
                payment_type_id: '',
                receipt: '',
                invoice: '',
            },

            isShowRenewModal: false,
        }
    },

    mounted() {
        this.getToolList()
    },

    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,
            listTools : state => state.storeTools.listTools,
            messageFormsTools : state => state.storeTools.messageFormsTools,
        }),

        modelName : {
            get() {
                return this.modalMode === 'add' ? this.toolModel.name : this.updateToolModel.name
            },
            set(val) {
                if (this.modalMode === 'add') {
                    this.toolModel.name = val
                } else {
                    this.updateToolModel.name = val
                }
            }
        },

        modelUrl : {
            get() {
                return this.modalMode === 'add' ? this.toolModel.url : this.updateToolModel.url
            },
            set(val) {
                if (this.modalMode === 'add') {
                    this.toolModel.url = val
                } else {
                    this.updateToolModel.url = val
                }
            }
        },

        modelUsername : {
            get() {
                return this.modalMode === 'add' ? this.toolModel.username : this.updateToolModel.username
            },
            set(val) {
                if (this.modalMode === 'add') {
                    this.toolModel.username = val
                } else {
                    this.updateToolModel.username = val
                }
            }
        },

        modelPassword : {
            get() {
                return this.modalMode === 'add' ? this.toolModel.password : this.updateToolModel.password
            },
            set(val) {
                if (this.modalMode === 'add') {
                    this.toolModel.password = val
                } else {
                    this.updateToolModel.password = val
                }
            }
        },

        modelRegisteredAt : {
            get() {
                return this.modalMode === 'add' ? this.toolModel.registered_at : this.updateToolModel.registered_at
            },
            set(val) {
                if (this.modalMode === 'add') {
                    this.toolModel.registered_at = val
                } else {
                    this.updateToolModel.registered_at = val
                }
            }
        },

        modelExpiredAt : {
            get() {
                return this.modalMode === 'add' ? this.toolModel.expired_at : this.updateToolModel.expired_at
            },
            set(val) {
                if (this.modalMode === 'add') {
                    this.toolModel.expired_at = val
                } else {
                    this.updateToolModel.expired_at = val
                }
            }
        },

        modelDetails : {
            get() {
                return this.modalMode === 'add' ? this.toolModel.details : this.updateToolModel.details
            },
            set(val) {
                if (this.modalMode === 'add') {
                    this.toolModel.details = val
                } else {
                    this.updateToolModel.details = val
                }
            }
        },

        modelIsPurchased : {
            get() {
                return this.modalMode === 'add' ? this.toolModel.is_purchased : this.updateToolModel.is_purchased
            },
            set(val) {
                if (this.modalMode === 'add') {
                    this.toolModel.is_purchased = val
                } else {
                    this.updateToolModel.is_purchased = val
                }
            }
        },
    },

    methods : {

        showPassword() {
            let passwordField = document.querySelector('#toolPasswordField')
            if (passwordField.getAttribute('type') === 'password') passwordField.setAttribute('type', 'text')
            else passwordField.setAttribute('type', 'password')
        },

        copyPassword () {
            this.copyPasswordText(this.modelPassword);
        },

        // queries
        async getToolList(page = 1) {
            let self = this;
            let loader = self.$loading.show();

            self.filterModel.page = page;

            await self.$store.dispatch('actionGetListTools', {params : self.filterModel});

            loader.hide();
        },

        async submitAddTool() {
            let self = this;
            let loader = self.$loading.show();

            let formDataTool = self.generateFormData(self.toolModel)

            // append purchase details
            if (self.toolModel.is_purchased) {
                formDataTool = self.appendPurchaseFormData(formDataTool, self.purchaseDetails);
            }

            self.isPopupLoading = true;
            await self.$store.dispatch('actionAddTool', formDataTool);
            self.isPopupLoading = false;

            if (self.messageFormsTools.action === 'added') {
                self.clearModel('add');
                self.clearModel('purchase');

                await self.getToolList(this.filterModel.page);

                self.closeModal()
                loader.hide();

                await swal.fire(
                    self.$t('message.tools.alert_success'),
                    self.$t('message.tools.alert_tool_added'),
                    'success'
                )
            } else {
                loader.hide();

                await swal.fire(
                    self.$t('message.tools.alert_error'),
                    self.messageFormsTools.message,
                    'error'
                )
            }
        },

        async submitUpdateTool() {
            let self = this;
            let loader = self.$loading.show();

            self.isPopupLoading = true;
            await self.$store.dispatch('actionUpdateTool', self.updateToolModel);
            self.isPopupLoading = false;

            if (this.messageFormsTools.action === 'updated') {
                self.clearModel('update');

                await self.getToolList(this.filterModel.page);

                self.closeModal()
                loader.hide();

                await swal.fire(
                    self.$t('message.tools.alert_success'),
                    self.$t('message.tools.alert_tool_updated'),
                    'success'
                )
            } else {
                loader.hide();

                await swal.fire(
                    self.$t('message.tools.alert_error'),
                    self.messageFormsTools.message,
                    'error'
                )
            }
        },

        deleteTool(id) {
            let self = this;
            swal.fire({
                title : self.$t('message.tools.alert_delete_tool'),
                text : self.$t('message.tools.alert_delete_confirm'),
                icon : "warning",
                showCancelButton : true,
                confirmButtonText : self.$t('message.tools.yes_delete'),
                cancelButtonText : self.$t('message.tools.no_delete')
            })
                .then((result) => {
                    if (result.isConfirmed) {
                        axios.delete('/api/tools/' + id)
                        .then(response => {

                            this.getToolList();

                            swal.fire(
                                self.$t('message.tools.alert_deleted'),
                                self.$t('message.tools.alert_deleted_tool'),
                                'success'
                            )
                        })
                        .catch(err => {
                            swal.fire(
                                self.$t('message.draft.error'),
                                err.response.data.message,
                                'error'
                            )
                        })
                    }
                });
        },

        // misc
        filterToolList() {
            this.$router.push({
                query : this.filterModel,
            });

            this.getToolList(1);
        },

        clearFilter() {
            this.clearModel('filter');
            this.$router.replace({'query' : null});
            this.getToolList();
        },

        updateTool(data) {
            this.updateToolModel.id = data.id;
            this.updateToolModel.url = data.url;
            this.updateToolModel.name = data.name;
            this.updateToolModel.details = data.details;
            this.updateToolModel.username = data.username;
            this.updateToolModel.password = data.password;
            this.updateToolModel.expired_at = data.expired_at;
            this.updateToolModel.registered_at = data.registered_at;
            this.updateToolModel.expiring_days = data.expiring_days;
        },

        modalOpener(mode) {
            let self = this;

            self.clearMessageForm();
            self.modalMode = mode;

            let element = this.$refs.modalTool
            $(element).modal('show')
        },

        closeModal() {
            let element = this.$refs.modalTool
            $(element).modal('hide')
        },

        toolRenewed () {
            let self = this;

            self.closeModal()
            self.getToolList(this.filterModel.page);
        },

        clearModel(mod) {
            if (mod === 'add') {
                this.toolModel = {
                    url : '',
                    name : '',
                    details : '',
                    username : '',
                    password : '',
                    expired_at: '',
                    registered_at: '',
                    is_purchased: false,
                }
            } else if (mod === 'update') {
                this.updateToolModel = {
                    id : '',
                    url : '',
                    name : '',
                    details : '',
                    username : '',
                    password : '',
                    expired_at: '',
                    registered_at: '',
                    is_purchased: false,
                    expiring_days: '',
                }
            } else if (mod === 'purchase') {
                this.purchaseDetails = {
                    notes: '',
                    amount: '',
                    type_id: '',
                    payment_type_id: '',
                    receipt: '',
                    invoice: '',
                }
            } else {
                this.filterModel = {
                    url : '',
                    page : 1,
                    name : '',
                    details : '',
                    username : '',
                    paginate : 10
                }
            }
        },

        togglePassword(index) {
            let eyes = this.$refs['eyes' + index][0];
            let pass = this.$refs['pass' + index][0];
            let badge = this.$refs['badge' + index][0];

            if (pass.style.display === 'none') {
                eyes.classList.value = 'fa fa-eye-slash'
                badge.style.display = 'none';
                pass.style.display = 'inline';
            } else {
                eyes.classList.value = 'fa fa-eye'
                badge.style.display = 'inline';
                pass.style.display = 'none';
            }
        },

        expireBadgeColor (days) {
            if (days > 100) {
                return 'badge badge-success'
            } else if (days > 50 && days < 100) {
                return 'badge badge-primary'
            } else if (days > 20 && days < 50) {
                return 'badge badge-warning'
            } else if (days < 20) {
                return 'badge badge-danger'
            } else {
                return 'badge badge-danger'
            }
        },

        clearMessageForm() {
            this.$store.dispatch('clearMessageFormTools');
        },
    },
}
</script>
