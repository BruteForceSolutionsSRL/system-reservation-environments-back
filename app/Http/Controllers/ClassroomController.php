<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Classroom;
use App\Models\Block;
use App\Models\ClassroomType;

class ClassroomController extends Controller
{
    /**
     * @param
     *  None
     * @return
     *  All classrooms
     */
    public function list()
    {
        $classrooms = Classroom::select('id', 'name', 'capacity', 'floor')
            ->get()
            ->map(function ($classroom) {
                return $this->formatClassroomResponse($classroom);
            });
        return response()->json($classrooms, 200);
    }

    private function formatClassroomResponse($classroom)
    {
        return [
            'classroom_id' => $classroom->id,
            'classroom_name' => $classroom->name,
            'capacity' => $classroom->capacity,
            'floor' => $classroom->floor,
        ];
    }

    /**
     * @covers:
     * To retrieve data about a environment
     */
    public function index()
    {
    }

    public function classroomsByBlock($blockId)
    {
        try {
            $classrooms = Classroom::select('id', 'name', 'capacity', 'floor')
                ->where('block_id', $blockId)
                ->get()
                ->map(function ($classroom){
                    return $this->formatClassroomResponse($classroom);
                });

            return response()->json($classrooms, 200);
        } catch (Exception $e) {
            return response()->json(['error'=>$e->getMessage()], 500);
        }
    }

    /**
     * @param
     * Request (body):
     * {
     * 'name': str,
     * 'capacity': 'number',
     * 'classroomTypeID': int,
     * 'blockID': int
     * 'floor': int
     * }
     */
    public function store(Request $request)
    {
        try {
            $name = strtolower($request->input('name'));
            $capacity = $request->input('capacity');
            $classroomTypeID = $request->input('classroomTypeID');
            $blockID = $request->input('blockID');
            $floor = $request->input('floor');

            $classroom = Classroom::where('name', '=', $name)
                            ->get()
                            ->pop();

            if ($classroom!=null) {
                return response()->json(['message'=>'name already registed'], 208);
            }
            $block = Block::find($blockID);
            if ($block==null) {
                return response()->json(['message'=>'block does not exists'], 404);
            }
            $classroomType = ClassroomType::find($classroomTypeID);
            if ($classroomType==null) {
                return response()->json(['message'=>'classroom type does not exist'], 404);
            }

            DB::transaction(
                function()
                use ($name, $capacity, $floor, $blockID, $classroomTypeID)
                {
                $classroom = new Classroom();
                $classroom->name = $name;
                $classroom->capacity = $capacity;
                $classroom->floor = $floor;
                $classroom->block_id = $blockID;
                $classroom->classroom_type_id = $classroomTypeID;

                $classroom->save();
            });
            return response()->json(['message'=>'classroom registered'], 200);
        } catch (Exception $e) {
            return response()->json(['error'=>$e->getMessage()], 500);
        }
    }
    /**
     * @covers
     * To cancel assigned types.
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * @covers
     * IDK
     */
    public function destroy($id)
    {
    }
}
