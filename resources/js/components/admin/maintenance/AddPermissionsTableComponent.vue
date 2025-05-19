<template>
    <Loader2 v-if="this.computedUsersLoader"></Loader2>
    <div v-else class="form-group add-roles py-2" id="add-roles">
        <div class="container-fluid">
            <section class="searchbar">
                <div class="row">
                    <div class="col-1 d-flex flex-column ">
                        <span class="text-center text-secondary">Total:</span>
                        <span class="text-center text-success">{{ this.computedUsers.countUsers }}</span>
                    </div>
                    <div class="col-11">
                        <i class="fa-solid fa-user fa-lg icon"></i>
                        <input ref="user" id="user" :class="{ 'form-control': true, 'input-field': true }" v-model="this.computedUsersSearch" type="text" @keyup="usersSearch()"
                            placeholder="User">
                    </div>
                </div>
            </section>
            <section class="users">
                <LoaderSearch v-if="this.computedUsersSearchLoader"></LoaderSearch>
                <div class="to-add-roles mt-3" v-for="(option,index) in this.computedUsers.users[paginator]" v-else>
                    <div class="row">
                        <div class="col-6">
                            <div class="d-flex flex-column">
                                <span class="h3 fw-bold text-success">{{ (option.first_name)+" "+(option?.middle_name ?? "")+" "+(option.last_name) }}</span>
                            </div>
                            <div class="vl mt- ps-2 d-flex flex-column">
                                <span class="text-secondary">Username: <span
                                        class="text-secondary fw-bold">{{option.email}}</span></span>
                            </div>
                        </div>
                        <div class="col-6 d-flex align-items-end justify-content-center flex-column">
                            <button type="button" class="btn btn-success bg-gradient w-50 h-75"
                            @click="addPermissions(option.id)">ADD PERMISSIONS</button>
                        </div>
                    </div>
                </div>
                <div class="card mt-3">
                <Paginator @page="paginatorChange"  :rows="5" :totalRecords="this.computedUsers.countUsers"></Paginator>
                </div>
            </section>
        </div>
    </div>
</template>
<style scoped>
button{
    border-radius: 25px;
}

.add-roles {
   margin: 0;
    padding-left: 250px;
    overflow-y: auto;
    height: -webkit-calc(100% - 50px);
    height: -moz-calc(100% - 50px);
    height: calc(100% - 50px);
}

input {
    padding-left: 35px;
    border-radius: 25px;
    height: 50px;
}


.icon {
    padding: 10px;
    margin-top: 15px;
    min-width: 40px;
    position: absolute;
}

.users {
    padding: 10px;
    height: 85vh;
    overflow-x: hidden;
    overflow-y: auto;
}

.to-add-roles {
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    width: 100%;
    box-sizing: border-box;
    padding: 10px;
    transition: transform 0.3s ease;
}
</style>
<script>
import Loader2 from '../Loader2.vue'
import LoaderSearch from '../LoaderSearch.vue';
import auth from '../../../router/auth_roles_permissions.js';
import Paginator from 'primevue/paginator';


export default{
    components:{
        Loader2,
        LoaderSearch,
        Paginator
    },
    beforeMount(){
        this.computedHeaderText = 'Add Permissions';
        this.computedUsersLoader = true;
        this.$store.dispatch('fetchUsers').then(response =>{
            this.computedUsersLoader = false;
        });
       
    },
    mounted(){
        document.body.style.overflow = 'hidden';
    },
    beforeUnmount(){
        document.body.style.overflow = 'auto';
    },
    methods:{
        paginatorChange(event){
            this.paginator = event.page;
            const addRoles = document.getElementById("add-roles");

            addRoles.scrollTo({
            top: 0,
            behavior: 'instant' // Enables smooth scrolling
            });
        },
        usersSearch(){ 
            this.computedUsersSearchLoader = true;
            clearTimeout(this.timeout);
            
            this.timeout = setTimeout(() => {
                this.$store.dispatch('searchUserRoles',{'user_fullname':this.computedUsersSearch}).then(response =>{
                    this.computedUsersSearchLoader = false;
                });
            }, 450);
        },
        addPermissions(userID){
            this.$router.push({ name: 'addUserPermissions',params:{'id':userID} });
        }
    },
    data(){
        return{
            paginator:0,
            timeout:null,
        }
    },
    computed:{
        
        computedUsersSearchLoader:{
            get(){
                return this.$store.state.usersSearchLoader;
            },
            set(value){
                this.$store.commit('setUsersSearchLoader',{'usersSearchLoader':value});
            }
        },
        computedUsersSearch:{
            get(){
                return this.$store.state.usersSearch
            },
            set(value){
                this.$store.commit('setUsersSearch',{'usersSearch':value});
            }
        },
        computedUsersLoader:{
            get(){
                return this.$store.state.usersLoader;
            },
            set(value){
                this.$store.commit('setUsersLoader',{'usersLoader':value});
            }
        },
        computedUsers:{
            get(){
                return this.$store.state.users;
            },
            set(value){
                this.$store.commit('setUsers',{'users':value});
            }
        },
        computedHeaderText:{
            get(){
                return this.$store.state.headText;
            },
            set(value){
                this.$store.commit('setHeaderText',{'headerText':value});
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