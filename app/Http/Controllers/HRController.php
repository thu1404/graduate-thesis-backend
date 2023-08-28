<?php

namespace App\Http\Controllers;

use App\Mail\Notify;
use App\Models\HiringProcess;
use App\Models\Jobs;
use App\Models\JobsCV;
use App\Models\SkillsCv;
use App\Models\SkillsJobs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class HRController extends Controller
{
    public function listJobs(Request $request)
    {
        try {

            $jobs = Jobs::orderBy('created_at', 'desc')->where('user_id', $request->user()->id);
            //have much request ->skill_id
            // ist-jobs?skill_id[]=3&skill_id[]=1213


            if ($request->skill_id) {
                //skill = []
                $jobs->whereHas('skill', function ($query) use ($request) {
                    $query->whereIn('skill_id', $request->skill_id);
                });
            }
            $jobs = $jobs->get();

            $jobs->map(function ($job) {
                $job->skills;
                $job->hiringProcess;
                return $job;
            });
            return response()->json([
                'status_code' => 200,
                'jobs' => $jobs,
                'message' => 'Get jobs success'
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in get jobs',
                'error' => $error,
            ]);
        }
    }
    public function createJob(Request $request)
    {
        try {
            $validated = $request->only('title', 'requirements', 'description', 'benefit', 'hiring_process', 'location', 'salary', 'gpa', 'english');
            $validated['user_id'] = $request->user()->id;
            $job = Jobs::create($validated);
            $job->user;
            $job->hiringProcess;

            $skills = $request->input('skills');
            foreach ($skills as $skill) {
                SkillsJobs::create([
                    'job_id' => $job->id,
                    'skill_id' => $skill,
                ]);
            }

            return response()->json([
                'status_code' => 200,
                'job' => $job,
                'message' => 'Create job success'
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in create job',
                'error' => $error,
            ]);
        }
    }

    public function editJob(Request $request, $id)
    {
        try {
            $validated = $request->only('title', 'requirements', 'description', 'benefit', 'hiring_process', 'location', 'salary');
            $validated['user_id'] = $request->user()->id;
            $job = Jobs::where('id', $id)->update([
                'title' => $validated['title'],
                'requirements' => $validated['requirements'],
                'description' => $validated['description'],
                'benefit' => $validated['benefit'],
                'hiring_process' => $validated['hiring_process'],
                'location' => $validated['location'],
                'salary' => $validated['salary'],
                'user_id' => $validated['user_id'],
                'gpa' => $request->input('gpa'),
                'english' => $request->input('english'),
            ]);

            /* Update skills */
            $skills = $request->input('skills');
            SkillsJobs::where('job_id', $id)->delete();
            foreach ($skills as $skill) {
                SkillsJobs::create([
                    'job_id' => $id,
                    'skill_id' => $skill,
                ]);
            }
            $job = Jobs::find($id);
            $job->user;
            $job->hiringProcess;
            return response()->json([
                'status_code' => 200,
                'job' => $job,
                'message' => 'Edit job success'
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in edit job',
                'error' => $error,
            ]);
        }
    }

    public function deleteJob(Request $request, $id)
    {
        try {
            $job = Jobs::where('id', $id)->delete();
            return response()->json([
                'status_code' => 200,
                'message' => 'Delete job success',
                'data' => []
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in delete job',
                'error' => $error,
            ]);
        }
    }

    //show job

    public function showJob(Request $request, $id)
    {
        try {
            $job = Jobs::find($id);
            $job->user;
            $job->hiringProcess;
            return response()->json([
                'status_code' => 200,
                'job' => $job,
                'message' => 'Show job success'
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in show job',
                'error' => $error->getMessage(),
            ]);
        }
    }
    //disable job
    public function disableJob(Request $request, $id)
    {
        try {
            $job = Jobs::where('id', $id)->update([
                'inactive' => Jobs::inactive,
            ]);
            return response()->json([
                'status_code' => 200,
                'message' => 'Disable job success',
                'data' => []
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in disable job',
                'error' => $error,
            ]);
        }
    }
    //enable job
    public function enableJob(Request $request, $id)
    {
        try {
            $job = Jobs::where('id', $id)->update([
                'inactive' => Jobs::active,
            ]);
            return response()->json([
                'status_code' => 200,
                'message' => 'Enable job success',
                'data' => []
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in enable job',
                'error' => $error,
            ]);
        }
    }


    //get the kanban board of hiring process
    public function getKanbanBoard(Request $request, $id)
    {
        try {
            $jobs = Jobs::find($id);
            $jobs->jobApplicant->map(function ($item) {
                $item->cv;
                $item->job;
                $item->round;
                return $item;
            });
            // jobapplicant sort by status desc;
            $jobs->jobApplicant = $jobs->jobApplicant
                ->sortByDesc('status')->values()->all();
            $hiringProcess = HiringProcess::where('id', $jobs->hiring_process)->get();
            $hiringProcess->map(function ($item) {
                $item->hiringProcessRound;
            });
            return response()->json([
                'status_code' => 200,
                'hiringProcess' => $hiringProcess,
                'kanbanBoard' => $jobs->jobApplicant,
                'message' => 'Get kanban board success'
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in get kanban board',
                'error' => $error,
            ]);
        }
    }

    // changeRound
    public function changeRound(Request $request, $id)
    {
        try {
            $job_cv = JobsCV::where('job_id', $id)->where('cv_id', $request->cv_id)->first();
            $hiring_process_round_id = $request->hiring_process_round_id;

            $job_cv->update([
                'round_id' => $hiring_process_round_id,
            ]);

            return response()->json([
                'status_code' => 200,
                'jobApplicant' => $job_cv,
                'message' => 'Change round success'
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in change round',
                'error' => $error,
            ]);
        }
    }
    public function rejectCV(Request $request, $id)
    {
        try {
            $job_cv = JobsCV::where('job_id', $id)->where('cv_id', $request->cv_id)->first();
            $job_cv->update([
                'status' => JobsCV::rejected,
                'round_id' => null,
            ]);

            return response()->json([
                'status_code' => 200,
                'jobApplicant' => $job_cv,
                'message' => 'Reject CV success'
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in reject CV',
                'error' => $error,
            ]);
        }
    }

    public function sendMailAccessCV(Request $request)
    {
        $candidate = $request->candidate;
        $email = User::where('id', $candidate)->first();
        $address = $request->address;
        $date = $request->date;
        $date = Carbon::parse($date)->format('H:i d-m-Y');
        Mail::to($email->email)->send(new Notify($date, $address));

        return response()->json([
            'status_code' => 200,
            'message' => 'Send mail success'
        ]);
    }
}
