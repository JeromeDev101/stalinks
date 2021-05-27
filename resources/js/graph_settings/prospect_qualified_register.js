import _ from 'underscore';

export default {
    graphSetting() {
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
                '#3F51B5'
            ],
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

    graphData(data) {
        return [{
            name: 'Qualified',
            data :_.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.qualified
                }
            }))
        },{
            name: 'Registered',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.total
                }
            }))
        }];
    },
}
