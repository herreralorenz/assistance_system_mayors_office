<template>
    <Loader2 v-if="this.computedClientTransactionAddBeneficiaryLoader"></Loader2>
    <Dialog v-model:visible="dialogVisibleBeneficiary" modal header="⚠️Transaction Found⚠️">
        <div>
           
        </div>
    </Dialog>
    <div class="form-group edit-beneficiary py-4" v-if="!this.computedClientTransactionAddBeneficiaryLoader">
        <div class="container-fluid">
            <section class="edit-beneficiary-information">
                <h1>Beneficiary</h1>
                <hr>
                <div class="row">
                    <div class="col-lg-12">
                        <label for="claimantRelationship">Client Relationship to Beneficiary</label>
                        <input v-on:keyup="this.validateFields('client_relationship')" id="client_relationship"  :class="{ 'form-control': true, 'is-invalid': proceedValidation.client_relationship }"  v-model="this.computedClient.relationship">
                    </div>
                    <div class="col-lg-3">
                        <label for="beneficiary_last_name">Last Name</label>
                        <AutoComplete :class="{'d-flex':true, 'invalid-field':this.proceedValidation.beneficiary_last_name}" :suggestions="this.computedAutoCompleteFullNameBeneficiaryWithRelationship" v-on:keyup="this.validateFields('beneficiary_last_name')" v-model="this.computedBeneficiary.last_name" :loading="this.autoCompleteLoader.beneficiary_last_name"  @item-select="this.onItemSelectBeneficiary" @hide="onHide">
                            <template #option="{ option }">
                                <div class="custom-item">
                                    <span :data-value="option.id">{{ option.first_name+" "+(option?.middle_name ?? "")+" "+option.last_name+" "+(option.suffix?.suffix ?? "")+" ("+option.birthdate+")" }}</span> 
                                </div>
                            </template>
                        </Autocomplete>
                    </div>
                    <div class="col-lg-3">
                        <label for="beneficiary_first_name">First Name</label>
                        <AutoComplete :class="{'d-flex':true, 'invalid-field':this.proceedValidation.beneficiary_first_name}" :suggestions="this.computedAutoCompleteFullNameBeneficiaryWithRelationship" v-on:keyup="this.validateFields('beneficiary_first_name')" v-model="this.computedBeneficiary.first_name" :loading="this.autoCompleteLoader.beneficiary_first_name"  @item-select="this.onItemSelectBeneficiary" @hide="onHide">
                            <template #option="{ option }">
                                <div class="custom-item">
                                    <span :data-value="option.id">{{ option.first_name+" "+(option?.middle_name ?? "")+" "+option.last_name+" "+(option.suffix?.suffix ?? "")+" ("+option.birthdate+")" }}</span> 
                                </div>
                            </template>
                        </Autocomplete>
                    </div>
                    <div class="col-lg-3">
                        <label for="beneficiary_middle_name">Middle Name</label>
                        <AutoComplete :class="{'d-flex':true, 'invalid-field':this.proceedValidation.beneficiary_middle_name}" :suggestions="this.computedAutoCompleteFullNameBeneficiaryWithRelationship" v-on:keyup="this.validateFields('beneficiary_middle_name')" v-model="this.computedBeneficiary.middle_name" :loading="this.autoCompleteLoader.beneficiary_middle_name"  @item-select="this.onItemSelectBeneficiary" @hide="onHide">
                            <template #option="{ option }">
                                <div class="custom-item">
                                    <span :data-value="option.id">{{ option.first_name+" "+(option?.middle_name ?? "")+" "+option.last_name+" "+(option.suffix?.suffix ?? "")+" ("+option.birthdate+")" }}</span> 
                                </div>
                            </template>
                        </Autocomplete>
                    </div>
                    <div class="col-lg-3">
                        <label for="beneficiary_suffix">Suffix</label>
                        <select id="beneficiary_suffix" v-model="this.computedBeneficiary.suffix"
                            class="custom-select form-select" v-on:keyup="validateFields('beneficiary_suffix')">
                            <option selected value=""></option>
                            <option :value="option.id" :key="option.id" v-for="(option, index) in this.computedFormSeeder.suffix">{{ option.suffix }}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <label for="beneficiary_birthdate">Birthdate</label>
                        <input type="date" v-model="this.computedBeneficiary.birthdate"
                            :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.beneficiary_birthdate }"
                            @change="validateFields('beneficiary_birthdate')" id="beneficiary_birthdate">
                    </div>
                    <div class="col-lg-1">
                        <label for="beneficiary_age">Age</label>
                        <input :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.beneficiary_age }"
                            v-model="this.computedBeneficiary.age" id="beneficiary_age" type="text" disabled>
                    </div>
                    <div class="col-lg-4">
                        <label for="beneficiary_sex">Sex</label>
                        <select id="beneficiary_sex" @change="validateFields('beneficiary_sex')"
                            v-model="this.computedBeneficiary.sex"
                            :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.beneficiary_sex }">
                            <option value="" selected></option>
                            <option :value="option.id" :key="option.id" v-for="(option,index) in this.computedFormSeeder.sex">{{ option.sex }}</option>
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label for="beneficiary_civil_status">Civil Status</label>
                        <select id="beneficiary_civil_status" v-model="this.computedBeneficiary.civil_status"
                            @change="validateFields('beneficiary_civil_status')"
                            :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.beneficiary_civil_status }">
                            <option value="" selected></option>
                            <option :value="option.id" :key="option.id" v-for="(option,index) in this.computedFormSeeder.civil_status">{{ option.civil_status }}</option>
                        </select>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <label for="beneficiary_street">Street/Purok</label>
                        <input class="form-control" v-on:keyup="validateFields('beneficiary_street')"
                            v-model="this.computedBeneficiary.street" id="beneficiary_street" type="text">
                    </div>
                    <div class="col-lg-2">
                        <label for="beneficiary_barangay">Barangay</label>
                        <select id="beneficiary_barangay" v-model="this.computedBeneficiary.barangay"
                            @change="validateFields('beneficiary_barangay')"
                            :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.beneficiary_barangay }">
                            <option value="" selected></option>
                            <option  v-for="(option,index) in this.computedFormSeeder.address_metadata[0].address_metadata['4A']['province_list']['CAVITE']['municipality_list']['GENERAL TRIAS CITY']['barangay_list']" :value="index" :key="index">{{ option }}</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label for="beneficiary_city">City</label>
                        <input :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.beneficiary_city }"
                            v-model="this.computedBeneficiary.city" id="beneficiary_city" type="text" disabled>
                    </div>
                    <div class="col-lg-2">
                        <label for="beneficiary_province">Province</label>
                        <input :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.beneficiary_province }"
                            v-model="this.computedBeneficiary.province" id="beneficiary_province" type="text" disabled>
                    </div>
                    <div class="col-lg-2">
                        <input @click="this.beneficiaryRegisteredVoter()"
                            :checked="!this.beneficiary_precint_disabled" type="checkbox"
                            id="beneficiaryCheckIsVoter" aria-label="Checkbox for following text input">
                        <label class="form-check-label" for="beneficiaryCheckIsVoter">Registered Voter?</label>
                        <input :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.beneficiary_precint }"
                            v-model="this.computedBeneficiary.precint"
                            v-on:keyup="this.validateFields('beneficiary_precintNumber')" id="beneficiaryIsVoter"
                            placeholder="Precint Number" type="text" :disabled="this.beneficiary_precint_disabled">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <label for="beneficiary_contact_number">Contact Number</label>
                        <input
                            :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.beneficiary_contact_number }"
                            v-on:keyup="validateFields('beneficiary_contact_number')"
                            v-model="this.computedBeneficiary.contact_number" id="beneficiary_contact_number" type="text">
                    </div>
                    <div class="col-lg-3">
                        <label for="beneficiary_id_type">ID Type</label>
                        <select @change="handleBeneficiaryIDType(this.computedBeneficiary.id_type)"
                            id="beneficiary_id_type" v-model="this.computedBeneficiary.id_type"
                            class="custom-select form-select">
                            <option value="" selected></option>
                            <option v-for="(option,index) in this.computedFormSeeder.id_type" :value="option.id" :key="option.id">{{ option.id_type }}</option>
                            <option value="OTHER">OTHER ID</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label for="beneficiaryOtherId">Other ID</label>
                        <input
                            :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.beneficiary_other_id_type }"
                            id="beneficiaryOtherId" v-model="computedBeneficiary.other_id_type" type="text"
                            v-on:keyup="validateFields('beneficiaryOtherId')"
                            :disabled="this.beneficiary_other_id_type_disabled">
                    </div>
                    <div class="col-lg-3">
                        <label for="beneficiary_id_number">ID Number</label>
                        <input :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.beneficiary_id_number }"
                            v-model="this.computedBeneficiary.id_number" id="beneficiary_id_number"
                            v-on:keyup="validateFields('beneficiary_id_number')" type="text"
                            :disabled="this.beneficiary_id_number_disabled">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <label for="beneficiary_occupation">Occupation</label>
                        <input class="form-control" v-on:keyup="this.validateFields('beneficiary_occupation')"
                            v-model="this.computedBeneficiary.occupation" id="beneficiary_occupation" type="text">
                    </div>
                    <div class="col-lg-6">
                        <label for="beneficiary_monthly_income">Monthly Income</label>
                        <input
                            :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.beneficiary_monthly_income }"
                            v-on:keyup="validateFields('beneficiary_monthly_income')"
                            v-model="this.computedBeneficiary.monthly_income" id="beneficiary_monthly_income" type="text">
                    </div>
                </div>
                <div>
                    <button v-if="this.uploadCaptureButtonVisible" type="button" class="btn btn-outline-success mt-2" @click="this.showBeneficiaryImageModal()">
                        <i class="fa fa-camera"></i>UPLOAD / CAPTURE IMAGE
                    </button>
                </div>
            </section>
            <section class="mt-3">
                <button type="button" class="btn btn-success bg-gradient w-100 h-10"
                    @click="validateFields('add')">ADD</button>
            </section>
        </div>
        <BeneficiaryImageModalComponent ref="beneficiaryImageModal" v-model:visible="this.clientImageModalVisible"></BeneficiaryImageModalComponent>
    </div>
</template>
<style scoped>
input,
select,
textarea,
button {
    border-radius: 25px;

}

::v-deep(.p-autocomplete .p-autocomplete-input:hover) {
    border-radius: 25px !important;
    display: block !important;
    width: 100% !important;
    padding: 0.375rem 0.75rem; 
    font-size: 1rem; 
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    background-color: rgb(248,250,252);
    background-clip: padding-box;
    border: 1px solid #ced4da;

}


/** for normal input */
::v-deep(.p-autocomplete .p-autocomplete-input) {
    border-radius: 25px !important;
    display: block !important;
    width: 100% !important;
    padding: 0.375rem 0.75rem; 
    font-size: 1rem; 
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    background-color: rgb(248,250,252);
    background-clip: padding-box;
    border: 1px solid #ced4da;

}

/** for normal input */
::v-deep(.p-autocomplete-input:focus) {
    color: #212529 !important;
    background-color: #fff !important;
    border-color: #86b7fe !important;
    outline: 0 !important;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
}



/* * for invalid */
::v-deep(.invalid-field .p-autocomplete-input:focus) {
    color: #212529 !important;
    background-color: #fff !important;
    border-color: #dc3545 !important;
    outline: 0 !important;
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important;

}

/* * for invalid */
::v-deep(.invalid-field .p-autocomplete-input:hover ) {
    color: #212529 !important;
    background-color: #fff !important;
    border-color: #dc3545 !important;
    outline: 0 !important;
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important;

}

/**for invalid */
::v-deep(.invalid-field .p-autocomplete-input) {
    display: block !important;
    width: 100% !important;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    border-radius: 25px !important;
    border-color: #dc3545;
    background-color: #ffffff;
    padding-right: 2.25rem;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 1rem;
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important;
}

.edit-beneficiary {
    margin: 0;
    padding-left: 250px;
    overflow-y: auto;
    height: -webkit-calc(100% - 50px);
    height: -moz-calc(100% - 50px);
    height: calc(100% - 50px);
}

.edit-beneficiary-information {
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
}
</style>
<script>
import BeneficiaryImageModalComponent from '../transactions/BeneficiaryImageModalComponent.vue'
import Loader2 from '../Loader2.vue';
import auth from '../../../router/auth_roles_permissions.js';
import AutoComplete from 'primevue/autocomplete';
import Dialog from 'primevue/dialog';
import { toHandlers } from 'vue';

export default {
    components: {
        BeneficiaryImageModalComponent,
        Loader2,
        AutoComplete,
        Dialog,
    },
    beforeMount() {
        this.computedHeaderText = 'Beneficiary Transactions > Transaction Details > Add Beneficiary';
        this.computedClientTransactionAddBeneficiaryLoader = true;

        for(const[key,value] of Object.entries(this.computedBeneficiary)){
            if(key != 'region' && key != 'province' && key != 'city'){
                this.computedBeneficiary[key] = ''
            }
        }

        
        for(const[key,value] of Object.entries(this.computedClient)){
            if(key != 'region' && key != 'province' && key != 'barangay' && key != 'city'){
                this.computedClient[key] = ''
            }
        }

        this.$store.dispatch('fetchSeeders').then(response1 => {
            this.computedClientTransactionAddBeneficiaryLoader = false;
        });
        
    },
    mounted() {
        document.body.style.overflow = 'hidden';
        window.scrollTo({
            top: 0,
            behavior: 'instant' // Optional: adds smooth scrolling effect
        });

    },
    beforeUnmount() {
        document.body.style.overflow = 'auto';
    },
    methods: {
        onHide(event){

        },
        onItemSelectBeneficiary(event){
            this.computedClient.relationship = event.value?.relationship_to_client?.relationship?.relationship;

            this.computedBeneficiary.last_name = event.value.last_name;
            this.computedBeneficiary.first_name = event.value.first_name;
            this.computedBeneficiary.middle_name = event.value?.middle_name ?? "";
            this.computedBeneficiary.suffix = event.value?.suffix?.id ?? "";
            this.computedBeneficiary.birthdate = event.value.birthdate;
            this.computedBeneficiary.age = event.value.age;
            this.computedBeneficiary.sex = event.value.sex.id;
            this.computedBeneficiary.civil_status = event.value.civil_status.id;
            this.computedBeneficiary.street = event.value?.street ?? "";
            this.computedBeneficiary.barangay = event.value.barangay_id;
            this.computedBeneficiary.city = event.value.city;
            this.computedBeneficiary.province = event.value.province;

            if(event.value?.precint?.precint){
                this.beneficiary_precint_disabled = false;
                this.computedBeneficiary.precint = event.value?.precint?.precint;
            }else{
                this.beneficiary_precint_disabled = true;
                this.computedBeneficiary.precint = "";
            }

            this.computedBeneficiary.contact_number = event.value.contact_number[0].contact_number;

            if(event.value.beneficiary_identification?.[0]?.id_type_id){
                this.computedBeneficiary.id_type = event.value?.beneficiary_identification?.[0].id_type_id;
                this.computedBeneficiary.id_number = event.value?.beneficiary_identification?.[0]?.id_number;
                this.beneficiary_id_number_disabled = false;
                this.computedBeneficiary.other_id_type = "";
            }else if(event.value.beneficiary_identification?.[0]?.other_id_type_id){
                this.computedBeneficiary.id_type = 'OTHER';
                this.computedBeneficiary.other_id_type = event.value?.beneficiary_identification?.[0]?.other_identification_type?.other_id_type;
                this.computedBeneficiary.id_number = event.value?.beneficiary_identification?.[0]?.id_number;
                this.beneficiary_id_number_disabled = false;
                this.beneficiary_other_id_type_disabled = false
            }else{
                this.computedBeneficiary.id_type = '';
                this.computedBeneficiary.other_id_type = '';
                this.computedBeneficiary.id_number = '';
                this.beneficiary_id_number_disabled = true;
                this.beneficiary_other_id_type_disabled = true;
            }

            if(event.value?.beneficiary_occupation){
                this.computedBeneficiary.occupation = event.value?.beneficiary_occupation?.[0]?.occupation;
                this.computedBeneficiary.monthly_income = event.value?.beneficiary_occupation?.[0]?.pivot?.monthly_income;
            }

            this.computedBeneficiary.beneficiary_image = event?.image?.[0]?.base64 ?? "";
            

         
        },
        showBeneficiaryImageModal() {
            this.clientImageModalVisible = true;

            this.$refs.beneficiaryImageModal.loadBeneficiaryClientImage();
           
        },
        validateFields(textfield) {


            for (let key in this.computedBeneficiary) {
                if (this.computedBeneficiary.hasOwnProperty(key)) {
                    if (key != 'age' && key != 'beneficiary_image'  && key != 'sex' && key != 'suffix' && key != 'civil_status' && key != 'barangay' && key != 'id_type' && key != 'monthly_income') {
                        this.computedBeneficiary[key] = this.computedBeneficiary[key].toUpperCase();
                    }
                }
            }

            const beneficiaryValidateFields = {
                'first_name':['required'],
                'last_name':['required'],
                'middle_name':['not_required'],
                'suffix':['not_required'],
                'birthdate':['calculateBeneficiaryAge'],
                'age':['required'],
                'sex':['required'],
                'civil_status':['required'],
                'street':['not_required'],
                'region':['required'],
                'barangay':['required'],
                'city':['required'],
                'province':['required'],
                'barangay':['required'],
                'city':['required'],
                'province':['required'],
                'precint':['checkPrecint'],
                'contact_number':['handleBeneficiaryContactNumber'],
                'id_type':['not_required'],
                'other_id_type':['checkBeneficiaryID'],
                'id_number':['checkBeneficiaryID'],
                'occupation':['not_required'],
                'monthly_income':['checkMonthlyIncome'],
                'beneficiary_image':['not_required'],
            };

            if(textfield === 'add'){
                // console.log(this.proceedValidation);
                for(const [key,value] of Object.entries(this.computedBeneficiary)){
                        if(beneficiaryValidateFields[key][0] === 'required' && this.computedBeneficiary[key] != '' && this.computedBeneficiary[key]){
                            this.proceedValidation[`beneficiary_${key}`] = false;
                        }else if(beneficiaryValidateFields[key][0] === 'required' && this.computedBeneficiary[key] == '' && !this.computedBeneficiary[key]){
                            this.proceedValidation[`beneficiary_${key}`] = true;
                        }else if(beneficiaryValidateFields[key][0] === 'not_required'){
                            this.proceedValidation[`beneficiary_${key}`] = false;
                        }else if(beneficiaryValidateFields[key][0] !== 'required' && beneficiaryValidateFields[key][0] !== 'not_required'){
                            this[beneficiaryValidateFields[key][0]](this.computedBeneficiary[key],`beneficiary_${key}`);
                        }
                }

                if( typeof this.computedClient.relationship === "string" && this.computedClient.relationship && this.computedClient != ''){
                    this.proceedValidation.client_relationship = false;
                }else if(typeof this.computedClient.relationship === "string" && !this.computedClient.relationship && !this.computedClient != ''){
                    this.proceedValidation.client_relationship = true;
                }

                let proc = true;
                for(const [key,value] of Object.entries(this.proceedValidation)){
                    if(this.proceedValidation[key] === true){
                        proc = false;
                        break;
                    }
                }
                

                if(proc && textfield === 'add'){
                    this.computedClientTransactionAddBeneficiaryLoader = true;
                    this.$store.dispatch('addClientTransactionBeneficiary',{'beneficiary':this.computedBeneficiary,'client':this.computedClient,'id':this.$route.params.id,'id2':this.$route.params.id2}).then(response => {
                        this.computedClientTransactionAddBeneficiaryLoader = false;
                        this.$router.push({name:'clientTransaction',params:{'id':this.$route.params.id}});
                    });
                }


            }else{

                if(textfield === 'client_relationship'){
                    if(typeof this.computedClient.relationship === "string" && this.computedClient.relationship && this.computedClient != ''){
                        this.proceedValidation.client_relationship = false;
                    }else{
                        this.proceedValidation.client_relationship = true;
                    }
                }else{
                    beneficiaryValidateFields[textfield.replace(/^beneficiary_/, "")].forEach(element => {
                        if(textfield.replace(/^beneficiary_/, "") === 'first_name' || textfield.replace(/^beneficiary_/, "") === 'middle_name' || textfield.replace(/^beneficiary_/, "") === 'last_name'){
                            if(element === 'required'){
                                if(this.computedBeneficiary[textfield.replace(/^beneficiary_/, "")] && this.computedBeneficiary[textfield.replace(/^beneficiary_/, "")] != ''){
                                    this.proceedValidation[textfield] = false
                                    clearTimeout(this.timeout);
                                    this.timeout = setTimeout(() => {
                                        this.$store.dispatch('autoCompleteFullNameBeneficiaryWithRelationship',{'beneficiary':this.computedBeneficiary,'id2':this.$route.params.id2}).then(response =>{
                                            this.autoCompleteLoader[textfield] = false;
                                        });
                                    });
                                    
                                }else{
                                    this.proceedValidation[textfield] = true;
                                }
                            }else{
                                this.proceedValidation[textfield] = false;
                               
                                clearTimeout(this.timeout);
                                
                                this.timeout = setTimeout(() => {
                                    this.$store.dispatch('autoCompleteFullNameBeneficiaryWithRelationship',{'beneficiary':this.computedBeneficiary,'id2':this.$route.params.id2}).then(response =>{
                                        this.autoCompleteLoader[textfield] = false;
                                    });
                                });
                            }
                        }else if(element === 'required'){
                            if(this.computedBeneficiary[textfield.replace(/^beneficiary_/, "")] && this.computedBeneficiary[textfield.replace(/^beneficiary_/, "")] != ''){
                                this.proceedValidation[textfield] = false
                            }else{
                                this.proceedValidation[textfield] = true;
                            }
                        }else if(element === 'not_required'){
                            this.proceedValidation[textfield] = false
                        }else{
                            this[element](this.computedBeneficiary[textfield.replace(/^beneficiary_/, "")],textfield);
                        }
                    });

                }

            }


    


        },
        checkMonthlyIncome(value,textfield){
            if(/^\d*(\.\d{1,2})?$/.test(value)){
                this.proceedValidation[textfield] = false;
            }else{
                this.proceedValidation[textfield] = true;
            }
        },
        checkPrecint(value,textfield){

            if((!this[textfield+'_disabled'] && value != '' && value) || (this[textfield+'_disabled'] && value === '')){
                this.proceedValidation[textfield] = false;
            }else{
                this.proceedValidation[textfield] = true;
            }
        },
        checkBeneficiaryID(value,textfield){
            if((!this[textfield+'_disabled'] && value != '' && value)  || (this[textfield+'_disabled'] && value === '')){
                this.proceedValidation[textfield] = false;
            }else{
                this.proceedValidation[textfield] = true;
            }
        },
        beneficiaryRegisteredVoter() {
            this.beneficiary_precint_disabled = !this.beneficiary_precint_disabled;
            this.proceedValidation['beneficiary_precint'] = false;
            if (!this.beneficiary_precint_disabled) {
                this.computedBeneficiary.precint = '';
            } 
        },
        handleBeneficiaryContactNumber(contactNumber,textfield) {
 
            if (/^(09|\+639)\d{9}$/.test(contactNumber)) {
                this.proceedValidation.beneficiary_contact_number = false;
            } else {
                this.proceedValidation.beneficiary_contact_number = true;
            }

        },
        handleBeneficiaryIDType(idType) {
            const validateBeneficiaryIDType = {
                '':[true,true],
                'OTHER':[false,false],
            }

            if(idType != 'OTHER' && idType != ''){
                validateBeneficiaryIDType[idType] = [true,false];
            }


            this.beneficiary_other_id_type_disabled = validateBeneficiaryIDType[idType][0];
            this.beneficiary_id_number_disabled = validateBeneficiaryIDType[idType][1];

            this.proceedValidation.beneficiary_other_id_type = false;
            this.proceedValidation.beneficiary_id_number = false;


        },
        calculateBeneficiaryAge(birthdate) {
            if (!birthdate) {
                this.computedBeneficiary.age = '';
            }

            const today = new Date();
            const birthDate = new Date(birthdate);

            let age = today.getFullYear() - birthDate.getFullYear();

            if (((today.getMonth() - birthDate.getMonth() <= 0) && (today.getDate() < birthDate.getDate()))) {
                age--;
            }

            if (age <= 0 || isNaN(age)) {
                age = 0;
                this.proceedValidation.beneficiary_birthdate = true;
                this.proceedValidation.beneficiary_age = true;
            } else {
                this.proceedValidation.beneficiary_birthdate = false;
                this.proceedValidation.beneficiary_age = false;
            }

            this.computedBeneficiary.age = age;
        }
       
    },
    computed: {
        computedAutoCompleteFullNameBeneficiaryWithRelationship:{
            get(){
                return this.$store.state.autoCompleteFullNameBeneficiaryWithRelationship;
            }
        },
        computedClientTransactionDetails:{
                get(){
                    return this.$store.state.clientTransactionDetails;
                },
                set(value){
                    this.$store.commit('setClientTransactionDetails',{'clientTransactionDetails':value});
                }
        },
        computedClient:{
            get(){
                return this.$store.state.client;
            },
            set(value){
                this.$store.commit('setClient',{client:value});
            }
        },
        computedClientTransactionDetails:{
                get(){
                    return this.$store.state.clientTransactionDetails;
                },
                set(value){
                    this.$store.commit('setClientTransactionDetails',{'clientTransactionDetails':value});
                }
        },
        computedFormSeeder:{
            get(){
                return this.$store.state.formSeeder;
            },
            set(value){
                this.$store.commit('setFormSeeder',{'formSeeder':value});
            }
        },
        computedClientTransactionAddBeneficiaryLoader:{
            get(){
                return this.$store.state.clientTransactionAddBeneficiaryLoader;
            },
            set(value){
                this.$store.commit('setClientTransactionAddBeneficiaryLoader',{'clientTransactionAddBeneficiaryLoader':value});
            }
        },
        computedBeneficiary: {
            get() {
                return this.$store.state.beneficiary;
            },
            set(value) {
                this.$store.commit('setBeneficiary', { beneficiary: value });
            }
        },
        computedHeaderText: {
            get() {
                return this.$store.state.headerText;
            },
            set(value) {
                this.$store.commit('setHeaderText', { headerText: value });
            }
        }

    },
    data() {
        return {
            timeout:null,
            autoCompleteLoader:{
                beneficiary_middle_name:false,
                beneficiary_last_name:false,
                beneficiary_first_name:false,
            },
            dialogVisibleClient: false,
            uploadCaptureButtonVisible:true,
            clientImageModalVisible: false,
            beneficiary_precint_disabled: true,
            beneficiary_other_id_type_disabled: true,
            beneficiary_id_number_disabled: true,
            autoCompleteLoader:{
                beneficiary_middle_name:false,
                beneficiary_last_name:false,
                beneficiary_first_name:false,
            },
            proceedValidation: {
                beneficiary_last_name: false,
                beneficiary_first_name: false,
                beneficiary_middle_name: false,
                beneficiary_suffix: false,
                beneficiary_birthdate: false,
                beneficiary_age: false,
                beneficiary_sex: false,
                beneficiary_civil_status: false,
                beneficiary_street: false,
                beneficiary_barangay: false,
                beneficiary_city: false,
                beneficiary_province: false,
                beneficiary_precint: false,
                beneficiary_contact_number: false,
                beneficiary_id_type: false,
                beneficiary_other_id_type: false,
                beneficiary_id_number: false,
                beneficiary_occupation: false,
                beneficiary_monthly_income: false,
                client_relationship: false,
            }
        }
    },
    beforeRouteEnter(to, from, next) {
        auth(to).then(response => {
            if(response){
                    next(response);
            }else{
                    next('/admin');
            }
        });

    },
    beforeRouteLeave(to, from, next) {
        next(true);
    }

}
</script>