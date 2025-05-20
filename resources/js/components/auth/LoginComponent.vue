<template>
  <Toast></Toast>
  <Transition>
    <Loader2 v-if="this.loader"></Loader2>
  </Transition>
    <section class="vh-100">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6 text-black">
    
            <div class="px-5 ms-xl-4 mt-3 text-center">
                <img @load="congOnylogoPicMethod" src="/storage/images/ljf.webp"
                alt="Login image" class="img-fluid" style="height: 250px;">
            </div>
    
            <div class="d-flex align-items-center h-custom-2 px-5 ms-xl-4 mt-5 pt-5 pt-xl-0 mt-xl-n5">
    
              <form style="width: 100%;">
    
                <!-- <h3 class="fw-normal mb-3 pb-3 text-center" style="letter-spacing: 1px;">Log in</h3> -->
    
                <div data-mdb-input-init class="form-outline mb-4">
                  <label class="form-label" for="email">Email address</label>
                  <input @keyup.enter="this.login();" type="email" id="email" :class="{'form-control':true, 'form-control-lg':true, 'is-invalid':this.proceedValidation.email}"  v-model="this.computedLogin.email"/>

                </div>
    
                <div data-mdb-input-init class="form-outline mb-4">
                  <label class="form-label" for="password">Password</label>
                  <input @keyup.enter="this.login();" type="password" id="password" :class="{'form-control':true, 'form-control-lg':true, 'is-invalid':this.proceedValidation.password}"  v-model="this.computedLogin.password" />

                </div>
  
                <div class="pt-1 mb-4">
                  <button data-mdb-button-init data-mdb-ripple-init class="btn btn-success btn-lg btn-block" type="button" @click="this.login()">Login</button>
                </div>
    
                <p class="small mb-5 pb-lg-2"><a class="text-muted">Forgot password? Contact your administrator</a></p>

              </form>
    
            </div>
    
          </div>
          <div class="col-sm-6 px-0 vh-100 d-flex align-items-center justify-content-center position-relative" >
            <div class="gradient h-100 w-100">
            </div>
            <img @load="congonyPicMethod" src="/storage/images/mayorjonlogin.webp"
            alt="Login image" class="object-fit-cover h-100 w-100">
          </div>
        </div>
      </div>
    </section>
    </template>
<style scoped>

.gradient{
  position: absolute;
  opacity: 30%;
  background: #2A7B9B;
  background: linear-gradient(90deg, rgba(42, 123, 155, 1) 0%, rgba(87, 199, 133, 1) 50%, rgba(237, 221, 83, 1) 100%);
}

  .v-leave-active {
    transition: opacity 0.5s ease;
  }


  .v-leave-to {
    opacity: 0;
  }

  
  .bg-image-vertical {
    position: relative;
    overflow: hidden;
    background-repeat: no-repeat;
    background-position: right center;
    background-size: auto 100%;
  }
    
    
</style>
<script>

import Loader2 from '../auth/Loader2.vue';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';

    export default{
        components:{
          Loader2,
          Toast,
        },
        beforeMount(){
          this.toast = useToast();
          
        },
        watch:{
          congOnylogoPic(newVal){
            if(newVal && this.congonyPic){
              this.loader = false;
            }
          },
          congonyPic(newVal){
            if(newVal && this.congOnylogoPic){
              this.loader = false;
            }
          }
        },
        computed:{
          computedLogin:{
            get(){
              return this.$store.state.login;
            },
            set(value){
              this.$store.commit('setLogin',{'login':value});
            }
          }
        },
        methods:{
          congOnylogoPicMethod(){
            this.congOnylogoPic = true;
          },
          congonyPicMethod(){
            this.congonyPic = true;
          },
          login(){

            if(/^[^@]+@[^@]+\.[^@]+$/.test(this.computedLogin.email)){
             
              this.proceedValidation.email = false;
            }else{
              this.proceedValidation.email = true;
            }

            if(typeof this.computedLogin.password === 'string' && this.computedLogin.password && this.computedLogin.password != ''){
              this.proceedValidation.password = false;
            }else{
              this.proceedValidation.password = true;
            }

            let proc = true;
            for(const [key,value] of Object.entries(this.proceedValidation)){
              if(this.proceedValidation[key] == true){
                proc = false;
                break;
              }
            }

            if(proc){
              // let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
              this.$store.dispatch('login',{'login':this.computedLogin}).then(response =>{
                if(response.status === 200){
                  this.toast.add({ severity: 'success', summary: 'Successfully login', detail: 'Redirecting to admin panel.', life: 5000 });
                  window.location.replace('/admin');
                }else{
                  
                  this.toast.add({ severity: 'warn', summary: 'Incorrect Credentials', detail: 'Invalid username/password', life: 5000 });
                }
              }).catch(error =>{
                if(error.response.status === 401){
                  this.toast.add({ severity: 'error', summary: 'Incorrect Credentials', detail: 'Invalid username/password', life: 5000 });
                }
            
              });
            }
            
          }
        },
        data(){
          return {
              toast:null,
              congOnylogoPic:false,
              congonyPic:false,
              loader:true,
              proceedValidation:{
                email:false,
                password:false,
              }
          }
        },
        beforeRouteEnter(from, to, next){
      

          next();
       
          
          
        }
    
       
    }
    </script>