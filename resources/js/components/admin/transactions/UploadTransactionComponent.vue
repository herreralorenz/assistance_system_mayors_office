<template>
    <div class="form-group upload-transactions py-4">
        <div class="container-fluid">
            <section class="upload-file">
                <h2>Upload Transactions</h2>
                <hr>
                <input class="form-control form-control-lg mt-2" id="formFileLg" type="file"
                @change="(e) => this.beneficiaryImageUpload(e)" ref="beneficiaryImageUpload">
            </section>
            <section class="table mt-3">
                <h2>Without Transcations (30 Days)</h2>
                <hr>
                <DataTable class="dataTable" filterDisplay="menu" paginator :rows="5" stateStorage="session" stateKey="print-clients" v-model:selection="selectedReviewTransactionsToPrint" :value="this.reviewTransactionsToPrint" dataKey="id" tableStyle="min-width: 50rem">
                    <Column selectionMode="multiple" headerStyle="width: 3rem"> 
                    </Column>
                    <Column sortable field="id" header="ID"></Column>
                    <Column sortable field="transactionID" header="Transaction ID"></Column>
                    <Column sortable field="beneficiaryFullName" header="Beneficiary Full Name"></Column>
                    <Column sortable field="clientFullName" header="Client Full Name"></Column>
                    <Column sortable field="clientBirthdate" header="Client Birthdate"></Column>
                    <Column sortable field="clientBarangay" header="Client Barangay"></Column>
                    <Column sortable field="transactionDate" header="Transaction Date"></Column>
                    <Column sortable field="typeOfAssistance" header="Type of Assistance"></Column>
                    <Column sortable field="status" header="Status">
                        <template #body="slotProps">
                            <div :style="{ 'font-size': '0.75rem', 'width':'120px', 'border-radius':'10px'}"  :class="{'bg-success':slotProps.data.status === 'CLAIMED' ? true : false,'bg-secondary':slotProps.data.status === 'TO CLAIM' ? true : false, 'text-white': true, 'text-center':true }">{{ slotProps.data.status }}</div>
                        </template>
                    </Column>
                    <Column header="Actions">
                        <template #body="{ data }">
                            <Button  label="Delete" icon="pi pi-trash" severity="danger" @click="deleteRow(data)" rounded></Button>
                        </template>
                    </Column>
                </DataTable>
            </section>
            <button type="button" class="btn btn-success w-100" @click="this.add()" rounded>TRANSACT</button>
            <section class="table mt-3">
                <h2>With Transcations (30 Days)</h2>
                <hr>
                <DataTable class="dataTable" filterDisplay="menu" paginator :rows="5" stateStorage="session" stateKey="print-clients" v-model:selection="selectedReviewTransactionsToPrint" :value="this.reviewTransactionsToPrint" dataKey="id" tableStyle="min-width: 50rem">
                    <Column selectionMode="multiple" headerStyle="width: 3rem"> 
                    </Column>
                    <Column sortable field="id" header="ID"></Column>
                    <Column sortable field="transactionID" header="Transaction ID"></Column>
                    <Column sortable field="beneficiaryFullName" header="Beneficiary Full Name"></Column>
                    <Column sortable field="clientFullName" header="Client Full Name"></Column>
                    <Column sortable field="clientBirthdate" header="Client Birthdate"></Column>
                    <Column sortable field="clientBarangay" header="Client Barangay"></Column>
                    <Column sortable field="transactionDate" header="Transaction Date"></Column>
                    <Column sortable field="typeOfAssistance" header="Type of Assistance"></Column>
                    <Column sortable field="status" header="Status">
                        <template #body="slotProps">
                            <div :style="{ 'font-size': '0.75rem', 'width':'120px', 'border-radius':'10px'}"  :class="{'bg-success':slotProps.data.status === 'CLAIMED' ? true : false,'bg-secondary':slotProps.data.status === 'TO CLAIM' ? true : false, 'text-white': true, 'text-center':true }">{{ slotProps.data.status }}</div>
                        </template>
                    </Column>
                    <Column header="Actions">
                        <template #body="{ data }">
                            <Button  label="Delete" icon="pi pi-trash" severity="danger" @click="deleteRow(data)" rounded></Button>
                        </template>
                    </Column>
                </DataTable>
            </section>
            <button type="button" class="btn btn-success w-100" @click="this.add()" rounded>TRANSACT</button>
        </div>
    </div>
</template>
<style scoped>
.upload-transactions{
    margin: 0;
    padding-left: 250px;
    min-height: 100%;
    display: flex;
    flex-direction: column;   
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

button{
    border-radius: 25px;
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
import auth from '../../../router/auth_roles_permissions.js';

export default{
    components:{
        DataTable,
        Column,
        Button,
    },
    beforeMount(){

    },
    mounted(){
        this.computedHeaderText = 'Upload Transactions';
    },
    beforeUnmount(){

    },
    computed:{
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