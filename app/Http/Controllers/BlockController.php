<?php
namespace App\Http\Controllers;

use Illuminate\Http\{
    JsonResponse as Response,
    Request
};
use Exception;

use App\Service\ServiceImplementation\BlockServiceImpl; 

class BlockController extends Controller
{
    private $blockService; 
    public function __construct()
    {
        $this->blockService = new BlockServiceImpl();
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
}
