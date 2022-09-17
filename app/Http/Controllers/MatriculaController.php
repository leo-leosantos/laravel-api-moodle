<?php

namespace App\Http\Controllers;

use App\Models\Matricular;

use App\Models\Paisesmoodle;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MatriculaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $request->user()->authorizeRoles(['colaborador', 'administrador']);
        // dd($this->obtenerCursos());

        $listramatriculas =   Matricular::orderby('id','desc')->paginate(2);

        return view('matricula.index', compact('listramatriculas'));
    }


    public function crearnuevoregistro(Request $request)
    {
        $request->user()->authorizeRoles(['colaborador', 'administrador']);
        //dd($this->obtenerCursos());

        $listaPaises = Paisesmoodle::get();
        $listadecursos = $this->obtenerCursos();

        return view('matricula.nuevoregistro', [
            'listaPaises' => $listaPaises,
            'listadecursos' => $listadecursos
        ]);
    }
    private function creareglasdevalidadcion(array $datosavalidar)
    {
        $messages = [
            'required' => 'Es :attribute é obrigatório',
            'pais.not_in' => 'O pais é obrigatorio',
            'codcurso.min' => 'O curso é obrigatorio',
        ];
        return Validator::make(
            $datosavalidar,
            [
                'nombre' => 'required|string|min:3|max:60',
                'apellidos' => 'required|string|min:3|max:60',
                'pais' => 'required|not_in:-1',
                'codcurso' => 'required|numeric|min:1',
                'email' => 'required|email:rfc,dns',
                'nombreusuario' => 'required|string|min:5|max:60',
                'contrasenia' => 'required|string|min:5|max:60'


            ],
            $messages
        );
    }

    public function guardarregistros(array $dadosdelformulario, $registrado = 0, $cantdadDiasSinIngreso =0 )
    {


        $matricular = new Matricular();
        $matricular->nombre = $dadosdelformulario['nombre'];
        $matricular->apellidos = $dadosdelformulario['apellidos'];
        $matricular->email = $dadosdelformulario['email'];
        $matricular->nombreusuario = $dadosdelformulario['nombreusuario'];
        $matricular->contrasenia = $dadosdelformulario['contrasenia'];
        $matricular->curso_id = $dadosdelformulario['codcurso'];
        //trazendo dados del curso
        $datoDelCurso = $this->obtenerCursosPorId($dadosdelformulario['codcurso']) ;
        $matricular->nombrecortodelcurso = $datoDelCurso->shortname;
        $matricular->nombrelargodelcurso = $datoDelCurso->fullname;

        $matricular->cantidaddiassiningreso = $cantdadDiasSinIngreso;

        //trazendo dados del paises
        $matricular->codigopais = $dadosdelformulario['pais'];
        $pais = Paisesmoodle::where('codigo',$dadosdelformulario['pais'])->first();
        $matricular->nombrepais =  $pais->nombre;

        $matricular->yaregistrado = $registrado;
        $matricular->save();

        return true;



    }
    public function guardanuevousuario(Request $request)
    {
        $request->user()->authorizeRoles(['colaborador', 'administrador']);
        $dadosdelformulario = $request->all();

        $ejecutar = $this->creareglasdevalidadcion($dadosdelformulario);
        $ejecutar->validate();
        $usuario = $this->obtenerUsuario($dadosdelformulario['email']);




        //dd($usuario[0]->fullname);
        if (count($usuario) == 0) {
            $usuario = $this->obtenerUsuario($dadosdelformulario['nombreusuario'], 'username');

            if (count($usuario) > 0) {
                return back()->withInput()->with('statuserror', 'usuário ja esta  cadastrado');
            }
            $this->guardarregistros($dadosdelformulario);
        } else {
            //validar que  o usuario nao esteja registrado no curso
            $cursosDelusuario = $this->obtenerCursosDeUsuario($usuario[0]->id);

            if (count($cursosDelusuario) > 0) {
                foreach ($cursosDelusuario as $curso) {
                    if ($curso->id == $dadosdelformulario['codcurso']) {
                        return back()->withInput()->with('statuserror', 'usuário ja esta  cadastrado e ja esta matriculado no curso');
                    }
                }
            }
            $cantdadDias = 0;
            if($usuario[0]->lastaccess > 0)
            {
                $date = new DateTime();
                $datenow = new DateTime();
                $date->setTimestamp($usuario[0]->lastaccess);
                $cantdadDias = $date->diff($datenow)->days;
            }
            $this->guardarregistros($dadosdelformulario, 1, $cantdadDias);
        }



        return redirect()->route('listramatricula')->with('status', 'usuário cadastrado com sucesso');
    }
}
