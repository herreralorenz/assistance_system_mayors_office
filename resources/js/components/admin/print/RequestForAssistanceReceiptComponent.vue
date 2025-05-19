<template>
 <Loader2 v-if="this.computedPrintDetailsLoader"></Loader2>
 <div class="form-group print" v-else-if="!this.computedPrintDetailsLoader">
    <img @load="this.onImageLoad" :src="'/storage/images/header.webp'" class="img-fluid"  style="padding: 5px;" />
    <div class="container-fluid">
        <br>
        <div class="row">
            <div class="col-6 d-flex flex-column justify-content-end">
                    <div class="w-100">
                        <b>TRANSACTION ID: {{ this.computedPrintDetails.transaction[0].transaction_id }}</b>
                    </div>
                    <div class="w-100">
                        <b>CLIENT PRECINT NUMBER: {{ this.computedPrintDetails.transaction[0].client?.precint?.precint }}</b>
                    </div>
                    <div class="w-100">
                        <b>BENEFICIARY PRECINT NUMBER: {{ this.computedPrintDetails.transaction[0]?.beneficiary_transaction?.[0]?.precint?.precint }}</b>
                    </div>
            </div>
            <div class="col-5 text-end">
                <QrcodeVue :value="this.computedPrintDetails.transaction[0].transaction_id" :size="125" level="H" />   

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
                <span class="border-bottom border-dark w-100">{{ (this.computedPrintDetails.transaction[0]?.beneficiary_transaction?.[0]?.first_name ?? "")+" "+(this.computedPrintDetails.transaction[0]?.beneficiary_transaction?.[0]?.middle_name ?? "")+" "+(this.computedPrintDetails.transaction[0]?.beneficiary_transaction?.[0]?.last_name ?? "")+" "+(this.computedPrintDetails.transaction[0]?.beneficiary_transaction?.[0]?.suffix?.suffix ?? "") }}</span>
            </div>
            <div class="col-12 d-flex">
                <span><b>ADDRESS: </b></span>
                <span class="border-bottom border-dark w-100">{{ (this.computedPrintDetails.transaction[0]?.beneficiary_transaction?.[0]?.street ?? "")+" "+(this.computedPrintDetails.transaction[0]?.beneficiary_transaction?.[0]?.barangay ?? "")+" "+ (this.computedPrintDetails.transaction[0]?.beneficiary_transaction?.[0]?.city ?? "")+" "+(this.computedPrintDetails.transaction[0]?.beneficiary_transaction?.[0]?.province ?? "")}}</span>
            </div>
            <div class="col-5 d-flex">
                <span><b>DATE&nbsp;OF&nbsp;BIRTH: </b></span>
                <span class="border-bottom border-dark w-100">{{ this.computedPrintDetails.transaction[0]?.beneficiary_transaction?.[0]?.birthdate }}</span>
            </div>
            <div class="col-1 d-flex">
                <span><b>AGE: </b></span>
                <span class="border-bottom border-dark w-100">{{this.computedPrintDetails.transaction[0]?.beneficiary_transaction?.[0]?.age}}</span>
            </div>
            <div class="col-2 d-flex">
                <span><b>SEX: </b></span>
                <span class="border-bottom border-dark w-100">{{ this.computedPrintDetails.transaction[0]?.beneficiary_transaction?.[0]?.sex?.sex }}</span>
            </div>
            <div class="col-4 d-flex">
                <span><b>CIVIL&nbsp;STATUS:</b></span>
                <span class="border-bottom border-dark w-100">{{ this.computedPrintDetails.transaction[0]?.beneficiary_transaction?.[0]?.civil_status?.civil_status }}</span>
            </div>
            <div class="col-6 d-flex">
                <span><b>OCCUPATION:</b></span>
                <span class="border-bottom border-dark w-100">{{this.computedPrintDetails.transaction[0]?.beneficiary_transaction?.[0]?.beneficiary_occupation?.[0]?.occupation}}</span>
            </div>
            <div class="col-6 d-flex">
                <span style="width:110px"><b>M. INCOME:</b></span>
                <span class="border-bottom border-dark w-100" >{{this.computedPrintDetails.transaction[0]?.beneficiary_transaction?.[0]?.beneficiary_occupation?.[0]?.pivot?.monthly_income}}</span>
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
                <span class="border-bottom border-dark w-100">{{ this.computedPrintDetails.transaction[0]?.client.first_name+" "+( this.computedPrintDetails.transaction[0]?.client?.middle_name ?? "" )+" "+ this.computedPrintDetails.transaction[0]?.client.last_name+" "+( this.computedPrintDetails.transaction[0]?.client?.suffix?.suffix ?? "") }}</span>
            </div>
            <div class="col-12 d-flex">
                <span><b>ADDRESS: </b></span>
                <span class="border-bottom border-dark w-100">{{  (this.computedPrintDetails.transaction[0]?.client.street ?? "")+" "+this.computedPrintDetails.transaction[0]?.client.barangay+" "+this.computedPrintDetails.transaction[0]?.client.city+" "+this.computedPrintDetails.transaction[0]?.client.province }}</span>
            </div>
            <div class="col-5 d-flex">
                <span><b>DATE&nbsp;OF&nbsp;BIRTH: </b></span>
                <span class="border-bottom border-dark w-100">{{ this.computedPrintDetails.transaction[0]?.client.birthdate }}</span>
            </div>
            <div class="col-1 d-flex">
                <span><b>AGE: </b></span>
                <span class="border-bottom border-dark w-100">{{ this.computedPrintDetails.transaction[0]?.client.age }}</span>
            </div>
            <div class="col-2 d-flex">
                <span><b>SEX: </b></span>
                <span class="border-bottom border-dark w-100">{{ this.computedPrintDetails.transaction[0]?.client.sex.sex }}</span>
            </div>
            <div class="col-4 d-flex">
                <span><b>CIVIL&nbsp;STATUS:</b></span>
                <span class="border-bottom border-dark w-100">{{ this.computedPrintDetails.transaction[0]?.client.civil_status.civil_status }}</span>
            </div>
            <div class="col-6 d-flex">
                <span><b>OCCUPATION:</b></span>
                <span class="border-bottom border-dark w-100">{{ this.computedPrintDetails.transaction[0]?.client?.client_occupation?.[0]?.occupation }}</span>
            </div>
            <div class="col-6 d-flex" >
                <span style="width: 110px;"><b>M. INCOME:</b></span>
                <span class="border-bottom border-dark w-100">{{ this.computedPrintDetails.transaction[0]?.client?.client_occupation?.[0]?.pivot?.monthly_income }}</span>
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
                <span style="width:180px"><b>ASSISTANCE TYPE:</b></span>
                <span class="border-bottom border-dark w-100" >{{this.computedPrintDetails.transaction[0].assistance_type.assistance_type}}</span>
            </div>
            <div class="col-12 d-flex">
                <span><b>NAME&nbsp;OF&nbsp;HOSPITAL:</b></span>
                <span class="border-bottom border-dark w-100">{{ (this.computedPrintDetails.transaction[0]?.hospital?.hospital_name ?? "") }}</span>
            </div>
            <div class="col-12 d-flex">
                <span><b>ASSISTANCE&nbsp;DESCRIPTION:</b></span>
                <span class="border-bottom border-dark w-100" >{{this.computedPrintDetails.transaction[0]?.assistance_description.assistance_description }}</span>
            </div>
        </div>
        <br>
        <br>
        <div class="row">
            <div class="col-4 d-flex">
                <span><b>AMOUNT:</b></span>
                <span class="border-bottom border-dark w-100" ></span>
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
@media print{
     @page {
        size: A4;
        margin: 20mm;
    }
    .print {
        width: 100%;
        height: 100%;
        overflow: hidden;
    }
}

</style>
<script>
import Loader2 from '../Loader2.vue'
import QrcodeVue from 'qrcode.vue';
import auth from '../../../router/auth_roles_permissions.js';
export default{
    components:{
        Loader2,
        QrcodeVue
    },
    beforeMount(){
        this.computedPrintDetailsLoader = true;
        this.$store.dispatch('fetchPrintDetails',{'id':this.$route.params.id}).then(response => {
            this.computedPrintDetailsLoader = false;
            console.log(this.computedPrintDetails);
        });
    },
    watch: {
        imageLoaded(newValue) {
            console.log("Image loaded:", newValue);
            if(!this.printDetailsLoader && !this.computedPrintDetailsLoader){
                this.$nextTick();
                window.print();
            }
    
        }
    },
    mounted(){
       
    },
    methods:{
        onImageLoad() {
            this.imageLoaded = true;
            console.log("Image loaded successfully");
        },
    },
    updated(){

    },
    computed:{
        computedPrintDetails:{
            get(){
                return this.$store.state.printDetails;
            }
        },
        computedPrintDetailsLoader:{
            get(){
                return this.$store.state.printDetailsLoader;
            },
            set(value){
                this.$store.commit('setPrintDetailsLoader',{'printDetailsLoader':value})
            }
        }
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