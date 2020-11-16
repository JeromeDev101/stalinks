import Login from '@/modules/auth/views/Login.vue';
import ListUser from '@/modules/users/views/List.vue';
import Profile from '@/modules/users/views/Profile.vue';
import ExtDomain from '@/modules/ext_domain/views/List.vue';
import AlexaDomain from '@/modules/ext_domain/views/GetAlexa.vue';
import BackLink from '@/modules/backlink/views/List.vue';
import Hosting from '@/modules/hosting/views/List.vue';
import IntDomain from '@/modules/int_domain/views/List.vue';
import Log from '@/modules/logs/views/List.vue';
import Admin from '@/components/Admin';
import Dashboard from '@/modules/dashboard/views/Dashboard.vue';
import ErrorPage from '@/components/errors/ErrorPage';
import DetailHosting from '@/modules/hosting/views/DetailHosting.vue'
import DetailDomain from '@/modules/domain/views/DetailDomain.vue'
import Domain from '@/modules/domain/views/Domain.vue'
import System from '@/modules/system/views/List.vue'
import MailTemplate from '@/modules/template_email/views/List.vue'
import MailLog from '@/modules/logs/views/MailLog.vue';
import Publisher from '@/modules/publisher/views/List.vue';
import FollowupSales from '@/modules/followup_sales/views/List.vue';
import Purchase from '@/modules/purchase/views/List.vue';
import Incomes from '@/modules/incomes/views/List.vue';
import BillingBuyer from '@/modules/billing/buyer/views/List.vue';
import BillingSeller from '@/modules/billing/seller/views/List.vue';
import BillingWriter from '@/modules/billing/writer/views/List.vue';
import Registration from '@/modules/registration/views/List.vue';
import RegistrationPage from '@/modules/registration/views/Registration.vue';
import Verification from '@/modules/registration/views/Verification.vue';
import WalletTransaction from '@/modules/wallet_transaction/views/List.vue';
import ArticleList from '@/modules/article/views/List.vue';
import Article from '@/modules/article/views/Article.vue';
import Mails from '@/modules/mails/views/Mail.vue';
import OverAllIncomes from '@/modules/incomes/admin/views/List.vue';
// import SentMails from '@/modules/mail_sent/views/Sent.vue';
import Inbox from '@/modules/mails/views/Inbox.vue';
// import ArticleContent from '@/modules/article/views/Content.vue';
import Buy from '@/modules/buy/views/List.vue';

const routes = [{
    path: '/login',
    name: 'login',
    component: Login,
},
{
    path: '/registration',
    name: 'registration',
    component: RegistrationPage,
},
{
    path: '/verification/:code',
    name: 'verification',
    component: Verification,
},
{
    path: '/',
    component: Admin,
    meta: { requiresAuth: true },
    name: 'Dashboard',
    redirect: '/',
    children: [{
        path: '/',
        component: Dashboard,
    },
    {
        path: '/users',
        name: 'List User',
        component: ListUser,
    },
    {
        path: '/articles-list',
        name: 'articles-list',
        component: ArticleList,
    },
    {
        path: '/overall-incomes',
        name: 'overall-incomes',
        component: OverAllIncomes,
    },
    {
        path: '/mails',
        name: 'mails',
        children: [{
            path: 'sent',
            name: 'Sent',
            component: Inbox,
        },{
            path: 'inbox',
            name: 'Inbox',
            component: Inbox,
        },{
            path: 'starred',
            name: 'Starred',
            component: Inbox,
        },{
            path: 'trash',
            name: 'Trash',
            component: Inbox,
        },{
            path: 'template',
            name: 'mail-template',
            component: MailTemplate,
        }],
        component: Mails,

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
        path: '/articles',
        name: 'articles',
        component: Article,
    },
    {
        path: '/followup-sales',
        name: 'followup-sales',
        component: FollowupSales,
    },
    {
        path: '/wallet-transaction',
        name: 'wallet-transaction',
        component: WalletTransaction,
    },
    {
        path: '/buyer-billing',
        name: 'buyer-billing',
        component: BillingBuyer,
    },
    {
        path: '/writer-billing',
        name: 'writer-billing',
        component: BillingWriter,
    },
    {
        path: '/publisher',
        name: 'publisher',
        component: Publisher,
    },
    {
        path: '/seller-billing',
        name: 'seller-billing',
        component: BillingSeller,
    },
    {
        path: '/purchase',
        name: 'purchase',
        component: Purchase,
    },
    {
        path: '/profile/:id',
        name: 'Profile',
        component: Profile,
    },
    {
        path: '/url-prospect',
        name: 'ExtDomain',
        component: ExtDomain,
    },
        {
        path: '/ext/alexa',
        name: 'AlexaDomain',
        component: AlexaDomain,
    },
    {
        path: '/followup-backlinks',
        name: 'BackLink',
        component: BackLink,
    },
    {
        path: '/list-backlinks',
        name: 'list-backlinks',
        component: Buy,
    },
    {
        path: '/incomes',
        name: 'incomes',
        component: Incomes,
    },
    {
        path: '/accounts',
        name: 'Registration',
        component: Registration,
    },
    {
        path: 'intdomains',
        name: 'int-domains',
        component: IntDomain,
    },
    {
        path: 'hostings',
        name: 'hostings',
        component: Hosting,
    },
    {
        path: 'logs',
        name: 'logs',
        component: Log,
    },
    {
        path: 'hostings/:id',
        name: 'detail-hosting',
        component: DetailHosting,
    },
    {
        path: 'domains',
        name: 'domains',
        component: Domain,
    },
    {
        path: 'domains/:id',
        name: 'detail-domains',
        component: DetailDomain,
    },
    {
        path: 'system',
        name: 'system',
        component: System,
    },
    // {
    //     path: 'mail-template',
    //     name: 'mail-template',
    //     component: MailTemplate,
    // },
    {
        path: 'mail-logs',
        name: 'mail-logs',
        component: MailLog,
    },
    {
        path: '*',
        name: 'error-page',
        component: ErrorPage,
    }]
}
];

export default routes;
