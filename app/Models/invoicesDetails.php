<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoicesDetails extends Model
{
    use HasFactory;
    protected $fillable = [
      'idInvoice',
      'invoiceNumber',
      'product',
      'section',
      'status',
      'valueStatus',
      'note',
      'user',
      'paymentDate',
  ];
  public function section()
  {
      return $this->belongsTo(sections::class,'section');
  }
}
