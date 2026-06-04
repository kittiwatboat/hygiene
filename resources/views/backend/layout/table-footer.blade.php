<div class="d-flex justify-content-between align-items-center mt-3">
    <div>
        <small class="text-muted">
            แสดง {{ $items->firstItem() }} ถึง {{ $items->lastItem() }} จาก {{ number_format($items->total(),0) }} รายการ
        </small>
    </div>
    <div>
        {!! $items->appends(request()->all())->links('back-end.layout.pagination') !!}
    </div>
</div>