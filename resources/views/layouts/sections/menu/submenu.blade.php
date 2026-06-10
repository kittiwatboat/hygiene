<ul class="menu-sub">
  @foreach ($menu as $submenu)

    @if (isset($submenu->menuHeader))
      <li class="menu-header small">
        <span class="menu-header-text">{{ __($submenu->menuHeader) }}</span>
      </li>
    @else
      @php
        $activeClass = isset($isActiveMenu) && $isActiveMenu($submenu)
            ? (isset($submenu->submenu) ? 'active open' : 'active')
            : '';
      @endphp

      <li class="menu-item {{ $activeClass }}">
        <a
          href="{{ isset($submenu->url) ? url($submenu->url) : 'javascript:void(0);' }}"
          class="{{ isset($submenu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
          @if (isset($submenu->target) && !empty($submenu->target)) target="_blank" @endif>

          @isset($submenu->icon)
            <i class="{{ $submenu->icon }}"></i>
          @endisset

          <div>{{ isset($submenu->name) ? __($submenu->name) : '' }}</div>

          @isset($submenu->badge)
            <div class="badge bg-{{ $submenu->badge[0] }} rounded-pill ms-auto">
              {{ $submenu->badge[1] }}
            </div>
          @endisset
        </a>

        @isset($submenu->submenu)
          @include('layouts.sections.menu.submenu', [
            'menu' => $submenu->submenu,
            'isActiveMenu' => $isActiveMenu
          ])
        @endisset
      </li>
    @endif

  @endforeach
</ul>
