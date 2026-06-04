@php
    $breadName = 'Dashboard';
    $nav_name = '';
    $nav_last = end($navs);
    if (@$navs[0] != null) {
        $nav_name = $nav_last['name'];
    }
@endphp
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ @$nav_name }}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{url("webpanel")}}">Dashboards</a></li>
                    @if (@$navs)
                        @php
                            $nav_end = key($navs);
                        @endphp
                        @foreach ($navs as $index => $nav)
                            <li class="breadcrumb-item  @if ($index == $nav_end) active @endif">
                                @if(@$nav['url'] != null && @$nav['url'] != "javascript:void(0)")
                                <a href="{{url($nav['url'])}}">{{ @$nav['name'] }}</a>
                                @else
                                {{ @$nav['name'] }}
                                @endif
                            </li>
                        @endforeach
                    @endif
                   
                    
                </ol>
            </div>

        </div>
    </div>
</div>
