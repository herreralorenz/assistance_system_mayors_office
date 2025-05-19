<template>
    <Toast></Toast>
    <Loader2 v-if="this.computedUserDetailsLoader"></Loader2>
    <div class="form-group add-users py-4" v-else>
        <div class="container-fluid">
            <section class="user-credentials">
                <h2>{{ this.computedHeaderText }}</h2>
                <hr>
                <div class="row">
                    <div class="col-3">
                        <label for="userLastName">Last Name</label>
                        <input @keyup="this.update('last_name')" id="userLastName" v-model="this.user[0].last_name" type="text" :class="{'form-control':true,'is-invalid':this.proceedValidation.last_name}">
                    </div>
                    <div class="col-3">
                        <label for="userFirstName">First Name</label>
                        <input @keyup="this.update('first_name')" id="userFirstName" type="text" v-model="this.user[0].first_name" :class="{'form-control':true,'is-invalid':this.proceedValidation.first_name}">
                    </div>
                    <div class="col-3">
                        <label for="userMiddleName">Middle Name</label>
                        <input @keyup="this.update('middle_name')" type="text" v-model="this.user[0].middle_name" :class="{'form-control':true,'is-invalid':this.proceedValidation.middle_name}">
                    </div>
                    <div class="col-3">
                        <label for="userSuffix">Suffix</label>
                        <select @change="this.update('suffix')" v-model="this.user[0].suffix" id="suffix" :class="{'form-select':true,'is-invalid':this.proceedValidation.suffix}" >
                            <option selected  value=""></option>
                            <option :key="option.id" :value="option.id" v-for="(option,index) in this.computedFormSeeder.suffix">{{ option.suffix }}</option>
                        </select>
                    </div>
                </div>
                <label for="username">Email</label>
                <input type="text"  @keyup="update('email')" v-model="this.user[0].email" :class="{'form-control':true,'is-invalid':this.proceedValidation.email}" class="form-control">
                <label for="password">Password</label>
                <input @keyup="update('password')" v-model="this.user[0].password" :type="passwordType" :class="{'form-control':true,'is-invalid':this.proceedValidation.password}">
                <label for="password">Confirm Password</label>
                <input :type="passwordType" @keyup="update('confirm_password')" v-model="this.user[0].confirm_password" :class="{'form-control':true, 'is-invalid':this.proceedValidation.confirm_password}">
                <div class="form-check">
                <input @change="this.showPasswordMethod()" class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                    Show password
                </label>
                </div>
                <button v-if="this.$route.path.includes('edit-user')" type="button" class="btn btn-success bg-gradient w-100 h-10 mt-2"
                @click="this.update('update')">UPDATE</button>
                <button v-if="this.$route.path.includes('add-user')" type="button" class="btn btn-success bg-gradient w-100 h-10 mt-2"
                @click="this.update('add')">ADD</button>
            </section>
        </div>
    </div>
</template>
<style scoped>
button{
    border-radius:25px;
}

input, select{
    border-radius: 25px;
}
.add-users{
    padding-left: 250px;
    overflow-y: scroll;
    height: -webkit-calc(100% - 50px);
    height: -moz-calc(100% - 50px);
    height: calc(100% - 50px);
}

.user-credentials{
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5); 
}
</style>
<script>

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import MultiSelect from 'primevue/multiselect';
import Select from 'primevue/select';
import Tag from 'primevue/tag';
import Button from 'primevue/button';
import Loader2 from '../Loader2.vue';
import auth from '../../../router/auth_roles_permissions.js'
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';


export default{
    components:{
        DataTable,
        Column,
        Button,
        Loader2,
        Toast,

    },
    created(){
       
    },
    beforeMount(){
        this.toast = useToast();
        
        if (Array.isArray(this.computedFormSeeder) && this.computedFormSeeder.length <= 0) {

            this.computedUserDetailsLoader = true;
            this.$store.dispatch('fetchSeeders').then(response => {
                if( this.$route.path.includes('edit-user')){
                    this.$store.dispatch('fetchUserDetails',{'id':this.$route.params.id}).then(response => {
                        this.computedHeaderText = 'Edit User';
                        this.computedUserDetailsLoader = false;
                        this.computedUserDetails.user.forEach((x, i) => {
                            for(const [key,value] of Object.entries(x)){
                                this.user[0][key] = value;
                            }
                        });
                    });
                }else{
                    this.computedHeaderText = 'Add User';
                    this.computedUserDetailsLoader = false;
                }   
            });
            
        }else{
            this.computedUserDetailsLoader = true;
            if(this.$route.path.includes('edit-user')){
                this.$store.dispatch('fetchUserDetails',{'id':this.$route.params.id}).then(response => {
                    this.computedHeaderText = 'Edit User';
                    this.computedUserDetailsLoader = false;
                    this.computedUserDetails.user.forEach((x, i) => {
                        for(const [key,value] of Object.entries(x)){
                            this.user[0][key] = value;
                        }
                    });
                });
            }else{
                this.computedHeaderText = 'Add User';
                this.computedUserDetailsLoader = false;
            }
        }
       
        
    },
    mounted(){
        document.body.style.overflow = 'hidden';
        this.computedHeaderText = this.computedHeaderText+' User';
    },
    beforeUnmount(){
        document.body.style.overflow = 'auto';
    },
    data(){
        return{

        }
    },
    methods:{
        update(textField){
            
          

            let proc = true;
            if(textField === 'update'){
                for(const [key,value] of Object.entries(this.textFieldsParams)){
                    if(this.textFieldsParams[key][0] && (new RegExp(this.textFieldsParams[key][1]).test(this.user[0][key]))){
                        this.proceedValidation[textField] = false;   
                    }else if(key === 'confirm_password' && this.textFieldsParams[key] && this.user[0][key] == this.user[0]['password'] && !this.proceedValidation['password']){
                        this.proceedValidation[key] = false;   
                    }else if(typeof this.user[0][key] === 'string' &&  this.user[0][key] &&  this.user[0][key] != '' && this.textFieldsParams[key] && key != 'email' && key != 'key' && key != 'confirm_password'){
                            this.proceedValidation[key] = false;   
                    }else if(!this.textFieldsParams[key]){
                        this.proceedValidation[key] = false;   
                    }else{
                        this.proceedValidation[key] = true;   
                    }
                }
                for(const [key,value] of Object.entries(this.proceedValidation)){
                    if(value){
                        proc = false;
                        break;
                    }
                }

                if(proc){
                    this.computedUserDetailsLoader = true
                    this.$store.dispatch('updateUserDetails',{'id':this.$route.params.id,'user':this.user}).then(response => {
                        this.computedUserDetailsLoader = false;
                        this.user[0].confirm_password = '';
                        this.user[0].password = '';
                        this.toast.add({ severity: 'success', summary: 'Success', detail: 'User updated successfully.', life: 5000 });
                    });
                }

            }else if(textField === 'add'){
                for(const [key,value] of Object.entries(this.textFieldsParams)){
                    if(this.textFieldsParams[key][0] && (new RegExp(this.textFieldsParams[key][1]).test(this.user[0][key]))){
                        this.proceedValidation[textField] = false;   
                    }else if(key === 'confirm_password' && this.textFieldsParams[key] && this.user[0][key] == this.user[0]['password'] && !this.proceedValidation['password']){
                        this.proceedValidation[key] = false;   
                    }else if(typeof this.user[0][key] === 'string' &&  this.user[0][key] &&  this.user[0][key] != '' && this.textFieldsParams[key] && key != 'email' && key != 'key' && key != 'confirm_password'){
                            this.proceedValidation[key] = false;   
                    }else if(!this.textFieldsParams[key]){
                        this.proceedValidation[key] = false;   
                    }else{
                        this.proceedValidation[key] = true;   
                    }
                }
                for(const [key,value] of Object.entries(this.proceedValidation)){
                    if(value){
                        proc = false;
                        break;
                    }
                }

                if(proc){
                    this.computedUserDetailsLoader = true
                    this.$store.dispatch('addUserDetails',{'user':this.user}).then(response => {
                        this.computedUserDetailsLoader = false;
                        this.user[0].confirm_password = '';
                        this.user[0].password = '';
                        console.log(response);
                        if(response.data?.failed?.length < 0){
                            this.toast.add({ severity: 'success', summary: 'Success', detail: 'User added successfully.', life: 5000 });
                        }else{
                            this.toast.add({ severity: 'error', summary: 'Error', detail: 'Email address has been taken.', life: 5000 });
                        }
                        
                    });
                }

            }else{

                if(textField != 'email' && textField != 'password' && textField != 'confirm_password'){
                    if(this.user[0][textField]){
                        this.user[0][textField] = this.user[0][textField].toUpperCase();
                    }
                }
                
                if(this.textFieldsParams[textField][0] && (new RegExp(this.textFieldsParams[textField][1]).test(this.user[0][textField]))){
                    this.proceedValidation[textField] = false;   
                }else if(textField === 'confirm_password' && this.textFieldsParams[textField] && this.user[0][textField] == this.user[0]['password'] && !this.proceedValidation['password']){
                    this.proceedValidation[textField] = false;   
                }else if(typeof this.user[0][textField] === 'string' &&  this.user[0][textField] &&  this.user[0][textField] != '' && this.textFieldsParams[textField] && textField != 'email' && textField != 'password' && textField != 'confirm_password'){
                        this.proceedValidation[textField] = false;   
                }else if(!this.textFieldsParams[textField]){
                    this.proceedValidation[textField] = false;   
                }else{
                    this.proceedValidation[textField] = true;   
                }
            }

           
        
        },
        showPasswordMethod(){
            this.showPassword = !this.showPassword;

            if(this.showPassword){
                this.passwordType = 'text';
            }else{
                this.passwordType = 'password';
            }
        }
    },
    computed:{
        computedFormSeeder: {
            get() {
                return this.$store.state.formSeeder;
            },
        },
        computedUserDetails:{
            get(){
                return this.$store.state.userDetails;
            },
            set(value){
                this.$store.commit('setUserDetails',{'userDetails':value});
            }
        },
        computedUserDetailsLoader:{
            get(){
                return this.$store.state.userDetailsLoader;
            },
            set(value){
                this.$store.commit('setUserDetailsLoader',{'userDetailsLoader':value});
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
        
    },
    beforeRouteEnter(to, from, next){
        auth(to).then(response => {
            if(response){
                    next(response);
            }else{
                    next('/admin');
            }
        });
    },
    data(){
        return {
            toast:null,
            showPassword: false,
            passwordType:'password',
            textFieldsParams: {
                'last_name':true,
                'first_name':true,
                'middle_name':false,
                'suffix':false,
                'email':[true,'^[\\w-\\.]+@([\\w-]+\\.)+[\\w-]{2,4}$'],
                'password':[true,'^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?!.* )(?=.*[^a-zA-Z0-9]).{8,16}$'],
                'confirm_password':true,
            },
            proceedValidation:{
               'last_name':false,
                'first_name':false,
                'middle_name':false,
                'suffix':false,
                'email':false,
                'password':false,
                'confirm_password':false
            },
            user:[
                {
                    "last_name": null,
                    "first_name": null,
                    "middle_name": null,
                    "suffix_id": null,
                    "email": null,
                    "email_verified_at": null,
                    "created_at": null,
                    "updated_at": null,
                    "suffix": null,
                    "password":null,
                    "confirm_password":null,
                }
            ]
        }
    }
}
</script>