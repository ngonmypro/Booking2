@extends('layouts.master')
@section('pageTitle', 'Flight Booking Report')
@section('content')
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{csrf_token()}}" />
</head>
<aside class="right-side">
  <h6 align="right"><a href="https://staff.icstravelgroup.com/cgi-local/flight/bairline.php?ssid=<?=$ssid?>&isid=<?=$isid?>" target='_blank'>Old Version</a></h6>

      <h4 style="text-align:center;"><b>Airline Booking Report</b></h4><br>
      <div class="box box-primary">
        <div class="box-body">
          <form class="" action="" method="post">
            <div class="row">
              <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Country</label></div>
              <div class="col-md-2">
                <div class="form-group">
                  <select class="form-control selectTo" name="" id="Country" OnChange="javascript:(); ">
                      <option value=""> # ALL # </option>
                    @foreach($CountryArray as $CountryArrayDate)
                      <option value="{{$CountryArrayDate->CountryDesc}}"> {{$CountryArrayDate->CountryDesc}} </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="col-md-1" style="text-align:right;"><label style="font-size:12px"> City</label></div>
              <div class="col-md-2">
                <div class="form-group">
                  <select class="form-control selectTo col-md-3" name="" id="City1" OnChange="javascript:(); ">
                        <option value="0"> # ALL # </option>
                      @foreach($CityArray1 as $CityArray1Data)
                        <option value="{{$CityArray1Data->CountryDesc}}~{{$CityArray1Data->FlightFrom}}">{{$CityArray1Data->FlightFrom}}</option>
                      @endforeach
                  </select>
                </div>
              </div>

              <div class="col-md-1" style="text-align:center;"><label style="font-size:12px">To</label></div>
              <div class="col-md-2">
                <div class="form-group">
                  <select class="form-control selectTo col-md-3" name="" id="City2" OnChange="javascript:(); ">
                        <option value="0"> # ALL # </option>
                      @foreach($CityArray2 as $CityArray2Data)
                        <option value="{{$CityArray2Data->CountryDesc}}~{{$CityArray2Data->FlightFrom}}">{{$CityArray2Data->FlightFrom}}</option>
                      @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Date</label></div>
              <div class="col-md-3">
                <div class="form-group input-daterange input-group" id="datepicker">
                  <input type="text" class="input-sm form-control datepicker" name="HBG_Date" id="Date" />
                  <span class="input-group-addon input-sm">to</span>
                  <input type="text" class="input-sm form-control datepicker" name="HBG_DateEnd" id="DateEnd" />
                </div>
              </div>

              <div class="col-md-1" style="text-align:right;"><label style="font-size:12px">Type</label></div>
              <div class="col-md-3">
                <div class="form-group">
                  <font size='2'>
                  <input type="radio" name="Type" id="Type1" value="1" checked> Airline &nbsp;&nbsp;
                  <input type="radio" name="Type" id="Type2" value="2"> Boat and Train &nbsp;&nbsp;
                  <input type="radio" name="Type" id="Type3" value="3"> Balloon
                  </font>
                </div>
              </div>
            </div>


            <div class="row">
              <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Airline</label></div>
              <div class="col-md-3">
                <div class="form-group">
                  <select class="form-control selectTo" name="" id="Airline">
                      <option value="0"> # ALL #</option>
                    @foreach($AirlineArray as $AirlineArrayData)
                      <option value="{{$AirlineArrayData->Airline}}">{{$AirlineArrayData->Airline}}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="col-md-1" style="text-align:right;"><label style="font-size:12px"> Tour Operators</label></div>
              <div class="col-md-3">
                <div class="form-group">
                  <select class="form-control selectTo" name="" id="TOperators">
                      <option value="0"> # ALL #</option>
                    @foreach($TOArray as $TOArrayData)
                      <option value="{{$TOArrayData->ContactsId}}">{{$TOArrayData->CompanyDesc}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Reservation</label></div>
              <div class="col-md-3">
                <div class="form-group">
                  <select class="form-control selectTo" name="" id="Reservation">
                      <option value=""> # ALL #</option>
                    @foreach($ReservationArray as $ReservationArrayData)
                      <option value="{{$ReservationArrayData->UsersId}}">{{$ReservationArrayData->UName}}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="col-md-1" style="text-align:right;"><label style="font-size:12px">Option</label></div>
              <div class="col-md-3">
                <div class="form-group">
                  <font size='2'>
                  <input type="radio" name="Option" id="Option1" value="d" checked> Domestic  &nbsp;&nbsp;
                  <input type="radio" name="Option" id="Option2" value="i"> International  &nbsp;&nbsp;
                  <input type="radio" name="Option" id="Option3" value="b"> Both
                  </font>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Flight booked by TO</label></div>
              <div class="col-md-2">
                <div class="form-group">
                  <select class="input-sm form-control" name="" id="FBBT">
                    <option value="I" selected> Include </option>
                    <option value="E"> Exclude </option>
                    <option value="O"> Only </option>
                  </select>
                </div>
              </div>

              <div class="col-md-1" style="text-align:right;"><label style="font-size:12px"> CXL </label></div>
              <div class="col-md-3">
                <div class="form-group">
                  <select class="input-sm form-control" name="" id="CXL">
                    <option value="2"> All </option>
                    <option value="0" selected> Exclude </option>
                    <option value="1"> CXL Only </option>
                  </select>
              </div>
              </div>
            </div>

            <div class="row" style="text-align:center;">
              <input type="button" class="btn btn-info" name="" value="Report" OnClick="javascript:searchflight_load();">
              <input type="reset" class="btn btn-warning" name="" value="Reset">
              <!-- <p><b>**Report must run from current date**</b></p> -->
            </div>
          </form><hr>

          <div class="row">
            <div class="col-md-1" style="text-align:right;"></div>
            <div class="col-md-3" style="text-align:right;">
              <!-- <p style="font-size:11px; color:red;"> <b>** Status :: OK </b>= Confirmed ,<b> CXL</b> = Cancelled ,<b> RQ </b>= Booked</p> -->
            </div>
          </div>

          <!-- <div class="row">
            <table align='center' width='95%' cellspacing='0' border='1' cellpadding='2' bordercolor='#c0c0c0'  style='font-size:13px;'>
              <tr bgcolor=#eeeeee>
                <th width=7%><nobr><font>Country</font></nobr></th>
                <th width=7%><nobr><font>Tour ID</font></nobr></th>
                <th><font>Airline</font></th>
                <th width=10%><font>Flight No</font></th>
                <th width=10%><font>Time</font></th>
                <th width=20%><nobr><font>Supplier</font></nobr></th>
                <th width=20%><nobr><font>Client</font></nobr></th>
                <th width=10%><nobr><font>T.Op.</font></nobr></th>
                <th width=10%><nobr><font>Refno.</font></nobr></th>
                <th width=10%><nobr><font>ICS remark for flight</font></nobr></th>
                <th nowrap><nobr><font>From</font></nobr></th>
                <th nowrap><nobr><font>To</font></nobr></th>
                <th width=7%><nobr><font>Booked On</font></nobr></th>
                <th width=7%><nobr><font>Date</font></nobr></th>
                <th width=5%><nobr><font>User</font></nobr></th>
                <th width=5%><nobr><font>Status</font></nobr></th>
                <th width=5%><nobr><font>Pax</font></nobr></th>
                <th width=5%><nobr><font>PP</font></nobr></th>
                <th width=5%><nobr><font>Price</font></nobr></th>
                <th width=5%><nobr><font>extra</font></nobr></th>
                <th width=5%><nobr><font>reduction</font></nobr></th>
                <th width=5%><nobr><font>TotalPrice</font></nobr></th>
              </tr>
            </table>
          </div> -->
          <div class="row" align="center" id="show_report"> </div>
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

function searchflight_load() {
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  var url = '{{url('/SearchDataFlight')}}';

  var Type = "";
  var Option = "";
  if ($('#Type1').is(':checked')) {
    Type = $("#Type1").val();
  }else if ($('#Type2').is(':checked')) {
    Type = $("#Type2").val();
  }else {
    Type = $("#Type3").val();
  }

  if ($('#Option1').is(':checked')) {
    Option = $("#Option1").val();
  }else if ($('#Option2').is(':checked')) {
    Option = $("#Option2").val();
  }else {
    Option = $("#Option3").val();
  }

  var Country = $("#Country").val();
  var City1 = $("#City1").val();
  var City2 = $("#City2").val();
  var Date = $("#Date").val();
  var DateEnd = $("#DateEnd").val();
  var Airline = $("#Airline").val();
  var TOperators = $("#TOperators").val();
  var Reservation = $("#Reservation").val();
  var FBBT = $("#FBBT").val();
  var CXL = $("#CXL").val();

      var request = $.ajax({
          url: url,
          method: "POST",
          data: {Type:Type,
            Option:Option,
            Country:Country,
            City1:City1,
            City2:City2,
            Date:Date,
            DateEnd:DateEnd,
            Airline:Airline,
            TOperators:TOperators,
            Reservation:Reservation,
            FBBT:FBBT,
            CXL:CXL,
            _token: CSRF_TOKEN},
            dataType: "text"
          });
      request.done(function(data){
        //alert(data)
        $("#show_report").html(data);
      });
      request.fail(function(data){

      });
}

    function ChkCity(country) {
      //alert(country)
      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
      var url = '{{url('/ChangeDataCityFlight')}}';

        var request = $.ajax({
            url: url,
            method: "POST",
            data: {country:country,
              _token: CSRF_TOKEN},
            dataType: "json"
         });
      request.done(function(data){
        $("#City1").html("");
        $("#City2").html("");
        var CityList  = data;
         $(CityList).each(function (){
             var option = $("<option/>");
              option.val(this.CountryDesc+'~'+this.FlightFrom);
              option.html(this.FlightFrom);
            $("#City1").append(option);
          });
          $(CityList).each(function (){
              var option = $("<option/>");
               option.val(this.CountryDesc+'~'+this.FlightFrom);
               option.html(this.FlightFrom);
             $("#City2").append(option);
           });
         $("#City1").prepend("<option value='0'> # ALL # </option>").val(0);
         $("#City2").prepend("<option value='0'> # ALL # </option>").val(0);
      });
      request.fail(function(data){
        alert("Error");
      });
    }

    function ChkAirline() {
      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
      var url = '{{url('/ChangeDataAirlineFlight')}}';

      var Country = "";
      var City1 = "";
      var City2 = "";
      if ($("#Country").val() != "") {
        Country = $("#Country").val();
      }
      if ($("#City1").val() != "0") {
        City1 = $("#City1").val();
      }
      if ($("#City2").val() != "0") {
        City2 = $("#City2").val();
      }
      // alert(Country+' | '+City1+' | '+City2)
      var request = $.ajax({
          url: url,
          method: "POST",
          data: {Country:Country,
            City1:City1,
            City2:City2,
            _token: CSRF_TOKEN},
          dataType: "json"
       });
      request.done(function(data){
        $("#Airline").html("");
        var AirlineList  = data;
         $(AirlineList).each(function (){
             var option = $("<option/>");
              option.val(this.Airline);
              option.html(this.Airline);
            $("#Airline").append(option);
          });
          $("#Airline").prepend("<option value='0'> # ALL # </option>").val(0);
      });
      request.fail(function(data){
        alert("Error");
      });
    }

    function ChkTO() {
      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
      var url = '{{url('/ChangeDataToFlight')}}';

      var Country = "";
      var City1 = "";
      var City2 = "";
      if ($("#Country").val() != "") {
        Country = $("#Country").val();
      }
      if ($("#City1").val() != "0") {
        City1 = $("#City1").val();
      }
      if ($("#City2").val() != "0") {
        City2 = $("#City2").val();
      }
      // alert(Country+' | '+City1+' | '+City2)
      var request = $.ajax({
          url: url,
          method: "POST",
          data: {Country:Country,
            City1:City1,
            City2:City2,
            _token: CSRF_TOKEN},
          dataType: "json"
       });
       request.done(function(data){
         $("#TOperators").html("");
         var TOperatorsList  = data;
          $(TOperatorsList).each(function (){
              var option = $("<option/>");
               option.val(this.ContactsId);
               option.html(this.CompanyDesc);
             $("#TOperators").append(option);
           });
           $("#TOperators").prepend("<option value='0'> # ALL # </option>").val(0);
       });
       request.fail(function(data){
         alert("Error");
       });

    }

//
//     function ChkRestaurant(city) {
//       var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
//       var url = '{{url('/ChangeDataRestaurant')}}';
//       var country = document.getElementById("Country").value;
//       //alert(url)
//       var request = $.ajax({
//           url: url,
//           method: "POST",
//           data: {city:city,
//             country:country,
//             _token: CSRF_TOKEN},
//           dataType: "json"
//        });
//       request.done(function(data){
//         //alert(data)
//         $("#Restaurant").html("");
//         var RestaurantList = data;
//          $(RestaurantList).each(function (){
//              var option = $("<option/>");
//               option.val(this.RestaurantId);
//               option.html(this.Restaurant+' ('+this.City+')');
//             $("#Restaurant").append(option);
//           });
//          $("#Restaurant").prepend("<option value='0'> SELECT Restaurant </option>").val(0);
//       });
//     }
//
//     function searchrestaurant_load() {
//       var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
//       var url = '{{url('/SearchDataRestaurant')}}';
//       var Country = document.getElementById("Country").value;
//       var City = document.getElementById("City").value;
//       var TORestaurant = document.getElementById("TORestaurant").value;
//       var Restaurant = document.getElementById("Restaurant").value;
//       var Company = document.getElementById("Company").value;
//       var Inbound = document.getElementById("Inbound").value;
//       var Start_Date = document.getElementById("Start_Date").value;
//       var Start_DateEnd = document.getElementById("Start_DateEnd").value;
//       var Restaurant_Date = document.getElementById("Restaurant_Date").value;
//       var Restaurant_DateEnd = document.getElementById("Restaurant_DateEnd").value;
//       var Status = document.getElementById("Status").value;
// //alert(Restaurant)
//       var request = $.ajax({
//           url: url,
//           method: "POST",
//           data: {Country:Country,
//             City:City,
//             TORestaurant:TORestaurant,
//             Restaurant:Restaurant,
//             Company:Company,
//             Inbound:Inbound,
//             Start_Date:Start_Date,
//             Start_DateEnd:Start_DateEnd,
//             Restaurant_Date:Restaurant_Date,
//             Restaurant_DateEnd:Restaurant_DateEnd,
//             Status:Status,
//             _token: CSRF_TOKEN},
//           dataType: "text"
//        });
//        request.done(function(data){
//          // alert(data);
//          $("#show_report").html(data);
//          //
//        });
//        request.fail(function(data){
//          alert("error");
//        });
//     }
</script>
@endsection
