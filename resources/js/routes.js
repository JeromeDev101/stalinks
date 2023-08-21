import Login from '@/modules/auth/views/Login.vue'
import ListUser from '@/modules/users/views/List.vue'
import Profile from '@/modules/users/views/Profile.vue'
import ExtDomain from '@/modules/ext_domain/views/List.vue'
import AlexaDomain from '@/modules/ext_domain/views/GetAlexa.vue'
import BackLink from '@/modules/backlink/views/List.vue'
import Hosting from '@/modules/hosting/views/List.vue'
import IntDomain from '@/modules/int_domain/views/List.vue'
import Log from '@/modules/logs/views/List.vue'
import Admin from '@/components/Admin'
import Dashboard from '@/modules/dashboard/views/Dashboard.vue'
import DashboardAdmin from '@/modules/dashboard/views/DashboardAdmin.vue'
import ErrorPage from '@/components/errors/ErrorPage'
import DetailHosting from '@/modules/hosting/views/DetailHosting.vue'
import DetailDomain from '@/modules/domain/views/DetailDomain.vue'
import Domain from '@/modules/domain/views/Domain.vue'
import System from '@/modules/system/views/List.vue'
import Finance from "./modules/system/views/Finance";
import IT from "./modules/system/views/IT";
import Devs from "./modules/system/views/Devs";
import MailTemplate from '@/modules/template_email/views/List.vue';
import AdminSurvey from './modules/survey/views/AdminSurveyTwo'
import AdminSurveyLegacy from './modules/survey/views/AdminSurvey'
// import MailLog from '@/modules/logs/views/MailLog.vue';
import Publisher from '@/modules/publisher/views/List.vue'
import FollowupSales from '@/modules/followup_sales/views/List.vue'
import Purchase from '@/modules/purchase/views/List.vue'
import Incomes from '@/modules/incomes/views/List.vue'
import InjectionRequests from '@/modules/injection_requests/views/InjectionRequests.vue'
import FollowupInjections from '@/modules/injection_requests/views/FollowupInjections.vue'
import BillingBuyer from '@/modules/billing/buyer/views/List.vue'
import BillingSeller from '@/modules/billing/seller/views/List.vue'
import BillingWriter from '@/modules/billing/writer/views/List.vue'
import Registration from '@/modules/registration/views/List.vue'
import ForgotPassword from '@/modules/registration/views/ForgotPassword.vue'
import RegistrationPage from '@/modules/registration/views/Registration.vue'
import RegistrationSuccess from '@/modules/registration/views/RegistrationSuccess.vue'
import Verification from '@/modules/registration/views/Verification.vue'
import OrderConfirmation from '@/modules/registration/views/order_verification.vue'
import CancelOrderConfirmation from '@/modules/registration/views/cancel_order_verification.vue'
import WalletTransaction from '@/modules/wallet_transaction/views/List.vue'
import WalletSummary from '@/modules/wallet_transaction/views/wallet_summary.vue'
import ArticleList from '@/modules/article/views/List.vue'
import Article from '@/modules/article/views/Article.vue'
import Mails from '@/modules/mails/views/Mail.vue'
import GenerateList from '@/modules/generate_list/views/List.vue'
import ValidateWriter from '@/modules/writers_validation/views/List.vue'
import BacklinksProspect from '@/modules/backlink_prospect/views/List.vue'
import Role from '@/modules/role/views/List.vue'
import Module from '@/modules/module/views/List.vue'

import OverAllIncomes from '@/modules/incomes/admin/views/List.vue'
import ResetPassword from '@/modules/reset_password/views/List.vue'
// import SentMails from '@/modules/mail_sent/views/Sent.vue';
import Inbox from '@/modules/mails/views/Inbox.vue'
import MailSignature from '@/modules/mails/views/MailSignature.vue'
import MailDrafts from '@/modules/mails/views/MailDrafts.vue'
import AutoReply from '@/modules/mails/views/AutoReply.vue'
import Maillog from '@/modules/mails/views/Maillog.vue'
// import ArticleContent from '@/modules/article/views/Content.vue';
import Buy from '@/modules/buy/views/List.vue'
import TestPush from '@/modules/article/views/Test.vue'

// Guide
import Help from '@/modules/help/help.vue'

// sellers guide
import SellerGuide1 from '@/modules/help/seller_guide/views/FirstHelp.vue'
import SellerGuide2 from '@/modules/help/seller_guide/views/Help_2.vue'
import SellerGuide3 from '@/modules/help/seller_guide/views/help_3.vue'
import SellerGuide4 from '@/modules/help/seller_guide/views/help_4.vue'

// writers guide
import WriterGuide1 from '@/modules/help/writer_guide/views/Help_1.vue'
import WriterGuide2 from '@/modules/help/writer_guide/views/Help_2.vue'
import WriterGuide3 from '@/modules/help/writer_guide/views/Help_3.vue'
import WriterGuide4 from '@/modules/help/writer_guide/views/Help_4.vue'

// buyers guide
import BuyerGuide1 from '@/modules/help/buyer_guide/views/Help_1.vue'
import BuyerGuide2 from '@/modules/help/buyer_guide/views/Help_2.vue'
import BuyerGuide3 from '@/modules/help/buyer_guide/views/Help_3.vue'
import BuyerGuide4 from '@/modules/help/buyer_guide/views/Help_4.vue'

// tools
import Tools from '@/modules/tools/views/Tools.vue'

// survey
import Survey from '@/modules/survey/views/Survey.vue'
import SurveySeller from '@/modules/survey/views/SellerSurvey.vue'
import WriterSurvey from '@/modules/survey/views/WriterSurvey.vue'

// subscription
import Subscription from '@/modules/subscription/views/Subscription.vue'

// purchases
import Config from '@/modules/purchases/views/Config.vue'
import Manual from '@/modules/purchases/views/Manual.vue'
import Summary from '@/modules/purchases/views/Summary.vue'
import PurchaseTools from '@/modules/purchases/views/Tools.vue'

// campaign setup
import CampaignSetup from '@/modules/campaign/views/CampaignSetup.vue'

import store from './store';

// CHECK MIXINS > CONSTANTS, ADD NEW ROUTES THERE FOR SYSTEM LOGS

const routes = [
    {
        path : '/login',
        name : 'login',
        component : Login,
    },
    {
        path : '/registration',
        name : 'registration',
        component : RegistrationPage,
    },
    {
        path : '/registration-successful',
        name : 'Registration Successful',
        component : RegistrationSuccess,
    },
    {
        path : '/forgot-password',
        name : 'forgot-password',
        component : ForgotPassword,
    },
    {
        path : '/reset-password',
        name : 'Reset Password',
        component : ResetPassword,
    },
    {
        path : '/test-aw',
        name : 'test_aw',
        component : TestPush,
    },
    {
        path : '/verification/:code',
        name : 'verification',
        component : Verification,
    },
    {
        path : '/order-confirmation/:id',
        name : 'order-confirmation',
        component : OrderConfirmation,
    },
    {
        path : '/subscribe/:sub/:code',
        name : 'subscription',
        component : Subscription,
    },
    {
        path : '/survey/:set/:code',
        name : 'survey',
        component : Survey,
    },
    {
        path : '/seller/survey/:set/:code',
        name : 'survey-seller',
        component : SurveySeller,
    },
    {
        path : '/writer/survey/:set/:code',
        name : 'survey-writer-code',
        component : WriterSurvey,
    },
    {
        path : '/cancel-order-confirmation/:id',
        name : 'cancel-order-confirmation',
        component : CancelOrderConfirmation,
    },
    {
        path : '/',
        component : Admin,
        meta : {requiresAuth : true},
        name : 'Dashboard',
        redirect : '/',
        children : [
            {
                path : '/',
                component : Dashboard,
            },
            {
                path : '/management/teams',
                name : 'List User',
                component : ListUser,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.isAdmin) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/help',
                name : 'help',
                component : Help,
                children : [
                    {
                        path : 'seller-guide-1',
                        name : 'seller-guide-1',
                        component : SellerGuide1,
                    },
                    {
                        path : 'seller-guide-2',
                        name : 'seller-guide-2',
                        component : SellerGuide2,
                    },
                    {
                        path : 'seller-guide-3',
                        name : 'seller-guide-3',
                        component : SellerGuide3,
                    },
                    {
                        path : 'seller-guide-4',
                        name : 'seller-guide-4',
                        component : SellerGuide4,
                    },
                    {
                        path : 'writer-guide-1',
                        name : 'writer-guide-1',
                        component : WriterGuide1,
                    },
                    {
                        path : 'writer-guide-2',
                        name : 'writer-guide-2',
                        component : WriterGuide2,
                    },
                    {
                        path : 'writer-guide-3',
                        name : 'writer-guide-3',
                        component : WriterGuide3,
                    },
                    {
                        path : 'writer-guide-4',
                        name : 'writer-guide-4',
                        component : WriterGuide4,
                    },
                    {
                        path : 'buyer-guide-1',
                        name : 'buyer-guide-1',
                        component : BuyerGuide1,
                    },
                    {
                        path : 'buyer-guide-2',
                        name : 'buyer-guide-2',
                        component : BuyerGuide2,
                    },
                    {
                        path : 'buyer-guide-3',
                        name : 'buyer-guide-3',
                        component : BuyerGuide3,
                    },
                    {
                        path : 'buyer-guide-4',
                        name : 'buyer-guide-4',
                        component : BuyerGuide4,
                    },
                ],
            },
            {
                path : '/dashboard',
                name : 'dashboard',
                component : DashboardAdmin,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-admin-dashboard-admin-dashboard')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/articles-list',
                name : 'articles-list',
                component : ArticleList,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-admin-article-admin-article')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/validate-writer',
                name : 'validate-writer',
                component : ValidateWriter,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-writer\'s-validation-writer\'s-validation')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/backlink-prospect',
                name : 'backlink-prospect',
                component : BacklinksProspect,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-backlinks-prospect-backlinks-prospect')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/management/roles',
                name : 'roles',
                component : Role,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-management-role')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/management/modules',
                name : 'modules',
                component : Module,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-management-module')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/overall-incomes',
                name : 'overall-incomes',
                component : OverAllIncomes,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-incomes-admin-incomes')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/mails',
                name : 'mails',
                component : Mails,
                children : [
                    {
                        path : 'sent',
                        name : 'Sent',
                        component : Inbox,
                    },
                    {
                        path : 'inbox',
                        name : 'Inbox',
                        component : Inbox,
                    },
                    {
                        path : 'starred',
                        name : 'Starred',
                        component : Inbox,
                    },
                    {
                        path : 'trash',
                        name : 'Trash',
                        component : Inbox,
                    },
                    {
                        path : 'template',
                        name : 'mail-template',
                        component : MailTemplate,
                    },
                    {
                        path : 'signature',
                        name : 'mail-signature',
                        component : MailSignature,
                    },
                    {
                        path : 'drafts',
                        name : 'Drafts',
                        component : MailDrafts,
                    },
                    {
                        path : 'auto',
                        name : 'auto-reply',
                        component : AutoReply,
                    },
                ],
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-mails-inbox')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            // {
            //     path: '/sent',
            //     name: 'sent',
            //     component: SentMails,
            // },
            // {
            //     path: '/deleted',
            //     name: 'deleted',
            //     component: DeleteMails,
            // },
            {
                path : '/articles',
                name : 'articles',
                component : Article,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-article-article')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/followup-sales',
                name : 'followup-sales',
                component : FollowupSales,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-seller-follow-up-sale')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/injection-requests',
                name : 'injection-requests',
                component : InjectionRequests,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-seller-injection-requests')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/followup-injection',
                name : 'followup-injection',
                component : FollowupInjections,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-buyer-follow-up-injection')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/wallet-transaction',
                name : 'wallet-transaction',
                component : WalletTransaction,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-billing-wallet-transaction')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/wallet-summary',
                name : 'wallet-summary',
                component : WalletSummary,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-billing-wallet-summary')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/buyer-billing',
                name : 'buyer-billing',
                component : BillingBuyer,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.isAdmin) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/writer-billing',
                name : 'writer-billing',
                component : BillingWriter,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-billing-writer-billing')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/publisher',
                name : 'publisher',
                component : Publisher,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-seller-list-publisher')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/seller-billing',
                name : 'seller-billing',
                component : BillingSeller,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-billing-seller-billing')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/purchase',
                name : 'purchase',
                component : Purchase,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-buyer-purchase')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/profile/:id',
                name : 'Profile',
                component : Profile,
            },
            {
                path : '/url-prospect',
                name : 'ExtDomain',
                component : ExtDomain,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-url-prospect-url-prospect')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/ext/alexa',
                name : 'AlexaDomain',
                component : AlexaDomain,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-get-alexa-get-alexa')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/followup-backlinks',
                name : 'BackLink',
                component : BackLink,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-buyer-follow-up-backlinks')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/list-backlinks',
                name : 'list-backlinks',
                component : Buy,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-buyer-list-backlinks-to-buy')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/incomes',
                name : 'incomes',
                component : Incomes,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-seller-incomes')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/accounts',
                name : 'Registration',
                component : Registration,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-registration-accounts-registration-accounts')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/survey-dashboard',
                name : 'survey-dashboard',
                component : AdminSurvey,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-survey-dashboard-survey-dashboard')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/survey-dashboard-legacy',
                name : 'survey-dashboard-legacy',
                component : AdminSurveyLegacy,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-survey-dashboard-survey-dashboard')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : 'intdomains',
                name : 'int-domains',
                component : IntDomain,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.isAdmin) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : 'hostings',
                name : 'hostings',
                component : Hosting,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.isAdmin) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/management/logs',
                name : 'logs',
                component : Log,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-management-system-logs')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : 'hostings/:id',
                name : 'detail-hosting',
                component : DetailHosting,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.isAdmin) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : 'domains',
                name : 'domains',
                component : Domain,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.isAdmin) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : 'domains/:id',
                name : 'detail-domains',
                component : DetailDomain,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.isAdmin) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            /*{
                path : 'system',
                name : 'system',
                component : System,
            },*/
            {
                path : 'system/it',
                name : 'system-it',
                component : IT,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-admin-settings-it')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : 'system/finance',
                name : 'system-finance',
                component : Finance,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-admin-settings-finance')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : 'system/dev',
                name : 'system-dev',
                component : Devs,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-admin-settings-dev')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            // {
            //     path: 'mail-template',
            //     name: 'mail-template',
            //     component: MailTemplate,
            // },
            {
                path : '/management/mail-logs',
                name : 'mail-logs',
                component : Maillog,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-management-mail-logs')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : 'generate-list',
                name : 'generate-list',
                component : GenerateList,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-generate-list-generate-list')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/management/tools',
                name : 'tools',
                component : Tools,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-management-tools')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/purchases/config',
                name : 'purchases-config',
                component : Config,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-purchases-config')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/purchases/summary',
                name : 'purchases-summary',
                component : Summary,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-purchases-summary')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/purchases/tools',
                name : 'purchases-tools',
                component : PurchaseTools,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-purchases-tools')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/purchases/manual',
                name : 'purchases-manual',
                component : Manual,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-purchases-manual')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/campaign-setup',
                name : 'campaign-setup',
                component : CampaignSetup,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.permission_list.includes('view-campaign-setup-campaign-setup')) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/survey/:set',
                name : 'survey',
                component : Survey,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.isAdmin || store.state.storeAuth.currentUser.role_id === 5) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/seller/survey/:set',
                name : 'survey-seller',
                component : SurveySeller,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.isAdmin || store.state.storeAuth.currentUser.role_id === 6) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '/writer/survey/:set',
                name : 'survey-writer',
                component : WriterSurvey,
                beforeEnter: (to, from, next) => {
                    if(store.state.storeAuth.currentUser.isAdmin || store.state.storeAuth.currentUser.role_id === 4) {
                        next();
                    } else {
                        next('*');
                    }
                }
            },
            {
                path : '*',
                name : 'error-page',
                component : ErrorPage,
            },
        ],
    },
]

export default routes
