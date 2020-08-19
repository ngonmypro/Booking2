@extends('layouts.master')
@section('pageTitle', 'Operation Report')
@section('content')
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{csrf_token()}}" />
</head>
<aside class="right-side">
  <!-- <h6 align="right"><a href="https://staff.icstravelgroup.com/php-bin/booking/bookdatechk.php?ssid=<?#=$ssid?>&isid=<?#=$isid?>" target='_blank'>Old Version</a></h6> -->

  <h4 style="text-align:center;"><b>Operation Report</b></h4><hr>
    <div class="box box-primary">
      <div class="box-body">
          <form action="{{ url("/SearchDataSupplier/Exportexcel") }}" method="post" id="frm" name="frm">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="row">
            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> 1.Country</label></div>
            <div class="col-md-3">
              <div class="form-group">
                <select class="form-control selectTo" name="Country" id="Country" OnChange="JavaScripct:ChkCountry(this.value);">
                    <option value="0"> Select Country </option>
                  @foreach($tbCountryArray AS $tbCountryData)
                    <option value="{{$tbCountryData->CountryId}}" <?php if($tbCountryData->CountryId == $EmpContryid){ echo "selected"; } ?>>{{$tbCountryData->CountryDesc}}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> 2.City</label></div>
            <div class="col-md-3">
              <div class="form-group">
                <select class="form-control selectTo" name="City" id="City" OnChange="JavaScripct:ChkCity(this.value);">
                    <option value="0"> Select City </option>
                    @foreach($CityArray AS $CityData)
                    <option value="{{$CityData->CityId}}">{{$CityData->City}}</option>
                    @endforeach
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> 3.Supplier Type</label></div>
            <div class="col-md-3">
              <div class="form-group">
                <select class="form-control selectTo" name="SupplierType" id="SupplierType" OnChange="JavaScripct:ChkSupplier(this.value);">
                    <!-- <option value=""></option> -->
                </select>
              </div>
            </div>

            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> 4.Supplier</label></div>
            <div class="col-md-3">
              <div class="form-group">
                <select class="form-control selectTo" name="Supplier" id="Supplier" OnChange="JavaScripct:ChkServiceName(this.value);">
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> 5.Service Name</label></div>
            <div class="col-md-8">
              <div class="form-group">
                <select class="form-control selectTo" name="ServiceName" id="ServiceName" >
                </select>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Service date on</label></div>
            <div class="col-md-3">
              <div class="input-daterange input-group" id="datepicker">
                <!--<input type="text" class="form-control" name="DOS_Date" value="" id="DOS_Date">-->
                <div class="input-daterange input-group" id="datepicker">
                  <input type="text" class="input-sm form-control datepicker" name="SDStart" id="SDStart" />
                  <span class="input-group-addon input-sm">to</span>
                  <input type="text" class="input-sm form-control datepicker" name="SDEnd" id="SDEnd" />
                </div>
              </div>
            </div>
            <!--<div class="col-md-2">
              <input type="date" class="form-control" name="DOS_DateEnd" value="" id="DOS_DateEnd">
            </div>-->
            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Tour date on</label></div>
            <div class="col-md-3">
              <!--<input type="date" class="form-control datepicker" name="HBG_Date" value="" id="HBG_Date">-->
              <div class="input-daterange input-group" id="datepicker">
                <input type="text" class="input-sm form-control datepicker" name="TDStart" id="TDStart" />
                <span class="input-group-addon input-sm">to</span>
                <input type="text" class="input-sm form-control datepicker" name="TDEnd" id="TDEnd" />
              </div>
            </div>
            <!--<div class="col-md-2">
              <input type="date" class="form-control" name="HBG_DateEnd" value="" id="HBG_DateEnd">
            </div>-->
          </div>
          <br>
          <div class="row" style="text-align:center;">
            <input type="button" class="btn btn-info" name="" value="Get Report" OnClick="javascript:searchSupplier_load();">
            <input type="submit" class="btn btn-success" name="" value="Export Excel">
            <input type="reset" class="btn btn-warning" name="" value="Clear">
          </div>
          <hr>
        </form>

        <div id="Head" name="Head"></div>
        <div id='result' name='result'></div>
        <!-- <div align="center" class="row" id="Head_Result" style="display:none;"></div><br> -->
        <!-- <div align="right" class="row" id="Data_Result" style="display:none;"></div> -->


      </div>
    </div>
</aside>
  @include('layouts.inc-scripts')
<script type="text/javascript">
$(document).ready(function () {
  $('.selectTo').select2();
  $('.datepicker').datepicker({
        format: 'dd/mm/yyyy'
      });
});

function ChkCountry(country) {
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  var url = '{{url('/ChangeDataCitySupplier')}}';
  // alert(country);
  var request = $.ajax({
      url: url,
      method: "POST",
      data: {country:country,
        _token: CSRF_TOKEN},
      dataType: "json"
   });
  request.done(function(data){
    // alert(data)
    $("#City").html("");
    $("#SupplierType").html("");
    $("#Supplier").html("");
    $("#ServiceName").html("");

    $("#SupplierType").val("0");
    $("#Supplier").val("0");
    $("#ServiceName").val("0");
    // $("#Restaurant").html("");
    var CityList  = data;
     $(CityList).each(function (){
         var option = $("<option/>");
          option.val(this.CityId);
          option.html(this.City);
        $("#City").append(option);
      });
     $("#City").prepend("<option value='0'> SELECT City </option>").val(0);
  });
}

function ChkCity(city) {
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  var url = '{{url('/ChangeDataSupplyType')}}';
  // alert(country);
  var request = $.ajax({
      url: url,
      method: "POST",
      data: {city:city,
        _token: CSRF_TOKEN},
      dataType: "json"
   });
  request.done(function(data){
    // alert(data)
    $("#SupplierType").html("");
    $("#Supplier").html("");
    $("#ServiceName").html("");
    $("#Supplier").val("0");
    $("#ServiceName").val("0");
    // $("#Restaurant").html("");
    var SupplyList  = data;
     $(SupplyList).each(function (){
         var option = $("<option/>");
          option.val(this.SupplyTypeID);
          option.html(this.SupplyType);
        $("#SupplierType").append(option);
      });
     $("#SupplierType").prepend("<option value='0'> SELECT Supplier Type </option>").val(0);
  });
}

function ChkSupplier(SupplierType) {
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  var url = '{{url('/ChangeDataSupplier')}}';
  var City = $("#City").val();
   // alert(SupplierType);
  var request = $.ajax({
      url: url,
      method: "POST",
      data: {SupplierType:SupplierType,
        City:City,
        _token: CSRF_TOKEN},
      dataType: "json"
   });
  request.done(function(data){
    // alert(data)
    $("#Supplier").html("");
    $("#ServiceName").html("");
    $("#ServiceName").val("0");
    // $("#Restaurant").html("");
    var SupplierList  = data;
     $(SupplierList).each(function (){
         var option = $("<option/>");
          option.val(this.CompanyId);
          option.html(this.CompanyName);
        $("#Supplier").append(option);
      });
     $("#Supplier").prepend("<option value='0'> SELECT Supplier </option>").val(0);
  });
}

function ChkServiceName(Supplier) {
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  var url = '{{url('/ChangeDataServiceName')}}';
  var City = $("#City").val();

  var request = $.ajax({
      url: url,
      method: "POST",
      data: {Supplier:Supplier,
        City: City,
        _token: CSRF_TOKEN},
      dataType: "json"
   });
  request.done(function(data){
    // alert(data)
    $("#ServiceName").html("");
    // $("#Restaurant").html("");
    var SupplierList  = data;
     $(SupplierList).each(function (){
         var option = $("<option/>");
          option.val(this.ServiceCode+'~'+this.ServiceContractId);
          option.html(this.ServiceName+'('+this.ValidFrom+'~'+this.ValidTo+')');
        $("#ServiceName").append(option);
      });
     $("#ServiceName").prepend("<option value='0'> SELECT Service Name </option>").val(0);
  });
}

function searchSupplier_load() {
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  var url = '{{url('/SearchSupplier')}}';
  // Service date start
  var SDStart = $('#SDStart').val();
  var DateChk = new Date(SDStart.substring(3,5)+' '+SDStart.substring(0,2)+' '+SDStart.substring(6));
  DateChk.setTime(DateChk.getTime() + 30 * 24 * 60 * 60 * 1000);
      var ddSDStart = DateChk.getDate();
      var mmSDStart = DateChk.getMonth() + 1; //January is 0!
      var yyyySDStart = DateChk.getFullYear();
        if (ddSDStart < 10) {
          ddSDStart = '0' + ddSDStart;
        }
        if (mmSDStart < 10) {
          mmSDStart = '0' + mmSDStart;
        }
      var DateChk2 = yyyySDStart +''+ mmSDStart +''+ ddSDStart;

  // Service date End
  var SDEnd = $('#SDEnd').val();
  var DateChkend = new Date(SDEnd.substring(3,5)+' '+SDEnd.substring(0,2)+' '+SDEnd.substring(6));
      var ddSDEnd = DateChkend.getDate();
      var mmSDEnd = DateChkend.getMonth() + 1; //January is 0!
      var yyyySDEnd = DateChkend.getFullYear();
        if (ddSDEnd < 10) {
          ddSDEnd = '0' + ddSDEnd;
        }
        if (mmSDEnd < 10) {
          mmSDEnd = '0' + mmSDEnd;
        }
      var DateChkend2 = yyyySDEnd +''+ mmSDEnd +''+ ddSDEnd;
// alert(DateChkend2+' | '+DateChk2)
  if (DateChkend2 > DateChk2) {
    alert("Service date to over 30 days.");
    exit();
  }

  var TDStart = $("#TDStart").val();
  var DateChkTDStart = new Date(TDStart.substring(3,5)+' '+TDStart.substring(0,2)+' '+TDStart.substring(6));
  DateChkTDStart.setTime(DateChkTDStart.getTime() + 30 * 24 * 60 * 60 * 1000);
      var ddTDStart = DateChkTDStart.getDate();
      var mmTDStart = DateChkTDStart.getMonth() + 1; //January is 0!
      var yyyyTDStart = DateChkTDStart.getFullYear();
        if (ddTDStart < 10) {
          ddTDStart = '0' + ddTDStart;
        }
        if (mmTDStart < 10) {
          mmTDStart = '0' + mmTDStart;
        }
      var DateChk3 = yyyyTDStart +''+ mmTDStart +''+ ddTDStart;

  var TDEnd = $("#TDEnd").val();
  var DateChkTDEnd = new Date(TDEnd.substring(3,5)+' '+TDEnd.substring(0,2)+' '+TDEnd.substring(6));
      var ddTDEnd = DateChkTDEnd.getDate();
      var mmTDEnd = DateChkTDEnd.getMonth() + 1; //January is 0!
      var yyyyTDEnd = DateChkTDEnd.getFullYear();
        if (ddTDEnd < 10) {
          ddTDEnd = '0' + ddTDEnd;
        }
        if (mmTDEnd < 10) {
          mmTDEnd = '0' + mmTDEnd;
        }
      var DateChkend4 = yyyyTDEnd +''+ mmTDEnd +''+ ddTDEnd;

  if (DateChkend4 > DateChk3) {
    alert("Tour date to over 30 days.");
    exit();
  }

  var Country = $("#Country").val();
  var City = $("#City").val();
  var SupplierType = $("#SupplierType").val();
  var Supplier = $("#Supplier").val();
  var ServiceName = $("#ServiceName").val();

  if (City == 0) {
    alert("Select City please.");
    exit();
  }
  if (SupplierType == 0) {
    alert("Select Supplier Type please.");
    exit();
  }
  if (Supplier == 0) {
    alert("Select Supplier please.");
    exit();
  }
  if (ServiceName == 0) {
    alert("Select Service Name please.");
    exit();
  }
  if (SDStart == "" && SDEnd == "" && TDStart == "" && TDEnd == "") {
    alert("Input date please.");
    exit();
  }

// alert(Country+' | '+City+' | '+SupplierType+' | '+Supplier+' | '+ServiceName)
  var request = $.ajax({
      url: url,
      method: "POST",
      data: {Country: Country,
        City: City,
        SupplierType: SupplierType,
        Supplier: Supplier,
        ServiceName: ServiceName,
        SDStart: SDStart,
        SDEnd: SDEnd,
        TDStart: TDStart,
        TDEnd: TDEnd,
        _token: CSRF_TOKEN},
      dataType: "text"
   });
   request.done(function(data){
     // alert(data)
     $("#result").html(data);
   });
   request.fail(function(data) {

   });
}
</script>
@endsection
