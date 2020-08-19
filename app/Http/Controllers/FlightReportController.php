<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Response;
use Mail;
use App\tbCountry;
use App\tbFlightBookings;
use App\tbContacts;
use App\tbISUsers;
use App\BookingRateCost;

class FlightReportController extends controller
{
  public function Flight($param)
  {
    list($ssid,$isid) = explode('|',$param);
    $Country = new tbCountry();
    $CountryArray = $Country->SelectCountry();
    $Flight = new tbFlightBookings();
    $CityArray1 = $Flight->SelectCity();
    $CityArray2 = $Flight->SelectCity();
    $AirlineArray = $Flight->SelectAirline();
    $TO = new tbContacts();
    $TOArray = $TO->SelectTO();
    $Reservation = new tbISUsers();
    $ReservationArray = $Reservation->SelectReservation();


    return view('flight.flightreport')->with(compact('CountryArray','CityArray1','CityArray2','AirlineArray','TOArray','ReservationArray','ssid','isid'));
  }

  public function ChangeCity(Request $req)
  {
    $Country = $req->country;
    $Flight = new tbFlightBookings();
    $City = $Flight->CheangCity($Country);
    // dd($City);
    //$City2 = $Flight->CheangCity($Country);
    return json_encode($City);
  }

  public function ChangeAirline(Request $req)
  {
    $Country = $req->Country;
    $City1 = !empty($req->City1) ? $req->City1:0;
    $City2 = !empty($req->City2) ? $req->City2:0;
    $CityArray1Post = "";
    $CityArray2Post = "";
    if ($City1 != "0") {
      $array1 = explode('~',$City1);
      $CityArray1Post = $array1[1];
    }
    if ($City2 != "0") {
      $array2 = explode('~',$City2);
      $CityArray2Post = $array2[1];
    }

    $Flight = new tbFlightBookings();
    $AirlineArray = $Flight->CheangAirline($Country, $CityArray1Post, $CityArray2Post);
    // dd($AirlineArray);
    return json_encode($AirlineArray);
  }

  public function ChangeTo(Request $req)
  {
    $Country = $req->Country;
    $City1 = !empty($req->City1) ? $req->City1:0;
    $City2 = !empty($req->City2) ? $req->City2:0;
    $CityArray1Post = "";
    $CityArray2Post = "";
    if ($City1 != "0") {
      $array1 = explode('~',$City1);
      $CityArray1Post = $array1[1];
    }
    if ($City2 != "0") {
      $array2 = explode('~',$City2);
      $CityArray2Post = $array2[1];
    }

    $TO = new tbContacts();
    $TOArray = $TO->ChangeTO($Country, $CityArray1Post, $CityArray2Post);

    return json_encode($TOArray);
  }

  public function SearchDataFlight(Request $req)
  {
    $Type = $req->Type;
    $Option = $req->Option;
    $Country = $req->Country;
    $City1 = !empty($req->City1) ? $req->City1:0;
    $City2 = !empty($req->City2) ? $req->City2:0;
      $CityArray1Post = "";
      $CityArray2Post = "";
        if ($City1 != "0") {
          $array1 = explode('~',$City1);
          $CityArray1Post = $array1[1];
        }
        if ($City2 != "0") {
          $array2 = explode('~',$City2);
          $CityArray2Post = $array2[1];
        }
    $Date = $req->Date;
    $DateEnd = $req->DateEnd;
      if (!empty($Date)) {
          $Date_N = str_replace('/', '-', $Date );
          $Date_NEW = date("Y-m-d", strtotime($Date_N));
        }else {
          $Date_NEW = '';
        }
        if (!empty($DateEnd)) {
          $DateEnd_N = str_replace('/', '-', $DateEnd );
          $DateEnd_NEW = date("Y-m-d", strtotime($DateEnd_N));
        }else {
          $DateEnd_NEW = '';
        }
    $Airline = !empty($req->Airline) ? $req->Airline:0;
    $TOperators = !empty($req->TOperators) ? $req->TOperators:0;
    $Reservation = $req->Reservation;
    $FBBT = $req->FBBT;
    $CXL = $req->CXL;
    $Bt = "";

    $Flight = new tbContacts();
    $FlightArray = $Flight->ReportFlight($Type, $Option, $Country, $CityArray1Post, $CityArray2Post, $Date_NEW, $DateEnd_NEW, $Airline, $TOperators, $Reservation, $FBBT, $CXL, $Bt);

     // dd($FlightArray);
    $datashow = "";
    $arr = ""; $dep = ""; $FlightNo = ""; $a = ""; $bdate = "";
    $CompanyDesc = ""; $CountryDesc = ""; $pax = ""; $isuser = ""; $tid = "";
    $Airline = ""; $fno = ""; $flfrom = ""; $flto = "";  $fdate = "";
    $Status = ""; $class = ""; $refno = ""; $clients = ""; $Shortcut = "";
    $tguid = ""; $clnt = ""; $fPrice = ""; $Real_PP = ""; $remark = "";
    $FBIdUniqueId = ""; $CompanyName = "";
    $datashow .= "<table align='left' width='98%' cellspacing='0' border='1' cellpadding='2' bordercolor='#c0c0c0'  style='font-size:13px;'>";
    $datashow .= "<tr bgcolor=#eeeeee>";
    $datashow .= "<th width=7%><nobr><font>Country</font></nobr></th>";
    $datashow .= "<th width=7%><nobr><font>Tour ID</font></nobr></th>";
    $datashow .= "<th><font>Airline</font></th>";
    $datashow .= "<th width=10%><font>Flight No</font></th>";
    $datashow .= "<th width=10%><font>Time</font></th>";
    $datashow .= "<th width=20%><nobr><font>Supplier</font></nobr></th>";
    $datashow .= "<th width=20%><nobr><font>Client</font></nobr></th>";
    $datashow .= "<th width=10%><nobr><font>T.Op.</font></nobr></th>";
    $datashow .= "<th width=10%><nobr><font>Refno.</font></nobr></th>";
    $datashow .= "<th width=10%><nobr><font>ICS remark for flight</font></nobr></th>";
    $datashow .= "<th nowrap><nobr><font>From</font></nobr></th>";
    $datashow .= "<th nowrap><nobr><font>To</font></nobr></th>";
    $datashow .= "<th width=7%><nobr><font>Booked On</font></nobr></th>";
    $datashow .= "<th width=7%><nobr><font>Date</font></nobr></th>";
    $datashow .= "<th width=5%><nobr><font>User</font></nobr></th>";
    $datashow .= "<th width=5%><nobr><font>Status</font></nobr></th>";
    $datashow .= "<th width=5%><nobr><font>Pax</font></nobr></th>";
    $datashow .= "<th width=5%><nobr><font>PP</font></nobr></th>";
    $datashow .= "<th width=5%><nobr><font>Price</font></nobr></th>";
    $datashow .= "<th width=5%><nobr><font>extra</font></nobr></th>";
    $datashow .= "<th width=5%><nobr><font>reduction</font></nobr></th>";
    $datashow .= "<th width=5%><nobr><font>TotalPrice</font></nobr></th>";
    $datashow .= "</tr>";

    $price_per_pax_sum = 0.00; $priceperpax_sum = 0.00; $totalprice_sum = 0.00; $reductionUS_sum = 0.00; $extraCostUS_sum = 0.00;
    $price_per_pax = 0; $pax1 = 0; $priceperpax = 0; $totalprice = 0; $reductionUS = 0; $extraCostUS = 0;
    foreach ($FlightArray as $row) {
      $arrF = substr($row->arr,0,2);
      $arrB = substr($row->arr,-2);
      $arr = $arrF.':'.$arrB;

      $depF = substr($row->dep,0,2);
      $depB = substr($row->dep,-2);
      $dep = $depF.':'.$depB;

      $FlightNo = $row->FlightNo;
      $a = $row->a;
      $bdate = $row->bdate;
      $CompanyDesc = $row->CompanyDesc;
      $CountryDesc = $row->CountryDesc;
      $pax = round($row->pax);
      $isuser = $row->isuser;
      $tid = $row->tid;
      $Airline = $row->Airline;
      $fno = $row->fno;
      $flfrom = $row->flfrom;
      $flto = $row->flto;
      $fdate = $row->fdate;
      $Status = $row->Status;
      $class = $row->class;
      $refno = $row->refno;
      $clients = $row->clients;
      $Shortcut = $row->Shortcut;
      $tguid = $row->tguid;
      $clnt = $row->clnt;
      $fPrice = $row->fPrice;
      $Real_PP = $row->Real_PP;
      $remark = $row->remark;
      $FBIdUniqueId = $row->FBIdUniqueId;
      $CompanyName = $row->CompanyName;

      $datashow .= "<tr>";
      $datashow .= "<td><font>$CountryDesc</font></td>";
      $datashow .= "<td align=center><font><a href='https://staff.icstravelgroup.com/bookingonline_v2/Overview.php?TourId=$tid' target='_blank'>$tid";
      if ($a != "") {
        $datashow .= " ($a) </a></font></td>";
      }
      $datashow .= "<td>$Airline</td>";
      $datashow .= "<td>$FlightNo</td>";
      if ($dep != ":" && $arr != ":") {
        $datashow .= "<td nowrap>$dep - $arr</td>";
      }else {
        $datashow .= "<td></td>";
      }
      $datashow .= "<td>$CompanyDesc</td>";
      $datashow .= "<td>$clients</td>";
      $datashow .= "<td>$Shortcut</td>";
      $datashow .= "<td>$refno</td>";
      if ($clnt == "1") {
        $datashow .= "<td>TO<br>$remark</td>";
      }else {
        $datashow .= "<td>$remark</td>";
      }

      $datashow .= "<td nowrap>$flfrom</td>";
      $datashow .= "<td nowrap>$flto</td>";
      $datashow .= "<td align=center><nobr>$bdate</nobr></td>";
      $datashow .= "<td align=center><nobr>$fdate</nobr></td>";
      $datashow .= "<td align=center>$isuser</td>";
      $datashow .= "<td align=center>$Status</td>";



      $BookingRateCost  = new BookingRateCost();
      $BookingRateCostArray = $BookingRateCost->getConfirmPrice($FBIdUniqueId);
      if (count($BookingRateCostArray) > 0) {
        foreach ($BookingRateCostArray as $rowCost) {
          // $price_per_pax = number_format(!empty($rowCost->PricePP)?$rowCost->PricePP:0, 2);
  			  // $pax1 = !empty($rowCost->Pax)?$rowCost->Pax:0;
          //   $priceperpax = number_format($price_per_pax * $pax1, 2);
          // $totalprice = number_format(!empty($rowCost->cost)?$rowCost->cost:0);
          // $reductionUS = number_format(!empty($rowCost->ReductionUS)?$rowCost->ReductionUS:0, 2);
          // $extraCostUS = number_format(!empty($rowCost->ExtraCostUS)?$rowCost->ExtraCostUS:0, 2);

          $price_per_pax = !empty($rowCost->PricePP)?$rowCost->PricePP:0;
  			  $pax1 = !empty($rowCost->Pax)?$rowCost->Pax:0;
            $priceperpax = $price_per_pax * $pax1;
          $totalprice = !empty($rowCost->cost)?$rowCost->cost:0;
          $reductionUS = !empty($rowCost->ReductionUS)?$rowCost->ReductionUS:0;
          $extraCostUS = !empty($rowCost->ExtraCostUS)?$rowCost->ExtraCostUS:0;

  //number_format($number, 2, '.', '')
          $datashow .= "<td align=right>$pax1</td>";
          $datashow .= "<td align=right>$".number_format($price_per_pax, 2)."</td>";
          $datashow .= "<td align=right>$".number_format($priceperpax, 2)."</td>";
          $datashow .= "<td align=right>$".number_format($extraCostUS, 2)."</td>";
          $datashow .= "<td align=right>$".number_format($reductionUS, 2)."</td>";
          $datashow .= "<td align=right>$".number_format($totalprice)."</td>";
          $price_per_pax_sum = ((float)$price_per_pax_sum + (float)number_format($price_per_pax, 2, '.', ''));
          $priceperpax_sum = ((float)$priceperpax_sum + (float)number_format($priceperpax, 2, '.', ''));
          $totalprice_sum = ((float)$totalprice_sum + (float)number_format($totalprice, 2, '.', ''));
          $reductionUS_sum = ((float)$reductionUS_sum + (float)number_format($reductionUS, 2, '.', ''));
          $extraCostUS_sum = ((float)$extraCostUS_sum + (float)number_format($extraCostUS, 2, '.', ''));
          // $price_per_pax_sum = ((int)$price_per_pax_sum + ((int)number_format($rowCost->PricePP, 2)));
          // $priceperpax_sum = (int)$priceperpax_sum + (int)number_format($priceperpax, 2);
          // $totalprice_sum = ((int)$totalprice_sum + ((int)number_format($rowCost->cost)));
          // $reductionUS_sum = ((int)$reductionUS_sum + ((int)number_format($rowCost->ReductionUS, 2)));
          // $extraCostUS_sum = ((int)$extraCostUS_sum + ((int)number_format($rowCost->ExtraCostUS, 2)));
        }
      }else {
        $datashow .= "<td align=right>0</td>";
        $datashow .= "<td align=right>$0.00</td>";
        $datashow .= "<td align=right>$0.00</td>";
        $datashow .= "<td align=right>$0.00</td>";
        $datashow .= "<td align=right>$0.00</td>";
        $datashow .= "<td align=right>$0</td>";
      }

      //printf($test); exit();
      $datashow .= "</tr>";
    }
    $datashow .= "<td colspan='17' align=right> Total:</td>";
    $datashow .= "<td align=right>$".number_format($price_per_pax_sum, 2)."</td>";
    $datashow .= "<td align=right>$".number_format($priceperpax_sum, 2)."</td>";
    $datashow .= "<td align=right>$".number_format($extraCostUS_sum, 2)."</td>";
    $datashow .= "<td align=right>$".number_format($reductionUS_sum, 2)."</td>";
    $datashow .= "<td align=right>$".number_format($totalprice_sum)."</td>";
    $datashow .= "</table><br>";

    echo $datashow;
    //echo $Type.' | '.$Option.' | '.$Country.' | '.$CityArray1Post.' | '.$CityArray2Post.' | '.$Date.' | '.$DateEnd.' | '.$Airline.' | '.$TOperators.' | '.$Reservation.' | '.$FBBT.' | '.$CXL;
  }

}
