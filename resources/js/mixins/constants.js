export const Constants = {
    AUTO_LOGOUT_DURATION: 60,

    // Add new routes in here for logs
    LOG_PAGES: [
        {
            name: 'Admin Settings - IT',
            path: '/system/it',
        },
        {
            name: 'Admin Settings - Finance',
            path: '/system/finance',
        },
        {
            name: 'Management - Role',
            path: '/management/roles',
        },
        {
            name: 'Management - Modules',
            path: '/management/modules',
        },
        {
            name: 'Management - Tools',
            path: '/management/tools',
        },
        {
            name: 'Management - Mail Logs',
            path: '/management/mail-logs',
        },
        {
            name: 'Management - System Logs',
            path: '/management/logs',
        },
        {
            name: 'Management - Team',
            path: '/management/teams',
        },
        {
            name: 'Admin Dashboard',
            path: '/dashboard',
        },
        {
            name: 'Get Alexa',
            path: '/ext/alexa',
        },
        {
            name: 'Generate List',
            path: '/generate-list',
        },
        {
            name: 'Registration Accounts',
            path: '/accounts',
        },
        {
            name: 'Survey Dashboard',
            path: '/survey-dashboard',
        },
        {
            name: 'Admin Article',
            path: '/articles-list',
        },
        {
            name: "Writer's Validation",
            path: '/validate-writer',
        },
        {
            name: 'Backlinks Prospect',
            path: '/backlink-prospect',
        },
        {
            name: 'Admin Incomes',
            path: '/overall-incomes',
        },
        {
            name: 'Billing - Seller Billing',
            path: '/seller-billing',
        },
        {
            name: 'Billing - Writer Billing',
            path: '/writer-billing',
        },
        {
            name: 'Billing - Injection Billing',
            path: '/injection-billing',
        },
        {
            name: 'Billing - Wallet Transaction',
            path: '/wallet-transaction',
        },
        {
            name: 'Billing - Wallet Summary',
            path: '/wallet-summary',
        },
        {
            name: 'Dashboard',
            path: '/',
        },
        {
            name: 'Campaign Setup',
            path: '/campaign-setup',
        },
        {
            name: 'URL Prospect',
            path: '/url-prospect',
        },
        {
            name: 'Mails - Inbox',
            path: '/mails/inbox',
        },
        {
            name: 'Mails - Sent',
            path: '/mails/sent',
        },
        {
            name: 'Mails - Starred',
            path: '/mails/starred',
        },
        {
            name: 'Mails - Trash',
            path: '/mails/trash',
        },
        {
            name: 'Mails - Draft',
            path: '/mails/drafts',
        },
        {
            name: 'Mails - Template',
            path: '/mails/template',
        },
        {
            name: 'Mails - Signatures',
            path: '/mails/signature',
        },
        {
            name: 'Mails - Auto Replies',
            path: '/mails/auto',
        },
        {
            name: 'Article',
            path: '/articles',
        },
        {
            name: 'Setup ChatGpt',
            path: '/content/setup-chatgpt',
        },
        {
            name: 'Seller - List Publisher',
            path: '/publisher',
        },
        {
            name: 'Seller - Follow up Sale',
            path: '/followup-sales',
        },
        {
            name: 'Seller - Incomes',
            path: '/incomes',
        },
        {
            name: 'Seller - Injection Requests',
            path: '/injection-requests',
        },
        {
            name: 'Buyer - List Backlinks to Buy',
            path: '/list-backlinks',
        },
        {
            name: 'Buyer - Follow up Backlinks',
            path: '/followup-backlinks',
        },
        {
            name: 'Buyer - Purchase',
            path: '/purchase',
        },
        {
            name: 'Buyer - Follow up Injection',
            path: '/followup-injection',
        },
        {
            name: 'Purchases - Config',
            path: '/purchases/config',
        },
        {
            name: 'Purchases - Summary',
            path: '/purchases/summary',
        },
        {
            name: 'Purchases - Tools',
            path: '/purchases/tools',
        },
        {
            name: 'Purchases - Manual',
            path: '/purchases/manual',
        },
    ],

    // Add page groups here for module selection
    PAGE_GROUPS: {
        'Admin Settings': [
            'IT',
            'Finance',
            'Dev'
        ],
        'Management': [
            'Role',
            'Module',
            'Tools',
            'Mail Logs',
            'System Logs',
            'Teams'
        ],
        'Admin Dashboard': [
            'Admin Dashboard'
        ],
        'Get Alexa': [
            'Get Alexa'
        ],
        'Generate List': [
            'Generate List'
        ],
        'Registration Accounts': [
            'Registration Accounts'
        ],
        'Survey Dashboard': [
            'Survey Dashboard'
        ],
        'Admin Article': [
            'Admin Article'
        ],
        "Writer's Validation": [
            "Writer's Validation"
        ],
        'Backlinks Prospect': [
            'Backlinks Prospect'
        ],
        'Incomes': [
            'Admin Incomes'
        ],
        'Billing': [
            'Seller Billing',
            'Writer Billing',
            'Injection Billing',
            'Wallet Transaction',
            'Wallet Summary'
        ],
        'Dashboard': [
            'Dashboard'
        ],
        'Campaign Setup': [
            'Campaign Setup'
        ],
        'URL Prospect': [
            'URL Prospect'
        ],
        'Mails': [
            'Inbox',
            'Sent',
            'Starred',
            'Trash',
            'Drafts',
            'Mail Template',
            'Signatures',
            'Auto Replies'
        ],
        'Content': [
            'Setup ChatGpt',
        ],
        'Article':[
            'Article'
        ],
        'Seller': [
            'List Publisher',
            'Follow up Sale',
            'Incomes',
            'Injection Requests'
        ],
        'Buyer': [
            'List Backlinks to Buy',
            'Follow up Backlinks',
            'Purchase',
            'Follow up Injection'
        ],
        'Purchases': [
            'Config',
            'Summary',
            'Tools',
            'Manual'
        ]
    }
}
