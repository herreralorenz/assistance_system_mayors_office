<template>
    <Loader2 v-if="this.computedClaimClientDetailsLoader"></Loader2>
    <Toast/>
    <div class="form-group claim-client py-4" v-if="!this.computedClaimClientDetailsLoader">
        <div class="container-fluid">
            <div class="row">
                <div class="col-8">
                    <div class="client-information">
                        <label class="fs-2 fw-bold ">Client Information</label>
                        <hr>
                        <div class="d-flex flex-row">
                            <img :src="this.computedClaimClientDetails[0].approve_transaction.transaction.client?.image[0]?.file_name
                                ? `/api/get-client-claim-image?data[clientClaimImage]=` + this.computedClaimClientDetails[0].approve_transaction.transaction.client?.image[0]?.file_name
                                : '/storage/images/congonylogo.webp'"></img>
                            <div class="d-flex flex-column justify-content-center ms-2">
                                <span class="fs-5 fw-bold">{{
                                    this.computedClaimClientDetails[0].approve_transaction.transaction.client.full_name
                                    }}</span>
                                <span class="fs-5 fw-bold underline text-secondary"><u>{{
                                    this.computedClaimClientDetails[0].approve_transaction.transaction.client.contact_number[0].contact_number.slice(0, 4) + "-" + this.computedClaimClientDetails[0].approve_transaction.transaction.client.contact_number[0].contact_number.slice(4, 8) + "-" + this.computedClaimClientDetails[0].approve_transaction.transaction.client.contact_number[0].contact_number.slice(8,11)
                                        }}</u></span>
                            </div>
                        </div>
                        <div class="clientDetails row mt-3">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">First Name:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client.first_name
                                            }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Middle Name:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client?.middle_name
                                                ?
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client?.middle_name
                                            : ""}}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Last Name:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client?.last_name
                                            }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Suffix:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client?.suffix?.suffix
                                                ?
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client?.suffix?.suffix
                                            : "" }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Birthdate:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client.birthdate
                                            }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Age:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client.age
                                            }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Sex:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client.sex.sex
                                            }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Civil Status:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client.civil_status.civil_status
                                            }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Relationship to Beneficiary:</label>
                                    </div>
                                    <div class="col-8">
                                        <span
                                            class="fs-6 ms-1 fw-bold">{{ this.computedClaimClientDetails[0].approve_transaction.transaction?.client.client_beneficiary_relationship[0]?.relationship[0]?.relationship
                                            ?? ""}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">City:</label>
                                    </div>
                                    <div class="col-8">
                                        <span
                                            class="fs-6 ms-1 fw-bold">{{ this.computedClaimClientDetails[0].approve_transaction.transaction.client.city
                                            }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Barangay:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client.barangay
                                            }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Street/Purok:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client.street
                                            }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">ID Type:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client?.client_identification[0]?.identification_type?.id_type
                                            }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">ID Number:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction?.client.client_identification[0]?.id_number
                                            }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Precint:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client?.precint?.precint}}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Occupation:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client.client_occupation?.[0]?.occupation}}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">M. Income:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client.client_occupation?.[0]?.pivot?.monthly_income.toString()}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="beneficiary-information mt-4">
                        <span class="fs-2 fw-bold">Beneficiary Information</span>
                        <hr>
                        <div class="d-flex flex-row">
                            <img :src="this.computedClaimClientDetails[0].approve_transaction.transaction.client?.client_beneficiary_relationship[0]?.image[0]?.file_name
                                ? `/api/get-beneficiary-claim-image?data[beneficiaryClaimImage]=` + this.computedClaimClientDetails[0].approve_transaction.transaction.client?.client_beneficiary_relationship[0]?.image[0]?.file_name
                                : '/storage/images/congonylogo.webp'"></img>
                            <div class="d-flex flex-column justify-content-center ms-2">
                                <span class="fs-5 fw-bold">{{
                                    this.computedClaimClientDetails[0].approve_transaction.transaction.client.client_beneficiary_relationship[0]?.full_name
                                    ?? "" }}</span>
                                <span class="fs-5 fw-bold underline text-secondary"><u>{{ (this.computedClaimClientDetails[0].approve_transaction.transaction.client.client_beneficiary_relationship[0]?.contact_number[0]?.contact_number.slice(0, 4).concat("-")
                                    ??
                                    "") + (this.computedClaimClientDetails[0].approve_transaction.transaction.client.client_beneficiary_relationship[0]?.contact_number[0]?.contact_number.slice(4, 8).concat("-")
                                        ??
                                        "") + (this.computedClaimClientDetails[0].approve_transaction.transaction.client.client_beneficiary_relationship[0]?.contact_number[0]?.contact_number.slice(8,11)
                                        ?? "")}}</u></span>
                            </div>
                        </div>
                        <div class="clientDetails row mt-3">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">First Name:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client.client_beneficiary_relationship[0]?.first_name
                                            ?? ""}}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Middle Name:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client.client_beneficiary_relationship[0]?.middle_name
                                                ?
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client.client_beneficiary_relationship[0]?.middle_name
                                            : ""}}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Last Name:</label>
                                    </div>
                                    <div class="col-8">
                                        <span
                                            class="fs-6 ms-1 fw-bold">{{ this.computedClaimClientDetails[0].approve_transaction.transaction.client.client_beneficiary_relationship[0]?.last_name
                                            ?? ""}}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Suffix:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client.client_beneficiary_relationship[0]?.suffix
                                                ?
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client.client_beneficiary_relationship[0]?.suffix
                                            : "" }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Birthdate:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client.client_beneficiary_relationship[0]?.birthdate
                                            ?? ""}}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Age:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client.client_beneficiary_relationship[0]?.age
                                            ?? "" }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Sex:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client.client_beneficiary_relationship[0]?.sex?.sex
                                            ?? "" }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Civil Status:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client.client_beneficiary_relationship[0]?.civil_status?.civil_status
                                            ?? "" }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">City:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client.client_beneficiary_relationship[0]?.city
                                            ?? "" }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Barangay:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client.client_beneficiary_relationship[0]?.barangay
                                            ?? "" }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Street/Purok:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client.client_beneficiary_relationship[0]?.street
                                            ?? "" }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">ID Type:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client.client_beneficiary_relationship[0]?.beneficiary_identification[0]?.identification_type?.id_type
                                            ??
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client.client_beneficiary_relationship[0]?.beneficiary_identification[0]?.other_identification_type?.other_id_type
                                            ??
                                            ""
                                            }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">ID Number:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client.client_beneficiary_relationship[0]?.beneficiary_identification[0]?.id_number
                                            ?? "" }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Precint:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client.client_beneficiary_relationship[0]?.precint?.precint
                                            }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Occupation:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client.client_beneficiary_relationship[0]?.beneficiary_occupation?.[0]?.occupation
                                            }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">M. Income:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClaimClientDetails[0].approve_transaction.transaction.client.client_beneficiary_relationship[0]?.beneficiary_occupation?.[0]?.pivot?.monthly_income ? this.computedClaimClientDetails[0].approve_transaction.transaction.client.client_beneficiary_relationship[0]?.beneficiary_occupation?.[0]?.pivot?.monthly_income.toString() : ""
                                            }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="assistance-details">
                        <label class="fs-2 fw-bold ">Assistance Details</label>
                        <div>
                            <label class="fs-6 text-secondary fw-bold">Transaction ID:</label>
                            <span class="fs-6 ms-1 fw-bold">{{
                                this.computedClaimClientDetails[0].approve_transaction.transaction.transaction_id
                                }}</span>
                        </div>
                        <hr>
                        <div class="row mt-2">
                            <div class="col-12 d-flex flex-row align-items-center">
                                <i style="width:50px; height:50px"
                                    :class="{ 'fa-solid fa-2xl fa-hands-holding text-white d-flex align-items-center circle-background bg-secondary': this.computedClaimClientDetails[0].transaction_claim_status_condition[0].status === 'TO CLAIM' ? true : false, 'fa-solid fa-2xl fa-exclamation text-white d-flex align-items-center circle-background bg-danger': this.computedClaimClientDetails[0].transaction_claim_status_condition[0].status === 'UNCLAIMED' ? true : false, 'fa-solid fa-2xl fa-check text-white d-flex align-items-center circle-background bg-sucess': this.computedClaimClientDetails[0].transaction_claim_status_condition[0].status === 'CLAIMED' ? true : false, }"></i>
                                <label class="fs-3 mx-2 fw-bold">{{
                                    this.computedClaimClientDetails[0].transaction_claim_status_condition[0].status
                                    }}</label>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-5">
                                <label class="fs-6 text-secondary fw-bold">Claimant Name:</label>
                            </div>
                            <div class="col-7">
                                <span
                                    class="fs-6 ms-1 fw-bold">{{ this.computedClaimClientDetails[0].claimant?.full_name }}</span>
                            </div>
                            <div class="col-5">
                                <label class="fs-6 text-secondary fw-bold">Contact Number:</label>
                            </div>
                            <div class="col-7">
                                <span
                                    class="fs-6 ms-1 fw-bold">{{ this.computedClaimClientDetails[0].claimant?.contact_number[0]?.contact_number }}</span>
                            </div>
                            <div class="col-5">
                                <label class="fs-6 text-secondary fw-bold">Date Claimed:</label>
                            </div>
                            <div class="col-7">
                                <span class="fs-6 ms-1 fw-bold">{{
                                    this.computedClaimClientDetails[0].transaction_claim_status_condition[0].pivot.status_condition_date
                                    }}</span>
                            </div>
                            <div class="col-5">
                                <label class="fs-6 text-secondary fw-bold">Type:</label>
                            </div>
                            <div class="col-7">
                                <span class="fs-6 ms-1 fw-bold">{{
                                    this.computedClaimClientDetails[0].approve_transaction.transaction.assistance_type.assistance_type
                                    }}</span>
                            </div>
                            <div class="col-5">
                                <label class="fs-6 text-secondary fw-bold">Amount:</label>
                            </div>
                            <div class="col-7">
                                <span class="fs-6 ms-1 fw-bold">{{
                                    this.computedClaimClientDetails[0].approve_transaction?.transaction_approve_amount?.amount.toString()
                                    }}</span>
                            </div>
                            <div class="col-5">
                                <label class="fs-6 text-secondary fw-bold">Description:</label>
                            </div>
                            <div class="col-7">
                                <span class="fs-6 ms-1 fw-bold">{{
                                    this.computedClaimClientDetails[0].approve_transaction.transaction.assistance_description.assistance_description
                                    }}</span>
                            </div>
                            <div class="col-5">
                                <label class="fs-6 text-secondary fw-bold">Category:</label>
                            </div>
                            <div class="col-7">
                                <span class="fs-6 ms-1 fw-bold">{{
                                    this.computedClaimClientDetails[0].approve_transaction.transaction.assistance_category.assistance_category
                                    }}</span>
                            </div>
                            <div class="col-5">
                                <label class="fs-6 text-secondary fw-bold">Remarks:</label>
                            </div>
                            <div class="col-7">
                                <span class="fs-6 ms-1 fw-bold">{{
                                    this.computedClaimClientDetails[0].transaction_claim_status_condition[0].pivot.remarks
                                    }}</span>
                            </div>
                            <div class="col-12 mt-3"   v-if="this.computedClaimClientDetails[0].transaction_claim_status_condition[0].status === 'TO CLAIM' || this.computedClaimClientDetails[0].transaction_claim_status_condition[0].status === 'UNCLAIMED' && (this.computedFetchAuthUserRolesPermissions.user_permissions.some(value => value.name === 'claimAssistance') || this.computedFetchAuthUserRolesPermissions.user_roles.some(value => value === 'superAdmin'))">
                                <button
                                    type="button" data-bs-toggle="modal" data-bs-target="#transactionClaimModal"
                                    class="btn btn-success bg-gradient w-100 h-100">CLAIM</button>
                            </div>
                            <div class="col-12 mt-3"  v-if="this.computedClaimClientDetails[0].transaction_claim_status_condition[0].status === 'CLAIMED' && (this.computedFetchAuthUserRolesPermissions.user_permissions.some(value => value.name === 'unclaimAssistance') || this.computedFetchAuthUserRolesPermissions.user_roles.some(value => value === 'superAdmin'))">
                                <button
                                    type="button" data-bs-toggle="modal" data-bs-target="#transactionUnclaimModel"
                                    class="btn btn-danger bg-gradient w-100 h-100">UNCLAIM</button>
                            </div>
                            <div class="col-12 mt-3"   v-if="this.computedClaimClientDetails[0].transaction_claim_status_condition[0].status === 'CLAIMED' && (this.computedFetchAuthUserRolesPermissions.user_permissions.some(value => value.name === 'editClaimAssistance') || this.computedFetchAuthUserRolesPermissions.user_roles.some(value => value === 'superAdmin'))">
                                <button
                                    type="button" data-bs-toggle="modal" data-bs-target="#transactionEditClaimModal"
                                    class="btn btn-success bg-gradient w-100 h-100">EDIT CLAIMED</button>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  <!-- Edit Claim modal -->
  <div v-if="!this.computedClaimClientDetailsLoader">
        <div class="modal fade" id="transactionEditClaimModal" ref="transactionEditClaimModal" tabindex="-1"
            aria-labelledby="transactionEditClaimModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="claimModalLabel">Edit claimed transaction</h5>
                        <button type="button" id="btn-close-edit-claim" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="container py-4">
                        <div>
                            <i class="fa-solid fa-calendar fa-lg icon"></i>
                            <input id="claim_date" v-model="this.computedClaimant.claim_date" v-on:keyup="this.handleKeyUp('claim_date')"
                                :class="{ 'form-control': true, 'input-field': true, 'is-invalid': this.proceedValidation.claim_date }"
                                type="date" placeholder="Claim Date">
                        </div>
                        <div class="mt-3">
                            <i class="fa-solid fa-user fa-lg icon"></i>
                            <input id="first_name" v-model="this.computedClaimant.first_name" v-on:keyup="this.handleKeyUp('first_name')"
                                :class="{ 'form-control': true, 'input-field': true, 'is-invalid': this.proceedValidation.first_name }"
                                type="text" placeholder="Claimant First Name">
                        </div>
                        <div class="mt-3">
                            <i class="fa-solid fa-user fa-lg icon"></i>
                            <input id="middle_name" v-model="this.computedClaimant.middle_name" 
                                :class="{ 'form-control': true, 'input-field': true }" type="text"
                                placeholder="Claimant Middle Name" v-on:keyup="this.handleKeyUp('middle_name')">
                        </div>
                        <div class="mt-3">
                            <i class="fa-solid fa-user fa-lg icon"></i>
                            <input id="last_name" v-model="this.computedClaimant.last_name" v-on:keyup="this.handleKeyUp('last_name')"
                                :class="{ 'form-control': true, 'input-field': true, 'is-invalid': this.proceedValidation.last_name }"
                                type="text" placeholder="Claimant Last Name">
                        </div>
                        <div class="mt-3">
                            <i class="fa-solid fa-user fa-lg icon"></i>
                            <select v-model="this.computedClaimant.suffix" id="suffix"
                                :class="{ 'form-select': true }">
                                <option value="" disabled selected>Suffix</option>
                                <option value="" selected></option>
                                <option v-for="(option, index) in this.computedSuffixSeeder" :key="option.id"
                                    :value="option.id">{{ option.suffix }}</option>
                            </select>
                        </div>
                        <div class="mt-3">
                            <i class="fa-solid fa-address-book fa-lg icon"></i>
                            <input id="contact_number" v-model="this.computedClaimant.contact_number" v-on:keyup="this.handleKeyUp('contact_number')"
                                :class="{ 'form-control': true, 'input-field': true, 'is-invalid': this.proceedValidation.contact_number }"
                                type="text" placeholder="Claimant Contact Number">
                        </div>
                        <div class="mt-3">
                            <i class="fa-solid fa-note-sticky fa-lg icon"></i>
                            <input id="claimRemarks" v-model="this.computedClaimant.remarks"
                                :class="{ 'form-control': true, 'input-field': true }" type="text"
                                placeholder="Remarks" v-on:keyup="this.handleKeyUp('remarks')">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button type="button" @click="editClaimClient()" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Claim modal -->
    <div v-if="!this.computedClaimClientDetailsLoader">
        <div class="modal fade" id="transactionClaimModal" ref="transactionClaimModal" tabindex="-1"
            aria-labelledby="transactionClaimModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="claimModalLabel">Are you sure to claim this transaction?</h5>
                        <button type="button" id="btn-close-claim" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="container py-4">
                        <div>
                            <i class="fa-solid fa-calendar fa-lg icon"></i>
                            <input id="claim_date" v-model="this.computedClaimant.claim_date" v-on:keyup="this.handleKeyUp('claim_date')"
                                :class="{ 'form-control': true, 'input-field': true, 'is-invalid': this.proceedValidation.claim_date }"
                                type="date" placeholder="Claim Date">
                        </div>
                        <div class="mt-3">
                            <i class="fa-solid fa-user fa-lg icon"></i>
                            <input id="first_name" v-model="this.computedClaimant.first_name" v-on:keyup="this.handleKeyUp('first_name')"
                                :class="{ 'form-control': true, 'input-field': true, 'is-invalid': this.proceedValidation.first_name }"
                                type="text" placeholder="Claimant First Name">
                        </div>
                        <div class="mt-3">
                            <i class="fa-solid fa-user fa-lg icon"></i>
                            <input id="middle_name" v-model="this.computedClaimant.middle_name" 
                                :class="{ 'form-control': true, 'input-field': true }" type="text"
                                placeholder="Claimant Middle Name" v-on:keyup="this.handleKeyUp('middle_name')">
                        </div>
                        <div class="mt-3">
                            <i class="fa-solid fa-user fa-lg icon"></i>
                            <input id="last_name" v-model="this.computedClaimant.last_name" v-on:keyup="this.handleKeyUp('last_name')"
                                :class="{ 'form-control': true, 'input-field': true, 'is-invalid': this.proceedValidation.last_name }"
                                type="text" placeholder="Claimant Last Name">
                        </div>
                        <div class="mt-3">
                            <i class="fa-solid fa-user fa-lg icon"></i>
                            <select v-model="this.computedClaimant.suffix" id="suffix"
                                :class="{ 'form-select': true }">
                                <option value="" disabled selected>Suffix</option>
                                <option value="" selected></option>
                                <option v-for="(option, index) in this.computedSuffixSeeder" :key="option.id"
                                    :value="option.id">{{ option.suffix }}</option>
                            </select>
                        </div>
                        <div class="mt-3">
                            <i class="fa-solid fa-address-book fa-lg icon"></i>
                            <input id="contact_number" v-model="this.computedClaimant.contact_number" v-on:keyup="this.handleKeyUp('contact_number')"
                                :class="{ 'form-control': true, 'input-field': true, 'is-invalid': this.proceedValidation.contact_number }"
                                type="text" placeholder="Claimant Contact Number">
                        </div>
                        <div class="mt-3">
                            <i class="fa-solid fa-note-sticky fa-lg icon"></i>
                            <input id="claimRemarks" v-model="this.computedClaimant.remarks"
                                :class="{ 'form-control': true, 'input-field': true }" type="text"
                                placeholder="Remarks" v-on:keyup="this.handleKeyUp('remarks')">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button type="button" @click="claimClient()" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Unclaim modal -->
    <div v-if="!this.computedClaimClientDetailsLoader">
        <div class="modal fade" id="transactionUnclaimModel" ref="transactionUnclaimModel" tabindex="-1"
            aria-labelledby="transactionUnclaimModel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="unclaimModalLabel">Are you sure to unclaim this transaction?</h5>
                        <button type="button" id="btn-close-unclaim" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button type="button" @click="unclaimClient()" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </div>      
    </div>
</template>
<style scoped>
.icon {
    padding: 10px;
    margin-top: 15px;
    min-width: 40px;
    position: absolute;
}

.input-field {
    padding-left: 35px;
    border-radius: 25px;
    height: 50px;
}

button {
    border-radius: 25px;
}

.circle-background {
    background-color: #28a745;
    /* Green background similar to 'text-success' */
    border-radius: 100%;
    /* Makes the background a circle */
    padding: 10px;
    /* Adjust this for the desired size */
    color: white;
    /* Ensures the icon is visible against the background */
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.client-information,
.beneficiary-information,
.assistance-details {
    border-radius: 10px;
    padding: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
}

.claim-client {
    padding-left: 250px;
    overflow-y: scroll;
    height: -webkit-calc(100% - 50px);
    height: -moz-calc(100% - 50px);
    height: calc(100% - 50px);
    /* display: flex;
    flex-direction: column;    */
}

img {
    height: 100px;
    width: 100px;
    border-radius: 100px;
    object-fit: fill;
}

select {
    border-radius: 25px;
    padding-left: 35px;
    border-radius: 25px;
    height: 50px;
}
</style>
<script>

import Loader2 from '../Loader2.vue'
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import auth from '../../../router/auth_roles_permissions.js';

export default {
    components: {
        Loader2,
        Toast,
    },
    beforeMount() {
        
        this.toast = useToast();

        this.computedHeaderText = 'Claiming of Assistance > Claim Client Transaction';
        this.computedClaimClientDetailsLoader = true;
        this.$store.dispatch('fetchClaimClientDetails', { transactionClaimID: this.$route.params.id }).then(response1 => {
            this.computedClaimClientDetailsLoader = false;
            this.computedClaimant.claim_date = response1.data.todayDate;
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
        handleKeyUp(textfield){

            const fieldsUpperCase = {
                'first_name': 'upper',
                'middle_name': 'upper',
                'last_name': 'upper',
                'remarks':'upper'
            };

            if(fieldsUpperCase[textfield] === 'upper'){
               
                this.computedClaimant[textfield] = this.computedClaimant[textfield].toUpperCase();
            }

            if(textfield != 'middle_name' && textfield != 'remarks'){
     
                if(textfield === 'contact_number' && /^(\+?63|0)?\d{10}$/.test(this.computedClaimant[textfield])){
                    this.proceedValidation[textfield] = false;
                }else if(textfield === 'contact_number' && !/^(\+?63|0)?\d{10}$/.test(this.computedClaimant[textfield])){
                    this.proceedValidation[textfield] = true;
                }else if(typeof this.computedClaimant[textfield] === 'string' && this.computedClaimant[textfield] && this.computedClaimant[textfield] != ''){
                    this.proceedValidation[textfield] = false;
                }else{
                    this.proceedValidation[textfield] = true;
                }
                           
            }
        },
        editClaimClient(){
            const validateClaimant = {
                'claim_date':['required'],
                'first_name':['required_if'],
                'middle_name':['required_if_input'],
                'last_name':['required_if'],
                'suffix':['required_if_input'],
                'contact_number':['required_if'],
                'remarks':['not_required']
            };

            for(const [key1,value1] of Object.entries(validateClaimant)){
                if(value1[0] === 'required'){
                    if(typeof this.computedClaimant[key1] === "string" && this.computedClaimant[key1] && this.computedClaimant[key1] != ''){
                        this.proceedValidation[key1] = false;  
                    }else{
                        this.proceedValidation[key1] = true;  
                    }
                }else if(value1[0] === 'required_if'){
                    if(typeof this.computedClaimant[key1] === "string" && this.computedClaimant[key1] && this.computedClaimant[key1] != ''){
                        for(const [key2,value2] of Object.entries(validateClaimant)){
                            if((key1 != key2) && value2[0] === 'required_if'){
                                if(!this.computedClaimant[key2] || this.computedClaimant[key2] === ''){
                                    this.proceedValidation[key2] = true;
                                }else{
                                    if(key2 === 'contact_number' && /^(\+?63|0)?\d{10}$/.test(this.computedClaimant.contact_number)){
                                        this.proceedValidation[key2] = false;
                                    }else if(key2 === 'contact_number' && !/^(\+?63|0)?\d{10}$/.test(this.computedClaimant.contact_number)){
                                        this.proceedValidation[key2] = true;
                                    }else{
                                        this.proceedValidation[key2] = false;  
                                    }
                                }
                          
                            }
                        }
                    }
                }else if(value1[0] === 'required_if_input'){
                    if(this.computedClaimant[key1] && this.computedClaimant[key1] != ''){
                        for(const [key3,value3] of Object.entries(validateClaimant)){
                            if(key1 != key3 && value3[0] === 'required_if'){
                                if(this.computedClaimant[key3] === '' || !this.computedClaimant[key3]){
                                    this.proceedValidation[key3] = true;
                                }else{
                                    if(key3 === 'contact_number' && /^(\+?63|0)?\d{10}$/.test(this.computedClaimant.contact_number)){
                                        this.proceedValidation[key3] = false;
                                    }else if(key3 === 'contact_number' && !/^(\+?63|0)?\d{10}$/.test(this.computedClaimant.contact_number)){
                                        this.proceedValidation[key3] = true;
                                    }else{
                                        this.proceedValidation[key3] = false;  
                                    }
                                }
                            }
                        }
                    }
                }else if(value1[0] === 'not_required'){
                    this.proceedValidation[key1] = false;
                }
            }

            let proc = true;
            for (const [key, value] of Object.entries(this.proceedValidation)) {
                if (this.proceedValidation[key] == true) {
                    proc = false
                    break;
                } else {
                    proc = true;
                }
            }

            if(proc){
                document.getElementById('btn-close-edit-claim').click();
                this.computedClaimClientDetailsLoader = true;
                this.$store.dispatch('updateClaimedClient',{'id':this.$route.params.id,'claimant':this.computedClaimant}).then(response => {
                    this.$store.dispatch('fetchClaimClientDetails', { transactionClaimID: this.$route.params.id }).then(response1 => {
                        this.computedClaimClientDetailsLoader = false;
                        for(const [key,value] of Object.entries(this.computedClaimant)){
                            if(key != 'claim_date'){
                                this.computedClaimant[key] = '';
                            }
                        }
                        this.computedClaimTransactionToast = true;
                        if(this.computedClaimTransactionToast){
                            this.toast.add({ severity: 'success', summary: 'Success', detail: 'Transaction updated successfully.', life: 5000 });
                            this.computedClaimTransactionToast = false;
                        }
                    })
                });
            }

        },
        claimClient(){
            const validateClaimant = {
                'claim_date':['required'],
                'first_name':['required_if'],
                'middle_name':['required_if_input'],
                'last_name':['required_if'],
                'suffix':['required_if_input'],
                'contact_number':['required_if'],
                'remarks':['not_required']
            };

            for(const [key1,value1] of Object.entries(validateClaimant)){
                if(value1[0] === 'required'){
                    if(typeof this.computedClaimant[key1] === "string" && this.computedClaimant[key1] && this.computedClaimant[key1] != ''){
                        this.proceedValidation[key1] = false;  
                    }else{
                        this.proceedValidation[key1] = true;  
                    }
                }else if(value1[0] === 'required_if'){
                    if(typeof this.computedClaimant[key1] === "string" && this.computedClaimant[key1] && this.computedClaimant[key1] != ''){
                        for(const [key2,value2] of Object.entries(validateClaimant)){
                            if((key1 != key2) && value2[0] === 'required_if'){
                                if(!this.computedClaimant[key2] || this.computedClaimant[key2] === ''){
                                    this.proceedValidation[key2] = true;
                                }else{
                                    if(key2 === 'contact_number' && /^(\+?63|0)?\d{10}$/.test(this.computedClaimant.contact_number)){
                                        this.proceedValidation[key2] = false;
                                    }else if(key2 === 'contact_number' && !/^(\+?63|0)?\d{10}$/.test(this.computedClaimant.contact_number)){
                                        this.proceedValidation[key2] = true;
                                    }else{
                                        this.proceedValidation[key2] = false;  
                                    }
                                }
                          
                            }
                        }
                    }
                }else if(value1[0] === 'required_if_input'){
                    if(this.computedClaimant[key1] && this.computedClaimant[key1] != ''){
                        for(const [key3,value3] of Object.entries(validateClaimant)){
                            if(key1 != key3 && value3[0] === 'required_if'){
                                if(this.computedClaimant[key3] === '' || !this.computedClaimant[key3]){
                                    this.proceedValidation[key3] = true;
                                }else{
                                    if(key3 === 'contact_number' && /^(\+?63|0)?\d{10}$/.test(this.computedClaimant.contact_number)){
                                        this.proceedValidation[key3] = false;
                                    }else if(key3 === 'contact_number' && !/^(\+?63|0)?\d{10}$/.test(this.computedClaimant.contact_number)){
                                        this.proceedValidation[key3] = true;
                                    }else{
                                        this.proceedValidation[key3] = false;  
                                    }
                                }
                            }
                        }
                    }
                }else if(value1[0] === 'not_required'){
                    this.proceedValidation[key1] = false;
                }
            }

            let proc = true;
            for (const [key, value] of Object.entries(this.proceedValidation)) {
                if (this.proceedValidation[key] == true) {
                    proc = false
                    break;
                } else {
                    proc = true;
                }
            }

            if (proc) {
                document.getElementById('btn-close-claim').click();
                this.computedClaimClientDetailsLoader = true;
                this.$store.dispatch('claimClient', { claimant: this.computedClaimant, id: this.$route.params.id }).then(response => {
                    this.$store.dispatch('fetchClaimClientDetails', { transactionClaimID: this.$route.params.id }).then(response1 => {
                        this.computedClaimClientDetailsLoader = false;
                        for(const [key,value] of Object.entries(this.computedClaimant)){
                            if(key != 'claim_date'){
                                this.computedClaimant[key] = '';
                            }
                        }
                        this.computedClaimTransactionToast = true;
                        if(this.computedClaimTransactionToast){
                            this.toast.add({ severity: 'success', summary: 'Success', detail: 'Transaction claimed successfully.', life: 5000 });
                            this.computedClaimTransactionToast = false;
                        }
                    });
                });
            }
        },
        unclaimClient() {
            document.getElementById('btn-close-unclaim').click();
            this.computedClaimClientDetailsLoader = true;
            this.$store.dispatch('unclaimClient', { id: this.$route.params.id }).then(response => {
                this.$store.dispatch('fetchClaimClientDetails', { transactionClaimID: this.$route.params.id }).then(response1 => {
                    this.computedClaimClientDetailsLoader = false;
                    for(const [key,value] of Object.entries(this.computedClaimant)){
                        if(key != 'claim_date'){
                            this.computedClaimant[key] = '';
                        }
                    }
                    this.computedClaimTransactionToast = true;
                    if(this.computedClaimTransactionToast){
                        this.toast.add({ severity: 'success', summary: 'Success', detail: 'Transaction unclaimed successfully.', life: 5000 });
                        this.computedClaimTransactionToast = false;
                    }
                });
            });
        }
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
        computedClaimTransactionToast:{
            get(){
                return this.$store.state.claimTransactionToast;
            },
            set(value){
                this.$store.commit('setClaimTransactionToast',{'claimTransactionToast':value});
            }
        },
        computedSuffixSeeder: {
            get() {
                return this.$store.state.suffixSeeder;
            },
        },
        computedClaimant: {
            get() {
                return this.$store.state.claimant;
            },
            set(value) {
                this.$store.commit('setClaimant', { claimant: value });
            }
        },
        computedClaimClientDetailsLoader: {
            get() {
                return this.$store.state.claimClientDetailsLoader;
            },
            set(value) {
                this.$store.commit('setClaimClientDetailsLoader', { claimClientDetailsLoader: value });
            }
        },
        computedClaimClientDetails: {
            get() {
                return this.$store.state.claimClientDetails;
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
            proceedValidation: {
                claim_date: false,
                first_name: false,
                middle_name: false,
                last_name: false,
                suffix: false,
                contact_number: false,
            }
        };
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