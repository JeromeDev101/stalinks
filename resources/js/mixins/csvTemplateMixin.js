export const csvTemplateMixin = {
    methods: {
        downloadCsvTemplate(rows, fileName) {
            console.log(rows, fileName);

            let csvContent = "data:text/csv;charset=utf-8,"
                + rows.map(e => e.join(",")).join("\n");

            let encodedUri = encodeURI(csvContent);
            let link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", fileName + ".csv");
            document.body.appendChild(link);

            link.click();
        },
    }
}
