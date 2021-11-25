export const buyerAccess = {
    methods: {
        isShowPriceBasis(user) {
            if (user.isOurs !== 1) {
                return true;
            } else {
                if (user.registration) {
                    return !(user.registration.is_show_price_basis === 0);
                } else {
                    return true;
                }
            }
        },
    }
}
