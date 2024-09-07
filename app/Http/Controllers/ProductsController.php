<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\sections;
use Illuminate\Http\Request;


class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections= sections::all();
        $products= products::all();
        return view('products.products',compact('sections','products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
        
        products::create([
    'productName'=>$request->productName,
    'description'=>$request->description,
    'sectionId'=>$request->sectionId,

        ]);
    
        session()->flash('Add','تم إضافة المنتج بنجاح ');
        return redirect('/products');
    }
     

    

    /**
     * Display the specified resource.
     */
    public function show(products $products)
    {

        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

       $id = sections::where('sectionName', $request->sectionName)->first()->id;

       $Products = products::findOrFail($request->id);

       $Products->update([
       'productName' => $request->productName,
       'description' => $request->description,
       'sectionId' => $id,
       ]);

       session()->flash('edit', 'تم تعديل المنتج بنجاح');
       return back();
        
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
    
           $products = products::findOrFail($request->id);
           $products->delete();
           session()->flash('delete', 'تم حذف المنتج بنجاح');
           return back();
      
  }}
    

