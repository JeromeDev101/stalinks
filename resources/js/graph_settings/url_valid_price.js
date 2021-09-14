import _ from "underscore";
import __ from 'lodash';

export default {

    urlValidPriceGraphSetting() {
        return {
            chart : {
                type : 'line',
                height : 350,
                // stackType: '100%',
                /*toolbar: {
                    show : true,
                    tools : {
                        download : true,
                        selection : true,
                        zoom : true,
                        zoomin : true,
                        zoomout : true,
                        pan : true,
                        customIcons : []
                    },
                }*/
            },
            markers : {
                size: 5
            },
            colors : [
                '#008FFB',
                '#00E396',
                '#FEB019',
                '#775DD0',
                '#FF4560',
                '#3F51B5'
            ],
            plotOptions : {
                bar : {
                    horizontal : false,
                    dataLabels: {
                        position: 'top', // top, center, bottom,
                    },
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
            dataLabels: {
                enabled: true,
                enabledOnSeries : [
                    0,
                    1,
                    2,
                    3,
                    4
                ],
                offsetY: -10,
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
                tickPlacement: 'between',
            },
            yaxis: [
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
                        text: "Total Uploads",
                        style: {
                            color: '#008FFB',
                        }
                    },
                    tooltip: {
                        enabled: true
                    }
                },{
                    seriesName: 'Upload',
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
                    seriesName: 'Upload',
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
                    seriesName: '%GP',
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
                        style: {
                            color: '#FF4560',
                        }
                    }
                },{
                    seriesName: '%GP',
                    show: false,
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
                        text: "Upload vs Valid %",
                        style: {
                            color: '#775DD0',
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

    urlValidPriceGraphData(data) {
        // var uploads = _.pluck(data, 'upload');

        return [{
            name: 'Upload',
            type: 'column',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    /*x: [
                        datum.xaxis,
                        '(GP: '+ ((datum.quality_price / datum.valid) * 100).toFixed(1) +'%)',
                        '(UV: '+ ((datum.valid / datum.upload) * 100).toFixed(1) +'%)'
                    ],*/
                    x: datum.xaxis,
                    y : datum.upload
                }
            }))
        },{
            name: 'Valid',
            type: 'column',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.valid
                }
            }))
        },{
            name: 'Quality Price',
            type: 'column',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.quality_price
                }
            }))
        },{
            name: '%V',
            type: 'line',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : ((datum.valid / datum.upload) * 100).toFixed(1)
                }
            }))
        },{
            name: '%GP',
            type: 'line',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : ((datum.quality_price / datum.valid) * 100).toFixed(1)
                }
            }))
        }];
    }
}
