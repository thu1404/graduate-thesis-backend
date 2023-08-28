<?php

namespace App\Http\Controllers;

use App\Models\HiringProcessRound;
use App\Models\JobsCV;
use Illuminate\Http\Request;

class HiringProcessRoundController extends Controller
{
    //
    //  'id',
    //     'name',

    //     'process_id',

    public function index(Request $request)
    {
        try {

            $hiringProcessRound = HiringProcessRound::orderBy('created_at', 'desc');
            $process_id = $request->input('process_id');
            if ($process_id) {
                $hiringProcessRound = HiringProcessRound::where('process_id', $process_id);
            }
            $hiringProcessRound = $hiringProcessRound->get();
            $hiringProcessRound->map(function ($item) {
                $item->hiringProcessRound;
            });
            return response()->json([
                'status_code' => 200,
                'hiring_process_round' => $hiringProcessRound,
                'message' => 'Get hiring process round success'
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in get hiring process round',
                'error' => $error,
            ]);
        }
    }

    //store

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required',
                'process_id' => 'required',
            ]);
            $hiringProcessRound = HiringProcessRound::create($validated);
            return response()->json([
                'status_code' => 200,
                'hiring_process_round' => $hiringProcessRound,
                'message' => 'Create hiring process round success'
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in create hiring process round',
                'error' => $error,
            ]);
        }
    }

    //edit

    public function edit(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required',
                'process_id' => 'required',
            ]);
            $hiringProcessRound = HiringProcessRound::find($id);
            $hiringProcessRound->update($validated);
            return response()->json([
                'status_code' => 200,
                'hiring_process_round' => $hiringProcessRound,
                'message' => 'Update hiring process round success'
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in update hiring process round',
                'error' => $error,
            ]);
        }
    }

    //delete

    public function destroy(Request $request, $id)
    {
        try {
            $hiringProcessRound = HiringProcessRound::find($id);

            if (!$hiringProcessRound) {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Hiring process round not found',
                ]);
            }

            $checkCvOnRound = JobsCV::where('round_id', $id)->first();
            if ($checkCvOnRound) {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Hiring process round is using',
                ]);
            }




            $hiringProcessRound->delete();
            return response()->json([
                'status_code' => 200,
                'message' => 'Delete hiring process round success',
                'data' => []
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in delete hiring process round',
                'error' => $error,
            ]);
        }
    }

    //show

    public function show(Request $request, $id)
    {
        try {
            $hiringProcessRound = HiringProcessRound::find($id);
            return response()->json([
                'status_code' => 200,
                'hiring_process_round' => $hiringProcessRound,
                'message' => 'Get hiring process round success'
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in get hiring process round',
                'error' => $error,
            ]);
        }
    }
}
