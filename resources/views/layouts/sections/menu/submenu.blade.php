@php
use Illuminate\Support\Facades\Route;
@endphp

<ul class="menu-sub">
  @foreach ($menu as $submenu)
    @php
      $submenuHeader = is_object($submenu) ? ($submenu->menuHeader ?? null) : ($submenu['menuHeader'] ?? null);
      $submenuSlug = is_object($submenu) ? ($submenu->slug ?? null) : ($submenu['slug'] ?? null);
      $submenuSubmenu = is_object($submenu) ? ($submenu->submenu ?? null) : ($submenu['submenu'] ?? null);
      $submenuUrl = is_object($submenu) ? ($submenu->url ?? null) : ($submenu['url'] ?? null);
      $submenuTarget = is_object($submenu) ? ($submenu->target ?? null) : ($submenu['target'] ?? null);
      $submenuIcon = is_object($submenu) ? ($submenu->icon ?? null) : ($submenu['icon'] ?? null);
      $submenuName = is_object($submenu) ? ($submenu->name ?? null) : ($submenu['name'] ?? null);
      $submenuBadge = is_object($submenu) ? ($submenu->badge ?? null) : ($submenu['badge'] ?? null);

      $activeClass = null;
      $currentRouteName = Route::currentRouteName();

      if (!empty($submenuSlug) && $currentRouteName === $submenuSlug) {
        $activeClass = 'active';
      } elseif (!empty($submenuSubmenu) && !empty($submenuSlug) && !empty($currentRouteName)) {
        if (is_array($submenuSlug)) {
          foreach ($submenuSlug as $slug) {
            if (!empty($slug) && str_contains($currentRouteName, $slug) && strpos($currentRouteName, $slug) === 0) {
              $activeClass = 'active open';
            }
          }
        } else {
          if (str_contains($currentRouteName, $submenuSlug) && strpos($currentRouteName, $submenuSlug) === 0) {
            $activeClass = 'active open';
          }
        }
      }

      $badgeColor = null;
      $badgeText = null;

      if (!empty($submenuBadge)) {
        if (is_array($submenuBadge)) {
          $badgeColor = $submenuBadge[0] ?? null;
          $badgeText = $submenuBadge[1] ?? null;
        } elseif (is_object($submenuBadge)) {
          $badgeColor = $submenuBadge->{0} ?? ($submenuBadge->color ?? null);
          $badgeText = $submenuBadge->{1} ?? ($submenuBadge->text ?? null);
        }
      }
    @endphp

    @if (!empty($submenuHeader))
      <li class="menu-header small">
        <span class="menu-header-text">{{ __($submenuHeader) }}</span>
      </li>
    @else
      <li class="menu-item {{ $activeClass }}">
        <a
          href="{{ !empty($submenuUrl) ? url($submenuUrl) : 'javascript:void(0);' }}"
          class="{{ !empty($submenuSubmenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
          @if (!empty($submenuTarget)) target="_blank" @endif
        >
          @if (!empty($submenuIcon))
            <i class="{{ $submenuIcon }}"></i>
          @endif

          <div>{{ !empty($submenuName) ? __($submenuName) : '' }}</div>

          @if (!empty($badgeColor) && !empty($badgeText))
            <div class="badge bg-{{ $badgeColor }} rounded-pill ms-auto">
              {{ $badgeText }}
            </div>
          @endif
        </a>

        @if (!empty($submenuSubmenu))
          @include('layouts.sections.menu.submenu', ['menu' => $submenuSubmenu])
        @endif
      </li>
    @endif
  @endforeach
</ul>
