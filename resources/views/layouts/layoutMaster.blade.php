@isset($pageConfigs)
  {!! app(\App\Helpers\Helpers::class)->updatePageConfig($pageConfigs) !!}
@endisset
@php
  $configData = app(\App\Helpers\Helpers::class)->appClasses();
@endphp

@isset($configData['layout'])
  @include(
      $configData['layout'] === 'horizontal'
          ? 'layouts.horizontalLayout'
          : ($configData['layout'] === 'blank'
              ? 'layouts.blankLayout'
              : ($configData['layout'] === 'front'
                  ? 'layouts.layoutFront'
                  : 'layouts.contentNavbarLayout')))
@endisset
