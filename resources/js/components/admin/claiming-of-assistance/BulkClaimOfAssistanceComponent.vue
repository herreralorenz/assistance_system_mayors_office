<template>
    <Loader2 v-if="this.computedBulkClaimAssistanceTableLoader"></Loader2>
    <div v-if="!this.computedBulkClaimAssistanceTableLoader" class="form-group bulk-claim py-4">
        <div class="container-fluid">
            <section class="transaction-to-claim">
                <h2 class="fw-bold">Select Transaction to Claim</h2>
                <section class="searchbar">
                    <div class="row">
                        <div class="col-12 position-relative">
                            <i class="fa fa-solid fa-magnifying-glass fa-lg icon"></i>
                            <select id="searchBy" :class="{ 'form-select': true, 'input-field': true }"
                                v-model="this.selectedSearchBy">
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
                            <input ref="clientBeneficiary" v-model="this.computedTransactionClaimSearch.client"
                                @keyup="searchClient()" id="clientBeneficiary"
                                :class="{ 'form-control': true, 'input-field': true }" type="text"
                                placeholder="Client">
                        </div>
                        <div class="col-12 position-relative" v-if="this.selectedSearchBy === '2'">
                            <i class="fa fa-file fa-lg icon"></i>
                            <input ref="transactionID" @keyup="this.searchTransactionID()"
                                v-model="this.computedTransactionClaimSearch.transactionID" id="transactionID"
                                :class="{ 'form-control': true, 'input-field': true }" type="text"
                                placeholder="Transaction ID">
                        </div>
                        <div class="col-6 position-relative" v-if="this.selectedSearchBy === '3'">
                            <label for="from" class="icon-date">From</label>
                            <input type="date" v-model="this.computedTransactionClaimSearch.date_from"
                                @change="this.searchDateRequest()" class="form-control" id="from"
                                :class="{ 'form-control': true, 'input-field-date': true }">
                        </div>
                        <div class="col-6 position-relative" v-if="this.selectedSearchBy === '3'">
                            <label for="to" class="icon-date">To</label>
                            <input type="date" v-model="this.computedTransactionClaimSearch.date_to"
                                @change="this.searchDateRequest()" class="form-control" id="to"
                                :class="{ 'form-control': true, 'input-field-date': true }">
                        </div>
                    </div>
                </section>
                <section class="table mt-3">
                    <!-- stateStorage="session" stateKey="to-claim-clients"-->
                    <LoaderSearch v-if="this.computedSearchLoader"></LoaderSearch>
                    <DataTable v-if="!this.computedSearchLoader" class="dataTable" filterDisplay="menu" paginator
                        :rows="5" v-model:selection="selectedTransactionsToClaim"
                        :value="this.computedBulkClaimAssistanceTable.transaction_claim[0]" dataKey="id"
                        tableStyle="min-width: 50rem">
                        <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                        <!-- <Column sortable field="id" header="ID"></Column> -->
                        <Column sortable field="approve_transaction.transaction.transaction_id" header="Transaction ID">
                        </Column>
                        <Column sortable field="approve_transaction.transaction.beneficiary_transaction.0.full_name"
                            header="Beneficiary Full Name"></Column>
                        <Column sortable field="approve_transaction.transaction.client.full_name"
                            header="Client Full Name"></Column>
                        <Column sortable field="approve_transaction.transaction.client.birthdate"
                            header="Client Birthdate"></Column>
                        <Column sortable field="approve_transaction.transaction.client.barangay"
                            header="Client Barangay"></Column>
                        <Column sortable field="approve_transaction.transaction.date_request" header="Transaction Date">
                        </Column>
                        <Column sortable field="approve_transaction.transaction.assistance_type.assistance_type"
                            header="Type of Assistance"></Column>
                        <Column sortable header="Status">
                            <template #body="slotProps">
                                <div :class="{ 'bg-success': slotProps.data.transaction_claim_status_condition[0].status === 'TO CLAIM' ? true : false, 'bg-danger': slotProps.data.transaction_claim_status_condition[0].status === 'UNCLAIMED' ? true : false, 'text-white': true, 'text-center': true }"
                                    :style="{ 'font-size': '0.75rem', 'width': '120px', 'border-radius': '10px' }">{{
                                        slotProps.data.transaction_claim_status_condition[0].status }}</div>
                            </template>
                        </Column>
                        <!-- <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
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
                    </Column> -->
                    </DataTable>
                </section>
                <button type="button" class="btn btn-primary w-100" @click="this.add()">ADD</button>
            </section>
            <section class="claim mt-3">
                <h2 class="fw-bold">Review Transactions to Claim</h2>
                <section class="table mt-3">
                    <!-- stateStorage="session" stateKey="claim-clients"-->
                    <DataTable class="dataTable" filterDisplay="menu" paginator :rows="5"
                        v-model:selection="selectedReviewTransactionsToClaim" :value="this.reviewTransactionsToClaim"
                        dataKey="id" tableStyle="min-width: 50rem">
                        <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                        <!-- <Column sortable field="id" header="ID"></Column> -->
                        <Column sortable field="approve_transaction.transaction.transaction_id" header="Transaction ID">
                        </Column>
                        <Column sortable field="approve_transaction.transaction.beneficiary_transaction.0.full_name"
                            header="Beneficiary Full Name"></Column>
                        <Column sortable field="approve_transaction.transaction.client.full_name"
                            header="Client Full Name">
                        </Column>
                        <Column sortable field="approve_transaction.transaction.client.birthdate"
                            header="Client Birthdate">
                        </Column>
                        <Column sortable field="approve_transaction.transaction.client.barangay"
                            header="Client Barangay"></Column>
                        <Column sortable field="approve_transaction.transaction.date_request" header="Transaction Date">
                        </Column>
                        <Column sortable field="approve_transaction.transaction.assistance_type.assistance_type"
                            header="Type of Assistance"></Column>
                        <Column sortable header="Status">
                            <template #body="slotProps">
                                <div :class="{ 'bg-success': slotProps.data.transaction_claim_status_condition[0].status === 'TO CLAIM' ? true : false, 'bg-danger': slotProps.data.transaction_claim_status_condition[0].status === 'UNCLAIMED' ? true : false, 'text-white': true, 'text-center': true }"
                                    :style="{ 'font-size': '0.75rem', 'width': '120px', 'border-radius': '10px' }">{{
                                        slotProps.data.transaction_claim_status_condition[0].status }}</div>
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
                </section>
                <button type="button" data-bs-toggle="modal" data-bs-target="#bulkClaimModal"
                    class="btn btn-success w-100">CLAIM</button>

            </section>
        </div>
    </div>

    <!-- Claim Modal -->
    <div class="modal fade" id="bulkClaimModal" ref="bulkClaimModal" tabindex="-1" aria-labelledby="bulkClaimModal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkClaimModalTitle">Are you sure to claim this transaction/s?</h5>
                    <button type="button" id="btn-close-claim" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="container py-4">
                    <div>
                        <i class="fa-solid fa-calendar fa-lg icon"></i>
                        <input id="claimDate"
                            :class="{ 'form-control': true, 'input-field': true, 'is-invalid': this.proceedValidation.claimDate }"
                            v-model="this.computedClaimant.claimDate" type="date" placeholder="Approve Date">
                    </div>
                    <!-- <div class="mt-3">
                        <i class="fa-solid fa-file fa-lg icon"></i>
                        <input id="bulkRemarks"
                            :class="{ 'form-control': true, 'input-field': true }"
                            v-model="this.computedClaimant.remarks" type="text" placeholder="Remarks to all">
                    </div> -->
                </div>
                <div class="modal-footer">
                    <button type="button" @click="this.claim()" class="btn btn-success">Submit</button>
                </div>
            </div>
        </div>
    </div>

</template>
<style scoped>
.transaction-to-claim,
.claim {
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
}


.bulk-claim {
    margin: 0;
    padding-left: 250px;
    overflow-y: scroll;
            
    height: -webkit-calc(100% - 50px);
    height: -moz-calc(100% - 50px);
    height: calc(100% - 50px);
    /* min-height: 100%;
    display: flex;
    flex-direction: column;  */
}


.icon {
    padding: 10px;
    margin-top: 15px;
    min-width: 40px;
    position: absolute;
}



.input-field-date {
    border-radius: 25px;
}



.input-field {
    padding-left: 35px;
    border-radius: 25px;
    height: 50px;
}

.table {
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    height: 100%;
    width: 100%;
    box-sizing: border-box;
    padding: 10px;
    transition: transform 0.3s ease;
}

button {
    border-radius: 25px;
}
</style>
<script>

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Loader2 from '../Loader2.vue';
import LoaderSearch from '../LoaderSearch.vue';
import auth from '../../../router/auth_roles_permissions.js';
export default {
    components: {
        DataTable,
        Column,
        Button,
        Loader2,
        LoaderSearch,
    },
    beforeMount() {
       
        this.computedHeaderText = 'Bulk Claim of Assistance';
        this.computedBulkClaimAssistanceTableLoader = true;
        this.$store.dispatch('fetchBulkClaimAssistanceTable').then(response => {
            this.computedBulkClaimAssistanceTableLoader = false;
        });
    },
    mounted() {
        
        window.scrollTo({
            top: 0,
            behavior: 'instant' // Optional: adds smooth scrolling effect
        });
    },
    beforeUnmount() {
        document.body.style.overflow = 'scroll';
        for(const [key,value] of Object.entries(this.computedTransactionClaimSearch)){
            this.computedTransactionClaimSearch[key] = '';
        }
    },
    methods: {
        deleteTransaction(id){
            this.reviewTransactionsMap.delete(id);
        },
        claim() {
            if(this.computedClaimant.claimDate === '' || this.computedClaimant.claimDate === null){
                this.proceedValidation.claimDate = true;
            }else{
                this.proceedValidation.claimDate = false;
                this.computedBulkClaimAssistanceTableLoader = true;
                if(this.selectedReviewTransactionsToClaim.length != 0){
                    document.getElementById('btn-close-claim').click();
                    this.$store.dispatch('bulkClaim',{claimDate:this.computedClaimant.claimDate, toClaim: this.selectedReviewTransactionsToClaim}).then(response => {
                        this.$store.dispatch('fetchBulkClaimAssistanceTable').then(response => {
                            this.computedBulkClaimAssistanceTableLoader = false;
                            this.selectedReviewTransactionsToClaim = [];
                            this.selectedTransactionsToClaim = [];
                            this.reviewTransactionsMap = new Map();
                        });
                    });
                }
                
            }
        },
       searchByClear() {
            for (const [key, value] of Object.entries(this.computedTransactionClaimSearch)) {

                this.computedTransactionClaimSearch[key] = '';
            }

        },
        searchDateRequest() {
            if (this.computedTransactionClaimSearch.date_from != '' && this.computedTransactionClaimSearch.date_to != '') {
                clearTimeout(this.timeout);

                this.timeout = setTimeout(() => {
                    this.computedSearchLoader = true;
                    this.$store.dispatch('searchBulkTransactionClaimDateRequest', { dateFrom: this.computedTransactionClaimSearch.date_from, dateTo: this.computedTransactionClaimSearch.date_to }).then(response => {
                        this.computedSearchLoader = false;
                        this.$store.commit('setBulkClaimAssistanceTable', { 'bulkClaimAssistanceTable': response.data });
                        console.log(this.computedBulkClaimAssistanceTable);
                    });

                }, 450);
            }
        },
        searchClient() {
            clearTimeout(this.timeout);

            this.timeout = setTimeout(() => {
                this.computedSearchLoader = true;
                this.$store.dispatch('searchBulkTransactionClaimClient', { client: this.computedTransactionClaimSearch.client }).then(response => {
                    this.computedSearchLoader = false;
                    this.$store.commit('setBulkClaimAssistanceTable', { 'bulkClaimAssistanceTable': response.data });
                });

            }, 450);
        },
        searchTransactionID() {
            clearTimeout(this.timeout);

            this.timeout = setTimeout(() => {
                this.computedSearchLoader = true;
                this.$store.dispatch('searchBulkTransactionClaimTransactionID', { transactionID: this.computedTransactionClaimSearch.transactionID }).then(response => {
                    this.computedSearchLoader = false;
                    this.$store.commit('setBulkClaimAssistanceTable', { 'bulkClaimAssistanceTable': response.data });
                });

            }, 450);
        },
        add() {
            // this.reviewTransactionsMap = new Map(this.selectedTransactionsToClaim.map(item => [item.id,item]));

            this.selectedTransactionsToClaim.forEach((val, index) => {
                this.reviewTransactionsMap.set(val.id, val);
            });
        },
        deleteRow(data) {

            this.reviewTransactionsMap.delete(data.id);
        },
        clearTable() {
            this.reviewTransactionsMap.clear();
        }
    },
    data() {
        return {
            selectedSearchBy: '1',
            searchBy: [
                { value: '1', text: 'Client' },
                { value: '2', text: 'Transaction ID' },
                { value: '3', text: 'From/To Date' }
            ],
            selectedTransactionsToClaim: [],
            selectedReviewTransactionsToClaim: [],
            reviewTransactionsMap: new Map(),
            timeout: null,
            proceedValidation: {
                claimDate: false,
            }

        }
    },
    computed: {
        computedClaimant: {
            get() {
                return this.$store.state.claimant;
            },
            set(value) {
                this.$store.commit('setClaimant', { 'claimant': value });
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
        computedTransactionClaimSearch: {
            get() {
                return this.$store.state.transactionClaimSearch;
            },
            set(value) {
                this.$store.commit('setTransactionClaimSearch', { 'transactionClaimSearch': value });
            }
        },
        computedBulkClaimAssistanceTable: {
            get() {
                return this.$store.state.bulkClaimAssistanceTable;
            },
            set(value) {
                this.$store.commit('setBulkClaimAssistanceTable', { 'bulkClaimAssistanceTable': value });
            }
        },
        computedBulkClaimAssistanceTableLoader: {
            get() {
                return this.$store.state.bulkClaimAssistanceTableLoader;
            },
            set(value) {
                this.$store.commit('setBulkClaimAssistanceTableLoader', { bulkClaimAssistanceTableLoader: value });
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
        reviewTransactionsToClaim() {
            // to cache when reviewtransactionmap changes
            return Array.from(this.reviewTransactionsMap.values());
        }
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