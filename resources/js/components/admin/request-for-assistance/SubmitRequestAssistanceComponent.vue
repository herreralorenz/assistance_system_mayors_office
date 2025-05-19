<template>
    <LoaderComponent v-if="this.computedSubmitLoader"></LoaderComponent>
    <div class="form-group submit-request-assistance py-4" v-if="!this.computedSubmitLoader">
        <section class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="details h-100">
                        <div class="row">
                            <div class="col-12">
                                <label for="transaction-date">Transaction Date</label>
                                <input id="transaction-date" type="date"
                                    :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.transaction_date }"
                                    v-model="this.computedTypeOfAssistance.transaction_date"
                                    v-on:keyup="validateFields('transactionDate')">
                            </div>
                            <div class="col-6">
                                <label for="agency">Agency</label>
                                <select id="agency"
                                    :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.agency }"
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
                                    :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.agency_program }"
                                    v-model="this.computedTypeOfAssistance
                                        .agency_program
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
                                    :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.type_of_assistance }"
                                    v-model="this.computedTypeOfAssistance
                                        .type_of_assistance
                                        " @change="this.handleSelect('typeOfAssistanceSelect')">
                                    <option value=""></option>
                                    <option v-for="(option, index) in this.computedFormSeeder?.assistance_program_type[this.computedTypeOfAssistance.agency]?.['agency_program']?.[this.computedTypeOfAssistance.agency_program]?.['assistance_type_agency_program']" :key="index" :value="index">
                                        {{ option.assistance_type }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-7">
                                <label for="descriptionOfAssistance">Description of Assistance</label>
                                <select id="descriptionOfAssistance"
                                    :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.description_of_assistance }"
                                    v-model="this.computedTypeOfAssistance
                                        .description_of_assistance
                                        " @change="this.handleSelect('descriptionOfAssistanceSelect')">
                                    <option value=""></option>
                                    <option v-for="(option, index) in this.computedFormSeeder?.assistance_program_type[this.computedTypeOfAssistance.agency]?.['agency_program']?.[this.computedTypeOfAssistance.agency_program]?.['assistance_type_agency_program']?.[this.computedTypeOfAssistance.type_of_assistance]?.['assistance_type_description']" :key="index" :value="index">
                                        {{
                                            option.assistance_description
                                        }}
                                    </option>
                                    <option value="OTHER">OTHERS</option>
                                </select>
                            </div>
                            <div class="col-5">
                                <label for="otherDescriptionOfAssistance">Other Description of Assistance</label>
                                <input v-model="this.computedTypeOfAssistance.other_description_of_assistance"
                                    id="otherDescriptionOfAssistance"
                                    :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.other_description_of_assistance }"
                                    type="text" :disabled="this.otherDescriptionOfAssistanceDisabled"
                                    v-on:keyup="validateFields('otherDescriptionOfAssistance')" />
                            </div>
                            <div class="col-4" v-if="this.computedTypeOfAssistance.type_of_assistance === '2'">
                                <label for="hospitalName">Hospital Name</label>
                                <input v-model="this.computedTypeOfAssistance.hospital_name" id="hospitalName"
                                    :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.hospital_name }"
                                    v-on:keyup="this.validateFields('hospitalName')" type="text" />
                            </div>
                            <div class="col-4" v-if="this.computedTypeOfAssistance.type_of_assistance === '2'">
                                <label for="maipCode">MAIP Code</label>
                                <input v-model="this.computedTypeOfAssistance.maip_code" id="maipCode"
                                    :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.maip_code }"
                                    v-on:keyup="this.validateFields('maipCode')" type="text" />
                            </div>
                            <div class="col-4" v-if="this.computedTypeOfAssistance.type_of_assistance === '2'">
                                <label for="hospitalType">Hospital Type</label>
                                <select v-model="this.computedTypeOfAssistance.hospital_type" id="hospitalType"
                                    :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.hospital_type }"
                                    @change="this.handleSelect('hospitalType')">
                                    <option value=""></option>
                                    <option v-for="(option, index) in this.computedFormSeeder.hospital_type"
                                        :key="option.id" :value="option.id">{{ option.hospital_type }}</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="reasonOfAssistance">Reason of Assistance</label>
                                <textarea v-model="this.computedTypeOfAssistance.reason_of_assistance" id="reasonOfAssistance" :class="{ 'form-control': true }">
                                </textarea>
                            </div>
                            <div class="col-3">
                                <label for="dueDate">Due Date</label>
                                <input v-model="this.computedTypeOfAssistance.due_date" id="dueDate" type="date" :class="{
                                    'form-control': true,
                                }" />
                            </div>
                            <div class="col-3">
                                <label for="category">Category</label>
                                <select v-model="this.computedTypeOfAssistance.category"
                                    @change="this.handleSelect('categorySelect')" id="category"
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
                <button class="btn w-100 bg-secondary text-white d-flex justify-content-center align-items-center m-3"
                    style="font-size: 25px" @click="this.backToClientBeneficiaryDetails()">
                    Back
                </button>
                <button class="btn w-100 bg-success text-white d-flex justify-content-center align-items-center m-3"
                    style="font-size: 25px" @click="this.validateFields('submit')">
                    Submit
                </button>
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

.details {
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
}

.submit-request-assistance {
    /* margin: 0; */
    padding-left: 250px;
    overflow-y: auto;
        height: -webkit-calc(100% - 160px);
    height: -moz-calc(100% - 160px);
    height: calc(100% - 160px);
    /* min-height: 100%;
    display: flex;
    flex-direction: column; */
}
</style>
<script>
import { ref } from "vue";
import { computed } from "vue";
import LoaderComponent from '../Loader2.vue';

export default {
    components: {
        LoaderComponent,
    },
    beforeMount() {
        this.computedTypeOfAssistance.transaction_date = this.computedFormSeeder.date_today;
        if(this.computedClient.age >= 60){
            this.computedTypeOfAssistance.category = 2;
        }else{
            this.computedTypeOfAssistance.category = 1;
        }

        window.addEventListener("scroll", this.handleScroll);
    },
    mounted() {
        document.body.style.overflow = 'hidden';
        window.scrollTo({
            top: 0,
            behavior: "instant", // Optional: adds smooth scrolling effect
        });

        this.$store.state.progressActive = 2;
        this.$store.state.progressBarWidth = "75%";
    },
    beforeUnmount() {
        window.removeEventListener("scroll", this.handleScroll);
    },
    methods: {
        handleSelect(select) {
            if (select === 'agencySelect') {
                if (this.computedTypeOfAssistance.agency === '' || this.computedTypeOfAssistance.agency === null) {
                    this.proceedValidation.agency = true;

                    this.computedTypeOfAssistance.agency_program = '';
                    this.computedTypeOfAssistance.type_of_assistance = '';
                    this.computedTypeOfAssistance.otherTypeOfAssistance = '';
                    this.computedTypeOfAssistance.description_of_assistance = '';
                    this.computedTypeOfAssistance.other_description_of_assistance = '';


                    this.proceedValidation.agency_program = false;
                    this.proceedValidation.type_of_assistance = false;
                    this.proceedValidation.otherTypeOfAssistance = false;
                    this.otherTypeOfAssistanceDisabled = true;
                    this.proceedValidation.description_of_assistance = false;
                    this.otherDescriptionOfAssistanceDisabled = true;
                    this.proceedValidation.other_description_of_assistance = false;
                } else {
                    this.proceedValidation.agency = false;

                    this.computedTypeOfAssistance.agency_program = '';
                    this.computedTypeOfAssistance.type_of_assistance = '';
                    this.computedTypeOfAssistance.otherTypeOfAssistance = '';
                    this.computedTypeOfAssistance.description_of_assistance = '';
                    this.computedTypeOfAssistance.other_description_of_assistance = '';
                }
            } else if (select === 'agencyProgramSelect') {
                if (this.computedTypeOfAssistance.agency_program === '' || this.computedTypeOfAssistance.agency_program === null) {
                    this.proceedValidation.agency_program = true;

                    this.computedTypeOfAssistance.type_of_assistance = '';
                    this.computedTypeOfAssistance.description_of_assistance = '';
                    this.computedTypeOfAssistance.other_description_of_assistance = '';

                    this.proceedValidation.type_of_assistance = false;
                    this.proceedValidation.description_of_assistance = false;
                    this.otherDescriptionOfAssistanceDisabled = true;
                    this.proceedValidation.other_description_of_assistance = false;

                } else {
                    this.proceedValidation.agency_program = false;

                    this.computedTypeOfAssistance.type_of_assistance = '';
                    this.computedTypeOfAssistance.description_of_assistance = '';
                    this.computedTypeOfAssistance.other_description_of_assistance = '';

                }
            } else if (select === 'typeOfAssistanceSelect') {
                if (this.computedTypeOfAssistance.type_of_assistance === '' || this.computedTypeOfAssistance.type_of_assistance === null) {
                    this.proceedValidation.type_of_assistance = true;


                    this.computedTypeOfAssistance.description_of_assistance = '';
                    this.computedTypeOfAssistance.other_description_of_assistance = '';
                    this.otherDescriptionOfAssistanceDisabled = true;

                } else {
                    this.proceedValidation.type_of_assistance = false;

                    this.computedTypeOfAssistance.description_of_assistance = '';
                    this.computedTypeOfAssistance.other_description_of_assistance = '';
                    this.otherDescriptionOfAssistanceDisabled = true;
                }
            } else if (select === 'descriptionOfAssistanceSelect') {
                if (this.computedTypeOfAssistance.description_of_assistance === '' || this.computedTypeOfAssistance.description_of_assistance === null) {
                    this.proceedValidation.description_of_assistance = true;
                    this.proceedValidation.other_description_of_assistance = false;

                    this.computedTypeOfAssistance.description_of_assistance = '';
                    this.computedTypeOfAssistance.other_description_of_assistance = '';
                    this.otherDescriptionOfAssistanceDisabled = true;
                } else if (this.computedTypeOfAssistance.description_of_assistance === 'OTHER') {
                    this.proceedValidation.description_of_assistance = false;
                    this.otherDescriptionOfAssistanceDisabled = false;

                    this.computedTypeOfAssistance.other_description_of_assistance = '';
                } else {
                    this.proceedValidation.description_of_assistance = false;
                    this.proceedValidation.other_description_of_assistance = false;


                    this.computedTypeOfAssistance.other_description_of_assistance = '';

                    this.computedTypeOfAssistance.other_description_of_assistance = '';
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
                if (this.computedTypeOfAssistance.transaction_date === '' || this.computedTypeOfAssistance.transaction_date === null) {
                    this.proceedValidation.transaction_date = true;
                } else {
                    this.proceedValidation.transaction_date = false;
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
                if (this.computedTypeOfAssistance.agency_program === '' || this.computedTypeOfAssistance.agency_program === null) {
                    this.proceedValidation.agency_program = true;
                } else {
                    this.proceedValidation.agency_program = false;
                }
            }


            if (textField === 'typeOfAssistanceSelect' || textField === 'submit') {
                if (this.computedTypeOfAssistance.type_of_assistance === '' || this.computedTypeOfAssistance.type_of_assistance === null) {
                    this.computedTypeOfAssistance.otherTypeOfAssistance = '';
                    this.proceedValidation.type_of_assistance = true;
                } else {
                    this.computedTypeOfAssistance.otherTypeOfAssistance = '';
                    this.proceedValidation.type_of_assistance = false;
                }
            }



            if (textField === 'descriptionOfAssistanceSelect' || textField === 'submit') {
                if (this.computedTypeOfAssistance.description_of_assistance === '' || this.computedTypeOfAssistance.description_of_assistance === null) {
                    this.proceedValidation.description_of_assistance = true;
                } else {
                    this.proceedValidation.description_of_assistance = false;
                }
            }

            if (textField === 'otherDescriptionOfAssistance' || textField === 'submit') {
                if (!this.otherDescriptionOfAssistanceDisabled) {
                    if (this.computedTypeOfAssistance.other_description_of_assistance === '' || this.other_description_of_assistance === null) {
                        this.proceedValidation.other_description_of_assistance = true;
                    } else {
                        this.proceedValidation.other_description_of_assistance = false;
                    }
                }
            }

            if(textField === 'maipCode' || textField === 'submit'){

                if(this.computedTypeOfAssistance.type_of_assistance == '2'){
                    if(this.computedTypeOfAssistance.maip_code === '' || !this.computedTypeOfAssistance.maip_code){
                        this.proceedValidation.maip_code = true;
                    }else{
                        this.proceedValidation.maip_code = false;
                    }
                }
            }

            if(textField === 'hospitalName' || textField === 'submit'){
                if(this.computedTypeOfAssistance.type_of_assistance == '2'){
                    if(this.computedTypeOfAssistance.hospital_name === '' || !this.computedTypeOfAssistance.hospital_name){
                        this.proceedValidation.hospital_name = true;
                    }else{
                        this.proceedValidation.hospital_name = false;
                    }
                }
            }

            if(textField === 'hospitalType' || textField === 'submit'){
                if(this.computedTypeOfAssistance.type_of_assistance == '2'){
                    if(this.computedTypeOfAssistance.hospital_type === '' || !this.computedTypeOfAssistance.hospital_type){
                        this.proceedValidation.hospital_type = true;
                    }else{
                        this.proceedValidation.hospital_type = false;
                    }
                }
            }



            if (this.computedTypeOfAssistance.category === '' || this.computedTypeOfAssistance.category === null) {
                this.proceedValidation.category = true;
            } else {
                this.proceedValidation.category = false;
            }


            if (textField === 'submit') {
                let proc = true;
                if (this.computedTypeOfAssistance.type_of_assistance === '2') {
                    for (const [key, value] of Object.entries(this.proceedValidation)) {
                        if (value === true) {
                            proc = false;
                            break;
                        }
                    }

                    if (proc) {
                        this.computedSubmitLoader = true;
                        // console.log({'beneficiary': this.computedBeneficiary, 'client': this.computedClient, 'typeOfAssistance': this.computedTypeOfAssistance});
                        this.$store.dispatch('submitData', { 'beneficiary': this.computedBeneficiary, 'client': this.computedClient, 'typeOfAssistance': this.computedTypeOfAssistance, 'sameAsAboveFields':this.computedSameAsAboveFields }).then(resp =>{
                            if (resp.status === 200 && (resp.data?.failed?.length === undefined || resp.data?.failed?.length <= 0) ) {
                                this.computedSubmitLoader = false;
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
                                   if(key != 'transaction_date'){
                                    this.computedTypeOfAssistance[key] = '';
                                   }
                                }

                                this.computedSameAsAboveFields = false;
                                
                                const url = this.$router.resolve({
                                name: "requestForAssistanceReceipt",
                                params: { id: resp.data.transactionID},
                                }).href;
                                window.open(url, "_blank");
                                this.computedRequestTransactionSuccessToast = true;
                                this.$router.push({ name: 'requestDetails' });   
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
                        this.computedSubmitLoader = true;
                        this.$store.dispatch('submitData', { 'beneficiary': this.computedBeneficiary, 'client': this.computedClient, 'typeOfAssistance': this.computedTypeOfAssistance,  'sameAsAboveFields':this.computedSameAsAboveFields }).then(resp => {
                            if (resp.status === 200 && (resp.data?.failed?.length === undefined || resp.data?.failed?.length <= 0) ) {
                                this.computedSubmitLoader = false;
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
                                   if(key != 'transaction_date'){
                                    this.computedTypeOfAssistance[key] = '';
                                   }
                                }


                                this.computedSameAsAboveFields = false;
                                

                                const url = this.$router.resolve({
                                name: "requestForAssistanceReceipt",
                                params: { id: resp.data.transactionID},
                                }).href;
                                window.open(url, "_blank");

                                this.computedRequestTransactionToast = true;
                                this.$router.push({ name: 'requestDetails' });   
                            }
                        });
                    }
                }
            }

        },
        backToClientBeneficiaryDetails() {
            this.$router.push({ name: "requestDetails" });
        },

    },

    computed: {
        computedRequestTransactionToast:{
            get(){
                return this.$store.state.requestTransactionToast;
            },
            set(value){
                this.$store.commit('setRequestTransactionToast',{'requestTransactionToast':value});
            }
        },
        computedSameAsAboveFields:{
            get(){
                return this.$store.state.sameAsAboveFields;
            },
            set(value){
                this.$store.commit('setSameAsAboveFields',{'sameAsAboveFields':value});
            }
        },
        computedSubmitLoader: {
            get() {
                return this.$store.state.submitLoader;
            },
            set(value) {
                this.$store.commit('setSubmitLoader', { 'submitLoader': value });
            }
        },
        computedFormSeeder: {
            get() {
                return this.$store.state.formSeeder;
            },
        },
        computedTypeOfAssistance: {
            get() {
                return this.$store.state.typeOfAssistance;
            },
            set(value) {
                this.$store.commit("setTypeOfAssistance", {
                    typeOfAssistance: value,
                });
            },
        },
        computedClient: {
            get() {
                return this.$store.state.client;
            },
            set(value) {
                this.$store.commit('setClient', { 'client': value })
            }
        },
        computedBeneficiary: {
            get() {
                return this.$store.state.beneficiary;
            },
            set(value) {
                this.$store.commit('setBeneficiary', { 'beneficiary': value })
            }
        }
    },
    data() {
        return {
            otherTypeOfAssistanceDisabled: true,
            otherDescriptionOfAssistanceDisabled: true,
            proceedValidation: {
                agency: false,
                agency_program: false,
                type_of_assistance: false,
                description_of_assistance: false,
                other_description_of_assistance: false,
                category: false,
                hospital_name: false,
                maip_code: false,
                hospital_type: false,
                transaction_date: false,
            },
            submitLoader: false,
        };
    },
    beforeRouteLeave(to, from, next) {
        next();
    },
    beforeRouteEnter(to, from, next) {
        next();
    },
};
</script>
