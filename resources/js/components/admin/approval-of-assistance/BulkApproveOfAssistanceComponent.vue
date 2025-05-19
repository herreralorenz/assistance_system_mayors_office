<template>
    <Loader2 v-if="this.computedBulkApproveAssistanceTableLoader"></Loader2>
    <Toast/>
    <div v-if="!this.computedBulkApproveAssistanceTableLoader" class="form-group bulk-approve py-4">
        <div class="container-fluid">
        <section class="transactionToApprove">
            <h2 class="fw-bold">Select Transaction to Approve</h2>
            <section class="searchbar">
                <div class="row">
                   <div class="col-12 position-relative">
                    <i class="fa fa-solid fa-magnifying-glass fa-lg icon"></i>
                        <select id="searchBy" :class="{'form-select':true, 'input-field':true}" @change="this.searchByClear()" v-model="this.selectedSearchBy">
                            <option selected disabled value="">Search by...</option>
                            <option v-for="option in this.searchBy" :key="option.value" :value="option.value">
                                {{ option.text }}
                            </option>
                        </select>                
                   </div> 
                </div>
                <div class="row mt-4 d-flex align-items-center justify-content-center">
                    <div class="col-12 position-relative" v-if="this.selectedSearchBy === '1'">
                        <i class="fa-solid fa-user fa-lg icon"></i>
                        <input ref="client" v-model="this.computedTransactionApproveSearch.client" @keyup="this.searchClient"  id="client" :class="{'form-control':true , 'input-field':true}" type="text" placeholder="Client">
                    </div>
                    <div class="col-12 position-relative" v-if="this.selectedSearchBy === '2'">
                        <i class="fa fa-file fa-lg icon"></i>
                        <input ref="transactionID"  id="transactionID" :class="{'form-control':true , 'input-field':true}" @keyup="this.searchTransactionID" v-model="this.computedTransactionApproveSearch.transactionID" type="text" placeholder="Transaction ID">
                    </div> 
                    <div class="col-6 position-relative" v-if="this.selectedSearchBy === '3'">
                        <label for="from" class="icon-date">From</label>
                        <input type="date" v-model="this.computedTransactionApproveSearch.date_from" @change="this.searchDateRequest" class="form-control" id="from" :class="{'form-control':true, 'input-field-date':true}" >
                    </div>
                    <div class="col-6 position-relative" v-if="this.selectedSearchBy === '3'">
                        <label for="to" class="icon-date">To</label>
                        <input type="date" v-model="this.computedTransactionApproveSearch.date_to"  class="form-control" id="to" @change="this.searchDateRequest()" :class="{'form-control':true, 'input-field-date':true}" >
                    </div>
                </div>
            </section>
            <section class="table mt-3">
                <LoaderSearch v-if="this.computedSearchLoader"></LoaderSearch>
                <DataTable v-if="!this.computedSearchLoader" class="dataTable" filterDisplay="menu" paginator :rows="5" v-model:selection="selectedTransactionsToApprove" :value="this.computedBulkApproveAssistanceTable['transaction']" dataKey="id" tableStyle="min-width: 50rem">
                    <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                    <Column sortable field="transaction_id" header="Transaction ID"></Column>
                    <Column sortable field="beneficiary_transaction.0.full_name" header="Beneficiary Full Name"></Column>
                    <Column sortable field="client.full_name" header="Client Full Name"></Column>
                    <Column sortable field="client.birthdate" header="Client Birthdate"></Column>
                    <Column sortable field="client.barangay" header="Client Barangay"></Column>
                    <Column sortable field="date_request" header="Transaction Date"></Column>
                    <Column sortable field="assistance_type.assistance_type" header="Type of Assistance"></Column>
                    <Column sortable field="transaction_approve.transaction_approve_status_condition.0.status" header="Status">
                        <template #body="slotProps">
                            <div :style="{ 'font-size': '0.75rem', 'width':'120px', 'border-radius':'10px'}"  :class="{'bg-secondary': slotProps.data.transaction_approve.transaction_approve_status_condition[0].status === 'TO APPROVE' ? true : false,'bg-danger': slotProps.data.transaction_approve.transaction_approve_status_condition[0].status === 'DECLINED' ? true : false,'bg-success': slotProps.data.transaction_approve.transaction_approve_status_condition[0].status === 'APPROVED' ? true : false, 'text-white': true, 'text-center':true }">{{  slotProps.data.transaction_approve.transaction_approve_status_condition[0].status }}</div>
                        </template>
                    </Column>
                </DataTable>
            </section>
            <button type="button" class="btn btn-primary w-100" @click="this.add()" rounded>ADD</button>
        </section>
        <section class="approve mt-3">
            <h2 class="fw-bold">Review Transactions to Approve</h2>
            <section class="table mt-3">
                <DataTable class="dataTable" filterDisplay="menu" paginator :rows="5" v-model:selection="selectedReviewTransactionsToApprove" :value="this.reviewTransactionsToApprove" dataKey="id" tableStyle="min-width: 50rem">
                     <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                    <Column sortable field="transaction_id" header="Transaction ID"></Column>
                    <Column sortable field="beneficiary_transaction.0.full_name" header="Beneficiary Full Name"></Column>
                    <Column sortable field="client.full_name" header="Client Full Name"></Column>
                    <Column sortable field="client.birthdate" header="Client Birthdate"></Column>
                    <Column sortable field="client.barangay" header="Client Barangay"></Column>
                    <Column sortable field="date_request" header="Transaction Date"></Column>
                    <Column sortable field="assistance_type.assistance_type" header="Type of Assistance"></Column>
                    <Column sortable field="transaction_approve.transaction_approve_status_condition.0.transaction_approve_status.status" header="Status">
                        <template #body="slotProps">
                            <div :style="{ 'font-size': '0.75rem', 'width':'120px', 'border-radius':'10px'}"  :class="{'bg-secondary': slotProps.data.transaction_approve.transaction_approve_status_condition[0].status === 'TO APPROVE' ? true : false,'bg-danger': slotProps.data.transaction_approve.transaction_approve_status_condition[0].status === 'DECLINED' ? true : false,'bg-success': slotProps.data.transaction_approve.transaction_approve_status_condition[0].status === 'APPROVED' ? true : false, 'text-white': true, 'text-center':true }">{{  slotProps.data.transaction_approve.transaction_approve_status_condition[0].status }}</div>
                        </template>
                    </Column>
                    <Column sortable field="id" header="Action">
                        <template #body="slotProps">
                            <Button :style="{ 'font-size': '0.75rem' }" type="button" icon="pi pi-eraser" severity="danger" v-model="slotProps.data.id" label="Delete"  @click="deleteTransaction(slotProps.data.id)" />
                        </template>
                    </Column>
                </DataTable>
                <Button class="mt-3" :style="{ 'font-size': '0.75rem', 'width':'120px' }" type="button" icon="pi pi-eraser" severity="danger" label="Clear Table"  @click="clearTable()" />
            </section>
            <button data-bs-toggle="modal" data-bs-target="#bulkApproveModal" type="button" class="btn btn-success w-100">APPROVE</button>

        </section>
        </div> 
    </div>

<!-- Approve Modal -->
<div class="modal fade" id="bulkApproveModal" ref="bulkApproveModal" tabindex="-1" aria-labelledby="bulkApproveModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkApproveModalTitle">Are you sure to approve this transaction?</h5>
                <button type="button" id="btn-close-approve" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="container py-4">
                    <div>
                        <i class="fa-solid fa-calendar fa-lg icon"></i>
                        <input id="approveDate" :class="{ 'form-control': true, 'input-field': true, 'is-invalid':this.proceedValidation.dateApprove }"  v-model="this.computedApproveDeclineClient.date_approve_decline" type="date" placeholder="Approve Date">
                    </div>
                    <div class="mt-3">
                        <i class="fa-solid fa-money-bill fa-lg icon"></i>
                        <input  id="bulkAmount" :class="{ 'form-control': true, 'input-field': true,'is-invalid':this.proceedValidation.amount }" v-model="this.computedApproveDeclineClient.amount" type="text" placeholder="Amount">
                    </div>
                </div>
            <div class="modal-footer">
                <button type="button" @click="this.approve()" class="btn btn-success">Submit</button>
            </div>
            </div>
        </div>
</div>   
</template>
<style scoped>

button{
    border-radius:25px;
}

.transaction-to-approve, .approve{
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5); 
}


.bulk-approve{
    /* margin: 0; */
    padding-left: 250px;
    overflow-y: scroll;
            
    height: -webkit-calc(100% - 50px);
    height: -moz-calc(100% - 50px);
    height: calc(100% - 50px);
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



.input-field-date{
    border-radius: 25px;
    height: 50px;
}



.input-field{
    padding-left: 35px;
    border-radius: 25px;
    height: 50px;
}

.table{
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    height: 100%;
    width: 100%;
    box-sizing: border-box;
    padding:10px;
    transition: transform 0.3s ease; 
}

.transactionToApprove{
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
import LoaderSearch from '../LoaderSearch.vue';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import auth from '../../../router/auth_roles_permissions.js'

export default{
    components:{
        DataTable,
        Column,
        Button,
        Loader2,
        LoaderSearch,
        Toast,
    },
    beforeMount(){
        this.toast = useToast();
        this.computedHeaderText = 'Bulk Approval of Assistance'
        this.computedAuthCheckUserRolesPermissionsLoader = true;
        this.computedBulkApproveAssistanceTableLoader = true;
        this.fetchBulkApproveAssistanceTable().then(response => {
          this.computedAuthCheckUserRolesPermissionsLoader = false;
          this.computedBulkApproveAssistanceTableLoader = false;
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
        for(const [key, value] of Object.entries(this.computedTransactionApproveSearch)){
            this.computedTransactionApproveSearch[key] = '';
        }
    },
    methods:{
        deleteTransaction(id){
            this.reviewTransactionsMap.delete(id);
        },
        approve(){
            if(this.computedApproveDeclineClient.date_approve_decline === '' || this.computedApproveDeclineClient.date_approve_decline === null){
                this.proceedValidation.dateApprove = true;
            }else{
                this.proceedValidation.dateApprove = false;
            }

            if(this.computedApproveDeclineClient.amount === '' || this.computedApproveDeclineClient.amount === null){
                this.proceedValidation.amount = true;
            }else{
                this.proceedValidation.amount = false;
            }

            let proc = false;
            for (const [key, value] of Object.entries(this.proceedValidation)) {
                if(this.proceedValidation[key] === true){
                    proc = true;
                }
            }

            if(!proc){
                if(this.selectedReviewTransactionsToApprove.length != 0){
                    document.getElementById('btn-close-approve').click();
                    this.computedBulkApproveAssistanceTableLoader = true;
                    this.$store.dispatch('bulkApprove',{amount:this.computedApproveDeclineClient.amount, toApprove: this.selectedReviewTransactionsToApprove, date_approve_decline:this.computedApproveDeclineClient.date_approve_decline}).then(response => {
                        this.fetchBulkApproveAssistanceTable().then(response => {
                            this.computedBulkApproveAssistanceTableLoader = false;
                            for (const [key, value] of Object.entries(this.computedApproveDeclineClient)) {
                                if(key != 'date_approve_decline'){
                                    this.computedApproveDeclineClient[key] = '';
                                }  
                            }
                            this.selectedTransactionsToApprove = [];
                            this.selectedReviewTransactionsToApprove = [];
                            this.reviewTransactionsMap = new Map();

                            this.computedApproveTransactionToast = true;
                            if(this.computedApproveTransactionToast){
                                this.toast.add({ severity: 'success', summary: 'Success', detail: 'Transaction/s successfully approved.', life: 5000 });
                                this.computedApproveTransactionToast = false
                            }
                        });
                    });
               }else{
                alert('Please select transaction to approve.');
               }
                
            }
           
        },
        searchByClear(){
            for (const [key, value] of Object.entries(this.computedTransactionApproveSearch)) {
                
                this.computedTransactionApproveSearch[key] = '';
            }

        },
        searchTransactionID(){
            clearTimeout(this.timeout);

            this.timeout = setTimeout(() => {
                this.computedSearchLoader = true;
                this.$store.dispatch('searchBulkTransactionApproveTransactionID', { transactionID: this.computedTransactionApproveSearch.transactionID}).then(response => {
                    this.computedSearchLoader = false;
                    this.$store.commit('setBulkApproveAssistanceTable',{'bulkApproveAssistanceTable':response.data});
                });

            }, 450); 
        
      },
        searchClient(){
            clearTimeout(this.timeout);

            this.timeout = setTimeout(() => {
                this.computedSearchLoader = true;
                this.$store.dispatch('searchBulkTransactionApproveClient', { client: this.computedTransactionApproveSearch.client}).then(response => {
                    this.computedSearchLoader = false;
                    this.$store.commit('setBulkApproveAssistanceTable',{'bulkApproveAssistanceTable':response.data});
                });

            }, 450); 
        
      },
      searchDateRequest(){

            if(this.computedTransactionApproveSearch.date_from != '' && this.computedTransactionApproveSearch.date_to != ''){
                clearTimeout(this.timeout);

                this.timeout = setTimeout(() => {
                    this.computedSearchLoader = true;
                    this.$store.dispatch('searchBulkTransactionApproveDateRequest', { dateFrom: this.computedTransactionApproveSearch.date_from, dateTo: this.computedTransactionApproveSearch.date_to}).then(response => {
                        this.computedSearchLoader = false;
                        this.$store.commit('setBulkApproveAssistanceTable',{'bulkApproveAssistanceTable':response.data});
                    });

                }, 450); 
            }
           
      },  
      add(){
        // this.reviewTransactionsMap = new Map(this.selectedTransactionsToApprove.map(item => [item.id,item]));
        this.selectedTransactionsToApprove.forEach((val,index) =>{
            this.reviewTransactionsMap.set(val.id,val);
        });
      },
      deleteRow(data){

        this.reviewTransactionsMap.delete(data.id);
      },
      clearTable(){
        this.reviewTransactionsMap.clear();
      },
      fetchBulkApproveAssistanceTable(){
            return this.$store.dispatch('fetchBulkApproveAssistanceTable');
      },
    },
    data(){
        return{
            toast:false,
            paginator:0,
            timeout:null,
            selectedSearchBy:'1',
            searchBy:[
                { value: '1', text: 'Client' },
                { value: '2', text: 'Transaction ID' },
                { value: '3', text: 'From/To Date' }
            ],
            selectedTransactionsToApprove:[],
            selectedReviewTransactionsToApprove:[],
            reviewTransactionsMap: new Map(),
            proceedValidation:{
                amount:false,
                dateApprove:false,
            }
            //reviewTransactionsToApprove:[],

        }
    },
    computed:{
        computedAuthCheckUserRolesPermissionsLoader:{
            get(){
                    return this.$store.state.authCheckUserRolesPermissionsLoader;
            },
            set(value){
                    this.$store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':value});
            }
        },
        computedApproveTransactionToast:{
                get(){
                    return this.$store.state.approveTransactionToast;
                },
                set(value){
                    this.$store.commit('setApproveTransactionToast',{'approveTransactionToast':value});
                }
        },
        computedSearchLoader:{
            get(){
                return this.$store.state.searchLoader;
            },
            set(value){
                this.$store.commit('setSearchLoader',{'searchLoader': value});
            }
        },
        computedBulkApproveAssistanceTableLoader:{
            get(){
                return this.$store.state.bulkApproveAssistanceTableLoader;
            },
            set(value){
                this.$store.commit('setBulkApproveAssistanceTableLoader',{bulkApproveAssistanceTableLoader: value});
            }
        },
        computedApproveDeclineClient:{
            get(){
                return this.$store.state.approveDeclineClient;
            },
            set(value){
                this.$store.commit('setApproveDeclineClient',{approveDeclineClient: value});
            }
        },
        computedTransactionApproveSearch:{
            get(){
                return this.$store.state.transactionApproveSearch;
            },
            set(value){
                this.$store.commit('setTransactionApproveSearch',{transactionApproveSearch: value});
            }
        },
        computedBulkApproveAssistanceTable:{
            get(){
                return this.$store.state.bulkApproveAssistanceTable;
            },
            set(value){
                this.$store.commit('setBulkApproveAssistanceTable',{bulkApproveAssistanceTable: value});
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
        reviewTransactionsToApprove(){
            // to cache when reviewtransactionmap changes
            return Array.from(this.reviewTransactionsMap.values());
        }
    },
    beforeRouteLeave(to, from, next) {
        next(true);
    },
    beforeRouteEnter(to,from,next){
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