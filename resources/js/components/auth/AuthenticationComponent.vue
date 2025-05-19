<template>
    <router-view :key="$route.fullPath" name="login"></router-view>
    <router-view v-slot="{ Component }">
        <component :is="Component" ref="routerView" :key="$route.fullPath"/>
    </router-view>
</template>
<style scoped>


</style>
<script>


import LoginComponent from '../auth/LoginComponent.vue'
export default{
    components:{
        LoginComponent
    },
    beforeMount(){

    },
    mounted(){
       
    }
    
}
</script>