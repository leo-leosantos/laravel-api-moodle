<?php

use Illuminate\Database\Seeder;
use App\Models\Matricular;

class MatricularTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $matricular = new Matricular();
        $matricular->nombre = 'Matriculado';
        $matricular->apellidos = 'De Prueba 1';
        $matricular->nombreusuario = "matriculaprueba01" ;
        $matricular->email = "matriculado3@domain.com" ;
        $matricular->contrasenia = "Nuevo123*";
        $matricular->curso_id = 7;
        $matricular->nombrecortodelcurso = "app con c sharp";
        $matricular->nombrelargodelcurso = "aplicaciones windows c sharp";
        $matricular->cantidaddiassiningreso = 0;
        $matricular->codigopais = "AD";
        $matricular->nombrepais = "Andorra";
        $matricular->matricula = 0;

        $matricular->save();

        $matricular = new Matricular();
        $matricular->nombre = 'Matriculado';
        $matricular->apellidos = 'De Prueba 2';
        $matricular->nombreusuario = "matriculaprueba02" ;
        $matricular->email = "matriculado2@domain.com" ;
        $matricular->contrasenia = "Nuevo123*";
        $matricular->curso_id = 1;
        $matricular->nombrecortodelcurso = "LMD";
        $matricular->nombrelargodelcurso = "Laravel y moodle mas Docker";
        $matricular->cantidaddiassiningreso = 0;
        $matricular->codigopais = "CR";
        $matricular->nombrepais = "Costa Rica";
        $matricular->matricula = 1;
        $matricular->save();

        $matricular = new Matricular();
        $matricular->nombre = 'Matriculado';
        $matricular->apellidos = 'De Prueba 3';
        $matricular->nombreusuario = "matriculaprueba03" ;
        $matricular->email = "matriculado1@domain.com" ;
        $matricular->contrasenia = "Nuevo123*";
        $matricular->curso_id = 1;
        $matricular->nombrecortodelcurso = "LMD";
        $matricular->nombrelargodelcurso = "Laravel y moodle mas Docker";
        $matricular->cantidaddiassiningreso = 0;
        $matricular->codigopais = "CR";
        $matricular->nombrepais = "Costa Rica";
        $matricular->matricula = 0;
        $matricular->save();

        $matricular = new Matricular();
        $matricular->nombre = 'Matriculado';
        $matricular->apellidos = 'De Prueba 4';
        $matricular->nombreusuario = "matriculaprueba04" ;
        $matricular->email = "matriculado1@domain.com" ;
        $matricular->contrasenia = "Nuevo123*";
        $matricular->curso_id = 1;
        $matricular->nombrecortodelcurso = "LMD";
        $matricular->nombrelargodelcurso = "Laravel y moodle mas Docker";
        $matricular->cantidaddiassiningreso = 0;
        $matricular->codigopais = "CR";
        $matricular->nombrepais = "Costa Rica";
        $matricular->matricula = 1;
        $matricular->save();

        $matricular = new Matricular();
        $matricular->nombre = 'Matriculado';
        $matricular->apellidos = 'De Prueba 5';
        $matricular->nombreusuario = "matriculaprueba05" ;
        $matricular->email = "matriculado1@domain.com" ;
        $matricular->contrasenia = "Nuevo123*";
        $matricular->curso_id = 1;
        $matricular->nombrecortodelcurso = "LMD";
        $matricular->nombrelargodelcurso = "Laravel y moodle mas Docker";
        $matricular->cantidaddiassiningreso = 0;
        $matricular->codigopais = "CR";
        $matricular->nombrepais = "Costa Rica";
        $matricular->matricula = 0;
        $matricular->save();
    }
}
