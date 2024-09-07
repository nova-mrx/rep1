<?php

namespace App\Http\Controllers;

use App\Models\InvoiceAttachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceAttachmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // يمكن إضافة الكود هنا
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // يمكن إضافة الكود هنا
    }

    /**
     * Store a newly created resource in storage.
     */
    
     public function store(Request $request)
     {
         $this->validate($request, [
             'fileName' => 'required|mimes:pdf,jpeg,png,jpg',
             'invoiceNumber' => 'required|string',
             'invoiceId' => 'required|integer',
         ], [
             'fileName.mimes' => 'صيغة المرفق يجب ان تكون pdf, jpeg, png, jpg',
         ]);
     
         // إعادة تسمية الحقل ليتطابق مع اسم الحقل في قاعدة البيانات
         $request->merge(['fileName' => $request->file('fileName')->getClientOriginalName()]);
     
         // حفظ المرفق في قاعدة البيانات
         $attachments = new InvoiceAttachments();
         $attachments->fileName = $request->fileName;
         $attachments->invoiceNumber = $request->invoiceNumber;
         $attachments->invoiceId = $request->invoiceId;
         $attachments->Created_by = Auth::user()->name;
         $attachments->save();
     
         // نقل الملف إلى المجلد المناسب
         $request->file('fileName')->move(public_path('Attachments/' . $request->invoiceNumber), $request->fileName);
     
         // عرض رسالة نجاح
         session()->flash('Add', 'تم اضافة المرفق بنجاح');
         return back();
     }
    

    /**
     * Display the specified resource.
     */
    public function show(InvoiceAttachments $invoiceAttachments)
    {
        // يمكن إضافة الكود هنا
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InvoiceAttachments $invoiceAttachments)
    {
        // يمكن إضافة الكود هنا
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InvoiceAttachments $invoiceAttachments)
    {
        // يمكن إضافة الكود هنا
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvoiceAttachments $invoiceAttachments)
    {
        // يمكن إضافة الكود هنا
    }
}
