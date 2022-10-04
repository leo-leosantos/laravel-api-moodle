@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Lista de Matricula') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @elseif (session('statuserror'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('statuserror') }}
                            </div>
                        @endif
                        <div class="row mr-5">
                            <a href="{{ route('crearmatricula') }}" class="btn btn-success"> Nova Matricula </a>
                        </div>
                        <div class="row">
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Ações</th>
                                        <th scope="col">Nome Completo</th>
                                        <th scope="col">Courso </th>
                                        <th scope="col">Email do usuário</th>
                                        <th scope="col">Informação</th>
                                        <th scope="col">Status</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($listramatriculas as $item)
                                        <tr>
                                            <th scope="row">{{ $item->id }}</th>
                                            <td>
                                                @if (Auth::User()->hasAnyRole(['administrador']))
                                                    @if (!$item->matricula)
                                                        <a href="{{ route('matricularalunno', $item->id) }}"
                                                            class="btn btn-success">Matricular</a>
                                                    @endif
                                                @endif

                                            </td>
                                            <td>{{ $item->nombre }} - {{ $item->apellidos }}</td>
                                            <td> {{ $item->nombrelargodelcurso }} - ({{ $item->nombrecortodelcurso }})</td>
                                            <td>{{ $item->email }}</td>

                                            <td>
                                               <b>Nome de usuario:  </b>{{ $item->nombreusuario }}   <b> Pais: </b> {{ $item->nombrepais }}<br />
                                                <b> Dias sem logar: </b> {{ $item->cantidaddiassiningreso }}<br />

                                            </td>

                                            <td>
                                                <span class="badge badge-{{ $item->matricula ? 'success' : 'info' }}">
                                                    {{ $item->matricula ? 'Matriculado' : 'Sim Matricular ' }} </span><br>
                                                @if (!$item->matricula)
                                                    <span
                                                        class="badge badge-{{ $item->yaregistrado ? 'danger' : 'warning' }}">
                                                        {{ $item->yaregistrado ? 'Já Matriculado' : 'Não Matriculado ' }}
                                                    </span><br>
                                                @endif

                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $listramatriculas->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
