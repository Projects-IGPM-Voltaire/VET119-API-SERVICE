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
            $currentDate = $currentDate->format('mdy');
            $authID = Auth::id();
            $user = User::findOrFail($authID);
            $userID = sprintf('%04d', intval($authID));
            $referenceNumber = "{$currentDate}-{$userID}-{$patientNumber}";
            $firstName = $payload['first_name'] ?? $user->first_name;
            $lastName = $payload['last_name'] ?? $user->last_name;

            $appointment = Appointment::create([
                'purpose' => $payload['purpose'],
                'date' => $payload['date'],
                'time_from' => $payload['time_from'],
                'time_to' => $payload['time_to'],
                'user_id' => $authID,
                'reference_number' => $referenceNumber,
                'first_name' => $firstName,
                'last_name' => $lastName,
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
            error_log($e->getMessage());
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
            $query = Appointment::with('user')->with('pets')->withCount('pets');
            $appointments = $query->get();

            #Debugging
            $payload['search'] = 'arnel';

            if (isset($payload['search']))
            {
                $searchTerm = '%' . $payload['search'] . '%';
                if (preg_match('~[0-9]+~', $searchTerm))
                {
                    $query->where('reference_number', 'like', $searchTerm);
                }
                else
                {
                    $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$searchTerm]);
                }
            }

            if (isset($payload['dateFrom']))
            {
                $query->whereDate(
                    'date',
                    '>=',
                    $payload['dateFrom']
                );
            }

            if (isset($payload['dateTo']))
            {
                $query->whereDate(
                    'date',
                    '<=',
                    $payload['dateTo']
                );
            }

            if (isset($payload['timeFrom']))
            {
                $query->whereTime(
                    'time_from',
                    '>=',
                    $payload['timeFrom']
                );
            }

            if (isset($payload['timeTo']))
            {
                $query->whereTime(
                    'time_from',
                    '<=',
                    $payload['timeTo']
                );
            }

            if (isset($payload['purpose']))
            {
                $query->where(
                    'purpose',
                    'like',
                    $payload['purpose']
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

    public function check($condition)
    {
        try {
            $query = Appointment::with('user')->with('pets')->withCount('pets');
            $userID = Auth::id();
            $query->where('user_id', $userID);

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

    public function filter($date)
    {
        try {
            $query = Appointment::query();
            $query->where('date', $date);
            $query->select('time_from');

            $appointments = $query->get();

            return customResponse()
                ->data($appointments)
                ->message('List request done.')
                ->success()
                ->generate();
        } catch (Exception $e) {
            return customResponse()
                ->data($e->getMessage())
                ->message('Debugging')
                ->failed()
                ->generate();
        }
    }

    public function delete(Request $request)
    {
        try {
            $payload = $request->all();

            $refNumbers = $payload['appointments'];

            foreach ($refNumbers as $refNumber)
            {
                $appointment = Appointment::where('reference_number', $refNumber)->firstOrFail();
                $appointment->delete();
            }

            return customResponse()
                ->data([])
                ->message('Delete request done.')
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
