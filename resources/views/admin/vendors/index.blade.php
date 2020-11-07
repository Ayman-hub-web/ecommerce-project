@extends('layouts.admin')

@section('content')
@include('admin.includes.alerts.success')
@include('admin.includes.alerts.errors')
@error('name')
    <span class="text-danger">{{$message}}</span>
@enderror
<h1 class="text-center mt-2">المتاجر  </h1>
    <a href="{{route('admin.vendors.create')}}" clas="link mt-2 mb-2">اضافة متجر </a>
    <div>

    </div>
    <table id="dtDynamicVerticalScrollExample" class="table table-striped table-bordered table-sm" cellspacing="0"
  width="100%">
            <thead>
                <tr>القسم 
                <th scope="col">الاسم </th>
                <th scope="col">اللوجو </th>
                <th scope="col">الهاتف </th>
                <th scope="col">الحالة </th>
                <th scope="col">القسم الرئيسي</th>
                <th scope="col">الاجراءات</th>
                </tr>
            </thead>
            <tbody>
            @isset($vendors)
            @foreach($vendors as $vendor)
                <tr>
                    <th scope="row">{{$vendor->name}}</th>
                    <td><img src="{{$vendor->logo}}" width="70" height="50"></td>
                    <td>{{$vendor->mobile}}</td>
                    <td>{{$vendor->getActive()}}</td>
                    <td>{{$vendor->category->name}}</td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <a href="{{route('admin.vendors.edit', $vendor->id)}}"
                            class="btn btn-outline-primary btn-min-width box-shadow-3 mr-1 mb-1">تعديل</a>

                            <a href="{{route('admin.vendors.delete', $vendor->id)}}"
                            class="btn btn-outline-danger btn-min-width box-shadow-3 mr-1 mb-1">حذف</a>

                            <a href="{{route('admin.vendors.changeStatus', $vendor->id)}}"
                            class="btn btn-outline-warning btn-min-width box-shadow-3 mr-1 mb-1">@if($vendor->active == 1)  الغاء تفعيل @else   تفعيل @endif</a>
                        </div>
                    </td>
                </tr>
            @endforeach
            @endisset
            </tbody>
    </table>
@endsection