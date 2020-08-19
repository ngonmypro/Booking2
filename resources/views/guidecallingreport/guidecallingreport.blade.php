@extends('layouts.master')
@section('pageTitle', 'Guide Calling Report')
@section('content')
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{csrf_token()}}" />
</head>
<aside class="right-side">

  <h4 style="text-align:center;"><b>Guide Calling Report</b></h4><br>
    <div class="box box-primary">
      <div class="box-body">
        <form action="{{ url("/GuideCalling/export") }}" method="post" id="frm" name="frm">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="row">
            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Tour Id</label></div>
            <div class="col-md-3">
              <div class="form-group">
                <input type="text" class="input-sm form-control" name="txtTourId" id="txtTourId" />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Country</label></div>
            <div class="col-md-2">
              <div class="form-group">
                <select class="form-control selectTo" name="slCountry" id="slCountry">
                    <option value=""> All </option>
                    @foreach($country_array as $index =>$country_data)
                    <option value="{{ $country_data->CountryId }}">{{ $country_data->CountryDesc }}</option>
                    @endforeach
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Service Date From</label></div>
            <div class="col-md-3">
              <div class="input-daterange input-group" id="datepicker">

                <div class="input-daterange input-group" id="datepicker">
                  <input type="text" class="input-sm form-control datepicker" name="txtDateFrom" id="txtDateFrom" />
                  <span class="input-group-addon input-sm">to</span>
                  <input type="text" class="input-sm form-control datepicker" name="txtDateTo" id="txtDateTo" />
                </div>
              </div>
            </div>
          </div>

          <div class="row" style="text-align:center;">
            <input type="button" class="btn btn-info" value="Report" name="btnSearch" id="btnSearch">
            <input type="submit" class="btn btn-success" value="Export" name="btnExport" id="btnExport">
            <input type="reset" class="btn btn-warning" name="" value="Reset">
          </div>
          <hr>
        </form>


        <div class="row" id="result">
        </div>


      </div>
    </div>
</aside>
  @include('layouts.inc-scripts')
<script type="text/javascript">
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

$(document).ready(function () {

  $('.datepicker').datepicker({
      format: 'dd-M-yyyy'
  });

  $("#btnSearch").click(function(){
    var tourid = $('#txtTourId').val();
    var q_date_form = $('#txtDateFrom').val();
    var q_date_to = $('#txtDateTo').val();



    if(
      q_date_form != '' &&  q_date_to != ''
    ){

      $('#result').html('<h4>Loading....</h4>');

      $.ajax({
          url: '{{ url("GuideCalling/search") }}',
          type: 'POST',
          data: $('#frm').serialize()+"&_token="+CSRF_TOKEN,
          dataType: 'text',
          success: function (data) {
            $('#result').html(data);
          }
      });
    }else{
      alert("Please select service date.");
    }
  });
});


</script>
@endsection
