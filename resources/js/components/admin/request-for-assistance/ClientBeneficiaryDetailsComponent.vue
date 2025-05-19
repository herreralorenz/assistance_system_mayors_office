<template>
    <LoaderComponent v-if="this.computedFormLoaderSeeder === true "></LoaderComponent>
    
    <Toast />
    <Dialog v-model:visible="dialogVisibleClient" modal header="⚠️Transaction Found⚠️">
        <div>
            <p><strong>{{(this.computedClientCheckerDetails?.client?.[0]?.last_name ?? "")+", "+(this.computedClientCheckerDetails?.client?.[0]?.first_name ?? "")+" "+(this.computedClientCheckerDetails?.client?.[0]?.middle_name ?? "")+" "+(this.computedClientCheckerDetails?.client?.[0]?.suffix?.suffix ?? "")}}</strong></p>
            <div>
                <span>Last Transaction Date: </span><span  style="color:red">{{this.computedClientCheckerDetails?.lastTransaction ?? "" }}</span>
            </div>
            <div>
                <span>Transaction Details: </span><span  style="color:red">{{"("+(this.computedClientCheckerDetails.client?.[0]?.transaction?.[0]?.agency.agency_abbreviation ?? "")+") - "+(this.computedClientCheckerDetails?.client?.[0]?.transaction?.[0]?.assistance_type.assistance_type ?? "" )}}</span>
            </div>
            <div>
                <span>Transaction Status: </span><span  style="color:red">{{this.computedClientCheckerDetails?.transactionStatus }}</span>
            </div>
        </div>
    </Dialog>
    <Dialog v-model:visible="dialogVisibleBeneficiary" modal header="⚠️Transaction Found⚠️">
        <div>
            <p><strong>{{(this.computedBeneficiaryCheckerDetails?.beneficiary?.[0]?.last_name ?? "")+", "+(this.computedBeneficiaryCheckerDetails?.beneficiary?.[0]?.first_name ?? "")+" "+(this.computedBeneficiaryCheckerDetails?.beneficiary?.[0]?.middle_name ?? "")+" "+(this.computedBeneficiaryCheckerDetails?.beneficiary?.[0]?.suffix?.suffix ?? "")}}</strong></p>
            <div>
                <span>Last Transaction Date: </span><span  style="color:red">{{this.computedBeneficiaryCheckerDetails?.lastTransaction ?? "" }}</span>
            </div>
            <div>
                <span>Transaction Details: </span><span  style="color:red">{{"("+(this.computedBeneficiaryCheckerDetails.beneficiary?.[0]?.beneficiary_transaction?.[0]?.agency.agency_abbreviation ?? "")+") - "+(this.computedBeneficiaryCheckerDetails?.beneficiary?.[0]?.beneficiary_transaction?.[0]?.assistance_type.assistance_type ?? "") }}</span>
            </div>
            <div>
            <span>Transaction Status: </span><span  style="color:red">{{this.computedBeneficiaryCheckerDetails?.transactionStatus ?? "" }}</span>
            </div>
        </div>
    </Dialog>          
    <ConfirmDialog group="client">
        <template #message>
            <div>
            <p><strong>{{(this.computedClientCheckerDetails?.client?.[0]?.last_name ?? "")+", "+(this.computedClientCheckerDetails?.client?.[0]?.first_name ?? "")+" "+(this.computedClientCheckerDetails?.client?.[0]?.middle_name ?? "")+" "+(this.computedClientCheckerDetails?.client?.[0]?.suffix?.suffix ?? "")}}</strong></p>
            <div>
                <span>Last Transaction Date: </span><span  style="color:red">{{this.computedClientCheckerDetails?.lastTransaction ?? "" }}</span>
            </div>
            <div>
                <span>Transaction Details: </span><span  style="color:red">{{"("+(this.computedClientCheckerDetails?.client?.[0]?.transaction?.[0]?.agency?.agency_abbreviation ?? "")+") - "+(this.computedClientCheckerDetails?.client?.[0]?.transaction?.[0]?.assistance_type.assistance_type ?? "") }}</span>
            </div>
            <div>
                <span>Transaction Status: </span><span  style="color:red">{{this.computedClientCheckerDetails?.transactionStatus ?? "" }}</span>
            </div>
            </div>
        </template>
    </ConfirmDialog>
    <ConfirmDialog group="beneficiary">
        <template #message>
            <div>
            <p><strong>{{(this.computedBeneficiaryCheckerDetails?.beneficiary?.[0]?.last_name ?? "")+", "+(this.computedBeneficiaryCheckerDetails?.beneficiary?.[0]?.first_name ?? "")+" "+(this.computedBeneficiaryCheckerDetails?.beneficiary?.[0]?.middle_name ?? "")+" "+(this.computedBeneficiaryCheckerDetails?.beneficiary?.[0]?.suffix?.suffix ?? "")}}</strong></p>
            <div>
                <span>Last Transaction Date: </span><span  style="color:red">{{this.computedBeneficiaryCheckerDetails?.lastTransaction ?? "" }}</span>
            </div>
            <div>
                <span>Transaction Details: </span><span  style="color:red">{{"("+(this.computedBeneficiaryCheckerDetails?.beneficiary?.[0]?.beneficiary_transaction?.[0]?.agency?.agency_abbreviation ?? "")+") - "+(this.computedBeneficiaryCheckerDetails?.beneficiary?.[0]?.beneficiary_transaction?.[0]?.assistance_type?.assistance_type ?? "") }}</span>
            </div>
            <div>
                <span>Transaction Status: </span><span  style="color:red">{{this.computedBeneficiaryCheckerDetails?.transactionStatus ?? "" }}</span>
            </div>
            </div>
        </template>
    </ConfirmDialog>

    <div class="form-group client-beneficiary-details py-4" v-if="this.computedFormLoaderSeeder === false">
        <div class="container-fluid">
            <section class="bg-secondary">
            </section>
            <section class="beneficiary">
                <h1>Beneficiary</h1>
                <hr>
                <div class="row">
                    <div class="col-lg-3">
                        <label for="beneficiary_last_name">Last Name</label>
                        <AutoComplete :class="{'d-flex':true, 'invalid-field':this.proceedValidation.beneficiary_last_name}" :suggestions="this.computedAutoCompleteFullNameBeneficiary" v-on:keyup="this.validateFields('beneficiary_last_name')" v-model="this.computedBeneficiary.last_name" :loading="this.autoCompleteLoader.beneficiary_last_name"  @item-select="this.onItemSelectBeneficiary" @hide="onHide">
                            <template #option="{ option }">
                                <div class="custom-item">
                                    <span :data-value="option.id">{{ option.first_name+" "+(option?.middle_name ?? "")+" "+option.last_name+" "+(option.suffix?.suffix ?? "")+" ("+option.birthdate+")" }}</span> 
                                </div>
                            </template>
                        </Autocomplete>
                    </div>
                    <div class="col-lg-3">
                        <label for="beneficiary_first_name">First Name</label>
                        <AutoComplete :class="{'d-flex':true, 'invalid-field':this.proceedValidation.beneficiary_first_name}" :suggestions="this.computedAutoCompleteFullNameBeneficiary" v-on:keyup="this.validateFields('beneficiary_first_name')" v-model="this.computedBeneficiary.first_name" :loading="this.autoCompleteLoader.beneficiary_first_name"  @item-select="this.onItemSelectBeneficiary" @hide="onHide">
                            <template #option="{ option }">
                                <div class="custom-item">
                                    <span :data-value="option.id">{{ option.first_name+" "+(option?.middle_name ?? "")+" "+option.last_name+" "+(option.suffix?.suffix ?? "")+" ("+option.birthdate+")" }}</span> 
                                </div>
                            </template>
                        </AutoComplete>
                    </div>
                    <div class="col-lg-3">
                        <label for="beneficiary_middle_name">Middle Name</label>
                        <AutoComplete :class="{'d-flex':true, 'invalid-field':this.proceedValidation.beneficiary_middle_name}" :suggestions="this.computedAutoCompleteFullNameBeneficiary" v-on:keyup="this.validateFields('beneficiary_middle_name')" v-model="this.computedBeneficiary.middle_name" :loading="this.autoCompleteLoader.beneficiary_middle_name"  @item-select="this.onItemSelectBeneficiary" @hide="onHide">
                        <template #option="{ option }">
                                <div class="custom-item">
                                    <span :data-value="option.id">{{ option.first_name+" "+(option?.middle_name ?? "")+" "+option.last_name+" "+(option.suffix?.suffix ?? "")+" ("+option.birthdate+")" }}</span> 
                                </div>
                        </template>
                        </AutoComplete>
                    </div>
                    <div class="col-lg-3">
                        <label for="beneficiary_suffix">Suffix</label>
                        <select id="beneficiary_suffix"
                            :class="{ 'form-select': true, 'is-invalid': proceedValidation.beneficiary_suffix }"
                            v-model="computedBeneficiary.suffix" class="custom-select form-select">
                            <option value="" selected></option>
                            <option v-for="(option, index) in this.computedFormSeeder.suffix" :key="option.id"
                                :value="option.id">{{ option.suffix }}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <label for="beneficiary_birthdate">Birthdate</label>
                        <input type="date" class="form-control" id="beneficiary_birthdate"
                            :class="{ 'form-control': true, 'is-invalid': proceedValidation.beneficiary_birthdate }"
                            v-model="computedBeneficiary.birthdate" @change="calculateAge('beneficiary')">
                    </div>
                    <div class="col-lg-1">
                        <label for="beneficiary_age">Age</label>
                        <input :class="{ 'form-control': true, 'is-invalid': proceedValidation.beneficiary_age }"
                            id="beneficiary_age" type="text" v-model="computedBeneficiary.age" disabled>
                    </div>
                    <div class="col-lg-4">
                        <label for="beneficiary_sex">Sex</label>
                        <select id="beneficiary_sex"
                            :class="{ 'form-select': true, 'is-invalid': proceedValidation.beneficiary_sex }"
                            @change="validateFields('beneficiary_select_sex')" v-model="computedBeneficiary.sex"
                            class="custom-select form-select">
                            <option value="" selected></option>
                            <option v-for="(option, index) in this.computedFormSeeder.sex" :key="option.id"
                                :value="option.id">{{ option.sex }}</option>
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label for="beneficiary_civil_status">Civil Status</label>
                        <select v-model="this.computedBeneficiary.civil_status"
                            :class="{ 'form-select': true, 'is-invalid': proceedValidation.beneficiary_civil_status }"
                            @change="validateFields('beneficiary_select_civil_status')" id="beneficiary_civil_status" class="custom-select form-select">
                            <option value="" selected></option>
                            <option v-for="(option, index) in this.computedFormSeeder.civil_status" :key="option.id"
                                :value="option.id">{{ option.civil_status }}</option>
                        </select>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <label for="beneficiary_street">Street/Purok</label>
                        <input class="form-control"
                            :class="{ 'form-control': true, 'is-invalid': proceedValidation.beneficiary_street }"
                             v-model="computedBeneficiary.street" id="beneficiary_street"
                            type="text">
                    </div>
                    <div class="col-lg-4">
                        <label for="beneficiary_barangay">Barangay</label>
                        <select id="beneficiary_barangay"
                            :class="{ 'form-select': true, 'is-invalid': proceedValidation.beneficiary_barangay }"
                            @change="validateFields('beneficiary_select_barangay')" v-model="computedBeneficiary.barangay">
                            <option value="" selected></option>
                            <option
                                v-for="(option, index) in this.computedFormSeeder.address_metadata[0]['address_metadata']['4A']['province_list']['CAVITE']['municipality_list']['GENERAL TRIAS CITY']['barangay_list']"
                                :key="index" :value="index">{{ option }}</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label for="beneficiary_city">City</label>
                        <input class="form-control"
                            :class="{ 'form-control': true, 'is-invalid': proceedValidation.beneficiary_city }"
                            v-on:keyup="validateFields('beneficiary_city')" id="beneficiary_city"
                            v-model="computedBeneficiary.city" type="text" disabled>
                    </div>
                    <div class="col-lg-2">
                        <label for="beneficiary_province">Province</label>
                        <input class="form-control"
                            :class="{ 'form-control': true, 'is-invalid': proceedValidation.beneficiary_province }"
                            v-on:keyup="validateFields('beneficiary_province')" id="beneficiary_province"
                            v-model="computedBeneficiary.province" type="text" disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <label for="beneficiary_contact_number">Contact Number</label>
                        <input
                            :class="{ 'form-control': true, 'is-invalid': proceedValidation.beneficiary_contact_number, }"
                            id="beneficiary_contact_number" v-on:keyup="handleContactNumber('beneficiary')"
                            v-model="computedBeneficiary.contact_number" type="text">
                    </div>
                    <div class="col-lg-2">
                        <label for="beneficiary_id_type">ID Type</label>
                        <select id="beneficiary_id_type" @change="handleIDType('beneficiary')"
                            v-model="computedBeneficiary.id_type" class="custom-select form-select">
                            <option value="" selected></option>
                            <option v-for="(option, index) in this.computedFormSeeder.id_type" :key="option.id"
                                :value="option.id">{{ option.id_type }}</option>
                            <option value="OTHER">OTHER ID</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label for="beneficiaryOtherId">Other ID</label>
                        <input :class="{ 'form-control': true, 'is-invalid': proceedValidation.beneficiary_other_id_type }"
                            v-on:keyup="handleOtherIDType('beneficiary')" id="beneficiaryOtherId"
                            v-model="computedBeneficiary.other_id_type" type="text"
                            :disabled="this.beneficiary_other_id_type_disabled">
                    </div>
                    <div class="col-lg-3">
                        <label for="beneficiary_id_number">ID Number</label>
                        <input :class="{ 'form-control': true, 'is-invalid': proceedValidation.beneficiary_id_number }"
                            id="beneficiary_id_number" v-on:keyup="handleIDNumber('beneficiary')"
                            v-model="computedBeneficiary.id_number" type="text"
                            :disabled="this.beneficiary_id_number_disabled">
                    </div>
                    <div class="col-lg-2">
                        <input :checked="!this.beneficiary_precint_disabled" type="checkbox" @click="handleRegisteredVoter('beneficiary')" id="beneficiaryCheckIsVoter"
                            aria-label="Checkbox for following text input">
                        <label class="form-check-label" for="beneficiaryCheckIsVoter">Registered Voter?</label>
                        <input class="form-control"
                            :class="{ 'form-control': true, 'is-invalid': proceedValidation.beneficiary_precint }"
                            v-on:keyup="precintCheck('beneficiary')" id="beneficiaryIsVoter"
                            placeholder="Precint Number" type="text" v-model="computedBeneficiary.precint"
                            :disabled="this.beneficiary_precint_disabled">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <label for="beneficiary_occupation">Occupation</label>
                        <input class="form-control" v-on:keyup="this.validateFields('beneficiary')"
                            id="beneficiary_occupation" v-model="computedBeneficiary.occupation" type="text">
                    </div>
                    <div class="col-lg-6">
                        <label for="beneficiary_monthly_income">Monthly Income</label>
                        <input
                            :class="{ 'form-control': true, 'is-invalid': proceedValidation.beneficiary_monthly_income }"
                            id="beneficiary_monthly_income" v-on:keyup="handleMonthlyIncome('beneficiary')"
                            v-model="computedBeneficiary.monthly_income" type="text">
                    </div>
                </div>
                <button type="button" class="mt-2 btn btn-outline-success" @click="this.showBeneficiaryImageModal()">
                    <i class="fa fa-camera"></i> UPLOAD / CAPTURE IMAGE
                </button>
            </section>
            <section class="client mt-4">
                <h1>Client</h1>
                <input v-model="this.computedSameAsAboveFields" @click="sameAsAboveFields()" type="checkbox"
                    id="clientSameAbove" aria-label="Checkbox for following text input">
                <label class="form-check-label" for="clientSameAbove">Same above fields</label>
                <hr>
                <div class="row">
                    <div class="col-lg-3">
                        <label for="client_last_name">Last Name</label>
                        <AutoComplete :class="{'d-flex':true, 'invalid-field':this.proceedValidation.client_last_name}" :suggestions="this.computedAutoCompleteFullNameClient" v-on:keyup="this.validateFields('client_last_name')" v-model="this.computedClient.last_name" :loading="this.autoCompleteLoader.client_last_name"  @item-select="this.onItemSelectClient" @hide="onHide">
                            <template #option="{ option }">
                                <div class="custom-item">
                                    <span :data-value="option.id">{{ option.first_name+" "+(option?.middle_name ?? "")+" "+option.last_name+" "+(option.suffix?.suffix ?? "")+" ("+option.birthdate+")" }}</span> 
                                </div>
                            </template>
                        </Autocomplete>
                    </div>
                    <div class="col-lg-3">
                        <label for="client_first_name">First Name</label>
                        <AutoComplete :class="{'d-flex':true, 'invalid-field':this.proceedValidation.client_first_name}" :suggestions="this.computedAutoCompleteFullNameClient" v-on:keyup="this.validateFields('client_first_name')" v-model="this.computedClient.first_name" :loading="this.autoCompleteLoader.client_first_name"  @item-select="this.onItemSelectClient" @hide="onHide">
                            <template #option="{ option }">
                                <div class="custom-item">
                                    <span :data-value="option.id">{{ option.first_name+" "+(option?.middle_name ?? "")+" "+option.last_name+" "+(option.suffix?.suffix ?? "")+" ("+option.birthdate+")" }}</span> 
                                </div>
                            </template>
                        </Autocomplete>
                    </div>
                    <div class="col-lg-3">
                        <label for="clientMiddlename">Middle Name</label>
                        <AutoComplete :class="{'d-flex':true, 'invalid-field':this.proceedValidation.client_middle_name}" :suggestions="this.computedAutoCompleteFullNameClient" v-on:keyup="this.validateFields('client_middle_name')" v-model="this.computedClient.middle_name" :loading="this.autoCompleteLoader.client_middle_name"  @item-select="this.onItemSelectClient" @hide="onHide">
                            <template #option="{ option }">
                                <div class="custom-item">
                                    <span :data-value="option.id">{{ option.first_name+" "+(option?.middle_name ?? "")+" "+option.last_name+" "+(option.suffix?.suffix ?? "")+" ("+option.birthdate+")" }}</span> 
                                </div>
                            </template>
                        </Autocomplete>
                    </div>
                    <div class="col-lg-3">
                        <label for="client_suffix">Suffix</label>
                        <select id="client_suffix" v-model="this.computedClient.suffix" class="custom-select form-select"
                            v-on:keyup="validateFields('client_suffix')">
                            <option value="" selected></option>
                            <option v-for="(option, index) in this.computedFormSeeder.suffix" :key="option.id"
                                :value="option.id">{{ option.suffix }}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <label for="client_birthdate">Birthdate</label>
                        <input type="date" v-model="this.computedClient.birthdate"
                            :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.client_birthdate }"
                            @change="calculateAge('client')" id="client_birthdate">
                    </div>
                    <div class="col-lg-1">
                        <label for="client_age">Age</label>
                        <input :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.client_age }"
                            v-model="this.computedClient.age" id="client_age" type="text" disabled>
                    </div>
                    <div class="col-lg-4">
                        <label for="client_sex">Sex</label>
                        <select id="client_sex" @change="validateFields('client_select_sex')" v-model="this.computedClient.sex"
                            :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.client_sex }">
                            <option value="" selected></option>
                            <option v-for="(option, index) in this.computedFormSeeder.sex" :key="option.id"
                                :value="option.id">{{ option.sex }}</option>
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label for="client_civil_status">Civil Status</label>
                        <select id="client_civil_status" v-model="this.computedClient.civil_status"
                            @change="validateFields('client_select_civil_status')"
                            :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.client_civil_status }">
                            <option value="" selected></option>
                            <option v-for="(option, index) in this.computedFormSeeder.civil_status" :key="option.id"
                                :value="option.id">{{ option.civil_status }}</option>
                        </select>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <label for="client_street">Street/Purok</label>
                        <input class="form-control" v-on:keyup="validateFields('client_street')"
                            v-model="this.computedClient.street" id="client_street" type="text">
                    </div>
                    <div class="col-lg-4">
                        <label for="client_barangay">Barangay</label>
                        <select id="client_barangay" v-model="this.computedClient.barangay"
                            @change="validateFields('client_select_barangay')"
                            :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.client_barangay }">
                            <option value="" selected></option>
                            <option
                                v-for="(option, index) in this.computedFormSeeder.address_metadata[0]['address_metadata']['4A']['province_list']['CAVITE']['municipality_list']['GENERAL TRIAS CITY']['barangay_list']"
                                :key="index" :value="index">{{ option }}</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label for="client_city">City</label>
                        <input :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.client_city }"
                            v-model="this.computedClient.city" id="client_city" type="text" disabled>
                    </div>
                    <div class="col-lg-2">
                        <label for="client_province">Province</label>
                        <input :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.client_province }"
                            v-model="this.computedClient.province" id="client_province" type="text" disabled>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-2">
                        <label for="client_contact_number">Contact Number</label>
                        <input
                            :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.client_contact_number }"
                            v-on:keyup="handleContactNumber('client')"
                            v-model="this.computedClient.contact_number" id="client_contact_number" type="text">
                    </div>
                    <div class="col-lg-2">
                        <label for="client_id_type">ID Type</label>
                        <select @change="handleIDType('client')" id="client_id_type"
                            v-model="this.computedClient.id_type" class="custom-select form-select">
                            <option value="" selected></option>
                            <option v-for="(option, index) in this.computedFormSeeder.id_type" :key="option.id"
                                :value="option.id">{{ option.id_type }}</option>
                            <option value="OTHER">OTHER ID</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label for="clientOtherId">Other ID</label>
                        <input :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.client_other_id_type }"
                            id="clientOtherId" v-model="computedClient.other_id_type" type="text"
                            v-on:keyup="validateFields('clientOtherId')" :disabled="this.client_other_id_type_disabled">
                    </div>
                    <div class="col-lg-3">
                        <label for="client_id_number">ID Number</label>
                        <input :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.client_id_number }"
                            v-model="this.computedClient.id_number" id="client_id_number"
                            v-on:keyup="validateFields('client_id_number')" type="text"
                            :disabled="this.client_id_number_disabled">
                    </div>
                    <div class="col-lg-2">
                        <input @click="this.handleRegisteredVoter('client')" :checked="!this.client_precint_disabled"
                            type="checkbox" id="clientCheckIsVoter" aria-label="Checkbox for following text input">
                        <label class="form-check-label" for="clientCheckIsVoter">Registered Voter?</label>
                        <input :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.client_precint }"
                            v-model="this.computedClient.precint"
                            v-on:keyup="this.precintCheck('client')" id="clientIsVoter"
                            placeholder="Precint Number" type="text" :disabled="this.client_precint_disabled">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <label for="client_occupation">Occupation</label>
                        <input class="form-control" v-on:keyup="this.validateFields('client')"
                            v-model="this.computedClient.occupation" id="client_occupation" type="text">
                    </div>
                    <div class="col-lg-4">
                        <label for="client_monthly_income">Monthly Income</label>
                        <input
                            :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.client_monthly_income }"
                            v-on:keyup="handleMonthlyIncome('client')"
                            v-model="this.computedClient.monthly_income" id="client_monthly_income" type="text">
                    </div>
                    <div class="col-lg-4">
                        <label for="client_relationship">Relationship to beneficiary</label>
                        <input v-model="this.computedClient.relationship" type="text" id="client_relationship"
                            v-on:keyup="validateFields('relationship')"
                            :class="{ 'form-control': true, 'is-invalid': this.proceedValidation.client_relationship }">
                    </div>
                </div>
                <button type="button" class="mt-2 btn btn-outline-success" @click="this.showClientImageModal()">
                    <i class="fa fa-camera"></i> UPLOAD / CAPTURE IMAGE
                </button>
            </section>
            <div class="d-flex justify-content-center align-items-center">
                <button class="btn bg-success text-white w-100 d-flex justify-content-center align-items-center mt-3"
                    style="height: 50px; font-size: 20px;" @click="navigateToSubmit()">Next</button>
            </div>
        </div>
    </div>
    <ClientImageModalComponent ref="clientImageModal" v-model:visible="this.clientImageModalVisible">
    </ClientImageModalComponent>
    <BeneficiaryImageModalComponent ref="beneficiaryImageModal" v-model:visible="this.beneficiaryImageModalVisible">
    </BeneficiaryImageModalComponent>

</template>

<style scoped>

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



.message {
    padding-left: 250px;

}

input,
select,
textarea,
button {
    border-radius: 25px;

}

select:invalid,
input:invalid {
    border: 1px solid red;

}

.client-beneficiary-details {
    /* margin: 0; */
    padding-left: 250px;
    overflow-y: scroll;
    
    height: -webkit-calc(100% - 160px);
    height: -moz-calc(100% - 160px);
    height: calc(100% - 160px);
    /* height: 100%;
    display: flex;
    flex-direction: column; */
}

.beneficiary,
.client {

    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
}
</style>
<script>
import { ref } from 'vue';
import { computed } from 'vue';
import ClientImageModalComponent from '../request-for-assistance/ClientImageModalComponent.vue';
import BeneficiaryImageModalComponent from '../request-for-assistance/BeneficiaryImageModalComponent.vue';
import LoaderComponent from '../Loader2.vue';
import { mapActions } from 'vuex';
import Message from 'primevue/message';
import AutoComplete from 'primevue/autocomplete';
import ConfirmDialog from 'primevue/confirmdialog';
import { useConfirm } from "primevue/useconfirm";
import Dialog from 'primevue/dialog';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import auth from '../../../router/auth_roles_permissions.js';

export default {
    components: {
        ClientImageModalComponent,
        BeneficiaryImageModalComponent,
        LoaderComponent,
        Message,
        AutoComplete,
        ConfirmDialog,
        Dialog,
        Toast
    },
    beforeMount() {

        this.computedFormLoaderSeeder = true;
        this.computedHeaderText = 'Request for Assistance';

        if (Array.isArray(this.computedFormSeeder) && this.computedFormSeeder.length <= 0) {
            this.fetchSeeders().then(response => {
                this.computedFormLoaderSeeder = false;
            }).catch(error => {
                console.error(error); // Handle error
                return Promise.reject(error); // Reject the promise with error
            });
        } else {
            this.computedFormLoaderSeeder = false;
               
        }

        

    },
    watch: {

    },
    updated(){
      
    },
    mounted() {

         document.body.style.overflow = 'hidden';
        this.confirm = useConfirm();
        this.toast = useToast();

        if(this.computedRequestTransactionToast){
            this.toast.add({ severity: 'success', summary: 'Success', detail: 'Request successfully submitted.', life: 5000 });
        }

        window.scrollTo({
            top: 0,
            behavior: 'instant' // Optional: adds smooth scrolling effect
        });

        this.$store.state.progressActive = 1;
        this.$store.state.progressBarWidth = '25%';


    },
    beforeUnmount() {
        this.computedRequestTransactionToast = false;
    
    },
    methods: {
        onHide(event){

        },
        onItemSelectClient(event){

            for(const [key,value] of Object.entries(this.proceedValidation)){
                if(key.includes('client')){
                    this.proceedValidation[key] = false;
                }
            }
            this.computedClient.last_name = event.value.last_name;
            this.computedClient.first_name = event.value.first_name;
            this.computedClient.middle_name = event.value?.middle_name ?? "";
            this.computedClient.suffix = event.value?.suffix_id ?? "";
            this.computedClient.birthdate = event.value.birthdate;
            this.computedClient.age = event.value.age;
            this.computedClient.sex = event.value.sex_id;
            this.computedClient.civil_status = event.value.civil_status_id;
            this.computedClient.street = event.value?.street ?? "";
            this.computedClient.barangay = event.value.barangay_id;
            this.computedClient.city = event.value.city;
            this.computedClient.province = event.value.province;
            this.computedClient.contact_number = event.value.contact_number[0].contact_number;
            if(event.value?.client_identification){
                this.computedClient.id_type = event.value.client_identification?.[0]?.id ?? "";
                this.computedClient.other_id_type = '';
                this.computedClient.id_number = event.value.client_identification?.[0]?.id_number ?? "";
                this.client_other_id_type_disabled = true;
                this.client_id_number_disabled = false;
            }else if(event.value?.client_identification?.other_identification_type){
                this.computedClient.id_type = '';
                this.computedClient.other_id_type = event.value.client_identification?.other_identification_type?.other_id_type ?? "";
                this.computedClient.id_number = event.value.client_identification?.[0]?.id_number ?? "";
                this.client_id_number_disabled = false;
                this.client_other_id_type_disabled = false;
            }else{
                this.client_id_number_disabled = true;
                this.client_other_id_type_disabled = true; 
                this.computedClient.id_type = '';
                this.computedClient.other_id_type = '';
            }

            if(event.value?.precint){
                this.client_precint_disabled = false;
                this.computedClient.precint = event.value.precint.precint
            }

            if(event.value?.client_occupation){
                this.computedClient.occupation = event.value.client_occupation[0]?.occupation ?? ""
            }

            if(event.value?.client_occupation?.[0]?.pivot?.monthly_income){
                this.computedClient.monthly_income = event.value?.client_occupation?.[0]?.pivot?.monthly_income ?? "";
            }

            this.$store.dispatch('fetchClientChecker',{'client':this.computedClient}).then(response =>{
                if(response.data.repeatAssistance){
                    this.dialogVisibleClient = true;
                }
            });

            
        },
        onItemSelectBeneficiary(event){


            for(const [key,value] of Object.entries(this.proceedValidation)){
                if(key.includes('beneficiary')){
                    this.proceedValidation[key] = false;
                }
            }

            this.computedBeneficiary.last_name = event.value.last_name;
            this.computedBeneficiary.first_name = event.value.first_name;
            this.computedBeneficiary.middle_name = event.value?.middle_name ?? "";
            this.computedBeneficiary.suffix = event.value?.suffix_id ?? "";
            this.computedBeneficiary.birthdate = event.value.birthdate;
            this.computedBeneficiary.age = event.value.age;
            this.computedBeneficiary.sex = event.value.sex_id;
            this.computedBeneficiary.civil_status = event.value.civil_status_id;
            this.computedBeneficiary.street = event?.value?.street ?? "";
            this.computedBeneficiary.barangay = event.value.barangay_id;
            this.computedBeneficiary.city = event.value.city;
            this.computedBeneficiary.province = event.value.province;
            this.computedBeneficiary.contact_number = event.value.contact_number[0].contact_number;
            if(event.value?.beneficiary_identification){
                this.computedBeneficiary.id_type = event.value.beneficiary_identification?.[0]?.id ?? "";
                this.computedBeneficiary.other_id_type = '';
                this.computedBeneficiary.id_number = event.value.beneficiary_identification?.[0]?.id_number ?? "";
                this.beneficiary_other_id_type_disabled = true;
                this.beneficiary_id_number_disabled = false;
            }else if(event.value?.beneficiary_identification?.other_identification_type){
                this.computedBeneficiary.id_type = '';
                this.computedBeneficiary.other_id_type = event.value.beneficiary_identification?.other_identification_type?.other_id_type ?? "";
                this.computedBeneficiary.id_number = event.value.beneficiary_identification?.[0]?.id_number ?? "";
                this.beneficiary_id_number_disabled = false;
                this.beneficiary_other_id_type_disabled = false;
            }else{
                this.beneficiary_id_number_disabled = true;
                this.beneficiary_other_id_type_disabled = true; 
                this.computedBeneficiary.id_type = '';
                this.computedBeneficiary.other_id_type = '';
            }

            if(event.value?.precint){
                this.beneficiary_precint_disabled = false;
                this.computedBeneficiary.precint = event.value.precint.precint
            }

            if(event.value?.beneficiary_occupation){
                this.computedBeneficiary.occupation = event.value.beneficiary_occupation?.[0]?.occupation ?? ""
            }

            if(event.value?.beneficiary_occupation?.[0]?.pivot?.monthly_income){
                this.computedBeneficiary.monthly_income = event.value?.beneficiary_occupation?.[0]?.pivot?.monthly_income ?? "";
            }

            this.$store.dispatch('fetchBeneficiaryChecker',{'beneficiary':this.computedBeneficiary}).then(response => {
                if(this.computedBeneficiaryCheckerDetails){
                    this.dialogVisibleBeneficiary = true;
                }
            });
        
    
            for(const [key,value] of Object.entries(this.autoCompleteLoader)){
                this.autoCompleteLoader[key] = false;
            }
        },
        fetchSeeders() {
            return this.$store.dispatch('fetchSeeders');
        },
        showClientImageModal() {
            this.clientImageModalVisible = true;
        },
        showBeneficiaryImageModal() {
            this.beneficiaryImageModalVisible = true;
        },
        navigateToSubmit() {
            this.$router.push({ name: 'requestSubmit' });
        },
        validateFields(field){

            

            if(field.includes('proceedButton')){
    
                const validationParams = {
                    'beneficiary':{
                        'last_name': ['required'],
                        'first_name': ['required'],
                        'middle_name': ['not_required'],
                        'suffix':['not_required'],
                        'birthdate':['required'],
                        'age':['calculateAge'],
                        'sex':['required'],
                        'civil_status':['required'],
                        'street':['not_required'],
                        'region':['required'],
                        'barangay':['required'],
                        'city':['required'],
                        'province':['required'],
                        'precint':['precintCheck'],
                        'contact_number':['handleContactNumber'],
                        'id_type':['not_required'],
                        'id_number':['handleIDNumber'],
                        'other_id_type':['handleOtherIDType'],
                        'occupation':['not_required'],
                        'monthly_income':['handleMonthlyIncome'],
                        'beneficiary_image':['not_required']
                    },
                    'client':{
                        'last_name': ['required'],
                        'first_name': ['required'],
                        'middle_name': ['not_required'],
                        'suffix':['not_required'],
                        'birthdate':['required'],
                        'age':['calculateAge'],
                        'sex':['required'],
                        'civil_status':['required'],
                        'street':['not_required'],
                        'region':['required'],
                        'barangay':['required'],
                        'city':['required'],
                        'province':['required'],
                        'precint':['precintCheck'],
                        'contact_number':['handleContactNumber'],
                        'id_type':['not_required'],
                        'id_number':['handleIDNumber'],
                        'other_id_type':['handleOtherIDType'],
                        'occupation':['not_required'],
                        'monthly_income':['handleMonthlyIncome'],
                        'relationship':['required'],
                        'beneficiary_image':['not_required']
                    }
                }


                for(const [key1,value1] of Object.entries(validationParams)){
                    for(const [key2,value2] of Object.entries(value1)){
                            if(!value2.includes('required') && !value2.includes('not_required')){
                                    this[value2[0]](key1);
                            }else{
                                if(value2.includes('required')){
                                    if( this.$store.state[key1][key2] && this.$store.state[key1][key2] != ''){
                                        this.proceedValidation[key1+"_"+key2] = false;
                                    }else{
                                        this.proceedValidation[key1+"_"+key2] = true;
                                    } 
                                }else{
                                    this.proceedValidation[key1+"_"+key2] = false;
                                }        
                            }
                           
                    }
                }
            }else if(field.includes('name')){
       
                    let who = field.split('_')[0];
                    let dispatch = field.split('_')[0].charAt(0).toUpperCase()+field.split('_')[0].slice(1);
                    let payload = {};
                    payload[who] = this.$store.state[who];
                    console.log(who);
                    this.$store.state[who][field.split('_')[1]+"_"+field.split('_')[2]] = this.$store.state[who][field.split('_')[1]+"_"+field.split('_')[2]].toUpperCase();


                    if(this.$store.state[who][field.split('_')[1]+"_"+field.split('_')[2]] != '' ){
                        clearTimeout(this.timeout);

                        this.timeout = setTimeout(() => {
                            this.autoCompleteLoader[field] = true;
                                
                            this.$store.dispatch('autoCompleteFullName'+dispatch,payload).then(response => {
                                this.proceedValidation[field] = false;
                                this.autoCompleteLoader[field] = false;
                            });
                            this.autoCompleteLoader[field] = false;

                        }, 250);
                    }else{
                        this.proceedValidation[field] = true;
                    }
                   
            }else if(field.includes('select')){

                const selectValidation = {
                    'client':{
                        'sex':['required'],
                        'civil_status':['required'],
                        'barangay':['required'],
                    },
                    'beneficiary':{
                        'sex':['required'],
                        'civil_status':['required'],
                        'barangay':['required'],
                    }
                };


                for(const [key1,value1] of Object.entries(selectValidation)){
                    for(const[key2,value2] of Object.entries(value1)){
                        if(field.split('_')[0] === key1 && (field.split('_')[2] === key2 || (field.split('_')[2]+"_"+field.split('_')[3] === key2))){
                            if(value2[0] === 'required'){

                                if(this.$store.state[key1][key2] === ''){
                                    this.proceedValidation[`${key1}_${key2}`] = true;
                                }else{
                                    this.proceedValidation[`${key1}_${key2}`] = false;

                                }
                            }
                        }
                    }
                }
            }else{

                if(field === 'relationship'){
                    if(typeof this.computedClient.relationship === 'string' && this.computedClient.relationship != ''){
                        this.computedClient.relationship = this.computedClient.relationship.toUpperCase();
                        this.proceedValidation.client_relationship = false;
                    }else{
                        this.proceedValidation.client_relationship = true;
                    }
                }else{
                    this.clientOccupation(field)
                }

            }
         
        },
        clientOccupation(who){
            if(typeof this.$store.state[who]['occupation'] === 'string' && this.$store.state[who]['occupation']){
                this.$store.state[who]['occupation'] = this.$store.state[who]['occupation'].toUpperCase();
            }
        },
        precintCheck(who){
            if(!this[`${who}_precint_disabled`] && this.$store.state[who]['precint'] == ''){
                this.proceedValidation[`${who}_precint`] = true;
            }else{
                if(typeof this.$store.state[who]['precint'] === 'string' && this.$store.state[who]['precint'] &&this.$store.state[who]['precint'] != ''){
                    this.$store.state[who]['precint'] = this.$store.state[who]['precint'].toUpperCase();
                }
                 this.proceedValidation[`${who}_precint`] = false;
            }
        },
        calculateAge(who){

            if (!this.$store.state[who]['birthdate'] || this.$store.state[who]['birthdate']  == ''){
                this.$store.state[who]['age'] = '';
            } 

            const today = new Date();
            const birthDate = new Date(this.$store.state[who]['birthdate']);

            let age = today.getFullYear() - birthDate.getFullYear();


            if (((today.getMonth() - birthDate.getMonth() <= 0) && (today.getDate() < birthDate.getDate()))) {
                age--;
            }

            if (age <= 0 || isNaN(age)) {
                age = 0;
                this.proceedValidation[who+"_birthdate"] = true;
                this.proceedValidation[who+"_age"] = true;
            }else{
                this.proceedValidation[who+"_birthdate"]  = false;
                this.proceedValidation[who+"_age"]  = false;
            }



            this.$store.state[who]['age'] = age;


            return age;
        },
        handleIDType(who){

            if(this.$store.state[who]['id_type'] === ''){
                this[`${who}_id_number_disabled`] = true;
                this[`${who}_other_id_type_disabled`] = true;
            }else if(this.$store.state[who]['id_type'] === 'OTHER'){
                this[`${who}_other_id_type_disabled`] = false;
                this[`${who}_id_number_disabled`] = false;
            }else{
                this[`${who}_other_id_type_disabled`] = true;
                this[`${who}_id_number_disabled`] = false;
            }

            this.$store.state[who].other_id_type = '';
            this.$store.state[who].id_number = '';
            this.proceedValidation[`${who}_other_id_type`] = false;
            this.proceedValidation[`${who}_id_number`] = false;
        },
        handleIDNumber(who){

            if(this.$store.state[who]['id_number'] && this.$store.state[who]['id_number'] != '' && typeof this.$store.state[who]['id_number'] === "string"){
                this.$store.state[who]['id_number'] = this.$store.state[who]['id_number'].toUpperCase();
            }
           
            if((this.$store.state[who]['id_type'] == '') || (this.$store.state[who]['id_type'] != '' && (this.$store.state[who]['id_number'] != ''))){
                this.proceedValidation[`${who}_id_number`] = false;
            }else{
                this.proceedValidation[`${who}_id_number`] = true;
            }

        },
        handleOtherIDType(who){

            if(this.$store.state[who]['other_id_type'] &&  this.$store.state[who]['other_id_type'] != '' && typeof this.$store.state[who]['other_id_type'] === 'string'){
                this.$store.state[who]['other_id_type'] = this.$store.state[who]['other_id_type'].toUpperCase();
            }
           
            if((this.$store.state[who]['id_type'] == 'OTHER' && this.$store.state[who]['other_id_type'] != '') || (this.$store.state[who]['id_type'] === '') || (this.$store.state[who]['id_type'] != 'OTHER')){
                this.proceedValidation[`${who}_other_id_type`] = false;
            }else{
                this.proceedValidation[`${who}_other_id_type`] = true;
            }
        },
        handleRegisteredVoter(who) {

            this[`${who}_precint_disabled`] =  !this[`${who}_precint_disabled`];

            this.proceedValidation[`${who}_precint`] = false;
            this.$store.state[who]['precint'] = '';
        },
     
        handleContactNumber(who) {

            if (/^(09|\+639)\d{9}$/.test(this.$store.state[who]['contact_number'])) {
                this.proceedValidation[`${who}_contact_number`] = false;
            }else{
                this.proceedValidation[`${who}_contact_number`] = true;
            }
    
        },
        handleMonthlyIncome(who) {

            if (/^$|(([0-9]+([.][0-9]*)?|[.][0-9]))$/.test(this.$store.state[who]['monthly_income'])) {
                this.proceedValidation[`${who}_monthly_income`] = false;
            } else {
                this.proceedValidation[`${who}_monthly_income`] = true;
            }
        },
        sameAsAboveFields(){
            this.computedSameAsAboveFields = !this.computedSameAsAboveFields;

            const sameAs = {
                'last_name':'same',
                'first_name':'same',
                'middle_name':'same',
                'suffix':'same',
                'birthdate':'same',
                'age':'same',
                'sex':'same',
                'civil_status':'same',
                'street':'same',
                'barangay':'same',
                'city':'same',
                'province':'same',
                'region':'same',
                'contact_number':'same',
                'id_type':'same',
                'other_id_type':() => { 
                    if(this.computedBeneficiary.id_type == 'OTHER'){
                        this.client_other_id_type_disabled = false;
                        this.computedClient.other_id_type = this.computedBeneficiary.other_id_type;
                        this.proceedValidation[`client_other_id_type`] = false;
                    }
                },
                'id_number':() =>{
                    if(this.computedBeneficiary.id_type != ''){
                        this.client_id_number_disabled = false;
                        this.computedClient.id_number = this.computedBeneficiary.id_number;
                        this.proceedValidation[`client_id_number`] = false;
                    }
                },
                'precint':() =>{
                    if(this.computedBeneficiary.precint != ''){
                        this.client_precint_disabled = false;
                        this.computedClient.precint = this.computedBeneficiary.precint;
                        this.proceedValidation[`client_precint`] = false;
                    }
                },
                'occupation':'same',
                'monthly_income':'same',
            };

            if(this.computedSameAsAboveFields){
                for(const [key,value] of Object.entries(sameAs)){
                    if(value === 'same'){
                        this.computedClient[key] = this.computedBeneficiary[key];
                        this.proceedValidation[`client_${key}`] = false;
                    }else{
                        sameAs[key]();
                        this.computedClient.relationship = false;
                    }
                }
                this.computedClient.relationship = 'HIMSELF';

                this.computedClient.client_image = this.computedBeneficiary.beneficiary_image;

                const img = new Image();

                const canvas = this.$refs.clientImageModal.$refs.clientCanvas;
                const ctx = canvas.getContext('2d');

                img.src = this.computedClient.client_image;

                img.onload = function () {
                    canvas.width = img.width;
                    canvas.height = img.width;
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                }
            }else{
                for(const [key,value] of Object.entries(this.computedClient)){
                    if(key != 'client_image' && key != 'city' && key != 'province' && key != 'region'){
                        this.computedClient[key] = '';
                    }else{
                        const img = new Image();

                        const canvas = this.$refs.clientImageModal.$refs.clientCanvas;
                        const ctx = canvas.getContext('2d');

                        img.src = "/storage/images/cityofgeneraltrias.webp";
                        this.computedClient.client_image = '';

                        img.onload = function () {
                            var hRatio = canvas.width / img.width;
                            var vRatio = canvas.height / img.height;
                            var ratio = Math.min(0.5, 0.5);
                            var centerShift_x = (canvas.width - (img.width * ratio)) / 2;
                            var centerShift_y = (canvas.height - (img.height * ratio)) / 2;

                            ctx.clearRect(0, 0, canvas.width, canvas.height);
                            ctx.drawImage(img, centerShift_x, centerShift_y, img.width * ratio, img.height * ratio);

                        }
                    }
                }
            }
        },
    },
    computed: {
        computedFetchAuthUserRolesPermissions:{
                get(){
                        return this.$store.state.authCheckUserRolesPermissions;
                },
                set(value){
                        this.$store.commit('setAuthCheckUserRolesPermissions',{'authCheckUserRolesPermissions':value});     
                }
        },
        computedAuthCheckUserRolesPermissionsLoader:{
                get(){
                        return this.$store.state.authCheckUserRolesPermissionsLoader;
                },
                set(value){
                        this.$store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':value});
                }
        },
        computedRequestTransactionToast:{
            get(){
                return this.$store.state.requestTransactionToast;
            },
            set(value){
                this.$store.commit('setRequestTransactionToast',{'requestTransactionToast':value});
            }
        },
        computedBeneficiaryCheckerDetails:{
            get(){
                return this.$store.state.beneficiaryCheckerDetails;
            }
        },
        computedClientCheckerDetails:{
            get(){
                return this.$store.state.clientCheckerDetails;
            }
        },
        computedAutoCompleteFullNameClient:{
            get(){
                return this.$store.state.autoCompleteFullNameClient;
            }
        },
        computedAutoCompleteFullNameBeneficiary:{
            get(){
                return this.$store.state.autoCompleteFullNameBeneficiary;
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
        computedFormLoaderSeeder: {
            get() {
                return this.$store.state.formSeederLoader;
            },
            set(value) {
                this.$store.commit('setFormSeederLoader', { 'formSeederLoader': value });
            }
        },
        computedFormSeeder: {
            get() {
                return this.$store.state.formSeeder;
            },
        },
        computedBeneficiary: {
            get() {
                return this.$store.state.beneficiary;
            },
            set(value) {
                this.$store.commit('setBeneficiary', { beneficiary: value });
            },
        },
        computedClient: {
            get() {
                return this.$store.state.client;
            },
            set(value) {
                this.$store.commit('setBeneficiary', { client: value });
            }
        },
        computedSameAsAboveFields: {
            get() {
                return this.$store.state.sameAsAboveFields;
            },
            set(value) {
                this.$store.commit('setSameAsAboveFields', { sameAsAboveFields: value });
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
            authorize:false,
            toast:null,
            proc:false,
            alertClient:false,
            alertBeneficiary:false,
            dialogVisibleBeneficiary:false,
            dialogVisibleClient: false,
            confirm:null,
            timeout:null,
            client_precint_disabled: true,
            beneficiary_precint_disabled: true,
            beneficiary_other_id_type_disabled: true,
            beneficiary_id_number_disabled: true,
            client_other_id_type_disabled: true,
            client_id_number_disabled: true,
            autoCompleteLoader:{
                beneficiary_middle_name:false,
                beneficiary_last_name:false,
                beneficiary_first_name:false,
                client_first_name:false,
                client_middle_name:false,
                client_last_name:false,
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
                client_relationship: false,
            },
            clientImageModalVisible: false,
            beneficiaryImageModalVisible: false,

        };
    },
    beforeRouteLeave(to, from, next) {
        if(to.path === '/request/submit'){

            this.validateFields('proceedButton')


            this.proc = true;
            for (let key in this.proceedValidation) {
                if (this.proceedValidation.hasOwnProperty(key)) {
                    console.log( "key:"+key+"()"+this.proceedValidation[key]);
                    if (this.proceedValidation[key]) {
                        this.proc = false;
                        break;
                    }
                }
            }

  
            if (this.proc) {
                this.computedFormLoaderSeeder = true;
                this.$store.dispatch('fetchBeneficiaryChecker',{'beneficiary':this.computedBeneficiary}).then(response1 => {
                this.$store.dispatch('fetchClientChecker',{'client':this.computedClient}).then(response2 => {
                        this.computedFormLoaderSeeder = false;
                        
                        if(this.computedClientCheckerDetails.repeatAssistance){
                            this.alertClient = true;
                            this.confirm.require({
                                group:'client',
                                message:'',
                                header: '⚠️Transaction Found⚠️ ',
                                icon: 'pi pi-exclamation-triangle',
                                rejectProps: {
                                    label: 'Cancel',
                                    severity: 'secondary',
                                    outlined: true
                                },
                                acceptProps: {
                                    label: 'Continue'
                                }, 
                                accept: () => {
                                    this.alertClient = false;
                                    if(!this.alertClient && !this.alertBeneficiary && this.proc){
                                        next(true);
                                    }
                                },
                                reject: () => {
                                    // toast.add({ severity: 'error', summary: 'Rejected', detail: 'You have rejected', life: 3000 });
                                },
                                
                            });
                            if(this.computedBeneficiaryCheckerDetails.repeatAssistance){
                                this.alertBeneficiary = true;
                                this.confirm.require({
                                group:'beneficiary',
                                message:'',
                                header: '⚠️Transaction Found⚠️ ',
                                icon: 'pi pi-exclamation-triangle',
                                rejectProps: {
                                    label: 'Cancel',
                                    severity: 'secondary',
                                    outlined: true
                                },
                                acceptProps: {
                                    label: 'Continue'
                                }, 
                                accept: () => {
                                    this.alertBeneficiary = false;
                                    if(!this.alertClient && !this.alertBeneficiary && this.proc){
                                        next(true);
                                    }
                                },
                                reject: () => {
                                    // toast.add({ severity: 'error', summary: 'Rejected', detail: 'You have rejected', life: 3000 });
                                },
                                
                            });
                                this.computedFormLoaderSeeder = false;
                            }else{
                                this.alertBeneficiary = false
                            }
                            this.computedFormLoaderSeeder = false;
                        }else if(this.computedBeneficiaryCheckerDetails.repeatAssistance){
                            this.confirm.require({
                                group:'beneficiary',
                                message:'',
                                header: '⚠️Transaction Found⚠️ ',
                                icon: 'pi pi-exclamation-triangle',
                                rejectProps: {
                                    label: 'Cancel',
                                    severity: 'secondary',
                                    outlined: true
                                },
                                acceptProps: {
                                    label: 'Continue'
                                }, 
                                accept: () => {
                                    this.alertBeneficiary = false;
                                    if(!this.alertClient && !this.alertBeneficiary && this.proc){
                                        next(true);
                                    }
                                },
                                reject: () => {
                                    // toast.add({ severity: 'error', summary: 'Rejected', detail: 'You have rejected', life: 3000 });
                                },
                                
                            });
                            this.alertBeneficiary = true;
                            if(this.computedClientCheckerDetails.repeatAssistance){
                                this.confirm.require({
                                group:'client',
                                message:'',
                                header: '⚠️Transaction Found⚠️ ',
                                icon: 'pi pi-exclamation-triangle',
                                rejectProps: {
                                    label: 'Cancel',
                                    severity: 'secondary',
                                    outlined: true
                                },
                                acceptProps: {
                                    label: 'Continue'
                                }, 
                                accept: () => {
                                    this.alertClient = false;
                                    if(!this.alertClient && !this.alertBeneficiary && this.proc){
                                        next(true)
                                    }
                                },
                                reject: () => {
                                    // toast.add({ severity: 'error', summary: 'Rejected', detail: 'You have rejected', life: 3000 });
                                },
                                
                            });
                            this.alertClient = true;
                            this.computedFormLoaderSeeder = false;
                            }else{
                                this.alertBeneficiary = false
                            }
                            this.computedFormLoaderSeeder = false;
                        }else{
                            this.alertClient = false;
                            this.alertBeneficiary = false;
                            if (this.proc & !this.alertClient && !this.alertBeneficiary) {
                                next(true)
                            this.computedFormLoaderSeeder = false;
                        
                            }else{
                                this.computedFormLoaderSeeder = false;
                            }
                        }

                        

                    });
                });
            }
        }else{
            next();
        }

    },
    beforeRouteEnter(to, from, next) {
        // this.computedProgressBarWidth = '25%';
        // this.computedProgressActive = 1; \
        console.log(to);
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