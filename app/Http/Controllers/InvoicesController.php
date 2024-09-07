<?php

namespace App\Http\Controllers;

use App\Models\invoices;

use App\Models\User;
use App\Models\sections;
use App\Models\invoicesDetails;
use App\Models\invoiceAttachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\addInvoice;
use App\Models\Invoics;
use Illuminate\Support\Facades\Notification;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $invoices=invoices::all();
        return view('invoices.invoices',compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections=sections::all();
        return view('invoices.add_invoice',compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $invoice = invoices::create([
            'invoiceNumber' => $request->invoice_number,
            'invoiceDate' => $request->invoice_Date,
            'dueDate' => $request->Due_date,
            'product' => $request->product,
            'sectionId' => $request->Section,
            'amountCollection' => $request->Amount_collection,
            'amountCommission' => $request->Amount_Commission,
            'discount' => $request->Discount,
            'valueVAT' => $request->Value_VAT,
            'rateVAT' => $request->Rate_VAT,
            'total' => $request->Total,
            'status' => 'غير مدفوعة',
            'valueStatus' => 2,
            'note' => $request->note,
        ]);

        invoicesDetails::create([
            'idInvoice' => $invoice->id,
            'invoiceNumber' => $request->invoice_number,
            'product' => $request->product,
            'section' => $request->Section,
            'status' => 'غير مدفوعة',
            'valueStatus' => 2,
            'note' => $request->note,
            'user' => Auth::user()->name,
        ]);

        if ($request->hasFile('pic')) {
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachments = new invoiceAttachments();
            $attachments->fileName = $file_name;
            $attachments->invoiceNumber = $invoice_number;
            $attachments->Created_by = Auth::user()->name;
            $attachments->invoiceId = $invoice->id;
            $attachments->save();

            $request->pic->move(public_path('Attachments/' . $invoice_number), $file_name);
        }

        $users = User::find(1);
        Notification::send($users, new \App\Notifications\addInvoice($invoice));

        session()->flash('Add', 'تم اضافة الفاتورة بنجاح');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
      $invoices = invoices::where('id', $id)->first();
      return view('invoices.status_update', compact('invoices'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

      $invoices = invoices::where('id', $id)->first();
      $sections = sections::all();
      return view('invoices.edit_invoice', compact('sections', 'invoices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
      $invoices = invoices::findOrFail($request->invoiceId);
      $invoices->update([
          'invoiceNumber' => $request->invoiceNumber,
          'invoiceDate' => $request->invoiceDate,
          'dueDate' => $request->dueDate,
          'product' => $request->product,
          'sectionId' => $request->Section,
          'amountCollection' => $request->amountCollection,
          'amountCommission' => $request->amountCommission,
          'discount' => $request->discount,
          'valueVAT' => $request->valueVAT,
          'rateVAT' => $request->rateVAT,
          'total' => $request->total,
          'note' => $request->note,
      ]);



      $invoice_id = invoices::latest()->first()->id;
      invoicesDetails::create([
              'idInvoice' => $invoice_id,
              'invoiceNumber' => $request->invoiceNumber,
              'product' => $request->product,
              'section' => $request->Section,
              'status' => 'غير مدفوعة',
              'valueStatus' => 2,
              'note' => $request->note,
              'user' => (Auth::user()->name),
          ]);

          if ($request->hasFile('pic')) {

            $invoiceId = Invoices::latest()->first()->id;
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoiceNumber = $request->invoiceNumber;

            $attachments = new invoiceAttachments();
            $attachments->fileName = $file_name;
            $attachments->invoiceNumber = $invoiceNumber;
            $attachments->Created_by = Auth::user()->name;
            $attachments->invoiceId = $invoiceId;
            $attachments->save();


    }
    session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
    return back();}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
     $id = $request->invoice_id;
     $invoices = invoices::where('id', $id)->first();
     $Details = invoiceAttachments::where('invoiceId', $id)->first();

      $id_page =$request->id_page;


     if (!$id_page==2) {

     if (!empty($Details->invoice_number)) {

         Storage::disk('public_uploads')->deleteDirectory($Details->invoice_number);
     }

     $invoices->forceDelete();
     session()->flash('delete_invoice');
     return redirect('/invoices');

     }

     else {

         $invoices->delete();
         session()->flash('archive_invoice');
         return redirect('/Archive');
     }
    }



  public function getproducts( $id){
        $products=DB::table("products")->where("sectionId",$id)->pluck("productName","id");
        return json_encode($products);
    }



    public function Status_Update($id, Request $request)
{
    $invoices = invoices::findOrFail($id);

    if ($request->status === 'مدفوعة') {
        $invoices->update([
            'valueStatus' => 1,
            'status' => $request->status,
            'paymentDate' => $request->paymentDate,
        ]);

        invoicesDetails::create([
            'idInvoice' => $request->invoiceId,
            'invoiceNumber' => $request->invoiceNumber,
            'product' => $request->product,
            'section' => $request->Section,
            'status' => $request->status,
            'valueStatus' => 1,
            'note' => $request->note,
            'paymentDate' => $request->paymentDate,
            'user' => (Auth::user()->name),
        ]);
    } else {
        $invoices->update([
            'valueStatus' => 3,
            'status' => $request->status,
            'paymentDate' => $request->paymentDate,
        ]);
        invoicesDetails::create([
            'idInvoice' => $request->invoiceId,
            'invoiceNumber' => $request->invoiceNumber,
            'product' => $request->product,
            'section' => $request->Section,
            'status' => $request->status,
            'valueStatus' => 3,
            'note' => $request->note,
            'paymentDate' => $request->paymentDate,
            'user' => (Auth::user()->name),
        ]);
    }
    session()->flash('Status_Update');
    return redirect('/invoices');
}


public function Invoice_Paid()
{
    $invoices = Invoices::where('valueStatus', 1)->get();
    return view('invoices.invoices_paid',compact('invoices'));
}

public function Invoice_unPaid()
{
    $invoices = Invoices::where('valueStatus',2)->get();
    return view('invoices.invoices_unpaid',compact('invoices'));
}

public function Invoice_Partial()
{
    $invoices = Invoices::where('valueStatus',3)->get();
    return view('invoices.invoices_Partial',compact('invoices'));
}

public function Print_invoice($id)
{
    $invoices = invoices::where('id', $id)->first();
    return view('invoices.Print_invoice',compact('invoices'));
}



public function MarkAsRead_all (Request $request)
{

    $userUnreadNotification= auth()->user()->unreadNotifications;

    if($userUnreadNotification) {
        $userUnreadNotification->markAsRead();
        return back();
    }

}


}
