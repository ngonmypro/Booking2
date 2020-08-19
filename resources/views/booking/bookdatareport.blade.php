@extends('layouts.master')
@section('pageTitle', 'Booking Report')
@section('content')
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{csrf_token()}}" />
</head>
<aside class="right-side">
  <h6 align="right"><a href="https://staff.icstravelgroup.com/php-bin/booking/bookdatechk.php?ssid=<?=$ssid?>&isid=<?=$isid?>" target='_blank'>Old Version</a></h6>

  <h4 style="text-align:center;"><b>Hotel Bookings Period Query Screen</b></h4><br>
    <div class="box box-primary">
      <div class="box-body">
          <form action="{{ url("/SearchDataBooking/Exportexcel") }}" method="post" id="frm" name="frm">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="row">
            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Reservation Officer</label></div>
            <div class="col-md-3">
              <div class="form-group">
                <select class="form-control selectTo" name="ROffice" id="ROffice">
                    <option value="">  </option>
                  @foreach($ROfficeArray as $Roffice_data)
                    <option value="{{$Roffice_data->UsersId}}">{{$Roffice_data->UName}}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Operator</label></div>
            <div class="col-md-3">
              <div class="form-group">
                <select class="form-control selectTo" name="Operator" id="Operator">
                    <option value="">  </option>
                    @foreach($ContactsArray as $Contacts_data)
                    <option value="{{$Contacts_data->ContactsId}}">{{$Contacts_data->CompanyDesc}}</option>
                    @endforeach
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Country</label></div>
            <div class="col-md-2">
              <div class="form-group">
                <select class="form-control selectTo" name="Country" id="Country" OnChange="javascript:ChkCity(this.value);">
                    <option value=""> All </option>
                    @foreach($CountryArray as $Country_data)
                    <option value="{{$Country_data}}">{{$Country_data}}</option>
                    @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-1"></div>

            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Overland City</label></div>
            <div class="col-md-2">
              <div class="form-group">
                <select class="form-control selectTo" name="City" id="City" OnChange="javascript:ChkHotel(this.value);">
                    <option value="0"> # SELECT CITY # </option>
                  @foreach($CityArray as $City_data)
                    <option value="{{$City_data->City}}">{{$City_data->City}}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Hotel</label></div>
            <div class="col-md-3">
              <div class="form-group">
                <select class="form-control selectTo" name="Hotel" id="Hotel" OnChange="javascript:ChkRoom(this.value);">
                    <option value="">  </option>
                  @foreach($HotelArray as $Hotel_data)
                    <option value="{{$Hotel_data->HotelId}}">{{$Hotel_data->Hotel}} - {{$Hotel_data->City}}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Room Category</label></div>
            <div class="col-md-3">
              <div class="form-group">
                <select class="form-control selectTo" name="RoomCat" id="RoomCat">

                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Cancel</label></div>
            <div class="col-md-3">
              <div class="form-group">
                <select class="form-control input-sm" name="Cancel" id="Cancel">
                    <option value=""> Include </option>
                    <option value="0"> Exclude </option>
                    <option value="1"> Only </option>
                </select>
              </div>
            </div>

            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Status</label></div>
            <div class="col-md-3">
              <div class="form-group">
                <select class="form-control input-sm" name="Status" id="Status">
                  <option  value="X">Exclude CXL</option>
                  <option selected value="XF">Exclude. CXL and Full</option>
                  <option value="C">Cancelled Only</option>
                  <option value="B">Booked</option>
                  <option value="N">Not yet booked</option>
                  <option value="O">OK only</option>
                  <option value="W">WL only</option>
                  <option value="F">Full only</option>
                  <option value="">All</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Date of stay</label></div>
            <div class="col-md-3">
              <div class="input-daterange input-group" id="datepicker">
                <!--<input type="text" class="form-control" name="DOS_Date" value="" id="DOS_Date">-->
                <div class="input-daterange input-group" id="datepicker">
                  <input type="text" class="input-sm form-control datepicker" name="DOS_Date" id="DOS_Date" />
                  <span class="input-group-addon input-sm">to</span>
                  <input type="text" class="input-sm form-control datepicker" name="DOS_DateEnd" id="DOS_DateEnd" />
                </div>
              </div>
            </div>
            <!--<div class="col-md-2">
              <input type="date" class="form-control" name="DOS_DateEnd" value="" id="DOS_DateEnd">
            </div>-->
            <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Hotel booking generated</label></div>
            <div class="col-md-3">
              <!--<input type="date" class="form-control datepicker" name="HBG_Date" value="" id="HBG_Date">-->
              <div class="input-daterange input-group" id="datepicker">
                <input type="text" class="input-sm form-control datepicker" name="HBG_Date" id="HBG_Date" />
                <span class="input-group-addon input-sm">to</span>
                <input type="text" class="input-sm form-control datepicker" name="HBG_DateEnd" id="HBG_DateEnd" />
              </div>
            </div>
            <!--<div class="col-md-2">
              <input type="date" class="form-control" name="HBG_DateEnd" value="" id="HBG_DateEnd">
            </div>-->
          </div>

          <div class="row" style="text-align:center;">
            <input type="button" class="btn btn-info" name="" value="Report" OnClick="javascript:searchbooking_load();">
            <input type="submit" class="btn btn-success" name="" value="Export Excel">
            <input type="reset" class="btn btn-warning" name="" value="Reset">
          </div>
          <hr>
        </form>

        <div id='result' name='result'></div>
        <div align="center" class="row" id="Head_Result" style="display:none;"></div><br>
        <div align="right" class="row" id="Data_Result" style="display:none;"></div>

        <div class="row">
          <form align="center" class="" action="" method="post" style="background-color:#E2DEDD; width:98%;">
            <br>
            <div class="row">
              <div class="col-md-2" style="text-align:right;">
                <h4><b> Send To Email </b></h4>
              </div>
            </div>
            <div class="row">
              <div class="col-md-1" style="text-align:right;"><label style="font-size:12px"> Email: </label></div>
              <div class="col-md-3">
                <div class="form-group">
                  <input type="email" class="input-sm form-control" name="" value="" id="Email">
                </div>
              </div>

              <div class="col-md-1" style="text-align:right;"><label style="font-size:12px"> Name: </label></div>
              <div class="col-md-2">
                <div class="form-group">
                  <input type="text" class="input-sm form-control" name="" value="" id="Name">
                </div>
              </div>

              <div class="col-md-1" style="text-align:right;"><label style="font-size:12px"> From: </label></div>
              <div class="col-md-2">
                <div class="form-group">
                  <input type="email" class="input-sm form-control" name="" value="info@is-intl.com" id="From" disabled>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-1" style="text-align:right;"><label style="font-size:12px"> Remark: </label></div>
              <div class="col-md-6">
                <div class="form-group">
                  <textarea name="name" class="input-sm form-control" rows="3" cols="80" id="Remark"></textarea>
                </div>
              </div>

              <div class="col-md-1" style="text-align:right;"></div>
              <div class="col-md-2">
                <button type="button" class="btn btn-success" name="button" OnClick="javascript:SendBookingToEmail();"> Send Email</button>
              </div>
            </div>
          </form>
        </div>
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
function ChkCity(country) {
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  var url = '{{url('/ChangeDataCityBK')}}';

  var request = $.ajax({
      url: url,
      method: "POST",
      data: {country: country,_token: CSRF_TOKEN},
      dataType: "json"
  });
  request.done(function(data) {
    $("#City").html("");
    $("#Hotel").html("");
    $("#RoomCat").html("");
    var CityList = data;
    $(CityList).each(function() {
      var option = $("<option/>");
       option.val(this.Country+'~'+this.City);
       option.html(this.City);
     $("#City").append(option);
    });
      $("#City").prepend("<option value='0'> SELECT City </option>").val(0);
      $("#Hotel").prepend("").val();
      $("#RoomCat").prepend("").val();
  });
}

function ChkHotel(city) {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = '{{url('/ChangeDataHotel')}}';
    var Country = $("#Country").val();

    var request = $.ajax({
        url: url,
        method: "POST",
        data: {Country: Country, city: city, _token: CSRF_TOKEN},
        dataType: "json"
    });
    request.done(function(data) {
      $("#Hotel").html("");
      $("#RoomCat").html("");
      var HotelList = data;
      $(HotelList).each(function() {
        var option = $("<option/>");
         option.val(this.HotelId);
         option.html(this.Hotel+' - '+this.City);
       $("#Hotel").append(option);
      });
        $("#Hotel").prepend("<option value='0'> SELECT Hotel </option>").val(0);
        $("#RoomCat").prepend("").val();
    });
}

function ChkRoom(hotel){
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var url = '{{url('/ChangeDataRoom')}}';

        var request = $.ajax({
        	  url: url,
        	  method: "POST",
        	  data: {hotel:hotel,_token: CSRF_TOKEN},
        	  dataType: "json"
         });
        request.done(function(data){
        	$("#RoomCat").html("");
        	var RoomCatList = data;
        	 $(RoomCatList).each(function (){
            	 var option = $("<option/>");
                option.val(this.RoomCatData);
                option.html(this.RoomCatData);
        			$("#RoomCat").append(option);
            });
        	 $("#RoomCat").prepend("<option value='0'> SELECT Hotel </option>").val(0);
        });
	}

function searchbooking_load() {
  //$("#Head_Result").remove();
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  var url = '{{url('/SearchDataBooking')}}';
  var ROffice = document.getElementById("ROffice").value;
  //alert(ROffice)
  var Operator = document.getElementById("Operator").value;
  var Country = document.getElementById("Country").value;
  var City = document.getElementById("City").value;
  var Hotel = document.getElementById("Hotel").value;
  var RoomCat = document.getElementById("RoomCat").value;
  var Cancel = document.getElementById("Cancel").value;
  var Status = document.getElementById("Status").value;
  var DOS_Date = document.getElementById("DOS_Date").value;
  var DOS_DateEnd = document.getElementById("DOS_DateEnd").value;
  var HBG_Date = document.getElementById("HBG_Date").value;
  var HBG_DateEnd = document.getElementById("HBG_DateEnd").value;
  var SendMail = 0;
  var Email = "";
  var Name = "";
  var From = "";
  var Remark = "";
  //alert(ROffice+' | '+Operator+' | '+Country+' | '+City+' | '+Hotel+' | '+RoomCat+' | '+Cancel+' | '+Status+' | '+DOS_Date+' | '+DOS_DateEnd+' | '+HBG_Date+' | '+HBG_DateEnd)
        var request = $.ajax({
            url: url,
            method: "POST",
            data: {ROffice: ROffice,
              Operator: Operator,
              Country: Country,
              City: City,
              Hotel: Hotel,
              RoomCat: RoomCat,
              Cancel: Cancel,
              Status: Status,
              DOS_Date: DOS_Date,
              DOS_DateEnd: DOS_DateEnd,
              HBG_Date: HBG_Date,
              HBG_DateEnd: HBG_DateEnd,
              SendMail: SendMail,
              Email: Email,
              Name: Name,
              From: From,
              Remark: Remark,
              _token: CSRF_TOKEN},
              dataType: "text"
          });
          request.done(function(data){
            $("#Head_Result tbody th").remove();
            $("#Data_Result > table").remove();
            $("#Head_Result").show();
            $("#Data_Result").show();
            $("#Head_Result").html("");
            $("#Data_Result > table").html("");

            $("#Data_Result").html(data);
          });
  }

  function booking_exportexcel() {
    //$("#Head_Result").remove();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = '{{url('/SearchDataBooking_Exportexcel')}}';
    var ROffice = document.getElementById("ROffice").value;
    //alert(ROffice)
    var Operator = document.getElementById("Operator").value;
    var Country = document.getElementById("Country").value;
    var City = document.getElementById("City").value;
    var Hotel = document.getElementById("Hotel").value;
    var RoomCat = document.getElementById("RoomCat").value;
    var Cancel = document.getElementById("Cancel").value;
    var Status = document.getElementById("Status").value;
    var DOS_Date = document.getElementById("DOS_Date").value;
    var DOS_DateEnd = document.getElementById("DOS_DateEnd").value;
    var HBG_Date = document.getElementById("HBG_Date").value;
    var HBG_DateEnd = document.getElementById("HBG_DateEnd").value;
    var SendMail = 0;
    var Email = "";
    var Name = "";
    var From = "";
    var Remark = "";
    //alert(ROffice+' | '+Operator+' | '+Country+' | '+City+' | '+Hotel+' | '+RoomCat+' | '+Cancel+' | '+Status+' | '+DOS_Date+' | '+DOS_DateEnd+' | '+HBG_Date+' | '+HBG_DateEnd)
          var request = $.ajax({
              url: url,
              method: "POST",
              data: {ROffice: ROffice,
                Operator: Operator,
                Country: Country,
                City: City,
                Hotel: Hotel,
                RoomCat: RoomCat,
                Cancel: Cancel,
                Status: Status,
                DOS_Date: DOS_Date,
                DOS_DateEnd: DOS_DateEnd,
                HBG_Date: HBG_Date,
                HBG_DateEnd: HBG_DateEnd,
                SendMail: SendMail,
                Email: Email,
                Name: Name,
                From: From,
                Remark: Remark,
                _token: CSRF_TOKEN},
                dataType: "text"
            });
            // request.done(function(data){
            //   $("#Head_Result tbody th").remove();
            //   $("#Data_Result > table").remove();
            //   $("#Head_Result").show();
            //   $("#Data_Result").show();
            //   $("#Head_Result").html("");
            //   $("#Data_Result > table").html("");
            //
            //   $("#Data_Result").html(data);
            // });
    }

  function SendBookingToEmail() {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = '{{url('/SearchDataBooking')}}';
    var ROffice = document.getElementById("ROffice").value;
    //alert(ROffice)
    var Operator = document.getElementById("Operator").value;
    var Country = document.getElementById("Country").value;
    var City = document.getElementById("City").value;
    var Hotel = document.getElementById("Hotel").value;
    var RoomCat = document.getElementById("RoomCat").value;
    var Cancel = document.getElementById("Cancel").value;
    var Status = document.getElementById("Status").value;
    var DOS_Date = document.getElementById("DOS_Date").value;
    var DOS_DateEnd = document.getElementById("DOS_DateEnd").value;
    var HBG_Date = document.getElementById("HBG_Date").value;
    var HBG_DateEnd = document.getElementById("HBG_DateEnd").value;
    var SendMail = 1;
    var Email = document.getElementById("Email").value;
    var Name = document.getElementById("Name").value;
    var From = document.getElementById("From").value;
    var Remark = document.getElementById("Remark").value;
    //alert(ROffice+' | '+Operator+' | '+Country+' | '+City+' | '+Hotel+' | '+RoomCat+' | '+Cancel+' | '+Status+' | '+DOS_Date+' | '+DOS_DateEnd+' | '+HBG_Date+' | '+HBG_DateEnd)
          var request = $.ajax({
              url: url,
              method: "POST",
              data: {ROffice: ROffice,
                Operator: Operator,
                Country: Country,
                City: City,
                Hotel: Hotel,
                RoomCat: RoomCat,
                Cancel: Cancel,
                Status: Status,
                DOS_Date: DOS_Date,
                DOS_DateEnd: DOS_DateEnd,
                HBG_Date: HBG_Date,
                HBG_DateEnd: HBG_DateEnd,
                SendMail: SendMail,
                Email: Email,
                Name: Name,
                From: From,
                Remark: Remark,
                _token: CSRF_TOKEN},
                dataType: "json"
            });
            request.done(function(data){
              alert(data);
              window.location.href="http://g.icstravelgroup.com/Booking2/public/Booking/<?=$ssid?>|<?=$isid?>";
            });
            request.fail(function(data){
            	alert("error");
            });
  }
</script>
@endsection
