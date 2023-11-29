<template>
    <aside class="main-sidebar main-sidebar-custom sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="#" class="brand-link navbar-light logo-switch">
            <img src="../../../../images/stalinks2.png" alt="Stalinks Logo" class="brand-image-xl img-fluid">
        </a>

        <!-- Sidebar -->
        <div class="sidebar" style="overflow-x: hidden;" v-if="!isProcessing">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul
                    role="menu"
                    class="nav nav-pills nav-sidebar text-sm nav-flat nav-legacy nav-compact nav-child-indent flex-column"
                    data-widget="treeview"
                    data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    <li v-if="canViewAdminHeader" class="nav-header">
                        {{ $t('message.sidebar.admin') }}
                    </li>

                    <!-- Admin Settings -->
                    <li
                        v-if="canViewAdminSettings"
                        class="nav-item"
                        :class="{
                            'active' :
                                $route.name == 'system-it' ||
                                $route.name == 'system-finance' ||
                                $route.name == 'system-dev',
                            'menu-open':
                                 $route.name == 'system-it' ||
                                $route.name == 'system-finance' ||
                                $route.name == 'system-dev'
                        }">
                        <a href="#" class="nav-link">
                            <img src="../../../../images/admin-settings.png"/>
                            <p>
                                {{ $t('message.sidebar.admin_settings') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li v-if="user.permission_list.includes('view-admin-settings-it')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/system/it' }"
                                    :class="{ active: $route.name == 'system-it' }">

                                    <i class="fas fa-desktop nav-icon"></i>
                                    <p>{{ $t('message.sidebar.it') }}</p>
                                </router-link>
                            </li>
                            <li v-if="user.permission_list.includes('view-admin-settings-finance')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/system/finance' }"
                                    :class="{ active: $route.name == 'system-finance' }">

                                    <i class="fas fa-hand-holding-usd nav-icon"></i>
                                    <p>{{ $t('message.sidebar.finance') }}</p>
                                </router-link>
                            </li>
                            <li v-if="user.permission_list.includes('view-admin-settings-dev')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/system/dev' }"
                                    :class="{ active: $route.name == 'system-dev' }">

                                    <i class="fas fa-code nav-icon"></i>
                                    <p>{{ $t('message.sidebar.devs') }}</p>
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <!-- Management -->
                    <li
                        v-if="canViewManagement"
                        class="nav-item"
                        :class="{
                            'active' :
                                $route.name == 'roles' ||
                                $route.name == 'tools' ||
                                $route.name == 'mail-logs' ||
                                $route.name == 'List User' ||
                                $route.name == 'modules',
                            'menu-open':
                                $route.name == 'roles' ||
                                $route.name == 'tools' ||
                                $route.name == 'mail-logs' ||
                                $route.name == 'List User' ||
                                $route.name == 'modules'
                        }">
                        <a href="#" class="nav-link">
                            <img src="../../../../images/admin-settings.png"/>
                            <p>
                                {{ $t('message.sidebar.management') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li v-if="user.permission_list.includes('view-management-role')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/management/roles' }"
                                    :class="{ active: $route.name == 'roles' }">

                                    <i class="fas fa-user nav-icon"></i>
                                    <p>{{ $t('message.sidebar.role') }}</p>
                                    <span class="pull-right-container"></span>
                                </router-link>
                            </li>
                            <li v-if="user.permission_list.includes('view-management-module')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/management/modules' }"
                                    :class="{ active: $route.name == 'modules' }">

                                    <i class="fas fa-bars nav-icon"></i>
                                    <p>{{ $t('message.sidebar.mod') }}</p>
                                </router-link>
                            </li>
                            <li v-if="user.permission_list.includes('view-management-tools')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/management/tools' }"
                                    :class="{ active: $route.name == 'tools' }">

                                    <i class="fas fa-tools nav-icon"></i>
                                    <p>{{ $t('message.sidebar.tools') }}</p>
                                </router-link>
                            </li>
                            <li v-if="user.permission_list.includes('view-management-mail-logs')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/management/mail-logs' }"
                                    :class="{ active: $route.name == 'mail-logs' }">

                                    <i class="fas fa-envelope nav-icon"></i>
                                    <p>{{ $t('message.sidebar.mail_logs') }}</p>
                                </router-link>
                            </li>
                            <li v-if="user.permission_list.includes('view-management-system-logs')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/management/logs' }"
                                    :class="{ active: $route.name == 'logs' }">
                                    <i class="fas fa-cogs nav-icon"></i>

                                    <p>{{ $t('message.sidebar.system_logs') }}</p>
                                </router-link>
                            </li>
                            <li v-if="user.permission_list.includes('view-management-teams')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/management/teams' }"
                                    :class="{ active: $route.name == 'List User' }">

                                    <i class="fas fa-users nav-icon"></i>
                                    <p>{{ $t('message.sidebar.teams') }}</p>
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <!-- Admin Dashboard -->
                    <li v-if="user.permission_list.includes('view-admin-dashboard-admin-dashboard')" class="nav-item">
                        <router-link
                            class="nav-link"
                            :to="{ path: '/dashboard' }"
                            :class="{ active: $route.name == 'dashboard' }">

                            <img src="../../../../images/dashboard.png"/>
                            <p>{{ $t('message.sidebar.dashboard_admin') }}</p>
                        </router-link>
                    </li>

                    <!-- Gex Alexa -->
                    <li v-if="user.permission_list.includes('view-get-alexa-get-alexa')" class="nav-item">
                        <router-link
                            class="nav-link"
                            :to="{ path: '/ext/alexa' }"
                            :class="{ active: $route.name == 'AlexaDomain' }">

                            <img src="../../../../images/alexa.png"/>
                            <p>{{ $t('message.sidebar.get_alexa') }}</p>
                        </router-link>
                    </li>

                    <!-- Generate List -->
                    <li v-if="user.permission_list.includes('view-generate-list-generate-list')" class="nav-item">
                        <router-link
                            class="nav-link"
                            :to="{ path: '/generate-list' }"
                            :class="{ active: $route.name == 'generate-list' }">

                            <img src="../../../../images/article.png"/>
                            <p>{{ $t('message.sidebar.generate_list') }}</p>
                        </router-link>
                    </li>

                    <!-- Registration Accounts -->
                    <li
                        v-if="user.permission_list.includes('view-registration-accounts-registration-accounts')"
                        class="nav-item">
                        <router-link
                            class="nav-link"
                            :to="{ path: '/accounts' }"
                            :class="{ active: $route.name == 'Registration' }">

                            <img src="../../../../images/registration.png"/>
                            <p>{{ $t('message.sidebar.registration_accounts') }}</p>
                        </router-link>
                    </li>

                    <!-- Survey Dashboard -->
                    <li v-if="user.permission_list.includes('view-survey-dashboard-survey-dashboard')" class="nav-item">
                        <router-link
                            class="nav-link"
                            :to="{ path: '/survey-dashboard' }"
                            :class="{ active: $route.name == 'survey-dashboard' }">

                            <img src="../../../../images/article.png"/>
                            <p>{{ $t('message.sidebar.survey_dashboard') }}</p>
                        </router-link>
                    </li>

                    <!-- Admin Article -->
                    <li v-if="user.permission_list.includes('view-admin-article-admin-article')" class="nav-item">
                        <router-link
                            class="nav-link"
                            :to="{ path: '/articles-list' }"
                            :class="{ active: $route.name == 'articles-list' }">

                            <img src="../../../../images/article.png"/>
                            <p>{{ $t('message.sidebar.article_admin') }}</p>
                        </router-link>
                    </li>

                    <!-- Writer's Validation -->
                    <li v-if="user.permission_list.includes('view-writer\'s-validation-writer\'s-validation')" class="nav-item">
                        <router-link
                            class="nav-link"
                            :to="{ path: '/validate-writer' }"
                            :class="{ active: $route.name == 'validate-writer' }">

                            <img src="../../../../images/article.png"/>
                            <p>{{ $t('message.sidebar.writer_validation') }}</p>
                        </router-link>
                    </li>

                    <!-- Backlinks Prospect -->
                    <li v-if="user.permission_list.includes('view-backlinks-prospect-backlinks-prospect')" class="nav-item">
                        <router-link
                            class="nav-link"
                            :to="{ path: '/backlink-prospect' }"
                            :class="{ active: $route.name == 'backlink-prospect' }">

                            <img src="../../../../images/article.png"/>
                            <p>{{ $t('message.sidebar.backlinks_prospect') }}</p>
                        </router-link>
                    </li>

                    <!-- Admin Incomes -->
                    <li v-if="user.permission_list.includes('view-incomes-admin-incomes')" class="nav-item">
                        <router-link
                            class="nav-link"
                            :to="{ path: '/overall-incomes' }"
                            :class="{ active: $route.name == 'overall-incomes' }">

                            <img src="../../../../images/incomes.png"/>
                            <p>{{ $t('message.sidebar.incomes_admin') }}</p>
                        </router-link>
                    </li>

                    <!-- Billing -->
                    <li
                        v-if="canViewBilling"
                        class="nav-item"
                        :class="{
                            active:
                                $route.name == 'seller-billing' ||
                                $route.name == 'wallet-transaction' ||
                                $route.name == 'wallet-summary' ||
                                $route.name == 'writer-billing',
                            'menu-open':
                                $route.name == 'wallet-transaction' ||
                                $route.name == 'seller-billing' ||
                                $route.name == 'wallet-summary' ||
                                $route.name == 'writer-billing'
                        }">
                        <a href="#" class="nav-link">
                            <img src="../../../../images/billing.png"/>
                            <p>
                                {{ $t('message.sidebar.billing') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li v-if="user.permission_list.includes('view-billing-seller-billing')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/seller-billing' }"
                                    :class="{ active: $route.name == 'seller-billing' }">

                                    <i class="far fa-user nav-icon"></i>
                                    <p>{{ $t('message.sidebar.seller_billing') }}</p>
                                </router-link>
                            </li>
                            <li v-if="user.permission_list.includes('view-billing-writer-billing')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/writer-billing' }"
                                    :class="{ active: $route.name == 'writer-billing' }">

                                    <i class="far fa-newspaper nav-icon"></i>
                                    <p>{{ $t('message.sidebar.writer_billing') }}</p>
                                </router-link>
                            </li>
                            <li v-if="user.permission_list.includes('view-billing-injection-billing')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/injection-billing' }"
                                    :class="{ active: $route.name == 'injection-billing' }">

                                    <i class="fas fa-link nav-icon"></i>
                                    <p>{{ $t('message.sidebar.injection_billing') }}</p>
                                </router-link>
                            </li>
                            <li v-if="user.permission_list.includes('view-billing-wallet-transaction')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/wallet-transaction' }"
                                    :class="{active: $route.name == 'wallet-transaction'}">

                                    <i class="far fa-money-bill-alt nav-icon"></i>
                                    <p>{{ $t('message.sidebar.wallet_trans') }}</p>
                                </router-link>
                            </li>
                            <li v-if="user.permission_list.includes('view-billing-wallet-summary')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/wallet-summary' }"
                                    :class="{active: $route.name == 'wallet-summary' }">

                                    <i class="fas fa-bars nav-icon"></i>
                                    <p>{{ $t('message.sidebar.wallet_summary') }}</p>
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-header">
                        {{ $t('message.sidebar.main_navigation') }}
                    </li>

                    <!-- Dashboard -->
                    <li class="nav-item">
                        <router-link
                            class="nav-link"
                            :to="{ path: '/' }"
                            :class="{ active: $route.name == 'Dashboard' }">

                            <img src="../../../../images/dashboard.png"/>
                            <p>{{ $t('message.sidebar.dashboard') }}</p>
                        </router-link>
                    </li>

                    <!-- Campaign Setup -->
                    <li v-if="user.permission_list.includes('view-campaign-setup-campaign-setup')" class="nav-item">
                        <router-link
                            class="nav-link"
                            :to="{ path: '/campaign-setup' }"
                            :class="{ active: $route.name == 'campaign-setup' }">

                            <img src="../../../../images/admin-settings.png"/>
                            <p>Campaign Setup</p>
                        </router-link>
                    </li>

                    <!-- URL Prospect -->
                    <li v-if="user.permission_list.includes('view-url-prospect-url-prospect')" class="nav-item">
                        <router-link
                            class="nav-link"
                            :to="{ path: '/url-prospect' }"
                            :class="{ active: $route.name == 'ExtDomain' }">

                            <img src="../../../../images/search-domains.png"/>
                            <p>{{ $t('message.sidebar.url_prospect') }}</p>
                        </router-link>
                    </li>

                    <!-- Mails -->
                    <li
                        v-if="canViewMails"
                        class="nav-item"
                        :class="{
                            active:
                                $route.name == 'Sent' ||
                                $route.name == 'Starred' ||
                                $route.name == 'Trash' ||
                                $route.name == 'Inbox' ||
                                $route.name == 'mail-logs' ||
                                $route.name == 'mail-template',
                            'menu-open':
                                $route.name == 'mail-logs' ||
                                $route.name == 'mail-template' ||
                                $route.name == 'Inbox' ||
                                $route.name == 'Sent' ||
                                $route.name == 'Trash' ||
                                $route.name == 'Starred'
                        }">
                        <router-link
                            class="nav-link"
                            :to="{ path: '/mails/inbox' }"
                            :class="{
                                active:
                                    $route.name == 'Inbox' ||
                                    $route.name == 'Sent' ||
                                    $route.name == 'Starred' ||
                                    $route.name == 'Trash'
                            }">

                            <img src="../../../../images/billing.png"/>
                            <p>{{ $t('message.sidebar.mails') }}</p>
                        </router-link>
                    </li>

                    <!-- Article -->
                    <!-- <li v-if="user.permission_list.includes('view-content-article')" class="nav-item">
                        <router-link
                            class="nav-link"
                            :to="{ path: '/articles' }"
                            :class="{ active: $route.name == 'articles' }">

                            <img src="../../../../images/article.png"/>
                            <p>{{ $t('message.sidebar.articles') }}</p>
                        </router-link>
                    </li> -->

                    <!-- Content -->
                    <li
                        v-if="user.permission_list.includes('view-content-article') || user.permission_list.includes('view-content-setup-chat-gpt')"
                        class="nav-item"
                        :class="{
                            active:
                                $route.name == 'articles' ||
                                $route.name == 'setup-chatgpt',
                            'menu-open':
                                $route.name == 'articles' ||
                                $route.name == 'setup-chatgpt'
                        }">
                        <a href="#" class="nav-link">
                            <img src="../../../../images/article.png"/>
                            <p>
                                {{ $t('message.sidebar.content') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li v-if="user.permission_list.includes('view-content-setup-chat-gpt')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/content/setup-chatgpt' }"
                                    :class="{ active: $route.name == 'setup-chatgpt' }">

                                    <i class="fas fa-cog nav-icon"></i>
                                    <p>{{ $t('message.sidebar.setup_gpt') }}</p>
                                </router-link>
                            </li>
                            <li v-if="user.permission_list.includes('view-content-article')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/content/articles' }"
                                    :class="{ active: $route.name == 'articles' }">

                                    <i class="fas fa-bars nav-icon"></i>
                                    <p>{{ $t('message.sidebar.articles') }}</p>
                                </router-link>
                            </li>
                        </ul>

                    <!-- Seller -->
                    <li
                        v-if="canViewSeller"
                        class="nav-item"
                        :class="{
                            active:
                                $route.name == 'publisher' ||
                                $route.name == 'followup-sales' ||
                                $route.name == 'injection-requests' ||
                                $route.name == 'incomes',
                            'menu-open':
                                $route.name == 'publisher' ||
                                $route.name == 'followup-sales' ||
                                $route.name == 'injection-requests' ||
                                $route.name == 'incomes'
                        }">
                        <a href="#" class="nav-link">
                            <img src="../../../../images/seller.png"/>
                            <p>
                                {{ $t('message.sidebar.seller') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li v-if="user.permission_list.includes('view-seller-list-publisher')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/publisher' }"
                                    :class="{ active: $route.name == 'publisher' }">

                                    <i class="fas fa-bars nav-icon"></i>
                                    <p>{{ $t('message.sidebar.list_publisher') }}</p>
                                </router-link>
                            </li>
                            <li v-if="user.permission_list.includes('view-seller-follow-up-sale')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/followup-sales' }"
                                    :class="{ active: $route.name == 'followup-sales' }">

                                    <i class="fa fa-fw fa-share nav-icon"></i>
                                    <p>{{ $t('message.sidebar.follow_up_sale') }}</p>
                                </router-link>
                            </li>
                            <li v-if="user.permission_list.includes('view-seller-incomes')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/incomes' }"
                                    :class="{ active: $route.name == 'incomes' }">

                                    <i class="fas fa-dollar-sign nav-icon"></i>
                                    <p>{{ $t('message.sidebar.incomes') }}</p>
                                </router-link>
                            </li>
                            <li v-if="user.permission_list.includes('view-seller-injection-requests')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/injection-requests' }"
                                    :class="{ active: $route.name == 'injection-requests' }">

                                    <i class="fas fa-link nav-icon"></i>
                                    <p>Injection Requests</p>
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <!-- Buyer -->
                    <li
                        v-if="canViewBuyer"
                        class="nav-item"
                        :class="{
                            active:
                                $route.name == 'BackLink' ||
                                $route.name == 'list-backlinks' ||
                                $route.name == 'purchase',
                            'menu-open':
                                $route.name == 'BackLink' ||
                                $route.name == 'list-backlinks' ||
                                $route.name == 'purchase'
                        }">
                        <a href="#" class="nav-link">
                            <img src="../../../../images/buyer.png"/>
                            <p>
                                {{ $t('message.sidebar.buyer') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li v-if="user.permission_list.includes('view-buyer-list-backlinks-to-buy')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/list-backlinks' }"
                                    :class="{ active: $route.name == 'list-backlinks' }">

                                    <i class="fas fa-bars nav-icon"></i>
                                    <p>{{ $t('message.sidebar.list_backlinks') }}</p>
                                </router-link>
                            </li>
                            <li v-if="user.permission_list.includes('view-buyer-follow-up-backlinks')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/followup-backlinks' }"
                                    :class="{ active: $route.name == 'BackLink' }">

                                    <i class="fa fa-fw fa-share nav-icon"></i>
                                    <p>{{ $t('message.sidebar.follow_up_backlinks') }}</p>
                                </router-link>
                            </li>

                            <li v-if="user.permission_list.includes('view-buyer-purchase')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/purchase' }"
                                    :class="{ active: $route.name == 'purchase' }">

                                    <i class="fab fa-btc nav-icon"></i>
                                    <p>{{ $t('message.sidebar.purchase') }}</p>
                                </router-link>
                            </li>
                            <li v-if="user.permission_list.includes('view-buyer-follow-up-injection')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/followup-injection' }"
                                    :class="{ active: $route.name == 'followup-injection' }">

                                    <i class="fas fa-link nav-icon"></i>
                                    <p>Follow Up Injection</p>
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <!-- Purchases -->
                    <li
                        v-if="canViewPurchases"
                        class="nav-item"
                        :class="{
                            active:
                                $route.name === 'purchases-config' ||
                                $route.name === 'purchases-summary' ||
                                $route.name === 'purchases-tools' ||
                                $route.name === 'purchases-manual',
                            'menu-open':
                                $route.name === 'purchases-config' ||
                                $route.name === 'purchases-summary' ||
                                $route.name === 'purchases-tools' ||
                                $route.name === 'purchases-manual',
                        }">
                        <a href="#" class="nav-link">
                            <img src="../../../../images/billing.png" alt="Purchases"/>
                            <p>
                                {{ $t('message.sidebar.purchases') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li v-if="user.permission_list.includes('view-purchases-config')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/purchases/config' }"
                                    :class="{ active: $route.name === 'purchases-config' }">

                                    <i class="fas fa-cog nav-icon"></i>
                                    <p>{{ $t('message.sidebar.purchases_config') }}</p>
                                </router-link>
                            </li>
                            <li v-if="user.permission_list.includes('view-purchases-summary')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/purchases/summary' }"
                                    :class="{ active: $route.name === 'purchases-summary' }">

                                    <i class="fas fa-clipboard-list nav-icon"></i>
                                    <p>{{ $t('message.sidebar.purchases_summary') }}</p>
                                </router-link>
                            </li>
                            <li v-if="user.permission_list.includes('view-purchases-tools')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/purchases/tools' }"
                                    :class="{ active: $route.name === 'purchases-tools' }">

                                    <i class="fas fa-tools nav-icon"></i>
                                    <p>{{ $t('message.sidebar.purchases_tools') }}</p>
                                </router-link>
                            </li>
                            <li v-if="user.permission_list.includes('view-purchases-manual')" class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/purchases/manual' }"
                                    :class="{ active: $route.name === 'purchases-manual' }">

                                    <i class="fas fa-cash-register nav-icon"></i>
                                    <p>{{ $t('message.sidebar.purchases_manual') }}</p>
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <!-- Help -->
                    <li class="nav-item">
                        <router-link
                            class="nav-link"
                            :to="{ path: '/help' }"
                            :class="{
                                active:
                                    $route.name == 'help' ||
                                    $route.name == 'seller-guide-1' ||
                                    $route.name == 'seller-guide-2' ||
                                    $route.name == 'seller-guide-3' ||
                                    $route.name == 'seller-guide-4'
                            }">

                            <i class="fa fa-question-circle nav-icon"></i>
                            <p>{{ $t('message.sidebar.help') }}</p>
                        </router-link>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>

        <!-- display only help when the status is 'Processing' -->
        <div class="sidebar" style="overflow-x: hidden;" v-if="isProcessing">
            <nav class="mt-2">
                <ul
                    role="menu"
                    class="nav nav-pills nav-sidebar text-sm nav-flat nav-legacy nav-compact nav-child-indent flex-column"
                    data-widget="treeview"
                    data-accordion="false">

                        <li class="nav-item">
                            <router-link
                                class="nav-link"
                                :to="{ path: '/' }"
                                :class="{ active: $route.name == 'Dashboard' }">

                                <!-- <img src="../../../../images/dashboard.png"/> -->
                                <p> {{ $t('message.sidebar.dashboard') }}</p>
                            </router-link>
                        </li>

                        <li class="nav-item">
                            <router-link
                                class="nav-link"
                                :to="{ path: '/help' }"
                                :class="{
                                    active:
                                        $route.name == 'help' ||
                                        $route.name == 'seller-guide-1' ||
                                        $route.name == 'seller-guide-2' ||
                                        $route.name == 'seller-guide-3' ||
                                        $route.name == 'seller-guide-4'
                                }">

                                <i class="fa fa-question-circle nav-icon"></i>
                                <p>{{ $t('message.sidebar.help') }}</p>
                            </router-link>
                        </li>
                </ul>
            </nav>
        </div>

        <!--        <div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden">-->
        <!--            <div class="os-scrollbar-track">-->
        <!--                <div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--        <div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden">-->
        <!--            <div class="os-scrollbar-track">-->
        <!--                <div class="os-scrollbar-handle" style="height: 69.1117%; transform: translate(0px, 0px);"></div>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--        <div class="os-scrollbar-corner"></div>-->
        <!-- /.sidebar -->
    </aside>
</template>

<style scoped>
    .nav-compact .nav-link>p>.right {
        top: 1.2rem;
    }
</style>

<script>
import {mapActions, mapState} from "vuex";

export default {
    name : "AppAside",
    data() {
        return {
            isSeller : false,
            isBuyer : false,
            isManager : false,
            isPostingWriter : false,
            isPostingWriterExt: false,
            isQc : false,
            isQcBuyer : false,
            isQcSeller : false,
            isQcBilling : false,
            isAffiliate: false,
            isMarketing: false,
            isCsSeller: false,
        };
    },
    created() {
        this.checkAccountType();
    },
    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser
        }),

        isProcessing() {
            let result = false;

            if(this.user.isOurs === 0) {
                result = false;
            } else {
                if(this.user.registration != null) {
                    if(this.user.registration.account_validation === 'processing') {
                        result = true;
                    }
                } else {
                    result = false;
                }
            }

            return result;
        },

        isExtWriter() {
            let result = false;

            if(this.user.isOurs === 1) {
                if(this.user.role_id === 4) {
                    result = true;
                }
            }

            return result;
        },

        canViewAdminHeader () {
            return this.user.isAdmin
                || (this.user.isOurs === 0
                    && (this.isManager
                        || this.isCsSeller
                        || this.isQcBuyer
                        || this.isQc
                        || this.isMarketing));
        },

        canViewAdminSettings () {
            let adminHeader = [
                'view-admin-settings-it',
                'view-admin-settings-finance',
                'view-admin-settings-dev',
            ]

            return this.user.permission_list.some(permission => adminHeader.includes(permission))
        },

        canViewManagement () {
            let managementHeader = [
                'view-management-role',
                'view-management-module',
                'view-management-tools',
                'view-management-mail-logs',
                'view-management-system-logs',
                'view-management-teams',
            ]

            return this.user.permission_list.some(permission => managementHeader.includes(permission))
        },

        canViewBilling () {
            let billingHeader = [
                'view-billing-seller-billing',
                'view-billing-wallet-summary',
                'view-billing-wallet-transaction',
                'view-billing-writer-billing',
            ]

            return this.user.permission_list.some(permission => billingHeader.includes(permission))
        },

        canViewMails () {
            let mailHeader = [
                'view-mails-inbox',
                'view-mails-sent',
                'view-mails-starred',
                'view-mails-trash',
                'view-mails-signatures',
                'view-mails-mail-template',
                'view-mails-drafts',
                'view-mails-auto-replies',
            ]

            return this.user.permission_list.some(permission => mailHeader.includes(permission))
        },

        canViewSeller () {
            let sellerHeader = [
                'view-seller-list-publisher',
                'view-seller-follow-up-sale',
                'view-seller-incomes',
            ]

            return this.user.permission_list.some(permission => sellerHeader.includes(permission))
        },

        canViewBuyer () {
            let buyerHeader = [
                'view-buyer-list-backlinks-to-buy',
                'view-buyer-follow-up-backlinks',
                'view-buyer-purchase',
            ]

            return this.user.permission_list.some(permission => buyerHeader.includes(permission))
        },

        canViewPurchases () {
            let purchaseHeader = [
                'view-purchases-config',
                'view-purchases-summary',
                'view-purchases-tools',
                'view-purchases-manual',
            ]

            return this.user.permission_list.some(permission => purchaseHeader.includes(permission))
        },
    },

    methods : {

        checkAccountType() {
            let that = this.user;

            if (that.role.id == 4 && that.isOurs == 1 && that.user_type.is_exam_passed != 1) {
                this.isPostingWriterExt = true;
            }

            // checking role type
            if (that.role.id == 5) {
                this.isBuyer = true;
            }

            if (that.role.id == 6) {
                this.isSeller = true;
            }

            if (that.role.id == 7) {
                this.isManager = true;
            }

            if (that.role.id == 8) {
                this.isQc = true;
            }

            if (that.role.id == 9) {
                this.isQcBilling = true;
            }

            if (that.role.id == 10) {
                this.isQcSeller = true;
            }

            if (that.role.id == 11) {
                this.isAffiliate = true;
            }

            if (that.role.id == 12) {
                this.isMarketing = true;
            }

            if (that.role.id == 13) {
                this.isPostingWriter = true;
            }

            if (that.role.id == 14) {
                this.isQcBuyer = true;
            }

            if (that.role.id == 15) {
                this.isCsSeller = true;
            }
        }
    }
};
</script>
