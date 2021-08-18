import _ from "underscore";
import __ from 'lodash';

export default {

    urlValidPriceGraphSetting() {
        return {
            chart : {
                type : 'bar',
                height : 600,
                // stacked: true,
                // stackType: '100%',
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
                tickPlacement: 'on',
            },
            yaxis : {
                tickAmount : 10,
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

    urlValidPriceGraphData(data) {
        var uploads = _.pluck(data, 'upload');

        return [{
            name: 'Upload (Total: '+ __.sum(uploads) +')',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: [
                        datum.xaxis,
                        '(GP: '+ ((datum.quality_price / datum.valid) * 100).toFixed(1) +'%)',
                        '(UV: '+ ((datum.valid / datum.upload) * 100).toFixed(1) +'%)'
                    ],
                    y : datum.upload
                }
            }))
        },{
            name: 'Valid',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.valid
                }
            }))
        },{
            name: 'Quality Price',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.quality_price
                }
            }))
        }];
    }
}
