<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Block;

class BlockController extends Controller
{
    public function list()
    {
        try {
            $blocks = Block::select('id', 'name')
                ->get()
                ->map(function ($block){
                    return [
                        'block_id' => $block->id,
                        'name' => $block->name,
                    ];
                });
            return response()->json($blocks, 200);
        } catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }
}
