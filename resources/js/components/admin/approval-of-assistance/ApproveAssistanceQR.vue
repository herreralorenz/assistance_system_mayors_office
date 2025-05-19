<template>
    <Loader2 v-if="this.computedApproveAssistanceQRDetailsLoader && this.computedAuthCheckUserRolesPermissionsLoader"></Loader2>
    <Toast />
    <Dialog modal  v-model:visible="dialogVisible" header="⚠️Transaction Found⚠️">
        <div class="position-relative">
                    <h4>Are you sure to approve this transaction?</h4>
                    <div class="fw-bold mt-3"> Transaction ID: {{ this.scannedCode }}</div>
                    <div class="fw-bold mt-3 "> Status: <span class="bg-secondary text-white" style="border-radius: 25px; padding:3px"> {{ this.computedApproveAssistanceQRDetails?.[0]?.transaction_approve.transaction_claim?.transaction_claim_status_condition?.[0]?.status ? this.computedApproveAssistanceQRDetails?.[0]?.transaction_approve?.transaction_claim?.transaction_claim_status_condition?.[0]?.status : this.computedApproveAssistanceQRDetails?.[0]?.transaction_approve?.transaction_approve_status_condition?.[0]?.status ?? "" }}</span> </div>
                    <div class="fw-bold mt-3">Client: {{ (this.computedApproveAssistanceQRDetails?.[0]?.client?.first_name ?? "")+" "+(this.computedApproveAssistanceQRDetails?.[0]?.client?.middle_name ?? "")+" "+(this.computedApproveAssistanceQRDetails?.[0]?.client?.last_name ?? "")+" "+(this.computedApproveAssistanceQRDetails?.[0]?.client?.suffix?.suffix ?? "") }}</div>
                    <div class="fw-bold">Beneficiary: {{ (this.computedApproveAssistanceQRDetails?.[0]?.beneficiary_transaction?.[0]?.first_name ?? "")+" "+(this.computedApproveAssistanceQRDetails?.[0]?.beneficiary_transaction?.[0]?.middle_name ?? "")+" "+(this.computedApproveAssistanceQRDetails?.[0]?.beneficiary_transaction?.[0]?.last_name ?? "")+" "+(this.computedApproveAssistanceQRDetails?.[0]?.beneficiary_transaction?.[0]?.suffix?.suffix ?? "") }}</div>
                    <div class="fw-bold mt-3">Assistance Agency: {{ this.computedApproveAssistanceQRDetails?.[0]?.agency?.agency_name ?? ""}}</div>
                    <div class="fw-bold">Assistance Program: {{ this.computedApproveAssistanceQRDetails?.[0]?.agency_program?.agency_program_name ?? ""}}</div>
                    <div class="fw-bold">Assistance Type: {{ this.computedApproveAssistanceQRDetails?.[0]?.assistance_type.assistance_type ?? ""}}</div>
                    <div class="fw-bold">Assistance Amount: ₱{{ this.computedApproveAssistanceQRDetails?.[0]?.transaction_approve?.transaction_approve_amount?.amount ?? ""}}</div>
                    <div class="container" v-if="this.computedApproveAssistanceQRDetails?.[0]?.transaction_approve?.transaction_approve_status_condition?.[0]?.id != 2">
             
                        <div>
                            <i class="fa-solid fa-calendar fa-lg icon"></i>
                            <input id="declineDate"  :class="{ 'form-control': true, 'input-field': true, 'is-invalid':this.proceedValidation?.date_approve_decline }" v-model="this.computedApproveDeclineClient.date_approve_decline" type="date" placeholder="Decline Date">
                        </div>
                        <div class="mt-3">
                            <i class="fa-solid fa-money-bill fa-lg icon"></i>
                            <input id="approveAmount"  :class="{ 'form-control': true, 'input-field': true, 'is-invalid':this.proceedValidation?.amount }" v-model="this.computedApproveDeclineClient.amount" type="text" placeholder="Amount">
                        </div>
                        <div class="mt-3">
                            <i class="fa-solid fa-note-sticky fa-lg icon"></i>
                            <input  id="declinedRemarks" :class="{ 'form-control': true, 'input-field': true }" v-model="this.computedApproveDeclineClient.remarks" type="text" placeholder="Remarks">
                        </div>
                        <div class="d-flex  justify-content-end mt-3">
                            <button class="btn bg-success text-white w-25 h-50" @click="this.approveClient()" type="button">Approve</button>
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
        Loader2,
        Dialog,
        Toast
    },
    beforeCreate(){ 

    },
    beforeMount(){

        this.toast = useToast();
        this.computedHeaderText = 'Claim Assistance > Approve Assistance QR';
        this.computedApproveAssistanceQRDetailsLoader = true;
        this.computedAuthCheckUserRolesPermissionsLoader = true;
        if (Array.isArray(this.computedFormSeeder) && this.computedFormSeeder.length <= 0) {
            this.$store.dispatch('fetchSeeders').then(response => {
                this.computedApproveAssistanceQRDetailsLoader = false;
                this.computedAuthCheckUserRolesPermissionsLoader = false;
                this.computedApproveDeclineClient.date_approve_decline = this.computedFetchSeeder.date_today;
            });
        }else{

            this.computedApproveAssistanceQRDetailsLoader = false;
            this.computedAuthCheckUserRolesPermissionsLoader = false;
        }
        

    },
    mounted(){
        document.body.style.overflow = 'hidden';
        this.toast = useToast();

        this.checkCamera();
    },
    methods:{

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
                    this.$store.dispatch('fetchAssistanceApproveQR',{'transactionID':this.scannedCode}).then(response => {
                        console.log(response);
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
                this.approveAssistanceQRDetailsLoader = true;
                this.$store.dispatch('approveAssistanceQR',{'transactionID': this.scannedCode,'amount':this.computedApproveDeclineClient.amount,'remarks':this.computedApproveDeclineClient.remarks, 'date_approve_decline':this.computedApproveDeclineClient.date_approve_decline}).then(response => {
                    if(response.status === 200 ){
                        this.approveAssistanceQRDetailsLoader = false;
                        this.dialogVisible = false;
                        this.toast.add({ severity: 'success', summary: 'Success', detail: 'Assistance successfully approved.', life: 5000 });
                    }
                });
            }

            }
    },
    computed:{
        computedFormSeeder: {
            get() {
                return this.$store.state.formSeeder;
            },
        },
        computedAuthCheckUserRolesPermissionsLoader:{
            get(){
                    return this.$store.state.authCheckUserRolesPermissionsLoader;
            },
            set(value){
                    this.$store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':value});
            }
        },
        computedApproveAssistanceQRDetails:{
            get(){
                return this.$store.state.approveAssistanceQRDetails;
            },
            set(value){
                this.$store.commit('setApproveAssistanceQRDetails',{'approveAssistanceQRDetails':value});
            }
        },
        computedFetchSeeder:{
            get(){
                return this.$store.state.formSeeder;
            }
        },
        computedApproveAssistanceQRDetailsLoader:{
            get(){
                return this.$store.state.approveAssistanceQRDetailsLoader;
            },
            set(value){
                this.$store.commit('setApproveAssistanceQRDetailsLoader',{'approveAssistanceQRDetailsLoader':value});
            }
        },
        computedApproveDeclineClient:{
            get(){
                return this.$store.state.approveDeclineClient;
            },
            set(value){
                this.$store.commit('setApproveDeclineClient',{'approveDeclineClient':value});
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
    
    },
    data(){
        return{
            proceedValidation:{
                amount:false,
                date_approve_decline: false,
            },
            toast:null,
            dialogVisible:false,
            proceedValidation:{
                amount:false,
                date_approve_decline: false,
            },
            errorMessage:null,
            hasCamera: false, 
            scannedCode:'',
            scan:true,
        }
    },
    beforeRouteLeave(to, from, next) {
        next(true);
    },
    beforeRouteEnter(to,from,next) {
        // this.computedProgressBarWidth = '25%';
        // this.computedProgressActive = 1; \
        console.log(to.path);
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