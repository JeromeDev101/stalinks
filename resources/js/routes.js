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
import Registration from '@/modules/registration/views/List.vue';
import RegistrationPage from '@/modules/registration/views/Registration.vue';
import Verification from '@/modules/registration/views/Verification.vue';

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
        path: '/publisher',
        name: 'Publisher URL List',
        component: Publisher,
    },
    {
        path: '/profile/:id',
        name: 'Profile',
        component: Profile,
    },
    {
        path: '/ext',
        name: 'ExtDomain',
        component: ExtDomain,
    },
        {
        path: '/ext/alexa',
        name: 'AlexaDomain',
        component: AlexaDomain,
    },
    {
        path: '/backlinks',
        name: 'BackLink',
        component: BackLink,
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
    {
        path: 'mail-template',
        name: 'mail-template',
        component: MailTemplate,
    },
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
