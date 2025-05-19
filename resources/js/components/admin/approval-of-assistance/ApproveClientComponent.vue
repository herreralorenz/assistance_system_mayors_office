<template>
    <LoaderComponent v-if="this.computedApproveClientDetailsLoader"></LoaderComponent>
    <Toast />
    <div class="form-group approve-client py-4" v-if="!this.computedApproveClientDetailsLoader">

        <div class="container-fluid">
                <div class="row">
                    <div class="col-8">
                        <div class="client-information">
                            <label class="fs-2 fw-bold ">Client Information</label>
                            <hr>
                            <div class="d-flex flex-row">
                                <img :src="'/api/get-client-approve-image?data[clientApproveImage]='+computedApproveClientDetails.transactionArray[0]?.client?.image[0]?.file_name" ></img>
                                <div class="d-flex flex-column justify-content-center ms-2">
                                    <span class="fs-5 fw-bold">{{ this.computedApproveClientDetails.transactionArray[0].client.first_name+" "+(this.computedApproveClientDetails.transactionArray[0].client.middle_name ? this.computedApproveClientDetails.transactionArray[0].client.middle_name : "")+" "+this.computedApproveClientDetails.transactionArray[0].client.last_name+" "+(this.computedApproveClientDetails.transactionArray[0].client?.suffix?.suffix ? this.computedApproveClientDetails.transactionArray[0].client?.suffix?.suffix : "") }}</span>
                                    <span class="fs-5 fw-bold underline text-secondary"><u>{{ this.computedApproveClientDetails.transactionArray[0].client.contact_number[0].contact_number.slice(0,4)+"-"+this.computedApproveClientDetails.transactionArray[0].client.contact_number[0].contact_number.slice(4,8)+"-"+this.computedApproveClientDetails.transactionArray[0].client.contact_number[0].contact_number.slice(8,11) }}</u></span>
                                </div>
                            </div>
                            <div class="clientDetails row mt-3">
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">First Name:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{ this.computedApproveClientDetails.transactionArray[0].client.first_name }}</span>
                                        </div>     
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">Middle Name:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{ this.computedApproveClientDetails.transactionArray[0].client.middle_name ? this.computedApproveClientDetails.transactionArray[0].client.middle_name : "" }}</span>
                                        </div>     
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">Last Name:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{ this.computedApproveClientDetails.transactionArray[0].client.last_name }}</span>
                                        </div>  
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">Suffix:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{ this.computedApproveClientDetails.transactionArray[0].client?.suffix?.suffix ? this.computedApproveClientDetails.transactionArray[0].client?.suffix?.suffix : "" }}</span>
                                        </div>  
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">Birthdate:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{ this.computedApproveClientDetails.transactionArray[0].client.birthdate }}</span>
                                        </div>  
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">Age:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{ this.computedApproveClientDetails.transactionArray[0].client.age }}</span>
                                        </div>  
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">Sex:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{ this.computedApproveClientDetails.transactionArray[0].client.sex.sex }}</span>
                                        </div>   
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">Civil Status:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{ this.computedApproveClientDetails.transactionArray[0].client.civil_status.civil_status }}</span>
                                        </div>
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">Relationship to Beneficiary:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.client_beneficiary_relationship[0]?.relationship.relationship}}</span>
                                        </div>    
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">City:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{ this.computedApproveClientDetails.transactionArray[0].client.city}}</span>
                                        </div>  
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">Barangay:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{ this.computedApproveClientDetails.transactionArray[0].client.barangay}}</span>
                                        </div>  
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">Street/Purok:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{ this.computedApproveClientDetails.transactionArray[0].client.street}}</span>
                                        </div>  
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">ID Type:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{ this.computedApproveClientDetails.transactionArray[0].client?.client_identification[0]?.identification_type ? this.computedApproveClientDetails.transactionArray[0].client?.client_identification[0]?.identification_type?.id_type : this.computedApproveClientDetails.transactionArray[0].client?.client_identification[0]?.other_identification_type?.other_id_type}}</span>
                                        </div>  
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">ID Number:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{ this.computedApproveClientDetails.transactionArray[0].client?.client_identification[0]?.id_number}}</span>
                                        </div>  
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">Precint:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{ this.computedApproveClientDetails.transactionArray[0].client?.precint?.precint}}</span>
                                        </div>  
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">Occupation:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{ this.computedApproveClientDetails.transactionArray[0].client?.client_occupation[0]?.occupation }}</span>
                                        </div>  
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">M. Income:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{ this.computedApproveClientDetails.transactionArray[0].client?.client_occupation[0]?.pivot?.monthly_income ? this.computedApproveClientDetails.transactionArray[0].client?.client_occupation[0]?.pivot?.monthly_income.toString() : ""}}</span>
                                        </div>      
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="beneficiary-information mt-4">
                            <span class="fs-2 fw-bold">Beneficiary Information</span>
                            <hr>
                            <div class="d-flex flex-row">
                                <img :src="'/api/get-beneficiary-approve-image?data[beneficiaryApproveImage]='+computedApproveClientDetails.transactionArray[0]?.beneficiary_transaction[0]?.image[0]?.file_name" ></img>
                                <div class="d-flex flex-column justify-content-center ms-2">
                                    <span class="fs-5 fw-bold">{{(this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.first_name ?? "")+" "+(this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.middle_name ? this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0].middle_name : "")+" "+(this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.last_name ?? "")+" "+(this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.suffix?.suffix ? this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.suffix?.suffix : "")}}</span>
                                    <span class="fs-5 fw-bold underline text-secondary"><u>{{(this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.contact_number[0].contact_number.slice(0,4).concat("-") ?? "")+(this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.contact_number[0].contact_number.slice(4,8).concat("-") ?? "")+(this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.contact_number[0].contact_number.slice(8,11) ?? "")}}</u></span>
                                </div>
                            </div>
                            <div class="clientDetails row mt-3">
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">First Name:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.first_name ?? ""}}</span>
                                        </div>     
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">Middle Name:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.middle_name ? this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.middle_name : ""}}</span>
                                        </div>     
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">Last Name:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.last_name ?? ""}}</span>
                                        </div>  
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">Suffix:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.suffix?.suffix ? this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.suffix?.suffix : ""}}</span>
                                        </div>  
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">Birthdate:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.birthdate ?? ""}}</span>
                                        </div>  
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">Age:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.age ?? ""}}</span>
                                        </div>  
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">Sex:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.sex?.sex ?? ""}}</span>
                                        </div>   <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">Civil Status:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.civil_status?.civil_status ?? ""}}</span>
                                        </div>  
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">City:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.city ?? ""}}</span>
                                        </div>  
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">Barangay:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.barangay ?? ""}}</span>
                                        </div>  
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">Street/Purok:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.street ?? ""}}</span>
                                        </div>  
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">ID Type:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{ this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.beneficiary_identification[0]?.identification_type ? this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.beneficiary_identification[0]?.identification_type?.id_type : this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.beneficiary_identification[0]?.other_identification_type?.other_id_type}}</span>
                                        </div>  
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">ID Number:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{ this.computedApproveClientDetails.transactionArray[0]?.beneficiary_transaction[0]?.beneficiary_identification[0]?.id_number }}</span>
                                        </div>  
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">Precint:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{ this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.precint?.precint }}</span>
                                        </div>  
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">Occupation:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{ this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.beneficiary_occupation?.[0]?.occupation }}</span>
                                        </div>  
                                        <div class="col-4">
                                            <label class="fs-6 text-secondary fw-bold">M. Income:</label>
                                        </div>
                                        <div class="col-8">
                                            <span class="fs-6 ms-1 fw-bold">{{ this.computedApproveClientDetails.transactionArray[0].beneficiary_transaction[0]?.beneficiary_occupation?.[0]?.pivot?.monthly_income.toString() }}</span>
                                        </div>      
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="beneficiary-information">
                            <label class="fs-2 fw-bold ">Assistance Details</label>
                            <div>
                                <label class="fs-6 text-secondary fw-bold">Transaction ID:</label>
                                <span class="fs-6 ms-1 fw-bold">{{ this.computedApproveClientDetails.transactionArray[0].transaction_id }}</span>
                            </div>           
                            <hr>
                                <div class="row mt-2">
                                    <div class="col-12 d-flex flex-row align-items-center">
                                        <i style="width:50px; height:50px" :class="{ 
                                        'fa-solid fa-2xl fa-exclamation text-white d-flex align-items-center circle-background bg-secondary':this.computedApproveClientDetails.transactionArray[0].transaction_approve.transaction_approve_status_condition[0].status === 'TO APPROVE' ? true : false,
                                        'fa-solid fa-2xl fa-check text-white d-flex align-items-center circle-background bg-success':this.computedApproveClientDetails.transactionArray[0].transaction_approve.transaction_approve_status_condition[0].status === 'APPROVED' ? true : false,
                                        'fa-solid fa-2xl fa-times text-white d-flex align-items-center circle-background bg-danger':this.computedApproveClientDetails.transactionArray[0].transaction_approve.transaction_approve_status_condition[0].status === 'DECLINED' ? true : false,
                                        }">
                                    </i>
                                        <label class="fs-3 mx-2 fw-bold">{{this.computedApproveClientDetails.transactionArray[0].transaction_approve.transaction_approve_status_condition[0].status}}</label>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-5">
                                            <label class="fs-6 text-secondary fw-bold">Date approved:</label>
                                    </div>
                                    <div class="col-7">
                                            <span class="fs-6 ms-1 fw-bold">{{ this.computedApproveClientDetails.transactionArray[0].transaction_approve.transaction_approve_status_condition[0].pivot.status_condition_date}}</span>
                                    </div>
                                    <div class="col-5">
                                            <label class="fs-6 text-secondary fw-bold">Agency:</label>
                                    </div>
                                    <div class="col-7">
                                            <span class="fs-6 ms-1 fw-bold">{{this.computedApproveClientDetails.transactionArray[0].agency.agency_abbreviation}}</span>
                                    </div>
                                    <div class="col-5">
                                            <label class="fs-6 text-secondary fw-bold">Type:</label>
                                    </div>
                                    <div class="col-7">
                                            <span class="fs-6 ms-1 fw-bold">{{ this.computedApproveClientDetails.transactionArray[0].assistance_type.assistance_type }}</span>
                                    </div>
                                    <div class="col-5">
                                            <label class="fs-6 text-secondary fw-bold">Description:</label>
                                    </div>
                                    <div class="col-7">
                                            <span class="fs-6 ms-1 fw-bold">{{ this.computedApproveClientDetails.transactionArray[0].assistance_description.assistance_description }}</span>
                                    </div> 
                                    <div class="col-5">
                                            <label class="fs-6 text-secondary fw-bold">Category:</label>
                                    </div>
                                    <div class="col-7">
                                            <span class="fs-6 ms-1 fw-bold">{{ this.computedApproveClientDetails.transactionArray[0].assistance_category.assistance_category }}</span>
                                    </div> 
                                    <div class="col-5">
                                            <label class="fs-6 text-secondary fw-bold">Remarks:</label>
                                    </div>
                                    <div class="col-7">
                                            <span class="fs-6 ms-1 fw-bold">{{this.computedApproveClientDetails.transactionArray[0].transaction_approve.transaction_approve_status_condition[0]?.pivot?.remarks}}</span>
                                    </div>
                                    <div class="col-5">
                                            <label class="fs-6 text-secondary fw-bold">Amount:</label>
                                    </div>
                                    <div class="col-7">
                                            <span class="fs-6 ms-1 fw-bold">{{this.computedApproveClientDetails.transactionArray[0].transaction_approve?.transaction_approve_amount?.amount.toString()}}</span>
                                    </div>   
                                    <div v-if="this.computedApproveClientDetails.transactionArray[0].transaction_approve.transaction_approve_status_condition[0].status === 'APPROVED' && (this.computedFetchAuthUserRolesPermissions.user_permissions.some(value => value.name === 'editApproveAssistance') || this.computedFetchAuthUserRolesPermissions.user_roles.some(value => value === 'superAdmin'))" class="col-12 mt-3">
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#transactionEditApproveModal" class="btn btn-success bg-gradient w-100 h-100">EDIT APPROVE</button>
                                    </div>  
                                    <div v-if="this.computedApproveClientDetails.transactionArray[0].transaction_approve.transaction_approve_status_condition[0].status === 'TO APPROVE' || this.computedApproveClientDetails.transactionArray[0].transaction_approve.transaction_approve_status_condition[0].status === 'DECLINED' && (this.computedFetchAuthUserRolesPermissions.user_permissions.some(value => value.name === 'approveAssistance') || this.computedFetchAuthUserRolesPermissions.user_roles.some(value => value === 'superAdmin'))" class="col-12 mt-3">
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#transactionApproveModal" class="btn btn-success bg-gradient w-100 h-100">APPROVE</button>
                                    </div>
                                    <div v-if="this.computedApproveClientDetails.transactionArray[0].transaction_approve.transaction_approve_status_condition[0].status != 'DECLINED' && (this.computedFetchAuthUserRolesPermissions.user_permissions.some(value => value.name === 'declineAssistance') || this.computedFetchAuthUserRolesPermissions.user_roles.some(value => value === 'superAdmin'))" class="col-12 mt-3">
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#transactionDeclineModal" class="btn btn-danger bg-gradient w-100 h-100">DECLINE</button>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>

<div v-if="!this.computedApproveClientDetailsLoader">

         <!-- Edit Approve Modal -->
         <div v-if="this.computedApproveClientDetails.transactionArray[0].transaction_approve.transaction_approve_status_condition[0].status === 'APPROVED'" class="modal fade" id="transactionEditApproveModal" ref="transactionEditApproveModal" tabindex="-1" aria-labelledby="transactionEditApproveModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editApproveModalLabel">Are you sure to update this approved transaction?</h5>
                <button type="button" id="btn-close-edit-approve" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="container py-4">
                    <div>
                        <i class="fa-solid fa-calendar fa-lg icon"></i>
                        <input id="declineDate"  :class="{ 'form-control': true, 'input-field': true, 'is-invalid':this.proceedValidation.date_approve_decline }" v-model="this.computedApproveDeclineClient.date_approve_decline" type="date" placeholder="Decline Date">
                    </div>
                    <div class="mt-3">
                        <i class="fa-solid fa-money-bill fa-lg icon"></i>
                        <input id="approveAmount"  :class="{ 'form-control': true, 'input-field': true, 'is-invalid':this.proceedValidation.amount }" v-model="this.computedApproveDeclineClient.amount" type="text" placeholder="Amount">
                    </div>
                    <div class="mt-3">
                        <i class="fa-solid fa-note-sticky fa-lg icon"></i>
                        <input  id="declinedRemarks" :class="{ 'form-control': true, 'input-field': true }" v-model="this.computedApproveDeclineClient.remarks" type="text" placeholder="Remarks">
                    </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button type="button" @click="this.editApproveClient()" class="btn btn-success">Yes</button>
            </div>
            </div>
        </div>
        </div>

        <!-- Decline modal -->
        <div v-if="this.computedApproveClientDetails.transactionArray[0].transaction_approve.transaction_approve_status_condition[0].status === 'APPROVED' || this.computedApproveClientDetails.transactionArray[0].transaction_approve.transaction_approve_status_condition[0].status === 'TO APPROVE'" class="modal fade" id="transactionDeclineModal" ref="transactionDeclineModal" tabindex="-1" aria-labelledby="transactionDeclineModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="declineModalLabel">Are you sure to decline this transaction?</h5>
                <button type="button" id="btn-close-decline" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="container py-4">
                    <div>
                        <i class="fa-solid fa-calendar fa-lg icon"></i>
                        <input id="declineDate"  :class="{ 'form-control': true, 'input-field': true, 'is-invalid':this.proceedValidation.date_approve_decline }" v-model="this.computedApproveDeclineClient.date_approve_decline" type="date" placeholder="Decline Date">
                    </div>
                    <div class="mt-3">
                        <i class="fa-solid fa-note-sticky fa-lg icon"></i>
                        <input  id="declinedRemarks" :class="{ 'form-control': true, 'input-field': true }" v-model="this.computedApproveDeclineClient.remarks" type="text" placeholder="Remarks">
                    </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button type="button" @click="declineClient()" class="btn btn-success">Yes</button>
            </div>
            </div>
        </div>
        </div>

        <!-- Approve modal -->
        <div v-if="this.computedApproveClientDetails.transactionArray[0].transaction_approve.transaction_approve_status_condition[0].status === 'DECLINED' || this.computedApproveClientDetails.transactionArray[0].transaction_approve.transaction_approve_status_condition[0].status === 'TO APPROVE'" class="modal fade" id="transactionApproveModal" ref="transactionApproveModal" tabindex="-1" aria-labelledby="transactionApproveModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">Are you sure to approve this transaction?</h5>
                <button type="button" id="btn-close-approve" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="container py-4">
                <div>
                    <i class="fa-solid fa-calendar fa-lg icon"></i>
                    <input id="approveDate"  :class="{ 'form-control': true, 'input-field': true, 'is-invalid':this.proceedValidation.date_approve_decline }" v-model="this.computedApproveDeclineClient.date_approve_decline" type="date" placeholder="Approve Date">
                </div>
                <div class="mt-3">
                    <i class="fa-solid fa-money-bill fa-lg icon"></i>
                    <input id="approveAmount"  :class="{ 'form-control': true, 'input-field': true, 'is-invalid':this.proceedValidation.amount }" v-model="this.computedApproveDeclineClient.amount" type="text" placeholder="Amount">
                </div>
                <div class="mt-3">
                    <i class="fa-solid fa-note-sticky fa-lg icon"></i>
                    <input  id="approveRemarks" :class="{ 'form-control': true, 'input-field': true }" v-model="this.computedApproveDeclineClient.remarks" type="text" placeholder="Remarks">
                </div>
                
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button type="button" @click="approveClient()" class="btn btn-success">Yes</button>
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

.circle-background {
    border-radius: 100%; /* Makes the background a circle */
    padding: 10px; /* Adjust this for the desired size */
    color: white; /* Ensures the icon is visible against the background */
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width:50px;
    height: 50px;
}

.client-information , .beneficiary-information{
    border-radius: 10px;
    padding: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5); 
}
.approve-client{
    padding-left: 250px;
    overflow-y: scroll;
        
    height: -webkit-calc(100% - 50px);
    height: -moz-calc(100% - 50px);
    height: calc(100% - 50px);
    /* display: flex;
    flex-direction: column;   
    overflow: scroll;  */
}

img {
 height: 100px;
 width: 100px;
 border-radius: 100px;
 object-fit: fill;
}

button{
    border-radius: 25px;
}
</style>
<script>


import LoaderComponent from '../Loader2.vue';
import Paginator from 'primevue/paginator';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import auth from '../../../router/auth_roles_permissions.js'

export default{
        components:{
            LoaderComponent,
            Paginator,
            Toast
        },
        beforeCreate(){
            
        },
        beforeMount(){
     

            this.toast = useToast();
            this.computedHeaderText = 'Approval of Assistance > Approve Client';
            this.computedApproveClientDetailsLoader = true;
       

            this.$store.dispatch('fetchApproveClientDetails', { transactionID: this.$route.params.id}).then(response1 => {
                this.computedApproveDeclineClient.date_approve_decline = this.computedApproveClientDetails.dateToday;
                // this.$store.dispatch('fetchClientApproveImage',{'clientApproveImage':response1.client?.image?.[0]?.file_name}).then(response2 =>{
                //     console.log(response);
                // });
                for (const [key, value] of Object.entries(this.computedApproveDeclineClient)) {
                    this.proceedValidation[key] = false;
                }
                this.computedApproveClientDetailsLoader = false;
          
             
            });
 
        
        },
        mounted(){
            document.body.style.overflow = 'hidden';
            window.scrollTo({
            top: 0,
            behavior: 'instant'
            });

        },
        beforeUnmount(){ 
            document.body.style.overflow = 'auto';
        },
        methods:{
            editApproveClient(){
                if(typeof this.computedApproveClientDetails.amount === 'number' ||this.computedApproveDeclineClient.amount === '' || !this.computedApproveDeclineClient.amount || !(/^\d+(\.\d{1,2})?$/.test(this.computedApproveDeclineClient.amount))){
                    this.proceedValidation.amount = true;

                }else{
                    this.proceedValidation.amount = false;
                }
                
                if(typeof this.computedApproveDeclineClient.date_approve_decline === 'string' && this.computedApproveDeclineClient.date_approve_decline === ''){
                    this.proceedValidation.date_approve_decline = true;
                }else{
                    this.proceedValidation.date_approve_decline  = false;
                }

                let proc = false;
                for(const [key,value] of Object.entries(this.proceedValidation)){
                    if(value){
                        proc = false;
                        break;
                    }else{
                        proc = true;
                    }
                }

                if(proc){
                    document.getElementById('btn-close-edit-approve').click();
                    this.computedApproveClientDetailsLoader = true;
                    this.$store.dispatch('updateApprovedClient',{'id':this.$route.params.id,'amount':this.computedApproveDeclineClient.amount,'remarks':this.computedApproveDeclineClient.remarks,'date_approve_decline':this.computedApproveDeclineClient.date_approve_decline}).then(response1 => {
                        this.$store.dispatch('fetchApproveClientDetails', { transactionID: this.$route.params.id}).then(response2 => {
                                this.computedApproveClientDetailsLoader = false;
                                for (const [key, value] of Object.entries(this.proceedValidation)) {
                                    this.proceedValidation[key] = false;
                                }

                                for (const [key, value] of Object.entries(this.computedApproveDeclineClient)) {
                                    if(key != 'date_approve_decline'){
                                        this.computedApproveDeclineClient[key] = '';
                                    }
                                }

                                this.computedApproveDeclineClient.date_approve_decline = this.computedApproveClientDetails.dateToday;
                                // this.computedApproveDeclineClient.amount = this.computedApproveClientDetails.transactionArray?.[0]?.transaction_approve?.transaction_approve_amount?.amount;
                                // this.computedApproveDeclineClient.remarks = this.computedApproveClientDetails.transactionArray?.[0]?.transaction_approve?.transaction_approve_status_condition?.[0]?.pivot?.remarks;
                                this.computedApproveTransactionToast = true;
                                if(this.computedApproveTransactionToast){
           
                                    this.toast.add({ severity: 'success', summary: 'Success', detail: 'Approved transaction successfully updated.', life: 5000 });
                                    this.computedApproveTransactionToast = false
                                }
                        
                        });
                    });
                }

            },
            approveClient(){

                if(this.computedApproveDeclineClient.amount === '' || this.computedApproveDeclineClient.amount === null || !(/^\d+(\.\d{1,2})?$/.test(this.computedApproveDeclineClient.amount))){
                    this.proceedValidation.amount = true;

                }else{
                    this.proceedValidation.amount = false;
                }
                
                if(this.computedApproveDeclineClient.date_approve_decline === '' || this.computedApproveDeclineClient.date_approve_decline === null){
                    this.proceedValidation.date_approve_decline = true;
                }else{
                    this.proceedValidation.date_approve_decline  = false;
                }

                let proc = true;

                for (const [key, value] of Object.entries(this.proceedValidation)) {

                    if (value) {
                        proc = false;
                        break;
                    }else{
                        proc = true;
                    }
                }
  
                if(proc){
                    this.$store.dispatch('approveClient',{'id': this.$route.params.id,'amount':this.computedApproveDeclineClient.amount,'remarks':this.computedApproveDeclineClient.remarks, 'date_approve_decline':this.computedApproveDeclineClient.date_approve_decline}).then(response => {
                            document.getElementById('btn-close-approve').click();
                            this.computedApproveClientDetailsLoader = true;
                            this.$store.dispatch('fetchApproveClientDetails', { transactionID: this.$route.params.id}).then(response => {
                                this.computedApproveClientDetailsLoader = false;
                                for (const [key, value] of Object.entries(this.proceedValidation)) {
                                    this.proceedValidation[key] = false;
                                }

                                for (const [key, value] of Object.entries(this.computedApproveDeclineClient)) {
                                    if(key != 'date_approve_decline'){
                                        this.computedApproveDeclineClient[key] = '';
                                    }
                                }

                                this.computedApproveTransactionToast = true;
                                if(this.computedApproveTransactionToast){
           
                                    this.toast.add({ severity: 'success', summary: 'Success', detail: 'Transaction successfully approved.', life: 5000 });
                                    this.computedApproveTransactionToast = false
                                }
                            });
                    });
                }
                
            },
            declineClient(){  
                    if(this.computedApproveDeclineClient.date_approve_decline === '' || this.computedApproveDeclineClient.date_approve_decline === null){
                        this.proceedValidation.date_approve_decline = true;
                    }else{
                        this.proceedValidation.date_approve_decline = false;
                    }

                    if(this.proceedValidation.date_approve_decline === false){
                        this.$store.dispatch('declineClient',{'id': this.$route.params.id,'remarks':this.computedApproveDeclineClient.remarks, 'date_approve_decline':this.computedApproveDeclineClient.date_approve_decline}).then(response => {
                            document.getElementById('btn-close-decline').click();
                            this.computedApproveClientDetailsLoader = true;
                            this.$store.dispatch('fetchApproveClientDetails', { transactionID: this.$route.params.id}).then(response => {
                                this.computedApproveClientDetailsLoader = false;
                                for (const [key, value] of Object.entries(this.proceedValidation)) {
                                    this.proceedValidation[key] = false;
                                }
                                
                                for (const [key, value] of Object.entries(this.computedApproveDeclineClient)) { 
                                    if(key != 'date_approve_decline'){
                                        this.computedApproveDeclineClient[key] = '';
                                    }
                                }

                                this.computedApproveTransactionToast = true;
                                if(this.computedApproveTransactionToast){
                                    this.toast.add({ severity: 'success', summary: 'Success', detail: 'Transaction successfully declined.', life: 5000 });
                                    this.computedApproveTransactionToast = false
                                }
                            });
                        });
                    }
                    
            }
        },
        computed:{
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
            computedApproveTransactionToast:{
                get(){
                    return this.$store.state.approveTransactionToast;
                },
                set(value){
                    this.$store.commit('setApproveTransactionToast',{'approveTransactionToast':value});
                }
            },
            computedApproveClientDetailsLoader:{
                get(){
                    return this.$store.state.approveClientDetailsLoader;
                },
                set(value){
                    this.$store.commit('setApproveClientLoader',{'approveClientDetailsLoader':value});
                }
            },
            computedApproveClientDetails:{
                get(){
                    return this.$store.state.approveClientDetails;
                }
            },
            computedHeaderText:{
                get(){
                    return this.$store.state.headerText;
                },
                set(value){
                    this.$store.commit('setHeaderText',{headerText:value});
                }, 
            },
            computedApproveDeclineClient:{
                get(){
                    return this.$store.state.approveDeclineClient;
                },
                set(value){
                    this.$store.commit('setApproveDeclineClient',{approveDeclineClient:value});
                }
            }
        },
        data(){
            return {
                toast:false,
                proceedValidation:{
                    amount:false,
                    date_approve_decline: false,
                },
                clientImageURL: '',
            };
        },
        beforeRouteLeave(to, from, next) {
            next(true);
        },
        beforeRouteEnter(to,from,next){
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