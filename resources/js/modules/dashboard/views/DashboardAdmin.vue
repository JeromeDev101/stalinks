<template>
    <div class="row">
        <section class="content-header col-sm-12">
            <h1>Dashboard Admin</h1>
            <br>
        </section>

        <div class="col-lg-12" >
            <div class="box box-primary" style="padding-bottom:0.5em;">

                <div class="box-header">
                    <h3 class="box-title text-primary">URL Statistics</h3>

                    <div class="small">
                        <line-chart :chart-data="datacollection" :options="options" :styles="styles"></line-chart>
                    </div>
                    
                </div>


            </div>
        </div>


        <div class="col-lg-12" >
            <div class="box box-primary" style="padding-bottom:0.5em;">

                <div class="box-header">
                    <h3 class="box-title text-primary">Seller Sites Statistics</h3>

                    <div class="small">
                        <line-chart :chart-data="datacollection2" :options="options" :styles="styles"></line-chart>
                    </div>
                    
                </div>


            </div>
        </div>


    </div>
</template>

<script>
import { mapState } from 'vuex';
import config from '@/config';
import Hepler from '@/library/Helper';
import LineChart from '@/components/chart/Line.js'
import axios from 'axios';

export default {

    name: 'Dashboard',
    components: {
        LineChart
    },
    data() {
        return {
            datacollection: {},
            datacollection2: {},
            options: {
                responsive: true,
                maintainAspectRatio: false,
				title: {
					display: false,
					text: 'Chart.js Line Chart'
				},
				tooltips: {
					mode: 'index',
					intersect: false,
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Month'
						}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Value'
						}
					}]
				}
            },
            
            styles: {
                height: '500px',
            },

        };
    },

    computed: {
        ...mapState({
            user: state => state.storeAuth.currentUser,
        }),
    },

    async created() {
        //
    },

    mounted() {
        this.fillData()
    },

    methods: {
        fillData() {
            axios.get('/api/dashboard-admin')
                .then((res) => {
                    this.datacollection = res.data.url_statistics;
                    this.datacollection2 = res.data.seller_statistics;
                    console.log(res.data)
                })
        },

        getRandomInt () {
            return Math.floor(Math.random() * (50 - 5 + 1)) + 5
        },
    }
}
</script>
