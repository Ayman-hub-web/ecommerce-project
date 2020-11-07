@extends('layouts.admin')

@section('content')
@include('admin.includes.alerts.success')
@include('admin.includes.alerts.errors')
@error('name')
    <span class="text-danger">{{$message}}</span>
@enderror
<h1 class="text-center mt-2">اقسام الموقع </h1>
    <a href="{{route('admin.main_categories.create')}}" clas="link mt-2 mb-2">اضافة قسم </a>
    <div>

    </div>
    <table id="dtDynamicVerticalScrollExample" class="table table-striped table-bordered table-sm" cellspacing="0"
  width="100%">
            <thead>
                <tr>القسم 
                <th scope="col">القسم</th>
                <th scope="col">اللغة</th>
                <th scope="col">الحالة</th>
                <th scope="col">الصورة</th>
                <th scope="col">الاجراءات</th>
                </tr>
            </thead>
            <tbody>
            @isset($main_categories)
            @foreach($main_categories as $main_category)
                <tr>
                    <th scope="row">{{$main_category->name}}</th>
                    <td>{{$main_category->translation_lang}}</td>
                    <td>{{$main_category->getActive()}}</td>
                    <td><img src="{{$main_category->photo}}" width="70" height="50"></td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <a href="{{route('admin.main_categories.edit', $main_category->id)}}"
                            class="btn btn-outline-primary btn-min-width box-shadow-3 mr-1 mb-1">تعديل</a>

                            <a href="{{route('admin.main_categories.delete', $main_category->id)}}"
                            class="btn btn-outline-danger btn-min-width box-shadow-3 mr-1 mb-1">حذف</a>

                            <a href="{{route('admin.main_categories.changeStatus', $main_category->id)}}"
                            class="btn btn-outline-warning btn-min-width box-shadow-3 mr-1 mb-1">@if($main_category->active == 1)  الغاء تفعيل @else   تفعيل @endif</a>
                        </div>
                    </td>
                </tr>
            @endforeach
            @endisset
            </tbody>
    </table>
@endsection