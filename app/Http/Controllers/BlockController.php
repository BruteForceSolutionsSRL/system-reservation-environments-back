<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Block;

class BlockController extends Controller
{
    public function list()
    {
        try {
            $blocks = [
                ["id"=>1, "value"=>"Edificio Nuevo", "max_floor"=>20],
                ["id"=>2, "value"=>"Edificio multiacademico", "max_floor"=>7],
                ["id"=>3, "value"=>"CAE", "max_floor"=>5], 
                ["id"=>4, "value"=>"Edificio MEMI", "max_floor"=>3],
                ["id"=>5, "value"=>"Departamento de Fisica", "max_floor"=>3],
                ["id"=>6, "value"=>"Departamento de Quimica", "max_floor"=>3],
                ["id"=>7, "value"=>"Departamento de Biologia", "max_floor"=>3]
            ];
            $result = array(); 
            foreach($blocks as $block) {
                $item = [
                    "block_id"=>$block["id"], 
                    "block_name"=>$block["value"], 
                    "block_maxfloor"=>$block["max_floor"] 
                ];
                array_push($result, $item);
            }
            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }
}
