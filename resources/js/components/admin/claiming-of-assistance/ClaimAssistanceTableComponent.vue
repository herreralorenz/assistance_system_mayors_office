<template>
    <LoaderComponent v-if="this.computedClaimAssistanceTableLoader"></LoaderComponent>
    <div id="claim-assistance" class="form-group claim-assistance py-2" v-if="!this.computedClaimAssistanceTableLoader">
        <div class="container-fluid">
            <section class="searchbar">
                <div class="row">
                    <div class="col-2 d-flex flex-column ">
                        <span class="text-center text-secondary">Total:</span>
                        <span class="text-center text-success">{{this.computedClaimAssistanceTable.transaction_claim_count}}</span>
                    </div>
                    <div class="col-5" style="position:relative">
                        <i class="fa-solid fa-user fa-lg icon"></i>
                        <input ref="client" @keyup="this.searchClaimClient()" v-model="this.computedTransactionClaimSearch.client" id="client" :class="{'form-control':true , 'input-field':true}" type="text" placeholder="Client">
                    </div>
                    <div class="col-5" style="position:relative">
                        <i class="fa fa-file fa-lg icon"></i>
                        <input ref="transactionID" @keyup="this.searchClaimTransactionID()"  id="transactionID" v-model="this.computedTransactionClaimSearch.transaction_id" :class="{'form-control':true , 'input-field':true}" type="text" placeholder="Transaction ID">
                    </div>
                </div>
            </section>

            <section class="claim-clients" >
                <LoaderSearchComponent v-if="this.computedSearchLoader"></LoaderSearchComponent>
                <div class="row" v-if="!this.computedSearchLoader">
                        <div class="col-12 my-2" v-for="option in this.computedClaimAssistanceTable.transaction_claim[this.paginator]">
                            <div class="to-claim">
                                <div class="d-flex flex-column">
                                <span class="h3 fw-bold text-success">{{ option.approve_transaction.transaction.client.first_name+" "+( option.approve_transaction.transaction.client.middle_name ?  option.approve_transaction.transaction.client.middle_name : "")+" "+option.approve_transaction.transaction.client.last_name+" "+(option.approve_transaction.transaction.client?.suffix?.suffix ? option.approve_transaction.transaction.client?.suffix?.suffix : "") }}</span>     
                                </div>
                                <div class="vl mt- ps-2 d-flex flex-column">
                                    <span class="text-secondary">Birthdate: <span class="text-secondary fw-bold">{{option.approve_transaction.transaction.client.birthdate}}</span></span>
                                    <span class="text-secondary">Address: <span class="text-secondary fw-bold">{{ (option.approve_transaction.transaction.client?.street ? option.approve_transaction.transaction.client?.street : "")+" "+option.approve_transaction.transaction.client.barangay+" "+option.approve_transaction.transaction.client.city+" "+option.approve_transaction.transaction.client.province }}</span></span>
                                </div>
                                <hr>
                                <div class="d-flex flex-column">
                                    <div class="row">
                                        <div class="col-6 d-flex flex-column">
                                            <span class="text-secondary">Transaction ID: <span class="text-secondary fw-bold">{{option.approve_transaction.transaction.transaction_id}}</span></span>
                                            <span class=" text-secondary">Beneficiary: <span class="text-secondary fw-bold">{{(option.approve_transaction.transaction.beneficiary_transaction[0]?.first_name ?? "")+" "+(option.approve_transaction.transaction.beneficiary_transaction[0]?.middle_name ? option.approve_transaction.transaction.beneficiary_transaction[0]?.middle_name : "")+" "+(option.approve_transaction.transaction.beneficiary_transaction[0]?.last_name ?? "")+" "+(option.approve_transaction.transaction.beneficiary_transaction[0]?.suffix?.suffix ? option.approve_transaction.transaction.beneficiary_transaction[0].suffix.suffix : "")}}</span></span>
                                            <span class="text-secondary">Type of Assistance: <span class="text-secondary fw-bold">{{" "+option.approve_transaction.transaction.agency.agency_abbreviation+" "+option.approve_transaction.transaction.assistance_type.assistance_type+" ("+option.approve_transaction.transaction.agency_program.agency_program_abbreviation+")"}}</span></span>
                                            <span class="text-secondary">Transaction Date: <span class="text-secondary fw-bold">{{ option.approve_transaction.transaction.date_request }}</span></span>
                                            <span class="text-secondary">Status: <span :class="{'fw-bold bg-secondary bg-gradient text-white rounded-3' : option.transaction_claim_status_condition[0].status === 'TO CLAIM','fw-bold bg-danger bg-gradient text-white rounded-3' : option.transaction_claim_status_condition[0].status === 'UNCLAIMED','fw-bold bg-success bg-gradient text-white rounded-3' : option.transaction_claim_status_condition[0].status === 'CLAIMED'}">{{option.transaction_claim_status_condition[0].status}}</span></span>
                                        </div>
                                        <div class="col-6 d-flex align-items-end justify-content-center flex-column">
                                            <button type="button" class="btn btn-success bg-gradient w-50 h-75" @click="client(option.approve_transaction.transaction.id)">VIEW</button>    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>              
                        <div class="card">
                            <Paginator @page="paginatorChange"  :rows="5" :totalRecords="this.computedClaimAssistanceTable.transaction_claim_count"></Paginator>
                        </div>
                </div>
            </section>       
        </div>  
    </div>

    </template>
<style scoped>

.claim-assistance{
    
    padding-left: 250px;
    overflow-y: hidden;
    height: -webkit-calc(100%);
    height: -moz-calc(100%);
    height: calc(100%);
    /* min-height: 100%;
    display: flex;
    flex-direction: column;    */
}

.vl {
  border-left: 5px solid #E8E8E8;
  height: 45px;
}

.to-claim{
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    height: 100%;
    width: 100%;
    box-sizing: border-box;
    padding:10px;
    transition: transform 0.3s ease; 
}


.to-claim:hover {
  transform: scale(1.012);
}


.icon {
     padding: 10px;
     margin-top: 15px;
     min-width: 40px;
     position: absolute;
}

.claim-clients{
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

button{
    border-radius: 25px;
}
</style>
<script>

import LoaderComponent from '../Loader2.vue';
import LoaderSearchComponent from '../LoaderSearch.vue';
import Paginator from 'primevue/paginator';
import auth from '../../../router/auth_roles_permissions.js'


export default{
    components:{
        LoaderComponent,
        LoaderSearchComponent,
        Paginator,
    },
    beforeMount(){
        
        this.computedHeaderText = 'Claiming of Assistance';

        this.computedClaimAssistanceTableLoader = true;
        this.$store.dispatch('fetchTransactionClaimTable').then(response => {
            this.computedClaimAssistanceTableLoader = false;
        });
    },
    mounted(){

        

        window.scrollTo({
        top: 0,
        behavior: 'instant' // Optional: adds smooth scrolling effect
        });
      
    },
    beforeUnmount(){
        document.body.style.overflow = 'scroll';
        for(const [key,value] of Object.entries(this.computedTransactionClaimSearch)){
            this.computedTransactionClaimSearch[key] = '';
        }
    },
    methods:{
        paginatorChange(event){
            this.paginator = event.page;
            const claimAssistance = document.getElementById("claim-assistance");

            claimAssistance.scrollTo({
            top: 0,
            behavior: 'instant' // Enables smooth scrolling
            });
        },
        searchClaimClient(){
            this.computedTransactionClaimSearch.transaction_id = '';
            
            clearTimeout(this.timeout);

            this.timeout = setTimeout(() => {
                this.computedSearchLoader = true;
                this.$store.dispatch('searchClientClaim', { client_fullname: this.computedTransactionClaimSearch.client}).then(response => {
                    this.computedSearchLoader = false;
                    this.$store.commit('setClaimAssistanceTable',{'claimAssistanceTable':response.data});
                });

            }, 450);
        },
        searchClaimTransactionID(){
            this.computedTransactionClaimSearch.client = '';

            clearTimeout(this.timeout);

            this.timeout = setTimeout(() => {
                this.computedSearchLoader = true;
                this.$store.dispatch('searchTransactionIDClaim', { transaction_id: this.computedTransactionClaimSearch.transaction_id}).then(response => {
                    this.computedSearchLoader = false;
                    this.$store.commit('setClaimAssistanceTable',{'claimAssistanceTable':response.data});
                });

            }, 450);
        },
        client(transactionID){
            this.$router.push({name:'claimClientAssistance', params:{id:transactionID}});
        },
    },
    computed:{
        computedClaimAssistanceTableLoader:{
            get(){
                return this.$store.state.claimAssistanceTableLoader
            },
            set(value){
                this.$store.commit('setClaimAssistanceTableLoader',{'claimAssistanceTableLoader':value});
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
        computedTransactionClaimSearch:{
            get(){
                return this.$store.state.transactionClaimSearch;
            },
            set(value){
            
                this.$store.commit('setTransactionClaimSearch',{transactionClaimSearch:value});
            }
        },
        computedClaimAssistanceTable:{
            get(){
                return this.$store.state.claimAssistanceTable;
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
    },
    data(){
        return {
            timeout:null,
            paginator:0,
        };
    }

}
</script>