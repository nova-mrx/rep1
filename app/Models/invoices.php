<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class invoices extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
      'invoiceNumber',
      'invoiceDate',
      'dueDate',
      'product',
      'sectionId',
      'amountCollection',
      'amountCommission',
      'discount',
      'valueVAT',
      'rateVAT',
      'total',
      'status',
      'valueStatus',
      'note',
      'paymentDate',
  ];

  protected $dates = ['deleted_at'];

  public function section()
  {
      return $this->belongsTo(sections::class,'sectionId');
  }
 }


