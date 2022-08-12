export const files = {
    methods: {
        checkFile (array, mode) {
            return array.some(e => e[mode] === 1)
        },

        getFile (array, mode) {
            return array.find(x => x[mode] === 1).path
        },

        getFileName (path) {
            let fileNameExtension = path.replace(/^.*[\\\/]/, '');
            return fileNameExtension.substring(0, fileNameExtension.lastIndexOf('.'));
        },

        getFileExtension (path) {
            let fileNameExtension = path.replace(/^.*[\\\/]/, '');
            return fileNameExtension.split('.').pop();
        },

        checkFileExtension (extension) {
            let file = ['doc','docx','pdf','csv','xlsx','xls'];
            let image = ['jpeg','png','jpg','gif','svg'];

            return file.includes(extension.toLowerCase())
                ? 'file'
                : image.includes(extension.toLowerCase())
                    ? 'image'
                    : null
        }
    }
}
