<template>
    <Loader2 v-if="this.computedClientTransactionDetailsLoader"></Loader2>
    <Toast/>
    <div class="form-group claim-client py-4" v-if="!this.computedClientTransactionDetailsLoader">
        <div class="container-fluid">
            <div class="row">
                <div class="col-8">
                    <div class="client-information">
                        <label class="fs-2 fw-bold ">Client Information</label>
                        <hr>
                        <div class="d-flex flex-row">
                            <img
                                :src='"/api/get-client-transaction-image/?data[clientTransactionImage]=" + this.computedClientTransactionDetails.transaction[0].client?.image?.[0]?.file_name'></img>
                            <div class="d-flex flex-column justify-content-center ms-2">
                                <span class="fs-5 fw-bold">{{
                                    this.computedClientTransactionDetails.transaction[0].client.first_name + " "+(this.computedClientTransactionDetails.transaction[0].client?.middle_name ?? "")+" "+(this.computedClientTransactionDetails.transaction[0].client?.suffix?.suffix ?? "")+" "+this.computedClientTransactionDetails.transaction[0].client.last_name
                                }}</span>
                                <span
                                    class="fs-5 fw-bold underline text-secondary"><u>{{ this.computedClientTransactionDetails.transaction[0].client.contact_number[0].contact_number.slice(0, 4) + "-" + this.computedClientTransactionDetails.transaction[0].client.contact_number[0].contact_number.slice(4, 8) + "-" + this.computedClientTransactionDetails.transaction[0].client.contact_number[0].contact_number.slice(8, 11) }}</u></span>
                            </div>
                        </div>
             
                        <div class="clientDetails row mt-3">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">First Name:</label>
                                    </div>
                                    <div class="col-8">
                                        <span
                                            class="fs-6 ms-1 fw-bold">{{ this.computedClientTransactionDetails.transaction[0].client.first_name }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Middle Name:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClientTransactionDetails.transaction[0].client?.middle_name ??
                                            "" }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Last Name:</label>
                                    </div>
                                    <div class="col-8">
                                        <span
                                            class="fs-6 ms-1 fw-bold">{{ this.computedClientTransactionDetails.transaction[0].client.last_name }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Suffix:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClientTransactionDetails.transaction[0].client?.suffix?.suffix
                                            ?? "" }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Birthdate:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClientTransactionDetails.transaction[0].client.birthdate
                                            }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Age:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClientTransactionDetails.transaction[0].client.age }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Sex:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClientTransactionDetails.transaction[0].client.sex.sex
                                            }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Civil Status:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClientTransactionDetails.transaction[0].client.civil_status.civil_status
                                            }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Relationship to Beneficiary:</label>
                                    </div>
                                    <div class="col-8">
                                        <span
                                            class="fs-6 ms-1 fw-bold">{{ this.computedClientTransactionDetails.transaction[0]?.beneficiary_transaction?.[0]?.client_beneficiary_relationship?.[0]?.relationship.relationship }}</span>
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
                                            this.computedClientTransactionDetails.transaction[0].client.city }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Barangay:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClientTransactionDetails.transaction[0].client.barangay
                                            }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Street/Purok:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClientTransactionDetails.transaction[0].client.street }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">ID Type:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClientTransactionDetails.transaction[0].client?.client_identification?.[0]?.identification_type?.id_type
                                            ??
                                            this.computedClientTransactionDetails.transaction[0].client.client_identification[0]?.other_identification_type?.other_id_type
                                            ?? ""}}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">ID Number:</label>
                                    </div>
                                    <div class="col-8">
                                        <span
                                            class="fs-6 ms-1 fw-bold">{{ this.computedClientTransactionDetails.transaction[0].client.client_identification?.[0]?.id_number }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Precint:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClientTransactionDetails.transaction[0].client.precint?.precint
                                            ?? "" }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Occupation:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClientTransactionDetails.transaction[0].client?.client_occupation?.[0]?.occupation
                                            ?? "" }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">M. Income:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClientTransactionDetails.transaction[0].client?.client_occupation?.[0]?.pivot?.monthly_income ? this.computedClientTransactionDetails.transaction[0].client?.client_occupation?.[0]?.pivot?.monthly_income.toString() : ""
                                            ?? "" }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="beneficiary-information mt-4">
                        <span class="fs-2 fw-bold">Beneficiary Information</span>
                        <hr>
                        <div class="d-flex flex-row">
                            <img :src="'/api/get-beneficiary-transaction-image/?data[beneficiaryTransactionImage]=' + this.computedClientTransactionDetails.transaction[0].beneficiary_transaction[0]?.image[0]?.file_name">
                            <div class="d-flex flex-column justify-content-center ms-2">
                                <span class="fs-5 fw-bold">{{
                                    (this.computedClientTransactionDetails.transaction[0]?.beneficiary_transaction?.[0]?.first_name
                                        ?? "") + " "+(this.computedClientTransactionDetails.transaction[0]?.beneficiary_transaction?.[0]?.middle_name
                                    ?? "") +" "+(this.computedClientTransactionDetails.transaction[0]?.beneficiary_transaction?.[0]?.last_name
                                    ?? "")+" "+(this.computedClientTransactionDetails.transaction[0]?.beneficiary_transaction?.[0]?.suffix?.suffix
                                    ?? "") }}</span>
                                <span class="fs-5 fw-bold underline text-secondary"><u>{{
                                    (this.computedClientTransactionDetails.transaction[0]?.beneficiary_transaction?.[0]?.contact_number?.[0]?.contact_number.slice(0, 4).concat('-')
                                        ??
                                        "") + (this.computedClientTransactionDetails.transaction[0]?.beneficiary_transaction?.[0]?.contact_number[0]?.contact_number.slice(4, 8).concat('-')
                                        ??
                                        "")+(this.computedClientTransactionDetails.transaction[0].beneficiary_transaction[0]?.contact_number[0]?.contact_number.slice(8,11)
                                        ?? "") }}</u></span>
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
                                            this.computedClientTransactionDetails.transaction[0]?.beneficiary_transaction?.[0]?.first_name
                                            ?? ""}}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Middle Name:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClientTransactionDetails.transaction[0]?.beneficiary_transaction?.[0]?.middle_name
                                            ?? ""}}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Last Name:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClientTransactionDetails.transaction[0]?.beneficiary_transaction?.[0]?.last_name
                                            ?? ""}}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Suffix:</label>
                                    </div>
                                    <div class="col-8">
                                        <span
                                            class="fs-6 ms-1 fw-bold">{{ this.computedClientTransactionDetails.transaction[0]?.beneficiary_transaction?.[0]?.suffix?.suffix
                                            ?? ""}}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Birthdate:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClientTransactionDetails.transaction[0]?.beneficiary_transaction?.[0]?.birthdate
                                            }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Age:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClientTransactionDetails.transaction[0]?.beneficiary_transaction?.[0]?.age
                                            }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Sex:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClientTransactionDetails.transaction[0]?.beneficiary_transaction?.[0]?.sex?.sex
                                            }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Civil Status:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClientTransactionDetails.transaction[0].beneficiary_transaction?.[0]?.civil_status?.civil_status
                                            }}</span>
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
                                            this.computedClientTransactionDetails.transaction[0]?.beneficiary_transaction?.[0]?.city}}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Barangay:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClientTransactionDetails.transaction[0]?.beneficiary_transaction?.[0]?.barangay
                                            }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Street/Purok:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClientTransactionDetails.transaction[0]?.beneficiary_transaction?.[0]?.street
                                            }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">ID Type:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClientTransactionDetails.transaction[0]?.beneficiary_transaction[0]?.beneficiary_identification?.[0]?.identification_type?.id_type
                                            ??
                                            this.computedClientTransactionDetails.transaction[0]?.beneficiary_transaction[0]?.beneficiary_identification?.[0]?.other_identification_type?.other_id_type
                                            ??
                                            "" }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">ID Number:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClientTransactionDetails.transaction[0]?.beneficiary_transaction[0]?.beneficiary_identification?.[0]?.id_number
                                            ?? "" }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Precint:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold"> {{
                                            this.computedClientTransactionDetails.transaction[0]?.beneficiary_transaction[0]?.precint?.precint
                                            ?? "" }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Occupation:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClientTransactionDetails.transaction[0]?.beneficiary_transaction?.[0]?.beneficiary_occupation?.[0]?.occupation
                                            ?? "" }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">M. Income:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{
                                            this.computedClientTransactionDetails.transaction[0]?.beneficiary_transaction?.[0]?.beneficiary_occupation?.[0]?.pivot?.monthly_income.toString()
                                            ?? ""}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button"
                            v-if="this.computedClientTransactionDetails.transaction[0]?.beneficiary_transaction.length != 0 && (this.computedFetchAuthUserRolesPermissions.user_permissions.some(value => value.name === 'editBeneficiary') || this.computedFetchAuthUserRolesPermissions.user_roles.some(value => value === 'superAdmin'))"
                            @click="editBeneficiary(this.computedClientTransactionDetails.transaction[0]?.beneficiary_transaction?.[0].id, this.computedClientTransactionDetails.transaction[0].client.id)"
                            class="btn btn-success bg-gradient w-100 h-100 mt-2">EDIT BENEFICIARY</button>
                        <button type="button"
                            v-if="this.computedClientTransactionDetails.transaction[0]?.beneficiary_transaction.length == 0 && (this.computedFetchAuthUserRolesPermissions.user_permissions.some(value => value.name === 'addBeneficiary') || this.computedFetchAuthUserRolesPermissions.user_roles.some(value => value === 'superAdmin'))"
                            @click="addBeneficiary(this.computedClientTransactionDetails.transaction[0].client.id)"
                            class="btn btn-success bg-gradient w-100 h-100 mt-2">ADD BENEFICIARY</button>
                    </div>
                </div>
                <div class="col-4">
                    <div class="assistance-details">
                        <label class="fs-2 fw-bold ">Assistance Details</label>
                        <div>
                            <label class="fs-6 text-secondary fw-bold">Transaction ID:</label>
                            <span class="fs-6 ms-1 fw-bold">{{
                                this.computedClientTransactionDetails.transaction[0].transaction_id }}</span>
                        </div>
                        <hr>
                        <div class="row mt-2">
                            <div class="col-12 d-flex flex-row">
                                <div class="d-flex align-items-center justify-content-center"
                                    v-if="this.computedClientTransactionDetails.transaction[0].transaction_approve.transaction_claim?.transaction_claim_status_condition?.[0]?.status === 'TO CLAIM' && this.computedClientTransactionDetails.transaction[0].transaction_approve.transaction_approve_status_condition[0].status === 'APPROVED'">
                                    <i
                                        class="fa-solid fa-2xl fa-hands-holding bg-secondary text-white d-flex align-items-center circle-background"></i>
                                    <label class="fs-3 mx-2 fw-bold">{{
                                        this.computedClientTransactionDetails.transaction[0].transaction_approve.transaction_claim?.transaction_claim_status_condition?.[0]?.status
                                        }}</label>
                                </div>
                                <div class="d-flex align-items-center justify-content-center"
                                    v-else-if="this.computedClientTransactionDetails.transaction[0].transaction_approve.transaction_claim?.transaction_claim_status_condition?.[0]?.status === 'UNCLAIMED' && this.computedClientTransactionDetails.transaction[0].transaction_approve.transaction_approve_status_condition[0].status === 'APPROVED'">
                                    <i
                                        class="fa-solid fa-2xl fa-exclamation bg-danger text-white d-flex align-items-center circle-background"></i>
                                    <label class="fs-3 mx-2 fw-bold">{{
                                        this.computedClientTransactionDetails.transaction[0].transaction_approve.transaction_claim?.transaction_claim_status_condition?.[0]?.status
                                        }}</label>
                                </div>
                                <div class="d-flex align-items-center justify-content-center"
                                    v-else-if="this.computedClientTransactionDetails.transaction[0].transaction_approve.transaction_claim?.transaction_claim_status_condition?.[0]?.status === 'CLAIMED' && this.computedClientTransactionDetails.transaction[0].transaction_approve.transaction_approve_status_condition[0].status === 'APPROVED'">
                                    <i
                                        class="fa-solid fa-2xl fa-check bg-success text-white d-flex align-items-center circle-background"></i>
                                    <label class="fs-3 mx-2 fw-bold">{{
                                        this.computedClientTransactionDetails.transaction[0].transaction_approve.transaction_claim?.transaction_claim_status_condition?.[0]?.status
                                        }}</label>
                                </div>
                                <div class="d-flex align-items-center justify-content-center"
                                    v-else-if="this.computedClientTransactionDetails.transaction[0].transaction_approve.transaction_approve_status_condition[0].status === 'TO APPROVE'">
                                    <i
                                        class="fa-solid fa-2xl fa-exclamation bg-secondary text-white d-flex align-items-center circle-background"></i>
                                    <label class="fs-3 mx-2 fw-bold">{{
                                        this.computedClientTransactionDetails.transaction[0].transaction_approve.transaction_approve_status_condition[0].status
                                        }}</label>
                                </div>
                                <div class="d-flex align-items-center justify-content-center"
                                    v-else-if="this.computedClientTransactionDetails.transaction[0].transaction_approve.transaction_approve_status_condition[0].status === 'APPROVED'">
                                    <i
                                        class="fa-solid fa-2xl fa-exclamation bg-success text-white d-flex align-items-center circle-background"></i>
                                    <label class="fs-3 mx-2 fw-bold">{{
                                        this.computedClientTransactionDetails.transaction[0].transaction_approve.transaction_approve_status_condition[0].status
                                        }}</label>
                                </div>
                                <div class="d-flex align-items-center justify-content-center"
                                    v-else-if="this.computedClientTransactionDetails.transaction[0].transaction_approve.transaction_approve_status_condition[0].status === 'DECLINED'">
                                    <i
                                        class="fa-solid fa-2xl fa-exclamation bg-danger text-white d-flex align-items-center circle-background"></i>
                                    <label class="fs-3 mx-2 fw-bold">{{
                                        this.computedClientTransactionDetails.transaction[0].transaction_approve.transaction_approve_status_condition[0].status
                                        }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-5">
                                <label class="fs-6 text-secondary fw-bold">Transaction Date:</label>
                            </div>
                            <div class="col-7">
                                <span class="fs-6 ms-1 fw-bold">{{
                                    this.computedClientTransactionDetails.transaction[0].date_request }}</span>
                            </div>
                            <div class="col-5">
                                <label class="fs-6 text-secondary fw-bold">Date Approved:</label>
                            </div>
                            <div class="col-7">
                                <span
                                    class="fs-6 ms-1 fw-bold">{{ this.computedClientTransactionDetails.transaction[0].transaction_approve.transaction_approve_status_condition[0].pivot.status_condition_date }}</span>
                            </div>
                            <div class="col-5">
                                <label class="fs-6 text-secondary fw-bold">Date Claimed:</label>
                            </div>
                            <div class="col-7">
                                <span class="fs-6 ms-1 fw-bold">{{
                                    this.computedClientTransactionDetails.transaction[0].transaction_approve?.transaction_claim?.transaction_claim_status_condition?.[0]?.pivot?.status_condition_date
                                    ?? "" }}</span>
                            </div>
                            <div class="col-5">
                                <label class="fs-6 text-secondary fw-bold">Type:</label>
                            </div>
                            <div class="col-7">
                                <span class="fs-6 ms-1 fw-bold">{{
                                    this.computedClientTransactionDetails.transaction[0].assistance_type.assistance_type
                                    }}</span>
                            </div>
                            <div class="col-5">
                                <label class="fs-6 text-secondary fw-bold">Description:</label>
                            </div>
                            <div class="col-7">
                                <span class="fs-6 ms-1 fw-bold">{{
                                    this.computedClientTransactionDetails.transaction[0]?.assistance_description?.assistance_description ?? this.computedClientTransactionDetails.transaction[0].other_assistance_description?.other_assistance_description
                                    }}</span>
                            </div>
                            <div class="col-5">
                                <label class="fs-6 text-secondary fw-bold">Category:</label>
                            </div>
                            <div class="col-7">
                                <span class="fs-6 ms-1 fw-bold">{{
                                    this.computedClientTransactionDetails.transaction[0].assistance_category.assistance_category
                                    }}</span>
                            </div>
                            <div class="col-5">
                                <label class="fs-6 text-secondary fw-bold">Reason:</label>
                            </div>
                            <div class="col-7">
                                <span class="fs-6 ms-1 fw-bold">{{
                                    this.computedClientTransactionDetails.transaction[0]?.assistance_reason ?? ""
                                    }}</span>
                            </div>
                            <div class="col-5">
                                <label class="fs-6 text-secondary fw-bold">Amount:</label>
                            </div>
                            <div class="col-7">
                                <span class="fs-6 ms-1 fw-bold">{{
                                    this.computedClientTransactionDetails.transaction[0].transaction_approve?.transaction_approve_amount?.amount.toString()
                                    ?? "" }}</span>
                            </div>
                            <div class="col-12 mt-3">
                                <button type="button" data-bs-toggle="modal" v-if="(this.computedFetchAuthUserRolesPermissions.user_permissions.some(value => value.name === 'editTransaction') || this.computedFetchAuthUserRolesPermissions.user_roles.some(value => value === 'superAdmin'))" data-bs-target="#editTransaction"
                                    class="btn btn-secondary bg-gradient w-100 h-100">EDIT TRANSACTION</button>
                            </div>
                            <div class="col-12 mt-3"  v-if="this.computedClientTransactionDetails.transaction[0].transaction_approve?.transaction_approve_status_condition[0].id ==  '2' && (this.computedFetchAuthUserRolesPermissions.user_permissions.some(value => value.name === 'voidTransaction') || this.computedFetchAuthUserRolesPermissions.user_roles.some(value => value === 'superAdmin'))">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#voidModal"
                                    class="btn btn-danger bg-gradient w-100 h-100" >VOID TRANSACTION</button>
                            </div>
                            <div class="col-12 mt-3" v-if="(this.computedFetchAuthUserRolesPermissions.user_permissions.some(value => value.name === 'deleteTransaction') || this.computedFetchAuthUserRolesPermissions.user_roles.some(value => value === 'superAdmin'))">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#deleteTransactionModal"
                                    class="btn btn-danger bg-gradient w-100 h-100" >DELETE TRANSACTION</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <div v-if="!this.computedClientTransactionDetailsLoader">
        <!-- Edit Transaction modal -->
        <div class="modal edit-transaction fade" id="editTransaction" tabindex="-1" aria-labelledby="editTransactionLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTransactionLabel">Edit Transaction</h5>
                        <button type="button" id='btn-close-edit-transaction' class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="container py-4">
                        <div class="row">
                            <div class="col-12">
                                <label for="transactionDate">Transaction Date</label>
                                <input type="date" v-on:keyup="this.handleSelect('transactionDate')" :class="{'form-control':true, 'input-field':true, 'is-invalid':this.proceedValidation.transactionDate}" v-model="this.computedTypeOfAssistance.transactionDate">
                            </div>
                            <div class="col-6">
                                <label for="agency">Agency</label>
                                <select id="agency" v-model="this.computedTypeOfAssistance.agency"
                                    @change="this.handleSelect('agency')"
                                    :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.agency, 'select-field': true }"
                                    class="custom-select form-select">
                                    <option value="" selected></option>
                                    <option :key="option.id" :value="option.id"
                                        v-for="(option, index) in this.computedFormSeeder.assistance_program_type">{{
                                        option.agency_name }}</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="agencyProgram">Agency Program</label>
                                <select id="agencyProgram" v-model="this.computedTypeOfAssistance.agencyProgram"
                                    @change="this.handleSelect('agencyProgram')"
                                    :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.agencyProgram, 'select-field': true }"
                                    class="custom-select form-select">
                                    <option value="" selected></option>
                                    <option :value="option.id" :key="option.id"
                                        v-for="(option, index) in this.computedFormSeeder.assistance_program_type?.[this.computedTypeOfAssistance.agency]?.['agency_program']">
                                        {{ option.agency_program_name+" ("+option.agency_program_abbreviation+")" }}</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="assistanceType">Type Of Assistance</label>
                                <select id="assistanceType" v-model="this.computedTypeOfAssistance.typeOfAssistance"
                                    @change="this.handleSelect('assistanceType')"
                                    :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.typeOfAssistance, 'select-field': true }"
                                    class="custom-select form-select">
                                    <option value="" selected></option>
                                    <option :key="option.id" :value="option.id" v-for="(option, index) in this.computedFormSeeder.assistance_program_type?.[this.computedTypeOfAssistance.agency]?.['agency_program']?.[this.computedTypeOfAssistance.agencyProgram]?.['assistance_type_agency_program']"> {{ option.assistance_type }}</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="descriptionOfAssistance">Description of Assistance</label>
                                <select id="descriptionOfAssistance"
                                    v-model="this.computedTypeOfAssistance.descriptionOfAssistance"
                                    @change="this.handleSelect('descriptionOfAssistance')"
                                    :class="{ 'form-select': true, 'is-invalid': this.proceedValidation.descriptionOfAssistance, 'select-field': true }"
                                    class="custom-select form-select">
                                    <option value="" selected></option>
                                    <option :key="option.id" :value="option.id"
                                        v-for="(option, index) in this.computedFormSeeder.assistance_program_type?.[this.computedTypeOfAssistance.agency]?.['agency_program']?.[this.computedTypeOfAssistance.agencyProgram]?.['assistance_type_agency_program']?.[this.computedTypeOfAssistance.typeOfAssistance]?.['assistance_type_description']">
                                        {{ option.assistance_description }}</option>
                                    <option value="OTHER">OTHER</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="otherDescriptionOfAssistance">Other Description of Assistance</label>
                                <input :disabled="this.otherDescriptionOfAssistanceDisabled"
                                    v-model="this.computedTypeOfAssistance.otherDescriptionOfAssistance"
                                    v-on:keyup="this.handleSelect('otherDescriptionOfAssistance')"
                                    :class="{ 'form-control': true, 'input-field': true, 'is-invalid': this.proceedValidation.otherDescriptionOfAssistance, }">
                            </div>
                            <div class="col-4" v-if="this.computedTypeOfAssistance.typeOfAssistance == 2">
                                <label for="amount">MAIP Code</label>
                                <input v-model="this.computedTypeOfAssistance.maipCode"
                                    v-on:keyup="this.handleSelect('maipCode')"
                                    :class="{ 'form-control': true, 'input-field': true, 'is-invalid': this.proceedValidation.maipCode }">
                            </div>
                            <div class="col-4" v-if="this.computedTypeOfAssistance.typeOfAssistance == 2">
                                <label for="hospitalName">Hospital Name</label>
                                <input v-model="this.computedTypeOfAssistance.hospitalName"
                                    v-on:keyup="this.handleSelect('hospitalName')"
                                    :class="{ 'form-control': true, 'input-field': true, 'is-invalid': this.proceedValidation.hospitalName }">
                            </div>
                            <div class="col-4" v-if="this.computedTypeOfAssistance.typeOfAssistance == 2">
                                <label for="hospitalType">Hospital Type</label>
                                <select v-model="this.computedTypeOfAssistance.hospitalType"
                                    @change="this.handleSelect('hospitalType')"
                                    :class="{ 'form-select': true, 'select-field': true, 'is-invalid': this.proceedValidation.hospitalType }">
                                <option value=""></option>
                                <option :value="option.id" :key="option.id" v-for="(option,index) in this.computedFormSeeder.hospital_type">{{ option.hospital_type }}</option>
                                </select>
                            </div>
                            <div class="col-5">
                                <label for="reasonOfAssistance">Reason of Assistance</label>
                                <textarea v-on:keyup="this.handleSelect()" :class="{'form-control':true, 'input-field':true}" v-model="this.computedTypeOfAssistance.reasonOfAssistance"></textarea>
                            </div>
                            <div class="col-4">
                                <label for="dueDate">Due Date</label>
                                <input type="date" v-model="this.computedTypeOfAssistance.dueDate" :class="{'form-control':true, 'input-field':true}">
                            </div>
                            <div class="col-3">
                                <label for="category">Category</label>
                                <select v-model="this.computedTypeOfAssistance.category"
                                    @change="this.handleSelect('category')"
                                    :class="{ 'form-select': true, 'select-field': true, 'is-invalid': this.proceedValidation.category,'is-invalid':this.proceedValidation.category }">
                                <option value=""></option>
                                <option :value="option.id" :key="option.id" v-for="(option,index) in this.computedFormSeeder.assistance_category">{{ option.assistance_category }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button type="button" class="btn btn-primary" @click="this.handleSelect('update')">Yes</button>
                    </div>
                </div>
            </div>
        </div>   
    </div>
    <div v-if="!this.computedClientTransactionDetailsLoader">
        <!-- Void modal -->
        <div class="modal fade" id="voidModal" tabindex="-1" aria-labelledby="voidModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="voidModalLabel">Are you sure to void this transaction?</h5>
                        <button type="button" id="btn-void-close" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button type="button" class="btn btn-primary" @click="voidTransaction(this.$route.params.id)">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-if="!this.computedClientTransactionDetailsLoader">
        <!-- Delete modal -->
        <div class="modal fade" id="deleteTransactionModal" tabindex="-1" aria-labelledby="deleteTransactionModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteTransactionModalLabel">Are you sure to delete this transaction?</h5>
                        <button type="button" id="btn-delete-transaction-close" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button type="button" class="btn btn-primary" @click="deleteTransaction(this.$route.params.id)">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>




</template>
<style scoped>
button {
    border-radius: 25px;
}

.select-field,
.input-field {
    border-radius: 25px;

}

.circle-background {
    background-color: #28a745;
    /* Green background similar to 'text-success' */
    width: 50px;
    height: 50px;
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
        margin: 0;
    padding-left: 250px;
    overflow-y: auto;
    height: -webkit-calc(100% - 50px);
    height: -moz-calc(100% - 50px);
    height: calc(100% - 50px);

}

img {
    height: 100px;
    width: 100px;
    border-radius: 100px;
    object-fit: fill;
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
        Toast
    },
    
    beforeMount() {

        this.toast = useToast();
        this.computedHeaderText = 'Client Transactions > Transaction Details';
        this.computedClientTransactionDetailsLoader = true;   
        this.$store.dispatch('fetchSeeders').then(response1 => {
            this.$store.dispatch('fetchClientTransactionDetails', { 'id': this.$route.params.id }).then(response2 => {
                console.log(response2);

                this.computedTypeOfAssistance.transactionDate = this.computedClientTransactionDetails.transaction[0].date_request;
                this.computedTypeOfAssistance.agency = this.computedClientTransactionDetails.transaction[0].agency_id;
                this.computedTypeOfAssistance.agencyProgram = this.computedClientTransactionDetails.transaction[0].agency_program_id;
                this.computedTypeOfAssistance.typeOfAssistance = this.computedClientTransactionDetails.transaction[0].assistance_type_id;

                if(this.computedClientTransactionDetails.transaction[0].assistance_type_id == 2){
                    this.computedTypeOfAssistance.maipCode =  this.computedClientTransactionDetails.transaction[0]?.hospital?.maip_code;
                    this.computedTypeOfAssistance.hospitalName = this.computedClientTransactionDetails.transaction[0]?.hospital?.hospital_name;
                    this.computedTypeOfAssistance.hospitalType = this.computedClientTransactionDetails.transaction[0]?.hospital?.hospital_type?.id;
                }

                if(!this.computedClientTransactionDetails.transaction[0].assistance_description_id){
                    this.otherDescriptionOfAssistanceDisabled = true;
                    this.computedTypeOfAssistance.typeOfAssistance = 'OTHER'
                    this.computedTypeOfAssistance.otherDescriptionOfAssistance = this.computedClientTransactionDetails.transaction[0].other_assistance_description?.other_assistance_description;
                }else{
                     this.otherDescriptionOfAssistanceDisabled = true;
                     this.computedTypeOfAssistance.otherDescriptionOfAssistance = ''
                    this.computedTypeOfAssistance.descriptionOfAssistance = this.computedClientTransactionDetails.transaction[0].assistance_description_id;
                }



                this.computedTypeOfAssistance.reasonOfAssistance = this.computedClientTransactionDetails.transaction[0]?.assistance_reason;
                this.computedTypeOfAssistance.dueDate = this.computedClientTransactionDetails.transaction[0]?.due_date;
                this.computedTypeOfAssistance.category = this.computedClientTransactionDetails.transaction[0].assistance_category_id;
                this.computedClientTransactionDetailsLoader = false;
            });
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
        deleteTransaction(transactionID){
            document.getElementById('btn-delete-transaction-close').click();
            this.computedClientTransactionDetailsLoader = true;
            this.$store.dispatch('deleteTransaction',{'id':transactionID}).then(response => {
                this.computedClientTransactionDetailsLoader = false;
                this.computedTransactionToast = true;
               this.$router.push({name:'clientTransactionTable'});
            });
        },
        voidTransaction(transactionID){
            document.getElementById('btn-void-close').click();
            this.computedClientTransactionDetailsLoader = true;
            this.$store.dispatch('voidTransaction',{'id':transactionID}).then(response => {
                this.$store.dispatch('fetchClientTransactionDetails', { 'id': this.$route.params.id }).then(response2 => {
                              

                              this.computedTypeOfAssistance.transactionDate = this.computedClientTransactionDetails.transaction[0].date_request;
                              this.computedTypeOfAssistance.agency = this.computedClientTransactionDetails.transaction[0].agency_id;
                              this.computedTypeOfAssistance.agencyProgram = this.computedClientTransactionDetails.transaction[0].agency_program_id;
                              this.computedTypeOfAssistance.typeOfAssistance = this.computedClientTransactionDetails.transaction[0].assistance_type_id;

                              if(this.computedClientTransactionDetails.transaction[0].assistance_type_id == 2){
                                  this.computedTypeOfAssistance.maipCode =  this.computedClientTransactionDetails.transaction[0]?.hospital?.maip_code;
                                  this.computedTypeOfAssistance.hospitalName = this.computedClientTransactionDetails.transaction[0]?.hospital?.hospital_name;
                                  this.computedTypeOfAssistance.hospitalType = this.computedClientTransactionDetails.transaction[0]?.hospital?.hospital_type?.id;
                              }

                              if(!this.computedClientTransactionDetails.transaction[0].assistance_description_id){
                                  this.otherDescriptionOfAssistanceDisabled = true;
                                  this.computedTypeOfAssistance.typeOfAssistance = 'OTHER'
                                  this.computedTypeOfAssistance.otherDescriptionOfAssistance = this.computedClientTransactionDetails.transaction[0].other_assistance_description?.other_assistance_description;
                              }else{
                                  this.otherDescriptionOfAssistanceDisabled = true;
                                  this.computedTypeOfAssistance.otherDescriptionOfAssistance = ''
                                  this.computedTypeOfAssistance.descriptionOfAssistance = this.computedClientTransactionDetails.transaction[0].assistance_description_id;
                              }



                              this.computedTypeOfAssistance.reasonOfAssistance = this.computedClientTransactionDetails.transaction[0]?.assistance_reason;
                              this.computedTypeOfAssistance.dueDate = this.computedClientTransactionDetails.transaction[0]?.due_date;
                              this.computedTypeOfAssistance.category = this.computedClientTransactionDetails.transaction[0].assistance_category_id;

                              this.computedClientTransactionDetailsLoader = false;
                              this.computedTransactionToast = true;
                              if(this.computedTransactionToast){
                                this.toast.add({ severity: 'success', summary: 'Success', detail: 'Transaction successfully voided.', life: 5000 });
                                this.computedTransactionToast = false;
                              }
                          });
            });
        },
        handleSelect(textfield) {

            for(const [key,value] of Object.entries(this.computedTypeOfAssistance)){
                if(key === 'otherDescriptionOfAssistance' || key === 'maipCode' || key === 'hospitalName' || key === 'reasonOfAssistance'){
                    if(typeof this.computedTypeOfAssistance[key] === 'string' && this.computedTypeOfAssistance[key]){
                        this.computedTypeOfAssistance[key] = this.computedTypeOfAssistance[key].toUpperCase();
                    }
                }
            }

            if (textfield === 'agency') {
                if (this.computedTypeOfAssistance.agency === '' || !this.computedTypeOfAssistance.agency) {

                    this.proceedValidation.agency = true;
                    for (const [key, value] of Object.entries(this.proceedValidation)) {
                        if (key != 'agency' && key != 'transactionDate' && key != 'reasonOfAssistance' && key != 'dueDate' && key != 'category') {
                            this.proceedValidation[key] = false;
                        }
                    }

                    for (const [key, value] of Object.entries(this.computedTypeOfAssistance)) {
                        if (key != 'agency' && key != 'transactionDate' && key != 'reasonOfAssistance' && key != 'dueDate' && key != 'category') {
                            this.computedTypeOfAssistance[key] = '';
                        }
                    }
                } else {
                    this.proceedValidation.agency = false;

                    for (const [key, value] of Object.entries(this.proceedValidation)) {
                        if (key != 'agency' && key != 'transactionDate' && key != 'reasonOfAssistance' && key != 'dueDate' && key != 'category') {
                            this.proceedValidation[key] = false;
                        }
                    }

                    for (const [key, value] of Object.entries(this.computedTypeOfAssistance)) {
                        if (key != 'agency' && key != 'transactionDate' && key != 'reasonOfAssistance' && key != 'dueDate' && key != 'category') {
                            this.computedTypeOfAssistance[key] = '';
                        }
                    }
                    
                }
            }else if (textfield === 'agencyProgram') {
                if (this.computedTypeOfAssistance.agencyProgram === '' || !this.computedTypeOfAssistance.agencyProgram) {
                    this.proceedValidation.agencyProgram = true;

                    for (const [key, value] of Object.entries(this.proceedValidation)) {
                        if (key != 'agency' && key != 'agencyProgram' && key != 'transactionDate' && key != 'reasonOfAssistance' && key != 'dueDate' && key != 'category') {
                            this.proceedValidation[key] = false;
                        }
                    }

                    for (const [key, value] of Object.entries(this.computedTypeOfAssistance)) {
                        if (key != 'agency' && key != 'agencyProgram' && key != 'transactionDate' && key != 'reasonOfAssistance' && key != 'dueDate' && key != 'category') {
                            this.computedTypeOfAssistance[key] = '';
                        }
                    }
                } else {
                    this.proceedValidation.agencyProgram = false;
                    
                    for (const [key, value] of Object.entries(this.proceedValidation)) {
                        if (key != 'agency' && key != 'agencyProgram' && key != 'transactionDate' && key != 'reasonOfAssistance' && key != 'dueDate' && key != 'category') {
                            this.proceedValidation[key] = false;
                        }
                    }

                    for (const [key, value] of Object.entries(this.computedTypeOfAssistance)) {
                        if (key != 'agency' && key != 'agencyProgram' && key != 'transactionDate' && key != 'reasonOfAssistance' && key != 'dueDate' && key != 'category') {
                            this.computedTypeOfAssistance[key] = '';
                        }
                    }
                }
            }else if (textfield === 'assistanceType') {
                if (this.computedTypeOfAssistance.typeOfAssistance === '' || !this.computedTypeOfAssistance.typeOfAssistance) {

                    this.proceedValidation.typeOfAssistance = true;

                    for (const [key, value] of Object.entries(this.proceedValidation)) {
                        if (key != 'agency' && key != 'agencyProgram' && key != 'typeOfAssistance' && key != 'transactionDate' && key != 'reasonOfAssistance' && key != 'dueDate' && key != 'category') {
                            this.proceedValidation[key] = false;
                        }
                    }
                    for (const [key, value] of Object.entries(this.computedTypeOfAssistance)) {
                        if (key != 'agency' && key != 'agencyProgram' && key != 'typeOfAssistance' && key != 'transactionDate' && key != 'reasonOfAssistance' && key != 'dueDate' && key != 'category') {
                            this.computedTypeOfAssistance[key] = '';
                        }
                    }
                }else{
                    this.proceedValidation.typeOfAssistance = false;

                    for (const [key, value] of Object.entries(this.proceedValidation)) {
                        if (key != 'agency' && key != 'agencyProgram' && key != 'typeOfAssistance' && key != 'transactionDate' && key != 'reasonOfAssistance' && key != 'dueDate' && key != 'category') {
                            this.proceedValidation[key] = false;
                        }
                    }
                    for (const [key, value] of Object.entries(this.computedTypeOfAssistance)) {
                        if (key != 'agency' && key != 'agencyProgram' && key != 'typeOfAssistance' && key != 'transactionDate' && key != 'reasonOfAssistance' && key != 'dueDate' && key != 'category') {
                            this.computedTypeOfAssistance[key] = '';
                        }
                    }
                }
            }else if (textfield === 'descriptionOfAssistance') {
                if(this.computedTypeOfAssistance.descriptionOfAssistance === '' || !this.computedTypeOfAssistance.descriptionOfAssistance) {
                    this.proceedValidation.descriptionOfAssistance = true;
                    this.otherDescriptionOfAssistanceDisabled = true;
                    for (const [key, value] of Object.entries(this.proceedValidation)) {
                        if (key != 'agency' && key != 'agencyProgram' && key != 'typeOfAssistance' && key != 'descriptionOfAssistance' && key != 'transactionDate' && key != 'reasonOfAssistance' && key != 'dueDate' && key != 'category' && key != 'maipCode' && key != 'hospitalName' && key != 'hospitalType') {
                            this.proceedValidation[key] = false;
                        }
                    }
                    for (const [key, value] of Object.entries(this.computedTypeOfAssistance)) {
                        if (key != 'agency' && key != 'agencyProgram' && key != 'typeOfAssistance' && key != 'descriptionOfAssistance' && key != 'transactionDate' && key != 'reasonOfAssistance' && key != 'dueDate' && key != 'category' && key != 'maipCode' && key != 'hospitalName' && key != 'hospitalType') {
                            this.computedTypeOfAssistance[key] = '';
                        }
                    }
                }else if (this.computedTypeOfAssistance.descriptionOfAssistance === 'OTHER') {
                    this.otherDescriptionOfAssistanceDisabled = false;
                    this.proceedValidation.descriptionOfAssistance = false;

                    for (const [key, value] of Object.entries(this.proceedValidation)) {
                        if (key != 'agency' && key != 'agencyProgram' && key != 'typeOfAssistance' && key != 'descriptionOfAssistance' && key != 'transactionDate' && key != 'reasonOfAssistance' && key != 'dueDate' && key != 'category' && key != 'maipCode' && key != 'hospitalName' && key != 'hospitalType') {
                            this.proceedValidation[key] = false;
                        }
                    }
                    for (const [key, value] of Object.entries(this.computedTypeOfAssistance)) {
                        if (key != 'agency' && key != 'agencyProgram' && key != 'typeOfAssistance' && key != 'descriptionOfAssistance' && key != 'transactionDate' && key != 'reasonOfAssistance' && key != 'dueDate' && key != 'category' && key != 'maipCode' && key != 'hospitalName' && key != 'hospitalType') {
                            this.computedTypeOfAssistance[key] = '';
                        }
                    }
                }else{
                    this.otherDescriptionOfAssistanceDisabled = true;
                    this.proceedValidation.descriptionOfAssistance = false;

                    for (const [key, value] of Object.entries(this.proceedValidation)) {
                        if (key != 'agency' && key != 'agencyProgram' && key != 'typeOfAssistance' && key != 'descriptionOfAssistance' && key != 'transactionDate' && key != 'reasonOfAssistance' && key != 'dueDate' && key != 'category' && key != 'maipCode' && key != 'hospitalName' && key != 'hospitalType') {
                            this.proceedValidation[key] = false;
                        }
                    }
                    for(const [key, value] of Object.entries(this.computedTypeOfAssistance)) {
                        if (key != 'agency' && key != 'agencyProgram' && key != 'typeOfAssistance' && key != 'descriptionOfAssistance' && key != 'transactionDate' && key != 'reasonOfAssistance' && key != 'dueDate' && key != 'category' && key != 'maipCode' && key != 'hospitalName' && key != 'hospitalType') {
                            this.computedTypeOfAssistance[key] = '';
                        }
                    }
                }
            }

            if(textfield === 'otherDescriptionOfAssistance' || textfield === '1') {
                if (!this.otherDescriptionOfAssistanceDisabled) {
                    if (this.computedTypeOfAssistance.otherDescriptionOfAssistance === '' || !this.computedTypeOfAssistance.otherDescriptionOfAssistance) {
                        this.proceedValidation.otherDescriptionOfAssistance = true;
                    } else {
                        this.proceedValidation.otherDescriptionOfAssistance = false;
                    }
                } else {
                    this.proceedValidation.otherDescriptionOfAssistance = false;
                }
            }

            if(textfield === 'maipCode' || textfield === 'update'){
                if(this.computedTypeOfAssistance.typeOfAssistance == '2'){
                    if(this.computedTypeOfAssistance.maipCode === '' || !this.computedTypeOfAssistance.maipCode){
                        this.proceedValidation.maipCode = true;
                    }else{
                        this.proceedValidation.maipCode = false;
                    }
                }
            }

            if(textfield === 'hospitalName' || textfield === 'update'){
                if(this.computedTypeOfAssistance.typeOfAssistance == '2'){
                    if(this.computedTypeOfAssistance.hospitalName === '' || !this.computedTypeOfAssistance.hospitalName){
                        this.proceedValidation.hospitalName = true;
                    }else{
                        this.proceedValidation.hospitalName = false;
                    }
                }
            }

            if(textfield === 'hospitalType' || textfield === 'update'){
                if(this.computedTypeOfAssistance.typeOfAssistance == '2'){
                    if(this.computedTypeOfAssistance.hospitalType === '' || !this.computedTypeOfAssistance.hospitalType){
                        this.proceedValidation.hospitalType = true;
                    }else{
                        this.proceedValidation.hospitalType = false;
                    }
                }
            }

            if(textfield === 'transactionDate' || textfield === 'update'){
                if(this.computedTypeOfAssistance.transactionDate === '' || !this.computedTypeOfAssistance.transactionDate){
                    this.proceedValidation.transactionDate = true;
                }else{
                    this.proceedValidation.transactionDate = false;
                }
            }

            if(textfield === 'category' || textfield === 'update'){
                if(this.computedTypeOfAssistance.category === '' || !this.computedTypeOfAssistance.category){
                    this.proceedValidation.category = true;
                }else{
                    this.proceedValidation.category = false;
                }
            }

            if(textfield === 'update'){
                if(this.computedTypeOfAssistance.agency === '' || !this.computedTypeOfAssistance.agency){
                    this.proceedValidation.agency = true;
                }else{
                    this.proceedValidation.agency = false;
                }

                if(this.computedTypeOfAssistance.agencyProgram === '' || !this.computedTypeOfAssistance.agencyProgram){
                    this.proceedValidation.agencyProgram = true;
                }else{
                    this.proceedValidation.agencyProgram = false;
                }

                if(this.computedTypeOfAssistance.typeOfAssistance === '' || !this.computedTypeOfAssistance.typeOfAssistance){
                    this.proceedValidation.typeOfAssistance = true;
                }else{
                    this.proceedValidation.typeOfAssistance = false;
                }

                if(this.computedTypeOfAssistance.descriptionOfAssistance === '' || !this.computedTypeOfAssistance.descriptionOfAssistance){
                    this.proceedValidation.descriptionOfAssistance = true;
                }else if(this.computedTypeOfAssistance === 'OTHER'){
                    if(this.computedTypeOfAssistance.otherDescriptionOfAssistance === '' || !this.computedTypeOfAssistance.otherDescriptionOfAssistance){
                        this.proceedValidation.otherDescriptionOfAssistance = true;
                    }else{
                        this.proceedValidation.otherDescriptionOfAssistance = false;
                    }
                }else{
                    this.proceedValidation.descriptionOfAssistance = false;
                }

                if(this.computedTypeOfAssistance.typeOfAssistance == '2'){
                    if(this.computedTypeOfAssistance.maipCode === '' || !this.computedTypeOfAssistance.maipCode){
                        this.proceedValidation.maipCode = true;
                    }else{
                        this.proceedValidation.maipCode = false;
                    }

                    if(this.computedTypeOfAssistance.hospitalName === '' || !this.computedTypeOfAssistance.hospitalName){
                        this.proceedValidation.hospitalName = true;
                    }else{
                        this.proceedValidation.hospitalName = false;
                    }

                    if(this.computedTypeOfAssistance.hospitalType === '' || !this.computedTypeOfAssistance.hospitalType){
                        this.proceedValidation.hospitalType = true;
                    }else{
                        this.proceedValidation.hospitalType = false;
                    }
                }

            }



            if(textfield === 'update'){
                let proc = true;
                    for(const [key,value] of Object.entries(this.proceedValidation)){

                        if(this.proceedValidation[key] === true){
                            proc = false;
                            break;
                        }
                    }

                    if(proc){
                        document.getElementById('btn-close-edit-transaction').click();
                        this.computedClientTransactionDetailsLoader = true;
                        this.$store.dispatch('updateClientTransactionAssistance',{'typeOfAssistance':this.computedTypeOfAssistance,'id':this.$route.params.id}).then(response =>{
                            this.$store.dispatch('fetchClientTransactionDetails', { 'id': this.$route.params.id }).then(response2 => {
                              

                                this.computedTypeOfAssistance.transactionDate = this.computedClientTransactionDetails.transaction[0].date_request;
                                this.computedTypeOfAssistance.agency = this.computedClientTransactionDetails.transaction[0].agency_id;
                                this.computedTypeOfAssistance.agencyProgram = this.computedClientTransactionDetails.transaction[0].agency_program_id;
                                this.computedTypeOfAssistance.typeOfAssistance = this.computedClientTransactionDetails.transaction[0].assistance_type_id;

                                if(this.computedClientTransactionDetails.transaction[0].assistance_type_id == 2){
                                    this.computedTypeOfAssistance.maipCode =  this.computedClientTransactionDetails.transaction[0]?.hospital?.maip_code;
                                    this.computedTypeOfAssistance.hospitalName = this.computedClientTransactionDetails.transaction[0]?.hospital?.hospital_name;
                                    this.computedTypeOfAssistance.hospitalType = this.computedClientTransactionDetails.transaction[0]?.hospital?.hospital_type?.id;
                                }

                                if(!this.computedClientTransactionDetails.transaction[0].assistance_description_id){
                                    this.otherDescriptionOfAssistanceDisabled = true;
                                    this.computedTypeOfAssistance.typeOfAssistance = 'OTHER'
                                    this.computedTypeOfAssistance.otherDescriptionOfAssistance = this.computedClientTransactionDetails.transaction[0].other_assistance_description?.other_assistance_description;
                                }else{
                                    this.otherDescriptionOfAssistanceDisabled = true;
                                    this.computedTypeOfAssistance.otherDescriptionOfAssistance = ''
                                    this.computedTypeOfAssistance.descriptionOfAssistance = this.computedClientTransactionDetails.transaction[0].assistance_description_id;
                                }



                                this.computedTypeOfAssistance.reasonOfAssistance = this.computedClientTransactionDetails.transaction[0]?.assistance_reason;
                                this.computedTypeOfAssistance.dueDate = this.computedClientTransactionDetails.transaction[0]?.due_date;
                                this.computedTypeOfAssistance.category = this.computedClientTransactionDetails.transaction[0].assistance_category_id;

                                this.computedClientTransactionDetailsLoader = false;
                                this.computedTransactionToast = true;
                                if(this.computedTransactionToast){
                                    this.toast.add({ severity: 'success', summary: 'Success', detail: 'Transaction successfully updated.', life: 5000 });
                                    this.computedTransactionToast = false;
                                }
                            });
                        });
                    }
                }
            
        },
        editBeneficiary(beneficiaryID, clientID) {
            this.$router.push({ name: 'clientTransactionEditBeneficiary', params: { 'id': this.$route.params.id, 'id2': beneficiaryID, 'id3': clientID } });
        },
        addBeneficiary(clientID) {
            console.log(clientID);
            this.$router.push({ name: 'clientTransactionAddBeneficiary', params: { 'id': this.$route.params.id, 'id2': clientID } });
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
        computedTransactionToast:{
            get(){
                return this.$store.state.transactionToast;
            },
            set(value){
                return this.$store.commit('setTransactionToast',{'transactionToast':value});
            }
        },
        computedApproveDeclineClient: {
            get() {
                return this.$store.state.approveDeclineClient;
            },
            set(value) {
                this.$store.commit('setApproveDeclineClient', { 'approveDeclineClient': value });
            }
        },
        computedTypeOfAssistance: {
            get() {
                return this.$store.state.typeOfAssistance;
            },
            set(value) {
                this.$store.commit('setTypeOfAssistance', { 'typeOfAssistance': value });
            }
        },
        computedFormSeeder: {
            get() {
                return this.$store.state.formSeeder;
            }
        },
        computedClientTransactionDetails: {
            get() {
                return this.$store.state.clientTransactionDetails;
            },
            set(value) {
                this.$store.commit('setClientTransactionDetails', { 'clientTransactionDetails': value });
            }
        },
        computedClientTransactionDetailsLoader: {
            get() {
                return this.$store.state.clientTransactionDetailsLoader;
            },
            set(value) {
                this.$store.commit('setClientTransactionDetailsLoader', { 'clientTransactionDetailsLoader': value });
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
            otherDescriptionOfAssistanceDisabled: true,
            proceedValidation: {
                agency: false,
                agencyProgram: false,
                typeOfAssistance: false,
                descriptionOfAssistance: false,
                otherDescriptionOfAssistance: false,
                hospitalType:false,
                maipCode:false,
                hospitalName:false,
                transactionDate:false,
                category:false,
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