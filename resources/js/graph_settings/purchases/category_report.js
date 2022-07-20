import _ from 'underscore';
import __ from "lodash";

export default {
    categoryReportGraphSettings () {
        return {
            chart : {
                type : 'bar',
                height : 600,
            },
            colors : [
                '#008FFB',
                '#00E396',
                '#FEB019',
                '#FF4560',
                '#775DD0',
            ],
            plotOptions : {
                bar : {
                    horizontal : false,
                },

                dataLabels: {
                    position: 'bottom'
                },
            },
            stroke : {
                width : 1,
                colors : ['#fff']
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
            },
            yaxis : {
                tickAmount : 10
            },
            tooltip : {
                y : {
                    formatter : function (val) {
                        return '$' + val
                    }
                }
            },
            fill : {
                opacity : 1

            },
            legend : {
                position : 'top',
                horizontalAlign : 'left',
                offsetX : 40,
                formatter: function(seriesName, opts) {
                    return [seriesName, " - $", __.sum(opts.w.globals.series[opts.seriesIndex])]
                }
            },
            noData: {
                offsetX: 0,
                offsetY: 0,
                align: "center",
                verticalAlign: "middle",
                text: "No Data Available",
                style: {
                    color: 'red',
                    fontSize: '14px',
                }
            },
        };
    },

    categoryReportGraphData (data) {
        return [{
            name: 'OP-Cost-IT',
            data :_.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.opCostIt
                }
            }))
        },{
            name: 'Marketing-Content',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.marketingContent
                }
            }))
        },{
            name: 'Marketing-SEO',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.marketingSEO
                }
            }))
        },{
            name: 'Marketing-Travel',
            data :_.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.marketingTravel
                }
            }))
        },{
            name: 'IT Tool',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.itTool
                }
            }))
        }];
    },
}
