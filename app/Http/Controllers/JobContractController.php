<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobContract;
use Illuminate\Support\Facades\DB;

class JobContractController extends Controller
{
    public function getJobContract(Request $request)
    {
        try {
            $userID = $request->query('userID');
            $page = $request->query('page', 1); // Default to page 1 if not provided
            $perPage = 20; // Number of records per page
            $offset = ($page - 1) * $perPage;

            // Query using Eloquent
            $jobContracts = JobContract::select([
                'job_contracts.id',
                'job_contracts.created_at',
                'job_contracts.job_id',
                'job_contracts.contract_state',
                'jobs.job_title',
                'jobs.job_description',
                DB::raw('IF(client.id = ?, free.first_name, client.first_name) AS first_name', [$userID]),
                DB::raw('IF(client.id = ?, free.last_name, client.last_name) AS last_name', [$userID]),
                DB::raw('IF(client.id = ?, free.location, client.location) AS location', [$userID]),
                DB::raw('IF(client.id = ?, free.profile_picture, client.profile_picture) AS profile_picture', [$userID])
            ])
                ->leftJoin('users as client', 'job_contracts.client_id', '=', 'client.id')
                ->leftJoin('users as free', 'job_contracts.freelance_id', '=', 'free.id')
                ->leftJoin('jobs', 'job_contracts.job_id', '=', 'jobs.id')
                ->where(function ($query) use ($userID) {
                    $query->where('client_id', $userID)
                        ->orWhere('freelance_id', $userID);
                })
                ->orderBy('job_contracts.created_at')
                ->offset($offset)
                ->limit($perPage)
                ->get();

            return response()->json(['data' => $jobContracts], 200);

        } catch (\Exception $e) {
                return response()->json(['message' => 'There was an issue with the server'], 500);
        }
    }

}
