@extends('panel.layouts.master')
@section('title', 'کاربران')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">کاربران</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex justify-content-end">
                                @can('users-create')
                                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                                        <i class="fa fa-plus mr-2"></i>
                                        ایجاد کاربر
                                    </a>
                                @endcan
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center"
                                       style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>نام</th>
                                        <th>نام خانوادگی</th>
                                        <th>شماره موبایل</th>
                                        <th>نقش</th>
                                        <th>تاریخ ایجاد</th>
                                        @can('users-edit')
                                            <th>ویرایش</th>
                                        @endcan
                                        @can('users-delete')
                                            <th>حذف</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($users as $key => $user)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->family }}</td>
                                            <td>{{ $user->phone }}</td>
                                            <td>{{ $user->role->label }}</td>
                                            <td>{{ verta($user->created_at)->format('H:i - Y/m/d') }}</td>
                                            @can('users-edit')
                                                <td>
                                                    <a class="btn btn-warning btn-floating {{ !auth()->user()->isSuperuser() && ($user->role->name == 'admin' && $user->id != auth()->id()) ? 'disabled' : ''}}"
                                                       href="{{ route('users.edit', $user->id) }}" >
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                            @endcan
                                            @can('users-delete')
                                                <td>
                                                    <button class="btn btn-danger btn-floating trashRow"
                                                            data-url="{{ route('users.destroy',$user->id) }}"
                                                            data-id="{{ $user->id }}" {{ (auth()->id() == $user->id)|| !auth()->user()->isSuperuser() && ($user->role->name == 'admin' && $user->id != auth()->id()) ? 'disabled' : ''}}>
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center">{{ $users->appends(request()->all())->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



