<script>
  document.addEventListener('DOMContentLoaded', function () {
    const districtUrl = "{{ url('/address/districts') }}";
    const subdistrictUrl = "{{ url('/address/subdistricts') }}";

    const $provinceSelect = $('#province_id');
    const $districtSelect = $('#district_id');
    const $subdistrictSelect = $('#subdistrict_id');
    const $postcodeInput = $('#postcode');

    function initSelect2() {
      $('.select2-address').each(function () {
        const $select = $(this);

        if ($select.hasClass('select2-hidden-accessible')) {
          $select.select2('destroy');
        }

        $select.select2({
          width: '100%',
          allowClear: true,
          placeholder: $select.data('placeholder') || '-- เลือกข้อมูล --',
          language: {
            noResults: function () {
              return 'ไม่พบข้อมูล';
            },
            searching: function () {
              return 'กำลังค้นหา...';
            }
          }
        });
      });
    }

    function resetSelect($select, placeholder) {
      $select.empty();

      const emptyOption = new Option(placeholder, '', true, false);
      $select.append(emptyOption);

      $select.val('').trigger('change.select2');
    }

    function appendOption($select, value, text, data = {}) {
      const option = new Option(text, value, false, false);

      Object.keys(data).forEach(function (key) {
        option.setAttribute(key, data[key]);
      });

      $select.append(option);
    }

    function fillPostcode() {
      const selectedOption = $subdistrictSelect.find(':selected');
      const zipcode = selectedOption.data('zipcode') || selectedOption.attr('data-zipcode') || '';

      $postcodeInput.val(zipcode);
    }

    async function loadDistricts(provinceId) {
      resetSelect($districtSelect, '-- เลือกอำเภอ/เขต --');
      resetSelect($subdistrictSelect, '-- เลือกตำบล/แขวง --');
      $postcodeInput.val('');

      if (!provinceId) {
        return;
      }

      $districtSelect.prop('disabled', true);

      try {
        const response = await fetch(`${districtUrl}/${provinceId}`, {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
          },
        });

        const result = await response.json();

        console.log('district result:', result);

        if (!result.success) {
          return;
        }

        result.data.forEach(function (district) {
          appendOption($districtSelect, district.id, district.name);
        });

        $districtSelect.prop('disabled', false);
        $districtSelect.val('').trigger('change.select2');
      } catch (error) {
        console.error('Cannot load districts:', error);
        $districtSelect.prop('disabled', false);
      }
    }

    async function loadSubdistricts(districtId) {
      resetSelect($subdistrictSelect, '-- เลือกตำบล/แขวง --');
      $postcodeInput.val('');

      if (!districtId) {
        return;
      }

      $subdistrictSelect.prop('disabled', true);

      try {
        const response = await fetch(`${subdistrictUrl}/${districtId}`, {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
          },
        });

        const result = await response.json();

        console.log('subdistrict result:', result);

        if (!result.success) {
          return;
        }

        result.data.forEach(function (subdistrict) {
          appendOption($subdistrictSelect, subdistrict.id, subdistrict.name, {
            'data-zipcode': subdistrict.zipcode || ''
          });
        });

        $subdistrictSelect.prop('disabled', false);
        $subdistrictSelect.val('').trigger('change.select2');
      } catch (error) {
        console.error('Cannot load subdistricts:', error);
        $subdistrictSelect.prop('disabled', false);
      }
    }

    initSelect2();

    $provinceSelect.on('change', function () {
      const provinceId = $(this).val();
      loadDistricts(provinceId);
    });

    $districtSelect.on('change', function () {
      const districtId = $(this).val();
      loadSubdistricts(districtId);
    });

    $subdistrictSelect.on('change', function () {
      fillPostcode();
    });

    fillPostcode();
  });
</script>
