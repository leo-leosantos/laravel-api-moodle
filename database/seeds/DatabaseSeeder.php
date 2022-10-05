<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleTableSeeder::class);
        //$this->call(MatricularTableSeeder::class);
        $this->call(PaisesMoodleTableSeeder::class);

        $user = new User();
        $user->name = "Admin Udemy";
        $user->email ='admin@domain.com';
        $user->password = bcrypt('admin123');
        $user->save();

        $user->roles()->attach(Role::where('name','administrador')->first());

        $user = new User();
        $user->name = "colaborador";
        $user->email ='colaborador@domain.com';
        $user->password = bcrypt('user123');
        $user->save();
        $user->roles()->attach(Role::where('name','colaborador')->first());

    }
}
