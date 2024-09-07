<?php

namespace App\Http\Controllers;

use App\Models\sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
$sections=sections::all();
 return view('sections.sections',compact('sections'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $input=$request->all();
       $b=sections::where('sectionName','=',$input['sectionName'])->exists();
       if($b){
        session()->flash('Error','القسم مسجل مسبقا');
        return redirect('/sections');
       }
       else{
        sections::create([
    'sectionName'=>$request->sectionName,
    'description'=>$request->description,
     'Created_by'=>(Auth::user()->name),
            
              
        ]);
    
        session()->flash('Add','تم إضافة القسم بنجاح ');
        return redirect('/sections');
    }
     

}

    /**
     * Display the specified resource.
     */
    public function show(sections $sections)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(sections $sections)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->id;

        $this->validate($request, [

            'sectionName' => 'required|max:255|unique:sections,sectionName,'.$id,
            'description' => 'required',
        ],[

            'sectionName.required' =>'يرجي ادخال اسم القسم',
            'sectionName.unique' =>'اسم القسم مسجل مسبقا',
            'description.required' =>'يرجي ادخال البيان',

        ]);

        $sections = sections::find($id);
        $sections->update([
            'sectionName' => $request->sectionName,
            'description' => $request->description,
        ]);

        session()->flash('edit','تم تعديل القسم بنجاج');
        return redirect('/sections');
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        sections::find($id)->delete();
        session()->flash('delete','تم حذف القسم بنجاح');
        return redirect('/sections');
    }
}
