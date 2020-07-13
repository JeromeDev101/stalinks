<template>
    <div class="row">
        <div class="col-sm-12">

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Articles Content</h3>
                    <div class="mt-3">
                        <a class="btn btn-default" href="/articles">Close</a>
                        <button class="btn btn-success">Save</button>
                    </div>
                </div>
                <hr/>
                <div class="box-body m-3 pb-5">
                    <div class="row">
                        <div class="col-md-9 col-sm-12">
                            <tinymce id="d1" v-model="data"></tinymce>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="">Title</label>
                                        <input type="text" class="form-control" name="" aria-describedby="helpId" placeholder="">
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="">Anchor Text</label>
                                        <input type="text" class="form-control" name="" aria-describedby="helpId" placeholder="">
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="">Date start</label>
                                        <input type="text" class="form-control" name="" aria-describedby="helpId" placeholder="" :disabled="true">
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="">Price</label>
                                        <input type="text" class="form-control" name="" aria-describedby="helpId" placeholder="">
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="">Status</label>
                                        <select name="" class="form-control">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                data: '',
            }
        },

        async created() {
            
        },

        computed: {
            
        },

        methods: {
            
        },
    }
</script>