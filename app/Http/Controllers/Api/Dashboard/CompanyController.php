<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $companies = Company::when($request->text,function($q) use($request){
            $q->where('name_ar','like',"%$request->text%")
            ->orWhere('name_en','like',"%$request->text%");
        })->latest()->paginate(10);
        return response()->json(['companies' => $companies]);
    }



    public function store(Request $request)
    {
        $data = $request->validate(['name_ar' => 'required|unique:companies,name_ar','name_en' => 'required|unique:companies,name_en']);
        Company::create($data);
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {

    }


    public function update(Request $request)
    {
        $data = $request->validate(['name_ar' => 'required|unique:companies,name_ar,'.$request->id,'name_en' => 'required|unique:companies,name_en,'.$request->id]);
        Company::find($request->id)->update($data);
    }


    public function destroy($id)
    {
        Company::find($id)->delete();
    }
}
