<?php

namespace App\Http\Controllers\alerts;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\MachineTank;
use App\Models\Maintenance;
use App\Models\Printer;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\SystemAlert;

class AlertController extends Controller
{
    public function index(Request $request)
{
    $query = SystemAlert::query()
        ->where('status', 'open')
        ->latest('updated_at');

    if ($request->filled('keyword')) {
        $keyword = $request->keyword;

        $query->where(function ($q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
                ->orWhere('message', 'like', "%{$keyword}%");
        });
    }

    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    if ($request->filled('level')) {
        $query->where('level', $request->level);
    }

    $alerts = $query->get();

    $summary = [
        'total' => SystemAlert::where('status', 'open')->count(),
        'urgent' => SystemAlert::where('status', 'open')
            ->where('level', 'urgent')
            ->count(),
        'danger' => SystemAlert::where('status', 'open')
            ->where('level', 'danger')
            ->count(),
        'warning' => SystemAlert::where('status', 'open')
            ->where('level', 'warning')
            ->count(),
    ];

    return view('alerts.index', compact('alerts', 'summary'));
}

    private function appendStockAlerts(Collection $alerts): void
    {
        $tanks = MachineTank::with([
                'machine.location',
                'product',
            ])
            ->where('is_active', 1)
            ->get();

        foreach ($tanks as $tank) {
            $remaining = (float) $tank->remaining_liters;
            $low = (float) $tank->low_stock_liters;
            $empty = (float) $tank->empty_stock_liters;

            if ($remaining <= $empty) {
                $alerts->push([
                    'type' => 'stock',
                    'level' => 'urgent',
                    'title' => 'น้ำยาหมด',
                    'description' => sprintf(
                        '%s ช่อง %s เหลือ %.2f ลิตร',
                        $tank->product?->name ?: 'น้ำยา',
                        $tank->tank_no,
                        $remaining
                    ),
                    'machine_code' => $tank->machine?->code,
                    'machine_name' => $tank->machine?->name,
                    'location' => $tank->machine?->location?->name,
                    'icon' => 'tabler-droplet-off',
                    'url' => route('stock.show', $tank),
                ]);

                continue;
            }

            if ($remaining <= $low) {
                $alerts->push([
                    'type' => 'stock',
                    'level' => 'warning',
                    'title' => 'น้ำยาใกล้หมด',
                    'description' => sprintf(
                        '%s ช่อง %s เหลือ %.2f ลิตร จากระดับแจ้งเตือน %.2f ลิตร',
                        $tank->product?->name ?: 'น้ำยา',
                        $tank->tank_no,
                        $remaining,
                        $low
                    ),
                    'machine_code' => $tank->machine?->code,
                    'machine_name' => $tank->machine?->name,
                    'location' => $tank->machine?->location?->name,
                    'icon' => 'tabler-droplet-exclamation',
                    'url' => route('stock.show', $tank),
                ]);
            }
        }
    }

    private function appendMachineAlerts(Collection $alerts): void
    {
        $machines = Machine::with('location')
            ->whereIn('status', [
                'offline',
                'error',
                'maintenance',
            ])
            ->get();

        foreach ($machines as $machine) {
            $level = match ($machine->status) {
                'error' => 'urgent',
                'offline' => 'danger',
                'maintenance' => 'warning',
                default => 'info',
            };

            $title = match ($machine->status) {
                'error' => 'ตู้มีปัญหา',
                'offline' => 'ตู้ออฟไลน์',
                'maintenance' => 'ตู้อยู่ระหว่างซ่อมบำรุง',
                default => 'สถานะตู้ผิดปกติ',
            };

            $alerts->push([
                'type' => 'machine',
                'level' => $level,
                'title' => $title,
                'description' => $machine->name ?: 'ไม่ระบุชื่อตู้',
                'machine_code' => $machine->code,
                'machine_name' => $machine->name,
                'location' => $machine->location?->name,
                'icon' => match ($machine->status) {
                    'offline' => 'tabler-plug-connected-x',
                    'maintenance' => 'tabler-tools',
                    default => 'tabler-alert-triangle',
                },
                'url' => route('machines.show', $machine),
            ]);
        }
    }

    private function appendPrinterAlerts(Collection $alerts): void
    {
        $printers = Printer::with('machine.location')
            ->where(function ($query) {
                $query->whereIn('status', [
                    'offline',
                    'error',
                    'paper_out',
                ])->orWhere('paper_available', 0);
            })
            ->get();

        foreach ($printers as $printer) {
            $isPaperOut = $printer->status === 'paper_out'
                || !$printer->paper_available;

            $alerts->push([
                'type' => 'printer',
                'level' => $printer->status === 'error'
                    ? 'urgent'
                    : ($isPaperOut ? 'warning' : 'danger'),
                'title' => match (true) {
                    $printer->status === 'error' => 'เครื่องปริ้นมีปัญหา',
                    $isPaperOut => 'กระดาษเครื่องปริ้นหมด',
                    default => 'เครื่องปริ้นออฟไลน์',
                },
                'description' => $printer->name ?: 'ไม่ระบุชื่อเครื่องปริ้น',
                'machine_code' => $printer->machine?->code,
                'machine_name' => $printer->machine?->name,
                'location' => $printer->machine?->location?->name,
                'icon' => $isPaperOut
                    ? 'tabler-file-off'
                    : 'tabler-printer-off',
                'url' => route('printers.show', $printer),
            ]);
        }
    }

    private function appendMaintenanceAlerts(Collection $alerts): void
    {
        $maintenances = Maintenance::with([
                'machine.location',
                'assignedTo',
            ])
            ->whereIn('status', [
                'reported',
                'assigned',
                'repairing',
            ])
            ->get();

        foreach ($maintenances as $maintenance) {
            $alerts->push([
                'type' => 'maintenance',
                'level' => $maintenance->priority === 'urgent'
                    ? 'urgent'
                    : ($maintenance->priority === 'high'
                        ? 'danger'
                        : 'warning'),
                'title' => match ($maintenance->status) {
                    'reported' => 'งานซ่อมรอดำเนินการ',
                    'assigned' => 'งานซ่อมมอบหมายแล้ว',
                    'repairing' => 'กำลังซ่อมบำรุง',
                    default => 'งานซ่อมบำรุง',
                },
                'description' => $maintenance->problem ?: 'ไม่ระบุรายละเอียดปัญหา',
                'machine_code' => $maintenance->machine?->code,
                'machine_name' => $maintenance->machine?->name,
                'location' => $maintenance->machine?->location?->name,
                'icon' => 'tabler-tool',
                'url' => route('maintenances.show', $maintenance),
            ]);
        }
    }
    public function show(SystemAlert $systemAlert)
{
    if (!$systemAlert->read_at) {
        $systemAlert->update([
            'read_at' => now(),
        ]);
    }

    if ($systemAlert->url) {
        return redirect($systemAlert->url);
    }

    return redirect()->route('alerts.index');
}
}
