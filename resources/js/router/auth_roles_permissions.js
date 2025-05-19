import store from '../store/store.js'

export default async function authCheck(to){

    if(store.state.authCheckUserRolesPermissions?.user_roles?.length > 0 || store.state.authCheckUserRolesPermissions?.user_permissions?.length > 0){
                   
        if(to.path.startsWith('/dashboard')){
                store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                const hasRole = store.state.authCheckUserRolesPermissions.user_roles.some(value => value === 'superAdmin');
                const hasPermission = store.state.authCheckUserRolesPermissions.user_permissions.some(value => value.name === 'dashboard');

                if(hasRole || hasPermission){
                        return true;
                }else{
                        return false;
                }
        }else if(to.path.startsWith('/request') || to.path === '/print/request-for-assistance-receipt/'+to.params.id || to.path === '/print/bulk-printing-of-receipt-holder'){                        
                store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                const hasRole = store.state.authCheckUserRolesPermissions.user_roles.some(value => value === 'superAdmin');
                const hasPermission = store.state.authCheckUserRolesPermissions.user_permissions.some(value => value.name === 'requestAssistance');
    
                if(hasRole || hasPermission){
             
                    return true;
                }else{
                    return false;
                }

        }else if(to.path === '/approve/client' || to.path === '/approve/client/'+to.params.id){
                store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                const hasRole = store.state.authCheckUserRolesPermissions.user_roles.some(value => value === 'superAdmin');
                const hasPermission = store.state.authCheckUserRolesPermissions.user_permissions.some(value => value.name === 'viewApproveAssistance');

                if(hasRole || hasPermission){
        
                        return true;
                }else{
                        return false;
                }
        }else if(to.path === '/approve/bulk'){
                store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                const hasRole = store.state.authCheckUserRolesPermissions.user_roles.some(value => value === 'superAdmin');
                const hasPermission = store.state.authCheckUserRolesPermissions.user_permissions.some(value => value.name === 'bulkApproveAssistance');
                if(hasRole || hasPermission){
                     
                        return true;
                }else{
                        return false;
                }
        }else if(to.path === '/approve/approve-qr'){
                store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                const hasRole = store.state.authCheckUserRolesPermissions.user_roles.some(value => value === 'superAdmin');
                const hasPermission = store.state.authCheckUserRolesPermissions.user_permissions.some(value => value.name === 'approveAssistance');
                if(hasRole || hasPermission){
                        return true;
                }else{
                        return false;
                }
        }else if(to.path === '/claim/client' || to.path === '/claim/client/'+to.params.id){
                store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                const hasRole = store.state.authCheckUserRolesPermissions.user_roles.some(value => value === 'superAdmin');
                const hasPermission =store.state.authCheckUserRolesPermissions.user_permissions.some(value => value.name === 'viewApproveAssistance');
                if(hasRole || hasPermission){
                        return true;
                }else{
                        return false;
                }
        }else if(to.path === '/claim/bulk'){

                store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                const hasRole = store.state.authCheckUserRolesPermissions.user_roles.some(value => value === 'superAdmin');
                const hasPermission = store.state.authCheckUserRolesPermissions.user_permissions.some(value => value.name === 'bulkClaimAssistance');
                if(hasRole || hasPermission){
                        return true;
                }else{
                        return false; 
                }

        }else if(to.path === '/claim/claim-qr'){

                store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                const hasRole = store.state.authCheckUserRolesPermissions.user_roles.some(value => value === 'superAdmin');
                const hasPermission = store.state.authCheckUserRolesPermissions.user_permissions.some(value => value.name === 'claimAssistance');
                if(hasRole || hasPermission){
                        return true;
                }else{
                        return false;
                }
        
        }else if(to.path === '/client/information' || to.path === '/client/information/'+to.params.id){
                store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                const hasRole = store.state.authCheckUserRolesPermissions.user_roles.some(value => value === 'superAdmin');
                const hasPermission = store.state.authCheckUserRolesPermissions.user_permissions.some(value => value.name === 'viewClientInformation');
                if(hasRole || hasPermission){
                        return true;
                }else{
                        return false;
                }
        
        }else if(to.path === '/client/information/'+to.params.id+'/edit'){
                store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                const hasRole = store.state.authCheckUserRolesPermissions.user_roles.some(value => value === 'superAdmin');
                const hasPermission = store.state.authCheckUserRolesPermissions.user_permissions.some(value => value.name === 'editClientInformation');
                if(hasRole || hasPermission){
                        return true;
                }else{
                        return false;
                }
        
        }else if(to.path === '/client/new-transaction' || to.path === '/client/new-transaction/'+to.params.id+'/beneficiary' || to.path === '/client/new-transaction/'+to.params.id+'/beneficiary/submit'){
                store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                const hasRole = store.state.authCheckUserRolesPermissions.user_roles.some(value => value === 'superAdmin');
                const hasPermission = store.state.authCheckUserRolesPermissions.user_permissions.some(value => value.name === 'newTransaction');
                if(hasRole || hasPermission){
                        return true;
                }else{
                        return false;
                }
        
        }else if(to.path === '/transactions/client-transactions' || to.path === '/transactions/client-transaction/'+to.params.id){

                store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                const hasRole =  store.state.authCheckUserRolesPermissions.user_roles.some(value => value === 'superAdmin');
                const hasPermission =  store.state.authCheckUserRolesPermissions.user_permissions.some(value => value.name === 'viewTransaction');
                if(hasRole || hasPermission){
                        return true;
                }else{
                        return false;
                }
          
        }else if(to.path === '/transactions/client-transactions/'+to.params.id+'/beneficiary/'+to.params.id2+'/client/'+to.params.id3){
                store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                const hasRole =  store.state.authCheckUserRolesPermissions.user_roles.some(value => value === 'superAdmin');
                const hasPermission =  store.state.authCheckUserRolesPermissions.user_permissions.some(value => value.name === 'editBeneficiary');
         
                if(hasRole || hasPermission){
                        return true;
                }else{
                        return false;
                }
   
        }else if(to.path === '/transactions/client-transactions/'+to.params.id+'/client/'+to.params.id2+'/add-beneficiary'){
                console.log(123);
                store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                const hasRole = store.state.authCheckUserRolesPermissions.user_roles.some(value => value === 'superAdmin');
                const hasPermission = store.state.authCheckUserRolesPermissions.user_permissions.some(value => value.name === 'addBeneficiary');
                if(hasRole || hasPermission){
                        return true;
                }else{
                        return false;
                }

       
        }else if(to.path === '/transactions/report'){
                store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                const hasRole =  store.state.authCheckUserRolesPermissions.user_roles.some(value => value === 'superAdmin');
                const hasPermission =  store.state.authCheckUserRolesPermissions.user_permissions.some(value => value.name === 'transactionReport');
                if(hasRole || hasPermission){
                        return true;
                }else{
                        return false;
                }

        }else if(to.path === '/transactions/bulk-printing'){
                store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                const hasRole =  store.state.authCheckUserRolesPermissions.user_roles.some(value => value === 'superAdmin');
                const hasPermission =  store.state.authCheckUserRolesPermissions.user_permissions.some(value => value.name === 'bulkPrintingOfReceipt');
                if(hasRole || hasPermission){
                        return true;
                }else{
                        return false;
                }

        }else if(to.path === '/maintenance/edit-return-days'){
                store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                const hasRole =  store.state.authCheckUserRolesPermissions.user_roles.some(value => value === 'superAdmin');
                const hasPermission =  store.state.authCheckUserRolesPermissions.user_permissions.some(value => value.name === 'returnDays');
                if(hasRole || hasPermission){
                        return true;
                }else{
                        return false;
                }

        }else if(to.path === '/maintenance/users' || to.path === '/maintenance/add-permissions' || to.path === '/maintenance/add-permissions/'+to.params.id || to.path === '/maintenance/edit-user/'+to.params.id || to.path === '/maintenance/add-user' || to.path === '/sms/send-sms/to-claim' || to.path === '/sms/sms-message'){
                store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                const hasRole =  store.state.authCheckUserRolesPermissions.user_roles.some(value => value === 'superAdmin');
                if(hasRole){
                        return true;
                }else{
                        return false;
                }

        }else if(to.path === '/print/bulk-printing-of-receipt-holder'){
                store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                const hasRole =  store.state.authCheckUserRolesPermissions.user_roles.some(value => value === 'superAdmin');
                const hasPermission =  store.state.authCheckUserRolesPermissions.user_permissions.some(value => value.name === 'bulkPrintingOfReceipt');
                if(hasRole){
                        return true;
                }else{
                        return false;
                }

        }else if(to.path === '/admin'){
               store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
               return true;
        }
}else{

        store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':true});
        const response = await store.dispatch('fetchAuthUserRolesPermissions');

        if(to.path.startsWith('/dashboard')){
                store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                const hasRole = store.state.authCheckUserRolesPermissions.user_roles.some(value => value === 'superAdmin');
                const hasPermission = store.state.authCheckUserRolesPermissions.user_permissions.some(value => value.name === 'dashboard');

                if(hasRole || hasPermission){
                        return true;
                }else{
                        return false;
                }
        }else if(to.path.startsWith('/request') || to.path === '/print/request-for-assistance-receipt/'+to.params.id || to.path === '/print/bulk-printing-of-receipt-holder'){                        
                
                const hasRole = response['data']['user_roles'].some(value => value === 'superAdmin');
                const hasPermission = response['data']['user_permissions'].some(value => value.name === 'requestAssistance');

                if(hasRole || hasPermission){
                        store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                 
                        return true;
                }else{
                        return false;
                }

        }else if(to.path === '/approve/client' || to.path === '/approve/client/'+to.params.id){
       
                const hasRole = response['data']['user_roles'].some(value => value === 'superAdmin');
                const hasPermission = response['data']['user_permissions'].some(value => value.name === 'viewApproveAssistance');
                if(hasRole || hasPermission){
                        store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                        return true;
                }else{
                        return false;
                }

        }else if(to.path === '/approve/bulk'){

                const hasRole = response['data']['user_roles'].some(value => value === 'superAdmin');
                const hasPermission = response['data']['user_permissions'].some(value => value.name === 'bulkApproveAssistance');
                if(hasRole || hasPermission){
                        store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                        return true;
                }else{
                        return false;
                }
 
        }else if(to.path === '/approve/approve-qr'){

           
                const hasRole = response['data']['user_roles'].some(value => value === 'superAdmin');
                const hasPermission = response['data']['user_permissions'].some(value => value.name === 'approveAssistance');
                if(hasRole || hasPermission){
                        store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                        return true;
                }else{
                        
                        return false;
                }

        }else if(to.path === '/claim/client' || to.path === '/claim/client/'+to.params.id){
        

                const hasRole = response['data']['user_roles'].some(value => value === 'superAdmin');
                const hasPermission = response['data']['user_permissions'].some(value => value.name === 'viewApproveAssistance');
                if(hasRole || hasPermission){
                        store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                        return true;
                }else{
                        return false;
                }


        }else if(to.path === '/claim/bulk'){


                const hasRole = response['data']['user_roles'].some(value => value === 'superAdmin');
                const hasPermission = response['data']['user_permissions'].some(value => value.name === 'bulkClaimAssistance');
                if(hasRole || hasPermission){
                        store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                        return true;
                }else{
                        return false;
                }

        }else if(to.path === '/claim/claim-qr'){


                const hasRole = response['data']['user_roles'].some(value => value === 'superAdmin');
                const hasPermission = response['data']['user_permissions'].some(value => value.name === 'claimAssistance');
                if(hasRole || hasPermission){
                        store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                        return true;
                }else{
                        return false;
                }

       
        }else if(to.path === '/client/information' || to.path === '/client/information/'+to.params.id){

                const hasRole = response['data']['user_roles'].some(value => value === 'superAdmin');
                const hasPermission = response['data']['user_permissions'].some(value => value.name === 'viewClientInformation');
                if(hasRole || hasPermission){
                        store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                        return true;
                }else{
                        return false;
                }

        }else if(to.path === '/client/information/'+to.params.id+'/edit'){
                store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                const hasRole = store.state.authCheckUserRolesPermissions.user_roles.some(value => value === 'superAdmin');
                const hasPermission = store.state.authCheckUserRolesPermissions.user_permissions.some(value => value.name === 'editClientInformation');
                if(hasRole || hasPermission){
                        return true;
                }else{
                        return false;
                }
        
        }else if(to.path === '/client/new-transaction' || to.path === '/client/new-transaction/'+to.params.id+'/beneficiary' || to.path === '/client/new-transaction/'+to.params.id+'/beneficiary/submit'){


                const hasRole = response['data']['user_roles'].some(value => value === 'superAdmin');
                const hasPermission = response['data']['user_permissions'].some(value => value.name === 'newTransaction');
                if(hasRole || hasPermission){
                        store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                        return true;
                }else{
                        return false;
                }

        }else if(to.path === '/transactions/client-transactions' || to.path === '/transactions/client-transaction/'+to.params.id){


                const hasRole = response['data']['user_roles'].some(value => value === 'superAdmin');
                const hasPermission = response['data']['user_permissions'].some(value => value.name === 'viewTransaction');
                if(hasRole || hasPermission){
                        store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                        return true;
                }else{
                        return false;
                }

                
        }else if(to.path === '/transactions/client-transactions/'+to.params.id+'/beneficiary/'+to.params.id2+'/client/'+to.params.id3){

                const hasRole = response['data']['user_roles'].some(value => value === 'superAdmin');
                const hasPermission = response['data']['user_permissions'].some(value => value.name === 'editBeneficiary');
                if(hasRole || hasPermission){
                        store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                        return true;
                }else{
                        return false;
                }

        }else if(to.path === '/transactions/client-transactions/'+to.params.id+'/client/'+to.params.id2+'/add-beneficiary'){
       
                const hasRole = response['data']['user_roles'].some(value => value === 'superAdmin');
                const hasPermission = response['data']['user_permissions'].some(value => value.name === 'addBeneficiary');
                if(hasRole || hasPermission){
                        store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                        return true;
                }else{
                        return false;
                }


        }else if(to.path === '/transactions/report'){

                const hasRole = response['data']['user_roles'].some(value => value === 'superAdmin');
                const hasPermission = response['data']['user_permissions'].some(value => value.name === 'transactionReport');
                if(hasRole || hasPermission){
                        store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                        return true;
                }else{
                        return false;
                }

      
        }else if(to.path === '/transactions/bulk-printing'){

                const hasRole = response['data']['user_roles'].some(value => value === 'superAdmin');
                const hasPermission = response['data']['user_permissions'].some(value => value.name === 'bulkPrintingOfReceipt');
                if(hasRole || hasPermission){
                        store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                        return true;
                }else{
                        return false;
                }

   
        }else if(to.path === '/maintenance/bulk-printing'){

                const hasRole = response['data']['user_roles'].some(value => value === 'superAdmin');
                const hasPermission = response['data']['user_permissions'].some(value => value.name === 'bulkPrintingOfReceipt');
                if(hasRole || hasPermission){
                        store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                        return true;
                }else{
                        return false;
                }

        }else if(to.path === '/maintenance/edit-return-days'){

                const hasRole = response['data']['user_roles'].some(value => value === 'superAdmin');
                const hasPermission = response['data']['user_permissions'].some(value => value.name === 'returnDays');
                if(hasRole || hasPermission){
                        store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                        return true;
                }else{
                        return false;
                }

        }else if(to.path === '/maintenance/users' || to.path === '/maintenance/add-permissions' || to.path === '/maintenance/add-permissions/'+to.params.id || to.path === '/maintenance/edit-user/'+to.params.id || to.path === '/maintenance/add-user' || to.path === '/sms/send-sms/to-claim' || to.path === '/sms/sms-message'){

                const hasRole = response['data']['user_roles'].some(value => value === 'superAdmin');
                if(hasRole){
                        store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                        return true;
                }else{
                        return false;
                }

        }else if(to.path === '/print/bulk-printing-of-receipt-holder'){

                const hasRole = response['data']['user_roles'].some(value => value === 'superAdmin');
                const hasPermission = response['data']['user_permissions'].some(value => value.name === 'bulkPrintingOfReceipt');
                if(hasRole || hasPermission){
                        store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                        return true;
                }else{
                        return false;
                }

        }else if(to.path === '/admin'){
                store.commit('setAuthCheckUserRolesPermissionsLoader',{'authCheckUserRolesPermissionsLoader':false});
                return true;
        }



        // });    
}
}

