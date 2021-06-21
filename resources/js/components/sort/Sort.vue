<template>
    <div class="custom-dropdown dropdown">
        <button @click="myFunction()" class="btn btn-default">Sort</button>

        <div id="myDropdown" class="dropdown-content container card p-1">
            <div class="row no-gutters">
                <div class="col-4 p-1" v-for="(option, index) in sortItems" :key="index">
                    <div class="card">
                        <div class="card-body p-1">
                            <div class="row">
                                <div class="col-6 col-md-6 d-flex">
                                    <span class="align-self-center">{{ option.name }}</span>
                                </div>

                                <div class="col-6 col-md-6 flex-column">
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
                <button class="btn btn-primary" @click="submitSort()" :disabled="checkSortItems">Sort</button>
                <button class="btn btn-success" @click="resetSort()">Reset</button>
                <button class="btn btn-default" @click="myFunction()">Close</button>
            </div>

<!--            <ul class="list-group">-->
<!--                <li v-for="(option, index) in data" class="list-group-item" :value="option" :key="index">-->
<!--                    <table border="0" width="100%">-->
<!--                        <tr>-->
<!--                            <td>-->
<!--                                {{ option }}-->
<!--                            </td>-->
<!--                            <td class="text-right">-->
<!--                                <select style="width:80px;">-->
<!--                                    <option value="ASC">ASC</option>-->
<!--                                    <option value="DESC">DESC</option>-->
<!--                                </select>-->
<!--                            </td>-->
<!--                        </tr>-->
<!--                    </table>-->
<!--                </li>-->
<!--            </ul>-->
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
                        column: ''
                    }
                ]
            }
        },
        data() {
            return {
                sortItems: this.items
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
            },

            submitSort() {
                this.$emit('submitSort', this.sortItems)
            },

            clickSort(index, sort) {
                this.sortItems[index].sort = this.sortItems[index].sort === sort
                    ? ''
                    : sort
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
        z-index: 1;
        display: none;
        width:100%;
        min-width: 500px;
        position: absolute;
        list-style-type: none;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    }

    @media only screen and (max-width: 600px) {
        .dropdown-content {
            min-width: 300px;
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
