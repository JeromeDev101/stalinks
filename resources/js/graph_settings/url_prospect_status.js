import _ from 'underscore';

export default {
    graphSetting() {
        return {
            chart : {
                type : 'line',
                height : 600,
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
                '#3F51B5',
                '#5A2A27',
                '#1B998B',
                '#F86624',
                '#2E294E',
                '#546E7A',
                '#90EE7E',
                '#F9A3A4',
                '#43BCCD'
            ],
            stroke : {
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
            name: 'New',
            data :_.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.new
                }
            }))
        },{
            name: 'Crawl Failed',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.crawl_failed
                }
            }))
        },{
            name: 'Contacts Null',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.contacts_null
                }
            }))
        },{
            name: 'Got Contacts',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.got_contacts
                }
            }))
        },{
            name: 'Got Email',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.got_email
                }
            }))
        },{
            name: 'Contacted',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.contacted
                }
            }))
        },{
            name: 'Contacted via Form',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.contacted_via_form
                }
            }))
        },{
            name: 'No Answer',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.no_answer
                }
            }))
        },{
            name: 'Refused',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.refused
                }
            }))
        },{
            name: 'In Touch',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.in_touch
                }
            }))
        },{
            name: 'Undefined',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.undefined
                }
            }))
        },{
            name: 'Unqualified',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.unqualified
                }
            }))
        },{
            name: 'Qualified',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.qualified
                }
            }))
        },{
            name: 'Total',
            data : _.toArray(_.map(data, function (datum) {
                return {
                    x: datum.xaxis,
                    y : datum.total
                }
            }))
        }];
    },
}
