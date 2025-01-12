import BackLinkService from '@/modules/backlink/api';

const BACKLINK_SET_TOTAL = 'BACKLINK_SET_TOTAL';
const PRICE_SET_TOTAL = 'PRICE_SET_TOTAL';
const BACKLINK_SET_ERROR = 'BACKLINK_SET_ERROR';
const LIST_BACKLINK = 'LIST_BACKLINK'
const LIST_BUYER_BOUGHT = 'LIST_BUYER_BOUGHT'
const MESSAGE_FORMS = 'MESSAGE_FORMS'
const FILLTER = 'FILLTER'
const state = {
    totalBackLink: 0,
    totalPrice: 0,
    listBackLink: {},
    listBuyerBought: { data:[] },
    error: {},
    fillter: {
        sub_buyer_id: '',
        url_advertiser: '',
        page: 0,
        querySearch: '',
        full_data: false,
        int_id: 0,
        status: '',
        seller: '',
        buyer: '',
        backlink_id: '',
        paginate: '50',
        process_date : {
            startDate: null,
            endDate: null
        },
        date_completed : {
            startDate: null,
            endDate: null
        }
    },
    tblFollowupBacklinksOpt: {
        id_backlink: true,
        seller: true,
        buyer: true,
        url_publisher: true,
        url_advertiser: true,
        link_from: true,
        link_to: true,
        price: true,
        prices: false,
        code_comb: true,
        code_price: true,
        price_basis: true,
        anchor_text: true,
        date_for_process: true,
        date_completed: true,
        status: true,
    },
    messageBacklinkForms: { obj: {}, action: '', message: '', errors: {} },
};

const mutations = {

    [BACKLINK_SET_TOTAL](state, totalBackLink) {
        state.totalBackLink = totalBackLink;
    },

    [PRICE_SET_TOTAL](state, totalPrice) {
        state.totalPrice = totalPrice;
    },


    [BACKLINK_SET_ERROR](state, error) {
        state.error = error;
    },

    [LIST_BACKLINK](state, listBackLink) {
        state.listBackLink = listBackLink
    },

    [LIST_BUYER_BOUGHT](state, dataSet){
        state.listBuyerBought = dataSet.listBuyerBought;
    },

    [MESSAGE_FORMS] (state, payload) {
        state.messageBacklinkForms = payload;
    },
    [FILLTER] (state, payload) {
        state.fillter = payload
    }
};

const actions = {
    async filterBackLink({ commit }, {vue, params}) {
        try {
            let response = await BackLinkService.getTotalBackLink(params);
            commit(BACKLINK_SET_TOTAL, response.data)
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(BACKLINK_SET_ERROR, errors);
            } else {
                commit(BACKLINK_SET_ERROR, e.response.data);
            }
        }
    },

    async filterPrice({ commit }, {vue, params}) {
        try {
            let response = await BackLinkService.getTotalPrice(params);
            commit(PRICE_SET_TOTAL, response.data)
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(BACKLINK_SET_ERROR, errors);
            } else {
                commit(BACKLINK_SET_ERROR, e.response.data);
            }
        }
    },

    async actionGetBackLink({ commit }, {vue, page, params}) {
        try {
            let response = await BackLinkService.actionGetBackLink(page, params);
            commit(LIST_BACKLINK, response.data)
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(BACKLINK_SET_ERROR, errors);
            } else {
                commit(BACKLINK_SET_ERROR, e.response.data);
            }
        }
    },

    async actionGetListBuyerBought({ commit }, params){
        try {
            let response = await BackLinkService.getListBuyerBought(params)
            commit(LIST_BUYER_BOUGHT , { listBuyerBought: response.data });
        }catch(e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(BACKLINK_SET_ERROR, errors);
            } else {
                commit(BACKLINK_SET_ERROR, e.response.data);
            }
        }
    },

    async actionDeleteBacklink({ commit }, {params}) {
        try {
            let response = await BackLinkService.deleteBacklink(params);

            if (response.status === 200) {
                commit(MESSAGE_FORMS, { action: 'deleted_backlink', message: '# Saved !' + response.data, errors: {} });
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(BACKLINK_SET_ERROR, errors);
            } else {
                commit(BACKLINK_SET_ERROR, e.response.data);
            }
        }
    },

    async actionSaveBacklink({ commit }, {params}) {
        try {
            let response = await BackLinkService.actionSaveBacklink(params);
            if (response.status === 200) {
                if (response.data.success === true) {
                    commit(MESSAGE_FORMS, { action: 'saved_backlink', message: '#' + response.data.data.id + ' Saved !', errors: {} });
                } else {
                    commit(MESSAGE_FORMS, { action: 'saved_backlink', message: '# Saved !', errors: {} });
                }
            }
            else if (response.response.status === 422) {
                commit(MESSAGE_FORMS, response.response.data);
            }
        } catch (e) {
            let errors = e.response.data.errors;
            if (errors) {
                commit(BACKLINK_SET_ERROR, errors);
            } else {
                commit(BACKLINK_SET_ERROR, e.response.data);
            }
        }
    },

    clearMessageBacklinkForm({commit}) {
        commit(MESSAGE_FORMS, { action: '', message: '', errors: {}});
    },
    actionResetFillterBacklink({commit}) {
        commit(FILLTER, {
            sub_buyer_id: '',
            url_advertiser: '',
            page: 0,
            querySearch: '',
            full_data: false,
            int_id: 0,
            status: '',
            seller: '',
            buyer: '',
            backlink_id: '',
            paginate: '50',
            process_date : {
                startDate: null,
                endDate: null
            },
            date_completed : {
                startDate: null,
                endDate: null
            }
        });
    }

};

const storeBackLink = {
    state,
    actions,
    mutations,
};
export default storeBackLink;
