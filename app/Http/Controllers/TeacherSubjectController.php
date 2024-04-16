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
        $list = [
            ["id"=>1, "group_number"=>1, "teacher_fullname"=>"LETICIA BLANCO COCA"],
            ["id"=>13, "group_number"=>2, "teacher_fullname"=>"LETICIA BLANCO COCA"],
            ["id"=>11, "group_number"=>3, "teacher_fullname"=>"LETICIA BLANCO COCA"],
            ["id"=>5, "group_number"=>4, "teacher_fullname"=>"ROSEMARY TORRICO BASCOPE"],
            ["id"=>14, "group_number"=>5, "teacher_fullname"=>"ROSEMARY TORRICO BASCOPE"],
            ["id"=>7, "group_number"=>6, "teacher_fullname"=>"VLADIMIR ABEL COSTAS JAUREGUI"],
            ["id"=>21, "group_number"=>7, "teacher_fullname"=>"CARLA SALAZAR SERRUDO"]
        ];
        $list2 = [
            ["id"=>15, "group_number"=>1, "teacher_fullname"=>"JORGE WALTER ORELLANA ARAOZ"], 
            ["id"=>20, "group_number"=>2, "teacher_fullname"=>"JORGE WALTER ORELLANA ARAOZ"],             
            ["id"=>23, "group_number"=>3, "teacher_fullname"=>"GROVER HUMBERTO CUSSI NICOLAS"],             
            ["id"=>27, "group_number"=>4, "teacher_fullname"=>"GROVER HUMBERTO CUSSI NICOLAS"],             
            ["id"=>32, "group_number"=>5, "teacher_fullname"=>"JUAN MARCELO FLORES SOLIZ"]             
        ];
        $result = array();
        $aux = rand(1, 2);
        if ($aux==1)
            foreach($list as $group) {
                $item = [
                    "group_number"=>$group["group_number"], 
                    "group_id"=>$group["id"], 
                    "teacher_fullname"=>$group["teacher_fullname"]
                ];
                array_push($result, $item);
            }
        else 
            foreach($list2 as $group) {
                $item = [
                    "group_number"=>$group["group_number"], 
                    "group_id"=>$group["id"], 
                    "teacher_fullname"=>$group["teacher_fullname"]
                ];
                array_push($result, $item);        
            }
        return response()->json($result, 200);
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
