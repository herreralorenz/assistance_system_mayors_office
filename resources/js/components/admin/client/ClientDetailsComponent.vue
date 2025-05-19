<template>
    <Loader2 v-if="this.computedClientDetailsLoader"></Loader2>
    <Toast/>
    <div class="form-group approve-client py-4" v-if="!this.computedClientDetailsLoader">
        <div class="container-fluid">
            <section class="client-information">
                <div class="row">
                    <div class="col-12">
                        <label class="fs-2 fw-bold ">Client Information</label>
                        <hr>
                        <div class="d-flex flex-row">
                            <img :src="'/api/get-client-image?data[clientImage]='+this.computedClientDetails.client[0]?.image[0]?.file_name"></img>
                            <div class="d-flex flex-column justify-content-center ms-2">
                                <span class="fs-5 fw-bold">{{ this.computedClientDetails.client[0].first_name+" "+(this.computedClientDetails.client[0].middle_name ?? "")+" "+this.computedClientDetails.client[0].last_name+" "+(this.computedClientDetails.client[0]?.suffix?.suffix ?? "")}}</span>
                                <span class="fs-5 fw-bold underline text-secondary"><u>{{ this.computedClientDetails.client[0].contact_number[0].contact_number.slice(0,4)+"-"+this.computedClientDetails.client[0].contact_number[0].contact_number.slice(4,8)+"-"+this.computedClientDetails.client[0].contact_number[0].contact_number.slice(8,11)}}</u></span>
                            </div>
                        </div>
                        <div class="clientDetails row mt-3">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">First Name:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{ this.computedClientDetails.client[0].first_name}}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Middle Name:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{ this.computedClientDetails.client[0].middle_name ?? "" }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Last Name:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{ this.computedClientDetails.client[0].last_name }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Suffix:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{ this.computedClientDetails.client[0]?.suffix?.suffix ?? "" }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Birthdate:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{ this.computedClientDetails.client[0].birthdate }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Age:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{ this.computedClientDetails.client[0].age }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Sex:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{ this.computedClientDetails.client[0].sex.sex }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Civil Status:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{ this.computedClientDetails.client[0].civil_status.civil_status }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">City:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{ this.computedClientDetails.client[0].city }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Barangay:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{ this.computedClientDetails.client[0].barangay }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Street/Purok:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{ this.computedClientDetails.client[0].street ?? "" }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">ID Type:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{ this.computedClientDetails.client[0]?.client_identification[0]?.identification_type?.id_type ?? this.computedClientDetails.client[0]?.client_identification[0]?.other_identification_type?.other_id_type ?? ""  }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">ID Number:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{ this.computedClientDetails.client[0]?.client_identification[0]?.id_number }}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Precint:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{this.computedClientDetails.client[0].precint?.precint ?? ""}}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">Occupation:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{ this.computedClientDetails.client[0]?.client_occupation?.[0]?.occupation ?? ""}}</span>
                                    </div>
                                    <div class="col-4">
                                        <label class="fs-6 text-secondary fw-bold">M. Income:</label>
                                    </div>
                                    <div class="col-8">
                                        <span class="fs-6 ms-1 fw-bold">{{ this.computedClientDetails.client[0]?.client_occupation?.[0]?.pivot?.monthly_income ?? "" }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="buttons">
                <div class="row mt-3">
                    <div class="col-6">
                        <button v-if="(this.computedFetchAuthUserRolesPermissions.user_permissions.some(value => {value.name === 'editClientInformation'}) || this.computedFetchAuthUserRolesPermissions.user_roles.some(value => value === 'superAdmin'))" type="button" class="btn btn-success bg-gradient w-100"
                            @click="editClient()">EDIT</button>
                    </div>
                    <div class="col-6">
                        <button v-if="(this.computedFetchAuthUserRolesPermissions.user_permissions.some(value => {value.name === 'deleteClientInformation'}) || this.computedFetchAuthUserRolesPermissions.user_roles.some(value => value === 'superAdmin'))" type="button" class="btn btn-danger bg-gradient w-100" data-bs-toggle="modal" data-bs-target="#deleteModal">DELETE</button>
                    </div>
                </div>

            </section>
        </div>
    </div>


    <!-- Delete modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <span>
                        <h5 class="modal-title" id="deleteModalLabel">Are you sure to delete this client?</h5>
                        <h5 class="modal-title" id="deleteModalLabel">(All of his/her transactions will be also deleted)</h5>
                    </span>
                    <button type="button" id="btn-close-delete" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button"  @click="deleteClient()" class="btn btn-primary">Yes</button>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
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
.beneficiary-information {
    border-radius: 10px;
    padding: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
}

.approve-client {
    padding-left: 250px;
    overflow-y: hidden;
    height: -webkit-calc(100% - 50px);
    height: -moz-calc(100% - 50px);
    height: calc(100% - 50px);
    /* min-height: 100%;
    display: flex;
    flex-direction: column; */
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
import Loader2 from '../Loader2.vue';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import auth from '../../../router/auth_roles_permissions.js';


export default {
    components:{
        Loader2,
        Toast,
    },
    beforeMount() {
       
        this.toast = useToast();
        this.computedClientDetailsLoader = true;
        this.computedHeaderText = 'Client > Client Details';
        this.$store.dispatch('fetchClientDetails',{'id':this.$route.params.id}).then(response1 =>{
            // this.$store.state.client.clientImage = response1.data.client[0]?.image[0]?.file_name;
            this.computedClientDetailsLoader = false;
        });
    },
    mounted() {
       document.body.style.overflow = 'hidden';
    },
    beforeUnmount() {
        document.body.style.overflow = 'auto';
    },
    methods: {
        editClient(){
            this.$router.push({ name: 'editClient',params:{'id':this.$route.params.id} });
        },
        deleteClient(){
            document.getElementById('btn-close-delete').click();
            this.computedClientDetailsLoader = true;
            this.$store.dispatch('deleteClient',{'clientID':this.$route.params.id}).then(response => {
                this.computedClientDetailsLoader = false;
                this.computedClientToast = true;
                if(this.computedClientDetails){
                    this.toast.add({ severity: 'success', summary: 'Success', detail: 'Client deleted successfully.', life: 5000 });
                    this.computedClientToast = false;
                }
                this.$router.push({ name: 'clientInformation'});
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
        computedClientToast:{
            get(){
                return this.$store.state.clientToast;
            },
            set(value){
                this.$store.commit('setClientToast',{'clientToast':value});
            }
        },
        computedClientDetails:{
            get(){
                return this.$store.state.clientDetails;
            },
            set(value){
                this.$store.commit('setClientDetails',{'clientDetails':value});
            }
        },
        computedClientDetailsLoader:{
            get(){
                return this.$store.state.clientDetailsLoader;
            },  
            set(value){
                this.$store.commit('setClientDetailsLoader',{'clientDetailsLoader':value});
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