<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendingMachine extends Model
{
    use SoftDeletes;

    protected $table = 'vending_machines';

    protected $fillable = [
        'machine_code',
        'machine_name',
        'location_name',
        'address',
        'latitude',
        'longitude',
        'tank_capacity_liter',
        'current_stock_liter',
        'volume_per_press_ml',
        'total_press_count',
        'status',
        'note',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'tank_capacity_liter' => 'decimal:2',
        'current_stock_liter' => 'decimal:2',
        'volume_per_press_ml' => 'decimal:2',
        'total_press_count' => 'integer',
    ];

    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            'active' => 'ใช้งานปกติ',
            'inactive' => 'ปิดใช้งาน',
            'maintenance' => 'ซ่อมบำรุง',
            'out_of_stock' => 'น้ำยาหมด',
            default => 'ไม่ทราบสถานะ',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'active' => 'bg-success',
            'inactive' => 'bg-secondary',
            'maintenance' => 'bg-warning',
            'out_of_stock' => 'bg-danger',
            default => 'bg-dark',
        };
    }

    public function getStockPercentAttribute(): float
    {
        if ((float) $this->tank_capacity_liter <= 0) {
            return 0;
        }

        $percent = ((float) $this->current_stock_liter / (float) $this->tank_capacity_liter) * 100;

        return round(min(max($percent, 0), 100), 2);
    }
}
