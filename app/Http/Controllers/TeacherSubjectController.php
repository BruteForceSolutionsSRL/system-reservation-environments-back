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
     * @param int personId
     * @return \Response
     */
    public function subjectsByTeacher($personId)
    {
        try {
            $universitySubjects = TeacherSubject::with('universitySubject:id,name')
                ->select('university_subject_id')
                ->where('person_id', $personId)
                ->groupBy('university_subject_id')
                ->get();

            $universitySubjects = $universitySubjects->map(function ($universitySubject) {
                return [
                    'subject_id' => $universitySubject->university_subject_id,
                    'subject_name' => $universitySubject->universitySubject->name,
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

    /**
     * Explain:
     * Obtaining teacher-groups,
     * through a university subject id.
     * @param int universitySubjectId
     * @return \Response
     */
    public function teachersBySubject($universitySubjectId)
    {
        try {
            $teacherSubjects = TeacherSubject::with('person')
                ->where('university_subject_id', $universitySubjectId)
                ->select('id','person_id', 'group_number')
                ->get();

            $teacherSubjects = $teacherSubjects->map(function ($teacherSubject){
                $teacher = Person::find($teacherSubject->person_id);
                return [
                    'id' => $teacherSubject->id,
                    'group_number' => $teacherSubject->group_number,
                    'person_id' => $teacher->id,
                    'teacher_name' => $teacher->name,
                    'teacher_last_name' => $teacher->last_name,
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
