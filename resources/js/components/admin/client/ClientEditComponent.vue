<template>
    <Loader2 v-if="this.computedEditClientDetailsLoader"></Loader2>
    <Toast/>
    <div class="form-group edit-client py-4" v-if="!this.computedEditClientDetailsLoader">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <section class="edit-client-information">
                        <h1>Client</h1>
                        <hr>
                        <div class="row">
                            <div class="col-lg-3">
                                <label for="client_last_name">Last Name</label>
                                <input
                                    :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.client_last_name }"
                                    id="client_last_name" v-model="this.computedClient.last_name" type="text"
                                    v-on:keyup="validateFields('client_last_name')">
                            </div>
                            <div class="col-lg-3">
                                <label for="client_first_name">First Name</label>
                                <input
                                    :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.client_first_name }"
                                    id="client_first_name" v-model="this.computedClient.first_name" type="text"
                                    v-on:keyup="validateFields('client_first_name')">
                            </div>
                            <div class="col-lg-3">
                                <label for="client_middle_name">Middle Name</label>
                                <input :class="{ 'form-control': true }" id="client_middle_name"
                                    v-model="this.computedClient.middle_name" type="text"
                                    v-on:keyup="validateFields('client_middle_name')">
                            </div>
                            <div class="col-lg-3">
                                <label for="client_suffix">Suffix</label>
                                <select v-model="this.computedClient.suffix" id="client_suffix"
                                    class="custom-select form-select" v-on:keyup="validateFields('client_suffix')">
                                    <option value="" selected></option>
                                    <option :key="option.id" :value="option.id" v-for="(option,index) in this.computedFormSeeder.suffix">{{ option.suffix }}</option>   
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <label for="client_birthdate">Birthdate</label>
                                <input type="date" v-model="this.computedClient.birthdate"
                                    :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.client_birthdate }"
                                    @change="validateFields('client_birthdate')" id="client_birthdate">
                            </div>
                            <div class="col-lg-1">
                                <label for="client_age">Age</label>
                                <input :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.client_age }"
                                    v-model="this.computedClient.age" id="client_age" type="text" disabled>
                            </div>
                            <div class="col-lg-4">
                                <label for="client_sex">Sex</label>
                                <select id="client_sex" @change="validateFields('client_sex')"
                                    v-model="this.computedClient.sex"
                                    :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.client_sex }">
                                    <option value="" selected></option>
                                   <option :key="option.id" :value="option.id" v-for="(option,index) in this.computedFormSeeder.sex">{{ option.sex }}</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="client_civil_status">Civil Status</label>
                                <select id="client_civil_status" v-model="this.computedClient.civil_status"
                                    @change="validateFields('client_civil_status')"
                                    :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.client_civil_status }">
                                    <option value="" selected></option>
                                    <option :key="option.id" :value="option.id" v-for="(option,index) in this.computedFormSeeder.civil_status">{{option.civil_status}}</option>
                                </select>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <label for="client_street">Street/Purok</label>
                                <input class="form-control" v-on:keyup="validateFields('client_street')"
                                    v-model="this.computedClient.street" id="client_street" type="text">
                            </div>
                            <div class="col-lg-2">
                                <label for="client_barangay">Barangay</label>
                                <select id="client_barangay" v-model="this.computedClient.barangay"
                                    @change="validateFields('client_barangay')"
                                    :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.client_barangay }">
                                    <option value="" selected></option>
                                    <option :value="index" :key="index" v-for="(option,index) in this.computedFormSeeder.address_metadata[0]['address_metadata']['4A']['province_list']['CAVITE']['municipality_list']['GENERAL TRIAS CITY']['barangay_list']">{{ option }}</option>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label for="client_city">City</label>
                                <input
                                    :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.client_city }"
                                    v-model="this.computedClient.city" id="client_city" type="text" disabled>
                            </div>
                            <div class="col-lg-2">
                                <label for="client_province">Province</label>
                                <input
                                    :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.client_province }"
                                    v-model="this.computedClient.province" id="client_province" type="text" disabled>
                            </div>
                            <div class="col-lg-2">
                                <input @click="this.clientRegisteredVoter()"
                                    :checked="!this.client_precint_disabled" type="checkbox" id="clientCheckIsVoter"
                                    aria-label="Checkbox for following text input">
                                <label class="form-check-label" for="clientCheckIsVoter">Registered Voter?</label>
                                <input
                                    :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.client_precint }"
                                    v-model="this.computedClient.precint"
                                    v-on:keyup="this.validateFields('client_precintNumber')" id="clientIsVoter"
                                    placeholder="Precint Number" type="text"
                                    :disabled="this.client_precint_disabled">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <label for="client_contact_number">Contact Number</label>
                                <input
                                    :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.client_contact_number }"
                                    v-on:keyup="validateFields('client_contact_number')"
                                    v-model="this.computedClient.contact_number" id="client_contact_number" type="text">
                            </div>
                            <div class="col-lg-3">
                                <label for="client_id_type">ID Type</label>
                                <select @change="handleClientIDType(this.computedClient.id_type)" id="client_id_type"
                                    v-model="this.computedClient.id_type" class="custom-select form-select">
                                    <option value="" selected></option>
                                    <option :key="option.id" :value="option.id" v-for="(option,index) in this.computedFormSeeder.id_type">{{option.id_type}}</option>
                                    <option value="OTHER">OTHER ID</option>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label for="clientOtherId">Other ID</label>
                                <input
                                    :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.client_other_id_type }"
                                    id="clientOtherId" v-model="this.computedClient.other_id_type" type="text"
                                    v-on:keyup="validateFields('clientOtherId')"
                                    :disabled="this.client_other_id_type_disabled">
                            </div>
                            <div class="col-lg-3">
                                <label for="client_id_number">ID Number</label>
                                <input
                                    :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.client_id_number }"
                                    v-model="this.computedClient.id_number" id="client_id_number"
                                    v-on:keyup="validateFields('client_id_number')" type="text"
                                    :disabled="this.client_id_number_disabled">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="client_occupation">Occupation</label>
                                <input class="form-control" v-on:keyup="this.validateFields('client_occupation')"
                                    v-model="this.computedClient.occupation" id="client_occupation" type="text">
                            </div> 
                            <div class="col-lg-6">
                                <label for="client_monthly_income">Monthly Income</label>
                                <input
                                    :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.client_monthly_income }"
                                    v-on:keyup="validateFields('client_monthly_income')"
                                    v-model="this.computedClient.monthly_income" id="client_monthly_income" type="text">
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-success mt-2" @click="showClientImageModal()">
                            <i class="fa fa-camera"></i> UPLOAD / CAPTURE IMAGE
                        </button>
                    </section>
                </div>
            </div>
            <button type="button" class="btn btn-success bg-gradient w-100 h-10 mt-3"
            @click="validateFields('update')">UPDATE</button>
        </div>
    </div>
    <ClientImageModalComponent v-if="!this.computedEditClientDetailsLoader" v-model:visible="this.clientImageModalVisible"></ClientImageModalComponent>
</template>
<style scoped>
input,
select,
textarea,
button {
    border-radius: 25px;

}

.edit-client {
    padding-left: 250px;
    overflow-y: auto;
    overflow-x: hidden;
    height: -webkit-calc(100% - 50px);
    height: -moz-calc(100% - 50px);
    height: calc(100% - 50px);
    /* min-height: 100%;
    display: flex;
    flex-direction: column; */
}

.edit-client-information {
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
}



video, canvas {
  height: 300px; /* Set the desired height */
  width: 100%;   /* Width adjusts automatically */
  object-fit: cover;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); 
}

</style>
<script>

import { setTransitionHooks } from 'vue';
import ClientImageModalComponent from '../client/ClientImageModalComponent.vue';
import Loader2 from '../Loader2.vue';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import auth from '../../../router/auth_roles_permissions.js';

export default {
    components: {
        ClientImageModalComponent,
        Loader2,
        Toast,
    },
    beforeMount() {

        this.toast = useToast();

        this.computedHeaderText = 'Client > Client Details > Edit Client';
        this.computedEditClientDetailsLoader = true;
        if(Array.isArray(this.$store.state.formSeeder) && this.$store.state.formSeeder <= 0){
            this.$store.dispatch('fetchSeeders').then(response => {
                this.$store.dispatch('fetchEditClientDetails',{'id':this.$route.params.id}).then(response1 =>{
                   
                    this.computedClient.last_name = response1.data.client[0].last_name;
                    this.computedClient.first_name = response1.data.client[0].first_name;
                    this.computedClient.middle_name = response1.data.client[0].middle_name;
                    this.computedClient.suffix = response1.data.client[0].suffix?.id ?? "";
                    this.computedClient.birthdate = response1.data.client[0].birthdate;
                    this.computedClient.age = response1.data.client[0].age;
                    this.computedClient.sex = response1.data.client[0].sex.id;
                    this.computedClient.civil_status = response1.data.client[0].civil_status.id;
                    this.computedClient.city = response1.data.client[0].city;
                    this.computedClient.province = response1.data.client[0].province;
                    this.computedClient.region = response1.data.client[0].region;
                    this.computedClient.street = response1.data.client[0]?.street ?? "";
                    this.computedClient.barangay = response1.data.client[0].barangay_id;
                    if(response1.data.client[0].precint?.precint != null){
                        this.computedClient.precint = response1.data.client[0].precint?.precint ?? "";
                        this.client_precint_disabled = false;
                    }else{
                        this.client_precint_disabled = true;
                    }
                    this.computedClient.contact_number = response1.data.client[0].contact_number[0].contact_number;
                    if(response1.data.client[0].client_identification[0]?.id_type_id != null){
                        this.computedClient.id_type = response1.data.client[0].client_identification[0].id_type_id;
                        this.computedClient.id_number = response1.data.client[0].client_identification[0].id_number;
                        this.client_id_number_disabled = false;
                        this.client_other_id_type_disabled = true;
                    }else if(response1.data.client[0].client_identification[0]?.other_id_type_id != null){
                        this.computedClient.id_type = 'OTHER';
                        this.computedClient.other_id_type = response1.data.client[0].client_identification[0].other_identification_type.other_id_type;
                        this.computedClient.id_number = response1.data.client[0].client_identification[0].id_number;
                        this.client_id_number_disabled = false;
                        this.client_other_id_type_disabled = false;
                    }else{
                        this.computedClient.id_type = '';
                        this.computedClient.other_id_type = '';
                        this.client_id_number_disabled = true;
                        this.client_other_id_type_disabled = true;
                    }
                    this.computedClient.occupation = response1.data.client[0].client_occupation[0]?.occupation ?? "";
                    this.computedClient.monthly_income = response1.data.client[0]?.monthly_income ?? ""; 
                    this.computedClient.client_image = response1.data.client[0]?.image?.[0]?.base64 ?? "";
 
                    
                   
                    this.computedEditClientDetailsLoader = false;
                });
            });
        }else{
            this.$store.dispatch('fetchEditClientDetails',{'id':this.$route.params.id}).then(response1 =>{

                this.computedClient.last_name = response1.data.client[0].last_name;
                    this.computedClient.first_name = response1.data.client[0].first_name;
                    this.computedClient.middle_name = response1.data.client[0].middle_name;
                    this.computedClient.suffix = response1.data.client[0].suffix?.id ?? "";
                    this.computedClient.birthdate = response1.data.client[0].birthdate;
                    this.computedClient.age = response1.data.client[0].age;
                    this.computedClient.sex = response1.data.client[0].sex.id;
                    this.computedClient.civil_status = response1.data.client[0].civil_status.id;
                    this.computedClient.city = response1.data.client[0].city;
                    this.computedClient.province = response1.data.client[0].province;
                    this.computedClient.region = response1.data.client[0].region;
                    this.computedClient.street = response1.data.client[0]?.street ?? "";
                    this.computedClient.barangay = response1.data.client[0].barangay_id;
                    if(response1.data.client[0].precint?.precint != null){
                        this.computedClient.precint = response1.data.client[0].precint?.precint ?? "";
                        this.client_precint_disabled = false;
                    }else{
                        this.client_precint_disabled = true;
                    }
                    this.computedClient.contact_number = response1.data.client[0].contact_number[0].contact_number;
                    if(response1.data.client[0].client_identification[0]?.id_type_id != null){
                        this.computedClient.id_type = response1.data.client[0].client_identification[0].id_type_id;
                        this.computedClient.id_number = response1.data.client[0].client_identification[0].id_number;
                        this.client_id_number_disabled = false;
                        this.client_other_id_type_disabled = true;
                    }else if(response1.data.client[0].client_identification[0]?.other_id_type_id != null){
                        this.computedClient.id_type = 'OTHER';
                        this.computedClient.other_id_type = response1.data.client[0].client_identification[0].other_identification_type.other_id_type;
                        this.computedClient.id_number = response1.data.client[0].client_identification[0].id_number;
                        this.client_id_number_disabled = false;
                        this.client_other_id_type_disabled = false;
                    }else{
                        this.computedClient.id_type = '';
                        this.computedClient.other_id_type = '';
                        this.client_id_number_disabled = true;
                        this.client_other_id_type_disabled = true;
                    }
                    this.computedClient.occupation = response1.data.client[0].client_occupation?.[0]?.occupation ?? "";
                    this.computedClient.monthly_income = response1.data.client[0]?.client_occupation?.[0]?.pivot?.monthly_income ?? "" ;
                    this.computedClient.client_image = response1.data.client[0]?.image?.[0]?.base64 ?? "";
                   
                    this.computedEditClientDetailsLoader = false;
       
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
        showClientImageModal(){
            this.clientImageModalVisible = true; 
        },
        validateFields(textfield) {

            for (let key in this.computedClient) {
                if (this.computedClient.hasOwnProperty(key)) {
                    if (key != 'monthly_income' && key != 'contact_number' && key != 'age' && key != 'client_image'  && key != 'sex' && key != 'suffix' && key != 'civil_status' && key != 'barangay' && key != 'id_type') {
                        if(typeof this.computedClient[key] === 'string' && this.computedClient[key] && this.computedClient[key] !== ''){
                            this.computedClient[key] = this.computedClient[key].toUpperCase();
                        }
                    }
                }
            }
            
            const clientValidateFields = {

                'first_name':['required'],
                'last_name':['required'],
                'middle_name':['not_required'],
                'suffix':['not_required'],
                'birthdate':['calculateClientAge'],
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
                'contact_number':['handleClientContactNumber'],
                'id_type':['not_required'],
                'other_id_type':['checkClientID'],
                'id_number':['checkClientID'],
                'occupation':['not_required'],
                'monthly_income':['checkMonthlyIncome'],
                'client_image':['not_required'],
                'relationship':['not_required'],
            };

            if(textfield === 'update'){

                    for(const [key,value] of Object.entries(this.computedClient)){
                        if(clientValidateFields[key][0] === 'required' && this.computedClient[key] !== '' && this.computedClient[key]){
                            this.proceedValidation[`client_${key}`] = false;
                        }else if(clientValidateFields[key][0] === 'required' && this.computedClient[key] === '' && !this.computedClient[key]){
                            this.proceedValidation[`client_${key}`] = true;
                        }else if(clientValidateFields[key][0] === 'not_required'){
                            this.proceedValidation[`client_${key}`] = false;
                        }else if(clientValidateFields[key][0] !== 'required' && this.computedClient[key][0] !== 'not_required'){
                            this[clientValidateFields[key][0]](this.computedClient[key],`client_${key}`);
                        }
                    }

                let proc = true;
                for(const [key,value] of Object.entries(this.proceedValidation)){
                    if(this.proceedValidation[key] === true){
                        proc = false;
                        break;
                    }
                }

                if(proc && textfield === 'update'){
                    this.computedEditClientDetailsLoader = true;
                    this.$store.dispatch('updateClient',{'clientID':this.$route.params.id,'client':this.computedClient}).then(response => {
                    this.computedEditClientDetailsLoader = false;
                    document.body.style.overflow = 'hidden';
                    if(this.computedClientDetails){
                        this.toast.add({ severity: 'success', summary: 'Success', detail: 'Client updated successfully.', life: 5000 });
                        this.computedClientToast = false;
                    }
                    
                });
                }

            }else{

                    clientValidateFields[textfield.replace(/^client_/, "")].forEach(element => {
                        if(element === 'required'){
                            if(this.computedClient[textfield.replace(/^client_/, "")] === ''){
                                this.proceedValidation[textfield] = true;
                            }else{
                                this.proceedValidation[textfield] = false;
                            }
                        }else if(element === 'not_required'){
                            this.proceedValidation[textfield] = false
                        }else{
                            this[element](this.computedClient[textfield.replace(/^client_/, "")],textfield);
                        }
                    });

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
        checkClientID(value,textfield){
            if((!this[textfield+'_disabled'] && value != '' && value)  || (this[textfield+'_disabled'] && value === '')){
                this.proceedValidation[textfield] = false;
            }else{
                this.proceedValidation[textfield] = true;
            }
        },
        clientRegisteredVoter() {
            this.client_precint_disabled = !this.client_precint_disabled;
            this.proceedValidation['client_precint'] = false;
            if (!this.client_precint_disabled) {
                this.computedClient.precint = '';
            } 
        },
        handleClientContactNumber(contactNumber,textfield) {
 
            if (/^(09|\+639)\d{9}$/.test(contactNumber)) {
                this.proceedValidation.client_contact_number = false;
            } else {
                this.proceedValidation.client_contact_number = true;
            }

        },
        handleClientIDType(idType) {
            const validateClientIDType = {
                '':[true,true],
                'OTHER':[false,false],
            }

            if(idType != 'OTHER' && idType != ''){
                validateClientIDType[idType] = [true,false];
            }


            this.client_other_id_type_disabled = validateClientIDType[idType][0];
            this.client_id_number_disabled = validateClientIDType[idType][1];

            this.proceedValidation.client_other_id_type = false;
            this.proceedValidation.client_id_number = false;

            this.computedClient.other_id_type = '';
            this.computedClient.id_number = '';


        },
        calculateClientAge(birthdate) {
            if (!birthdate) {
                this.computedClient.age = '';
            }

            const today = new Date();
            const birthDate = new Date(birthdate);

            let age = today.getFullYear() - birthDate.getFullYear();

            if (((today.getMonth() - birthDate.getMonth() <= 0) && (today.getDate() < birthDate.getDate()))) {
                age--;
            }

            if (age <= 0 || isNaN(age)) {
                age = 0;
                this.proceedValidation.client_birthdate = true;
                this.proceedValidation.client_age = true;
            } else {
                this.proceedValidation.client_birthdate = false;
                this.proceedValidation.client_age = false;
            }

            this.computedClient.age = age;
        }
    },
    computed: {
        computedClientToast:{
            get(){
                return this.$store.state.clientToast;
            },
            set(value){
                this.$store.commit('setClientToast',{'clientToast':value});
            }
        },
        computedEditClientDetailsLoader:{
            get(){
                return this.$store.state.editClientDetailsLoader;
            },
            set(value){
                this.$store.commit('setEditClientDetailsLoader',{'editClientDetailsLoader':value});
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
        computedClientDetails:{
            get(){
                return this.$store.state.clientDetails;
            },
            set(value){
                this.$store.commit('setClientDetails',{'clientDetails':value});
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
            toast:false,
            clientImageModalVisible:false,
            client_precint_disabled: true,
            client_other_id_type_disabled: true,
            client_id_number_disabled: true,
            proceedValidation: {
                client_last_name: false,
                client_first_name: false,
                client_middle_name: false,
                client_suffix: false,
                client_birthdate: false,
                client_age: false,
                client_sex: false,
                client_civil_status: false,
                client_street: false,
                client_barangay: false,
                client_city: false,
                client_province: false,
                client_precint: false,
                client_contact_number: false,
                client_id_type: false,
                client_other_id_type: false,
                client_id_number: false,
                client_occupation: false,
                client_monthly_income: false,
            }
        }
    },
    beforeRouteEnter(to, from, next) {
        next(true);
    },
    beforeRouteLeave(to, from, next) {
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