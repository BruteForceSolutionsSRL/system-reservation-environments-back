<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Teacher;
use App\Models\Person;
use App\Models\TeacherSubject;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //     
    }

    public function teacherCommonSubjects($subjectID){
        try {
            $teacherSubjects = TeacherSubject::with('teacher.person')
                ->where('university_subject_id', $subjectID)
                ->get();
   
            $teachersInfo = $teacherSubjects->map(function ($teacherSubject){
                return [
                    'teacher_id' => $teacherSubject->teacher->id,
                    'teacher_name' => $teacherSubject->teacher->person->name,
                    'teacher_last_name' => $teacherSubject->teacher->person->last_name,
                    'group_number' => $teacherSubject->group_number,
                ];
            })->unique();
            return response()->json($teachersInfo, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
