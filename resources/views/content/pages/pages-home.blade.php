@extends('layouts/layoutMaster')

@section('title', 'Dashboard ระบบตู้กดน้ำยา')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/apex-charts/apex-charts.scss'
])
@endsection

@section('vendor-script')
@vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

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

  .hygiene-title {
    color: #1f2937;
    font-weight: 700;
  }

  .hygiene-subtitle {
    color: #6b7280;
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

  .hygiene-location-item {
    border: 1px solid rgba(67, 89, 113, .10);
    border-radius: 14px;
    padding: 14px;
    transition: all .2s ease;
  }

  .hygiene-location-item:hover {
    border-color: rgba(0, 127, 196, .35);
    box-shadow: 0 8px 20px rgba(0, 127, 196, .08);
  }

  .hygiene-machine-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    display: inline-block;
  }

  .hygiene-dot-online {
    background: #28c76f;
  }

  .hygiene-dot-offline {
    background: #ea5455;
  }

  .hygiene-dot-warning {
    background: #ff9f43;
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

  .hygiene-filter-btn.active {
    background: var(--hygiene-primary);
    color: #ffffff;
    border-color: var(--hygiene-primary);
  }

  .hygiene-map-placeholder {
    min-height: 280px;
    border-radius: 16px;
    background:
      linear-gradient(135deg, rgba(0, 127, 196, .12), rgba(0, 207, 232, .08)),
      repeating-linear-gradient(
        45deg,
        rgba(0, 127, 196, .04),
        rgba(0, 127, 196, .04) 10px,
        rgba(255, 255, 255, .8) 10px,
        rgba(255, 255, 255, .8) 20px
      );
    border: 1px dashed rgba(0, 127, 196, .35);
  }

  .hygiene-alert-list {
    max-height: 352px;
    overflow-y: auto;
  }

  .hygiene-alert-list::-webkit-scrollbar {
    width: 6px;
  }

  .hygiene-alert-list::-webkit-scrollbar-thumb {
    background: rgba(0, 127, 196, .28);
    border-radius: 999px;
  }

  .hygiene-timeline {
    position: relative;
    padding-left: 0;
  }

  .hygiene-timeline .timeline-item:not(:last-child) {
    padding-bottom: 22px;
  }
</style>
@endsection

@section('page-script')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const hygienePrimary = '#007FC4';
    const hygieneSuccess = '#28C76F';
    const hygieneWarning = '#FF9F43';
    const hygieneDanger = '#EA5455';
    const hygieneInfo = '#00CFE8';
    const hygieneMuted = '#A8AAAE';

    const cardColor = window.Helpers ? window.Helpers.getCssVar('card-bg') : '#ffffff';
    const headingColor = window.Helpers ? window.Helpers.getCssVar('heading-color') : '#566a7f';
    const labelColor = window.Helpers ? window.Helpers.getCssVar('body-color') : '#6f6b7d';
    const borderColor = window.Helpers ? window.Helpers.getCssVar('border-color') : '#dbdade';

    const chartFontFamily = 'Public Sans, sans-serif';

    if (document.querySelector('#salesOverviewChart')) {
      const salesOverviewChart = new ApexCharts(document.querySelector('#salesOverviewChart'), {
        chart: {
          height: 330,
          type: 'area',
          parentHeightOffset: 0,
          toolbar: { show: false },
          fontFamily: chartFontFamily
        },
        series: [
          {
            name: 'ยอดขาย',
            data: [1450, 1880, 2140, 1760, 2590, 3120, 2840]
          },
          {
            name: 'จำนวนการกด',
            data: [92, 118, 132, 101, 158, 196, 181]
          }
        ],
        colors: [hygienePrimary, hygieneInfo],
        dataLabels: { enabled: false },
        stroke: {
          width: 3,
          curve: 'smooth'
        },
        fill: {
          type: 'gradient',
          gradient: {
            shadeIntensity: .6,
            opacityFrom: .32,
            opacityTo: .06,
            stops: [0, 90, 100]
          }
        },
        grid: {
          borderColor: borderColor,
          strokeDashArray: 5,
          padding: {
            top: 0,
            right: 8,
            bottom: 0,
            left: 8
          }
        },
        legend: {
          show: true,
          position: 'top',
          horizontalAlign: 'right',
          labels: {
            colors: labelColor
          }
        },
        xaxis: {
          categories: ['จันทร์', 'อังคาร', 'พุธ', 'พฤหัส', 'ศุกร์', 'เสาร์', 'อาทิตย์'],
          labels: {
            style: {
              colors: labelColor
            }
          },
          axisBorder: { show: false },
          axisTicks: { show: false }
        },
        yaxis: {
          labels: {
            style: {
              colors: labelColor
            },
            formatter: function (value) {
              return value >= 1000 ? (value / 1000).toFixed(1) + 'k' : value;
            }
          }
        },
        tooltip: {
          y: {
            formatter: function (value, options) {
              if (options.seriesIndex === 0) {
                return '฿' + value.toLocaleString();
              }

              return value.toLocaleString() + ' ครั้ง';
            }
          }
        }
      });

      salesOverviewChart.render();
    }

    if (document.querySelector('#machineStatusChart')) {
      const machineStatusChart = new ApexCharts(document.querySelector('#machineStatusChart'), {
        chart: {
          height: 260,
          type: 'donut',
          fontFamily: chartFontFamily
        },
        labels: ['ออนไลน์', 'ออฟไลน์', 'น้ำยาใกล้หมด', 'ซ่อมบำรุง'],
        series: [28, 4, 6, 2],
        colors: [hygieneSuccess, hygieneDanger, hygieneWarning, hygieneMuted],
        stroke: {
          width: 0
        },
        dataLabels: {
          enabled: true,
          formatter: function (val) {
            return Math.round(val) + '%';
          }
        },
        legend: {
          position: 'bottom',
          labels: {
            colors: labelColor
          }
        },
        plotOptions: {
          pie: {
            donut: {
              size: '68%',
              labels: {
                show: true,
                name: {
                  color: labelColor
                },
                value: {
                  color: headingColor,
                  fontSize: '22px',
                  fontWeight: 700
                },
                total: {
                  show: true,
                  label: 'ตู้ทั้งหมด',
                  color: labelColor,
                  formatter: function () {
                    return '40';
                  }
                }
              }
            }
          }
        }
      });

      machineStatusChart.render();
    }

    if (document.querySelector('#liquidUsageChart')) {
      const liquidUsageChart = new ApexCharts(document.querySelector('#liquidUsageChart'), {
        chart: {
          height: 280,
          type: 'bar',
          parentHeightOffset: 0,
          toolbar: { show: false },
          fontFamily: chartFontFamily
        },
        series: [
          {
            name: 'น้ำยาซักผ้า',
            data: [18.5, 22.2, 19.8, 24.4, 28.1, 31.5, 29.2]
          },
          {
            name: 'น้ำยาปรับผ้านุ่ม',
            data: [9.2, 10.8, 9.7, 12.1, 13.8, 15.4, 14.6]
          }
        ],
        colors: [hygienePrimary, hygieneInfo],
        plotOptions: {
          bar: {
            borderRadius: 8,
            columnWidth: '42%'
          }
        },
        dataLabels: { enabled: false },
        grid: {
          borderColor: borderColor,
          strokeDashArray: 5
        },
        legend: {
          position: 'top',
          horizontalAlign: 'right',
          labels: {
            colors: labelColor
          }
        },
        xaxis: {
          categories: ['จันทร์', 'อังคาร', 'พุธ', 'พฤหัส', 'ศุกร์', 'เสาร์', 'อาทิตย์'],
          labels: {
            style: {
              colors: labelColor
            }
          },
          axisBorder: { show: false },
          axisTicks: { show: false }
        },
        yaxis: {
          labels: {
            style: {
              colors: labelColor
            },
            formatter: function (value) {
              return value + 'L';
            }
          }
        },
        tooltip: {
          y: {
            formatter: function (value) {
              return value + ' ลิตร';
            }
          }
        }
      });

      liquidUsageChart.render();
    }

    if (document.querySelector('#topLocationChart')) {
      const topLocationChart = new ApexCharts(document.querySelector('#topLocationChart'), {
        chart: {
          height: 300,
          type: 'bar',
          parentHeightOffset: 0,
          toolbar: { show: false },
          fontFamily: chartFontFamily
        },
        series: [
          {
            name: 'ยอดขายวันนี้',
            data: [8450, 6230, 4280, 3180, 1840]
          }
        ],
        colors: [hygienePrimary],
        plotOptions: {
          bar: {
            horizontal: true,
            borderRadius: 8,
            barHeight: '48%'
          }
        },
        dataLabels: {
          enabled: true,
          formatter: function (value) {
            return '฿' + value.toLocaleString();
          }
        },
        grid: {
          borderColor: borderColor,
          strokeDashArray: 5
        },
        xaxis: {
          categories: ['คอนโด B', 'หอพัก A', 'ร้านซักผ้า C', 'อพาร์ตเมนต์ D', 'หอพัก E'],
          labels: {
            style: {
              colors: labelColor
            },
            formatter: function (value) {
              return value >= 1000 ? (value / 1000).toFixed(1) + 'k' : value;
            }
          }
        },
        yaxis: {
          labels: {
            style: {
              colors: labelColor
            }
          }
        },
        tooltip: {
          y: {
            formatter: function (value) {
              return '฿' + value.toLocaleString();
            }
          }
        }
      });

      topLocationChart.render();
    }
  });
</script>
@endsection

@section('content')
@php
  $summaryCards = [
    [
      'title' => 'ยอดขายวันนี้',
      'subtitle' => 'รวมทุกจุดติดตั้ง',
      'value' => '฿12,450',
      'trend' => '+18.2%',
      'trend_class' => 'text-success',
      'icon' => 'tabler-currency-baht',
      'icon_class' => 'hygiene-icon-primary'
    ],
    [
      'title' => 'จำนวนการกดวันนี้',
      'subtitle' => 'น้ำยาออกจากตู้ทั้งหมด',
      'value' => '312 ครั้ง',
      'trend' => '+9.4%',
      'trend_class' => 'text-success',
      'icon' => 'tabler-hand-click',
      'icon_class' => 'hygiene-icon-info'
    ],
    [
      'title' => 'ตู้ออนไลน์',
      'subtitle' => 'เครื่องที่เชื่อมต่ออยู่',
      'value' => '28 / 40',
      'trend' => '70%',
      'trend_class' => 'text-success',
      'icon' => 'tabler-plug-connected',
      'icon_class' => 'hygiene-icon-success'
    ],
    [
      'title' => 'ตู่ออฟไลน์',
      'subtitle' => 'ขาดการเชื่อมต่อ',
      'value' => '4 เครื่อง',
      'trend' => 'ต้องตรวจสอบ',
      'trend_class' => 'text-danger',
      'icon' => 'tabler-plug-connected-x',
      'icon_class' => 'hygiene-icon-danger'
    ],
    [
      'title' => 'น้ำยาใกล้หมด',
      'subtitle' => 'ต่ำกว่าเกณฑ์ขั้นต่ำ',
      'value' => '6 เครื่อง',
      'trend' => 'ควรเติมวันนี้',
      'trend_class' => 'text-warning',
      'icon' => 'tabler-droplet-half-2',
      'icon_class' => 'hygiene-icon-warning'
    ],
    [
      'title' => 'แจ้งเตือนค้างอยู่',
      'subtitle' => 'Offline / Low Stock / Error',
      'value' => '12 รายการ',
      'trend' => '3 รายการด่วน',
      'trend_class' => 'text-danger',
      'icon' => 'tabler-bell-ringing',
      'icon_class' => 'hygiene-icon-danger'
    ]
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
      'low_stock' => 1
    ],
    [
      'name' => 'คอนโด B',
      'address' => 'ลาดพร้าว กรุงเทพฯ',
      'machines' => 8,
      'online' => 7,
      'offline' => 1,
      'sales' => '฿2,840',
      'press_count' => 83,
      'low_stock' => 2
    ],
    [
      'name' => 'ร้านซักผ้า C',
      'address' => 'นนทบุรี',
      'machines' => 3,
      'online' => 2,
      'offline' => 1,
      'sales' => '฿890',
      'press_count' => 26,
      'low_stock' => 0
    ],
    [
      'name' => 'อพาร์ตเมนต์ D',
      'address' => 'ปทุมธานี',
      'machines' => 6,
      'online' => 4,
      'offline' => 1,
      'sales' => '฿1,640',
      'press_count' => 52,
      'low_stock' => 2
    ]
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
    ]
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
    ]
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
    ]
  ];

  $timelines = [
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
    [
      'type' => 'info',
      'title' => 'HY-007 กลับมาออนไลน์',
      'description' => 'ตู้คอนโด B โซนซักผ้ากลับมาเชื่อมต่อระบบแล้ว',
      'time' => '1 ชั่วโมงที่แล้ว'
    ]
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
          <h4 class="hygiene-title mb-1">ภาพรวมระบบตู้กดน้ำยาซักผ้า</h4>
          <p class="hygiene-subtitle mb-0">
            ติดตามยอดขาย สถานะตู้ ปริมาณน้ำยา และแจ้งเตือนเติม Stock จากทุกจุดติดตั้ง
          </p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
          <button type="button" class="btn btn-outline-secondary hygiene-filter-btn active">วันนี้</button>
          <button type="button" class="btn btn-outline-secondary hygiene-filter-btn">7 วัน</button>
          <button type="button" class="btn btn-outline-secondary hygiene-filter-btn">เดือนนี้</button>
          <button type="button" class="btn btn-primary" style="background:#007FC4;border-color:#007FC4;">
            <i class="icon-base ti tabler-refresh me-1"></i>
            รีเฟรชข้อมูล
          </button>
        </div>
      </div>
    </div>
  </div>

  @foreach ($summaryCards as $card)
    <div class="col-xxl-2 col-lg-4 col-md-4 col-sm-6 col-12">
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
            <small class="{{ $card['trend_class'] }} fw-medium">{{ $card['trend'] }}</small>
          </div>
        </div>
      </div>
    </div>
  @endforeach

  <div class="col-xxl-8 col-xl-8 col-12">
    <div class="card hygiene-card h-100">
      <div class="card-header d-flex flex-column flex-md-row justify-content-between gap-3">
        <div>
          <h5 class="card-title mb-1">ยอดขายและจำนวนการกด</h5>
          <p class="card-subtitle mb-0">เปรียบเทียบยอดขายรวมกับจำนวนครั้งที่ลูกค้ากดใช้งาน</p>
        </div>
        <div class="d-flex align-items-center gap-2">
          <span class="badge bg-label-primary">ยอดขายรวม ฿12,450</span>
          <span class="badge bg-label-info">312 ครั้ง</span>
        </div>
      </div>
      <div class="card-body">
        <div id="salesOverviewChart"></div>
      </div>
    </div>
  </div>

  <div class="col-xxl-4 col-xl-4 col-12">
    <div class="card hygiene-card h-100">
      <div class="card-header">
        <h5 class="card-title mb-1">สถานะตู้ทั้งหมด</h5>
        <p class="card-subtitle mb-0">ภาพรวมสถานะเครื่องจากทุกจุดติดตั้ง</p>
      </div>
      <div class="card-body">
        <div id="machineStatusChart"></div>
      </div>
    </div>
  </div>

  <div class="col-xl-7 col-12">
    <div class="card hygiene-card h-100">
      <div class="card-header d-flex flex-column flex-md-row justify-content-between gap-3">
        <div>
          <h5 class="card-title mb-1">สถานะตามจุดติดตั้ง</h5>
          <p class="card-subtitle mb-0">ดูยอดขายและสถานะตู้แยกตามสถานที่</p>
        </div>
        <a href="javascript:void(0);" class="btn btn-sm btn-outline-primary">
          ดูทุกจุดติดตั้ง
        </a>
      </div>
      <div class="card-body">
        <div class="row g-4">
          @foreach ($locationSummaries as $location)
            <div class="col-md-6">
              <div class="hygiene-location-item">
                <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                  <div>
                    <h6 class="mb-1">{{ $location['name'] }}</h6>
                    <small class="text-body-secondary">{{ $location['address'] }}</small>
                  </div>
                  <span class="badge bg-label-primary">{{ $location['machines'] }} ตู้</span>
                </div>

                <div class="row g-3">
                  <div class="col-6">
                    <small class="text-body-secondary d-block">ออนไลน์</small>
                    <div class="d-flex align-items-center gap-2">
                      <span class="hygiene-machine-dot hygiene-dot-online"></span>
                      <span class="fw-medium">{{ $location['online'] }} เครื่อง</span>
                    </div>
                  </div>
                  <div class="col-6">
                    <small class="text-body-secondary d-block">ออฟไลน์</small>
                    <div class="d-flex align-items-center gap-2">
                      <span class="hygiene-machine-dot hygiene-dot-offline"></span>
                      <span class="fw-medium">{{ $location['offline'] }} เครื่อง</span>
                    </div>
                  </div>
                  <div class="col-6">
                    <small class="text-body-secondary d-block">ยอดขายวันนี้</small>
                    <span class="fw-medium">{{ $location['sales'] }}</span>
                  </div>
                  <div class="col-6">
                    <small class="text-body-secondary d-block">จำนวนการกด</small>
                    <span class="fw-medium">{{ $location['press_count'] }} ครั้ง</span>
                  </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                  <small class="text-warning">
                    <i class="icon-base ti tabler-alert-triangle me-1"></i>
                    น้ำยาใกล้หมด {{ $location['low_stock'] }} เครื่อง
                  </small>
                  <a href="javascript:void(0);" class="small fw-medium">รายละเอียด</a>
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
      <div class="card-header">
        <h5 class="card-title mb-1">ปริมาณน้ำยาที่ใช้งาน</h5>
        <p class="card-subtitle mb-0">สรุปการใช้น้ำยาซักผ้าและน้ำยาปรับผ้านุ่มรายวัน</p>
      </div>
      <div class="card-body">
        <div id="liquidUsageChart"></div>
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

  <div class="col-xl-4 col-12">
    <div class="card hygiene-card h-100">
      <div class="card-header">
        <h5 class="card-title mb-1">อันดับจุดขายดี</h5>
        <p class="card-subtitle mb-0">ยอดขายวันนี้แยกตามจุดติดตั้ง</p>
      </div>
      <div class="card-body">
        <div id="topLocationChart"></div>
      </div>
    </div>
  </div>

  <div class="col-xl-4 col-12">
    <div class="card hygiene-card h-100">
      <div class="card-header">
        <h5 class="card-title mb-1">ภาพรวมจุดติดตั้ง</h5>
        <p class="card-subtitle mb-0">ตำแหน่งตู้และสถานะแต่ละพื้นที่</p>
      </div>
      <div class="card-body">
        <div class="hygiene-map-placeholder d-flex align-items-center justify-content-center text-center p-4">
          <div>
            <div class="hygiene-icon-box hygiene-icon-primary mx-auto mb-3">
              <i class="icon-base ti tabler-map-pin icon-26px"></i>
            </div>
            <h6 class="mb-1">แผนที่จุดติดตั้ง</h6>
            <p class="text-body-secondary mb-3">
              สามารถเชื่อม Google Maps เพื่อแสดง Marker สีตามสถานะตู้
            </p>
            <div class="d-flex justify-content-center flex-wrap gap-2">
              <span class="badge bg-label-success">ปกติ 28</span>
              <span class="badge bg-label-warning">น้ำยาใกล้หมด 6</span>
              <span class="badge bg-label-danger">ออฟไลน์ 4</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-4 col-12">
    <div class="card hygiene-card h-100">
      <div class="card-header">
        <h5 class="card-title mb-1">Activity Timeline</h5>
        <p class="card-subtitle mb-0">เหตุการณ์ล่าสุดของระบบ</p>
      </div>
      <div class="card-body">
        <ul class="timeline hygiene-timeline mb-0">
          @foreach ($timelines as $timeline)
            <li class="timeline-item timeline-item-transparent">
              <span class="timeline-point timeline-point-{{ $timeline['type'] }}"></span>
              <div class="timeline-event">
                <div class="timeline-header mb-2">
                  <h6 class="mb-0">{{ $timeline['title'] }}</h6>
                  <small class="text-body-secondary">{{ $timeline['time'] }}</small>
                </div>
                <p class="mb-0 text-body-secondary">{{ $timeline['description'] }}</p>
              </div>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection
