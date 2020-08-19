<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Response;
use App\tbRestaurants;
use App\tbContacts;
use App\tbISUsers;

  class RestaurantReportController extends controller{

    public function Restaurant($param)
    {
      list($ssid,$isid) = explode('|',$param);
      $Restaurant = new tbRestaurants;
      $RestaurantArray = $Restaurant->SelectRestaurants();
      $ContryArray = $Restaurant->SelectContry();
      $CityArray = $Restaurant->SelectCity();
      $Company = new tbContacts;
      $CompanyArray = $Company->RestaurantSelectCompany();
      $Inbound = new tbISUsers;
      $InboundArray = $Inbound->SelectInbound();
      //dd($ContryArray);
      return view('restaurant.restaurantreport')->with(compact('RestaurantArray','ContryArray','CityArray','CompanyArray','InboundArray','ssid','isid'));
    }

    public function ChangeCity(Request $req)
    {
      $country = $req->country;
      $City = new tbRestaurants();
      $CityArray = $City->ChkSelectCity($country);
      //dd($CityArray);
      return json_encode($CityArray);
    }

    public function ChangeRes(Request $req)
    {
      $country = $req->country;
      $city = $req->city;
      $Restaurant = new tbRestaurants();
      $RestaurantArray = $Restaurant->ChkSelectRestaurants($city, $country);
      //dd($country);
      return json_encode($RestaurantArray);
    }

    public function SearchRestaurant(Request $req){
      $Country = !empty($req->Country) ? $req->Country:null;
      $City = !empty($req->City) ? $req->City:null;
      $TORestaurant = !empty($req->TORestaurant) ? $req->TORestaurant:null;
      $Restaurant = !empty($req->Restaurant) ? $req->Restaurant:null;
      $Company = !empty($req->Company) ? $req->Company:null;
      $Inbound = !empty($req->Inbound) ? $req->Inbound:null;

      $Start_Date = !empty($req->Start_Date) ? $req->Start_Date:null;
      if (!empty($Start_Date)) {
          $Start_Date_N = str_replace('/', '-', $Start_Date );
          $Start_Date_NEW = date("Y-m-d", strtotime($Start_Date_N));
      }else {
        $Start_Date_NEW = '';
      }

      $Start_DateEnd = !empty($req->Start_DateEnd) ? $req->Start_DateEnd:null;
      if (!empty($Start_DateEnd)) {
          $Start_DateEnd_N = str_replace('/', '-', $Start_DateEnd );
          $Start_DateEnd_NEW = date("Y-m-d", strtotime($Start_DateEnd_N));
      }else {
        $Start_DateEnd_NEW = '';
      }

      $Restaurant_Date = !empty($req->Restaurant_Date) ? $req->Restaurant_Date:null;
      if (!empty($Restaurant_Date)) {
          $Restaurant_Date_N = str_replace('/', '-', $Restaurant_Date );
          $Restaurant_Date_NEW = date("Y-m-d", strtotime($Restaurant_Date_N));
      }else {
        $Restaurant_Date_NEW = '';
      }

      $Restaurant_DateEnd = !empty($req->Restaurant_DateEnd) ? $req->Restaurant_DateEnd:null;
      if (!empty($Restaurant_DateEnd)) {
          $Restaurant_DateEnd_N = str_replace('/', '-', $Restaurant_DateEnd );
          $Restaurant_DateEnd_NEW = date("Y-m-d", strtotime($Restaurant_DateEnd_N));
      }else {
        $Restaurant_DateEnd_NEW = '';
      }

      $Book_Date = !empty($req->Book_Date) ? $req->Book_Date:null;
      if (!empty($Book_Date)) {
          $Book_Date_N = str_replace('/', '-', $Book_Date );
          $Book_Date_NEW = date("Y-m-d", strtotime($Book_Date_N));
      }else {
        $Book_Date_NEW = '';
      }

      $Book_DateEnd = !empty($req->Book_DateEnd) ? $req->Book_DateEnd:null;
      if (!empty($Book_DateEnd)) {
          $Book_DateEnd_N = str_replace('/', '-', $Book_DateEnd );
          $Book_DateEnd_NEW = date("Y-m-d", strtotime($Book_DateEnd_N));
      }else {
        $Book_DateEnd_NEW = '';
      }

      $Status = !empty($req->Status) ? $req->Status:null;

      $restaurant = new tbRestaurants();
      $restaurantArray = $restaurant->ReportRestaurant($Country, $City, $TORestaurant, $Restaurant, $Company, $Inbound, $Start_Date_NEW, $Start_DateEnd_NEW, $Restaurant_Date_NEW, $Restaurant_DateEnd_NEW, $Book_Date_NEW, $Book_DateEnd_NEW, $Status);
      $restuarantNameArray = $restaurant->ReportRestaurantName($Restaurant);

        //dd($restaurantArray);
        $DataArray2 = array();
        $datashow = "";
        $Name = "";
        $headshow = "";
        if (count($restaurantArray) > 0) {
          $y = 1;
          $tid_tmp = ""; $tid = ""; $clients = ""; $pax = ""; $stdate = "";
          $etdate = ""; $bdate = ""; $CompanyDesc = ""; $mstcontact = ""; $uname = "";
          $rsid = ""; $msttour = "";
          foreach($restaurantArray as $row){
            $tid = $row->tid;
            $clients = $row->clients;
            $pax = $row->pax;
            $stdate = $row->stdate;
            $etdate = $row->etdate;
            $bdate = $row->bdate;
            $CompanyDesc = $row->CompanyDesc;
            $mstcontact = $row->mstcontact;
            $uname = $row->uname;
            $rsid = $row->rsid;
            $msttour = $row->msttour;
           // $DataArray2[] = array(
           //   'tid' => $row->tid,
           //   'clients' => $row->clients,
           //   'pax' => $row->pax,
           //   'stdate' => $row->stdate,
           //   'etdate' => $row->etdate,
           //   'bdate' => $row->bdate,
           //   'CompanyDesc' => $row->CompanyDesc,
           //   'mstcontact' => $row->mstcontact,
           //   'uname' => $row->uname,
           //   'rsid' => $row->rsid,
           //   'msttour' => $row->msttour
           // );
           if ($tid_tmp != $tid) {

           $datashow .= "<table align='center' width='100%' cellspacing='0' border='1' cellpadding='2' bordercolor='#c0c0c0'  style='font-size:13px;'>";
           $datashow .= "<tr><td width='10%'><b>No: $y - $tid</b></td>";
           $datashow .= "<td width='30%'><a href='https://staff.icstravelgroup.com/cgi-local/booking.cgi?1+3+isa+$mstcontact+$tid+HJ7789-H6767HG-677777678-6GFD4S+$msttour' target='_blank'>$clients</a></td>";
           $datashow .= "<td width='13%'><b>Tour Start:</b> <nobr>$stdate</nobr></td>";
           $datashow .= "<td width='13%'><b>Tour End:</b> <nobr>$etdate</nobr></td>";
           $datashow .= "<td width='13%'><b>Booked:</b> <nobr>$bdate</nobr></td>";
           $datashow .= "<td width='20%'>$CompanyDesc <br>$uname</td></tr>";

             $restaurantData = $restaurant->ReportRestaurantData($tid, $City);
             $Restaurant = ""; $City = ""; $Status = ""; $resbdate = ""; $resonday = ""; $AtTime = ""; $Pax = ""; $LunchDinner = "";
             // if (count($restaurantData) > 0) {
               foreach ($restaurantData as $contact) {
                 $Restaurant = $contact->Restaurant;
                 $Cityda = $contact->City;
                 $resbdate = $contact->resbdate;
                 $resonday = $contact->resonday;
                 $AtTime = $contact->AtTime;
                 $Pax = $contact->Pax;
                 $LunchDinner = $contact->LunchDinner;
                 $Status = $contact->Status;
                  if (strtoupper($Status) == "CONFIRMED") { $Status="OK"; }
			               else if (strtoupper($Status) == "OK") { $Status="OK"; }
			                  else if (strtoupper($Status) == "CANCELLED") { $Status="CXL"; }
			                     else if (strtoupper($Status) == "CXL") { $Status="CXL"; }
			                        else if (strtoupper($Status) == "booked") { $Status="RQ"; }
			                           else { $Status="RQ"; }

                          $datashow .= "<tr>";
                          $datashow .= "<td colspan=2><b>$Restaurant - $Cityda</b></td>";
                          $datashow .= "<td style='text-align:center;'><b>Status:</b> $Status</td>";
                          $datashow .= "<td nowrap><b>On:</b> <nobr>$resonday</nobr></td>";
                          $datashow .= "<td nowrap><b>Time:</b> $AtTime</td>";
                          $datashow .= "<td><b>Meal:</b>&nbsp;$LunchDinner,&nbsp;";
                          $datashow .= "&nbsp;$Pax <b>Pax</b>&nbsp;</td>";
                          $datashow .= "</tr>";
                  }
                // }
             //dd($restaurantData);
           $datashow .= "</table><br/>";
           $y += 1;
            }
            $tid_tmp = $tid;
          }
          $num = $y -1;
          if (count($restuarantNameArray) > 0) {
            foreach ($restuarantNameArray as $name) {
              $Name = $name->Restaurant;
              $headshow = "<table align='center' width='100%' cellspacing='2' bgcolor='#eeeeee'><tr><th align='left' style='width:95%; background-color:#eeeeee;'>$Name ($num Records)</font></td></table><br>";
            }
          }else {
            $headshow = "<table align='center' width='100%' cellspacing='2' bgcolor='#eeeeee'><tr><th align='left' style='width:95%; background-color:#eeeeee;'> ($num Records)</font></td></table><br>";
          }
        }else {
          $headshow = "<table align='center' width='100%' cellspacing='2' bgcolor='#eeeeee'><tr><th align='left' style='width:95%; background-color:#eeeeee;'> (0 Records)</font></td></table><br>";

          $datashow = "<table align='center' width='100%' cellspacing='0' border='0' cellpadding='2' bordercolor='#c0c0c0'  style='font-size:13px;'>";
          $datashow .= "<td style='font-size:16px; text-align: center;'><b> No records found! </b></td>";
          $datashow .= "</table><br>";
        }

        echo $headshow,$datashow;
    }

  }
