@extends('layouts.master')
@section('pageTitle', 'Guide Calling Report')
@section('content')
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{csrf_token()}}" />
</head>
<aside class="right-side">

    <div class="box box-primary">
      <div class="box-body">
        <form action="{{ url("/GuideCalling/export") }}" method="post" id="frm" name="frm">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">

          <div class="row">
            <h4 style="text-align:center;"><b>Booking and Quotation Assignment</b></h4>
          </div>
          <?php
            var_dump($tour_data);
          ?>
          @if($type == "booking")
            <div class="row">
              <table width="95%" cellspacing="2" cellpadding="2" border="0">
                <tr>
                  <th colspan="5" align="left">Total <span style="color:#F00">{{ count($tour_data) }}</span>  record(s)</th>
                </tr>
                <tr>
                  <th bgcolor="#CCCCCC">TourID</th>
                  <th bgcolor="#CCCCCC">Client</th>
                  <th bgcolor="#CCCCCC">Tour Start Date</th>
                  <th bgcolor="#CCCCCC">Tour End Date</th>
                  <th bgcolor="#CCCCCC">Pax</th>
                </tr>

                @foreach ($tour_data as $data)
                <tr>
                  <td height="23" align="center">{{ $data->TourId }}</td>
                  <td align="left">{{ $data->Clients }}</td>
                  <td align="center">{{ $data->TourSDate }}</td>
                  <td align="center">{{ $data->TourEDate }}</td>
                  <td align="center">{{ $data->NoPax }}</td>
                </tr>
                @endforeach

              </table>
            </div>
          @else

          @endif
      </div>
    </div>
</aside>
  @include('layouts.inc-scripts')
<script type="text/javascript">
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

$(document).ready(function () {

});


</script>
@endsection
