import PurchaseService from '@/modules/purchases/api';

const PURCHASES = 'PURCHASES';
const PURCHASES_BUYERS = 'PURCHASES_BUYERS';
const MANUAL_PURCHASES = 'MANUAL_PURCHASES';
const MANUAL_PURCHASES_BUYERS = 'MANUAL_PURCHASES_BUYERS';
const TOOLS_PURCHASES = 'TOOLS_PURCHASES';
const TOOLS_PURCHASES_BUYERS = 'TOOLS_PURCHASES_BUYERS';
const CATEGORIES = 'CATEGORIES';
const CATEGORIES_SELECTION = 'CATEGORIES_SELECTION';
const TYPES = 'TYPES';
const TYPES_SELECTION = 'TYPES_SELECTION';
const MODULES_SELECTION = 'MODULES_SELECTION';
const PURCHASES_MESSAGE_FORMS = 'PURCHASES_MESSAGE_FORMS';
const MANUAL_PURCHASES_MESSAGE_FORMS = 'MANUAL_PURCHASES_MESSAGE_FORMS';
const TOOLS_PURCHASES_MESSAGE_FORMS = 'TOOLS_PURCHASES_MESSAGE_FORMS';
const CATEGORIES_MESSAGE_FORMS = 'CATEGORIES_MESSAGE_FORMS';
const TYPES_MESSAGE_FORMS = 'TYPES_MESSAGE_FORMS';

// graphs

const CATEGORY_REPORT = 'CATEGORY_REPORT';
const PURCHASE_TYPE_REPORT = 'PURCHASE_TYPE_REPORT';
const PAYMENT_METHOD_REPORT = 'PAYMENT_METHOD_REPORT';

const state = {
    purchases: { data:[] },
    purchasesBuyers: { data:[] },
    manuals: { data:[] },
    manualBuyers: { data:[] },
    tools: { data:[] },
    toolBuyers: { data:[] },
    categories: { data:[] },
    categoriesSelection: { data:[] },
    types: { data:[] },
    typesSelection: { data:[] },
    moduleSelection: { data:[] },
    messageFormsPurchases: { action: '', message: '', errors: {} },
    messageFormsManualPurchases: { action: '', message: '', errors: {} },
    messageFormsToolsPurchases: { action: '', message: '', errors: {} },
    messageFormsCategories: { action: '', message: '', errors: {} },
    messageFormsTypes: { action: '', message: '', errors: {} },

    // graphs
    categoryReport: { data: [] },
    purchaseTypeReport: { data: [] },
    paymentMethodReport: { data: [] },
};

const mutations = {
    [PURCHASES](state, { purchases }) {
        return state.purchases = purchases;
    },

    [PURCHASES_BUYERS](state, { purchasesBuyers }) {
        return state.purchasesBuyers = purchasesBuyers;
    },

    [MANUAL_PURCHASES](state, { manuals }) {
        return state.manuals = manuals;
    },

    [MANUAL_PURCHASES_BUYERS](state, { manualBuyers }) {
        return state.manualBuyers = manualBuyers;
    },

    [TOOLS_PURCHASES](state, { tools }) {
        return state.tools = tools;
    },

    [TOOLS_PURCHASES_BUYERS](state, { toolBuyers }) {
        return state.toolBuyers = toolBuyers;
    },

    [CATEGORIES](state, { categories }) {
        return state.categories = categories;
    },

    [CATEGORIES_SELECTION](state, { categoriesSelection }) {
        return state.categoriesSelection = categoriesSelection;
    },

    [TYPES](state, { types }) {
        return state.types = types;
    },

    [TYPES_SELECTION](state, { typesSelection }) {
        return state.typesSelection = typesSelection;
    },

    [MODULES_SELECTION](state, { moduleSelection }) {
        return state.moduleSelection = moduleSelection;
    },

    [PURCHASES_MESSAGE_FORMS] (state, payload) {
        state.messageFormsPurchases = payload;
    },

    [MANUAL_PURCHASES_MESSAGE_FORMS] (state, payload) {
        state.messageFormsManualPurchases = payload;
    },

    [TOOLS_PURCHASES_MESSAGE_FORMS] (state, payload) {
        state.messageFormsToolsPurchases = payload;
    },

    [CATEGORIES_MESSAGE_FORMS] (state, payload) {
        state.messageFormsCategories = payload;
    },

    [TYPES_MESSAGE_FORMS] (state, payload) {
        state.messageFormsTypes = payload;
    },

    // graphs

    [CATEGORY_REPORT](state, { categoryReport }) {
        return state.categoryReport = categoryReport;
    },

    [PURCHASE_TYPE_REPORT](state, { purchaseTypeReport }) {
        return state.purchaseTypeReport = purchaseTypeReport;
    },

    [PAYMENT_METHOD_REPORT](state, { paymentMethodReport }) {
        return state.paymentMethodReport = paymentMethodReport;
    },
};

const actions = {
    // PURCHASE SUMMARY
    async actionGetPurchases ({ commit }, params){
        try {
            let response = await PurchaseService.getPurchases(params)
            commit(PURCHASES , { purchases: response.data });
            commit(PURCHASES_MESSAGE_FORMS, {
                action: 'loaded',
                message: 'Purchases successfully loaded.',
                errors: {}
            });
        }catch(e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(PURCHASES_MESSAGE_FORMS, errors);
            } else {
                commit(PURCHASES_MESSAGE_FORMS, e.response.data);
            }
        }
    },

    async actionGetPurchaseBuyerSelection ({ commit }, params){
        try {
            let response = await PurchaseService.getPurchasesBuyerSelection(params)
            commit(PURCHASES_BUYERS , { purchasesBuyers: response.data });
            commit(PURCHASES_MESSAGE_FORMS, {
                action: 'loaded',
                message: 'Purchases buyer selection successfully loaded.',
                errors: {}
            });
        }catch(e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(PURCHASES_MESSAGE_FORMS, errors);
            } else {
                commit(PURCHASES_MESSAGE_FORMS, e.response.data);
            }
        }
    },

    async actionUpdatePurchase ({ commit }, params) {
        try {
            let response = await PurchaseService.updatePurchase(params);

            if (response.status === 200 && response.data.success === true) {
                commit(PURCHASES_MESSAGE_FORMS, {
                    action: 'updated',
                    message: 'Purchase successfully updated!',
                    errors: {}
                });
            }
            else if (response.response.status === 422) {
                commit(PURCHASES_MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(PURCHASES_MESSAGE_FORMS, errors);
            } else {
                commit(PURCHASES_MESSAGE_FORMS, e.response.data);
            }
        }
    },

    async actionDeletePurchase ({ commit }, params) {
        try {
            let response = await PurchaseService.deletePurchase(params);

            if (response.status === 200 && response.data.success === true) {
                commit(PURCHASES_MESSAGE_FORMS, {
                    action: 'deleted',
                    message: 'Purchase successfully deleted!',
                    errors: {}
                });
            }
            else if (response.response.status === 422) {
                commit(PURCHASES_MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(PURCHASES_MESSAGE_FORMS, errors);
            } else {
                commit(PURCHASES_MESSAGE_FORMS, e.response.data);
            }
        }
    },

    // PURCHASE CATEGORIES
    async actionGetPurchaseCategories ({ commit }, params){
        try {
            let response = await PurchaseService.getPurchaseCategories(params)
            commit(CATEGORIES , { categories: response.data });
            commit(CATEGORIES_MESSAGE_FORMS, {
                action: 'loaded',
                message: 'Purchase categories successfully loaded.',
                errors: {}
            });
        }catch(e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(CATEGORIES_MESSAGE_FORMS, errors);
            } else {
                commit(CATEGORIES_MESSAGE_FORMS, e.response.data);
            }
        }
    },

    async actionGetPurchaseCategoriesSelection ({ commit }, params){
        try {
            let response = await PurchaseService.getPurchaseCategoriesSelection(params)
            commit(CATEGORIES_SELECTION , { categoriesSelection: response.data });
            commit(CATEGORIES_MESSAGE_FORMS, {
                action: 'loaded',
                message: 'Purchase categories selection successfully loaded.',
                errors: {}
            });
        }catch(e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(CATEGORIES_MESSAGE_FORMS, errors);
            } else {
                commit(CATEGORIES_MESSAGE_FORMS, e.response.data);
            }
        }
    },

    async actionAddPurchaseCategory ({ commit }, params) {
        try {
            let response = await PurchaseService.addPurchaseCategory(params);

            if (response.status === 200 && response.data.success === true) {
                commit(CATEGORIES_MESSAGE_FORMS, {
                    action: 'added',
                    message: 'Purchase category successfully added!',
                    errors: {}
                });
            }
            else if (response.response.status === 422) {
                commit(CATEGORIES_MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(CATEGORIES_MESSAGE_FORMS, errors);
            } else {
                commit(CATEGORIES_MESSAGE_FORMS, e.response.data);
            }
        }
    },

    async actionUpdatePurchaseCategory ({ commit }, params) {
        try {
            let response = await PurchaseService.updatePurchaseCategory(params);

            if (response.status === 200 && response.data.success === true) {
                commit(CATEGORIES_MESSAGE_FORMS, {
                    action: 'updated',
                    message: 'Purchase category successfully updated!',
                    errors: {}
                });
            }
            else if (response.response.status === 422) {
                commit(CATEGORIES_MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(CATEGORIES_MESSAGE_FORMS, errors);
            } else {
                commit(CATEGORIES_MESSAGE_FORMS, e.response.data);
            }
        }
    },

    // PURCHASE TYPES
    async actionGetPurchaseTypesSelection ({ commit }, params){
        try {
            let response = await PurchaseService.getPurchaseTypesSelection(params)
            commit(TYPES_SELECTION , { typesSelection: response.data });
            commit(TYPES_MESSAGE_FORMS, {
                action: 'loaded',
                message: 'Purchase types selection successfully loaded.',
                errors: {}
            });
        }catch(e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(TYPES_MESSAGE_FORMS, errors);
            } else {
                commit(TYPES_MESSAGE_FORMS, e.response.data);
            }
        }
    },

    async actionAddPurchaseType ({ commit }, params) {
        try {
            let response = await PurchaseService.addPurchaseType(params);

            if (response.status === 200 && response.data.success === true) {
                commit(TYPES_MESSAGE_FORMS, {
                    action: 'added',
                    message: 'Purchase type successfully added!',
                    errors: {}
                });
            }
            else if (response.response.status === 422) {
                commit(TYPES_MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(TYPES_MESSAGE_FORMS, errors);
            } else {
                commit(TYPES_MESSAGE_FORMS, e.response.data);
            }
        }
    },

    async actionUpdatePurchaseType ({ commit }, params) {
        try {
            let response = await PurchaseService.updatePurchaseType(params);

            if (response.status === 200 && response.data.success === true) {
                commit(TYPES_MESSAGE_FORMS, {
                    action: 'updated',
                    message: 'Purchase type successfully updated!',
                    errors: {}
                });
            }
            else if (response.response.status === 422) {
                commit(TYPES_MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(TYPES_MESSAGE_FORMS, errors);
            } else {
                commit(TYPES_MESSAGE_FORMS, e.response.data);
            }
        }
    },

    // MANUAL PURCHASE
    async actionGetManualPurchase ({ commit }, params){
        try {
            let response = await PurchaseService.getPurchases(params)
            commit(MANUAL_PURCHASES , { manuals: response.data });
            commit(MANUAL_PURCHASES_MESSAGE_FORMS, {
                action: 'loaded',
                message: 'Manual purchases successfully loaded.',
                errors: {}
            });
        }catch(e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(MANUAL_PURCHASES_MESSAGE_FORMS, errors);
            } else {
                commit(MANUAL_PURCHASES_MESSAGE_FORMS, e.response.data);
            }
        }
    },

    async actionGetManualPurchaseBuyerSelection ({ commit }, params){
        try {
            let response = await PurchaseService.getPurchasesBuyerSelection(params)
            commit(MANUAL_PURCHASES_BUYERS , { manualBuyers: response.data });
            commit(MANUAL_PURCHASES_MESSAGE_FORMS, {
                action: 'loaded',
                message: 'Manual purchases buyer selection successfully loaded.',
                errors: {}
            });
        }catch(e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(MANUAL_PURCHASES_MESSAGE_FORMS, errors);
            } else {
                commit(MANUAL_PURCHASES_MESSAGE_FORMS, e.response.data);
            }
        }
    },

    async actionAddManualPurchase ({ commit }, params) {
        try {
            let response = await PurchaseService.addPurchase(params);

            if (response.status === 200 && response.data.success === true) {
                commit(MANUAL_PURCHASES_MESSAGE_FORMS, {
                    action: 'added',
                    message: 'Manual purchase successfully added!',
                    errors: {}
                });
            }
            else if (response.response.status === 422) {
                commit(MANUAL_PURCHASES_MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(MANUAL_PURCHASES_MESSAGE_FORMS, errors);
            } else {
                commit(MANUAL_PURCHASES_MESSAGE_FORMS, e.response.data);
            }
        }
    },

    async actionUpdateManualPurchase ({ commit }, params) {
        try {
            let response = await PurchaseService.updatePurchase(params);

            if (response.status === 200 && response.data.success === true) {
                commit(MANUAL_PURCHASES_MESSAGE_FORMS, {
                    action: 'updated',
                    message: 'Manual purchase successfully updated!',
                    errors: {}
                });
            }
            else if (response.response.status === 422) {
                commit(MANUAL_PURCHASES_MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(MANUAL_PURCHASES_MESSAGE_FORMS, errors);
            } else {
                commit(MANUAL_PURCHASES_MESSAGE_FORMS, e.response.data);
            }
        }
    },

    async actionDeleteManualPurchase ({ commit }, params) {
        try {
            let response = await PurchaseService.deletePurchase(params);

            if (response.status === 200 && response.data.success === true) {
                commit(MANUAL_PURCHASES_MESSAGE_FORMS, {
                    action: 'deleted',
                    message: 'Manual purchase successfully deleted!',
                    errors: {}
                });
            }
            else if (response.response.status === 422) {
                commit(MANUAL_PURCHASES_MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(MANUAL_PURCHASES_MESSAGE_FORMS, errors);
            } else {
                commit(MANUAL_PURCHASES_MESSAGE_FORMS, e.response.data);
            }
        }
    },

    // TOOLS PURCHASE

    async actionGetToolsPurchases ({ commit }, params){
        try {
            let response = await PurchaseService.getPurchases(params)
            commit(TOOLS_PURCHASES , { tools: response.data });
            commit(TOOLS_PURCHASES_MESSAGE_FORMS, {
                action: 'loaded',
                message: 'Tool purchases successfully loaded.',
                errors: {}
            });
        }catch(e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(TOOLS_PURCHASES_MESSAGE_FORMS, errors);
            } else {
                commit(TOOLS_PURCHASES_MESSAGE_FORMS, e.response.data);
            }
        }
    },

    async actionGetToolsPurchaseBuyerSelection ({ commit }, params){
        try {
            let response = await PurchaseService.getPurchasesBuyerSelection(params)
            commit(TOOLS_PURCHASES_BUYERS , { toolBuyers: response.data });
            commit(TOOLS_PURCHASES_MESSAGE_FORMS, {
                action: 'loaded',
                message: 'Tool purchases buyer selection successfully loaded.',
                errors: {}
            });
        }catch(e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(TOOLS_PURCHASES_MESSAGE_FORMS, errors);
            } else {
                commit(TOOLS_PURCHASES_MESSAGE_FORMS, e.response.data);
            }
        }
    },

    async actionUpdateToolsPurchase ({ commit }, params) {
        try {
            let response = await PurchaseService.updatePurchase(params);

            if (response.status === 200 && response.data.success === true) {
                commit(TOOLS_PURCHASES_MESSAGE_FORMS, {
                    action: 'updated',
                    message: 'Tool purchase successfully updated!',
                    errors: {}
                });
            }
            else if (response.response.status === 422) {
                commit(TOOLS_PURCHASES_MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(TOOLS_PURCHASES_MESSAGE_FORMS, errors);
            } else {
                commit(TOOLS_PURCHASES_MESSAGE_FORMS, e.response.data);
            }
        }
    },

    async actionDeleteToolsPurchase ({ commit }, params) {
        try {
            let response = await PurchaseService.deletePurchase(params);

            if (response.status === 200 && response.data.success === true) {
                commit(TOOLS_PURCHASES_MESSAGE_FORMS, {
                    action: 'deleted',
                    message: 'Tool purchase successfully deleted!',
                    errors: {}
                });
            }
            else if (response.response.status === 422) {
                commit(TOOLS_PURCHASES_MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(TOOLS_PURCHASES_MESSAGE_FORMS, errors);
            } else {
                commit(TOOLS_PURCHASES_MESSAGE_FORMS, e.response.data);
            }
        }
    },

    // MESSAGE FORMS

    clearMessageFormPurchases ({commit}) {
        commit(PURCHASES_MESSAGE_FORMS, { action: '', message: '', errors: {}});
    },

    clearMessageFormManualPurchases ({commit}) {
        commit(MANUAL_PURCHASES_MESSAGE_FORMS, { action: '', message: '', errors: {}});
    },

    clearMessageFormToolPurchases ({commit}) {
        commit(TOOLS_PURCHASES_MESSAGE_FORMS, { action: '', message: '', errors: {}});
    },

    clearMessageFormCategories ({commit}) {
        commit(CATEGORIES_MESSAGE_FORMS, { action: '', message: '', errors: {}});
    },

    clearMessageFormTypes ({commit}) {
        commit(TYPES_MESSAGE_FORMS, { action: '', message: '', errors: {}});
    },

    // GRAPHS

    async actionGetCategoryReportData ({ commit }, params){
        try {
            let response = await PurchaseService.getCategoryReportData(params)
            commit(CATEGORY_REPORT , { categoryReport: response.data });
        }catch(e) {
            let errors = e.response.data.errors;
            console.log(errors)
        }
    },

    async actionGetPurchaseTypeReportData ({ commit }, params){
        try {
            let response = await PurchaseService.getPurchaseTypeReportData(params)
            commit(PURCHASE_TYPE_REPORT , { purchaseTypeReport: response.data });
        }catch(e) {
            let errors = e.response.data.errors;
            console.log(errors)
        }
    },

    async actionGetPaymentMethodReportData ({ commit }, params){
        try {
            let response = await PurchaseService.getPaymentMethodReportData(params)
            commit(PAYMENT_METHOD_REPORT , { paymentMethodReport: response.data });
        }catch(e) {
            let errors = e.response.data.errors;
            console.log(errors)
        }
    },

    // MODULE SELECTION
    async actionGetPurchaseModuleSelection ({ commit }, params){
        try {
            let response = await PurchaseService.getPurchaseModuleSelection(params)
            commit(MODULES_SELECTION , { moduleSelection: response.data });
        }catch(e) {
            let errors = e.response.data.errors;
            console.log(errors)
        }
    },
};

const storePurchases = {
    state,
    actions,
    mutations,
};

export default storePurchases;
