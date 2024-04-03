<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Block;

class BlockController extends Controller
{
    public function index()
    {
        try {
            $blocks = Block::select('id', 'name')
                ->get();
            return response()->json($blocks, 200);
        } catch (Exeption $e) {
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }
}
