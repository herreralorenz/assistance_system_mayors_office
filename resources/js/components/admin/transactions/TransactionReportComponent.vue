<template>
    <Loader2 v-if="this.computedTransactionReportLoader"></Loader2>
    <div v-if="!this.computedTransactionReportLoader" class="form-group transaction-report py-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    <label for="from">From:</label>
                    <input v-model="this.computedTransactionReport.from_date" type="date" :class="{'form-control':true,'is-invalid':this.proceedValidation.from_date}" id="from">
                </div>
                <div class="col-6">
                    <label for="to">To:</label>
                    <input v-model="this.computedTransactionReport.to_date" type="date" :class="{'form-control':true,'is-invalid':this.proceedValidation.to_date}" id="to">
                </div>
            </div>
            <div class="transaction-report-table mt-4">
                <LoaderSearch v-if="this.computedTransactionReportTableLoader"></LoaderSearch>
                <DataTable  v-else-if="!this.computedTransactionReportTableLoader" class="dataTable" filterDisplay="menu" paginator :rows="5" :total-records="this.computedGenerateReport.transactionCount"   v-model:first="resetPage" :value="this.computedGenerateReport.transaction" dataKey="id" tableStyle="min-width: 50rem">
                    <!-- <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>  -->
                    <Column sortable field="transaction_id" header="Transaction ID"></Column>
                    <Column sortable header="Client Full Name">
                        <template #body="slotProps">
                            {{ slotProps.data.client.first_name+" "+(slotProps.data.client.middle_name ?? "")+" "+slotProps.data.client.last_name+" "+(slotProps.data.client?.suffix?.suffix ?? "") }}
                        </template>
                    </Column>
                    <Column sortable field="assistance_type.assistance_type" header="Type of Assistance"></Column>
                    <Column sortable field="assistance_description.assistance_description" header="Description of Assistance"></Column>
                    <Column sortable field="transaction_approve.transaction_approve_amount.amount" header="Amount of Assistance"></Column>
                    <Column sortable field="date_request" header="Transaction Date"></Column>
                    <Column sortable  header="Approved Date">
                        <template #body="slotProps">
                            {{ slotProps.data.transaction_approve.transaction_approve_status_condition[0].status == "APPROVED" ? slotProps.data.transaction_approve.transaction_approve_status_condition[0].pivot.status_condition_date : "" }}
                        </template>
                    </Column>
                    <Column sortable field="transactionDate" header="Claimed Date">
                        <template #body="slotProps">
                            {{ slotProps.data.transaction_approve?.transaction_claim?.transaction_claim_status_condition?.[0]?.status === 'CLAIMED' ? slotProps.data.transaction_approve?.transaction_claim?.transaction_claim_status_condition?.[0]?.pivot.status_condition_date : ""}}
                        </template>
                    </Column>
                </DataTable>
            </div>
            <button type="button" class="btn btn-secondary w-100 mt-3" @click="this.generate()">GENERATE</button>
            <div class="row mt-3 container">
                <div class="col-6  ">
                    <div class="h-100">
                        <button type="button" class="btn btn-success w-100 h-100" @click="this.downloadAllData()">DOWNLOAD ALL DATA</button> 
                    </div>
                </div>
                <div class="col-6  button-container">
                    <div class="row">
                        <div class="col-6">
                                <label for="agency">Agency</label>
                                <select id="agency"
                                    :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.agency,'select-field':true }"
                                    v-model="this.computedTypeOfAssistance.agency
                                        " @change="this.handleSelect('agencySelect')">
                                    <option value=""></option>
                                    <option v-for="(option, index) in this
                                        .computedFormSeeder
                                        .assistance_program_type" :key="option.id" :value="option.id">
                                        {{ option.agency_name }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="agencyProgram">Agency Program</label>
                                <select id="agencyProgram"
                                    :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.agencyProgram,'select-field':true }"
                                    v-model="this.computedTypeOfAssistance
                                        .agencyProgram
                                        " @change="this.handleSelect('agencyProgramSelect')">
                                    <option value=""></option>
                                    <option v-for="(option, index) in this
                                        .computedFormSeeder
                                        ?.assistance_program_type[
                                        this.computedTypeOfAssistance.agency
                                    ]?.['agency_program']" :key="option.id" :value="option.id">
                                        {{
                                            option.agency_program_name +
                                            " (" +
                                            option.agency_program_abbreviation +
                                            ")"
                                        }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="typeofassistance">Type of Assistance</label>
                                <select id="typeofassistance"
                                    :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.typeOfAssistance,'select-field':true }"
                                    v-model="this.computedTypeOfAssistance
                                        .typeOfAssistance
                                        " @change="this.handleSelect('typeOfAssistanceSelect')">
                                    <option value=""></option>
                                    <option v-for="(option, index) in this.computedFormSeeder?.assistance_program_type[this.computedTypeOfAssistance.agency]?.['agency_program']?.[this.computedTypeOfAssistance.agencyProgram]?.['assistance_type_agency_program']" :key="index" :value="index">
                                        {{ option.assistance_type }}
                                    </option>
                                </select>
                            </div>
                    </div>
                    <div class="mt-3">
                        <button type="button" class="btn btn-secondary w-100 m-1" @click="this.downloadClaimed()">DOWNLOAD CLAIMED</button>
                        <button type="button" class="btn btn-secondary w-100 m-1" @click="this.downloadUnclaimed()">DOWNLOAD UNCLAIMED</button>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>

.select-field,
.input-field {
    border-radius: 25px;
}

button{
    border-radius: 25px;
}
select-field{
    border-radius: 25px;
}

.transaction-report-table{
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    height: 100%;
    width: 100%;
    box-sizing: border-box;
    padding:10px;


}

.button-container{
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    padding: 10px
}

.transaction-report{
    margin: 0;
    padding-left: 250px;
    overflow-y: auto;
    height: -webkit-calc(100% - 50px);
    height: -moz-calc(100% - 50px);
    height: calc(100% - 50px);
}

input{
    border-radius: 25px;
    height: 50px;
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
import LoaderSearch from '../LoaderSearch.vue'
import Loader2 from '../Loader2.vue'
import auth from '../../../router/auth_roles_permissions.js';

export default{
    components:{
        DataTable,
        Column,
        Button,
        LoaderSearch,
        Loader2,
    },
    beforeMount(){
        
        for(const [key,value] of Object.entries(this.computedTypeOfAssistance)){
            this.computedTypeOfAssistance[key] = '';
        }
        
        for(const [key,value] of Object.entries(this.computedTransactionReport)){
            this.computedTransactionReport[key] = '';
        }

        this.computedGenerateReport = [];
        this.computedHeaderText = 'Transaction Report';
        this.computedTransactionReportLoader = true;
        this.$store.dispatch('fetchSeeders').then(response => {

            this.computedTransactionReportLoader = false;
        });

    },
    mounted(){
        
        window.scrollTo({
            top: 0,
            behavior: 'instant' // Optional: adds smooth scrolling effect
        });
    },
    beforeUnmount(){
        document.body.style.overflow = 'auto';
    },
    methods:{
        downloadUnclaimed(){

        if(typeof this.computedTransactionReport.from_date == 'string' && this.computedTransactionReport.from_date == '' && !this.computedTransactionReport.from_date){
            this.proceedValidation.from_date = true;
        }else{
            this.proceedValidation.from_date = false;
        }

        if(typeof this.computedTransactionReport.to_date == 'string' && this.computedTransactionReport.to_date == '' && !this.computedTransactionReport.to_date){
            this.proceedValidation.to_date = true;
        }else{
            this.proceedValidation.to_date = false;
        }

        if(this.computedTypeOfAssistance.agency === '' || this.computedTypeOfAssistance.agency === null){
            this.proceedValidation.agency = true;
        }else{
            this.proceedValidation.agency = false;
        }

        if(this.computedTypeOfAssistance.agencyProgram === '' || this.computedTypeOfAssistance.agencyProgram === null) {
            this.proceedValidation.agencyProgram = true;
        }else{
            this.proceedValidation.agencyProgram = false;
        }

        if (this.computedTypeOfAssistance.typeOfAssistance === '' || this.computedTypeOfAssistance.typeOfAssistance === null) {
            this.proceedValidation.typeOfAssistance = true;
        }else{
            this.proceedValidation.typeOfAssistance = false;
        }

        let proc = true;
        for(const [key,value] of Object.entries(this.proceedValidation)){
            if(this.proceedValidation[key]){
                proc = false;
                break;
            }
        }

        if(proc){
            window.open('/api/download-unclaimed-report?data[transactionReport][from_date]='+this.computedTransactionReport.from_date+'&data[transactionReport][to_date]='+this.computedTransactionReport.to_date+'&data[typeOfAssistance][agency]='+this.computedTypeOfAssistance.agency+'&data[typeOfAssistance][agencyProgram]='+this.computedTypeOfAssistance.agencyProgram+'&data[typeOfAssistance][typeOfAssistance]='+this.computedTypeOfAssistance.typeOfAssistance, "_self");
        }

        },
        downloadClaimed(){

            if(typeof this.computedTransactionReport.from_date == 'string' && this.computedTransactionReport.from_date == '' && !this.computedTransactionReport.from_date){
                this.proceedValidation.from_date = true;
            }else{
                this.proceedValidation.from_date = false;
            }
        
            if(typeof this.computedTransactionReport.to_date == 'string' && this.computedTransactionReport.to_date == '' && !this.computedTransactionReport.to_date){
                this.proceedValidation.to_date = true;
            }else{
                this.proceedValidation.to_date = false;
            }

            if(this.computedTypeOfAssistance.agency === '' || this.computedTypeOfAssistance.agency === null){
                this.proceedValidation.agency = true;
            }else{
                this.proceedValidation.agency = false;
            }

            if(this.computedTypeOfAssistance.agencyProgram === '' || this.computedTypeOfAssistance.agencyProgram === null) {
                this.proceedValidation.agencyProgram = true;
            }else{
                this.proceedValidation.agencyProgram = false;
            }

            if (this.computedTypeOfAssistance.typeOfAssistance === '' || this.computedTypeOfAssistance.typeOfAssistance === null) {
                this.proceedValidation.typeOfAssistance = true;
            }else{
                this.proceedValidation.typeOfAssistance = false;
            }

            let proc = true;
            for(const [key,value] of Object.entries(this.proceedValidation)){
                if(this.proceedValidation[key]){
                    proc = false;
                    break;
                }
            }
            
            if(proc){
                window.open('/api/download-claimed-report?data[transactionReport][from_date]='+this.computedTransactionReport.from_date+'&data[transactionReport][to_date]='+this.computedTransactionReport.to_date+'&data[typeOfAssistance][agency]='+this.computedTypeOfAssistance.agency+'&data[typeOfAssistance][agencyProgram]='+this.computedTypeOfAssistance.agencyProgram+'&data[typeOfAssistance][typeOfAssistance]='+this.computedTypeOfAssistance.typeOfAssistance, "_self");
            }
            
        },
        handleSelect(select) {
            if (select === 'agencySelect') {
                if (this.computedTypeOfAssistance.agency === '' || this.computedTypeOfAssistance.agency === null) {
                    this.proceedValidation.agency = true;

                    this.computedTypeOfAssistance.agencyProgram = '';
                    this.computedTypeOfAssistance.typeOfAssistance = '';
                    this.computedTypeOfAssistance.otherTypeOfAssistance = '';
                    this.computedTypeOfAssistance.descriptionOfAssistance = '';
                    this.computedTypeOfAssistance.otherDescriptionOfAssistance = '';


                    this.proceedValidation.agencyProgram = false;
                    this.proceedValidation.typeOfAssistance = false;
                    this.proceedValidation.otherTypeOfAssistance = false;
                    this.proceedValidation.descriptionOfAssistance = false;
                    this.proceedValidation.otherDescriptionOfAssistance = false;
                } else {
                    this.proceedValidation.agency = false;

                    this.computedTypeOfAssistance.agencyProgram = '';
                    this.computedTypeOfAssistance.typeOfAssistance = '';
                    this.computedTypeOfAssistance.otherTypeOfAssistance = '';
                    this.computedTypeOfAssistance.descriptionOfAssistance = '';
                    this.computedTypeOfAssistance.otherDescriptionOfAssistance = '';
                }
            } else if (select === 'agencyProgramSelect') {
                if (this.computedTypeOfAssistance.agencyProgram === '' || this.computedTypeOfAssistance.agencyProgram === null) {
                    this.proceedValidation.agencyProgram = true;

                    this.computedTypeOfAssistance.typeOfAssistance = '';
                    this.computedTypeOfAssistance.descriptionOfAssistance = '';
                    this.computedTypeOfAssistance.otherDescriptionOfAssistance = '';

                    this.proceedValidation.typeOfAssistance = false;
                    this.proceedValidation.descriptionOfAssistance = false;
                    this.proceedValidation.otherDescriptionOfAssistance = false;

                } else {
                    this.proceedValidation.agencyProgram = false;

                    this.computedTypeOfAssistance.typeOfAssistance = '';
                    this.computedTypeOfAssistance.descriptionOfAssistance = '';
                    this.computedTypeOfAssistance.otherDescriptionOfAssistance = '';

                }
            } else if (select === 'typeOfAssistanceSelect') {
                if (this.computedTypeOfAssistance.typeOfAssistance === '' || this.computedTypeOfAssistance.typeOfAssistance === null) {
                    this.proceedValidation.typeOfAssistance = true;


                    this.computedTypeOfAssistance.descriptionOfAssistance = '';
                    this.computedTypeOfAssistance.otherDescriptionOfAssistance = '';

                } else {
                    this.proceedValidation.typeOfAssistance = false;

                    this.computedTypeOfAssistance.descriptionOfAssistance = '';
                    this.computedTypeOfAssistance.otherDescriptionOfAssistance = '';
    
                }
            }
        },
        generate(){
            if(typeof this.computedTransactionReport.from_date == 'string' && this.computedTransactionReport.from_date == '' && !this.computedTransactionReport.from_date){
                this.proceedValidation.from_date = true;
            }else{
                this.proceedValidation.from_date = false;
            }
        
            if(typeof this.computedTransactionReport.to_date == 'string' && this.computedTransactionReport.to_date == '' && !this.computedTransactionReport.to_date){
                this.proceedValidation.to_date = true;
            }else{
                this.proceedValidation.to_date = false;
            }

            let proc = true;
            for(const [key,value] of Object.entries(this.proceedValidation)){
                if(this.proceedValidation[key]){
                    proc = false;
                    break;
                }
            }

            if(proc){
                this.computedTransactionReportTableLoader = true;
                this.$store.dispatch('generateReport',{'transactionReport':this.computedTransactionReport}).then(response => {
          
                    this.computedTransactionReportTableLoader = false;
                    
                });
            }

            this.resetPage = 0;
            
        },
        downloadAllData(){

            if(typeof this.computedTransactionReport.from_date == 'string' && this.computedTransactionReport.from_date == '' && !this.computedTransactionReport.from_date){
                this.proceedValidation.from_date = true;
            }else{
                this.proceedValidation.from_date = false;
            }
        
            if(typeof this.computedTransactionReport.to_date == 'string' && this.computedTransactionReport.to_date == '' && !this.computedTransactionReport.to_date){
                this.proceedValidation.to_date = true;
            }else{
                this.proceedValidation.to_date = false;
            }

            let proc = true;
            for(const [key,value] of Object.entries(this.proceedValidation)){
                
                if(key == 'from_date' || key == 'to_date'){
                    if(this.proceedValidation[key]){
                        proc = false;
                        break;
                    }
                }
                
            }


            if(proc){
                window.open('/api/download-all-report?data[transactionReport][from_date]='+this.computedTransactionReport.from_date+'&data[transactionReport][to_date]='+this.computedTransactionReport.to_date, "_self");
            }
            
        }   
    },
    data(){
        return{
            resetPage:0,
            selectedGeneratedReport:[],
            proceedValidation:{
                from_date:false,
                to_date:false,
                agency: false,
                agencyProgram: false,
                typeOfAssistance: false,
            }
        }
    },
    computed:{
        computedTypeOfAssistance:{
            get(){
                return this.$store.state.typeOfAssistance;
            },
            set(value){
                this.$store.commit('setTypeOfAssistance',{'typeOfAssistance':value});
            }
        },
        computedFormSeeder:{
            get(){
                return this.$store.state.formSeeder;
            },
        },
        computedTransactionReportLoader:{
            get(){
                return this.$store.state.transactionReportLoader;
            },
            set(value){
                this.$store.commit('setTransactionReportLoader',{'transactionReportLoader':value});
            }
        },
        computedTransactionReportTableLoader:{
            get(){
                return this.$store.state.transactionReportTableLoader;
            },
            set(value){
                this.$store.commit('setTransactionReportTableLoader',{'transactionReportTableLoader':value});
            }
        },
        computedGenerateReport:{
            get(){
                return this.$store.state.generateReport;
            },
            set(value){
                this.$store.commit('setGenerateReport',{'generateReport':value});
            }
        },
        computedTransactionReport:{
            get(){
                return this.$store.state.transactionReport;
            },
            set(value){
                this.$store.commit('setTransactionReport',{'transactionReport':value});
            }
        },
        computedHeaderText:{
            get(){
                this.$store.state.headerText;
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
    }
}


</script>