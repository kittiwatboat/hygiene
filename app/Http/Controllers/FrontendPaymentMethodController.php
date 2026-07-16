<?php

namespace App\Http\Controllers;

use App\Models\FrontendPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FrontendPaymentMethodController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                'unique:frontend_payment_methods,code',
            ],
            'name' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'action_key' => ['nullable', 'string', 'max:100'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'remark' => ['nullable', 'string'],
        ]);

        $logoPath = null;

        if ($request->hasFile('logo')) {
            $logoPath = $this->uploadLogo($request->file('logo'));
        }

        FrontendPaymentMethod::create([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'subtitle' => $validated['subtitle'] ?? null,
            'action_key' => $validated['action_key'] ?? null,
            'logo_path' => $logoPath,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $validated['sort_order'] ?? 0,
            'remark' => $validated['remark'] ?? null,
        ]);

        return back()->with('success', 'เพิ่มช่องทางการชำระเงินสำเร็จ');
    }

    public function update(Request $request, FrontendPaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('frontend_payment_methods', 'code')->ignore($paymentMethod->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'action_key' => ['nullable', 'string', 'max:100'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'remark' => ['nullable', 'string'],
        ]);

        $logoPath = $paymentMethod->logo_path;

        if ($request->hasFile('logo')) {
            $this->deleteLogo($logoPath);
            $logoPath = $this->uploadLogo($request->file('logo'));
        }

        $paymentMethod->update([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'subtitle' => $validated['subtitle'] ?? null,
            'action_key' => $validated['action_key'] ?? null,
            'logo_path' => $logoPath,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $validated['sort_order'] ?? 0,
            'remark' => $validated['remark'] ?? null,
        ]);

        return back()->with('success', 'แก้ไขช่องทางการชำระเงินสำเร็จ');
    }

    public function destroy(FrontendPaymentMethod $paymentMethod)
    {
        $this->deleteLogo($paymentMethod->logo_path);

        $paymentMethod->delete();

        return back()->with('success', 'ลบช่องทางการชำระเงินสำเร็จ');
    }

    private function uploadLogo($file): string
    {
        $uploadPath = base_path('../public_html/assets/img/frontend/payment-methods');

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $fileName = uniqid('payment_', true)
            . '.'
            . strtolower($file->getClientOriginalExtension());

        $file->move($uploadPath, $fileName);

        return $fileName;
    }

    private function deleteLogo(?string $fileName): void
    {
        if (!$fileName) {
            return;
        }

        $filePath = base_path('../public_html/assets/img/frontend/payment-methods/' . $fileName);

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
