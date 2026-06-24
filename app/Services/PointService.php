<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\PointTransaction;
use App\Models\Promotion;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class PointService
{
    /**
     * เพิ่มแต้มให้สมาชิกหลังชำระเงินสำเร็จ
     */
    public function earnFromSale(Sale $sale): int
    {
        if (!$sale->customer_id) {
            return 0;
        }

        /*
        |--------------------------------------------------------------------------
        | ป้องกันเพิ่มแต้มซ้ำในรายการขายเดียวกัน
        |--------------------------------------------------------------------------
        */
        $alreadyEarned = PointTransaction::query()
            ->where('sale_id', $sale->id)
            ->where('type', 'earn')
            ->exists();

        if ($alreadyEarned) {
            return (int) $sale->points_earned;
        }

        /*
        |--------------------------------------------------------------------------
        | ปรับชื่อตามสถานะจริงของระบบ
        |--------------------------------------------------------------------------
        */
        if ($sale->payment_status !== 'paid') {
            return 0;
        }

        return DB::transaction(function () use ($sale) {
            $customer = Customer::query()
                ->lockForUpdate()
                ->find($sale->customer_id);

            if (
                !$customer ||
                !$customer->is_active ||
                $customer->status !== 'active'
            ) {
                return 0;
            }

            $promotion = $this->findEarnPointPromotion($sale);

            if (!$promotion) {
                return 0;
            }

            $pointsEarned = (int) $promotion->points_reward;

            if ($pointsEarned <= 0) {
                return 0;
            }

            $balanceBefore = (int) $customer->points_balance;
            $balanceAfter = $balanceBefore + $pointsEarned;

            $customer->update([
                'points_balance' => $balanceAfter,
                'last_used_at' => now(),
            ]);

            $sale->update([
                'promotion_id' => $promotion->id,
                'points_earned' => $pointsEarned,
            ]);

            PointTransaction::create([
                'customer_id' => $customer->id,
                'sale_id' => $sale->id,
                'promotion_id' => $promotion->id,
                'type' => 'earn',
                'points' => $pointsEarned,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'reference_no' => $this->generateReference(),
                'description' => 'ได้รับแต้มจากรายการขาย #' . $sale->id,
                'expired_at' => null,
                'created_by' => null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | นับจำนวนการใช้โปรโมชัน
            |--------------------------------------------------------------------------
            */
            $promotion->increment('used_count');

            return $pointsEarned;
        });
    }

    /**
     * ค้นหาโปรโมชันรับแต้มที่ใช้กับรายการขายนี้ได้
     */
    private function findEarnPointPromotion(Sale $sale): ?Promotion
    {
        return Promotion::query()
            ->where('promotion_type', 'earn_points')
            ->where('is_active', true)

            /*
            |--------------------------------------------------------------------------
            | วันเริ่มไม่ใส่ = ใช้ได้ทันที
            |--------------------------------------------------------------------------
            */
            ->where(function ($query) {
                $query->whereNull('start_at')
                    ->orWhere('start_at', '<=', now());
            })

            /*
            |--------------------------------------------------------------------------
            | วันสิ้นสุดไม่ใส่ = ใช้ได้ตลอด
            |--------------------------------------------------------------------------
            */
            ->where(function ($query) {
                $query->whereNull('end_at')
                    ->orWhere('end_at', '>=', now());
            })

            /*
            |--------------------------------------------------------------------------
            | จำนวนสิทธิ์ไม่กำหนด หรือยังใช้ไม่ครบ
            |--------------------------------------------------------------------------
            */
            ->where(function ($query) {
                $query->whereNull('usage_limit')
                    ->orWhereColumn('used_count', '<', 'usage_limit');
            })

            /*
            |--------------------------------------------------------------------------
            | ยอดซื้อขั้นต่ำ
            |--------------------------------------------------------------------------
            */
            ->where(function ($query) use ($sale) {
                $query->whereNull('minimum_amount')
                    ->orWhere(
                        'minimum_amount',
                        '<=',
                        $this->getSaleAmount($sale)
                    );
            })

            /*
            |--------------------------------------------------------------------------
            | ใช้กับสินค้าทั้งหมด หรือเฉพาะสินค้าของรายการขาย
            |--------------------------------------------------------------------------
            */
            ->where(function ($query) use ($sale) {
                $query->where('scope', 'all')
                    ->orWhere(function ($subQuery) use ($sale) {
                        $subQuery->where('scope', 'product')
                            ->where(
                                'product_id',
                                $sale->product_id
                            );
                    });
            })

            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->first();
    }

    private function getSaleAmount(Sale $sale): float
    {
        /*
        |--------------------------------------------------------------------------
        | ปรับตามชื่อคอลัมน์ยอดเงินจริงของตาราง sales
        |--------------------------------------------------------------------------
        */
        if (isset($sale->total_amount)) {
            return (float) $sale->total_amount;
        }

        if (isset($sale->amount)) {
            return (float) $sale->amount;
        }

        return 0;
    }

    private function generateReference(): string
    {
        return 'PT-'
            . now()->format('YmdHis')
            . '-'
            . strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
    }
}
