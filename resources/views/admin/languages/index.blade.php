@extends('layouts.admin')

@section('content')
@include('admin.includes.alerts.success')
@include('admin.includes.alerts.errors')
@error('name')
    <span class="text-danger">{{$message}}</span>
@enderror
<h1 class="text-center mt-2">لغات الموقع</h1>
    <a href="{{route('admin.languages.create')}}" clas="link mt-2 mb-2">اضافة لغة</a>
    <div>

    </div>
        <table class="table">
            <thead>
                <tr>
                <th scope="col">الاسم</th>
                <th scope="col">الاختصار</th>
                <th scope="col">اتجاه</th>
                <th scope="col">الحالة</th>
                <th scope="col">الاجراءات</th>
                </tr>
            </thead>
            <tbody>
            @isset($languages)
            @foreach($languages as $language)
                <tr>
                    <th scope="row">{{$language->name}}</th>
                    <td>{{$language->abbr}}</td>
                    <td>{{$language->direction}}</td>
                    <td>{{$language->active}}</td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <a href="{{route('admin.languages.edit', $language->id)}}"
                            class="btn btn-outline-primary btn-min-width box-shadow-3 mr-1 mb-1">تعديل</a>

                            <a href="{{route('admin.languages.delete', $language->id)}}"
                            class="btn btn-outline-danger btn-min-width box-shadow-3 mr-1 mb-1">حذف</a>
                        </div>
                    </td>
                </tr>
            @endforeach
            @endisset
            </tbody>
    </table>
@endsection