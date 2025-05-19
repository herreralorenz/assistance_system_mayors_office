<template>
    <Loader2 v-if="this.computedNewTransactionBeneficiaryLoader"></Loader2>
    <div class="form-group edit-beneficiary py-4" v-if="!this.computedNewTransactionBeneficiaryLoader">
        <div class="container-fluid">
            <section class="edit-beneficiary-information">
                <h1>Beneficiary</h1>
                <hr>
                <div class="row">
                    <div class="col-lg-12">
                        <label for="clientRelationship">Client Relationship to Beneficiary</label>
                        <input id="clientRelationship"
                            :class="{ 'form-control': true, 'is-invalid': proceedValidation.clientRelationship }"
                            @change="validateFields('clientRelationship')" v-model="this.computedClient.relationship"
                           >
                    </input>
                    </div>
                    <div class="col-lg-3">
                        <label for="beneficiaryLastName">Last Name</label>
                        <input
                            :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.beneficiaryLastName }"
                            id="beneficiaryLastName" v-model="this.computedBeneficiary.last_name" type="text"
                            v-on:keyup="validateFields('beneficiaryLastName')">
                    </div>
                    <div class="col-lg-3">
                        <label for="beneficiaryFirstName">First Name</label>
                        <input
                            :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.beneficiaryFirstName }"
                            id="beneficiaryFirstName" v-model="this.computedBeneficiary.first_name" type="text"
                            v-on:keyup="validateFields('beneficiaryFirstName')">
                    </div>
                    <div class="col-lg-3">
                        <label for="beneficiaryMiddlename">Middle Name</label>
                        <input :class="{ 'form-control': true }" id="beneficiaryMiddlename"
                            v-model="this.computedBeneficiary.middle_name" type="text"
                            v-on:keyup="validateFields('beneficiaryMiddleName')">
                    </div>
                    <div class="col-lg-3">
                        <label for="beneficiarySuffix">Suffix</label>
                        <select id="beneficiarySuffix" v-model="this.computedBeneficiary.suffix"
                            class="custom-select form-select" v-on:keyup="validateFields('beneficiarySuffix')">
                            <option selected value=""></option>
                            <option v-for="(option, index) in this.computedFormSeeder.suffix" :value="option.id" :key="option.id">{{ option.suffix }}</option>

                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <label for="beneficiaryBirthdate">Birthdate</label>
                        <input type="date" v-model="this.computedBeneficiary.birthdate"
                            :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.beneficiaryBirthdate }"
                            @change="validateFields('beneficiaryBirthdate')" id="beneficiaryBirthdate">
                    </div>
                    <div class="col-lg-1">
                        <label for="beneficiaryAge">Age</label>
                        <input :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.beneficiaryAge }"
                            v-model="this.computedBeneficiary.age" id="beneficiaryAge" type="text" disabled>
                    </div>
                    <div class="col-lg-4">
                        <label for="beneficiarySex">Sex</label>
                        <select id="beneficiarySex" @change="validateFields('beneficiarySex')"
                            v-model="this.computedBeneficiary.sex"
                            :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.beneficiarySex }">
                            <option value="" selected></option>
                            <option v-for="(option,index) in this.computedFormSeeder.sex" :key="option.id" :value="option.id">{{ option.sex }}</option>
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label for="beneficiaryCivilStatus">Civil Status</label>
                        <select id="beneficiaryCivilStatus" v-model="this.computedBeneficiary.civil_status"
                            @change="validateFields('beneficiaryCivilStatus')"
                            :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.beneficiaryCivilStatus }">
                            <option value="" selected></option>
                            <option v-for="(option, index) in this.computedFormSeeder.civil_status" :value="option.id" :key="option.id" >{{ option.civil_status }}</option>
                        </select>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <label for="beneficiaryStreet">Street/Purok</label>
                        <input class="form-control" v-on:keyup="validateFields('beneficiaryStreet')"
                            v-model="this.computedBeneficiary.street" id="beneficiaryStreet" type="text">
                    </div>
                    <div class="col-lg-2">
                        <label for="beneficiaryBarangay">Barangay</label>
                        <select id="beneficiaryBarangay" v-model="this.computedBeneficiary.barangay"
                            @change="validateFields('beneficiaryBarangay')"
                            :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.beneficiaryBarangay }">
                            <option value="" selected></option>
                            <option v-for="(option, index) in this.computedFormSeeder.address_metadata[0].address_metadata['4A']['province_list']['CAVITE']['municipality_list']['GENERAL TRIAS CITY']['barangay_list']" :key="index" :value="index" selected>{{ option }}</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label for="beneficiaryCity">City</label>
                        <input :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.beneficiaryCity }"
                            v-model="this.computedBeneficiary.city" id="beneficiaryCity" type="text" disabled>
                    </div>
                    <div class="col-lg-2">
                        <label for="beneficiaryProvince">Province</label>
                        <input
                            :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.beneficiaryProvince }"
                            v-model="this.computedBeneficiary.province" id="beneficiaryProvince" type="text" disabled>
                    </div>
                    <div class="col-lg-2">
                        <input @click="this.beneficiaryRegisteredVoter()"
                            :checked="!this.beneficiaryPrecintNumberDisabled" type="checkbox"
                            id="beneficiaryCheckIsVoter" aria-label="Checkbox for following text input">
                        <label class="form-check-label" for="beneficiaryCheckIsVoter">Registered Voter?</label>
                        <input
                            :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.beneficiaryPrecint }"
                            v-model="this.computedBeneficiary.precint"
                            v-on:keyup="this.validateFields('beneficiaryPrecintNumber')" id="beneficiaryIsVoter"
                            placeholder="Precint Number" type="text" :disabled="this.beneficiaryPrecintNumberDisabled">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <label for="beneficiaryContactNumber">Contact Number</label>
                        <input
                            :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.beneficiaryContactNumber }"
                            v-on:keyup="validateFields('beneficiaryContactNumber')"
                            v-model="this.computedBeneficiary.contact_number" id="beneficiaryContactNumber" type="text">
                    </div>
                    <div class="col-lg-3">
                        <label for="beneficiaryIdType">ID Type</label>
                        <select @change="handleBeneficiaryIdType(this.computedBeneficiary.id_type)"
                            id="beneficiaryIdType" v-model="this.computedBeneficiary.id_type"
                            class="custom-select form-select">
                            <option value="" selected></option>
                            <option v-for="(option,index) in this.computedFormSeeder.id_type" :key="option.id" :value="option.id">{{ option.id_type }}</option>
                            <option value="OTHER">OTHER ID</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label for="beneficiaryOtherId">Other ID</label>
                        <input
                            :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.beneficiaryOtherIdType }"
                            id="beneficiaryOtherId" v-model="computedBeneficiary.other_id_type" type="text"
                            v-on:keyup="validateFields('beneficiaryOtherId')"
                            :disabled="this.beneficiaryOtherIdTypeDisabled">
                    </div>
                    <div class="col-lg-3">
                        <label for="beneficiaryIdNumber">ID Number</label>
                        <input
                            :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.beneficiaryIdNumber }"
                            v-model="this.computedBeneficiary.id_number" id="beneficiaryIdNumber"
                            v-on:keyup="validateFields('beneficiaryIdNumber')" type="text"
                            :disabled="this.beneficiaryIdNumberDisabled">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <label for="beneficiaryOccupation">Occupation</label>
                        <input class="form-control" v-on:keyup="this.validateFields('beneficiaryOccupation')"
                            v-model="this.computedBeneficiary.occupation" id="beneficiaryOccupation" type="text">
                    </div>
                    <div class="col-lg-6">
                        <label for="beneficiaryMonthlyIncome">Montly Income</label>
                        <input
                            :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.beneficiaryMonthlyIncome }"
                            v-on:keyup="validateFields('beneficiaryMonthlyIncome')"
                            v-model="this.computedBeneficiary.monthly_income" id="beneficiaryMonthlyIncome" type="text">
                    </div>
                </div>
                <div>
                    <input :checked="this.computedSameAsClient" type="checkbox" @click="sameAsClient()" id="sameAsClientCheckbox"
                        aria-label="Checkbox for following text input">
                    <label class="form-check-label" for="sameAsClientCheckbox">Same as Client</label>
                </div>
                <button  v-if="!this.computedSameAsClient" type="button" class="btn btn-outline-success mt-2" @click="this.showBeneficiaryImageModal()">
                    <i class="fa fa-camera"></i>UPLOAD / CAPTURE IMAGE
                </button>
            </section>
            <section class="mt-3">
                <button type="button" class="btn btn-success bg-gradient w-100 h-10"
                    @click="this.proceedToTypeOfAssistance()">NEXT</button>
            </section>
        </div>
    </div>
    <ClientNewTransactionBeneficiaryImageModal v-model:visible="this.beneficiaryImageModalVisible">
    </ClientNewTransactionBeneficiaryImageModal>
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
    overflow-y: auto;
    height: -webkit-calc(100% - 50px);
    height: -moz-calc(100% - 50px);
    height: calc(100% - 50px);
    /* min-height: 100%;
    display: flex;
    flex-direction: column; */
}

.edit-beneficiary-information {
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
}
</style>
<script>
import ClientNewTransactionBeneficiaryImageModal from '../client/ClientNewTransactionBeneficiaryImageModalComponent.vue'
import Loader2 from '../Loader2.vue'
import auth from '../../../router/auth_roles_permissions.js';

export default {
    components: {
        Loader2,
        ClientNewTransactionBeneficiaryImageModal
    },
    beforeMount() {
        
        this.computedHeaderText = 'Client New Transaction > Beneficiary';
        this.computedSameAsClient = false;
        for(const [key,value] of Object.entries(this.computedBeneficiary)){
            if(key != 'region' && key != 'province' && key != 'city'){
                this.computedBeneficiary[key] = '';
            }
        }
        this.computedNewTransactionBeneficiaryLoader = true;
        if(Array.isArray(this.computedFormSeeder) && this.computedFormSeeder.length <= 0){
            this.$store.dispatch('fetchSeeders').then(response => {
                this.$store.dispatch('fetchClientNewTransaction',{'clientID':this.$route.params.id}).then(response => {
                    this.computedNewTransactionBeneficiaryLoader = false;
                    console.log(response);
                });
            }).catch(error => { 
            console.error(error); // Handle error
            return Promise.reject(error); // Reject the promise with error
            });
        }else{
            this.$store.dispatch('fetchClientNewTransaction',{'clientID':this.$route.params.id}).then(response => {
                    this.computedNewTransactionBeneficiaryLoader = false;
            });
        }
        
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
        sameAsClient(){
            
            if(this.computedSameAsClient){
                this.computedSameAsClient = false;
                for(const [key,value] of Object.entries(this.computedBeneficiary)){
                    if(key != 'region' && key != 'province' && key != 'city'){
                        this.computedBeneficiary[key] = '';
                    }
                }
                this.beneficiaryPrecintNumberDisabled = true;
                this.beneficiaryIdNumberDisabled = true;
                this.beneficiaryOtherIdTypeDisabled = true;
                this.computedClient.relationship = '';
            }else{
                this.computedSameAsClient = true;
                this.computedClient.relationship = 'HIMSELF';
                this.computedBeneficiary.last_name = this.computedClientNewTransaction.client[0].last_name;
                this.computedBeneficiary.first_name = this.computedClientNewTransaction.client[0].first_name;
                this.computedBeneficiary.middle_name = this.computedClientNewTransaction.client[0].middle_name;
                this.computedBeneficiary.suffix = this.computedClientNewTransaction.client[0].suffix_id;
                this.computedBeneficiary.birthdate = this.computedClientNewTransaction.client[0].birthdate;
                this.computedBeneficiary.age = this.computedClientNewTransaction.client[0].age;
                this.computedBeneficiary.sex = this.computedClientNewTransaction.client[0].sex_id;
                this.computedBeneficiary.civil_status = this.computedClientNewTransaction.client[0].civil_status_id;
                this.computedBeneficiary.street = this.computedClientNewTransaction.client[0].street;
                this.computedBeneficiary.barangay = this.computedClientNewTransaction.client[0].barangay_id;
                if(this.computedClientNewTransaction.client[0]?.precint != null){
                    this.beneficiaryPrecintNumberDisabled = false;
                    this.computedBeneficiary.precint = this.computedClientNewTransaction.client[0]?.precint?.precint;
                }else{
                    this.beneficiaryPrecintNumberDisabled = true;
                }
                this.computedBeneficiary.contact_number = this.computedClientNewTransaction.client[0].contact_number[0].contact_number;
                if(this.computedClientNewTransaction.client[0]?.client_identification[0]?.id_type_id != null){
                    this.computedBeneficiary.id_type = this.computedClientNewTransaction.client[0]?.client_identification[0]?.id_type_id;
                    this.beneficiaryOtherIdTypeDisabled = true;
                    this.beneficiaryIdNumberDisabled = false;
                    this.computedBeneficiary.id_number =  this.computedClientNewTransaction.client[0]?.client_identification[0]?.id_number;
                }else if(this.computedClientNewTransaction.client[0]?.client_identification[0]?.other_id_type_id != null){
                    this.computedBeneficiary.id_type = 'OTHER';
                    this.beneficiaryOtherIdTypeDisabled = false;
                    this.beneficiaryIdNumberDisabled = false;
                    this.computedBeneficiary.other_id_type = this.computedClientNewTransaction.client[0]?.client_identification[0]?.other_identification_type.other_id_type;
                    this.computedBeneficiary.id_number = this.computedClientNewTransaction.client[0]?.client_identification[0]?.id_number;
                }
                this.computedBeneficiary.occupation = this.computedClientNewTransaction.client[0].client_occupation?.[0]?.occupation;
                this.computedBeneficiary.monthly_income = this.computedClientNewTransaction.client[0]?.client_occupation?.[0]?.pivot?.monthly_income;
               
            }
            
            
        
        },
        proceedToTypeOfAssistance() {
            this.validateFields('update');

            let proc = true;
            for (let key in this.proceedValidation) {
                if (this.proceedValidation.hasOwnProperty(key)) {
                    if (this.proceedValidation[key]) {
                        proc = false;
                        break;
                    }
                }
            }
            if (proc) {

                this.$router.push({ name: 'clientNewTransactionSubmit',params:{'id':this.$route.params.id}  });
            }
        },
        showBeneficiaryImageModal() {
            this.beneficiaryImageModalVisible = true;
        },
        validateFields(textfield) {


            for (let key in this.computedBeneficiary) {
                if (this.computedBeneficiary.hasOwnProperty(key)) {
                    if (key != 'monthly_income' && key != 'contact_number' && key != 'age' && key != 'beneficiaryImage' && key != 'sex' && key != 'suffix' && key != 'civil_status' && key != 'barangay' && key != 'id_type') {
                        if (typeof this.computedBeneficiary[key] === 'string' && this.computedBeneficiary[key] && this.computedBeneficiary[key] !== '') {
                            this.computedBeneficiary[key] = this.computedBeneficiary[key].toUpperCase();
                        }
                    }
                }
            }


            if (textfield === 'beneficiaryLastName' || textfield === 'update') {
                if (this.computedBeneficiary.last_name.trim() === '') {
                    this.proceedValidation.beneficiaryLastName = true;
                } else {
                    this.proceedValidation.beneficiaryLastName = false;
                }
            }

            if (textfield === 'beneficiaryFirstName' || textfield === 'update') {
                if (this.computedBeneficiary.first_name.trim() === '') {
                    this.proceedValidation.beneficiaryFirstName = true
                } else {
                    this.proceedValidation.beneficiaryFirstName = false;
                }
            }

            if (textfield === 'beneficiaryBirthdate' || textfield === 'update') {
                this.calculateBeneficiaryAge(this.computedBeneficiary.birthdate);
            }

            if (textfield === 'beneficiarySex' || textfield === 'update') {
              
                if (this.computedBeneficiary.sex === '') {
                    this.proceedValidation.beneficiarySex = true;
                } else {
                    this.proceedValidation.beneficiarySex = false;
                }
            }

            if (textfield === 'beneficiaryCivilStatus' || textfield === 'update') {
                if (this.computedBeneficiary.civil_status === '') {
                    this.proceedValidation.beneficiaryCivilStatus = true;
                } else {
                    this.proceedValidation.beneficiaryCivilStatus = false;
                }
            }

            if (textfield === 'beneficiaryBarangay' || textfield === 'update') {
                if (this.computedBeneficiary.barangay === '') {
                    this.proceedValidation.beneficiaryBarangay = true;
                } else {
                    this.proceedValidation.beneficiaryBarangay = false;
                }
            }

            if(textfield === 'beneficiaryContactNumber' || textfield === 'update'){
                if (this.handleBeneficiaryContactNumber()) {
                this.proceedValidation.beneficiaryContactNumber = false;
                } else {
                    this.proceedValidation.beneficiaryContactNumber = true;
                }
            }
          

            if (textfield === 'beneficiaryOtherId' || textfield === 'update') {
                if (this.computedBeneficiary.id_type === 'OTHER') {
                    if (this.computedBeneficiary.other_id_type === '') {
                        this.proceedValidation.beneficiaryOtherIdType = true;
                    } else {
                        this.proceedValidation.beneficiaryOtherIdType = false;
                    }
                }
            }


            if (textfield === 'beneficiaryIdNumber' || textfield === 'update') {
                if (this.computedBeneficiary.id_type != '') {
                    if (this.computedBeneficiary.id_number.trim() === '') {
                        this.proceedValidation.beneficiaryIdNumber = true;
                    } else {
                        this.proceedValidation.beneficiaryIdNumber = false;
                    }
                }
            }

            if (textfield === "clientRelationship" || textfield === 'update') {

                if (this.computedClient.relationship === '') {
           
                    this.proceedValidation.clientRelationship = true;
                } else {
                    this.proceedValidation.clientRelationship = false;
                }
            }

        },
        beneficiaryRegisteredVoter() {
            if (this.beneficiaryPrecintNumberDisabled) {
                this.beneficiaryPrecintNumberDisabled = false;
            } else {
                this.beneficiaryPrecintNumberDisabled = true;
            }
        },
        handleBeneficiaryContactNumber() {
            if (/^(09|\+639)\d{9}$/.test(this.computedBeneficiary.contact_number)) {
                return true;
            } else {
                return false;
            }

        },
        handleBeneficiaryIdType(idType) {
            if (idType === '') {
                this.beneficiaryIdNumberDisabled = true;
                this.beneficiaryOtherIdTypeDisabled = true;

                this.computedBeneficiary.id_number = '';
                this.computedBeneficiary.other_id_type = '';

                this.proceedValidation.beneficiaryIdNumber = false;
                this.proceedValidation.beneficiaryOtherIdType = false;
            } else if (idType === 'OTHER') {
                this.beneficiaryIdNumberDisabled = false;
                this.beneficiaryOtherIdTypeDisabled = false;

                this.computedBeneficiary.id_number = '';
                this.computedBeneficiary.other_id_type = '';

                this.proceedValidation.beneficiaryIdNumber = false;
                this.proceedValidation.beneficiaryOtherIdType = false;
            } else {
                this.beneficiaryIdNumberDisabled = false;
                this.beneficiaryOtherIdTypeDisabled = true;
                this.computedBeneficiary.id_number = '';
                this.computedBeneficiary.other_id_type = '';

                this.proceedValidation.beneficiaryIdNumber = false;
                this.proceedValidation.beneficiaryOtherIdType = false;
            }
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
                this.proceedValidation.beneficiaryBirthdate = true;
                this.proceedValidation.beneficiaryAge = true;
            } else {
                this.proceedValidation.beneficiaryBirthdate = false;
                this.proceedValidation.beneficiaryAge = false;
            }

            this.computedBeneficiary.age = age;
        }
    },
    computed: {
        computedClientNewTransaction:{
            get(){
                return this.$store.state.clientNewTransaction;
            },
            set(value){
                this.$store.commit('setClientNewTransaction',{'clientNewTransaction':value});
            }
        },
        computedFormSeeder:{
            get(){
                return this.$store.state.formSeeder;
            }
        },
        computedNewTransactionBeneficiaryLoader:{
            get(){
                return this.$store.state.clientNewTransactionBeneficiaryLoader;
            },
            set(value){
                this.$store.commit('setClientNewTransactionBeneficiaryLoader',{clientNewTransactionBeneficiaryLoader:value});
            }
        },
        computedClient: {
            get() {
                return this.$store.state.client;
            },
            set(value) {
                this.$store.commit('setClient', { client: value });
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
        },
        computedSameAsClient:{
            get(){
                return this.$store.state.sameAsClient;
            },
            set(value){
                this.$store.commit('setSameAsClient',{'sameAsClient':value});
            }
        }

    },
    data() {
        return {
            beneficiaryImageModalVisible: false,
            beneficiaryPrecintNumberDisabled: true,
            beneficiaryOtherIdTypeDisabled: true,
            beneficiaryIdNumberDisabled: true,
            proceedValidation: {
                beneficiaryLastName: false,
                beneficiaryFirstName: false,
                beneficiaryMiddleName: false,
                beneficiarySuffix: false,
                beneficiaryBirthdate: false,
                beneficiaryAge: false,
                beneficiarySex: false,
                beneficiaryCivilStatus: false,
                beneficiaryStreet: false,
                beneficiaryBarangay: false,
                beneficiaryCity: false,
                beneficiaryProvince: false,
                beneficiaryPrecint: false,
                beneficiaryContactNumber: false,
                beneficiaryIdType: false,
                beneficiaryOtherIdType: false,
                beneficiaryIdType: false,
                beneficiaryIdNumber: false,
                beneficiaryOccupation: false,
                beneficiaryMonthlyIncome: false,
                beneficiaryRelationship: false,
                clientRelationship: false,
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