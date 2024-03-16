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
            $userID = $payload['user_id'];
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


}
