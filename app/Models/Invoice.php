<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Invoice extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function customer(): Relation
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice_items(): Relation
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
