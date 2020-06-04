<template>
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar" style="height: auto;">
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu tree" data-widget="tree">
                <li v-if="user.isAdmin" class="header">Admin</li>
                <li v-if="user.isAdmin" :class="{ active: $route.name == 'List User' }">
                    <router-link class="page-sidebar__item" :to="{ path: '/users' }">
                        <i class="fa fa-fw fa-users"></i> <span>Employees</span>
                    </router-link>
                </li>

                <li v-if="user.isAdmin" :class="{ active: $route.name == 'logs' }">
                    <router-link class="page-sidebar__item" :to="{ path: '/logs' }">
                        <i class="fa fa-fw fa-circle"></i> <span>System Logs</span>
                        <span class="pull-right-container"></span>
                    </router-link>
                </li>

                <li v-if="user.isAdmin" :class="{ active: $route.name == 'system' }">
                    <router-link class="page-sidebar__item" :to="{ path: '/system' }">
                        <i class="fa fa-fw fa-cog"></i> <span>Admin setting</span>
                        <span class="pull-right-container"></span>
                    </router-link>
                </li>

                <li v-if="user.isAdmin" :class="{ active: $route.name == 'AlexaDomain' }">
                    <router-link :to="{ path: '/ext/alexa' }">
                        <i class="fa fa-circle-o"></i>
                        <span>Get Alexa</span>
                        <span class="pull-right-container"></span>
                    </router-link>
                </li>

                <li class="header">MAIN NAVIGATION</li>
                <li :class="{ active: $route.name == 'Dashboard' }">
                    <router-link  :to="{ path: '/' }">
                      <i class="fa fa-dashboard"></i> <span> Dashboard</span>
                    </router-link>
                </li>

                <li :class="{ active: $route.name == 'Buyer' }">
                    <router-link class="page-sidebar__item" :to="{ path: '/buyer-backlinks' }">
                        <i class="fa fa-btc"></i>
                        <span>Buyer Backlinks</span>
                        <span class="pull-right-container"></span>
                    </router-link>
                </li>

                <li :class="{ active: $route.name == 'ExtDomain' || $route.name == 'mail-logs' || $route.name == 'mail-template', 'treeview': true, 'menu-open': $route.name == 'ExtDomain' || $route.name == 'mail-logs' || $route.name == 'mail-template' }">
                    <a href="#">
                        <i class="fa fa-search"></i>
                        <span>Search  Domains</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li :class="{ active: $route.name == 'ExtDomain' }">
                            <router-link :to="{ path: '/ext' }">
                                <i class="fa fa-reorder"></i> 
                                <span>Summary</span>
                                <span class="pull-right-container"></span>
                            </router-link>
                        </li>
                        <li :class="{ active: $route.name == 'mail-logs' }">
                            <router-link class="page-sidebar__item" :to="{ path: '/mail-logs' }">
                                <i class="fa fa-fw fa-square"></i> <span>Mail Logs</span>
                                <span class="pull-right-container"></span>
                            </router-link>
                        </li>
                        <li :class="{ active: $route.name == 'mail-template' }">
                            <router-link class="page-sidebar__item" :to="{ path: '/mail-template' }">
                                <i class="fa fa-fw fa-envelope-o"></i> <span>Mail Template</span>
                                <span class="pull-right-container"></span>
                            </router-link>
                        </li>
                    </ul>
                </li>

                <li :class="{ active: $route.name == 'Seller' }">
                    <router-link class="page-sidebar__item" :to="{ path: '/seller-backlinks' }">
                        <i class="fa fa-dollar"></i>
                        <span>Seller Backlinks</span>
                        <span class="pull-right-container"></span>
                    </router-link>
                </li>

                <li :class="{ active: $route.name == 'Publisher URL List' }">
                    <router-link class="page-sidebar__item" :to="{ path: '/publisher' }">
                        <i class="fa fa-file"></i>
                        <span>Publisher URL List</span>
                        <span class="pull-right-container"></span>
                    </router-link>
                </li>

                <li :class="{ active: $route.name == 'BackLink' }">
                    <router-link class="page-sidebar__item" :to="{ path: '/backlinks' }">
                        <i class="fa fa-fw fa-link"></i>
                        <span>Backlinks</span>
                        <span class="pull-right-container"></span>
                    </router-link>
                </li>

                <!--
                <li>
                    <router-link :to="{ path: '/intdomains' }">
                        <i class="fa fa-fw fa-anchor"></i> <span>Internal Domains</span>
                        <span class="pull-right-container"></span>
                    </router-link>
                </li>

                <li>
                    <router-link class="page-sidebar__item" :to="{ path: '/hostings' }">
                        <i class="fa fa-fw fa-external-link-square"></i> <span>Hosting List</span>
                        <span class="pull-right-container"></span>
                    </router-link>
                </li>

                <li>
                    <router-link class="page-sidebar__item" :to="{ path: '/domains' }">
                        <i class="fa fa-fw fa-server"></i> <span>Domain Provider List</span>
                        <span class="pull-right-container"></span>
                    </router-link>
                </li>

                -->

            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>
</template>

<script>

import { mapActions, mapState } from 'vuex';
export default {
    name: 'AppAside',
    computed: {
        ...mapState({
            user: state => state.storeAuth.currentUser,
        }),

        currentPage() {
            return this.$route.path;
        }
    },

}
</script>
