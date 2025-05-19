<template>
<div class="print-template">
    <div v-for="(option,key) in this.computedSelectedReviewTransactionsToPrint" class="print-page">
        <BulkReceipt :reviewTransactionToPrint="option" @counter="loaded"></BulkReceipt>
    </div>
</div>

</template>
<style scoped>

.print-template{
    all: unset;
    display: block;
    position: relative;
}
@media print {
    .print-page {
        break-after: page;
    }
}
</style>

<script>
import { nextTick } from 'vue';
import BulkReceipt from '../print/BulkReceiptComponent.vue';
import auth from '../../../router/auth_roles_permissions.js';
  export default {
    components: {
      BulkReceipt,
    },
    methods:{
        loaded(message){
            if(this.computedSelectedReviewTransactionsToPrint.length == message){
                 this.$nextTick(() => {
                    window.print();
                });
            }
        }
    },
    beforeMount(){
        
    },
    mounted(){
      
    },
    computed:{
        computedSelectedReviewTransactionsToPrint:{
            get(){
                return this.$store.state.selectedReviewTransactionsToPrint;
            },
            set(value){
                this.$store.commit('setSelectedReviewTransactionsToPrint',{'selectedReviewTransactionsToPrint':value});
            }
        },
    },
    updated(){

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

  };
</script>
  