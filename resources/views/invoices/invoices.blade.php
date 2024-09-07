@extends('layouts.master')
<title>قائمة الفواتير</title>
@section('css')
<!-- Internal Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
@endsection

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/قائمة الفواتير</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')
<!-- row -->
<div class="row">
    <!--div-->
    @if (session()->has('delete_invoice'))
        <script>
            window.onload = function() {
                notif({
                    msg: "تم حذف الفاتورة بنجاح",
                    type: "success"
                })
            }
        </script>
    @endif

    @if (session()->has('Status_Update'))
        <script>
            window.onload = function() {
                notif({
                    msg: "تم تحديث حالة الدفع بنجاح",
                    type: "success"
                })
            }
        </script>
    @endif

    @if (session()->has('archive_invoice'))
        <script>
            window.onload = function() {
                notif({
                    msg: "تم حذف الفاتورة بنجاح",
                    type: "success"
                })
            }
        </script>
    @endif

    <div class="col-xl-12">
        <div class="card mg-b-20">
            @can('اضافة فاتورة')
            <div class="d-flex justify-content-between">
                <div class="col-sm-6 col-md-4 col-xl-3 mg-t-20">
                    <a href="invoices/create" class="modal-effect btn btn-sm btn-primary" style="color:white">
                        <i class="fas fa-plus"></i>&nbsp; اضافة فاتورة
                    </a>
                </div>
              
                <i class="mdi mdi-dots-horizontal text-gray"></i>
            </div>
            @endcan
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table key-buttons text-md-nowrap">
                        <thead>
                            <tr>
                                <th class="border-bottom-0">#</th>
                                <th class="border-bottom-0">رقم الفاتورة</th>
                                <th class="border-bottom-0">تاريخ الفاتورة</th>
                                <th class="border-bottom-0">تاريخ الإستحقاق</th>
                                <th class="border-bottom-0">المنتج</th>
                                <th class="border-bottom-0">القسم</th>
                                <th class="border-bottom-0">الخصم</th>
                                <th class="border-bottom-0">نسبة الضريبة</th>
                                <th class="border-bottom-0">قيمة الضريبة</th>
                                <th class="border-bottom-0">الإجمالي</th>
                                <th class="border-bottom-0">الحالة</th>
                                <th class="border-bottom-0">ملاحظات</th>
                                <th class="border-bottom-0">العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=0?>
                            @foreach($invoices as $invoice)
                            <?php $i++?>
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$invoice->invoiceNumber}}</td>
                                <td>{{$invoice->invoiceDate}}</td>
                                <td>{{$invoice->dueDate}}</td>
                                <td>{{$invoice->product}}</td>
                                <td>
                                    <a href="{{url('/invoicesDetails')}}/{{$invoice->id}}">
                                        {{ optional($invoice->section)->sectionName ?? 'No Section' }}
                                    </a>
                                </td>
                                <td>{{$invoice->discount}}</td>
                                <td>{{$invoice->rateVAT}}</td>
                                <td>{{$invoice->valueVAT}}</td>
                                <td>{{$invoice->total}}</td>
                                <td>
                                    @if($invoice->valueStatus == 1)
                                    <span class="text-success">{{$invoice->status}}</span>
                                    @elseif($invoice->valueStatus == 2)
                                    <span class="text-danger">{{$invoice->status}}</span>
                                    @else($invoice->valueStatus == 3)
                                    <span class="text-warning">{{$invoice->status}}</span>
                                    @endif
                                </td>
                                <td>{{$invoice->note}}</td>
                                <td>
                                    <div class="dropdown">
                                        <button aria-expanded="false" aria-haspopup="true"
                                            class="btn ripple btn-primary btn-sm" data-toggle="dropdown" type="button">العمليات
                                            <i class="fas fa-caret-down ml-1"></i>
                                        </button>
                                        @can('تعديل الفاتورة')
                                        <div class="dropdown-menu tx-13">
                                            <a class="dropdown-item" href="{{ url('edit_invoice') }}/{{ $invoice->id }}">تعديل الفاتورة</a>
                                            @endcan
                                            @can('حذف الفاتورة')
                                            <a class="dropdown-item" href="#" data-invoiceId="{{ $invoice->id }}" data-toggle="modal" data-target="#delete_invoice">
                                                <i class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;حذف الفاتورة
                                            </a>
                                            @endcan
                                            @can('تغير حالة الدفع')
                                            <a class="dropdown-item" href="{{ URL::route('Status_show', [$invoice->id]) }}">
                                                <i class="text-success fas fa-money-bill"></i>&nbsp;&nbsp;تغير حالة الدفع
                                            </a>
                                            @endcan
                                            @can('ارشفة الفاتورة')
                                            <a class="dropdown-item" href="#" data-invoiceId="{{ $invoice->id }}" data-toggle="modal" data-target="#Transfer_invoice">
                                                <i class="text-warning fas fa-exchange-alt"></i>&nbsp;&nbsp;نقل الى الارشيف
                                            </a>
                                            @endcan
                                            @can('طباعةالفاتورة')
                                            <a class="dropdown-item" href="Print_invoice/{{ $invoice->id }}"><i
                                                class="text-success fas fa-print"></i>&nbsp;&nbsp;طباعة الفاتورة</a>
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--/div-->

    <!--div-->
</div>

<!-- تعديل المودال الخاص بالحذف -->
<div class="modal fade" id="delete_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">حذف الفاتورة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('invoices.destroy', 'test') }}" method="post">
                {{ method_field('delete') }}
                {{ csrf_field() }}
                <div class="modal-body">
                    هل انت متاكد من عملية الحذف؟
                    <input type="hidden" name="invoice_id" id="invoiceId" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                    <button type="submit" class="btn btn-danger">تاكيد</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- تعديل المودال الخاص بالأرشفة -->
<div class="modal fade" id="Transfer_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ارشفة الفاتورة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('invoices.destroy', 'test') }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <div class="modal-body">
                    هل انت متاكد من عملية الارشفة؟
                    <input type="hidden" name="invoice_id" id="invoice-id" value="">
                    <input type="hidden" name="id_page" id="id_page" value="2">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                    <button type="submit" class="btn btn-success">تاكيد</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Container closed -->
</div>
<!-- main-content closed -->
@endsection

@section('js')
<!-- Internal Data tables -->
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>

<!--Internal Datatable js -->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>
<script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>
<script src="{{URL::asset('assets/plugins/notify/js/notifit-custom.js')}}"></script>

<!-- Script للتعامل مع المودال الخاص بالحذف -->
<script>
    $('#delete_invoice').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var invoiceId = button.data('invoiceid');
        var modal = $(this);
        modal.find('.modal-body #invoiceId').val(invoiceId);
    });

    $('#Transfer_invoice').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var invoiceId = button.data('invoiceid');
        var modal = $(this);
        modal.find('.modal-body #invoice-id').val(invoiceId);
    });

    // Export to Excel functionality
  
</script>
@endsection
