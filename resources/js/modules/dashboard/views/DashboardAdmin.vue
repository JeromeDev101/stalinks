<template>
    <div class="row">
        <section class="content-header col-sm-12">
            <h1>Dashboard Admin</h1>
            <br>
        </section>

        <div class="col-lg-12" >
            <div class="box box-primary" style="padding-bottom:0.5em;">

                <div class="box-header">
                    <h3 class="box-title text-primary">Total Seller</h3>

                    <div class="small">
                        <line-chart :chart-data="datacollection" :options="options" :styles="styles"></line-chart>
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

export default {

    name: 'Dashboard',
    components: {
        LineChart
    },
    data() {
        return {
            datacollection: {},
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
            this.datacollection = {
                labels: ['15-Oct', '30-Oct', '15-Nov', '30-Nov', '15-Dec', '30-Dec'],
                datasets: [
                    {
                        label: 'No. Sites',
                        backgroundColor: 'rgba(255,255,255,000.2)',
                        borderColor: '#f87979',
                        data: [
                            this.getRandomInt(), 
                            this.getRandomInt(),
                            this.getRandomInt(),
                            this.getRandomInt(),
                            this.getRandomInt(),
                            this.getRandomInt()
                        ]
                    }, {
                        label: 'No. Valid',
                        backgroundColor: 'rgba(255,255,255,000.2)',
                        borderColor: '#ff5454',
                        data: [
                            this.getRandomInt(), 
                            this.getRandomInt(),
                            this.getRandomInt(),
                            this.getRandomInt(),
                            this.getRandomInt(),
                            this.getRandomInt()
                        ]
                    }, {
                        label: 'No. Unchecked',
                        backgroundColor: 'rgba(255,255,255,000.2)',
                        borderColor: '#3530d1',
                        data: [
                            this.getRandomInt(), 
                            this.getRandomInt(),
                            this.getRandomInt(),
                            this.getRandomInt(),
                            this.getRandomInt(),
                            this.getRandomInt()
                        ]
                    }, {
                        label: 'No. Invalid',
                        backgroundColor: 'rgba(255,255,255,000.2)',
                        borderColor: '#54a851',
                        data: [
                            this.getRandomInt(), 
                            this.getRandomInt(),
                            this.getRandomInt(),
                            this.getRandomInt(),
                            this.getRandomInt(),
                            this.getRandomInt()
                        ]
                    }
                ]
            }
        },

        getRandomInt () {
            return Math.floor(Math.random() * (50 - 5 + 1)) + 5
        },
    }
}
</script>
