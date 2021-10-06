<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Admin Settings</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="col-sm-12">
                    <!--                        LANGUAGE LIST-->
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Language List</h3>
                            <div class="card-tools">
                                <button @click="clearMessageform" data-toggle="modal" data-target="#modal-add-language"
                                        class="btn btn-success float-right"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="card-body" style="height: 650px; overflow: scroll;">
                            <table class="table table-hover table-bordered table-striped rlink-table">
                                <thead>
                                <tr class="label-primary">
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(item, index) in langaugeList.data" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ item.name }}</td>
                                    <td>{{ item.code }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button @click="doEditlanguage(item)" data-toggle="modal"
                                                    data-target="#modal-update-language" type="submit" title="Edit"
                                                    class="btn btn-default"><i class="fa fa-fw fa-edit"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--                        END LANGUAGE LIST-->
                </div>

                <div class="col-sm-12">
                    <!--                        MAIL ACCESS-->
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Mail Access</h3>
                            <div class="card-tools">
                            </div>
                        </div>
                        <div class="card-body" style="height: 650px; overflow: scroll;">
                            <table class="table table-condensed table-hover table-striped table-bordered">
                                <thead>
                                <tr class="label-primary">
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(item, index) in emailAccessList.data" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ item.username }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button
                                                title="Edit"
                                                type="submit"
                                                class="btn btn-default"
                                                data-toggle="modal"
                                                data-target="#modal-email-access"

                                                @click="doEditEmailAccess(item)">

                                                <i class="fa fa-fw fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--                        END MAIL ACCESS-->
                </div>

                <div class="col-sm-12">
                    <!--                        PAYMENT METHOD-->
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Payment Method</h3>
                            <div class="card-tools">
                                <button data-toggle="modal"
                                        @click="clearMessageform"
                                        data-target="#modal-add-payment"
                                        class="btn btn-success float-right"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="card-body" style="height: 650px; overflow: scroll;">
                            <table class="table table-condensed table-hover table-striped table-bordered">
                                <thead>
                                <tr class="label-primary">
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Receive Payment</th>
                                    <th>Send Payment</th>
                                    <th>Registration</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(item, index) in paymentList.data" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ item.type }}</td>
                                    <td>{{ item.receive_payment }}</td>
                                    <td>{{ item.send_payment }}</td>
                                    <td>{{ item.show_registration }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="submit"
                                                    @click="doEditPayment(item)"
                                                    data-toggle="modal"
                                                    data-target="#modal-update-payment"
                                                    title="Edit"
                                                    class="btn btn-default"><i class="fa fa-fw fa-edit"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--                        END PAYMENT METHOD-->
                </div>
            </div>

            <div class="col-sm-6">
                <div class="col-sm-12">
                    <!--                        AHREF API-->
                    <div class="card card-outline card-secondary">
                        <div class="card-body">
                            <div class="progress-group">
                                <span class="progress-text">Ahref API rows consume</span>
                                <span class="progress-number"><b>{{ rows_consume }}</b>/500,000</span>

                                <div class="progress sm my-3">
                                    <div class="progress-bar progress-bar-aqua"
                                         :style="'width:'+ consume_percentage + '%'"></div>
                                </div>

                                <b>Used: </b> {{ rows_consume }} <br/>
                                <b>Remaining: </b> {{ rows_remaining }} <br/>
                            </div>

                            <button class="btn btn-link pull-right" @click="getSubscriptionInfo">Check update
                                <i v-if="loadingUpdate" class="fa fa-refresh fa-spin"></i></button>
                        </div>
                    </div>
                    <!--                        END AHREF API-->
                </div>

                <div class="col-sm-12">
                    <!--                        FORMULA FOR EXTERNAL BUYER-->
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Formula for External Buyer</h3>
                            <div class="card-tools">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-12">
                                        <form>
                                            <div class="form-group row">
                                                <label for="" class="col-sm-2 col-form-label">Additional</label>
                                                <div class="col-sm-10">
                                                    <input type="number" class="form-control" v-model="updateFormula.additional">
                                                    <span v-if="messageForms.errors.additional" v-for="err in messageForms.errors.additional" class="text-danger">{{ err }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="" class="col-sm-2 col-form-label">Percentage</label>
                                                <div class="col-sm-10">
                                                    <input type="number" class="form-control" v-model="updateFormula.percentage">
                                                    <span v-if="messageForms.errors.percentage" v-for="err in messageForms.errors.percentage" class="text-danger">{{ err }}</span>
                                                </div>
                                            </div>
                                        </form>
                                        <hr/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-sm-12">
                                        WITH ARTICLE
                                    </div>
                                    <div class="col-sm-12 p-3 text-primary">Commission = No <hr/></div>
                                    <div class="col-sm-12 text-danger">Formula: (Remain selling price)</div>

                                    <div class="col-sm-12 p-3 text-primary">Commission = Yes <hr/></div>
                                    <div class="col-sm-12 text-danger">Formula: (Percentage * selling_price) + selling_price</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-sm-12">
                                        WITHOUT ARTICLE
                                    </div>
                                    <div class="col-sm-12 p-3 text-primary">Commission = No <hr/></div>
                                    <div class="col-sm-12 text-danger">Formula: selling price + Additional</div>

                                    <div class="col-sm-12 p-3 text-primary">Commission = Yes <hr/></div>
                                    <div class="col-sm-12 text-danger">Formula: (Percentage * selling_price) + selling_price + Additional</div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="button" @click="submitFormula" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                    <!--                        END FORMULA FOR EXTERNAL BUYER-->
                </div>

                <div class="col-sm-12" v-for="(configs, typeConfig) in configList.data" v-if="typeConfig !== 'crypto'">
                    <!--                        CONFIG-->
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Config {{ typeConfig }}</h3>
                            <div class="card-tools">
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="" role="form">
                                <div v-for="(item) in configs" class="form-group">
                                    <label>{{ item.name }}</label>
                                    <input type="text" class="form-control" v-model="item.value" :placeholder="'Enter' + item.name">
                                </div>
                            </form>
                        </div>

                        <div class="card-footer">
                            <button type="button" @click="submitSaveConfig(typeConfig)" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                    <!--                        END CONFIG-->
                </div>

                <div class="col-sm-12">
                    <!--                        CONFIG USDT-->
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">USDT QR Code</h3>
                            <div class="card-tools">
                            </div>
                        </div>
                        <div class="card-body">
                            <img src="storage/usdt.jpg" width="250px" alt="" class="mx-auto d-block">
                            <p class="text-center">{{ configList.data.crypto[0].value }}</p>

                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" class="form-control" v-model="cryptoModel.address">
                            </div>

                            <div class="form-group">
                                <label>QR Code Image</label>
                                <input type="file" class="form-control" enctype="multipart/form-data" ref="qrcode" name="file">
                                <small class="text-muted">Note: It must be JPG type.</small><br/>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="button" @click="updateUsdt" class="btn btn-primary">Upload</button>
                        </div>
                    </div>
                    <!--                        END CONFIG USDT-->
                </div>
            </div>
        </div>

        <!--        MODALS-->
        <div class="modal fade" id="modal-add-language" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Language</h5>
                        <!--                        <div class="modal-load overlay float-right">-->
                        <!--                            <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>-->

                        <!--                            <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">-->
                        <!--                                {{ messageForms.message }}-->
                        <!--                            </span>-->
                        <!--                        </div>-->
                    </div>
                    <div class="modal-body">
                        <div :class="{'form-group': true, 'has-error': messageForms.errors.name}" class="form-group">
                            <label for="">Name</label>
                            <input type="text" v-model="languageModel.name" class="form-control" required>
                            <span v-if="messageForms.errors.name" v-for="err in messageForms.errors.name"
                                  class="text-danger">{{ err }}</span>
                        </div>
                        <div :class="{'form-group': true, 'has-error': messageForms.errors.code}" class="form-group">
                            <label for="">Code</label>
                            <input type="text" v-model="languageModel.code" class="form-control" required>
                            <span v-if="messageForms.errors.code" v-for="err in messageForms.errors.code"
                                  class="text-danger">{{ err }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitAddLanguage" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade"
             id="modal-email-access"
             tabindex="-1"
             role="dialog"
             aria-labelledby="modal-email-access"
             aria-hidden="true"
             data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Email Access</h5>
                        <div class="modal-load overlay float-right">
                            <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                            <span v-if="messageForms.message !== '' && !isPopupLoading"
                                  :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                                    {{ messageForms.message }}
                                </span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="border p-2 mb-3">
                            <h5 class="text-primary">{{ emailAccessModel.username }}</h5>
                            <span>{{ emailAccessModel.work_mail }}</span>
                        </div>

                        <div class="form-group">
                            <label>Emails</label>

                            <span
                                class="text-danger pull-right"
                                style="cursor: pointer;"

                                @click="emailAccessModel.emails = []">
                                    Remove all
                                </span>

                            <v-select
                                v-model="emailAccessModel.emails"
                                multiple
                                label="role_name"
                                placeholder="Select emails"
                                :options="emailAccessOptions"
                                :searchable="true"
                                :reduce="email => email.work_mail"/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitEmailAccess()" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-add-payment" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Payment Type</h5>
                    </div>
                    <div class="modal-body">
                        <div :class="{'form-group': true, 'has-error': messageForms.errors.type}" class="form-group">
                            <label for="">Payment Type</label>
                            <input type="text" v-model="paymentModel.type" class="form-control" placeholder="Enter Payment Type" required>
                            <span v-if="messageForms.errors.type" v-for="err in messageForms.errors.type" class="text-danger">{{ err }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitAddPayment" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-update-payment" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Payment Type</h5>
                    </div>
                    <div class="modal-body">
                        <div :class="{'form-group': true, 'has-error': messageForms.errors.type}" class="form-group">
                            <label for="">Payment Type</label>
                            <input type="text" v-model="paymentUpdate.type" class="form-control" placeholder="Enter Payment Type" required>
                            <span v-if="messageForms.errors.type" v-for="err in messageForms.errors.type" class="text-danger">{{ err }}</span>
                        </div>

                        <div :class="{'form-group': true, 'has-error': messageForms.errors.receive_payment}" class="form-group">
                            <label for="">Receive Payment</label>
                            <select name="" id="" class="form-control" v-model="paymentUpdate.receive_payment">
                                <option value=""></option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                            <span v-if="messageForms.errors.receive_payment" v-for="err in messageForms.errors.receive_payment" class="text-danger">{{ err }}</span>
                        </div>

                        <div :class="{'form-group': true, 'has-error': messageForms.errors.send_payment}" class="form-group">
                            <label for="">Send Payment</label>
                            <select name="" id="" class="form-control" v-model="paymentUpdate.send_payment">
                                <option value=""></option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                            <span v-if="messageForms.errors.send_payment" v-for="err in messageForms.errors.send_payment" class="text-danger">{{ err }}</span>
                        </div>

                        <div :class="{'form-group': true, 'has-error': messageForms.errors.show_registration}" class="form-group">
                            <label for="">Show in Registration</label>
                            <select name="" id="" class="form-control" v-model="paymentUpdate.show_registration">
                                <option value=""></option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                            <span v-if="messageForms.errors.show_registration" v-for="err in messageForms.errors.show_registration" class="text-danger">{{ err }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitUpdatePayment" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--    <div class="row">

            &lt;!&ndash; <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ countryList.total }}</h3>
                        <p>Total Countries</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-anchor"></i>
                    </div>
                </div>
            </div> &ndash;&gt;

            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-12">
                                &lt;!&ndash;                    LANGUAGE LIST&ndash;&gt;
                                <div class="card card-outline card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">Language List</h3>
                                        <div class="card-tools">
                                            &lt;!&ndash; Buttons, labels, and many other things can be placed here! &ndash;&gt;
                                            &lt;!&ndash; Here is a label for example &ndash;&gt;
                                            <button @click="clearMessageform" data-toggle="modal" data-target="#modal-add-language" class="btn btn-success float-right"><i class="fa fa-plus"></i></button>
                                        </div>
                                        &lt;!&ndash; /.card-tools &ndash;&gt;
                                    </div>
                                    &lt;!&ndash; /.card-header &ndash;&gt;
                                    <div class="card-body">
                                        <table class="table table-hover table-bordered table-striped rlink-table">
                                            <thead>
                                            <tr class="label-primary">
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Code</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="(item, index) in langaugeList.data" :key="index">
                                                <td>{{ index + 1 }}</td>
                                                <td>{{ item.name }}</td>
                                                <td>{{ item.code }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button @click="doEditlanguage(item)" data-toggle="modal" data-target="#modal-update-language" type="submit" title="Edit" class="btn btn-default"><i class="fa fa-fw fa-edit"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                &lt;!&ndash; /.card &ndash;&gt;
                                &lt;!&ndash;                    END LANGUAGE LIST&ndash;&gt;
                            </div>
                        </div>

    &lt;!&ndash;                    <div class="box">&ndash;&gt;
    &lt;!&ndash;                        <div class="box-header">&ndash;&gt;
    &lt;!&ndash;                            <h3 class="box-title">Language List</h3>&ndash;&gt;
    &lt;!&ndash;                            <button @click="clearMessageform" data-toggle="modal" data-target="#modal-add-language" class="btn btn-success float-right"><i class="fa fa-plus"></i></button>&ndash;&gt;
    &lt;!&ndash;                        </div>&ndash;&gt;

    &lt;!&ndash;                        <div class="box-body no-padding relative" style="height: 650px">&ndash;&gt;
    &lt;!&ndash;                            <table class="table table-hover table-bordered table-striped rlink-table">&ndash;&gt;
    &lt;!&ndash;                                <thead>&ndash;&gt;
    &lt;!&ndash;                                    <tr class="label-primary">&ndash;&gt;
    &lt;!&ndash;                                        <th>#</th>&ndash;&gt;
    &lt;!&ndash;                                        <th>Name</th>&ndash;&gt;
    &lt;!&ndash;                                        <th>Code</th>&ndash;&gt;
    &lt;!&ndash;                                        <th>Action</th>&ndash;&gt;
    &lt;!&ndash;                                    </tr>&ndash;&gt;
    &lt;!&ndash;                                </thead>&ndash;&gt;
    &lt;!&ndash;                                <tbody>&ndash;&gt;
    &lt;!&ndash;                                    <tr v-for="(item, index) in langaugeList.data" :key="index">&ndash;&gt;
    &lt;!&ndash;                                        <td>{{ index + 1 }}</td>&ndash;&gt;
    &lt;!&ndash;                                        <td>{{ item.name }}</td>&ndash;&gt;
    &lt;!&ndash;                                        <td>{{ item.code }}</td>&ndash;&gt;
    &lt;!&ndash;                                        <td>&ndash;&gt;
    &lt;!&ndash;                                            <div class="btn-group">&ndash;&gt;
    &lt;!&ndash;                                                <button @click="doEditlanguage(item)" data-toggle="modal" data-target="#modal-update-language" type="submit" title="Edit" class="btn btn-default"><i class="fa fa-fw fa-edit"></i></button>&ndash;&gt;
    &lt;!&ndash;                                            </div>&ndash;&gt;
    &lt;!&ndash;                                        </td>&ndash;&gt;
    &lt;!&ndash;                                    </tr>&ndash;&gt;
    &lt;!&ndash;                                </tbody>&ndash;&gt;
    &lt;!&ndash;                            </table>&ndash;&gt;
    &lt;!&ndash;                        </div>&ndash;&gt;
    &lt;!&ndash;                    </div>&ndash;&gt;

                        &lt;!&ndash; <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">Countries List</h3>
                                <button @click="doAdd" data-toggle="modal" data-target="#modal-add" class="btn btn-success float-right"><i class="fa fa-plus"></i></button>
                                <div class="input-group input-group-sm float-right" style="width: 65px">
                                    <select @change="doSearchList" class="form-control pull-right" v-model="filterModel.per_page" style="height: 37px;">
                                        <option v-for="value in listPageOptions" :value="value">{{ value }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="box-body no-padding relative">
                                <table class="table table-hover table-bordered table-striped rlink-table">
                                    <tbody>
                                    <tr class="label-primary">
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Action</th>
                                    </tr>
                                    <tr>
                                        <td style="max-width: 30px;">
                                        </td>

                                        <td>
                                            <div class="input-group input-group-sm">
                                                <input type="text" v-model="filterModel.name_temp"  class="form-control pull-right" placeholder="Search Name">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="input-group input-group-sm">
                                                <input type="text" v-model="filterModel.code_temp"  class="form-control pull-right" placeholder="Search Code">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button @click="doSearchList" type="submit" title="Filter" class="btn btn-default"><i class="fa fa-fw fa-search"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-for="(item, index) in countryList.data" :key="index">
                                        <td class="center-content">{{ index + 1 }}</td>
                                        <td>{{ item.name }}</td>
                                        <td>{{ item.code }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button @click="doEdit(item)" data-toggle="modal" data-target="#modal-update" type="submit" title="Edit" class="btn btn-default"><i class="fa fa-fw fa-edit"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="overlay" v-if="isLoadingTable">
                                    <i class="fa fa-refresh fa-spin"></i>
                                </div>
                            </div>
                            <div class="box-footer clearfix">
                                <div class="paging_simple_numbers">
                                    <pagination :data="countryList" @pagination-change-page="goToPage"></pagination>
                                </div>
                            </div>
                        </div> &ndash;&gt;

                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">Mail Access</h3>
                            </div>

                            <div class="box-body no-padding relative" style="height: 650px">
                                <table class="table table-condensed table-hover table-striped table-bordered">
                                    <thead>
                                    <tr class="label-primary">
                                        <th>#</th>
                                        <th>Username</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(item, index) in emailAccessList.data" :key="index">
                                        <td>{{ index + 1}}</td>
                                        <td>{{ item.username }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button
                                                    title="Edit"
                                                    type="submit"
                                                    class="btn btn-default"
                                                    data-toggle="modal"
                                                    data-target="#modal-email-access"

                                                    @click="doEditEmailAccess(item)">

                                                    <i class="fa fa-fw fa-edit"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="overlay" v-if="isLoadingTable">
                                    <i class="fa fa-refresh fa-spin"></i>
                                </div>
                            </div>
                        </div>

                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">Payment Method</h3>
                                <button data-toggle="modal" @click="clearMessageform" data-target="#modal-add-payment" class="btn btn-success float-right"><i class="fa fa-plus"></i></button>
                            </div>

                            <div class="box-body no-padding relative">
                                <table class="table table-condensed table-hover table-striped table-bordered">
                                    <thead>
                                        <tr class="label-primary">
                                            <th>#</th>
                                            <th>Type</th>
                                            <th>Receive Payment</th>
                                            <th>Send Payment</th>
                                            <th>Registration</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in paymentList.data" :key="index">
                                            <td>{{ index + 1}}</td>
                                            <td>{{ item.type }}</td>
                                            <td>{{ item.receive_payment }}</td>
                                            <td>{{ item.send_payment }}</td>
                                            <td>{{ item.show_registration }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="submit" @click="doEditPayment(item)" data-toggle="modal" data-target="#modal-update-payment" title="Edit" class="btn btn-default"><i class="fa fa-fw fa-edit"></i></button>
                                                </div>
                                                &lt;!&ndash; <div class="btn-group">
                                                    <button title="Delete" class="btn btn-default"><i class="fa fa-fw fa-trash"></i></button>
                                                </div> &ndash;&gt;
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="row">

                            <div class="col-sm-12">
                                <div class="box p-4">
                                    <div class="box-body">
                                        <div class="progress-group">
                                            <span class="progress-text">Ahref API rows consume</span>
                                            <span class="progress-number"><b>{{ rows_consume }}</b>/500,000</span>

                                            <div class="progress sm my-3">
                                                <div class="progress-bar progress-bar-aqua" :style="'width:'+ consume_percentage + '%'"></div>
                                            </div>

                                            <b>Used: </b> {{ rows_consume }} <br/>
                                            <b>Remaining: </b> {{ rows_remaining }} <br/>
                                        </div>

                                        <button class="btn btn-link pull-right" @click="getSubscriptionInfo">Check update <i v-if="loadingUpdate" class="fa fa-refresh fa-spin"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="box pb-4">
                                    <div class="box-header">
                                        <h3 class="box-title">Formula for External Buyer</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-md-12">
                                                    <form>
                                                        <div class="form-group row">
                                                            <label for="" class="col-sm-2 col-form-label">Additional</label>
                                                            <div class="col-sm-10">
                                                                <input type="number" class="form-control" v-model="updateFormula.additional">
                                                                <span v-if="messageForms.errors.additional" v-for="err in messageForms.errors.additional" class="text-danger">{{ err }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label for="" class="col-sm-2 col-form-label">Percentage</label>
                                                            <div class="col-sm-10">
                                                                <input type="number" class="form-control" v-model="updateFormula.percentage">
                                                                <span v-if="messageForms.errors.percentage" v-for="err in messageForms.errors.percentage" class="text-danger">{{ err }}</span>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <hr/>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="col-sm-12">
                                                    WITH ARTICLE
                                                </div>
                                                <div class="col-sm-12 p-3 text-primary">Commission = No <hr/></div>
                                                <div class="col-sm-12 text-danger">Formula: (Remain selling price)</div>

                                                <div class="col-sm-12 p-3 text-primary">Commission = Yes <hr/></div>
                                                <div class="col-sm-12 text-danger">Formula: (Percentage * selling_price) + selling_price</div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="col-sm-12">
                                                    WITHOUT ARTICLE
                                                </div>
                                                <div class="col-sm-12 p-3 text-primary">Commission = No <hr/></div>
                                                <div class="col-sm-12 text-danger">Formula: selling price + Additional</div>

                                                <div class="col-sm-12 p-3 text-primary">Commission = Yes <hr/></div>
                                                <div class="col-sm-12 text-danger">Formula: (Percentage * selling_price) + selling_price + Additional</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-footer clearfix">
                                        <button type="button" @click="submitFormula" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </div>


                            <div class="col-sm-12" v-for="(configs, typeConfig) in configList.data" v-if="typeConfig !== 'crypto'">
                                <div class="box">
                                    <div class="box-header">
                                        <h3 class="box-title">Config {{ typeConfig }}</h3>
                                    </div>
                                    &lt;!&ndash; /.box-header &ndash;&gt;
                                    <div class="box-body table-responsive relative">

                                        <form action="" role="form">
                                            <div v-for="(item) in configs" class="form-group">
                                                <label>{{ item.name }}</label>
                                                <input type="text" class="form-control" v-model="item.value" :placeholder="'Enter' + item.name">
                                            </div>
                                        </form>

                                        <div class="overlay" v-if="isLoadingConfig[typeConfig]">
                                            <i class="fa fa-refresh fa-spin"></i>
                                        </div>
                                    </div>
                                    &lt;!&ndash; /.box-body &ndash;&gt;
                                    <div class="box-footer clearfix">
                                        <button type="button" @click="submitSaveConfig(typeConfig)" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                                &lt;!&ndash; /.box &ndash;&gt;
                            </div>

                            <div class="col-sm-12">
                                <div class="box">
                                    <div class="box-header">
                                        <h3 class="box-title">USDT QR Code</h3>
                                    </div>

                                    <div class="box-body">
                                        <img src="storage/usdt.jpg" width="250px" alt="" class="mx-auto d-block">
                                        <p class="text-center">{{ configList.data.crypto[0].value }}</p>

                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" class="form-control" v-model="cryptoModel.address">
                                        </div>

                                        <div class="form-group">
                                            <label>QR Code Image</label>
                                            <input type="file" class="form-control" enctype="multipart/form-data" ref="qrcode" name="file">
                                            <small class="text-muted">Note: It must be JPG type.</small><br/>
                                        </div>
                                    </div>

                                    <div class="box-footer clearfix">
                                        <button type="button" @click="updateUsdt" class="btn btn-primary">Upload</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            &lt;!&ndash; Modal Update Payment Type &ndash;&gt;
            <div class="modal fade" id="modal-update-payment" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Payment Type</h5>
                            <div class="modal-load overlay float-right">
                                <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                                <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                                    {{ messageForms.message }}
                                </span>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div :class="{'form-group': true, 'has-error': messageForms.errors.type}" class="form-group">
                                <label for="">Payment Type</label>
                                <input type="text" v-model="paymentUpdate.type" class="form-control" placeholder="Enter Payment Type" required>
                                <span v-if="messageForms.errors.type" v-for="err in messageForms.errors.type" class="text-danger">{{ err }}</span>
                            </div>

                            <div :class="{'form-group': true, 'has-error': messageForms.errors.receive_payment}" class="form-group">
                                <label for="">Receive Payment</label>
                                <select name="" id="" class="form-control" v-model="paymentUpdate.receive_payment">
                                    <option value=""></option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                                <span v-if="messageForms.errors.receive_payment" v-for="err in messageForms.errors.receive_payment" class="text-danger">{{ err }}</span>
                            </div>

                            <div :class="{'form-group': true, 'has-error': messageForms.errors.send_payment}" class="form-group">
                                <label for="">Send Payment</label>
                                <select name="" id="" class="form-control" v-model="paymentUpdate.send_payment">
                                    <option value=""></option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                                <span v-if="messageForms.errors.send_payment" v-for="err in messageForms.errors.send_payment" class="text-danger">{{ err }}</span>
                            </div>

                            <div :class="{'form-group': true, 'has-error': messageForms.errors.show_registration}" class="form-group">
                                <label for="">Show in Registration</label>
                                <select name="" id="" class="form-control" v-model="paymentUpdate.show_registration">
                                    <option value=""></option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                                <span v-if="messageForms.errors.show_registration" v-for="err in messageForms.errors.show_registration" class="text-danger">{{ err }}</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" @click="submitUpdatePayment" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            &lt;!&ndash; Modal Add Language &ndash;&gt;
            <div class="modal fade" id="modal-add-language" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Language</h5>
                            <div class="modal-load overlay float-right">
                                <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                                <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                                    {{ messageForms.message }}
                                </span>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div :class="{'form-group': true, 'has-error': messageForms.errors.name}" class="form-group">
                                <label for="">Name</label>
                                <input type="text" v-model="languageModel.name" class="form-control" required>
                                <span v-if="messageForms.errors.name" v-for="err in messageForms.errors.name" class="text-danger">{{ err }}</span>
                            </div>
                            <div :class="{'form-group': true, 'has-error': messageForms.errors.code}" class="form-group">
                                <label for="">Code</label>
                                <input type="text" v-model="languageModel.code" class="form-control" required>
                                <span v-if="messageForms.errors.code" v-for="err in messageForms.errors.code" class="text-danger">{{ err }}</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" @click="submitAddLanguage" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            &lt;!&ndash; End Add Modal Language &ndash;&gt;


            &lt;!&ndash; Modal Update Language &ndash;&gt;
            <div class="modal fade" id="modal-update-language" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Language</h5>
                            <div class="modal-load overlay float-right">
                                <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                                <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                                    {{ messageForms.message }}
                                </span>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div :class="{'form-group': true, 'has-error': messageForms.errors.name}" class="form-group">
                                <label for="">Name</label>
                                <input type="text" v-model="languageUpdate.name" class="form-control" required>
                                <span v-if="messageForms.errors.name" v-for="err in messageForms.errors.name" class="text-danger">{{ err }}</span>
                            </div>
                            <div :class="{'form-group': true, 'has-error': messageForms.errors.code}" class="form-group">
                                <label for="">Code</label>
                                <input type="text" v-model="languageUpdate.code" class="form-control" required>
                                <span v-if="messageForms.errors.code" v-for="err in messageForms.errors.code" class="text-danger">{{ err }}</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" @click="submitUpdateLanguage" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            &lt;!&ndash; End Add Update Language &ndash;&gt;

            &lt;!&ndash; Modal Add Payment&ndash;&gt;
            <div class="modal fade" id="modal-add-payment" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Payment Type</h5>
                            <div class="modal-load overlay float-right">
                                <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                                <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                                    {{ messageForms.message }}
                                </span>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div :class="{'form-group': true, 'has-error': messageForms.errors.type}" class="form-group">
                                <label for="">Payment Type</label>
                                <input type="text" v-model="paymentModel.type" class="form-control" placeholder="Enter Payment Type" required>
                                <span v-if="messageForms.errors.type" v-for="err in messageForms.errors.type" class="text-danger">{{ err }}</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" @click="submitAddPayment" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            &lt;!&ndash; End of Modal Add Payment&ndash;&gt;

            &lt;!&ndash; Modal Email Access &ndash;&gt;
            <div class="modal fade" id="modal-email-access" tabindex="-1" role="dialog" aria-labelledby="modal-email-access" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Email Access</h5>
                            <div class="modal-load overlay float-right">
                                <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                                <span v-if="messageForms.message !== '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                                    {{ messageForms.message }}
                                </span>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="border p-2 mb-3">
                                <h5 class="text-primary">{{ emailAccessModel.username }}</h5>
                                <span>{{ emailAccessModel.work_mail }}</span>
                            </div>

                            <div class="form-group">
                                <label>Emails</label>

                                <span
                                    class="text-danger pull-right"
                                    style="cursor: pointer;"

                                    @click="emailAccessModel.emails = []">
                                    Remove all
                                </span>

                                <v-select
                                    v-model="emailAccessModel.emails"
                                    multiple
                                    label="role_name"
                                    placeholder="Select emails"
                                    :options="emailAccessOptions"
                                    :searchable="true"
                                    :reduce="email => email.work_mail"/>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" @click="submitEmailAccess()" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            &lt;!&ndash; Modal Add Country &ndash;&gt;
            &lt;!&ndash; <div class="modal fade" id="modal-add" style="display: none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Country</h4>
                            <div class="modal-load overlay float-right">
                                <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                                <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                                    {{ messageForms.message }}
                                </span>
                            </div>
                        </div>
                        <div class="modal-body relative">
                            <form class="row" action="">
                                <div class="col-md-6">
                                    <div :class="{'form-group': true, 'has-error': messageForms.errors.domain}" class="form-group">
                                        <label style="color: #333">Name</label>
                                        <input type="text" v-model="countryModel.name" class="form-control" value="" required="required" placeholder="Enter Name">
                                        <span v-if="messageForms.errors.name" v-for="err in messageForms.errors.name" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div :class="{'form-group': true, 'has-error': messageForms.errors.code}" class="form-group">
                                        <label style="color: #333">Code</label>
                                        <input type="text" v-model="countryModel.code" class="form-control" value="" required="required" placeholder="Enter Code">
                                        <span v-if="messageForms.errors.code" v-for="err in messageForms.errors.code" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                            </form>
                            <div class="overlay" v-if="isPopupLoading"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                            <button type="button" @click="submitAdd" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div> &ndash;&gt;
            &lt;!&ndash; End Modal Add Country &ndash;&gt;

            &lt;!&ndash; Modal Update Country &ndash;&gt;
            &lt;!&ndash; <div class="modal fade" id="modal-update" style="display: none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Update Country {{ countryUpdate.name }}</h4>
                            <div class="modal-load overlay float-right">
                                <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                                <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                                    {{ messageForms.message }}
                                </span>
                            </div>
                        </div>
                        <div class="modal-body relative">
                            <form class="row" action="">
                                <div class="col-md-6">
                                    <div :class="{'form-group': true, 'has-error': messageForms.errors.domain}" class="form-group">
                                        <label style="color: #333">Name</label>
                                        <input type="text" v-model="countryUpdate.name" class="form-control" value="" required="required" placeholder="Enter Name">
                                        <span v-if="messageForms.errors.name" v-for="err in messageForms.errors.name" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div :class="{'form-group': true, 'has-error': messageForms.errors.code}" class="form-group">
                                        <label style="color: #333">Code</label>
                                        <input type="text" v-model="countryUpdate.code" class="form-control" value="" required="required" placeholder="Enter Code">
                                        <span v-if="messageForms.errors.code" v-for="err in messageForms.errors.code" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                            </form>
                            <div class="overlay" v-if="isPopupLoading"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                            <button type="button" @click="submitUpdate" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div> &ndash;&gt;
            &lt;!&ndash; End Modal Update Country &ndash;&gt;
        </div>-->
</template>

<script>
import {mapState} from 'vuex';
import axios from 'axios';

export default {
    name : 'System',

    data() {
        return {
            countryModel : {
                id : 0,
                name : '',
                code : '',
            },

            countryUpdate : {
                id : 0,
                name : '',
                code : ''
            },

            paymentModel : {
                id : 0,
                type : ''
            },

            paymentUpdate : {
                id : 0,
                type : '',
                show_registration : '',
                receive_payment : '',
                send_payment : ''
            },

            languageModel : {
                name : '',
                code : '',
            },

            languageUpdate : {
                id : '',
                name : '',
                code : '',
            },

            emailAccessModel : {
                user_id : 0,
                username : '',
                work_mail : '',
                emails : []
            },

            cryptoModel : {
                address : ''
            },

            skrillModel : {
                email : ''
            },

            filterModel : {
                name : this.$route.query.name || '',
                code : this.$route.query.name || '',
                name_temp : this.$route.query.name_temp || '',
                code_temp : this.$route.query.code_temp || '',
                page : this.$route.query.page || 0,
                per_page : this.$route.query.per_page || 10,
                paginate : 1
            },

            listPageOptions : [
                5,
                10,
                25,
                50,
                100
            ],
            isLoadingTable : false,
            isPopupLoading : false,
            isLoadingConfig : {
                alexa : false,
                ahrefs : false
            },
            rows_consume : 0,
            consume_percentage : 0,
            rows_remaining : 0,
            loadingUpdate : false,
            updateFormula : {
                id : '',
                additional : '',
                percentage : '',
            },
            formData : null
        };
    },

    async created() {
        await this.$store.dispatch('actionCheckAdminCurrentUser', {vue : this});
        if (!this.user.isAdmin) {
            window.location.href = '/';
            return;
        }

        this.getConfigList();
        this.getPaymentList();

        // this.getCountryList({
        //     params: this.filterModel
        // });

        this.getSubscriptionInfo();
        this.getFormula();
        this.getLanguageList();
        this.getEmailAccessList();
    },

    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,
            countryList : state => state.storeSystem.countryList,
            paymentList : state => state.storeSystem.paymentList,
            messageForms : state => state.storeSystem.messageForms,
            configList : state => state.storeSystem.configList,
            Info : state => state.storeSystem.Info,
            formula : state => state.storeSystem.formula,
            langaugeList : state => state.storeSystem.langaugeList,
            emailAccessList : state => state.storeSystem.emailAccessList,
        }),

        emailAccessOptions() {
            let self = this;
            const seen = [];
            const emails = self.emailAccessList.data;

            emails.forEach(item => {
                item.role_name = '[' + item.role.name + '] ' + item.work_mail
            });

            return emails.filter(item => {
                const duplicate = seen.includes(item.work_mail) || item.work_mail === self.emailAccessModel.work_mail
                seen.push(item.work_mail)
                return !duplicate;
            })
        }
    },

    methods : {
        async getLanguageList() {
            await this.$store.dispatch('actionGetLanguageList');
        },

        async getEmailAccessList() {
            this.isLoadingTable = true;
            await this.$store.dispatch('actionGetEmailAccessList');
            this.isLoadingTable = false;
        },

        async submitAddLanguage() {
            await this.$store.dispatch('actionAddLanguage', this.languageModel);

            this.getLanguageList();

            this.languageModel = {
                name : '',
                code : '',
            }
        },

        async submitUpdateLanguage() {
            await this.$store.dispatch('actionUpdateLanguage', this.languageUpdate);
            this.getLanguageList();
        },

        doEditlanguage(item) {
            this.$store.dispatch('clearMessageFormSystem');
            this.languageUpdate = JSON.parse(JSON.stringify(item))
        },

        async submitFormula() {
            await this.$store.dispatch('actionUpdateFormula', this.updateFormula);

            if (this.messageForms.action == 'save_formula') {
                swal.fire(
                    'Saved!',
                    'Successfully Updated.',
                    'success'
                )

                this.getFormula();
            }
        },

        async getFormula() {
            await this.$store.dispatch('actionGetFormula');

            this.updateFormula = this.formula.data[0];
        },

        async getConfigList() {
            await this.$store.dispatch('actionGetConfigList');

            for (let configType in this.configList.data) {
                this.isLoadingConfig[configType] = false;
            }
        },

        async getSubscriptionInfo() {
            this.loadingUpdate = true;
            await this.$store.dispatch('actionGetSubscriptionInfo');

            let rows_left = this.Info.info.rows_left
            let rows_limit = this.Info.info.rows_limit
            let consume_rows = parseFloat(rows_limit) - parseFloat(rows_left);

            this.rows_remaining = rows_left;
            this.rows_consume = consume_rows;
            this.consume_percentage = (parseFloat(consume_rows) / parseFloat(rows_limit)) * 100;

            this.loadingUpdate = false;
        },

        async getCountryList(params) {
            this.isLoadingTable = true;
            await this.$store.dispatch('actionGetCountryList', params);
            this.isLoadingTable = false;
        },

        async getPaymentList(params) {
            this.isLoadingTable = true;
            await this.$store.dispatch('actionGetPaymentList', params);
            this.isLoadingTable = false;
        },

        async doSearchList() {
            let that = this;
            that.filterModel.name = that.filterModel.name_temp;
            that.filterModel.code = that.filterModel.code_temp;

            this.$router.push({
                query : that.filterModel,
            });

            this.getCountryList({
                params : that.filterModel
            });
        },

        clearModel() {
            this.countryModel = {
                id : 0,
                name : '',
                code : ''
            };

            this.paymentModel = {
                id : 0,
                type : ''
            };
        },

        async goToPage(pageNum) {
            let that = this;
            that.filterModel.page = pageNum;
            this.$router.push({
                query : that.filterModel,
            });

            await this.getCountryList({
                params : that.filterModel
            });
        },

        doAdd() {
            this.$store.dispatch('clearMessageFormSystem');
        },

        doEdit(item) {
            this.$store.dispatch('clearMessageFormSystem');
            this.countryUpdate = JSON.parse(JSON.stringify(item))
        },

        doEditPayment(item) {
            this.clearMessageform();
            this.$store.dispatch('clearMessageFormSystem');
            this.paymentUpdate = JSON.parse(JSON.stringify(item))
        },

        doEditEmailAccess(item) {
            this.emailAccessModel.user_id = item.id;
            this.emailAccessModel.username = item.username;
            this.emailAccessModel.work_mail = item.work_mail;
            this.emailAccessModel.emails = this.flattenEmailAccess(item.access)
        },

        flattenEmailAccess(emails) {
            return emails.map(a => a.work_mail);
        },

        async submitEmailAccess() {
            let self = this
            self.isPopupLoading = true;
            await self.$store.dispatch('actionAddEmailAccess', self.emailAccessModel);
            self.isPopupLoading = false;

            if (self.messageForms.action === 'add_email_access') {

                await swal.fire(
                    'Saved!',
                    'Successfully saved.',
                    'success'
                )

                await this.getEmailAccessList();
            }
        },

        async submitAdd() {
            let that = this;
            this.isPopupLoading = true;
            await this.$store.dispatch('actionAddCountry', that.countryModel);
            this.isPopupLoading = false;

            if (this.messageForms.action === 'saved_country') {
                this.clearModel();
                this.getCountryList({
                    params : this.filterModel
                });
            }
        },

        async submitAddPayment() {
            let that = this;
            this.isPopupLoading = true;
            await this.$store.dispatch('actionAddPayment', that.paymentModel);
            this.isPopupLoading = false;

            if (this.messageForms.action === 'saved_payment') {
                this.clearModel();
                this.getPaymentList();
            }
        },

        async submitUpdate() {
            this.isPopupLoading = true;
            await this.$store.dispatch('actionUpdateCountry', this.countryUpdate);
            this.isPopupLoading = false;

            if (this.messageForms.action === 'updated_country') {
                for (var index in this.countryList.data) {
                    if (this.countryList.data[index].id === this.countryUpdate.id) {
                        this.countryList.data[index] = this.countryUpdate;
                        break;
                    }
                }
            }
        },

        async submitUpdatePayment() {
            this.isPopupLoading = true;
            await this.$store.dispatch('actionUpdatePayment', this.paymentUpdate);
            this.isPopupLoading = false;

            if (this.messageForms.action === 'updated_payment') {
                for (var index in this.paymentList.data) {
                    if (this.paymentList.data[index].id === this.paymentUpdate.id) {
                        this.paymentList.data[index] = this.paymentUpdate;
                        break;
                    }
                }
            }
        },

        async submitSaveConfig(configType) {
            this.isLoadingConfig[configType] = true;
            await this.saveListConfig(this.configList.data[configType])
            this.isLoadingConfig[configType] = false;

            if (this.messageForms.action === 'saved_config') {

            }

            alert('Saved configs success');
        },

        async saveListConfig(configs) {
            for (let index in configs) {
                await this.saveConfig(configs[index]);
            }
        },

        async saveConfig(config) {
            await this.$store.dispatch('actionUpdateConfig', config);
        },

        clearMessageform() {
            this.$store.dispatch('clearMessageFormSystem');
        },

        updateUsdt() {
            this.formData = new FormData();
            this.formData.append('address', this.cryptoModel.address);
            this.formData.append('file', this.$refs.qrcode.files[0]);

            axios.post('/api/crypto/usdt', this.formData)
                .then((response) => {
                    swal.fire(
                        'Success',
                        'Successfully Updated',
                        'success'
                    )
                }).catch((error) => {
                swal.fire(
                    'Error',
                    'Error encountered',
                    'error'
                )
            });
        }
    },
}
</script>
