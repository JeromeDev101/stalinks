<template>
    <div class="row">
        <div class="col-sm-12">

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Filter</h3>
                </div>

                <div class="box-body m-3">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Search</label>
                                <input v-on:keyup="getBackLinkList()" v-model="fillter.querySearch" v-on:keyup.enter="getBackLinkList()" type="text" name="search" class="form-control" placeholder="Search by external domains or internal domain">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Status</label>
                                <select class="form-control" name="" id="">
                                    <option v-for="status in statusBaclink" v-bind:value="status">{{ status }}</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <button class="btn btn-default">Clear</button>
                            <button @click="getBackLinkList()" type="submit" name="submit" class="btn btn-default">Search</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box">

                <div class="box-header">
                    <h3 class="box-title">Backlinks</h3>
                    
                    <div v-if="Object.keys(listBackLink).length !== 0" class="pull-right">
                        <download-csv
                            :data = "listBackLink.data"
                            :fileds = "data_filed"
                            :nameFile = "file_csv">
                        </download-csv>
                    </div>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-bordered table-striped rlink-table">
                        <tbody>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>URL Publisher</th>
                                <th>URL Advertiser</th>
                                <th>Link To</th>
                                <th>Price</th>
                                <th>Anchor Text</th>
                                <th>Date for Proccessing</th>
                                <th>Date Completed</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            <tr v-for="(backLink, index) in listBackLink.data" :key="index">
                                <td class="center-content">{{ index + 1 }}</td>
                                <td>{{ backLink.publisher == null ? backLink.ext_domain.domain:backLink.publisher.url }}</td>
                                <td>{{ backLink.int_domain == null ? '':backLink.int_domain.domain }}</td>
                                <td><a href="backLink.link">{{ backLink.link }}</a></td>
                                <td>$ {{ convertPrice(backLink.price) }}</td>
                                <td>{{ backLink.anchor_text }}</td>
                                <td>{{ backLink.date_process }}</td>
                                <td>{{ backLink.live_date }}</td>
                                <td>{{ backLink.status }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-default" @click="editBackLink(backLink)" title="Edit"><i class="fa fa-fw fa-edit"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <pagination :data="listBackLink" @pagination-change-page="getBackLinkList($event)"></pagination>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!--    Modal Add Backlink-->
        <div v-if="openModalBackLink" class="modal fade"  ref="modalEditBacklink" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add BackLink Domain</h4>
                        <div class="modal-load overlay float-right">
                            <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>
                            <span v-if="messageBacklinkForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageBacklinkForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageBacklinkForms.message }}
                            </span>
                        </div>
                    </div>
                    <div class="modal-body relative">
                        <form class="row" action="">
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.ext_domain_id}" class="form-group">
                                    <label style="color: #333">URL Publisher</label>
                                    <input type="text" v-model="modelBaclink.ext_domain.domain" :disabled="true" class="form-control" required="required" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.int_domain_id}" class="form-group">
                                    <label style="color: #333">URL Advertiser</label>
                                    <input type="text" v-model="modelBaclink.int_domain.domain" :disabled="true"  class="form-control" required="required" >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.price}" class="form-group">
                                    <div>
                                        <label style="color: #333">Price</label>
                                        <input type="number" v-model="modelBaclink.price" class="form-control" value="" required="required" >
                                        <span v-if="messageBacklinkForms.errors.price" v-for="err in messageBacklinkForms.errors.price" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.int_domain_id}" class="form-group">
                                    <label style="color: #333">Buyer Backlink</label>
                                    <input type="text" v-model="modelBaclink.user.name" :disabled="true"  class="form-control" required="required" >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.anchor_text}" class="form-group">
                                    <div>
                                        <label style="color: #333">Anchor text</label>
                                        <input type="text" v-model="modelBaclink.anchor_text" class="form-control" required="required" >
                                        <span v-if="messageBacklinkForms.errors.anchor_text" v-for="err in messageBacklinkForms.errors.anchor_text" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.link}" class="form-group">
                                    <div>
                                        <label style="color: #333">Link</label>
                                        
                                        <input type="text" v-model="modelBaclink.link" class="form-control"  required="required" >
                                        <span v-if="messageBacklinkForms.errors.link" v-for="err in messageBacklinkForms.errors.link" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>
                            </div>

                              <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.live_date}" class="form-group">
                                    <div>
                                        <label style="color: #333">Date Completed</label>
                                        <input type="date" v-model="modelBaclink.live_date" class="form-control" >
                                        <span v-if="messageBacklinkForms.errors.live_date" v-for="err in messageBacklinkForms.errors.live_date" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.status}" class="form-group">
                                    <div>
                                        <label style="color: #333">Status</label>
                                        <select  class="form-control pull-right" v-model="modelBaclink.status" style="height: 37px;">
                                          <option v-for="status in statusBaclink" v-bind:value="status">{{ status }}</option>
                                        </select>
                                        <span v-if="messageBacklinkForms.errors.status" v-for="err in messageBacklinkForms.errors.status" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">Payment Type</label>
                                        <input type="text" class="form-control" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">Article ID</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">Date Processed</label>
                                        <input type="date" v-model="modelBaclink.date_process" class="form-control">
                                    </div>
                                </div>
                            </div>

                        </form>
                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" @click="closeModalBacklink">Close</button>
                        <button type="button" :disabled="checkSelectIntDomain" @click="submitEditBacklink" class="btn btn-primary">Save</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--    End Modal Add-->
    </div>
</template>
<script>
    import Hepler from '@/library/Helper';
    import { mapState } from 'vuex';
    import { Domain } from 'domain';
    import DownloadCsv from '@/components/export-csv/Csv.vue'
    import _ from 'lodash'
    
    export default {
      name: 'BackLinkList',
      data() {
        return {
          file_csv: 'baclink.xls',
          statusBaclink: ['Processing', 'Content writing', 'Content sent', 'Live'],
          data_filed: {
            'Ext Domain': 'ext_domain.domain',
            'Int Domain': 'int_domain.domain',
            'Link': 'link',
            'Price': 'price',
            'Anchor Text': 'anchor_text',
            'Live Date': 'live_date',
            'Status': 'status',
            'User': 'user.name'
          },
          json_meta: [
            [{
              'key': 'charset',
              'value': 'utf-8'
            }]
          ],
          page: this.$route.query.page || 1,
          modalAddBackLink: false,
          modelBaclink: {
            ext_domain: {
              domain: ''
            },
            int_domain: {
              domain: ''
            },
            user: {
              name: ''
            }
          },
          loadIntDomain: false,
          isPopupLoading: false,
        }
      },
      async created() {
          await this.$store.dispatch('actionCheckAdminCurrentUser', { vue: this });
          await this.getBackLinkList();
      },
    
      computed: {
        ...mapState({
          listBackLink: state => state.storeBackLink.listBackLink,
          fillter: state => state.storeBackLink.fillter,
          user: state => state.storeAuth.currentUser,
          messageBacklinkForms: state => state.storeBackLink.messageBacklinkForms,
          
        }),
        openModalBackLink() {
          if (this.modalAddBackLink = true) {
              return true
          }
    
          return false
        },
    
        checkSelectIntDomain () {
          if (this.modelBaclink.int_domain_id == 0) {
              return true
          }
    
          return false
        },
      },
    
      mounted() {
        $(this.$refs.modalEditBacklink).on("hidden.bs.modal", this.handleCloseBacklinkModal)
      },
    
      methods: {
        getBackLinkList: _.debounce(async function(page) {
          if (page) {
            this.page = page
          }
          this.$router.push({
            query: {
              page: this.page,
            },
          })
          await this.$store.dispatch('actionGetBackLink', {
            vue: this,
            page: this.page,
            params: this.fillter
          });
        }, 200),
    
        checkArray(array) {
          return Hepler.arrayNotEmpty(array);
        },
    
        editBackLink(baclink) {
            this.modalAddBackLink = true
            let that = Object.assign({}, baclink)

            this.modelBaclink.id = that.id
            this.modelBaclink.ext_domain_id = that.publisher == null ? that.ext_domain.id:that.publisher.id
            this.modelBaclink.ext_domain.domain = that.publisher == null ? that.ext_domain.domain:that.publisher.url
            this.modelBaclink.int_domain.domain = that.int_domain == null ? '':that.int_domain.domain
            this.modelBaclink.user.name = that.user.name
            this.modelBaclink.anchor_text = that.anchor_text
            this.modelBaclink.price = that.price
            this.modelBaclink.link = that.link
            this.modelBaclink.live_date = that.live_date
            this.modelBaclink.status = that.status
            this.modelBaclink.user_id = that.user_id
            this.modelBaclink.date_process = that.date_process

            let element = this.$refs.modalEditBacklink
            $(element).modal('show')          
        },

        closeModalBacklink() {
          this.modalAddBackLink = false
          let element = this.$refs.modalEditBacklink
          $(element).modal('hide')
        },

        async submitEditBacklink () {
            await this.$store.dispatch('actionSaveBacklink', {
                params: this.modelBaclink
            })
            
            if (this.messageBacklinkForms.action === 'saved_backlink') {
                this.closeModalBacklink()
                this.getBackLinkList()
            }
        },

        handleCloseBacklinkModal () {
            this.modalAddBackLink =  false
            this.$store.dispatch('clearMessageBacklinkForm')
        },

        convertPrice(price) {
            return parseFloat(price).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }
      },
       components: {
          DownloadCsv
        }
    }
</script>