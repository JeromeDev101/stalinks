<template>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header d-flex">
                    <h3 class="box-title align-self-center">Mail Signature</h3>

                    <button
                        class="btn btn-success ml-auto"
                        data-toggle="modal"
                        data-target="#modal-add"

                        @click="">

                        <i class="fa fa-plus"></i>
                    </button>

                    <div class="input-group input-group-sm float-right" style="width: 65px">
                        <select
                            v-model="filterModel.paginate"
                            style="height: 37px;"
                            class="form-control pull-right"

                            @change="">

                            <option v-for="value in listPageOptions" :value="value">{{ value }}</option>
                        </select>
                    </div>
                </div>

                <div class="box-body no-padding relative">
                    <table class="table table-hover table-bordered table-striped">
                        <tbody>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>Name</th>
                                <th>User</th>
                                <th>Actions</th>
                            </tr>

                            <tr>
                                <td style="max-width: 30px;"></td>

                                <td>
                                    <div class="form-group">
                                        <input type="text" v-model="filterModel.name"  class="form-control pull-right" placeholder="Search Name">
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group">
                                        <select v-model="filterModel.user" class="form-control pull-right">
                                            <option value="">Search User</option>
                                            <option v-for="option in listUsers" :value="option.id">
                                                {{ option.username }}
                                            </option>
                                        </select>
                                    </div>
                                </td>

                                <td>
                                    <div class="btn-group">
                                        <button
                                            title="Search"
                                            class="btn btn-default"

                                            @click="">

                                            <i class="fa fa-fw fa-search"></i>
                                        </button>

                                        <button
                                            title="Clear"
                                            class="btn btn-default ml-2"

                                            @click="">

                                            <i class="fa fa-fw fa-refresh"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <pagination :data="listEmailSignature" @pagination-change-page="getSignatureList" :limit="8"></pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    data() {
        return {
            listUsers: [],

            listPageOptions: [5, 10, 25, 50, 100],

            filterModel: {
                name: this.$route.query.title || '',
                user: this.$route.query.user || '',
                page: this.$route.query.page || 0,
                paginate: this.$route.query.paginate || 10,
            },
        }
    },

    created() {
        this.getListUsers()

        this.getSignatureList()
    },

    computed: {
        ...mapState({
            user: state => state.storeAuth.currentUser,
            listEmailSignature: state => state.storeMailgun.listEmailSignature,
            messageForms: state => state.storeMailgun.messageForms,
        }),
    },

    methods: {
        getListUsers(){
            axios.get('/api/mail/get-user-list')
            .then(response => {
                this.listUsers = response.data
            })
        },

        async getSignatureList(page = 1) {
            this.isLoadingTable = true;
            this.filterModel.page = page
            await this.$store.dispatch('actionGetEmailSignatureList', {
                params: this.filterModel
            });
            this.isLoadingTable = false;
        },
    },
}
</script>
