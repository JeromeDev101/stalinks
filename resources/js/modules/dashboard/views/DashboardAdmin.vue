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
        this.fillData2()
    },

    methods: {
        fillData2() {
            this.datacollection2 = {
                labels: ['15-Oct', '30-Oct', '15-Nov', '30-Nov', '15-Dec', '30-Dec'],
                datasets: [
                    {
                        label: 'New',
                        backgroundColor: 'rgba(255,255,255,000.2)',
                        borderColor: '#ed13d7',
                        data: [
                            this.getRandomInt(), 
                            this.getRandomInt(),
                            this.getRandomInt(),
                            this.getRandomInt(),
                            this.getRandomInt(),
                            this.getRandomInt()
                        ]
                    }, {
                        label: 'GotContacts',
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
                        label: 'Contacted',
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
                        label: 'InTouched',
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
                    }, {
                        label: 'Qualified',
                        backgroundColor: 'rgba(255,255,255,000.2)',
                        borderColor: '#0cdfeb',
                        data: [
                            this.getRandomInt(), 
                            this.getRandomInt(),
                            this.getRandomInt(),
                            this.getRandomInt(),
                            this.getRandomInt(),
                            this.getRandomInt()
                        ]
                    }, {
                        label: 'NoAnswer',
                        backgroundColor: 'rgba(255,255,255,000.2)',
                        borderColor: '#f6ff47',
                        data: [
                            this.getRandomInt(), 
                            this.getRandomInt(),
                            this.getRandomInt(),
                            this.getRandomInt(),
                            this.getRandomInt(),
                            this.getRandomInt()
                        ]
                    }, {
                        label: 'Refused',
                        backgroundColor: 'rgba(255,255,255,000.2)',
                        borderColor: '#f0752e',
                        data: [
                            this.getRandomInt(), 
                            this.getRandomInt(),
                            this.getRandomInt(),
                            this.getRandomInt(),
                            this.getRandomInt(),
                            this.getRandomInt()
                        ]
                    }, {
                        label: 'Unqualified',
                        backgroundColor: 'rgba(255,255,255,000.2)',
                        borderColor: '#7900e3',
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

        fillData() {
            this.datacollection = {
                labels: ['15-Oct', '30-Oct', '15-Nov', '30-Nov', '15-Dec', '30-Dec'],
                datasets: [
                    {
                        label: 'No. Sites',
                        backgroundColor: 'rgba(255,255,255,000.2)',
                        borderColor: '#ed13d7',
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
