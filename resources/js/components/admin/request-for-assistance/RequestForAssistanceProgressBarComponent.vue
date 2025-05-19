<template>
    <div class="container py-4"> 
            <div class="progress-container"> 
                <ul id="progressbar"> 
                    <li :class="{ active: computedProgressActive === 1 }" id="step1"> 
                        <strong style="user-select:none" @click="clientBeneficiaryClick()">Client/Beneficiary Details</strong> 
                    </li> 
                    <li id="step2" :class="{ active: computedProgressActive === 2 }"> 
                          <strong style="user-select:none" @click="typeOfAssistanceClick()">Type of Assistance</strong> 
                    </li> 
                </ul>
                <div class="progress"> 
                    <div class="progress-bar" :style="{width: computedProgressBarWidth}"></div> 
                </div>
            </div> 
            
        </div> 
    </template>
    
    <style scoped>
    /* style.css */
    
    
    .progress-container { 
        margin: 0;
        padding-left: 250px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    } 
      
    #progressbar { 
      
        width: 100%;
        display: flex; 
        align-items: center;
        justify-content: center;
        color: lightgrey; 
        list-style-type: none; 
        padding: 0;
    } 
      
    #progressbar li { 
        flex: 1; 
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center; 
        font-size: 15px; 
        font-weight: bold; 
        position: relative; 
    
    } 
      
    #progressbar li.active { 
        color: #2F8D46; 
        transition: color 0.4s ease-in-out; 
    } 
      
    .progress { 
        width: 100%;
        height: 20px; 
        background-color: #E8E8E8; 
        border-radius: 5px; 
        overflow: hidden; 
    } 
      
    .progress-bar { 
        background-color: #2F8D46; 
        width: 25%; 
        height: 100%; 
        transition: width 0.4s ease-in-out; 
    } 
      
    
      
    </style>
    <script>
    
    export default{
    beforeMount(){
        
    },  
    mounted(){
        window.scrollTo({
        top: 0,
        behavior: 'instant' // Optional: adds smooth scrolling effect
         });



    },
    beforeUnmount(){

    },
    methods:{
        clientBeneficiaryClick(){

            this.$router.push({name: 'requestDetails'});
        },
        typeOfAssistanceClick(){
            
            this.$router.push({name: 'requestSubmit'});
   
        }
        
    },
    computed:{
        computedProgressBarWidth:{
            get(){
                return this.$store.state.progressBarWidth;
            },
            set(value){
                this.$store.commit('setProgressBarWidth', { progressBarWidth: value });
            }
        },
        computedProgressActive:{
            get(){
                return this.$store.state.progressActive;
            },
            set(value) {
                this.$store.commit('setProgressActive', { progressActive: value });
            }
        }
    },
    data(){
        return{
     
        }
    
    }
    
    }
    
    
    </script>