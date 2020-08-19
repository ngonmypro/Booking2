<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use File;
use Session;
use Response;
use Mail;

use App\tbCountry;

class GuideCallingReportController extends controller
{
  public function GuideCalling()
  {
    $country_array = tbCountry::whereNull('ddate')->orderBy('tbCountry.CountryDesc','ASC')->get();
    if(empty($country_array)){
      $country_array = array();
    }

    //dd($country_array);
    return view('guidecallingreport.guidecallingreport')->with(compact('country_array'));
  }

  public function SearchGuideCalling(Request $request){
    $html = "";
    $array_guide = array();

    // $date_from = $request->txtDateFrom;
    // $date_to = $request->txtDateTo;
    // dd($date_from,$date_to);
    // if(empty($date_from) AND empty($date_to)){
    //   echo "<h3>Please select Service Date.</h3>";
    //   exit();
    // }
    // dd($request);

    $array_guide = $this->getGuideCallingData($request);

    if(count($array_guide) > 0){

      $html .='<table class="table" id="tb_result">';
      $html .='   <tr>';
      $html .='     <th>TourId</th>';
      $html .='     <th>Onday</th>';
      $html .='     <th>Guide Name</th>';
      $html .='     <th>Country</th>';
      $html .='     <th>City</th>';
      $html .='     <th>Transportation</th>';
      $html .='   </tr>';

      foreach ($array_guide as $key => $value) {
        foreach ($value as $value2) {
          $cnt_vehicle = count($value2['Vehicle']);
          $temp_table = "";

          if($cnt_vehicle > 0){
            $rowspan = "rowspan=\"$cnt_vehicle\"";
          }else{
            $rowspan = "";
          }
          $html .='<tr>';
          $html .='    <td '.$rowspan.'><a href="https://staff.icstravelgroup.com/bookingonline_v2/Overview.php?TourId='.$key.'" target="_blank">'.$key.'</a></td>';
          $html .='    <td '.$rowspan.'>'.$value2['OnDay'].'</td>';
          $html .='    <td '.$rowspan.'>'.$value2['GuideName'].'</td>';

          for($i=0;$i<$cnt_vehicle;$i++){
            if($i==0){
              $html .= "<td>".$value2['Vehicle'][$i]["Country"]."</td><td>".$value2['Vehicle'][$i]["City"]."</td><td>".$value2['Vehicle'][$i]["ServiceName"]."</td>";
            }else{
              $temp_table .= "<tr><td>".$value2['Vehicle'][$i]["Country"]."</td><td>".$value2['Vehicle'][$i]["City"]."</td><td>".$value2['Vehicle'][$i]["ServiceName"]."</td></tr>";
            }
          }

          if($cnt_vehicle == 0){
            $html .= '<td></td><td></td><td></td>';
          }

          $html .= '</tr>';

          $html .= $temp_table;
        }
      }

      $html .='</table>';

    }
    echo $html;
  }

  public function ExportGuideCalling(Request $request){
    $array_guide = array();
    $array_guide = $this->getGuideCallingData($request);
    // dd($array_guide);

    header('Content-Type: application/csv');
    header('Content-type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="GuideCallingReport_'.time().'.csv";');

    $file_handler = fopen("php://output", "w");
      // - Write Header
    $header_value_array = array();
    $header_value_array[0] = "Date";
    $header_value_array[1] = "Time of calling";
    $header_value_array[2] = "Booking ID";
    $header_value_array[3] = "TO";
    $header_value_array[4] = "Country";
    $header_value_array[5] = "City";
    $header_value_array[6] = "Guidename";

    $header_value_array[7] = "On time";
    $header_value_array[8] = "Well-groomed";
    $header_value_array[9] = "Branding Material";
    $header_value_array[10] = "Name of clients are correct";
    $header_value_array[11] = "cleaness of the vehicle(inside/ trunk/outside)";
    $header_value_array[12] = "Remark";
    $header_value_array[13] = "Service Name";

    fputcsv($file_handler, $header_value_array);

    foreach ($array_guide as $key => $value) {
      foreach ($value as $value2) {
        // echo "<pre>";
        // var_dump(count($value2['Vehicle']));
        // echo "<hr/>";

        if(count($value2['Vehicle']) > 0){
          for($i=0;$i<count($value2['Vehicle']);$i++){
            $item_value_array[0] = $value2['OnDay'];
            $item_value_array[1] = '';
            $item_value_array[2] = $key;
            $item_value_array[3] = $value2['Company'];
            $item_value_array[4] = $value2['Vehicle'][$i]['Country'];
            $item_value_array[5] = $value2['Vehicle'][$i]['City'];
            $item_value_array[6] = $value2['GuideName'];

            $item_value_array[7] = '';
            $item_value_array[8] = '';
            $item_value_array[9] = '';
            $item_value_array[10] = '';
            $item_value_array[11] = '';
            $item_value_array[12] = '';
            $item_value_array[13] = $value2['Vehicle'][$i]['ServiceName'];
            fputcsv($file_handler, $item_value_array);
          }
        }else{

          $item_value_array[0] = $value2['OnDay'];
          $item_value_array[1] = '';
          $item_value_array[2] = $key;
          $item_value_array[3] = $value2['Company'];
          $item_value_array[4] = '';
          $item_value_array[5] = '';
          $item_value_array[6] = $value2['GuideName'];

          $item_value_array[7] = '';
          $item_value_array[8] = '';
          $item_value_array[9] = '';
          $item_value_array[10] = '';
          $item_value_array[11] = '';
          $item_value_array[12] = '';
          $item_value_array[13] = '';
          fputcsv($file_handler, $item_value_array);

        }

      }
    }

    fclose($file_handler);
  }

  public function getGuideCallingData($request){
    $array_guide = array();

    $tourid = $request->txtTourId;
    $countryid = $request->slCountry;
    $date_from = $request->txtDateFrom;
    $date_to = $request->txtDateTo;

    $where = "";
    $where2 = "";

    if(!empty($tourid)){
      $where .= " AND CHARINDEX('$tourid',tbGuideBookings.TourId) > 0";
    }

    if(!empty($countryid)){
      $where .= " AND tbCountry.CountryId = '$countryid'";
      if($countryid == "BKG1200800000006"){
        $where2 .= " AND ((CHARINDEX('Transfer',SupplierServiceMaster.ServiceMasterName) > 0 AND CHARINDEX('Luggage',SupplierServiceMaster.ServiceMasterName) = 0) OR CHARINDEX('Charter',SupplierServiceMaster.ServiceMasterName) > 0  OR CHARINDEX('By vehicle',SupplierServiceMaster.ServiceMasterName) > 0)
        ";
      }else{
        //$where2 .= " AND ((CHARINDEX('Transfer',SupplierServiceMaster.ServiceMasterName) > 0 OR CHARINDEX('By vehicle',SupplierServiceMaster.ServiceMasterName) > 0) AND CHARINDEX('Luggage',SupplierServiceMaster.ServiceMasterName) = 0)";
        $where2 .= " AND (CHARINDEX('Luggage',SupplierServiceMaster.ServiceMasterName) = 0)";
      }
    }

    if(!empty($date_from) AND !empty($date_to)){
      // $where .= " AND (
      //   (
      //     (tbTours.TourStartDate BETWEEN CAST('$date_from' AS DATETIME) AND CAST('$date_to' AS DATETIME))
      //     OR
      //     (tbTours.TourEndDate BETWEEN CAST('$date_from' AS DATETIME) AND CAST('$date_to' AS DATETIME))
      //   )
      //   OR
      //   (
      //     (CAST('$date_from' AS DATETIME) BETWEEN tbTours.TourStartDate AND tbTours.TourEndDate)
      //     OR
      //     (CAST('$date_to' AS DATETIME) BETWEEN tbTours.TourStartDate AND tbTours.TourEndDate)
      //   )
      // )";

      $where .= " AND tbGuideBookings.Startdate BETWEEN CAST('$date_from' AS DATETIME) AND CAST('$date_to' AS DATETIME)";
    }

    $sql = "
    SELECT tbGuideBookings.TourId,
           Guide.FirstName + ' ' + Guide.LastName as guide_name,
           tbGuideBookings.NewGuideId,
           tbContacts.CompanyDesc,
           dbo.Date_Format(tbGuideBookings.Startdate, 'dd-mmm-yy') as first_date
    FROM tbGuideBookings
         INNER JOIN Guide ON tbGuideBookings.NewGuideId = Guide.GuideId
         INNER JOIN tbTours ON tbGuideBookings.TourId = tbTours.TourId
         INNER JOIN tbContacts ON tbTours.ContactsId = tbContacts.ContactsId
         INNER JOIN tbCountry ON Guide.CountryId = tbCountry.CountryId
    WHERE tbGuideBookings.[Status] = 'OK'
          AND tbTours.Cancelled <> 1
          $where
    ORDER BY tbGuideBookings.Startdate,tbGuideBookings.TourId,guide_name
    ";

    //echo "<pre>".$sql;
    $guide = DB::select($sql);

    if(empty($guide)){
      $guide = array();
    }

    foreach ($guide as $value) {
      $guidebooking_tourid = $value->TourId;
      $service_date = $value->first_date;
      $guide_name = $value->guide_name;
      $company = $value->CompanyDesc;

      $sql2 = "
      SELECT  VehicleBooking.OnDay,
              tbCountry.CountryDesc,
              tbCity.City,
              SupplierServiceMaster.ServiceMasterName
      FROM VehicleBooking
      INNER JOIN SupplierServiceContract ON VehicleBooking.ServiceContractId = SupplierServiceContract.ServiceContractId
      INNER JOIN SupplierServiceMaster ON SupplierServiceContract.ServiceMasterId = SupplierServiceMaster.ServiceMasterId
      INNER JOIN tbCountry ON SupplierServiceMaster.FromCountryId = tbCountry.CountryId
      INNER JOIN tbCity ON SupplierServiceMaster.FlightFromCityId = tbCity.CityId
      WHERE VehicleBooking.Tourid = '$guidebooking_tourid'
      AND VehicleBooking.OnDay = CAST('$service_date' AS DATETIME)
      $where2
      AND VehicleBooking.[Status] IN ('OK','NU')
      ORDER BY VehicleBooking.OnDay
      ";

      $vehicle = DB::select($sql2);
      if(empty($vehicle)){
        $vehicle = array();
      }

      $array_vehicle = array();
      foreach($vehicle as $value2){
        $country = $value2->CountryDesc;
        $city = $value2->City;
        $servicename = $value2->ServiceMasterName;

        $array_vehicle[] = array(
          'Country'=>$country,
          'City'=>$city,
          'ServiceName'=>$servicename
        );
      }


      $array_guide[$guidebooking_tourid][] = array(
        'GuideName'=>$guide_name,
        'OnDay'=>$service_date,
        'Company'=>$company,
        'Vehicle'=>$array_vehicle
      );
    }

    return $array_guide;
  }
}
?>
