<template>
    <div class="form-group headers">
        <div class="h-100 d-flex align-items-center">
            <label class="text-center header-text mx-2">{{this.computedHeaderText}}</label>
        </div>
    </div>
</template>
<style scoped>
.headers{
    background-color: #E8E8E8;
    height: 50px;
    margin-left: 250px;
    border-radius: 10px;
    flex-shrink: 0; 
    /* width: 100%; */
}
.header-text{
    font-weight: bold;
    color:#9b9a9a;
    font-size: 18px;
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

    },
    computed:{
        computedHeaderText:{
            get(){
                return this.$store.state.headerText;
            },
            set(value){
                this.$store.commit('setHeaderText',{headerText:value});
            }
        }
    },
    data(){
        return{
    
        }
    }
    
}

</script>