<template>
    <div class="custom-dropdown dropdown" :class="customClass">
        <button
            class="btn w-100"
            :class="{'btn-primary': checkSortItems, 'btn-success': !checkSortItems}"

            @click="myFunction()">
            Sort
        </button>

        <div id="myDropdown" class="dropdown-content container card p-1">
            <div class="row no-gutters">
                <div class="col-6 col-md-4 p-1" v-for="(option, index) in sortItems" v-if="!option.hidden" :key="index">
                    <div class="card">
                        <div class="card-body p-1">
                            <div class="row">
                                <div class="col-8 col-md-9 d-flex">
                                    <span class="align-self-center">{{ option.name }}</span>
                                </div>

                                <div class="col-4 col-md-3 flex-column">
                                    <div class="d-flex flex-column align-items-end">
                                        <i
                                            class="fa fa-caret-up fa-lg"
                                            :style="[option.sort === 'asc' ? {'color': '#3caed2'} : {'color': 'grey'}]"

                                            @click="clickSort(index, 'asc')">
                                        </i>

                                        <i
                                            class="fa fa-caret-down fa-lg"
                                            :style="[option.sort === 'desc' ? {'color': '#3caed2'} : {'color': 'grey'}]"

                                            @click="clickSort(index, 'desc')">
                                        </i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr/>

            <div class="px-2 pb-2 w-100 text-right">
                <button
                    :class="{'btn-primary': checkSortItems, 'btn-success': !checkSortItems}"
                    class="btn"

                    @click="submitSort()" :disabled="checkSortItems">Sort</button>
                <button class="btn btn-danger" @click="resetSort()" :disabled="checkSortItems">Clear</button>
                <button class="btn btn-default" @click="myFunction()">Close</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Sort',
        props: {
            items: {
                type: Array,
                default:[
                    {
                        name: '',
                        sort: '',
                        column: '',
                        hidden: false
                    }
                ]
            },

            sorted: {
                type: Boolean,
                default: false
            },

            customClass: {
                type: Array,
                default: []
            }
        },

        data() {
            return {
                sortItems: this.items,
                isSorted: this.sorted
            }
        },

        watch: {
            items: function (newVal) {
                this.updateHidden(newVal);
            },

            sorted: function (newVal) {
                this.isSorted = newVal;
            },

            sortItems: {
                deep: true,
                handler() {
                    this.updateParent();
                }
            }
        },

        computed: {
            checkSortItems() {
                return this.sortItems.every(e => e.sort === '')
            }
        },

        methods: {
            resetSort() {
                this.sortItems.forEach(e => e.sort = '');

                if (this.isSorted) {
                    this.$emit('submitSort', this.sortItems)
                }
            },

            submitSort() {
                this.myFunction();
                this.$emit('submitSort', this.sortItems)
            },

            clickSort(index, sort) {
                this.sortItems[index].sort = this.sortItems[index].sort === sort
                    ? ''
                    : sort
            },

            updateHidden(data) {
                data.forEach((value, index) => {
                    this.sortItems[index].hidden = value.hidden;
                    if (value.hidden) {
                        this.sortItems[index].sort = '';
                    }
                })
            },

            updateParent() {
                this.$emit('updateOptions', this.sortItems)
            },

            myFunction() {
                document.getElementById("myDropdown").classList.toggle("show");
            },
        }
    };
</script>

<style scoped>
    .custom-dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        margin: 0;
        width:100%;
        z-index: 1;
        display: none;
        min-width: 500px;
        right: 0;
        position: absolute;
        list-style-type: none;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    }

    @media only screen and (max-width: 600px) {
        .dropdown-content {
            min-width: 350px;
        }
    }

    .dropdown-content li {
        color: black;
        display: block;
        padding: 6px 8px;
    }

    .show {display: block;}

    i {
        cursor: pointer;
    }

    .list-group-item {
        cursor: default !important;
    }
</style>
