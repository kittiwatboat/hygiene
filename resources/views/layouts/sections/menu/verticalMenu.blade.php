@php
  $activeClass = null;
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

  if ($isActiveMenu($menu)) {
      $activeClass = isset($menu->submenu) ? 'active open' : 'active';
  }
@endphp
