<template>
    <footer class="main-footer">
        <div class="pull-right col-sm-1">
            <select name="language" id="language" class="form-control" v-model="pageLanguage">
                <option value="en">English</option>
                <option value="jp">Japanese</option>
            </select>
        </div>

        <div class="pull-right hidden-xs">
            <b>Version</b> 2.4.13
        </div>

        <strong>Copyright © 2020 <a href="https://www.stalinks.com">StaLink System</a>.</strong> All rights
        reserved.

    </footer>
</template>
<script>
    export default {
        name: 'AppFooter',

        data() {
            return {
                pageLanguage: 'en'
            }
        },

        watch : {
            pageLanguage(newvalue, oldValue) {
                this.$i18n.locale = newvalue;
            }
        },
    }
</script>

