@extends('panel.layouts.master')
@section('title', 'ویرایش سفارش')
@section('styles')
    <style>
        #products_table input, #products_table select {
            width: auto;
        }

        #other_products_table input, #other_products_table select {
            width: auto;
        }
    </style>
@endsection
@section('content')
    {{--  discount Modal  --}}
    <div class="modal fade" id="discountModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="discountModalLabel">اعمال کد تخفیف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="code" class="col-form-label"> کد تخفیف:<span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="code">
                            <div class="invalid-feedback d-flex" id="error_div"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                    <button type="button" class="btn btn-primary" id="btn_apply_discount">اعمال</button>
                </div>
            </div>
        </div>
    </div>
    {{--  end discount Modal  --}}
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">ویرایش سفارش</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex justify-content-between align-items-center mb-5">
                                <div class="w-100">
                                    @if($invoice->status != 'invoiced')
                                        <div class="col-12 mb-4 text-center mt-5">
                                            <h4>درخواست برای</h4>
                                        </div>
                                        <div class="btn-group w-100" role="group">
                                            <input type="radio" id="req_for1" name="req_for" class="btn-check" value="pre-invoice" form="invoice_form" {{ $invoice->req_for == 'pre-invoice' && old('req_for') == null || old('req_for') == 'pre-invoice' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary justify-content-center" for="req_for1">پیش فاکتور</label>

                                            <input type="radio" id="req_for2" name="req_for" class="btn-check" value="invoice" form="invoice_form" {{ $invoice->req_for == 'invoice' || old('req_for') == 'invoice' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary justify-content-center" for="req_for2">فاکتور</label>

                                            <input type="radio" id="req_for3" name="req_for" class="btn-check" value="amani-invoice" form="invoice_form" {{ $invoice->req_for == 'amani-invoice' || old('req_for') == 'amani-invoice' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary justify-content-center" for="req_for3">فاکتور امانی</label>
                                        </div>
                                    @else
                                        <input type="hidden" name="req_for" value="{{ $invoice->req_for }}" form="invoice_form">
                                    @endif
                                    <input type="hidden" name="type" value="official" form="invoice_form">
                                </div>
                            </div>
                            <form action="{{ route('invoices.update', $invoice->id) }}" method="post" id="invoice_form" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <div class="row mb-4">
                                    <div class="col-12 mb-4 text-center">
                                        <h4>مشخصات خریدار</h4>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="buyer_name">نام شخص حقیقی/حقوقی <span class="text-danger">*</span></label>
                                        <select name="buyer_name" id="buyer_name" class="form-control" data-toggle="select2">
                                            <option value="" disabled selected>انتخاب کنید...</option>
                                            @foreach(\App\Models\Customer::all(['id','name','code']) as $customer)
                                                <option value="{{ $customer->id }}" {{ $invoice->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->code.' - '.$customer->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('buyer_name')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="economical_number">شماره اقتصادی
                                            @can('system-user')
                                                <span class="text-danger">*</span>
                                            @endcan
                                        </label>
                                        <input type="text" name="economical_number" class="form-control" id="economical_number" value="{{ $invoice->economical_number }}">
                                        @error('economical_number')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="national_number">شماره ثبت/ملی<span class="text-danger">*</span></label>
                                        <input type="text" name="national_number" class="form-control" id="national_number" value="{{ $invoice->national_number }}">
                                        @error('national_number')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="need_no">شماره نیاز</label>
                                        <input type="text" name="need_no" class="form-control" id="need_no" value="{{ $invoice->need_no }}">
                                        @error('need_no')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="postal_code">کد پستی<span class="text-danger">*</span></label>
                                        <input type="text" name="postal_code" class="form-control" id="postal_code" value="{{ $invoice->postal_code }}">
                                        @error('postal_code')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="phone">شماره تماس<span class="text-danger">*</span></label>
                                        <input type="text" name="phone" class="form-control" id="phone" value="{{ $invoice->phone }}">
                                        @error('phone')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="province">استان <span class="text-danger">*</span></label>
                                        <select name="province" id="province" class="form-control" data-toggle="select2">
                                            @foreach(\App\Models\Province::all() as $province)
                                                <option value="{{ $province->name }}" {{ $invoice->province == $province->name ? 'selected' : '' }}>{{ $province->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('province')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="city">شهر<span class="text-danger">*</span></label>
                                        <input type="text" name="city" class="form-control" id="city" value="{{ $invoice->city }}">
                                        @error('city')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="address">نشانی<span class="text-danger">*</span></label>
                                        <textarea name="address" id="address" class="form-control">{{ $invoice->address }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="description">توضیحات</label>
                                        <textarea name="description" id="description" class="form-control">{{ $invoice->description }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="payment_doc">رسید پرداخت</label>
                                        <input type="file" name="payment_doc" id="payment_doc" class="form-control" accept="application/pdf,image/png,image/jpg,image/jpeg">
                                        @if($invoice->payment_doc)
                                            <a href="{{ $invoice->payment_doc }}" target="_blank">دانلود پیش فاکتور</a>
                                        @endif
                                        @error('payment_doc')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @can('accountant')
                                        <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                            <label for="status">وضعیت <span class="text-danger">*</span></label>
                                            <select name="status" id="status" class="form-control" data-toggle="select2">
                                                <option value="order" {{ $invoice->status == 'order' ? 'selected' : '' }}>{{ \App\Models\Invoice::STATUS['order'] }}</option>
                                                <option value="pending" {{ $invoice->status == 'pending' ? 'selected' : '' }}>{{ \App\Models\Invoice::STATUS['pending'] }}</option>
                                                <option value="invoiced" {{ $invoice->status == 'invoiced' ? 'selected' : '' }}>{{ \App\Models\Invoice::STATUS['invoiced'] }}</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @else
                                        <input type="hidden" name="status" value="{{ $invoice->status }}">
                                    @endcan

                                    @if($invoice->status != 'invoiced' || \Illuminate\Support\Facades\Gate::allows('sales-manager'))
                                        <div class="col-12 mb-4 mt-2 text-center">
                                            <hr>
                                            <h4>مشخصات کالا یا خدمات مورد معامله</h4>
                                        </div>
                                        <div class="col-12 mt-2 text-center">
                                            <h5>محصولات شرکت</h5>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <div class="d-flex justify-content-between mb-3">
                                                <button class="btn btn-outline-success" type="button" id="btn_add"><i
                                                        class="fa fa-plus mr-2"></i> افزودن کالا
                                                </button>
                                            </div>
                                            <div class="overflow-auto">
                                                <table class="table table-bordered table-striped text-center" id="products_table">
                                                    <thead>
                                                    <tr>
                                                        <th>کالا</th>
                                                        <th>رنگ</th>
                                                        <th>تعداد</th>
                                                        <th>واحد اندازه گیری</th>
                                                        <th>مبلغ واحد</th>
                                                        <th>مبلغ کل</th>
                                                        <th>مبلغ تخفیف</th>
                                                        <th>مبلغ اضافات</th>
                                                        <th>مبلغ کل پس از تخفیف و اضافات</th>
                                                        <th>جمع مالیات و عوارض</th>
                                                        <th>خالص فاکتور</th>
                                                        <th>اعمال تخفیف</th>
                                                        <th>حذف</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if($invoice->products()->exists())
                                                        @foreach($invoice->products as $item)
                                                            @php
                                                                $usedCoupon = DB::table('coupon_invoice')->where([
                                                                    'product_id' => $item->pivot->product_id,
                                                                    'invoice_id' => $invoice->id,
                                                                ])->first();

                                                                if ($usedCoupon){
                                                                    $coupon = \App\Models\Coupon::find($usedCoupon->coupon_id);
                                                                    $discount_amount = $item->pivot->total_price * ($coupon->amount_pc / 100);
                                                                }else{
                                                                    $discount_amount = 0;
                                                                }
                                                            @endphp
                                                            <tr>
                                                                <td>
                                                                    <select class="form-control" name="products[]" data-toggle="select2" required>
                                                                        <option value="" disabled selected>..................... انتخاب
                                                                            کنید .....................
                                                                        </option>
                                                                        @foreach(\App\Models\Product::all(['id','title','code']) as $product)
                                                                            <option value="{{ $product->id }}" {{ $item->pivot->product_id == $product->id ? 'selected' : '' }}>{{ $product->code.' - '.$product->title }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select class="form-control" name="colors[]">
                                                                        @foreach(\App\Models\Product::COLORS as $key => $value)
                                                                            <option value="{{ $key }}" {{ $item->pivot->color == $key ? 'selected' : '' }}>{{ $value }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="counts[]" class="form-control" min="1" value="{{ $item->pivot->count }}" required>
                                                                </td>
                                                                <td>
                                                                    <select class="form-control" name="units[]">
                                                                        <option value="{{ $item->pivot->unit }}">{{ \App\Models\Product::UNITS[$item->pivot->unit] }}</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="prices[]" class="form-control" min="0" value="{{ $item->pivot->price }}" readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="total_prices[]" class="form-control" min="0" value="{{ $item->pivot->total_price }}" readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="discount_amounts[]" class="form-control" min="0" value="{{ $discount_amount }}" readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="extra_amounts[]" class="form-control" min="0" value="{{ $item->pivot->extra_amount }}" readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="total_prices_with_off[]" class="form-control" min="0" value="{{ $item->pivot->total_price - ($item->pivot->extra_amount + $discount_amount) }}" readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="taxes[]" class="form-control" min="0" value="{{ $item->pivot->tax }}" readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="invoice_nets[]" class="form-control" min="0" value="{{ $item->pivot->invoice_net }}" readonly>
                                                                </td>
                                                                <td>
                                                                    <button type="button" class="btn btn-primary btn-floating btn_discount" data-bs-toggle="modal" data-bs-target="#discountModal">
                                                                        <i class="fa fa-percent"></i>
                                                                    </button>
                                                                </td>
                                                                <td>
                                                                    <button class="btn btn-danger btn-floating btn_remove" type="button">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-4 text-center">
                                            <h5>محصولات دیگر</h5>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <div class="d-flex justify-content-between mb-3">
                                                <button class="btn btn-outline-success" type="button" id="btn_other_add"><i
                                                        class="fa fa-plus mr-2"></i> افزودن کالا
                                                </button>
                                            </div>
                                            <div class="overflow-auto">
                                                <table class="table table-bordered table-striped text-center" id="other_products_table">
                                                    <thead>
                                                    <tr>
                                                        <th>کالا</th>
                                                        <th>رنگ</th>
                                                        <th>تعداد</th>
                                                        <th>واحد اندازه گیری</th>
                                                        <th>مبلغ واحد</th>
                                                        <th>مبلغ کل</th>
                                                        <th>مبلغ تخفیف</th>
                                                        <th>مبلغ اضافات</th>
                                                        <th>مبلغ کل پس از تخفیف و اضافات</th>
                                                        <th>جمع مالیات و عوارض</th>
                                                        <th>خالص فاکتور</th>
                                                        <th>حذف</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if($invoice->other_products()->exists())
                                                        @foreach($invoice->other_products as $product)
                                                            <tr>
                                                                <td>
                                                                    <input type="text" name="other_products[]" class="form-control"
                                                                           value="{{ $product->title }}" placeholder="عنوان کالا"
                                                                           required>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="other_colors[]" class="form-control"
                                                                           value="{{ $product->color }}" placeholder="نام رنگ" required>
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="other_counts[]" class="form-control"
                                                                           min="1" value="{{ $product->count }}" required>
                                                                </td>
                                                                <td>
                                                                    <select class="form-control" name="other_units[]">
                                                                        <option value="number">عدد</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="other_prices[]" class="form-control"
                                                                           min="0" value="{{ $product->price }}" required>
                                                                    <span class="price_with_grouping text-primary"></span>
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="other_total_prices[]"
                                                                           class="form-control" min="0"
                                                                           value="{{ $product->total_price }}" readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="other_discount_amounts[]"
                                                                           class="form-control" min="0"
                                                                           value="{{ $product->discount_amount }}" required>
                                                                    <span class="price_with_grouping text-primary"></span>
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="other_extra_amounts[]"
                                                                           class="form-control" min="0"
                                                                           value="{{ $product->extra_amount }}" readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="other_total_prices_with_off[]"
                                                                           class="form-control" min="0"
                                                                           value="{{ $product->total_price - ($product->extra_amount + $product->discount_amount) }}"
                                                                           readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="other_taxes[]" class="form-control"
                                                                           min="0" value="{{ $product->tax }}" readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="other_invoice_nets[]"
                                                                           class="form-control" min="0"
                                                                           value="{{ $product->invoice_net }}" readonly>
                                                                </td>
                                                                <td>
                                                                    <button class="btn btn-danger btn-floating btn_remove"
                                                                            type="button"><i class="fa fa-trash"></i></button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-2 mt-2 text-center">
                                            <hr>
                                            <h4>تخفیف نهایی</h4>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <label for="final_discount">مبلغ تخفیف</label>
                                                <input type="text" class="form-control" name="final_discount" id="final_discount" value="{{ $invoice->discount }}" required>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <button class="btn btn-primary" type="submit" id="btn_form">ثبت فرم</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        var products = [];
        var colors = [];

        var form = document.getElementById('invoice_form');
        form.addEventListener('keypress', function (e) {
            if (e.keyCode === 13) {
                e.preventDefault();
            }
        })

        @foreach(\App\Models\Product::all(['id','title','code']) as $product)
        products.push({
            "id": "{{ $product->id }}",
            "title": "{{ $product->title }}",
            "code": "{{ $product->code }}",
        })
        @endforeach
        @foreach(\App\Models\Product::COLORS as $key => $value)
        colors.push({
            "key": "{{ $key }}",
            "value": "{{ $value }}",
        })
        @endforeach

        var products_options_html = '';
        var colors_options_html = '';

        $.each(products, function (i, item) {
            products_options_html += `<option value="${item.id}">${item.code} - ${item.title}</option>`
        })

        $.each(colors, function (i, item) {
            colors_options_html += `<option value="${item.key}">${item.value}</option>`
        })

        $(document).ready(function () {
            // add artin property
            $('#btn_add').on('click', function () {
                $('#products_table tbody').append(`
                <tr>
                <td>
                    <select class="form-control" name="products[]" data-toggle="select2" style="width: 300px !important;" required>
                        <option value="" disabled selected>..................... انتخاب کنید .....................</option>
                        ${products_options_html}
                    </select>
                </td>
                <td>
                    <select class="form-control" name="colors[]" required>
                        ${colors_options_html}
                    </select>
                </td>
                <td>
                    <input type="number" name="counts[]" class="form-control" min="1" value="1" required>
                </td>
                <td>
                    <select class="form-control" name="units[]">
                        <option value="number">عدد</option>
                    </select>
                </td>
                <td>
                    <input type="number" name="prices[]" class="form-control" min="0" value="0" readonly>
                </td>
                <td>
                    <input type="number" name="total_prices[]" class="form-control" min="0" value="0" readonly>
                </td>
                <td>
                    <input type="number" name="discount_amounts[]" class="form-control" min="0" value="0" readonly>
                </td>
                <td>
                    <input type="number" name="extra_amounts[]" class="form-control" min="0" value="0" readonly>
                </td>
                <td>
                    <input type="number" name="total_prices_with_off[]" class="form-control" min="0" value="0" readonly>
                </td>
                <td>
                    <input type="number" name="taxes[]" class="form-control" min="0" value="0" readonly>
                </td>
                <td>
                    <input type="number" name="invoice_nets[]" class="form-control" min="0" value="0" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-primary btn-floating btn_discount" data-bs-toggle="modal" data-bs-target="#discountModal">
                        <i class="fa fa-percent"></i>
                    </button>
                </td>
                <td>
                    <button class="btn btn-danger btn-floating btn_remove" type="button"><i class="fa fa-trash"></i></button>
                </td>
            </tr>

`);
                $('[data-toggle="select2"]').select2();

            })
            // end add artin property

            // add other property
            $('#btn_other_add').on('click', function () {
                $('#other_products_table tbody').append(`
                <tr>
                <td>
                    <input type="text" class="form-control" name="other_products[]" placeholder="عنوان کالا" required>
                </td>
                <td>
                    <input type="text" class="form-control" name="other_colors[]" placeholder="نام رنگ" required>
                </td>
                <td>
                    <input type="number" name="other_counts[]" class="form-control" min="1" value="1" required>
                </td>
                <td>
                    <select class="form-control" name="other_units[]">
                        <option value="number">عدد</option>
                    </select>
                </td>
                <td>
                    <input type="number" name="other_prices[]" class="form-control" min="0" value="0" required>
                    <span class="price_with_grouping text-primary"></span>
                </td>
                <td>
                    <input type="number" name="other_total_prices[]" class="form-control" min="0" value="0" readonly>
                </td>
                <td>
                    <input type="number" name="other_discount_amounts[]" class="form-control" min="0" value="0" required>
                    <span class="price_with_grouping text-primary"></span>
                </td>
                <td>
                    <input type="number" name="other_extra_amounts[]" class="form-control" min="0" value="0" readonly>
                </td>
                <td>
                    <input type="number" name="other_total_prices_with_off[]" class="form-control" min="0" value="0" readonly>
                </td>
                <td>
                    <input type="number" name="other_taxes[]" class="form-control" min="0" value="0" readonly>
                </td>
                <td>
                    <input type="number" name="other_invoice_nets[]" class="form-control" min="0" value="0" readonly>
                </td>
                <td>
                    <button class="btn btn-danger btn-floating btn_remove" type="button"><i class="fa fa-trash"></i></button>
                </td>
            </tr>

`);
            })
            // end add other property

            // remove property
            $(document).on('click', '.btn_remove', function () {
                $(this).parent().parent().remove();
            })
            // end remove property

            // calc the product invoice
            $(document).on('change', '#products_table select[name="products[]"]', function () {
                $('#btn_form').attr('disabled', 'disabled').text('درحال محاسبه...');
                CalcProductInvoice(this)
            })
            $(document).on('keyup', '#products_table input[name="counts[]"]', function (e) {
                if (e.originalEvent.explicitOriginalTarget.defaultValue != this.value) {
                    $('#btn_form').attr('disabled', 'disabled').text('درحال محاسبه...');
                }
            })
            $(document).on('change', '#products_table input[name="counts[]"]', function () {
                $('#btn_form').attr('disabled', 'disabled').text('درحال محاسبه...');
                CalcProductInvoice(this)
            })
            $(document).on('keyup', '#other_products_table input[name="other_counts[]"]', function (e) {
                if (e.originalEvent.explicitOriginalTarget.defaultValue != this.value) {
                    $('#btn_form').attr('disabled', 'disabled').text('درحال محاسبه...');
                }
            })
            $(document).on('change', '#other_products_table input[name="other_counts[]"]', function () {
                $('#btn_form').attr('disabled', 'disabled').text('درحال محاسبه...');
                CalcOtherProductInvoice(this)
            })
            $(document).on('keyup', '#other_products_table input[name="other_prices[]"]', function (e) {
                if (e.originalEvent.explicitOriginalTarget.defaultValue != this.value) {
                    $('#btn_form').attr('disabled', 'disabled').text('درحال محاسبه...');
                }
            })
            $(document).on('change', '#other_products_table input[name="other_prices[]"]', function () {
                CalcOtherProductInvoice(this)
            })
            $(document).on('keyup', '#other_products_table input[name="other_discount_amounts[]"]', function (e) {
                if (e.originalEvent.explicitOriginalTarget.defaultValue != this.value) {
                    $('#btn_form').attr('disabled', 'disabled').text('درحال محاسبه...');
                }
            })
            $(document).on('change', '#other_products_table input[name="other_discount_amounts[]"]', function () {
                CalcOtherProductInvoice(this)
            })
            // end calc the product invoice

            // get customer info
            $(document).on('change', 'select[name="buyer_name"]', function () {
                let customer_id = this.value;

                $.ajax({
                    url: '/panel/get-customer-info/' + customer_id,
                    type: 'post',
                    success: function (res) {
                        $('#economical_number').val(res.data.economical_number)
                        $('#national_number').val(res.data.national_number)
                        $('#postal_code').val(res.data.postal_code)
                        $('#phone').val(res.data.phone1)
                        $('#address').val(res.data.address1)
                        $('#province').val(res.data.province).trigger('change');
                        $('#city').val(res.data.city)
                    }
                })
            })
            // end get customer info
        })

        function CalcProductInvoice(changeable) {
            var index = $(changeable).parent().parent().index()
            let product_id = $('#products_table select[name="products[]"]')[index].value;
            let count = $('#products_table input[name="counts[]"]')[index].value;

            $.ajax({
                url: "{{ route('calcProductsInvoice') }}",
                type: 'post',
                data: {
                    'product_id': product_id,
                    'count': count,
                },
                success: function (res) {
                    $('#products_table input[name="prices[]"]')[index].value = res.data.price;
                    $('#products_table input[name="total_prices[]"]')[index].value = res.data.total_price;
                    $('#products_table input[name="discount_amounts[]"]')[index].value = res.data.discount_amount;
                    $('#products_table input[name="extra_amounts[]"]')[index].value = res.data.extra_amount;
                    $('#products_table input[name="total_prices_with_off[]"]')[index].value = res.data.total_price_with_off;
                    $('#products_table input[name="taxes[]"]')[index].value = res.data.tax;
                    $('#products_table input[name="invoice_nets[]"]')[index].value = res.data.invoice_net;

                    $('#btn_form').removeAttr('disabled').text('ثبت فرم');
                },
                error: function (request, status, error) {
                    //
                }
            })
        }

        function CalcOtherProductInvoice(changeable) {
            var index = $(changeable).parent().parent().index()
            let count = $('#other_products_table input[name="other_counts[]"]')[index].value;
            let price = $('#other_products_table input[name="other_prices[]"]')[index].value;
            let discount_amount = $('#other_products_table input[name="other_discount_amounts[]"]')[index].value;

            // thousands grouping
            $($('#other_products_table input[name="other_prices[]"]')[index]).siblings()[0].innerText = price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            $($('#other_products_table input[name="other_discount_amounts[]"]')[index]).siblings()[0].innerText = discount_amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            $.ajax({
                url: "{{ route('calcOtherProductsInvoice') }}",
                type: 'post',
                data: {
                    'price': price,
                    'count': count,
                    'discount_amount': discount_amount,
                },
                success: function (res) {
                    $('#other_products_table input[name="other_prices[]"]')[index].value = res.data.price;
                    $('#other_products_table input[name="other_total_prices[]"]')[index].value = res.data.total_price;
                    $('#other_products_table input[name="other_discount_amounts[]"]')[index].value = res.data.discount_amount;
                    $('#other_products_table input[name="other_extra_amounts[]"]')[index].value = res.data.extra_amount;
                    $('#other_products_table input[name="other_total_prices_with_off[]"]')[index].value = res.data.total_price_with_off;
                    $('#other_products_table input[name="other_taxes[]"]')[index].value = res.data.tax;
                    $('#other_products_table input[name="other_invoice_nets[]"]')[index].value = res.data.invoice_net;

                    $('#btn_form').removeAttr('disabled').text('ثبت فرم');
                },
                error: function (request, status, error) {
                    //
                }
            })
        }
    </script>
@endsection
