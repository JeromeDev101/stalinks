<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"></h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">{{ $t('message.module_page.filter_title') }}</h3>
                        <div class="card-tools" style="float: left!important;">
                            <button class="btn btn-primary ml-5"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#collapseExample"
                                    aria-expanded="false"
                                    aria-controls="collapseExample">
                                <i class="fa fa-plus"></i> {{ $t('message.module_page.filter_show') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body collapse" id="collapseExample">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ $t('message.module_page.search') }}</label>
                                    <input type="text"
                                           class="form-control"
                                           name=""
                                           aria-describedby="helpId"
                                           :placeholder="$t('message.module_page.type')">
                                </div>
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="col-md-2">
                                <button class="btn btn-default">
                                    {{ $t('message.module_page.clear') }}
                                </button>
                                <button class="btn btn-default">
                                    {{ $t('message.module_page.search') }}
                                    <i v-if="false" class="fa fa-refresh fa-spin"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">{{ $t('message.module_page.m_title') }}</h3>
                        <div class="card-tools">

                        </div>
                    </div>
                    <div class="card-body">



                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import {mapState} from 'vuex';
import axios from 'axios';
import VueVirtualTable from 'vue-virtual-table';

export default {
    components : {
        VueVirtualTable,
    },
    data() {
        return {
            //
        }
    },

    async created() {
        //
    },

    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,
        }),
    },

    methods : {
        //
    }
}
</script>
