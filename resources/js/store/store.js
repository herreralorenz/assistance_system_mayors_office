import axios from 'axios';
import {createStore} from 'vuex';


const store = createStore({
    state () {
      return {
        dashboardLoader:false,
        dashboard:{
          cost:[],
          transactions:[],
          approved:[],
          claimed:[],
          yearly_assistance:[],
          barangay:[],
          assistance_description:[],
          sent_sms:[],
        },
        HTTPSMSUsage:[],
        autoCompleteFullNameBeneficiaryWithRelationship:[],
        SMSMessage:{
          subject:'',
          message:'',
        },
        SMSMessageDetailsLoader:false,
        SMSMessageDetails:[],
        SMSSearch:{
          client:'',
          transaction_id:'',
          date_from: '',
          date_to:'',
        },
        sendSMSTransactionDetailsLoader:false,
        sendSMSTransactionDetails:[],
        userDetailsLoader:false,
        userDetails:[],
        usersSearch:'',
        authCheckUserRolesPermissionsLoader:true,
        authCheckUserRolesPermissions:[],
        userPermissionsCheck:{
          requestAssistance:false,
          approveAssistance:false,
          declineAssistance:false,
          bulkApproveAssistance:false,
          claimAssistance:false,
          unclaimAssistance:false,
          bulkClaimAssistance:false,
          viewClientInformation:false,
          editClientInformation:false,
          deleteClientInformation:false,
          newTransaction:false,
          viewTransaction:false,
          voidTransaction:false,
          editBeneficiary:false,
          transactionReport:false,
          bulkPrintingOfReceipt:false,
          returnDays:false,
          addBeneficiary:false,
          editApproveAssistance:false,
          viewApproveAssistance:false,
          editClaimAssistance:false,
          viewClaimAssistance:false,
          sendSMS:false,
          SMSMessage:false,
          dashboard:false,
        },
        userPermissionsLoader:false,
        userPermissions:[],
        usersSearchLoader:false,
        usersLoader:false,
        users:[],
        returnDaysLoader:false,
        approveAssistanceQRDetails:[],
        approveAssistanceQRDetailsLoader:false,
        claimAssistanceQRDetailsLoader:false,
        claimAssistanceQRDetails:[],
        login:{
          email:'',
          password:'',
        },
        receiptCounter:0,
        selectedReviewTransactionsToPrint:[],
        bulkPrintingOfReceiptLoader:false,
        bulkPrintingOfReceiptDetails:[],
        bulkPrintingOfReceipt:{
          client_fullname:'',
          from_date:'',
          to_date:'',
          transaction_id:'',
        },
        transactionReportLoader:false,
        transactionReportTableLoader:false,
        generateReport:[],
        transactionReport:{
          from_date:'',
          to_date:'',
        },
        transactionToast:false,
        clientToast:false,
        claimTransactionToast:false,
        approveTransactionToast:false,
        requestTransactionToast:false,
        printDetailsLoader:false,
        printDetails:[],
        beneficiaryCheckerDetails:[],
        clientCheckerDetails:[],
        autoCompleteFullNameClient:[],
        autoCompleteFullNameBeneficiary:[],
        clientTransactionAddBeneficiaryLoader:false,
        clientTransactionEditBeneficiaryLoader:false,
        clientTransctionBeneficiary:[],
        clientTransactionDetailsLoader:false,
        clientTransactionDetails:[],
        transactionSearch:{
          client:"",
          transaction_id:"",
        },
        clientTransactionTableLoader:false,
        clientTransactionTable: [],
        sameAsClient:false,
        clientNewTransactionSubmitLoader:false,
        clientNewTransaction:[],
        clientNewTransactionBeneficiaryLoader:false,
        clientNewTransactionTable:[],
        clientNewTransactionLoader:false,
        editClientDetailsLoader:false,
        clientDetailsLoader:false,
        clientInformationTableLoader:false,
        clientSearch:'',
        clientDetails:[],
        clientInformationTable:[],
        bulkClaimAssistanceTable:[],
        bulkClaimAssistanceTableLoader:false,
        claimant:{
          claim_date:'',
          last_name:'',
          first_name:'',
          middle_name:'',
          suffix:'',
          contact_number:'',
          remarks:'',
        },
        suffixSeeder:[],
        claimClientDetailsLoader:false,
        claimClientDetails:[],
        claimAssistanceTableLoader:false,
        bulkApproveAssistanceTableLoader:false,
        claimAssistanceTable:[],
        bulkApproveAssistanceTable:[],
        approveClientDetails:[],
        approveClientDetailsLoader:false,
        approveAssistanceTable:[],
        submitLoader: false,
        approveAssistanceTableLoader:false,
        searchLoader:false,
        formSeederLoader: false,
        formSeeder:[],
        returnDays:'',
        headerText:'',
        progressBarWidth: '',
        progressActive: 0,
        sameAsAboveFields: false,
        transactionApproveSearch:{
          client:'',
          transaction_id:'',
          date_from: '',
          date_to:'',
        },
        transactionClaimSearch:{
          client:'',
          transaction_id:'',
          date_from: '',
          date_to:'',
        },
        typeOfAssistance:{
          agency:'',
          agency_program:'',
          type_of_assistance:'',
          description_of_assistance:'',
          other_description_of_assistance:'',
          reason_of_assistance:'',
          due_date:'',
          category:'',
          hospital_name:'',
          maip_code:'',
          hospital_type:'',
          transaction_date:'',
        },
        // beneficiary:{
        //   last_name:'',
        //   first_name:'',
        //   middle_name:'',
        //   suffix:'',
        //   birthdate:'',
        //   age:'',
        //   sex:'',
        //   civil_status:'',
        //   street:'',
        //   region:'4A',
        //   barangay:'',
        //   city:'GENERAL TRIAS CITY',
        //   province:'CAVITE',
        //   precint:'',
        //   contact_number:'',
        //   id_type:'',
        //   id_number:'',
        //   other_id_type:'',
        //   occupation:'',
        //   monthly_income:'',
        //   beneficiary_image:'',
        // },
        // client:{
        //   last_name:'',
        //   first_name:'',
        //   middle_name:'',
        //   suffix:'',
        //   birthdate:'',
        //   age:'',
        //   sex:'',
        //   civil_status:'1',
        //   region:'4A',
        //   street:'',
        //   barangay:'',
        //   city:'GENERAL TRIAS CITY',
        //   province:'CAVITE',
        //   precint:'',
        //   contact_number:'',
        //   id_type:'',
        //   id_number:'',
        //   other_id_type:'',
        //   occupation:'',
        //   monthly_income:'',
        //   relationship:'',
        //   client_image:'',
        // },
        beneficiary:{
          last_name:'',
          first_name:'',
          middle_name:'',
          suffix:'',
          birthdate:'',
          age:'',
          sex:'',
          civil_status:'',
          street:'',
          region:'4A',
          barangay:'',
          city:'GENERAL TRIAS CITY',
          province:'CAVITE',
          precint:'',
          contact_number:'',
          id_type:'',
          id_number:'',
          other_id_type:'',
          occupation:'',
          monthly_income:'',
          beneficiary_image:'',
        },
        client:{
          last_name:'',
          first_name:'',
          middle_name:'',
          suffix:'',
          birthdate:'',
          age:'',
          sex:'',
          civil_status:'',
          region:'4A',
          street:'',
          barangay:'',
          city:'GENERAL TRIAS CITY',
          province:'CAVITE',
          precint:'',
          contact_number:'',
          id_type:'',
          id_number:'',
          other_id_type:'',
          occupation:'',
          monthly_income:'',
          relationship:'',
          client_image:'',
        },
        approveDeclineClient:{
          amount:'',
          remarks:'',
          date_approve_decline:'',
        },
      }
    },
    mutations: {
        setDashboardLoader(state, payload){
          state.dashboardLoader = payload.dashboardLoader;
        },
        setDashboard(state, payload){
          state.dashboard = payload.dashboard;
        },
        setHTTPSMSUsage(state, payload){
          state.HTTPSMSUsage = payload.HTTPSMSUsage;
        },
        setAutoCompleteFullNameBeneficiaryWithRelationship(state, payload){
          state.autoCompleteFullNameBeneficiaryWithRelationship = payload.autoCompleteFullNameBeneficiaryWithRelationship;
        },
        setSMSMessage(state, payload){
          state.SMSMessage = payload.SMSMessage;
        },
        setSMSMessageDetailsLoader(state, payload){
          state.SMSMessageDetailsLoader = payload.SMSMessageDetailsLoader;
        },
        setSMSMessageDetails(state, payload){
          state.SMSMessageDetails = payload.SMSMessageDetails;
        },
        setSMSSearch(state, payload){
          state.SMSSearch = payload.SMSSearch;
        },
        setSendSMSTransactionDetailsLoader(state, payload){
          state.sendSMSTransactionDetailsLoader = payload.sendSMSTransactionDetailsLoader;
        },
        setSendSMSTransactionDetails(state, payload){
          state.sendSMSTransactionDetails = payload.sendSMSTransactionDetails;
        },
        setUserDetailsLoader(state, payload){
          state.userDetailsLoader = payload.userDetailsLoader;
        },
        setUserDetails(state, payload){
          state.userDetails = payload.userDetails;
        },
        setUsersSearchLoader(state,payload){
          state.usersSearchLoader = payload.usersSearchLoader; 
        },
        setUsersSearch(state,payload){
          state.usersSearch = payload.usersSearch;
        },
        setAuthCheckUserRolesPermissionsLoader(state, payload){
          state.authCheckUserRolesPermissionsLoader = payload.authCheckUserRolesPermissionsLoader;
        },
        setAuthCheckUserRolesPermissions(state, payload){
          state.authCheckUserRolesPermissions = payload.authCheckUserRolesPermissions;
        },
        setUserPermissionsCheck(state, payload){
          state.userPermissionsCheck = payload.userPermissionsCheck;
        },
        setUserPermissionsLoader(state, payload){
          state.userPermissionsLoader = payload.userPermissionsLoader;
        },
        setUserPermissions(state, payload){
          state.userPermissions = payload.userPermissions;
        },
        setUsersLoader(state, payload){
          state.usersLoader = payload.usersLoader;
        },
        setUsers(state,payload){
          state.users = payload.users;
        },
        setReturnDaysLoader(state, payload){
          state.returnDaysLoader = payload.returnDaysLoader;
        },
        setApproveAssistanceQRDetails(state, payload){
          state.approveAssistanceQRDetails = payload.approveAssistanceQRDetails
        },
        setApproveAssistanceQRDetailsLoader(state,payload){
          state.approveAssistanceQRDetailsLoader = payload.approveAssistanceQRDetailsLoader;
        },
        setClaimAssistanceQRDetailsLoader(state, payload){
          state.claimAssistanceQRDetailsLoader = payload.claimAssistanceQRDetailsLoader;
        },
        setClaimAssistanceQRDetails(state, payload){
          state.claimAssistanceQRDetails = payload.claimAssistanceQRDetails;
        },
        setLogin(state, payload){
          state.login = payload.login;
        },
        setReceiptCounter(state,payload){
          state.receiptCounter = payload.receiptCounter;
        },
        setSelectedReviewTransactionsToPrint(state, payload){
          state.selectedReviewTransactionsToPrint = payload.selectedReviewTransactionsToPrint;
        },
        setBulkPrintingOfReceiptLoader(state, payload){
          state.bulkPrintingOfReceiptLoader = payload.bulkPrintingOfReceiptLoader;
        },
        setBulkPrintingOfReceipt(state, payload){
          state.bulkPrintingOfReceipt = payload.bulkPrintingOfReceipt;
        },
        setBulkPrintingOfReceiptDetails(state, payload){
          state.bulkPrintingOfReceiptDetails = payload.bulkPrintingOfReceiptDetails;
        },
        setTransactionReportLoader(state, payload){
          state.transactionReportLoader = payload.transactionReportLoader;
        },
        setTransactionReportTableLoader(state, payload){
          state.transactionReportTableLoader = payload.transactionReportTableLoader;
        },
        setGenerateReport(state,payload){
          state.generateReport = payload.generateReport;
        },
        setTransactionReport(state, payload){
          state.transactionReport = payload.transactionReport
        },
        setTransactionToast(state, payload){
          state.transactionToast = payload.transactionToast;
        },
        setClientToast(state, payload){
          state.clientToast = payload.clientToast;
        },
        setClaimTransactionToast(state, payload){
          state.claimTransactionToast = payload.claimTransactionToast;
        },
        setApproveTransactionToast(state,payload){
          state.approveTransactionToast = payload.approveTransactionToast;
        },
        setRequestTransactionToast(state, payload){
          state.requestTransactionToast = payload.requestTransactionToast;
        },
        setPrintDetailsLoader(state, payload){
          state.printDetailsLoader = payload.printDetailsLoader;
        },
        setPrintDetails(state, payload){
          state.printDetails = payload.printDetails;
        },
        setBeneficiaryCheckerDetails(state, payload){
          state.beneficiaryCheckerDetails = payload.beneficiaryCheckerDetails;
        },
        setClientCheckerDetails(state, payload){
          state.clientCheckerDetails = payload.clientCheckerDetails;
        },
        setAutoCompleteFullNameClient(state, payload){
          state.autoCompleteFullNameClient = payload.autoCompleteFullNameClient;
        },
        setAutoCompleteFullNameBeneficiary(state, payload){
          state.autoCompleteFullNameBeneficiary = payload.autoCompleteFullNameBeneficiary;
        },
        setClientTransactionAddBeneficiaryLoader(state, payload){
          state.clientTransactionAddBeneficiaryLoader = payload.clientTransactionAddBeneficiaryLoader;
        },
        setClientTransactionEditBeneficiaryLoader(state, payload){
          state.clientTransactionEditBeneficiaryLoader = payload.clientTransactionEditBeneficiaryLoader;
        },
        setClientTransactionBeneficiary(state, payload){
          state.clientTransactionBeneficiary = payload.clientTransactionBeneficiary;
        },
        setClientTransactionDetailsLoader(state, payload){
          state.clientTransactionDetailsLoader = payload.clientTransactionDetailsLoader;
        },
        setClientTransactionDetails(state,payload){
          state.clientTransactionDetails = payload.clientTransactionDetails;
        },
        setTransactionSearch(state, payload){
          state.transactionSearch = payload.transactionSearch;
        },
        setClientTransactionTableLoader(state, payload){
          state.clientTransactionTableLoader = payload.clientTransactionTableLoader;
        },
        setClientTransactionTable(state, payload){
          state.clientTransactionTable = payload.clientTransactionTable;
        },
        setSameAsClient(state, payload){
          state.sameAsClient = payload.sameAsClient;
        },
        setClientNewTransactionSubmitLoader(state, payload){
          state.clientNewTransactionSubmitLoader = payload.clientNewTransactionSubmitLoader;
        },
        setClientNewTransaction(state, payload){
          state.clientNewTransaction = payload.clientNewTransaction;
        },
        setClientNewTransactionBeneficiaryLoader(state, payload){
          state.clientNewTransactionBeneficiaryLoader = payload.clientNewTransactionBeneficiaryLoader;
        },
        setClientNewTransactionTable(state, payload){
          state.clientNewTransactionTable = payload.clientNewTransactionTable;
        },
        setclientNewTransactionLoader(state, payload){
          state.clientNewTransactionLoader = payload.clientNewTransactionLoader;
        },
        setEditClientDetailsLoader(state, payload){
          state.editClientDetailsLoader = payload.editClientDetailsLoader;
        },
        setClientDetailsLoader(state,payload){
          state.clientDetailsLoader = payload.clientDetailsLoader;
        },
        setClientSearch(state, payload){
          state.clientSearch = payload.clientSearch;
        },  
        setClientDetails(state,payload){
          state.clientDetails = payload.clientDetails
        },
        setClientInformationTableLoader(state,payload){
          state.clientInformationTableLoader = payload.clientInformationTableLoader;
        },  
        setClientInformationTable(state, payload){
          state.clientInformationTable = payload.clientInformationTable;
        },
        setBulkClaimAssistanceTable(state, payload){
          state.bulkClaimAssistanceTable = payload.bulkClaimAssistanceTable;
        },
        setBulkClaimAssistanceTableLoader(state,payload){
          state.bulkClaimAssistanceTableLoader = payload.bulkClaimAssistanceTableLoader;
        },
        setSuffixSeeder(state,payload){
          state.suffixSeeder = payload;
        },
        setClaimant(state,payload){
          state.claimant = payload.claimant;
        },
        setClaimClientDetailsLoader(state,payload){
          state.claimClientDetailsLoader = payload.claimClientDetailsLoader;
        },
        setClaimClientDetails(state, payload){
          state.claimClientDetails = payload.claimClientDetails;
        },
        setClaimAssistanceTableLoader(state, payload){
          state.claimAssistanceTableLoader = payload.claimAssistanceTableLoader;
        },
        setTransactionClaimSearch(state,payload){
          state.transactionClaimSearch = payload.transactionClaimSearch;
        },
        setClaimAssistanceTable(state, payload){
          state.claimAssistanceTable = payload.claimAssistanceTable
        },
        setBulkApproveAssistanceTable(state, payload){
          state.bulkApproveAssistanceTable = payload.bulkApproveAssistanceTable;
        },
        setSearchLoader(state,payload){
          state.searchLoader = payload.searchLoader;
        },
        setTransactionApproveSearch(state,payload){
          state.transactionApproveSearch = payload.transactionApproveSearch;
        },
        setApproveDeclineClient(state,payload){
          state.approveDeclineClient = payload.approveDeclineClient;
        },
        setApproveClientLoader(state, payload){
          state.approveClientDetailsLoader = payload.approveClientDetailsLoader;
        },
        setApproveClientDetails(state, payload){
          state.approveClientDetails = payload.approveClientDetails;
        },
        setApproveAssistanceTableLoader(state,payload){
          state.approveAssistanceTableLoader = payload.approveAssistanceTableLoader;
        },
        setApproveAssistanceTable(state, payload){
          state.approveAssistanceTable = payload.approveAssistanceTable;
        },
        setSubmitLoader(state, payload){
          state.submitLoader = payload.submitLoader;
        },
        setTransactionDate(state, payload){
          state.typeOfAssistance.transactionDate = payload.transactionDate;
        },
        setFormSeederLoader(state,payload){
          state.formSeederLoader = payload.formSeederLoader;
        },
        setTypeOfAssistance(state, payload){
          state.typeOfAssistance = payload.typeOfAssistance
        },
        setFormSeeder(state,payload){
          state.formSeeder = payload;
        },
        setBeneficiaryImage(state,payload){
          state.beneficiary.beneficiary_image = payload.beneficiary_image;
        },
        setClientImage(state,payload){
          state.client.client_image = payload.client_image;
        },
        setReturnDays(state,payload){
          state.returnDays = payload.returnDays;
        },
        setHeaderText(state,payload){
          state.headerText = payload.headerText;
        },
        setSameAsAboveFields(state,payload){
          state.sameAsAboveFields = payload.sameAsAboveFields;
        },
        setProgressBarWidth(state,payload){
          state.progressBarWidth = payload.progressBarWidth;
        },
        setProgressActive(state,payload){
          state.progressActive = payload.progressActive;
        },
        setBeneficiary(state,payload){
          state.beneficiary = payload.beneficiary; 
        },
        setClient(state,payload){
          state.client = payload.client;
        },
        setBulkApproveAssistanceTableLoader(state,payload){
          state.bulkApproveAssistanceTableLoader = payload.bulkApproveAssistanceTableLoader;
        }
    },
    actions: {
      async fetchDashboardSentSMS({commit}, data){
        try{
          const response = await axios.get('/api/dashboard-fetch-sent-sms',{
            withCredentials:true
          });
          
           this.state.dashboard.sent_sms = response.data;
          return response;
        }catch(error){
          return await Promise.reject(error)
        }
      },
      async fetchDashboardAssistanceDescription({commit}, data){
        try{
          const response = await axios.get('/api/dashboard-fetch-assistance-description', {
             withCredentials: true,
          });

          this.state.dashboard.assistance_description = response.data;

          return response;
        }catch(error){
          return await Promise.reject(error);
        }
      },
      async fetchDashboardBarangay({commit}, data){
        try{
          const response = await axios.get('/api/dashboard-fetch-barangay',{
            withCredentials: true,
          });

          this.state.dashboard.barangay = response.data;

          return response;
        }catch(error){
          return await Promise.reject(error);
        }
      },
      async fetchDashboardYearlyAssistance({commit}, data){
        try{
          const response = await axios.get('/api/dashboard-fetch-yearly-assistance',{
            withCredentials: true,
          });

          this.state.dashboard.yearly_assistance = response.data;
          return response;
        }catch(error){
          return await Promise.reject(error);
        }
      },
      async fetchDashboardClaimed({commit},data){
        try{
          const response = await axios.get('/api/dashboard-fetch-claimed',{
            withCredentials:true,
          });

          this.state.dashboard.claimed = response.data;

          return response;
        }catch(error){
          return await Promise.reject(error);
        }
      },
      async fetchDashboardApproved({commit}, data){
        try{
          const response  = await axios.get('/api/dashboard-fetch-approved',{
            withCredentials:true,
          });

          this.state.dashboard.approved = response.data
          return response;
        }catch(error){
          return await Promise.reject(error);
        }
      },
      async fetchDashboardTransactions({commit}, data){
        try{
          const response = await axios.get('/api/dashboard-fetch-transactions',{
            withCredentials:true,
          });

          this.state.dashboard.transactions = response.data;

          return response;
        }catch(error){
          return await Promise.reject(error);
        }
      },
      async fetchDashboardCost({commit},data){
        try{
          const response = await axios.get('/api/dashboard-fetch-cost',{
            withCredentials:true,
          });

          this.state.dashboard.cost = response.data;
          return response;
        }catch(error){
          return await Promise.reject(error);
        }
      },
      async fetchHTTPSMSUsage({commit},data){
        try{
          const response = await axios.get('/api/http-sms-usage',{
            withCredentials:true,
          });
          commit('setHTTPSMSUsage',{'HTTPSMSUsage':response.data});
          return response;
        }catch(error){
          return await Promise.reject(error);
        }
      },
      async autoCompleteFullNameBeneficiaryWithRelationship({commit}, data){
        try{
          const response = await axios.get('/api/autocomplete-fullname-client-with-relationship/'+data.id2,{
            withCredentials:true,
            params:{
              data
            }
          });
          commit('setAutoCompleteFullNameBeneficiaryWithRelationship',{'autoCompleteFullNameBeneficiaryWithRelationship': response.data});
          return response;
        }catch(error){
          return await Promise.reject(error);
        }
      },
      async sendBulkSMS({commit},data){
        try{
          const response = await axios.post('/api/send-bulk-sms',{'data':data},{
            withCredentials:true,
          });

          return response
        }catch(error){
          return await Promise.reject(error);
        }
      },
      async addMessage({commit}, data){
        try{
          const response = await axios.post('/api/add-sms-message',{'data':data},{
            withCredentials:true,
          });

          return response;
        }catch(error){
          return await Promise.reject(error);
        }
      },
      async deleteMessage({commit},data){
        try{
          const response = await axios.delete('/api/delete-sms-message/'+data.id,{'data':data},{
            withCredentials:true,
          });

          return response;
        }catch(error){
          return await Promise.reject(error);
        }
      },
      async updateMessage({commit}, data){
        try{
          const response = await axios.patch('/api/update-sms-message/'+data.id,{'data':data},{
            withCredentials:true,
          });

          return response;
        }catch(error){
          return await Promise.reject(error);
        }
      },
      async fetchSMSMessageDetails({commit}, data){
        try{
          const response = await axios.get('/api/get-sms-message',{
            withCredentials:true,
          });
          commit('setSMSMessageDetails',{'SMSMessageDetails':response.data});
          return response;
        }catch(error){
          return await Promise.reject(error);
        }
      },
      async sendBulkSMS({commit},data){
        try{
          const response =  await axios.post('/api/send-bulk-sms',{'data':data},{
            withCredentials:true
          });

          return response;
        }catch(error){
          return await Promise.reject(error);
        }
      },
      async searchSendSMSTransactionDateRequest({commit},data){
        try {
          return await axios.get('/api/search-sms-transaction-details-transaction-date', {
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params: {
              data, // Spread the data object into query parameters
            },
          });
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async searchSendSMSTransactionID({commit},data){
        try {
          return await axios.get('/api/search-sms-transaction-details-transaction-id', {
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params: {
              data, // Spread the data object into query parameters
            },
          });
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async searchSendSMSTransactionClient({commit},data){
        try {
          return await axios.get('/api/search-sms-transaction-details-client', {
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params: {
              data, // Spread the data object into query parameters
            },
          });
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async fetchSendSMSTransactionDetails({commit},data){
        const response = await axios.get('/api/get-sms-transaction-details',{
          withCredentials:true
        });
        commit('setSendSMSTransactionDetails',{'sendSMSTransactionDetails':response.data});
        return response
      },
      async deleteUserDetails({commit},data){
        try{
          const response = await axios.delete('/api/delete-user-details/'+data.id,{'data':data},{
            withCredentials:true
          });

          return response;
        }catch(response){
          return await Promise.reject(error);
        }
      },
      async addUserDetails({commit},data){
        try{
          const response = await axios.post('/api/add-user-details',{'data':data},{
            withCredentials:true
          });

          return response;
        }catch(response){
          return await Promise.reject(error);
        }
      },
      async updateUserDetails({commit},data){
        try{
          const response = await axios.patch('/api/update-user-details/'+data.id,{'data':data},{
            withCredentials:true
          });

          return response;
        }catch(response){
          return await Promise.reject(error);
        }
      },
      async searchUserRoles({commit},data){
      try{
        const response = await axios.get('/api/search-user-roles',{
          withCredentials:true,
          params:{
            data
          }
        });
        commit('setUsers',{'users':response.data});
        return response;
      }catch(error){
        return await Promise.reject(error);
      }
      },
      async fetchAuthUserRolesPermissions({commit},data){
        try{
          const response = await axios.get('/api/get-auth-user-roles-permissions',{
            withCredentials:true
          });
          commit('setAuthCheckUserRolesPermissions',{'authCheckUserRolesPermissions':response.data});
      
          return response;
        }catch(error){
          return await Promise.reject(error);
        }
      },
      async setUserPermissions({commit},data){
        try{
          const response = await axios.post('/api/set-user-permissions/'+data.id,{'data':data},{
            withCredentials:true,
          });

          return response;
        }catch(error){
          return await Promise.reject(error);
        }
      },
      async fetchUserPermissions({commit}, data){
        try{
          const response = await axios.get('/api/get-user-permissions/'+data.id,{
            withCredentials:true,
          });
          commit('setUserPermissions',{'userPermissions':response.data});
          return response;
        }catch(error){
          return await Promise.reject(error);
        }
      },
      async fetchUserDetails({commit},data){
        try{
          const response = await axios.get('/api/get-user-details/'+data.id,{
            withCredentials:true,
          });
          commit('setUserDetails',{'userDetails':response.data});
          return response;
        }catch(error){
          return await Promise.reject(error);
        }
      },
      async fetchUsers({commit},data){
        try{
          const response = await axios.get('/api/get-users',{
            withCredentials:true,
          });
          commit('setUsers',{'users':response.data});
          return response;
        }catch(error){
          return await Promise.reject(error);
        }
      },
      async updateReturnDays({commit},data){
        try{
          const response = await axios.patch('/api/update-return-days',{'data':data},{
            withCredentials:true,
          });

          return response;
        }catch(error){
          return await Promise.reject(error)
        }
      },
      async fetchReturnDays({commit}, data){
        try{
          const response = await axios.get('/api/get-return-days',{
            withCredentials:true
          });

          commit('setReturnDays',{'returnDays':response.data.return_days});
          return response;
        }catch(error){
          return await Promise.reject(error);
        }
      },
      async approveAssistanceQR({commit}, data){
        try{
          const response = await axios.post('/api/client-approve-qr',{'data':data},{
            withCredentials:true
          });
          return response;
        }catch(error){
          return await Promise.reject(error);
        }
   
      },
      async fetchAssistanceApproveQR({commit}, data){
        try{
          const response = await axios.get('/api/get-client-approve-qr',{
            withCredentials:true,
            params:{
              data,
            }
          });
          commit('setApproveAssistanceQRDetails',{'approveAssistanceQRDetails':response.data});
         
          return response;
        }catch(error){
          return await Promise.reject(error);
        }
      },
      async claimAssistanceQR({commit}, data){
        try{
          const response = await axios.patch('/api/client-claim-qr',{'data':data},{
            withCredentials: true,
          })

          return response;
        }catch(error){
          return await Promise.reject(error);
        }
      },
      async fetchAssistanceClaimQR({commit}, data){
        try{
          const response = await axios.get('/api/get-client-claim-qr',{
            withCredentials: true,
            params:{
              data,
             },
            
          });
          commit('setClaimAssistanceQRDetails',{'claimAssistanceQRDetails':response.data});
  
          return response;
        }catch(error){
          return await Promise.reject(error);
        }
      },
      async logout({commit}, data){
        try{
          const response = await axios.post('/logout',{data},{
            withCredentials: true,
           
          });
          
          return response;
        }catch(error){
          return await Promise.reject(error)
        }
      },
      async login({commit},data){
        try{



          const response = await axios.post('/login',{
            data
          },
          { 
            withCredentials: true,
          //   headers: {
          //     'X-CSRF-TOKEN': data.headers['X-CSRF-TOKEN'],  // ✅ Ensure CSRF token is sent
          //     'X-Requested-With': 'XMLHttpRequest'  // ✅ Mark it as an AJAX request
          // },
          });
          
        
          return response;
        }catch(error){
          
          return await Promise.reject(error)
        }
      },
      async searchBulkPrintingOfReceiptDate({commit},data){
        try{
          const response = await axios.get('/api/bulk-printing-of-receipt-date',{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params:{
            data,
           },
          
          });
          commit('setBulkPrintingOfReceiptDetails',{'bulkPrintingOfReceiptDetails':response.data});
          return response;
        }catch(error){
          return await Promise.reject(error)
        }
      },
      async searchBulkPrintingOfReceiptTransactionID({commit},data){
        try{
          const response = await axios.get('/api/bulk-printing-of-receipt-transaction-id',{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params:{
              data,
              
            }
          });
          commit('setBulkPrintingOfReceiptDetails',{'bulkPrintingOfReceiptDetails':response.data});
          return response;
        }catch(error){
          return await Promise.reject(error)
        }
      },
      async searchBulkPrintingOfReceiptClient({commit},data){
        try{
          const response = await axios.get('/api/bulk-printing-of-receipt-client',{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params:{
              data,
            }
        });
          commit('setBulkPrintingOfReceiptDetails',{'bulkPrintingOfReceiptDetails':response.data});
          return response;
        }catch(error){
          return await Promise.reject(error)
        }
      },
      async downloadReport({commit},data){
        try{
          const response = await axios.get('/api/download-report',{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params:{
              data,

            }
        });
        }catch(error){
          return await Promise.reject(error);
        }
      },  
      async generateReport({commit},data){
        try{
          const response = await axios.get('/api/transaction-report',{
            withCredentials: true,
            params:{
              data,
        
            }
        });

          commit('setGenerateReport',{'generateReport':response.data});
          return response;
        }catch(error){
          return await Promise.reject(error);
        }
      },
      async deleteTransaction({commit},data){
        try{

          const response = await axios.delete('/api/delete-transaction/'+data.id,{'data':data},{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
  
          });

          return response;
        }catch(error){
          return await Promise.reject(error);
        }
      },
      async updateClaimedClient({commit},data){
        try{

          const response = await axios.patch('/api/update-claim-client/'+data.id,{'data':data},{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
      
          });

          return response;
        }catch(error){
          return await Promise.reject(error);
        }

      },
      async updateApprovedClient({commit},data){
        try{
          const response = await axios.patch('/api/update-approve-client/'+data.id,{'data':data},{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
          });
    
          return response;
        }catch(error){
          return await Promise.reject(error)
        }
      },
      async fetchPrintDetails({commit},data){
        try{
          const response = await axios.get('/api/get-request-of-assistance-receipt/'+data.id,{
            withCredentials: true,
          });
          commit('setPrintDetails',{'printDetails':response.data});
          return response;
        }catch(error){
          return await Promise.reject(error)
        }
      },
      async fetchBeneficiaryChecker({commit},data){
        try{
          const response = await axios.post('/api/check-beneficiary-assistance',{'data':data},{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            
        });
          commit('setBeneficiaryCheckerDetails',{'beneficiaryCheckerDetails':response.data});
          return response;
        }catch(error){
          console.log(error);
          return await Promise.reject(error);
        }
      },
      async fetchClientChecker({commit},data){
        try{
          const response = await axios.post('/api/check-client-assistance',{'data':data},{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
          });
          commit('setClientCheckerDetails',{'clientCheckerDetails':response.data});
          return response;
        }catch(error){
          console.log(error);
          return await Promise.reject(error);
        }
      },
      async autoCompleteFullNameClient({commit},data){
        try{
          const response = await axios.get('/api/autocomplete-fullname-client',{
            withCredentials: true,
            params:{
              data
            },
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },

          });
          commit('setAutoCompleteFullNameClient',{'autoCompleteFullNameClient':response.data});
          
          return response;
        }catch(error){
          console.log(error);
          return await Promise.reject(error);
        }
      },
      async autoCompleteFullNameBeneficiary({commit},data){
        try{
          const response = await axios.get('/api/autocomplete-fullname-beneficiary',{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params:{  
              data,
              
            }
          });
          // commit('setAutoCompleteFullName',{'autoCompleteFullName':response.data.map(item => ({
          //   id: item.id,
          //   full_name: item.full_name, // Unique key (e.g., database ID)
          //   birthdate: item.birthdate
          // }))});
          commit('setAutoCompleteFullNameBeneficiary',{'autoCompleteFullNameBeneficiary':response.data});
          
          return response;
        }catch(error){
          console.log(error);
          return await Promise.reject(error);
        }
      },
      async voidTransaction({commit},data){
        try{
          const response = await axios.delete('/api/void-transaction/'+data.id,{'data':data},{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
          });

          return response;
        }catch(error){
          console.log(error);
          return await Promise.reject(error);
        }
      },
      async addClientTransactionBeneficiary({commit},data){
        try{
        const response = await axios.post('/api/add-client-transaction-beneficiary/'+data.id+'/client/'+data.id2,{'data':data},{
          withCredentials: true,
          headers: {
            //'Authorization': `Bearer ${localStorage.getItem('token')}`
          },
        });
        return response;
        }catch(error){
          console.log(error);
          return await Promise.reject(error);
        }
      },
      async updateClientTransactionAssistance({commit}, data){
        try{
          const response =  await axios.patch('/api/update-client-transaction-assistance/'+data.id,{'data':data},{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
       
          });

          return response;
        }catch(error){
          console.log(error);
          return await Promise.reject(error);
        }
      },
      async updateClientTransactionBeneficiary({commit},data){
        try{
        const response = await axios.patch('/api/update-client-transaction-beneficiary/'+data.id+'/beneficiary/'+data.id2+'/client/'+data.id3,{'data':data},{
          withCredentials: true,
          headers: {
            //'Authorization': `Bearer ${localStorage.getItem('token')}`
          },
          
        });
        return response;
        }catch(error){
          console.log(error);
          return await Promise.reject(error);
        }
      },
      async fetchClientTransactionBeneficiary({commit},data){
        try{
        const response = await axios.get('/api/get-client-transaction-beneficiary/'+data.id+'/beneficiary/'+data.id2+'/client/'+data.id3,{
          withCredentials: true,
          headers: {
            //'Authorization': `Bearer ${localStorage.getItem('token')}`
          },
        });
        commit('setClientTransactionBeneficiary',{'clientTransactionBeneficiary':response.data});
        return response;
        }catch(error){
          console.log(error);
          return await Promise.reject(error);
        }
      },
      async fetchClientTransactionDetails({commit}, data){
        try{
          const response = await axios.get('/api/get-client-transaction-details/'+data.id,{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
          });
          commit('setClientTransactionDetails',{'clientTransactionDetails':response.data});
          return response;
        }catch(error){
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async searchTransactionIDTransaction({commit}, data){
        try{
         const response = await axios.get('/api/search-transaction-id-transaction',{
          withCredentials: true,
          headers: {
            //'Authorization': `Bearer ${localStorage.getItem('token')}`
          },
          params: {
            data
          }
        });
         commit('setClientTransactionTable',{'clientTransactionTable':response.data});
         return response;
        }catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async searchClientTransaction({commit}, data){
        try{
         const response = await axios.get('/api/search-client-transaction',{
          withCredentials: true,
          headers: {
            //'Authorization': `Bearer ${localStorage.getItem('token')}`
          },
          params: {data}
        });
         commit('setClientTransactionTable',{'clientTransactionTable':response.data});
         return response;
        }catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async fetchClientTransactionTable({commit},data){
        try{
          const response = await axios.get('/api/client-transaction-table',{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
          });
          commit('setClientTransactionTable',{'clientTransactionTable':response.data});
          return response;
        }catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
       
      },
      async newTransaction({commit},data){
        try{
          const response = await axios.post('/api/new-transaction/'+data.clientID,{'data':data},{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            data,
          });
          return response;
        }catch( error){
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async fetchClientNewTransaction({commit},data){
        try{
          const response = await axios.get('/api/get-client-new-transaction/'+data.clientID,{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
          });
          commit('setClientNewTransaction',{clientNewTransaction:response.data});
          return response;
        }catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
       
      },
      async fetchClientNewTransactionTable({commit},data){
        try{
          const response = await axios.get('/api/get-client-new-transaction-table',{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params:{
              data
            }
          });
          commit('setClientNewTransactionTable',{'clientNewTransactionTable':response.data});
          return response;
        }catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },  
      async deleteClient({commit},data){
        try{ 
          const response = await axios.delete('/api/delete-client-details/'+data.clientID,{'data':data},{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },

          });
          return response;
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async updateClient({commit},data){
        try {
          const response = await axios.patch('/api/update-client-details/'+data.clientID,{'data':data}, {
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
    
          });
          return response;
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async fetchClientImage({commit},data){
        try {
          const response = await axios.get('/api/get-client-image', {
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params: {
              data,
            
            }
          });
          return response;
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async fetchEditClientDetails({commit}, data){
        try {
          const response = await axios.get('/api/get-client-details/'+data.id+"/edit",{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params:{
              data,

            }
          });
          commit('setClientDetails',{'clientDetails':response.data});
          return response;
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async fetchClientDetails({commit}, data){
        try {
          const response = await axios.get('/api/get-client-details/'+data.id,{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params:{
              data,
       
            }
          });
          commit('setClientDetails',{'clientDetails':response.data});
          return response;
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async searchClient({commit}, data){
        try {
          const response = await axios.get('/api/search-client',{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params:{
              data,
     
            }
          });
          commit('setClientInformationTable',{'clientInformationTable':response.data});
          return response;
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async fetchClientDetailsTable({commit}, data){
        try {
          const response = await axios.get('/api/get-client-information-table',{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
          });
          commit('setClientInformationTable',{'clientInformationTable':response.data});
          return response;
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async bulkClaim({commit},data){
        try {
          return await axios.patch('/api/bulk-claim', {'data':data},{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
     
          });
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async searchBulkTransactionClaimDateRequest({commit},data){
        try {
          return await axios.get('/api/search-transaction-date-bulk-claim', {
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params: {
              data, // Spread the data object into query parameters
            },
          });
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async searchBulkTransactionClaimTransactionID({commit},data){
        try {
          return await axios.get('/api/search-transaction-id-bulk-claim', {
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params: {
              data, // Spread the data object into query parameters
            },
          });
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async searchBulkTransactionClaimClient({commit},data){
        try {
          return await axios.get('/api/search-client-bulk-claim', {
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params: {
              data, // Spread the data object into query parameters
            },
          });
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async fetchBulkClaimAssistanceTable({commit}, data){
        try{
          const response = await axios.get('/api/bulk-claiming-of-assistance-table',{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
          });
          commit('setBulkClaimAssistanceTable', { 'bulkClaimAssistanceTable': response.data });
          this.state.claimant.claimDate = response.data.todayDate;
          return response;
        }catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async unclaimClient({commit}, data){
        try{
          const response = await axios.patch('/api/unclaim-client/'+data.id,{'data':data},{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
   
          });
          return response;
        }catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async claimClient({commit}, data){
        try{
          const response = await axios.post('/api/claim-client/'+data.id,{'data':data},{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
          });
          return response;
        }catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async fetchBeneficiaryClaimImage({commit}, data){
        try {
          const response = await axios.get('/api/get-beneficiary-claim-image', {
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params: {
              data,
            }
          });
          return response;
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async fetchClientClaimImage({commit}, data){
        try {
          const response = await axios.get('/api/get-client-claim-image', {
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params: {
              data,
            }
          });
          return response;
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async fetchClaimClientDetails({commit},data){
        return axios.get('/api/claim-client-details/' + data.transactionClaimID,{
          withCredentials: true,
          headers: {
            //'Authorization': `Bearer ${localStorage.getItem('token')}`
          },
        })
        .then(response => {
          commit('setSuffixSeeder',response.data.suffix);
          this.state.claimant.claimDate = response.data.todayDate;
          commit('setClaimClientDetails', { claimClientDetails: response.data.transactionClaimArray });
          return response; // Ensure this is returned
        })
        .catch(error => {
          console.error('Action Error:', error);
          throw error; // Propagate the error
        });
      },
      async searchTransactionIDClaim({commit},data){
        try{
          return await axios.get('/api/search-transaction-id-claim',{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params: {
              data,
            }
          });
        }catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async searchClientClaim({commit},data){
        try{
          return await axios.get('/api/search-client-claim',{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params: {
              data,
            }
            });
        }catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async fetchTransactionClaimTable({commit},data){
        try {
          return await axios.get('/api/claiming-of-assistance-table', {
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params: {
              data, 
             }
            }).then(response => {
            commit('setClaimAssistanceTable', { 'claimAssistanceTable': response.data });
          });
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async bulkApprove({commit},data){
        try {
          return await axios.patch('/api/bulk-approve', {'data':data},{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
          
          });
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async searchBulkTransactionApproveDateRequest({commit},data){
        try {
          return await axios.get('/api/search-transaction-date-bulk-approve', {
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params: {
              data, // Spread the data object into query parameters
              
            },
          });
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async searchBulkTransactionApproveTransactionID({commit},data){
        try {
          return await axios.get('/api/search-transaction-id-bulk-approve', {
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params: {
              data, // Spread the data object into query parameters

            },
          });
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async searchBulkTransactionApproveClient({commit},data){
        try {
          return await axios.get('/api/search-client-bulk-approve', {
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params: {
              data, // Spread the data object into query parameters
              
            },
          });
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async fetchBulkApproveAssistanceTable({commit},data){
        try {
          const response = await axios.get('/api/bulk-approval-of-assistance-table',{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
          });
          commit('setBulkApproveAssistanceTable', { 'bulkApproveAssistanceTable': response.data });
          this.state.approveDeclineClient.date_approve_decline = response.data.dateToday;
          return response;
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async fetchBeneficiaryApproveImage({commit}, data){
        try {
          const response = await axios.get('/api/get-beneficiary-approve-image', {
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params: {
              data,
            }
          });
          return response;
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async fetchClientApproveImage({commit}, data){
        try {
          const response = await axios.get('/api/get-client-approve-image', {
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params: {
              data,
            }
          });
          return response;
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async searchTransactionIDApprove({commit},data){
        try {
          const response = await axios.get('/api/search-transactionID-approve', {
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params: {
              data, // Spread the data object into query parameters
           
            },
          });
          return response;
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
    },
      async searchClientApprove({commit},data){
          try {
          const response = await axios.get('/api/search-client-approve', {
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            params: {
              data,

            },
          });
          return response;
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        };
      },
      async declineClient({commit},data){
        const { id, amount } = data; // Destructure id and data from the payload
        try {
          const response = await axios.patch('/api/decline-client/' + data.id, {'data':data},{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            }, 
           
          });
          return response;
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async approveClient({commit},data){
        const { id, amount } = data; // Destructure id and data from the payload
        try {
          const response = await axios.patch('/api/approve-client/' + data.id, {'data':data},{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
           
          });
          return response;
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async submitData({ commit }, data) { // Action to handle data submission
        try {

          // await axios.get('/sanctum/csrf-cookie', { 
          //   withCredentials: true,
          // });
          
          const response = await axios.post('/api/submit-request-for-assistance', {'data':data},{ 
            withCredentials: true,
          });
          return response;
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async fetchSeeders({commit},data){
        try {

          const response = await axios.get('/api/form-seeder', {
            withCredentials: true, 
            headers: { 'Accept': 'application/json' }
        });
          commit('setFormSeeder', response.data);
          commit('setTransactionDate', { 'transactionDate': response.data.date_today });
          return response;
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async fetchApproveAssistanceTable({commit},data){
        try {
          const response = await axios.get('/api/approval-of-assistance-table',{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
          });
          commit('setApproveAssistanceTable', { 'approveAssistanceTable': response.data });
          return response;
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      },
      async fetchApproveClientDetails({commit}, data){
        try {
          const response = await axios.get('/api/approve-client-details/' + data.transactionID,{
            withCredentials: true,
            headers: {
              //'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
          });
          commit('setApproveClientDetails', { 'approveClientDetails': response.data });
          return response;
        } catch (error) {
          console.error(error); // Handle error
          return await Promise.reject(error);
        }
      }
     
    }
})

export default store;