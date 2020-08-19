@extends('layouts.master')
@section('pageTitle', 'Restaurant Booking Report')
@section('content')
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{csrf_token()}}" />
</head>
<aside class="right-side">
  <h6 align="right"><a href="https://staff.icstravelgroup.com/php-bin/jojo/Report_V2/RestaurantReport/rest.php" target='_blank'>Old Version</a></h6>

      <h4 style="text-align:center;"><b>Restaurant Booking Managemet System</b></h4><br>
      <div class="box box-primary">
        <div class="box-body">
          <form class="" action="" method="post">
            <div class="row" align="center">
              <div class="col-md-4" style="text-align:right;"><label style="font-size:12px"> Process Booking By Tour ID</label></div>
              <div class="col-md-4">
                <div class="form-group">
                  <input type="text" class="form-control" name="" value="">
                </div>
              </div>

              <div class="col-md-2">
                <input type="button" class="btn btn-info" name="" value="Report" OnClick="javascript:();">
              </div>
            </div>

            <div class="row">
              <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Restaurant</label></div>
              <div class="col-md-5">
                <div class="form-group">
                  <select class="form-control selectTo" name="" id="Restaurant">
                    <option value=""> # SELECT Restaurant # </option>

                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Company</label></div>
              <div class="col-md-3">
                <div class="form-group">
                  <select class="form-control selectTo" name="" id="Company">
                    <option value=""></option>

                  </select>
                </div>
              </div>

              <div class="col-md-1" style="text-align:right;"><label style="font-size:12px"> Inbound</label></div>
              <div class="col-md-3">
                <div class="form-group">
                  <select class="form-control selectTo" name="" id="Inbound">
                    <option value=""></option>

                  </select>
                </div>
              </div>
            </div>

            <!-- <div class="row">
              <div class="col-md-2" style="text-align:right;"></div>
              <div class="col-md-5">
                <h6><b style="color:red;">** Please enter either Tour Date or Restaurant Date</b></h6>
              </div>
            </div> -->

            <div class="row">
              <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Start Tour</label></div>
              <div class="col-md-3">
                <div class="input-daterange input-group" id="datepicker">
                  <input type="text" class="input-sm form-control datepicker" name="HBG_Date" id="Start_Date" />
                  <span class="input-group-addon input-sm">to</span>
                  <input type="text" class="input-sm form-control datepicker" name="HBG_DateEnd" id="Start_DateEnd" />
                </div>
              </div>

              <div class="col-md-1" style="text-align:right;"><label style="font-size:12px"> Restaurant Date</label></div>
              <div class="col-md-3">
                <div class="input-daterange input-group" id="datepicker">
                  <input type="text" class="input-sm form-control datepicker" name="HBG_Date" id="Restaurant_Date" />
                  <span class="input-group-addon input-sm">to</span>
                  <input type="text" class="input-sm form-control datepicker" name="HBG_DateEnd" id="Restaurant_DateEnd" />
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-2" style="text-align:right;"><label style="font-size:12px"> Booking Date <br> (Restaurant Box)</label></div>
              <div class="col-md-3">
                <div class="input-daterange input-group" id="datepicker">
                  <input type="text" class="input-sm form-control datepicker" name="HBG_Date" id="Book_Date" />
                  <span class="input-group-addon input-sm">to</span>
                  <input type="text" class="input-sm form-control datepicker" name="HBG_DateEnd" id="Book_DateEnd" />
                </div>
              </div>

              <div class="col-md-1" style="text-align:right;"><label style="font-size:12px"> Cancelled</label></div>
              <div class="col-md-2">
                <select class="input-sm form-control" name="" id="Status">
                    <option value="">Include</option>
                    <option value="Exclude" selected>Exclude</option>
                </select>
              </div>
            </div>

            <!-- <div class="row">

            </div> -->

            <div class="row" style="text-align:center;">
              <input type="button" class="btn btn-info" name="" value="Report" OnClick="javascript:searchrestaurant_load();">
              <input type="button" class="btn btn-warning" name="" value="Reset" OnClick="javascript:OnReset();">
              <!-- <p><b>**Report must run from current date**</b></p> -->
            </div>
          </form><hr>

          <div class="row">
            <div class="col-md-4" style="text-align:right;">
              <p style="font-size:11px; color:red;"> <b>** Status :: OK </b>= Confirmed ,<b> CXL</b> = Cancelled ,<b> RQ </b>= Booked</p>
            </div>
          </div>
          <div class="row" align="center" id="show_report" style="overflow-x:auto;"> </div>
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
      //alert(country)
      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
      var url = '{{url('/ChangeDataCity')}}';
      var request = $.ajax({
          url: url,
          method: "POST",
          data: {country:country,
            _token: CSRF_TOKEN},
          dataType: "json"
       });
      request.done(function(data){
        $("#City").html("");
        $("#Restaurant").html("");
        var CityList  = data;
         $(CityList).each(function (){
             var option = $("<option/>");
              option.val(this.City);
              option.html(this.City);
            $("#City").append(option);
          });
         $("#City").prepend("<option value='0'> SELECT City </option>").val(0);
      });
    }

    function OnReset() {
      window.location.reload(true);
        }

    function ChkRestaurant(city) {
      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
      var url = '{{url('/ChangeDataRestaurant')}}';
      var country = document.getElementById("Country").value;
      //alert(url)
      var request = $.ajax({
          url: url,
          method: "POST",
          data: {city:city,
            country:country,
            _token: CSRF_TOKEN},
          dataType: "json"
       });
      request.done(function(data){
        //alert(data)
        $("#Restaurant").html("");
        var RestaurantList = data;
         $(RestaurantList).each(function (){
             var option = $("<option/>");
              option.val(this.RestaurantId);
              option.html(this.Restaurant+' ('+this.City+')');
            $("#Restaurant").append(option);
          });
         $("#Restaurant").prepend("<option value='0'> SELECT Restaurant </option>").val(0);
      });
    }

    function searchrestaurant_load() {
      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
      var url = '{{url('/SearchDataRestaurant')}}';
      var Country = document.getElementById("Country").value;
      var City = document.getElementById("City").value;
      var TORestaurant = document.getElementById("TORestaurant").value;
      var Restaurant = document.getElementById("Restaurant").value;
      var Company = document.getElementById("Company").value;
      var Inbound = document.getElementById("Inbound").value;
      var Start_Date = document.getElementById("Start_Date").value;
      var Start_DateEnd = document.getElementById("Start_DateEnd").value;
      var Restaurant_Date = document.getElementById("Restaurant_Date").value;
      var Restaurant_DateEnd = document.getElementById("Restaurant_DateEnd").value;
      var Book_Date = document.getElementById("Book_Date").value;
      var Book_DateEnd = document.getElementById("Book_DateEnd").value;
      var Status = document.getElementById("Status").value;
//alert(Restaurant)
      var request = $.ajax({
          url: url,
          method: "POST",
          data: {Country:Country,
            City:City,
            TORestaurant:TORestaurant,
            Restaurant:Restaurant,
            Company:Company,
            Inbound:Inbound,
            Start_Date:Start_Date,
            Start_DateEnd:Start_DateEnd,
            Restaurant_Date:Restaurant_Date,
            Restaurant_DateEnd:Restaurant_DateEnd,
            Book_Date:Book_Date,
            Book_DateEnd:Book_DateEnd,
            Status:Status,
            _token: CSRF_TOKEN},
          dataType: "text"
       });
       request.done(function(data){
         // alert(data);
         $("#show_report").html(data);
         //
       });
       request.fail(function(data){
         alert("error");
       });
    }
</script>
@endsection
