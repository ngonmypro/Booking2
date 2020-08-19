<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Response;
use App\TOMarketMaster;
use App\tbContacts;
use App\Country;
use App\tbTours;

class SpecialreportController extends controller{
  public function BookingTimeReport()
  {
    $TOMarketMaster = DB::table('TOMarketMaster')
                  ->orderby('TOMarketData','asc')
                  ->get();
    $tbContacts = DB::table('tbContacts')
                  ->join('Quotation','Quotation.ContactsId','=','tbContacts.ContactsId')
                  ->select('tbContacts.ContactsId','tbContacts.CompanyDesc')
                  ->where('tbContacts.IsMainContact','=','1')
                  ->where('tbContacts.CompanyDesc','NOT LIKE','%Closed%')
                  ->groupby('tbContacts.ContactsId','tbContacts.CompanyDesc')
                  ->orderby('tbContacts.CompanyDesc','asc')
                  ->get();
    $Country = DB::table('Country')
                  ->where('Country','=','Vietnam')
                  ->orwhere('Country','=','Indonesia')
                  ->orwhere('Country','=','Myanmar')
                  ->orwhere('Country','=','Thailand')
                  ->orderby('Country','asc')
                  ->get();
                  // dd($Country);

    return view('specialreport.bookingturnaroundtime')->with(compact('TOMarketMaster','tbContacts','Country'));
  }

  public function ReportBookingTime(Request $req)
  {
    $bsdateOld = str_replace('/', '-', $req->bsdate );
    $bsdate = date("Y-m-d", strtotime($bsdateOld));
    $bedateOld = str_replace('/', '-', $req->bedate );
    $bedate = date("Y-m-d", strtotime($bedateOld));
    $tomarket = $req->tomarket;
    $client = $req->client;
    $country = $req->country;
    $datashow = "";
      $tbTours = new tbTours();
      $ToursArray = $tbTours->SelectUser($bsdate, $bedate, $tomarket, $client, $country);

      $datashow .= "<table align='center' width='80%' cellspacing='0' border='1' cellpadding='2' bordercolor='#c0c0c0'  style='font-size:13px;'> ";
      $datashow .= "<tr bgcolor=#eeeeee>";
      $datashow .= "<th width=30%><nobr><font>Name</font></nobr></th>";
      $datashow .= "<th width=40%><nobr><font>Team</font></nobr></th>";
      $datashow .= "<th  width=20%><font>Country</font></th>";
      $datashow .= "<th width=10%><font>Avg. per day</font></th>";
      $datashow .= "</tr>";
      foreach ($ToursArray as $ToursArray_row) {
        $uid = $ToursArray_row->uid;
        $uname = $ToursArray_row->uname;
        $department = $ToursArray_row->department;
        $Country = $ToursArray_row->Country;
        $City = $ToursArray_row->City;

        $ToursCount = $tbTours->SelectTours($bsdate, $bedate, $tomarket, $client, $country, $uid);
        foreach ($ToursCount as $ToursCount_row) {
          $tot = number_format($ToursCount_row->tot, 2);
          // echo $uid.' '.$uname.' '.$department.' '.$Country.'('.$City.')'.$tot.'<br>';
          $datashow .= "<tr onmouseover='this.bgColor=\"Yellow\";' onmouseout='this.bgColor=\"\";'>";
          $datashow .= "<td><font>$uname</font></td>";
          $datashow .= "<td><font>$department</font></td>";
          $datashow .= "<td><font>$Country ( $City ) </font></td>";
          $datashow .= "<td align='center'><font>$tot</font></td>";
          $datashow .= "</tr>";
        }
      }
      $datashow .= "</table><br>";
    // dd($ToursArray);
    return $datashow;
  }

  public function Exportexcel(Request $req)
  {
    $bsdateOld = str_replace('/', '-', $req->bsdate );
    $bsdate = date("Y-m-d", strtotime($bsdateOld));
    $bedateOld = str_replace('/', '-', $req->bedate );
    $bedate = date("Y-m-d", strtotime($bedateOld));
    $tomarket = $req->tomarket;
    $client = $req->client;
    $country = $req->country;
    $datashow = "";
      $tbTours = new tbTours();
      $ToursArray = $tbTours->SelectUser($bsdate, $bedate, $tomarket, $client, $country);

      header('Content-Type: application/csv');
      header('Content-type: text/csv; charset=UTF-8');
      header('Content-Disposition: attachment; filename="ReportBookingTime'.time().'.csv";');

      $file_handler = fopen("php://output", "w");
      $header_value_array = array();
      $header_value_array[0] = iconv('UTF-8', 'TIS-620', 'Name');
      $header_value_array[1] = iconv('UTF-8', 'TIS-620', 'Team');
      $header_value_array[2] = iconv('UTF-8', 'TIS-620', 'Country');
      $header_value_array[3] = iconv('UTF-8', 'TIS-620', 'Avg. per day');
      fputcsv($file_handler, $header_value_array);
      foreach ($ToursArray as $ToursArray_row) {
        $uid = $ToursArray_row->uid;
        $uname = $ToursArray_row->uname;
        $department = $ToursArray_row->department;
        $Country = $ToursArray_row->Country;
        $City = $ToursArray_row->City;

        $ToursCount = $tbTours->SelectTours($bsdate, $bedate, $tomarket, $client, $country, $uid);
        foreach ($ToursCount as $ToursCount_row) {
          $tot = number_format($ToursCount_row->tot, 2);
          $item_value_array = array();
          $item_value_array[0] = $uname;
          $item_value_array[1] = $department;
          $item_value_array[2] = $Country.'('.$City.')';
          $item_value_array[3] = $tot;
            fputcsv($file_handler, $item_value_array);
        }
      }
    // return $datashow;
  }

}
