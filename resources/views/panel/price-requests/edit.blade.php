@extends('panel.layouts.master')
@section('title', 'ثبت قیمت')
@section('styles')
    <style>
        table tbody tr td input {
            text-align: center;
            width: fit-content !important;
        }
    </style>
@endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">ثبت قیمت</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('price-requests.update', $priceRequest->id) }}" method="post">
                                @csrf
                                @method('put')
                                <div class="form-row">
                                    <div class="col-12 mb-3">
                                        <table class="table table-striped table-bordered text-center">
                                            <thead class="table-primary">
                                            <tr>
                                                <th>عنوان کالا</th>
                                                <th>تعداد</th>
                                                <th>قیمت (تومان)</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach(json_decode($priceRequest->items) as $item)
                                                <tr>
                                                    <td>{{ $item->product }}</td>
                                                    <td>{{ $item->count }}</td>
                                                    <td class="d-flex justify-content-center">
                                                        <input type="text" class="form-control" name="prices[]" value="{{ isset($item->price) ? number_format($item->price) : 0 }}" required>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                            <tr></tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <button class="btn btn-primary mt-5" type="submit">ثبت فرم</button>
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
        // item changed
        $(document).on('keyup', 'input[name="prices[]"]', function () {
            $(this).val(addCommas($(this).val()))
        })

        function funcReverseString(str) {
            return str.split('').reverse().join('');
        }

        // for thousands grouping
        function addCommas(nStr) {
            // event handlers
            let thisElementValue = nStr
            thisElementValue = thisElementValue.replace(/,/g, "");

            let seperatedNumber = thisElementValue.toString();
            seperatedNumber = funcReverseString(seperatedNumber);
            seperatedNumber = seperatedNumber.split("");

            let tmpSeperatedNumber = "";

            j = 0;
            for (let i = 0; i < seperatedNumber.length; i++) {
                tmpSeperatedNumber += seperatedNumber[i];
                j++;
                if (j == 3) {
                    tmpSeperatedNumber += ",";
                    j = 0;
                }
            }

            seperatedNumber = funcReverseString(tmpSeperatedNumber);
            if (seperatedNumber[0] === ",") seperatedNumber = seperatedNumber.replace(",", "");
            return seperatedNumber;
        }
    </script>
@endsection




