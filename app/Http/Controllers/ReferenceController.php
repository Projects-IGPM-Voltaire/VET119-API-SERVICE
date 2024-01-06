<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReferenceController extends Controller
{
    public function getBarangays(Request $request)
    {
        try {
            $payload = $request->all();
            $search = $payload['search'] ?? null;
            $page = $payload['page'] ?? 1;
            $perPage = $payload['per_page'] ?? 5;
            $query = Barangay::query();
            $sortBy = $payload['sort_by'] ?? 'desc';
            if (isset($search)) {
                $_search = strtolower($search);
                $query->where(DB::raw('lower(name)'), 'like', "%{$_search}%");
            }
            $barangays = $query
                ->with(['city'])
                ->orderBy('created_at', $sortBy)
                ->paginate($perPage, ['*'], 'page', $page);

            return customResponse()
                ->data($barangays)
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
}
