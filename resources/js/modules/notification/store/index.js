import NotificationService from "@/modules/notification/api";

const NOTIFICATIONS = 'NOTIFICATIONS';

const state = {
    notifications : {}
}

const mutations = {
    [NOTIFICATIONS](state, payload) {
        state.notifications = payload;
    }
}

const actions = {
    async getUserNotifications({commit}, id) {
        let response = await NotificationService.getNotifications(id);

        if (response.status === 200) {
            commit(NOTIFICATIONS, response.data);
        }
    },

    async seenUserNotifications({commit}, id) {
        await NotificationService.seenNotifications(id);
    }
}

const storeNotification = {
    state,
    actions,
    mutations
};

export default storeNotification;
