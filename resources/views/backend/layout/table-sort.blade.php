<div class="row mb-2">
    <div class="col-md-auto">
        <div class="d-flex align-items-center gap-2">
            <label for="perPageSelect" class="form-label mb-0">แสดง:</label>
            <select id="perPageSelect" name="total" class="form-select form-select-sm w-auto"
                    onchange="document.getElementById('filterForm').submit()">
                @foreach ([1,15, 25, 50, 100, 200] as $number)
                    <option value="{{ $number }}" {{ request('total', 15) == $number ? 'selected' : '' }}>
                        {{ $number }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>