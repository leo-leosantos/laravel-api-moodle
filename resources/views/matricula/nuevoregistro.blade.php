@extends('layouts.app')

@section('css')
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-md-10">
                <div class="card">

                    <div class="card-header">
                        <div class="row">
                            <div class="col-10">
                                <b> Novo Usuário</b>
                            </div>
                            <div class="col-2">
                                <a class="btn btn-info" href="{{ route('listramatricula') }}" role="button"> Voltar</a>
                            </div>
                        </div>
                    </div>

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

                        <form method="POST" action="{{ route('guardanuevousuario') }}">
                            @csrf
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right"> Email </label>


                                <div class="col-md-5">
                                    <input id="email" name="email" type="email"
                                        class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                        value="{{ old('email') }}" >

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                            <strong> {{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="nombre" class="col-md-4 col-form-label text-md-right"> Nome </label>
                                <div class="col-md-5">

                                    <input id="nombre" name="nombre" type="text"
                                        class="form-control{{ $errors->has('nombre') ? ' is-invalid' : '' }}"
                                        value="{{ old('nombre') }}" >

                                    @if ($errors->has('nombre'))
                                        <span class="invalid-feedback">
                                            <strong> {{ $errors->first('nombre') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="apellidos" class="col-md-4 col-form-label text-md-right"> Apelidos </label>
                                <div class="col-md-5">

                                    <input id="apellidos" name="apellidos" type="text"
                                        class="form-control{{ $errors->has('apellidos') ? ' is-invalid' : '' }}"
                                        value="{{ old('apellidos') }}" >

                                    @if ($errors->has('apellidos'))
                                        <span class="invalid-feedback">
                                            <strong> {{ $errors->first('apellidos') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="pais" class="col-md-4 col-form-label text-md-right"> Pais </label>
                                <div class="col-md-5">

                                    <select class="form-control{{ $errors->has('pais') ? 'is-invalid' : '' }}" id="pais" name="pais" data-live-search="true">
                                        <option value="-1" {{ old('pais', '-1') == '-1' ? 'selected' : '' }}  >Selecione o pais(opcional)</option>
                                        @foreach ($listaPaises as $item)
                                            {{ $item }}
                                            <option value="{{ $item->codigo }}" {{ old('pais', '-1') == $item->codigo ? 'selected' : '' }} > {{ $item->nombre }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('pais'))
                                        <span class="invalid-feedback">
                                            <strong> {{ $errors->first('pais') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="nombreusuario" class="col-md-4 col-form-label text-md-right"> Nome Usuário </label>

                                <div class="col-md-5">
                                    <input id="nombreusuario" name="nombreusuario" type="text"
                                        class="form-control{{ $errors->has('nombreusuario') ? ' is-invalid' : '' }}"
                                        value="{{ old('nombreusuario') }}" >

                                    @if ($errors->has('nombreusuario'))
                                        <span class="invalid-feedback">
                                            <strong> {{ $errors->first('nombreusuario') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="nombreusuario" class="col-md-4 col-form-label text-md-right"> Senha </label>

                                <div class="col-md-5">
                                    <input id="contrasenia" name="contrasenia" type="password"
                                        class="form-control{{ $errors->has('contrasenia') ? ' is-invalid' : '' }}"
                                        value="{{ old('contrasenia') }}" >

                                    @if ($errors->has('contrasenia'))
                                        <span class="invalid-feedback">
                                            <strong> {{ $errors->first('contrasenia') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="codcurso" class="col-md-4 col-form-label text-md-right"> Codigo do Curso </label>

                                <div class="col-md-5">
                                <select class="form-control{{ $errors->has('codcurso') ? 'is-invalid' : '' }}" id="codcurso" name="codcurso" data-live-search="true">
                                    <option value="-1" {{ old('codcurso', '-1') == '-1' ? 'selected' : '' }}  >Selecione o Curso</option>
                                    @foreach ($listadecursos as $item)
                                        <option value="{{ $item['id'] }}" {{ old('codcurso', '-1') == $item['id'] ? 'selected' : '' }}  >

                                            {{ $item['fullname'] }} |  {{ $item['shortname'] }} -  {{ $item['visible'] == 1 ? 'A' : 'I' }}
                                        </option>
                                    @endforeach
                                </select>

                                    @if ($errors->has('codcurso'))
                                        <span class="invalid-feedback">
                                            <strong> {{ $errors->first('codcurso') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-6 text-center">
                                    <button type="submit" class="btn btn-success" id="btenviar">Cadastrar
                                        Registro</button>
                                </div>
                                <div class="col-6 text-center">
                                    <a class="btn btn-info" href="{{ route('listramatricula') }}" role="button">Regressar
                                        Registro</a>
                                </div>
                            </div>
                        </form>



                    </div>

                </div>

            </div>


        </div>
    </div>
@endsection
@section('script')
@endsection
