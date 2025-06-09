<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Exception;


class UserController extends Controller
{

    
    public function index()
    {
        try {
            $pdo = DB::getPdo();
            $stmt = $pdo->prepare('SELECT id, name, cpf, email, birthday, phone, created_at FROM users');
            $stmt->execute();
            $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return response()->json($users, 200);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erro ao listar usuários',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

     public function store(StoreUserRequest $request)
    {
        try {
            $pdo = DB::getPdo();

            $stmt = $pdo->prepare('
                INSERT INTO users (name, cpf, email, password, birthday, phone, created_at, updated_at)
                VALUES (:name, :cpf, :email, :password, :birthday, :phone, NOW(), NOW())
            ');

            $hashedPassword = Hash::make($request->input('password'));

            $name = $request->input('name');
            $cpf = $request->input('cpf');
            $email = $request->input('email');
            $password = $hashedPassword;
            $birthday = $request->input('birthday');
            $phone = $request->input('phone');

            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':email', $email) ;
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':birthday', $birthday);
            $stmt->bindParam(':phone', $phone);

            $stmt->execute();

            return response()->json(['message' => 'Usuário criado com sucesso.'], 201);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erro ao criar usuário.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $pdo = DB::getPdo();

            $checkEmail = $pdo->prepare('SELECT id FROM users WHERE email = :email AND id != :id');
            $checkEmail->execute([
                ':email' => $request->input('email'),
                ':id' => $id
            ]);
            if ($checkEmail->fetch()) {
                return response()->json([
                    'error' => 'E-mail já está em uso por outro usuário.'
                ], 422);
            }

            $checkPhone = $pdo->prepare('SELECT id FROM users WHERE phone = :phone AND id != :id');
            $checkPhone->execute([
                ':phone' => $request->input('phone'),
                ':id' => $id
            ]);
            if ($checkPhone->fetch()) {
                return response()->json([
                    'error' => 'Telefone já está em uso por outro usuário.'
                ], 422);
            }

            $sql = 'UPDATE users SET name = :name, email = :email, birthday = :birthday, phone = :phone';
            if ($request->filled('password')) {
                $sql .= ', password = :password';
            }
            $sql .= ', updated_at = NOW() WHERE id = :id';

            $stmt = $pdo->prepare($sql);

            $params = [
                ':name' => $request->input('name'),
                ':email' => $request->input('email'),
                ':birthday' => $request->input('birthday'),
                ':phone' => $request->input('phone'),
                ':id' => $id,
            ];

            if ($request->filled('password')) {
                $params[':password'] = Hash::make($request->input('password'));
            }

            $stmt->execute($params);

            return response()->json(['message' => 'Usuário atualizado com sucesso.']);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erro ao atualizar usuário',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $pdo = DB::getPdo();

            $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return response()->json(['message' => 'Usuário deletado com sucesso.']);
            } else {
                return response()->json(['message' => 'Usuário não encontrado.'], 404);
            }

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erro',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $pdo = DB::getPdo();
            $stmt = $pdo->prepare('SELECT id, name, cpf, email, birthday, phone FROM users WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$user) {
                return response()->json(['error' => 'Usuário não encontrado.'], 404);
            }

            return response()->json($user);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erro',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}