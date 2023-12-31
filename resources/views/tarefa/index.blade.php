@extends('layouts.app')

@section('content')
    <div class='container'>
        <div class='row justify-content-center'>
            <div class='col-md-8'>
                <div class='card'>
                    <div class='card-header'>
                        <div class='row'>
                            <div class='col-3'>
                                {{ __('Tarefas') }}
                            </div>
                            <div class='col-9'>
                                <div class='float-end'>
                                    <a href='{{ route('tarefa.create') }}' style='margin-right: 0.6rem;'>Nova Tarefa</a>
                                    <a href='{{ route('tarefa.exportacao', 'xlsx') }}' style='margin-right: 0.6rem;'>XLSX</a>
                                    <a href='{{ route('tarefa.exportacao', 'csv') }}' style='margin-right: 0.6rem;'>CSV</a>
                                    <a href='{{ route('tarefa.exportacao', 'pdf') }}' style='margin-right: 0.6rem;'>PDF</a>
                                    <a href='{{ route('tarefa.exportar') }}' target='_blank'>PDF V2</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class='card-body'>
                        <table class='table'>
                            <thead>
                                <tr>
                                    <th scope='col'>#</th>
                                    <th scope='col'>Tarefa</th>
                                    <th scope='col'>Data Limite Conclusão</th>
                                    <th scope='col'></th>
                                    <th scope='col'></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tarefas as $tarefa)
                                    <tr>
                                        <th scope='row'>{{ $tarefa->id }}</th>
                                        <td>{{ $tarefa->tarefa }}</td>
                                        <td>{{ date('d/m/Y', strtotime($tarefa->data_limite_conclusao)) }}</td>
                                        <td><a href='{{ route('tarefa.edit', $tarefa->id) }}'>Editar</a></td>
                                        <td>
                                            <form id='form_{{ $tarefa->id }}' method='post'
                                                action='{{ route('tarefa.destroy', $tarefa->id) }}'>
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <a href='#'
                                                onclick='document.getElementById('form_{{ $tarefa->id }}').submit()'>Excluir</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class='d-flex justify-content-center'>
                            {{ $tarefas->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
