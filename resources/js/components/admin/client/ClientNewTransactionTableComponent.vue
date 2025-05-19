<template>
    <Loader2 v-if="this.computedClientNewTransactionLoader"></Loader2>
    <Toast/>
    <div id="client-new-transaction-table py-2" v-if="!this.computedClientNewTransactionLoader" class="form-group client-new-transaction-table py-4">
        <div class="container-fluid">
            <section class="searchbar">
                <div class="row">
                    <div class="col-1 d-flex flex-column ">
                        <span class="text-center text-secondary">Total:</span>
                        <span class="text-center text-success">{{ this.computedClientNewTransactionTable.clientCount }}</span>
                    </div>
                    <div class="col-11" style="position:relative">
                        <i class="fa-solid fa-user fa-lg icon"></i>
                        <input v-model="this.computedClientSearch" ref="client" @keyup="this.clientSearch(this.computedClientSearch)"  id="clientBeneficiary" :class="{'form-control':true , 'input-field':true}" type="text" placeholder="Client">
                    </div>
                </div>
            </section>
            <section class="clients-new-transaction">
                <LoaderSearch v-if="this.computedSearchLoader"></LoaderSearch>
                <div v-if="!this.computedSearchLoader" class="row" v-for="(option,index) in this.computedClientNewTransactionTable.client[paginator]">
                        <div class="col-12 my-2">
                            <div class="new-transaction">
                                <div class="d-flex flex-column">
                                <span class="h3 fw-bold text-success">{{option.full_name}}</span>     
                                </div>
                                <div class="vl mt- ps-2 d-flex flex-column">
                                    <span class="text-secondary">Birthdate: <span class="text-secondary fw-bold">{{ option.birthdate }}</span></span>
                                    <span class="text-secondary">Address: <span class="text-secondary fw-bold">{{ (option?.street ?? "")+" "+option.barangay+" "+option.city+" "+option.province }}</span></span>
                                </div>
                                <hr>
                                <div class="d-flex flex-column">
                                    <div class="row">
                                        <div class="col-6 d-flex flex-column">
                                            <span class="text-secondary">Transactions made: <span class="text-secondary fw-bold">{{ option.transaction_count }}</span></span>
                                            <span class=" text-secondary">Last transaction: <span class="text-secondary fw-bold">{{ option.transaction[0]?.date_request }}</span></span>
                                        </div>
                                        <div class="col-6 d-flex align-items-end justify-content-center flex-column">
                                            <button type="button" class="btn btn-success bg-gradient w-50 h-75" @click="client(option.id)">NEW TRANSACTION</button>    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                   
                </div>
                <div class="card">
                    <Paginator  @page="this.paginatorChange()" :rows="5" :totalRecords="this.computedClientNewTransactionTable.clientCount"></Paginator>
                </div>
            </section>
        </div>  
    </div>
</template>
<style scoped>

    
.vl {
      border-left: 5px solid #E8E8E8;
      height: 45px;
    }

.client-new-transaction-table{
    margin: 0;
    padding-left: 250px;
    overflow-y: scroll;
    height: -webkit-calc(100%);
    height: -moz-calc(100%);
    height: calc(100%);
    /* min-height: 100%;
    display: flex;
    flex-direction: column;    */
}

.icon {
     padding: 10px;
     margin-top: 15px;
     min-width: 40px;
     position: absolute;
}

.input-field{
    padding-left: 35px;
    border-radius: 25px;
    height: 50px;
}

.clients-new-transaction{
    padding: 10px;
    height: 85vh;
    overflow-x: hidden;
    overflow-y: auto;
}

button{
    border-radius: 25px;
}

.new-transaction{
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    height: 100%;
    width: 100%;
    box-sizing: border-box;
    padding:10px;
    transition: transform 0.3s ease; 
}

.new-transaction:hover {
    transform: scale(1.012);
}

</style>
<script>
import Loader2 from '../Loader2.vue';
import LoaderSearch from '../LoaderSearch.vue'
import Paginator from 'primevue/paginator';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import auth from '../../../router/auth_roles_permissions.js';

export default{

    components:{
        Loader2,
        Paginator,
        LoaderSearch,
        Toast,
    },
    beforeMount(){
        
        this.toast = useToast();

        this.computedHeaderText = 'Client New Transaction';
        this.computedClientNewTransactionLoader = true;
        this.$store.dispatch('fetchClientNewTransactionTable').then(response => {
            this.computedClientNewTransactionLoader = false;
            console.log(this.computedClientNewTransactionTable);
        });
    },
    mounted(){



        if(this.computedClientToast){
            this.toast.add({ severity: 'success', summary: 'Success', detail: 'Transaction successfully added.', life: 5000 });
        }

        window.scrollTo({
        top: 0,
        behavior: 'instant' // Optional: adds smooth scrolling effect
        });
        
    },
    beforeUnmount(){
        document.body.style.overflow = 'scroll';
    },
    methods:{
        
        clientSearch(clientSearch){
            clearTimeout(this.timeout);

            this.timeout = setTimeout(() => {
                this.computedSearchLoader = true;
                console.log(clientSearch);
                this.$store.dispatch('searchClient', { client_fullname: clientSearch}).then(response => {
                    this.computedSearchLoader = false;
                    this.$store.commit('setClientNewTransactionTable',{'clientNewTransactionTable':response.data});
                });

            }, 450);
        },
        client(clientID){
            this.$router.push({name:'clientNewTransactionBeneficiary',params:{'id':clientID} });
        },
        paginatorChange(event){
            this.paginator = event.page;
            const approveClients = document.getElementById("client-new-transaction-table");

            approveClients.scrollTo({
            top: 0,
            behavior: 'instant' // Enables smooth scrolling
            });
        },
    },
    computed:{
        computedClientToast:{
            get(){
                return this.$store.state.clientToast;
            },
            set(value){
                this.$store.commit('setClientToast',{'clientToast':value});
            }
        },
        computedClientSearch:{
            get(){
                return this.$store.state.clientSearch;
            },
            set(value){
                this.$store.commit('setClientSearch',{'clientSearch':value});
            }
        },
        computedSearchLoader:{
            get(){
                return this.$store.state.searchLoader;
            },
            set(value){
                this.$store.commit('setSearchLoader',{'searchLoader':value});
            }
        },
        computedClientNewTransactionTable:{
            get(){
                return this.$store.state.clientNewTransactionTable;
            },
            set(value){
                this.$store.commit('setClientNewTransactionTable',{'clientNewTransactionTable':value});
            }
        },
        computedClientNewTransactionLoader:{
            get(){
                return this.$store.state.clientNewTransactionLoader;
            },
            set(value){
                this.$store.commit('setclientNewTransactionLoader',{'clientNewTransactionLoader':value});
            }
        },
        computedHeaderText:{
            get(){
                return this.$store.state.headerText;
            },
            set(value){
                this.$store.commit('setHeaderText',{headerText:value});
            }
        }
    },
    data(){
        return {
            data:false,
            timeout:null,
            paginator:0,
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
