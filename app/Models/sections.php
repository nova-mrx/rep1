<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sections extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(products::class, 'sectionId');
    }

    public function invoices()
    {
        return $this->hasMany(invoices::class, 'sectionId');
    }
    public function invoicesDetails()
    {
        return $this->hasMany(invoicesDetails::class, 'sectionId');
    }
    
}
