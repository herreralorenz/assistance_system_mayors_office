<template>
        <div v-if="!isPrintRoute" class="router" :key="$route.fullPath">
                <router-view :key="$route.fullPath" name="header"></router-view>
                <div v-if="this.computedAuthCheckUserRolesPermissionsLoader === false">
                        <router-view v-if="this.computedFetchAuthUserRolesPermissions['user_permissions'].some(value => value.name === 'requestAssistance') || this.computedFetchAuthUserRolesPermissions['user_roles'].some(value => {if(value === 'superAdmin'){return true}}) " :key="$route.fullPath" name="progressbar"></router-view>
                </div>   
                <router-view :key="$route.fullPath" name="navigationbar"></router-view>
                <router-view :key="$route.fullPath" name="welcome"></router-view>
                <router-view :key="$route.fullPath" name="dashboard"></router-view>
                <router-view v-if="$route.fullPath != '/admin' && $route.fullPath != '/dashboard'"  v-slot="{ Component }" >
                        <component :is="Component" ref="routerView" :key="$route.fullPath"/>
                </router-view>
        </div>
        <div v-else-if="isPrintRoute">
                <router-view v-slot="{ Component }">
                        <component :is="Component" ref="routerView" :key="$route.fullPath"/>
                </router-view>
        </div>
</template>
<style scoped>


.v-leave-active {
        transition: opacity 0.5s ease;
}


.v-leave-to {
        opacity: 0;
}

.router{
        background-color: rgb(247, 248, 250);
        height: 100%;
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
}

</style>

<script>
import auth from '../../router/auth_roles_permissions.js';

export default {
        components:{
 
        },
        watch:{
                
        },
        created(){
            
        },
        beforeMount(){
                
        },
        mounted(){

        },
        data(){
                return{
                  
                }
        },
        computed: {
                computedAuthCheckUserRolesPermissionsLoader:{
                        get(){
                                return this.$store.state.authCheckUserRolesPermissionsLoader;
                        },
                        set(value){
                                this.$store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':value});
                        }
                },
                computedFetchAuthUserRolesPermissions:{
                        get(){
                                return this.$store.state.authCheckUserRolesPermissions;
                        },
                        set(value){
                                this.$store.commit('setAuthCheckUserRolesPermissions',{'authCheckUserRolesPermissions':value});     
                        }
                },
                computedFormSeederLoader: {
                        get() {
                         return this.$store.state.formSeederLoader;
                        },
                        set(value){
                         this.$store.commit('setFormLoader',{'formSeederLoader':value});
                        } 
                },
                isPrintRoute() {
                 return this.$route.path.startsWith('/bulk-printing-of-receipt-holder');
                }
                
        },
        methods:{
          
        },
        beforeRouteEnter(to, from, next) {
                auth(to).then(response => {
                        if(response){
                                next(response);
                        }else{
                                next('/admin');
                        }
                });

                
        },
        beforeRouteLeave(to, from, next){
                next(true);
        }

}


</script>