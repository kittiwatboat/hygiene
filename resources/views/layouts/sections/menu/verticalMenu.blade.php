@php
use Illuminate\Support\Facades\Route;

$configData = Helper::appClasses();

$currentRouteName = Route::currentRouteName() ?? '';
$currentPath = trim(request()->path(), '/');

$isActiveMenu = function ($menu) use (&$isActiveMenu, $currentRouteName, $currentPath) {
    $slugs = [];

    if (isset($menu->slug)) {
        $slugs = is_array($menu->slug) ? $menu->slug : [$menu->slug];
    }

    foreach ($slugs as $slug) {
        $slug = trim((string) $slug, '/');

        if ($slug !== '') {
            if ($currentRouteName === $slug || str_starts_with($currentRouteName, $slug . '.')) {
                return true;
            }

            if ($currentPath === $slug || str_starts_with($currentPath, $slug . '/')) {
                return true;
            }
        }
    }

    if (isset($menu->url)) {
        $urlPath = trim(parse_url($menu->url, PHP_URL_PATH) ?? '', '/');

        if ($urlPath !== '') {
            if ($currentPath === $urlPath || str_starts_with($currentPath, $urlPath . '/')) {
                return true;
            }
        }
    }

    if (isset($menu->submenu)) {
        foreach ($menu->submenu as $submenu) {
            if ($isActiveMenu($submenu)) {
                return true;
            }
        }
    }

    return false;
};
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu"
  @foreach ($configData['menuAttributes'] as $attribute => $value)
    {{ $attribute }}="{{ $value }}"
  @endforeach>

  @if (!isset($navbarFull))
    <div class="app-brand demo">
      <a href="{{ url('/') }}" class="app-brand-link">
        <span class="app-brand-logo demo">@include('_partials.macros')</span>
        <span class="app-brand-text demo menu-text fw-bold ms-3">
          {{ config('variables.templateName') }}
        </span>
      </a>

      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
        <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
        <i class="icon-base ti tabler-x d-block d-xl-none"></i>
      </a>
    </div>
  @endif

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    @foreach ($menuData[0]->menu as $menu)

      @if (isset($menu->menuHeader))
        <li class="menu-header small">
          <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
        </li>
      @else
        @php
          $activeClass = $isActiveMenu($menu)
              ? (isset($menu->submenu) ? 'active open' : 'active')
              : '';
        @endphp

        <li class="menu-item {{ $activeClass }}">
          <a
            href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}"
            class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
            @if (isset($menu->target) && !empty($menu->target)) target="_blank" @endif>

            @isset($menu->icon)
              <i class="{{ $menu->icon }}"></i>
            @endisset

            <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>

            @isset($menu->badge)
              <div class="badge bg-{{ $menu->badge[0] }} rounded-pill ms-auto">
                {{ $menu->badge[1] }}
              </div>
            @endisset
          </a>

          @isset($menu->submenu)
            @include('layouts.sections.menu.submenu', [
              'menu' => $menu->submenu,
              'isActiveMenu' => $isActiveMenu
            ])
          @endisset
        </li>
      @endif

    @endforeach
  </ul>
</aside>
