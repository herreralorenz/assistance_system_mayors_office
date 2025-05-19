<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class SpatieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //


        // $role = Role::create(['name' => 'andrei']);
        Permission::create(['name' => 'requestAssistance']);
        Permission::create(['name' => 'approveAssistance']);
        Permission::create(['name' => 'declineAssistance']);
        Permission::create(['name' => 'bulkApproveAssistance']);
        Permission::create(['name' => 'claimAssistance']);
        Permission::create(['name' => 'unclaimAssistance']);
        Permission::create(['name' => 'bulkClaimAssistance']);
        Permission::create(['name' => 'viewClientInformation']);
        Permission::create(['name' => 'editClientInformation']);
        Permission::create(['name' => 'deleteClientInformation']);
        Permission::create(['name' => 'newTransaction']);
        Permission::create(['name' => 'viewTransaction']);
        Permission::create(['name' => 'voidTransaction']);
        Permission::create(['name' => 'editBeneficiary']);
        Permission::create(['name' => 'transactionReport']);
        Permission::create(['name' => 'bulkPrintingOfReceipt']);
        Permission::create(['name' => 'returnDays']);

        Permission::create(['name' => 'addBeneficiary']);

        Permission::create(['name' => 'editApproveAssistance']);
        Permission::create(['name' => 'viewApproveAssistance']);

        Permission::create(['name' => 'editClaimAssistance']);
        Permission::create(['name' => 'viewClaimAssistance']);

        Permission::create(['name' => 'deleteTransaction']);

        Permission::create(['name' => 'deleteUser']);
        Permission::create(['name' => 'editUser']);
        Permission::create(['name' => 'addUser']);
        Permission::create(['name' => 'SMSMessage']);
        Permission::create(['name' => 'sendSMS']);
        Permission::create(['name' => 'dashboard']);

        Role::create(['name' => 'superAdmin']);
        $role = Role::findByName('superAdmin');
        $role->givePermissionTo('requestAssistance');
        $role->givePermissionTo('approveAssistance');
        $role->givePermissionTo('declineAssistance');
        $role->givePermissionTo('bulkApproveAssistance');
        $role->givePermissionTo('claimAssistance');
        $role->givePermissionTo('unclaimAssistance');
        $role->givePermissionTo('bulkClaimAssistance');
        $role->givePermissionTo('viewClientInformation');
        $role->givePermissionTo('editClientInformation');
        $role->givePermissionTo('deleteClientInformation');
        $role->givePermissionTo('newTransaction');
        $role->givePermissionTo('viewTransaction');
        $role->givePermissionTo('voidTransaction');
        $role->givePermissionTo('editBeneficiary');
        $role->givePermissionTo('transactionReport');
        $role->givePermissionTo('bulkPrintingOfReceipt');
        $role->givePermissionTo('returnDays');
        $role->givePermissionTo('addBeneficiary');
        $role->givePermissionTo('editApproveAssistance');
        $role->givePermissionTo('viewApproveAssistance');
        $role->givePermissionTo('editClaimAssistance');
        $role->givePermissionTo('viewClaimAssistance');
        $role->givePermissionTo('deleteTransaction');
        $role->givePermissionTo('deleteUser');
        $role->givePermissionTo('addUser');
        $role->givePermissionTo('editUser');
        $role->givePermissionTo('sendSMS');
        $role->givePermissionTo('SMSMessage');
        $role->givePermissionTo('dashboard');

        

        $user = User::find(1); 
        $user->assignRole('superAdmin');

        //can assign role to user (bulk)
        // $user = User::find(1); 
        // $user->assignRole('andrei');

        // can give permission directly to role name
        // $role = Role::findByName('admin');  // Find the 'admin' role
        // $role->givePermissionTo('edit articles');

        //can give permission directly to user
        // $user = User::find(1);  // Find the user by ID
        // $user->givePermissionTo('edit articles');


    }
}
