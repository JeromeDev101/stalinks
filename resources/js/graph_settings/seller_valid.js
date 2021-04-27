import _ from "underscore";

export default {

    sellerValidGraphSetting() {
        return {
            chart : {
                type : 'bar',
                height : 600,
                toolbar: {
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
                }
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
                tickPlacement: 'on'
            },
            yaxis : {
                tickAmount : 10
            },
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
                offsetX : 40
            }
        };
    },

    sellerValidGraphData(data) {

        return [{
            name: 'Total',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.total_registration
                }
            }))
        },{
            name: 'Valid',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.total_valid
                }
            }))
        }];
    }
}
