@extends('layouts/layoutMaster')
@section('title','แก้ไขกลุ่มตู้')
@section('content')
<form action="{{ route('machine-groups.update',$machineGroup) }}" method="POST">@csrf @method('PUT') @include('content.pages.machine-groups._form')</form>
@endsection
