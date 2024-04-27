<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\TeacherSubject;
use App\Models\Person;

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
            $universitySubjects = TeacherSubject::where('person_id', $teacherId)
                ->get();

            $universitySubjects = $universitySubjects->map(function ($universitySubject)
            {
                return [
                    'id' => $universitySubject->university_subject_id,
                    'name' => $universitySubject->universitySubject->name,
                ];
            });     
            return response()->json($universitySubjects, 200);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'Hubo un error en el servidor',
                    'error' => $e -> getMessage()
                ],
                500
            );
        }
    }

    public function teachersBySubject($universitySubjectId)
    {
        try {
            $teacherSubjects = TeacherSubject::where('university_subject_id', $universitySubjectId)
                ->get();
            
            $teacherSubjects = $teacherSubjects->map(function ($teacherSubject)
            {
                $teacher = Person::find($teacherSubject->person_id);
                $fullname = $teacher->name . ' ' . $teacher->last_name;
                return [
                    'id' => $teacherSubject->id,
                    'group_number' => $teacherSubject->group_number,
                    'teacher_fullname' => $fullname
                ];
            });
            return response()->json([$teacherSubjects], 200);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'Hubo un error en el servidor',
                    'error' => $e -> getMessage()
                ],
                500
            );
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
