@extends('panel.layouts.master')
@section('title', 'گزارشات روزانه')
@section('content')
    {{--  items Modal  --}}
    <div class="modal fade" id="itemsModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemsModalLabel">مشاهده وظایف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <ol></ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>
    {{--  end items Modal  --}}
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">گزارشات روزانه</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex justify-content-end">
                                @can('reports-create')
                                    <a href="{{ route('reports.create') }}" class="btn btn-primary">
                                        <i class="fa fa-plus mr-2"></i>
                                        ثبت گزارش
                                    </a>
                                @endcan
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center" style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        @canany(['admin','ceo','it-manager'])
                                            <th>همکار</th>
                                        @endcanany
                                        <th>تاریخ گزارش</th>
                                        <th>تاریخ ثبت</th>
                                        <th>مشاهده</th>
                                        @can('reports-edit')
                                            <th>ویرایش</th>
                                        @endcan
                                        @can('reports-delete')
                                            <th>حذف</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($reports as $key => $report)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            @canany(['admin','ceo','it-manager'])
                                                <td>{{ $report->user->fullName() }}</td>
                                            @endcanany
                                            <td>{{ verta($report->date)->format('Y/m/d') }}</td>
                                            <td>{{ verta($report->created_at)->format('H:i - Y/m/d') }}</td>
                                            <td>
                                                <button class="btn btn-info btn-floating btn_show" data-bs-toggle="modal" data-bs-target="#itemsModal" data-id="{{ $report->id }}">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            </td>
                                            @can('reports-edit')
                                                @php $isEditable = verta($report->created_at)->formatDate() == verta(now())->formatDate() @endphp
                                                <td>
                                                    <a class="btn btn-warning btn-floating {{ $isEditable ? '' : 'disabled' }}"
                                                       href="{{ route('reports.edit', $report->id) }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                            @endcan
                                            @can('reports-delete')
                                                <td>
                                                    <button class="btn btn-danger btn-floating trashRow"
                                                            data-url="{{ route('reports.destroy',$report->id) }}"
                                                            data-id="{{ $report->id }}" {{ $isEditable ? '' : 'disabled' }}>
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
                            <div class="d-flex justify-content-center">{{ $reports->appends(request()->all())->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('.btn_show').on('click', function () {
                $('#itemsModal .modal-body ol').html('')

                let id = $(this).data('id')
                $.ajax({
                    url: '/panel/get-report-items/' + id,
                    type: 'get',
                    success: function (res) {
                        $.each(res.data, function (i, item) {
                            $('#itemsModal .modal-body ol').append(`<li>${item}</li>`);
                        })
                    }
                })
            })
        })
    </script>
@endsection

