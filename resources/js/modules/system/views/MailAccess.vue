<template>
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
<!--                        <div class="modal-load overlay float-right">
                            <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                            <span v-if="messageForms.message !== '' && !isPopupLoading"
                                  :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                                    {{ messageForms.message }}
                                </span>
                        </div>-->
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
    </div>
</template>

<script>
import {mapState} from "vuex";

export default {
    name : "MailAccess",

    props : [
        'messageForms',
        'isPopupLoading'
    ],

    data() {
        return {
            emailAccessModel : {
                user_id : 0,
                username : '',
                work_mail : '',
                emails : []
            },
        }
    },

    computed : {
        ...mapState({
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

    created() {
        this.getEmailAccessList();
    },

    methods : {
        async getEmailAccessList() {
            this.isLoadingTable = true;
            await this.$store.dispatch('actionGetEmailAccessList');
            this.isLoadingTable = false;
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
    }
}
</script>

<style scoped>

</style>
