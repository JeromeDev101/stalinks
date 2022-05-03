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
                        <h3 class="card-title text-primary">{{ $t('message.url_prospect.filter_title') }}</h3>
                        <div class="card-tools" style="float: left!important;">
                            <button class="btn btn-primary ml-5"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#collapseExample"
                                    aria-expanded="false"
                                    aria-controls="collapseExample">
                                <i class="fa fa-plus"></i> {{ $t('message.url_prospect.filter_show') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body collapse" id="collapseExample">
                        <div class="row">
                            <div v-if="tableShow.domain" class="col-md-3">
                                <div class="from-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.filter_email') }}
                                    </label>
                                    <input type="text"
                                           v-model="filterModel.email"
                                           class="form-control pull-right"
                                           :placeholder="$t('message.url_prospect.filter_search_email')">
                                </div>
                            </div>

                            <div v-if="tableShow.country" class="col-md-3">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.filter_email_req') }}</label>
                                    <select v-model="filterModel.required_email_temp" class="form-control pull-right">
                                        <option value="0">{{ $t('message.url_prospect.no') }}</option>
                                        <option value="1">{{ $t('message.url_prospect.yes') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div v-if="tableShow.country" class="col-md-3">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.filter_country') }}</label>
                                    <!-- <select v-model="filterModel.country_id_temp" class="form-control pull-right">
                                       <option v-for="(option, index) in filterModel.countryList.data" v-bind:value="option.id">
                                           {{ option.name }}
                                       </option>
                                       </select> -->
                                    <v-select
                                        v-model="filterModel.country_id_temp"
                                        multiple
                                        label="name"
                                        :placeholder="$t('message.url_prospect.all')"
                                        :options="listCountryAll.data"
                                        :reduce="name => name.name"
                                        :searchable="true"/>
                                </div>
                            </div>

                            <div v-if="tableShow.domain" class="col-md-3">
                                <div class="from-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.filter_url') }}</label>
                                    <input type="text"
                                           v-model="filterModel.domain_temp"
                                           class="form-control pull-right"
                                           :placeholder="$t('message.url_prospect.filter_url')">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.url_prospect.filter_dz') }}</label>
                                    <v-select
                                        v-model="filterModel.domain_zone"
                                        multiple
                                        label="name"
                                        :placeholder="$t('message.url_prospect.all')"
                                        :options="listDomainZones.data"
                                        :searchable="true"
                                        :reduce="domain => domain.name"/>
                                </div>
                            </div>

                            <div v-if="tableShow.status" class="col-md-3">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.filter_status') }}</label>
                                    <v-select
                                        multiple
                                        v-model="filterModel.status_temp"
                                        :options="objectToArrayWithCustomSort(listStatusText)"
                                        :reduce="status => status.id"
                                        label="text"
                                        :searchable="false"
                                        :placeholder="$t('message.url_prospect.all')"/>
                                    <!--                        <select v-model="filterModel.status_temp" class="form-control">-->
                                    <!--                            <option value="-1" selected>All-->
                                    <!--                            </option>-->
                                    <!--                           <option v-for="(option, key) in listStatusText" v-bind:value="key">-->
                                    <!--                              {{ option.text }}-->
                                    <!--                           </option>-->
                                    <!--                        </select>-->
                                </div>
                            </div>

                            <div class="col-md-3" v-if="user.isAdmin || tableShow.employee">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.filter_emp') }}</label>
                                    <!-- <select @change="doSearchList" class="form-control pull-right" v-model="filterModel.employee_id" style="height: 37px;">
                                       <option value="0">Select Employee</option>
                                       <option v-for="user in listUser.data" :value="user.id">{{ user.username }}</option>
                                       </select> -->
                                    <v-select
                                        multiple
                                        v-model="filterModel.employee_id"
                                        :options="listSellerTeam.data"
                                        :reduce="username => username.username"
                                        label="username"
                                        :searchable="false"
                                        :placeholder="$t('message.url_prospect.all')"/>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label
                                        style="color: #333">{{ $t('message.url_prospect.filter_date_up') }}
                                    </label>
                                    <div class="input-group">
                                        <date-range-picker
                                            ref="picker"
                                            opens="left"
                                            v-model="filterModel.alexa_date_upload"
                                            :ranges="generateDefaultDateRange()"
                                            :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                            :dateRange="filterModel.alexa_date_upload"
                                            :linkedCalendars="true"
                                            style="width: 100%"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label style="color: #333">
                                        {{ $t('message.url_prospect.filter_alexa') }}
                                        <i class="fa fa-question-circle" title="From and To must not be empty"></i>
                                    </label>
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control"
                                               :placeholder="$t('message.url_prospect.fr')"
                                               v-model="filterModel.alexa_rank_from">
                                        <input type="text"
                                               class="form-control"
                                               :placeholder="$t('message.url_prospect.to')"
                                               v-model="filterModel.alexa_rank_to">
                                    </div>
                                </div>
                            </div>

                            <div v-if="tableShow.from" class="col-md-3">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.prospect_from') }}</label>

                                    <v-select
                                        v-model="filterModel.from"
                                        multiple
                                        label="name"
                                        :placeholder="$t('message.url_prospect.all')"
                                        :options="['Alexa', 'Backlinks', 'Manual']"
                                        :searchable="true"/>
                                </div>
                            </div>

                            <div v-if="tableShow.status" class="col-md-3">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.filter_action') }}</label>
                                    <br>
                                    <div class="btn-group">
                                        <button class="btn btn-default" @click="clearSearch">
                                            {{ $t('message.url_prospect.filter_clear') }}
                                        </button>
                                        <button @click="doSearchList"
                                                type="submit"
                                                :title="$t('message.url_prospect.filter_title')"
                                                class="btn btn-default"><i class="fa fa-fw fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="Object.keys(extListTotals).length !== 0" class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box rounded bg-aqua">
                    <div class="inner">
                        <h3>{{ listExt.total }}</h3>
                        <p>Total URL's</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-link"></i>
                    </div>
                </div>
            </div>

            <div v-for="total in extListStatusTotals" class="col-lg-3 col-6">
                <div class="small-box rounded" :class="total.badge">
                    <div class="inner">
                        <h3>{{ total.total }}</h3>
                        <p>
                            {{ total.text }}

                            <span>
                                ({{ total.percentage }}%)
                            </span>
                        </p>
                    </div>
                    <div class="icon">
                        <i :class="total.icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">{{ $t('message.url_prospect.url_title') }}</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-12 my-3">
                                <div class="input-group">
                                    <input type="file"
                                           class="form-control"
                                           v-on:change="checkDataExcel"
                                           enctype="multipart/form-data"
                                           ref="excel"
                                           name="file">
                                    <div class="input-group-btn">
                                        <button
                                            :title="$t('message.url_prospect.url_upload')"
                                            class="btn btn-primary btn-flat"
                                            :disabled="isEnableBtn"

                                            @click="submitUpload">
                                            <i class="fa fa-upload"></i>
                                        </button>

                                        <button
                                            :title="$t('message.url_prospect.url_download')"
                                            class="btn btn-primary btn-flat"

                                            @click="downloadTemplate">
                                            <i class="fa fa-download"></i>
                                        </button>
                                    </div>
                                </div>
                                <span v-if="messageForms.errors.file"
                                      v-for="err in messageForms.errors.file"
                                      class="text-danger">{{ err }}</span>
                                <span v-if="messageForms.action == 'uploaded'"
                                      class="text-success">{{ messageForms.message }}</span>

                                <!-- <div class="row my-3" v-show="showLang">
                                   <div class="col-12">
                                       <select class="form-control" name="language" ref="language" v-on:change="checkData">
                                           <option value="">Select language</option>
                                           <option v-for="(option, index) in filterModel.countryList.data" v-bind:value="option.id">
                                               {{ option.name }}
                                           </option>
                                       </select>
                                   </div>
                                   </div>

                                   <div class="row" v-show="showLang">
                                   <div class="col-sm-12">
                                       <select class="form-control" name="status" ref="status" v-on:change="checkData">
                                           <option value="">Select Status</option>
                                           <option value="50">Contacted</option>
                                           <option value="60">Refused</option>
                                           <option value="70">InTouched</option>
                                           <option value="90">Unqualified</option>
                                           <option value="100">Qualified</option>
                                       </select>
                                   </div>
                                   </div> -->
                            </div>

                            <div class="col-md-6 col-sm-12 my-3">
                                <button @click="doAddExt"
                                        data-toggle="modal"
                                        data-target="#modal-add"
                                        class="btn btn-success float-right"><i class="fa fa-plus"></i></button>
                                <button data-toggle="modal"
                                        data-target="#modal-setting"
                                        class="btn btn-default float-right"><i class="fa fa-cog"></i></button>
                                <div class="input-group input-group-sm float-right" style="width: 65px">
                                    <select @change="doSearchList"
                                            class="form-control pull-right"
                                            v-model="filterModel.per_page"
                                            style="height: 37px;">
                                        <option v-for="value in listPageOptions" :value="value">{{ value }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row my-0">
                            <div class="col-sm-12">
                                <small class="text-secondary">
                                    {{ $t('message.url_prospect.url_reminder') }}
                                </small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-sm">
                                    <div class="btn-group">
                                        <button
                                            class="btn btn-default"
                                            @click="selectAll">

                                            {{
                                                !allSelected
                                                    ? $t('message.url_prospect.ac_select_all')
                                                    : $t('message.url_prospect.ac_deselect_all')
                                            }}
                                        </button>

                                        <button
                                            data-toggle="modal"
                                            type="submit"
                                            :title="$t('message.url_prospect.ac_send_email')"
                                            class="btn btn-default"

                                            @click="doSendEmail(null, $event)">

                                            <i class="fas fa-envelope"></i>
                                        </button>

                                        <button
                                            type="submit"
                                            :title="$t('message.url_prospect.ac_get_ahrefs')"
                                            class="btn btn-default"

                                            @click="getAhrefs()">

                                            <i class="fas fa-chart-area"></i>
                                        </button>

                                        <button
                                            type="submit"
                                            :title="$t('message.url_prospect.tb_status')"
                                            class="btn btn-default"

                                            @click="doMultipleStatus">

                                            <i class="fa fa-fw fa-tag"></i>
                                        </button>

                                        <button
                                            type="submit"
                                            :title="$t('message.url_prospect.ac_emp')"
                                            class="btn btn-default"

                                            @click="doMultipleEmployee">

                                            <i class="fa fa-fw fa-user"></i>
                                        </button>

                                        <button
                                            type="submit"
                                            :title="$t('message.url_prospect.ac_delete')"
                                            class="btn btn-default"

                                            @click="deleteAll">

                                            <i class="fa fa-fw fa-trash"></i>
                                        </button>

                                        <button
                                            @click="doCrawlExtList"
                                            :disabled="isCrawling"
                                            type="submit"
                                            :title="$t('message.url_prospect.ac_crawl')"
                                            class="btn btn-default">
                                            <i class="fa fa-fw fa-globe"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex flex-column align-items-end">
                                    <Sort
                                        ref="sortComponent"
                                        :sorted="isSorted"
                                        :items="sortOptions"
                                        :custom-class="['w-25']"

                                        @submitSort="sortSubmit"
                                        @updateOptions="updateSortOptions">
                                    </Sort>
                                </div>
                            </div>
                        </div>

                    <div :class="{ 'box-body': true, 'no-padding': true, 'table-responsive': true }" class="mt-3">

                       <span v-if="listExt.total > 10" class="pagination-custom-footer-text ml-3">
                           <b v-if="!isResultCrawled">
                               Showing {{ listExt.from }} to {{ listExt.to }} of {{ listExt.total }} entries.
                           </b>
                           <b v-else>
                               Showing {{ listExt.data.length }} crawled items.
                               Click search or clear filter to refresh the list.
                           </b>
                       </span>

                            <vue-virtual-table
                                v-if="!tableLoading"
                                width="100%"
                                :height="600"
                                :bordered="true"
                                :item-height="60"
                                :config="tableConfig"
                                :data="listExt.data">
                                <template
                                    slot-scope="scope"
                                    slot="actionButtons">
                                    <div class="btn-group"
                                         v-if="checkSellerAccess(scope.row.users == null ? null:scope.row.users.id, scope.row.users != null) || (scope.row.users == null ? false:scope.row.users.status == 'inactive' ? true:false)">
                                        <button
                                            data-action="a1"
                                            :data-index="scope.index"
                                            data-toggle="modal"
                                            data-target="#modal-update"
                                            class="btn btn-default"
                                            :title="$t('message.url_prospect.ac_edit')"

                                            @click="doEditExt(scope.row)">
                                            <i class="fa fa-fw fa-edit"></i>
                                        </button>
                                        <!-- <button data-action="a2"
                                                :data-index="scope.index"
                                                v-if="hasBacklink(scope.row.status)" @click="doShowBackLink(scope.row)" data-toggle="modal" data-target="#modal-backlink" type="submit" title="Back Link" class="btn btn-default"><i class="fa fa-fw fa-link"></i></button> -->
                                        <button
                                            data-action="a4"
                                            :data-index="scope.index"
                                            data-toggle="modal"
                                            type="submit"
                                            :title="$t('message.url_prospect.ac_send_email')"
                                            class="btn btn-default"

                                            @click="doSendEmail(scope.row,$event)" >
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                        <!-- <button v-if="ext.status == '30'" type="submit" title="Get Ahrefs" @click="getAhrefsById(ext.id, ext.status)" class="btn btn-default"><i class="fa fa-fw fa-area-chart"></i></button> -->
                                        <button
                                            type="submit"
                                            :title="$t('message.url_prospect.ac_get_ahrefs')"
                                            class="btn btn-default"

                                            @click="getAhrefsById(scope.row.id, scope.row.status)">
                                            <i class="fas fa-chart-area"></i>
                                        </button>
                                    </div>
                                </template>

                                <template
                                    slot-scope="scope"
                                    slot="actionCheckbox">
                                    <div class="btn-group">
                                        <button class="btn btn-default">
                                            <input type="checkbox"
                                                   class="custom-checkbox"
                                                   v-on:change="checkSelected"
                                                   :id="scope.row.id"
                                                   :value="scope.row"
                                                   v-model="checkIds">
                                        </button>
                                    </div>
                                </template>

                                <template
                                    slot-scope="scope"
                                    slot="employeeData">
                                    {{
                                        scope.row.users == null ?
                                            'N/A' : scope.row.users.username
                                    }}
                                </template>

                                <template
                                    slot-scope="scope"
                                    slot="domainData">
                                    <a :href="'//' + scope.row.domain" target="_blank">
                                        {{ scope.row.domain }}
                                    </a>
                                </template>

                                <template slot-scope="scope" slot="emailsData">
                                    <div v-if="isCrawling" style="width: 100%; text-align: center;">
                                        <img src="/images/row-loading.gif" alt="crawling">
                                    </div>

                                    <div v-else>
                                        <div v-if="scope.row.email">
                                            <ol class="pl-15">
                                                <li v-for="item in scope.row.email.split('|')">
                                                    {{ item }}
                                                </li>
                                            </ol>
                                        </div>

                                        <div v-else>
                                            <span>N/A</span>
                                        </div>
                                    </div>
                                </template>

                                <template slot-scope="scope" slot="facebookData">
                                    <div v-if="isCrawling" style="width: 100%; text-align: center;">
                                        <img src="/images/row-loading.gif" alt="crawling">
                                    </div>

                                    <div v-else>
                                        <div v-if="scope.row.facebook">
                                            <ol class="pl-15">
                                                <li v-for="item in scope.row.facebook.split('|')">
                                                    {{ item }}
                                                </li>
                                            </ol>
                                        </div>

                                        <div v-else>
                                            <span>N/A</span>
                                        </div>
                                    </div>
                                </template>

                                <template slot-scope="scope" slot="phoneData">
                                    <div v-if="isCrawling" style="width: 100%; text-align: center;">
                                        <img src="/images/row-loading.gif" alt="crawling">
                                    </div>

                                    <div v-else>
                                        <div v-if="scope.row.phone">
                                            <ol class="pl-15">
                                                <li v-for="item in scope.row.phone.split('|')">
                                                    {{ item }}
                                                </li>
                                            </ol>
                                        </div>

                                        <div v-else>
                                            <span>N/A</span>
                                        </div>
                                    </div>
                                </template>

                                <template slot-scope="scope" slot="statusData">
                                    <span :class="['badge', (listStatusText[scope.row.status]
                                     ? listStatusText[scope.row.status].label
                                     : 'bg-warning')]">
                                        {{
                                            listStatusText[scope.row.status]
                                                ? listStatusText[scope.row.status].text
                                                : 'undefined'
                                        }}
                                    </span>
                                </template>

<!--                                <template-->
<!--                                    slot-scope="scope"-->
<!--                                    slot="totalSpentData">-->
<!--                                    {{-->
<!--                                        convertPrice(scope.row.total_spent)-->
<!--                                    }}-->
<!--                                </template>-->

                                <template
                                    slot-scope="scope"
                                    slot="organicKwData">
                                    {{
                                        formatPrice(scope.row.organic_keywords)
                                    }}
                                </template>

                                <template
                                    slot-scope="scope"
                                    slot="organicTrafficData">
                                    {{
                                        formatPrice(scope.row.organic_traffic)
                                    }}
                                </template>

                            </vue-virtual-table>
                        </div>
                    </div>

                    <div class="card-body">
                        <component :is="pagination" :callMethod="goToPage"></component>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Add -->
        <div class="modal fade" id="modal-add" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('message.url_prospect.add_title') }}</h4>
                    </div>
                    <div class="modal-body relative">
                        <form class="row" action="">
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.domain}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.add_domain') }}</label>
                                    <input type="text"
                                           v-model="extModel.domain"
                                           class="form-control"
                                           value=""
                                           required
                                           :placeholder="$t('message.url_prospect.add_enter_domain')">
                                    <span v-if="messageForms.errors.domain"
                                          v-for="err in messageForms.errors.domain"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.add_status') }}</label>
                                    <select class="form-control" v-model="extModel.status">
                                        <option value="50">{{ $t('message.url_prospect.add_contacted') }}</option>
                                        <option value="120">{{ $t('message.url_prospect.add_contacted_form') }}</option>
                                        <option value="60">{{ $t('message.url_prospect.add_refused') }}</option>
                                        <option value="70">{{ $t('message.url_prospect.add_intouch') }}</option>
                                        <option value="90">{{ $t('message.url_prospect.add_unqualified') }}</option>
                                        <option value="100">{{ $t('message.url_prospect.add_qualified') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.country_id}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.add_country') }}</label>
                                    <div>
                                        <select v-model="extModel.country_id" class="form-control pull-right">
                                            <option value="0">-- {{ $t('message.url_prospect.add_select_country') }} --</option>
                                            <option v-for="option in listCountryAll.data" v-bind:value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <span
                                        v-if="messageForms.errors.country_id"
                                        v-for="err in messageForms.errors.country_id"
                                        class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div
                                    :class="{'has-error': messageForms.errors.email || checkEmailValidationError(messageForms.errors).length !== 0}"
                                    class="form-group">

                                    <label style="color: #333">{{ $t('message.url_prospect.add_email') }}</label>
                                    <!--                           <textarea-->
                                    <!--                               v-model="extModel.email"-->
                                    <!--                               type="text"-->
                                    <!--                               class="form-control"-->
                                    <!--                               placeholder="Enter Email">-->

                                    <!--                           </textarea>-->

                                    <vue-tags-input
                                        v-model="email_add"
                                        :max-tags="10"
                                        :tags="extModel.email"
                                        :allow-edit-tags="true"
                                        :separators="separators"
                                        :class="{
                                            'vue-tag-error': messageForms.errors.email
                                            || checkEmailValidationError(messageForms.errors).length !== 0
                                        }"
                                        class="mt-0"
                                        ref="emailTag"
                                        :placeholder="$t('message.url_prospect.add_enter_email')"

                                        @tags-changed="newTags => extModel.email = newTags"
                                    />

                                    <p
                                        v-if="extModel.email.length"
                                        class="text-primary small mb-0"
                                        style="cursor: pointer"

                                        @click="extModel.email = []">
                                        {{ $t('message.url_prospect.add_remove_emails') }}
                                    </p>

                                    <span v-if="messageForms.errors.email"
                                          v-for="err in messageForms.errors.email"
                                          class="text-danger">{{ err }}</span>

                                    <div
                                        v-if="checkEmailValidationError(messageForms.errors).length !== 0"
                                        class="text-danger">
                                        <ul
                                            v-for="emailErrors in checkEmailValidationError(messageForms.errors)"
                                            class="m-0 p-0">
                                            <li>
                                                {{ emailErrors }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.facebook}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.add_facebook') }}</label>
                                    <textarea type="text"
                                              v-model="extModel.facebook"
                                              class="form-control"
                                              value=""
                                              :placeholder="$t('message.url_prospect.add_enter_facebook')">

                                    </textarea>
                                    <span v-if="messageForms.errors.facebook"
                                          v-for="err in messageForms.errors.facebook"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.phone}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.add_phone') }}</label>
                                    <textarea type="text"
                                              v-model="extModel.phone"
                                              class="form-control"
                                              value=""
                                              :placeholder="$t('message.url_prospect.add_enter_phone')">

                                    </textarea>
                                    <span v-if="messageForms.errors.phone"
                                          v-for="err in messageForms.errors.phone"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                            <!-- <div class="col-md-6">
                               <div :class="{'form-group': true, 'has-error': messageForms.errors.alexa_rank}" class="form-group">
                                   <label style="color: #333">Alexa Rank</label>
                                   <input type="text" v-model="extModel.alexa_rank" class="form-control" value=""  placeholder="Enter Alexa Rank">
                                   <span v-if="messageForms.errors.alexa_rank" v-for="err in messageForms.errors.alexa_rank" class="text-danger">{{ err }}</span>
                               </div>
                               </div>

                               <div class="col-md-6">
                               <div :class="{'form-group': true, 'has-error': messageForms.errors.ahrefs_rank}" class="form-group">
                                   <label style="color: #333">Ahrefs Traffic</label>
                                   <input type="number" v-model="extModel.ahrefs_rank" class="form-control" value=""  placeholder="Enter Ahrefs Rank">
                                   <span v-if="messageForms.errors.ahrefs_rank" v-for="err in messageForms.errors.ahrefs_rank" class="text-danger">{{ err }}</span>
                               </div>
                               </div>

                               <div class="col-md-6">
                               <div :class="{'form-group': true, 'has-error': messageForms.errors.ref_domains}" class="form-group">
                                   <label style="color: #333">Ref Domains</label>
                                   <input type="number" v-model="extModel.ref_domains" class="form-control" value=""  placeholder="Enter Ref Domains">
                                   <span v-if="messageForms.errors.ref_domains" v-for="err in messageForms.errors.ref_domains" class="text-danger">{{ err }}</span>
                               </div>
                               </div>

                               <div class="col-md-6">
                               <div :class="{'form-group': true, 'has-error': messageForms.errors.no_backlinks}" class="form-group">
                                   <label style="color: #333">No Backlinks</label>
                                   <input type="number" v-model="extModel.no_backlinks" class="form-control" value=""  placeholder="Enter No Backlinks">
                                   <span v-if="messageForms.errors.no_backlinks" v-for="err in messageForms.errors.no_backlinks" class="text-danger">{{ err }}</span>
                               </div>
                               </div>

                               <div class="col-md-6">
                               <div :class="{'form-group': true, 'has-error': messageForms.errors.url_rating}" class="form-group">
                                   <label style="color: #333">URL Rating</label>
                                   <input type="number" v-model="extModel.url_rating" class="form-control" value=""  placeholder="Enter URL Rating">
                                   <span v-if="messageForms.errors.url_rating" v-for="err in messageForms.errors.url_rating" class="text-danger">{{ err }}</span>
                               </div>
                               </div>

                               <div class="col-md-6">
                               <div :class="{'form-group': true, 'has-error': messageForms.errors.domain_rating}" class="form-group">
                                   <label style="color: #333">Domain Rating</label>
                                   <input type="number" v-model="extModel.domain_rating" class="form-control" value=""  placeholder="Enter Domain Rating">
                                   <span v-if="messageForms.errors.domain_rating" v-for="err in messageForms.errors.domain_rating" class="text-danger">{{ err }}</span>
                               </div>
                               </div>

                               <div class="col-md-6">
                               <div :class="{'form-group': true, 'has-error': messageForms.errors.organic_keywords}" class="form-group">
                                   <label style="color: #333">Organic Keywords</label>
                                   <input type="text" v-model="extModel.organic_keywords" class="form-control" value=""  placeholder="Enter Organic Keywords">
                                   <span v-if="messageForms.errors.organic_keywords" v-for="err in messageForms.errors.organic_keywords" class="text-danger">{{ err }}</span>
                               </div>
                               </div>

                               <div class="col-md-6">
                               <div :class="{'form-group': true, 'has-error': messageForms.errors.organic_traffic}" class="form-group">
                                   <label style="color: #333">Organic Traffic</label>
                                   <input type="text" v-model="extModel.organic_traffic" class="form-control" value=""  placeholder="Enter Organic Traffic">
                                   <span v-if="messageForms.errors.organic_traffic" v-for="err in messageForms.errors.organic_traffic" class="text-danger">{{ err }}</span>
                               </div>
                               </div> -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.add_skype') }}</label>
                                    <textarea type="text"
                                              v-model="extModel.skype"
                                              class="form-control"
                                              value=""
                                              :placeholder="$t('message.url_prospect.add_enter_skype')">

                                    </textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.add_info') }}</label>
                                    <textarea type="text"
                                              v-model="extModel.info"
                                              class="form-control"
                                              value=""
                                              :placeholder="$t('message.url_prospect.add_enter_info')">

                                    </textarea>
                                </div>
                            </div>
                            <!-- <div class="col-md-6">
                               <div class="form-group">
                                   <label style="color: #333">Price</label>
                                   <input type="number" v-model="extModel.price" class="form-control" value="" placeholder="0.00">
                               </div>
                               </div>

                               <div class="col-md-6">
                               <div class="form-group">
                                   <label style="color: #333">Include Article</label>
                                   <select class="form-control" v-model="extModel.inc_article">
                                       <option value="Yes">Yes</option>
                                       <option value="No">No</option>
                                   </select>
                               </div>
                               </div> -->
                        </form>
                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            {{ $t('message.url_prospect.close') }}
                        </button>
                        <button type="button" @click="submitAdd" class="btn btn-primary">
                            {{ $t('message.url_prospect.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Add-->

        <!-- Modal Backlink -->
        <div class="modal fade" id="modal-backlink" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{ listBackLink.total }} {{ $t('message.url_prospect.b_title') }}
                            <a target="_blank" :href="'http://' + extBackLink.domain">
                                {{ extBackLink.domain }}
                            </a>
                        </h4>
                    </div>
                    <div class="modal-body relative">
                        <table class="table table-hover table-bordered table-striped rlink-table">
                            <tbody>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>{{ $t('message.url_prospect.b_ext_dom') }}</th>
                                <th>{{ $t('message.url_prospect.b_int_dom') }}</th>
                                <th>{{ $t('message.url_prospect.b_link') }}</th>
                                <th>{{ $t('message.url_prospect.b_price') }}</th>
                                <th>{{ $t('message.url_prospect.b_anchor_text') }}</th>
                                <th>{{ $t('message.url_prospect.b_live_date') }}</th>
                                <th>{{ $t('message.url_prospect.b_status') }}</th>
                                <th>{{ $t('message.url_prospect.b_user') }}</th>
                            </tr>
                            <tr v-for="(backLink, index) in listBackLink.data" :key="index">
                                <td class="center-content">{{ index + 1 }}</td>
                                <td>{{ backLink.ext_domain.domain }}</td>
                                <td>{{ backLink.int_domain.domain }}</td>
                                <td><a href="backLink.link">{{ backLink.link }}</a></td>
                                <td>{{ backLink.price }}</td>
                                <td>{{ backLink.anchor_text }}</td>
                                <td>{{ backLink.live_date }}</td>
                                <td>{{ backLink.status }}</td>
                                <td>{{ backLink.user.name }}</td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            {{ $t('message.url_prospect.close') }}
                        </button>
                        <download-csv
                            :data="listBackLink.data"
                            :fileds="csvExport.data_filled"
                            :nameFile="extBackLink.domain + '_' + csvExport.file_csv">
                        </download-csv>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Backlink -->

        <!-- Modal Update -->
        <div class="modal fade" id="modal-update" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{ $t('message.url_prospect.up_title') }}
                            <a :href="'http://' + extUpdate.domain">{{ extUpdate.domain }}</a>
                        </h4>
                    </div>
                    <div class="modal-body relative">
                        <form class="row" action="">
                            <div class="col-md-12">
                                <ul>
                                    <li>{{ $t('message.url_prospect.up_info') }}</li>
                                    <li>{{ $t('message.url_prospect.up_info_2') }}</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <div
                                    :class="{'form-group': true, 'has-error': messageForms.errors.user_id}"
                                    class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.up_in_charge') }}</label>
                                    <select type="text"
                                            v-model="extUpdate.user_id"
                                            class="form-control"
                                            value=""
                                            required="required">

                                        <option
                                            v-for="(option, key) in listSellerTeam.data"
                                            v-bind:value="option.id"
                                            v-if="option.username != 'N/A' && option.status == 'active'"
                                            :selected="(option.id === extUpdate.user_id ? 'selected' : '')">
                                            {{ option.username }}
                                        </option>
                                    </select>

                                    <span
                                        v-if="messageForms.errors.user_id"
                                        v-for="err in messageForms.errors.user_id"
                                        class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.domain}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.add_domain') }}</label>
                                    <input type="text"
                                           v-model="extUpdate.domain"
                                           class="form-control"
                                           value=""
                                           required="required"
                                           :placeholder="$t('message.url_prospect.add_enter_domain')">

                                    <span v-if="messageForms.errors.domain"
                                          v-for="err in messageForms.errors.domain"
                                          class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.status}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.add_status') }}</label>
                                    <select type="text"
                                            v-model="extUpdate.status"
                                            @change="showAddURL()"
                                            class="form-control"
                                            value=""
                                            required="required">

                                        <option v-for="(option, key) in objectToArrayWithCustomSort(listStatusText)"
                                                v-bind:value="option.id"
                                                :selected="(key === extUpdate.status ? 'selected' : '')">
                                            {{ option.text }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors.status"
                                          v-for="err in messageForms.errors.status"
                                          class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.country_id}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.add_country') }}</label>
                                    <div>
                                        <select v-model="extUpdate.country_id" class="form-control pull-right">
                                            <option v-for="option in listCountryAll.data" v-bind:value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <span v-if="messageForms.errors.country_id"
                                          v-for="err in messageForms.errors.country_id"
                                          class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.facebook}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.add_facebook') }}</label>
                                    <textarea
                                        type="text"
                                        v-model="extUpdate.facebook"
                                        class="form-control"
                                        value=""
                                        :placeholder="$t('message.url_prospect.add_enter_facebook')">

                                    </textarea>

                                    <span v-if="messageForms.errors.facebook"
                                          v-for="err in messageForms.errors.facebook"
                                          class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.from}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.simple_from') }}</label>

                                    <select v-model="extUpdate.from" class="form-control pull-right">
                                        <option v-for="option in ['Alexa', 'Backlinks', 'Manual']" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select>

                                    <span v-if="messageForms.errors.from"
                                          v-for="err in messageForms.errors.from"
                                          class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div
                                    :class="{ 'has-error': messageForms.errors.email || checkEmailValidationError(messageForms.errors).length !== 0 }"
                                    class="form-group">

                                    <label style="color: #333">{{ $t('message.url_prospect.add_email') }}</label>
                                    <!--                           <textarea type="text" v-model="extUpdate.email" class="form-control" value=""  placeholder="Enter Email"></textarea>-->

                                    <vue-tags-input
                                        v-model="email_add"
                                        :max-tags="10"
                                        :tags="extUpdate.email"
                                        :allow-edit-tags="true"
                                        :separators="separators"
                                        :class="{
                                            'vue-tag-error': messageForms.errors.email
                                            || checkEmailValidationError(messageForms.errors).length !== 0
                                        }"
                                        class="mt-0"
                                        ref="emailTagUpdate"
                                        :placeholder="$t('message.url_prospect.add_enter_email')"

                                        @tags-changed="newTags => extUpdate.email = newTags"
                                    />

                                    <p
                                        v-if="extUpdate.email.length"
                                        class="text-primary small mb-0"
                                        style="cursor: pointer"

                                        @click="extUpdate.email = []">
                                        {{ $t('message.url_prospect.add_remove_emails') }}
                                    </p>

                                    <span v-if="messageForms.errors.email"
                                          v-for="err in messageForms.errors.email"
                                          class="text-danger">
                                        {{ err }}
                                    </span>

                                    <div
                                        v-if="checkEmailValidationError(messageForms.errors).length !== 0"
                                        class="text-danger">
                                        <ul
                                            v-for="emailErrors in checkEmailValidationError(messageForms.errors)"
                                            class="m-0 p-0">
                                            <li>
                                                {{ emailErrors }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.phone}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.add_phone') }}</label>
                                    <textarea
                                        type="text"
                                        v-model="extUpdate.phone"
                                        class="form-control"
                                        value=""
                                        placeholder="Enter Phone">

                                    </textarea>

                                    <span v-if="messageForms.errors.phone"
                                          v-for="err in messageForms.errors.phone"
                                          class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                            <!-- <div class="col-md-6">
                               <div :class="{'form-group': true, 'has-error': messageForms.errors.ahrefs_rank}" class="form-group">
                                   <label style="color: #333">Ahrefs Rank</label>
                                   <input type="number" v-model="extUpdate.ahrefs_rank" class="form-control" value=""  placeholder="Enter Ahrefs Rank">
                                   <span v-if="messageForms.errors.ahrefs_rank" v-for="err in messageForms.errors.ahrefs_rank" class="text-danger">{{ err }}</span>
                               </div>
                               </div>

                               <div class="col-md-6">
                               <div :class="{'form-group': true, 'has-error': messageForms.errors.ref_domains}" class="form-group">
                                   <label style="color: #333">Ref Domains</label>
                                   <input type="number" v-model="extUpdate.ref_domains" class="form-control" value=""  placeholder="Enter Ref Domains">
                                   <span v-if="messageForms.errors.ref_domains" v-for="err in messageForms.errors.ref_domains" class="text-danger">{{ err }}</span>
                               </div>
                               </div>

                               <div class="col-md-6">
                               <div :class="{'form-group': true, 'has-error': messageForms.errors.no_backlinks}" class="form-group">
                                   <label style="color: #333">No Backlinks</label>
                                   <input type="number" v-model="extUpdate.no_backlinks" class="form-control" value=""  placeholder="Enter No Backlinks">
                                   <span v-if="messageForms.errors.no_backlinks" v-for="err in messageForms.errors.no_backlinks" class="text-danger">{{ err }}</span>
                               </div>
                               </div>

                               <div class="col-md-6">
                               <div :class="{'form-group': true, 'has-error': messageForms.errors.url_rating}" class="form-group">
                                   <label style="color: #333">URL Rating</label>
                                   <input type="number" v-model="extUpdate.url_rating" class="form-control" value=""  placeholder="Enter URL Rating">
                                   <span v-if="messageForms.errors.url_rating" v-for="err in messageForms.errors.url_rating" class="text-danger">{{ err }}</span>
                               </div>
                               </div>

                               <div class="col-md-6">
                               <div :class="{'form-group': true, 'has-error': messageForms.errors.domain_rating}" class="form-group">
                                   <label style="color: #333">Domain Rating</label>
                                   <input type="number" v-model="extUpdate.domain_rating" class="form-control" value=""  placeholder="Enter Domain Rating">
                                   <span v-if="messageForms.errors.domain_rating" v-for="err in messageForms.errors.domain_rating" class="text-danger">{{ err }}</span>
                               </div>
                               </div>

                               <div class="col-md-6">
                               <div :class="{'form-group': true, 'has-error': messageForms.errors.organic_keywords}" class="form-group">
                                   <label style="color: #333">Organic Keywords</label>
                                   <input type="text" v-model="extUpdate.organic_keywords" class="form-control" value=""  placeholder="Enter Organic Keywords">
                                   <span v-if="messageForms.errors.organic_keywords" v-for="err in messageForms.errors.organic_keywords" class="text-danger">{{ err }}</span>
                               </div>
                               </div>

                               <div class="col-md-6">
                               <div :class="{'form-group': true, 'has-error': messageForms.errors.organic_traffic}" class="form-group">
                                   <label style="color: #333">Organic Traffic</label>
                                   <input type="text" v-model="extUpdate.organic_traffic" class="form-control" value=""  placeholder="Enter Organic Traffic">
                                   <span v-if="messageForms.errors.organic_traffic" v-for="err in messageForms.errors.organic_traffic" class="text-danger">{{ err }}</span>
                               </div>
                               </div> -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.add_skype') }}</label>
                                    <textarea
                                        type="text"
                                        v-model="extUpdate.skype"
                                        class="form-control"
                                        value=""
                                        :placeholder="$t('message.url_prospect.add_enter_skype')">

                                    </textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.add_info') }}</label>
                                    <textarea
                                        type="text"
                                        v-model="extUpdate.info"
                                        class="form-control"
                                        value=""
                                        :placeholder="$t('message.url_prospect.add_enter_info')">

                                    </textarea>
                                </div>
                            </div>
                            <!-- <div class="col-md-6">
                               <div class="form-group">
                                   <label style="color: #333">Price</label>
                                   <input type="number" v-model="extUpdate.price" class="form-control" value="" placeholder="0.00">
                               </div>
                               </div>

                               <div class="col-md-6">
                               <div class="form-group">
                                   <label style="color: #333">Include Article</label>
                                   <select class="form-control" v-model="extUpdate.inc_article">
                                       <option value="Yes">Yes</option>
                                       <option value="No">No</option>
                                   </select>
                               </div>
                               </div> -->
                        </form>
                        <div class="row mt-3" v-show="formAddUrl">
                            <div class="col-md-12">
                                <h4>{{ $t('message.url_prospect.up_publisher_list') }}</h4>
                                <hr>
                            </div>

                            <div class="col-md-12" v-if="isDomainExistListPublisher">
                                <div class="alert alert-info">
                                    <b>{{ $t('message.url_prospect.up_information') }} </b>
                                    {{ $t('message.url_prospect.up_information_2') }}
                                </div>
                            </div>

                            <div class="col-md-12" v-if="!isDomainExistListPublisher">
                                <div
                                    :class="{'form-group': true, 'has-error': messageForms.errors['pub.seller']}"
                                    class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.up_seller') }}</label>
                                    <!--                           <select name="" class="form-control" v-model="publisherAdd.seller" :disabled="isEditable">-->
                                    <!--                              <option value="">Select Seller</option>-->
                                    <!--                              <option v-for="option in listSeller.data" v-bind:value="option.id">-->
                                    <!--                                 {{ option.username == null ? option.name:option.username }}-->
                                    <!--                              </option>-->
                                    <!--                           </select>-->

                                    <v-select
                                        v-model="publisherAdd.seller"
                                        label="username"
                                        :searchable="true"
                                        :options="getSellerTeamInCharge(listSeller.data)"
                                        :reduce="seller => seller.id"/>

                                    <span v-if="messageForms.errors['pub.seller']"
                                          v-for="err in messageForms.errors['pub.seller']"
                                          class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors['pub.inc_article']}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.up_inc_article') }}</label>
                                    <select
                                        name=""
                                        id=""
                                        class="form-control"
                                        v-model="publisherAdd.inc_article"
                                        :disabled="isEditable">
                                        <option value="">{{ $t('message.url_prospect.up_select_inc_article') }}</option>
                                        <option value="yes">{{ $t('message.url_prospect.yes') }}</option>
                                        <option value="no">{{ $t('message.url_prospect.no') }}</option>
                                    </select>

                                    <span v-if="messageForms.errors['pub.inc_article']"
                                          v-for="err in messageForms.errors['pub.inc_article']"
                                          class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors['pub.url']}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.up_url') }}</label>
                                    <input type="text" class="form-control" v-model="publisherAdd.url" :disabled="true">

                                    <span v-if="messageForms.errors['pub.url']"
                                          v-for="err in messageForms.errors['pub.url']"
                                          class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors['pub.language_id']}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.up_language') }}</label>
                                    <select
                                        name=""
                                        class="form-control"
                                        v-model="publisherAdd.language_id"
                                        :disabled="isEditable">

                                        <option value="">{{ $t('message.url_prospect.up_select_language') }}</option>
                                        <option v-for="option in listLanguages.data" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>

                                    <span v-if="messageForms.errors['pub.language_id']"
                                          v-for="err in messageForms.errors['pub.language_id']"
                                          class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors['pub.price']}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.up_price') }}</label>
                                    <input
                                        type="number"
                                        class="form-control"
                                        v-model="publisherAdd.price"
                                        :disabled="isEditable">

                                    <span v-if="messageForms.errors['pub.price']"
                                          v-for="err in messageForms.errors['pub.price']"
                                          class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div
                                    :class="{'form-group': true, 'has-error': messageForms.errors['pub.casino_sites']}"
                                    class="form-group">

                                    <label style="color: #333">{{ $t('message.url_prospect.up_cb') }}</label>

                                    <select
                                        name=""
                                        class="form-control"
                                        v-model="publisherAdd.casino_sites"
                                        :disabled="isEditable">

                                        <option value="">{{ $t('message.url_prospect.up_select_cb') }}</option>
                                        <option value="yes">{{ $t('message.url_prospect.yes') }}</option>
                                        <option value="no">{{ $t('message.url_prospect.no') }}</option>
                                    </select>

                                    <span v-if="messageForms.errors['pub.casino_sites']"
                                          v-for="err in messageForms.errors['pub.casino_sites']"
                                          class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors['pub.topic']}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.up_topic') }}</label>
                                    <select
                                        name=""
                                        class="form-control"
                                        v-model="publisherAdd.topic"
                                        :disabled="isEditable">
                                        <option value="">{{ $t('message.url_prospect.up_select_topic') }}</option>
                                        <option v-for="option in topic" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select>

                                    <span v-if="messageForms.errors['pub.topic']"
                                          v-for="err in messageForms.errors['pub.topic']"
                                          class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="overlay" v-if="isPopupLoading"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            {{ $t('message.url_prospect.close') }}
                        </button>
                        <button type="button" @click="submitUpdate" class="btn btn-primary">
                            {{ $t('message.url_prospect.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Update -->

        <!-- Modal Setting -->
        <div class="modal fade" id="modal-setting" style="display: none;">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('message.url_prospect.sd_title') }}</h4>
                    </div>
                    <div class="modal-body relative">
                        <div class="form-group row">
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox"
                                              @change="toggleColumn(2, tableShow.id)"
                                              :checked="tableShow.id ? 'checked':''" v-model="tableShow.id">#</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox"
                                              @change="toggleColumn(3, tableShow.employee)"
                                              :checked="tableShow.employee ? 'checked':''" v-model="tableShow.employee">{{ $t('message.url_prospect.filter_emp') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox"
                                              @change="toggleColumn(5, tableShow.country)"
                                              :checked="tableShow.country ? 'checked':''" v-model="tableShow.country">{{ $t('message.url_prospect.filter_country') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox"
                                              @change="toggleColumn(5, tableShow.from)"
                                              :checked="tableShow.from ? 'checked':''" v-model="tableShow.from">{{ $t('message.url_prospect.simple_from') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox"
                                              @change="toggleColumn(6, tableShow.domain)"
                                              :checked="tableShow.domain ? 'checked':''" v-model="tableShow.domain">{{ $t('message.url_prospect.add_domain') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox"
                                              @change="toggleColumn(7, tableShow.email)"
                                              :checked="tableShow.email ? 'checked':''" v-model="tableShow.email">{{ $t('message.url_prospect.add_email') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox"
                                              @change="toggleColumn(8, tableShow.facebook)"
                                              :checked="tableShow.facebook ? 'checked':''" v-model="tableShow.facebook">{{ $t('message.url_prospect.add_facebook') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox"
                                              @change="toggleColumn(10, tableShow.rank)"
                                              :checked="tableShow.rank ? 'checked':''"
                                              v-model="tableShow.rank">{{ $t('message.url_prospect.sd_rank') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox"
                                              @change="toggleColumn(11, tableShow.status)"
                                              :checked="tableShow.status ? 'checked':''" v-model="tableShow.status">{{ $t('message.url_prospect.tb_status') }}</label>
                            </div>
<!--                            <div class="checkbox col-md-4">-->
<!--                                <label><input type="checkbox"-->
<!--                                              @change="toggleColumn(12, tableShow.total_spent)"-->
<!--                                              :checked="tableShow.total_spent ? 'checked':''"-->
<!--                                              v-model="tableShow.total_spent">Total Spent</label>-->
<!--                            </div>-->
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox"
                                              @change="toggleColumn(13, tableShow.ahrefs_rank)"
                                              :checked="tableShow.ahrefs_rank ? 'checked':''"
                                              v-model="tableShow.ahrefs_rank">{{ $t('message.url_prospect.tb_ahrefs') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox"
                                              @change="toggleColumn(14, tableShow.no_backlinks)"
                                              :checked="tableShow.no_backlinks ? 'checked':''"
                                              v-model="tableShow.no_backlinks">{{ $t('message.url_prospect.tb_backlinks') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox"
                                              @change="toggleColumn(15, tableShow.url_rating)"
                                              :checked="tableShow.url_rating ? 'checked':''"
                                              v-model="tableShow.url_rating">{{ $t('message.url_prospect.tb_ur') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox"
                                              @change="toggleColumn(16, tableShow.domain_rating)"
                                              :checked="tableShow.domain_rating ? 'checked':''"
                                              v-model="tableShow.domain_rating">{{ $t('message.url_prospect.tb_dr') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox"
                                              @change="toggleColumn(17, tableShow.ref_domains)"
                                              :checked="tableShow.ref_domains ? 'checked':''"
                                              v-model="tableShow.ref_domains">{{ $t('message.url_prospect.tb_ref_domains') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox"
                                              @change="toggleColumn(18, tableShow.organic_keywords)"
                                              :checked="tableShow.organic_keywords ? 'checked':''"
                                              v-model="tableShow.organic_keywords">{{ $t('message.url_prospect.tb_org_keywords') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox"
                                              @change="toggleColumn(19, tableShow.organic_traffic)"
                                              :checked="tableShow.organic_traffic ? 'checked':''"
                                              v-model="tableShow.organic_traffic">{{ $t('message.url_prospect.tb_org_traffic') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox"
                                              @change="toggleColumn(9, tableShow.phone)"
                                              :checked="tableShow.phone ? 'checked':''" v-model="tableShow.phone">{{ $t('message.url_prospect.add_phone') }}</label>
                            </div>
                        </div>
                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            {{ $t('message.url_prospect.close') }}
                        </button>
                        <button
                            type="button"
                            @click="submitUpdate"
                            data-dismiss="modal"
                            class="btn btn-primary">
                            {{ $t('message.url_prospect.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Setting -->
        <!-- Modal Add Backlink -->
        <div v-if="openModalBackLink" class="modal fade" ref="modalBacklink" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('message.url_prospect.ab_title') }}</h4>
                    </div>
                    <div class="modal-body relative">
                        <form class="row" action="">
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.ext_domain_id}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.ab_ext_domain') }}</label>
                                    <input type="text"
                                           :disabled="true"
                                           v-model="modelBaclink.ext_name"
                                           class="form-control"
                                           value=""
                                           required="required">
                                    <span v-if="messageBacklinkForms.errors.ext_domain_id"
                                          v-for="err in messageBacklinkForms.errors.ext_domain_id"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.country_id}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.tb_country') }}</label>
                                    <div>
                                        <select @change="fillterIntByCountry($event)" class="form-control pull-right">
                                            <option value="0">-- {{ $t('message.url_prospect.add_select_country') }} --</option>
                                            <option v-for="option in listCountriesInt.data" v-bind:value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.int_domain_id}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.b_int_dom') }}</label>
                                    <div>
                                        <select :disabled="loadIntDomain"
                                                v-model="modelBaclink.int_domain_id"
                                                class="form-control pull-right">
                                            <option v-bind:value="0">-- {{ $t('message.url_prospect.ab_select_internal_domain') }} --</option>
                                            <option v-for="option in listInt" v-bind:value="option.id">
                                                {{ option.domain }}
                                            </option>
                                        </select>
                                    </div>

                                    <span v-if="messageBacklinkForms.errors.int_domain_id"
                                          v-for="err in messageBacklinkForms.errors.int_domain_id"
                                          class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.price}"
                                     class="form-group">
                                    <div>
                                        <label style="color: #333">{{ $t('message.url_prospect.b_price') }}</label>
                                        <input
                                            type="number"
                                            v-model="modelBaclink.price"
                                            class="form-control"
                                            value=""
                                            required="required">

                                        <span v-if="messageBacklinkForms.errors.price"
                                              v-for="err in messageBacklinkForms.errors.price"
                                              class="text-danger">
                                            {{ err }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" @click="closeModalBacklink">
                            {{ $t('message.url_prospect.close') }}
                        </button>

                        <button
                            type="button"
                            :disabled="checkSelectIntDomain"
                            @click="submitAddBacklink"
                            class="btn btn-primary">

                            {{ $t('message.url_prospect.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Add -->

        <!-- Modal Send Email -->
        <div id="modal-email" class="modal fade" ref="modalEmail" style="display: none;" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <!-- <h4 class="modal-title">Send email to {{ mailInfo.receiver_text }}</h4> -->
                        <h4 class="modal-title">{{ $t('message.url_prospect.se_title') }}</h4>
                    </div>
                    <div class="modal-body relative">
                        <form class="row" action="">
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
                                        <label style="color: #333">{{ $t('message.url_prospect.se_language') }}</label>
                                        <div>
                                            <select @change="doChangeEmailCountry"
                                                    v-model="mailInfo.country.id"
                                                    class="form-control pull-right">
                                                <option v-for="option in listLanguages.data" v-bind:value="option.id">
                                                    {{ option.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label style="color: #333">
                                                {{ $t('message.url_prospect.se_select_template') }}
                                                {{ mailInfo.country.name }}
                                            </label>

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
                            <!-- <div class="col-md-12" style="margin-top: 15px;">
                               <div :class="{'form-group': true, 'has-error': messageForms.errors.mail_name}" class="form-group">
                                   <label style="color: #333">Email Name</label>
                                   <input type="text" v-model="modelMail.mail_name" class="form-control" value="" required="required" >
                                   <span v-if="messageForms.errors.mail_name" v-for="err in messageForms.errors.mail_name" class="text-danger">{{ err }}</span>
                               </div>
                               </div> -->

                            <div class="col-md-12" style="margin-top: 15px;">
                                <div :class="{'form-group': true, 'has-error': messageFormsMail.errors.email}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.filter_email') }}</label>

                                    <vue-tags-input
                                        v-model="email_to"
                                        :separators="separators"
                                        :tags="urlEmails"
                                        :class="{'vue-tag-error': messageFormsMail.errors.email}"
                                        ref="urlTag"
                                        placeholder=""

                                        @tags-changed="newTags => urlEmails = newTags"
                                    />

                                    <span
                                        v-if="messageFormsMail.errors.email"
                                        v-for="err in messageFormsMail.errors.email"
                                        class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-12" style="margin-top: 15px;">
                                <div :class="{'form-group': true, 'has-error': messageFormsMail.errors.title}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.se_inp_title') }}</label>
                                    <input
                                        type="text"
                                        v-model="modelMail.title"
                                        class="form-control"
                                        value=""
                                        required="required">

                                    <span
                                        v-if="messageFormsMail.errors.title"
                                        v-for="err in messageFormsMail.errors.title"
                                        class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageFormsMail.errors.content}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.url_prospect.se_content') }}</label>
                                    <!--                           <textarea rows="10" type="text" v-model="modelMail.content" class="form-control" value="" required="required"></textarea>-->
                                    <tiny-editor
                                        editor-id="urlEmailEditor"
                                        v-model="modelMail.content"
                                        ref="urlEmailEditor">

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
                                    <label style="color: #333">{{ $t('message.url_prospect.se_attachment') }}</label>
                                    <input
                                        multiple
                                        type="file"
                                        class="form-control"
                                        id="file_send_url"
                                        ref="file_send_url">
                                </div>
                            </div>
                        </form>
                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" @click="modalCloser()">
                            {{ $t('message.url_prospect.close') }}
                        </button>

                        <button
                            type="button"
                            class="btn btn-primary"
                            :disabled="!allowSending"

                            @click="submitSendMail">

                            {{ $t('message.url_prospect.send') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Send Email -->

        <!-- Modal multiple edit of Employee -->
        <div class="modal fade"
             ref="modalMultipleEmployee"
             tabindex="-1"
             role="dialog"
             aria-labelledby="modelTitleId"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.url_prospect.ce_title') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for=""></label>
                                    <select
                                        type="text"
                                        v-model="updateMultiEmployee"
                                        class="form-control"
                                        value=""
                                        required="required">

                                        <option
                                            v-for="(option,key) in listSellerTeam.data"
                                            v-bind:value="option.id"
                                            v-if="option.username != 'N/A' && option.status == 'active'"
                                            :selected="(option.id === extUpdate.user_id ? 'selected' : '')">
                                            {{ option.username }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.url_prospect.close') }}
                        </button>
                        <button type="button" class="btn btn-primary" @click="submitUpdateMultipleEmployee()">
                            {{ $t('message.url_prospect.update') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of multiple edit of Employee -->

        <!-- Modal Change multiple Status -->
        <div id="modal-multiple-status" class="modal fade" ref="modalMultipleStatus">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('message.url_prospect.cms_title') }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.status}">
                                    <label style="color: #333">{{ $t('message.url_prospect.cms_status') }}</label>
                                    <select
                                        type="text"
                                        class="form-control"
                                        v-on:change="checkQualified"
                                        v-model="updateStatus.status"
                                        required="required">

                                        <option value="">{{ $t('message.url_prospect.cms_select_status') }}</option>
                                        <option
                                            v-for="(option, key) in objectToArrayWithCustomSort(listStatusText)"
                                            v-bind:value="option.id">
                                            {{ option.text }}
                                        </option>
                                    </select>

                                    <span
                                        v-if="messageBacklinkForms.errors.status"
                                        v-for="err in messageBacklinkForms.errors.status"
                                        class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12" v-if="isQualified">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.seller}">
                                    <label style="color: #333">{{ $t('message.url_prospect.cms_seller') }}</label>
                                    <select
                                        type="text"
                                        v-model="updateStatus.seller"
                                        class="form-control"
                                        required="required">

                                        <option value="">{{ $t('message.url_prospect.cms_select_seller') }}</option>
                                        <option v-for="option in listExtSeller" v-bind:value="option.id">
                                            {{ option.username }}
                                        </option>
                                    </select>

                                    <span
                                        v-if="messageBacklinkForms.errors.seller"
                                        v-for="err in messageBacklinkForms.errors.seller"
                                        class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            {{ $t('message.url_prospect.close') }}
                        </button>
                        <button type="button" class="btn btn-primary" @click="doUpdateMultipleStatus(false, '')">
                            {{ $t('message.url_prospect.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Change Multiple Status -->
        <!-- Modal Existing Domain -->
        <div class="modal fade" id="modal-existing-domain">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('message.url_prospect.fu_title') }}</h4>
                    </div>

                    <div class="modal-body">
                        <table class="table table-condensed">
                            <tr>
                                <th colspan="2">
                                    {{ $t('message.url_prospect.fu_total') }}
                                    ({{ existingDomain.total }})
                                </th>
                            </tr>
                            <tr v-for="(ext, index) in existingDomain.data" :key="index">
                                <td>{{ ext }}</td>
                                <td class="text-danger">{{ $t('message.url_prospect.fu_not_uploaded') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            {{ $t('message.url_prospect.close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Existing Domain -->
    </div>
</template>
<style>
.custom-checkbox {
    /* Double-sized Checkboxes */
    -ms-transform: scale(2); /* IE */
    -moz-transform: scale(2); /* FF */
    -webkit-transform: scale(2); /* Safari and Chrome */
    -o-transform: scale(2); /* Opera */
    transform: scale(2);
    padding: 10px;
}
</style>
<script>

import {mapState} from 'vuex';
import axios from 'axios';
import DownloadCsv from '@/components/export-csv/Csv.vue'
import {createTags} from '@johmun/vue-tags-input';
import VueVirtualTable from 'vue-virtual-table';
import {csvTemplateMixin} from "../../../mixins/csvTemplateMixin";
import _ from 'underscore';
import TinyEditor from "../../../components/editor/TinyEditor";
import Sort from '@/components/sort/Sort';
import {dateRange} from "../../../mixins/dateRange";

export default {
    components : {
        DownloadCsv,
        VueVirtualTable,
        TinyEditor,
        Sort
    },
    name : 'ExtList',
    mixins : [csvTemplateMixin, dateRange],
    data() {
        return {
            csvExport : {
                file_csv : 'baclink.csv',
                data_filled : {
                    'Ext Domain' : 'ext_domain.domain',
                    'Int Domain' : 'int_domain.domain',
                    'Link' : 'link',
                    'Price' : 'price',
                    'Anchor Text' : 'anchor_text',
                    'Live Date' : 'live_date',
                    'Status' : 'status',
                    'User' : 'user.name'
                },
                json_meta : [
                    [
                        {
                            'key' : 'charset',
                            'value' : 'utf-8'
                        }
                    ]
                ]
            },
            dataTable : null,
            filterModel : {
                id : this.$route.query.id || 0,
                id_temp : this.$route.query.id_temp || 0,
                country_id : this.$route.query.country_id || 0,
                country_id_temp : this.$route.query.country_id || '',
                countryList : {data : [], total : 0},
                domain : this.$route.query.domain || '',
                domain_temp : this.$route.query.domain_temp || '',
                email : this.$route.query.email || '',
                from : this.$route.query.from || '',
                status : this.$route.query.status || -1,
                status_temp :
                    this.$route.query.status_temp ||
                    null,
                page : this.$route.query.page || 0,
                per_page : this.$route.query.per_page || 50,
                employee_id : this.$route.query.employee_id || '',
                required_email_temp : this.$route.query.required_email_temp || 0,
                required_email : this.$route.query.required_email || 0,
                alexa_rank_from : this.$route.query.alexa_rank_from || '',
                alexa_rank_to : this.$route.query.alexa_rank_to || '',
                sort_key : this.$route.query.sort_key || 'id',
                sort_value :
                    this.$route.query.sort_value || 'desc',
                alexa_date_upload : {
                    startDate : null,
                    endDate : null
                },
                domain_zone : this.$route.query.domain_zone || '',
                adv_sort: ''
            },
            listPageOptions : [
                50,
                150,
                250,
                350,
                500,
                1000,
                2000
            ],
            extModel : {
                id : 0,
                domain : '',
                country_id : 0,
                alexa_created_at : 'N/A',
                alexa_rank : 0,
                ahrefs_rank : 0,
                no_backlinks : 0,
                url_rating : 0,
                domain_rating : 0,
                ref_domains : 0,
                organic_keywords : '',
                organic_traffic : '',
                facebook : '',
                email : [],
                phone : '',
                skype : '',
                info : '',
                // price: '',
                status : '',
                // inc_article: 'Yes',
            },
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
                title : '',
                content : '',
                mail_name : '',
            },
            extBackLink : {},
            extUpdate : {
                email : []
            },
            publisherAdd : {
                seller : '',
                language_id : '',
                inc_article : '',
                topic : '',
                casino_sites : '',
                url : '',
                price : '',
            },
            isUpdateMode : false,
            isCrawling : false,
            isLoadingTable : false,
            isPopupLoading : false,
            modalAddBackLink : false,
            modelBaclink : {
                int_domain_id : 0,
                ext_name : ''
            },
            loadIntDomain : false,
            allowSending : true,
            listSortKey : [],
            listSortValue : [],
            checkIds : [],
            showLang : false,
            isEnableBtn : true,
            updateStatus : {
                seller : '',
                status : '',
            },
            isQualified : false,
            formAddUrl : false,
            topic : [
                'Adult',
                'Art',
                'Beauty',
                'Charity',
                'Cooking',
                'Crypto',
                'Education',
                'Fashion',
                'Finance',
                'Games',
                'Health',
                'History',
                'Job',
                'Marketing',
                'Movies & Music',
                'News',
                'Pet',
                'Photograph',
                'Real Estate',
                'Religion',
                'Shopping',
                'Sports',
                'Tech',
                'Travel',
                'Unlisted',
            ],
            isEditable : false,
            existingDomain : {
                total : 0,
                data : []
            },
            email_to : '',
            email_add : '',
            extDomain_id : '',
            allSelected : false,
            separators : [
                ';',
                ',',
                '|',
                ' '
            ],
            urlEmails : [],
            tableLoading : false,
            updateMultiEmployee : '',
            isResultCrawled : false,

            sort_options: [],
            isDomainExistListPublisher: false,

            templateTypeAndCategory: {
                type: '',
                category: ''
            },

            extListTotals: [],
        };
    },
    async created() {
        await this.$store.dispatch('actionCheckAdminCurrentUser', {vue : this});
        this.updateUserPermission();
        this.getUserList();
        this.getListCountriesInt();
        this.getExtList({
            params : this.filterModel
        });
        this.fillterIntByCountry();
        this.checkQualified();
        this.getListExtSeller();

        let seller = this.listSeller.data;
        if (seller.length === 0) {
            this.getListSeller();
        }

        let countries = this.listCountryAll.data;
        if (countries.length === 0) {
            this.getListCountries();
        }

        this.getListSellerTeam();
        this.getListLanguages();
        this.getListDomainZones();
        await this.getExtListTotals();
    },
    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,
            tableShow : state => state.storeExtDomain.tableExtShowOptions,
            listExt : state => state.storeExtDomain.listExt,
            listStatusText : state => state.storeExtDomain.listStatusText,
            listUser : state => state.storeUser.listUser,
            messageForms : state => state.storeExtDomain.messageForms,
            messageFormsMail : state => state.storeMailgun.messageForms,
            messageBacklinkForms : state => state.storeBackLink.messageBacklinkForms,
            listInt : state => state.storeIntDomain.listInt,
            listBackLink : state => state.storeBackLink.listBackLink,
            filterBackLink : state => state.storeBackLink.fillter,
            listCountriesInt : state => state.storeExtDomain.listCountriesInt,
            listAhrefs : state => state.storeExtDomain.listAhrefs,
            listMailTemplate : state => state.storeExtDomain.listMailTemplate,
            listExtSeller : state => state.storeExtDomain.listExtSeller,
            listSeller : state => state.storePublisher.listSeller,
            listCountryAll : state => state.storePublisher.listCountryAll,
            listSellerTeam : state => state.storeExtDomain.listSellerTeam,
            listLanguages : state => state.storePublisher.listLanguages,
            listDomainZones : state => state.storePublisher.listDomainZones,
        }),

        pagination() {
            return {
                props : {
                    callMethod : ""
                },
                template : `
                    <div class="paging_simple_numbers">${this.listExt.pagination}</div>`,
                methods : {
                    async goToPage(pageNum) {
                        this.callMethod(pageNum);
                    }
                }
            }
        },
        checkSelectIntDomain() {
            if (this.modelBaclink.int_domain_id == 0) {
                return true
            }
            return false
        },
        allowSendMail() {
            if (this.allowSending = true) {
                return true;
            }
            return false;
        },
        openModalBackLink() {
            if (this.modalAddBackLink = true) {
                return true
            }
            return false
        },

        tableConfig() {
            return [
                {
                    prop : '_action',
                    name : this.$t('message.url_prospect.tb_action'),
                    actionName : 'actionButtons',
                    width : 200,
                    isHidden : false
                },
                {
                    prop : '_action',
                    name : ' ',
                    actionName : 'actionCheckbox',
                    width : 100,
                    isHidden : false
                },
                {
                    prop : 'id',
                    name : this.$t('message.url_prospect.tb_id'),
                    sortable : true,
                    width : 75,
                    isHidden : !this.tableShow.id
                },
                {
                    prop : 'from',
                    name : this.$t('message.url_prospect.simple_from'),
                    sortable : true,
                    width : 100,
                    isHidden : !this.tableShow.from
                },
                {
                    prop : '_action',
                    name : this.$t('message.url_prospect.tb_emp'),
                    actionName : 'employeeData',
                    width : 100,
                    isHidden : !this.tableShow.employee
                },
                {
                    prop : 'created_at',
                    name : this.$t('message.url_prospect.tb_date_up'),
                    sortable : true,
                    width : 150,
                    isHidden : false
                },
                {
                    prop : 'country.name',
                    name : this.$t('message.url_prospect.tb_country'),
                    sortable : true,
                    width : 100,
                    isHidden : !this.tableShow.country
                },
                {
                    prop : '_action',
                    name : this.$t('message.url_prospect.up_url'),
                    actionName : 'domainData',
                    width : 150,
                    isHidden : !this.tableShow.domain
                },
                {
                    prop : '_action',
                    name : this.$t('message.url_prospect.tb_email'),
                    actionName : 'emailsData',
                    width : 200,
                    isHidden : !this.tableShow.email
                },
                {
                    prop : '_action',
                    name : this.$t('message.url_prospect.tb_facebook'),
                    actionName : 'facebookData',
                    width : 200,
                    isHidden : !this.tableShow.facebook
                },
                {
                    prop : '_action',
                    name : this.$t('message.url_prospect.tb_phone'),
                    actionName : 'phoneData',
                    sortable : true,
                    width : 100,
                    isHidden : !this.tableShow.phone
                },
                {
                    prop : 'alexa_rank',
                    name : this.$t('message.url_prospect.tb_alexa'),
                    sortable : true,
                    width : 100,
                    isHidden : !this.tableShow.rank
                },
                {
                    prop : '_action',
                    name : this.$t('message.url_prospect.tb_status'),
                    actionName : 'statusData',
                    width : 100,
                    isHidden : !this.tableShow.status
                },
                // {
                //     prop : '_action',
                //     name : 'Total Spent',
                //     actionName : 'totalSpentData',
                //     width : 100,
                //     isHidden : true
                // },
                {
                    prop : 'ahrefs_rank',
                    name : this.$t('message.url_prospect.tb_ahrefs'),
                    sortable : true,
                    width : 100,
                    isHidden : !this.tableShow.ahrefs_rank
                },
                {
                    prop : 'no_backlinks',
                    name : this.$t('message.url_prospect.tb_backlinks'),
                    sortable : true,
                    width : 105,
                    isHidden : !this.tableShow.no_backlinks
                },
                {
                    prop : 'url_rating',
                    name : this.$t('message.url_prospect.tb_ur'),
                    sortable : true,
                    width : 100,
                    isHidden : !this.tableShow.url_rating
                },
                {
                    prop : 'domain_rating',
                    name : this.$t('message.url_prospect.tb_dr'),
                    sortable : true,
                    width : 100,
                    isHidden : !this.tableShow.domain_rating
                },
                {
                    prop : 'ref_domains',
                    name : this.$t('message.url_prospect.tb_ref_domains'),
                    sortable : true,
                    width : 105,
                    isHidden : !this.tableShow.ref_domains
                },
                {
                    prop : '_action',
                    name : this.$t('message.url_prospect.tb_org_keywords'),
                    actionName : 'organicKwData',
                    width : 110,
                    isHidden : !this.tableShow.organic_keywords
                },
                {
                    prop : '_action',
                    name : this.$t('message.url_prospect.tb_org_traffic'),
                    actionName : 'organicTrafficData',
                    width : 110,
                    isHidden : !this.tableShow.organic_traffic
                }
            ];
        },

        // for sort

        isSorted() {
            return this.filterModel.adv_sort !== '' && this.filterModel.adv_sort.length !== 0;
        },

        sortOptions() {
            return [
                {
                    name: this.$t('message.url_prospect.tb_id'),
                    sort: '',
                    column: 'id',
                    hidden: !this.tableShow.id
                },
                {
                    name: this.$t('message.url_prospect.tb_emp'),
                    sort: '',
                    column: 'A.username',
                    hidden: !this.tableShow.employee
                },
                {
                    name: this.$t('message.url_prospect.simple_from'),
                    sort: '',
                    column: 'from',
                    hidden: !this.tableShow.from
                },
                {
                    name: this.$t('message.url_prospect.tb_date_up'),
                    sort: '',
                    column: 'created_at',
                    hidden: false
                },
                {
                    name: this.$t('message.url_prospect.tb_country'),
                    sort: '',
                    column: 'countries.name',
                    hidden: !this.tableShow.country
                },
                {
                    name: this.$t('message.url_prospect.up_url'),
                    sort: '',
                    column: 'REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(domain,\'http://\',\'\'), \'https://\', \'\'), \'www.\', \'\'), \'/\', \'\'), \' \', \'\')',
                    hidden: !this.tableShow.domain
                },
                {
                    name: this.$t('message.url_prospect.tb_email'),
                    sort: '',
                    column: 'email',
                    hidden: !this.tableShow.email
                },
                {
                    name: this.$t('message.url_prospect.tb_facebook'),
                    sort: '',
                    column: 'facebook',
                    hidden: !this.tableShow.facebook
                },
                {
                    name: this.$t('message.url_prospect.tb_phone'),
                    sort: '',
                    column: 'phone',
                    hidden: !this.tableShow.phone
                },
                {
                    name: this.$t('message.url_prospect.tb_alexa'),
                    sort: '',
                    column: 'cast(alexa_rank as unsigned)',
                    hidden: !this.tableShow.rank
                },
                {
                    name: this.$t('message.url_prospect.tb_status'),
                    sort: '',
                    column: 'status',
                    hidden: !this.tableShow.status
                },
                {
                    name: this.$t('message.url_prospect.tb_ahrefs'),
                    sort: '',
                    column: 'cast(ahrefs_rank as unsigned)',
                    hidden: !this.tableShow.ahrefs_rank
                },
                {
                    name: this.$t('message.url_prospect.tb_backlinks'),
                    sort: '',
                    column: 'cast(no_backlinks as unsigned)',
                    hidden: !this.tableShow.no_backlinks
                },
                {
                    name: this.$t('message.url_prospect.tb_ur'),
                    sort: '',
                    column: 'cast(url_rating as unsigned)',
                    hidden: !this.tableShow.url_rating
                },
                {
                    name: this.$t('message.url_prospect.tb_dr'),
                    sort: '',
                    column: 'cast(domain_rating as unsigned)',
                    hidden: !this.tableShow.domain_rating
                },
                {
                    name: this.$t('message.url_prospect.tb_ref_domains'),
                    sort: '',
                    column: 'cast(ref_domains as unsigned)',
                    hidden: !this.tableShow.ref_domains
                },
                {
                    name: this.$t('message.url_prospect.tb_org_keywords'),
                    sort: '',
                    column: 'cast(organic_keywords as unsigned)',
                    hidden: !this.tableShow.organic_keywords
                },
                {
                    name: this.$t('message.url_prospect.tb_org_traffic'),
                    sort: '',
                    column: 'cast(organic_traffic as unsigned)',
                    hidden: !this.tableShow.organic_traffic
                },
            ]
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

        extListStatusTotals () {
            let self = this;

             return [
                 {
                     text: 'New',
                     total: self.extListTotals['New'],
                     badge: 'bg-primary',
                     icon: 'fas fa-asterisk',
                     percentage: self.calculatePercentage(self.extListTotals['New'])
                 },

                 {
                     text: 'Crawl Failed',
                     total: self.extListTotals['CrawlFailed'],
                     badge: 'bg-danger',
                     icon: 'fas fa-times-circle',
                     percentage: self.calculatePercentage(self.extListTotals['CrawlFailed'])
                 },

                 {
                     text: 'Contacted',
                     total: self.extListTotals['Contacted'],
                     badge: 'bg-success',
                     icon: 'fas fa-check-circle',
                     percentage: self.calculatePercentage(self.extListTotals['Contacted'])
                 },

                 {
                     text: 'Contacted Via Form',
                     total: self.extListTotals['ContactedViaForm'],
                     badge: 'bg-secondary',
                     icon: 'fab fa-wpforms',
                     percentage: self.calculatePercentage(self.extListTotals['ContactedViaForm'])
                 },

                 {
                     text: 'Contact Null',
                     total: self.extListTotals['ContactNull'],
                     badge: 'bg-warning',
                     icon: 'fas fa-comment-slash',
                     percentage: self.calculatePercentage(self.extListTotals['ContactNull'])
                 },

                 {
                     text: 'Got Contacts',
                     total: self.extListTotals['GotContacts'],
                     badge: 'bg-gradient-teal',
                     icon: 'fas fa-address-card',
                     percentage: self.calculatePercentage(self.extListTotals['GotContacts'])
                 },

                 {
                     text: 'Got Email',
                     total: self.extListTotals['GotEmail'],
                     badge: 'bg-cyan',
                     icon: 'fas fa-at',
                     percentage: self.calculatePercentage(self.extListTotals['GotEmail'])
                 },

                 {
                     text: 'No Answer',
                     total: self.extListTotals['NoAnswer'],
                     badge: 'bg-orange',
                     icon: 'fas fa-phone-slash',
                     percentage: self.calculatePercentage(self.extListTotals['NoAnswer'])
                 },

                 {
                     text: 'Refused',
                     total: self.extListTotals['Refused'],
                     badge: 'bg-maroon',
                     icon: 'fas fa-handshake-alt-slash',
                     percentage: self.calculatePercentage(self.extListTotals['Refused'])
                 },

                 {
                     text: 'In Touched',
                     total: self.extListTotals['InTouched'],
                     badge: 'bg-purple',
                     icon: 'fas fa-comments',
                     percentage: self.calculatePercentage(self.extListTotals['InTouched'])
                 },

                 {
                     text: 'Unqualified',
                     total: self.extListTotals['Unqualified'],
                     badge: 'bg-dark',
                     icon: 'fas fa-ban',
                     percentage: self.calculatePercentage(self.extListTotals['Unqualified'])
                 },

                 {
                     text: 'Qualified',
                     total: self.extListTotals['Qualified'],
                     badge: 'bg-olive',
                     icon: 'fas fa-certificate',
                     percentage: self.calculatePercentage(self.extListTotals['Qualified'])
                 },
             ];
        }
    },
    mounted() {
        let that = this;
        $(this.$refs.modalBacklink).on("hidden.bs.modal", this.handleCloseBacklinkModal)
        $('.freeze-table').on('click', '.clone-column-table-wrap *[data-action]', function (e) {
            e.preventDefault();
            var action = $(this).attr('data-action');
            var index = $(this).attr('data-index');
            if (action == "a1") {
                that.doEditExtIndex(index);
            } else if (action == "a2") {
                that.doShowBackLinkIndex(index);
            } else if (action == "a3") {
                that.addBackLinkIndex(index);
            } else if (action == "a4") {
                that.doSendEmailIndex(index);
            }
        });
        $('.freeze-table').on('click', '.clone-head-table-wrap th', function (e) {
            e.preventDefault();
            var index = $(this).attr('data-index');
            $('#data-table th[data-index=' + index + ']').click();
            var m_class = $('#data-table th[data-index=' + index + ']').attr('class');
            $(this).attr('class', m_class);
        });
    },
    methods : {
        modalCloser() {
            let self = this;

            if (this.modelMail.title || this.modelMail.content) {

                swal.fire({
                    title : self.$t('message.url_prospect.swal_sure'),
                    text : self.$t('message.url_prospect.swal_email_remove'),
                    icon : "warning",
                    showCancelButton : true,
                    confirmButtonText : 'Yes',
                    cancelButtonText : 'No'
                })
                    .then((result) => {
                        if (result.isConfirmed) {
                            // remove all images inserted on editor
                            this.$refs.urlEmailEditor.deleteImages('All');

                            this.closeModal()
                            this.clearMailModel()
                        }
                    });

            } else {
                this.$refs.urlEmailEditor.deleteImages('All');
                this.clearMailModel()
                this.closeModal()
            }
        },

        closeModal() {
            let element = this.$refs.modalEmail
            $(element).modal('hide')
        },

        clearMailModel() {
            this.modelMail = {
                title : '',
                content : '',
                mail_name : '',
            }
        },

        async saveColumnSetting() {
            let loader = this.$loading.show();
            this.toggleTableLoading();

            await new Promise(resolve => {
                setTimeout(resolve, 2000)
            })

            this.toggleTableLoading();
            loader.hide();
        },

        toggleColumn(index, columnState) {
            this.tableConfig[index].isHidden =
                !columnState;
        },

        toggleTableLoading() {
            if (this.tableLoading) {
                this.tableLoading = false;
            } else {
                this.tableLoading = true;
            }
        },

        selectAll() {
            this.checkIds = [];
            if (!this.allSelected) {
                for (let ext in this.listExt.data) {
                    this.checkIds.push(this.listExt.data[ext]);
                }
            }
            this.allSelected = !this.allSelected;
        },

        async getListLanguages() {
            await this.$store.dispatch('actionGetListLanguages');
        },

        async getListDomainZones(params) {
            await this.$store.dispatch('actionGetListDomainZones', params);
        },

        async getListCountries(params) {
            await this.$store.dispatch('actionGetListCountries', params);
        },

        showAddURL() {
            this.formAddUrl = false;
            if (this.extUpdate.status == 100) {
                this.formAddUrl = true;
                this.extUpdate.url = this.extUpdate.domain;
            }
        },

        checkSellerAccess(seller_id, in_charge) {
            if (this.user.role_id == 6 && this.user.isOurs == 0) {
                let check = false;
                if (this.user.id == seller_id) {
                    check = true;
                }

                if (!in_charge) {
                    check = true;
                }

                return check;
            } else {
                return true;
            }
        },

        async getListSeller(params) {
            await this.$store.dispatch('actionGetListSeller', params);
        },

        async getListSellerTeam(params) {
            await this.$store.dispatch('actionGetListSellerTeam', params);
        },

        async getListExtSeller() {
            await this.$store.dispatch('actionGetListExtSeller');
        },

        checkQualified() {
            let check = this.updateStatus.status
            if (check == 100) {
                this.isQualified = true;
            } else {
                this.isQualified = false;
            }
        },

        // checkData() {
        //     this.isEnableBtn = true;
        //     if( this.$refs.language.value && this.$refs.status.value){
        //         this.isEnableBtn = false;
        //     }
        // },

        checkDataExcel() {
            this.isEnableBtn = true;
            if (this.$refs.excel.value) {
                this.isEnableBtn = false;
            }
        },

        doMultipleStatus() {
            let self = this;

            if (this.checkIds.length > 0) {
                let element = this.$refs.modalMultipleStatus
                $(element).modal('show')
            } else {
                swal.fire(
                    self.$t('message.url_prospect.swal_no_item'),
                    self.$t('message.url_prospect.swal_no_item_selected'),
                    'error'
                )
            }
        },

        async deleteAll() {
            let self = this;

            if (this.checkIds.length > 0) {
                if (confirm(self.$t('message.url_prospect.swal_delete_records'))) {
                    await this.$store.dispatch('actionDeleteExtDomain', {
                        params : {
                            id : this.checkIds,
                        }
                    });

                    this.getExtList({
                        params : this.filterModel
                    });
                    this.checkIds = []
                    swal.fire(
                        self.$t('message.url_prospect.swal_saved'),
                        self.$t('message.url_prospect.swal_successfully_updated'),
                        'success'
                    )
                }
            } else {
                swal.fire(
                    self.$t('message.url_prospect.swal_no_item'),
                    self.$t('message.url_prospect.swal_no_item_selected'),
                    'error'
                )
            }

        },

        async submitUpload() {
            let self = this;
            let loader = this.$loading.show();
            this.isEnableBtn = true;

            // clear error messages

            this.existingDomain.total = 0;
            this.existingDomain.data = [];

            this.formData = new FormData();
            this.formData.append('file', this.$refs.excel.files[0]);
            // this.formData.append('language', this.$refs.language.value);
            // this.formData.append('status', this.$refs.status.value);

            await this.$store.dispatch('actionUploadCsvExtDomain', this.formData);

            if (this.messageForms.action === 'uploaded') {
                // this.getExtList();
                this.getExtList({
                    params : this.filterModel
                });

                this.$refs.excel.value = '';
                // this.$refs.language.value = '';
                // this.$refs.status.value = '';
                this.isEnableBtn = true;
                this.showLang = false;

                // console.log(this.messageForms.errors.length)
                let cnt_existing = this.messageForms.errors.length;
                if (cnt_existing > 0) {
                    for (let key in this.messageForms.errors) {
                        this.existingDomain.data.push(this.messageForms.errors[key].message)
                    }

                    this.existingDomain.total = cnt_existing;
                    $("#modal-existing-domain").modal('show')
                }

                swal.fire(
                    self.$t('message.url_prospect.swal_uploaded'),
                    self.$t('message.url_prospect.swal_successfully_uploaded'),
                    'success'
                )
            }

            loader.hide();
        },

        formatPrice(value) {
            let val = (value / 1).toFixed(0)
            return val;
        },

        checkSelected() {
            this.isDisabled = true;
            if (this.checkIds.length > 0) {
                this.isDisabled = false;
            }
        },

        async updateUserPermission() {
            let that = this;
            await this.$store.dispatch('actionUpdateCurrentUserCountriesExt', {vue : this, userId : that.user.id});
            this.initFilter();
        },
        clearExtModel() {
            this.extModel = {
                id : 0,
                domain : '',
                country_id : 0,
                alexa_rank : 0,
                ahrefs_rank : 0,
                no_backlinks : 0,
                url_rating : 0,
                domain_rating : 0,
                ref_domains : 0,
                organic_keywords : '',
                organic_traffic : '',
                facebook : '',
                email : [],
                phone : '',
                status : 0
            }
        },
        initFilter() {
            let that = this;
            this.user.countries_ext_accessable.forEach(function (country) {
                that.filterModel.countryList.data.push({id : country.id, name : country.name});
            });
            this.listSortKey = {
                'id' : {text : '----'},
                'alexa_rank' : {text : 'Alexa Rank'},
                'ahrefs_rank' : {text : 'Ahrefs Rank'},
                'no_backlinks' : {text : 'No Backlink'},
                'url_rating' : {text : 'URL Rating'},
                'domain_rating' : {text : 'Domain Rating'},
                'ref_domains' : {text : 'Ref Domains'},
                'organic_keywords' : {text : 'Organic keywords'},
                'organic_traffic' : {text : 'Organic traffic'},
                // 'total_spent' : {text : 'Total Spent'},
            };
            this.listSortValue = {
                'asc' : {text : 'Ascending '},
                'desc' : {text : 'Descending '}
            };
            this.filterModel.sort_value = 'desc';
        },
        async getUserList() {
            await this.$store.dispatch('actionCheckAdminCurrentUser', {vue : this});
            if (this.user.isAdmin) {
                await this.$store.dispatch('actionGetUser', {
                    vue : this,
                    params : {params : {full_data : true}},
                    showMainLoading : false
                });
            }
        },
        async getListCountriesInt() {
            await this.$store.dispatch('getListCountriesInt', {vue : this});
        },
        async getExtList(params) {
            let loader = this.$loading.show();
            this.isLoadingTable = true;

            if (this.isSorted) {
                params.params.adv_sort = this.getSortData()
            }

            await this.$store.dispatch('getListExt', params);

            this.isLoadingTable = false;
            $('.freeze-table').freezeTable({'columnNum' : 4, 'shadow' : true, 'scrollable' : true});
            this.isResultCrawled = false;
            loader.hide();
        },

        getExtListTotals () {
            axios.get('/api/ext-totals', {
                params: this.filterModel
            })
            .then((res) => {
                this.extListTotals = res.data
            })
            .catch((err) => {
                console.log(err)
            })
        },

        calculatePercentage (number) {
            if (this.listExt.total === 0) {
                return 0;
            } else {
                return ((number / this.listExt.total) * 100).toFixed(2);
            }
        },

        async doSearchList() {
            let that = this;
            that.filterModel.country_id = that.filterModel.country_id_temp;
            that.filterModel.status = that.filterModel.status_temp;
            that.filterModel.domain = that.filterModel.domain_temp;
            that.filterModel.id = that.filterModel.id_temp;
            that.filterModel.required_email = that.filterModel.required_email_temp;
            that.filterModel.sort = that.filterModel.sort_key + ',' + that.filterModel.sort_value;

            // change the format of date
            that.filterModel.alexa_date_upload = this.formatFilterDates(this.filterModel.alexa_date_upload)

            this.$router.push({
                query : that.filterModel,
            });
            this.getExtList({
                params : {
                    country_id : that.filterModel.country_id,
                    status : that.filterModel.status,
                    domain : that.filterModel.domain,
                    email : that.filterModel.email,
                    employee_id : that.filterModel.employee_id,
                    required_email : that.filterModel.required_email,
                    id : that.filterModel.id,
                    sort : that.filterModel.sort_key + ',' + that.filterModel.sort_value,
                    per_page : that.filterModel.per_page,
                    alexa_rank_from : that.filterModel.alexa_rank_from,
                    alexa_rank_to : that.filterModel.alexa_rank_to,
                    alexa_date_upload : that.filterModel.alexa_date_upload,
                    domain_zone : that.filterModel.domain_zone,
                    from : that.filterModel.from
                }
            });

            this.getExtListTotals()
        },
        async goToPage(pageNum) {
            let that = this;

            // change the format of date
            that.filterModel.alexa_date_upload = this.formatFilterDates(this.filterModel.alexa_date_upload)

            this.$router.push({
                query : that.filterModel,
            });
            await this.getExtList({
                params : {
                    page : pageNum,
                    country_id : that.filterModel.country_id,
                    status : that.filterModel.status,
                    domain : that.filterModel.domain,
                    id : that.filterModel.id,
                    sort : that.filterModel.sort_key + ',' + that.filterModel.sort_value,
                    per_page : that.filterModel.per_page,
                    employee_id : that.filterModel.employee_id,
                    required_email : that.filterModel.required_email,
                    alexa_date_upload : that.filterModel.alexa_date_upload,
                    domain_zone : that.filterModel.domain_zone,
                    from : that.filterModel.from
                }
            });
        },

        sortSubmit(data) {
            this.filterModel.adv_sort = data.filter(item => item.sort !== '' && item.hidden !== true)
            this.getExtList({
                params : this.filterModel
            });
        },

        updateSortOptions(data) {
            this.sort_options = data;
        },

        getSortData() {
            return this.sort_options.filter(item => item.sort !== '' && item.hidden !== true)
        },

        async doCrawlExtList() {
            let self = this;
            let loader = this.$loading.show();
            this.isCrawling = true;
            let arrayIds = [];
            if (this.checkIds) {
                for (let key in this.checkIds) {
                    arrayIds.push(this.checkIds[key].id);
                }
            }
            if (arrayIds.length == 0) {
                swal.fire(
                    self.$t('message.url_prospect.swal_no_item'),
                    self.$t('message.url_prospect.swal_no_item_selected'),
                    'error'
                )

                this.isCrawling = false;
                loader.hide();
                return;
            }

            if (arrayIds.length > 50) {
                swal.fire(
                    self.$t('message.url_prospect.swal_err'),
                    self.$t('message.url_prospect.swal_err_crawl'),
                    'error'
                );

                this.isCrawling = false;
                loader.hide();
                return;
            }

            await this.$store.dispatch('crawlExtList', {
                domain_ids : arrayIds.join(","),
                queue : false
            });

            if (this.messageForms.action === 'crawled') {
                swal.fire(
                    self.$t('message.url_prospect.swal_success'),
                    self.$t('message.url_prospect.swal_crawled') + this.listExt.data.length + self.$t('message.url_prospect.swal_items'),
                    'success'
                )

                this.isResultCrawled = true;
            }

            this.isCrawling = false;
            this.checkIds = [];
            this.allSelected = false;
            this.$store.dispatch('clearMessageForm');
            loader.hide();
        },
        async submitAdd() {
            let that = this;
            this.isPopupLoading = true;
            await this.$store.dispatch('addExt', that.extModel);
            this.isPopupLoading = false;
            if (this.messageForms.action === 'saved_ext') {
                this.clearExtModel();
                //this.listExt.data.pop();
                //this.listExt.data.unshift(this.messageForms.obj);
                this.doSearchList();
                this.isEditable = true;
            }
        },
        convertPrice(price) {
            return parseFloat(price).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        },
        async submitUpdate() {
            let self = this;
            let loader = this.$loading.show();
            this.toggleTableLoading();
            this.isPopupLoading = true;
            await this.$store.dispatch('updateExt', {
                ext : this.extUpdate,
                pub : this.publisherAdd,
            });
            this.isPopupLoading = false;
            if (this.messageForms.action === 'updated_ext') {
                // console.log(this.extUpdate)
                // for (var index in this.listExt.data) {
                //     if (this.listExt.data[index].id === this.extUpdate.id) {
                //         this.listExt.data[index] = this.extUpdate;
                //         break;
                //     }
                // }


                this.getExtList({
                    params : this.filterModel
                });

                $('#modal-update').modal('hide');

            }
            this.toggleTableLoading();
            loader.hide();

            swal.fire(
                self.$t('message.url_prospect.swal_success'),
                self.$t('message.url_prospect.swal_successfully_updated'),
                'success'
            );
        },
        async doShowBackLink(extDomain) {
            this.extBackLink = extDomain;
            this.filterBackLink.ext = extDomain.id;
            this.filterBackLink.full_data = true;
            this.isPopupLoading = true;
            await this.$store.dispatch('actionGetBackLink', {
                vue : this,
                params : this.filterBackLink
            });
            this.isPopupLoading = false;
        },

        async doShowBackLinkIndex(index) {
            var extDomain = this.listExt.data[index];
            this.doShowBackLinkIndex(extDomain);
        },

        doAddExt() {
            this.$store.dispatch('clearMessageForm');
        },

        doEditExt(extDomain) {
            this.formAddUrl = true;
            this.extUpdate.email = [];

            this.$store.dispatch('clearMessageForm');
            this.extUpdate = JSON.parse(JSON.stringify(extDomain))

            this.extUpdate.email = (this.extUpdate.email === null || this.extUpdate.email === '') ? [] : this.extUpdate.email;

            let emails = [];

            if (typeof (this.extUpdate.email) === "string") {
                emails = this.extUpdate.email.split('|')
            } else {
                emails = this.extUpdate.email.map(a => a.text);
            }

            this.extUpdate.email = createTags(emails)

            if (this.extUpdate.status != 100) {
                this.formAddUrl = false;
            }

            this.getPublisherInfo(this.extUpdate.domain).then(res => {
                var result = res.data
                if (res.data.success == true) {
                    this.publisherAdd.seller = result.data.user_id
                    this.publisherAdd.inc_article = (result.data.inc_article).toLowerCase();
                    this.publisherAdd.url = result.data.url
                    this.publisherAdd.language_id = result.data.language_id
                    this.publisherAdd.topic = result.data.topic
                    this.publisherAdd.casino_sites = result.data.casino_sites
                    this.publisherAdd.price = result.data.price

                    this.isEditable = true;
                    this.isDomainExistListPublisher = true;
                } else {
                    this.publisherAdd.seller = ''
                    this.publisherAdd.inc_article = ''
                    this.publisherAdd.url = this.extUpdate.domain;
                    this.publisherAdd.language_id = ''
                    this.publisherAdd.topic = ''
                    this.publisherAdd.casino_sites = ''
                    this.publisherAdd.price = ''

                    this.isEditable = false;
                    this.isDomainExistListPublisher = false;
                }

            });

            $('#modal-update').modal('show');

        },

        async getPublisherInfo(domain) {
            try {
                const response = await axios.get('api/get-publisher-info', {
                    params : {
                        url : domain,
                    }
                });
                return response;
            } catch (error) {
                console.error(error);
            }
        },

        doEditExtIndex(index) {
            let extDomain = this.listExt.data[index];
            this.doEditExt(extDomain);
        },

        hasBacklink(status) {
            if (status == 70) {
                return true
            }
            return false
        },
        async addBackLink(ext) {
            this.modalAddBackLink = true
            this.modelBaclink.ext_domain_id = ext.id
            this.modelBaclink.ext_name = ext.domain
            let element = this.$refs.modalBacklink
            $(element).modal('show')
        },
        async addBackLinkIndex(index) {
            let extDomain = this.listExt.data[index];
            this.addBackLink(extDomain);
        },
        async fillterIntByCountry(event) {
            if (typeof event == 'undefined') {
                var countryID = 0
            } else {
                var countryID = event.target.value
            }
            this.loadIntDomain = true
            await this.$store.dispatch('getListInt', {
                params : {
                    country_id : countryID,
                    full_page : true
                }
            });
            this.loadIntDomain = false
        },
        async submitAddBacklink() {
            await this.$store.dispatch('actionSaveBacklink', {
                params : this.modelBaclink
            })

            if (this.messageForms.action === 'saved_backlink') {
                this.closeModalBacklink()
            }
        },
        handleCloseBacklinkModal() {
            this.modelBaclink = {
                int_domain_id : 0,
                ext_name : ''
            }
            this.$store.dispatch('clearMessageBacklinkForm')
        },
        closeModalBacklink() {
            let element = this.$refs.modalBacklink
            $(element).modal('hide')
        },
        closeModalEmail() {
            let element = this.$refs.modalEmail
            $(element).modal('hide')
        },
        async getAhrefs() {
            let self = this;
            // var listInvalid = this.checkIds.some(ext => ext.status != 30);
            // if (listInvalid === true) {
            //     alert('List invalid: status diff with GotContacts');
            //     return;
            // }
            let listIds = this.checkIds.map(ext => ext.id).join(',');
            this.isLoadingTable = true;
            await this.$store.dispatch('getListAhrefs', {params : {domain_ids : listIds}});
            this.isLoadingTable = false;
            let that = this;

            if (that.listAhrefs.length !== 0) {

                // this.checkIds.forEach(item => {
                //     if (that.listAhrefs.hasOwnProperty(item.id)) {
                //         let itemAherf = that.listAhrefs[item.id];
                //         item.ahrefs_rank = itemAherf.ahrefs_rank;
                //         item.no_backlinks = itemAherf.no_backlinks;
                //         item.url_rating = itemAherf.url_rating;
                //         item.domain_rating = itemAherf.domain_rating;
                //         item.organic_keywords = itemAherf.organic_keywords;
                //         item.organic_traffic = itemAherf.organic_traffic;
                //         item.ref_domains = itemAherf.ref_domains;
                //         item.status = itemAherf.status;
                //     }
                // });

                this.getExtList({
                    params : this.filterModel
                });

                swal.fire(
                    self.$t('message.url_prospect.swal_success'),
                    self.$t('message.url_prospect.swal_url_updated'),
                    'success'
                )
            } else {
                swal.fire(
                    self.$t('message.url_prospect.swal_err'),
                    self.$t('message.url_prospect.swal_err_ahref'),
                    'error'
                )
            }
        },
        async getAhrefsById(extId, extStatus) {
            let self = this;
            // if (extStatus != 30) {
            //     alert('List invalid: status diff with GotContacts');
            //     return;
            // }
            var listIds = extId;
            this.isLoadingTable = true;
            await this.$store.dispatch('getListAhrefs', {params : {domain_ids : listIds}});
            this.isLoadingTable = false;
            var that = this;

            if (that.listAhrefs.length !== 0) {
                // this.listExt.data.forEach(item => {
                //     if (that.listAhrefs.hasOwnProperty(item.id)) {
                //         let itemAherf = that.listAhrefs[item.id];
                //         item.ahrefs_rank = itemAherf.ahrefs_rank;
                //         item.no_backlinks = itemAherf.no_backlinks;
                //         item.url_rating = itemAherf.url_rating;
                //         item.domain_rating = itemAherf.domain_rating;
                //         item.organic_keywords = itemAherf.organic_keywords;
                //         item.organic_traffic = itemAherf.organic_traffic;
                //         item.ref_domains = itemAherf.ref_domains;
                //         item.status = itemAherf.status;
                //     }
                // });

                this.getExtList({
                    params : this.filterModel
                });

                swal.fire(
                    self.$t('message.url_prospect.swal_success'),
                    self.$t('message.url_prospect.swal_url_updated'),
                    'success'
                )
            } else {
                swal.fire(
                    self.$t('message.url_prospect.swal_err'),
                    self.$t('message.url_prospect.swal_err_ahref'),
                    'error'
                )
            }
        },
        openModalEmailElem() {
            let element = this.$refs.modalEmail;
            $(element).modal('show');
            this.allowSending = true;
        },

        doSendEmail(ext, event) {
            let self = this;
            this.$store.dispatch('clearMessageForm');
            this.urlEmails = [];

            if (this.user.work_mail) {

                if (ext == null) {
                    this.extDomain_id = '';

                    let selectedEmails = [];

                    for (let index in this.checkIds) {
                        if (this.checkIds[index].email !== "" && this.checkIds[index].email != null) {
                            if (typeof (this.checkIds[index].email) === "string") {
                                selectedEmails.push(this.checkIds[index].email.split('|'))
                            } else {
                                selectedEmails.push(this.checkIds[index].email.map(a => a.text))
                            }
                        }
                    }

                    if (this.checkIds.length == 0) {
                        swal.fire(
                            self.$t('message.url_prospect.swal_no_item'),
                            self.$t('message.url_prospect.swal_no_item_selected'),
                            'error');

                    } else if (selectedEmails.flat().length > 10) {
                        swal.fire(
                            self.$t('message.url_prospect.swal_err_invalid'),
                            self.$t('message.url_prospect.swal_err_10'),
                            'error'
                        )
                    } else {
                        let err = this.checkIds.some(function (items) {
                            return items.email == "" | items.email == null;
                        });

                        if (err) {
                            swal.fire(
                                self.$t('message.url_prospect.swal_err_invalid'),
                                self.$t('message.url_prospect.swal_err_selection_email'),
                                'error'
                            );
                        } else {
                            this.openModalEmailElem();
                            let emails = [];
                            for (let index in this.checkIds) {
                                if (this.checkIds[index].email != "" || this.checkIds[index].email != null) {
                                    if (typeof (this.checkIds[index].email) === "string") {
                                        emails.push(this.checkIds[index].email.split('|'))
                                    } else {
                                        emails.push(this.checkIds[index].email.map(a => a.text))
                                    }
                                }
                            }

                            this.urlEmails = createTags(emails.flat())

                            // this.email_to = emails.join('|')
                        }
                    }
                    // console.log(this.checkIds)
                    // return false;
                }

                if (ext != null) {
                    // if (ext.status == 50) {
                    //     swal.fire('Invalid', 'Record already contacted.', 'error')
                    // } else

                    if (ext.email == "" || ext.email == null || ext.email.length == 0) {
                        swal.fire(
                            self.$t('message.url_prospect.swal_err_no_email'),
                            self.$t('message.url_prospect.swal_err_check_email'),
                            'error'
                        )
                    } else {
                        this.openModalEmailElem();
                        // this.email_to = ext.email;
                        this.extDomain_id = ext.id;

                        let emails = [];

                        if (typeof (ext.email) === "string") {
                            emails = ext.email.split('|')
                        } else {
                            emails = ext.email.map(a => a.text);
                        }

                        this.urlEmails = emails ? createTags(emails) : [];
                    }
                }

            } else {
                swal.fire(
                    self.$t('message.url_prospect.swal_err'),
                    self.$t('message.url_prospect.swal_err_work_mail'),
                    'error'
                )
            }
        },

        getStatus() {
            axios.get('/api/mail/status')
                .then((res) => {
                    console.log(res.data)
                })
        },

        doMultipleEmployee() {
            let self = this;

            if (this.checkIds.length > 0) {
                let element = this.$refs.modalMultipleEmployee
                $(element).modal('show')
            } else {
                swal.fire(
                    self.$t('message.url_prospect.swal_no_item'),
                    self.$t('message.url_prospect.swal_no_item_selected'),
                    'error'
                )
            }
        },

        submitUpdateMultipleEmployee() {
            let self = this;
            let ids = [];
            for (let index in this.checkIds) {
                ids.push(this.checkIds[index].id)
            }

            axios.post('/api/update-multiple-employee', {
                ids : ids,
                emp_id : this.updateMultiEmployee
            }).then((res) => {
                if (res.data.success === true) {

                    let element = this.$refs.modalMultipleEmployee
                    $(element).modal('hide')

                    swal.fire(
                        self.$t('message.url_prospect.swal_success'),
                        self.$t('message.url_prospect.swal_successfully_updated'),
                        'success'
                    )

                    this.getExtList({
                        params : this.filterModel
                    });

                }

                this.updateMultiEmployee = '';
                this.checkIds = [];
            })
        },

        async submitSendMail() {
            let self = this;
            this.allowSending = false;
            this.isPopupLoading = true;

            // create form data

            let formData = new FormData();
            formData.append('cc', '');
            formData.append('email', JSON.stringify(this.urlEmails));
            formData.append('title', this.modelMail.title);
            formData.append('content', this.modelMail.content);
            formData.append('from', 'prospect');

            // get attachments

            let attachments = this.$refs.file_send_url.files;

            if (!attachments.length) {
                formData.append('attachment', 'undefined');
            } else {
                for (let i = 0; i < attachments.length; i++) {
                    formData.append('attachment[]', attachments[i]);
                }
            }

            // await this.$store.dispatch('sendMailWithMailgun', {
            //     cc: '',
            //     email: this.urlEmails,
            //     title: this.modelMail.title,
            //     content: this.modelMail.content,
            //     attachment: 'undefined',
            // })

            await this.$store.dispatch('actionSendMailgun', formData);

            this.isPopupLoading = false;

            if (this.messageFormsMail.action === 'success') {

                this.$refs.urlEmailEditor.deleteImages('Removed');

                if (this.mailInfo.tpl === 0) {
                    this.modelMail = {
                        title : '',
                        content : '',
                        mail_name : '',
                    }
                }

                // this.getStatus();
                let result = true;
                if (this.extDomain_id == '') {
                    result = false;
                    this.updateStatus.status = 50;
                }

                this.doUpdateMultipleStatus(result, this.extDomain_id);

                $("#modal-email").modal('hide')

                // clear attachments

                this.$refs.file_send_url.value = "";

                // clear message form

                await this.$store.dispatch('clearMessageForm');
            } else {
                swal.fire(
                    self.$t('message.url_prospect.swal_err'),
                    self.$t('message.url_prospect.swal_err_send'),
                    'error'
                )
                this.allowSending = true;
            }
        },

        async doUpdateMultipleStatus(is_sending, id) {
            let self = this;
            await this.$store.dispatch('actionUpdateMultipleStatus', {
                id : is_sending ? id : this.checkIds,
                seller : this.updateStatus.seller,
                status : is_sending ? 50 : this.updateStatus.status,
            });

            if (this.messageForms.action == 'updated') {
                let element = this.$refs.modalMultipleStatus
                $(element).modal('hide')

                // this.getExtList();

                if (is_sending) {
                    let obj = this.listExt.data.findIndex(o => o.id === id);
                    this.listExt.data[obj].status = 50;
                } else {
                    for (let index in this.checkIds) {
                        let id2 = this.checkIds[index].id
                        let obj = this.listExt.data.findIndex(o => o.id === id2);
                        this.listExt.data[obj].status = this.updateStatus.status;
                    }
                }

                this.checkIds = []
                this.updateStatus.seller = '';
                this.updateStatus.status = '';
                let message = is_sending
                    ? self.$t('message.url_prospect.swal_email_sent')
                    : self.$t('message.url_prospect.swal_successfully_updated')

                swal.fire(
                    self.$t('message.url_prospect.swal_done'),
                    message,
                    'success'
                )
            }

            this.checkIds = []

            this.getExtList({
                params : this.filterModel
            });
        },

        // async doSendEmail(ext, event) {
        //     console.log(event);
        //     this.$store.dispatch('clearMessageForm');
        //     var ids = '';

        //     if(ext != null) {
        //         if (ext === -1) {
        //             ids = this.listExt.data.map(item => item.id).join(",");
        //             var ctemp = -1;
        //             if (this.listExt.data.some(item => {
        //                 if (ctemp === -1) ctemp = item.country;
        //                 return item.country.id !== ctemp.id;
        //             })) {
        //                 alert("can't not handle with multiple countries");
        //                 return;
        //             }
        //             if (this.listExt.data.some(item => {
        //                 return (item.status != 30 && item.status != 40);
        //             })) {
        //                 alert("can't not handle with external domain not have contacts or was contacted");
        //                 return;
        //             }
        //             if (this.listExt.data.some(item => {
        //                 return (item.email === '' || item.email.split('|').length > 1);
        //             })) {
        //                 alert("can't not handle with external domain not have email or multiple emails");
        //                 return;
        //             }
        //             this.mailInfo.ids = ids;
        //             this.mailInfo.receiver_text = " all list";
        //             this.mailInfo.country = ctemp;
        //             this.fetchTemplateMail(this.mailInfo.country.id);
        //             this.openModalEmailElem();
        //             return;
        //         }
        //         if (ext.status != 30 && ext.status != 40) {
        //             alert("can't not handle with external domain not have contacts or was contacted");
        //             return;
        //         }
        //         if (ext.email === '' || ext.email.split('|').length > 1) {
        //             alert("can't not handle with external domain not have email or multiple emails");
        //             return;
        //         }

        //         this.mailInfo.ids = ext.id;
        //         this.mailInfo.receiver_text = ext.domain;
        //         this.mailInfo.country = ext.country;
        //         this.fetchTemplateMail(this.mailInfo.country.id);
        //         this.openModalEmailElem();
        //     }

        //     if(ext == null) {
        //         var ext_id = [];
        //         var ext_domain = [];
        //         var ext_country = [];

        //         var i =0;
        //         this.checkIds.forEach(function(entry) {
        //             if (entry.status != 30 && entry.status != 40) {
        //                 alert("can't not handle with external domain not have contacts or was contacted");
        //                 return;
        //             }
        //             if (entry.email === '' || entry.email.split('|').length > 1) {
        //                 alert("can't not handle with external domain not have email or multiple emails");
        //                 return;
        //             }

        //             ext_id[i] = entry.id;
        //             ext_domain[i] = entry.domain;
        //             ext_country[i] = entry.country;

        //             i++;
        //         });

        //         ext_id = ext_id.join(", ");
        //         ext_domain = ext_domain.join(", ");

        //         this.mailInfo.ids = ext_id;
        //         this.mailInfo.receiver_text = ext_domain;
        //         this.mailInfo.country =ext_country[0];
        //         this.fetchTemplateMail(this.mailInfo.country.id);
        //         this.openModalEmailElem();
        //     }
        // },

        async doSendEmailIndex(index) {
            let extDomain = this.listExt.data[index];
            this.doSendEmail(extDomain);
        },

        async fetchTemplateMail(countryId) {
            this.isPopupLoading = true;
            await this.$store.dispatch('getListMails', {params : {country_id : countryId, full_page : 1}});
            this.isPopupLoading = false;
        },

        async doChangeEmailTemplate() {
            let that = this;
            this.modelMail = this.listMailTemplate.data.filter(item => item.id === that.mailInfo.tpl)[0];
            this.modelMail.content = this.convertLineBreaks(this.modelMail.content)
        },

        convertLineBreaks(str) {
            return str.replace(/(?:\r\n|\r|\n)/g, '<br />');
        },

        doChangeEmailCountry() {
            let that = this, data = {};
            let list = this.listLanguages.data
            // this.mailInfo.country = this.listLanguages.data.filter(item => item.id === that.mailInfo.country.id)[0];

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

        objectToArray(ob) {
            let arr = [];
            Object.keys(ob).forEach((key) => {
                ob[key]['id'] = key;
                arr.push(ob[key]);
            });

            return _.sortBy(arr, 'text');
        },

        objectToArrayWithCustomSort(ob) {
            let sort_array = [
                '0',
                '10',
                '20',
                '30',
                '110',
                '50',
                '120',
                '70',
                '100',
                '60',
                '90',
                '55'
            ];
            let arr = [];

            sort_array.forEach((sort) => {
                Object.keys(ob).forEach((key) => {
                    if (sort === key) {
                        ob[key]['id'] = key;
                        arr.push(ob[key]);
                    }
                });
            })

            return arr;
        },

        clearSearch() {
            this.filterModel = {
                id : 0,
                id_temp : 0,
                country_id : 0,
                country_id_temp : '',
                countryList : {data : [], total : 0},
                domain : '',
                domain_temp : '',
                email : '',
                alexa_rank_from : '',
                alexa_rank_to : '',
                status : -1,
                status_temp : null,
                page : 0,
                per_page : 50,
                employee_id : '',
                required_email_temp : 0,
                required_email : 0,
                sort_key : 'id',
                sort_value : 'desc',
                alexa_date_upload : {
                    startDate : null,
                    endDate : null
                },
                domain_zone : '',
                adv_sort: '',
            };

            this.getExtList({
                params : this.filterModel
            });

            this.getExtListTotals()

            this.$refs.sortComponent.resetSort();

            this.$router.replace({'query' : null});
        },

        // async submitSendMail() {
        //     this.allowSending = false;
        //     this.isPopupLoading = true;
        //     await this.$store.dispatch('sendMail', { ids: this.mailInfo.ids, mail_name: this.modelMail.mail_name, title: this.modelMail.title, content: this.modelMail.content });
        //     this.isPopupLoading = false;
        // }

        getSellerTeamInCharge(list) {
            return this.extUpdate.user_id === null
                ? list
                : list.filter(el => el.team_in_charge === this.extUpdate.user_id);
        },

        checkEmailValidationError(error) {
            let obj = error;
            return Object.keys(obj)
                .filter(function (er) {
                    return er.includes('email.');
                })
                .map(function (key) {
                    return obj[key];
                })
                .flat();
        },

        downloadTemplate() {
            let headers = [
                [
                    'Domain',
                    'Status',
                    'Country',
                    'Email'
                ]
            ];

            this.downloadCsvTemplate(headers, 'url_prospect_csv_template');
        }
    },
}
</script>
