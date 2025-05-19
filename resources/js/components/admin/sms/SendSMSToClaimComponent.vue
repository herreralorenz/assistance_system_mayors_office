<template>
    <Toast/>
    <Loader2 v-if="this.computedSendSMSTransactionDetailsLoader"></Loader2>
    <div class="send-sms form-group py-4" v-else>
        <div class="container-fluid">
            <section class="dashboard">
                    <div class="d-flex flex-row h-100">
                        <div class="col-3 card">
                            <div class="row h-100">
                                <div class="col-3 position-relative h-100">
                                    <div class="position-absolute top-50 start-50 translate-middle">
                                        <div class="text-white circle d-flex justify-content-center align-items-center">
                                            <span class="pi pi-arrow-up-right" style="font-size: 20px"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-7 h-100">
                                    <div class="text-white" style="font-size: 25px; line-height: 1;"><b>{{ this.computedHTTPSMSUsage?.data?.sent_messages }}</b></div>
                                    <div class="text-white" style="font-size: 15px; line-height: 1;">Messages Sent</div>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
            <section class="transaction-sms mt-3">
                <h2 class="fw-bold">Select Transaction to Send SMS</h2>
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
                            <input ref="client" v-model="this.computedSMSSearch.client" @keyup="this.searchClient"  id="client" :class="{'form-control':true , 'input-field':true}" type="text" placeholder="Client">
                        </div>
                        <div class="col-12 position-relative" v-if="this.selectedSearchBy === '2'">
                            <i class="fa fa-file fa-lg icon"></i>
                            <input ref="transaction_id"  id="transaction_id" :class="{'form-control':true , 'input-field':true}" @keyup="this.searchTransactionID" v-model="this.computedSMSSearch.transaction_id" type="text" placeholder="Transaction ID">
                        </div> 
                        <div class="col-6 position-relative" v-if="this.selectedSearchBy === '3'">
                            <label for="from" class="icon-date">From</label>
                            <input type="date" v-model="this.computedSMSSearch.date_from" @change="this.searchDateRequest" class="form-control" id="from" :class="{'form-control':true, 'input-field-date':true}" >
                        </div>
                        <div class="col-6 position-relative" v-if="this.selectedSearchBy === '3'">
                            <label for="to" class="icon-date">To</label>
                            <input type="date" v-model="this.computedSMSSearch.date_to"  class="form-control" id="to" @change="this.searchDateRequest()" :class="{'form-control':true, 'input-field-date':true}" >
                        </div>
                    </div>
                </section>
                <section class="table mt-3">
                    <LoaderSearch v-if="this.computedSearchLoader"></LoaderSearch>
                    <DataTable v-else class="dataTable" filterDisplay="menu" paginator :rows="5" v-model:selection="this.selectedSendSMS" :value="this.computedSendSMSTransactionDetails['transaction_approve']" dataKey="id" tableStyle="min-width: 50rem">
                        <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                        <Column sortable field="transaction.transaction_id" header="Transaction ID"></Column>
                        <Column sortable field="transaction.beneficiary_transaction.0.full_name" header="Beneficiary Full Name"></Column>
                        <Column sortable field="transaction.client.full_name" header="Client Full Name"></Column>
                        <Column sortable field="transaction.client.barangay" header="Client Barangay"></Column>
                        <Column sortable field="transaction.date_request" header="Transaction Date"></Column>
                        <Column sortable header="Assistance">
                            <template #body="slotProps">
                                <p>{{slotProps.data.transaction.agency.agency_abbreviation+" "+slotProps.data.transaction.agency_program.agency_program_abbreviation+" "+slotProps.data.transaction.assistance_type.assistance_type}}</p>
                            </template>
                        </Column>
                         <Column sortable header="SMS Status">
                            <template #body="slotProps">
                                <div :style="{ 'font-size': '0.75rem', 'width':'120px', 'border-radius':'10px'}" :class="{'bg-success': slotProps.data?.latest_sent_s_m_s?.webhook?.type === 'message.phone.delivered' ? true : false,'bg-danger': slotProps.data?.latest_sent_s_m_s?.webhook?.type != 'message.phone.delivered' ? true : false, 'text-white':true, 'text-center':true}">{{slotProps.data?.latest_sent_s_m_s?.webhook?.type === "message.phone.delivered" ? 'DELIVERED' :  slotProps.data?.latest_sent_s_m_s?.webhook != null  ? slotProps.data?.latest_sent_s_m_s?.webhook?.type.split(".")[2].toUpperCase() : ''}}</div>
                            </template>
                        </Column>
                        <Column sortable header="Sent Count">
                            <template #body="slotProps">
                                <div :style="{ 'font-size': '0.75rem', 'width':'30px', 'border-radius':'10px','color': 'white'}" :class="{'bg-secondary': true, 'text-center':true}">{{slotProps?.data?.latest_sent_s_m_s?.sms_count}}</div>
                            </template>
                        </Column>
                    </DataTable>
                    <button type="button" class="btn btn-primary w-100" @click="this.add()">ADD</button>
                </section>
            </section>
            <section class="transaction-sms mt-3">
                <h2 class="fw-bold">Review Transaction to Send SMS</h2>
                <div class="table mt-3">
                    <DataTable class="dataTable" filterDisplay="menu" paginator :rows="5" :value="this.computedSelectedSMSMap" v-model:selection="this.selectedReviewSendSMS"  dataKey="id" tableStyle="min-width: 50rem">
                        <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                        <Column sortable field="transaction.transaction_id" header="Transaction ID"></Column>
                        <Column sortable field="transaction.beneficiary_transaction.0.full_name" header="Beneficiary Full Name"></Column>
                        <Column sortable field="transaction.client.full_name" header="Client Full Name"></Column>
                        <Column sortable field="transaction.client.barangay" header="Client Barangay"></Column>
                        <Column sortable field="transaction.date_request" header="Transaction Dates"></Column>
                        <Column sortable header="Assistance">
                            <template #body="slotProps">
                                <p>{{slotProps.data.transaction.agency.agency_abbreviation+" "+slotProps.data.transaction.agency_program.agency_program_abbreviation+" "+slotProps.data.transaction.assistance_type.assistance_type}}</p>
                            </template>
                        </Column>
                        <Column sortable header="SMS Status">
                            <template #body="slotProps">
                                <div :style="{ 'font-size': '0.75rem', 'width':'120px', 'border-radius':'10px'}" :class="{'bg-success': slotProps.data?.latest_sent_s_m_s?.webhook?.type === 'message.phone.delivered' ? true : false,'bg-danger': slotProps.data?.latest_sent_s_m_s?.webhook?.type != 'message.phone.delivered' ? true : false, 'text-white':true, 'text-center':true}">{{slotProps.data?.latest_sent_s_m_s?.webhook?.type === "message.phone.delivered" ? 'DELIVERED' :  slotProps.data?.latest_sent_s_m_s?.webhook != null  ? slotProps.data?.latest_sent_s_m_s?.webhook?.type.split(".")[2].toUpperCase() : ''}}</div>
                            </template>
                        </Column>
                         <Column sortable header="Sent Count">
                            <template #body="slotProps">
                                <div :style="{ 'font-size': '0.75rem', 'width':'30px', 'border-radius':'10px','color': 'white'}" :class="{'bg-secondary': true, 'text-center':true}">{{slotProps?.data?.latest_sent_s_m_s?.sms_count}}</div>
                            </template>
                        </Column>
                        <Column sortable field="id" header="Action">
                        <template #body="slotProps">
                            <Button :style="{ 'font-size': '0.75rem' }" type="button" icon="pi pi-eraser" severity="danger" v-model="slotProps.data.id" label="Delete"  @click="deleteTransaction(slotProps.data.id)" />
                        </template>
                    </Column>
                    </DataTable>
                    <Button class="mt-2" :style="{ 'font-size': '0.75rem', 'width': '120px' }" type="button"
                    icon="pi pi-eraser" severity="danger" label="Clear Table" @click="clearTable()" rounded />
                </div>
                <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#SMSMessageModal">SEND SMS</button>
            </section>
        </div>
    </div>


     <!-- SMS Message Modal -->
     <div v-if="!this.computedSearchLoader" class="modal fade" id="SMSMessageModal" ref="SMSMessageModal" tabindex="-1" aria-labelledby="SMSMessageModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="SMSMessageLabel">SMS Message</h5>
                    
                    <button type="button" id="btn-close-sms" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                    <div class="container my-2">
                        <label for="smsSelect">Select SMS Message</label>
                        <select v-model="this.selectedSMSMessage" id="smsSelect" :class="{'form-select':true, 'is-invalid': this.proceedValidation.selectedSMSMessage}">
                            <option value="" selected>Please select a message</option>
                            <option :key="option.id" :value="option.id" v-for="(option,index) in this.computedSMSMessageDetails"> {{ option.subject }}</option>
                        </select>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" @click="this.sendSMS()" class="btn btn-success">Send</button>
                </div>
                </div>
            </div>
        </div>
</template>
<style scoped>

.dashboard{
    height: 80px;
}
.circle{
    background-color: #039132;
    border-radius: 50%;
    width: 50px;
    height: 50px;
}

.card{
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    box-sizing: border-box;
    padding: 20px;
    background-color: rgb(58, 109, 62);
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

.input-field-date{
    border-radius: 25px;
    height: 50px;
}


select, button{
    border-radius:25px;
}

.send-sms{
    margin: 0;
    padding-left: 250px;
    overflow-y: scroll;
            
            
    height: -webkit-calc(100% - 50px);
    height: -moz-calc(100% - 50px);
    height: calc(100% - 50px);
}

.table {
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    height: 100%;
    width: 100%;
    box-sizing: border-box;
    padding:10px;
    transition: transform 0.3s ease; 
}


.transaction-sms{
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
}


</style>
<script>
import auth from '../../../router/auth_roles_permissions.js';
import Loader2 from '../Loader2.vue'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import LoaderSearch from '../LoaderSearch.vue';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';
export default{
    components:{
        DataTable,
        Loader2,
        Column,
        Button,
        LoaderSearch,
        Toast,
    },
    beforeMount(){
        this.toast = useToast();
        this.computedHeaderText = "Send SMS"
        this.computedSendSMSTransactionDetailsLoader = true;
        this.$store.dispatch('fetchSendSMSTransactionDetails').then(response =>{
             this.$store.dispatch('fetchSMSMessageDetails').then(response => {
                this.$store.dispatch('fetchHTTPSMSUsage').then(response =>{
                    this.computedSendSMSTransactionDetailsLoader = false;

                });
             });
        });
        
    },
    mounted(){
        
    },
    beforeUnmount(){
        document.body.style.overflow = 'auto';
    },
    methods:{
        deleteTransaction(id){
            this.addSelectedSendSMSMap.delete(id);
        },
        sendSMS(){
            // this.$store.dispatch('sendBulkSMS').then(response =>{
            //     console.log(response);
            // });

            if(this.selectedSMSMessage != ''){
                this.proceedValidation.selectedSMSMessage = false;
            }else{
                this.proceedValidation.selectedSMSMessage = true
            }
   
            let slice = 10;

            //0 - 3
            //    
            if(!this.proceedValidation.selectedSMSMessage){
                document.getElementById('btn-close-sms').click();
                console.log(this.selectedReviewSendSMS);
                for(let i = 0 ; i < this.selectedReviewSendSMS.length ; i+=slice){
                    this.$store.dispatch('sendBulkSMS',{'selectedSMS':this.selectedReviewSendSMS.slice(i,slice+i),'smsMessage':this.selectedSMSMessage}).then(response => {
                        this.computedSendSMSTransactionDetailsLoader = true;
                        this.$store.dispatch('fetchSendSMSTransactionDetails').then(response => {
                            this.$store.dispatch('fetchSMSMessageDetails').then(response => {
                                this.$store.dispatch('fetchHTTPSMSUsage').then(response => {
                                    this.computedSendSMSTransactionDetailsLoader = false;
                                    this.addSelectedSendSMSMap = new Map();
                                    this.toast.add({ severity: 'success', summary: 'SMS', detail: 'All messages are now sending.', life: 5000 });
                                });
                            });
                        });
                    });
                }
            }
 
        },
        searchByClear() {
            for (const [key, value] of Object.entries(this.computedSMSSearch)) {

                this.computedSMSSearch[key] = '';
            }

        },
        searchDateRequest() {
            if (this.computedSMSSearch.date_from != '' && this.computedSMSSearch.date_to != '') {
                clearTimeout(this.timeout);

                this.timeout = setTimeout(() => {
                    this.computedSearchLoader = true;
                    this.$store.dispatch('searchSendSMSTransactionDateRequest', { date_from: this.computedSMSSearch.date_from, date_to: this.computedSMSSearch.date_to }).then(response => {
                        this.computedSearchLoader = false;
                        this.$store.commit('setSendSMSTransactionDetails', { 'sendSMSTransactionDetails': response.data });
              
                    });

                }, 450);
            }
        },
        searchClient() {
            clearTimeout(this.timeout);

            this.timeout = setTimeout(() => {
                this.computedSearchLoader = true;
                this.$store.dispatch('searchSendSMSTransactionClient', { client: this.computedSMSSearch.client }).then(response => {
                    this.computedSearchLoader = false;
                    this.$store.commit('setSendSMSTransactionDetails', { 'sendSMSTransactionDetails': response.data });
                });

            }, 450);
        },
        searchTransactionID() {
            clearTimeout(this.timeout);

            this.timeout = setTimeout(() => {
                this.computedSearchLoader = true;
                this.$store.dispatch('searchSendSMSTransactionID', { transaction_id: this.computedSMSSearch.transaction_id }).then(response => {
                    this.computedSearchLoader = false;
                    console.log(response);
                    this.$store.commit('setSendSMSTransactionDetails', { 'sendSMSTransactionDetails': response.data });
                });

            }, 450);
        },
        add(){
            this.selectedSendSMS.forEach((val, index) =>{
                this.addSelectedSendSMSMap.set(val.id,val);
            });
        },
        clearTable(){
            this.addSelectedSendSMSMap = new Map();
        }
    },
    computed:{
        computedHTTPSMSUsage:{
            get(){
                return this.$store.state.HTTPSMSUsage;
            },
            set(value){
                this.$store.dispatch('setHTTPSMSUsage',{'HTTPSMSUsage':value});
            }
        },
        computedSMSMessageDetails:{
            get(){
                return this.$store.state.SMSMessageDetails;
            }
        },
        computedSearchLoader: {
            get() {
                return this.$store.state.searchLoader;
            },
            set(value) {
                this.$store.commit('setSearchLoader', { 'searchLoader': value });
            }
        },
        computedSMSSearch:{
            get(){
                return this.$store.state.SMSSearch;
            },
            set(value){
                this.$store.commit('setSMSSearch',{'SMSSearch':value});
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
        computedSendSMSTransactionDetails:{
            get(){
                return this.$store.state.sendSMSTransactionDetails;
            },
            
        },
        computedSendSMSTransactionDetailsLoader:{
            get(){
                return this.$store.state.sendSMSTransactionDetailsLoader;
            },
            set(value){
                this.$store.commit('setSendSMSTransactionDetailsLoader',{'sendSMSTransactionDetailsLoader':value});
            }
            
        },
        computedSelectedSMSMap(){
            return Array.from(this.addSelectedSendSMSMap.values());
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
    },
    data(){
        return{
            toast:null,
            proceedValidation:{
                selectedSMSMessage:false,
            },
            selectedSMSMessage:'',
            timeout:null,
            addSelectedSendSMSMap: new Map(),
            selectedSendSMS:[],
            selectedReviewSendSMS:[],
            selectedSearchBy:'1',
            searchBy:[
                { value: '1', text: 'Client' },
                { value: '2', text: 'Transaction ID' },
                { value: '3', text: 'From/To Date' }
            ],
        }
    }
}

</script>