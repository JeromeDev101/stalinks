export const dateRange = {
    methods: {
        generateDefaultDateRange () {
            let today = new Date()
            today.setHours(0, 0, 0, 0)

            let yesterday = new Date()
            yesterday.setDate(today.getDate() - 1)
            yesterday.setHours(0, 0, 0, 0);

            return {
                'Today': [today, today],
                'Yesterday': [yesterday, yesterday],
                'This month': [
                    new Date(today.getFullYear(), today.getMonth(), 1),
                    new Date(today.getFullYear(), today.getMonth() + 1, 0)
                ],
                'Last month': [
                    new Date(today.getFullYear(), today.getMonth() - 1, 1),
                    new Date(today.getFullYear(), today.getMonth(), 0)
                ],
                'This year': [
                    new Date(today.getFullYear(), 0, 1),
                    new Date(today.getFullYear(), 11, 31)
                ],
                'Last year': [
                    new Date(today.getFullYear() - 1, 0, 1),
                    new Date(today.getFullYear() - 1, 11, 31)
                ],
            }
        }
    }
}
