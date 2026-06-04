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
                    <!-- start breadcrumb -->


                    {{-- START:CONTENT --}}
                    <div class="row">
                        <div class="col-xl-4">
                            <div class="card overflow-hidden">
                                <div class="bg-primary-subtle">
                                    <div class="row">
                                        <div class="col-7">
                                            <div class="text-primary p-3">
                                                <h5 class="text-primary">ยินดีต้อนรับ</h5>
                                                <p>Metrocat Dashboard</p>
                                            </div>
                                        </div>
                                        <div class="col-5 align-self-end">
                                            <img src="{{asset("backend/assets/images/profile-img.png")}}" alt="" class="img-fluid">
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
