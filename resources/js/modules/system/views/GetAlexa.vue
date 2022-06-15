<template>
    <div class="card card-outline card-secondary">
        <div class="card-body">
            <div class="progress-group">
                <span class="progress-text">{{ $t('message.IT.ga_title') }}</span>
                <span class="progress-number"><b>{{ rows_consume }}</b>/40,000</span>

                <div class="progress sm my-3">
                    <div class="progress-bar progress-bar-aqua"
                         :style="'width:'+ consume_percentage + '%'"></div>
                </div>

                <b>{{ $t('message.IT.a_used') }}: </b> {{ rows_consume }} <br/>
                <b>{{ $t('message.IT.a_remaining') }}: </b> {{ rows_remaining }} <br/>
            </div>

            <button class="btn btn-link pull-right" @click="getUpdates">
                {{ $t('message.IT.a_check_update') }}
                <i v-if="loadingUpdate" class="fa fa-refresh fa-spin"></i>
            </button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import {mapState} from "vuex";

export default {
    name : "GetAlexa",

    data() {
        return {
            rows_consume : 0,
            consume_percentage : 0,
            rows_remaining : 0,
            loadingUpdate : false,

        }
    },

    computed : {

    },

    mounted() {
        this.getUpdates()
    },

    methods : {
       getUpdates() {
           axios.get('/api/alexa-consume')
           .then((res)=> {
                let result = res.data

                let rows_left = 40000 - parseInt(result.value)
                let rows_limit = 40000
                let consume_rows = parseFloat(rows_limit) - parseFloat(rows_left);

                this.rows_remaining = rows_left;
                this.rows_consume = consume_rows;
                this.consume_percentage = (parseFloat(consume_rows) / parseFloat(rows_limit)) * 100;
           })
       }
    },
}
</script>

<style scoped>

</style>
