<?php

namespace App\Services;

use App\Models\MachineTank;
use App\Models\SystemAlert;

class SystemAlertService
{
    public function syncTankStock(MachineTank $tank): void
    {
        $tank->loadMissing([
            'machine.location',
            'product',
        ]);

        $remaining = (float) $tank->remaining_liters;
        $lowLevel = (float) $tank->low_stock_liters;
        $emptyLevel = (float) $tank->empty_stock_liters;

        $alertKey = 'stock:tank:' . $tank->id;

        /*
        |--------------------------------------------------------------------------
        | น้ำยาหมด
        |--------------------------------------------------------------------------
        */
        if ($remaining <= $emptyLevel) {
            SystemAlert::updateOrCreate(
                [
                    'alert_key' => $alertKey,
                ],
                [
                    'type' => 'stock',
                    'level' => 'urgent',
                    'source_type' => MachineTank::class,
                    'source_id' => $tank->id,
                    'title' => 'น้ำยาหมด',
                    'message' => $this->makeStockMessage(
                        $tank,
                        $remaining,
                        $emptyLevel,
                        'หมด'
                    ),
                    'url' => route('stock.show', $tank),
                    'status' => 'open',
                    'read_at' => null,
                    'resolved_at' => null,
                ]
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | น้ำยาใกล้หมด
        |--------------------------------------------------------------------------
        */
        if ($remaining <= $lowLevel) {
            $existingAlert = SystemAlert::where('alert_key', $alertKey)->first();

            $shouldResetRead = !$existingAlert
                || $existingAlert->status !== 'open'
                || $existingAlert->level !== 'warning';

            SystemAlert::updateOrCreate(
                [
                    'alert_key' => $alertKey,
                ],
                [
                    'type' => 'stock',
                    'level' => 'warning',
                    'source_type' => MachineTank::class,
                    'source_id' => $tank->id,
                    'title' => 'น้ำยาใกล้หมด',
                    'message' => $this->makeStockMessage(
                        $tank,
                        $remaining,
                        $lowLevel,
                        'ใกล้หมด'
                    ),
                    'url' => route('stock.show', $tank),
                    'status' => 'open',
                    'read_at' => $shouldResetRead
                        ? null
                        : $existingAlert?->read_at,
                    'resolved_at' => null,
                ]
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Stock กลับมาอยู่ระดับปกติ
        |--------------------------------------------------------------------------
        */
        SystemAlert::where('alert_key', $alertKey)
            ->where('status', 'open')
            ->update([
                'status' => 'resolved',
                'resolved_at' => now(),
            ]);
    }

    private function makeStockMessage(
        MachineTank $tank,
        float $remaining,
        float $alertLevel,
        string $status
    ): string {
        $machine = $tank->machine?->code ?: '-';
        $product = $tank->product?->name ?: 'ไม่ระบุน้ำยา';
        $tankName = $tank->tank_name ?: 'ช่อง ' . $tank->tank_no;
        $location = $tank->machine?->location?->name;

        $message = sprintf(
            '%s ตู้ %s %s คงเหลือ %.2f ลิตร สถานะ%s กำหนดแจ้งเตือนที่ %.2f ลิตร',
            $product,
            $machine,
            $tankName,
            $remaining,
            $status,
            $alertLevel
        );

        if ($location) {
            $message .= ' จุดติดตั้ง ' . $location;
        }

        return $message;
    }
}
