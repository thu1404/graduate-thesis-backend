<?php

namespace App\Http\Controllers;

use App\Models\Skills;
use Illuminate\Http\Request;

class SkillsController extends Controller
{
    //
    public function index(Request $request)
    {
        try {

            $skills = Skills::orderBy('created_at', 'desc');
            $name = $request->input('name');
            if ($name) {
                $skills = Skills::where('name', 'like', '%' . $name . '%');
            }
            $skills = $skills->get();
            $skills->map(function ($item) {
                $item->skills;
            });
            return response()->json([
                'status_code' => 200,
                'skills' => $skills,
                'message' => 'Get skills success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    //store

    public function store(Request $request)
    {
        try {
            $check = Skills::where('name', $request->name)->first();
            if ($check) {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Kỹ năng đã tồn tại',
                ]);
            }
            $skills = Skills::create([
                'name' => $request->name,
            ]);
            return response()->json([
                'status_code' => 200,
                'skills' => $skills,
                'message' => 'Create skills success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    //update

    public function edit(Request $request, $id)
    {
        try {
            $check = Skills::where('name', $request->name)->whereNotIn('id', [$id])->first();
            if ($check) {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Kỹ năng đã tồn tại',
                ]);
            }

            $skills = Skills::find($id);
            $skills->update([
                'name' => $request->name,
            ]);
            return response()->json([
                'status_code' => 200,
                'skills' => $skills,
                'message' => 'Update skills success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    //delete


    public function destroy($id)
    {
        try {
            $skills = Skills::find($id);
            $skills->delete();
            return response()->json([
                'status_code' => 200,
                'skills' => $skills,
                'message' => 'Delete skills success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    //show

    public function show($id)
    {
        try {
            $skills = Skills::find($id);
            return response()->json([
                'status_code' => 200,
                'skills' => $skills,
                'message' => 'Show skills success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
