@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Adicionar Tarefa') }}</div>

                    <div class="card-body">
                        <form method="post" action="{{ route('tarefa.store') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <label>Tarefa</label>
                                <input type="text" class="form-control" name="tarefa" placeholder="Insira o nome da tarefa">
                            </div>
                            <div class="form-group mb-3">
                                <label>Data Limite de Conclusão</label>
                                <input type="date" class="form-control" name="data_limite_conclusao">
                            </div>
                            <button type="submit" class="btn btn-primary">Cadastrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
