<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->name = 'administrador';
        $role->description = 'Administrator de Cursos';
        $role->save();



        $role = new Role();
        $role->name = 'colaborador';
        $role->description = 'Revisor de Cursos';
        $role->save();

        $role = new Role();
        $role->name = 'usuarios';
        $role->description = 'Usuário com poucos privilegios';
        $role->save();

    }
}
