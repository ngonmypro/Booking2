@extends('layouts.master')
@section('pageTitle', 'Booking and Quotation Assignment')
@section('content')
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{csrf_token()}}" />
</head>
<aside class="right-side">

    <div class="box box-primary">
      <div class="box-body">
        <form action="" method="post" id="frm" name="frm">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">

          <div class="row">
            <h4 style="text-align:center;"><b>Booking and Quotation Assignment</b></h4>
          </div>

          <div class="row">
            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> IS User/Travel Consultant:</label></div>
            <div class="col-md-3">
              <div class="form-group">

                <select id="slISUser" name="slISUser" style="width: 300px">
                  <option value="">- s e l e c t -</option>
                  @foreach($user_array as $index =>$user)
                  <option value="{{ $user->UsersId }}|{{ $user->ISID }}|{{ $user->UserShortcut }}">{{ $user->uname }}</option>
                  @endforeach
                </select>

              </div>
            </div>

            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Transfer To</label></div>
            <div class="col-md-3">
              <div class="form-group">

                <select id="slTransferISUser" name="slTransferISUser" style="width: 300px">
                  <option value="">- s e l e c t -</option>
                  @foreach($user_array as $index =>$user)
                  <option value="{{ $user->UsersId }}|{{ $user->ISID }}|{{ $user->UserShortcut }}">{{ $user->uname }}</option>
                  @endforeach
                </select>

              </div>
            </div>

            <div class="col-md-2" style="text-align:right;">
              <input type="button" class="btn btn-info" value="U p d a t e" name="btnUpdateTC" id="btnUpdateTC">
            </div>
          </div>

          <div class="row">
            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Travel Designer:</label></div>
            <div class="col-md-3">
              <div class="form-group">

                <select id="slTDUser" name="slTDUser" style="width: 300px">
                  <option value="">- s e l e c t -</option>
                  @foreach($user_array as $index =>$user)
                  <option value="{{ $user->UsersId }}|{{ $user->ISID }}|{{ $user->UserShortcut }}">{{ $user->uname }}</option>
                  @endforeach
                </select>

              </div>
            </div>

            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Transfer To</label></div>
            <div class="col-md-3">
              <div class="form-group">

                <select id="slTransferTDUser" name="slTransferTDUser" style="width: 300px">
                  <option value="">- s e l e c t -</option>
                  @foreach($user_array as $index =>$user)
                  <option value="{{ $user->UsersId }}|{{ $user->ISID }}|{{ $user->UserShortcut }}">{{ $user->uname }}</option>
                  @endforeach
                </select>

              </div>
            </div>

            <div class="col-md-2" style="text-align:right;">
              <input type="button" class="btn btn-info" value="U p d a t e" name="btnUpdateTD" id="btnUpdateTD">
            </div>
          </div>

          <div class="row">
            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> TO Market:</label></div>
            <div class="col-md-3">
              <div class="form-group">
                <select id="slTOMarket" name="slTOMarket" style="width: 300px">
                  <option value="">- s e l e c t -</option>
                  @foreach($to_market_array as $index =>$market)
                  <option value="{{ $market->TOMarketId }}">{{ $market->TOMarketData }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> RECORD TYPE:</label></div>

            <div class="col-md-5">
              <div class="form-group">
                <div class="custom-control custom-radio custom-control-inline">
                	<input type="radio" id="rdType1" name="rdType" value="1" class="custom-control-input" checked="">
                	<label class="custom-control-label" for="rdType1">Booking</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                	<input type="radio" id="rdType2" name="rdType" value="2" class="custom-control-input">
                	<label class="custom-control-label" for="rdType2">Quotation</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="rdType3" name="rdType" value="3" class="custom-control-input">
                    <label class="custom-control-label" for="rdType3">Invoice</label> <label style="color:red;">*for invoice please select dropdown below</label>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <h4 style="text-align:center;"><b>SCRM Invoice Assignment</b></h4>
          </div>

          <div class="row">
            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> INVOICE STAFF:</label></div>
            <div class="col-md-3">
              <div class="form-group">
                <select id="slInvoice" name="slInvoice" style="width: 300px">
                  <option value="">- s e l e c t -</option>
                  @foreach($invoice_array as $index =>$invoice)
                  <option value="{{ $invoice->ISID }}">{{ $invoice->FirstName }} {{ $invoice->LastName }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Transfer To</label></div>
            <div class="col-md-3">
              <div class="form-group">

                <select id="slTransferInvoice" name="slTransferInvoice" style="width: 300px">
                  <option value="">- s e l e c t -</option>
                  @foreach($invoice_array as $index =>$invoice)
                  <option value="{{ $invoice->ISID }}">{{ $invoice->FirstName }} {{ $invoice->LastName }}</option>
                  @endforeach
                </select>

              </div>
            </div>

            <div class="col-md-2" style="text-align:right;">
              <input type="button" class="btn btn-info" value="U p d a t e" name="btnUpdateInvoice" id="btnUpdateInvoice">
            </div>
          </div>

          <div class="row">
            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> COMPANY IN CRM:</label></div>
            <div class="col-md-3">
              <div class="form-group">
                <select id="slCompany" name="slCompany" style="width: 300px">
                  <option value="">- s e l e c t -</option>
                  @foreach($location_array as $index =>$location)
                  <option value="{{ $location->LocationID }}">{{ $location->Company }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <div class="row" style="text-align:center;">
            <input type="button" class="btn btn-info" value="S e a r c h" name="btnSearch" id="btnSearch">
            <input type="reset" class="btn btn-warning" name="" value="R e s e t">
          </div>
          <hr>


          <div class="row" id="result">
          </div>

        </form>
      </div>
    </div>
</aside>
  @include('layouts.inc-scripts')
<script type="text/javascript">
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

$(document).ready(function () {
  $("#btnUpdateTC").click(function(){
    var source_is_user = $('#slISUser').val();
    var transfer_is_user = $('#slTransferISUser').val();

    if(
      source_is_user != ''
      &&  transfer_is_user != ''
    ){

      $.ajax({
          url: '{{ url("JobTransfer/transfer-b-tc") }}',
          type: 'POST',
          data: $('#frm').serialize()+"&_token="+CSRF_TOKEN,
          dataType: 'text',
          success: function (data) {
            //$('#result').html(data);
            alert(data);
          }
      });
    }else{
      alert("Please select STAFF.");
    }
  });

  $("#btnUpdateTD").click(function(){
    var source_td = $('#slTDUser').val();
    var transfer_td = $('#slTransferTDUser').val();

    if(
      source_td != ''
      &&  transfer_td != ''
    ){

      $.ajax({
          url: '{{ url("JobTransfer/transfer-b-td") }}',
          type: 'POST',
          data: $('#frm').serialize()+"&_token="+CSRF_TOKEN,
          dataType: 'text',
          success: function (data) {
            alert(data);
          }
      });
    }else{
       alert("Please select STAFF.");
    }
   });

  $("#btnUpdateInvoice").click(function(){
    var invoice = $('#slInvoice').val();
    var transfer_invoice = $('#slTransferInvoice').val();

    if(
      invoice != ''
      &&  transfer_invoice != ''
    ){

      $.ajax({
          url: '{{ url("JobTransfer/transfer-invoice") }}',
          type: 'POST',
          data: $('#frm').serialize()+"&_token="+CSRF_TOKEN,
          dataType: 'text',
          success: function (data) {
            alert(data);
          }
      });
    }else{
      alert("Please select STAFF.");
    }
  });

  $("#btnSearch").click(function(){
    // alert($('#slISUser').val());
    var is_user = $('#slISUser').val();
    var td_user = $('#slTDUser').val();
    var to_market_to = $('#slTOMarket').val();
    var type = $('#rdType').val();
    var invoice = $('#slInvoice').val();
    var company = $('#slCompany').val();

    if(
      is_user != ''
      ||  td_user != ''
      ||  invoice != ''
    ){

      $('#result').html('<h4>Loading....</h4>');

      $.ajax({
          url: '{{ url("JobTransfer/search") }}',
          type: 'POST',
          data: $('#frm').serialize()+"&_token="+CSRF_TOKEN,
          dataType: 'text',
          success: function (data) {
            $('#result').html(data);
          }
      });
    }else{
      alert("Please select STAFF.");
    }
  });
});


</script>
@endsection
