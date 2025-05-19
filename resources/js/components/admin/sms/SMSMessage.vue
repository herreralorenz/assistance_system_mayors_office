<template>
    <Toast></Toast>
    <Loader2 v-if="this.computedSMSMessageDetailsLoader"></Loader2>
    <div class="sms-message form-group py-4" v-else>
        <div class="container-fluid">
            <div class="content">
                <h2>SMS Message</h2>
                <select v-model="this.selectedSMSMessage" @change="handleMessage()" class="form-select">
                    <option value="" selected>Add a message</option>
                    <option :value="option.id" :key="option.id" v-for="(option,index) in this.computedSMSMessageDetails">{{ option.subject }}</option>
                </select>
                <section class="message mt-3">
                    <input  @keyup="handleMessageCharacters('subject')" placeholder="Subject" type="text" v-model="this.computedSMSMessage.subject" :class="{'form-control':true,'mt-3':true, 'focus':this.proceedValidation.subject.validate, 'invalid':this.proceedValidation.subject.validate}">
                    <textarea @keyup="handleMessageCharacters('message')"  :class="{'form-control':true,'mt-3':true, 'focus':this.proceedValidation.message.validate, 'invalid':this.proceedValidation.message.validate}"  v-model="this.computedSMSMessage.message" rows="10"></textarea>
                    <span>Characters:{{ this.computedSMSMessage.message.length }}</span>
                </section>
                <section class="mt-3 text-end">
                    <button type="button" class="btn btn-success w-25" v-if="this.selectedSMSMessage == ''" @click="this.add()">ADD</button>
                    <button type="button" class="btn btn-success w-25 mx-2"  v-if="this.selectedSMSMessage  != ''"  @click="this.update()">UPDATE</button>
                    <button type="button" class="btn btn-danger w-25"  v-if="this.selectedSMSMessage  != ''"  @click="this.delete()">DELETE</button>
                </section>
            </div>
        </div>
    </div>
</template>
<style scoped>

input,button,select, textarea{
    border-radius: 25px;
}

.sms-message{
    margin: 0;
    padding-left: 250px;
    overflow-y: scroll;
            
            
    height: -webkit-calc(100% - 50px);
    height: -moz-calc(100% - 50px);
    height: calc(100% - 50px);
}

.content{
    border-radius: 10px;
    padding: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5); 
}

.focus:focus {
    border-color: #ff5733 !important; /* Change border color */
    box-shadow: 0 0 5px rgba(255, 87, 51, 0.5) !important; /* Optional: Change box-shadow */
    outline: none !important;
}

.invalid{
    border-color: #ff5733 !important; /* Change border color */
    box-shadow: 0 0 5px rgba(255, 87, 51, 0.5) !important; /* Optional: Change box-shadow */
    outline: none !important;
}
</style>
<script>
import auth from '../../../router/auth_roles_permissions.js';
import Loader2 from '../Loader2.vue';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';
export default{
    components:{
        Loader2,
        Toast
    },
    beforeMount(){
        this.toast = useToast();
        this.computedHeaderText = 'SMS Message';

        this.computedSMSMessageDetailsLoader = true;
        this.$store.dispatch('fetchSMSMessageDetails').then(response => {
            this.computedSMSMessageDetailsLoader = false;

        });
    },
    mounted(){
        document.body.style.overflow = 'hidden';
    },
    beforeUnmount(){
        document.body.style.overflow = 'auto';
    },
    computed:{
        computedSMSMessageDetailsLoader:{
            get(){
                return this.$store.state.SMSMessageDetailsLoader;
            },
            set(value){
                this.$store.commit('setSMSMessageDetailsLoader',{'SMSMessageDetailsLoader':value});
            }
        },
        computedSMSMessageDetails:{
            get(){
                return this.$store.state.SMSMessageDetails;
            },
            set(value){
                this.$store.commit('setSMSMessageDetails',{'SMSMessageDetails':value});
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
        computedSMSMessage:{
            get(){
                return this.$store.state.SMSMessage;
            },
            set(value){
                this.$store.commit('setSMSMessage',{'SMSMessage':value});
            }
            
        },

    },
    methods:{
        delete(){
            this.computedSMSMessageDetailsLoader = true;
            this.$store.dispatch('deleteMessage',{'id':this.selectedSMSMessage}).then(response => {
                this.$store.dispatch('fetchSMSMessageDetails').then(response => {
                    this.computedSMSMessageDetailsLoader = false;
                    for(const [key,value] of Object.entries(this.computedSMSMessage)){
                        this.computedSMSMessage[key] = '';
                    }
                    this.toast.add({ severity: 'success', summary: 'Successfully deleted', detail: 'Subject and message has been succesfully deleted.', life: 5000 });
                });
            });
        },
        add(){
    


            for(const [key,value] of Object.entries(this.proceedValidation)){
                if(this.computedSMSMessage[key].length >= this.proceedValidation[key]['minChar'] && this.computedSMSMessage[key].length <= this.proceedValidation[key]['maxChar']){
                    this.proceedValidation[key].validate = false;
                }else{
                    this.proceedValidation[key].validate = true;
                }
            }

            let proc = true;
            for(const [key,value] of Object.entries(this.proceedValidation)){
                if(this.proceedValidation[key].validate){
                    proc = false;
                }
            }

            if(proc){
                this.computedSMSMessageDetailsLoader = true;
                this.$store.dispatch('addMessage',{'message':this.computedSMSMessage}).then(response => {
                this.$store.dispatch('fetchSMSMessageDetails').then(response => {
                    this.toast.add({ severity: 'success', summary: 'Successfully added', detail: 'Subject and message has been succesfully added.', life: 5000 });
                    this.computedSMSMessageDetailsLoader = false;
                    });
                });
            }
            
        },
        handleMessageCharacters(textField){
           if(this.computedSMSMessage[textField].length >= this.proceedValidation[textField].minChar && this.computedSMSMessage[textField].length <= this.proceedValidation[textField].maxChar){
                this.proceedValidation[textField].validate = false;
           }else{
            this.proceedValidation[textField].validate = true;
           }
        },
        handleMessage(){
            let selected = this.computedSMSMessageDetails.find((element) => element.id === this.selectedSMSMessage);
            this.computedSMSMessage.subject = selected?.subject ?? "";
            this.computedSMSMessage.message = selected?.message ?? "";
        },
        update(){

            for(const [key,value] of Object.entries(this.proceedValidation)){
                if(this.computedSMSMessage[key].length >= this.proceedValidation[key]['minChar'] && this.computedSMSMessage[key].length <= this.proceedValidation[key]['maxChar']){
                    this.proceedValidation[key].validate = false;
                }else{
                    this.proceedValidation[key].validate = true;
                }
            }

            let proc = true;
            for(const [key,value] of Object.entries(this.proceedValidation)){
                if(this.proceedValidation[key].validate){
                    proc = false;
                }
            }

            if(proc){
                this.computedSMSMessageDetailsLoader = true;
                this.$store.dispatch('updateMessage',{'id':this.selectedSMSMessage,'message':this.computedSMSMessage}).then(response => {
                    this.toast.add({ severity: 'success', summary: 'Successfully updated', detail: 'Subject and message has been succesfully updated.', life: 5000 });
                    this.computedSMSMessageDetailsLoader = false;
                });
            }
        }
    },
    data(){
        return {
            toast:null,
            selectedSMSMessage:'',
            proceedValidation:{
                'message':{
                    'validate':false,
                    'maxChar':3000,
                    'minChar':10
                },
                'subject':{
                    'validate':false,
                    'maxChar':250,
                    'minChar':10,
                }
                
            }
        }
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