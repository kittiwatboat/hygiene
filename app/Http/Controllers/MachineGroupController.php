<?php

namespace App\Http\Controllers;

use App\Models\FrontendTheme;
use App\Models\MachineGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MachineGroupController extends Controller
{
  public function index(Request $request)
  {
    $keyword = trim((string)$request->get('keyword', ''));
    $status = $request->get('status');
    $groups = MachineGroup::query()->with('theme')->withCount('machines')
      ->when($keyword !== '', fn($q) => $q->where(fn($s) => $s->where('name', 'like', "%{$keyword}%")->orWhere('code', 'like', "%{$keyword}%")->orWhere('remark', 'like', "%{$keyword}%")))
      ->when(in_array((string)$status, ['0', '1'], true), fn($q) => $q->where('is_active', (int)$status))
      ->orderByDesc('is_active')->orderBy('name')->paginate(20)->withQueryString();
    return view('content.pages.machine-groups.index', compact('groups', 'keyword', 'status'));
  }
  public function create()
  {
    $themes = FrontendTheme::query()->where('is_active', true)->orderBy('name')->get();
    return view('content.pages.machine-groups.create', compact('themes'));
  }
  public function store(Request $request)
  {
    $v = $this->validateGroup($request);
    MachineGroup::create(['name' => $v['name'], 'code' => strtoupper($v['code']), 'frontend_theme_id' => $v['frontend_theme_id'] ?? null, 'is_active' => $request->boolean('is_active'), 'remark' => $v['remark'] ?? null]);
    return redirect()->route('machine-groups.index')->with('success', 'เพิ่มกลุ่มตู้สำเร็จ');
  }
  public function edit(MachineGroup $machineGroup)
  {
    $themes = FrontendTheme::query()->where('is_active', true)->orWhere('id', $machineGroup->frontend_theme_id)->orderBy('name')->get();
    return view('content.pages.machine-groups.edit', compact('machineGroup', 'themes'));
  }
  public function update(Request $request, MachineGroup $machineGroup)
  {
    $v = $this->validateGroup($request, $machineGroup);
    $machineGroup->update(['name' => $v['name'], 'code' => strtoupper($v['code']), 'frontend_theme_id' => $v['frontend_theme_id'] ?? null, 'is_active' => $request->boolean('is_active'), 'remark' => $v['remark'] ?? null]);
    return redirect()->route('machine-groups.index')->with('success', 'แก้ไขกลุ่มตู้สำเร็จ');
  }
  public function destroy(MachineGroup $machineGroup)
  {
    if ($machineGroup->machines()->exists()) return back()->with('error', 'ไม่สามารถลบกลุ่มตู้ได้ เนื่องจากยังมีตู้ใช้งานกลุ่มนี้อยู่');
    $machineGroup->delete();
    return redirect()->route('machine-groups.index')->with('success', 'ลบกลุ่มตู้สำเร็จ');
  }
  private function validateGroup(Request $request, ?MachineGroup $machineGroup = null): array
  {
    return $request->validate(['name' => ['required', 'string', 'max:255'], 'code' => ['required', 'string', 'max:100', Rule::unique('machine_groups', 'code')->ignore($machineGroup?->id)], 'frontend_theme_id' => ['nullable', 'integer', 'exists:frontend_themes,id'], 'is_active' => ['nullable', 'boolean'], 'remark' => ['nullable', 'string']], ['name.required' => 'กรุณากรอกชื่อกลุ่มตู้', 'code.required' => 'กรุณากรอกรหัสกลุ่มตู้', 'code.unique' => 'รหัสกลุ่มตู้นี้ถูกใช้งานแล้ว', 'frontend_theme_id.exists' => 'Theme ที่เลือกไม่ถูกต้อง']);
  }
}
