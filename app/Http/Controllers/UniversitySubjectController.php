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
            $universitySubjects = UniversitySubject::select('id','name')
                ->get();
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
    public function subjectsCommonTeachers($id)
    {
        try {
            $universitySubjects = UniversitySubject::select('id', 'name')
                ->whereIn('id', function ($query) use ($id){
                    $query->select('university_subject_id')
                        ->from('teacher_subjects')
                        ->where('teacher_id', $id);
                })
                ->get();

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
