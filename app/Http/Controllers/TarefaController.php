<?php

namespace App\Http\Controllers;

use App\Exports\TarefasExport;
use App\Mail\NovaTarefaMail;
use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class TarefaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $tarefas = Tarefa::where('user_id', $user_id)->paginate(10);
        return view('tarefa.index', ['tarefas' => $tarefas]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tarefa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dados = $request->all();
        $dados['user_id'] = Auth::user()->id;
        $tarefa = Tarefa::create($dados);
        $destinatario = Auth::user()->email;
        Mail::to($destinatario)->send(new NovaTarefaMail($tarefa));
        return redirect()->route('tarefa.show', ['tarefa' => $tarefa->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tarefa $tarefa)
    {
        return view('tarefa.show', ['tarefa' => $tarefa]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tarefa $tarefa)
    {
        if ($tarefa->user_id !== Auth::user()->id) return view('acesso-negado');
        return view('tarefa.edit', ['tarefa' => $tarefa]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tarefa $tarefa)
    {
        if ($tarefa->user_id !== Auth::user()->id) return view('acesso-negado');
        $tarefa->update($request->all());
        return redirect()->route('tarefa.show', ['tarefa' => $tarefa->id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tarefa $tarefa)
    {
        if ($tarefa->user_id !== Auth::user()->id) return view('acesso-negado');
        $tarefa->delete();
        return redirect()->route('tarefa.index');
    }

    public function exportacao(string $extensao)
    {
        if (!in_array($extensao, ['xlsx', 'csv', 'pdf'])) return redirect()->route('tarefa.index');
        return Excel::download(new TarefasExport, "lista_de_tarefas.$extensao");
    }

    public function exportar()
    {
        $tarefas = Auth::user()->tarefas()->get();
        $pdf = Pdf::loadView('tarefa.pdf', ['tarefas' => $tarefas]);
        // return $pdf->download('lista_de_tarefas.pdf'); // Faz o download direto
        $pdf->setPaper('a4', 'landscape'); //a4, letter - landscape (Paisagem), portrait (Retrato)
        return $pdf->stream('lista_de_tarefas.pdf'); // Abre o PDF em uma página no navegador
    }
}
