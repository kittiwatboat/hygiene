<script>
  document.addEventListener('DOMContentLoaded', function () {
    const provinceSelect = document.getElementById('province_id');
    const districtSelect = document.getElementById('district_id');
    const subdistrictSelect = document.getElementById('subdistrict_id');
    const postcodeInput = document.getElementById('postcode');

    const districtUrl = "{{ url('/address/districts') }}";
    const subdistrictUrl = "{{ url('/address/subdistricts') }}";

    function clearSelect(selectElement, placeholder) {
      selectElement.innerHTML = '';

      const option = document.createElement('option');
      option.value = '';
      option.textContent = placeholder;

      selectElement.appendChild(option);
    }

    function fillPostcode() {
      const selectedOption = subdistrictSelect.options[subdistrictSelect.selectedIndex];

      if (!selectedOption) {
        postcodeInput.value = '';
        return;
      }

      postcodeInput.value = selectedOption.getAttribute('data-zipcode') || '';
    }

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
          const option = document.createElement('option');
          option.value = district.id;
          option.textContent = district.name;

          districtSelect.appendChild(option);
        });
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
          const option = document.createElement('option');
          option.value = subdistrict.id;
          option.textContent = subdistrict.name;
          option.setAttribute('data-zipcode', subdistrict.zipcode || '');

          subdistrictSelect.appendChild(option);
        });
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
