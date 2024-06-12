<?php
namespace App\Http\Controllers;

use Illuminate\Http\{
    JsonResponse as Response,
    Request
};
use Exception;

use App\Service\ServiceImplementation\{
    BlockServiceImpl,
    ClassroomServiceImpl
}; 

use Illuminate\Support\Facades\Validator;

class BlockController extends Controller
{
    private $blockService; 
    private $classroomService;
    public function __construct()
    {
        $this->blockService = new BlockServiceImpl();
        $this->classroomService = new ClassroomServiceImpl();
    }

    /**
     * Retrieve a list of all blocks registered
     * @param none
     * @return Response
     */
    public function list(): Response
    {
        try {
            return response()->json(
                $this->blockService->getAllBlocks(), 
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
     * Retrieve a single block by its ID
     * @param int $block_id
     * @return Response
     */
    public function show(int $block_id): Response 
    {
        try {
            return response()->json(
                $this->blockService->getBlock($block_id), 
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
     * Retrieve a response including statistical information
     * @param int $block_id
     * @return Response
     */
    public function getBlockStatistics(int $block_id): Response 
    {
        try {
            return response()->json(
                $this->blockService->getBlockStatistics($block_id), 
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
     * Store a single block
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response 
    {
        try {
            $validator = $this->validateBlockData($request); 
            if ($validator->fails()) {
                $message = '';
                foreach ($validator->errors()->all() as $value)
                    $message = $message . $value . '.';
                
                return response()->json(
                    ['message' => $message],
                    400
                );
            }

            $data = $validator->validated();

            if (count($this->blockService->findByName($data['block_name']))!=0)
                return response()->json(
                    ['message' => 'El nombre del bloque ya existe'], 
                    400
                );

            return response()->json(
                $this->blockService->store($data), 
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
     * Updates a single block with its new information
     * @param Request $request
     * @param int $block_id
     * @return Response
     */
    public function update(Request $request, int $block_id): Response 
    {
        try {
            $validator = $this->validateBlockData($request); 
            if ($validator->fails()) {
                $message = '';
                foreach ($validator->errors()->all() as $value)
                    $message = $message . $value . '.';
                
                return response()->json(
                    ['message' => $message],
                    400
                );
            }

            $data = $validator->validated();

            $block = $this->blockService->getBlock($block_id); 

            if (empty($block))
                return response()->json(
                    ['message' => 'El id del bloque no existe'], 
                    400
                );

            if (count($block['block_classrooms']) > $data['block_maxclassrooms'])
                return response()->json(
                    ['message' => 'La capacidad de ambientes no debe ser menor a la cantidad actual'], 
                    400
                );

            $max_floor = 0; 
            foreach ($block['block_classrooms'] as $classroom)
                $max_floor = max($max_floor, $classroom['floor']); 

            if ($max_floor > $data['block_maxfloor']) 
                return response()->json(
                    ['message' => 'La cantidad de pisos debe ser mayor o igual al piso de los ambientes existentes'], 
                    400
                );

            return response()->json(
                $this->blockService->update($data, $block_id), 
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
     * Destroy a single block by its ID
     * @param int $block_id
     * @return Response
     */
    public function destroy(int $block_id): Response 
    {
        try {
            $block = $this->blockService->getBlock($block_id); 

            if (empty($block))
                return response()->json(
                    ['message' => 'El id del bloque no existe'], 
                    400
                );

            $enabledClassrooms = $this->classroomService
                ->getDisponibleClassroomsByBlock($block_id); 

            if (!empty($enabledClassrooms)) 
                return response()->json(
                    ['message' => 'Existen ambientes habilitados en el bloque'], 
                    400
                );

            return response()->json(
                $this->blockService->delete($block_id), 
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
     * Validates the single block data
     * @param Request $request
     * @return mixed
     */
    private function validateBlockData(Request $request)
    {
        return Validator::make($request->all(), [
            'block_name' => '
                required|
                regex:/^[A-Z0-9\-\. ]+$/',
            'block_maxfloor' => '
                required|
                integer|
                min:0',
            'block_maxclassrooms' => '
                required|
                integer|
                min:0',
            'block_status_id' => '
                integer|
                exists:block_statuses,id'
        ], [
            'block_name.required' => 'El atributo \'nombre\' no debe ser nulo o vacio',
            'block_name.regex' => 'El nombre solamente puede tener caracteres alfanumericos y \'-\', \'.\', \' \'',

            'block_maxfloor.required' => 'El atributo \'pisos\' no debe ser nulo o vacio',
            'block_maxfloor.integer' => 'La \'pisos\' debe ser un valor entero',
            'block_maxfloor.min' => 'Debe seleccionar una \'pisos\' mayor o igual a 0',

            'block_maxclassrooms.required' => 'El atributo \'capacidad de ambientes\' no debe ser nulo o vacio',
            'block_maxclassrooms.integer' => 'El \'capacidad de ambientes\' debe ser un valor entero',
            'block_maxclassrooms.exists' => 'El \'capacidad de ambientes\' debe ser un numero mayor a 0',

            'block_status_id.integer' => 'El \'estado\' debe ser un valor entero',
            'block_status_id.exists' => 'El \'estado\' debe ser una seleccion valida'
        ]);
    }
}