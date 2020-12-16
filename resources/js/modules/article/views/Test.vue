<template>
    <h1>Test Pusher</h1>
</template>

<script>
import { mapState } from 'vuex';
export default {
   
   computed: {
            ...mapState({
                listArticlesAdmin: state => state.storeArticles.listArticlesAdmin,
                listCountries: state => state.storeArticles.listCountries,
                listWriter: state => state.storeArticles.listWriter,
                user: state => state.storeAuth.currentUser,
            })
        },
    mounted(){
        Echo.channel('morley') //Should be Channel Name
            .listen('LikeEvent', (e) => {
               console.log(e);

                if(this.user.id == e.user_id){
                     alert(e.message);
                }
            });

        
    },
}
</script>