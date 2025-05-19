<template>
    <Loader2 v-if="this.computedClientTransactionEditBeneficiaryLoader"></Loader2>
    <div class="form-group edit-beneficiary py-4" v-if="!this.computedClientTransactionEditBeneficiaryLoader">
        <div class="container-fluid">
            <section class="edit-beneficiary-information">
                <h1>Beneficiary</h1>
                <hr>
                <div class="row">
                    <div class="col-lg-12">
                        <label for="claimantRelationship">Client Relationship</label>
                        <input id="claimantRelationship" v-on:keyup="this.validateFields('client_relationship')"  :class="{ 'form-control': true, 'is-invalid': proceedValidation.client_relationship }"  v-model="this.computedClient.relationship">
                    </div>
                    <div class="col-lg-3">
                        <label for="beneficiary_last_name">Last Name</label>
                        <input :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.beneficiary_last_name }"
                            id="beneficiary_last_name" v-model="this.computedBeneficiary.last_name" type="text"
                            v-on:keyup="validateFields('beneficiary_last_name')">
                    </div>
                    <div class="col-lg-3">
                        <label for="beneficiary_first_name">First Name</label>
                        <input :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.beneficiary_first_name }"
                            id="beneficiary_first_name" v-model="this.computedBeneficiary.first_name" type="text"
                            v-on:keyup="validateFields('beneficiary_first_name')">
                    </div>
                    <div class="col-lg-3">
                        <label for="beneficiary_middle_name">Middle Name</label>
                        <input :class="{ 'form-control': true }" id="beneficiary_middle_name"
                            v-model="this.computedBeneficiary.middle_name" type="text"
                            v-on:keyup="validateFields('beneficiary_middle_name')">
                    </div>
                    <div class="col-lg-3">
                        <label for="beneficiary_suffix">Suffix</label>
                        <select id="beneficiary_suffix" v-model="this.computedBeneficiary.suffix"
                            class="custom-select form-select" v-on:keyup="validateFields('beneficiary_suffix')">
                            <option disabled selected value=""></option>
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
                            v-on:keyup="this.validateFields('beneficiary_precint')" id="beneficiaryIsVoter"
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
                            v-on:keyup="validateFields('beneficiary_other_id_type')"
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
                    <input type="checkbox" id="sameAsClient" v-model="this.computedSameAsClient" @click="this.handleSameAsClient()">
                    <label for="sameAsClient">Same as Client</label>
                </div>
                <div>
                    <button v-if="this.uploadCaptureButtonVisible" type="button" class="btn btn-outline-success mt-2" @click="this.showBeneficiaryImageModal()">
                        <i class="fa fa-camera"></i>UPLOAD / CAPTURE IMAGE
                    </button>
                </div>
            </section>
            <section class="mt-3">
                <button type="button" class="btn btn-success bg-gradient w-100 h-10"
                    @click="validateFields('update')">UPDATE</button>
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

.edit-beneficiary {
    padding-left: 250px;
    min-height: 100%;
    display: flex;
    flex-direction: column;
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


export default {
    components: {
        BeneficiaryImageModalComponent,
        Loader2
    },
    beforeMount() {
        this.computedHeaderText = 'Beneficiary Transactions > Transaction Details > Edit Beneficiary';
        this.computedClientTransactionEditBeneficiaryLoader = true;
        

        this.$store.dispatch('fetchSeeders').then(response1 => {
            this.$store.dispatch('fetchClientTransactionBeneficiary',{'id':this.$route.params.id,'id2':this.$route.params.id2,'id3':this.$route.params.id3}).then(response2 => {

                this.computedSameAsClient = false;
                this.computedClient.relationship = response2.data.beneficiary[0]?.client_relationship?.[0]?.relationship?.[0].relationship;
                this.computedBeneficiary.last_name = response2.data.beneficiary[0].last_name;
                this.computedBeneficiary.first_name = response2.data.beneficiary[0].first_name;
                this.computedBeneficiary.middle_name = response2.data.beneficiary[0].middle_name;
                this.computedBeneficiary.suffix = response2.data.beneficiary[0]?.suffix_id ?? "";
                this.computedBeneficiary.birthdate = response2.data.beneficiary[0].birthdate;
                this.computedBeneficiary.age = response2.data.beneficiary[0].age;
                this.computedBeneficiary.sex = response2.data.beneficiary[0].sex_id;
                this.computedBeneficiary.civil_status = response2.data.beneficiary[0].civil_status_id;
                this.computedBeneficiary.street = response2.data.beneficiary[0].street;
                this.computedBeneficiary.barangay = response2.data.beneficiary[0].barangay_id;
                this.computedBeneficiary.city = response2.data.beneficiary[0].city;
                this.computedBeneficiary.province = response2.data.beneficiary[0].province;
                if(response2.data.beneficiary[0]?.precint != null){
                    this.beneficiary_precint_disabled = false;
                    this.computedBeneficiary.precint = response2.data.beneficiary[0]?.precint?.precint ?? "";
                }else{
                    this.beneficiary_precint_disabled= true;
                }
                this.computedBeneficiary.contact_number = response2.data.beneficiary[0].contact_number[0].contact_number ?? "";

                if(response2.data.beneficiary[0]?.beneficiary_identification.length > 0){
                    if(response2.data.beneficiary[0]?.beneficiary_identification?.[0]?.identification_type != null){
                        this.beneficiary_id_number_disabled = false;
                        this.beneficiary_other_id_type_disabled= true; 
                        this.computedBeneficiary.id_number = response2.data.beneficiary[0]?.beneficiary_identification?.[0]?.id_number;
                        this.computedBeneficiary.id_type = response2.data.beneficiary[0]?.beneficiary_identification?.[0]?.identification_type?.id;
                    }else{
                        this.beneficiary_id_number_disabled = false;
                        this.beneficiary_other_id_type_disabled = false;
                        this.computedBeneficiary.id_type = 'OTHER';
                        this.computedBeneficiary.id_number = response2.data.beneficiary[0]?.beneficiary_identification?.[0]?.id_number;
                        this.computedBeneficiary.other_id_type = response2.data.beneficiary[0]?.beneficiary_identification?.[0]?.other_identification_type?.other_id_type;
                    }
                }
                this.computedBeneficiary.occupation = response2.data.beneficiary[0]?.beneficiary_occupation?.[0]?.occupation ?? "";
                this.computedBeneficiary.monthly_income = response2.data.beneficiary[0].beneficiary_occupation?.[0]?.pivot?.monthly_income ?? ""; 
                this.computedBeneficiary.beneficiary_image = response2.data.beneficiary[0]?.image?.[0]?.base64 ?? "";

       
                this.computedClientTransactionEditBeneficiaryLoader = false;
            });
        });

    },
    mounted() {
        window.scrollTo({
            top: 0,
            behavior: 'instant' // Optional: adds smooth scrolling effect
        });

    },
    beforeUnmount() {

    },
    methods: {
        handleSameAsClient(){
    
            if(this.computedSameAsClient){

                this.computedSameAsClient = false;
                this.uploadCaptureButtonVisible = true;
                this.computedClient.relationship = this.computedClientTransactionBeneficiary.beneficiary[0]?.client_relationship?.[0]?.relationship?.[0].relationship;
                this.computedBeneficiary.last_name = this.computedClientTransactionBeneficiary.beneficiary[0].last_name;
                this.computedBeneficiary.first_name = this.computedClientTransactionBeneficiary.beneficiary[0].first_name;
                this.computedBeneficiary.middle_name = this.computedClientTransactionBeneficiary.beneficiary[0].middle_name;
                this.computedBeneficiary.suffix = this.computedClientTransactionBeneficiary.beneficiary[0]?.suffix_id ?? "";
                this.computedBeneficiary.birthdate = this.computedClientTransactionBeneficiary.beneficiary[0].birthdate;
                this.computedBeneficiary.age = this.computedClientTransactionBeneficiary.beneficiary[0].age;
                this.computedBeneficiary.sex = this.computedClientTransactionBeneficiary.beneficiary[0].sex_id;
                this.computedBeneficiary.civil_status = this.computedClientTransactionBeneficiary.beneficiary[0].civil_status_id;
                this.computedBeneficiary.street = this.computedClientTransactionBeneficiary.beneficiary[0].street;
                this.computedBeneficiary.barangay = this.computedClientTransactionBeneficiary.beneficiary[0].barangay_id;
                this.computedBeneficiary.city = this.computedClientTransactionBeneficiary.beneficiary[0].city;
                this.computedBeneficiary.province = this.computedClientTransactionBeneficiary.beneficiary[0].province;
                if(this.computedBeneficiary.precint != null ){
                    this.beneficiary_precint_disabled = false;
                    this.computedBeneficiary.precint = this.computedClientTransactionBeneficiary.beneficiary[0].precint?.precint ?? "";
                }else{
                    this.beneficiary_precint_disabled = true;
                }
                this.computedBeneficiary.contact_number = this.computedClientTransactionBeneficiary.beneficiary[0].contact_number[0].contact_number ?? "";
        
                if(this.computedClientTransactionBeneficiary.beneficiary[0]?.beneficiary_identification.length > 0){
                    if(this.computedClientTransactionBeneficiary.beneficiary[0]?.beneficiary_identification?.[0]?.identification_type != null){
                        this.beneficiary_id_number_disabled = false;
                        this.beneficiary_other_id_type_disabled = true;
                        this.computedBeneficiary.id_number = this.computedClientTransactionBeneficiary.beneficiary[0]?.beneficiary_identification?.[0]?.id_number ?? "";
                        this.computedBeneficiary.id_type = this.computedClientTransactionBeneficiary.beneficiary[0]?.beneficiary_identification?.[0]?.identification_type?.id ?? "";
                    }else{
                        this.beneficiary_id_number_disabled = true;
                        this.beneficiary_other_id_type_disabled = false;
                        this.computedBeneficiary.id_type = 'OTHER';
                        this.computedBeneficiary.other_id_type = this.computedClientTransactionBeneficiary.beneficiary[0]?.other_id_type_id?.[0]?.other_identification_type?.other_id_type ?? "";
                    }
                }
                this.computedBeneficiary.occupation = this.computedClientTransactionBeneficiary.beneficiary[0]?.beneficiary_occupation?.[0]?.occupation ?? "";
                this.computedBeneficiary.monthly_income = this.computedClientTransactionBeneficiary.beneficiary[0].beneficiary_occupation?.[0]?.pivot?.monthly_income ?? "";
                this.computedBeneficiary.beneficiary_image = this.computedClientTransactionBeneficiary.beneficiary[0]?.image?.[0]?.base64 ?? "";

            }else{
                this.computedSameAsClient = true;
                this.uploadCaptureButtonVisible = false;
                this.computedClient.relationship = 'HIMSELF';
             
                this.computedBeneficiary.last_name =    this.computedClientTransactionBeneficiary.beneficiary[0].client_relationship[0].last_name;
                this.computedBeneficiary.first_name =    this.computedClientTransactionBeneficiary.beneficiary[0].client_relationship[0].first_name;
                this.computedBeneficiary.middle_name =    this.computedClientTransactionBeneficiary.beneficiary[0].client_relationship[0]?.middle_name;
                this.computedBeneficiary.suffix =    this.computedClientTransactionBeneficiary.beneficiary[0].client_relationship[0]?.suffix?.suffix; 
                this.computedBeneficiary.birthdate =    this.computedClientTransactionBeneficiary.beneficiary[0].client_relationship[0].birthdate;
                this.computedBeneficiary.age =    this.computedClientTransactionBeneficiary.beneficiary[0].client_relationship[0].age;
                this.computedBeneficiary.sex =    this.computedClientTransactionBeneficiary.beneficiary[0].client_relationship[0].sex.id;
                this.computedBeneficiary.civil_status =    this.computedClientTransactionBeneficiary.beneficiary[0].client_relationship[0].civil_status.id;
                this.computedBeneficiary.street =    this.computedClientTransactionBeneficiary.beneficiary[0].client_relationship[0]?.street;
                this.computedBeneficiary.barangay =    this.computedClientTransactionBeneficiary.beneficiary[0].client_relationship[0].barangay_id;
                this.computedBeneficiary.city =    this.computedClientTransactionBeneficiary.beneficiary[0].client_relationship[0].city;
                this.computedBeneficiary.province =    this.computedClientTransactionBeneficiary.beneficiary[0].client_relationship[0].province;
                if(this.computedClientTransactionBeneficiary.beneficiary[0].client_relationship[0]?.precint){
                    this.beneficiary_precint_disabled = false;
                    this.computedBeneficiary.precint =  this.computedClientTransactionBeneficiary.beneficiary[0].client_relationship[0]?.precint?.precint;
                }else{
                    console.log(this.computedClientTransactionBeneficiary.beneficiary[0].client_relationship[0]?.precint);
                    this.beneficiary_precint_disabled = true;
                    this.computedBeneficiary.precint = '';
                }
                this.computedBeneficiary.contact_number =    this.computedClientTransactionBeneficiary.beneficiary[0].client_relationship[0]?.contact_number?.[0].contact_number;
                if(this.computedClientTransactionBeneficiary.beneficiary[0].client_relationship[0]?.client_identification.length > 0){
                    if(this.computedClientTransactionBeneficiary.beneficiary[0].client_relationship[0]?.client_identification?.[0]?.id_type_id != null ||    this.computedClientTransactionBeneficiary.beneficiary[0].client_relationship[0]?.client_identification?.[0]?.id_type_id != ''){
                        this.beneficiary_id_number_disabled = false;
                        this.beneficiary_other_id_type_disabled = true;
                        this.computedBeneficiary.id_number =    this.computedClientTransactionBeneficiary.beneficiary[0].client_relationship[0]?.client_identification?.[0]?.id_number;
                        this.computedBeneficiary.id_type =    this.computedClientTransactionBeneficiary.beneficiary[0].client_relationship[0]?.client_identification?.[0]?.id_type_id;
                        this.computedBeneficiary.other_id_type = '';
                    }else{
                        this.computedBeneficiary.id_type = 'OTHER'
                        this.beneficiary_id_number_disabled = false;
                        this.beneficiary_other_id_type_disabled = false;
                        this.computedBeneficiary.id_number =    this.computedClientTransactionBeneficiary.beneficiary[0].client_relationship[0]?.client_identification?.[0]?.id_number;
                        this.computedBeneficiary.other_id_type =    this.computedClientTransactionBeneficiary.beneficiary[0].client_relationship[0]?.client_identification?.[0]?.other_identification_type?.other_id_type;
                    }
                }else{
  
                    this.beneficiary_id_number_disabled = true;
                    this.beneficiary_other_id_type_disabled = true;
                    this.computedBeneficiary.id_number = ""
                    this.computedBeneficiary.other_id_type = ""
                }
                this.computedBeneficiary.occupation =    this.computedClientTransactionBeneficiary.beneficiary[0].client_relationship[0]?.client_occupation?.[0]?.occupation;
                this.computedBeneficiary.monthly_income =    this.computedClientTransactionBeneficiary.beneficiary[0].client_relationship[0]?.client_occupation?.[0]?.pivot?.monthly_income;
                this.computedBeneficiary.beneficiary_image =    '';
            }
        },
        showBeneficiaryImageModal() {
            this.clientImageModalVisible = true;

            this.$refs.beneficiaryImageModal.loadBeneficiaryClientImage();
           
        },
        validateFields(textfield) {
            

            for (let key in this.computedBeneficiary) {
                if (this.computedBeneficiary.hasOwnProperty(key)) {
                    if (key != 'monthly_income' && key != 'contact_number' && key != 'age' && key != 'beneficiary_image'  && key != 'sex' && key != 'suffix' && key != 'civil_status' && key != 'barangay' && key != 'id_type') {
                        if(typeof this.computedBeneficiary[key] === 'string' && this.computedBeneficiary[key] && this.computedBeneficiary[key] !== ''){
                            this.computedBeneficiary[key] = this.computedBeneficiary[key].toUpperCase();
                        }
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

            if(textfield === 'update'){
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
                
                // console.log(this.proceedValidation);
                let proc = true;
                for(const [key,value] of Object.entries(this.proceedValidation)){
                    if(this.proceedValidation[key] === true){
                        proc = false;
                        break;
                    }
                }

                if(proc && textfield === 'update'){
                    this.computedClientTransactionEditBeneficiaryLoader = true;
                    this.$store.dispatch('updateClientTransactionBeneficiary',{'id':this.$route.params.id, 'id2':this.$route.params.id2,'id3':this.$route.params.id3,'beneficiary':this.computedBeneficiary, 'client':this.computedClient,'sameAsClient':this.computedSameAsClient}).then(response => {
                        this.computedClientTransactionEditBeneficiaryLoader = false;
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
                        if(element === 'required'){
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
        computedClient:{
            get(){
                return this.$store.state.client;
            },
            set(value){
                this.$store.commit('setClient',{client:value});
            }
        },
        computedSameAsClient:{
            get(){
                return this.$store.state.sameAsClient;
            },
            set(value){
                this.$store.commit('setSameAsClient',{'sameAsClient':value});
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
        computedClientTransactionEditBeneficiaryLoader:{
            get(){
                return this.$store.state.clientTransactionEditBeneficiaryLoader;
            },
            set(value){
                this.$store.commit('setClientTransactionEditBeneficiaryLoader',{'clientTransactionEditBeneficiaryLoader':value});
            }
        },
        computedClientTransactionBeneficiary:{
            get(){
                return this.$store.state.clientTransactionBeneficiary;
            },
            set(value){
                this.$store.commit('setClientTransactionBeneficiary',{'clientTransactionBeneficiary':value});
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
            uploadCaptureButtonVisible:true,
            clientImageModalVisible: false,
            beneficiary_precint_disabled: true,
            beneficiary_other_id_type_disabled: true,
            beneficiary_id_number_disabled: true,
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