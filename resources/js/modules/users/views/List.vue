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
                        <h3 class="card-title text-primary">{{ $t('message.teams.filter_title') }}</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">{{ $t('message.teams.filter_search_username') }}</label>
                                    <input type="text"
                                           v-model="filterModel.name_temp"
                                           class="form-control pull-right"
                                           :placeholder="$t('message.teams.filter_search_username')">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">{{ $t('message.teams.filter_email') }}</label>
                                    <input type="text"
                                           v-model="filterModel.email_temp"
                                           class="form-control pull-right"
                                           :placeholder="$t('message.teams.filter_search_email')">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">{{ $t('message.teams.filter_role') }}</label>
                                    <select name="" class="form-control" v-model="filterModel.role">
                                        <option value="">{{ $t('message.teams.all') }}</option>
                                        <option v-for="option in roleList" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">{{ $t('message.teams.filter_status') }}</label>
                                    <select name="" class="form-control" v-model="filterModel.status">
                                        <option value="">{{ $t('message.teams.all') }}</option>
                                        <option value="active">{{ $t('message.teams.active') }}</option>
                                        <option value="inactive">{{ $t('message.teams.inactive') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">{{ $t('message.teams.filter_type') }}</label>
                                    <select name="" class="form-control" v-model="filterModel.type">
                                        <option value="">{{ $t('message.teams.all') }}</option>
                                        <option v-for="(option, key) in userTypeList" v-bind:value="key">
                                            {{ option }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="row my-3">
                            <div class="col-md-2">
                                <button class="btn btn-default" @click="clearSearch">
                                    {{ $t('message.teams.clear') }}
                                </button>
                                <button class="btn btn-default" @click="doSearchList">
                                    {{ $t('message.teams.search') }}
                                    <i class="fa fa-refresh fa-spin" v-if="isSearchLoading"></i></button>
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
                        <h3 class="card-title text-primary">{{ $t('message.teams.t_title') }}</h3>
                        <div class="card-tools">
                            <button @click="doAddUser"
                                    data-toggle="modal"
                                    data-target="#modal-add"
                                    class="btn btn-success btn-tool"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="input-group input-group-sm float-right" style="width: 65px">
                            <select @change="doSearchList"
                                    class="form-control pull-right"
                                    v-model="filterModel.per_page"
                                    style="height: 37px;">
                                <option v-for="value in listPageOptions" :value="value">{{ value }}</option>
                            </select>
                        </div>

                        <div class="table-responsive">
                            <table id="tbl-users" class="table table-hover table-bordered table-striped rlink-table">
                                <thead>
                                <tr class="label-primary">
                                    <th>#</th>
                                    <th>{{ $t('message.teams.t_username') }}</th>
                                    <th>{{ $t('message.teams.t_email') }}</th>
                                    <th>{{ $t('message.teams.t_work_mail') }}</th>
                                    <th>{{ $t('message.teams.t_skype') }}</th>
                                    <th>{{ $t('message.teams.filter_role') }}</th>
                                    <th>{{ $t('message.teams.filter_status') }}</th>
                                    <th>{{ $t('message.teams.filter_type') }}</th>
                                    <th>{{ $t('message.teams.t_action') }}</th>
                                </tr>

                                <!-- <tr>
                                    <td style="max-width: 30px;">
                                        Filter
                                    </td>

                                    <td style="max-width: 30px;">
                                        <div class="input-group input-group-sm">
                                            <input type="text" v-model="filterModel.name_temp"  class="form-control pull-right" placeholder="Search Name">
                                        </div>
                                    </td>

                                    <td style="max-width: 30px;">
                                        <div class="input-group input-group-sm">
                                            <input type="text" v-model="filterModel.email_temp"  class="form-control pull-right" placeholder="Search Email">
                                        </div>
                                    </td>

                                    <td style="max-width: 30px;">
                                        <div class="input-group input-group-sm">
                                            <input type="text" v-model="filterModel.work_mail_temp"  class="form-control pull-right" placeholder="Search Work Mail">
                                        </div>
                                    </td>

                                    <td style="max-width: 30px;">
                                        <div class="input-group input-group-sm">
                                            <input type="text" v-model="filterModel.phone_temp"  class="form-control pull-right" placeholder="Search Phone">
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <div class="btn-group">
                                            <button @click="doSearchList" type="submit" title="Filter" class="btn btn-default"><i class="fa fa-fw fa-search"></i></button>
                                        </div>
                                    </td>


                                </tr> -->
                                </thead>

                                <tbody>
                                <tr v-for="(user, index) in listUser.data" :key="index">
                                    <td class="center-content">{{ index + 1 }}</td>
                                    <td>{{ user.username == null ? user.name : user.username }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>{{ user.work_mail }}</td>
                                    <td>{{ user.skype }}</td>
                                    <td>{{ user.role ? user.role.name : '' }}</td>
                                    <td>{{ user.status }}</td>
                                    <td>{{ userTypeList[user.type] }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button
                                                data-toggle="modal"
                                                data-target="#modal-update"
                                                class="btn btn-default"
                                                :title="$t('message.teams.t_edit_user')"

                                                @click="doUpdateUser(user)">

                                                <i class="fa fa-fw fa-edit"></i>
                                            </button>
                                            <!-- <button class="btn btn-default" @click="doUpdatePermission(user)" data-toggle="modal" data-target="#modal-permission" title="Edit Country IntDomain"><i class="fa fa-fw fa-id-card"></i></button> -->
                                            <button
                                                data-toggle="modal"
                                                data-target="#modal-permission-ext"
                                                class="btn btn-default"
                                                :title="$t('message.teams.t_edit_country')"

                                                @click="doUpdatePermissionExt(user)">

                                                <i class="fas fa-eye-dropper"></i>
                                            </button>
                                            <!-- <button class="btn btn-default" @click="doUpdateInternalPermission(user)" data-toggle="modal" data-target="#modal-int-permission" title="Edit Permission IntDomain"><i class="fa fa-fw fa-anchor"></i></button> -->
                                            <router-link
                                                class="btn btn-default"
                                                title="View detail"
                                                :to="{ path: `/profile/${user.id}` }">
                                                <i class="fa fa-fw fa-eye"></i>
                                            </router-link>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="overlay" v-if="isLoadingTable">
                            <i class="fa fa-refresh fa-spin"></i>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="paging_simple_numbers">
                            <pagination :data="listUser" @pagination-change-page="goToPage"></pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--    Modal Add-->
        <div class="modal fade" id="modal-add" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('message.teams.ae_title') }}</h4>
                    </div>
                    <div class="modal-body relative">
                        <form class="row" action="" autocomplete="off">
                            <input autocomplete="off" name="hidden" type="text" style="display:none;">
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.username}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.teams.t_username') }}</label>
                                    <input type="text"
                                           v-model="userModel.username"
                                           class="form-control"
                                           value=""
                                           required="required"
                                           :placeholder="$t('message.teams.ae_enter_username')">
                                    <span v-if="messageForms.errors.username"
                                          v-for="err in messageForms.errors.username"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.name}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.teams.ae_name') }}</label>
                                    <input type="text"
                                           v-model="userModel.name"
                                           class="form-control"
                                           value=""
                                           required="required"
                                           :placeholder="$t('message.teams.ae_enter_name')">
                                    <span v-if="messageForms.errors.name"
                                          v-for="err in messageForms.errors.name"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.email}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.teams.t_email') }}</label>
                                    <input type="text"
                                           v-model="userModel.email"
                                           class="form-control"
                                           value=""
                                           required="required"
                                           :placeholder="$t('message.teams.ae_enter_email')">
                                    <span v-if="messageForms.errors.email"
                                          v-for="err in messageForms.errors.email"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <!--                            <div class="col-md-6">-->
                            <!--                                <div :class="{'form-group': true, 'has-error': messageForms.errors.phone}" class="form-group">-->
                            <!--                                    <label style="color: #333">Phone</label>-->
                            <!--                                    <input type="text" v-model="userModel.phone" class="form-control" value="" required="required" placeholder="Enter Phone">-->
                            <!--                                    <span v-if="messageForms.errors.phone" v-for="err in messageForms.errors.phone" class="text-danger">{{ err }}</span>-->
                            <!--                                </div>-->
                            <!--                            </div>-->

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.skype}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.teams.t_skype') }}</label>
                                    <input type="text"
                                           v-model="userModel.skype"
                                           class="form-control"
                                           value=""
                                           required="required"
                                           :placeholder="$t('message.teams.ae_enter_skype')">
                                    <span v-if="messageForms.errors.skype"
                                          v-for="err in messageForms.errors.skype"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.password}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.teams.ae_password') }}</label>
                                    <input autocomplete="off"
                                           type="password"
                                           v-model="userModel.password"
                                           class="form-control"
                                           value=""
                                           required="required"
                                           :placeholder="$t('message.teams.ae_enter_password')">
                                    <span v-if="messageForms.errors.password"
                                          v-for="err in messageForms.errors.password"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.c_password}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.teams.ae_confirm_password') }}</label>
                                    <input autocomplete="off"
                                           type="password"
                                           v-model="userModel.c_password"
                                           class="form-control"
                                           value=""
                                           required="required"
                                           :placeholder="$t('message.teams.ae_confirm_password')">
                                    <span v-if="messageForms.errors.c_password"
                                          v-for="err in messageForms.errors.c_password"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.role_id}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.teams.filter_role') }}</label>
                                    <div>
                                        <select v-model="userModel.role_id" class="form-control pull-right">
                                            <option value="0">{{ $t('message.teams.ae_select_role') }}</option>
                                            <option v-for="option in roleList" v-bind:value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <span v-if="messageForms.errors.role_id"
                                          v-for="err in messageForms.errors.role_id"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.type}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.teams.filter_type') }}</label>
                                    <div>
                                        <select v-model="userModel.type" class="form-control pull-right">
                                            <option v-for="(option, key) in userTypeList" v-bind:value="key">
                                                {{ option }}
                                            </option>
                                        </select>
                                    </div>
                                    <span v-if="messageForms.errors.type"
                                          v-for="err in messageForms.errors.type"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <hr>
                            <div class="col-md-12" style="margin-top: 15px">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.work_mail}"
                                             class="form-group">
                                            <label style="color: #333">{{ $t('message.teams.t_work_mail') }}</label>
                                            <input type="text"
                                                   v-model="userModel.work_mail"
                                                   class="form-control"
                                                   value=""
                                                   required="required"
                                                   :placeholder="$t('message.teams.ae_enter_work_mail')">
                                            <span v-if="messageForms.errors.work_mail"
                                                  v-for="err in messageForms.errors.work_mail"
                                                  class="text-danger">{{ err }}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.work_mail_pass}"
                                             class="form-group">
                                            <label style="color: #333">{{ $t('message.teams.ae_work_mail_pass') }}</label>
                                            <input type="text"
                                                   v-model="userModel.work_mail_pass"
                                                   class="form-control"
                                                   value=""
                                                   required="required"
                                                   :placeholder="$t('message.teams.ae_enter_work_mail_pass')">
                                            <span v-if="messageForms.errors.work_mail_pass"
                                                  v-for="err in messageForms.errors.work_mail_pass"
                                                  class="text-danger">{{ err }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            {{ $t('message.teams.close') }}
                        </button>
                        <button type="button" @click="submitAdd" class="btn btn-primary">
                            {{ $t('message.teams.save') }}
                        </button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--    End Modal Add-->

        <!--    Modal Update-->
        <div class="modal fade" id="modal-update" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('message.teams.us_title') }} <b>{{ userUpdate.name }}</b></h4>
                    </div>
                    <div class="modal-body relative">
                        <form class="row" action="" autocomplete="off">
                            <input autocomplete="off" name="hidden" type="text" style="display:none;">
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.username}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.teams.t_username') }}</label>
                                    <input type="text"
                                           v-model="userUpdate.username"
                                           class="form-control"
                                           value=""
                                           required="required"
                                           :placeholder="$t('message.teams.ae_enter_username')">
                                    <span v-if="messageForms.errors.username"
                                          v-for="err in messageForms.errors.username"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.name}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.teams.ae_name') }}</label>
                                    <input type="text"
                                           v-model="userUpdate.name"
                                           class="form-control"
                                           value=""
                                           required="required"
                                           :placeholder="$t('message.teams.ae_enter_name')">
                                    <span v-if="messageForms.errors.name"
                                          v-for="err in messageForms.errors.name"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.email}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.teams.t_email') }}</label>
                                    <input type="text"
                                           v-model="userUpdate.email"
                                           class="form-control"
                                           value=""
                                           required="required"
                                           :placeholder="$t('message.teams.ae_enter_email')">
                                    <span v-if="messageForms.errors.email"
                                          v-for="err in messageForms.errors.email"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <!--                            <div class="col-md-6">-->
                            <!--                                <div :class="{'form-group': true, 'has-error': messageForms.errors.phone}" class="form-group">-->
                            <!--                                    <label style="color: #333">Phone</label>-->
                            <!--                                    <input autocomplete="off" type="text" v-model="userUpdate.phone" class="form-control" value="" required="required" placeholder="Enter Phone">-->
                            <!--                                    <span v-if="messageForms.errors.phone" v-for="err in messageForms.errors.phone" class="text-danger">{{ err }}</span>-->
                            <!--                                </div>-->
                            <!--                            </div>-->

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.skype}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.teams.t_skype') }}</label>
                                    <input autocomplete="off"
                                           type="text"
                                           v-model="userUpdate.skype"
                                           class="form-control"
                                           value=""
                                           required="required"
                                           :placeholder="$t('message.teams.ae_enter_skype')">
                                    <span
                                        v-if="messageForms.errors.skype"
                                        class="text-danger">
                                        {{ $t('message.teams.us_skype_error') }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.password}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.teams.us_new_password') }}</label>
                                    <input autocomplete="off"
                                           type="password"
                                           v-model="userUpdate.password"
                                           class="form-control"
                                           value=""
                                           required="required"
                                           :placeholder="$t('message.teams.ae_enter_password')">
                                    <span v-if="messageForms.errors.password"
                                          v-for="err in messageForms.errors.password"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.c_password}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.teams.us_confirm_new_password') }}</label>
                                    <input type="password"
                                           v-model="userUpdate.c_password"
                                           class="form-control"
                                           value=""
                                           required="required"
                                           :placeholder="$t('message.teams.ae_confirm_password')">
                                    <span v-if="messageForms.errors.c_password"
                                          v-for="err in messageForms.errors.c_password"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.role_id}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.teams.filter_role') }}</label>
                                    <div>
                                        <select v-model="userUpdate.role_id" class="form-control pull-right">
                                            <option value="0">{{ $t('message.teams.ae_select_role') }}</option>
                                            <option v-for="option in roleList" v-bind:value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <span v-if="messageForms.errors.role_id"
                                          v-for="err in messageForms.errors.role_id"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.type}"
                                     class="form-group">
                                    <label style="color: #333">{{ $t('message.teams.filter_type') }}</label>
                                    <div>
                                        <select v-model="userUpdate.type" class="form-control pull-right">
                                            <option v-for="(option, key) in userTypeList" v-bind:value="key">
                                                {{ option }}
                                            </option>
                                        </select>
                                    </div>
                                    <span v-if="messageForms.errors.type"
                                          v-for="err in messageForms.errors.type"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.teams.us_work_host_mail') }}</label>
                                    <input type="text"
                                           v-model="userUpdate.host_mail"
                                           class="form-control"
                                           required
                                           :placeholder="$t('message.teams.us_enter_smtp')">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.work_mail}"
                                             class="form-group">
                                            <label style="color: #333">{{ $t('message.teams.t_work_mail') }}</label>
                                            <input type="text"
                                                   v-model="userUpdate.work_mail"
                                                   class="form-control"
                                                   value=""
                                                   required="required"
                                                   :placeholder="$t('message.teams.ae_enter_work_mail')">
                                            <span v-if="messageForms.errors.work_mail"
                                                  v-for="err in messageForms.errors.work_mail"
                                                  class="text-danger">{{ err }}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.work_mail_pass}"
                                             class="form-group">
                                            <label style="color: #333">{{ $t('message.teams.ae_work_mail_pass') }}</label>
                                            <input type="text"
                                                   v-model="userUpdate.work_mail_pass"
                                                   class="form-control"
                                                   value=""
                                                   required="required"
                                                   :placeholder="$t('message.teams.ae_enter_work_mail_pass')">
                                            <span v-if="messageForms.errors.work_mail_pass"
                                                  v-for="err in messageForms.errors.work_mail_pass"
                                                  class="text-danger">{{ err }}</span>
                                        </div>
                                    </div>

                                    <!--                                    <div class="col-md-6">-->
                                    <!--                                        <div class="form-group">-->
                                    <!--                                            <label style="color: #333">Payment Type</label>-->
                                    <!--                                            <select v-model="userUpdate.id_payment_type" class="form-control pull-right">-->
                                    <!--                                                <option value="">&#45;&#45; Select Payment Type &#45;&#45;</option>-->
                                    <!--                                                <option v-for="option in listPayment.data" v-bind:value="option.id">-->
                                    <!--                                                    {{ option.type }}-->
                                    <!--                                                </option>-->
                                    <!--                                            </select>-->
                                    <!--                                        </div>-->
                                    <!--                                    </div>-->

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label style="color: #333">{{ $t('message.teams.filter_status') }}</label>
                                            <select name="" id="" class="form-control" v-model="userUpdate.status">
                                                <option value="active">{{ $t('message.teams.active') }}</option>
                                                <option value="inactive">{{ $t('message.teams.inactive') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!--                                    <div class="col-md-6">-->
                                    <!--                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.credit_auth}" class="form-group">-->
                                    <!--                                            <label style="color: #333">Credit Authorization</label>-->
                                    <!--                                            <select name="" class="form-control" v-model="userUpdate.credit_auth">-->
                                    <!--                                                <option value=""></option>-->
                                    <!--                                                <option value="Yes">Yes</option>-->
                                    <!--                                                <option value="No">No</option>-->
                                    <!--                                            </select>-->
                                    <!--                                            <span v-if="messageForms.errors.credit_auth" v-for="err in messageForms.errors.credit_auth" class="text-danger">{{ err }}</span>-->
                                    <!--                                        </div>-->
                                    <!--                                    </div>-->

                                </div>
                            </div>
                        </form>
                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            {{ $t('message.teams.close') }}
                        </button>
                        <button type="button" @click="submitUpdate" class="btn btn-primary">
                            {{ $t('message.teams.save') }}
                        </button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--    End Modal Update -->

        <!--    Modal Update-->
        <div class="modal fade" id="modal-permission" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{ $t('message.teams.scid_title') }} <b>{{ userCountryUpdate.name }}</b>
                            ({{ listCountryUpdate.total }})
                        </h4>
                    </div>
                    <div class="modal-body relative">
                        <div class="row">
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.country_id}">
                                    <div>
                                        <select v-model="countryListAddId" class="form-control pull-right">
                                            <option value="0">{{ $t('message.teams.scid_select_country') }}</option>
                                            <option v-for="option in countryList.data" v-bind:value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <span v-if="messageForms.errors.country_id"
                                          v-for="err in messageForms.errors.country_id"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <button class="btn btn-success" @click="doAddCountryForUser">
                                        {{ $t('message.teams.add') }}
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered table-striped rlink-table">
                                        <tbody>
                                        <tr class="label-primary">
                                            <th>#</th>
                                            <th>{{ $t('message.teams.scid_country') }}</th>
                                            <th>{{ $t('message.teams.scid_code') }}</th>
                                            <th>{{ $t('message.teams.t_action') }}</th>
                                        </tr>

                                        <tr v-for="(item, index) in listCountryUpdate.data" :key="index">
                                            <td> {{ index }}</td>
                                            <td> {{ item.name }}</td>
                                            <td> {{ item.code }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button
                                                        title="Remove"
                                                        class="btn btn-default"
                                                        @click="doRemovePermission(item)">

                                                        <i class="fa fa-fw fa-times"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            {{ $t('message.teams.close') }}
                        </button>
                        <button type="button" @click="submitUpdatePermission" class="btn btn-primary">
                            {{ $t('message.teams.save') }}
                        </button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--    End Modal Update -->

        <!--    Modal Update-->
        <div class="modal fade" id="modal-permission-ext" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{ $t('message.teams.sced_title') }} <b>{{ userCountryUpdate.name }}</b>
                            ({{ listCountryUpdate.total }})
                        </h4>
                    </div>
                    <div class="modal-body relative">
                        <div class="row">
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.country_id}">
                                    <div>
                                        <select v-model="countryListAddId" class="form-control pull-right">
                                            <option value="0">{{ $t('message.teams.scid_select_country') }}</option>
                                            <option v-for="option in countryList.data" v-bind:value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <span v-if="messageForms.errors.country_id"
                                          v-for="err in messageForms.errors.country_id"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <button class="btn btn-success" @click="doAddCountryForUser">
                                        {{ $t('message.teams.add') }}
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered table-striped rlink-table">
                                        <tbody>
                                        <tr class="label-primary">
                                            <th>#</th>
                                            <th>{{ $t('message.teams.scid_country') }}</th>
                                            <th>{{ $t('message.teams.scid_code') }}</th>
                                            <th>{{ $t('message.teams.t_action') }}</th>
                                        </tr>

                                        <tr v-for="(item, index) in listCountryUpdate.data" :key="index">
                                            <td> {{ index }}</td>
                                            <td> {{ item.name }}</td>
                                            <td> {{ item.code }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button
                                                        title="Remove"
                                                        class="btn btn-default"
                                                        @click="doRemovePermission(item)">

                                                        <i class="fa fa-fw fa-times"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            {{ $t('message.teams.close') }}
                        </button>
                        <button type="button" @click="submitUpdateExtPermission" class="btn btn-primary">
                            {{ $t('message.teams.save') }}
                        </button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--    End Modal Update -->

        <!--    Modal Int Update-->
        <div class="modal fade" id="modal-int-permission" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{ $t('message.teams.sid_title') }} <b>{{ userIntUpdate.name }}</b>
                            ({{ listIntUpdate.total }})
                        </h4>
                    </div>
                    <div class="modal-body relative">
                        <div class="row">
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.country_id}">
                                    <div>
                                        <select v-model="countryIdForInt"
                                                @change="doUpdateListIntSelect"
                                                class="form-control pull-right">
                                            <option value="0">{{ $t('message.teams.scid_select_country') }}</option>
                                            <option v-for="option in countryList.data" v-bind:value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <span v-if="messageForms.errors.country_id"
                                          v-for="err in messageForms.errors.country_id"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-12" style="margin-top: 15px;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.country_id}">
                                            <div>
                                                <select v-model="intListAddId" class="form-control pull-right">
                                                    <option value="0">{{ $t('message.teams.sid_select_int_domain') }}</option>
                                                    <option v-for="option in listIntSelect.data"
                                                            v-bind:value="option.id">
                                                        {{ option.domain }}
                                                    </option>
                                                </select>
                                            </div>
                                            <span v-if="messageForms.errors.int_domain_id"
                                                  v-for="err in messageForms.errors.int_domain_id"
                                                  class="text-danger">{{ err }}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button class="btn btn-success" @click="doAddIntForUser">
                                                {{ $t('message.teams.add') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered table-striped rlink-table">
                                        <thead>
                                        <tr class="label-primary">
                                            <th>#</th>
                                            <th>{{ $t('message.teams.scid_country') }}</th>
                                            <th>{{ $t('message.teams.sid_domain') }}</th>
                                            <th>{{ $t('message.teams.sid_hosting_provider') }}</th>
                                            <th>{{ $t('message.teams.sid_domain_provider') }}</th>
                                            <th>{{ $t('message.teams.sid_user') }}</th>
                                            <th>{{ $t('message.teams.t_action') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(item, index) in listIntUpdate.data" :key="index">
                                            <td class="center-content">{{ index + 1 }}</td>
                                            <td>{{ item.country.name }}</td>
                                            <td><a :href="'http://' + item.domain" target="_blank">{{ item.domain }}</a>
                                            </td>
                                            <td><a href="#">{{ item.hosting_provider.name }}</a></td>
                                            <td><a href="#">{{ item.domain_provider.name }}</a></td>
                                            <td>{{ item.user.name }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button @click="doRemoveIntPermission(item)"
                                                            class="btn btn-default"
                                                            title="Remove"><i class="fa fa-fw fa-times"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            {{ $t('message.teams.close') }}
                        </button>
                        <button type="button" @click="submitUpdateIntPermission" class="btn btn-primary">
                            {{ $t('message.teams.save') }}
                        </button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--    End Modal Int Update -->
    </div>
</template>

<script>
import {mapState} from 'vuex';

export default {
    name : 'UserList',
    data() {
        return {
            filterModel : {
                type : this.$route.query.type || '',
                role : this.$route.query.role || '',
                status : this.$route.query.status || '',
                email : this.$route.query.email || '',
                email_temp : this.$route.query.email_temp || '',
                work_mail_temp : this.$route.query.work_mail_temp || '',
                work_mail : this.$route.query.work_mail || '',
                name : this.$route.query.name || '',
                name_temp : this.$route.query.name_temp || '',
                phone : this.$route.query.phone || '',
                skype : this.$route.query.skype || '',
                phone_temp : this.$route.query.phone_temp || '',
                page : this.$route.query.page || 0,
                per_page : this.$route.query.per_page || 10,
            },

            userModel : {
                name : '',
                email : '',
                // phone: '',
                skype : '',
                password : '',
                c_password : '',
                role_id : '',
                type : 0,
                work_mail_pass : '',
                work_mail : ''
            },

            userUpdate : {
                id : '',
                username : '',
                name : '',
                email : '',
                // phone: '',
                skype : '',
                password : '',
                c_password : '',
                role_id : '',
                type : 0,
                work_mail_pass : '',
                work_mail : '',
                host_mail : '',
                status : '',
                id_payment_type : '',
                credit_auth : '',
            },

            userCountryUpdate : {id : 0},
            userIntUpdate : {id : 0},
            countryListAddId : 0,
            intListAddId : 0,
            countryIdForInt : 0,
            listPageOptions : [
                10,
                25,
                50,
                100,
                'All'
            ],
            isLoadingTable : false,
            isPopupLoading : false,
            isSearchLoading : false,
        };
    },

    async created() {
        await this.$store.dispatch('actionCheckAdminCurrentUser', {vue : this});

        if (!this.user.isAdmin) {
            window.location.href = '/';
            return;
        }

        this.getUserList({params : this.filterModel});
        this.getCountryList();
        this.getRoleList();
        this.getUserTypeList();
        this.getPaymentList();
    },

    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,
            listUser : state => state.storeUser.listUser,
            listPayment : state => state.storeUser.listPayment,
            messageForms : state => state.storeUser.messageForms,
            filter : state => state.storeUser.filter,
            countryList : state => state.storeUser.countryList,
            userTypeList : state => state.storeUser.userTypeList,
            roleList : state => state.storeUser.roleList,
            listCountryUpdate : state => state.storeUser.listCountryUpdate,
            listIntUpdate : state => state.storeUser.listIntUpdate,
            listIntSelect : state => state.storeUser.listIntSelect,
        }),
    },

    methods : {

        clearSearch() {
            this.filterModel = {
                status : '',
                role : '',
                type : '',
                name_temp : '',
                email_temp : '',
                per_page : '10',
            }

            this.getUserList({
                params : this.filterModel
            });

            this.$router.replace({'query' : null});
        },

        async getCountryList() {
            await this.$store.dispatch('actionGetListCountry', {vue : this});
        },

        async getRoleList() {
            await this.$store.dispatch('actionGetListRole', {vue : this});
        },

        async getPaymentList() {
            await this.$store.dispatch('actionGetListPayment', {vue : this});
        },

        async getUserTypeList() {
            await this.$store.dispatch('actionGetListUserType', {vue : this});
        },

        async getUserList(filterParams) {
            $('#tbl-users').DataTable().destroy();

            this.isLoadingTable = true;
            this.isSearchLoading = true;
            await this.$store.dispatch('actionGetUser', {
                vue : this,
                params : filterParams,
                showMainLoading : false
            });
            this.isLoadingTable = false;
            this.isSearchLoading = false;

            $('#tbl-users').DataTable({
                paging : false,
                searching : false,
                columnDefs : [
                    {orderable : true, targets : 0},
                    {orderable : true, targets : 1},
                    {orderable : true, targets : 3},
                    {orderable : true, targets : 4},
                    {orderable : true, targets : 5},
                    {orderable : true, targets : 6},
                    {orderable : true, targets : 7},
                    {orderable : false, targets : '_all'}
                ]
            });
        },

        async goToPage(pageNum) {
            var that = this;
            this.filterModel.page = pageNum;
            this.$router.push({
                query : that.filterModel,
            });

            await this.getUserList({
                params : that.filterModel
            });
        },

        doAddUser() {
            this.$store.dispatch('clearMessageFormUser');
        },

        doUpdateUser(user) {
            console.log(user)
            this.$store.dispatch('clearMessageFormUser');
            this.userUpdate = JSON.parse(JSON.stringify(user))
            this.userUpdate.password = '';
            this.userUpdate.c_password = '';
        },

        clearUserModel() {
            this.userModel = {
                name : '',
                email : '',
                // phone: '',
                skype : '',
                password : '',
                c_password : '',
                role_id : '',
                type : 0,
            };
        },

        async submitAdd() {
            let that = this;
            this.isPopupLoading = true;
            await this.$store.dispatch('actionAddUser', that.userModel);
            this.isPopupLoading = false;

            if (this.messageForms.action === 'saved_user') {
                this.clearUserModel();
                this.getUserList({params : this.filterModel});

                swal.fire(
                    that.$t('message.teams.alert_success'),
                    that.$t('message.teams.alert_employee_added'),
                    'success'
                )
            }
        },

        async submitUpdate() {
            let self = this;
            this.isPopupLoading = true;
            this.userUpdate.isOurs = this.user.isOurs;
            await this.$store.dispatch('actionUpdateUser', this.userUpdate);
            this.isPopupLoading = false;

            if (this.messageForms.action === 'updated_user') {
                // for (var index in this.listUser.data) {
                //     if (this.listUser.data[index].id === this.userUpdate.id) {
                //         this.listUser.data[index] = this.userUpdate;
                //         break;
                //     }
                // }

                this.getUserList()

                swal.fire(
                    self.$t('message.teams.alert_updated'),
                    self.$t('message.teams.alert_info_updated'),
                    'success'
                )

            }
        },

        async doSearchList() {
            let that = this;
            that.filterModel.name = that.filterModel.name_temp;
            that.filterModel.email = that.filterModel.email_temp;
            that.filterModel.phone = that.filterModel.phone_temp;
            that.filterModel.work_mail = that.filterModel.work_mail_temp;
            this.goToPage(0);
        },

        async doUpdatePermission(user) {
            this.userCountryUpdate = JSON.parse(JSON.stringify(user))
            this.isPopupLoading = true;
            await this.$store.dispatch('actionGetListCountryId', {vue : this, params : {user_id : user.id}});
            this.isPopupLoading = false;
        },

        async doUpdatePermissionExt(user) {
            this.userCountryUpdate = JSON.parse(JSON.stringify(user))
            this.isPopupLoading = true;
            await this.$store.dispatch('actionGetListCountryId', {
                vue : this,
                params : {user_id : user.id, for_ext : 1, for_assign : 1}
            });
            this.isPopupLoading = false;
        },

        async doUpdateInternalPermission(user) {
            this.userIntUpdate = JSON.parse(JSON.stringify(user))
            this.isPopupLoading = true;
            await this.$store.dispatch('actionGetListInternalDomain', {vue : this, userId : user.id});
            this.isPopupLoading = false;
        },

        findCountryById(countryId, isListUpdate) {
            var result = {id : 0};
            var tempList = isListUpdate ? this.listCountryUpdate.data : this.countryList.data;

            tempList.forEach(function (val, index) {
                if (val.id === countryId) {
                    result = val;
                    return true;
                }
            });

            return result;
        },

        findIntDomainById(intId, isListUpdate) {
            var result = {id : 0};
            var tempList = isListUpdate ? this.listIntUpdate.data : this.listIntSelect.data;

            tempList.forEach(function (val, index) {
                if (val.id === intId) {
                    result = val;
                    return true;
                }
            });

            return result;
        },

        doAddCountryForUser() {
            let self = this;
            var cid = this.findCountryById(this.countryListAddId, true);
            if (cid.id !== 0) {
                alert(self.$t('message.teams.alert_item_added'));
                return;
            }

            var country = this.findCountryById(this.countryListAddId, false);
            if (country.id === 0) return;
            this.listCountryUpdate.data.unshift(country);
        },

        doAddIntForUser() {
            var cid = this.findIntDomainById(this.intListAddId, true);
            if (cid.id !== 0) {
                alert('item already added');
                return;
            }

            var intDomain = this.findIntDomainById(this.intListAddId, false);
            if (intDomain.id === 0) return;
            this.listIntUpdate.data.unshift(intDomain);
        },

        doRemovePermission(country) {
            let size = this.listCountryUpdate.data.length;
            for (var i = 0; i < size; i++) {
                if (this.listCountryUpdate.data[i].id === country.id) {
                    this.listCountryUpdate.data.splice(i, 1);
                }
            }
        },

        doRemoveIntPermission(intDomain) {
            let size = this.listIntUpdate.data.length;
            for (var i = 0; i < size; i++) {
                if (this.listIntUpdate.data[i].id === intDomain.id) {
                    this.listIntUpdate.data.splice(i, 1);
                }
            }
        },

        getListCountryUpdateId() {
            var arrId = [];
            let size = this.listCountryUpdate.data.length;
            for (var i = 0; i < size; i++) {
                arrId.push(this.listCountryUpdate.data[i].id);
            }

            return arrId;
        },

        getListIntUpdateId() {
            var arrId = [];
            let size = this.listIntUpdate.data.length;
            for (var i = 0; i < size; i++) {
                arrId.push(this.listIntUpdate.data[i].id);
            }

            return arrId;
        },

        async doUpdateListIntSelect() {
            this.isPopupLoading = true;
            await this.$store.dispatch('actionGetListInternalDomainSelect', {
                vue : this,
                countryId : this.countryIdForInt
            });
            this.isPopupLoading = false;
        },

        async submitUpdatePermission() {
            let that = this;
            this.isPopupLoading = true;
            await this.$store.dispatch('actionUpdateUserPermission', {
                id : that.userCountryUpdate.id,
                countries_id : that.getListCountryUpdateId()
            });
            this.isPopupLoading = false;

            if (this.messageForms.action === 'updated_permission_user') {

            }
        },

        async submitUpdateExtPermission() {
            let that = this;
            this.isPopupLoading = true;
            await this.$store.dispatch('actionUpdateUserPermission', {
                id : that.userCountryUpdate.id,
                countries_id : that.getListCountryUpdateId(),
                for_ext : 1
            });
            this.isPopupLoading = false;

            if (this.messageForms.action === 'updated_permission_user') {

            }
        },

        async submitUpdateIntPermission() {
            let that = this;
            this.isPopupLoading = true;
            await this.$store.dispatch('actionUpdateUserIntPermission', {
                id : that.userIntUpdate.id,
                int_domains_id : that.getListIntUpdateId()
            });
            this.isPopupLoading = false;

            if (this.messageForms.action === 'updated_int_permission_user') {

            }
        }
    }
}
</script>
