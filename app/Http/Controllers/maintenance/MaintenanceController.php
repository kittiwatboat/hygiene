<?php

namespace App\Http\Controllers\maintenance;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\Maintenance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Maintenance::with([
                'machine.location',
                'reportedBy',
                'assignedTo',
            ])
            ->latest();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('code', 'like', "%{$keyword}%")
                    ->orWhere('problem', 'like', "%{$keyword}%")
                    ->orWhereHas('machine', function ($machineQuery) use ($keyword) {
                        $machineQuery->where('code', 'like', "%{$keyword}%")
                            ->orWhere('name', 'like', "%{$keyword}%");
                    });
            });
        }

        if ($request->filled('machine_id')) {
            $query->where('machine_id', $request->machine_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $maintenances = $query->get();

        $machines = Machine::orderBy('code')->get();

        return view('content.pages.maintenances.index', compact('maintenances', 'machines'));
    }

    public function create()
    {
        $machines = Machine::orderBy('code')->get();

        $technicians = User::whereIn('role', ['admin', 'technician'])
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();

        return view('content.pages.maintenances.create', compact('machines', 'technicians'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'machine_id' => ['required', 'exists:machines,id'],
                'type' => ['required', 'in:machine_error,printer_error,network_error,cleaning,refill_issue,other'],
                'status' => ['required', 'in:reported,assigned,repairing,completed,cancelled'],
                'priority' => ['required', 'in:low,normal,high,urgent'],
                'problem' => ['required', 'string'],
                'solution' => ['nullable', 'string'],
                'assigned_to' => ['nullable', 'exists:users,id'],
                'reported_at' => ['nullable', 'date'],
                'started_at' => ['nullable', 'date'],
                'finished_at' => ['nullable', 'date'],
                'remark' => ['nullable', 'string'],
            ],
            [
                'machine_id.required' => 'กรุณาเลือกตู้',
                'machine_id.exists' => 'ไม่พบข้อมูลตู้ที่เลือก',
                'type.required' => 'กรุณาเลือกประเภทงานซ่อม',
                'status.required' => 'กรุณาเลือกสถานะงาน',
                'priority.required' => 'กรุณาเลือกระดับความสำคัญ',
                'problem.required' => 'กรุณาระบุปัญหา',
            ]
        );

        $maintenance = DB::transaction(function () use ($request) {
            $maintenance = Maintenance::create([
                'machine_id' => $request->machine_id,
                'code' => $this->generateCode(),
                'type' => $request->type,
                'status' => $request->status,
                'priority' => $request->priority,
                'problem' => $request->problem,
                'solution' => $request->solution,
                'reported_by' => Auth::id(),
                'assigned_to' => $request->assigned_to,
                'reported_at' => $request->reported_at ?: now(),
                'started_at' => $request->started_at,
                'finished_at' => $request->finished_at,
                'remark' => $request->remark,
            ]);

            if (in_array($maintenance->status, ['reported', 'assigned', 'repairing'])) {
                $maintenance->machine?->update([
                    'status' => 'maintenance',
                ]);
            }

            if ($maintenance->status === 'completed') {
                $maintenance->machine?->update([
                    'status' => 'active',
                ]);
            }

            return $maintenance;
        });

        return redirect()
            ->route('maintenances.show', $maintenance)
            ->with('success', 'เปิดงานซ่อมบำรุงสำเร็จ');
    }

    public function show(Maintenance $maintenance)
    {
        $maintenance->load([
            'machine.location',
            'reportedBy',
            'assignedTo',
        ]);

        return view('content.pages.maintenances.show', compact('maintenance'));
    }

    public function edit(Maintenance $maintenance)
    {
        $maintenance->load('machine');

        $machines = Machine::orderBy('code')->get();

        $technicians = User::whereIn('role', ['admin', 'technician'])
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();

        return view('content.pages.maintenances.edit', compact('maintenance', 'machines', 'technicians'));
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        $request->validate(
            [
                'machine_id' => ['required', 'exists:machines,id'],
                'type' => ['required', 'in:machine_error,printer_error,network_error,cleaning,refill_issue,other'],
                'status' => ['required', 'in:reported,assigned,repairing,completed,cancelled'],
                'priority' => ['required', 'in:low,normal,high,urgent'],
                'problem' => ['required', 'string'],
                'solution' => ['nullable', 'string'],
                'assigned_to' => ['nullable', 'exists:users,id'],
                'reported_at' => ['nullable', 'date'],
                'started_at' => ['nullable', 'date'],
                'finished_at' => ['nullable', 'date'],
                'remark' => ['nullable', 'string'],
            ],
            [
                'machine_id.required' => 'กรุณาเลือกตู้',
                'type.required' => 'กรุณาเลือกประเภทงานซ่อม',
                'status.required' => 'กรุณาเลือกสถานะงาน',
                'priority.required' => 'กรุณาเลือกระดับความสำคัญ',
                'problem.required' => 'กรุณาระบุปัญหา',
            ]
        );

        DB::transaction(function () use ($request, $maintenance) {
            $maintenance->update([
                'machine_id' => $request->machine_id,
                'type' => $request->type,
                'status' => $request->status,
                'priority' => $request->priority,
                'problem' => $request->problem,
                'solution' => $request->solution,
                'assigned_to' => $request->assigned_to,
                'reported_at' => $request->reported_at ?: $maintenance->reported_at,
                'started_at' => $request->started_at,
                'finished_at' => $request->finished_at,
                'remark' => $request->remark,
            ]);

            if (in_array($maintenance->status, ['reported', 'assigned', 'repairing'])) {
                $maintenance->machine?->update([
                    'status' => 'maintenance',
                ]);
            }

            if ($maintenance->status === 'completed') {
                $maintenance->machine?->update([
                    'status' => 'active',
                ]);
            }
        });

        return redirect()
            ->route('maintenances.show', $maintenance)
            ->with('success', 'อัปเดตงานซ่อมบำรุงสำเร็จ');
    }

    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();

        return redirect()
            ->route('maintenances.index')
            ->with('success', 'ลบงานซ่อมบำรุงสำเร็จ');
    }

    private function generateCode(): string
    {
        $prefix = 'MT-' . now()->format('ymd');

        $count = Maintenance::whereDate('created_at', today())->count() + 1;

        return $prefix . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
