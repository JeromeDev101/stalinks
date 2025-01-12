import Vue from 'vue';
import Vuex from 'vuex'
import * as Cookies from 'js-cookie';
import storeLoading from '@/modules/loading/store';
import storeAccount from '@/modules/registration/store';
import storeAuth from '@/modules/auth/store';
import storeUser from '@/modules/users/store';
import storeArticles from '@/modules/article/store';
import storeExtDomain from '@/modules/ext_domain/store';
import storePublisher from '@/modules/publisher/store';
import storeIncomes from '@/modules/incomes/store';
import storeIncomesAdmin from '@/modules/incomes/admin/store';
import storeBuy from '@/modules/buy/store';
import storePurchase from '@/modules/purchase/store';
import storeIntDomain from '@/modules/int_domain/store';
import storeBackLink from '@/modules/backlink/store';
import storeHosting from '@/modules/hosting/store';
import storeFollowupSales from '@/modules/followup_sales/store';
import storeWalletTransaction from '@/modules/wallet_transaction/store';
import storeBillingBuyer from '@/modules/billing/buyer/store';
import storeBillingSeller from '@/modules/billing/seller/store';
import storeBillingWriter from '@/modules/billing/writer/store';
import storeDashboard from '@/modules/dashboard/store';
import storeLog from '@/modules/logs/store';
import storeDomain from '@/modules/domain/store';
import storeSystem from '@/modules/system/store';
import emailTemplateSystem from '@/modules/template_email/store';
import storeMailgun from '@/modules/mails/store';
import storeNotification from "@/modules/notification/store";
import storeTools from "@/modules/tools/store";
import storePurchases from "@/modules/purchases/store";
import createPersistedState from 'vuex-persistedstate';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        storeAuth,
        storeUser,
        storeAccount,
        storeLoading,
        storePublisher,
        storeBuy,
        storeArticles,
        storeWalletTransaction,
        storeBillingBuyer,
        storeBillingSeller,
        storeBillingWriter,
        storePurchase,
        storeExtDomain,
        storeIncomes,
        storeIntDomain,
        storeBackLink,
        storeHosting,
        storeFollowupSales,
        storeLog,
        storeDomain,
        storeDashboard,
        storeSystem,
        storeIncomesAdmin,
        storeMailgun,
        emailTemplateSystem,
        storeNotification,
        storeTools,
        storePurchases
    },
    plugins: [createPersistedState({
        paths: [
            'storeAuth.token',
            'storeAuth.currentUser'
        ],
    })],
});
