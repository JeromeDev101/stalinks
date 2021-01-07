<template>
    <div class="row">
        <section class="content-header col-sm-12">
            <h1>Dashboard Admin</h1>
            <hr>
            <div class="my-3">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <!-- <label for="">Date</label> -->
                            <div class="input-group">
                                <select name="" class="form-control" v-model="filterModel.date_type" @change="changeSelection">
                                    <option value="Monthly">Monthly</option>
                                    <option value="Range Date">Range Date</option>
                                </select>
                                <select class="form-control" v-model="filterModel.monthly" v-show="isMonthly">
                                    <option value="1">January</option>
                                    <option value="2">February</option>
                                    <option value="3">March</option>
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">June</option>
                                    <option value="7">July</option>
                                    <option value="8">August</option>
                                    <option value="9">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                                <input type="date" class="form-control" v-model="filterModel.date1" v-show="isRangeDate">
                                <input type="date" class="form-control" v-model="filterModel.date2" v-show="isRangeDate">

                                <button class="btn btn-default" @click="fillData('with')">Filter</button>
                                <button class="btn btn-default">Clear</button>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
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
            isRangeDate: false,
            isMonthly: true,
            filterModel: {
                date_type: 'Monthly',
                monthly: '',
                date1: '',
                date2: '',
            },
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
        this.fillData('without')
    },

    methods: {
        changeSelection() {
            let date_type = this.filterModel.date_type

            if( date_type != 'Monthly' ) {
                this.isRangeDate = true;
                this.isMonthly = false;
            } else {
                this.isRangeDate = false;
                this.isMonthly = true;
            }
        },

        fillData(isFilter) {
            axios.get('/api/dashboard-admin',{
                params: {
                    date_type: this.filterModel.date_type,
                    monthly: this.filterModel.monthly,
                    date1: this.filterModel.date1,
                    date2: this.filterModel.date2,
                    is_filter: isFilter == 'with' ? true: false,
                }
            })
            .then((res) => {
                this.datacollection = res.data.url_statistics;
                this.datacollection2 = res.data.seller_statistics;
            })
        },
    }
}
</script>
