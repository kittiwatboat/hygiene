@php
use Illuminate\Support\Facades\Route;

$configData = app(\App\Helpers\Helpers::class)->appClasses();

/*
|--------------------------------------------------------------------------
| Normalize Menu Data
|--------------------------------------------------------------------------
| ป้องกัน error:
| Cannot use object of type stdClass as array
|
| เพราะบางครั้ง $menuData มาเป็น object:
| $menuData->menu
|
| แต่บางครั้งมาเป็น array:
| $menuData[0]->menu
*/
$menus = [];

if (isset($menuData)) {
  if (is_array($menuData)) {
    $firstMenuData = $menuData[0] ?? null;

    if (is_object($firstMenuData) && isset($firstMenuData->menu)) {
      $menus = $firstMenuData->menu;
    } elseif (is_array($firstMenuData) && isset($firstMenuData['menu'])) {
      $menus = $firstMenuData['menu'];
    }
  } elseif (is_object($menuData)) {
    if (isset($menuData->menu)) {
      $menus = $menuData->menu;
    } elseif (isset($menuData->{0}) && isset($menuData->{0}->menu)) {
      $menus = $menuData->{0}->menu;
    }
  }
}

$currentRouteName = Route::currentRouteName();
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu"
  @foreach ($configData['menuAttributes'] ?? [] as $attribute => $value)
    {{ $attribute }}="{{ $value }}"
  @endforeach
>

  {{-- Hide app brand if navbar-full --}}
  @if (!isset($navbarFull))
    <div class="app-brand demo">
      <a href="{{ url('/') }}" class="app-brand-link">
        <span class="app-brand-logo demo">
          @include('_partials.macros')
        </span>
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
    @foreach ($menus as $menu)
      @php
        /*
        |--------------------------------------------------------------------------
        | Normalize Single Menu
        |--------------------------------------------------------------------------
        */
        $menuHeader = is_object($menu) ? ($menu->menuHeader ?? null) : ($menu['menuHeader'] ?? null);
        $menuSlug = is_object($menu) ? ($menu->slug ?? null) : ($menu['slug'] ?? null);
        $menuSubmenu = is_object($menu) ? ($menu->submenu ?? null) : ($menu['submenu'] ?? null);
        $menuUrl = is_object($menu) ? ($menu->url ?? null) : ($menu['url'] ?? null);
        $menuTarget = is_object($menu) ? ($menu->target ?? null) : ($menu['target'] ?? null);
        $menuIcon = is_object($menu) ? ($menu->icon ?? null) : ($menu['icon'] ?? null);
        $menuName = is_object($menu) ? ($menu->name ?? null) : ($menu['name'] ?? null);
        $menuBadge = is_object($menu) ? ($menu->badge ?? null) : ($menu['badge'] ?? null);

        $activeClass = null;

        if (!empty($menuSlug) && $currentRouteName === $menuSlug) {
          $activeClass = 'active';
        } elseif (!empty($menuSubmenu) && !empty($menuSlug) && !empty($currentRouteName)) {
          if (is_array($menuSlug)) {
            foreach ($menuSlug as $slug) {
              if (!empty($slug) && str_contains($currentRouteName, $slug) && strpos($currentRouteName, $slug) === 0) {
                $activeClass = 'active open';
              }
            }
          } else {
            if (str_contains($currentRouteName, $menuSlug) && strpos($currentRouteName, $menuSlug) === 0) {
              $activeClass = 'active open';
            }
          }
        }

        $badgeColor = null;
        $badgeText = null;

        if (!empty($menuBadge)) {
          if (is_array($menuBadge)) {
            $badgeColor = $menuBadge[0] ?? null;
            $badgeText = $menuBadge[1] ?? null;
          } elseif (is_object($menuBadge)) {
            $badgeColor = $menuBadge->{0} ?? ($menuBadge->color ?? null);
            $badgeText = $menuBadge->{1} ?? ($menuBadge->text ?? null);
          }
        }
      @endphp

      {{-- menu headers --}}
      @if (!empty($menuHeader))
        <li class="menu-header small">
          <span class="menu-header-text">{{ __($menuHeader) }}</span>
        </li>
      @else
        {{-- main menu --}}
        <li class="menu-item {{ $activeClass }}">
          <a
            href="{{ !empty($menuUrl) ? url($menuUrl) : 'javascript:void(0);' }}"
            class="{{ !empty($menuSubmenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
            @if (!empty($menuTarget)) target="_blank" @endif
          >
            @if (!empty($menuIcon))
              <i class="{{ $menuIcon }}"></i>
            @endif

            <div>{{ !empty($menuName) ? __($menuName) : '' }}</div>

            @if (!empty($badgeColor) && !empty($badgeText))
              <div class="badge bg-{{ $badgeColor }} rounded-pill ms-auto">
                {{ $badgeText }}
              </div>
            @endif
          </a>

          {{-- submenu --}}
          @if (!empty($menuSubmenu))
            @include('layouts.sections.menu.submenu', ['menu' => $menuSubmenu])
          @endif
        </li>
      @endif
    @endforeach
  </ul>
</aside>
