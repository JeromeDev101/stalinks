export const filterModel = {
    methods: {
        cleanFilterModel (obj) {
            let newObj = {};

            for (let key in obj) {
                if (obj.hasOwnProperty(key)) {
                    if (obj[key] !== null && obj[key] !== '') {

                        if (key === 'date') {
                            if (obj[key].hasOwnProperty('startDate')) {
                                if (obj[key]['startDate'] !== null && obj[key]['startDate'] !== '') {
                                    newObj[key] = obj[key];
                                }
                            }
                        } else {
                            newObj[key] = obj[key];
                        }
                    }
                }
            }

            return newObj;
        },

        generateFormData (data) {
            let formData = new FormData();

            for (let key in data) {
                if (data.hasOwnProperty(key)) {
                    formData.append(
                        key,
                        data[key] === null
                            ? ''
                            : data[key]
                    );
                }
            }

            return formData;
        },
    }
}
