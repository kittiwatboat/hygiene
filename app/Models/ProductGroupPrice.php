<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductGroupPrice extends Model
{
    protected $table = 'product_group_prices';

    protected $fillable = [
        'product_id',
        'machine_group_id',
        'amount_ml',
        'price',
        'special_price',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'amount_ml' => 'integer',
        'price' => 'decimal:2',
        'special_price' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function machineGroup(): BelongsTo
    {
        return $this->belongsTo(MachineGroup::class);
    }
}
