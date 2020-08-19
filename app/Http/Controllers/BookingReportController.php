<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Response;
use Mail;
use App\tbISUsers;
use App\tbHotels;
use App\tbContacts;
use App\tbHotelRoomCategory;

class BookingReportController extends controller{
  public function Booking($param){
    list($ssid,$isid) = explode('|',$param);
    $ROffice = new tbISUsers;
    $ROfficeArray = $ROffice->SelectUser();
    $CityHotel = new tbHotels;
    $CityArray = $CityHotel->SelectCity();
    $HotelArray = $CityHotel->SelestHotel();
    $CountryArray = array("Cambodia","Indonesia","Laos","Myanmar","Thailand","Vietnam");
    $Contacts = new tbContacts;
    $ContactsArray = $Contacts->SelectContacts();
    // $hotel = 'BKG1201400000349';
    // $RoomCat = new tbHotelRoomCategory();
    // $RoomCatArray = $RoomCat->SelectRoomCat($hotel);
    //echo $ROfficeArray;
   return view('booking.bookdatareport')->with(compact('ROfficeArray','CityArray','HotelArray','CountryArray','ContactsArray','ssid','isid'));
  }

  public function ChangeRoom(Request $req)
  {
    $hotel = $req->hotel;
    $RoomCat = new tbHotelRoomCategory();
    $RoomCatArray = $RoomCat->SelectRoomCat($hotel);
    return json_encode($RoomCatArray);
  }

  public function ChangeHotel(Request $req)
  {
    $country = "";
    $citycountry = $req->city;
    $array = explode('~',$citycountry);
    $city = $array[1];
    if ($req->Country != '') {
      $country = $req->Country;
    }else {
      $country = $array[0];
    }
    //dd($country);
    $Hotel = new tbHotels;
    $HotelArray = $Hotel->SelectHotelChangeCity($country,$city);
    // dd($HotelArray);
    return json_encode($HotelArray);
  }

  public function ChangeCity(Request $req)
  {
    $countrydata = $req->country;
    // dd($countrydata);
    $Country = new tbHotels;
    $CityArray = $Country->SelectCountryChangeCity($countrydata);
    return json_encode($CityArray);
  }

  public function SearchBooking(Request $req)
  {
    $ROffice =!empty($req->ROffice) ? $req->ROffice:null;
    $Operator = !empty($req->Operator) ? $req->Operator:null;
    $Country = !empty($req->Country) ? $req->Country:null;

    $city = $req->City;
    if (!empty($city)) {
      $ArrayCity = explode('~',$city);
      $City = $ArrayCity[1];
    }else {
      $City = "";
    }

    $Hotel = !empty($req->Hotel) ? $req->Hotel:null;
    $RoomCat = !empty($req->RoomCat) ? $req->RoomCat:null;
    $Cancel = !empty($req->Cancel) ? $req->Cancel:null;
    $Status = $req->Status;
    $DOS_Date = !empty($req->DOS_Date) ? $req->DOS_Date:null;
    if (!empty($DOS_Date)) {
      $DOS_Date_N = str_replace('/', '-', $DOS_Date );
      $DOS_Date_NEW = date("Y-m-d", strtotime($DOS_Date_N));
    }else {
      $DOS_Date_NEW = '';
    }

    $DOS_DateEnd = !empty($req->DOS_DateEnd) ? $req->DOS_DateEnd:null;
    if (!empty($DOS_DateEnd)) {
      $DOS_DateEnd_N = str_replace('/', '-', $DOS_DateEnd );
      $DOS_DateEnd_NEW = date("Y-m-d", strtotime($DOS_DateEnd_N));
    }else {
      $DOS_DateEnd_NEW = '';
    }

    $HBG_Date = !empty($req->HBG_Date) ? $req->HBG_Date:null;
    if (!empty($HBG_Date)) {
        $HBG_Date_N = str_replace('/', '-', $HBG_Date );
        $HBG_Date_NEW = date("Y-m-d", strtotime($HBG_Date_N));
    }else {
      $HBG_Date_NEW = '';
    }

    $HBG_DateEnd = !empty($req->HBG_DateEnd) ? $req->HBG_DateEnd:null;
    if (!empty($HBG_DateEnd)) {
      $HBG_DateEnd_N = str_replace('/', '-', $HBG_DateEnd );
      $HBG_DateEnd_NEW = date("Y-m-d", strtotime($HBG_DateEnd_N));
    }else {
      $HBG_DateEnd_NEW = '';
    }

    $SendMail = $req->SendMail;
    $Email = $req->Email;
    $Name = $req->Name;
    $From = $req->From;
    $Remark = $req->Remark;

    $DataReport = new tbContacts;
    $ReportBookingArray = $DataReport->ReportBooking($ROffice, $Operator, $Country, $City, $Hotel, $RoomCat, $Cancel, $Status, $DOS_Date_NEW, $DOS_DateEnd_NEW, $HBG_Date_NEW, $HBG_DateEnd_NEW);
    //dd($ReportBookingArray);
    //$DataArray = [];
    $DataArray2 = array();

    if (count($ReportBookingArray) > 0) {
      $result = [];
      $DataSendMail = "";
      $HeadData = "";
      $TotalData = "";
      $numHead = "";
      $y = 1;
      $numberBooking = 1;
      $TourId = ""; $Oldid = ""; $Clients = ""; $Refer = ""; $Nopax = ""; $Sdate = ""; $Edate = ""; $Bdate = ""; $Cost = ""; $Company = "";
      $Uname = ""; $Hotel = ""; $City = ""; $Rtype = ""; $Status = ""; $Hsdate = ""; $Htdate = ""; $Hbdate = ""; $Sgl = ""; $Dbl = "";
      $Tpl = ""; $Twn = ""; $NumRoom = ""; $Nights = ""; $Numrs = ""; $Bremark = "";$tmp_TourId=""; $totalnights = 0; $totalroom = 0;
      foreach ($ReportBookingArray as $row) {

      //   if ($SendMail == 0) {
      //   // อาเรย์ไว้โชว์ข้อมูล
      //   $DataArray2[] = array(
      //     'oldid'=>$row->oldid,
      //     'TourId'=>$row->TourId,
      //     'TourCount'=>count($row->TourId),
      //     'clients'=>$row->clients,
      //     'nopax'=>intval($row->nopax),
      //     'sdate'=>$row->sdate,
      //     'edate'=>$row->edate,
      //     'bdate'=>$row->bdate,
      //     'CompanyDesc'=>$row->CompanyDesc,
      //     'uname'=>$row->uname,
      //     'hotel'=>$row->hotel,
      //     'Status'=>$row->Status,
      //     'Sgl'=>intval($row->Sgl),
      //     'Dbl'=>intval($row->Dbl),
      //     'Tpl'=>intval($row->Tpl),
      //     'twn'=>intval($row->twn),
      //     'rtype'=>$row->rtype,
      //     'hsdate'=>$row->hsdate,
      //     'htdate'=>$row->htdate,
      //     'hbdate'=>$row->hbdate,
      //     'bremark'=>$row->bremark,
      //     'city'=>$row->city,
      //     'tguid'=>$row->tguid,
      //     'nights'=>$row->nights,
      //     'refer'=>$row->refer,
      //     'sguid'=>$row->sguid,
      //     'cost'=>number_format($row->cost,2)
      //   );
      // }else {
        $TourId = strtoupper($row->TourId);  $Oldid = $row->oldid;  $Clients = $row->clients;  $Refer = $row->refer;  $Nopax = intval($row->nopax);
        $Sdate = $row->sdate;  $Edate = $row->edate;  $Bdate = $row->bdate;  $Cost = number_format($row->cost,2);  $Company = $row->CompanyDesc;
        $Uname = $row->uname;  $Hotel = $row->hotel;  $City = $row->city;  $Rtype = $row->rtype;  $Status = $row->Status;
        $Hsdate  = $row->hsdate;  $Htdate  = $row->htdate;  $Hbdate  = $row->hbdate;  $Sgl = $row->Sgl;  $Dbl = $row->Dbl;
        $Tpl = $row->Tpl;  $Twn = $row->twn;  $NumRoom = $Sgl + $Dbl + $Tpl;  $Nights = $row->nights;  $Numrs = $Dbl - $Twn;
        $Bremark = $row->bremark;

        $totalnights = $totalnights + $Nights;
        $totalroom = $totalroom + $NumRoom;

        if($TourId!==$tmp_TourId){
          if($y!=1){
            $DataSendMail .= "</table><br/>";
          }
          $DataSendMail.= "<table align='center' width='100%' cellspacing='0' border='1' cellpadding='2' bordercolor='#c0c0c0'  style='font-size:12px;'>";
          // อาเรย์ไว้ส่ง MAIL
          $DataSendMail.= "<tr>\n<td width='10%'  style='font-size:13px;'><b> No: $numberBooking - $TourId";
          if ($Oldid != "") { $DataSendMail .= "($DataSendMail)"; }
          $DataSendMail .= "</b></td>\n";

          $DataSendMail .= "<td width='27%'  style='font-size:13px;'>\n";
          if ($SendMail == 0) {
            $DataSendMail .= "<a href='https://staff.icstravelgroup.com/bookingonline_v2/Overview.php?TourId=$TourId' target='_blank'>$Clients</a>";
          }else{
            $DataSendMail .= "$Clients";
          }
          if ($Refer != "") {
          $DataSendMail .= "&nbsp;($Refer)"; }
          $DataSendMail .= "</td>\n";

          $DataSendMail .= "<td width='5%'>Pax: $Nopax</td>\n";
          $DataSendMail .= "<td width='8%'>Tour start: <nobr>$Sdate</nobr></td>\n";
          $DataSendMail .= "<td width='8%'>Tour end: <nobr>$Edate</nobr></td>\n";
          $DataSendMail .= "<td width='10%'>Booked: <nobr>$Bdate</nobr></td>\n";
          $DataSendMail .= "<td width='10%'>Total USD: <nobr>$Cost</nobr></td>\n";
          $DataSendMail .= "<td width='24%'>$Company <br><b>IS User/Travel Consultant: $Uname</b></td></tr>\n";

          $DataSendMail .= "<tr>\n";
          $DataSendMail .= "<td colspan='2' style='font-size:13px;'><b>$Hotel - $City <br> $Rtype</b></td>\n";
          $DataSendMail .= "<td align='center'>Status: $Status</td>\n";
          $DataSendMail .= "<td>Arr: <nobr>$Hsdate</nobr></td>\n";
          $DataSendMail .= "<td>Out: <nobr>$Htdate</nobr></td>\n";
          $DataSendMail .= "<td>Booked: <nobr>$Hbdate</nobr></td>\n";

          $DataSendMail .= "<td colspan='2'>\n";
          $DataSendMail .= "$Nights Nights / ";
          $DataSendMail .= "$NumRoom Room (";
          if($Sgl > 0){ $DataSendMail .= "SGL: $Sgl "; }
          if($Numrs > 0){ $DataSendMail .= "DBL: $Numrs "; }
          if($Twn > 0){ $DataSendMail .= "TWN: $Twn "; }
          if($Tpl > 0){ $DataSendMail .= "TPL: $Tpl "; }
          $DataSendMail .= ")";
          $DataSendMail .= "&nbsp;&nbsp;<b>$Bremark</td>\n</tr>\n";
          // $DataSendMail .= "<tr><td align='right' colspan='4'><b>Grand Total : </b></td>\n";
          // $DataSendMail .= "<tr><td align='right' colspan='2'><b>Nights : </b>$totalnights</td>\n";
          // $DataSendMail .= "<tr><td align='right'><b>Room : </b>$totalroom</td></tr>\n";
          $numberBooking += 1;
        }else{
          $DataSendMail .= "<tr>\n";
          $DataSendMail .= "<td colspan='2' style='font-size:13px;'><b>$Hotel - $City <br> $Rtype</b></td>\n";
          $DataSendMail .= "<td align='center'>Status: $Status</td>\n";
          $DataSendMail .= "<td>Arr: <nobr>$Hsdate</nobr></td>\n";
          $DataSendMail .= "<td>Out: <nobr>$Htdate</nobr></td>\n";
          $DataSendMail .= "<td>Booked: <nobr>$Hbdate</nobr></td>\n";

          $DataSendMail .= "<td colspan='2'>\n";
          $DataSendMail .= "$Nights Nights / ";
          $DataSendMail .= "$NumRoom Room (";
          if($Sgl > 0){ $DataSendMail .= "SGL: $Sgl "; }
          if($Numrs > 0){ $DataSendMail .= "DBL: $Numrs "; }
          if($Twn > 0){ $DataSendMail .= "TWN: $Twn "; }
          if($Tpl > 0){ $DataSendMail .= "TPL: $Tpl "; }
          $DataSendMail .= ")";
          $DataSendMail .= "&nbsp;&nbsp;<b>$Bremark</td>\n</tr>\n";
        }

        if(count($ReportBookingArray) == $y){


          $DataSendMail .= "</table><br/>";
        }
        $tmp_TourId = $TourId;
        $y = $y + 1;

        //}
      }

      $TotalData.= "<table align='right' width='40%' cellspacing='0' border='1' cellpadding='2' bordercolor='#c0c0c0'  style='font-size:12px;'>";
      $TotalData .= "<tr><td align='center' width='50%' ><b>Grand Total : </b></td>\n";
      $TotalData .= "<td align='center' width='25%'><b>Nights : </b>$totalnights</td>\n";
      $TotalData .= "<td align='center' width='25%'><b>Room : </b>$totalroom</td></tr>\n";
      $TotalData .= "</table><br/><br/>";

      $numHead = $numberBooking - 1;
      $HeadData = "<table align='center' class='table table-bordered' style='width:98%; background-color:#eeeeee;' cellspacing='2'>";
      $HeadData .= "<th  style='width:95%; background-color:#eeeeee;'>Hotel Bookings Period Report - ($numHead Records)</th>";
      $HeadData .= "</table>";
    }else {
      $HeadData = "<table align='center' class='table table-bordered' style='width:98%; background-color:#eeeeee;' cellspacing='2'>";
      $HeadData .= "<th  style='width:95%; background-color:#eeeeee;'>Hotel Bookings Period Report - (0 Records)</th>";
      $HeadData .= "</table>";

      $DataSendMail = "<table align='center' width='95%' cellspacing='0' border='0' cellpadding='2' bordercolor='#c0c0c0'  style='font-size:13px;'>";
      $DataSendMail .= "<td style='font-size:16px; text-align: center;'><b> No records found! </b></td>";
      $DataSendMail .= "</table><br>";

      $TotalData.= "<table align='right' width='40%' cellspacing='0' border='1' cellpadding='2' bordercolor='#c0c0c0'  style='font-size:12px;'>";
      $TotalData .= "<td style='font-size:16px; text-align: center;'><b> No records found! </b></td>";
      $TotalData .= "</table><br/><br/>";
    }

    //echo $DataSendMail;
    //exit();

    if ($SendMail == 0) {
      //return view('booking.bookdatareport')->with(compact('DataSendMail','HeadData'));
      echo $HeadData.$DataSendMail.$TotalData;
    }else {
        $localtime = localtime();
				$localtime_assoc = localtime(time(), true);
				$xtid=($localtime[1]*60).($localtime[2]*3600);
         //$filename = "C:\\xampp\\htdocs\\testsend\\Hotel_Period_".$xtid.".txt";
				$filename = "E:\\temp\\Hotel_Period_".$xtid.".txt";
				if (!file_exists($filename))
				{
				    $handle = fopen($filename, "w");
					if (is_writable($filename))
					{
						$content="";
						$vhead="<html>\n<body>\n<head>\n<style>\n";
						$vhead.="TD,TH { font:8 pt;} \n";
						$vhead.="TH { font:bold; }\n";
						$vhead.="Body { Font-Family:tahoma, arial,,sans-serif; }\n";
						$vhead.="</style>\n";
						$content.=$vhead;
						$content.=$DataSendMail;
						$content.=$TotalData;
						$content.="\n<br/><font size=2>$Remark</font>\n";
						$content.="</body>\n</html>\n";

						if (fwrite($handle, $content) === FALSE) {
							echo "Cannot write to file ($filename)";
							exit;
						}
					}
					else
					{
						 echo "The file $filename is not writable";
					}
					fclose($handle);
				}
        //dd($content);


				# Create Header
				$from=$From;
				$fromMsg="ISInfo";
        $subject = "Hotel Booking Period Reports -Attn $Name";
        $email = $Email;
        //$message.= $Remark;

        Mail::send('booking.sendMail', ['body' => $content], function ($message) use ($subject,$email,$from) {
            $message->subject($subject);
            $message->to($email);
            $message->from($from, "");
          });

          return json_encode("Send Mail Success");
    }
  }

  public function exportExcel(Request $req)
  {
    $ROffice =!empty($req->ROffice) ? $req->ROffice:null;
    $Operator = !empty($req->Operator) ? $req->Operator:null;
    $Country = !empty($req->Country) ? $req->Country:null;

    $city = $req->City;
    if (!empty($city)) {
      $ArrayCity = explode('~',$city);
      $City = $ArrayCity[1];
    }else {
      $City = "";
    }

    $Hotel = !empty($req->Hotel) ? $req->Hotel:null;
    $RoomCat = !empty($req->RoomCat) ? $req->RoomCat:null;
    $Cancel = !empty($req->Cancel) ? $req->Cancel:null;
    $Status = $req->Status;
    $DOS_Date = !empty($req->DOS_Date) ? $req->DOS_Date:null;
    if (!empty($DOS_Date)) {
      $DOS_Date_N = str_replace('/', '-', $DOS_Date );
      $DOS_Date_NEW = date("Y-m-d", strtotime($DOS_Date_N));
    }else {
      $DOS_Date_NEW = '';
    }

    $DOS_DateEnd = !empty($req->DOS_DateEnd) ? $req->DOS_DateEnd:null;
    if (!empty($DOS_DateEnd)) {
      $DOS_DateEnd_N = str_replace('/', '-', $DOS_DateEnd );
      $DOS_DateEnd_NEW = date("Y-m-d", strtotime($DOS_DateEnd_N));
    }else {
      $DOS_DateEnd_NEW = '';
    }

    $HBG_Date = !empty($req->HBG_Date) ? $req->HBG_Date:null;
    if (!empty($HBG_Date)) {
        $HBG_Date_N = str_replace('/', '-', $HBG_Date );
        $HBG_Date_NEW = date("Y-m-d", strtotime($HBG_Date_N));
    }else {
      $HBG_Date_NEW = '';
    }

    $HBG_DateEnd = !empty($req->HBG_DateEnd) ? $req->HBG_DateEnd:null;
    if (!empty($HBG_DateEnd)) {
      $HBG_DateEnd_N = str_replace('/', '-', $HBG_DateEnd );
      $HBG_DateEnd_NEW = date("Y-m-d", strtotime($HBG_DateEnd_N));
    }else {
      $HBG_DateEnd_NEW = '';
    }

    // $SendMail = $req->SendMail;
    // $Email = $req->Email;
    // $Name = $req->Name;
    // $From = $req->From;
    // $Remark = $req->Remark;

    $DataReport = new tbContacts;
    $ReportBookingArray = $DataReport->ReportBooking($ROffice, $Operator, $Country, $City, $Hotel, $RoomCat, $Cancel, $Status, $DOS_Date_NEW, $DOS_DateEnd_NEW, $HBG_Date_NEW, $HBG_DateEnd_NEW);
    // dd($ReportBookingArray);
    header('Content-Type: application/csv');
    header('Content-type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="GuideCallingReport_'.time().'.csv";');
    $file_handler = fopen("php://output", "w");
    $header_value_array = array();
    $header_value_array[0] = iconv('UTF-8', 'TIS-620', 'TourId');
    $header_value_array[1] = iconv('UTF-8', 'TIS-620', 'Clients');
    $header_value_array[2] = iconv('UTF-8', 'TIS-620', 'Refer');
    $header_value_array[3] = iconv('UTF-8', 'TIS-620', 'Pax');
    $header_value_array[4] = iconv('UTF-8', 'TIS-620', 'Tour start');
    $header_value_array[5] = iconv('UTF-8', 'TIS-620', 'Tour end');
    $header_value_array[6] = iconv('UTF-8', 'TIS-620', 'Booked');
    $header_value_array[7] = iconv('UTF-8', 'TIS-620', 'Total USD');
    $header_value_array[8] = iconv('UTF-8', 'TIS-620', 'Company');
    $header_value_array[9] = iconv('UTF-8', 'TIS-620', 'IS User/Travel Consultant');
    $header_value_array[10] = iconv('UTF-8', 'TIS-620', 'Hotel');
    $header_value_array[11] = iconv('UTF-8', 'TIS-620', 'City');
    $header_value_array[12] = iconv('UTF-8', 'TIS-620', 'RoomType');
    $header_value_array[13] = iconv('UTF-8', 'TIS-620', 'Status');
    $header_value_array[14] = iconv('UTF-8', 'TIS-620', 'Arr');
    $header_value_array[15] = iconv('UTF-8', 'TIS-620', 'Out');
    $header_value_array[16] = iconv('UTF-8', 'TIS-620', 'Booked');
    $header_value_array[17] = iconv('UTF-8', 'TIS-620', 'Nights');
    $header_value_array[18] = iconv('UTF-8', 'TIS-620', 'TotalRoom');
    $header_value_array[19] = iconv('UTF-8', 'TIS-620', 'SGL');
    $header_value_array[20] = iconv('UTF-8', 'TIS-620', 'DBL');
    $header_value_array[21] = iconv('UTF-8', 'TIS-620', 'TWN');
    $header_value_array[22] = iconv('UTF-8', 'TIS-620', 'TPL');
    $header_value_array[23] = iconv('UTF-8', 'TIS-620', 'Remark');
    fputcsv($file_handler, $header_value_array);
    $TourId = "";
    $Oldid = "";
    $Clients = "";
    $Refer = "";
    $Nopax = "";
    $Sdate = "";
    $Edate = "";
    $Bdate = "";
    $Cost = "";
    $Company = "";
    $Uname = "";
    $Hotel = "";
    $City = "";
    $Rtype = "";
    $Status = "";
    $Hsdate = "";
    $Htdate = "";
    $Hbdate = "";
    $Sgl = "";
    $Dbl = "";
    $Tpl = "";
    $Twn = "";
    $NumRoom = "";
    $Nights = "";
    $Numrs = "";
    $Bremark = "";

    foreach ($ReportBookingArray as $row) {
      $TourId = strtoupper($row->TourId);  $Oldid = $row->oldid;  $Clients = $row->clients;  $Refer = $row->refer;  $Nopax = intval($row->nopax);
      $Sdate = $row->sdate;  $Edate = $row->edate;  $Bdate = $row->bdate;  $Cost = number_format($row->cost,2);  $Company = $row->CompanyDesc;
      $Uname = $row->uname;  $Hotel = $row->hotel;  $City = $row->city;  $Rtype = $row->rtype;  $Status = $row->Status;
      $Hsdate  = $row->hsdate;  $Htdate  = $row->htdate;  $Hbdate  = $row->hbdate;  $Sgl = $row->Sgl;  $Dbl = $row->Dbl;
      $Tpl = $row->Tpl;  $Twn = $row->twn;  $NumRoom = $Sgl + $Dbl + $Tpl;  $Nights = $row->nights;  $Numrs = $Dbl - $Twn;
      $Bremark = $row->bremark;

      $item_value_array = array();
      $item_value_array[0] = $TourId;
      $item_value_array[1] = $Clients;
      $item_value_array[2] = $Refer;
      $item_value_array[3] = $Nopax;
      $item_value_array[4] = $Sdate;
      $item_value_array[5] = $Edate;
      $item_value_array[6] = $Bdate;
      $item_value_array[7] = $Cost;
      $item_value_array[8] = $Company;
      $item_value_array[9] = $Uname;
      $item_value_array[10] = $Hotel;
      $item_value_array[11] = $City;
      $item_value_array[12] = $Rtype;
      $item_value_array[13] = $Status;
      $item_value_array[14] = $Hsdate;
      $item_value_array[15] = $Htdate;
      $item_value_array[16] = $Hbdate;
      $item_value_array[17] = $Nights;
      $item_value_array[18] = $NumRoom;
      $item_value_array[19] = $Sgl;
      $item_value_array[20] = $Numrs;
      $item_value_array[21] = $Twn;
      $item_value_array[22] = $Tpl;
      $item_value_array[23] = $Bremark;

      fputcsv($file_handler, $item_value_array);
    }

    fclose($file_handler);
  }


}
