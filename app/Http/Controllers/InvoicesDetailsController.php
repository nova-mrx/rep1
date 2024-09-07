<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use App\Models\invoicesDetails;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;
use App\Models\invoiceAttachments;
use Illuminate\Support\Facades\Storage;


class InvoicesDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(invoicesDetails $invoicesDetails)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
      $invoices=invoices::where('id',$id)->first();
      $details=invoicesDetails::where('idInvoice',$id)->get();
      $attachments=invoiceAttachments::where('invoiceId',$id)->get();

        return view('invoices.invoicesDetails',compact('invoices','details','attachments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoicesDetails $invoicesDetails)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $invoices = invoiceAttachments::findOrFail($request->id_file);
        $invoices->delete();
        Storage::disk('public_uploads')->delete($request->invoiceNumber.'/'.$request->file_name);
        session()->flash('delete', 'تم حذف المرفق بنجاح');
        return back();
    }

     public function get_file($invoiceNumber,$file_name)

    {
      $path = $invoiceNumber . '/' . $file_name;

// تحقق من وجود الملف قبل محاولة تنزيله
if (Storage::disk('public_uploads')->exists($path)) {
    // الحصول على المسار الكامل للملف في النظام
    $filePath = Storage::disk('public_uploads')->path($path);
    return response()->download($filePath);
} else {
    return response()->json(['error' => 'File not found.'], 404);
}

    }



    public function open_file($invoiceNumber,$file_name)

    {
      $path = $invoiceNumber . '/' . $file_name;

      if (Storage::disk('public_uploads')->exists($path)) {
          $filePath = Storage::disk('public_uploads')->path($path);
          return response()->file($filePath);
      } else {
          return response()->json(['error' => 'File not found.'], 404);

}
}
}