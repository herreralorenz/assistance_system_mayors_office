<template>
    <Loader2 v-if="this.computedClientInformationTableLoader"></Loader2>
    <div id="client-information" class="form-group client-information py-2" v-if="!this.computedClientInformationTableLoader">
        <div class="container-fluid">
            <section class="searchbar">
                <div class="row">
                    <div class="col-1 d-flex flex-column ">
                        <span class="text-center text-secondary">Total:</span>
                        <span class="text-center text-success">{{ this.computedClientInformationTable.clientCount }}</span>
                    </div>
                    <div class="col-11" style="position:relative">
                        <i class="fa-solid fa-user fa-lg icon"></i>
                        <input ref="clientBeneficiary" v-model="this.computedClientSearch" id="clientBeneficiary" @keyup="this.clientSearch(this.computedClientSearch)"
                            :class="{ 'form-control': true, 'input-field': true }" type="text" placeholder="Client">
                    </div>
                </div>
            </section>
            <section class="client-container">
                <LoaderSearch v-if="this.computedSearchLoader"></LoaderSearch>
                <div v-if="!this.computedSearchLoader" class="row" v-for="(option,index) in this.computedClientInformationTable.client[paginator]">
                    <div class="col-12 my-2">
                        <div class="client-details">
                            <div class="d-flex flex-column">
                                <span class="h3 fw-bold text-success">{{ option.first_name+" "+option.middle_name+" "+option.last_name+" "+(option?.suffix?.suffix ? option?.suffix?.suffix : "") }}</span>
                            </div>
                            <div class="vl mt- ps-2 d-flex flex-column">
                                <span class="text-secondary">Birthdate: <span
                                        class="text-secondary fw-bold">{{option.birthdate}}</span></span>
                                <span class="text-secondary">Address: 
                                    <span class="text-secondary fw-bold">{{ (option.street ?? "")+" "+option.barangay+" "+option.city+" "+option.province }}</span></span>
                            </div>
                            <hr>
                            <div class="d-flex flex-column">
                                <div class="row">
                                    <div class="col-6 d-flex flex-column">
                                        <span class="text-secondary">Transactions made: <span
                                                class="text-secondary fw-bold">{{ option.transaction_count }}</span></span>
                                        <span class=" text-secondary">Last transaction: <span
                                                class="text-secondary fw-bold">{{ option.transaction[0]?.date_request }}</span></span>
                                    </div>
                                    <div class="col-6 d-flex align-items-end justify-content-center flex-column">
                                        <button type="button" class="btn btn-success bg-gradient w-50 h-75"
                                            @click="client(option.id)">VIEW</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <Paginator @page="paginatorChange"  :rows="5" :totalRecords="this.computedClientInformationTable.clientCount"></Paginator>
                </div>
            </section>
        </div>
    </div>
</template>

<style scoped>
button{
    border-radius: 25px;
}

.vl {
    border-left: 5px solid #E8E8E8;
    height: 45px;
}

.client-details {
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    height: 100%;
    width: 100%;
    box-sizing: border-box;
    padding: 10px;
    transition: transform 0.3s ease;
}


.client-details:hover {
    transform: scale(1.012);
}

.client-information {
    margin: 0;
    padding-left: 250px;
    overflow-y: hidden;
    height: -webkit-calc(100%);
    height: -moz-calc(100%);
    height: calc(100%);
    /* min-height: 100%;
    display: flex;
    flex-direction: column; */
}

.icon {
    padding: 10px;
    margin-top: 15px;
    min-width: 40px;
    position: absolute;
}

.client-container {
    padding: 10px;
    height: 85vh;
    overflow-x: hidden;
    overflow-y: auto;
}


.input-field {
    padding-left: 35px;
    border-radius: 25px;
    height: 50px;
}

/* ::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
        border-radius: 10px;
        background-color: #F5F5F5;
    } */

/* ::-webkit-scrollbar {
        width: 12px;
        background-color: #ffffff;
    }
    
    ::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
        background-color: #2F8D46;
    } */
</style>
<script>

import Loader2 from '../Loader2.vue';
import LoaderSearch from '../LoaderSearch.vue'
import Paginator from 'primevue/paginator';
import auth from '../../../router/auth_roles_permissions.js';

export default {
    components:{
        Loader2,
        LoaderSearch,
        Paginator
    },
    beforeMount() {
        
        this.computedHeaderText = 'Client';
        this.computedClientInformationTableLoader = true;
        this.$store.dispatch('fetchClientDetailsTable').then(response =>{
            this.computedClientInformationTableLoader = false;
            this.$store.commit('setClientInformationTable',{'clientInformationTable':response.data});
        });
    },
    mounted() {


        
        window.scrollTo({
            top: 0,
            behavior: 'instant' // Optional: adds smooth scrolling effect
        });


    },
    beforeUnmount() {
        document.body.style.overflow = 'scroll';
        this.computedClientSearch = '';
    },
    methods: {
        paginatorChange(event){
            this.paginator = event.page;
            const approveClients = document.getElementById("client-information");

            approveClients.scrollTo({
            top: 0,
            behavior: 'instant' // Enables smooth scrolling
            });
        },
        clientSearch(clientSearch){
            clearTimeout(this.timeout);

            this.timeout = setTimeout(() => {
                this.computedSearchLoader = true;
                this.$store.dispatch('searchClient', { client_fullname: clientSearch}).then(response => {
                    this.computedSearchLoader = false;
                    
                });

            }, 450);
        },
        client(clientID) {
            this.$router.push({ name: 'clientDetails',params:{'id':clientID} });
        }
    },
    computed: {
        computedSearchLoader:{
            get(){
                return this.$store.state.searchLoader;
            },
            set(value){
                this.$store.commit('setSearchLoader',{'searchLoader':value});
            }
        },
        computedClientInformationTableLoader:{
            get(){
                return this.$store.state.clientInformationTableLoader;
            },
            set(value){
                this.$store.commit('setClientInformationTableLoader',{'clientInformationTableLoader':value});
            }
        },
        computedClientSearch:{
            get(){
                return this.$store.state.clientSearch;
            },
            set(value){
                this.$store.commit('setClientSearch',{'clientSearch':value});
            }
        },
        computedClientInformationTable:{
            get(){
                return this.$store.state.clientInformationTable;
            },
            set(value){
                this.$store.commit('setClientInformationTable',{'clientInformationTable':value});
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
            timeout:null,
            paginator:0,
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