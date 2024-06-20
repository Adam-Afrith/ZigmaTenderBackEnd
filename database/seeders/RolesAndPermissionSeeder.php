<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         // Reset cached roles and permissions
         app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

          //  User::create([
        //     'name' => 'Prabakaran',
        //     'email' => 'prabakaran@santhila.com',
        //     'password' => bcrypt('123'),
        // ]);

    //    $user= User::create([
    //         'name' => 'Test',
    //         'email' => 'test@santhila.com',
    //         'password' => bcrypt('123'),
    //     ]);

        //  // create permissions
        // $viewPer= Permission::create(['name' => 'view']);
        // $Perde =Permission::create(['name' => 'delete']);
        // $Ceate= Permission::create(['name' => 'create']);
        // $edit=Permission::create(['name' => 'edit']);
         Permission::create(['name' => 'userCreation-list']);
         Permission::create(['name' => 'userCreation-create']);
         Permission::create(['name' => 'userCreation-edit']);
         Permission::create(['name' => 'userCreation-delete']);
 

        // //  // create roles and assign created permissions
 
        // //  // this can be done as separate statements
        //  $role = Role::create(['name' => 'Guest']);
        //  $role->givePermissionTo('view');
       
        //  $role->givePermissionTo(Permission::all());
 
        // //  // or may be done by chaining
        //  $role = Role::create(['name' => 'Field-Executive'])
        //      ->givePermissionTo(['create', 'view']);
 
        //  $role = Role::create(['name' => 'Admin']);
        //  $role->givePermissionTo(Permission::all());

      

        // USER::find(1)->assignRole("Field-Executive");
        // USER::find(2)->assignRole("Admin");
        // $user->assignRole($role );
        

        // Retrieve the "Admin" role
        $adminRole = Role::where('name', 'admin')->first();
        // $user->assignRole($adminRole );

        // // // // Retrieve the permissions you want to assign to the "Admin" role
        $listPermission = Permission::where('name', 'userCreation-list')->first();
        $createPermission = Permission::where('name', 'userCreation-create')->first();
        $editPermission = Permission::where('name', 'userCreation-edit')->first();
        $deletePermission = Permission::where('name', 'userCreation-delete')->first();

        // // // // Assign the permissions to the "Admin" role
        $adminRole->givePermissionTo([
            $listPermission->id,
            $createPermission->id,
            $editPermission->id,
            $deletePermission->id,
        ]);


    }
}
