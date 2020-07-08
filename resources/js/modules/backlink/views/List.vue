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
                                <label for="">Search URL Publisher</label>
                                <!-- <input v-on:keyup="getBackLinkList()" v-model="fillter.querySearch" v-on:keyup.enter="getBackLinkList()" type="text" name="search" class="form-control" placeholder=""> -->
                                <input v-model="fillter.querySearch" type="text" name="search" class="form-control" placeholder="">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Status</label>
                                <select class="form-control" name="" v-model="fillter.status">
                                    <option value="">All</option>
                                    <option v-for="status in statusBaclink" v-bind:value="status">{{ status }}</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <button class="btn btn-default" @click="clearSearch">Clear</button>
                            <button @click="getBackLinkList()" type="submit" name="submit" class="btn btn-default">Search <i v-if="searchLoading" class="fa fa-refresh fa-spin" ></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box">

                <div class="box-header">
                    <h3 class="box-title">Follow up Backlinks</h3>

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
                                <th v-if="user.isAdmin">URL Advertiser</th>
                                <th>Link From</th>
                                <th>Link To</th>
                                <th>Price</th>
                                <th>Anchor Text</th>
                                <th>Date for Proccessing</th>
                                <th>Date Completed</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            <!-- <tr v-if="listBackLink.data.length == 0">
                                <td colspan="11" class="text-center">No record</td>
                            </tr> -->
                            <tr v-for="(backLink, index) in listBackLink.data" :key="index">
                                <td class="center-content">{{ index + 1 }}</td>
                                <td>{{ backLink.publisher.url}}</td>
                                <td v-if="user.isAdmin">{{ backLink.url_advertiser }}</td>
                                <td>{{ backLink.link_from }}</td>
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
                                    <div v-if="user.isAdmin" class="btn-group">
                                        <button class="btn btn-default" @click="deleteBackLink(backLink.id)" title="Delete"><i class="fa fa-fw fa-trash"></i></button>
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
                        <h4 class="modal-title">Follow up Backlinks Information</h4>
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
                                <div class="form-group">
                                    <label style="color: #333">Seller name</label>
                                    <input type="text" v-model="modelBaclink.seller" :disabled="true" class="form-control" required="required" >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">Date Processed</label>
                                        <input type="date" :disabled="isBuyer" v-model="modelBaclink.date_process" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.ext_domain_id}" class="form-group">
                                    <label style="color: #333">URL Publisher</label>
                                    <input type="text" v-model="modelBaclink.ext_domain.domain" :disabled="true" class="form-control" required="required" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: #333">URL Advertiser</label>
                                    <input type="text" v-model="modelBaclink.url_advertiser" class="form-control" required="required" >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.price}" class="form-group">
                                    <div>
                                        <label style="color: #333">Price</label>
                                        <input type="number" v-model="modelBaclink.price" :disabled="isBuyer" class="form-control" value="" required="required" >
                                        <span v-if="messageBacklinkForms.errors.price" v-for="err in messageBacklinkForms.errors.price" class="text-danger">{{ err }}</span>
                                    </div>
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
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.status}" class="form-group">
                                    <div>
                                        <label style="color: #333">Status</label>
                                        <select  class="form-control pull-right" v-model="modelBaclink.status" style="height: 37px;" :disabled="isBuyer">
                                          <option v-for="status in statusBaclink" v-bind:value="status">{{ status }}</option>
                                        </select>
                                        <span v-if="messageBacklinkForms.errors.status" v-for="err in messageBacklinkForms.errors.status" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.link}" class="form-group">
                                    <div>
                                        <label style="color: #333">Link To</label>

                                        <input type="text" v-model="modelBaclink.link" class="form-control"  required="required" >
                                        <span v-if="messageBacklinkForms.errors.link" v-for="err in messageBacklinkForms.errors.link" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.live_date}" class="form-group">
                                    <div>
                                        <label style="color: #333">Date Completed</label>
                                        <input type="date" v-model="modelBaclink.live_date" class="form-control" :disabled="isBuyer">
                                        <span v-if="messageBacklinkForms.errors.live_date" v-for="err in messageBacklinkForms.errors.live_date" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">Article ID</label>
                                        <input type="text" class="form-control" :disabled="isBuyer">
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
    import { mapState, mapSetter } from 'vuex';
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
            'URL Publisher': 'publisher.url',
            'URL Advertiser': 'url_advertiser',
            'Link From': 'link_from',
            'Link To': 'link',
            'Price': 'price',
            'Anchor Text': 'anchor_text',
            'Date Completed': 'live_date',
            'Status': 'status'
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
          isBuyer: false,
          searchLoading: false,
        }
      },
      async created() {
          await this.$store.dispatch('actionCheckAdminCurrentUser', { vue: this });
          await this.getBackLinkList();
          this.checkAccountType()
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
          this.searchLoading = true;
          await this.$store.dispatch('actionGetBackLink', {
            vue: this,
            page: this.page,
            params: this.fillter,
          });
          this.searchLoading = false;
        }, 200),

        async deleteBackLink(id) {
            if( confirm('Are you you want to delete these record?') ){
                await this.$store.dispatch('actionDeleteBacklink', {
                    params: {
                        id:id
                    }
                }) 

                this.getBackLinkList();
            }
            
        },

        async clearSearch() {
            await this.$store.dispatch('actionResetFillterBacklink');
            this.fillter.status = ''
            this.getBackLinkList();
        },

        checkArray(array) {
          return Hepler.arrayNotEmpty(array);
        },

        checkAccountType() {
            let that = this.user
            if( that.user_type ){
                if( that.user_type.type == 'Buyer' ){
                    this.isBuyer = true;
                }
            }
        },

        editBackLink(baclink) {
            this.modalAddBackLink = true
            let that = Object.assign({}, baclink)

            this.modelBaclink.id = that.id
            this.modelBaclink.publisher_id = that.publisher.id
            this.modelBaclink.ext_domain.domain = that.publisher == null ? that.ext_domain.domain:that.publisher.url
            this.modelBaclink.int_domain.domain = that.int_domain == null ? '':that.int_domain.domain
            this.modelBaclink.anchor_text = that.anchor_text
            this.modelBaclink.price = that.price
            this.modelBaclink.link = that.link
            this.modelBaclink.live_date = that.live_date
            this.modelBaclink.status = that.status
            this.modelBaclink.user_id = that.user_id
            this.modelBaclink.date_process = that.date_process
            this.modelBaclink.url_advertiser = that.url_advertiser

            this.modelBaclink.seller = that.publisher.user.name

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