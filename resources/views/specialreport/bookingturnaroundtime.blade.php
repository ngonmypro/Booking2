@extends('layouts.master')
@section('pageTitle', 'Indochina Services Booking Report')
@section('content')
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{csrf_token()}}" />
  </head>
  <body>
    <aside class="right-side">
      <h6 align="right"><a href="https://staff.icstravelgroup.com/php-bin/report/booking/booking_time_report.php" target='_blank'>Old Version</a></h6>
        <br>
      <form action="{{ url("/ReportBookingTime/Exportexcel") }}" method="post" id="frm" name="frm">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
          <div class="row">
            <div class="col-md-3" align='right'><label for=""> Entered date :</label></div>
            <div class="col-md-4">
              <table width="98%" border="0" cellspacing="0" cellpadding="0">
                <tbody  >
                  <tr>
                    <td>
                      <input type="text" class="form-control datepicker" name="bsdate" id="bsdate" style="cursor: pointer;" readonly>
                    </td>
                    <td> &nbsp;&nbsp;To&nbsp;&nbsp;</td>
                    <td>
                      <input type="text" class="form-control datepicker" name="bedate" id="bedate" style="cursor: pointer;" readonly>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="row">
            <div class="col-md-3" align='right'><label for=""> Market :</label></div>
            <div class="col-md-5">
              <select class="form-control selectTo" name="tomarket" id="tomarket">
                <option value=""> # All Market # </option>
                @foreach($TOMarketMaster AS $TOMarketMaster_data)
                <option value="{{$TOMarketMaster_data->TOMarketId}}">{{$TOMarketMaster_data->TOMarketData}}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="row">
            <div class="col-md-3" align='right'><label for=""> Tour Operator :</label></div>
            <div class="col-md-6">
              <select class="form-control selectTo" name="client" id="client">
                <option value=""> # All Tour Operator # </option>
                @foreach($tbContacts AS $tbContacts_data)
                <option value="{{$tbContacts_data->ContactsId}}">{{$tbContacts_data->CompanyDesc}}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="row">
            <div class="col-md-3" align='right'><label for=""> Country :</label></div>
            <div class="col-md-4">
              <select class="form-control selectTo" name="country" id="country">
                <option value=""> # All Country # </option>
                @foreach($Country AS $Country_data)
                <option value="{{$Country_data->CountryID}}">{{$Country_data->Country}}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-5" align='center'>
              <input type="button" class="btn btn-info" value="Display" name="btnDisplay" id="btnDisplay" OnClick='JavaScripct:ReportBookingTime();'>
              <input type="submit" class="btn btn-success" value="Export" name="btnExport" id="btnExport">
            </div>
          </div>
        </div>
      </form>
      <hr>
      <div class="ShowDisplay" id="ShowDisplay">

      </div>
    </aside>

  </body>
</html>
@include('layouts.inc-scripts')
<script type="text/javascript">
  $(document).ready(function () {
    $('.selectTo').select2();
    $('.datepicker').datepicker({
      format: 'dd/mm/yyyy'
    });
  });

  function ReportBookingTime() {
     var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
     var url = '{{url('/ReportBookingTime')}}';
     var bsdate = $("#bsdate").val();
     var bedate = $("#bedate").val();
     var tomarket = $("#tomarket").val();
     var client = $("#client").val();
     var country = $("#country").val();

     var request = $.ajax({
         url: url,
         method: "POST",
         data: {bsdate: bsdate,
           bedate: bedate,
           tomarket: tomarket,
           client: client,
           country: country,
           _token: CSRF_TOKEN},
           dataType: "text"
         });
     request.done(function(data){
       // alert(data)
       $("#ShowDisplay").html(data);
     });
     request.fail(function(data){

     });
    // alert("TTTT");
  }
</script>
@endsection
