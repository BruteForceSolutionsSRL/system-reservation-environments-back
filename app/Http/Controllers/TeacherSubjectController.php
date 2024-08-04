<?php

namespace App\Http\Controllers;

use App\Service\ServiceImplementation\TeacherSubjectServiceImpl;

use Illuminate\Http\{
    JsonResponse as Response,
    Request
};

use Illuminate\Support\Facades\{
    Validator,
    DB
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

    /**
     * 
     */
    public function saveGroup(Request $request): Response
    {
        try {
            $validator = $this->validateSaveGroupData($request);

            if ($validator->fails()) {
                return response()->json(
                    ['message' => (implode(',', $validator->errors()->all()))],
                    400
                );
            }
        
            $data = $validator->validated();

            return response()->json([
                $this->teacherSubjectService->saveGroup($data),
                200
            ]);
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
     * 
     */
    private function validateSaveGroupData(Request $request): mixed
{
    $validDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

    return Validator::make($request->all(), [
        'university_subject_id' => 'required|exists:university_subjects,id',
        'person_id' => 'required|exists:people,id',
        'group_number' => 'required|integer',
        'academic_period_id' => 'required|exists:academic_periods,id',
        'study_plan_ids' => 'array',
        'study_plan_ids.*' => 'required|exists:study_plans,id',
        'classroom_ids' => 'array',
        'classroom_ids.*' => 'required|exists:classrooms,id',
        'class_schedules' => [
            'required',
            'array',
            function ($attribute, $value, $fail) use ($validDays) {
                foreach ($value as $day => $timeSlots) {
                    if (!in_array($day, $validDays)) {
                        $fail('El campo ' . $attribute . ' contiene un día inválido: ' . $day);
                    } elseif (!is_array($timeSlots) || count($timeSlots) !== 2) {
                        $fail('El campo ' . $attribute . '.' . $day . ' debe contener exactamente dos periodos de tiempo');
                    } else {
                        foreach ($timeSlots as $index => $timeSlot) {
                            if (!is_int($timeSlot)) {
                                $fail('El periodo de tiempo en ' . $attribute . '.' . $day . '[' . $index . '] debe ser un entero');
                            } elseif (!DB::table('time_slots')->where('id', $timeSlot)->exists()) {
                                $fail('El periodo de tiempo con ID ' . $timeSlot . ' en ' . $attribute . '.' . $day . ' no existe');
                            }
                        }

                        if ($timeSlots[1] <= $timeSlots[0]) {
                            $fail('El segundo periodo de tiempo para el día ' . $day . ' debe ser mayor que el primero');
                        }
                    }
                }
            },
        ],
    ], [
        'university_subject_id.required' => 'El campo materia es obligatorio',
        'university_subject_id.exists' => 'El campo materia debe existir en la base de datos',
        
        'person_id.required' => 'El campo de docente es obligatorio',
        'person_id.exists' => 'El campo de docente debe existir en la base de datos',
        
        'group_number.required' => 'El campo de numero de grupo es obligatorio',
        'group_number.integer' => 'El campo de nuemro de grupo debe ser un número entero',
        
        'academic_period_id.required' => 'El periodo academico es obligatorio',
        'academic_period_id.exists' => 'El periodo academico debe existir en la base de datos',
        
        'study_plan_ids.array' => 'Las carreras designadas deben estar en un arreglo',
        'study_plan_ids.*.required' => 'Debe seleccionar almenos una carrera designada',
        'study_plan_ids.*.exists' => 'Cada carrera designada debe existir en la base de datos.',
        
        'class_schedules.required' => 'El campo de horario de clases es obligatorio.',
        'class_schedules.array' => 'El campo de horario de clases debe ser un arreglo.',
    ]);
}

}