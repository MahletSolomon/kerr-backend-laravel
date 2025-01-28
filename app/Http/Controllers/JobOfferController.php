<?php

namespace App\Http\Controllers;
use App\Models\JobOffer;
use Illuminate\Http\Request;

class JobOfferController extends Controller
{
    public function postJobOffer(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer',
                'job_id' => 'required|integer',
            ]);

            $jobOffer = JobOffer::create([
                'user_id' => $request->user_id,
                'job_id' => $request->job_id,
            ]);

            $newOfferId = $jobOffer->id;

            return response()->json([
                'message' => 'Success Creation',
                'job_offer_id' => $newOfferId,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'There was an issue with the server',
            ], 500);
        }
    }
    public function getJobOffer(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer',
            ]);
            $userId = $request->query('user_id');

            $jobOffers = JobOffer::with(['job.user', 'user'])
                ->where('user_id', $userId)
                ->get();

            $data = $jobOffers->map(function ($jobOffer) {
                return [
                    'id' => $jobOffer->id,
                    'user_id' => $jobOffer->user_id,
                    'first_name' => $jobOffer->user->first_name,
                    'last_name' => $jobOffer->user->last_name,
                    'profile_picture' => $jobOffer->user->profile_picture,
                    'job_id' => $jobOffer->job->id,
                    'job_title' => $jobOffer->job->job_title,
                    'job_description' => $jobOffer->job->job_description,
                    'job_price' => $jobOffer->job->job_price,
                ];
            });

            return response()->json([
                'data' => $data,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'There was an issue with the server',
            ], 500);
        }
    }
    public function deleteOffer(Request $request, $id)
    {
        try {
            $jobOffer = JobOffer::find($id);

            if (!$jobOffer) {
                return response()->json([
                    'message' => 'Job offer not found',
                ], 404);
            }

            $jobOffer->delete();

            return response()->json([
                'message' => 'Success',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'There was an issue with the server',
            ], 500);
        }
    }
}
