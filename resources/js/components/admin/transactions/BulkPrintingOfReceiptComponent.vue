<template>
    <div class="form-group bulk-print py-4">
        <div class="container-fluid">
        <section class="transaction-to-print">
            <h2 class="fw-bold">Select Transaction to Print</h2>
            <section class="searchbar">
                <div class="row">
                   <div class="col-12 position-relative">
                    <i class="fa fa-solid fa-magnifying-glass fa-lg icon"></i>
                        <select id="searchBy" :class="{'form-select':true, 'input-field':true}" @change="changeSelect()" v-model="this.selectedSearchBy">
                            <option selected disabled value="">Search by...</option>
                            <option v-for="option in this.searchBy" :key="option.value" :value="option.value">
                                {{ option.text }}
                            </option>
                        </select>                
                   </div>
                </div>
                <div class="row mt-4 d-flex align-items-center justify-content-center">
                    <div class="col-1 d-flex flex-column">
                        <span class="text-center text-secondary">Total:</span>
                        <span class="text-center text-success">{{ this.computedBulkPrintingOfReceiptDetails.transactionCount }}</span>
                    </div>
                    <div class="col-11 position-relative" v-if="this.selectedSearchBy === '1'">
                        <i class="fa-solid fa-user fa-lg icon"></i>
                        <input ref="clientBeneficiary" v-model="this.computedBulkPrintingOfReceipt.client_fullname" v-on:keyup="searchClient()"  id="clientBeneficiary" :class="{'form-control':true , 'input-field':true}" type="text" placeholder="Client">
                    </div>
                    <div class="col-11 position-relative" v-if="this.selectedSearchBy === '2'">
                        <i class="fa fa-file fa-lg icon"></i>
                        <input ref="clientBeneficiary" v-model="this.computedBulkPrintingOfReceipt.transaction_id"  id="clientBeneficiary" v-on:keyup="searchTransactionID()" :class="{'form-control':true , 'input-field':true}" type="text" placeholder="Transaction ID">
                    </div> 
                    <div class="col-5 position-relative" v-if="this.selectedSearchBy === '3'">
                        <label for="from" class="icon-date">From</label>
                        <input type="date" @change="this.searchDate()" v-model="this.computedBulkPrintingOfReceipt.from_date"  class="form-control" id="from" :class="{'form-control':true, 'input-field-date':true}" >
                    </div>
                    <div class="col-5" v-if="this.selectedSearchBy === '3'">
                        <label for="to" class="icon-date">To</label>
                        <input type="date" @change="this.searchDate()" v-model="this.computedBulkPrintingOfReceipt.to_date"  class="form-control" id="to" :class="{'form-control':true, 'input-field-date':true}" >
                    </div>
                </div>
            </section>
            <section class="table mt-3">
                <LoaderSearch v-if="this.computedBulkPrintingOfReceiptLoader"></LoaderSearch>
                <DataTable v-else-if="!this.computedBulkPrintingOfReceiptLoader"  class="dataTable" filterDisplay="menu" paginator :rows="5"  v-model:selection="selectedTransactionsToPrint" :value="this.computedBulkPrintingOfReceiptDetails.transaction" dataKey="id" tableStyle="min-width: 50rem">
                    <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                    <Column sortable field="transaction_id" header="Transaction ID"></Column>
                    <Column sortable header="Client Full Name">
                        <template #body="slotProps">
                            {{ slotProps.data.client.first_name+" "+(slotProps.data.client.middle_name ?? "")+" "+slotProps.data.client.last_name+" "+(slotProps.data.client?.suffix?.suffix ?? "") }}
                        </template>
                    </Column>
                    <Column sortable field="client.birthdate" header="Client Birthdate"></Column>
                    <Column sortable field="client.barangay" header="Client Barangay"></Column>
                    <Column sortable field="date_request" header="Transaction Date"></Column>
                    <Column sortable field="assistance_type.assistance_type" header="Type of Assistance"></Column>
                    <Column sortable field="status" header="Status">
                        <template #body="slotProps">
                            <div :style="{ 'font-size': '0.75rem', 'width':'120px', 'border-radius':'10px'}"  :class="{'bg-success':slotProps.data.transaction_approve?.transaction_claim?.transaction_claim_status_condition?.[0]?.status === 'CLAIMED' || slotProps.data.transaction_approve?.transaction_approve_status_condition?.[0]?.status === 'APPROVED'  ? true : false,'bg-secondary':slotProps.data.transaction_approve?.transaction_claim?.transaction_claim_status_condition?.[0]?.status === 'TO CLAIM' ? true : slotProps.data.transaction_approve?.transaction_approve_status_condition?.[0]?.status === 'TO APPROVE' ? true : false,'bg-danger': slotProps.data.transaction_approve?.transaction_approve_status_condition?.[0]?.status === 'DECLINED' ? true : false, 'text-white': true, 'text-center':true }">{{ slotProps.data.transaction_approve?.transaction_claim?.transaction_claim_status_condition?.[0]?.status ?? slotProps.data.transaction_approve?.transaction_approve_status_condition?.[0]?.status }}</div>
                        </template>
                    </Column>
                </DataTable>
            </section>
            <button type="button" class="btn btn-primary w-100" @click="this.add()">ADD</button>
        </section>
        <section class="print mt-3">
            <h2 class="fw-bold">Review Transactions to Print</h2>
            <section class="table mt-3">
                <DataTable class="dataTable" filterDisplay="menu" paginator :rows="5" v-model:selection="selectedReviewTransactionsToPrint" :value="this.reviewTransactionsToPrint" dataKey="id" tableStyle="min-width: 50rem">
                    <Column selectionMode="multiple" headerStyle="width: 3rem"> 
                    </Column>
                    <Column sortable field="transaction_id" header="Transaction ID"></Column>
                    <Column sortable header="Client Full Name">
                        <template #body="slotProps">
                            {{ slotProps.data.client.first_name+" "+(slotProps.data.client.middle_name ?? "")+" "+slotProps.data.client.last_name+" "+(slotProps.data.client?.suffix?.suffix ?? "") }}
                        </template>
                    </Column>
                    <Column sortable field="client.birthdate" header="Client Birthdate"></Column>
                    <Column sortable field="client.barangay" header="Client Barangay"></Column>
                    <Column sortable field="date_request" header="Transaction Date"></Column>
                    <Column sortable field="assistance_type.assistance_type" header="Type of Assistance"></Column>
                    <Column sortable field="status" header="Status">
                        <template #body="slotProps">
                            <div :style="{ 'font-size': '0.75rem', 'width':'120px', 'border-radius':'10px'}"  :class="{'bg-success':slotProps.data.transaction_approve?.transaction_claim?.transaction_claim_status_condition?.[0]?.status === 'CLAIMED' || slotProps.data.transaction_approve?.transaction_approve_status_condition?.[0]?.status === 'APPROVED'  ? true : false,'bg-secondary':slotProps.data.transaction_approve?.transaction_claim?.transaction_claim_status_condition?.[0]?.status === 'TO CLAIM' ? true : slotProps.data.transaction_approve?.transaction_approve_status_condition?.[0]?.status === 'TO APPROVE' ? true : false,'bg-danger': slotProps.data.transaction_approve?.transaction_approve_status_condition?.[0]?.status === 'DECLINED' ? true : false, 'text-white': true, 'text-center':true }">{{ slotProps.data.transaction_approve?.transaction_claim?.transaction_claim_status_condition?.[0]?.status ?? slotProps.data.transaction_approve?.transaction_approve_status_condition?.[0]?.status }}</div>
                        </template>
                    </Column>
                </DataTable>
                <Button class="mt-3" :style="{ 'font-size': '0.75rem', 'width':'120px' }" type="button" icon="pi pi-eraser" severity="danger" label="Clear Table"  @click="clearTable()" rounded/>
            </section>
            <div class="row">
                <div class="col-12">
                    <button @click="this.printReceipt()" type="button" class="btn btn-success w-100">PRINT RECEIPT</button>
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


.transaction-to-print, .print{
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5); 
}


.bulk-print{
    margin: 0;
    padding-left: 250px;
    overflow-y: auto;
    height: -webkit-calc(100% - 50px);
    height: -moz-calc(100% - 50px);
    height: calc(100% - 50px);  
}


.icon {
     padding: 10px;
     margin-top: 15px;
     min-width: 40px;
     position: absolute;
}



.input-field-date{
    border-radius: 25px;
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
</style>
<script>

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import MultiSelect from 'primevue/multiselect';
import Select from 'primevue/select';
import Tag from 'primevue/tag';
import Button from 'primevue/button';
import LoaderSearch from '../LoaderSearch.vue';
import auth from '../../../router/auth_roles_permissions.js';

export default{
    components:{
        DataTable,
        Column,
        Button,
        LoaderSearch,
    },
    beforeMount(){
        this.computedReceiptCounter = 0;
        this.computedHeaderText = 'Bulk Print of Receipt'
        document.body.style.overflow = 'auto';
    },
    mounted(){
        document.body.style.overflow = 'hidden';
        window.scrollTo({
        top: 0,
        behavior: 'instant' // Optional: adds smooth scrolling effect
        });
    },
    beforeUnmount(){
        document.body.style.overflow = 'auto';
    },
    methods:{
      printReceipt(){
        this.computedSelectedReviewTransactionsToPrint = this.selectedReviewTransactionsToPrint;
        this.$router.push({name:'bulkPrintingOfReceiptHolder'});
      },
      changeSelect(){
        for(const [key,value] of Object.entries(this.computedBulkPrintingOfReceipt)){
            this.computedBulkPrintingOfReceipt[key] = '';
        }
      },
      searchTransactionID(){
            clearTimeout(this.timeout);
            this.computedBulkPrintingOfReceiptLoader = true;
            this.timeout = setTimeout(() =>{
                this.$store.dispatch('searchBulkPrintingOfReceiptTransactionID',{'transaction_id':this.computedBulkPrintingOfReceipt.transaction_id}).then(response => {
                    this.computedBulkPrintingOfReceiptLoader = false;
                });
            },450);
      },
      searchClient(){
        clearTimeout(this.timeout);
        this.computedBulkPrintingOfReceiptLoader = true;
        this.timeout = setTimeout(() =>{
            this.$store.dispatch('searchBulkPrintingOfReceiptClient',{'client_fullname':this.computedBulkPrintingOfReceipt.client_fullname}).then(response => {
                this.computedBulkPrintingOfReceiptLoader = false;
            });
        },450);
        
      },
      searchDate(){

        if((typeof this.computedBulkPrintingOfReceipt.from_date == 'string' && this.computedBulkPrintingOfReceipt.from_date && this.computedBulkPrintingOfReceipt.from_date != '') && (typeof this.computedBulkPrintingOfReceipt.to_date == 'string' && this.computedBulkPrintingOfReceipt.to_date && this.computedBulkPrintingOfReceipt.to_date != '')){
            clearTimeout(this.timeout);
            this.computedBulkPrintingOfReceiptLoader = true;
            this.timeout = setTimeout(() =>{
                this.$store.dispatch('searchBulkPrintingOfReceiptDate',{'from_date':this.computedBulkPrintingOfReceipt.from_date, 'to_date':this.computedBulkPrintingOfReceipt.to_date}).then(response => {
                    this.computedBulkPrintingOfReceiptLoader = false;
                    console.log(response);
                });
            },450);
        }
     
        
      },
      add(){
        // this.reviewTransactionsMap = new Map(this.selectedTransactionsToPrint.map(item => [item.id,item]));

        this.selectedTransactionsToPrint.forEach((val, index) => {
                this.reviewTransactionsMap.set(val.id, val);
            });
      },
      deleteRow(data){

        this.reviewTransactionsMap.delete(data.id);
      },
      clearTable(){
        this.reviewTransactionsMap.clear();
      }
    },
    data(){
        return{
            timeout:null,
            selectedSearchBy:'1',
            searchBy:
            [
                { value: '1', text: 'Client' },
                { value: '2', text: 'Transaction ID' },
                { value: '3', text: 'From/To Date' }
            ],
            selectedTransactionsToPrint:[],
            selectedReviewTransactionsToPrint:[],
            reviewTransactionsMap: new Map(),

        }
    },
    computed:{
        computedReceiptCounter:{
            get(){
                return this.$store.state.receiptCounter;
            },
            set(value){
                this.$store.commit('setReceiptCounter',{'receiptCounter':value});
            }
        },
        computedSelectedReviewTransactionsToPrint:{
            get(){
                return this.$store.state.selectedReviewTransactionsToPrint;
            },
            set(value){
                this.$store.commit('setSelectedReviewTransactionsToPrint',{'selectedReviewTransactionsToPrint':value});
            }
        },
        computedBulkPrintingOfReceiptLoader:{
            get(){
                return this.$store.state.bulkPrintingOfReceiptLoader;
            },
            set(value){
                this.$store.commit('setBulkPrintingOfReceiptLoader',{'bulkPrintingOfReceiptLoader':value});
            }
        },
        computedBulkPrintingOfReceipt:{
            get(){
                return this.$store.state.bulkPrintingOfReceipt;
            },
            set(value){
                this.$store.state.commit('setBulkPrintingOfReceipt',{'bulkPrintingOfReceipt':value});
            }
        },
        computedBulkPrintingOfReceiptDetails:{
            get(){
                return this.$store.state.bulkPrintingOfReceiptDetails;
            },
            set(value){
                this.$store.state.commit('setBulkPrintingOfReceiptDetails',{'bulkPrintingOfReceiptDetails':value});
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
        reviewTransactionsToPrint(){
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