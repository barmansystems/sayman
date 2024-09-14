@extends('panel.layouts.master')
@section('title', 'خروج')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">خروج</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex justify-content-end">
                                @can('output-reports-create')
                                    <a href="{{ route('inventory-reports.create', ['type' => 'output', 'warehouse_id' => $warehouse_id]) }}"
                                       class="btn btn-primary">
                                        <i class="fa fa-plus me-2"></i>
                                        ثبت خروجی
                                    </a>
                                @endcan
                            </div>
                            <form action="{{ route('inventory-reports.search') }}" method="get" id="search_form">
                                <input type="hidden" name="warehouse_id" value="{{ $warehouse_id }}">
                                <input type="hidden" name="type" value="{{ request()->type }}">
                            </form>
                            <div class="row mb-3">
                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12">
                                    <select name="inventory_id" form="search_form" class="form-control" data-toggle="select2">
                                        <option value="all">فیلتر بر اساس کالا (همه)</option>
                                        @foreach(\App\Models\Inventory::where('warehouse_id',$warehouse_id)->pluck('title','id') as $id => $title)
                                            <option value="{{ $id }}" {{ request()->inventory_id == $id ? 'selected' : '' }}>{{ $title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-2 xl-lg-2 col-md-3 col-sm-12">
                                    <button type="submit" class="btn btn-primary" form="search_form">جستجو</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>تحویل گیرنده</th>
                                        <th>سفارش</th>
                                        <th>تاریخ خروج</th>
                                        <th>تاریخ ثبت</th>
                                        <th>خروج انبار</th>
                                        @can('output-reports-edit')
                                            <th>ویرایش</th>
                                        @endcan
                                        @can('output-reports-delete')
                                            <th>حذف</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($reports as $key => $item)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td><strong>{{ $item->person }}</strong></td>
                                            <td>
                                                @if($item->invoice)
                                                    <strong><u><a href="{{ route('invoices.show', [$item->invoice->id]) }}" class="text-primary" target="_blank">{{ $item->invoice_id }}</a></u></strong>
                                                @else
                                                    ---
                                                @endif
                                            </td>
                                            <td>{{ verta($item->date)->format('Y/m/d') }}</td>
                                            <td>{{ verta($item->created_at)->format('H:i - Y/m/d') }}</td>
                                            <td>
                                                <a class="btn btn-info btn-floating"
                                                   href="{{ route('inventory-reports.show', $item) }}">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                            @can('output-reports-edit')
                                                <td>
                                                    <a class="btn btn-warning btn-floating"
                                                       href="{{ route('inventory-reports.edit', ['inventory_report' => $item->id, 'type' => 'output', 'warehouse_id' => $warehouse_id]) }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                            @endcan
                                            @can('output-reports-delete')
                                                <td>
                                                    <button class="btn btn-danger btn-floating trashRow"
                                                            data-url="{{ route('inventory-reports.destroy',$item->id) }}"
                                                            data-id="{{ $item->id }}">
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
                            <div class="d-flex justify-content-center">{{ $reports->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

