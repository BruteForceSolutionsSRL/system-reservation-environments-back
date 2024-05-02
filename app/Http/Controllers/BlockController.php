<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Block;

class BlockController extends Controller
{
    /**
     * Retrieve a list of all blocks registered
     * @return \Response
     */
    public function list()
    {
        try {
            $blocks = Block::all()
                        ->map(
                            function ($block) 
                            {
                                return [
                                    'block_id' => $block->id, 
                                    'block_name' => $block->name, 
                                    'block_maxfloor' => $block->max_floor
                                ];
                            }
                        );
            return response()->json($blocks, 200);
        } catch (\Exception $e) {
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
