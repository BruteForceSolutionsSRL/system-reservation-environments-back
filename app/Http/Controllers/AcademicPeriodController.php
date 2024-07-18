<?php
namespace App\Http\Controllers;

use App\Service\ServiceImplementation\AcademicPeriodServiceImpl;
use Exception;
use Illuminate\Http\{
    JsonResponse as Response,
    Request
};
use Illuminate\Support\Facades\Validator;

class AcademicPeriodController extends Controller
{
    private $academicPeriodService;

    public function __construct()
    {
        $this->academicPeriodService = new AcademicPeriodServiceImpl;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        try {
            $validator = $this->validateAcademicPeriodData($request);

            if ($validator->fails()) {
                return response()->json(
                    ['message' => implode('.', $validator->errors()->all())],
                    400
                );
            }

            $data = $validator->validated();

            return response()->json(
                ['message' => $this->academicPeriodService->store($data)],
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => 'Hubo un error en el servidor',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function validateAcademicPeriodData(Request $request)
    {
        return Validator::make($request->all(), [
            'academic_period_name' => '
                required|
                unique:academic_periods,name',
            'initial_date' => 'required|date',
            'end_date' => 'required|date|after:initial_date'
        ],
        [
            'academic_period_name.required' => 'Debe ingresar el nombre del periodo academico.',
            'academic_period_name.unique' => 'El nombre del periodo academico ya existe',
            'initial_date.required' => 'La fecha inicial es obligatoria.',
            'initial_date.date' => 'La fecha debe tener un formato válido.',
            'end_date.required' => 'La fecha final es obligatoria',
            'end_date.date' => 'La fecha debe tener un formato válido',
            'end_date.after' => 'La fecha final debe ser mayor que la fecha inicial',
        ]);
    }

    /**
     * @return Response
     */
    public function getActiveAcademicPeriod(): Response
    {
        try {
            $academicPeriod = $this->academicPeriodService->getActiveAcademicPeriod();

            if ($academicPeriod === []) {
                return response()->json(
                    [ 'message' => 'No existe un periodo academico activo' ],
                    404
                );
            }

            return response()->json($academicPeriod, 200);
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => 'Hubo un error en el servidor',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    /**
     * @return Response
     */
    public function deactivateActiveAcademicPeriod(): Response
    {
        try {
            $academicPeriod = $this->academicPeriodService->getActiveAcademicPeriod();
            $state = $this->academicPeriodService->deactivateActiveAcademicPeriod();

            if ($state == 1) {
                return response()->json(
                    [
                        'message' => 'El periodo academico '.$academicPeriod['academic_period_name'].' fue deshabilitado.'
                    ],
                    200
                );
            }

            if ($state == 3) {
                return response()->json(
                    [
                        'message' => 'El periodo academico '.$academicPeriod['academic_period_name'].' aun sigue activo.'
                    ],
                    200
                );
            }

            return response()->json(
                [
                    'message' => 'No existe un periodo academico activo'
                ],
                404
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => 'Hubo un error en el servidor',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }
}

