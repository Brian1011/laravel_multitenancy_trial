<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
           'name' => ['required'],
            'course_id' => ['required'],
        ]);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all(), 'status'=>0], 422);
        } else {
            $unit = Unit::create($request->only('name','course_id'));
            return response()->json(['status'=>1, 'data'=>$unit], 200);
        }
    }

    public function getAll(){
        $units = Unit::with('course:id,name')
            ->select(['id','name','course_id'])
            ->get();
        return response()->json(['status'=>1, 'data'=>$units], 200);
    }
}
