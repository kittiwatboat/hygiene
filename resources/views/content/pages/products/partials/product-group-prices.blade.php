{{--
|--------------------------------------------------------------------------
| product-group-prices.blade.php
|--------------------------------------------------------------------------
| ใช้ในหน้า create/edit ของสินค้า/น้ำยา
|
| ตัวแปรที่ต้องมี:
| $machineGroups
| $product (เฉพาะหน้า edit)
|--------------------------------------------------------------------------
--}}

@php
    $existingGroupPrices = isset($product)
        ? $product->groupPrices->values()
        : collect();

    $oldGroupPrices = old('group_prices');

    if (is_array($oldGroupPrices)) {
        $priceRows = collect($oldGroupPrices);
    } else {
        $priceRows = $existingGroupPrices->map(function ($price) {
            return [
                'machine_group_id' => $price->machine_group_id,
                'amount_ml' => $price->amount_ml,
                'price' => $price->price,
                'special_price' => $price->special_price,
                'is_active' => $price->is_active ? 1 : 0,
            ];
        });
    }

    if ($priceRows->isEmpty()) {
        $priceRows = collect([
            [
                'machine_group_id' => '',
                'amount_ml' => '',
                'price' => '',
                'special_price' => '',
                'is_active' => 1,
            ],
        ]);
    }
@endphp

<div class="col-12">
    <hr class="my-2">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h6 class="mb-1">
                ราคาตามกลุ่มตู้
            </h6>

            <p class="text-muted mb-0">
                สินค้าเดียวกันสามารถกำหนดหลายปริมาตร
                และราคาแตกต่างกันในแต่ละกลุ่มตู้ได้
            </p>
        </div>

        <button
            type="button"
            class="btn btn-label-primary btn-sm"
            id="addGroupPriceRow"
        >
            <i class="icon-base ti tabler-plus me-1"></i>
            เพิ่มราคา
        </button>
    </div>
</div>

<div class="col-12">
    @error('group_prices')
        <div class="alert alert-danger">
            {{ $message }}
        </div>
    @enderror

    <div class="table-responsive border rounded">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="min-width:220px;">
                        กลุ่มตู้
                    </th>
                    <th style="min-width:150px;">
                        ปริมาตร / ml
                    </th>
                    <th style="min-width:150px;">
                        ราคาปกติ / บาท
                    </th>
                    <th style="min-width:150px;">
                        ราคาพิเศษ
                    </th>
                    <th style="width:110px;" class="text-center">
                        เปิดใช้
                    </th>
                    <th style="width:80px;"></th>
                </tr>
            </thead>

            <tbody id="groupPriceRows">
                @foreach ($priceRows as $index => $row)
                    <tr class="group-price-row">
                        <td>
                            <select
                                name="group_prices[{{ $index }}][machine_group_id]"
                                class="form-select"
                            >
                                <option value="">
                                    -- เลือกกลุ่มตู้ --
                                </option>

                                @foreach ($machineGroups as $group)
                                    <option
                                        value="{{ $group->id }}"
                                        {{ (string) ($row['machine_group_id'] ?? '') === (string) $group->id ? 'selected' : '' }}
                                    >
                                        {{ $group->name }}
                                        @if ($group->code)
                                            ({{ $group->code }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </td>

                        <td>
                            <input
                                type="number"
                                min="1"
                                step="1"
                                name="group_prices[{{ $index }}][amount_ml]"
                                value="{{ $row['amount_ml'] ?? '' }}"
                                class="form-control"
                                placeholder="เช่น 500"
                            >
                        </td>

                        <td>
                            <input
                                type="number"
                                min="0"
                                step="0.01"
                                name="group_prices[{{ $index }}][price]"
                                value="{{ $row['price'] ?? '' }}"
                                class="form-control"
                                placeholder="เช่น 20"
                            >
                        </td>

                        <td>
                            <input
                                type="number"
                                min="0"
                                step="0.01"
                                name="group_prices[{{ $index }}][special_price]"
                                value="{{ $row['special_price'] ?? '' }}"
                                class="form-control"
                                placeholder="ถ้าไม่มีเว้นว่าง"
                            >
                        </td>

                        <td class="text-center">
                            <input
                                type="hidden"
                                name="group_prices[{{ $index }}][is_active]"
                                value="0"
                            >

                            <div class="form-check form-switch d-inline-block">
                                <input
                                    type="checkbox"
                                    name="group_prices[{{ $index }}][is_active]"
                                    value="1"
                                    class="form-check-input"
                                    {{ (int) ($row['is_active'] ?? 1) === 1 ? 'checked' : '' }}
                                >
                            </div>
                        </td>

                        <td class="text-center">
                            <button
                                type="button"
                                class="btn btn-sm btn-icon btn-label-danger remove-group-price-row"
                                title="ลบ"
                            >
                                <i class="icon-base ti tabler-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="form-text mt-2">
        ตัวอย่าง: Group A → 500 ml = 20 บาท,
        1,000 ml = 35 บาท
    </div>
</div>

<template id="groupPriceRowTemplate">
    <tr class="group-price-row">
        <td>
            <select
                data-name="machine_group_id"
                class="form-select"
            >
                <option value="">
                    -- เลือกกลุ่มตู้ --
                </option>

                @foreach ($machineGroups as $group)
                    <option value="{{ $group->id }}">
                        {{ $group->name }}
                        @if ($group->code)
                            ({{ $group->code }})
                        @endif
                    </option>
                @endforeach
            </select>
        </td>

        <td>
            <input
                type="number"
                min="1"
                step="1"
                data-name="amount_ml"
                class="form-control"
                placeholder="เช่น 500"
            >
        </td>

        <td>
            <input
                type="number"
                min="0"
                step="0.01"
                data-name="price"
                class="form-control"
                placeholder="เช่น 20"
            >
        </td>

        <td>
            <input
                type="number"
                min="0"
                step="0.01"
                data-name="special_price"
                class="form-control"
                placeholder="ถ้าไม่มีเว้นว่าง"
            >
        </td>

        <td class="text-center">
            <div class="form-check form-switch d-inline-block">
                <input
                    type="checkbox"
                    data-name="is_active"
                    value="1"
                    class="form-check-input"
                    checked
                >
            </div>
        </td>

        <td class="text-center">
            <button
                type="button"
                class="btn btn-sm btn-icon btn-label-danger remove-group-price-row"
            >
                <i class="icon-base ti tabler-trash"></i>
            </button>
        </td>
    </tr>
</template>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tbody = document.getElementById('groupPriceRows');
    const addButton = document.getElementById('addGroupPriceRow');
    const template = document.getElementById('groupPriceRowTemplate');

    if (!tbody || !addButton || !template) {
        return;
    }

    function reindexRows() {
        tbody.querySelectorAll('.group-price-row').forEach(function (row, index) {
            const fields = row.querySelectorAll('input, select');

            fields.forEach(function (field) {
                const key = field.dataset.name;

                if (key) {
                    field.name = `group_prices[${index}][${key}]`;
                }
            });

            const activeCheckbox = row.querySelector(
                'input[type="checkbox"][data-name="is_active"]'
            );

            if (activeCheckbox) {
                let hidden = row.querySelector(
                    'input[type="hidden"].group-price-active-hidden'
                );

                if (!hidden) {
                    hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.className = 'group-price-active-hidden';
                    activeCheckbox.before(hidden);
                }

                hidden.name = `group_prices[${index}][is_active]`;
                hidden.value = '0';
            }
        });
    }

    addButton.addEventListener('click', function () {
        const clone = template.content.cloneNode(true);
        tbody.appendChild(clone);
        reindexRows();
    });

    tbody.addEventListener('click', function (event) {
        const button = event.target.closest('.remove-group-price-row');

        if (!button) {
            return;
        }

        const rows = tbody.querySelectorAll('.group-price-row');

        if (rows.length === 1) {
            const row = rows[0];

            row.querySelectorAll('input').forEach(function (input) {
                if (input.type === 'checkbox') {
                    input.checked = true;
                } else {
                    input.value = '';
                }
            });

            row.querySelectorAll('select').forEach(function (select) {
                select.value = '';
            });

            return;
        }

        button.closest('.group-price-row').remove();
        reindexRows();
    });

    reindexRows();
});
</script>
