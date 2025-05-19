import {createRouter, createWebHistory, createWebHashHistory} from 'vue-router';

import ClientBeneficiaryDetails from '../components/admin/request-for-assistance/ClientBeneficiaryDetailsComponent.vue'
import AdminComponent from '../components/admin/AdminComponent.vue';
import RequestForAssistanceProgressBar from '../components/admin/request-for-assistance/RequestForAssistanceProgressBarComponent.vue'
import SubmitRequestAssistance from '../components/admin/request-for-assistance/SubmitRequestAssistanceComponent.vue';
import NavigationBar from '../components/admin/layout/NavigationComponent.vue'
import ApproveAssistanceTable from '../components/admin/approval-of-assistance/ApproveAssistanceTableComponent.vue'
import ApproveClient from '../components/admin/approval-of-assistance/ApproveClientComponent.vue'
import Header from '../components/admin/layout/HeaderComponent.vue'
import BulkApproveOfAssistance  from'../components/admin/approval-of-assistance/BulkApproveOfAssistanceComponent.vue'
import ClaimAssistanceTable from '../components/admin/claiming-of-assistance/ClaimAssistanceTableComponent.vue'
import ClaimClient from '../components/admin/claiming-of-assistance/ClaimClientComponent.vue'
import BulkClaimOfAssistance from '../components/admin/claiming-of-assistance/BulkClaimOfAssistanceComponent.vue'
import Client from '../components/admin/client/ClientInformationTableComponent.vue'
import ClientDetails from '../components/admin/client/ClientDetailsComponent.vue'
import EditClient from '../components/admin/client/ClientEditComponent.vue'
import ClientNewTransactionTable from '../components/admin/client/ClientNewTransactionTableComponent.vue'
import ClientNewTransactionSubmit from '../components/admin/client/ClientNewTransactionSubmitComponent.vue'
import ClientTransactionTable from '../components/admin/transactions/ClientTransactionTableComponent.vue'
import ClientTransaction from '../components/admin/transactions/ClientTransactionDetailsComponent.vue'
import ClientTransactionEditBeneficiary from '../components/admin/transactions/ClientTransactionEditBeneficiaryComponent.vue'
import ClientTransactionAddBeneficiary from '../components/admin/transactions/ClientTransactionAddBeneficiaryComponent.vue'
import TransactionReport from '../components/admin/transactions/TransactionReportComponent.vue'
import BulkPrintingOfReceipt from '../components/admin/transactions/BulkPrintingOfReceiptComponent.vue'
import EditReturnDays from '../components/admin/maintenance/EditReturnDaysComponent.vue'
import UploadTransaction from '../components/admin/transactions/UploadTransactionComponent.vue'
import AddEditUsers from '../components/admin/maintenance/AddEditUsersComponent.vue'
import AddPermissionsTable from '../components/admin/maintenance/AddPermissionsTableComponent.vue'
import AddUserPermissions from '../components/admin/maintenance/AddUserPermissionsComponent.vue'
import ClientNewTransactionBeneficiary from '../components/admin/client/ClientNewTransactionBeneficiaryComponent.vue'
import RequestForAssistanceReceipt from '../components/admin/print/RequestForAssistanceReceiptComponent.vue';
import BulkPrintingOfReceiptHolder from '../components/admin/transactions/BulkReceiptHolderComponent.vue'
import Login from '../components/auth/LoginComponent.vue';
import Authentication  from '../components/auth/AuthenticationComponent.vue';
import ClaimAssistanceQR from '../components/admin/claiming-of-assistance/ClaimAssistanceQR.vue';
import ApproveAssistanceQR from '../components/admin/approval-of-assistance/ApproveAssistanceQR.vue';
import UsersComponent from '../components/admin/maintenance/UsersTableComponent.vue';
import SendSMSToClaim from '../components/admin/sms/SendSMSToClaimComponent.vue';
import SMSMessage from '../components/admin/sms/SMSMessage.vue';
import Welcome from '../components/admin/WelcomeComponent.vue';
import Dashboard from '../components/admin/Dashboard.vue';

const routes = [


    {
        path:"/",
        name:'default',
        components:{
            default:Authentication,
            login:Login,
        },
    },
    {
        path:"/dashboard",
        name:'dashboard',
        components:{
            default: AdminComponent,
            header: Header,
            navigationbar: NavigationBar,
            dashboard:Dashboard,
        },
    },
    {
        path:"/admin",
        name:'admin',
        components:{
            default: AdminComponent,
            navigationbar: NavigationBar,
            welcome:Welcome,
        },
    },
    {
        path:'/sms',
        name:'sms',
        components:{
            default: AdminComponent,
            header: Header,
            navigationbar: NavigationBar,
        },
        children:[
            {
                path: 'send-sms/to-claim',
                name: 'sendSMS',
                component: SendSMSToClaim,
            },
            {
                path:'sms-message',
                name:'SMSMessage',
                component:SMSMessage,
            },
            // {
            //     path: 'message-sms',
            //     name: 'messageSMS',
            //     component: SubmitRequestAssistance,
            // },
           
        ]
        
    },
    {
        path:"/print",
        name:'print',
        components:{
            default:AdminComponent,
        },
        children:[
            {
                path:'request-for-assistance-receipt/:id',
                name: 'requestForAssistanceReceipt',
                component:RequestForAssistanceReceipt,
            },
            {
                path: 'bulk-printing-of-receipt-holder',
                name: 'bulkPrintingOfReceiptHolder',
                component: BulkPrintingOfReceiptHolder,
            }
        ]
    },
    {
        path:'/request',
        name:'requestForAssistance',
        components:{
            default: AdminComponent,
            header: Header,
            progressbar: RequestForAssistanceProgressBar,
            navigationbar: NavigationBar,
        },
        children:[
            {
                path: 'details',
                name: 'requestDetails',
                component: ClientBeneficiaryDetails,
            },
            {
                path: 'submit',
                name: 'requestSubmit',
                component: SubmitRequestAssistance,
            },
           
        ]
        
    },
    {
        path: '/approve',
        name: 'approveForAssistance',
        components: {
            default: AdminComponent,
            navigationbar: NavigationBar,
            header: Header,
        },
        children: [
            {
                path:'approve-qr',
                name:'approveAssistanceQR',
                component:ApproveAssistanceQR,
            },
            {
                path: 'client',
                name: 'approveAssistanceTable',
                component: ApproveAssistanceTable,
            },
            {
                path: 'client/:id',
                name: 'approveClientAssistance',
                component: ApproveClient,
            },
            {
                path: 'bulk',
                name: 'bulkApproveClientAssistance',
                component:  BulkApproveOfAssistance,
            }
        ]
    },
    {
        path:'/claim',
        name:'claimForAssistance',
        components:{
            default:AdminComponent,
            navigationbar: NavigationBar,
            header: Header,
        },
        children:[
            {
                path:'claim-qr',
                name:'claimAssistanceQR',
                component:ClaimAssistanceQR,
            },
            {
                path: 'client',
                name: 'claimAssistanceTable',
                component:ClaimAssistanceTable
            },
            {
                path: 'client/:id',
                name: 'claimClientAssistance',
                component: ClaimClient,
            },
            {
                path: 'bulk',
                name: 'bulkClaimClientAssistance',
                component:BulkClaimOfAssistance,
            }
        ]
    },
    {
        path: '/client',
        name: 'clients',
        components:{
            default:AdminComponent,
            navigationbar: NavigationBar,
            header: Header,
        },
        children:[
            {
                path: 'information',
                name: 'clientInformation',
                component:Client,
            },
            {
                path: 'information/:id',
                name: 'clientDetails',
                component:ClientDetails,
            },
            {
                path: 'information/:id/edit',
                name: 'editClient',
                component: EditClient,
            },
            {
                path: 'new-transaction',
                name: 'clientNewTransactionTable',
                component: ClientNewTransactionTable,
            },
            {
                path: 'new-transaction/:id/beneficiary/submit',
                name: 'clientNewTransactionSubmit',
                component: ClientNewTransactionSubmit,
            },
            {
                path: 'new-transaction/:id/beneficiary',
                name: 'clientNewTransactionBeneficiary',
                component: ClientNewTransactionBeneficiary,
            }
        ]
    },
    {
        path: '/transactions',
        name: 'transactions',
        components: {
            default:AdminComponent,
            navigationbar: NavigationBar,
            header: Header,
        },
        children:[
            {
                path: 'client-transactions',
                name: 'clientTransactionTable',
                component: ClientTransactionTable,
            },
            {
                path:'client-transaction/:id',
                name: 'clientTransaction',
                component: ClientTransaction
            },
            {
                path:'client-transactions/:id/beneficiary/:id2/client/:id3',
                name: 'clientTransactionEditBeneficiary',
                component: ClientTransactionEditBeneficiary,
            },
            {
                path:'client-transactions/:id/client/:id2/add-beneficiary',
                name:'clientTransactionAddBeneficiary',
                component:ClientTransactionAddBeneficiary,
            },
            {
                path: 'report',
                name: 'transactionReport',
                component: TransactionReport,
            },
            {
                path: 'bulk-printing',
                name: 'bulkPrinting',
                component: BulkPrintingOfReceipt,
            },
            {
                path: 'upload-transactions',
                name: 'uploadTransactions',
                component: UploadTransaction,
            },
           
        ]
    },
    {
        path: '/maintenance',
        name: 'maintenance',
        components:{
            default:AdminComponent,
            navigationbar: NavigationBar,
            header: Header,
        },
        children:[
            {
                path:'edit-return-days',
                name:'editReturnDays',
                component:EditReturnDays,
            },
            {
                path:'users',
                name:'usersTable',
                component:UsersComponent,
            },
            {
                path:'edit-user/:id',
                name:'editUser',
                component:AddEditUsers,
            },
            {
                path:'add-user',
                name:'addUser',
                component:AddEditUsers
            },
            {
                path:'add-permissions',
                name:'addPermissions',
                component:AddPermissionsTable
            },
            {
                path:'add-permissions/:id',
                name: 'addUserPermissions',
                component: AddUserPermissions
            }
        ]
    }
    
    

];

const router = createRouter({
    history:createWebHistory(),
    routes
});

export default router;