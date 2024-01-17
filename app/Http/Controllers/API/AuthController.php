<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\RegisterUserRequest;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'index', 'update']]);
    }

    public function index()
    {
        $users = User::all();

        return response()->json([
            'status_code' => 201,
            'status_message' => 'liste des users',
            'formation' => $users
        ]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {

        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = Auth::user();

        return $this->respondWithToken($token, $user);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Déconnexion réussie']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $user = auth()->user();
        return $this->respondWithToken(auth()->refresh(), $user);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $user)
    {
        return response()->json([
            'status_code' => 200,
            'utlisatur' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function register(RegisterUserRequest $request)
    {
        try {

            if ($request->password !== $request->confirmPassword) {
                return response()->json([
                    'status_code' => 400,
                    'status_message' => 'Le mot de passe et la confirmation ne correspondent pas.',
                ], 400);
            }

            $user = new User();
            $user->name = $request->name;
            $user->firstName = $request->firstName;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->role_id = 1;
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'status_code' => 201,
                'status_message' => 'utilisateur ajouté avec succes',
                'status_body' => $user
            ]);
        } catch (Exception $e) {
            return response()->json([$e]);
        }
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $user->update($request->only(['name', 'firstName', 'phone', 'email']));

            return response()->json([
                'status_code' => 200,
                'status_message' => 'Informations utilisateur mises à jour avec succès',
                'user' => $user,
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function archive(User $user)
    {
        try {
            $user->update([
                'isArchived' => 1
            ]);
            $user->save();
            return response()->json([
                'status_code' => 200,
                'status_message' => "Vous avez archivés cet utilisateur"
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    public function userNonArchive()
    {
        try {
            return response()->json([
                'status_code' => 200,
                'status_message' => 'Voici la liste de tous les utilisateurs non archivés',
                'utilisateurs' => User::where('isArchived', 0)->get(),
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    public function userArchive()
    {
        try {
            return response()->json([
                'status_code' => 200,
                'status_message' => 'Voici la liste de tous les utilisateurs archivés',
                'utilisateurs' => User::where('isArchived', 1)->get(),
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
}
