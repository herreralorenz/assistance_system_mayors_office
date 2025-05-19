<template>
    <LoaderComponent v-if="this.computedApproveAssistanceTableLoader"></LoaderComponent>
    <div class="form-group approve-assistance py-2" v-if="!this.computedApproveAssistanceTableLoader ">
        <div class="container-fluid">
            <section class="searchbar">
                <div class="row">
                    <div class="col-2 d-flex flex-column ">
                        <span class="text-center text-secondary">Total:</span>
                        <span class="text-center text-success">{{ this.computedApproveOfAssistance.transaction_approve_count }}</span>
                    </div>
                    <div class="col-5" style="position:relative">
                        <i class="fa-solid fa-user fa-lg icon"></i>
                        <input ref="clientBeneficiary" id="clientBeneficiary"
                            :class="{ 'form-control': true, 'input-field': true }" type="text"
                            v-model="this.computedTransactionApproveSearch.client"
                            @keyup="this.searchClient()"
                            placeholder="Client" >
                    </div>
                    <div class="col-5" style="position:relative">
                        <i class="fa fa-file fa-lg icon"></i>
                        <input ref="clientBeneficiary" id="clientBeneficiary"
                             @keyup="this.searchTransactionID()"
                             v-model="this.computedTransactionApproveSearch.transaction_id"
                            :class="{ 'form-control': true, 'input-field': true }" type="text"
                            placeholder="Transaction ID">
                    </div>
                </div>
            </section>
            <section id="approve-clients" class="approve-clients">
                <LoaderSearchComponent v-if="this.computedSearchLoader"></LoaderSearchComponent>
                <div class="row">
                    <div v-for="(option,index) in this.computedApproveOfAssistance.transaction[paginator]" :key="index" class="col-12 my-2">
                        <div class="to-approve">
                            <div class="d-flex flex-column">
                                <span class="h3 fw-bold text-success">{{option.client.first_name+" "+(option.client.middle_name ? option.client.middle_name : "") +" "+option.client.last_name+" "+(option.client?.suffix?.suffix ? option.client?.suffix?.suffix : "" )}}</span>
                            </div>
                            <div class="vl ps-2 d-flex flex-column">
                                <span class="text-secondary">Birthdate: <span class="text-secondary fw-bold">{{ option.client.birthdate }}</span></span>
                                <span class="text-secondary">Address: <span class="text-secondary fw-bold">{{ option.client?.street ? option.client?.street+" "+option.client.barangay+" "+option.client.city+", "+option.client.province : option.client.barangay+" "+option.client.city+", "+option.client.province}}</span></span>
                            </div>
                            <hr>
                            <div class="d-flex flex-column">
                                <div class="row">
                                    <div class="col-6 d-flex flex-column">
                                        <span class="text-secondary">Transaction ID: <span class="text-secondary fw-bold">{{ option.transaction_id }}</span></span>
                                        <span class=" text-secondary">Beneficiary: <span class="text-secondary fw-bold">{{ option.beneficiary_transaction[0]?.suffix?.suffix  ? (option.beneficiary_transaction[0].first_name ?? "")+" "+(option.beneficiary_transaction[0]?.middle_name ?? "")+" "+(option.beneficiary_transaction[0]?.last_name ?? "")+" "+option.beneficiary_transaction[0]?.suffix?.suffix ?? "" : (option.beneficiary_transaction[0]?.first_name ?? '')+" "+(option.beneficiary_transaction[0]?.middle_name ?? "") +" "+(option.beneficiary_transaction[0]?.last_name ?? "")}}</span></span>
                                        <span class="text-secondary">Type of Assistance: <span class="text-secondary fw-bold">{{ option.agency.agency_abbreviation+" "+option.assistance_type.assistance_type+" ("+option.agency_program.agency_program_abbreviation+")"}}</span></span>
                                        <span class="text-secondary">Transaction Date: <span class="text-secondary fw-bold">{{ option.date_request }}</span></span>
                                        <span class="text-secondary">Status: <span :class="{'bg-secondary': option.transaction_approve.transaction_approve_status_condition[0].status == 'TO APPROVE','bg-success': option.transaction_approve.transaction_approve_status_condition[0].status == 'APPROVED','bg-danger': option.transaction_approve.transaction_approve_status_condition[0].status == 'DECLINED','fw-bold':true, 'text-white':true, 'rounded-3':true}">{{ option.transaction_approve.transaction_approve_status_condition[0].status}}</span></span>
                                    </div>
                                    <div class="col-6 d-flex align-items-end justify-content-center flex-column">
                                        <button type="button" class="btn btn-success bg-gradient w-50 h-75" @click="transaction(option.id)">VIEW</button>    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-12 my-2">
                        <div class="to-approve">
                            <div class="d-flex flex-column">
                            <span class="h3 fw-bold text-success">ANDREI LORENZ VILLADIEGO HERRERA</span>     
                            </div>
                            <div class="vl mt- ps-2 d-flex flex-column">
                                <span class="text-secondary">Birthdate: <span class="text-secondary fw-bold">10-28-1998</span></span>
                                <span class="text-secondary">Address: <span class="text-secondary fw-bold">368 SAN JUAN 1 GENERAL TRIAS, CAVITE</span></span>
                            </div>
                            <hr>
                            <div class="d-flex flex-column">
                                <div class="row">
                                    <div class="col-6 d-flex flex-column">
                                        <span class="text-secondary">Transaction ID: <span class="text-secondary fw-bold">DO-101</span></span>
                                        <span class=" text-secondary">Beneficiary: <span class="text-secondary fw-bold">BESSIE VILLADIEGO HERRERA</span></span>
                                        <span class="text-secondary">Type of Assistance: <span class="text-secondary fw-bold">FINANCIAL ASSISTANCE (DSWD)</span></span>
                                        <span class="text-secondary">Transaction Date: <span class="text-secondary fw-bold">01-01-1900</span></span>
                                        <span class="text-secondary">Status: <span class=" fw-bold bg-success bg-gradient text-white rounded-3">APPROVED</span></span>
                                    </div>
                                    <div class="col-6 d-flex align-items-end justify-content-center flex-column">
                                        <button type="button" class="btn btn-success bg-gradient w-50 h-75" @click="client()">VIEW</button>    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>               -->
                </div>
                <div class="card">
                    <Paginator @page="paginatorChange"  :rows="5" :totalRecords="this.computedApproveOfAssistance.transaction_approve_count"></Paginator>
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

.to-approve {
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    height: 100%;
    width: 100%;
    box-sizing: border-box;
    padding: 10px;
    transition: transform 0.3s ease;
}


.to-approve:hover {
    transform: scale(1.012);
}

.approve-assistance {
    /* margin: 0; */
    padding-left: 250px;
    overflow-y: hidden;
    
    height: -webkit-calc(100%);
    height: -moz-calc(100%);
    height: calc(100%);
    /* min-height: 100%;
    display: flex;
    flex-direction: column; */
}

.icon {
    padding: 10px;
    margin-top: 15px;
    min-width: 40px;
    position: absolute;
}

.approve-clients {
    padding: 10px;
    height: 85vh;
    overflow-x: hidden;
}

button {
    border-radius: 25px;
}

.input-field {
    padding-left: 35px;
    border-radius: 25px;
    height: 50px;
}

/* ::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    border-radius: 10px;
    background-color: #F5F5F5;
} */

/* ::-webkit-scrollbar {
    width: 12px;
    background-color: #ffffff;
}

::-webkit-scrollbar-thumb {
    border-radius: 10px;
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    background-color: #2F8D46;
} */
</style>
<script>
import LoaderComponent from '../Loader2.vue';
import LoaderSearchComponent from '../LoaderSearch.vue';
import Paginator from 'primevue/paginator';
import auth from '../../../router/auth_roles_permissions.js'


export default {
    components:{
        LoaderComponent,
        LoaderSearchComponent,
        Paginator
    },
    beforeMount() {
        this.computedApproveAssistanceTableLoader = true;
        // this.computedAuthCheckUserRolesPermissionsLoader = true;

        
        this.computedHeaderText = 'Approval of Assistance';

        this.fetchApproveAssistanceTable().then(response => {
            this.computedApproveAssistanceTableLoader = false;
            // this.computedAuthCheckUserRolesPermissionsLoader = false;
        });
    },
    mounted() {

        window.scrollTo({
            top: 0,
            behavior: 'instant' // Optional: adds smooth scrolling effect
        });

        
        document.body.style.overflow = "hidden";
    },
    beforeUnmount() {
        document.body.style.overflow = 'scroll';
        for(const [key,value] of Object.entries(this.computedTransactionApproveSearch)){
            this.computedTransactionApproveSearch[key] = '';
        }
    },
    methods: {
        paginatorChange(event){

            this.paginator = event.page;
            const approveClients = document.getElementById("approve-clients");

            approveClients.scrollTo({
            top: 0,
            behavior: 'instant' // Enables smooth scrolling
            });
        },
        searchClient(){
            this.computedTransactionApproveSearch.transaction_id = '',

            clearTimeout(this.timeout);

            this.timeout = setTimeout(() => {
                this.computedSearchLoader = true;
                this.$store.dispatch('searchClientApprove', { client_fullname: this.computedTransactionApproveSearch.client}).then(response => {
                    this.computedSearchLoader = false;
                    this.$store.commit('setApproveAssistanceTable',{'approveAssistanceTable':response.data});
                });

            }, 450);
        
        },
        searchTransactionID(){
            this.computedTransactionApproveSearch.client = '',

            clearTimeout(this.timeout);

            this.timeout = setTimeout(() => {
                this.computedSearchLoader = true;
                this.$store.dispatch('searchTransactionIDApprove', { transaction_id: this.computedTransactionApproveSearch.transaction_id}).then(response => {
                    this.computedSearchLoader = false;
                    this.$store.commit('setApproveAssistanceTable',{'approveAssistanceTable':response.data});
                });

            }, 450); 
        
        },
        transaction(transactionID) {
            this.$router.push({ name: 'approveClientAssistance', params:{id:transactionID}});
        },
        fetchApproveAssistanceTable(){
            return this.$store.dispatch('fetchApproveAssistanceTable');
        },
      
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
        computedSearchLoader:{
            get(){
                return this.$store.state.searchLoader;
            },
            set(value){
                this.$store.commit('setSearchLoader',{'searchLoader':value});
            }
        },
        computedTransactionApproveSearch:{
            get(){
                return this.$store.state.transactionApproveSearch;
            },
            set(value){
                this.$store.commit('setTransactionApproveSearch',{'transactionApproveSearch':value});
            }
        },
        computedApproveAssistanceTableLoader:{
            get(){
                return this.$store.state.approveAssistanceTableLoader
            },
            set(value){
                this.$store.commit('setApproveAssistanceTableLoader',{'approveAssistanceTableLoader':value});
            }
        },
        computedHeaderText: {
            get() {
                return this.$store.state.headerText;
            },
            set(value) {
                this.$store.commit('setHeaderText', { headerText: value });
            }
        },
        computedApproveOfAssistance:{
            get(){
                return this.$store.state.approveAssistanceTable;
            },
        },
    
    },
    data() {
        return {
            timeout: null,
            statusColor:'',
            paginator: 0,
        };
    },
    beforeRouteLeave(to, from, next) {
        next(true);
    },
    beforeRouteEnter(to, from, next) {
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