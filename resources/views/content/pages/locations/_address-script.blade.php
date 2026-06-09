<script>
  document.addEventListener('DOMContentLoaded', function () {
    const provinceSelect = document.getElementById('province_id');
    const districtSelect = document.getElementById('district_id');
    const subdistrictSelect = document.getElementById('subdistrict_id');
    const postcodeInput = document.getElementById('postcode');

    const districtUrl = "{{ url('/address/districts') }}";
    const subdistrictUrl = "{{ url('/address/subdistricts') }}";

    function hasJqueryAndSelect2() {
      return typeof window.jQuery !== 'undefined' && typeof window.jQuery.fn.select2 !== 'undefined';
    }

    function initSelect2() {
      if (!hasJqueryAndSelect2()) {
        return;
      }

      $('.select2-address').each(function () {
        const $this = $(this);

        if ($this.hasClass('select2-hidden-accessible')) {
          $this.select2('destroy');
        }

        $this.select2({
          width: '100%',
          allowClear: true,
          placeholder: $this.data('placeholder') || '-- เลือกข้อมูล --',
          language: {
            noResults: function () {
              return 'ไม่พบข้อมูล';
            },
            searching: function () {
              return 'กำลังค้นหา...';
            },
            inputTooShort: function () {
              return 'กรุณาพิมพ์เพื่อค้นหา';
            }
          }
        });
      });
    }

    function refreshSelect2(selectElement) {
      if (!hasJqueryAndSelect2()) {
        return;
      }

      $(selectElement).trigger('change.select2');
    }

    function clearSelect(selectElement, placeholder) {
      selectElement.innerHTML = '';

      const option = document.createElement('option');
      option.value = '';
      option.textContent = placeholder;

      selectElement.appendChild(option);

      refreshSelect2(selectElement);
    }

    function fillPostcode() {
      const selectedOption = subdistrictSelect.options[subdistrictSelect.selectedIndex];

      if (!selectedOption) {
        postcodeInput.value = '';
        return;
      }

      postcodeInput.value = selectedOption.getAttribute('data-zipcode') || '';
    }

    function appendOption(selectElement, value, text, attributes = {}) {
      const option = document.createElement('option');

      option.value = value;
      option.textContent = text;

      Object.keys(attributes).forEach(function (key) {
        option.setAttribute(key, attributes[key]);
      });

      selectElement.appendChild(option);
    }

    initSelect2();

    provinceSelect.addEventListener('change', async function () {
      const provinceId = this.value;

      clearSelect(districtSelect, '-- เลือกอำเภอ/เขต --');
      clearSelect(subdistrictSelect, '-- เลือกตำบล/แขวง --');
      postcodeInput.value = '';

      if (!provinceId) {
        return;
      }

      try {
        const response = await fetch(`${districtUrl}/${provinceId}`, {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
          },
        });

        const result = await response.json();

        if (!result.success) {
          return;
        }

        result.data.forEach(function (district) {
          appendOption(districtSelect, district.id, district.name);
        });

        refreshSelect2(districtSelect);
      } catch (error) {
        console.error('Cannot load districts:', error);
      }
    });

    districtSelect.addEventListener('change', async function () {
      const districtId = this.value;

      clearSelect(subdistrictSelect, '-- เลือกตำบล/แขวง --');
      postcodeInput.value = '';

      if (!districtId) {
        return;
      }

      try {
        const response = await fetch(`${subdistrictUrl}/${districtId}`, {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
          },
        });

        const result = await response.json();

        if (!result.success) {
          return;
        }

        result.data.forEach(function (subdistrict) {
          appendOption(
            subdistrictSelect,
            subdistrict.id,
            subdistrict.name,
            {
              'data-zipcode': subdistrict.zipcode || ''
            }
          );
        });

        refreshSelect2(subdistrictSelect);
      } catch (error) {
        console.error('Cannot load subdistricts:', error);
      }
    });

    subdistrictSelect.addEventListener('change', function () {
      fillPostcode();
    });

    fillPostcode();
  });
</script>
