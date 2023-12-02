<?php

namespace App\Http\Controllers;

use App\Http\Requests\HealthCenter\AddHealthCenterMemberRequest;
use App\Http\Requests\HealthCenter\CreateHealthCenterRequest;
use App\Http\Requests\HealthCenter\UpdateHealthCenterRequest;
use App\Models\HealthCenter;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class HealthCenterController extends Controller
{
    public function store(CreateHealthCenterRequest $request)
    {
        $healthCenter = null;
        try {
            $payload = $request->all();
            $healthCenter = HealthCenter::create([
                'name' => $payload['name'],
            ]);
            $healthCenter->address()->create([
                'city_code' => $payload['city_code'],
                'barangay_code' => $payload['barangay_code'],
                'house_number' => $payload['house_number'],
                'street' => $payload['street'],
                'map_url' => $payload['map_url'],
            ]);
            $image = $payload['image'] ?? null;
            if (isset($image)) {
                try {
                    $timestamp = Carbon::now()->format('YmdHisu');
                    $name = "{$healthCenter->id}_health_center_${timestamp}.{$image->getClientOriginalExtension()}";
                    $path = $image->storeAs('images', $name);
                    $healthCenter->image()->create([
                        'name' => $name,
                        'original_name' => $image->getClientOriginalName(),
                        'extension' => ".{$image->getClientOriginalExtension()}",
                        'size' => floatval(Storage::size($path) / 1024),
                        'path' => $path,
                    ]);
                } catch (Exception $e) {
                    throw new Exception($e->getMessage());
                }
            }

            return customResponse()
                ->data($healthCenter)
                ->message('Create request done.')
                ->success()
                ->generate();
        } catch (Exception $e) {
            if (isset($healthCenter)) {
                $healthCenter->image()->delete();
                $healthCenter->delete();
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
            $sortBy = $payload['sort_by'] ?? 'desc';
            $healthCenters = HealthCenter::with(['address', 'image', 'members'])
                ->orderBy('id', $sortBy)
                ->get();
            return customResponse()
                ->data($healthCenters)
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
            $healthCenter = HealthCenter::with([
                'address',
                'image',
                'members',
                'members.user',
            ])->findOrFail($id);
            return customResponse()
                ->data($healthCenter)
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

    public function update(UpdateHealthCenterRequest $request, $id)
    {
        try {
            $payload = $request->all();
            $healthCenter = HealthCenter::findOrFail($id);
            $healthCenter->update([
                'name' => $payload['name'],
            ]);
            $healthCenter->address()->update([
                'city_code' => $payload['city_code'],
                'barangay_code' => $payload['barangay_code'],
                'house_number' => $payload['house_number'],
                'street' => $payload['street'],
            ]);
            $image = $payload['image'] ?? null;
            if (isset($image)) {
                $image = $request->file('image');
                $timestamp = Carbon::now()->format('YmdHisu');
                $name = "{$healthCenter->id}_health_center_${timestamp}.{$image->getClientOriginalExtension()}";
                $path = $image->storeAs('images', $name);
                $oldPath = $healthCenter->image->path;
                $healthCenter->image()->update([
                    'name' => $name,
                    'original_name' => $image->getClientOriginalName(),
                    'extension' => ".{$image->getClientOriginalExtension()}",
                    'size' => floatval(Storage::size($path) / 1024),
                    'path' => $path,
                ]);
                Storage::disk('local')->delete($oldPath);
            }

            return customResponse()
                ->data($healthCenter->load('address', 'image'))
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
            $healthCenter = HealthCenter::findOrFail($id);
            $healthCenter->image()->delete();
            $healthCenter->delete();
            return customResponse()
                ->data($healthCenter)
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

    public function addMember(AddHealthCenterMemberRequest $request, $id)
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
            $image = $payload['image'] ?? null;
            if (isset($image)) {
                try {
                    $timestamp = Carbon::now()->format('YmdHisu');
                    $name = "{$user->id}_user_${timestamp}.{$image->getClientOriginalExtension()}";
                    $path = $image->storeAs('images', $name);
                    $user->image()->create([
                        'name' => $name,
                        'original_name' => $image->getClientOriginalName(),
                        'extension' => ".{$image->getClientOriginalExtension()}",
                        'size' => floatval(Storage::size($path) / 1024),
                        'path' => $path,
                    ]);
                } catch (Exception $e) {
                    throw new Exception($e->getMessage());
                }
            }
            $healthCenter = HealthCenter::findorFail($id);
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
}
