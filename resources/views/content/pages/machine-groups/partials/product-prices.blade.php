@php
    $savedRows = isset($machineGroup)
        ? $machineGroup->productPrices->sortBy('sort_order')->values()->map(fn ($row) => [
            'product_id' => $row->product_id,
            'amount_ml' => $row->amount_ml,
            'price' => $row->price,
            'special_price' => $row->special_price,
            'is_active' => $row->is_active ? 1 : 0,
        ])
        : collect();

    $oldRows = old('product_prices');
    $priceRows = is_array($oldRows) ? collect($oldRows) : $savedRows;

    if ($priceRows->isEmpty()) {
        $priceRows = collect([[
            'product_id' => '',
            'amount_ml' => '',
            'price' => '',
            'special_price' => '',
            'is_active' => 1,
        ]]);
    }
@endphp

<div class="col-12">
    <hr class="my-2">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h6 class="mb-1">สินค้าและราคาของกลุ่ม</h6>
            <p class="text-muted mb-0">
                เลือกว่าสินค้าใดขายในกลุ่มนี้ และกำหนดหลายปริมาตร/ราคาได้
            </p>
        </div>

        <button
            type="button"
            class="btn btn-label-primary btn-sm"
            id="addMachineGroupProductPriceRow"
        >
            <i class="icon-base ti tabler-plus me-1"></i>
            เพิ่มสินค้า / ราคา
        </button>
    </div>
</div>

<div class="col-12">
    @error('product_prices')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="table-responsive border rounded">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="min-width:260px;">สินค้า / น้ำยา</th>
                    <th style="min-width:140px;">ปริมาตร / ml</th>
                    <th style="min-width:150px;">ราคาปกติ / บาท</th>
                    <th style="min-width:150px;">ราคาพิเศษ</th>
                    <th style="width:110px;" class="text-center">เปิดใช้</th>
                    <th style="width:80px;"></th>
                </tr>
            </thead>
            <tbody id="machineGroupProductPriceRows">
                @foreach ($priceRows as $index => $row)
                    <tr class="machine-group-product-price-row">
                        <td>
                            <select
                                name="product_prices[{{ $index }}][product_id]"
                                class="form-select"
                            >
                                <option value="">-- เลือกสินค้า / น้ำยา --</option>
                                @foreach ($products as $product)
                                    <option
                                        value="{{ $product->id }}"
                                        {{ (string) ($row['product_id'] ?? '') === (string) $product->id ? 'selected' : '' }}
                                    >
                                        {{ $product->name }}
                                        @if (!empty($product->code))
                                            ({{ $product->code }})
                                        @endif
                                        @if (!empty($product->type))
                                            - {{ $product->type }}
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
                                name="product_prices[{{ $index }}][amount_ml]"
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
                                name="product_prices[{{ $index }}][price]"
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
                                name="product_prices[{{ $index }}][special_price]"
                                value="{{ $row['special_price'] ?? '' }}"
                                class="form-control"
                                placeholder="ถ้าไม่มีเว้นว่าง"
                            >
                        </td>

                        <td class="text-center">
                            <input
                                type="hidden"
                                name="product_prices[{{ $index }}][is_active]"
                                value="0"
                            >
                            <div class="form-check form-switch d-inline-block">
                                <input
                                    type="checkbox"
                                    name="product_prices[{{ $index }}][is_active]"
                                    value="1"
                                    class="form-check-input"
                                    {{ (int) ($row['is_active'] ?? 1) === 1 ? 'checked' : '' }}
                                >
                            </div>
                        </td>

                        <td class="text-center">
                            <button
                                type="button"
                                class="btn btn-sm btn-icon btn-label-danger remove-machine-group-product-price-row"
                            >
                                <i class="icon-base ti tabler-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<template id="machineGroupProductPriceRowTemplate">
    <tr class="machine-group-product-price-row">
        <td>
            <select data-name="product_id" class="form-select">
                <option value="">-- เลือกสินค้า / น้ำยา --</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">
                        {{ $product->name }}
                        @if (!empty($product->code))
                            ({{ $product->code }})
                        @endif
                        @if (!empty($product->type))
                            - {{ $product->type }}
                        @endif
                    </option>
                @endforeach
            </select>
        </td>

        <td>
            <input type="number" min="1" step="1" data-name="amount_ml" class="form-control" placeholder="เช่น 500">
        </td>

        <td>
            <input type="number" min="0" step="0.01" data-name="price" class="form-control" placeholder="เช่น 20">
        </td>

        <td>
            <input type="number" min="0" step="0.01" data-name="special_price" class="form-control" placeholder="ถ้าไม่มีเว้นว่าง">
        </td>

        <td class="text-center">
            <div class="form-check form-switch d-inline-block">
                <input type="checkbox" data-name="is_active" value="1" class="form-check-input" checked>
            </div>
        </td>

        <td class="text-center">
            <button type="button" class="btn btn-sm btn-icon btn-label-danger remove-machine-group-product-price-row">
                <i class="icon-base ti tabler-trash"></i>
            </button>
        </td>
    </tr>
</template>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tbody = document.getElementById('machineGroupProductPriceRows');
    const addButton = document.getElementById('addMachineGroupProductPriceRow');
    const template = document.getElementById('machineGroupProductPriceRowTemplate');

    if (!tbody || !addButton || !template) return;

    function reindexRows() {
        tbody.querySelectorAll('.machine-group-product-price-row').forEach(function (row, index) {
            row.querySelectorAll('[data-name]').forEach(function (field) {
                field.name = `product_prices[${index}][${field.dataset.name}]`;
            });

            const checkbox = row.querySelector(
                'input[type="checkbox"][data-name="is_active"]'
            );

            if (checkbox) {
                let hidden = row.querySelector(
                    '.machine-group-product-price-active-hidden'
                );

                if (!hidden) {
                    hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.className =
                        'machine-group-product-price-active-hidden';
                    checkbox.before(hidden);
                }

                hidden.name = `product_prices[${index}][is_active]`;
                hidden.value = '0';
            }
        });
    }

    addButton.addEventListener('click', function () {
        tbody.appendChild(template.content.cloneNode(true));
        reindexRows();
    });

    tbody.addEventListener('click', function (event) {
        const button = event.target.closest(
            '.remove-machine-group-product-price-row'
        );

        if (!button) return;

        const rows = tbody.querySelectorAll(
            '.machine-group-product-price-row'
        );

        if (rows.length === 1) {
            const row = rows[0];

            row.querySelectorAll('select').forEach(function (select) {
                select.value = '';
            });

            row.querySelectorAll('input').forEach(function (input) {
                if (input.type === 'checkbox') {
                    input.checked = true;
                } else if (input.type !== 'hidden') {
                    input.value = '';
                }
            });

            return;
        }

        button.closest('.machine-group-product-price-row').remove();
        reindexRows();
    });

    reindexRows();
});
</script>
