<div class="table-responsive">
    <table class="table table-bordered translation-edit-table">
        <thead class="table-light">
            <tr>
                <th style="width:80px;">#</th>
                <th style="width:260px;">ตำแหน่งในหน้า</th>
                <th style="width:280px;">Translation Key</th>
                <th>ข้อความเริ่มต้น (ไทย)</th>
                <th>แปลเป็นปัจจุบัน</th>
                <th style="width:130px;" class="text-center">การจัดการ</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($rows as $row)
                @php
                    $translationKey = $row['translationKey'];

                    $fieldName =
                        'translations.'
                        . ($translationKey->id ?? 'missing_' . $row['key']);

                    $fieldId =
                        'translation-field-'
                        . md5($row['key']);
                @endphp

                <tr>
                    <td class="text-center">
                        <span class="translation-number-badge">
                            {{ $row['number'] }}
                        </span>
                    </td>

                    <td>
                        <div class="fw-semibold">
                            {{ $row['title'] }}
                        </div>

                        <div class="text-muted mt-1">
                            {{ $row['description'] }}
                        </div>
                    </td>

                    <td>
                        <div class="translation-key-pill">
                            {{ $row['key'] }}
                        </div>
                    </td>

                    <td>
                        <div class="fw-medium">
                            {{ $row['default_value'] ?: '-' }}
                        </div>
                    </td>

                    <td>
                        @if ($translationKey)
                            <textarea
                                id="{{ $fieldId }}"
                                name="translations[{{ $translationKey->id }}]"
                                rows="2"
                                class="form-control @error($fieldName) is-invalid @enderror"
                                placeholder="{{ $row['default_value'] }}"
                            >{{ $row['current_value'] }}</textarea>

                            @error($fieldName)
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        @else
                            <div class="text-danger">
                                ไม่พบ Translation Key:
                                <code>{{ $row['key'] }}</code>
                            </div>
                        @endif
                    </td>

                    <td class="text-center">
                        @if ($translationKey)
                            <button
                                type="button"
                                class="btn btn-outline-primary"
                                onclick="resetTranslationField(
                                    @js($fieldId),
                                    @js($row['default_value'])
                                )"
                            >
                                <i class="icon-base ti tabler-refresh me-1"></i>
                                รีเซ็ต
                            </button>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
