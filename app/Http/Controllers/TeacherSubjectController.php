<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\TeacherSubject;

class TeacherSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([], 200);
    }

    /**
     * Explain:
     * Obtaining subjects, 
     * through a teacher id.
     */
    public function subjectsByTeacher($teacherId)
    {
        try {
            $universitySubjects = TeacherSubject::with('universitySubject:id,name')
                ->where('teacher_id', $teacherId)
                ->get();

            $universitySubjects = $universitySubjects->map(function ($universitySubject){
                return[
                    'university_subject_id' => $universitySubject->university_subject_id,
                    'name' => $universitySubject->universitySubject->name,
                ];
            });     
            return response()->json($universitySubjects, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e -> getMessage()],500);
        }
    }

    public function teachersBySubject($universitySubjectId)
    {
        try {
            $teacherSubjects = TeacherSubject::with('teacher.person')
                ->where('university_subject_id', $universitySubjectId)
                ->select('id','teacher_id', 'group_number')
                ->get();
   
            $teacherSubjects = $teacherSubjects->map(function ($teacherSubject){
                return [
                    'teacher_subject_id' => $teacherSubject->id,
                    'group_number' => $teacherSubject->group_number,
                    'teacher_id' => $teacherSubject->teacher->id,
                    'teacher_name' => $teacherSubject->teacher->person->name,
                    'teacher_last_name' => $teacherSubject->teacher->person->last_name,    
                ];
            });
            return response()->json($teacherSubjects, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e -> getMessage()],500);
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
