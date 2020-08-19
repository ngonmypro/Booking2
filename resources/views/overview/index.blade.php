@extends('layouts.master')
@section('pageTitle', 'Booking Overview')
@section('content')
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{csrf_token()}}" />
  <style type="text/css">
  body {
  	margin-left: 0px;
  	margin-top: 0px;
  	margin-right: 0px;
  	margin-bottom: 0px;
  	font-size: 10pt;
  	font-family:tahoma,Arial, Helvetica, sans-serif !important;
  }
  .tableBorder{border: solid #dddddd 1px;}
  .tdBorder{border: solid #dddddd 1px;}
  #trTourDescription p{ padding:1px; margin:0px;}

  ul{margin: 0; padding: 0;}
  ul li {margin: 0px; position: relative; padding: 0px 0; cursor: pointer; float: left;  list-style: none;}
  ul li strong{ padding-left:5px; white-space:nowrap;}
  .ui-widget{font-family:Arial, Helvetica, sans-serif !important;  font-size:1em !important; cursor: pointer; cursor: hand;}
  .ui-widget-header{font-weight:normal !important;}

  .fontGreenBold{color:#060; font-weight:bolder;}
  .bgYellow{background-color:#FF9;}
  .fontGray{color:#999;}
  .fontBlue{color:blue;}

  </style>
  <script type="text/javascript">
  function OpenNewWindow(XXvars,Xnew,features) {
    	window.open(XXvars,Xnew,features);
  }
  </script>
</head>

<body>
  @php
  //var_dump($theme,$tour);
  if(count($tour)>0){
    foreach ($tour as $value) {
      $location_id = $value->LocationId;
      $to_ref = $value->ToRef;
      $ccode = $value->Ccode;
      $client = $value->Clients;
      $no_pax = $value->NoPax;
      $tour_start =  date("d-M-Y", strtotime($value->TourStartDate));
      $tour_end = date("d-M-Y", strtotime($value->TourEndDate));
      $day = $value->days;
      $service = $value->Services;
      $remark = $value->Remarks;
      $bookdate = $value->BookingDate;
      $company = $value->CompanyDesc;
      $country = $value->Country;

      $tc = $value->UName;
      $td = $value->TravelDesignerName;

      $tour_description = $value->TourDescription;
      $shortcut = $value->shortcut;
      $email = $value->Email;
      $cxl_date = $value->CxlDate;
      $cxl = $value->Cancelled;
      $special_treat = $value->SpecialTreat;
      $th_supplier_code = $value->THSupplierCode;
      $cdate = date("d-M-Y H:m:s", strtotime($value->cdate));

      $cnt_logo = $value->cnt_logo;
      $cnt_doc = $value->cnt_doc;
    }
  }else{
    echo "Please check booking.";
    exit();
  }
  @endphp
<div id="contentAll">
  <table width="97%" border="0" align="center" cellpadding="5" cellspacing="0">
    <tr class="trHeader">
      <td bgcolor="#CCCCCC">
        <form id="form1" name="form1" method="post" action="">
          <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="90%" align="left">
                @if(!empty($tc))
                  IS User/ Travel Consultant: {{ $tc }}<br/>
                @endif
                @if(!empty($td))
                  Travel Designer: {{ $td }}
                @endif
              </td>
              <td align="right">
                <a href="javascript:window.print();">Printout</a>
              </td>
            </tr>
          </table>
        </form>
      </td>
    </tr>

    <tr class="trHeader">
      <td>
        <table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="2" style="font-size:18px; font-weight:bold;">
              {{ $client }}
            </td>
          </tr>
          <tr>
            <td>
              <strong>Tour Date :</strong> {{ $tour_start }}
            </td>
            <td align="right">
              <strong>Entered on : </strong>{{ $cdate }}
            </td>
          </tr>
        </table>
      </td>
    </tr>

    <tr class="trHeader">
      <td>
        <table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td width="25%" valign="top" align="right">
              <strong>Our Ref :</strong>
            </td>
            <td align="left" valign="top">{{ $tourid }}</td>
            <td style="font-size:18px; font-weight:bold;" nowrap="nowrap" align="right">

              @if(!empty($special_treat))
                <a href="https://staff.icstravelgroup.com/crm/Location/editlocation.php?sessionid={{ $ssid }}&loc_id={{ $location_id }}&tab=8&readonly=0" target="_crm">{{ $company }}</a>
              @else
                {{ $company }}
              @endif

              @if($cnt_logo>0)
                <br/><a href="https://staff.icstravelgroup.com/bookingonline_v2/OverviewTOLogoList.php?sessionid={{ $ssid }}&loc_id={{ $location_id }}" target="_crm"><font size="2">Logo</font></a>
              @endif

              @if($cnt_doc>0)
                <br/><a href="https://staff.icstravelgroup.com/bookingonline_v2/OverviewTODocumentList.php?sessionid={{ $ssid }}&loc_id={{ $location_id }}" target="_crm"><font size="2">Document</font></a>
              @endif
            </td>
          </tr>

          <tr>
            <td valign="top" align="right">
              <strong>Country:</strong>
            </td>
            <td align="left" valign="top">
              {{ implode(" - ",$tour_country) }} <a target="_blank" href="https://staff.icstravelgroup.com/cgi-local/haddress.cgi?{{  $tourid }}+1+1+{{ $ccode }}" style="text-decoration:none;color:green;">In country office addresses</a>
            </td>
            <td nowrap="nowrap" align="right">( {{  $country }} )</td>
          </tr>

          <tr>
            <td valign="top" align="right"><strong>Your ref:</strong></td>
            <td align="left">{{ $to_ref }}</td>
            <td>&nbsp;</td>
          </tr>

          <tr>
            <td valign="top" align="right"><strong>Start of Tour:</strong></td>
            <td align="left">{{ $tour_start }}</td>
            <td>&nbsp;</td>
          </tr>

          <tr>
            <td valign="top" align="right"><strong>End of Tour:</strong></td>
            <td align="left">{{ $tour_end }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $day+1 }} <strong>days/</strong> {{ $day }} <strong>nights</strong></td>
            <td>&nbsp;</td>
          </tr>

          <tr>
            <td valign="top" align="right"><strong>Pax:</strong></td>
            <td align="left">{{ $no_pax }}</td>
            <td>&nbsp;</td>
          </tr>

          <tr>
            <td valign="top" align="right"><strong>Services:</strong></td>
            <td align="left">{{ $service }}</td>
            <td>&nbsp;</td>
          </tr>

          <tr>
            <td valign="top" align="right"><strong>Remarks:</strong></td>
            <td align="left">@php echo nl2br($remark) @endphp</td>
            <td>&nbsp;</td>
          </tr>

          @if(!empty($tour_description))
          <tr>
            <td valign="top" align="right"><strong>Tour description (invisible on ICS website):</strong></td>
            <td align="left">@php echo nl2br($tour_description) @endphp</td>
            <td>&nbsp;</td>
          </tr>
          @endif

          @if(!empty($th_supplier_code))
          <tr>
            <td valign="top" align="right"><strong>TH Supplier Code:</strong></td>
            <td align="left">@php nl2br($th_supplier_code) @endphp</td>
            <td>&nbsp;</td>
          </tr>
          @endif

          @if(strstr($ccode,'Myanmar'))
          <tr>
            <td align="right" valign="top"><strong> Note:</strong></td>
            <td colspan="2">
              <table width="97%" align="center" cellspacing="3">
                <tbody>
                  <tr>
                    <td align="left">Please remind your clients to bring ample cash in US dollars or Euros to cover all personal expenses for their entire trip as Kyat, US dollars and Euros are the only currencies accepted in Myanmar.   Both US dollars and Euros can be exchanged at banks and money exchange counters. Credit cards and travelers cheques may not generally be used in Myanmar and other methods of wiring money into the country are limited.   Although there are some new ATM machines and credit card merchant facilities, at this stage, we cannot guarantee these will function properly.  Please inform clients to be aware when using US Dollar and Euro bank notes in Myanmar that the notes should be in very good condition (not old, folded, stained or damaged) as these will not be accepted at government offices including the immigration department, most hotels and restaurants in Myanmar.  NEW foreign currency bills are preferred.  For further information, please see our Travel Information Exchange at   <a href=\"https://staff.icstravelgroup.com/travelnews/travhome.htm\" target=money><font color=blue>HERE</a>.</td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
          @endif

        </table>
      </td>
    </tr>

    @php
      if(count($change)>0){
    @endphp
    <tr>
      <td bgcolor="#CCCCCC" class="ui-widget-header ui-corner-all" onclick="showHide(this,'ChangeBox');">
        <ul class="ui-widget ui-helper-clearfix">
          <li><span id="spanChangeBox" class="ui-icon ui-icon-circle-triangle-e" ></span></li>
          <li><strong>CHANGE BOX</strong></li>
        </ul>
      </td>
    </tr>

    <tr id="trChangeBox">
      <td>
        <table class="tableBorder"  width="97%" align="center" border="1" bordercolor="#dddddd" cellspacing="0">
          <tbody>
            <tr bgcolor="#eeeeee">
              <th width="200" align="left" class="tdBorder">Change Date</th>
              <th align="left" class="tdBorder">Change history for this booking</th>
            </tr>
            @php
            foreach ($change as $value){
              if(empty($value->ChangesDate)){
                $change_date = '';
              }else{
                $change_date = date("d-M-Y", strtotime($value->ChangesDate));
              }

              $change_text = nl2br($value->Changes);
            @endphp
            <tr>
              <td align="left" class="tdBorder">{{ $change_date }}&nbsp;</td>
              <td align="left" class="tdBorder">@php echo $change_text; @endphp&nbsp;</td>
            </tr>
            @php
            }
            @endphp
          </tbody>
        </table>
      </td>
    </tr>
    @php
      }
    @endphp

    @php
      if(count($pax)>0){
    @endphp
    <tr class="trHeader">
      <td bgcolor="#CCCCCC">
        <strong>PAX LIST</strong>
      </td>
    </tr>

    <tr id="trPaxList" style="display: table-row;">
      <td>

        <table class="tableBorder" width="97%" cellspacing="0" bordercolor="#dddddd" border="1" align="center">
          <tbody>
            <tr bgcolor="#eeeeee">
              <th class="tdBorder" width="2%" align="left">No.</th>
              <th class="tdBorder" width="11%" align="left">Last Name</th>
              <th class="tdBorder" width="13%" nowrap="nowrap" align="center">First Name</th>
              <th class="tdBorder" width="5%" nowrap="nowrap" align="left">Title</th>
              <th class="tdBorder" width="13%" nowrap="nowrap" align="left">Pass. No</th>
              <th class="tdBorder" width="14%" nowrap="nowrap" align="left">Nationality</th>
              <th class="tdBorder" width="14%" nowrap="nowrap" align="left">Birth Date</th>
              <th class="tdBorder" width="16%" nowrap="nowrap" align="left">Room</th>
              <th class="tdBorder" width="12%" nowrap="nowrap" align="left">Entered</th>
            </tr>
            @php
              $x = 0;
              $total = 0;
              $sgl = 0;
              $dbl = 0;
              $twn = 0;
              $tpl = 0;
              foreach ($pax as $value){
                $birth_date = empty($value->BirthDate)?'':date("d-M-Y", strtotime($value->BirthDate));
                $enter_date = empty($value->EnterDate)?'':date("d-M-Y", strtotime($value->EnterDate));
            @endphp
            <tr>
              <td align="left" class="tdBorder">@php echo ++$x; @endphp&nbsp;</td>
              <td align="left" class="tdBorder">{{ $value->LastName }}&nbsp;</td>
              <td align="left" nowrap="nowrap" class="tdBorder">{{ $value->FirstName }}</td>
              <td align="left" nowrap="nowrap" class="tdBorder">{{ $value->Mr_Mrs }}</td>
              <td align="left" nowrap="nowrap" class="tdBorder">{{ $value->PassportNo }}</td>
              <td align="left" nowrap="nowrap" class="tdBorder">{{ $value->Nationality }}</td>
              <td align="left" nowrap="nowrap" class="tdBorder">{{ $birth_date }}</td>
              <td align="left" nowrap="nowrap" class="tdBorder">{{ $value->SGL_DBL }}</td>
              <td align="left" nowrap="nowrap" class="tdBorder">{{ $enter_date }}</td>
            </tr>
            @php
                if(strlen($value->Remark)> 0 ){
            @endphp
              <tr>
                <td class="tdBorder">&nbsp;</td>
                <td colspan="8" align="left" class="tdBorder"><strong>Remark : </strong>{{ $value->Remark }}&nbsp;</td>
              </tr>
            @php
                }
                $sgl_dbl = strtoupper($value->SGL_DBL);
                if(strstr($sgl_dbl,'SGL')){
                  $sgl++;
                }else if(strstr($sgl_dbl,'DBL')){
                  $dbl++;
                }elseif(strstr($sgl_dbl,'TWN')){
                  $twn++;
                }elseif(strstr($sgl_dbl,'TPL')){
                  $tpl++;
                }
                $total = $sgl + ($dbl) + ($twn) + ($tpl);
              }

            @endphp
          </tbody>
        </table>

        <!-- Pax Remark -->
        <table width="97%" align="center">
          <tbody>
            <tr>
              <td valign="top">ROOMS&nbsp;&nbsp;&nbsp;&nbsp;SGL:&nbsp;{{ $sgl }}&nbsp;&nbsp;&nbsp;&nbsp;DBL:&nbsp;{{ ceil($dbl/2) }}&nbsp;&nbsp;&nbsp;&nbsp;TWN:&nbsp;{{ ceil($twn/2) }}&nbsp;&nbsp;&nbsp;&nbsp;TPL:&nbsp;{{ ceil($tpl/3) }}&nbsp;&nbsp;&nbsp;&nbsp;Pax:&nbsp;{{ $total }}</td>
            </tr>

            <tr>
              <td valign="top">
                Please send us the pax details/updates by email
                <a href="javascript:OpenNewWindow('https://staff.icstravelgroup.com/cgi-local/haddress.cgi?BKG1901233+1+6+chk1+2 ','evtlet','scrollbars=yes, width=640, height=550, toolbar=no, location=no, directories=no, status=no, menubar=yes, resizable=yes')">
                <font size="1" color="green">E.V.T. letter/Myanmar</font></a><font size="1" color="green"></font>
              </td>
            </tr>
          </tbody>
        </table>

        <table width="97%" align="center">
        <tbody>
        <tr>
        <td valign="top">
        ** Please check the travellers' visa requirements
        @php
          $features="scrollbars=yes, width=740, height=500, toolbar=no, location=no, directories=no, status=no, menubar=yes, resizable=yes";
          if(strstr($ccode,"V")){
            $xvisa="https://staff.icstravelgroup.com/vn/evisacrossings.htm";
            echo  "<a href=\"javascript:OpenNewWindow('$xvisa','avisa','$features');\"><font color=blue>Vietnam</font></a> ";
          }
          if(strstr($ccode,"C")){
            $xvisa="https://staff.icstravelgroup.com/cmb/visacrossings.htm";
            echo  "<a href=\"javascript:OpenNewWindow('$xvisa','avisa','$features');\"><font color=blue>Cambodia</font></a> ";
          }
          if(strstr($ccode,"L")){
            $xvisa="https://staff.icstravelgroup.com/laos/evisacrossings.htm";
            echo  "<a href=\"javascript:OpenNewWindow('$xvisa','avisa','$features');\"><font color=blue>Laos</font></a> ";
          }
          if(strstr($ccode,"M")){
            $xvisa="https://staff.icstravelgroup.com/mya/evisacrossings.htm";
            echo "<a href=\"javascript:OpenNewWindow('$xvisa','avisa','$features');\"><font color=blue>Myanmar</font></a> ";
          }
        @endphp
        </tr>
        </tbody>
        </table>

      </td>
    </tr>
    @php
      }
    @endphp

    @php
      if(count($visa)>0){
    @endphp
    <tr>
      <td bgcolor="#CCCCCC" class="ui-widget-header ui-corner-all">
        <ul id="icons" class="ui-widget ui-helper-clearfix">
          <li><span id="spanVisa" class="ui-icon ui-icon-circle-triangle-e" ></span></li>
          <li>
            <strong>
            <a>VISA</a>
            </strong>
          </li>
        </ul>
      </td>
    </tr>
    <tr id="trVisa">
      <td><table class="tableBorder" width="97%" align="center" border="1" bordercolor="#dddddd" cellspacing="0">
        <tbody>
          <tr bgcolor="#eeeeee">
            <th width="13%" align="left" class="tdBorder">Visa</th>
            <th width="15%" align="center" class="tdBorder">Kind of visa</th>
            <th width="7%" align="center" nowrap="nowrap" class="tdBorder">Multiple</th>
            <th width="9%" align="center" nowrap="nowrap" class="tdBorder">Entry at</th>
            <th width="13%" align="center" nowrap="nowrap" class="tdBorder">Urgency</th>
            <th width="13%" align="center" nowrap="nowrap" class="tdBorder">Start</th>
            <th width="9%" align="center" nowrap="nowrap" class="tdBorder">Submitted</th>
            <th width="21%" align="center" nowrap="nowrap" class="tdBorder">Status/Approval</th>
          </tr>
          @php
            foreach($visa as $value){
              if($value->IsMultipleEntry==1){
                $multiple_entry = "Yes";
              }else{
                $multiple_entry = "No";
              }

              if(empty($value->EnterWhen)){
                $entry_when = "";
              }else{
                $entry_when = $value->EnterWhen;
              }

              if(empty($value->SubmittedOn)){
                $submitted_on = "";
              }else{
                $submitted_on = $value->SubmittedOn;
              }
          @endphp
          <tr>
            <td align="left" class="tdBorder">{{ $value->VisaWhere }}&nbsp;</td>
            <td align="center" class="tdBorder">{{ $value->KindOfVisa }}</td>
            <td align="center" nowrap="nowrap" class="tdBorder">{{ $multiple_entry }}</td>
            <td align="center" nowrap="nowrap" class="tdBorder">{{ $value->EntryPort }}</td>
            <td align="left" nowrap="nowrap" class="tdBorder">{{ $value->Urgency }}</td>
            <td align="left" nowrap="nowrap" class="tdBorder">{{ $entry_when }}</td>
            <td align="center" nowrap="nowrap" class="tdBorder">{{ $submitted_on }}</td>
            <td align="center" nowrap="nowrap" class="tdBorder">{{ $value->Status }}
              &nbsp;
              {{ $value->Appcode }}
            </td>
          </tr>
          @php
            }
          @endphp
          </tbody>
      </table>

        <table width="97%" align="center">
          <tbody>
            <tr>
              <td valign="top"><a target="_blank">
              List of approval codes / visa application procedures
      			  </a>&nbsp;</td>
            </tr>
          </tbody>
      </table>
      <table width="97%" align="center">
          <tbody>

            <tr>
              <td valign="top">
                ** Please check the travellers\' visa requirements&nbsp;
              </td>
            </tr>
          </tbody>
        </table>
        </td>
    </tr>
    @php
      }
    @endphp

    @php
      if(count($flight)>0){
    @endphp
    <tr>
      <td bgcolor="#CCCCCC" class="ui-widget-header ui-corner-all" onclick="showHide(this,'Airline');">
        <ul id="icons2" class="ui-widget ui-helper-clearfix">
          <li><span id="spanAirline" class="ui-icon ui-icon-circle-triangle-e" ></span></li>
          <li><strong>FLIGHTS</strong></li>
        </ul>
      </td>
    </tr>

    <tr id="trAirline">
      <td>
        <table class="tableBorder" width="97%" align="center" border="1" bordercolor="#dddddd" cellspacing="0">
          <tbody>
            <tr bgcolor="#eeeeee">
              <th width="10%" align="center" nowrap="nowrap" class="tdBorder">Date</th>
              <th width="7%" align="center" nowrap="nowrap" class="tdBorder">Flight No.</th>
              <th width="10%" align="left" class="tdBorder">Airlines</th>
              <th width="5%" align="center" nowrap="nowrap" class="tdBorder">Class</th>
              <th width="10%" align="center" nowrap="nowrap" class="tdBorder">From</th>
              <th width="10%" align="center" nowrap="nowrap" class="tdBorder">To</th>
              <th width="10%" align="center" nowrap="nowrap" class="tdBorder">Time</th>
              <th width="5%" align="center" nowrap="nowrap" class="tdBorder">Pax</th>
              <th width="15%" align="center" class="tdBorder">ICS remark for flight</th>
              <th width="10%" align="center" nowrap="nowrap" class="tdBorder">Status</th>
              <th width="10%" align="left" class="tdBorder"><strong>Supplier<br/>(ICS internal use only)</strong></th>
              <th width="15%" align="left" class="tdBorder">Service<br/>(ICS internal use only)</th>
              <th width="" align="left" class="tdBorder">Phone</th>
            </tr>
            @php
              foreach($flight as $value){
                $bgColor = 'style="color:blue;"';
                if($value->Status == "CXL"){
                  $bgColor = 'style="color:#999;"';
                }else if($value->Status == "WL"){
                  $bgColor = 'style="color:red;"';
                }else if($value->IsIntlByClient == "1"){
                  $bgColor = 'style="color:black;"';
                }

                $phone = "";
                if(!empty($value->CountryPrefix) AND !empty($value->Phone)){
                  $phone .= $value->CountryPrefix." ";
                }
                if(!empty($value->CityPrefix)  AND !empty($value->Phone)){
                  $phone .= $value->CityPrefix." ";
                }
                if(!empty($value->Phone)){
                  $phone .= $value->Phone;
                }
            @endphp
                <tr {{ $bgColor }}>
                  <td align="center" nowrap="nowrap" class="tdBorder">{{ $value->Fbdate }}</td>
                  <td align="center" nowrap="nowrap" class="tdBorder">{{ $value->FlightNo }}</td>
                  <td align="left" class="tdBorder"><nobr>{{ $value->Airline }}</nobr></td>
                  <td align="center" nowrap="nowrap" class="tdBorder">{{ $value->class }}</td>
                  <td align="left" nowrap="nowrap" class="tdBorder">{{ $value->FlightFrom }}</td>
                  <td align="left" nowrap="nowrap" class="tdBorder">{{ $value->FlightTo }}</td>
                  <td align="center" nowrap="nowrap" class="tdBorder">{{ $value->dep }} - {{ $value->arr }}</td>
                  <td align="center" nowrap="nowrap" class="tdBorder">{{ $value->Pax }}</td>
                  <td align="center" class="tdBorder">
                    <a {{ $bgColor }} href="#" onclick="window.open('/cgi-local/flight/flighttariff/eticket.php?tourid={{ $tourid }}&FBId={{ $value->FBId }}','flight','scrollbars=yes,width=790, height=550, toolbars=no, location=no, status=no, menubar=no, resizeable=no');return false;" style="text-decoration:none; font-weight:bolder;">@php $value->intl==1?"TO<br />":''; @endphp
                      {{ $value->remark }}
                    </a>
                  </td>
                  <td align="center" nowrap="nowrap" class="tdBorder">{{ $value->Status }}</td>
                  <td align="left" class="tdBorder">{{ $value->CompanyName }}&nbsp;</td>
                  <td align="left" class="tdBorder">{{ $value->ServiceName }}&nbsp;</td>
                  <td align="left" class="tdBorder">{{ $phone }}</td>
                </tr>
            @php
                if (strstr($value->Airline,"Vietnam Airlines")) {
                  $vnair="<b>Vietnam Airlines impose special booking conditions. For details
                  <a href=\"https://staff.icstravelgroup.com/html/vnairflightpolicy.htm\" target=\"vnair\">
                  <font color=\"blue\">click here</font>
                  </a>
                  </b><br>";
                }

                $cb="Cambodia Angkor Air impose special booking conditions. For details";
                $cb1=" click here";

                if (strstr($value->Airline,"Cambodia Angkor Air")) {
                  $cbair="<b>$cb<a href=\"https://staff.icstravelgroup.com/html/cmbairflightpolicy.htm\" target=\"cbair\"><font color=\"blue\">$cb1</font></a></b><br>";
                }
              }
            @endphp
          </tbody>
        </table>

        <table width="97%" align="center" border="0" cellspacing="0" cellpadding="2">
          <tr>
          <td width="150" valign="top"><strong>- Remarks:</strong></td>
            <td>
              @php
              if(!empty($vnair)) echo "$vnair";
              if(!empty($cbair)) echo "$cbair";
              @endphp
            </td>
          </tr>
        </table>

      </td>
    </tr>
    @php
      }
    @endphp

    @php
      if(count($hotel)>0){
    @endphp
    <tr>
      <td bgcolor="#CCCCCC" class="ui-widget-header ui-corner-all" onclick="showHide(this,'Hotels');">
        <ul id="icons6" class="ui-widget ui-helper-clearfix">
          <li><span id="spanHotels" class="ui-icon ui-icon-circle-triangle-e"></span></li>
          <li><strong>HOTELS</strong></li>
        </ul>
      </td>
    </tr>

    <tr id="trHotels">
      <td>
        <table class="tableBorder" width="97%" align="center" border="1" bordercolor="#dddddd" cellpadding="2" cellspacing="0">
          <tbody>
            <tr bgcolor="#eeeeee">
              <th width="10%" align="center" class="tdBorder">In</th>
              <th width="10%" align="center" class="tdBorder">Out</th>
              <th width="27%" align="left" class="tdBorder">Hotel / Remarks</th>
              <th width="20%" align="left" class="tdBorder">Room Category</th>
              <th width="5%" align="center" class="tdBorder">Sgl</th>
              <th width="5%" align="center" class="tdBorder">Dbl</th>
              <th width="5%" align="center" class="tdBorder">Twn</th>
              <th width="5%" align="center" class="tdBorder">Tpl</th>
              <th width="10%" align="center" class="tdBorder">Status</th>
            </tr>

            @php
              $in = "";
              $out = "";
              $status = "";

              foreach ($hotel as $value){

                $classColor2 = "";
                $classColor1 = ($value->Status=="CXL"||$value->Status=="FULL")?' color:#999; ':'';
                if(strstr($value->bk_remark,'HOBICS') or  (strstr(strtolower($value->bk_remark),'allotment'))){
                  $classColor2 = ' background-color:yellow; ';
                }
                $classColor3 = ($in==$value->CheckIn&&$out==$value->CheckOut&&$value->Status=='OK'&&$value->HideAlternativeRemark==0)?' color:#060; font-weight:bolder; ':'';
                $classColor4 = (empty($classColor1) && empty($classColor2) && empty($classColor3))?' color:blue; ':'';

                $styLink ="";
                if($value->Status=="WL")
                {
                  $classColor1 = "";
                  $classColor2 = "";
                  $classColor3 = "";
                  $classColor4 = "";
                  $styLink = "color:black;";
                }

                $hotelId =  $value->HotelId;
                $hotelName = $value->Hotel;
                $rtype = $value->RoomCategory;
                $hcity = $value->hcity;
                $msrepl_tran_version = $value->msrepl_tran_version;
                $CheckIn = $value->CheckIn;
                $CheckOut = $value->CheckOut;

                $vhtml = "$hotelName, $hcity ";

                if(
                  $in==$value->CheckIn AND
                  $out==$value->CheckOut AND
                  $value->Status=='OK' AND
                  !$value->HideAlternativeRemark
                )
                {
                  $vhtml .= "(alternative hotel)";
                }
            @endphp

            <tr style="<{{ $classColor1 }} {{ $classColor2 }} {{ $classColor3 }} {{ $classColor4 }}" >
              <td align="center" class="tdBorder">{{$value->CheckIn}}</td>
              <td align="center" class="tdBorder">{{$value->CheckOut}}</td>
              <td align="left" class="tdBorder">
                @php
                echo $vhtml;
                if(!empty($value->bk_remark))
                {
                @endphp
                <br/>- Note : {{ $value->bk_remark }}
                @php
                }
                @endphp
              </td>
              <td align="left" class="tdBorder">{{$value->RoomCategory}}</td>
              <td align="center" class="tdBorder">{{$value->Sgl}}</td>
              <td align="center" class="tdBorder">{{$value->Dbl-$value->Twn}}</td>
              <td align="center" class="tdBorder">{{$value->Twn}}</td>
              <td align="center" class="tdBorder">{{$value->Tpl}}</td>
              <td align="center" class="tdBorder">{{$value->Status}}</td>
            </tr>
            @php
                $in = $value->CheckIn;
                $out = $value->CheckOut;
                $status = $value->Status;
              }
            @endphp
          </tbody>
        </table>

        <table width="97%" border="0" align="center" cellpadding="2" cellspacing="0">
          <tr>
            <td>
              &nbsp;
            </td>
          </tr>
          <tr>
            <td><a href="btn_hotel_inboundRemark.php?tourid={{ $tourid }}" target="_blank">* Inbound Remarks</a>&nbsp;</td>
          </tr>
        </table>
      </td>
    </tr>
    @php
      }
    @endphp

    @php
      if(count($restaurant)>0){
    @endphp
    <tr>
      <td bgcolor="#CCCCCC" class="ui-corner-all ui-widget-header " onclick="showHide(this,'Restaurant');">
        <ul id="icons7" class="ui-widget ui-helper-clearfix">
          <li><span id="spanRestaurant" class="ui-icon ui-icon-circle-triangle-e" ></span></li>
          <li><strong>RESTAURANTS</strong></li>
        </ul>
      </td>
    </tr>

    <tr id="trRestaurant">
      <td>
        <table class="tableBorder" width="97%" align="center" border="1" bordercolor="#ddd" cellspacing="0">
          <tbody>
            <tr bgcolor="#eeeeee">
              <th width="10%" align="center" class="tdBorder">Date</th>
              <th width="15%" align="center" class="tdBorder">City</th>
              <th width="30%" align="left" class="tdBorder">Restaurant</th>
              <th width="10%" align="center" class="tdBorder">Meal</th>
              <th width="20%" align="center" class="tdBorder">Menu</th>
              <th width="5%" align="center" class="tdBorder">Pax</th>
              <th width="10%" align="center" class="tdBorder">Time</th>
              <th width="10%" align="center" class="tdBorder">Status</th>
            </tr>

            @php
              foreach($restaurant as $value){
                if ( strtoupper($value->Status) == "CONFIRMED") {
                  $sts="OK";
                }elseif (strtoupper($value->Status) == "OK") {
                  $sts="OK";
                }elseif (strtoupper($value->Status) == "CANCELLED") {
                  $sts="CXL";
                }elseif (strtoupper($value->Status) == "CXL") {
                  $sts="CXL";
                }elseif (strtoupper($value->Status) == "BOOKED") {
                  $sts="RQ";
                }elseif (strtoupper($value->Status) == "RCX") {
                  $sts="CXL";
                }elseif (strtoupper($value->Status) == "NU") {
                  $sts="NU"; }
                else {
                  $sts = "RQ";
                }
            @endphp
            <tr>
			        <td align="center" class="tdBorder">{{ $value->onday1 }}</td>
			        <td align="left" class="tdBorder">{{ $value->City }}</td>
              <td align="left" class="tdBorder">{{ $value->Restaurant }}
      			  @php
                echo !empty($value->Remark)?'<br/> - '.trim($value->Remark):'';
              @endphp
              </td>
              <td align="center" class="tdBorder">{{ $value->LunchDinner }}</td>
              <td align="left" class="tdBorder">{{ $value->Menu }}
              @php
                echo !empty($value->MenuDescriptionRemark)?'<br/>'.nl2br($value->MenuDescriptionRemark):'';
              @endphp
			        </td>
              <td align="center" class="tdBorder">{{ $value->Pax }}</td>
              <td align="center" class="tdBorder">{{ $value->AtTime }}</td>
              <td align="center" class="tdBorder">{{ $sts }}</td>
            </tr>
            @php
              }
            @endphp
          </tbody>
      </table></td>
    </tr>
    @php
      }
    @endphp

    @php
      if(count($boat)>0){
    @endphp
    <tr>
      <td bgcolor="#CCCCCC" class="ui-widget-header ui-corner-all" onclick="showHide(this,'Transport');">
        <ul id="icons3" class="ui-widget ui-helper-clearfix">
          <li><span id="spanTransport" class="ui-icon ui-icon-circle-triangle-e" ></span></li>
          <li><strong>BOAT / TRAIN / BALLOON</strong></li>
        </ul>
      </td>
    </tr>

    <tr id="trTransport">
      <td>
        <table width="97%" class="tableBorder" align="center" border="1" bordercolor="#dddddd" cellspacing="0">
          <tbody>
            <tr bgcolor="#eeeeee">
              <th align="center" nowrap="nowrap" class="tdBorder"><?=$TextPositionArray['Date']?></th>
              <th align="left" class="tdBorder">Transport (Boat/Train/Balloon)</th>
              <th width="5%" align="center" class="tdBorder">Class</th>
              <th align="center" nowrap="nowrap" class="tdBorder">From</th>
              <th align="center" nowrap="nowrap" class="tdBorder">To</th>
              <th width="10%" align="center" class="tdBorder">Remarks</th>
              <th align="center" nowrap="nowrap" class="tdBorder">Time</th>
              <th align="center" nowrap="nowrap" class="tdBorder">Status</th>
              <th align="left" class="tdBorder"><strong>Supplier</strong></th>
              <th align="left" class="tdBorder">Service</th>
              <th align="left" class="tdBorder">Phone</th>
            </tr>
            @php
              foreach($boat as $value){
                $phone = "";
                if(!empty($value->CountryPrefix) AND !empty($value->Phone)){
                  $phone .= $value->CountryPrefix." ";
                }
                if(!empty($value->CityPrefix)  AND !empty($value->Phone)){
                  $phone .= $value->CityPrefix." ";
                }
                if(!empty($value->Phone)){
                  $phone .= $value->Phone;
                }
            @endphp
              <tr>
                <td align="center" class="tdBorder">{{ $value->FlightDate }}</td>
                <td align="left" class="tdBorder">{{ $value->Airline }}</td>
                <td align="center" class="tdBorder">{{ $value->class }}</td>
                <td align="left" class="tdBorder">{{ $value->FlightFrom }}</td>
                <td align="left" class="tdBorder">{{ $value->FlightTo }}</td>
                <td align="left" class="tdBorder">{{ $value->remark }}</td>
                <td align="center" class="tdBorder">{{ $value->dep }}&nbsp;-&nbsp;{{ $value->arr }}</td>
                <td align="center" class="tdBorder">{{ $value->Status }}</td>
                <td align="left" class="tdBorder">{{ $value->CompanyName }}&nbsp;</td>
                <td align="left" class="tdBorder">{{ $value->ServiceName }}</td>
                <td align="left" class="tdBorder">{{ $phone }}</td>
              </tr>
            @php
              }
            @endphp
          </tbody>
        </table>
      </td>
    </tr>
    @php
      }
    @endphp

    @php
      if(count($entrancefee)>0){
    @endphp
    <tr>
      <td bgcolor="#CCCCCC" class="ui-widget-header ui-corner-all" >
        <ul id="icons4" class="ui-widget ui-helper-clearfix">
          <li><span id="spanEntranceFee" class="ui-icon ui-icon-circle-triangle-e"></span></li>
          <li><strong>Entrance Fee</strong></li>
        </ul>
      </td>
    </tr>

    <tr id="trPackage">
      <td>
        <table class="tableBorder" width="97%" align="center" border="1" bordercolor="#dddddd" cellspacing="0">
          <tbody>
            <tr bgcolor="#eeeeee">
              <th bgcolor="#EEEEEE" class="tdBorder">Date&nbsp;</th>
              <th bgcolor="#EEEEEE" class="tdBorder">Country&nbsp;&nbsp;</th>
              <th bgcolor="#EEEEEE" class="tdBorder">City&nbsp;&nbsp;</th>
              <th align="left" bgcolor="#EEEEEE" class="tdBorder">Company&nbsp;</th>
              <th align="left" bgcolor="#EEEEEE" class="tdBorder">Service&nbsp;</th>
              <th align="left" bgcolor="#EEEEEE" class="tdBorder">Pax&nbsp;</th>
              <th bgcolor="#EEEEEE" class="tdBorder">Status&nbsp;</th>
              <th bgcolor="#EEEEEE" class="tdBorder">Remarks&nbsp;</th>
              <th bgcolor="#EEEEEE" class="tdBorder">Phone&nbsp;</th>
            </tr>
            @php
              foreach($entrancefee as $value){
                $on_day = date("d-M-Y", strtotime($value->OnDay));
                $phone = "";
                if(!empty($value->CountryPrefix) AND !empty($value->Phone)){
                  $phone .= $value->CountryPrefix." ";
                }
                if(!empty($value->CityPrefix)  AND !empty($value->Phone)){
                  $phone .= $value->CityPrefix." ";
                }
                if(!empty($value->Phone)){
                  $phone .= $value->Phone;
                }
            @endphp
            <tr>
              <td align="center" class="tdBorder">{{ $on_day }}</td>
              <td align="center" class="tdBorder">{{ $value->CountryDesc }}</td>
              <td align="center" class="tdBorder">{{ $value->City }}</td>
              <td class="tdBorder">{{ $value->CompanyName }}</td>
              <td align="left" class="tdBorder">{{ $value->ServiceName }}</td>
              <td align="left" class="tdBorder">{{ $value->Pax }}</td>
              <td align="center" class="tdBorder">{{ $value->Status }}</td>
              <td align="center" class="tdBorder">{{ $value->Remark }}</td>
              <td align="center" class="tdBorder">{{ $phone }}</td>
            </tr>

            @php
              }
            @endphp
          </tbody>
        </table>
      </td>
    </tr>
    @php
      }
    @endphp

    @php
      if(count($package)>0){
    @endphp
    <tr>
      <td bgcolor="#CCCCCC" class="ui-widget-header ui-corner-all" onclick="showHide(this,'Package');">
        <ul id="icons4" class="ui-widget ui-helper-clearfix">
          <li><span id="spanPackage" class="ui-icon ui-icon-circle-triangle-e"></span></li>
          <li><strong>PACKAGES AND ACTIVITIES</strong></li>
        </ul>
      </td>
    </tr>

    <tr id="trPackage">
      <td>
        <table class="tableBorder" width="97%" align="center" border="1" bordercolor="#dddddd" cellspacing="0">
          <tbody>
            <tr bgcolor="#eeeeee">
              <th bgcolor="#EEEEEE" class="tdBorder">Date&nbsp;</th>
              <th bgcolor="#EEEEEE" class="tdBorder">Country&nbsp;&nbsp;</th>
              <th bgcolor="#EEEEEE" class="tdBorder">City&nbsp;&nbsp;</th>
              <th align="left" bgcolor="#EEEEEE" class="tdBorder">Company&nbsp;</th>
              <th align="left" bgcolor="#EEEEEE" class="tdBorder">Service&nbsp;</th>
              <th align="left" bgcolor="#EEEEEE" class="tdBorder">Service Category&nbsp;</th>
              <th align="left" bgcolor="#EEEEEE" class="tdBorder">Package Code&nbsp;</th>
              <th bgcolor="#EEEEEE" class="tdBorder">Status&nbsp;</th>
              <th bgcolor="#EEEEEE" class="tdBorder">Remarks&nbsp;</th>
              <th bgcolor="#EEEEEE" class="tdBorder">Phone&nbsp;</th>
            </tr>
            @php
              foreach($package as $value){
                $on_day = date("d-M-Y", strtotime($value->OnDay));
                $phone = "";
                if(!empty($value->CountryPrefix) AND !empty($value->Phone)){
                  $phone .= $value->CountryPrefix." ";
                }
                if(!empty($value->CityPrefix)  AND !empty($value->Phone)){
                  $phone .= $value->CityPrefix." ";
                }
                if(!empty($value->Phone)){
                  $phone .= $value->Phone;
                }
            @endphp

            <tr>
              <td align="center" class="tdBorder">{{ $on_day }}</td>
              <td align="center" class="tdBorder">{{ $value->CountryDesc }}</td>
              <td align="center" class="tdBorder">{{ $value->City }}</td>
              <td class="tdBorder">{{ $value->CompanyName }}</td>
              <td align="left" class="tdBorder">{{ $value->ServiceName }}</td>
              <td align="left" class="tdBorder">{{ $value->ServiceCategory_Desc }}</td>
              <td align="left" class="tdBorder">{{ $value->PackageCode }}</td>
              <td align="center" class="tdBorder">{{ $value->Status }}</td>
              <td align="center" class="tdBorder">{{ $value->Remark }}</td>
              <td align="center" class="tdBorder">{{ $phone }}</td>
            </tr>

            @php
              }
            @endphp
          </tbody>
        </table>
      </td>
    </tr>
    @php
      }
    @endphp


    @php
    if(count($guide)>0){
    @endphp
    <tr>
      <td bgcolor="#CCCCCC" class="ui-widget-header ui-corner-all" onclick="showHide(this,'Guide');">
        <ul id="icons5" class="ui-widget ui-helper-clearfix">
          <li><span id="spanGuide" class="ui-icon ui-icon-circle-triangle-e" ></span></li>
          <li><strong>GUIDES</strong></li>
        </ul>
      </td>
    </tr>

    <tr id="trGuide">
      <td>
        <table class="tableBorder" width="97%" align="center" border="1" bordercolor="#dddddd" cellspacing="0">
          <tbody>
          <tr bgcolor="#eeeeee">
            <th align="center" nowrap="nowrap" class="tdBorder">&nbsp;From&nbsp;</th>
            <th align="center" nowrap="nowrap" class="tdBorder">&nbsp;To&nbsp;</th>
            <th align="center" nowrap="nowrap" class="tdBorder">&nbsp;City&nbsp;</th>
            <th bgcolor="#EEEEEE" class="tdBorder">&nbsp;Guide&nbsp;</th>
            <th bgcolor="#EEEEEE" class="tdBorder">&nbsp;Title&nbsp;</th>
            <th bgcolor="#EEEEEE" class="tdBorder">&nbsp;Mobile&nbsp;</th>
            <th bgcolor="#EEEEEE" class="tdBorder">&nbsp;Language&nbsp;</th>
          </tr>
          @php
          for($i=0;$i<count($guide);$i++){
          @endphp
          <tr>
            <td align="center" class="tdBorder">{{ $guide[$i]["From"] }}&nbsp;</td>
            <td align="center" class="tdBorder">{{ $guide[$i]["To"] }}&nbsp;</td>
            <td align="center" class="tdBorder">{{ $guide[$i]["City"] }}&nbsp;</td>
            <td class="tdBorder">{{ $guide[$i]["FullName"] }}</td>
            <td align="center" class="tdBorder">{{ $guide[$i]["TitleMasterData"] }}</td>
            <td align="center" class="tdBorder">{{ $guide[$i]["MobilePhone"] }}</td>
            <td class="tdBorder">{{ $guide[$i]["GuidePriceDesc"] }}&nbsp;</td>
          </tr>
          @php
          }
          @endphp
          </tbody>
        </table>
      </td>
    </tr>
    @php
      }
    @endphp

    @php
      if(count($vehicle)>0){
    @endphp
    <tr>
      <td bgcolor="#CCCCCC" class="ui-widget-header ui-corner-all" onclick="showHide(this,'Vehicle');">
        <ul id="icons4" class="ui-widget ui-helper-clearfix">
          <li><span id="spanVehicle" class="ui-icon ui-icon-circle-triangle-e"></span></li>
          <li><strong>VEHICLE</strong></li>
        </ul>
      </td>
    </tr>

    <tr id="trVehicle">
      <td>
        <table class="tableBorder" width="97%" align="center" border="1" bordercolor="#dddddd" cellspacing="0">
          <tbody>
            <tr bgcolor="#eeeeee">
              <th bgcolor="#EEEEEE" class="tdBorder">&nbsp;Date&nbsp;</th>
              <th bgcolor="#EEEEEE" class="tdBorder">&nbsp;Country&nbsp;</th>
              <th align="left" bgcolor="#EEEEEE" class="tdBorder">&nbsp;VehiclesCompany&nbsp;</th>
              <th bgcolor="#EEEEEE" class="tdBorder">&nbsp;Type&nbsp;</th>
              <th bgcolor="#EEEEEE" class="tdBorder">&nbsp;Service&nbsp;</th>
              <th bgcolor="#EEEEEE" class="tdBorder">&nbsp;Status&nbsp;</th>
              <th bgcolor="#EEEEEE" class="tdBorder">&nbsp;Remarks&nbsp;</th>
              <th bgcolor="#EEEEEE" class="tdBorder">&nbsp;Phone&nbsp;</th>
            </tr>

          @php
            foreach($vehicle as $value){
              $on_day = date("d-M-Y", strtotime($value->OnDay));
              $phone = "";
              if(!empty($value->CountryPrefix) AND !empty($value->Phone)){
                $phone .= $value->CountryPrefix." ";
              }
              if(!empty($value->CityPrefix)  AND !empty($value->Phone)){
                $phone .= $value->CityPrefix." ";
              }
              if(!empty($value->Phone)){
                $phone .= $value->Phone;
              }
          @endphp

            <tr>
              <td class="tdBorder">{{ $on_day }}</td>
              <td class="tdBorder">{{ $value->CountryDesc }}</td>
              <td class="tdBorder">{{ $value->CompanyName }}</td>
              <td class="tdBorder">{{ $value->ServiceUnitType }}</td>
              <td class="tdBorder">{{ $value->ServiceName }}</td>
              <td class="tdBorder">{{ $value->Status }}</td>
              <td class="tdBorder">{{ $value->Remark }}&nbsp;</td>
              <td class="tdBorder">{{ $phone }}&nbsp;</td>
            </tr>

          @php
            }
          @endphp

          </tbody>
        </table>
      </td>
    </tr>
    @php
      }
    @endphp


    @php
      if(count($other)>0){
    @endphp
    <tr>
      <td bgcolor="#CCCCCC" class="ui-widget-header ui-corner-all" onclick="showHide(this,'Miscellaneous');">
        <ul id="icons4" class="ui-widget ui-helper-clearfix">
          <li><span id="spanMiscellaneous" class="ui-icon ui-icon-circle-triangle-e"></span></li>
          <li><strong>MISCELLANEOUS</strong></li>
        </ul>
      </td>
    </tr>

    <tr id="trMiscellaneous">
      <td>
        <table class="tableBorder" width="97%" align="center" border="1" bordercolor="#dddddd" cellspacing="0">
          <tbody>
            <tr bgcolor="#eeeeee">
              <th bgcolor="#EEEEEE" class="tdBorder">&nbsp;Date&nbsp;</th>
              <th bgcolor="#EEEEEE" class="tdBorder">&nbsp;Country&nbsp;</th>
              <th bgcolor="#EEEEEE" class="tdBorder">&nbsp;City&nbsp;</th>
              <th align="left" bgcolor="#EEEEEE" class="tdBorder">&nbsp;Company&nbsp;</th>
              <th align="left" bgcolor="#EEEEEE" class="tdBorder">&nbsp;Service&nbsp;</th>
              <th align="left" bgcolor="#EEEEEE" class="tdBorder">&nbsp;Service Category&nbsp;</th>
              <th bgcolor="#EEEEEE" class="tdBorder">&nbsp;Status&nbsp;</th>
              <th bgcolor="#EEEEEE" class="tdBorder">&nbsp;Remarks&nbsp;</th>
              <th bgcolor="#EEEEEE" class="tdBorder">&nbsp;Phone&nbsp;</th>
            </tr>
            @php
              foreach($other as $value){
                $on_day = date("d-M-Y", strtotime($value->OnDay));
                $phone = "";
                if(!empty($value->CountryPrefix) AND !empty($value->Phone)){
                  $phone .= $value->CountryPrefix." ";
                }
                if(!empty($value->CityPrefix)  AND !empty($value->Phone)){
                  $phone .= $value->CityPrefix." ";
                }
                if(!empty($value->Phone)){
                  $phone .= $value->Phone;
                }
            @endphp
            <tr>
              <td align="center" class="tdBorder">{{ $on_day }}</td>
              <td align="center" class="tdBorder">{{ $value->CountryDesc }}</td>
              <td align="center" class="tdBorder">{{ $value->City }}</td>
              <td class="tdBorder">{{ $value->CompanyName }}</td>
              <td align="left" class="tdBorder">{{ $value->ServiceName }}</td>
              <td align="left" class="tdBorder">{{ $value->ServiceCategory_Desc }}</td>
              <td align="center" class="tdBorder">{{ $value->Status }}</td>
              <td align="center" class="tdBorder">{{ $value->Remark }}&nbsp;</td>
              <td align="center" class="tdBorder">{{ $phone }}&nbsp;</td>
            </tr>
            @php
              }
            @endphp
          </tbody>
        </table>
      </td>
    </tr>
    @php
      }
    @endphp

    <tr>
      <td bgcolor="#CCCCCC" class="ui-widget-header ui-corner-all" onclick="showHide(this,'Itinerary');">
        <ul id="icons8" class="ui-widget ui-helper-clearfix">
          <li><span id="spanItinerary" class="ui-icon ui-icon-circle-triangle-e" ></span></li>
          <li><strong>ITINERARY</strong></li>
        </ul>
      </td>
    </tr>

    <tr>
      <td>
        <table width="100%" align="center">
          <tbody>
            <tr>
              <td align="center"><a href="http://www.icstravelgroup.com" target="_top"><u>&#169; Indochina Services {{date('Y')}}</u></a></td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>

  </table>
</div>
</body>

@include('layouts.inc-scripts')
@endsection
