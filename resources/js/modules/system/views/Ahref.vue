<template>
    <div class="card card-outline card-secondary">
        <div class="card-body">
            <div class="progress-group">
                <span class="progress-text">{{ $t('message.IT.a_title') }}</span>
                <span class="progress-number"><b>{{ rows_consume }}</b>/500,000</span>

                <div class="progress sm my-3">
                    <div class="progress-bar progress-bar-aqua"
                         :style="'width:'+ consume_percentage + '%'"></div>
                </div>

                <b>{{ $t('message.IT.a_used') }}: </b> {{ rows_consume }} <br/>
                <b>{{ $t('message.IT.a_remaining') }}: </b> {{ rows_remaining }} <br/>
                <b>Limit reset date: </b> Every 10th of the month<br/>
            </div>

            <button class="btn btn-link pull-right" @click="getSubscriptionInfo">
                {{ $t('message.IT.a_check_update') }}
                <i v-if="loadingUpdate" class="fa fa-refresh fa-spin"></i>
            </button>
        </div>
    </div>
</template>

<script>
import {mapState} from "vuex";

export default {
    name : "Ahref",

    data() {
        return {
            rows_consume : 0,
            consume_percentage : 0,
            rows_remaining : 0,
            loadingUpdate : false,

        }
    },

    computed : {
        ...mapState({
            Info : state => state.storeSystem.Info,
        }),
    },

    mounted() {
        this.getSubscriptionInfo();
    },

    methods : {
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
    },
}
</script>

<style scoped>

</style>
