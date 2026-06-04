<script src="{{ asset('backend/assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('backend/assets/libs/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('backend/assets/libs/node-waves/waves.min.js') }}"></script>

<!-- apexcharts -->
<script src="{{ asset('backend/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

<!-- dashboard init -->
<script src="{{ asset('backend/assets/js/pages/dashboard.init.js') }}"></script>

<!-- App js -->
<script src="{{ asset('backend/assets/js/app.js') }}"></script>

<script src="{{ asset('backend/assets/libs/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('backend/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
<script type="text/javascript" src="ckeditor/ckeditor.js?t={{ now()->timestamp }}"></script>
@include("$prefix.layout.loading")

<div id="app-loader">
    <div class="loading-logo">
      <img src="backend/assets/metrocat_logo.jpg" alt="Logo" style="width:120px; height:40px;" class="logo-spin">
    </div>
  </div>

<div class="modal modal_01 fade" id="modal01" tabindex="-1" aria-labelledby="modal01Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="show_modal_01"></div>
        </div>
    </div>
</div>

<script>
    $('.select2').select2();

    function chkNumber(ele) {
        var vchar = String.fromCharCode(event.keyCode);
        if ((vchar < '0' || vchar > '9') && (vchar != '.')) return false;
        ele.onKeyPress = vchar;
    }

    function loadingSwal() {
        showLoader();
    }

    function showLoading() {
        Swal.fire({
            title: 'Please wait...',
            allowOutsideClick: () => !Swal.isLoading(),
            timer: 1000,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading()
            }
        });
    }

    function hideLoading() {
        Swal.close();
    }

    function showLoader() {
        document.getElementById('app-loader').style.display = 'flex';
        document.documentElement.style.overflow = 'hidden';
        document.body.style.overflow = 'hidden';
    }

    function hideLoader() {
        document.getElementById('app-loader').style.display = 'none';
        document.documentElement.style.overflow = '';
        document.body.style.overflow = '';
    }
</script>
