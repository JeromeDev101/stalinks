import _ from "underscore";
import __ from 'lodash';

export default {

    sellerValidGraphSetting() {
        return {
            chart : {
                type : 'line',
                height : 350,
            },
            markers : {
                size: 5
            },
            colors : [
                '#008FFB',
                '#00E396',
                '#FEB019',
                '#FF4560',
                '#775DD0',
                '#3F51B5'
            ],
            plotOptions : {
                bar : {
                    horizontal : false,
                    dataLabels: {
                        position: 'top', // top, center, bottom,
                    },
                },
            },
            dataLabels: {
                enabled: true,
                enabledOnSeries : [
                    0,
                    1,
                    2,
                    3,
                    4
                ],
                offsetY: -15,
                formatter: function (val, { seriesIndex, dataPointIndex, w }) {
                    return seriesIndex === 3 || seriesIndex === 4 ? val + '%' : val;
                },
                style: {
                    fontSize: '12px',
                    colors : [
                        "#008FFB",
                        "#00E396",
                        "#FEB019",
                        "#775DD0",
                        "#FF4560"
                    ]
                }
            },
            stroke : {
                width : 1,
                curve: 'smooth'
            },
            xaxis : {
                type: 'category',
                axisTicks : {
                    show : true,
                    borderType : 'solid',
                    color : '#78909C',
                    height : 6,
                    offsetX : 0,
                    offsetY : 0
                },
                tickPlacement: 'between'
            },
            yaxis : [
                {
                    axisTicks: {
                        show: true,
                    },
                    axisBorder: {
                        show: true,
                        color: '#008FFB'
                    },
                    labels: {
                        style: {
                            colors: '#008FFB',
                        }
                    },
                    title: {
                        text: "Total Registration",
                        style: {
                            color: '#008FFB',
                        }
                    },
                    tooltip: {
                        enabled: true
                    }
                },{
                    seriesName: 'Registration',
                    show:false,
                    opposite: true,
                    axisTicks: {
                        show: true,
                    },
                    axisBorder: {
                        show: true,
                        color: '#FF4560'
                    },
                    labels: {
                        style: {
                            colors: '#FF4560',
                        },
                    },
                    title: {
                        style: {
                            color: '#FF4560',
                        }
                    }
                },{
                    seriesName: 'Registration',
                    show:false,
                    opposite: true,
                    axisTicks: {
                        show: true,
                    },
                    axisBorder: {
                        show: true,
                        color: '#FF4560'
                    },
                    labels: {
                        style: {
                            colors: '#FF4560',
                        },
                    },
                    title: {
                        style: {
                            color: '#FF4560',
                        }
                    }
                },{
                    seriesName: '% Valid',
                    opposite: true,
                    axisTicks: {
                        show: true,
                    },
                    axisBorder: {
                        show: true,
                        color: '#775DD0'
                    },
                    labels: {
                        style: {
                            colors: '#775DD0',
                        },
                        formatter: function (val) {
                            return val + '%';
                        },
                    },
                    title: {
                        style: {
                            color: '#775DD0',
                        }
                    }
                },{
                    seriesName: '% Valid',
                    show: false,
                    opposite: true,
                    axisTicks: {
                        show: true,
                    },
                    axisBorder: {
                        show: true,
                        color: '#FF4560'
                    },
                    labels: {
                        style: {
                            colors: '#FF4560',
                        },
                        formatter: function (val) {
                            return val + '%';
                        },
                    },
                    title: {
                        text: "Registration vs Valid w/ URLs %",
                        style: {
                            color: '#FF4560',
                        }
                    }
                }
            ],
            tooltip : {
                y : {
                    formatter : function (val) {
                        return val
                    }
                }
            },
            fill : {
                opacity : 1

            },
            legend : {
                position : 'top',
                horizontalAlign : 'left',
            }
        };
    },

    sellerValidGraphData(data) {
        // var registers = _.pluck(data, 'total_registration');

        return [{
            name: 'Registration',
            type: 'column',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.total_registration
                }
            }))
        },{
            name: 'Valid',
            type: 'column',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.total_valid
                }
            }))
        },{
            name: 'Valid with URLs',
            type: 'column',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.valid_with_url
                }
            }))
        },{
            name: '% Valid',
            type: 'line',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : ((datum.total_valid / datum.total_registration) * 100).toFixed(1)
                }
            }))
        },{
            name: '% URL',
            type: 'line',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : ((datum.valid_with_url / datum.total_registration) * 100).toFixed(1)
                }
            }))
        }];
    }
}
