<template>
    <Loader2 v-if="this.computedClaimAssistanceQRDetailsLoader"></Loader2>
    <Toast />
    <Dialog modal  v-model:visible="dialogVisible" header="⚠️Transaction Found⚠️">
        <div class="position-relative">
                    <h4>Are you sure to claim this transaction?</h4>
                    <div class="fw-bold mt-3"> Transaction ID: {{ this.scannedCode }}</div>
                    <div class="fw-bold mt-3 "> Status: <span class="bg-secondary text-white" style="border-radius: 25px; padding:3px"> {{ this.computedClaimAssistanceQRDetails?.[0]?.transaction_approve?.transaction_claim?.transaction_claim_status_condition?.[0]?.status ? this.computedClaimAssistanceQRDetails?.[0]?.transaction_approve.transaction_claim?.transaction_claim_status_condition?.[0]?.status : this.computedClaimAssistanceQRDetails?.[0]?.transaction_approve?.transaction_approve_status_condition?.[0]?.status ?? ""}}</span> </div>
                    <div class="fw-bold mt-3">Client: {{ (this.computedClaimAssistanceQRDetails?.[0]?.client?.first_name ?? "")+" "+(this.computedClaimAssistanceQRDetails?.[0]?.client?.middle_name ?? "")+" "+(this.computedClaimAssistanceQRDetails?.[0]?.client.last_name ?? "")+" "+(this.computedClaimAssistanceQRDetails?.[0]?.client?.suffix?.suffix ?? "") }}</div>
                    <div class="fw-bold">Beneficiary: {{ (this.computedClaimAssistanceQRDetails?.[0]?.beneficiary_transaction?.[0]?.first_name)+" "+(this.computedClaimAssistanceQRDetails?.[0]?.beneficiary_transaction?.[0]?.middle_name ?? "")+" "+(this.computedClaimAssistanceQRDetails?.[0]?.beneficiary_transaction?.[0]?.last_name)+" "+(this.computedClaimAssistanceQRDetails?.[0]?.beneficiary_transaction?.[0]?.suffix?.suffix ?? "") }}</div>
                    <div class="fw-bold mt-3">Assistance Agency: {{ this.computedClaimAssistanceQRDetails?.[0]?.agency?.agency_name }}</div>
                    <div class="fw-bold">Assistance Program: {{ this.computedClaimAssistanceQRDetails?.[0]?.agency_program?.agency_program_name }}</div>
                    <div class="fw-bold">Assistance Type: {{ this.computedClaimAssistanceQRDetails?.[0]?.assistance_type.assistance_type }}</div>
                    <div class="fw-bold">Assistance Amount: ₱{{ this.computedClaimAssistanceQRDetails?.[0]?.transaction_approve?.transaction_approve_amount?.amount}}</div>
                    <div class="container" v-if="this.computedClaimAssistanceQRDetails?.[0]?.transaction_approve?.transaction_claim?.transaction_claim_status_condition?.[0]?.id != 3">
                        <div>
                            <i class="fa-solid fa-calendar fa-lg icon"></i>
                            <input id="claimDate" v-model="this.computedClaimant.claimDate"
                                :class="{ 'form-control': true, 'input-field': true, 'is-invalid': this.proceedValidation?.claimDate }"
                                type="date" placeholder="Claim Date">
                        </div>
                        <div class="mt-3">
                            <i class="fa-solid fa-user fa-lg icon"></i>
                            <input id="claimantFirstName" v-model="this.computedClaimant.first_name"
                                :class="{ 'form-control': true, 'input-field': true, 'is-invalid': this.proceedValidation?.claimantFirstName }"
                                type="text" placeholder="Claimant First Name">
                        </div>
                        <div class="mt-3">
                            <i class="fa-solid fa-user fa-lg icon"></i>
                            <input id="claimantMiddleName" v-model="this.computedClaimant.middle_name"
                                :class="{ 'form-control': true, 'input-field': true }" type="text"
                                placeholder="Claimant Middle Name">
                        </div>
                        <div class="mt-3">
                            <i class="fa-solid fa-user fa-lg icon"></i>
                            <input id="claimantLastName" v-model="this.computedClaimant.last_name"
                                :class="{ 'form-control': true, 'input-field': true, 'is-invalid': this.proceedValidation?.claimantLastName }"
                                type="text" placeholder="Claimant Last Name">
                        </div>
                        <div class="mt-3">
                            <i class="fa-solid fa-user fa-lg icon"></i>
                            <select v-model="this.computedClaimant.suffix" id="claimantSuffix"
                                :class="{ 'form-select': true }">
                                <option value="" disabled selected>Suffix</option>
                                <option value="" selected></option>
                                <option v-for="(option, index) in this.computedFetchSeeder.suffix" :key="option.id"
                                    :value="option.id">{{ option.suffix }}</option>
                            </select>
                        </div>
                        <div class="mt-3">
                            <i class="fa-solid fa-address-book fa-lg icon"></i>
                            <input id="claimantContactNumber" v-model="this.computedClaimant.contact_number"
                                :class="{ 'form-control': true, 'input-field': true, 'is-invalid': this.proceedValidation?.claimantContactNumber }"
                                type="text" placeholder="Claimant Contact Number">
                        </div>
                        <div class="mt-3">
                            <i class="fa-solid fa-note-sticky fa-lg icon"></i>
                            <input id="claimRemarks" v-model="this.computedClaimant.remarks"
                                :class="{ 'form-control': true, 'input-field': true }" type="text"
                                placeholder="Remarks">
                        </div>
                        <div class="d-flex  justify-content-end mt-3">
                            <button class="btn bg-success text-white w-25 h-50" @click="this.claimClient()" type="button">Claim</button>
                        </div>
                    </div>
             </div>

    </Dialog>
 
    <div class="claim-qr">
        <div class="container-fluid">
            <div class="d-flex flex-column align-items-center justify-content-center">
                <div  v-if="this.hasCamera === false">
                    <img  src="/storage/images/congonylogo.webp" class="mt-3 logo object-fit-contain">
                </div>
                <div v-if="this.hasCamera" class="qr-code-container mt-3 position-relative">
                    <qrcode-stream  v-if="this.hasCamera && this.scan" @detect="onDetect" @camera-on-error="onCameraError"   @init="onInit" ></qrcode-stream>
                </div>
                <span class="mt-3 text-white rounded-3 w-50 text-center  justify-content-center" style="background-color: #75ffc3;
background-image: linear-gradient(62deg, #75ffc3 0%, #ff4d4e 100%);" v-if="this.hasCamera">
                    <h1 class="fw-bold">QR CODE SCANNER</h1>
                </span>
                <!-- <button type="button" class="start mt-3 btn btn-success bg-gradient" @click="checkCamera">Start Scanner</button> -->
            </div>
            
        </div>
      
    </div>
</template>
<style scoped>

select {
    border-radius: 25px;
    padding-left: 35px;
    border-radius: 25px;
    height: 50px;
}
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
.claim-qr {
    margin: 0;
    padding-left: 250px;
    overflow-y: hidden;
    height: -webkit-calc(100% - 50px);
    height: -moz-calc(100% - 50px);
    height: calc(100% - 50px);
    /* min-height: 100%;
    display: flex;
    flex-direction: column;  */
}

.logo{
    position: relative;
    border-radius: 10px;
    padding: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    width: 500px;
    height: 500px;
    overflow: hidden; /* Prevents the border from overflowing */
}
.qr-code-container{
    position: relative;
    border-radius: 10px;
    padding: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    width: 500px;
    height: 500px;
    overflow: hidden; /* Prevents the border from overflowing */
    
}


/* 
.qr-code-container::before {
    content: "";
    position: absolute;
    top:-25%;
    left:-25%;
    height: 150%;
    width: 150%;
    background: conic-gradient(
        #008120 0deg,
 
        #f51818 360deg
    );

    mask: radial-gradient(transparent 230px, rgb(0, 0, 0) 250px); 
    -webkit-mask: radial-gradient(transparent 230px, black 250px);

    animation: spinborder 2s linear infinite;
}

@keyframes spinborder {
      0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
} */


</style>
<script>

import { QrcodeStream, QrcodeDropZone, QrcodeCapture } from 'vue-qrcode-reader';
import ConfirmDialog from 'primevue/confirmdialog';
import { useConfirm } from "primevue/useconfirm";
import Loader2 from "../Loader2.vue";
import Dialog from 'primevue/dialog';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import auth from '../../../router/auth_roles_permissions.js';


export default{
    components:{
        QrcodeStream,
        QrcodeDropZone,
        QrcodeCapture,
        ConfirmDialog,
        Loader2,
        Dialog,
        Toast
    },
    beforeCreate(){

    },
    beforeMount(){
    
        this.toast = useToast();
        this.computedHeaderText = 'Claim Assistance > Claim Assistance QR';
        this.computedClaimAssistanceQRDetailsLoader = true;
        this.$store.dispatch('fetchSeeders').then(response => {
            this.computedClaimAssistanceQRDetailsLoader = false;
            this.computedClaimant.claimDate = this.computedFetchSeeder.date_today;
        });

    },
    mounted(){
        document.body.style.overflow = 'hidden';
        this.confirm = useConfirm();
        this.checkCamera();
    },
    methods:{
        claimClient() {

            if (this.computedClaimant.claimDate === '' || this.computedClaimant.claimDate === null) {
                this.proceedValidation.claimDate = true;
            } else {
                this.proceedValidation.claimDate = false;
            }


            if (this.computedClaimant.first_name !== '' || this.computedClaimant.last_name !== '' || this.computedClaimant.contact_number) {

                if (this.computedClaimant.first_name === '' || this.computedClaimant.first_name === null) {
                    this.proceedValidation.claimantFirstName = true;
                } else {
                    this.proceedValidation.claimantFirstName = false;
                }

                if (this.computedClaimant.last_name === '' || this.computedClaimant.last_name === null) {
                    this.proceedValidation.claimantLastName = true;
                } else {
                    this.proceedValidation.claimantLastName = false;
                }

                if (this.computedClaimant.contact_number === '' || this.computedClaimant.contact_number === null) {
                    this.proceedValidation.claimantContactNumber = true;
                } else {
                    if (/^(\+?63|0)?\d{10}$/.test(this.computedClaimant.contact_number)) {
                        this.proceedValidation.claimantContactNumber = false;
                    } else {
                        this.proceedValidation.claimantContactNumber = true;
                    }
                }
            } else {
                for (const [key, value] of Object.entries(this.proceedValidation)) {
                    this.proceedValidation[key] = false;
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
                this.claimAssistanceQRDetailsLoader = true;
                this.$store.dispatch('claimAssistanceQR',{'transactionID':this.scannedCode,'claimant':this.computedClaimant}).then(response =>{

                    if(response.status === 200){
                        this.claimAssistanceQRDetailsLoader = false;
                        this.dialogVisible = false;
                        this.toast.add({ severity: 'success', summary: 'Success', detail: 'Assistance successfully claimed.', life: 5000 });
                    }
                });
            }

            },
        async checkCamera(){
            try {

                const devices = await navigator.mediaDevices.enumerateDevices();

                const videoDevices = devices.filter(device => device.kind === "videoinput");


                if (videoDevices.length > 0) {
                this.hasCamera = true;
                this.errorMessage = null;
                } else {
                this.errorMessage = "No camera detected.";
                }
            } catch (error) {
                console.error("Error checking camera:", error);
                this.errorMessage = "Unable to access the camera.";
            }
        },
        async onInit(promise) {
            console.log("Initializing camera...");
            try {
                await promise; // Wait for camera to initialize
                console.log("Camera initialized successfully.");
            } catch (error) {
                this.onCameraError(error);
            }
        },
        onDetect (detectedCodes) {
            // ...

            try{
                if(detectedCodes){
                    this.scannedCode = detectedCodes[0].rawValue;
                    this.$store.dispatch('fetchAssistanceClaimQR',{'transactionID':this.scannedCode}).then(response =>{

                        if(response.data.length > 0){
                            this.scan = false;
                            this.dialogVisible = true;
                        }else{
                            alert('This QR Code is invalid');
                        }
                     
                    });

                    setTimeout(()=>{
                        this.scan = true;
                    },2000)
            
                    
                    
                }
                
            }catch(error){
                console.log(error);
            }
        },
        onCameraError(error) {
            console.error("Camera error:", error);
            this.errorMessage = "No camera found or permission denied.";
        }
    },
    computed:{
        computedFetchSeeder:{
            get(){
                return this.$store.state.formSeeder;
            }
        },
        computedClaimAssistanceQRDetailsLoader:{
            get(){
                return this.$store.state.claimAssistanceQRDetailsLoader
            },
            set(value){
                this.$store.commit('setClaimAssistanceQRDetailsLoader',{'claimAssistanceQRDetailsLoader':value});
            }
        },
        computedClaimant:{
            get(){
                return this.$store.state.claimant;
            },
            set(value){
                this.$store.commit('setClaimant',{'claimant':value});
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
        computedClaimAssistanceQRDetails:{
            get(){
                return this.$store.state.claimAssistanceQRDetails;
            },
            set(value){
                this.$store.commit('setClaimAssistanceQRDetails',{'claimAssistanceQRDetails':value});
            }
        }
    },
    data(){
        return{
            toast:null,
            dialogVisible:false,
            proceedValidation: {
                toast:false,
                claimDate: false,
                claimantFirstName: false,
                claimantMiddleName: false,
                claimantLastName: false,
                claimantSuffix: false,
                claimantContactNumber: false,
            },
            errorMessage:null,
            hasCamera: false, 
            confirm:null,
            scannedCode:'',
            scan:true,
        }
    },
    beforeRouteEnter(to, from, next){
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