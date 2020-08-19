<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Response;
use App\Employee;
use App\tbCountry;
use App\tbCity;
use App\SupplyType;
use App\ContactCompany;
use App\SupplierServiceContract;
use App\MisceOtherBooking;
use App\VehicleBooking;
use App\EntranceFeeBooking;
use App\tbFlightBookings;

class SupplierController extends controller{
  public function SupplierIndex($param)
  {
      // $ISID = '3713';
    $Employee = new Employee;
    $EmployeeArray = $Employee->SelectCountryforEmployee($param);
    foreach ($EmployeeArray as $row) {
      $EmpContryid = $row->CountryId;
    }
    $City = new tbCity;
    $CityArray = $City->SelectCitySupplier($EmpContryid);
// dd($CityArray);
    $tbCountry = new tbCountry;
    $tbCountryArray = $tbCountry->SelectCountrySupplier();
    // dd($EmployeeArray, $tbCountryArray);

    return view('Supplier.Supplierreport')->with(compact('EmpContryid','tbCountryArray','CityArray'));
  }

  public function ChangeDataCitySupplier(Request $req)
  {
    $countryid = $req->country;
    $City = new tbCity;
    $CityArray = $City->ChangeCitySupplier($countryid);
// dd($country);
    return json_encode($CityArray);
  }

  public function ChangeDataSupplyType()
  {
    $SupplyType = new SupplyType;
    $SupplyTypeArray = $SupplyType->ChangeSupplyType();
    // dd($SupplyTypeArray);
    return json_encode($SupplyTypeArray);
  }

  public function ChangeDataSupplier(Request $req)
  {
    $City = $req->City;
    $SupplierType = $req->SupplierType;
    $ContactCompany = new ContactCompany;
    $ContactCompanyArray = $ContactCompany->ChangeContactCompany($City,$SupplierType);
    // dd($ContactCompanyArray);
    return json_encode($ContactCompanyArray);
  }

  public function ChangeDataServiceName(Request $req)
  {
    $City = $req->City;
    $Supplier = $req->Supplier;
    $SupplierServiceContract = new SupplierServiceContract;
    $SupplierServiceContractArray = $SupplierServiceContract->ChangeSupplierServiceContract($City,$Supplier);
    // dd($SupplierServiceContractArray);
    return json_encode($SupplierServiceContractArray);
  }

  public function SearchSupplier(Request $req)
  {

    $SDStart = isset($req->SDStart)? date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $req->SDStart))) : NULL ;
    $SDEnd = isset($req->SDEnd)? date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $req->SDEnd))) : NULL ;
    $TDStart = isset($req->TDStart)? date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $req->TDStart))) : NULL ;
    $TDEnd = isset($req->TDEnd)? date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $req->TDEnd))) : NULL ;
    $Country = $req->Country;
    $City = $req->City;
    $SupplierType = $req->SupplierType;
    $Supplier = $req->Supplier;
    $ServiceName = $req->ServiceName;
    $array = explode('~',$ServiceName);
    $ServiceContractId = $array[1];
    $ServiceCode = $array[0];
    $datashow = "";
    if ($ServiceCode == 'BL' || $ServiceCode == 'BO' || $ServiceCode == 'FL' || $ServiceCode == 'HE' || $ServiceCode == 'TR') {
      // tbFlightBooking
      $tbFlightBookings = new tbFlightBookings;
      $tbFlightBookingsArray = $tbFlightBookings->SelectFlightBookingService($ServiceContractId,$SDStart,$SDEnd,$TDStart,$TDEnd);
      $datashow .= "<h4 align='center'>Report Flight</H4><br>";
      $datashow .= "<table align='left' width='98%' cellspacing='0' border='1' cellpadding='2' bordercolor='#c0c0c0'  style='font-size:13px;'>";
      $datashow .= "<tr bgcolor=#eeeeee>";
      $datashow .= "<th width=10%><nobr><font>Booking ID</font></nobr></th>";
      $datashow .= "<th width=30%><nobr><font>Booking Name</font></nobr></th>";
      $datashow .= "<th width=40%><nobr><font>Service Name</font></nobr></th>";
      $datashow .= "<th width=5%><font>TO/TA</font></th>";
      $datashow .= "<th width=5%><font>Pax</font></th>";
      $datashow .= "<th width=10%><nobr><font>Service Date</font></nobr></th>";
      $datashow .= "</tr>";
      foreach ($tbFlightBookingsArray as $row) {
      $datashow .= "<tr>";
      $datashow .= "<td><font>$row->TourId</font></td>";
      $datashow .= "<td><font>$row->Clients</font></td>";
      $datashow .= "<td><font>$row->ServiceName</font></td>";
      $datashow .= "<td><font>$row->TO_TA</font></td>";
      $datashow .= "<td><font>$row->Pax</font></td>";
      $datashow .= "<td><font>$row->FlightDate</font></td>";
      $datashow .= "</tr>";
      }

    }elseif ($ServiceCode == 'VH') {
      // VehicleBooking
      $VehicleBooking = new VehicleBooking;
      $VehicleBookingArray = $VehicleBooking->SelectVehicleBookingService($ServiceContractId,$SDStart,$SDEnd,$TDStart,$TDEnd);
      // dd($VehicleBookingArray);
      $datashow .= "<h4 align='center'>Report Vehicle</H4><br>";
      $datashow .= "<table align='left' width='98%' cellspacing='0' border='1' cellpadding='2' bordercolor='#c0c0c0'  style='font-size:13px;'>";
      $datashow .= "<tr bgcolor=#eeeeee>";
      $datashow .= "<th width=10%><nobr><font>Booking ID</font></nobr></th>";
      $datashow .= "<th width=30%><nobr><font>Booking Name</font></nobr></th>";
      $datashow .= "<th width=40%><nobr><font>Service Name</font></nobr></th>";
      $datashow .= "<th width=5%><font>TO/TA</font></th>";
      $datashow .= "<th width=5%><font>Pax</font></th>";
      $datashow .= "<th width=10%><nobr><font>Service Date</font></nobr></th>";
      $datashow .= "</tr>";
      foreach ($VehicleBookingArray as $row) {
      $datashow .= "<tr>";
      $datashow .= "<td><font>$row->TourId</font></td>";
      $datashow .= "<td><font>$row->Clients</font></td>";
      $datashow .= "<td><font>$row->ServiceName</font></td>";
      $datashow .= "<td><font>$row->TO_TA</font></td>";
      $datashow .= "<td><font>$row->Pax</font></td>";
      $datashow .= "<td><font>$row->OnDay</font></td>";
      $datashow .= "</tr>";
      }
    }elseif ($ServiceCode == 'EF') {
      // EntranceFeeBooking
      $EntranceFeeBooking = new EntranceFeeBooking;
      $EntranceFeeBookingArray = $EntranceFeeBooking->SelectEntranceFeeBookingService($ServiceContractId,$SDStart,$SDEnd,$TDStart,$TDEnd);
      $datashow .= "<h4 align='center'>Report Entrance Fee</H4><br>";
      $datashow .= "<table align='left' width='98%' cellspacing='0' border='1' cellpadding='2' bordercolor='#c0c0c0'  style='font-size:13px;'>";
      $datashow .= "<tr bgcolor=#eeeeee>";
      $datashow .= "<th width=10%><nobr><font>Booking ID</font></nobr></th>";
      $datashow .= "<th width=30%><nobr><font>Booking Name</font></nobr></th>";
      $datashow .= "<th width=40%><nobr><font>Service Name</font></nobr></th>";
      $datashow .= "<th width=5%><font>TO/TA</font></th>";
      $datashow .= "<th width=5%><font>Pax</font></th>";
      $datashow .= "<th width=10%><nobr><font>Service Date</font></nobr></th>";
      $datashow .= "</tr>";
      foreach ($EntranceFeeBookingArray as $row) {
      $datashow .= "<tr>";
      $datashow .= "<td><font>$row->TourId</font></td>";
      $datashow .= "<td><font>$row->Clients</font></td>";
      $datashow .= "<td><font>$row->ServiceName</font></td>";
      $datashow .= "<td><font>$row->TO_TA</font></td>";
      $datashow .= "<td><font>$row->Pax</font></td>";
      $datashow .= "<td><font>$row->OnDay</font></td>";
      $datashow .= "</tr>";
      }
    }elseif ($ServiceCode == 'AT' || $ServiceCode == 'GD' || $ServiceCode == 'HT' || $ServiceCode == 'ML' || $ServiceCode == 'OR' || $ServiceCode == 'PK') {
      // MisceOtherBooking
      $MisceOtherBooking = new MisceOtherBooking;
      $MisceOtherBookingArray = $MisceOtherBooking->SelectMisceOtherBookingService($ServiceContractId,$SDStart,$SDEnd,$TDStart,$TDEnd);
      $datashow .= "<h4 align='center'>Report Entrance Fee</H4><br>";
      $datashow .= "<table align='left' width='98%' cellspacing='0' border='1' cellpadding='2' bordercolor='#c0c0c0'  style='font-size:13px;'>";
      $datashow .= "<tr bgcolor=#eeeeee>";
      $datashow .= "<th width=10%><nobr><font>Booking ID</font></nobr></th>";
      $datashow .= "<th width=30%><nobr><font>Booking Name</font></nobr></th>";
      $datashow .= "<th width=40%><nobr><font>Service Name</font></nobr></th>";
      $datashow .= "<th width=5%><font>TO/TA</font></th>";
      $datashow .= "<th width=5%><font>Pax</font></th>";
      $datashow .= "<th width=10%><nobr><font>Service Date</font></nobr></th>";
      $datashow .= "</tr>";
      foreach ($MisceOtherBookingArray as $row) {
      $datashow .= "<tr>";
      $datashow .= "<td><font>$row->TourId</font></td>";
      $datashow .= "<td><font>$row->Clients</font></td>";
      $datashow .= "<td><font>$row->ServiceName</font></td>";
      $datashow .= "<td><font>$row->TO_TA</font></td>";
      $datashow .= "<td><font>$row->Pax</font></td>";
      $datashow .= "<td><font>$row->OnDay</font></td>";
      $datashow .= "</tr>";
      }
    }

    return $datashow;
  }

  public function exportExcel(Request $req)
  {
    $SDStart = isset($req->SDStart)? date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $req->SDStart))) : NULL ;
    $SDEnd = isset($req->SDEnd)? date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $req->SDEnd))) : NULL ;
    $TDStart = isset($req->TDStart)? date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $req->TDStart))) : NULL ;
    $TDEnd = isset($req->TDEnd)? date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $req->TDEnd))) : NULL ;
    $Country = $req->Country;
    $City = $req->City;
    $SupplierType = $req->SupplierType;
    $Supplier = $req->Supplier;
    $ServiceName = $req->ServiceName;
    $array = explode('~',$ServiceName);
    $ServiceContractId = $array[1];
    $ServiceCode = $array[0];
    // $datashow = "";
    // dd($ServiceContractId,$SDStart,$SDEnd,$TDStart,$TDEnd);

    header('Content-Type: application/csv');
    header('Content-type: text/csv; charset=UTF-8');

    $file_handler = fopen("php://output", "w");
    $header_value_array = array();
    $header_value_array[0] = iconv('UTF-8', 'TIS-620', 'Booking ID');
    $header_value_array[1] = iconv('UTF-8', 'TIS-620', 'Booking Name');
    $header_value_array[2] = iconv('UTF-8', 'TIS-620', 'Service Name');
    $header_value_array[3] = iconv('UTF-8', 'TIS-620', 'TO/TA');
    $header_value_array[4] = iconv('UTF-8', 'TIS-620', 'Pax');
    $header_value_array[5] = iconv('UTF-8', 'TIS-620', 'Service Date');
    fputcsv($file_handler, $header_value_array);
    if ($ServiceCode == 'BL' || $ServiceCode == 'BO' || $ServiceCode == 'FL' || $ServiceCode == 'HE' || $ServiceCode == 'TR') {
      // tbFlightBooking
      $tbFlightBookings = new tbFlightBookings;
      $tbFlightBookingsArray = $tbFlightBookings->SelectFlightBookingService($ServiceContractId,$SDStart,$SDEnd,$TDStart,$TDEnd);
      header('Content-Disposition: attachment; filename="OperationFlightReport_'.time().'.csv";');
      foreach ($tbFlightBookingsArray as $row) {
      $item_value_array = array();
      $item_value_array[0] = $row->TourId;
      $item_value_array[1] = $row->Clients;
      $item_value_array[2] = $row->ServiceName;
      $item_value_array[3] = $row->TO_TA;
      $item_value_array[4] = $row->Pax;
      $item_value_array[5] = $row->FlightDate;
        fputcsv($file_handler, $item_value_array);
      }
    }elseif ($ServiceCode == 'VH') {
      // VehicleBooking
      $VehicleBooking = new VehicleBooking;
      $VehicleBookingArray = $VehicleBooking->SelectVehicleBookingService($ServiceContractId,$SDStart,$SDEnd,$TDStart,$TDEnd);
      // dd($VehicleBookingArray);
      header('Content-Disposition: attachment; filename="OperationVehicleReport_'.time().'.csv";');
      foreach ($VehicleBookingArray as $row) {
      $item_value_array = array();
      $item_value_array[0] = $row->TourId;
      $item_value_array[1] = $row->Clients;
      $item_value_array[2] = $row->ServiceName;
      $item_value_array[3] = $row->TO_TA;
      $item_value_array[4] = $row->Pax;
      $item_value_array[5] = $row->OnDay;
        fputcsv($file_handler, $item_value_array);
      }
    }elseif ($ServiceCode == 'EF') {
      // EntranceFeeBooking
      $EntranceFeeBooking = new EntranceFeeBooking;
      $EntranceFeeBookingArray = $EntranceFeeBooking->SelectEntranceFeeBookingService($ServiceContractId,$SDStart,$SDEnd,$TDStart,$TDEnd);
      header('Content-Disposition: attachment; filename="OperationEntrancefeeReport_'.time().'.csv";');
      foreach ($EntranceFeeBookingArray as $row) {
      $item_value_array = array();
      $item_value_array[0] = $row->TourId;
      $item_value_array[1] = $row->Clients;
      $item_value_array[2] = $row->ServiceName;
      $item_value_array[3] = $row->TO_TA;
      $item_value_array[4] = $row->Pax;
      $item_value_array[5] = $row->OnDay;
        fputcsv($file_handler, $item_value_array);
      }
    }elseif ($ServiceCode == 'AT' || $ServiceCode == 'GD' || $ServiceCode == 'HT' || $ServiceCode == 'ML' || $ServiceCode == 'OR' || $ServiceCode == 'PK') {
      // MisceOtherBooking
      $MisceOtherBooking = new MisceOtherBooking;
      $MisceOtherBookingArray = $MisceOtherBooking->SelectMisceOtherBookingService($ServiceContractId,$SDStart,$SDEnd,$TDStart,$TDEnd);
      header('Content-Disposition: attachment; filename="OperationMisceReport_'.time().'.csv";');
      foreach ($MisceOtherBookingArray as $row) {
      $item_value_array = array();
      $item_value_array[0] = $row->TourId;
      $item_value_array[1] = $row->Clients;
      $item_value_array[2] = $row->ServiceName;
      $item_value_array[3] = $row->TO_TA;
      $item_value_array[4] = $row->Pax;
      $item_value_array[5] = $row->OnDay;
        fputcsv($file_handler, $item_value_array);
      }
    }
  }
}
