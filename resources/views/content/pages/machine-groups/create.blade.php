@extends('layouts/layoutMaster')
@section('title','เพิ่มกลุ่มตู้')
@section('content')
<form action="{{ route('machine-groups.store') }}" method="POST">@csrf @include('content.pages.machine-groups._form')</form>
@endsection
