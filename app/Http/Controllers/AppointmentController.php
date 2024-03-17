<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment;
use App\Models\Pet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $appointment = null;
        try {
            $payload = $request->all();

            $todayAppointmentCount = Appointment::whereDate('date', $payload['date'])
                ->get()
                ->count();
            $patientNumber = sprintf('%04d', intval($todayAppointmentCount) + 1);
            $currentDate = new \DateTime($payload['date']);
            $currentDate = $currentDate->format('ymd');
            $authID = Auth::id();
            $userID = sprintf('%04d', intval($authID));
            $referenceNumber = "{$currentDate}-{$userID}-{$patientNumber}";

            $appointment = Appointment::create([
                'purpose' => $payload['purpose'],
                'date' => $payload['date'],
                'time_from' => $payload['time_from'],
                'time_to' => $payload['time_to'],
                'user_id' => $authID,
                'reference_number' => $referenceNumber
            ]);

            $petPayload = $payload['pets'];
            foreach ($petPayload as $pet)
            {
                $newPet = Pet::create([
                    'name' => $pet['name'],
                    'species' => $pet['species'],
                    'breed' => $pet['breed'] ?? null,
                    'user_id' => $authID,
                ]);
                $appointment->pets()->attach($newPet);
            }

            return customResponse()
                ->data($appointment)
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
            $query = Appointment::with('pets')->withCount('pets');
            $userID = Auth::id();
            $query->where('user_id', $userID);
            $condition = $payload['condition'] ?? null;

            if ($condition == 'upcoming')
            {
                $now = now();
                $query
                    ->whereDate(
                        'date',
                        '>=',
                        $now
                    );
            }

            if ($condition == 'past')
            {
                $now = now();
                $query
                    ->whereDate(
                        'date',
                        '<',
                        $now
                    );
            }

            $appointments = $query->get();

            return customResponse()
                ->data($appointments)
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
            $appointment = Appointment::with('pets')->withCount('pets')->findOrFail($id);
            return customResponse()
                ->data($appointment)
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

}
