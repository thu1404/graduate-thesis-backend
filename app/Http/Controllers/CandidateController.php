<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cvs;
use App\Models\HiringProcessRound;
use App\Models\Jobs;
use App\Models\JobsCV;
use App\Models\SkillsCv;
use DB;
use App\Traits\StorageImageTrait;

class CandidateController extends Controller
{
    use StorageImageTrait;
    public function getProfile()
    {
        try {
            $data = Cvs::where('creator_id', auth()->user()->id)->get();
            $data->map(function ($item) {
                $item->getSkills;
                return $item;
            });
            return response()->json([
                'status_code' => 200,
                'message' => 'List of candidate’s CVs',
                'data' => $data
            ]);
        } catch (\Exception $exception) {
            // DB::rollBack();
            // \Log::error('Message: '. $exception->getMessage() . '---Line: ' . $exception->getLine());
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in list of candidate’s CVs',
                'error' => $exception
            ]);
        }
    }

    public function getJob(Request $request)
    {
        try {

            $candidate_id = auth()->user()->id;
            // return $candidate_id;
            $jobs = Jobs::orderByDesc('created_at')->where('inactive', Jobs::active)->get();

            $cvs_of_candidate = Cvs::where('creator_id', $candidate_id)->get();
            // return $cvs_of_candidate;
            //get cv of candidate have skill match with skil of job -> up rate of job
            foreach ($jobs as $job) {
                $job->getCv;
                $job->skill;
                $job->rate = 0;
                // return $job->skill;
                foreach ($cvs_of_candidate as $cv) {
                    $cv->getSkills;

                    foreach ($cv->getSkills as $skill) {
                        foreach ($job->skill as $jobskill) {
                            if ($skill->id == $jobskill->id) {
                                $job->rate += 1;
                            }
                        }
                    }
                }
                if ($job->rate == 0) {
                    $job->rate = 0;
                }
                if ($job->rate > 0 && count($job->skill) > 0) {
                    $job->rate = $job->rate / count($job->skill) * 100;
                } else {
                    $job->rate = 0;
                }
                //rate count percent of skill match with job

            }

            // $jobs = $jobs->map(function ($job) use ($candidate_id) {
            //     $job->getCv;
            //     $job->rate = 0;

            //     return $job;
            // });
            // $jobs->map(function ($job) {
            //     $job->getCv;
            //     return $job;
            // });

            return response()->json([
                'status_code' => 200,
                'message' => 'List of jobs that candidate can apply to, return Cv if that appled',
                'data' => $jobs
            ]);
        } catch (\Exception $exception) {
            // DB::rollBack();
            // \Log::error('Message: ' . $exception->getMessage() . '---Line: ' . $exception->getLine());
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in list of jobs that candidate can apply to',
                'error' => $exception,
            ]);
        }
    }
    public function storeProfile(Request $request)
    {
        try {
            // DB::beginTransaction();
            $data = [
                'creator_id' => auth()->user()->id,
                'name' => $request->name,
                'age' => $request->age,
                'gender_id' => $request->gender_id, //male: 1, female: 2
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'position' => $request->position,
                'education' => $request->education,
                'experience' => $request->experience,
                'gpa' => $request->gpa,
                'english' => $request->english,
                // 'skills' => $request->skills,
                // 'cv_files' => $request->cv_files,
            ];

            $dataUploadImg = $this->storageTraitUpload($request, 'avatar', 'cv/avatar');
            if (!empty($dataUploadImg)) {
                $data['avatar'] = $dataUploadImg['file_path'];
            }
            $dataUploadCvFile = $this->storageTraitUpload($request, 'cv_file', 'cv/file');
            if (!empty($dataUploadCvFile)) {
                $data['cv_files'] = $dataUploadCvFile['file_path'];
            }
            $cvs = Cvs::create($data);
            /* Thêm skill */
            foreach ($request->skills as $skill => $level) {
                SkillsCv::create([
                    'cv_id' => $cvs->id,
                    'skill_id' => $skill,
                    'num_lever' => $level
                ]);
            }
            // DB::commit();
            return response()->json([
                'status_code' => 200,
                'message' => 'Create profile successfuly',
                'data' => $cvs
            ]);
        } catch (\Exception $exception) {
            // DB::rollBack();
            // \Log::error('Message: ' . $exception->getMessage() . '---Line: ' . $exception->getLine());
            return response()->json([
                'status_code' => 500,
                'message' => 'Create profile failed',
                'error' => $exception,
            ]);
        }
    }

    public function updateProfile(Request $request, $id)
    {
        try {
            $data = [
                'creator_id' => auth()->user()->id,
                'name' => $request->name,
                'age' => $request->age,
                'gender_id' => $request->gender_id, //male: 1, female: 2
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'position' => $request->position,
                'education' => $request->education,
                'experience' => $request->experience,
                'gpa' => $request->gpa,
                'english' => $request->english,
                // 'skills' => $request->skills,
                // 'cv_files' => $request->cv_files,
            ];
            $dataUploadImg = $this->storageTraitUpload($request, 'avatar', 'cv/avatar');
            if (!empty($dataUploadImg)) {
                $data['avatar'] = $dataUploadImg['file_path'];
            }
            $dataUploadCvFile = $this->storageTraitUpload($request, 'cv_file', 'cv/file');
            if (!empty($dataUploadCvFile)) {
                $data['cv_files'] = $dataUploadCvFile['file_path'];
            }

            $cvs = Cvs::find($id);
            $cvs->creator_id = auth()->user()->id;
            $cvs->name = $request->name;
            $cvs->age = $request->age;
            $cvs->gender_id = $request->gender_id;
            $cvs->phone = $request->phone;
            $cvs->email = $request->email;
            $cvs->address = $request->address;
            $cvs->position  = $request->position;
            $cvs->education = $request->education;
            $cvs->experience = $request->experience;
            // $cvs->skills = $request->skills;
            $cvs->gpa = $request->gpa;
            $cvs->english = $request->english;
            if (!empty($dataUploadImg)) {
                $cvs->avatar = $data['avatar'];
            }
            if (!empty($dataUploadCvFile)) {
                $cvs->cv_files = $data['cv_files'];
            }

            $cvs->save();

            /* Update skill */
            SkillsCv::where('cv_id', $id)->delete();
            foreach ($request->skills as $skill => $level) {
                SkillsCv::create([
                    'cv_id' => $cvs->id,
                    'skill_id' => $skill,
                    'num_lever' => $level
                ]);
            }

            return response()->json([
                'status_code' => 200,
                'message' => 'Update profile success',
                'data' => $cvs,
            ]);
        } catch (\Exception $exception) {
            // DB::rollBack();
            // \Log::error('Message: ' . $exception->getMessage() . '---Line: ' . $exception->getLine());
            return response()->json([
                'status_code' => 500,
                'message' => 'Update profile failed',
                'error' => $exception,
            ]);
        }
    }

    public function deleteProfile(Request $request, $id)
    {
        try {
            $cvs = Cvs::find($id);
            $cvs->delete();
            return response()->json([
                'status_code' => 200,
                'message' => 'Delete profile success',
                'data' => [],
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Delete profile failed',
                'error' => $exception,
            ]);
        }
    }


    //Apply job

    public function applyJobs(Request $request)
    {
        try {

            $job_id = $request->job_id;
            $cv_id =  $request->cv_id;
            $check  = JobsCV::where('cv_id', $cv_id)->where('job_id', $job_id)->where('status', JobsCV::OnProcess)->first();
            if ($check) {
                return  response()->json([
                    'status_code' => 500,
                    'message' => 'You have applied before',
                ]);
            }
            $process_id =  Jobs::find($job_id)->hiring_process;
            $round_id = HiringProcessRound::orderBy('id', 'asc')->where('process_id', $process_id)->first()->id;
            $apply = JobsCV::create([
                'cv_id' => $cv_id,
                'job_id' => $job_id,
                'round_id' => $round_id,
                'status' => JobsCV::OnProcess, // On process
            ]);


            return response()->json([
                'status_code' => 200,
                'message' => 'Apply jobs success',
                'data' =>  Jobs::find($job_id),
                'cv' => Cvs::find($cv_id),
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Apply jobs failed',
                'error' => $exception,
            ]);
        }
    }
}
