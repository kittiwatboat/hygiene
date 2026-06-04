<!doctype html>
<html lang="en">

<head>
    @include("$prefix.layout.header")
</head>

<body data-sidebar="dark">

    <!-- Begin page -->
    <div id="layout-wrapper">
        @include("$prefix.layout.header-menu")

        <!-- ========== Left Sidebar Start ========== -->
        @include("$prefix.layout.sidebar")
        <!-- Left Sidebar End -->

        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <!-- start breadcrumb -->
                    @include("$prefix.layout.breadcrumb")
                    <!-- end breadcrumb -->

                    {{-- START:CONTENT --}}


                    <div class="row">
                        <div class="col-12">
                            <div class="card welcome-box mb-4">
                                <div class="card-body p-4">
                                    <div class="row align-items-center">
                                        <div class="col-lg-8 col-md-8">
                                            <div class="welcome-content">
                                                <h3 class="text-white mb-2">Dashboard ระบบตู้กดน้ำยาปรับผ้านุ่ม</h3>
                                                <p class="mb-3 text-white-50">
                                                    ภาพรวมยอดขาย สถานะตู้ ปริมาณน้ำยาคงเหลือ และรายการแจ้งเตือนสำหรับการเติมสต็อก
                                                </p>

                                                <div class="d-flex flex-wrap gap-2">
                                                    <span class="badge bg-light text-primary px-3 py-2">
                                                        วันนี้: {{ date('d/m/Y') }}
                                                    </span>
                                                    <span class="badge bg-light text-success px-3 py-2">
                                                        ระบบพร้อมใช้งาน
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-4 text-end d-none d-md-block">
                                            <div class="welcome-content">
                                                <i class="bx bx-water" style="font-size: 110px; color: rgba(255,255,255,0.85);"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Summary Cards --}}
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card dashboard-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="dashboard-title">จำนวนตู้ทั้งหมด</p>
                                            <h3 class="dashboard-value">24</h3>
                                            <p class="dashboard-desc">ตู้ที่ลงทะเบียนในระบบ</p>
                                        </div>
                                        <div class="dashboard-icon bg-soft-blue">
                                            <i class="bx bx-cube-alt"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card dashboard-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="dashboard-title">ยอดขายวันนี้</p>
                                            <h3 class="dashboard-value">฿8,450</h3>
                                            <p class="dashboard-desc">จากการกดทั้งหมด 169 ครั้ง</p>
                                        </div>
                                        <div class="dashboard-icon bg-soft-green">
                                            <i class="bx bx-money"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card dashboard-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="dashboard-title">น้ำยาคงเหลือรวม</p>
                                            <h3 class="dashboard-value">386 L</h3>
                                            <p class="dashboard-desc">รวมทุกตู้ในระบบ</p>
                                        </div>
                                        <div class="dashboard-icon bg-soft-orange">
                                            <i class="bx bx-droplet"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card dashboard-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="dashboard-title">ต้องเติมน้ำยา</p>
                                            <h3 class="dashboard-value">5</h3>
                                            <p class="dashboard-desc">ตู้ที่น้ำยาต่ำกว่าเกณฑ์</p>
                                        </div>
                                        <div class="dashboard-icon bg-soft-red">
                                            <i class="bx bx-error-circle"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Main Detail --}}
                    <div class="row">
                        <div class="col-xl-8">
                            <div class="card dashboard-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <div>
                                            <h4 class="section-title">สถานะตู้กดน้ำยา</h4>
                                            <p class="section-subtitle">แสดงสถานะการออนไลน์และปริมาณน้ำยาคงเหลือของแต่ละตู้</p>
                                        </div>

                                        <a href="javascript:void(0);" class="btn btn-primary btn-sm">
                                            ดูทั้งหมด
                                        </a>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <div class="machine-card">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <div>
                                                        <p class="machine-name">ตู้ HY-001</p>
                                                        <p class="machine-location">สาขา เซ็นทรัลพระราม 9</p>
                                                    </div>
                                                    <span class="badge-soft-success">
                                                        <span class="machine-status-dot status-online"></span>
                                                        Online
                                                    </span>
                                                </div>

                                                <div class="liquid-label">
                                                    <span>น้ำยาคงเหลือ</span>
                                                    <span>82%</span>
                                                </div>

                                                <div class="progress mb-3">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 82%;"></div>
                                                </div>

                                                <div class="d-flex justify-content-between">
                                                    <small class="text-muted">คงเหลือ 41 L</small>
                                                    <small class="text-muted">กดวันนี้ 42 ครั้ง</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 mb-3">
                                            <div class="machine-card">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <div>
                                                        <p class="machine-name">ตู้ HY-002</p>
                                                        <p class="machine-location">สาขา บางนา</p>
                                                    </div>
                                                    <span class="badge-soft-warning">
                                                        <span class="machine-status-dot status-warning"></span>
                                                        Low Stock
                                                    </span>
                                                </div>

                                                <div class="liquid-label">
                                                    <span>น้ำยาคงเหลือ</span>
                                                    <span>18%</span>
                                                </div>

                                                <div class="progress mb-3">
                                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 18%;"></div>
                                                </div>

                                                <div class="d-flex justify-content-between">
                                                    <small class="text-muted">คงเหลือ 9 L</small>
                                                    <small class="text-muted">กดวันนี้ 31 ครั้ง</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 mb-3">
                                            <div class="machine-card">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <div>
                                                        <p class="machine-name">ตู้ HY-003</p>
                                                        <p class="machine-location">สาขา รังสิต</p>
                                                    </div>
                                                    <span class="badge-soft-success">
                                                        <span class="machine-status-dot status-online"></span>
                                                        Online
                                                    </span>
                                                </div>

                                                <div class="liquid-label">
                                                    <span>น้ำยาคงเหลือ</span>
                                                    <span>64%</span>
                                                </div>

                                                <div class="progress mb-3">
                                                    <div class="progress-bar bg-info" role="progressbar" style="width: 64%;"></div>
                                                </div>

                                                <div class="d-flex justify-content-between">
                                                    <small class="text-muted">คงเหลือ 32 L</small>
                                                    <small class="text-muted">กดวันนี้ 25 ครั้ง</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 mb-3">
                                            <div class="machine-card">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <div>
                                                        <p class="machine-name">ตู้ HY-004</p>
                                                        <p class="machine-location">สาขา พระราม 2</p>
                                                    </div>
                                                    <span class="badge-soft-danger">
                                                        <span class="machine-status-dot status-offline"></span>
                                                        Offline
                                                    </span>
                                                </div>

                                                <div class="liquid-label">
                                                    <span>น้ำยาคงเหลือ</span>
                                                    <span>0%</span>
                                                </div>

                                                <div class="progress mb-3">
                                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 4%;"></div>
                                                </div>

                                                <div class="d-flex justify-content-between">
                                                    <small class="text-muted">คงเหลือ 0 L</small>
                                                    <small class="text-muted">กดวันนี้ 0 ครั้ง</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="card dashboard-card">
                                <div class="card-body">
                                    <div class="mb-4">
                                        <h4 class="section-title">แจ้งเตือนล่าสุด</h4>
                                        <p class="section-subtitle">รายการที่ต้องตรวจสอบหรือดำเนินการ</p>
                                    </div>

                                    <div class="alert-item">
                                        <div class="alert-icon bg-soft-red">
                                            <i class="bx bx-error"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">ตู้ HY-004 ออฟไลน์</h6>
                                            <p class="mb-0 text-muted font-size-13">
                                                ไม่ได้รับสัญญาณจากตู้มากกว่า 30 นาที
                                            </p>
                                            <small class="text-muted">10 นาทีที่แล้ว</small>
                                        </div>
                                    </div>

                                    <div class="alert-item">
                                        <div class="alert-icon bg-soft-orange">
                                            <i class="bx bx-droplet"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">ตู้ HY-002 น้ำยาใกล้หมด</h6>
                                            <p class="mb-0 text-muted font-size-13">
                                                คงเหลือ 9 ลิตร ต่ำกว่าเกณฑ์ที่กำหนด
                                            </p>
                                            <small class="text-muted">25 นาทีที่แล้ว</small>
                                        </div>
                                    </div>

                                    <div class="alert-item">
                                        <div class="alert-icon bg-soft-blue">
                                            <i class="bx bx-package"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">มีรายการเติมน้ำยาใหม่</h6>
                                            <p class="mb-0 text-muted font-size-13">
                                                พนักงานเติมน้ำยา 50 ลิตร ที่ตู้ HY-001
                                            </p>
                                            <small class="text-muted">1 ชั่วโมงที่แล้ว</small>
                                        </div>
                                    </div>

                                    <div class="alert-item">
                                        <div class="alert-icon bg-soft-green">
                                            <i class="bx bx-check-circle"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">ตู้ HY-003 กลับมาออนไลน์</h6>
                                            <p class="mb-0 text-muted font-size-13">
                                                ระบบเชื่อมต่อกับตู้สำเร็จ
                                            </p>
                                            <small class="text-muted">2 ชั่วโมงที่แล้ว</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Transaction Table --}}
                    <div class="row">
                        <div class="col-xl-8">
                            <div class="card dashboard-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <div>
                                            <h4 class="section-title">รายการกดน้ำยาล่าสุด</h4>
                                            <p class="section-subtitle">ข้อมูลการใช้งานจากตู้กดน้ำยาแบบเรียลไทม์</p>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-dashboard mb-0">
                                            <thead>
                                                <tr>
                                                    <th>เวลา</th>
                                                    <th>รหัสตู้</th>
                                                    <th>สาขา</th>
                                                    <th>จำนวนกด</th>
                                                    <th>ปริมาณ</th>
                                                    <th>ยอดเงิน</th>
                                                    <th>สถานะ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>13:24</td>
                                                    <td>HY-001</td>
                                                    <td>เซ็นทรัลพระราม 9</td>
                                                    <td>1 ครั้ง</td>
                                                    <td>250 ml</td>
                                                    <td>฿50</td>
                                                    <td>
                                                        <span class="badge-soft-success">สำเร็จ</span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>13:18</td>
                                                    <td>HY-002</td>
                                                    <td>บางนา</td>
                                                    <td>2 ครั้ง</td>
                                                    <td>500 ml</td>
                                                    <td>฿100</td>
                                                    <td>
                                                        <span class="badge-soft-success">สำเร็จ</span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>13:05</td>
                                                    <td>HY-003</td>
                                                    <td>รังสิต</td>
                                                    <td>1 ครั้ง</td>
                                                    <td>250 ml</td>
                                                    <td>฿50</td>
                                                    <td>
                                                        <span class="badge-soft-success">สำเร็จ</span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>12:58</td>
                                                    <td>HY-002</td>
                                                    <td>บางนา</td>
                                                    <td>1 ครั้ง</td>
                                                    <td>250 ml</td>
                                                    <td>฿50</td>
                                                    <td>
                                                        <span class="badge-soft-warning">น้ำยาใกล้หมด</span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>12:43</td>
                                                    <td>HY-001</td>
                                                    <td>เซ็นทรัลพระราม 9</td>
                                                    <td>3 ครั้ง</td>
                                                    <td>750 ml</td>
                                                    <td>฿150</td>
                                                    <td>
                                                        <span class="badge-soft-success">สำเร็จ</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="card dashboard-card">
                                <div class="card-body">
                                    <div class="mb-4">
                                        <h4 class="section-title">สรุปน้ำยาคงเหลือตามสูตร</h4>
                                        <p class="section-subtitle">แยกตามประเภทน้ำยาปรับผ้านุ่ม</p>
                                    </div>

                                    <div class="mb-4">
                                        <div class="liquid-label">
                                            <span>กลิ่น Ocean Fresh</span>
                                            <span>142 L</span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 72%;"></div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <div class="liquid-label">
                                            <span>กลิ่น Floral Soft</span>
                                            <span>96 L</span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 58%;"></div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <div class="liquid-label">
                                            <span>กลิ่น Baby Mild</span>
                                            <span>48 L</span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 31%;"></div>
                                        </div>
                                    </div>

                                    <div class="mb-0">
                                        <div class="liquid-label">
                                            <span>กลิ่น Luxury Perfume</span>
                                            <span>100 L</span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 65%;"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- END:CONTENT --}}

                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            @include("$prefix.layout.footer")
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- JAVASCRIPT -->
    @include("$prefix.layout.script")

    <script>

    </script>

</body>

</html>
