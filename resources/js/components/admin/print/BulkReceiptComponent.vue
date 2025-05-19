<template>
 <div class="form-group print">
    <img @load="onImageLoad" :src="'/storage/images/header.webp'" class="img-fluid img-template"  style="padding: 5px;" />
    <div class="container-fluid">
        <br>
        <div class="row">
            <div class="col-6 d-flex flex-column justify-content-end">
                    <div class="w-100">
                        <b>TRANSACTION ID: {{ this.reviewTransactionToPrint?.transaction_id}}</b>
                    </div>
                    <div class="w-100">
                        <b>CLIENT PRECINT NUMBER: {{ this.reviewTransactionToPrint?.client?.precint?.precint }}</b>
                    </div>
                    <div class="w-100">
                        <b>BENEFICIARY PRECINT NUMBER: {{this.reviewTransactionToPrint?.beneficiary_transaction?.[0]?.precint?.precint }}</b>
                    </div>
            </div>
            <div class="col-5 text-end">
                <QrcodeVue :value="this.reviewTransactionToPrint?.transaction_id" :size="125" level="H" />   

            </div>
        </div>
        <br>
        <br>
        <div class="row">
            <div class="col-12">
                <span><b><u>*BENEFICIARY'S INFORMATION</u></b></span>
            </div>
            <br>
            <br>
            <div class="col-12 d-flex">
                <span><b>FULL&nbsp;NAME:</b></span>
                <span class="border-bottom border-dark w-100">{{ (this.reviewTransactionToPrint?.beneficiary_transaction?.[0]?.first_name)+" "+( this.reviewTransactionToPrint?.beneficiary_transaction?.[0]?.middle_name ?? "")+" "+( this.reviewTransactionToPrint?.beneficiary_transaction?.[0]?.last_name)+" "+( this.reviewTransactionToPrint?.beneficiary_transaction?.[0]?.suffix?.suffix ?? "") }}</span>
            </div>
            <div class="col-12 d-flex">
                <span><b>ADDRESS: </b></span>
                <span class="border-bottom border-dark w-100">{{(this.reviewTransactionToPrint?.beneficiary_transaction?.[0]?.street)+" "+(this.reviewTransactionToPrint?.beneficiary_transaction?.[0]?.barangay)+" "+this.reviewTransactionToPrint?.beneficiary_transaction?.[0]?.city+" "+this.reviewTransactionToPrint?.beneficiary_transaction?.[0]?.province}}</span>
            </div>
            <div class="col-4 d-flex">
                <span><b>DATE&nbsp;OF&nbsp;BIRTH: </b></span>
                <span class="border-bottom border-dark w-100">{{this.reviewTransactionToPrint?.beneficiary_transaction?.[0]?.birthdate }}</span>
            </div>
            <div class="col-2 d-flex">
                <span><b>AGE: </b></span>
                <span class="border-bottom border-dark w-100">{{this.reviewTransactionToPrint?.beneficiary_transaction?.[0]?.age}}</span>
            </div>
            <div class="col-2 d-flex">
                <span><b>SEX: </b></span>
                <span class="border-bottom border-dark w-100">{{this.reviewTransactionToPrint?.beneficiary_transaction?.[0]?.sex?.sex }}</span>
            </div>
            <div class="col-4 d-flex">
                <span><b>CIVIL&nbsp;STATUS:</b></span>
                <span class="border-bottom border-dark w-100">{{ this.reviewTransactionToPrint?.beneficiary_transaction?.[0]?.civil_status?.civil_status }}</span>
            </div>
            <div class="col-6 d-flex">
                <span><b>OCCUPATION:</b></span>
                <span class="border-bottom border-dark w-100">{{ this.reviewTransactionToPrint?.beneficiary_transaction?.[0]?.beneficiary_occupation?.[0]?.occupation}}</span>
            </div>
            <div class="col-6 d-flex">
                <span style="width:100px"><b>M. INCOME:</b></span>
                <span class="border-bottom border-dark w-100" >{{this.reviewTransactionToPrint?.beneficiary_transaction?.[0]?.beneficiary_occupation?.[0]?.pivot?.monthly_income}}</span>
            </div>
        </div>
        <br>
        <br>
        <div class="row">
            <div class="col-12">
                <span><b><u>*CLIENT'S INFORMATION</u></b></span>
            </div>
            <br>
            <br>
            <div class="col-12 d-flex">
                <span><b>FULL&nbsp;NAME:</b></span>
                <span class="border-bottom border-dark w-100">{{ (this.reviewTransactionToPrint?.client.first_name)+" "+(this.reviewTransactionToPrint?.client?.middle_name ?? "")+" "+(this.reviewTransactionToPrint?.client?.last_name)+" "+(this.reviewTransactionToPrint?.client?.suffix?.suffix ?? "") }}</span>
            </div>
            <div class="col-12 d-flex">
                <span><b>ADDRESS: </b></span>
                <span class="border-bottom border-dark w-100">{{ (this.reviewTransactionToPrint?.client?.street ?? "")+" "+(this.reviewTransactionToPrint?.client?.barangay)+" "+(this.reviewTransactionToPrint?.client?.city)+" "+(this.reviewTransactionToPrint?.client?.province) }}</span>
            </div>
            <div class="col-4 d-flex">
                <span><b>DATE&nbsp;OF&nbsp;BIRTH: </b></span>
                <span class="border-bottom border-dark w-100">{{ this.reviewTransactionToPrint?.client?.birthdate }}</span>
            </div>
            <div class="col-2 d-flex">
                <span><b>AGE: </b></span>
                <span class="border-bottom border-dark w-100">{{ this.reviewTransactionToPrint?.client?.age }}</span>
            </div>
            <div class="col-2 d-flex">
                <span><b>SEX: </b></span>
                <span class="border-bottom border-dark w-100">{{ this.reviewTransactionToPrint?.client?.sex?.sex }}</span>
            </div>
            <div class="col-4 d-flex">
                <span><b>CIVIL&nbsp;STATUS:</b></span>
                <span class="border-bottom border-dark w-100">{{ this.reviewTransactionToPrint?.client?.civil_status?.civil_status }}</span>
            </div>
            <div class="col-6 d-flex">
                <span><b>OCCUPATION:</b></span>
                <span class="border-bottom border-dark w-100">{{ this.reviewTransactionToPrint?.client?.client_occupation?.[0]?.occupation }}</span>
            </div>
            <div class="col-6 d-flex" >
                <span style="width: 100px;"><b>M. INCOME:</b></span>
                <span class="border-bottom border-dark w-100">{{ this.reviewTransactionToPrint?.client?.client_occupation?.[0]?.pivot?.monthly_income }}</span>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-12">
                <span><b><u>*OTHER INFORMATION</u></b></span>
            </div>
            <br>
            <br>
            <div class="col-12 d-flex">
                <span style="width:155px"><b>ASSISTANCE TYPE:</b></span>
                <span class="border-bottom border-dark w-100" >{{ this.reviewTransactionToPrint?.assistance_type?.assistance_type }}</span>
            </div>
            <div class="col-12 d-flex">
                <span><b>NAME&nbsp;OF&nbsp;HOSPITAL:</b></span>
                <span class="border-bottom border-dark w-100">{{ this.reviewTransactionToPrint?.hospital?.hospital_name }}</span>
            </div>
            <div class="col-12 d-flex">
                <span><b>ASSISTANCE&nbsp;DESCRIPTION:</b></span>
                <span class="border-bottom border-dark w-100" >{{ this.reviewTransactionToPrint?.assistance_description?.assistance_description }}</span>
            </div>
        </div>
        <br>
        <br>
        <div class="row">
            <div class="col-4 d-flex">
                <span><b>AMOUNT:</b></span>
                <span class="border-bottom border-dark w-100" >{{ (this.reviewTransactionToPrint?.transaction_approve?.transaction_approve_amount?.amount ?? "") }}</span>
            </div>
        </div>
        <br>
        <br>
        <br>
        <div class="row">
            <div class="col-4">
                <div class="w-100">
                    <hr>
                </div>
                <div class="d-flex align-items-center justify-content-center">
                    <b>SIGNATURE OF APPLICANT</b>
                </div>
            </div>
        </div>
    </div>
 </div>
</template>
<style scoped>

</style>
<script>

import Loader2 from '../Loader2.vue'
import { nextTick } from "vue";
import QrcodeVue from 'qrcode.vue';
import auth from '../../../router/auth_roles_permissions.js';

export default{
    props:{
        reviewTransactionToPrint: Object,
    },
    components:{
        Loader2,
        QrcodeVue
    },
    beforeMount(){

    },
    watch: {
        imageLoaded(newValue) { 
            if (newValue) {
                this.$emit('counter',++this.computedReceiptCounter);
            }
        },
    },
    mounted(){ 
        console.log(this.reviewTransactionToPrint);
    },
    computed:{
        computedReceiptCounter:{
            get(){
                return this.$store.state.receiptCounter;
            },
            set(value){
                this.$store.commit('setReceiptCounter',{'receiptCounter':value});
            }
        }
    },
    methods:{
        onImageLoad() {
            this.imageLoaded = true;
            
        },
    },
    updated(){

    },
    data(){
        return{
            imageLoaded:false,
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