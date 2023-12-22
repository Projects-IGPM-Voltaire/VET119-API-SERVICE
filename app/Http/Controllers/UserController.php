<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateUserRequest;
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
                'birthday' => $payload['birthday'],
                'mobile_number' => $payload['mobile_number'],
                'password' => $payload['password'],
                'level' => 'admin',
            ]);
            $image = $request->file('image') ?? null;
            if (isset($image)) {
                try {
                    $timestamp = Carbon::now()->format('YmdHisu');
                    $name = "{$user->id}_user_${timestamp}.{$image->getClientOriginalExtension()}";
                    $path = Storage::disk('public')->putFile('images', $image);
                    $user->image()->create([
                        'name' => $name,
                        'original_name' => $image->getClientOriginalName(),
                        'extension' => ".{$image->getClientOriginalExtension()}",
                        'size' => 0,
                        'path' => $path,
                    ]);
                } catch (Exception $e) {
                    throw new Exception($e->getMessage());
                }
            }
            $healthCenter = HealthCenter::findorFail(
                $payload['health_center_id']
            );
            $healthCenter->members()->create([
                'user_id' => $user->id,
                'position' => $payload['position'],
            ]);

            return customResponse()
                ->data($user)
                ->message('Create request done.')
                ->success()
                ->generate();
        } catch (Exception $e) {
            if (isset($user)) {
                $user->image()->delete();
                $user->delete();
            }
            return customResponse()
                ->data([])
                ->message($e->getMessage())
                ->success()
                ->generate();
        }
    }

    public function index(Request $request)
    {
        try {
            $payload = $request->all();
            $query = User::query();
            $healthCenterID = $payload['health_center_id'] ?? null;
            $sortBy = $payload['sort_by'] ?? 'desc';
            $search = $payload['search'] ?? null;
            $position = $payload['position'] ?? null;
            if (isset($healthCenterID)) {
                $query->whereHas('health_center_member', function ($q) use (
                    $healthCenterID
                ) {
                    $q->where('health_center_id', $healthCenterID);
                });
            }
            if (isset($search)) {
                $query->where(
                    DB::raw('concat(first_name, last_name)'),
                    'like',
                    '%' . strtolower($search) . '%'
                );
            }
            if (isset($position)) {
                $query->whereHas('health_center_member', function ($q) use (
                    $position
                ) {
                    $q->where('position', $position);
                });
            }

            $users = $query
                ->with(['image', 'health_center_member'])
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
                ->success()
                ->generate();
        }
    }

    public function show($id)
    {
        try {
            $user = User::with('image')->findOrFail($id);
            return customResponse()
                ->data($user)
                ->message('Get request done.')
                ->success()
                ->generate();
        } catch (Exception $e) {
            return customResponse()
                ->data($e->getMessage())
                ->message($e->getMessage())
                ->success()
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
                'birthday' => $payload['birthday'],
            ]);
            $image = $request->file('image') ?? null;
            if (isset($image)) {
                $timestamp = Carbon::now()->format('YmdHisu');
                $name = "{$user->id}_user_${timestamp}.{$image->getClientOriginalExtension()}";
                $path = Storage::disk('public')->putFile('images', $image);
                $user->image()->update([
                    'name' => $name,
                    'original_name' => $image->getClientOriginalName(),
                    'extension' => ".{$image->getClientOriginalExtension()}",
                    'size' => 0,
                    'path' => $path,
                ]);
            }

            return customResponse()
                ->data($user->load('image'))
                ->message('Update request done.')
                ->success()
                ->generate();
        } catch (Exception $e) {
            return customResponse()
                ->data($e->getMessage())
                ->message($e->getMessage())
                ->success()
                ->generate();
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->image()->delete();
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
                ->success()
                ->generate();
        }
    }
}
