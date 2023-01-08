<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\TypeOfComplaint;
use Illuminate\Http\Request;

class TypesOfComplaintsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $companies = TypeOfComplaint::when($request->text,function($q) use($request){
            $q->where('name_ar','like',"%$request->text%")
            ->orWhere('name_en','like',"%$request->text%");
        })->latest()->paginate(10);
        return response()->json(['companies' => $companies]);
    }



    public function store(Request $request)
    {
        $data = $request->validate(['name_ar' => 'required|unique:type_of_complaints,name_ar','name_en' => 'required|unique:type_of_complaints,name_en']);
        TypeOfComplaint::create($data);
    }


    public function all_types_of_complaints()
    {
        return response()->json(['types' => TypeOfComplaint::all()]);
    }


    public function edit($id)
    {

    }


    public function update(Request $request)
    {
        $data = $request->validate(['name_ar' => 'required|unique:type_of_complaints,name_ar,'.$request->id,'name_en' => 'required|unique:type_of_complaints,name_en,'.$request->id]);
        TypeOfComplaint::find($request->id)->update($data);
    }


    public function destroy($id)
    {
        TypeOfComplaint::find($id)->delete();
    }
}
