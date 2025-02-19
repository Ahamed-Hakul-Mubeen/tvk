<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    public function scopeWithProcurementSum($query)
    {
        return $query->withSum('procurement as total_price', 'price');
    }
    /**
     * Get all of the comments for the Supplier
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function procurement()
    {
        return $this->hasMany(Procurement::class, 'supplier_id', 'id');
    }
    public function procurement_payment()
    {
        return $this->hasMany(ProcurementPayment::class, 'supplier_id', 'id');
    }
}
