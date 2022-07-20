import _ from 'underscore';
import __ from "lodash";

export default {
    paymentMethodReportGraphSettings (data) {
        return {
            chart : {
                type : 'bar',
                height : 600,
            },
            colors : this.generateRandomColors(data),
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

    paymentMethodReportGraphData (data, methods) {
        let graphData = [];

        methods.forEach((method) => {
            graphData.push({
                name: method.type,
                data :_.toArray(_.map(data, function (datum) {
                    return {
                        x: datum.xaxis,
                        y : datum[method.type.replace(/\s/g,'').toLowerCase()]
                    }
                }))
            })
        });

        return graphData;
    },

    generateRandomColors (data) {
        let colors = [];

        if (data[0] == null){
            return [
                '#008FFB',
                '#00E396',
                '#FEB019',
                '#FF4560',
                '#775DD0',
            ]
        } else {
            for (let i = 0; i < Object.keys(data[0]).length; i++) {
                colors[i] = '#'+(0x1000000+(Math.random())*0xffffff).toString(16).substr(1,6)
            }

            return colors;
        }
    }
}
