<template>
    <div class="welcome">
        <div class="container-fluid h-100">
            <div class="d-flex flex-row h-100 ">
                <div class="cong">
                    <Transition>
                        <img v-if="transition" src="/storage/images/mayorjon.webp" class="congony">
                    </Transition>
                </div>
                <div class="logo">
                    <div>
                        <Transition>
                            <p  v-if="transition" class="welcome-text">WELCOME.</p>
                        </Transition>
                        <Transition>
                            <p  v-if="transition" class="district-6">CITY OF GENERAL TRIAS ASSISTANCE SYSTEM</p>
                        </Transition>
                    </div>
                    <hr>
                    <div >
                        <Transition>
                                <img v-if="transition" src="/storage/images/ljf.webp"  class="congonylogo">
                        </Transition>
                        <Transition>
                                <img v-if="transition" src="/storage/images/cityofgeneraltrias.webp"  class="hor">
                        </Transition>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.v-enter-active,
.v-leave-active {
  transition: opacity 1s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}

.welcome-text{
    font-family: 'Poppins', sans-serif;
    font-size: 120px;
    font-weight: 800;
    color: green;
    line-height: 1;
    margin-bottom: 0; /* Remove bottom margin */
    padding-bottom: 0; /* Remove bottom padding */

}

.district-6{
    font-family: 'Poppins', sans-serif;
    font-size: 30px;
    font-weight: 600;
    color: green;
    line-height: 1;
    margin-bottom: 0; /* Remove bottom margin */
    padding-bottom: 0; /* Remove bottom padding */
}
.welcome {
    padding-left: 250px;
    height: 100%;
    background: rgb(255,255,255);
    background: radial-gradient(circle, rgba(255,255,255,1) 0%, rgba(200,222,206,1) 100%);
}


.cong {
    position: relative;
    height: 100%;
    width: 50%;
}

.congony {
    position: absolute;

    height: 95%;
    bottom:0;
}

.logo {
    z-index: 1000;
    padding-top: 180px;
    height: 100%;
    width: 100%;
}

.congonylogo {
    /* position:absolute;
    top:50px;
    height: 35%;
    width: auto; */
    height: 150px;
}

.hor {
    /* position:absolute;
    top:50px;
    height: 35%;
    width: auto; */
    height: 130px;
}

@media (max-width: 1200px) {
    
    .welcome-text {
        
        font-size: 80px; /* Reduce text size for smaller screens */
    }
    .district-6 {
        
        font-size: 24px;
    }
    .congonylogo {
        height: 120px;
    }

    .hor{
        height: 100px;
    }
}

@media (max-width: 992px) {
    .welcome-text {
        font-size: 60px; /* Further reduce text size */
    }
    .district-6 {
        font-size: 20px;
    }
    .congonylogo {
        height: 100px;
    }
}

@media (max-width: 768px) {
    .d-flex.flex-row {
        text-align: center;
        flex-direction: column; /* Stack elements vertically */
        align-items: center;
    }
    .cong {
        width: 100%;
        text-align: center;
    }
    .logo {
        text-align: center;
        padding-top: 50px;
    }
    .welcome-text {
        text-align: center;
        font-size: 50px;
    }
    .district-6 {
        text-align: center;
        font-size: 18px;
    }
    .congonylogo {
        text-align: center;
        height: 80px;
    }
}


</style>
<script>
export default{
    components:{

    },
    mounted(){

    },
    mounted(){
        this.transition = true;
    },
    beforeRouteEnter(to, from, next){
        next();
    },
    data(){
        return{
            transition:false,
        }
    }
}
</script>