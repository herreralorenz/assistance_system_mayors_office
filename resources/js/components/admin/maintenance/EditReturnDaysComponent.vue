<template>
    <Loader2 v-if="this.computedReturnDaysLoader"></Loader2>
    <Toast></Toast>
    <section class="edit-return-days form-group py-4">
        <div class="container-fluid">
            <div class="container-return-days">
                <h2>Return Days</h2>
                <hr>
                <input  v-on:keyup="this.checkReturnDays('returnDays')" type="text" :class="{'form-control':true,'is-invalid':this.returnDaysValidation}" v-model="this.computedReturnDays" placeholder="Enter amount of days...">
                <button type="button" class="btn btn-success w-100 mt-3" @click="this.checkReturnDays('update')">UPDATE</button>
            </div>
        </div>
    </section>
</template>
<style scoped>
input, select, textarea, button{
    border-radius: 25px;

}

.edit-return-days{
    margin: 0;
    padding-left: 250px;
    overflow-y: auto;
    height: -webkit-calc(100% - 50px);
    height: -moz-calc(100% - 50px);
    height: calc(100% - 50px);
}

.container-return-days{
    border-radius: 10px;
    padding: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5); 
}

</style>
<script>
import Loader2 from '../Loader2.vue'
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import auth from '../../../router/auth_roles_permissions.js';

export default{
    components:{
        Loader2,
        Toast
    },
    beforeMount(){
        
        this.computedHeaderText = 'Edit Return Days';
        this.computedReturnDaysLoader = true;
        this.$store.dispatch('fetchReturnDays').then(response =>{
            this.computedReturnDaysLoader = false;
           
        });
    },
    mounted(){
        document.body.style.overflow = 'hidden';
        this.toast = useToast();
    },
    beforeUnmount(){
        document.body.style.overflow = 'auto';
    },
    data(){
        return {
            returnDaysValidation:false,
            toast:null
        }
    },
    methods:{
        checkReturnDays(textfield){

            if(textfield == 'returnDays' || textfield == 'update'){
                if(/^\d+$/.test(this.computedReturnDays) ){
                    this.returnDaysValidation = false;
                }else{
                    this.returnDaysValidation = true;
                }
            }
           
            if(textfield == 'update'){
                if(!this.returnDaysValidation){
                    this.computedReturnDaysLoader = true;
                    this.$store.dispatch('updateReturnDays',{'returnDays':this.computedReturnDays}).then(response =>{
                        this.computedReturnDaysLoader = false
                        this.toast.add({ severity: 'success', summary: 'Success', detail: 'Return days successfully updated.', life: 5000 });
                    });
                }
            }
            
          
        }
    },
    computed:{
        computedReturnDaysLoader:{
            get(){
                return this.$store.state.returnDaysLoader;
            },
            set(value){
                this.$store.commit('setReturnDaysLoader',{'returnDaysLoader':value});
            }
        },
        computedHeaderText:{
            get(){
                return this.$store.state.headerText;
            },
            set(value){
                this.$store.commit('setHeaderText',{headerText:value});
            }
        },
        computedReturnDays:{
            get(){
                return this.$store.state.returnDays;
            },
            set(value){
                this.$store.commit('setReturnDays',{returnDays:value});
            }
        }
    },
    beforeRouteEnter(to, from, next){
        auth(to).then(response => {
            if(response){
                    next(response);
            }else{
                    next('/admin');
            }
        });
    }
}

</script>