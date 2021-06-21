<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
  
class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $super = User::create([
            'name' => 'Admin 1', 
            'email' => 'admin1@gmail.com', //change using ur email
            'password' => bcrypt('123456')
        ]);
    
        $role = Role::create(['name' => 'Super Admin']);
        $permissions = Permission::pluck('id','id')->all();
        $role->syncPermissions($permissions);
        $super->assignRole([$role->id]);

    }
}
