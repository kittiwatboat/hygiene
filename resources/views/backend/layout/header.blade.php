<base href="{{url("/th")}}">
<meta charset="utf-8" />
<title>Dashboard | Management</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
<meta content="Themesbrand" name="author" />
<!-- App favicon -->
<link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.ico') }}">
<!-- Bootstrap Css -->
<link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{ asset('backend/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<!-- App js -->
<script src="{{ asset('backend/assets/js/plugin.js') }}"></script>

<link href="{{asset("backend/assets/libs/select2/css/select2.min.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("backend/assets/libs/sweetalert2/sweetalert2.min.css")}}" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">

<style>
     label.required::after {
        content: " *";
        color: red;
    }
    .select2-selection{
        height: 35px !important;
    }

    .select2-selection__rendered{
        line-height: 33px !important;
    }

    .form-control-solid {
        background-color: #f5f8fa;
        border: 1px solid #e4e6ef;
        color: #3f4254;
        transition: color 0.2s ease, background-color 0.2s ease;
        box-shadow: none;
    }

    .form-control-solid:focus {
        background-color: #eef3f7;
        border-color: #d1d3e0;
        outline: 0;
        box-shadow: none;
    }

    .cursor-move {
        cursor: move;
    }

    #app-loader {
    position: fixed;
    inset: 0;
    background: rgba(255, 255, 255, 0.8);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    }

    .loading-logo {
    display: flex;
    align-items: center;
    justify-content: center;
    }

    .logo-spin {
    width: 100px;
    height: 100px;
    animation: spin 1.5s linear infinite;
    }

    @keyframes spin {
    from { transform: rotate(0deg); }
    to   { transform: rotate(360deg); }
    }
</style>