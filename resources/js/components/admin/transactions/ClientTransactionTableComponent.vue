<template>
    <Loader2 v-if="this.computedClientTransactionLoader"></Loader2>
    <Toast></Toast>
    <div v-if="!this.computedClientTransactionLoader" class="form-group client-transactions py-2" >
        <div class="container-fluid">
            <section class="searchbar">
                <div class="row">
                    <div class="col-2 d-flex flex-column ">
                        <span class="text-center text-secondary">Total:</span>
                        <span class="text-center text-success">{{ this.computedClientTransactionTable.transactionCount }}</span>
                    </div>
                    <div class="col-5">
                        <i class="fa-solid fa-user fa-lg icon"></i>
                        <input ref="client" v-model="this.computedTransactionSearch.client" @keyup="this.searchClient(this.computedTransactionSearch.client)"  id="client" :class="{'form-control':true , 'input-field':true}" type="text" placeholder="Client">
                    </div>
                    <div class="col-5">
                        <i class="fa fa-file fa-lg icon"></i>
                        <input ref="transaction_id" v-model="this.computedTransactionSearch.transaction_id" @keyup="this.searchTransactionID(this.computedTransactionSearch.transaction_id)"  id="transaction_id" :class="{'form-control':true , 'input-field':true}" type="text" placeholder="Transaction ID">
                    </div>
                </div>
            </section>
            <section class="view-transaction" id="client-transactions">
                <LoaderSearch v-if="this.computedSearchLoader"></LoaderSearch>
                <div class="row" v-if="!this.computedSearchLoader">
                        <div class="col-12 my-2" v-for="(option,index) in this.computedClientTransactionTable.transaction[paginator]">
                            <div class="to-view-transaction">
                                <div class="d-flex flex-column">
                                <span class="h3 fw-bold text-success">{{(option.client.first_name)+" "+(option.client?.middle_name ?? "")+" "+(option.client.last_name)+" "+(option.client?.suffix?.suffix ?? "")}}</span>     
                                </div>
                                <div class="vl mt- ps-2 d-flex flex-column">
                                    <span class="text-secondary">Birthdate: <span class="text-secondary fw-bold">{{ option.client.birthdate }}</span></span>
                                    <span class="text-secondary">Address: <span class="text-secondary fw-bold">{{ (option.client?.street ?? "")+" "+option.client.barangay+" "+option.client.city+" "+option.client.province }}</span></span>
                                </div>
                                <hr>
                                <div class="d-flex flex-column">
                                    <div class="row">
                                        <div class="col-6 d-flex flex-column">
                                            <span class="text-secondary">Transaction ID: <span class="text-secondary fw-bold">{{ option.transaction_id }}</span></span>
                                            <span class=" text-secondary">Beneficiary: <span class="text-secondary fw-bold">{{ (option.beneficiary_transaction[0]?.first_name ?? "")+" "+(option.beneficiary_transaction[0]?.middle_name ?? "")+" "+(option.beneficiary_transaction[0]?.last_name ?? "")+" "+(option.beneficiary_transaction[0]?.suffix?.suffix ?? "")}}</span></span>
                                            <span class="text-secondary">Type of Assistance: <span class="text-secondary fw-bold">{{ option.assistance_type.assistance_type+" ("+option.agency.agency_abbreviation+")" }}</span></span>
                                            <span class="text-secondary">Transaction Date: <span class="text-secondary fw-bold">{{ option.date_request }}</span></span>
                                            <span v-if="option.transaction_approve.transaction_approve_status_condition[0].status === 'APPROVED' && option.transaction_approve.transaction_claim.transaction_claim_status_condition[0].status === 'TO CLAIM'" class="text-secondary">Status: <span class=" fw-bold bg-secondary bg-gradient text-white rounded-3">{{ option.transaction_approve.transaction_claim.transaction_claim_status_condition[0].status ? option.transaction_approve.transaction_claim.transaction_claim_status_condition[0].status : option.transaction_approve.transaction_approve_status_condition[0].status}}</span></span>
                                            <span v-else-if="option.transaction_approve.transaction_approve_status_condition[0].status === 'APPROVED' && option.transaction_approve.transaction_claim.transaction_claim_status_condition[0].status === 'CLAIMED'" class="text-success">Status: <span class=" fw-bold bg-success bg-gradient text-white rounded-3">{{ option.transaction_approve.transaction_claim.transaction_claim_status_condition[0].status ? option.transaction_approve.transaction_claim.transaction_claim_status_condition[0].status : option.transaction_approve.transaction_approve_status_condition[0].status }}</span></span>
                                            <span v-else-if="option.transaction_approve.transaction_approve_status_condition[0].status === 'APPROVED' && option.transaction_approve.transaction_claim.transaction_claim_status_condition[0].status === 'UNCLAIMED'" class="text-success">Status: <span class=" fw-bold bg-danger bg-gradient text-white rounded-3">{{ option.transaction_approve.transaction_claim.transaction_claim_status_condition[0].status ? option.transaction_approve.transaction_claim.transaction_claim_status_condition[0].status : option.transaction_approve.transaction_approve_status_condition[0].status }}</span></span>
                                            <span v-else-if="option.transaction_approve.transaction_approve_status_condition[0].status === 'TO APPROVE'" class="text-secondary">Status: <span class=" fw-bold bg-secondary bg-gradient text-white rounded-3">{{ option.transaction_approve.transaction_approve_status_condition[0].status }}</span></span>
                                            <span v-else-if="option.transaction_approve.transaction_approve_status_condition[0].status === 'APPROVED'" class="text-secondary">Status: <span class=" fw-bold bg-success bg-gradient text-white rounded-3">{{ option.transaction_approve.transaction_approve_status_condition[0].status }}</span></span>
                                            <span v-else-if="option.transaction_approve.transaction_approve_status_condition[0].status === 'DECLINED'" class="text-secondary">Status: <span class=" fw-bold bg-danger bg-gradient text-white rounded-3">{{ option.transaction_approve.transaction_approve_status_condition[0].status }}</span></span>
                                        </div>
                                        <div class="col-6 d-flex align-items-end justify-content-center flex-column">
                                            <button type="button" class="btn btn-success bg-gradient w-50 h-75" @click="client(option.id)">VIEW</button>    
                                        </div>
                                    </div>
                                </div>
                            </div>  
                        </div>  
                        <div class="card">
                                <Paginator @page="paginatorChange"  :rows="5" :totalRecords="this.computedClientTransactionTable.transactionCount"></Paginator>
                        </div>                
                </div>
            </section>
        </div>
    </div>
    </template>
<style scoped>

button{
    border-radius: 25px;
}

.client-transactions{
    padding-left: 250px;
    overflow-y: hidden;
    height: -webkit-calc(100%);
    height: -moz-calc(100%);
    height: calc(100%);

}

.vl {
  border-left: 5px solid #E8E8E8;
  height: 45px;
}

.to-view-transaction{
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    height: 100%;
    width: 100%;
    box-sizing: border-box;
    padding:10px;
    transition: transform 0.3s ease; 
}


.to-view-transaction:hover {
  transform: scale(1.012);
}


.icon {
     padding: 10px;
     margin-top: 15px;
     min-width: 40px;
     position: absolute;
}

.view-transaction{
    padding: 10px;
    height: 85vh;
    overflow-x: hidden;
    overflow-y: auto;
}


.input-field{
    padding-left: 35px;
    border-radius: 25px;
    height: 50px;
}
</style>
<script>
import Loader2 from '../Loader2.vue';
import Paginator from 'primevue/paginator';
import LoaderSearch from '../LoaderSearch.vue'
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import auth from '../../../router/auth_roles_permissions.js';


export default{
    components:{
        Loader2,
        Paginator,
        LoaderSearch,
        Toast
    },
    beforeMount(){

        this.toast = useToast();

        this.computedHeaderText = 'Client Transactions';
        
        this.computedClientTransactionLoader = true;
        this.$store.dispatch('fetchClientTransactionTable').then(response =>{
            this.computedClientTransactionLoader = false;
            console.log(this.computedClientTransactionTable);

        });
    },
    mounted(){

        if(this.computedTransactionToast){
            this.toast.add({ severity: 'success', summary: 'Success', detail: 'Transaction successfully deleted.', life: 5000 });
            this.computedTransactionToast = false;
        }

        window.scrollTo({
        top: 0,
        behavior: 'instant' // Optional: adds smooth scrolling effect
        });
        
    
    },
    beforeUnmount(){

    },
    methods:{
    searchTransactionID(){
        clearTimeout(this.timeout);
            this.computedTransactionSearch.client = '';
            this.timeout = setTimeout(() => {
                this.computedSearchLoader = true;
                this.$store.dispatch('searchTransactionIDTransaction', { transaction_id: this.computedTransactionSearch.transaction_id}).then(response => {
                    this.computedSearchLoader = false;
                    console.log(response);
                });

            }, 450);
    },
    searchClient(){
        clearTimeout(this.timeout);
            this.computedTransactionSearch.transaction_id = '';
            this.timeout = setTimeout(() => {
                this.computedSearchLoader = true;
                this.$store.dispatch('searchClientTransaction', { client_fullname: this.computedTransactionSearch.client}).then(response => {
                    this.computedSearchLoader = false;
                    console.log(response);
                });

            }, 450);
    },
    paginatorChange(event){

            this.paginator = event.page;
            const transaction = document.getElementById("client-transactions");

            transaction.scrollTo({
            top: 0,
            behavior: 'instant' // Enables smooth scrolling
            });
    },
    client(transactionID){
        this.$router.push({name:'clientTransaction',params:{id:transactionID}});
    }
    },
    computed:{
        computedTransactionToast:{
            get(){
                return this.$store.state.transactionToast;
            },
            set(value){
                this.$store.commit('setTransactionToast',{'transactionToast':value});
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
        computedTransactionSearch:{
            get(){
                return this.$store.state.transactionSearch;
            },
            set(value){
                this.$store.commit('setTransactionSearch', {'transactionSearch':value})
            }
        },
        computedClientTransactionTable:{
            get(){
                return this.$store.state.clientTransactionTable;
            },
            set(value){
                this.$store.commit('setClientTransactionTable',{'clientTransactionTable':value});
            }
        },
        computedClientTransactionLoader:{
            get(){
                return this.$store.state.clientTransactionTableLoader;
            },
            set(value){
                this.$store.commit('setClientTransactionTableLoader',{'clientTransactionTableLoader':value});
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
        return{
            toast:false,
            paginator:0,
        }
    },
    beforeRouteLeave(to,from,next){
        next(true);
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