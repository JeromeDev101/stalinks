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
                                <label for="">Search </label>
                                <input type="text" class="form-control" name="" aria-describedby="helpId" placeholder="Type here">
                            </div>
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <button class="btn btn-default">Clear</button>
                            <button class="btn btn-default">Search <i v-if="false" class="fa fa-refresh fa-spin" ></i></button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="box">

                <div class="box-header">
                    <h3 class="box-title">Wallet Transaction</h3>
                </div>

                <div class="box-body table-responsive no-padding relative">
                    <table class="table table-hover table-bordered table-striped rlink-table">
                        <thead>
                            <tr class="label-primary">
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        data() {
            return {

            }
        },


    }
</script>