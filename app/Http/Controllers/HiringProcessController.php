<?php

namespace App\Http\Controllers;

use App\Models\HiringProcess;
use App\Models\HiringProcessRound;
use App\Models\JobsCV;
use Illuminate\Http\Request;

class HiringProcessController extends Controller
{

    public function index(Request $request)
    {
        try {

            $hiringProcess = HiringProcess::orderBy('created_at', 'desc')->where('user_id', $request->user()->id)->get();
            $hiringProcess->map(function ($item) {
                $item->hiringProcessRound;
            });
            return response()->json([
                'status_code' => 200,
                'hiring_process' => $hiringProcess,
                'message' => 'Get hiring process success'
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in get hiring process',
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
            ]);

            //check name in hiring process
            $hiringProcess = HiringProcess::where('name', $validated['name'])->where('user_id', $request->user()->id)->first();
            if ($hiringProcess) {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Name is exist'
                ]);
            }

            $validated['user_id'] = $request->user()->id;
            $hiringProcess = HiringProcess::create($validated);
            //hỉring processround = [[name: 'test', process_id: 1], [name: 'test2', process_id: 1]
            $hiringProcessRound = $request->hiring_process_round;
            if ($hiringProcessRound && count($hiringProcessRound) > 0) {
                foreach ($hiringProcessRound as $item) {
                    $item['process_id'] = $hiringProcess->id;
                    HiringProcessRound::create($item);
                }
            }

            $hiringProcess = HiringProcess::find($hiringProcess->id);

            return response()->json([
                'status_code' => 200,
                'hiring_process' => $hiringProcess,
                'message' => 'Create hiring process success'
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in create hiring process',
                'error' => $error,
            ]);
        }
    }

    //edit

    public function edit(Request $request, $id)
    {
        try {
            $hiringProcess = HiringProcess::find($id);
            $name = $request->name;
            $hiringProcess->name = $name;
            $hiringProcess->save();

            $hiringProcess->hiringProcessRound;
            foreach ($hiringProcess->hiringProcessRound as $item) {
                $checkCvs = JobsCV::where('round_id', $item->id)->first();
                if ($checkCvs) {
                    return response()->json([
                        'status_code' => 500,
                        'message' => 'Updated name, can not update  hiring process round because have cv in round'
                    ]);
                }
            }


            $hiringProcessRound = $request->hiring_process_round;
            if ($hiringProcessRound && count($hiringProcessRound) > 0) {
                HiringProcessRound::where('process_id', $hiringProcess->id)->delete();
                foreach ($hiringProcessRound as $item) {
                    $item['process_id'] = $hiringProcess->id;
                    HiringProcessRound::create($item);
                }
            }

            return response()->json([
                'status_code' => 200,
                'hiring_process' => $hiringProcess,
                'message' => 'Get hiring process success'
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in get hiring process',
                'error' => $error,
            ]);
        }
    }


    //delete

    public function destroy(Request $request, $id)
    {
        try {
            $hiringProcess = HiringProcess::find($id);
            $hiringProcess->hiringProcessRound;
            foreach ($hiringProcess->hiringProcessRound as $item) {
                $checkCvs = JobsCV::where('round_id', $item->id)->first();
                if ($checkCvs) {
                    return response()->json([
                        'status_code' => 500,
                        'message' => 'Can not delete hiring process'
                    ]);
                }
            }

            $hiringProcess->delete();
            return response()->json([
                'status_code' => 200,
                'message' => 'Delete hiring process success',
                'data' => []
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in delete hiring process',
                'error' => $error,
            ]);
        }
    }

    // show

    public function show(Request $request, $id)
    {
        try {
            $hiringProcess = HiringProcess::find($id);
            $hiringProcess->hiringProcessRound;
            return response()->json([
                'status_code' => 200,
                'hiring_process' => $hiringProcess,
                'message' => 'Get hiring process success'
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in get hiring process',
                'error' => $error,
            ]);
        }
    }
}
