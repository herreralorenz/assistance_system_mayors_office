<template>
    <Toast></Toast>
    <Loader2 v-if="this.computedUserPermissionsLoader"></Loader2>
    <div v-else class="form-group user-roles py-2">
        <div class="container-fluid">
            <section class="Dashboard">
                <h2>Dashboard</h2>
                <hr>
                <div class="row">
                    <div class="col-4 my-2">
                        <div class="roles">
                            <h3>Dashboard</h3>
                            <hr>
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" role="switch" id="dashboard" @change="handleCheckBox('dashboard')"  :checked="this.computedUserPermissionsCheck.dashboard">
                                <label class="form-check-label mx-1 fs-6" for="requestForAssistance">Allow user to
                                    view dashboard</label>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="request-for-assistance">
                <h2>Request for Assistance</h2>
                <hr>
                <div class="row">
                    <div class="col-4 my-2">
                        <div class="roles">
                            <h3>Request for Assistance</h3>
                            <hr>
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" role="switch" id="requestForAssistance" @change="handleCheckBox('requestAssistance')"  :checked="this.computedUserPermissionsCheck.requestAssistance">
                                <label class="form-check-label mx-1 fs-6" for="requestForAssistance">Allow user to
                                    request for assistance</label>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="approval-of-assistance mt-3">
                <h2>Approval of Assistance</h2>
                <hr>
                <div class="row">
                    <div class="col-4 my-2">
                        <div class="roles">
                            <h3>Approve Assistance</h3>
                            <hr>
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" role="switch" id="viewApproveAssistance"  @change="handleCheckBox('viewApproveAssistance')" :checked="this.computedUserPermissionsCheck.viewApproveAssistance">
                                <label class="form-check-label mx-1 fs-6" for="viewApproveAssistance">Allow user to view approve
                                    assistance</label>
                            </div>
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" role="switch" id="editApproveAssistance"  @change="handleCheckBox('editApproveAssistance')" :checked="this.computedUserPermissionsCheck.editApproveAssistance">
                                <label class="form-check-label mx-1 fs-6" for="editApproveAssistance">Allow user to edit approve
                                    assistance</label>
                            </div>
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" role="switch" id="approveAssistance"  @change="handleCheckBox('approveAssistance')" :checked="this.computedUserPermissionsCheck.approveAssistance">
                                <label class="form-check-label mx-1 fs-6" for="approveAssistance">Allow user to approve
                                    assistance</label>
                            </div>
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" role="switch" id="declineAssistance"  @change="handleCheckBox('declineAssistance')" :checked="this.computedUserPermissionsCheck.declineAssistance">
                                <label class="form-check-label mx-1 fs-6" for="declineAssistance">Allow user to decline
                                    assistance</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 my-2">
                        <div class="roles">
                            <h3>Bulk Approve Assistance</h3>
                            <hr>
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    id="bulkApproveAssistance"  @change="handleCheckBox('bulkApproveAssistance')" :checked="this.computedUserPermissionsCheck.bulkApproveAssistance">
                                <label class="form-check-label mx-1 fs-6" for="bulkApproveAssistance">Allow user to
                                    approve assistance</label>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="claiming-of-assistance mt-3">
                <h2>Claiming of Assistance</h2>
                <hr>
                <div class="row">
                    <div class="col-4 my-2">
                        <div class="roles">
                            <h3>Claim Assistance</h3>
                            <hr>
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" role="switch" id="viewClaimAssistance"  @change="handleCheckBox('viewClaimAssistance')" :checked="this.computedUserPermissionsCheck.viewClaimAssistance">
                                <label class="form-check-label mx-1 fs-6" for="viewClaimAssistance">Allow user to view claim 
                                    assistance</label>
                            </div>
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" role="switch" id="editClaimAssistance"  @change="handleCheckBox('editClaimAssistance')" :checked="this.computedUserPermissionsCheck.editClaimAssistance">
                                <label class="form-check-label mx-1 fs-6" for="editClaimAssistance">Allow user to edit/update 
                                    assistance</label>
                            </div>
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" role="switch" id="claimAssistance"  @change="handleCheckBox('claimAssistance')" :checked="this.computedUserPermissionsCheck.claimAssistance">
                                <label class="form-check-label mx-1 fs-6" for="claimAssistance">Allow user to claim
                                    assistance</label>
                            </div>
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" role="switch" id="unclaimAssistance"  @change="handleCheckBox('unclaimAssistance')" :checked=" this.computedUserPermissionsCheck.unclaimAssistance">
                                <label class="form-check-label mx-1 fs-6" for="unclaimAssistance">Allow user to unclaim
                                    assistance</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 my-2">
                        <div class="roles">
                            <h3>Bulk Claim Assistance</h3>
                            <hr>
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" role="switch" id="bulkClaimAssistance"  @change="handleCheckBox('bulkClaimAssistance')" :checked="this.computedUserPermissionsCheck.bulkClaimAssistance">
                                <label class="form-check-label mx-1 fs-6" for="bulkClaimAssistance">Allow user to bulk claim
                                    assistance</label>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="client mt-3">
                <h2>Client</h2>
                <hr>
                <div class="row">
                    <div class="col-4 my-2 my-2">
                        <div class="roles">
                            <h3>Client Information</h3>
                            <hr>
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" role="switch" id="viewClientInformation"  @change="handleCheckBox('viewClientInformation')" :checked="this.computedUserPermissionsCheck.viewClientInformation">
                                <label class="form-check-label mx-1 fs-6" for="viewClientInformation">Allow user to view client information</label>
                            </div>
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" role="switch" id="editClientInformation" @change="handleCheckBox('editClientInformation')" :checked="this.computedUserPermissionsCheck.editClientInformation">
                                <label class="form-check-label mx-1 fs-6" for="editClientInformation">Allow user to edit client</label>
                            </div>
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" role="switch" id="deleteClientInformation" @change="handleCheckBox('deleteClientInformation')" :checked="this.computedUserPermissionsCheck.deleteClientInformation">
                                <label class="form-check-label mx-1 fs-6" for="deleteClientInformation">Allow user to delete client</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 my-2 my-2">
                        <div class="roles">
                            <h3>New Transaction</h3>
                            <hr>
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" role="switch" id="newTransaction" @change="handleCheckBox('newTransaction')" :checked="this.computedUserPermissionsCheck.newTransaction">
                                <label class="form-check-label mx-1 fs-6" for="newTransaction">Allow user to create a new transaction</label>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="transactions mt-3">
                <h2>Transactions</h2>
                <hr>
                <div class="row">
                    <div class="col-4 my-2 my-2">
                        <div class="roles">
                            <h3>Client Transactions</h3>
                            <hr>
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" role="switch" id="viewTransaction"  @change="handleCheckBox('viewTransaction')"  :checked="this.computedUserPermissionsCheck.viewTransaction">
                                <label class="form-check-label mx-1 fs-6" for="viewTransaction">Allow user to view client transaction</label>
                            </div>
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" role="switch" id="voidTransaction" @change="handleCheckBox('voidTransaction')"  :checked="this.computedUserPermissionsCheck.voidTransaction">
                                <label class="form-check-label mx-1 fs-6" for="voidTransaction">Allow user to void transactions</label>
                            </div>
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" role="switch" id="editBeneficiary" @change="handleCheckBox('editBeneficiary')"  :checked="this.computedUserPermissionsCheck.editBeneficiary">
                                <label class="form-check-label mx-1 fs-6" for="editBeneficiary">Allow user to edit beneficiary</label>
                            </div>
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" role="switch" id="addBeneficiary" @change="handleCheckBox('addBeneficiary')"  :checked="this.computedUserPermissionsCheck.addBeneficiary">
                                <label class="form-check-label mx-1 fs-6" for="addBeneficiary">Allow user to add beneficiary</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 my-2 my-2">
                        <div class="roles">
                            <h3>Transaction Report</h3>
                            <hr>
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" role="switch" id="transactionReport" @change="handleCheckBox('transactionReport')"  :checked="this.computedUserPermissionsCheck.transactionReport">
                                <label class="form-check-label mx-1 fs-6" for="transactionReport">Allow user to download all/claimed/unclaimed transaction report</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 my-2 my-2">
                        <div class="roles">
                            <h3>Bulk Printing of Receipt</h3>
                            <hr>
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" role="switch" id="bulkPrint" @change="handleCheckBox('bulkPrintingOfReceipt')"  :checked="this.computedUserPermissionsCheck.bulkPrintingOfReceipt">
                                <label class="form-check-label mx-1 fs-6" for="bulkPrint">Allow user to print bulk receipts (receiving copy/claimant's receipt)</label>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="sms mt-3">
                <h2>SMS</h2>
                <hr>
                <div class="row">
                    <div class="col-4 my-2 my-2">
                        <div class="roles">
                            <h3>Send SMS</h3>
                            <hr>
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" role="switch" id="sendSMS" @change="handleCheckBox('sendSMS')" :checked="this.computedUserPermissionsCheck.sendSMS">
                                <label class="form-check-label mx-1 fs-6" for="sendSMS">Allow user to send SMS</label>
                            </div>
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" role="switch" id="SMSMessage" @change="handleCheckBox('SMSMessage')" :checked="this.computedUserPermissionsCheck.SMSMessage">
                                <label class="form-check-label mx-1 fs-6" for="SMSMessage">Allow user to add/edit SMS message</label>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="maintenance mt-3">
                <h2>Maintenance</h2>
                <hr>
                <div class="row">
                    <div class="col-4 my-2 my-2">
                        <div class="roles">
                            <h3>Edit Return Days</h3>
                            <hr>
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" role="switch" id="returnDays" @change="handleCheckBox('returnDays')" :checked="this.computedUserPermissionsCheck.returnDays">
                                <label class="form-check-label mx-1 fs-6" for="returnDays">Allow user to edit return days</label>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <button class="btn bg-success text-white w-100 d-flex justify-content-center align-items-center mt-3"
            style="height: 50px; font-size: 20px;" @click="save()">Save</button>
        </div>
    </div>
</template>
<script>
import Loader2 from '../Loader2.vue'
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import auth from '../../../router/auth_roles_permissions.js';

export default{
    components:{
        Loader2,
        Toast,
    },
    beforeMount(){
        this.computedHeaderText = 'Add Roles';
        this.computedUserPermissionsLoader = true;



        this.$store.dispatch('fetchUserPermissions',{'id':this.$route.params.id}).then(request => {
            for(const [key2,value] of Object.entries(this.computedUserPermissionsCheck)){
                this.computedUserPermissionsCheck[key2] = false;
            }
    
            for(let key1 in this.computedUserPermissions.user_permissions){
                for(const [key2,value] of Object.entries(this.computedUserPermissionsCheck)){
                    if(this.computedUserPermissions.user_permissions[key1].name === key2){
                        this.computedUserPermissionsCheck[key2]= true;
                    }
                }
            }
            this.computedUserPermissionsLoader = false;
        });
    },
    mounted(){
      document.body.style.overflow = 'hidden';
      this.toast = useToast();
    },
    beforeUnmount(){
        document.body.style.overflow = 'auto';
    },
    methods:{
        handleCheckBox(checkbox){

            this.computedUserPermissionsCheck[checkbox] =  !this.computedUserPermissionsCheck[checkbox];
           
            const buttonDependent = {
                'viewApproveAssistance': ['approveAssistance','editApproveAssistance','declineAssistance','bulkApproveAssistance'],
                'approveAssistance':['viewApproveAssistance'],
                'editApproveAssistance':['viewApproveAssistance'],
                'declineAssistance':['viewApproveAssistance'],
                'bulkApproveAssistance':['viewApproveAssistance'],
                'viewClaimAssistance': ['claimAssistance','editClaimAssistance','unclaimAssistance','claimAssistance','bulkClaimAssistance'],
                'claimAssistance':['viewClaimAssistance'],
                'editClaimAssistance':['viewClaimAssistance'],
                'unclaimAssistance':['viewClaimAssistance'],
                'claimAssistance':['viewClaimAssistance'],
                'bulkClaimAssistance':['viewClaimAssistance'],
                'viewClientInformation':['editClientInformation','deleteClientInformation','newTransaction'],
                'editClientInformation':['viewClientInformation'],
                'deleteClientInformation':['viewClientInformation'],
                'newTransaction':['viewClientInformation'],
                'viewTransaction':['voidTransaction','editBeneficiary','addBeneficiary','transactionReport','bulkPrintingOfReceipt'],
                'voidTransaction':['viewTransaction'],
                'editBeneficiary':['viewTransaction'],
                'addBeneficiary':['viewTransaction'],
                'transactionReport':['viewTransaction'],
                'bulkPrintingOfReceipt':['viewTransaction'],
                'SMSMessage':['SMSMessage'],
                'sendSMS':['sendSMS'],
                'dashboard':['dashboard']


            };

            for(const [key,value] of Object.entries(buttonDependent)){
                if(checkbox === key && key.toString().includes('view')){
                    for(const value2 in value){
                        if(!this.computedUserPermissionsCheck[checkbox]){
                            this.computedUserPermissionsCheck[value[value2]] = false;
                        }
                    }
                }else if(checkbox === key){
                    for(const value2 in value){
                        if(this.computedUserPermissionsCheck[checkbox]){
                            this.computedUserPermissionsCheck[value[value2]] = true;
                        }
                    }
                }
            }
        },
        save(){
            this.computedUserPermissionsLoader = true;
            this.$store.dispatch('setUserPermissions',{'id':this.$route.params.id,'permissions':this.computedUserPermissionsCheck}).then(response => {
                this.computedUserPermissionsLoader = false;
                this.toast.add({ severity: 'success', summary: 'Success', detail: 'Roles successfully updated.', life: 5000 });
            });
        }
    },
    data(){
        return{
           toast:null,
           
        }
        
    },
    computed:{
        computedUserPermissionsCheck:{
            get(){
                return this.$store.state.userPermissionsCheck;
            },
            set(value){
                this.$store.commit('setUserPermissionsCheck',{'userPermissionsCheck':value});
            },
        },
        computedUserPermissions:{
            get(){
                return this.$store.state.userPermissions;
            },
            set(value){

            },
        },
        computedUserPermissionsLoader:{
            get(){
                return this.$store.state.userPermissionsLoader;
            },
            set(value){
                this.$store.commit('setUserPermissionsLoader',{'userPermissionsLoader':value});
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
<style scoped>
.form-switch .form-check-input {
    width: 3.25em;
    height: 1.5em;
}

.user-roles {
    padding-left: 250px;
    overflow-y: scroll;
    height: -webkit-calc(100% - 50px);
    height: -moz-calc(100% - 50px);
    height: calc(100% - 50px);
}



.roles,
.request-for-assistance,
.approval-of-assistance,
.claiming-of-assistance,
.client,
.transactions,
.maintenance {
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    box-sizing: border-box;
    padding: 10px;
}
</style>