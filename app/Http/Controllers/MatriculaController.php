<?php

namespace App\Http\Controllers;

use App\Models\Matricular;

use App\Models\Paisesmoodle;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
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

        $listramatriculas =   Matricular::orderby('id', 'desc')->paginate(2);

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

    public function guardarregistros(array $dadosdelformulario, $registrado = 0, $cantdadDiasSinIngreso = 0)
    {


        $matricular = new Matricular();
        $matricular->nombre = $dadosdelformulario['nombre'];
        $matricular->apellidos = $dadosdelformulario['apellidos'];
        $matricular->email = $dadosdelformulario['email'];
        $matricular->nombreusuario = $dadosdelformulario['nombreusuario'];
        $matricular->contrasenia = $dadosdelformulario['contrasenia'];
        $matricular->curso_id = $dadosdelformulario['codcurso'];
        //trazendo dados del curso
        $datoDelCurso = $this->obtenerCursosPorId($dadosdelformulario['codcurso']);
        $matricular->nombrecortodelcurso = $datoDelCurso->shortname;
        $matricular->nombrelargodelcurso = $datoDelCurso->fullname;

        $matricular->cantidaddiassiningreso = $cantdadDiasSinIngreso;

        //trazendo dados del paises
        $matricular->codigopais = $dadosdelformulario['pais'];
        $pais = Paisesmoodle::where('codigo', $dadosdelformulario['pais'])->first();
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
            if ($usuario[0]->lastaccess > 0) {
                $date = new DateTime();
                $datenow = new DateTime();
                $date->setTimestamp($usuario[0]->lastaccess);
                $cantdadDias = $date->diff($datenow)->days;
            }
            $this->guardarregistros($dadosdelformulario, 1, $cantdadDias);
        }



        return redirect()->route('listramatricula')->with('status', 'usuário cadastrado com sucesso');
    }


    public function enviarAMatricular($usuarioAMatricular, $idusuario)
    {

        try {
            if ($usuarioAMatricular->cantidaddiassiningreso > 35) {
                $this->actualizarContraseninausuario($idusuario, $usuarioAMatricular->contrasenia);
            }
            // logia para matricular

            $matricularusuario = true;
            $cursosDelusuario = $this->obtenerCursosDeUsuario($idusuario);

            foreach ($cursosDelusuario as $curso) {
                if ($curso->id == $usuarioAMatricular->curso_id) {
                    $matricularusuario = false;
                }
            }
            if ($matricularusuario) {
                $matriculado = $this->matricularEnCurso($idusuario, $usuarioAMatricular->curso_id);
            }

            $usuarioAMatricular->matricula = 1;
            $usuarioAMatricular->save();
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }


    public function crearUsuarioEnMoodle($matricular)
    {
        $nuevoUsuario = $this->crearusuario(
            $matricular->nombreusuario,
            $matricular->contrasenia,
            $matricular->nombre,
            $matricular->apellidos,
            $matricular->email,
            $matricular->codigopais
        );

        $nuevoUsuario->yaregistrado = 1;
        $nuevoUsuario->save();

        // dd($nuevoUsuario);
        return $nuevoUsuario;
    }

    public function enviarCorreoEletronico($usuarioAMatricular)
    {

        try {
            $contrasenia = $usuarioAMatricular->contrasenia;

            if( $usuarioAMatricular->cantidaddiassiningreso > 0 &&  $usuarioAMatricular->cantidaddiassiningreso <= 100){

                $contrasenia = "sua senha nao foi modificada";
            }

            $subject = "usuario:" . $usuarioAMatricular->nombreusuario . "foi matricualdo";

            $data = array('email'=> $usuarioAMatricular->email,
                'subject' => $subject,
                'nombrecurso'=>$usuarioAMatricular->nombrelargodelcurso . '(' . $usuarioAMatricular->nombrecortodelcurso . ')',
                'nombrecompleto'=>$usuarioAMatricular->nombre . ' ' . $usuarioAMatricular->apellidos,
                'nombreusuario' => $usuarioAMatricular->nombreusuario,
                'contrasenia'=>$contrasenia

            );

                Mail::send('email.email' , ['data'=> $data], function ($message) use ($data) {
                            $message->from('zabbixnotificacoes@gmail.com', 'EAD MOODLE');
                            $message->to($data['email']);
                            $message->subject($data['subject']);

                    });
            }

         catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function matricular(Request $request, $id)
    {
        $request->user()->authorizeRoles('administrador');

        $resultadoMatricula = false;
        //$dadosdelformulario = $request->all();

        $usuarioAMatricular = Matricular::where('id', $id)->first();
        //  dd($usuarioAMatricular);
        if (!$usuarioAMatricular->yaregistrado) {

            $usuario =  $this->obtenerUsuario($usuarioAMatricular->email);

            //dd($usuario);
            if (count($usuario) == 0) {
                $usuario =  $this->obtenerUsuario($usuarioAMatricular->nombreusuario, 'username');

                if (count($usuario) == 0) {
                    //creo nuevo usuario
                    $nuevoUsuario = $this->crearUsuarioEnMoodle($usuarioAMatricular);
                    // dd($nuevoUsuario[0]->id);

                    //envio a matricular
                    $resultadoMatricula =  $this->enviarAMatricular($usuarioAMatricular, $nuevoUsuario[0]->id);
                } else {
                    return back()->withInput()->with('statuserror', 'O usuário ja exite');
                }
            } else {
                $usuario =  $this->obtenerUsuario($usuarioAMatricular->email);

                //envio a matricular
                $resultadoMatricula =  $this->enviarAMatricular($usuarioAMatricular, $usuario[0]->id);
            }
        } else {
            $usuario =  $this->obtenerUsuario($usuarioAMatricular->email);

            //envio a matricular
            $resultadoMatricula =  $this->enviarAMatricular($usuarioAMatricular, $usuario[0]->id);
        }

        //enviar email de resultado da matriula
        if ($resultadoMatricula) {

            //enviar email
          $resultadodeEnvioEmail =  $this->enviarCorreoEletronico($usuarioAMatricular);
          if(!$resultadodeEnvioEmail){

            return redirect()->route('listramatricula')->with('status', 'usuario matriculado com sucesso');

            return back()->withInput()->with('statuserror', 'error não foi possivel enviar o email');

          }


        } else {
            return back()->withInput()->with('statuserror', 'error não foi possivel matricular o usuário');
        }

        return redirect()->route('listramatricula')->with('status', 'usuário matriculado com sucesso');
    }


    public function eliminar(Request $request, $id)
    {
        $request->user()->authorizeRoles(['colaborador','administrador']);

        $usuarioAEliminar = Matricular::where('id', $id)->first();

        $usuarioAEliminar->delete();

        return redirect()->route('listramatricula')->with('status', 'usuário excluido com sucesso');


    }
}
