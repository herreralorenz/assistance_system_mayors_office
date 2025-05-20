<template>
    <div id="sidebar-wrapper" class="flex-shrink-0 p-3"><a class="d-flex align-items-center pb-3 mb-3 link-dark text-decoration-none border-bottom"><img :src="'/storage/images/ljf.webp'" class="img-fluid" src="" width="85" style="padding: 5px;" /><span class="fs-6 fw-semibold text-white">Assistance System</span></a>
        <ul class="list-unstyled ps-0">
            <button v-if="this.computedFetchAuthUserRolesPermissions['user_permissions'].some(value => {if(value.name === 'dashboard'){return true}}) || this.computedFetchAuthUserRolesPermissions['user_roles'].some(value => {if(value === 'superAdmin'){return true}})" @click="this.navigate('dashboard')" class="btn align-items-center rounded collapsed text-white">
                Dashboard
            </button>
            <button v-if="this.computedFetchAuthUserRolesPermissions['user_permissions'].some(value => {if(value.name === 'requestAssistance'){return true}}) || this.computedFetchAuthUserRolesPermissions['user_roles'].some(value => {if(value === 'superAdmin'){return true}})" @click="this.navigate('request')" class="btn align-items-center rounded collapsed text-white">
                Request for Assistance
            </button>
            <li class="mb-1" v-if="this.computedFetchAuthUserRolesPermissions['user_permissions'].some((element) => {const permissions = ['approveAssistance','declineAssistance','bulkApproveAssistance','viewApproveAssistance','editApproveAssistance']; if(permissions.includes(element.name)){return true;}})">
               <button class="btn btn-toggle align-items-center rounded collapsed text-white" data-bs-toggle="collapse" data-bs-target="#approval-collapse">
                    Approval of Assistance
                </button>
                <div id="approval-collapse" class="collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><button v-if="(this.computedFetchAuthUserRolesPermissions['user_permissions'].map(value => value.name).includes('viewApproveAssistance') || this.computedFetchAuthUserRolesPermissions['user_permissions'].map(value => value.name).some(name => {const name1 = ['approveAssistance','declineAssistance','editApproveAssistance']; return name.includes(name1);})) || this.computedFetchAuthUserRolesPermissions['user_roles'].some(value => value === 'superAdmin')" class="btn align-items-center rounded collapsed text-white"  @click="this.navigate('approve')">Approve Assistance</button></li>
                        <li><button v-if="this.computedFetchAuthUserRolesPermissions['user_permissions'].map(value => value.name).includes('bulkApproveAssistance') || this.computedFetchAuthUserRolesPermissions['user_roles'].some(value => value === 'superAdmin')" class="btn align-items-center rounded collapsed text-white" @click="this.navigate('bulkApprove')">Bulk Approval Assistance</button></li>
                        <li><button v-if="this.computedFetchAuthUserRolesPermissions['user_permissions'].map(value => value.name).includes('approveAssistance') || this.computedFetchAuthUserRolesPermissions['user_roles'].some(value => value === 'superAdmin')" class="btn align-items-center rounded collapsed text-white" @click="navigate('approveQR')">Approve Assistance QR</button></li>
                        <!--<li><a class="link-dark rounded" href="#">Sign out</a></li>-->
                    </ul>
                </div>
            </li>
            <li v-if="this.computedFetchAuthUserRolesPermissions['user_permissions'].some((element) => {const permissions = ['claimAssistance','unclaimAssistance','bulkClaimAssistance','editClaimAssistance','viewClaimAssistance']; if(permissions.includes(element.name)){return true;}})" class="mb-1" >
               <button class="btn btn-toggle align-items-center rounded collapsed text-white" data-bs-toggle="collapse" data-bs-target="#claiming-collapse">
                    Claiming of Assistance
                </button>
                <div id="claiming-collapse" class="collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><button v-if="(this.computedFetchAuthUserRolesPermissions['user_permissions'].map(value => value.name).includes('viewClaimAssistance') || this.computedFetchAuthUserRolesPermissions['user_permissions'].map(value => value.name).some(name => {const name1 = ['claimAssisstance','unclaimAssistance','editClaimAssistance']; return name.includes(name1)})) || this.computedFetchAuthUserRolesPermissions['user_roles'].some(value => value === 'superAdmin')" class="btn align-items-center rounded collapsed text-white" @click="this.navigate('claim')">Claiming Assistance</button></li>
                        <li><button v-if="this.computedFetchAuthUserRolesPermissions['user_permissions'].map(value => value.name).includes('bulkClaimAssistance') || this.computedFetchAuthUserRolesPermissions['user_roles'].some(value => value === 'superAdmin')" class="btn align-items-center rounded collapsed text-white" @click="this.navigate('bulkClaim')">Bulk Claiming of Assistance</button></li>
                        <li><button v-if="this.computedFetchAuthUserRolesPermissions['user_permissions'].map(value => value.name).includes('claimAssistance') || this.computedFetchAuthUserRolesPermissions['user_roles'].some(value => value === 'superAdmin')" class="btn align-items-center rounded collapsed text-white" @click="this.navigate('claimQR')">Claim Assistance QR</button></li>
                        <!--<li><a class="link-dark rounded" href="#">Sign out</a></li>-->
                    </ul>
                </div>
            </li>
            <li class="mb-1" v-if="this.computedFetchAuthUserRolesPermissions['user_permissions'].some((element) => {const permissions = ['viewClientInformation','editClientInformation','deleteClientInformation','editClaimAssistance','newTransaction']; if(permissions.includes(element.name)){return true;}})">
               <button class="btn btn-toggle align-items-center rounded collapsed text-white" data-bs-toggle="collapse" data-bs-target="#client-collapse">
                    Client
                </button>
                <div id="client-collapse" class="collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><button v-if="(this.computedFetchAuthUserRolesPermissions['user_permissions'].map(value => value.name).includes('viewClientInformation') || this.computedFetchAuthUserRolesPermissions['user_permissions'].map(value => value.name).some(name => { const name1 = ['editClientInformation','deleteClientInformation']; return name.includes(name1) })) || this.computedFetchAuthUserRolesPermissions['user_roles'].some(value => value === 'superAdmin')" class="btn align-items-center rounded collapsed text-white" @click="navigate('clientInformation')">Client Information</button></li>
                        <li><button v-if="this.computedFetchAuthUserRolesPermissions['user_permissions'].map(value => value.name).includes('viewClientInformation','newTransaction') || this.computedFetchAuthUserRolesPermissions['user_roles'].some(value => value === 'superAdmin')" class="btn align-items-center rounded collapsed text-white" @click="navigate('clientNewTransactionTable')">New Transaction</button></li>
                        <!--<li><a class="link-dark rounded" href="#">Sign out</a></li>-->
                    </ul>
                </div>
            </li>
            <li class="mb-1" v-if="this.computedFetchAuthUserRolesPermissions['user_permissions'].some((element) => {const permissions = ['viewTransaction','voidTransaction','editBeneficiary','transactionReport','bulkPrintingOfReceipt','deleteTransaction','addBeneficiary']; if(permissions.includes(element.name)){return true;}})">
               <button class="btn btn-toggle align-items-center rounded collapsed text-white" data-bs-toggle="collapse" data-bs-target="#transaction-collapse">
                    Transactions
                </button>
                <div id="transaction-collapse" class="collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><button v-if="(this.computedFetchAuthUserRolesPermissions['user_permissions'].map(value => value.name).includes('viewTransaction') || this.computedFetchAuthUserRolesPermissions['user_permissions'].map(value => value.name).some(name => { const name1 = ['voidTransaction','editBeneficiary','deleteTransaction']; return name.includes(name1); })) || this.computedFetchAuthUserRolesPermissions['user_roles'].some(value => value === 'superAdmin')" class="btn align-items-center rounded collapsed text-white" @click="navigate('clientTransactions')">Client Transactions</button></li>
                        <li><button v-if="this.computedFetchAuthUserRolesPermissions['user_permissions'].map(value => value.name).includes('transactionReport') || this.computedFetchAuthUserRolesPermissions['user_roles'].some(value => value === 'superAdmin')" class="btn align-items-center rounded collapsed text-white" @click="navigate('transactionsReport')">Transaction Report</button></li>
                        <li><button v-if="this.computedFetchAuthUserRolesPermissions['user_permissions'].map(value => value.name).includes('bulkPrintingOfReceipt') || this.computedFetchAuthUserRolesPermissions['user_roles'].some(value => value === 'superAdmin')" class="btn align-items-center rounded collapsed text-white" @click="navigate('bulkPrinting')">Bulk Printing of Receipt</button></li>
                        <!-- <li><button class="btn align-items-center rounded collapsed text-white" @click="navigate('uploadTransactions')">Upload Transactions</button></li> -->
                        <!--<li><a class="link-dark rounded" href="#">Sign out</a></li>-->
                    </ul>
                </div>
            </li>
            <li class="mb-1" v-if="this.computedFetchAuthUserRolesPermissions['user_permissions'].some((element) => {const permissions = ['SMSMessage','sendSMS']; if(permissions.includes(element.name)){return true;}})">
               <button class="btn btn-toggle align-items-center rounded collapsed text-white" data-bs-toggle="collapse" data-bs-target="#sms-collapse">
                    SMS
                </button>
                <div id="sms-collapse" class="collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><button v-if="this.computedFetchAuthUserRolesPermissions['user_permissions'].map(value => value.name).includes('sendSMSToClaim') || this.computedFetchAuthUserRolesPermissions['user_roles'].some(value => value === 'superAdmin')" class="btn align-items-center rounded collapsed text-white" @click="navigate('sendSMS')"> Send SMS</button></li>
                        <li><button v-if="this.computedFetchAuthUserRolesPermissions['user_permissions'].map(value => value.name).includes('SMSMessage') || this.computedFetchAuthUserRolesPermissions['user_roles'].some(value => value === 'superAdmin')" class="btn align-items-center rounded collapsed text-white" @click="navigate('SMSMessage')"> SMS Message</button></li>
                    </ul>
                </div>
            </li>
            <li class="mb-1" v-if="this.computedFetchAuthUserRolesPermissions['user_permissions'].some((element) => {const permissions = ['returnDays']; if(permissions.includes(element.name)){return true;}})">
               <button class="btn btn-toggle align-items-center rounded collapsed text-white" data-bs-toggle="collapse" data-bs-target="#maintenance-collapse">
                    Maintenance
                </button>
                <div id="maintenance-collapse" class="collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><button v-if="this.computedFetchAuthUserRolesPermissions['user_permissions'].map(value => value.name).includes('returnDays') || this.computedFetchAuthUserRolesPermissions['user_roles'].some(value => value === 'superAdmin')" class="btn align-items-center rounded collapsed text-white" @click="navigate('editReturnDays')">Edit Return Days</button></li>
                        <li><button v-if="this.computedFetchAuthUserRolesPermissions['user_roles'].some(value => value === 'superAdmin')" class="btn align-items-center rounded collapsed text-white" @click="navigate('users')">Users</button></li>
                        <li><button v-if="this.computedFetchAuthUserRolesPermissions['user_roles'].some(value => value === 'superAdmin')" class="btn align-items-center rounded collapsed text-white" @click="navigate('addPermissions')">Add Permissions</button></li>
                        <!--<li><a class="link-dark rounded" href="#">Sign out</a></li>-->
                    </ul>
                </div>
            </li>
            <li class="mb-1">
               <button class="btn btn-toggle align-items-center rounded collapsed text-white" data-bs-toggle="collapse" data-bs-target="#account-collapse">
                    Account
                </button>
                <div id="account-collapse" class="collapse">
                    <!-- <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><a href="" class="link-dark rounded">Change Password</a></li>
                    </ul> -->
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <!--<li><a class="link-dark rounded" href="#">Profile</a></li>-->
                        <!-- <form id="logout-form" action="/logout" method="POST" class=""> -->
                            <li><button class="btn align-items-center rounded collapsed text-white"  @click="logout()" >Sign out</button></li>
                        <!-- </form> -->
                        <!--<li><a class="link-dark rounded" href="#">Sign out</a></li>-->
                    </ul>
                </div>
            </li>
        </ul>
    </div>
    </template>
    <style scoped>
    #sidebar-wrapper {
        z-index: 1000;
        position: fixed;
        left: 250px;
        width: 250px;
        height: 100%;

        margin-left: -250px;
        overflow-y: auto;
        background: url('/storage/images/mayorjonnavigation.webp');
        background-size: cover; /* Ensure the image covers the container */
        background-repeat: no-repeat; /* Prevent the image from repeating */
        background-position: -60px;
        /* background: #34c26a; */
        border-top-right-radius: 2%;
        border-bottom-right-radius: 2%;
      }
      
      .bi {
        vertical-align: -.125em;
        pointer-events: none;
        fill: currentColor;
      }
      
      .dropdown-toggle {
        outline: 0;
      }
      
      .nav-flush .nav-link {
        border-radius: 0;
      }
      
      .btn{
        display: inline-flex;
        align-items: center;
        padding: .25rem .5rem;
        font-weight: 600;
        color: rgb(255, 255, 255);
        background-color: transparent;
        border: 0;
      }

      .btn:hover, .btn:focus {
        color: rgba(255, 255, 255);
        background-color: #005239;
      }
      
      .btn-toggle {
        display: inline-flex;
        align-items: center;
        padding: .25rem .5rem;
        font-weight: 600;
        color: rgb(255, 255, 255);
        background-color: transparent;
        border: 0;
      }
      
      .btn-toggle:hover, .btn-toggle:focus {
        color: rgba(255, 255, 255);
        background-color: #005239;
      }
      
      .btn-toggle::before {
        width: 1.25em;
        line-height: 0;
        content: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='rgba%28255,255,255,1%29' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 14l6-6-6-6'/%3e%3c/svg%3e");
        transition: transform .35s ease;
        transform-origin: .5em 50%;
      }
      
      .btn-toggle[aria-expanded="true"] {
        color: rgba(255, 255, 255, 0.85);
      }
      
      .btn-toggle[aria-expanded="true"]::before {
        transform: rotate(90deg);
      }
      
      .btn-toggle-nav a {
        display: inline-flex;
        padding: .1875rem .5rem;
        margin-top: .125rem;
        margin-left: 1.25rem;
        text-decoration: none;
      }
      
      .btn-toggle-nav a:hover, .btn-toggle-nav a:focus {
        background-color: #005239;
      }
      
      .scrollarea {
        overflow-y: auto;
      }
      
      .fw-semibold {
        font-weight: 600;
      }
      
      .lh-tight {
        line-height: 1.25;
      }
      
     .lh-tight {
        line-height: 1.25;
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
      data(){
        return {

        };
      },
      computed:{
          computedFetchAuthUserRolesPermissions:{
                  get(){
                          return this.$store.state.authCheckUserRolesPermissions;
                  },
                  set(value){
                          this.$store.commit('setAuthCheckUserRolesPermissions',{'authCheckUserRolesPermissions':value});     
                  }
          },
      },
      methods:{
        logout(){
        //   new Promise (async (resolve,reject) => {
        //     try{
        //         const response = await axios.post('/logout');
        //         resolve(response.data); 
        //     }catch(error){
        //         reject(error);
        //     };
        // }).then(data => {
        //   // console.log(data);
        //   window.location.href = '/login'
        // });
        this.$store.dispatch('logout').then(response =>{
          if(response.status === 200){
            window.location.replace('/');
          }
        });
        },
        navigate(nav){
          if(nav === 'approve'){
            this.$router.push({ name: 'approveAssistanceTable' });
          }else if(nav === 'request'){
            this.$router.push({ name: 'requestDetails' });
          }else if(nav === 'bulkApprove'){
            this.$router.push({ name: 'bulkApproveClientAssistance' });
          }else if(nav === 'claim'){
            this.$router.push({name: 'claimAssistanceTable'});
          }else if(nav === 'bulkClaim'){
            this.$router.push({name: 'bulkClaimClientAssistance'});
          }else if(nav === 'clientInformation'){
            this.$router.push({name: 'clientInformation'});
          }else if(nav === 'clientNewTransactionTable'){
            this.$router.push({name: 'clientNewTransactionTable'});
          }else if(nav === 'clientTransactions'){
            this.$router.push({name: 'clientTransactionTable'});
          }else if(nav === 'transactionsReport'){
            this.$router.push({name: 'transactionReport'});
          }else if(nav === 'bulkPrinting'){
            this.$router.push({name: 'bulkPrinting'});
          }else if(nav === 'editReturnDays'){
            this.$router.push({name: 'editReturnDays'});
          }else if(nav === 'addPermissions'){
            this.$router.push({name: 'addPermissions'});  
          }else if(nav === 'users'){
            this.$router.push({name: 'usersTable'});  
          }else if(nav === 'uploadTransactions'){
            this.$router.push({name: 'uploadTransactions'});  
          }else if(nav === 'claimQR'){
            this.$router.push({name: 'claimAssistanceQR'});  
          }else if(nav === 'approveQR'){
            this.$router.push({name: 'approveAssistanceQR'});  
          }else if(nav === 'sendSMS'){
            this.$router.push({name: 'sendSMS'});  
          }else if(nav === 'SMSMessage'){
            this.$router.push({name: 'SMSMessage'});  
          }else if(nav === 'dashboard'){
            this.$router.push({name: 'dashboard'});  
          }
        }
      }
    }
    </script>