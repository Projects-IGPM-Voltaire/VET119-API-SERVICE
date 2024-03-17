<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment;
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

            $todayAppointmentCount = Appointment::whereDate('created_at', today())
                ->get()
                ->count();
            $patientNumber = sprintf('%04d', intval($todayAppointmentCount) + 1);
            $currentDate = now()->format('ymd');
            $userID = sprintf('%04d', intval($payload['user_id']));
            $referenceNumber = "{$currentDate}-{$userID}-{$patientNumber}";

            $appointment = Appointment::create([
                'purpose' => $payload['purpose'],
                'date' => $payload['date'],
                'time_from' => $payload['time_from'],
                'time_to' => $payload['time_to'],
                'user_id' => $payload['user_id'],
                'reference_number' => $referenceNumber
            ]);

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
            $query = Appointment::query();
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
            $appointment = Appointment::findOrFail($id);
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
