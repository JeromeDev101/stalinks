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
                        <h3 class="card-title text-primary">{{ $t('message.generate_list.filter_title') }}</h3>
                        <div class="card-tools" style="float: left!important;">
                            <button class="btn btn-primary ml-5"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#collapseExample"
                                    aria-expanded="false"
                                    aria-controls="collapseExample">
                                <i class="fa fa-plus"></i> {{ $t('message.generate_list.filter_show') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body collapse" id="collapseExample">
                        <div class="row">

                        </div>

                        <div class="row mb-3">
                            <div class="col-md-2">
                                <button class="btn btn-default" >
                                    {{ $t('message.generate_list.clear') }}
                                </button>
                                <button class="btn btn-default" >
                                    {{ $t('message.generate_list.search') }}
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
                        <h3 class="card-title text-primary">{{ $t('message.sidebar.setup_gpt') }}</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button class="btn btn-success" data-toggle="modal" data-target="#modalAddPrompt">
                                    <span class="fa fa-plus"></span> Add Prompt
                                </button>
                            </div>
                        </div>

                        <vue-virtual-table
                            width="100%"
                            :min-width="450"
                            :height="600"
                            :bordered="true"
                            :item-height="60"
                            :hover-highlight="true"
                            :config="tableConfig"
                            :enable-export="true"
                            :data="SetupGptList.data">

                            <template slot-scope="scope" slot="action-buttons">
                                <button
                                    title="Edit"
                                    class="btn btn-primary m-1"

                                    @click="editPrompt(scope.row.id, scope.row);">

                                    <i class="fas fa-pen-square"></i>
                                </button>

                                <button
                                    title="Delete"
                                    class="btn btn-danger"

                                    @click="deletePrompt(scope.row.id)">

                                    <i class="fas fa-trash"></i>
                                </button>
                            </template>

                        </vue-virtual-table>

                        <pagination
                            v-if="SetupGptList.data"
                            :limit="8"
                            :data="SetupGptList"

                            @pagination-change-page="getSetupChatGptList">

                        </pagination>

                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Add prompt -->
        <div class="modal fade" id="modalAddPrompt" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Prompt</h5>
                            <button type="button" class="close" @click="clearInputs" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    Add your prompt and use square brackets [ ] to specify options. For example, "Generate a list of queries related to [Topic] in [Language]."
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control" v-model="addModelPrompt.name" aria-describedby="helpId" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Prompt</label>
                                    <textarea class="form-control" @keyup="fetchText('add')" v-model="addModelPrompt.prompt" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <form ref="myForm">
                            <div class="row">
                                <div class="col-md-12" id="optionsContainer"></div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="clearInputs">Close</button>
                        <button type="button" class="btn btn-success" @click="savePrompt">Save Record</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Add prompt -->


        <!-- Modal edit prompt -->
        <div class="modal fade" id="modalEditPrompt" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Prompt</h5>
                            <button type="button" class="close" data-dismiss="modal" @click="clearInputsEdit" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    Add your prompt and use square brackets [ ] to specify options. For example, "Generate a list of queries related to [Topic] in [Language]."
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control" v-model="editModelPrompt.name" aria-describedby="helpId" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Prompt</label>
                                    <textarea class="form-control" @keyup="fetchText('edit')" v-model="editModelPrompt.prompt" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <form ref="myFormEdit" id="EditForm">
                            <div class="row">
                                <div class="col-md-12" id="optionsContainerEdit"></div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="clearInputsEdit" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="updatePrompt">Update Record</button>
                    </div>
                </div>
            </div>
        </div>
         <!-- End of Modal edit prompt -->

    </div>
</template>

<style>
    .color-coding-a {
        background: #f2a035;
        color: #fff;
    }

    .color-coding-b {
        background: #ffda6b;
        color: #000;
    }

    .color-coding-c {
        background: #2dc22b;
        color: #fff;
    }

    .color-coding-d {
        background: #4878e0;
        color: #fff;
    }

    .color-coding-e {
        background: #ffcccc;
        color: #b30000;
    }
</style>

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
            addModelPrompt: {
                name: '',
                prompt: '',
                values: [],
            },
            editModelPrompt: {
                name: '',
                prompt: '',
                values: [],
            },
            inputGroups: [],
            inputGroupsEdit: [],
            SetupGptList: [],
        }
    },

    async created() {
        this.getSetupChatGptList()
    },

    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,

        }),

        tableConfig() {
            return [
                {
                    prop : 'id',
                    name : 'ID',
                    sortable: true,
                    width: '100'
                },
                {
                    prop : 'name',
                    name : 'Name',
                },
                {
                    prop : 'prompt',
                    name : 'Prompt',
                },
                {
                    prop : '_action',
                    name : this.$t('message.publisher.t_action'),
                    actionName : 'action-buttons',
                    width : '150',
                },
            ];
        },
    },

    methods : {

        getSetupChatGptList(page = 1) {
            axios.get('/api/setup-chat-gpt', {
                params : {
                    page : page,
                }
            }).then((res) => {
                this.SetupGptList = res.data
            })
        },

        fetchText(type) {
            let prompt = type == 'add' ? this.addModelPrompt.prompt:this.editModelPrompt.prompt
            var newMatches = prompt.match(/\[(.*?)\]/g);
            var optionsContainer = type == 'add' ? $('#optionsContainer'):$('#optionsContainerEdit');
            var newOptions = [];
            let inputGroups = type == 'add' ? this.inputGroups:this.inputGroupsEdit

            if (newMatches) {
                for (var i = 0; i < newMatches.length; i++) {
                    var match = newMatches[i];
                    var optionName = match.substring(1, match.length - 1);
                    newOptions.push(optionName);
                }
            }

            var existingLabels = optionsContainer.find('label');
            existingLabels.each(function(index, label) {
                var labelName = $(label).text().replace(' options:', '');
                if (newOptions.indexOf(labelName) === -1) {
                    $(label).next('.input-group').remove();
                    $(label).remove();
                }
            });

            for (var j = 0; j < inputGroups.length; j++) {
                var inputGroup = inputGroups[j];
                var optionInput = inputGroup.find('input');
                var optionName = optionInput.attr('placeholder');

                if (newOptions.indexOf(optionName) === -1) {
                    inputGroup.remove(); // Remove the input group if the option is no longer in the prompt
                    inputGroups.splice(j, 1); // Remove the input group from the array
                    j--; // Adjust the loop index after removing an input group
                }
            }

            for (var k = 0; k < newOptions.length; k++) {
                var optionName = newOptions[k];
                var existingInputGroup = inputGroups.find(function(group) {
                    return group.attr('data-option-name') === optionName;
                });

                if (!existingInputGroup) {
                    var label = $('<label>').text(optionName + ' options:').addClass('mb-2');
                    optionsContainer.append(label);

                    var inputGroup = $('<div>').addClass('input-group mb-2').attr('data-option-name', optionName);
                    optionsContainer.append(inputGroup);

                    var optionInput = $('<input>').attr({
                        'type': 'text',
                        'placeholder': optionName,
                        'class': 'form-control'
                    });
                    inputGroup.append(optionInput);

                    var addButton = $('<button>').text('Add Option').addClass('btn btn-success btn-sm ml-2').attr('type', 'button');
                    addButton.on('click', this.createAddOptionHandler(optionName, label, addButton));
                    inputGroup.append(addButton);

                    inputGroups.push(inputGroup); // Add the input group to the array
                }
            }

            console.log('test')

        },

        createAddOptionHandler(optionName, label, addButton) {
            return function() {
                var newInputGroup = $('<div>').addClass('input-group mb-2');
                var newOptionInput = $('<input>').attr({
                    'type': 'text',
                    'placeholder': optionName,
                    'class': 'form-control'
                });
                newInputGroup.append(newOptionInput);

                var newAddButton = $('<button>').text('Add Option').addClass('btn btn-success btn-sm ml-2').attr('type', 'button');
                newAddButton.on('click', () => this.createAddOptionHandler(optionName, label, addButton));
                newInputGroup.append(newAddButton);

                label.after(newInputGroup);
                newAddButton.attr('disabled', 'disabled');

                this.inputGroups.push(newInputGroup); // Add the new input group to the array
            };
        },

        savePrompt() {
            const form = this.$refs.myForm;
            const inputs = form.querySelectorAll('input[type="text"]');

            if(this.addModelPrompt.name == '' || this.addModelPrompt.prompt == ''){
                swal.fire('Invalid', 'Please provide fillup all the fields', 'error');
            }

            this.addModelPrompt.values = Array.from(inputs).map(input => input.getAttribute('placeholder')+'-'+input.value)

            axios.post('/api/add-prompt', this.addModelPrompt)
                    .then(res => {
                        this.getSetupChatGptList()
                        this.clearInputs();
                        $('#modalAddPrompt').modal('hide')
                        swal.fire('Success', 'Successfully Added', 'success')
                    })

        },

        clearInputs() {
            this.addModelPrompt = {
                name: '',
                prompt: '',
                values: [],
            }

            $('#optionsContainer').html('')

        },

        clearInputsEdit() {
            // this.editModelPrompt = {
            //     name: '',
            //     prompt: '',
            //     values: [],
            // }

            // $('#optionsContainerEdit').html('')
        },

        updateOptions(prompt) {
            let optionsContainer = $('#optionsContainerEdit');
            optionsContainer.empty();

            // Regular expression to match content inside square brackets
            let regex = /\[([^\]]+)]/g;
            let match;

            while ((match = regex.exec(prompt)) !== null) {
                let optionType = match[1];

                let label = `<label>${optionType} options:</label>`;
                let inputGroup = `<div class="input-group mb-2">
                                    <input type="text" class="form-control" placeholder="${optionType}">
                                    <button class="btn btn-success btn-sm ml-2" type="button">Add Option</button>
                                </div>`;

                optionsContainer.append(label + inputGroup);
            }
        },

        editPrompt(id, prompt) {
            this.editModelPrompt = prompt
            // this.fetchText('edit')

            this.updateOptions(prompt.prompt)

            axios.get(`/api/edit-prompt/${id}/edit`)
                .then((res) => {
                    if(res.data.success) {
                        let optionsData = res.data.result.options;

                        $('#modalEditPrompt').modal('show')

                        for (var i = 0; i < optionsData.length; i++) {
                            var option = optionsData[i];
                            var optionName = option.name;
                            var optionValue = JSON.parse(option.value);

                            // Find the label and input group with the corresponding option name
                            var label = $('#optionsContainerEdit label:contains("' + optionName + ' options:")');
                            var inputGroup = label.next('.input-group');

                            // Clear any existing option inputs in the input group
                            inputGroup.find('input').not(':first').remove();

                            // Enable all "Add Option" buttons in the input group
                            inputGroup.find('.btn-success').removeAttr('disabled');

                            // Populate the option inputs with the values from the database
                            for (var j = 0; j < optionValue.length; j++) {
                                var inputValue = optionValue[j];

                                if (j === 0 && inputGroup.find('input').length === 1 && inputGroup.find('input').val() === '') {
                                    // Skip adding the first empty input if it exists
                                    inputGroup.find('input').val(inputValue);
                                    continue;
                                }

                                var newOptionInput = $('<input>').attr({
                                    'type': 'text',
                                    'placeholder': optionName,
                                    'class': 'form-control',
                                    'value': inputValue
                                });

                                var newAddButton = $('<button>').text('Add Option').addClass('btn btn-success btn-sm ml-2').attr('type', 'button');
                                newAddButton.on('click', this.createAddOptionHandlerPopulate(optionName, label, newAddButton));

                                var newInputGroup = $('<div>').addClass('input-group mb-2');
                                newInputGroup.append(newOptionInput);
                                newInputGroup.append(newAddButton);

                                inputGroup.after(newInputGroup);
                            }

                            // Disable all "Add Option" buttons in the input group except the last one
                            let optionInputs = label.nextUntil('label').find('input');
                            optionInputs.siblings('.btn-success').attr('disabled', 'disabled');
                            optionInputs.last().siblings('.btn-success').removeAttr('disabled');
                        }

                    } else {
                        swal.fire('Error', 'Problem on viewing data', 'error')
                    }
                })
        },

        createAddOptionHandlerPopulate(optionName, label, addButton) {
            return function() {
                var newInputGroup = $('<div>').addClass('input-group mb-2');
                var newOptionInput = $('<input>').attr({
                    'type': 'text',
                    'placeholder': optionName,
                    'class': 'form-control'
                });
                newInputGroup.append(newOptionInput);

                var newAddButton = $('<button>').text('Add Option').addClass('btn btn-success btn-sm ml-2').attr('type', 'button');
                newAddButton.on('click', () => this.createAddOptionHandlerPopulate(optionName, label, addButton));
                newInputGroup.append(newAddButton);

                label.after(newInputGroup);
                addButton.attr('disabled', 'disabled');

                // Disable the other "Add Option" buttons in the input group
                label.next('.input-group').find('.btn-success').attr('disabled', 'disabled');

                // Enable the last "Add Option" button if there is only one input with a value
                let optionInputs = label.next('.input-group').find('input');

                if (optionInputs.length === 1 && optionInputs.val() !== '') {
                    optionInputs.siblings('.btn-success').removeAttr('disabled');
                }
            };
        },

        updatePrompt() {
            const form = this.$refs.myFormEdit;
            const inputs = form.querySelectorAll('input[type="text"]');

            if(this.editModelPrompt.name == '' || this.editModelPrompt.prompt == ''){
                swal.fire('Invalid', 'Please provide fillup all the fields', 'error');
            }

            this.editModelPrompt.values = Array.from(inputs).map(input => input.getAttribute('placeholder')+'-'+input.value)

            axios.post('/api/update-prompt', this.editModelPrompt)
                    .then(res => {

                        this.getSetupChatGptList()
                        this.clearInputsEdit()
                        $('#modalEditPrompt').modal('hide')
                        swal.fire('Success', 'Successfully Updated', 'success')
                    })
        },

        deletePrompt(id) {
            swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.get('/api/delete-prompt', {
                        params : {
                            id : id,
                        }
                    }).then((res) => {
                        this.getSetupChatGptList()

                        swal.fire({
                            title: "Deleted!",
                            text: "Your file has been deleted.",
                            icon: "success"
                        });
                    })
                }
            });
        }
    }
}
</script>
