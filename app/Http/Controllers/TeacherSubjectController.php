<?php

namespace App\Http\Controllers;

use App\Service\ServiceImplementation\TeacherSubjectServiceImpl;

use Illuminate\Http\{
    JsonResponse as Response,
    Request
};
use Exception;

class TeacherSubjectController extends Controller
{
    private $teacherSubjectService; 
    public function __construct()
    {
        $this->teacherSubjectService = new TeacherSubjectServiceImpl(); 
    }

    public function list() 
    {
        return response()->json($this->teacherSubjectService->getAllTeacherSubjects(), 200);
    }

    /**
     * Obtaining subjects through a teacher id.
     * @param int $personId
     * @param Request $request
     * @return Response
     */
    public function subjectsByTeacher(int $personId, Request $request): Response
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
     * Obtaining teacher-groups through a university subject id.
     * @param int $universitySubjectId
     * @param Request $request
     * @return Response
     */
    public function teachersBySubject(int $universitySubjectId, Request $request): Response
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