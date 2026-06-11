{{-- @extends('layouts/layoutMaster')

@section('title', 'Dashboard ระบบตู้กดน้ำยา')

@section('page-style')
<style>
  :root {
    --hygiene-primary: #007FC4;
    --hygiene-primary-soft: rgba(0, 127, 196, .12);
    --hygiene-success-soft: rgba(40, 199, 111, .12);
    --hygiene-warning-soft: rgba(255, 159, 67, .12);
    --hygiene-danger-soft: rgba(234, 84, 85, .12);
    --hygiene-info-soft: rgba(0, 207, 232, .12);
  }

  .hygiene-page-header {
    border: 1px solid rgba(67, 89, 113, .12);
    border-radius: 18px;
    padding: 22px 24px;
    background:
      radial-gradient(circle at top right, rgba(0, 127, 196, .16), transparent 32%),
      linear-gradient(135deg, #ffffff 0%, #f5fbff 100%);
    box-shadow: 0 8px 24px rgba(67, 89, 113, .06);
  }

  .hygiene-card {
    border: 1px solid rgba(67, 89, 113, .12);
    border-radius: 18px;
    box-shadow: 0 8px 24px rgba(67, 89, 113, .06);
  }

  .hygiene-stat-card {
    position: relative;
    overflow: hidden;
  }

  .hygiene-stat-card::after {
    content: "";
    position: absolute;
    width: 120px;
    height: 120px;
    right: -48px;
    top: -48px;
    border-radius: 50%;
    background: rgba(0, 127, 196, .08);
  }

  .hygiene-icon-box {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .hygiene-icon-primary {
    color: var(--hygiene-primary);
    background: var(--hygiene-primary-soft);
  }

  .hygiene-icon-success {
    color: #28c76f;
    background: var(--hygiene-success-soft);
  }

  .hygiene-icon-warning {
    color: #ff9f43;
    background: var(--hygiene-warning-soft);
  }

  .hygiene-icon-danger {
    color: #ea5455;
    background: var(--hygiene-danger-soft);
  }

  .hygiene-icon-info {
    color: #00cfe8;
    background: var(--hygiene-info-soft);
  }

  .hygiene-badge-online {
    color: #28c76f;
    background: rgba(40, 199, 111, .14);
  }

  .hygiene-badge-offline {
    color: #ea5455;
    background: rgba(234, 84, 85, .14);
  }

  .hygiene-badge-low {
    color: #ff9f43;
    background: rgba(255, 159, 67, .14);
  }

  .hygiene-badge-error {
    color: #ea5455;
    background: rgba(234, 84, 85, .14);
  }

  .hygiene-badge-maintenance {
    color: #6c757d;
    background: rgba(108, 117, 125, .14);
  }

  .hygiene-location-card {
    border: 1px solid rgba(67, 89, 113, .10);
    border-radius: 16px;
    padding: 16px;
    height: 100%;
    background: #fff;
    transition: all .2s ease;
  }

  .hygiene-location-card:hover {
    border-color: rgba(0, 127, 196, .35);
    box-shadow: 0 8px 20px rgba(0, 127, 196, .08);
  }

  .hygiene-table th {
    font-size: 12px;
    white-space: nowrap;
  }

  .hygiene-table td {
    vertical-align: middle;
  }

  .hygiene-progress-sm {
    height: 7px;
    border-radius: 999px;
  }

  .hygiene-alert-list {
    max-height: 420px;
    overflow-y: auto;
  }

  .hygiene-alert-list::-webkit-scrollbar {
    width: 6px;
  }

  .hygiene-alert-list::-webkit-scrollbar-thumb {
    background: rgba(0, 127, 196, .28);
    border-radius: 999px;
  }

  .hygiene-summary-box {
    border: 1px solid rgba(67, 89, 113, .10);
    border-radius: 16px;
    padding: 16px;
    background: #fff;
  }

  .hygiene-status-dot {
    width: 10px;
    height: 10px;
    display: inline-block;
    border-radius: 50%;
  }

  .hygiene-dot-success {
    background: #28c76f;
  }

  .hygiene-dot-danger {
    background: #ea5455;
  }

  .hygiene-dot-warning {
    background: #ff9f43;
  }

  .hygiene-dot-secondary {
    background: #6c757d;
  }
</style>
@endsection

@section('content')
@php
  $summaryCards = [
    [
      'title' => 'ยอดขายวันนี้',
      'subtitle' => 'รวมทุกจุดติดตั้ง',
      'value' => '฿12,450',
      'detail' => '+18.2% จากเมื่อวาน',
      'detail_class' => 'text-success',
      'icon' => 'tabler-currency-baht',
      'icon_class' => 'hygiene-icon-primary'
    ],
    [
      'title' => 'จำนวนการกดวันนี้',
      'subtitle' => 'จำนวนครั้งที่ลูกค้าใช้งาน',
      'value' => '312 ครั้ง',
      'detail' => '+29 ครั้ง',
      'detail_class' => 'text-success',
      'icon' => 'tabler-hand-click',
      'icon_class' => 'hygiene-icon-info'
    ],
    [
      'title' => 'ตู้ทั้งหมด',
      'subtitle' => 'ทุกสาขา / ทุกจุด',
      'value' => '40 เครื่อง',
      'detail' => 'ติดตั้งแล้วทั้งหมด',
      'detail_class' => 'text-body-secondary',
      'icon' => 'tabler-wash-machine',
      'icon_class' => 'hygiene-icon-primary'
    ],
    [
      'title' => 'ตู้ออนไลน์',
      'subtitle' => 'เชื่อมต่อระบบอยู่',
      'value' => '28 เครื่อง',
      'detail' => '70% ของตู้ทั้งหมด',
      'detail_class' => 'text-success',
      'icon' => 'tabler-plug-connected',
      'icon_class' => 'hygiene-icon-success'
    ],
    [
      'title' => 'ตู้ออฟไลน์',
      'subtitle' => 'ขาดการเชื่อมต่อ',
      'value' => '4 เครื่อง',
      'detail' => 'ต้องตรวจสอบ',
      'detail_class' => 'text-danger',
      'icon' => 'tabler-plug-connected-x',
      'icon_class' => 'hygiene-icon-danger'
    ],
    [
      'title' => 'น้ำยาใกล้หมด',
      'subtitle' => 'ต่ำกว่าเกณฑ์ขั้นต่ำ',
      'value' => '6 เครื่อง',
      'detail' => 'ควรเติมวันนี้',
      'detail_class' => 'text-warning',
      'icon' => 'tabler-droplet-half-2',
      'icon_class' => 'hygiene-icon-warning'
    ],
    [
      'title' => 'แจ้งเตือนค้างอยู่',
      'subtitle' => 'Offline / Low Stock / Error',
      'value' => '12 รายการ',
      'detail' => '3 รายการด่วน',
      'detail_class' => 'text-danger',
      'icon' => 'tabler-bell-ringing',
      'icon_class' => 'hygiene-icon-danger'
    ],
    [
      'title' => 'รายได้เดือนนี้',
      'subtitle' => 'สะสมตั้งแต่ต้นเดือน',
      'value' => '฿186,720',
      'detail' => '+12.7% จากเดือนก่อน',
      'detail_class' => 'text-success',
      'icon' => 'tabler-report-money',
      'icon_class' => 'hygiene-icon-success'
    ],
  ];

  $statusOverview = [
    [
      'label' => 'ออนไลน์',
      'value' => 28,
      'total' => 40,
      'percent' => 70,
      'dot' => 'hygiene-dot-success',
      'progress' => 'bg-success'
    ],
    [
      'label' => 'ออฟไลน์',
      'value' => 4,
      'total' => 40,
      'percent' => 10,
      'dot' => 'hygiene-dot-danger',
      'progress' => 'bg-danger'
    ],
    [
      'label' => 'น้ำยาใกล้หมด',
      'value' => 6,
      'total' => 40,
      'percent' => 15,
      'dot' => 'hygiene-dot-warning',
      'progress' => 'bg-warning'
    ],
    [
      'label' => 'ซ่อมบำรุง',
      'value' => 2,
      'total' => 40,
      'percent' => 5,
      'dot' => 'hygiene-dot-secondary',
      'progress' => 'bg-secondary'
    ],
  ];

  $locationSummaries = [
    [
      'name' => 'หอพัก A',
      'address' => 'ถนนรัชดา กรุงเทพฯ',
      'machines' => 5,
      'online' => 5,
      'offline' => 0,
      'sales' => '฿1,250',
      'press_count' => 48,
      'low_stock' => 1,
      'status' => 'ปกติ',
      'status_class' => 'bg-label-success'
    ],
    [
      'name' => 'คอนโด B',
      'address' => 'ลาดพร้าว กรุงเทพฯ',
      'machines' => 8,
      'online' => 7,
      'offline' => 1,
      'sales' => '฿2,840',
      'press_count' => 83,
      'low_stock' => 2,
      'status' => 'ควรตรวจสอบ',
      'status_class' => 'bg-label-warning'
    ],
    [
      'name' => 'ร้านซักผ้า C',
      'address' => 'นนทบุรี',
      'machines' => 3,
      'online' => 2,
      'offline' => 1,
      'sales' => '฿890',
      'press_count' => 26,
      'low_stock' => 0,
      'status' => 'มีตู้ Offline',
      'status_class' => 'bg-label-danger'
    ],
    [
      'name' => 'อพาร์ตเมนต์ D',
      'address' => 'ปทุมธานี',
      'machines' => 6,
      'online' => 4,
      'offline' => 1,
      'sales' => '฿1,640',
      'press_count' => 52,
      'low_stock' => 2,
      'status' => 'ควรเติมน้ำยา',
      'status_class' => 'bg-label-warning'
    ],
  ];

  $machineRows = [
    [
      'code' => 'HY-001',
      'name' => 'ตู้หอพัก A ชั้น 1',
      'location' => 'หอพัก A',
      'status' => 'ออนไลน์',
      'status_class' => 'hygiene-badge-online',
      'detergent' => 72,
      'softener' => 28,
      'sales' => '฿420',
      'press_count' => 18,
      'last_update' => '1 นาทีที่แล้ว'
    ],
    [
      'code' => 'HY-002',
      'name' => 'ตู้หอพัก A ชั้น 2',
      'location' => 'หอพัก A',
      'status' => 'น้ำยาใกล้หมด',
      'status_class' => 'hygiene-badge-low',
      'detergent' => 18,
      'softener' => 14,
      'sales' => '฿350',
      'press_count' => 15,
      'last_update' => '3 นาทีที่แล้ว'
    ],
    [
      'code' => 'HY-007',
      'name' => 'ตู้คอนโด B โซนซักผ้า',
      'location' => 'คอนโด B',
      'status' => 'ออนไลน์',
      'status_class' => 'hygiene-badge-online',
      'detergent' => 64,
      'softener' => 53,
      'sales' => '฿890',
      'press_count' => 31,
      'last_update' => '30 วินาทีที่แล้ว'
    ],
    [
      'code' => 'HY-009',
      'name' => 'ตู้คอนโด B อาคาร 2',
      'location' => 'คอนโด B',
      'status' => 'ออฟไลน์',
      'status_class' => 'hygiene-badge-offline',
      'detergent' => 45,
      'softener' => 40,
      'sales' => '฿0',
      'press_count' => 0,
      'last_update' => '26 นาทีที่แล้ว'
    ],
    [
      'code' => 'HY-012',
      'name' => 'ตู้ร้านซักผ้า C',
      'location' => 'ร้านซักผ้า C',
      'status' => 'Error',
      'status_class' => 'hygiene-badge-error',
      'detergent' => 38,
      'softener' => 22,
      'sales' => '฿120',
      'press_count' => 5,
      'last_update' => '12 นาทีที่แล้ว'
    ],
    [
      'code' => 'HY-018',
      'name' => 'ตู้หอพัก E หน้าอาคาร',
      'location' => 'หอพัก E',
      'status' => 'ซ่อมบำรุง',
      'status_class' => 'hygiene-badge-maintenance',
      'detergent' => 80,
      'softener' => 76,
      'sales' => '฿0',
      'press_count' => 0,
      'last_update' => '1 ชั่วโมงที่แล้ว'
    ],
  ];

  $stockAlerts = [
    [
      'machine' => 'HY-002',
      'location' => 'หอพัก A',
      'liquid_type' => 'น้ำยาซักผ้า',
      'remain' => '3.5 ลิตร',
      'min' => '5 ลิตร',
      'level' => 'ใกล้หมด',
      'class' => 'hygiene-badge-low'
    ],
    [
      'machine' => 'HY-002',
      'location' => 'หอพัก A',
      'liquid_type' => 'น้ำยาปรับผ้านุ่ม',
      'remain' => '1.8 ลิตร',
      'min' => '5 ลิตร',
      'level' => 'ต้องเติมด่วน',
      'class' => 'hygiene-badge-error'
    ],
    [
      'machine' => 'HY-014',
      'location' => 'อพาร์ตเมนต์ D',
      'liquid_type' => 'น้ำยาซักผ้า',
      'remain' => '4.2 ลิตร',
      'min' => '5 ลิตร',
      'level' => 'ใกล้หมด',
      'class' => 'hygiene-badge-low'
    ],
    [
      'machine' => 'HY-021',
      'location' => 'คอนโด B',
      'liquid_type' => 'น้ำยาปรับผ้านุ่ม',
      'remain' => '0.6 ลิตร',
      'min' => '5 ลิตร',
      'level' => 'ต้องเติมด่วน',
      'class' => 'hygiene-badge-error'
    ],
  ];

  $transactions = [
    [
      'time' => '14:05',
      'machine' => 'HY-001',
      'location' => 'หอพัก A',
      'item' => 'น้ำยาซักผ้า',
      'volume' => '30 ml',
      'price' => '฿10',
      'status' => 'สำเร็จ',
      'class' => 'bg-label-success'
    ],
    [
      'time' => '14:02',
      'machine' => 'HY-007',
      'location' => 'คอนโด B',
      'item' => 'น้ำยาปรับผ้านุ่ม',
      'volume' => '20 ml',
      'price' => '฿5',
      'status' => 'สำเร็จ',
      'class' => 'bg-label-success'
    ],
    [
      'time' => '13:58',
      'machine' => 'HY-012',
      'location' => 'ร้านซักผ้า C',
      'item' => 'น้ำยาซักผ้า',
      'volume' => '30 ml',
      'price' => '฿10',
      'status' => 'ล้มเหลว',
      'class' => 'bg-label-danger'
    ],
    [
      'time' => '13:52',
      'machine' => 'HY-014',
      'location' => 'อพาร์ตเมนต์ D',
      'item' => 'น้ำยาซักผ้า + ปรับผ้านุ่ม',
      'volume' => '50 ml',
      'price' => '฿15',
      'status' => 'สำเร็จ',
      'class' => 'bg-label-success'
    ],
    [
      'time' => '13:46',
      'machine' => 'HY-021',
      'location' => 'คอนโด B',
      'item' => 'น้ำยาปรับผ้านุ่ม',
      'volume' => '20 ml',
      'price' => '฿5',
      'status' => 'สำเร็จ',
      'class' => 'bg-label-success'
    ],
  ];

  $topMachines = [
    [
      'rank' => 1,
      'machine' => 'HY-007',
      'location' => 'คอนโด B',
      'sales' => '฿8,450',
      'press_count' => 390
    ],
    [
      'rank' => 2,
      'machine' => 'HY-002',
      'location' => 'หอพัก A',
      'sales' => '฿6,230',
      'press_count' => 284
    ],
    [
      'rank' => 3,
      'machine' => 'HY-014',
      'location' => 'อพาร์ตเมนต์ D',
      'sales' => '฿4,280',
      'press_count' => 198
    ],
    [
      'rank' => 4,
      'machine' => 'HY-012',
      'location' => 'ร้านซักผ้า C',
      'sales' => '฿3,180',
      'press_count' => 144
    ],
  ];

  $activities = [
    [
      'type' => 'danger',
      'title' => 'HY-009 ขาดการเชื่อมต่อ',
      'description' => 'ตู้คอนโด B อาคาร 2 ไม่ส่ง heartbeat เกิน 20 นาที',
      'time' => '2 นาทีที่แล้ว'
    ],
    [
      'type' => 'warning',
      'title' => 'HY-002 น้ำยาปรับผ้านุ่มใกล้หมด',
      'description' => 'คงเหลือ 1.8 ลิตร ต่ำกว่าเกณฑ์ขั้นต่ำ 5 ลิตร',
      'time' => '8 นาทีที่แล้ว'
    ],
    [
      'type' => 'success',
      'title' => 'เติมน้ำยา HY-006 สำเร็จ',
      'description' => 'พนักงานเติมน้ำยาซักผ้า 20 ลิตร และน้ำยาปรับผ้านุ่ม 15 ลิตร',
      'time' => '35 นาทีที่แล้ว'
    ],
    [
      'type' => 'primary',
      'title' => 'รายการขายใหม่',
      'description' => 'HY-001 รับรายการน้ำยาซักผ้า ฿10 สำเร็จ',
      'time' => '42 นาทีที่แล้ว'
    ],
  ];
@endphp

<div class="row g-6">
  <div class="col-12">
    <div class="hygiene-page-header">
      <div class="d-flex flex-column flex-lg-row justify-content-between gap-4">
        <div>
          <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge rounded-pill" style="background:#007FC4;color:#fff;">Hygiene Dashboard</span>
            <span class="badge bg-label-success rounded-pill">Real-time Monitoring</span>
          </div>
          <h4 class="mb-1 fw-bold">ภาพรวมระบบตู้กดน้ำยาซักผ้า</h4>
          <p class="text-body-secondary mb-0">
            ติดตามยอดขาย สถานะตู้ ปริมาณน้ำยา แจ้งเตือนเติม Stock และธุรกรรมล่าสุดจากทุกจุดติดตั้ง
          </p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
          <button type="button" class="btn btn-outline-secondary">วันนี้</button>
          <button type="button" class="btn btn-outline-secondary">7 วัน</button>
          <button type="button" class="btn btn-outline-secondary">เดือนนี้</button>
          <button type="button" class="btn btn-primary" style="background:#007FC4;border-color:#007FC4;">
            <i class="icon-base ti tabler-refresh me-1"></i>
            รีเฟรชข้อมูล
          </button>
        </div>
      </div>
    </div>
  </div>

  @foreach ($summaryCards as $card)
    <div class="col-xxl-3 col-lg-4 col-md-6 col-12">
      <div class="card hygiene-card hygiene-stat-card h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-4">
            <div class="hygiene-icon-box {{ $card['icon_class'] }}">
              <i class="icon-base ti {{ $card['icon'] }} icon-26px"></i>
            </div>
          </div>
          <h6 class="mb-1">{{ $card['title'] }}</h6>
          <small class="text-body-secondary d-block mb-3">{{ $card['subtitle'] }}</small>
          <div class="d-flex justify-content-between align-items-end gap-2">
            <h4 class="mb-0">{{ $card['value'] }}</h4>
            <small class="{{ $card['detail_class'] }} fw-medium">{{ $card['detail'] }}</small>
          </div>
        </div>
      </div>
    </div>
  @endforeach

  <div class="col-xl-4 col-12">
    <div class="card hygiene-card h-100">
      <div class="card-header">
        <h5 class="card-title mb-1">สถานะตู้โดยรวม</h5>
        <p class="card-subtitle mb-0">สรุปจำนวนตู้ตามสถานะ</p>
      </div>
      <div class="card-body">
        @foreach ($statusOverview as $status)
          <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div class="d-flex align-items-center gap-2">
                <span class="hygiene-status-dot {{ $status['dot'] }}"></span>
                <span class="fw-medium">{{ $status['label'] }}</span>
              </div>
              <span class="fw-medium">{{ $status['value'] }} / {{ $status['total'] }} เครื่อง</span>
            </div>
            <div class="progress hygiene-progress-sm">
              <div
                class="progress-bar {{ $status['progress'] }}"
                role="progressbar"
                style="width: {{ $status['percent'] }}%;"
                aria-valuenow="{{ $status['percent'] }}"
                aria-valuemin="0"
                aria-valuemax="100">
              </div>
            </div>
            <small class="text-body-secondary">{{ $status['percent'] }}% ของตู้ทั้งหมด</small>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <div class="col-xl-8 col-12">
    <div class="card hygiene-card h-100">
      <div class="card-header d-flex flex-column flex-md-row justify-content-between gap-3">
        <div>
          <h5 class="card-title mb-1">สถานะตามจุดติดตั้ง</h5>
          <p class="card-subtitle mb-0">ดูยอดขายและสถานะตู้แยกตามสถานที่</p>
        </div>
        <a href="javascript:void(0);" class="btn btn-sm btn-outline-primary align-self-md-start">
          ดูทุกจุดติดตั้ง
        </a>
      </div>

      <div class="card-body">
        <div class="row g-4">
          @foreach ($locationSummaries as $location)
            <div class="col-md-6">
              <div class="hygiene-location-card">
                <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                  <div>
                    <h6 class="mb-1">{{ $location['name'] }}</h6>
                    <small class="text-body-secondary">{{ $location['address'] }}</small>
                  </div>
                  <span class="badge {{ $location['status_class'] }}">{{ $location['status'] }}</span>
                </div>

                <div class="row g-3">
                  <div class="col-6">
                    <small class="text-body-secondary d-block">จำนวนตู้</small>
                    <span class="fw-medium">{{ $location['machines'] }} เครื่อง</span>
                  </div>
                  <div class="col-6">
                    <small class="text-body-secondary d-block">ยอดขายวันนี้</small>
                    <span class="fw-medium">{{ $location['sales'] }}</span>
                  </div>
                  <div class="col-6">
                    <small class="text-body-secondary d-block">ออนไลน์</small>
                    <span class="text-success fw-medium">{{ $location['online'] }} เครื่อง</span>
                  </div>
                  <div class="col-6">
                    <small class="text-body-secondary d-block">ออฟไลน์</small>
                    <span class="text-danger fw-medium">{{ $location['offline'] }} เครื่อง</span>
                  </div>
                  <div class="col-6">
                    <small class="text-body-secondary d-block">จำนวนการกด</small>
                    <span class="fw-medium">{{ $location['press_count'] }} ครั้ง</span>
                  </div>
                  <div class="col-6">
                    <small class="text-body-secondary d-block">น้ำยาใกล้หมด</small>
                    <span class="text-warning fw-medium">{{ $location['low_stock'] }} เครื่อง</span>
                  </div>
                </div>

                <div class="mt-3 pt-3 border-top text-end">
                  <a href="javascript:void(0);" class="small fw-medium">ดูรายละเอียด</a>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-5 col-12">
    <div class="card hygiene-card h-100">
      <div class="card-header d-flex justify-content-between gap-3">
        <div>
          <h5 class="card-title mb-1">แจ้งเตือนเติมน้ำยา</h5>
          <p class="card-subtitle mb-0">รายการที่ต่ำกว่าเกณฑ์ขั้นต่ำ</p>
        </div>
        <span class="badge bg-label-warning align-self-start">{{ count($stockAlerts) }} รายการ</span>
      </div>

      <div class="card-body hygiene-alert-list">
        @foreach ($stockAlerts as $alert)
          <div class="d-flex gap-3 mb-4 pb-4 border-bottom">
            <div class="hygiene-icon-box hygiene-icon-warning flex-shrink-0">
              <i class="icon-base ti tabler-droplet-exclamation icon-24px"></i>
            </div>

            <div class="w-100">
              <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                <div>
                  <h6 class="mb-1">{{ $alert['machine'] }} - {{ $alert['liquid_type'] }}</h6>
                  <small class="text-body-secondary">{{ $alert['location'] }}</small>
                </div>
                <span class="badge rounded-pill {{ $alert['class'] }}">{{ $alert['level'] }}</span>
              </div>

              <div class="d-flex justify-content-between mt-3">
                <small>คงเหลือ: <span class="fw-medium">{{ $alert['remain'] }}</span></small>
                <small>ขั้นต่ำ: <span class="fw-medium">{{ $alert['min'] }}</span></small>
              </div>
            </div>
          </div>
        @endforeach

        <button type="button" class="btn btn-outline-primary w-100">
          ดูแจ้งเตือนทั้งหมด
        </button>
      </div>
    </div>
  </div>

  <div class="col-xl-7 col-12">
    <div class="card hygiene-card h-100">
      <div class="card-header d-flex flex-column flex-md-row justify-content-between gap-3">
        <div>
          <h5 class="card-title mb-1">รายการใช้งานล่าสุด</h5>
          <p class="card-subtitle mb-0">ประวัติการกดน้ำยาและสถานะรายการล่าสุด</p>
        </div>
        <a href="javascript:void(0);" class="btn btn-sm btn-outline-primary align-self-md-start">
          ดูรายการขายทั้งหมด
        </a>
      </div>

      <div class="table-responsive">
        <table class="table table-hover hygiene-table mb-0">
          <thead class="table-light">
            <tr>
              <th>เวลา</th>
              <th>รหัสตู้</th>
              <th>จุดติดตั้ง</th>
              <th>รายการ</th>
              <th>ปริมาณ</th>
              <th>ราคา</th>
              <th>สถานะ</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($transactions as $transaction)
              <tr>
                <td>{{ $transaction['time'] }}</td>
                <td>
                  <span class="fw-medium">{{ $transaction['machine'] }}</span>
                </td>
                <td>{{ $transaction['location'] }}</td>
                <td>{{ $transaction['item'] }}</td>
                <td>{{ $transaction['volume'] }}</td>
                <td>
                  <span class="fw-medium">{{ $transaction['price'] }}</span>
                </td>
                <td>
                  <span class="badge {{ $transaction['class'] }}">{{ $transaction['status'] }}</span>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card hygiene-card">
      <div class="card-header d-flex flex-column flex-md-row justify-content-between gap-3">
        <div>
          <h5 class="card-title mb-1">รายการตู้ทั้งหมด</h5>
          <p class="card-subtitle mb-0">ติดตามสถานะตู้ น้ำยาคงเหลือ ยอดขาย และเวลาอัปเดตล่าสุด</p>
        </div>

        <div class="d-flex flex-wrap gap-2">
          <button type="button" class="btn btn-sm btn-outline-secondary">
            <i class="icon-base ti tabler-filter me-1"></i>
            ตัวกรอง
          </button>
          <button type="button" class="btn btn-sm btn-primary" style="background:#007FC4;border-color:#007FC4;">
            <i class="icon-base ti tabler-plus me-1"></i>
            เพิ่มตู้
          </button>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover hygiene-table mb-0">
          <thead class="table-light">
            <tr>
              <th>รหัสตู้</th>
              <th>ชื่อตู้</th>
              <th>จุดติดตั้ง</th>
              <th>สถานะ</th>
              <th>น้ำยาซักผ้า</th>
              <th>น้ำยาปรับผ้านุ่ม</th>
              <th>ยอดขายวันนี้</th>
              <th>จำนวนการกด</th>
              <th>อัปเดตล่าสุด</th>
              <th class="text-center">จัดการ</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($machineRows as $machine)
              <tr>
                <td>
                  <span class="fw-medium">{{ $machine['code'] }}</span>
                </td>
                <td>
                  <div class="d-flex flex-column">
                    <span class="fw-medium text-heading">{{ $machine['name'] }}</span>
                    <small class="text-body-secondary">เครื่องกดน้ำยาอัตโนมัติ</small>
                  </div>
                </td>
                <td>{{ $machine['location'] }}</td>
                <td>
                  <span class="badge rounded-pill {{ $machine['status_class'] }}">
                    {{ $machine['status'] }}
                  </span>
                </td>
                <td style="min-width:150px;">
                  <div class="d-flex justify-content-between mb-1">
                    <small>{{ $machine['detergent'] }}%</small>
                    <small class="{{ $machine['detergent'] <= 20 ? 'text-danger' : 'text-body-secondary' }}">
                      {{ $machine['detergent'] <= 20 ? 'ต่ำ' : 'ปกติ' }}
                    </small>
                  </div>
                  <div class="progress hygiene-progress-sm">
                    <div
                      class="progress-bar {{ $machine['detergent'] <= 20 ? 'bg-danger' : 'bg-primary' }}"
                      role="progressbar"
                      style="width: {{ $machine['detergent'] }}%;"
                      aria-valuenow="{{ $machine['detergent'] }}"
                      aria-valuemin="0"
                      aria-valuemax="100">
                    </div>
                  </div>
                </td>
                <td style="min-width:150px;">
                  <div class="d-flex justify-content-between mb-1">
                    <small>{{ $machine['softener'] }}%</small>
                    <small class="{{ $machine['softener'] <= 20 ? 'text-danger' : 'text-body-secondary' }}">
                      {{ $machine['softener'] <= 20 ? 'ต่ำ' : 'ปกติ' }}
                    </small>
                  </div>
                  <div class="progress hygiene-progress-sm">
                    <div
                      class="progress-bar {{ $machine['softener'] <= 20 ? 'bg-danger' : 'bg-info' }}"
                      role="progressbar"
                      style="width: {{ $machine['softener'] }}%;"
                      aria-valuenow="{{ $machine['softener'] }}"
                      aria-valuemin="0"
                      aria-valuemax="100">
                    </div>
                  </div>
                </td>
                <td>
                  <span class="fw-medium">{{ $machine['sales'] }}</span>
                </td>
                <td>{{ $machine['press_count'] }} ครั้ง</td>
                <td>
                  <small class="text-body-secondary">{{ $machine['last_update'] }}</small>
                </td>
                <td class="text-center">
                  <div class="dropdown">
                    <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill" type="button" data-bs-toggle="dropdown">
                      <i class="icon-base ti tabler-dots-vertical"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                      <a class="dropdown-item" href="javascript:void(0);">
                        <i class="icon-base ti tabler-eye me-2"></i>
                        ดูรายละเอียด
                      </a>
                      <a class="dropdown-item" href="javascript:void(0);">
                        <i class="icon-base ti tabler-droplet-plus me-2"></i>
                        บันทึกเติมน้ำยา
                      </a>
                      <a class="dropdown-item" href="javascript:void(0);">
                        <i class="icon-base ti tabler-settings me-2"></i>
                        ตั้งค่าเครื่อง
                      </a>
                    </div>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-xl-6 col-12">
    <div class="card hygiene-card h-100">
      <div class="card-header">
        <h5 class="card-title mb-1">อันดับตู้ขายดีวันนี้</h5>
        <p class="card-subtitle mb-0">เรียงจากยอดขายสูงสุด</p>
      </div>

      <div class="table-responsive">
        <table class="table table-hover hygiene-table mb-0">
          <thead class="table-light">
            <tr>
              <th>อันดับ</th>
              <th>รหัสตู้</th>
              <th>จุดติดตั้ง</th>
              <th>ยอดขาย</th>
              <th>จำนวนการกด</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($topMachines as $machine)
              <tr>
                <td>
                  <span class="badge bg-label-primary">#{{ $machine['rank'] }}</span>
                </td>
                <td>
                  <span class="fw-medium">{{ $machine['machine'] }}</span>
                </td>
                <td>{{ $machine['location'] }}</td>
                <td>
                  <span class="fw-medium">{{ $machine['sales'] }}</span>
                </td>
                <td>{{ $machine['press_count'] }} ครั้ง</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-xl-6 col-12">
    <div class="card hygiene-card h-100">
      <div class="card-header">
        <h5 class="card-title mb-1">Activity Timeline</h5>
        <p class="card-subtitle mb-0">เหตุการณ์ล่าสุดของระบบ</p>
      </div>

      <div class="card-body">
        <ul class="timeline mb-0">
          @foreach ($activities as $activity)
            <li class="timeline-item timeline-item-transparent">
              <span class="timeline-point timeline-point-{{ $activity['type'] }}"></span>
              <div class="timeline-event">
                <div class="timeline-header mb-2">
                  <h6 class="mb-0">{{ $activity['title'] }}</h6>
                  <small class="text-body-secondary">{{ $activity['time'] }}</small>
                </div>
                <p class="mb-0 text-body-secondary">{{ $activity['description'] }}</p>
              </div>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection --}}

@extends('layouts/layoutMaster')

@section('title', 'Dashboard')

@section('page-style')
<style>
  .welcome-card {
    border-radius: 18px;
    border: 1px solid rgba(67, 89, 113, .12);
    background:
      radial-gradient(circle at top right, rgba(0, 127, 196, .16), transparent 34%),
      linear-gradient(135deg, #ffffff 0%, #f5fbff 100%);
    box-shadow: 0 8px 24px rgba(67, 89, 113, .06);
  }

  .welcome-icon {
    width: 68px;
    height: 68px;
    border-radius: 22px;
    background: rgba(0, 127, 196, .12);
    color: #007FC4;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .welcome-icon i {
    font-size: 38px;
  }

  .welcome-title {
    color: #007FC4;
    font-weight: 700;
  }

  .welcome-mini-card {
    border: 1px solid rgba(67, 89, 113, .12);
    border-radius: 16px;
    height: 100%;
    transition: all .2s ease;
  }

  .welcome-mini-card:hover {
    border-color: rgba(0, 127, 196, .35);
    box-shadow: 0 8px 20px rgba(0, 127, 196, .08);
  }

  .welcome-mini-icon {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    background: rgba(0, 127, 196, .12);
    color: #007FC4;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }
</style>
@endsection

@section('content')
<div class="row g-4">

  <div class="col-12">
    <div class="card welcome-card">
      <div class="card-body p-5">
        <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-4">
          <div class="d-flex align-items-start gap-4">
            <div class="welcome-icon flex-shrink-0">
              <i class="icon-base ti tabler-droplet"></i>
            </div>

            <div>
              <span class="badge rounded-pill mb-3" style="background:#007FC4;color:#fff;">
                Hygiene Dashboard
              </span>

              <h3 class="welcome-title mb-2">
                ยินดีต้อนรับเข้าสู่ระบบจัดการตู้กดน้ำยา
              </h3>

              <p class="text-muted mb-0">
                ระบบหลังบ้านสำหรับจัดการตู้ จุดติดตั้ง สินค้า/น้ำยา Stock การเติมน้ำยา ซ่อมบำรุง และรายการขาย
              </p>
            </div>
          </div>

          <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('machines.index') }}" class="btn btn-primary" style="background:#007FC4;border-color:#007FC4;">
              <i class="icon-base ti tabler-wash-machine me-1"></i>
              จัดการตู้
            </a>

            <a href="{{ route('stock.index') }}" class="btn btn-label-primary">
              <i class="icon-base ti tabler-bottle me-1"></i>
              ดู Stock น้ำยา
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6 col-xl-3">
    <a href="{{ route('machines.index') }}" class="text-decoration-none text-body">
      <div class="card welcome-mini-card">
        <div class="card-body">
          <div class="welcome-mini-icon mb-3">
            <i class="icon-base ti tabler-wash-machine"></i>
          </div>
          <h6 class="mb-1">จัดการตู้</h6>
          <p class="text-muted mb-0 small">
            เพิ่ม แก้ไข และตั้งค่าช่องน้ำยาของแต่ละตู้
          </p>
        </div>
      </div>
    </a>
  </div>

  <div class="col-md-6 col-xl-3">
    <a href="{{ route('products.index') }}" class="text-decoration-none text-body">
      <div class="card welcome-mini-card">
        <div class="card-body">
          <div class="welcome-mini-icon mb-3">
            <i class="icon-base ti tabler-droplet"></i>
          </div>
          <h6 class="mb-1">สินค้า / น้ำยา</h6>
          <p class="text-muted mb-0 small">
            จัดการรายการน้ำยาซักผ้า น้ำยาปรับผ้านุ่ม และน้ำยาอื่น ๆ
          </p>
        </div>
      </div>
    </a>
  </div>

  <div class="col-md-6 col-xl-3">
    <a href="{{ route('refills.index') }}" class="text-decoration-none text-body">
      <div class="card welcome-mini-card">
        <div class="card-body">
          <div class="welcome-mini-icon mb-3">
            <i class="icon-base ti tabler-droplet-plus"></i>
          </div>
          <h6 class="mb-1">บันทึกเติมน้ำยา</h6>
          <p class="text-muted mb-0 small">
            บันทึกการเติมน้ำยาและอัปเดต Stock อัตโนมัติ
          </p>
        </div>
      </div>
    </a>
  </div>

  <div class="col-md-6 col-xl-3">
    <a href="{{ route('sales.index') }}" class="text-decoration-none text-body">
      <div class="card welcome-mini-card">
        <div class="card-body">
          <div class="welcome-mini-icon mb-3">
            <i class="icon-base ti tabler-receipt"></i>
          </div>
          <h6 class="mb-1">รายการขาย</h6>
          <p class="text-muted mb-0 small">
            ดูรายการขายและประวัติการกดน้ำยาจากตู้
          </p>
        </div>
      </div>
    </a>
  </div>

</div>
@endsection
