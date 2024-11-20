<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParticipanteController extends Controller
{
        public function cadastrar(Request $request)
    {
        
        $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|unique:participante,cpf|max:255',
            'telefone' => 'required|string|unique:participante,tel|max:255',
        ]);

   
        try {
            $participante = \DB::table('participante')->insertGetId([
                'nome' => $request->nome,
                'cpf' => $request->cpf,
                'tel' => $request->telefone,
            ]);

            return response()->json([
                'message' => 'Participante cadastrado com sucesso',
                'idParticipante' => $participante,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao cadastrar participante: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $user = DB::table('participante')
            ->where('nome', $request->nome)
            ->where('cpf', $request->cpf)
            ->where('tel', $request->telefone)
            ->first();

        if ($user) {
            return response()->json(['message' => 'Login bem-sucedido', 'idParticipante' => $user->idParticipante]);
        }

        return response()->json(['error' => 'Dados incorretos'], 404);
    }

    public function adminLogin(Request $request)
    {
        $admin = DB::table('participante')
            ->where('nome', $request->nome)
            ->where('senha', $request->senha)
            ->first();

        if ($admin) {
            return response()->json(['message' => 'Login bem-sucedido', 'idParticipante' => $admin->idParticipante]);
        }

        return response()->json(['error' => 'Dados incorretos'], 404);
    }

    public function comprar(Request $request)
    {
        $validated = $request->validate([
            'idParticipante' => 'required|integer',
            'numeros' => 'required|array',
        ]);

        $values = array_map(fn($numero) => [
            'numero' => $numero,
            'idParticipante' => $validated['idParticipante'],
        ], $validated['numeros']);

        DB::table('rifa')->insert($values);

        return response()->json(['message' => 'Números comprados com sucesso!']);
    }
    

    public function numerosComprados()
    {
        $numeros = DB::table('rifa')->pluck('numero');
        return response()->json($numeros);
    }

    public function totalNumeros()
    {
        $total = DB::table('rifa')->count();
        return response()->json(['totalNumeros' => $total]);
    }

    public function buscarParticipante(Request $request)
    {
        $numero = $request->query('numero');
        $result = DB::table('rifa')
            ->join('participante', 'rifa.idParticipante', '=', 'participante.idParticipante')
            ->where('rifa.numero', $numero)
            ->select('participante.nome', 'participante.cpf')
            ->first();

        if ($result) {
            return response()->json($result);
        }

        return response()->json(['error' => 'Nenhum participante encontrado'], 404);
    }

    public function limparRifa()
    {
        DB::table('rifa')->truncate();
        DB::table('participante')->where('idParticipante', '!=', '34')->delete();

        return response()->json(['message' => 'Rifa limpa com sucesso!']);
    }
}
