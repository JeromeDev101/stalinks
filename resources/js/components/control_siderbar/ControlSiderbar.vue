<template>
    <aside class="control-sidebar control-sidebar-dark">
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-bell-o"></i></a></li>
            <li class="active"><a href="#control-sidebar-notification" data-toggle="tab"><i class="fa fa-bell-o"></i></a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane" id="control-sidebar-home-tab">
                test
            </div>

            <div class="tab-pane" id="control-sidebar-notification">
                <h3 class="control-sidebar-heading">Notification</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:void(0)">
                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>
                                <p>Will be 23 on April 24th</p>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </aside>
</template>

<script>
export default {
    name: 'AppControlSiderbar',
};
</script>
