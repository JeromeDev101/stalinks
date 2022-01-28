import _ from 'lodash';

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
            name: 'Yes',
            data : this.dataWithoutNull(data, 'yes'),
        },{
            name: 'No',
            data : this.dataWithoutNull(data, 'no')
        }];
    },

    mapData(data, answer) {
        let fields = [
            'one',
            'two',
            'three',
            'four',
            'five'
        ];

        return _.map(_.find(data, ['set', 'a']), function (value, key) {

            let yValue = 0;

            if (_.includes(fields, key)) {
                let temp = _.countBy(data, (ob)=> {
                    return ob[key] ? ob[key] : null
                })[answer];

                yValue = temp ? temp : 0;
            } else {
                yValue = null;
            }

            return {
                x: _.includes(fields, key) ? key : null,
                y : yValue,
            }
        })
    },

    dataWithoutNull(data, answer) {
        return _.toArray(_.pickBy(this.mapData(data, answer), function (d) {
            return d.x !== null && d.y !== null;
        }))
    },
}

