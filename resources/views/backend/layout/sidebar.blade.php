@php
    $prefix = 'webpanel';
@endphp
<div class="vertical-menu">
    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Menu</li>

                <li class="{{ Request::is("$prefix") || Request::is("$prefix/") ? 'mm-active' : '' }}">
                    <a href="{{ url("$prefix") }}" class="waves-effect {{ Request::is("$prefix") || Request::is("$prefix/") ? 'active' : '' }}">
                        <i class="bx bx-home-circle"></i>
                        <span>Dashboards</span>
                    </a>
                </li>
                <li class="{{ Request::is("$prefix/user*") ? 'mm-active' : '' }}">
                    <a href="{{ url("$prefix/user") }}" class="waves-effect {{ Request::is("$prefix/user*") ? 'active' : '' }}">
                        <i class="bx bx-user"></i><span>User</span>
                    </a>
                </li>
                {{-- <li class="{{ Request::is("$prefix/career*") ? 'mm-active' : '' }}">
                    <a href="{{ url("$prefix/career") }}" class="waves-effect {{ Request::is("$prefix/career*") ? 'active' : '' }}">
                        <i class="bx bx-user"></i><span>Career</span>
                    </a>
                </li> --}}
                <li class="{{ Request::is("$prefix/menu*") ? 'mm-active' : '' }}">
                    <a href="{{ url("$prefix/menu") }}" class="waves-effect {{ Request::is("$prefix/menu*") ? 'active' : '' }}">
                        <i class="bx bx-menu-alt-left"></i><span>Menu</span>
                    </a>
                </li>
                <li class="{{ Request::is("$prefix/calendar*") ? 'mm-active' : '' }}">
                    <a href="{{ url("$prefix/calendar") }}" class="waves-effect {{ Request::is("$prefix/calendar*") ? 'active' : '' }}">
                        <i class="bx bx-calendar-event"></i><span>Calendar</span>
                    </a>
                </li>
                <li class="{{ Request::is("$prefix/file-manage*") ? 'mm-active' : '' }}">
                    <a href="{{ url("$prefix/file-manage") }}" class="waves-effect {{ Request::is("$prefix/file-manage*") ? 'active' : '' }}">
                        <i class="bx bx-folder"></i><span>File Manage</span>
                    </a>
                </li>
                <li class="{{ Request::is("$prefix/support*") ? 'mm-active' : '' }}">
                    <a href="{{ url("$prefix/support") }}" class="waves-effect {{ Request::is("$prefix/support*") ? 'active' : '' }}">
                        <i class="bx bx-support"></i><span>Support</span>
                    </a>
                </li>
                @php $orgActive = Request::is("$prefix/master-organization*") || Request::is("$prefix/organization*"); @endphp
                <li class="{{ $orgActive ? 'mm-active' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow waves-effect {{ $orgActive ? 'mm-active' : '' }}" aria-expanded="{{ $orgActive ? 'true' : 'false' }}">
                        <i class="bx bx-cog"></i>
                        <span>Organization</span>
                    </a>
                    <ul class="sub-menu {{ $orgActive ? 'mm-show' : '' }}" aria-expanded="{{ $orgActive ? 'true' : 'false' }}">
                        <li><a href="{{ url("$prefix/master-organization") }}" class="{{ Request::is("$prefix/master-organization*") ? 'active' : '' }}"><i class="bx bx-list-ol"></i> Master Organization</a></li>
                        <li><a href="{{ url("$prefix/organization") }}" class="{{ Request::is("$prefix/organization*") ? 'active' : '' }}"><i class="bx bx-list-ol"></i> Organization</a></li>
                    </ul>
                </li>
                @php $wsActive = Request::is("$prefix/workspace*") || Request::is("$prefix/workspace/*"); @endphp
                <li class="{{ $wsActive ? 'mm-active' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow waves-effect {{ $wsActive ? 'mm-active' : '' }}" aria-expanded="{{ $wsActive ? 'true' : 'false' }}">
                        <i class="bx bx-cog"></i>
                        <span>Work Space</span>
                    </a>
                    <ul class="sub-menu {{ $wsActive ? 'mm-show' : '' }}" aria-expanded="{{ $wsActive ? 'true' : 'false' }}">
                        <li><a href="{{ url("$prefix/workspace/category") }}" class="{{ Request::is("$prefix/workspace/category*") ? 'active' : '' }}"><i class="bx bx-list-ol"></i> Category</a></li>
                        <li><a href="{{ url("$prefix/workspace/application") }}" class="{{ Request::is("$prefix/workspace/application*") ? 'active' : '' }}"><i class="bx bx-list-ol"></i> Application</a></li>
                        <li><a href="{{ url("$prefix/workspace/dictionary") }}" class="{{ Request::is("$prefix/workspace/dictionary*") ? 'active' : '' }}"><i class="bx bx-list-ol"></i> Dictionary</a></li>
                    </ul>
                </li>
                @php $setActive = Request::is("$prefix/master/*") || Request::is("$prefix/master/banner*") || Request::is("$prefix/master/department*")
                || Request::is("$prefix/master/division*") || Request::is("$prefix/master/position*") || Request::is("$prefix/master/feed*"); @endphp
                <li class="{{ $setActive ? 'mm-active' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow waves-effect {{ $setActive ? 'mm-active' : '' }}" aria-expanded="{{ $setActive ? 'true' : 'false' }}">
                        <i class="bx bx-cog"></i>
                        <span>Setting</span>
                    </a>
                    <ul class="sub-menu {{ $setActive ? 'mm-show' : '' }}" aria-expanded="{{ $setActive ? 'true' : 'false' }}">
                        <li><a href="{{ url("$prefix/master/banner") }}" class="{{ Request::is("$prefix/master/banner*") ? 'active' : '' }}"><i class="bx bx-list-ol"></i> Banner</a></li>
                        <li><a href="{{ url("$prefix/master/department") }}" class="{{ Request::is("$prefix/master/department*") ? 'active' : '' }}"><i class="bx bx-list-ol"></i> Department</a></li>
                        <li><a href="{{ url("$prefix/master/division") }}" class="{{ Request::is("$prefix/master/division*") ? 'active' : '' }}"><i class="bx bx-list-ol"></i> Division</a></li>
                        <li><a href="{{ url("$prefix/master/position") }}" class="{{ Request::is("$prefix/master/position*") ? 'active' : '' }}"><i class="bx bx-list-ol"></i> Position</a></li>
                        <li><a href="{{ url("$prefix/master/feed") }}" class="{{ Request::is("$prefix/master/feed*") ? 'active' : '' }}"><i class="bx bx-list-ol"></i> Feed</a></li>
                        <li><a href="{{ url("$prefix/master/file-category") }}" class="{{ Request::is("$prefix/master/file-category*") ? 'active' : '' }}"><i class="bx bx-list-ol"></i> File Category</a></li>
                        {{-- <li><a href="{{ url("$prefix/master/career") }}" class="{{ Request::is("$prefix/master/career*") ? 'active' : '' }}"><i class="bx bx-list-ol"></i> Career Position</a></li> --}}
                    </ul>
                </li>
                @php $wsActive = Request::is("$prefix/merchandise*") || Request::is("$prefix/merchandise/*"); @endphp
                <li class="{{ $wsActive ? 'mm-active' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow waves-effect {{ $wsActive ? 'mm-active' : '' }}" aria-expanded="{{ $wsActive ? 'true' : 'false' }}">
                        <i class="bx bx-cog"></i>
                        <span>Merchandise</span>
                    </a>
                    <ul class="sub-menu {{ $wsActive ? 'mm-show' : '' }}" aria-expanded="{{ $wsActive ? 'true' : 'false' }}">
                        <li><a href="{{ url("$prefix/merchandise-category") }}" class="{{ Request::is("$prefix/merchandise-category*") ? 'active' : '' }}"><i class="bx bx-list-ol"></i> Category</a></li>
                        <li><a href="{{ url("$prefix/merchandise-item") }}" class="{{ Request::is("$prefix/merchandise-item*") ? 'active' : '' }}"><i class="bx bx-list-ol"></i> Merchandise</a></li>
                    </ul>
                </li>

                @php $wsActive = Request::is("$prefix/machinery*") || Request::is("$prefix/machinery/*"); @endphp
                <li class="{{ $wsActive ? 'mm-active' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow waves-effect {{ $wsActive ? 'mm-active' : '' }}" aria-expanded="{{ $wsActive ? 'true' : 'false' }}">
                        <i class="bx bx-cog"></i>
                        <span>Machinery</span>
                    </a>
                    <ul class="sub-menu {{ $wsActive ? 'mm-show' : '' }}" aria-expanded="{{ $wsActive ? 'true' : 'false' }}">
                        <li><a href="{{ url("$prefix/machinery-category") }}" class="{{ Request::is("$prefix/machinery-category*") ? 'active' : '' }}"><i class="bx bx-list-ol"></i> Category</a></li>
                        <li><a href="{{ url("$prefix/machinery-item") }}" class="{{ Request::is("$prefix/machinery-item*") ? 'active' : '' }}"><i class="bx bx-list-ol"></i> Branch</a></li>
                    </ul>
                </li>

                @php $wsActive = Request::is("$prefix/product*") || Request::is("$prefix/product/*"); @endphp
                <li class="{{ $wsActive ? 'mm-active' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow waves-effect {{ $wsActive ? 'mm-active' : '' }}" aria-expanded="{{ $wsActive ? 'true' : 'false' }}">
                        <i class="bx bxl-product-hunt"></i>
                        <span>Product</span>
                    </a>
                    <ul class="sub-menu {{ $wsActive ? 'mm-show' : '' }}" aria-expanded="{{ $wsActive ? 'true' : 'false' }}">
                        <li><a href="{{ url("$prefix/product-category") }}" class="{{ Request::is("$prefix/product-category*") ? 'active' : '' }}"><i class="bx bx-list-ol"></i> Category</a></li>
                        <li><a href="{{ url("$prefix/product-item") }}" class="{{ Request::is("$prefix/product-item*") ? 'active' : '' }}"><i class="bx bx-list-ol"></i> Product</a></li>
                    </ul>
                </li>

                <li class="{{ Request::is("$prefix/notification*") ? 'mm-active' : '' }}">
                    <a href="{{ url("$prefix/notification") }}" class="waves-effect {{ Request::is("$prefix/notification*") ? 'active' : '' }}">
                        <i class="bx bx-notification"></i><span>Notification</span>
                    </a>
                </li>
                <li class="menu-title" key="t-menu">Administrator</li>
                <li class="{{ Request::is("$prefix/admin*") ? 'mm-active' : '' }}">
                    <a href="{{ url("$prefix/admin") }}" class="waves-effect {{ Request::is("$prefix/admin*") ? 'active' : '' }}">
                        <i class="bx bx-user"></i><span>Admin</span>
                    </a>
                </li>
            </ul>

        </div>


        <!-- Sidebar -->
    </div>
</div>
