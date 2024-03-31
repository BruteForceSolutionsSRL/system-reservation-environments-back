<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\UniversitySubject;
use App\Models\TeacherSubject;

class UniversitySubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $universitySubjects = UniversitySubject::pluck('id','name');
            return response()->json($universitySubjects,200);
        } catch (Exeption $e) {
            return response()->json(['error' => $e -> getMessage()],500);
        } 
    }

    /**
     * Explain:
     * Obtaining subjects, 
     * through a teacher id.
     */
    public function getSubjectsByTeacher($id)
    {
        try {
            $teacherSubjects = TeacherSubject::where('teacher_id', $id)->get();
            $subjects = $teacherSubjects->pluck('university_subject_id');
            $universitySubjects = UniversitySubject::whereIn('id',$subjects)->pluck('id','name');
            return response()->json($universitySubjects, 200);
        } catch (Exeption $e) {
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
