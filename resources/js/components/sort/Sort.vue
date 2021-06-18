<template>
    <div class="custom-dropdown">
        <button @click="myFunction()" class="btn btn-default">Sort</button>
        <ul id="myDropdown" class="dropdown-content">
        
            <li v-for="(option, index) in data" v-bind:value="option">
                <table border="0" width="100%">
                    <tr>
                        <td>
                            {{ option }}
                        </td>
                        <td class="text-right">
                            <select style="width:80px;">
                                <option value="ASC">ASC</option>
                                <option value="DESC">DESC</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </li>
        </ul>
    </div>
</template>


<style scoped>
    .custom-dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        list-style-type: none;
        margin: 0;
        padding: 0;
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        min-width: 200px;
        overflow: auto;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    .dropdown-content li {
        color: black;
        padding: 6px 8px;
        display: block;
    }

    .show {display: block;}
</style>
    
<script>
    export default {
        name: 'Sort',
        props: ['data'],
        data() {
            return {

            }
        },
        methods: {
            myFunction() {
                document.getElementById("myDropdown").classList.toggle("show");
            },
        }
    };
</script>