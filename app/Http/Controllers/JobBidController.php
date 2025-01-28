<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobBid;
class JobBidController extends Controller
{
    public function postJobBid(Request $request)
    {
        try {
            $request->validate([
                'userID' => 'required|integer',
                'jobID' => 'required|integer',
                'bidPitch' => 'required|string',
                'bidCounterPrice' => 'required|numeric',
            ]);

            $userID = $request->input('userID');
            $jobID = $request->input('jobID');
            $bidPitch = $request->input('bidPitch');
            $bidCounterPrice = $request->input('bidCounterPrice');

            $jobBid = JobBid::create([
                'user_id' => $userID,
                'job_id' => $jobID,
                'bid_pitch' => $bidPitch,
                'bid_counter_price' => $bidCounterPrice,
                'created_at' => now(),
            ]);

            // Return the response with the new bid ID
            return response()->json([
                'message' => 'Success Creation',
                'jobBidID' => $jobBid->id,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'There was an issue with the server'], 500);
        }
    }
    public function getJobBid(Request $request)
    {
        try {
            $userID = $request->query('userID');

            if (!$userID) {
                return response()->json(['message' => 'User ID is required'], 400);
            }

            $jobBids = JobBid::select(
                'jobs.job_price',
                'jobs.id as job_id',
                'jobs.job_title',
                'jobs.job_description',
                'job_bids.user_id',
                'job_bids.id',
                'job_bids.bid_pitch'
            )
                ->leftJoin('jobs', 'jobs.id', '=', 'job_bids.job_id')
                ->where('job_bids.user_id', $userID)
                ->get();

            return response()->json(['data' => $jobBids], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'There was an issue with the server'], 500);
        }
    }
    public function deleteBid($id)
    {
        try {
            $jobBid = JobBid::find($id);

            if (!$jobBid) {
                return response()->json(['message' => 'Job bid not found'], 404);
            }

            $jobBid->delete();

            return response()->json(['message' => 'Success'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'There was an issue with the server'], 500);
        }
    }
}
