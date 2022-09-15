<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Matricular;
use App\Models\Paisesmoodle;

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
        $request->user()->authorizeRoles(['colaborador','administrador']);
       // dd($this->obtenerCursos());

        $listramatriculas =   Matricular::paginate(2);

        return view('matricula.index', compact('listramatriculas'));
    }


    public function crearnuevoregistro(Request $request)
    {
        $request->user()->authorizeRoles(['colaborador','administrador']);
        //dd($this->obtenerCursos());

        $listaPaises = Paisesmoodle::get();

        return view('matricula.nuevoregistro',[
            'listaPaises' => $listaPaises
        ]);


    }


}
