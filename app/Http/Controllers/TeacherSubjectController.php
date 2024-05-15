<?php

namespace App\Http\Controllers;

use App\Service\ServiceImplementation\TeacherSubjectServiceImpl;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse as Response;

use Exception;

class TeacherSubjectController extends Controller
{
    private $teacherSubjectService; 
    public function __construct()
    {
        $this->teacherSubjectService = new TeacherSubjectServiceImpl(); 
    }
    /**
     * Explain:
     * Obtaining subjects,
     * through a teacher id.
     * @param int personId
     * @return Response
     */
    public function subjectsByTeacher($personId): Response
    {
        try {
            return response()->json(
                $this->teacherSubjectService->getSubjectsByTeacherId(
                    $personId
                ), 
                200
            );
        } catch (Exception $e) {
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
     * @return Response
     */
    public function teachersBySubject($universitySubjectId): Response
    {
        try {
            return response()->json(
                $this->teacherSubjectService->getTeachersBySubjectId(
                    $universitySubjectId
                ), 
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => 'Hubo un error en el servidor',
                    'error' => $e -> getMessage()
                ],
                500
            );
        }
    }
}
