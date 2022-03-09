import _ from 'lodash';

export default {
    graphSetting(number) {
        return {
            labels: this.graphLabels(number),
            chart : {
                type : 'donut',
                height : 350,
            },
            colors : [
                '#008FFB',
                '#00E396',
                '#FEB019',
                '#FF4560',
                '#775DD0',
                '#3F51B5'
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
                position : 'bottom',
                horizontalAlign : 'left',
                offsetX : 40
            },
        };
    },

    graphData(data, number) {
        let tally = _.map(_.filter(data, function(o) {
            return o.set === 'a' && o.type === 'buyer';
        }), number);

        let answers = this.graphLabels(number);

        let totals = _.pick(_.countBy(tally), answers);

        let temp = answers.reduce((r, value) => {
            r[value] = totals[value] || 0;
            return r; }, {});

        return _.map(answers, (value) => {
            return temp[value];
        });
    },

    graphLabels(number) {
        return [
            'yes',
            'no',
        ];
    }
}
