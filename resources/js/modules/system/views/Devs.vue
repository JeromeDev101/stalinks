<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $t('message.devs.d_title') }}</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <div class="row mb-4">
            <div class="col-1">
                <button @click="getResources" class="btn btn-success" :class="isButtonLoading ? 'disabled' : ''">
                    {{ $t('message.devs.d_reload') }}
                </button>
            </div>
        </div>

        <div class="row">
            <template v-for="resource in resources">
                <div @click="alert(target)" :class="'col-sm-12 col-md-6 col-lg-4 col-xl-'+resource.columnSize" v-for="target in resource.targets" role="button">
                    <div class="info-box" :class="target.result ? (target.result.healthy ? 'bg-success' : 'bg-danger') : 'bg-warning'">
                        <span class="info-box-icon" :class="target.result ? (target.result.healthy ? 'bg-success' : 'bg-danger') : 'bg-warning'"><i class="far" :class="target.result ? (target.result.healthy ? 'fa-check-circle' : 'fa-times-circle') : 'fa-question-circle'"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ resource.name }}</span>
                            <span class="info-box-number" v-if="target.name !== 'default'">{{ target.display }}</span>
                        </div>

                        <div class="overlay" v-if="resource.isLoading">
                            <i class="fas fa-3x fa-spin fa-sync-alt"></i>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
export default {
    name : "Devs",

    data() {
        return {
            resources : null,
            isButtonLoading: false
        }
    },

    mounted() {
        this.getResources();
    },

    methods: {
        getResources() {
            this.isButtonLoading = true;

            axios.get('/api/health/resources')
            .then((response) => {
                this.resources = response.data;

                let that = this;
                Object.keys(this.resources).forEach(async function (key) {
                    await that.getResource(key, that.resources[key]);
                });

                this.isButtonLoading = false;
            });
        },

        alert(target) {
            let self = this;
            let message = target.result.healthy ? self.$t('message.devs.alert_no_error') : target.result.errorMessage
            let type = target.result.healthy ? 'success' : 'error';

            swal.fire(
                target.resource.name,
                message,
                type
            )
        },

        getResource(resource, data) {
            this.resources[resource].isLoading = true;

            axios.get('/api/health/resources/' + data.slug + '?flush=1')
            .then((response) => {
                this.resources[resource] = response.data;
            })
        },
    }
}
</script>

<style scoped>

</style>
