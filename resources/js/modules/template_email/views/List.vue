<template>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">{{ $t('message.template.ml_title') }}</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">

<!--                        <div class="form-check" v-if="user.isOurs == 0">-->
<!--                            <label class="form-check-label">-->
<!--                                <input type="checkbox" class="form-check-input" v-model="filterModel.is_general_template" @click="doSearchList">-->
<!--                                General template-->
<!--                            </label>-->
<!--                        </div>-->

                        <div class="row mb-2">
                            <div class="col-12">
                                <button @click="doAdd" data-toggle="modal" data-target="#modal-add" class="btn btn-success float-right">
                                    <i class="fa fa-plus"></i>
                                </button>

                                <div class="input-group input-group-sm float-right mr-2" style="width: 65px">
                                    <select @change="doSearchList" class="form-control" v-model="filterModel.per_page" style="height: 37px;">
                                        <option v-for="value in listPageOptions" :value="value">{{ value }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-striped rlink-table">
                                <tbody>
                                <tr class="label-primary">
                                    <th>#</th>
                                    <th>{{ $t('message.template.category') }}</th>
                                    <th>{{ $t('message.template.type') }}</th>
                                    <th>{{ $t('message.template.ml_name') }}</th>
                                    <th>{{ $t('message.template.ml_ip_title') }}</th>
                                    <th>{{ $t('message.template.ml_lang') }}</th>
                                    <th>{{ $t('message.template.ml_action') }}</th>
                                </tr>
                                <tr>
                                    <td style="max-width: 30px;">
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <select v-model="filterModel.category_temp" class="form-control pull-right">
                                                <option value="">{{ $t('message.template.all') }}</option>
                                                <option value="none">N/A</option>
                                                <option v-for="category in categories" :value="category.value">
                                                    {{ category.label }}
                                                </option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <select v-model="filterModel.type_temp" class="form-control pull-right">
                                                <option value="">{{ $t('message.template.all') }}</option>
                                                <option value="none">N/A</option>
                                                <option v-for="type in types" :value="type.value">
                                                    {{ type.label }}
                                                </option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <input
                                                type="text"
                                                v-model="filterModel.mail_name_temp"
                                                class="form-control pull-right"
                                                :placeholder="$t('message.template.filter_search_email_name')">
                                        </div>
                                    </td>

                                    <td>
                                        <div class="input-group input-group-sm">
                                            <input
                                                type="text"
                                                v-model="filterModel.title_temp"
                                                class="form-control pull-right"
                                                :placeholder="$t('message.template.filter_search_title')">
                                        </div>
                                    </td>

                                    <!--                                    <td>-->
                                    <!--                                        <div class="input-group input-group-sm">-->
                                    <!--                                            <input type="text" v-model="filterModel.content_temp"  class="form-control pull-right" placeholder="Search Content">-->
                                    <!--                                        </div>-->
                                    <!--                                    </td>-->

                                    <td>
                                        <div class="input-group input-group-sm">
                                            <select v-model="filterModel.country_id_temp" class="form-control pull-right">
                                                <option value="0"></option>
                                                <option v-for="option in listLanguages.data" v-bind:value="option.id">
                                                    {{ option.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button
                                                @click="doSearchList"
                                                type="submit"
                                                :title="$t('message.template.filter_title')"
                                                class="btn btn-default">
                                                <i class="fa fa-fw fa-search"></i>
                                            </button>

                                            <button
                                                @click="clearSearch()"
                                                title="Clear search"
                                                class="btn btn-default">
                                                <i class="fa fa-fw fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-for="(item, index) in emailList.data" :key="index">
                                    <td class="center-content">{{ item.id }}</td>
                                    <td>
                                        <span class="badge" :class="getCategoryClass(item.category)">
                                            {{ getCategoryLabel(item.category) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge" :class="getTypeClass(item.type)">
                                            {{ getTypeLabel(item.type) }}
                                        </span>
                                    </td>
                                    <td>{{ item.mail_name }}</td>
                                    <td>{{ item.title }}</td>
                                    <td>{{ (item.language) ? item.language.name : '' }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button @click="Export2Word(item, item.title)" class="btn btn-default">{{ $t('message.template.export_text') }}</button>
                                            <button
                                                @click="doEdit(item)"
                                                data-toggle="modal"
                                                data-target="#modal-update"
                                                type="submit"
                                                :title="$t('message.template.action_edit')"
                                                class="btn btn-default">

                                                <i class="fa fa-fw fa-edit"></i>
                                            </button>

                                            <button
                                                @click="doDelete(item)"
                                                data-toggle="modal"
                                                :title="$t('message.template.action_delete')"
                                                class="btn btn-default">

                                                <i class="fa fa-fw fa-times"></i>
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

                    <div class="card-footer">
                        <component :is="pagination" :callMethod="goToPage"></component>
                    </div>
                </div>
            </div>
        </div>

        <!--    Modal Add-->
        <div class="modal fade" id="modal-add" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('message.template.al_title') }}</h4>
                    </div>
                    <div class="modal-body relative">
                        <form class="row" action="">

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.country_id}" class="form-group">
                                    <label style="color: #333">{{ $t('message.template.al_lang') }}</label>
                                    <div>
                                        <select v-model="emailModel.country_id" class="form-control pull-right">
                                            <option value="0">-- {{ $t('message.template.al_select_lang') }} --</option>
                                            <option v-for="option in listLanguages.data" v-bind:value="option.id">
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
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.mail_name}" class="form-group">
                                    <label style="color: #333">{{ $t('message.template.al_email') }}</label>
                                    <input
                                        type="text"
                                        v-model="emailModel.mail_name"
                                        class="form-control"
                                        value=""
                                        required="required"
                                        :placeholder="$t('message.template.al_enter_email')">

                                    <span
                                        v-if="messageForms.errors.mail_name"
                                        v-for="err in messageForms.errors.mail_name"
                                        class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-12" v-if="user.isOurs == 0">
                                <!--                                <hr>-->
                                <!--                                    <div class="form-check">-->
                                <!--                                        <label class="form-check-label">-->
                                <!--                                            <input type="checkbox" class="form-check-input" v-model="emailModel.is_general_template">-->
                                <!--                                            General template-->
                                <!--                                        </label>-->
                                <!--                                    </div>-->
                                <!--                                <hr>-->
                            </div>

                            <div class="col-md-6 mb-3" v-if="user.isOurs === 0">
                                <label style="color: #333">{{ $t('message.template.category') }}</label>

                                <div>
                                    <select v-model="emailModel.category" class="form-control pull-right">
                                        <option value="">{{ $t('message.template.none') }}</option>
                                        <option v-for="category in categories" :value="category.value">
                                            {{ category.label }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3" v-if="user.isOurs === 0">
                                <label style="color: #333">{{ $t('message.template.type') }}</label>

                                <div>
                                    <select v-model="emailModel.type" class="form-control pull-right">
                                        <option value="">{{ $t('message.template.none') }}</option>
                                        <option v-for="type in types" :value="type.value">
                                            {{ type.label }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.title}" class="form-group">
                                    <label style="color: #333">{{ $t('message.template.al_ip_title') }}</label>
                                    <input
                                        type="text"
                                        v-model="emailModel.title"
                                        class="form-control"
                                        value=""
                                        required="required"
                                        :placeholder="$t('message.template.al_enter_title')">

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
                                    <label style="color: #333">{{ $t('message.template.al_content') }}</label>
                                    <textarea
                                        rows="10"
                                        type="text"
                                        v-model="emailModel.content"
                                        class="form-control"
                                        value=""
                                        required="required"
                                        :placeholder="$t('message.template.al_enter_content')">

                                    </textarea>

                                    <span
                                        v-if="messageForms.errors.content"
                                        v-for="err in messageForms.errors.content"
                                        class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                        </form>
                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            {{ $t('message.template.close') }}
                        </button>
                        <button type="button" @click="submitAdd" class="btn btn-primary">
                            {{ $t('message.template.save') }}
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
                        <h4 class="modal-title">{{ $t('message.template.ut_title') }}</h4>
                    </div>
                    <div class="modal-body relative">
                        <form class="row" action="">

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.country_id}" class="form-group">
                                    <label style="color: #333">{{ $t('message.template.al_lang') }}</label>
                                    <div>
                                        <select v-model="emailUpdate.country_id" class="form-control pull-right">
                                            <option value="0">-- {{ $t('message.template.al_select_lang') }} --</option>
                                            <option v-for="option in listLanguages.data" v-bind:value="option.id">
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
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.mail_name}" class="form-group">
                                    <label style="color: #333">{{ $t('message.template.al_email') }}</label>
                                    <input
                                        type="text"
                                        v-model="emailUpdate.mail_name"
                                        class="form-control"
                                        value=""
                                        required="required"
                                        :placeholder="$t('message.template.al_enter_email')">

                                    <span
                                        v-if="messageForms.errors.mail_name"
                                        v-for="err in messageForms.errors.mail_name"
                                        class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3" v-if="user.isOurs === 0">
                                <label style="color: #333">{{ $t('message.template.category') }}</label>

                                <div>
                                    <select v-model="emailUpdate.category" class="form-control pull-right">
                                        <option value="null">{{ $t('message.template.none') }}</option>
                                        <option v-for="category in categories" :value="category.value">
                                            {{ category.label }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3" v-if="user.isOurs === 0">
                                <label style="color: #333">{{ $t('message.template.type') }}</label>

                                <div>
                                    <select v-model="emailUpdate.type" class="form-control pull-right">
                                        <option value="null">{{ $t('message.template.none') }}</option>
                                        <option v-for="type in types" :value="type.value">
                                            {{ type.label }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.title}" class="form-group">
                                    <label style="color: #333">{{ $t('message.template.al_ip_title') }}</label>
                                    <input
                                        type="text"
                                        v-model="emailUpdate.title"
                                        class="form-control" value=""
                                        required="required"
                                        :placeholder="$t('message.template.al_enter_title')">

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
                                    <label style="color: #333">{{ $t('message.template.al_content') }}</label>
                                    <textarea
                                        rows="10"
                                        type="text"
                                        v-model="emailUpdate.content"
                                        class="form-control"
                                        value=""
                                        required="required"
                                        :placeholder="$t('message.template.al_enter_content')">

                                    </textarea>

                                    <span
                                        v-if="messageForms.errors.content"
                                        v-for="err in messageForms.errors.content"
                                        class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                        </form>
                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            {{ $t('message.template.close') }}
                        </button>
                        <button type="button" @click="submitUpdate" class="btn btn-primary">
                            {{ $t('message.template.save') }}
                        </button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        name: 'emailTemplateSystem',

        data() {
            return {
                emailModel: {
                    id: 0,
                    title: '',
                    content: '',
                    country_id: 0,
                    is_general_template: false,
                    category: '',
                    type: '',
                },

                emailUpdate: {
                    id: 0,
                    title: '',
                    content: '',
                    country_id: 0,
                    category: '',
                    type: '',
                },

                filterModel: {
                    title: this.$route.query.title || '',
                    is_general_template: this.$route.query.is_general_template || false,
                    mail_name: this.$route.query.mail_name || '',
                    category: this.$route.query.category || '',
                    category_temp: this.$route.query.category_temp || '',
                    type: this.$route.query.type || '',
                    type_temp: this.$route.query.type_temp || '',
                    mail_name_temp: this.$route.query.mail_name_temp || '',
                    content: this.$route.query.content || '',
                    title_temp: this.$route.query.title_temp || '',
                    content_temp: this.$route.query.content_temp || '',
                    country_id: this.$route.query.country_id || 0,
                    country_id_temp: this.$route.query.country_id_temp || '',
                    page: this.$route.query.page || 0,
                    per_page: this.$route.query.per_page || 10,
                    paginate: 1
                },

                listPageOptions: [5, 10, 25, 50, 100],
                isLoadingTable: false,
                isPopupLoading: false,
            };
        },

        async created() {
            await this.$store.dispatch('actionGetCountryList');
            this.getEmailList({
                params: this.filterModel
            });


            let language = this.listLanguages.data;
            if ( language.length === 0 ) {
                this.getListLanguages();
            }
        },

        computed: {
            ...mapState({
                user: state => state.storeAuth.currentUser,
                emailList: state => state.emailTemplateSystem.emailList,
                messageForms: state => state.emailTemplateSystem.messageForms,
                countryList: state => state.storeSystem.countryList,
                listLanguages: state => state.storePublisher.listLanguages,
            }),

            pagination() {
                return {
                    props: {
                        callMethod: ""
                    },
                    template: `<div class="paging_simple_numbers">${this.emailList.pagination}</div>`,
                    methods: {
                        async goToPage(pageNum) {
                            this.callMethod(pageNum);
                        }
                    }
                }
            },

            categories () {
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

            types () {
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
            }
        },

        methods: {

            async getListLanguages() {
                await this.$store.dispatch('actionGetListLanguages');
            },

            async getEmailList(params) {
                this.isLoadingTable = true;
                await this.$store.dispatch('actionGetEmailTemplateList', params);
                this.isLoadingTable = false;
            },

            async doSearchList() {
                let that = this;
                that.filterModel.title = that.filterModel.title_temp;
                that.filterModel.content = that.filterModel.content_temp;
                that.filterModel.country_id = that.filterModel.country_id_temp;
                that.filterModel.mail_name = that.filterModel.mail_name_temp;
                that.filterModel.category = that.filterModel.category_temp;
                that.filterModel.type = that.filterModel.type_temp;
                that.filterModel.is_general_template = that.filterModel.is_general_template ? 0:1;

                this.$router.push({
                    query: that.filterModel,
                });

                this.getEmailList({
                    params: that.filterModel
                });
            },

            clearSearch() {
                let that = this;

                that.filterModel.title = '';
                that.filterModel.title_temp = '';
                that.filterModel.content = '';
                that.filterModel.content_temp = '';
                that.filterModel.country_id = 0;
                that.filterModel.country_id_temp = 0;
                that.filterModel.mail_name = '';
                that.filterModel.mail_name_temp = '';
                that.filterModel.category = '';
                that.filterModel.category_temp = '';
                that.filterModel.type = '';
                that.filterModel.type_temp = '';

                this.$router.push({
                    query: that.filterModel,
                });

                this.getEmailList({
                    params: that.filterModel
                });
            },

            Export2Word(element, filename = ''){
                let preHtml = "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'><head><meta charset='utf-8'><title>Export HTML To Doc</title></head><body>";
                let postHtml = "</body></html>";
                let lang = (element.language) ? element.language.name: ''
                let content = `
                    <b>Title: </b> <br>`+ element.title +` <br><br>
                    <b>Template name: </b> <br>`+ element.mail_name +` <br><br>
                    <b>Language: </b> <br>`+ lang +` <br><br><br>
                    <b>Content: </b><br>
                    <p>`+ element.content +`</p> <br>
                `;
                let html = preHtml+content+postHtml;

                let blob = new Blob(['\ufeff', html], {
                    type: 'application/msword'
                });

                // Specify link url
                let url = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(html);

                // Specify file name
                filename = filename?filename+'.doc':'document.doc';

                // Create download link element
                let downloadLink = document.createElement("a");

                document.body.appendChild(downloadLink);

                if(navigator.msSaveOrOpenBlob ){
                    navigator.msSaveOrOpenBlob(blob, filename);
                }else{
                    // Create a link to the file
                    downloadLink.href = url;

                    // Setting the file name
                    downloadLink.download = filename;

                    //triggering the function
                    downloadLink.click();
                }

                document.body.removeChild(downloadLink);
            },

            clearModel() {
                this.emailModel = {
                    id: 0,
                    name: '',
                    code: '',
                    category: '',
                    type: '',
                };
            },

            async goToPage(pageNum) {
                let that = this;
                that.filterModel.page = pageNum;
                this.$router.push({
                    query: that.filterModel,
                });

                await this.getEmailList({
                    params: that.filterModel
                });
            },

            doAdd() {
                this.$store.dispatch('clearMessageFormEmailTemplate');
            },

            doEdit(item) {
                this.$store.dispatch('clearMessageFormEmailTemplate');
                this.emailUpdate = JSON.parse(JSON.stringify(item))
            },

            async doDelete(item) {
                let self = this;

                if (!confirm(self.$t('message.template.dl_info') + item.title + '" ? ')) return;
                this.isLoadingTable = true;
                await this.$store.dispatch('actionDeleteEmailTemplate', {  params: { id: item.id } });

                if (this.messageForms.action === 'deleted_tpl') {
                    this.getEmailList({
                        params: this.filterModel
                    });
                }
            },

            async submitAdd() {
                let that = this;
                this.isPopupLoading = true;

                await this.$store.dispatch('actionAddTemplate', that.emailModel);
                this.isPopupLoading = false;

                if (this.messageForms.action === 'saved_template') {
                    this.clearModel();
                    this.getEmailList({
                        params: this.filterModel
                    });

                    await swal.fire(
                        that.$t('message.template.alert_saved'),
                        that.$t('message.template.alert_saved_success'),
                        'success'
                    )
                }
            },

            async submitUpdate() {
                let self = this;

                this.isPopupLoading = true;
                await this.$store.dispatch('actionUpdateEmailTemplate', this.emailUpdate);
                this.isPopupLoading = false;

                if (this.messageForms.action === 'updated_tpl') {
                    for (var index in this.emailList.data) {
                        if (this.emailList.data[index].id === this.emailUpdate.id) {
                            this.emailList.data[index] = this.emailUpdate;

                            for (var indct in this.countryList.data) {
                                if (this.countryList.data[indct].id === this.emailUpdate.country_id) {
                                    this.emailList.data[index].country = this.countryList.data[indct];
                                    break;
                                }
                            }
                            break;
                        }
                    }

                    await swal.fire(
                        self.$t('message.template.alert_updated'),
                        self.$t('message.template.alert_updated_success'),
                        'success'
                    )
                }
            },

            getCategoryClass (category) {
                return {
                    'bg-primary': category === 'prospect',
                    'bg-success': category === 'follow',
                    'bg-danger': category === null,
                }
            },

            getCategoryLabel (category) {
                let self = this;

                let categories = {
                    prospect:  self.$t('message.template.prospect'),
                    follow: self.$t('message.template.follow')
                }

                return category ? categories[category] : 'N/A';
            },

            getTypeClass (type) {
                return {
                    'bg-info': type === 'corporate',
                    'bg-warning': type === 'straight',
                    'bg-danger': type === null,
                }
            },

            getTypeLabel (type) {
                let self = this;

                let types = {
                    corporate:  self.$t('message.template.corporate'),
                    straight: self.$t('message.template.straight')
                }

                return type ? types[type] : 'N/A';
            },
        },
    }
</script>
