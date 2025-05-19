<template>
    <Loader2 v-if="this.computedClientNewTransactionSubmitLoader"></Loader2>
    <div v-if="!this.computedClientNewTransactionSubmitLoader" class="form-group new-transaction py-4">
        <section class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="details h-100 ">
                        <div class="row">
                            <div class="col-12">
                                <label for="typeofassistance">Transaction Date</label>
                                <input v-model="this.computedTypeOfAssistance.transactionDate" id="transactionDate"
                                    :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.transactionDate }"
                                    type="date">
                            </div>
                            <div class="col-7 ">
                                <label for="typeofassistance">Agency</label>
                                <select v-model="this.computedTypeOfAssistance.agency" id="agency"  @change="this.handleSelect('agencySelect')"
                                    :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.agency }">
                                    <option value=""></option>
                                    <option v-for="(option, index) in this.computedFormSeeder.assistance_program_type"
                                        :key="option.id" :value="option.id">{{ option.agency_name }}</option>
                                </select>
                            </div>
                            <div class="col-5">
                                <label for="typeofassistance">Agency Program</label>
                                <select v-model="this.computedTypeOfAssistance.agencyProgram" id="agencyProgram" @change="this.handleSelect('agencyProgramSelect')"
                                    :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.agencyProgram }">
                                    <option value=""></option>
                                    <option
                                        v-for="(option, index) in this.computedFormSeeder?.assistance_program_type[this.computedTypeOfAssistance?.agency]?.agency_program"
                                        :key="option?.id" :value="option?.id">{{ option?.agency_program_name }}</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="typeofassistance">Type of Assistance</label>
                                <select v-model="this.computedTypeOfAssistance.typeOfAssistance" id="typeofassistance" @change="this.handleSelect('typeOfAssistanceSelect')"
                                    :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.typeOfAssistance }">
                                    <option value=""></option>
                                    <option
                                        v-for="(option, index) in this.computedFormSeeder?.assistance_program_type[this.computedTypeOfAssistance?.agency]?.agency_program[this.computedTypeOfAssistance?.agencyProgram]?.assistance_type_agency_program"
                                        :key="option?.id" :value="option?.id">{{ option?.assistance_type }}</option>
                                </select>
                            </div>
                            <div class="col-7">
                                <label for="descriptionOfAssistance">Description of Assistance</label>
                                <select @change="this.handleSelect('descriptionOfAssistanceSelect')"
                                    v-model="this.computedTypeOfAssistance.descriptionOfAssistance"
                                    id="descriptionOfAssistance"
                                    :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.descriptionOfAssistance }">
                                    <option value=""></option>
                                    <option
                                        v-for="(option, index) in this.computedFormSeeder?.assistance_program_type[this.computedTypeOfAssistance?.agency]?.agency_program[this.computedTypeOfAssistance?.agencyProgram]?.assistance_type_agency_program[this.computedTypeOfAssistance?.typeOfAssistance]?.assistance_type_description"
                                        :key="option.id" :value="option.id">{{ option.assistance_description }}</option>
                                    <option value="OTHER">OTHER</option>
                                </select>
                            </div>
                            <div class="col-5">
                                <label for="otherDescriptionOfAssistance">Other Description of Assistance</label>
                                <input v-model="this.computedTypeOfAssistance.otherDescriptionOfAssistance" v-on:keyup="validateFields('otherDescriptionOfAssistance')"
                                    id="otherDescriptionOfAssistance"
                                    :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.otherDescriptionOfAssistance }"
                                    type="text" :disabled="this.otherDescriptionOfAssistanceDisabled">
                            </div>
                            <div class="col-4" v-if="this.computedTypeOfAssistance.typeOfAssistance === 2">
                                <label for="hospitalName">Hospital Name</label>
                                <input v-model="this.computedTypeOfAssistance.hospitalName" id="hospitalName"  v-on:keyup="this.validateFields('hospitalName')"
                                    :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.hospitalName }"
                                    type="text">
                            </div>
                            <div class="col-4" v-if="this.computedTypeOfAssistance.typeOfAssistance === 2">
                                <label for="maipCode">MAIP Code</label>
                                <input v-model="this.computedTypeOfAssistance.maipCode" id="maipCode"  v-on:keyup="this.validateFields('maipCode')"
                                    :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.maipCode }"
                                    type="text">
                            </div>
                            <div class="col-4" v-if="this.computedTypeOfAssistance.typeOfAssistance === 2">
                                <label for="hospitalType">Hospital Type</label>
                                <select v-model="this.computedTypeOfAssistance.hospitalType" id="hospitalType"  @change="this.handleSelect('hospitalType')"
                                    :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.hospitalType }"
                                    >
                                    <option value=""></option>
                                    <option v-for="(option, index) in this.computedFormSeeder.hospital_type"
                                        :key="option.id" :value="option.id">{{ option.hospital_type }}</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="reasonOfAssistance">Reason of Assistance</label>
                                <textarea v-model="this.computedTypeOfAssistance.reasonOfAssistance"
                                    id="reasonOfAssistance" :class="{ 'form-control': true }">
                            </textarea>
                            </div>
                            <div class="col-3">
                                <label for="dueDate">Due Date</label>
                                <input v-model="this.computedTypeOfAssistance.dueDate" id="dueDate" type="date" :class="{
                                    'form-control': true,
                                }">
                            </div>
                            <div class="col-3">
                                <label for="category">Category</label>
                                <select v-model="this.computedTypeOfAssistance.category"  @change="this.handleSelect('categorySelect')" id="category"
                                    :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.category }">
                                    <option value=""></option>
                                    <option v-for="(option, index) in this.computedFormSeeder.assistance_category"
                                        :key="option.id" :value="option.id">{{ option.assistance_category }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center align-items-center">
                <button class="btn w-100  bg-success text-white d-flex justify-content-center align-items-center m-3"
                    style="font-size: 25px;" @click="this.validateFields('submit')">Submit</button>
            </div>
        </section>

    </div>
</template>
<style scoped>
input,
select,
textarea,
button {
    border-radius: 25px;

}

video,
canvas {
    height: 300px;
    /* Set the desired height */
    width: 100%;
    /* Width adjusts automatically */
    object-fit: cover;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
}

.new-transaction {
    margin: 0;
    padding-left: 250px;
    overflow-y: auto;
    height: -webkit-calc(100% - 50px);
    height: -moz-calc(100% - 50px);
    height: calc(100% - 50px);
    /* min-height: 100%;
    display: flex;
    flex-direction: column; */
}

.webcam,
.details {
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
}
</style>
<script>

import { ref } from 'vue';
import { computed } from 'vue';
import Loader2 from '../Loader2.vue';
import auth from '../../../router/auth_roles_permissions.js';

export default {
    components: {
        Loader2
    },
    beforeMount() {
        
        window.scrollTo({
            top: 0,
            behavior: 'instant' // Optional: adds smooth scrolling effect
        });

        for (const [key, value] of Object.entries(this.computedTypeOfAssistance)) {
            if (key != 'transactionDate') {
                this.computedTypeOfAssistance[key] = '';
            }
        }

        this.computedHeaderText = 'Client New Transaction > Beneficiary > Submit';

        this.computedClientNewTransactionSubmitLoader = true;
        this.$store.dispatch('fetchSeeders').then(response => {
            this.computedClientNewTransactionSubmitLoader = false;
            console.log(response);
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
        document.body.style.overflow = 'scroll';
    },
    methods: {

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
                    this.otherTypeOfAssistanceDisabled = true;
                    this.proceedValidation.descriptionOfAssistance = false;
                    this.otherDescriptionOfAssistanceDisabled = true;
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
                    this.otherDescriptionOfAssistanceDisabled = true;
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
                    this.otherDescriptionOfAssistanceDisabled = true;

                } else {
                    this.proceedValidation.typeOfAssistance = false;

                    this.computedTypeOfAssistance.descriptionOfAssistance = '';
                    this.computedTypeOfAssistance.otherDescriptionOfAssistance = '';
                    this.otherDescriptionOfAssistanceDisabled = true;
                }
            } else if (select === 'descriptionOfAssistanceSelect') {
                if (this.computedTypeOfAssistance.descriptionOfAssistance === '' || this.computedTypeOfAssistance.descriptionOfAssistance === null) {
                    this.proceedValidation.descriptionOfAssistance = true;
                    this.proceedValidation.otherDescriptionOfAssistance = false;

                    this.computedTypeOfAssistance.descriptionOfAssistance = '';
                    this.computedTypeOfAssistance.otherDescriptionOfAssistance = '';
                    this.otherDescriptionOfAssistanceDisabled = true;
                } else if (this.computedTypeOfAssistance.descriptionOfAssistance === 'OTHER') {
                    this.proceedValidation.descriptionOfAssistance = false;
                    this.otherDescriptionOfAssistanceDisabled = false;

                    this.computedTypeOfAssistance.otherDescriptionOfAssistance = '';
                } else {
                    this.proceedValidation.descriptionOfAssistance = false;
                    this.proceedValidation.otherDescriptionOfAssistance = false;


                    this.computedTypeOfAssistance.otherDescriptionOfAssistance = '';

                    this.computedTypeOfAssistance.otherDescriptionOfAssistance = '';
                    this.otherDescriptionOfAssistanceDisabled = true;
                }
            } else if (select === 'categorySelect') {
                if (this.computedTypeOfAssistance.category === '' || this.computedTypeOfAssistance.category === null) {
                    this.proceedValidation.category = true;
                } else {
                    this.proceedValidation.category = false;
                }
            }
        },
        validateFields(textField) {


            if (textField === 'transactionDate' || textField === 'submit') {
                if (this.computedTypeOfAssistance.transactionDate === '' || this.computedTypeOfAssistance.transactionDate === null) {
                    this.proceedValidation.transactionDate = true;
                } else {
                    this.proceedValidation.transactionDate = false;
                }
            }

            if (textField === 'agencySelect' || textField === 'submit') {
                if (this.computedTypeOfAssistance.agency === '' || this.computedTypeOfAssistance.agency === null) {
                    this.proceedValidation.agency = true;
                } else {
                    this.proceedValidation.agency = false;
                }
            }

            if (textField === 'agencyProgramSelect' || textField === 'submit') {
                if (this.computedTypeOfAssistance.agencyProgram === '' || this.computedTypeOfAssistance.agencyProgram === null) {
                    this.proceedValidation.agencyProgram = true;
                } else {
                    this.proceedValidation.agencyProgram = false;
                }
            }


            if (textField === 'typeOfAssistanceSelect' || textField === 'submit') {
                if (this.computedTypeOfAssistance.typeOfAssistance === '' || this.computedTypeOfAssistance.typeOfAssistance === null) {
                    this.computedTypeOfAssistance.otherTypeOfAssistance = '';
                    this.proceedValidation.typeOfAssistance = true;
                } else {
                    this.computedTypeOfAssistance.otherTypeOfAssistance = '';
                    this.proceedValidation.typeOfAssistance = false;
                }
            }



            if (textField === 'descriptionOfAssistanceSelect' || textField === 'submit') {
                if (this.computedTypeOfAssistance.descriptionOfAssistance === '' || this.computedTypeOfAssistance.descriptionOfAssistance === null) {
                    this.proceedValidation.descriptionOfAssistance = true;
                } else {
                    this.proceedValidation.descriptionOfAssistance = false;
                }
            }

            if (textField === 'otherDescriptionOfAssistance' || textField === 'submit') {
                if (!this.otherDescriptionOfAssistanceDisabled) {
                    if (this.computedTypeOfAssistance.otherDescriptionOfAssistance === '' || this.otherDescriptionOfAssistance === null) {
                        this.proceedValidation.otherDescriptionOfAssistance = true;
                    } else {
                        this.proceedValidation.otherDescriptionOfAssistance = false;
                    }
                }
            }

            if(textField === 'maipCode' || textField === 'submit'){
                if(this.computedTypeOfAssistance.typeOfAssistance == '2'){
                    if(this.computedTypeOfAssistance.maipCode === '' || !this.computedTypeOfAssistance.maipCode){
                        this.proceedValidation.maipCode = true;
                    }else{
                        this.proceedValidation.maipCode = false;
                    }
                }
            }

            if(textField === 'hospitalName' || textField === 'submit'){
                if(this.computedTypeOfAssistance.typeOfAssistance == '2'){
                    if(this.computedTypeOfAssistance.hospitalName === '' || !this.computedTypeOfAssistance.hospitalName){
                        this.proceedValidation.hospitalName = true;
                    }else{
                        this.proceedValidation.hospitalName = false;
                    }
                }
            }

            if(textField === 'hospitalType' || textField === 'submit'){
                if(this.computedTypeOfAssistance.typeOfAssistance == '2'){
                    if(this.computedTypeOfAssistance.hospitalType === '' || !this.computedTypeOfAssistance.hospitalType){
                        this.proceedValidation.hospitalType = true;
                    }else{
                        this.proceedValidation.hospitalType = false;
                    }
                }
            }


            if(textField === 'categorySelect' || textField === 'submit'){
                if (this.computedTypeOfAssistance.category === '' || this.computedTypeOfAssistance.category === null) {
                this.proceedValidation.category = true;
                }else{
                    this.proceedValidation.category = false;
                }
            }
         


            if (textField === 'submit') {
                let proc = true;
                if (this.computedTypeOfAssistance.typeOfAssistance === '2') {
                    for (const [key, value] of Object.entries(this.proceedValidation)) {
                        if (value === true) {
                            proc = false;
                            break;
                        }
                    }

                    if (proc) {
                        // console.log({'beneficiary': this.computedBeneficiary, 'client': this.computedClient, 'typeOfAssistance': this.computedTypeOfAssistance});
                        this.computedClientNewTransactionSubmitLoader = true;
                        this.$store.dispatch('newTransaction', { 'beneficiary': this.computedBeneficiary, 'client': this.computedClient, 'typeOfAssistance': this.computedTypeOfAssistance,'clientID': this.$route.params.id, 'sameAsClient':this.computedSameAsClient }).then(resp => {
                            
                            if (resp.status === 200 && (resp.data?.failed?.length === undefined || resp.data?.failed?.length <= 0) ) {
                                 
                                 for (const [key, value] of Object.entries(this.computedBeneficiary)) {
                                     if(key != 'region' && key != 'city' && key != 'province'){
                                         this.computedBeneficiary[key] = '';
                                     }
                                 }
 
                                 for (const [key, value] of Object.entries(this.computedClient)) {                       
                                     if(key != 'region' && key != 'city' && key != 'province'){
                                         this.computedClient[key] = '';
                                     }
                                 }
 
                                 for (const [key, value] of Object.entries(this.computedTypeOfAssistance)) {
                                    if(key != 'transactionDate'){
                                     this.computedTypeOfAssistance[key] = '';
                                    }
                                 }
                                      
                                 this.computedClientNewTransactionSubmitLoader = false;
                                 this.$router.push({ name: 'clientNewTransactionTable' });  
                            
                             }
                        });
                    }
                } else {
                    for (const [key, value] of Object.entries(this.proceedValidation)) {
                        if (key != 'hospitalName' && key != 'maipCode' && key != 'hospitalType') {
                            if (value === true) {
                                proc = false;
                                break;
                            }
                        }
                    }

                    if (proc) {
                        this.computedClientNewTransactionSubmitLoader = true;
                        this.$store.dispatch('newTransaction', { 'beneficiary': this.computedBeneficiary, 'client': this.computedClient, 'typeOfAssistance': this.computedTypeOfAssistance, 'clientID': this.$route.params.id,'sameAsClient':this.computedSameAsClient}).then(resp => {
                            if (resp.status === 200 && (resp.data?.failed?.length === undefined || resp.data?.failed?.length <= 0) ) {
                                 
                                 for (const [key, value] of Object.entries(this.computedBeneficiary)) {
                                     if(key != 'region' && key != 'city' && key != 'province'){
                                         this.computedBeneficiary[key] = '';
                                     }
                                 }
 
                                 for (const [key, value] of Object.entries(this.computedClient)) {                       
                                     if(key != 'region' && key != 'city' && key != 'province'){
                                         this.computedClient[key] = '';
                                     }
                                 }
 
                                 for (const [key, value] of Object.entries(this.computedTypeOfAssistance)) {
                                    if(key != 'transactionDate'){
                                     this.computedTypeOfAssistance[key] = '';
                                    }
                                 }
                                 this.computedClientNewTransactionSubmitLoader = false;
                                 this.computedClientToast = true;
                                 this.$router.push({ name: 'clientNewTransactionTable' });   
                             }
                        });
                    }
                }
            }
        },
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
        computedSameAsClient:{
            get(){
                return this.$store.state.sameAsClient;
            },
            set(value){
                this.$store.commit('setSameAsClient',{'sameAsClient':value});
            }
        },
        computedClient:{
            get(){
                return this.$store.state.client;
            },
            set(value){
                this.$store.commit('setClient',{'client':value});
            }
        },
        computedBeneficiary:{
            get(){
                return this.$store.state.beneficiary;
            },
            set(value){
                this.$store.commit('setBeneficiary',{'beneficiary':value});
            }
        },

        computedTypeOfAssistance: {
            get() {
                return this.$store.state.typeOfAssistance
            },
            set(value) {
                this.$store.commit('setTypeOfAssistance', { 'typeOfAssistance': value })
            }
        },
        computedFormSeeder: {
            get() {
                return this.$store.state.formSeeder;
            },
        },
        computedClientNewTransactionSubmitLoader: {
            get() {
                return this.$store.state.clientNewTransactionSubmitLoader;
            },
            set(value) {
                this.$store.commit('setClientNewTransactionSubmitLoader', { 'clientNewTransactionSubmitLoader': value });
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
            otherDescriptionOfAssistanceDisabled: true,
            proceedValidation: {
                transactionDate: false,
                agency: false,
                agencyProgram: false,
                typeOfAssistance: false,
                descriptionOfAssistance: false,
                otherDescriptionOfAssistance: false,
                hospitalName: false,
                hospitalType: false,
                maipCode: false,
            }
        };
    },
    beforeRouteLeave(to, from, next) {
        next();
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
}
</script>