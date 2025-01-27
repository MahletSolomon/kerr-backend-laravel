<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\JobContract;
use App\Models\JobOffer;
use App\Models\JobBid;
use App\Models\JobCompletionRequest;
use App\Models\Tag;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class JobController extends Controller
{
    public function postJob(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'userID' => 'required|integer|exists:users,id',
                'jobTitle' => 'required|string',
                'jobDescription' => 'required|string',
                'jobPrice' => 'required|numeric',
                'jobNegotiation' => 'required|boolean',
                'jobPublic' => 'required|boolean',
                'tag' => 'nullable|array',
                'tag.*' => 'string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
            $job = Job::create([
                'user_id' => $request->input('userID'),
                'job_title' => $request->input('jobTitle'),
                'job_description' => $request->input('jobDescription'),
                'job_price' => $request->input('jobPrice'),
                'job_negotiation' => $request->input('jobNegotiation'),
                'job_public' => $request->input('jobPublic'),
            ]);
            if ($request->has('tag')) {
                foreach ($request->input('tag') as $tagName) {
                    Tag::create([
                        'job_id' => $job->id,
                        'name' => $tagName,
                    ]);
                }
            }
            return response()->json(['jobID' => $job->id], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'There was an issue with the server'], 500);
        }
    }
    public function getJob($id)
    {
        try {
            $job = DB::table('jobs as j')
                ->select(
                    'cu.id as client_id',
                    'cu.location as client_location',
                    'cu.first_name as client_first_name',
                    'cu.last_name as client_last_name',
                    'cu.profile_picture as client_profile_picture',
                    'cui.average_rating as client_rating',
                    'cui.success_percentage',
                    'cui.total_job_completed',
                    'j.job_title',
                    'j.job_description',
                    'j.created_at',
                    'j.job_state',
                    'j.job_price',
                    'j.job_negotiation',
                    'jc.contract_state',
                    'fu.id as freelance_id',
                    'fu.location as freelance_location',
                    'fu.first_name as freelance_first_name',
                    'fu.last_name as freelance_last_name',
                    'fu.profile_picture as freelance_profile_picture',
                    'fui.average_rating as freelance_rating'
                )
                ->leftJoin('users as cu', 'j.user_id', '=', 'cu.id')
                ->leftJoin('user_informations as cui', 'cu.id', '=', 'cui.user_id')
                ->leftJoin('job_contracts as jc', 'j.id', '=', 'jc.job_id')
                ->leftJoin('users as fu', 'jc.freelance_id', '=', 'fu.id')
                ->leftJoin('user_informations as fui', 'fu.id', '=', 'fui.user_id')
                ->where('j.id', $id)
                ->first();

            if (!$job) {
                return response()->json(['message' => 'Job not found'], 404);
            }

            return response()->json(['data' => $job], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'There was an issue with the server'], 500);
        }
    }
    public function getAllJobs(Request $request)
    {
        try {
            $search = $request->query('search', '');

            $jobs = DB::table('jobs as jb')
                ->select(
                    'ur.id as user_id',
                    'ur.profile_picture',
                    'ur.location',
                    DB::raw("CONCAT(ur.first_name, ' ', ur.last_name) as full_name"),
                    'jb.id as job_id',
                    'jb.job_title',
                    'jb.job_description',
                    'jb.job_price',
                    DB::raw("GROUP_CONCAT(tg.name) as tags")
                )
                ->leftJoin('users as ur', 'ur.id', '=', 'jb.user_id')
                ->leftJoin('tags as tg', 'jb.id', '=', 'tg.job_id')
                ->where('jb.job_title', 'LIKE', "%{$search}%") // Filter by search term
                ->groupBy('jb.id')
                ->orderBy('jb.id')
                ->limit(20)
                ->offset(0)
                ->get();

            return response()->json(['data' => $jobs], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'There was an issue with the server'], 500);
        }
    }
    public function postJobCompletionRequest(Request $request, $id)
    {
        try {
            $request->validate([
                'userID' => 'required|integer',
                'image' => 'required|string',
                'message' => 'required|string',
            ]);

            $userID = $request->input('userID');
            $image = $request->input('image');
            $message = $request->input('message');

            $newRequest = JobCompletionRequest::create([
                'user_id' => $userID,
                'job_id' => $id,
                'image' => $image,
                'message' => $message,
                'created_at' => now(), // Automatically sets the current timestamp
            ]);

            return response()->json([
                'message' => 'Job completion request created successfully',
                'new_request_id' => $newRequest->id,
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'There was an issue with the server'], 500);
        }
    }
    public function getJobCompletionRequest($id)
    {
        try {
            $requests = JobCompletionRequest::where('job_id', $id)->get();

            return response()->json(['data' => $requests], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'There was an issue with the server'], 500);
        }
    }
    public function deleteJobCompletionRequest($id)
    {
        try {
            // Delete job completion requests for the given job ID
            $deleted = JobCompletionRequest::where('job_id', $id)->delete();

            // Check if any records were deleted
            if ($deleted > 0) {
                return response()->json(['message' => 'Job completion requests deleted successfully'], 200);
            } else {
                return response()->json(['message' => 'No job completion requests found for the given job ID'], 404);
            }

        } catch (\Exception $e) {
            \Log::error('Error: ' . $e->getMessage());
            return response()->json(['message' => 'There was an issue with the server'], 500);
        }
    }
    public function getJobBid($id)
    {
        try {
            $bids = JobBid::select(
                'job_bids.id as id',
                'job_bids.user_id as user_id',
                'job_bids.bid_counter_price',
                'job_bids.bid_pitch',
                'users.profile_picture',
                'users.first_name',
                'users.last_name'
            )
                ->leftJoin('jobs', 'jobs.id', '=', 'job_bids.job_id')
                ->leftJoin('users', 'users.id', '=', 'job_bids.user_id')
                ->where('jobs.id', $id)
                ->get();

            return response()->json(['data' => $bids], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'There was an issue with the server'], 500);
        }
    }
    public function getJobOffer($id)
    {
        try {
            $offers = JobOffer::select(
                'job_offers.id as id',
                'job_offers.user_id as user_id',
                'users.first_name',
                'users.last_name',
                'users.username',
                'users.profile_picture',
                'jobs.id as job_id',
                'jobs.job_title',
                'jobs.job_description'
            )
                ->leftJoin('jobs', 'job_offers.job_id', '=', 'jobs.id')
                ->leftJoin('users', 'job_offers.user_id', '=', 'users.id')
                ->where('jobs.id', $id)
                ->get();

            return response()->json(['data' => $offers], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'There was an issue with the server'], 500);
        }
    }
    public function postJobContract(Request $request, $id)
    {
        try {
            $request->validate([
                'clientID' => 'required|integer',
                'freelanceID' => 'required|integer',
                'price' => 'nullable|integer|min:0',
            ]);

            $clientID = $request->input('clientID');
            $freelanceID = $request->input('freelanceID');
            $price = $request->input('price', -1);

            DB::beginTransaction();

            $contract = JobContract::create([
                'client_id' => $clientID,
                'freelance_id' => $freelanceID,
                'job_id' => $id,
                'contract_state' => 1, // Assuming 1 represents an active contract
                'created_at' => now(),
            ]);

            if ($price > 0) {
                Job::where('id', $id)->update(['job_price' => $price]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Job contract created successfully',
                'new_contract_id' => $contract->id,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'There was an issue with the server'], 500);
        }
    }
    public function finishJob($id)
    {
        try {
            $updated = JobContract::where('job_id', $id)
                ->where('contract_state', '!=', 2) // Avoid updating already finished contracts
                ->update(['contract_state' => 2]);

            if ($updated > 0) {
                return response()->json(['message' => 'Job marked as finished successfully'], 200);
            } else {
                return response()->json(['message' => 'No active job contracts found for the given job ID'], 404);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => 'There was an issue with the server'], 500);
        }
    }
    public function deleteJob($id)
    {
        try {
            $job = Job::find($id);
            if (!$job) {
                return response()->json(['message' => 'Job not found'], 404);
            }
            $job->delete();
            return response()->json(['message' => 'Job deleted successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'There was an issue with the server'], 500);
        }
    }
}
