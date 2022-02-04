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


        <div v-if="!isExtWriter">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title text-primary">Filter</h3>
                            <div class="card-tools">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Anchor Text</label>
                                        <input type="text" class="form-control" v-model="filterModel.anchor_text">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Writer</label>
                                        <select name="" class="form-control" v-model="filterModel.writer_id">
                                            <option value="">All</option>
                                            <option v-for="option in ListExtWriters.data" v-bind:value="option.id">
                                                {{ option.username }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Status</label>
                                        <select name="" class="form-control" v-model="filterModel.status">
                                            <option value="">All</option>
                                            <option v-for="option in statusFilter" v-bind:value="option" :key="option">
                                                {{ option }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <button class="btn btn-default" @click="clearSearch">Clear</button>
                                    <button class="btn btn-default" @click="doSearch">Search <i v-if="searchLoading" class="fa fa-refresh fa-spin"></i>
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
                        <div class="card-body">
                            <table id="tbl_wallet_transaction"
                                class="table table-hover table-bordered table-striped rlink-table">
                                <thead>
                                <tr class="label-primary">
                                    <th>#</th>
                                    <th>Action</th>
                                    <th>Writer</th>
                                    <th>Anchor Text</th>
                                    <th>Link To</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(writer, index) in ListExtWriters.data" :key="index">
                                        <td>{{ index + 1 }}</td>
                                        <td>
                                            
                                            <button title="Create Exam"
                                                v-if="!writer.anchor_text"
                                                @click="doCreateExam(writer.id, writer.username)"
                                                class="btn btn-success">
                                                Create Exam
                                            </button>

                                            <button title="View Content"
                                                v-else
                                                @click="doUpdate(writer)"
                                                class="btn btn-default"><i class="fa fa-fw fa-eye"></i>
                                            </button>
                                            
                                        </td>
                                        <td>{{ writer.username }}</td>
                                        <td>{{ writer.anchor_text }}</td>
                                        <td>
                                            <a :href="'//'+writer.link_to" target="_blank">{{ writer.link_to }}</a>
                                        </td>
                                        <td>{{ writer.exam_status == "" || writer.exam_status == null ? "Not Yet Setup":writer.exam_status}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Create Exam Edit -->
            <div class="modal fade" id="modalEditWriterValidate" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Create Exam</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Writer</label>
                                        <input type="text" class="form-control" :disabled="true" v-model="addExam.writer_name">
                                        <input type="hidden" class="form-control" v-model="addExam.writer_id">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Anchor Text</label>
                                        <input type="text" class="form-control" v-model="addExam.anchor_text">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Link To</label>
                                        <input type="text" class="form-control" v-model="addExam.link_to">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success" @click="submitExam()">Create Exam</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Modal Edit -->

            <!-- Modal View Content -->
            <div class="modal fade" id="modalEditWriterValidateViewContent" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">View Content</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Status</label>
                                        <select name="" class="form-control" v-model="viewModel.status">
                                            <option v-for="option in status" v-bind:value="option" :key="option">
                                                {{ option }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Title</label>
                                        <input type="text" class="form-control" v-model="viewModel.title">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Anchor Text</label>
                                        <input type="text" class="form-control" v-model="viewModel.anchor_text" :disabled="true">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Link To</label>
                                        <input type="text" class="form-control" v-model="viewModel.link_to" :disabled="true">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Meta Description</label>
                                        <textarea class="form-control" cols="30" rows="5" v-model="viewModel.meta_description"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Content</label>
                                        <tiny-editor
                                            v-model="data2"
                                            ref="composeEditExam"
                                            editor-id="composeEditExam">

                                        </tiny-editor>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" @click="submitUpdate">Update</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Modal View Content -->

        </div>

        <!-- Viewing for External Writers -->
        <div v-if="isExtWriter">
            <div class="alert alert-info" v-if="ExamUpdate.anchor_text == '' || ExamUpdate.link_to == '' ">
                <p>Please wait for the admin to create your exam.</p>
            </div>

            <div class="row" v-if="ExamUpdate.anchor_text != '' && ExamUpdate.link_to != '' ">
                <div class="col-sm-12">
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title text-primary">Examination</h3>
                            <div class="card-tools">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12" v-if="ExamUpdate.status == 'For Checking'">
                                    <div class="alert alert-success">
                                        <p>Your exam is now currently on checking. Please wait for the result. Thanks</p>
                                    </div>
                                </div>

                                <div class="col-md-12" v-if="ExamUpdate.status == 'Approved'">
                                    <div class="alert alert-success">
                                        <p><b>Congratulations!</b> you passed in the exam. Please re-login to update your account. Thanks</p>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <p><b>Instruction</b></p>
                                        1. Write an article with atleast <b>600 words</b>.<br/>
                                        2. Identify the keywords you have used in the article by using a <b>bold</b> character.<br/>
                                        3. Use the <b>anchor text</b> as <b>natural</b> as possible within your article and <b>hyperlink</b> it to the assigned website.<br/>
                                        4. Create a creative title.<br/>
                                        5. Write a meta description with <b>110 - 160</b> characters.<br/>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Title</label>
                                        <input type="text" class="form-control" v-model="ExamUpdate.title">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Anchor Text</label>
                                        <input type="text" class="form-control" :disabled="true" v-model="ExamUpdate.anchor_text">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Link To</label>
                                        <input type="text" class="form-control" :disabled="true" v-model="ExamUpdate.link_to">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Meta Description</label>
                                        <textarea class="form-control" cols="30" rows="5" v-model="ExamUpdate.meta_description"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Content</label>
                                        <tiny-editor
                                            v-model="data"
                                            ref="composeEditExam2"
                                            editor-id="composeEditExam2">

                                        </tiny-editor>
                                    </div>
                                </div>

                                <div class="col-md-12 my-3" v-if="ExamUpdate.status != 'For Checking' && ExamUpdate.status != 'Approved'">
                                    <button class="btn btn-primary btn-lg" @click="submitWriterExam">Submit Exam</button>
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
import {mapState} from 'vuex';
import axios from 'axios';
import 'tinymce/skins/lightgray/skin.min.css';
import TinyEditor from "../../../components/editor/TinyEditor";

export default {
    components: {
        TinyEditor,
    },
    data() {
        return {
            statusFilter: ['Not Yet Setup', 'Setup', 'For Checking', 'Approved', 'Disapproved'],
            status: ['Setup', 'For Checking', 'Approved', 'Disapproved'],
            data: '',
            data2: '',
            ListExtWriters: [],
            addExam: {
                writer_name: '',
                writer_id: '',
                anchor_text: '',
                link_to: '',
            },
            filterModel: {
                status: '',
                anchor_text: '',
                writer_id: '',
            },
            ExamUpdate: {
                link_to: '',
                anchor_text: '',
                meta_description: '',
                title: '',
                content: '',
                status: '',
            },
            isExtWriter: false,
            searchLoading: false,
            viewModel: {
                id: '',
                title: '',
                anchor_text: '',
                meta_description: '',
                status: '',
                link_to: '',
                writer_id: '',
            }
        }
    },

    async created() {
        this.getListExtWriters();
        this.checkUser();
    },

    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,
        }),

    },

    methods : {

        submitUpdate() {
    
            if(this.viewModel.status != 'Setup') {
                if(this.viewModel.meta_description == '' || this.data2 == '' || this.viewModel.title == '') {
                    swal.fire(
                        'Sorry!',
                        'Exam is not yet started by the writer.',
                        'error',
                    )
                    
                    return false;
                }
            }
            

            axios.post('/api/check-exam', this.viewModel)
                .then((res) => {
                    this.doSearch();
                    swal.fire(
                        'Success',
                        'Successfully Updated.',
                        'success',
                    )

                    $("#modalEditWriterValidateViewContent").modal('show')
                })
        },

        clearSearch() {
            this.filterModel = {
                status: '',
                anchor_text: '',
                writer_id: '',
            }

            this.getListExtWriters({
                params: this.filterModel
            });

            this.$router.replace({'query': null});
        },

        doSearch() {
            this.$router.push({
                query: this.filterModel,
            });

            this.getListExtWriters({
                params: {
                    status: this.filterModel.status,
                    writer_id: this.filterModel.writer_id,
                    anchor_text: this.filterModel.anchor_text
                }
            });
        },

        checkUser() {
            // check if external writer
            if(this.user.role_id == 4 && this.user.isOurs == 1) {
                this.isExtWriter = true
                this.getExamDetails();
            }
        },

        getExamDetails() {
            axios.get('/api/get-exam', {
                params: {
                    id: this.user.id
                }
            }).then((res) => {
                var result = res.data.data
                this.ExamUpdate = result;
                this.data = result.content
            })
        },

        submitWriterExam() {
            this.ExamUpdate.content = this.data;

            if(this.ExamUpdate.content == "" || this.ExamUpdate.title == "" || this.ExamUpdate.meta_description == "") {
                swal.fire(
                    'Error',
                    'Please provide the Title, Meta Description and Content.',
                    'error',
                )
                return false;
            }

            axios.post('/api/update-exam', this.ExamUpdate);
            swal.fire(
                'Success',
                'Successfully Submitted.',
                'success',
            )
            
            this.ExamUpdate.status = 'For Checking';
        },

        getListExtWriters(filters) {
            axios.get('/api/ext-writers', filters)
                .then((res) => {
                    this.ListExtWriters = res.data
                })
        },

        doUpdate(writer) {
            console.log(writer)
            this.viewModel.writer_id = writer.exam_writer_id
            this.viewModel.id = writer.exam_id
            this.viewModel.title = writer.title
            this.viewModel.anchor_text = writer.anchor_text
            this.viewModel.status = writer.exam_status
            this.viewModel.link_to = writer.link_to
            this.viewModel.meta_description = writer.meta_description
            // this.data2 = writer.content

            $("#modalEditWriterValidateViewContent").modal('show')
        },

        submitExam() {
            if(this.addExam.anchor_text == "" || this.addExam.link_to == "") {
                swal.fire(
                    'Error',
                    'Please Fill up all fields.',
                    'error',
                )
                return false;
            }

            axios.post('/api/add-exam', this.addExam);

            swal.fire(
                'Success',
                'Exam Successfully Created',
                'success',
            )

            this.doSearch();

            $("#modalEditWriterValidate").modal('hide');
            
        },

        doCreateExam(writer_id, writer_username) {
            this.addExam.writer_name = writer_username
            this.addExam.writer_id = writer_id
            this.addExam.anchor_text = ''
            this.addExam.link_to = ''

            $("#modalEditWriterValidate").modal('show')
        },
    },

}
</script>
