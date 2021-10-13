<template>
    <div class="card card-outline card-secondary">
        <div class="card-header">
            <h3 class="card-title">ETH QR Code</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body">
            <img src="/storage/eth.jpg" width="250px" alt="" class="mx-auto d-block">
            <p class="text-center">{{ cryptoValue('eth_address') }}</p>

            <div class="form-group">
                <label>Address</label>
                <input type="text" class="form-control" v-model="cryptoModelEth.address">
            </div>

            <div class="form-group">
                <label>QR Code Image</label>
                <input type="file" class="form-control" enctype="multipart/form-data" ref="qrcodeEth" name="file">
                <small class="text-muted">Note: It must be JPG type.</small><br/>
            </div>
        </div>

        <div class="card-footer">
            <button type="button" @click="updateEth" class="btn btn-primary">Upload</button>
        </div>
    </div>
</template>

<script>
import _ from "lodash";
import {mapState} from "vuex";
import axios from "axios";

export default {
    name : "Eth",

    data() {
        return {
            cryptoModelEth : {
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

        updateEth() {
            this.formData = new FormData();
            this.formData.append('address', this.cryptoModelEth.address);
            this.formData.append('file', this.$refs.qrcodeEth.files[0]);

            axios.post('/api/crypto/eth', this.formData)
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
            this.cryptoModelEth = {
                address : '',
            };
        },
    }
}
</script>

<style scoped>

</style>
