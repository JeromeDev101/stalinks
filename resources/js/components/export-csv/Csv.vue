<template>
    <download-excel
    class="col-md3 mb-3 btn btn-success"
    :data = "dataText"
    :fields = "dataFields"
    worksheet = "My Worksheet"
    :name = "name">
    Download CSV
   </download-excel>
</template>
<script>
import { maxHeaderSize } from 'http';
export default {
    props: [
        'data',
        'fileds',
        'nameFile'
    ],
    data() {
        return {
            nameExport: 'application.csv'
        }
    },
    computed: {
      dataText: function () {
          return this.data
        },
      dataFields: function () {
          return this.fileds
      },
      name: function () {
          return this.nameFile
      }
  }
}
</script>>