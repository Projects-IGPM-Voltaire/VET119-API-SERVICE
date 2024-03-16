<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\ResetUserPasswordRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\HealthCenter;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function store(CreateUserRequest $request)
    {
        $user = null;
        try {
            $payload = $request->all();
            $user = User::create([
                'first_name' => $payload['first_name'],
                'last_name' => $payload['last_name'],
                'mobile_number' => $payload['mobile_number'],
                'password' => $payload['password'],
                'level' => 'admin',
                'email' => $payload['email'],
            ]);

            return customResponse()
                ->data($user)
                ->message('Create request done.')
                ->success()
                ->generate();
        } catch (Exception $e) {
            return customResponse()
                ->data([])
                ->message($e->getMessage())
                ->failed()
                ->generate();
        }
    }

    public function index(Request $request)
    {
        try {
            $payload = $request->all();
            $query = User::query();
            $sortBy = $payload['sort_by'] ?? 'desc';
            $search = $payload['search'] ?? null;
            if (isset($search)) {
                $query->where(
                    DB::raw('concat(first_name, last_name)'),
                    'like',
                    '%' . strtolower($search) . '%'
                );
            }

            $users = $query
                ->orderBy('id', $sortBy)
                ->get();
            return customResponse()
                ->data($users)
                ->message('List request done.')
                ->success()
                ->generate();
        } catch (Exception $e) {
            return customResponse()
                ->data([])
                ->message($e->getMessage())
                ->failed()
                ->generate();
        }
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return customResponse()
                ->data($user)
                ->message('Get request done.')
                ->success()
                ->generate();
        } catch (Exception $e) {
            return customResponse()
                ->data($e->getMessage())
                ->message($e->getMessage())
                ->failed()
                ->generate();
        }
    }

    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $payload = $request->all();
            $user = User::findOrFail($id);
            $user->update([
                'first_name' => $payload['first_name'],
                'last_name' => $payload['last_name'],
                'mobile_number' => $payload['mobile_number']
            ]);

            return customResponse()
                ->data($user)
                ->message('Update request done.')
                ->success()
                ->generate();
        } catch (Exception $e) {
            return customResponse()
                ->data($e->getMessage())
                ->message($e->getMessage())
                ->failed()
                ->generate();
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return customResponse()
                ->data($user)
                ->message('Delete request done.')
                ->success()
                ->generate();
        } catch (Exception $e) {
            return customResponse()
                ->data($e->getMessage())
                ->message($e->getMessage())
                ->failed()
                ->generate();
        }
    }

    public function resetPassword(ResetUserPasswordRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update([
                'password' => bcrypt($request->input('password')),
            ]);
            return customResponse()
                ->data($user)
                ->message('Reset password done.')
                ->success()
                ->generate();
        } catch (Exception $e) {
            return customResponse()
                ->data($e->getMessage())
                ->message($e->getMessage())
                ->failed()
                ->generate();
        }
    }
}
