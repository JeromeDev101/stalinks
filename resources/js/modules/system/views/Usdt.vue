<template>
    <div class="card card-outline card-secondary">
        <div class="card-header">
            <h3 class="card-title">USDT QR Code</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body">
            <img src="/storage/usdt.jpg" width="250px" alt="" class="mx-auto d-block">
            <p class="text-center">{{ cryptoValue('usdt_address') }}</p>

            <div class="form-group">
                <label>Address</label>
                <input type="text" class="form-control" v-model="cryptoModel.address">
            </div>

            <div class="form-group">
                <label>QR Code Image</label>
                <input type="file" class="form-control" enctype="multipart/form-data" ref="qrcode" name="file">
                <small class="text-muted">Note: It must be JPG type.</small><br/>
            </div>
        </div>

        <div class="card-footer">
            <button type="button" @click="updateUsdt" class="btn btn-primary">Upload</button>
        </div>
    </div>
</template>

<script>
import _ from "lodash";
import axios from "axios";
import {mapState} from "vuex";

export default {
    name : "Usdt",

    data() {
        return {
            cryptoModel : {
                address : ''
            },
            formData : null
        }
    },

    computed : {
        ...mapState({
            configList : state => state.storeSystem.configList,
        }),
    },

    methods : {
        cryptoValue(code) {
            let val = _.find(this.configList.data.crypto, function (o) {
                return o.code === code
            }).value;

            return val;
        },

        updateUsdt() {
            this.formData = new FormData();
            this.formData.append('address', this.cryptoModel.address);
            this.formData.append('file', this.$refs.qrcode.files[0]);

            axios.post('/api/crypto/usdt', this.formData)
                .then((response) => {
                    this.getConfigList();
                    this.clearForm();

                    swal.fire(
                        'Success',
                        'Successfully Updated',
                        'success'
                    )
                }).catch((error) => {
                swal.fire(
                    'Error',
                    'Error encountered',
                    'error'
                )
            });
        },

        getConfigList() {
            this.$emit('getconfiglist');
        },

        clearForm() {
            this.cryptoModel = {
                address : ''
            }
        },
    }
}
</script>

<style scoped>

</style>
