<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceAttachmentsController;
use App\Http\Controllers\ProfileController;
use App\Models\Invoices;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\InvoiceArchiveController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Invoices_Report;
use App\Http\Controllers\Customers_Report;
use App\Models\InvoiceAttachments;

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('invoices', InvoicesController::class);
    Route::resource('sections', SectionsController::class);
    Route::resource('products', ProductsController::class);
    Route::resource('InvoiceAttachments', InvoiceAttachmentsController::class);
});

Route::get('invoicesDetails/{id}', [InvoicesDetailsController::class, 'edit']);
Route::get('download/{invoiceNumber}/{file_name}', [InvoicesDetailsController::class, 'get_file']);
Route::get('View_file/{invoiceNumber}/{file_name}', [InvoicesDetailsController::class, 'open_file']);
Route::post('delete_file', [InvoicesDetailsController::class, 'destroy'])->name('delete_file');

Route::get('Invoice_Paid', [InvoicesController::class, 'Invoice_Paid']);
Route::get('Invoice_UnPaid', [InvoicesController::class, 'Invoice_UnPaid']);
Route::get('Invoice_Partial', [InvoicesController::class, 'Invoice_Partial']);
Route::resource('Archive', InvoiceArchiveController::class);
Route::get('archive', [InvoiceArchiveController::class, 'archiveIndex'])->name('Archive.index');

// تعريف المسارات لتقرير الفواتير
Route::get('invoices_report', [Invoices_Report::class, 'index']);
Route::post('Search_invoices', [Invoices_Report::class, 'Search_invoices'])->name('Search_invoices');

Route::get('section/{id}', [InvoicesController::class, 'getproducts']);
Route::get('/edit_invoice/{id}', [InvoicesController::class, 'edit']);
Route::resource('invoices', InvoicesController::class);
Route::get('/Status_show/{id}', [InvoicesController::class, 'show'])->name('Status_show');
Route::post('/Status_Update/{id}', [InvoicesController::class, 'Status_Update'])->name('status_Update');
Route::get('Print_invoice/{id}', 'App\Http\Controllers\InvoicesController@Print_invoice');

// تعريف المسارات لتقرير العملاء
Route::get('customers_report', [Customers_Report::class, 'index'])->name("customers_report");
Route::post('Search_customers', [Customers_Report::class, 'Search_customers'])->name('Search_customers');
Route::get('MarkAsRead_all', [InvoicesController::class, 'MarkAsRead_all'])->name('MarkAsRead_all');

require __DIR__.'/auth.php';

Route::get('/{page}', [AdminController::class, 'index']);
