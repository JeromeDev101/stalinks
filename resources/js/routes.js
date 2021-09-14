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
import MailTemplate from '@/modules/template_email/views/List.vue'
// import MailLog from '@/modules/logs/views/MailLog.vue';
import Publisher from '@/modules/publisher/views/List.vue'
import FollowupSales from '@/modules/followup_sales/views/List.vue'
import Purchase from '@/modules/purchase/views/List.vue'
import Incomes from '@/modules/incomes/views/List.vue'
import BillingBuyer from '@/modules/billing/buyer/views/List.vue'
import BillingSeller from '@/modules/billing/seller/views/List.vue'
import BillingWriter from '@/modules/billing/writer/views/List.vue'
import Registration from '@/modules/registration/views/List.vue'
import ForgotPassword from '@/modules/registration/views/ForgotPassword.vue'
import RegistrationPage from '@/modules/registration/views/Registration.vue'
import Verification from '@/modules/registration/views/Verification.vue'
import WalletTransaction from '@/modules/wallet_transaction/views/List.vue'
import WalletSummary from '@/modules/wallet_transaction/views/wallet_summary.vue'
import ArticleList from '@/modules/article/views/List.vue'
import Article from '@/modules/article/views/Article.vue'
import Mails from '@/modules/mails/views/Mail.vue'
import GenerateList from '@/modules/generate_list/views/List.vue'

import OverAllIncomes from '@/modules/incomes/admin/views/List.vue'
import ResetPassword from '@/modules/reset_password/views/List.vue'
// import SentMails from '@/modules/mail_sent/views/Sent.vue';
import Inbox from '@/modules/mails/views/Inbox.vue'
import MailSignature from '@/modules/mails/views/MailSignature.vue'
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

// buyers guide
import BuyerGuide1 from '@/modules/help/buyer_guide/views/Help_1.vue'
import BuyerGuide2 from '@/modules/help/buyer_guide/views/Help_2.vue'
import BuyerGuide3 from '@/modules/help/buyer_guide/views/Help_3.vue'

// tools
import Tools from '@/modules/tools/views/Tools.vue'

const routes = [
  {
    path: '/login',
    name: 'login',
    component: Login
  },
  {
    path: '/registration',
    name: 'registration',
    component: RegistrationPage
  },
  {
    path: '/forgot-password',
    name: 'forgot-password',
    component: ForgotPassword
  },
  {
    path: '/reset-password',
    name: 'Reset Password',
    component: ResetPassword
  },
  {
    path: '/test-aw',
    name: 'test_aw',
    component: TestPush
  },
  {
    path: '/verification/:code',
    name: 'verification',
    component: Verification
  },
  {
    path: '/',
    component: Admin,
    meta: { requiresAuth: true },
    name: 'Dashboard',
    redirect: '/',
    children: [
      {
        path: '/',
        component: Dashboard
      },
      {
        path: '/users',
        name: 'List User',
        component: ListUser
      },
      {
        path: '/help',
        name: 'help',
        component: Help,
        children: [
          {
            path: 'seller-guide-1',
            name: 'seller-guide-1',
            component: SellerGuide1
          },
          {
            path: 'seller-guide-2',
            name: 'seller-guide-2',
            component: SellerGuide2
          },
          {
            path: 'seller-guide-3',
            name: 'seller-guide-3',
            component: SellerGuide3
          },
          {
            path: 'seller-guide-4',
            name: 'seller-guide-4',
            component: SellerGuide4
          },
          {
            path: 'writer-guide-1',
            name: 'writer-guide-1',
            component: WriterGuide1
          },
          {
            path: 'writer-guide-2',
            name: 'writer-guide-2',
            component: WriterGuide2
          },
          {
            path: 'writer-guide-3',
            name: 'writer-guide-3',
            component: WriterGuide3
          },
          {
            path: 'buyer-guide-1',
            name: 'buyer-guide-1',
            component: BuyerGuide1
          },
          {
            path: 'buyer-guide-2',
            name: 'buyer-guide-2',
            component: BuyerGuide2
          },
          {
            path: 'buyer-guide-3',
            name: 'buyer-guide-3',
            component: BuyerGuide3
          }
        ]
      },
      {
        path: '/dashboard',
        name: 'dashboard',
        component: DashboardAdmin
      },
      {
        path: '/articles-list',
        name: 'articles-list',
        component: ArticleList
      },
      {
        path: '/overall-incomes',
        name: 'overall-incomes',
        component: OverAllIncomes
      },
      {
        path: '/mails',
        name: 'mails',
        component: Mails,
        children: [
          {
            path: 'sent',
            name: 'Sent',
            component: Inbox
          },
          {
            path: 'inbox',
            name: 'Inbox',
            component: Inbox
          },
          {
            path: 'starred',
            name: 'Starred',
            component: Inbox
          },
          {
            path: 'trash',
            name: 'Trash',
            component: Inbox
          },
          {
            path: 'template',
            name: 'mail-template',
            component: MailTemplate
          },
            {
                path: 'signature',
                name: 'mail-signature',
                component: MailSignature
            }
        ]
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
        component: Article
      },
      {
        path: '/followup-sales',
        name: 'followup-sales',
        component: FollowupSales
      },
      {
        path: '/wallet-transaction',
        name: 'wallet-transaction',
        component: WalletTransaction
      },
      {
        path: '/wallet-summary',
        name: 'wallet-summary',
        component: WalletSummary
      },
      {
        path: '/buyer-billing',
        name: 'buyer-billing',
        component: BillingBuyer
      },
      {
        path: '/writer-billing',
        name: 'writer-billing',
        component: BillingWriter
      },
      {
        path: '/publisher',
        name: 'publisher',
        component: Publisher
      },
      {
        path: '/seller-billing',
        name: 'seller-billing',
        component: BillingSeller
      },
      {
        path: '/purchase',
        name: 'purchase',
        component: Purchase
      },
      {
        path: '/profile/:id',
        name: 'Profile',
        component: Profile
      },
      {
        path: '/url-prospect',
        name: 'ExtDomain',
        component: ExtDomain
      },
      {
        path: '/ext/alexa',
        name: 'AlexaDomain',
        component: AlexaDomain
      },
      {
        path: '/followup-backlinks',
        name: 'BackLink',
        component: BackLink
      },
      {
        path: '/list-backlinks',
        name: 'list-backlinks',
        component: Buy
      },
      {
        path: '/incomes',
        name: 'incomes',
        component: Incomes
      },
      {
        path: '/accounts',
        name: 'Registration',
        component: Registration
      },
      {
        path: 'intdomains',
        name: 'int-domains',
        component: IntDomain
      },
      {
        path: 'hostings',
        name: 'hostings',
        component: Hosting
      },
      {
        path: 'logs',
        name: 'logs',
        component: Log
      },
      {
        path: 'hostings/:id',
        name: 'detail-hosting',
        component: DetailHosting
      },
      {
        path: 'domains',
        name: 'domains',
        component: Domain
      },
      {
        path: 'domains/:id',
        name: 'detail-domains',
        component: DetailDomain
      },
      {
        path: 'system',
        name: 'system',
        component: System
      },
      // {
      //     path: 'mail-template',
      //     name: 'mail-template',
      //     component: MailTemplate,
      // },
      {
        path: 'mail-logs',
        name: 'mail-logs',
        component: Maillog
      },
      {
        path: 'generate-list',
        name: 'generate-list',
        component: GenerateList
      },
        {
            path: 'tools',
            name: 'tools',
            component: Tools
        },
      {
        path: '*',
        name: 'error-page',
        component: ErrorPage
      }
    ]
  }
]

export default routes
