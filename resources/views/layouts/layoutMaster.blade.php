@isset($pageConfigs)
  {!! app()->make('App\Helpers\Helper')->updatePageConfig($pageConfigs) !!}
@endisset
@php
  $configData = app()->make('App\Helpers\Helper')->appClasses();
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
