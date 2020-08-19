<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use File;
use Session;
use Response;
use Mail;

use App\tbCountry;

class JobTransferController extends controller
{
  public function JobTransfer()
  {

    $user_array = DB::select("
    SELECT tbISUsers.UsersId
    ,tbISUsers.UName AS uname
    ,tbISUsers.UserShortcut
    ,[IS].ISID
    FROM [IS]
    INNER JOIN Employee ON [IS].EmployeeID = Employee.EmployeeID
    INNER JOIN tbISUsers ON Employee.Email = tbISUsers.Email
    ORDER BY tbISUsers.uname;
    ");

    $company_array = DB::select("
    SELECT ContactsId
		,CAST(CompanyDesc as varchar(1000)) AS company
		,Shortcut
		FROM  tbContacts
		WHERE ContactsId IS NOT NULL
		AND ContactsId<>''
		AND IsMainContact = 1
		ORDER BY CompanyDesc;
    ");

    $invoice_array = DB::select("
    SELECT [IS].ISID, FirstName,LastName
		FROM [IS]
		INNER JOIN EMPLOYEE ON dbo.Employee.EmployeeID=[IS].EmployeeID
		WHERE DEPT_ID=15 AND [IS].inactive=0
    ORDER BY FirstName,LastName;
    ");

    $location_array = DB::select("
    SELECT Location.LocationID,Location.Company
    FROM dbo.Location
    ORDER BY Location.Company ;
    ");

    $to_market_array = DB::select("
    SELECT  CAST( TOMarketId AS varchar(36)) as TOMarketId, TOMarketData
    FROM dbo.TOMarketMaster
    ORDER BY TOMarketData;
    ");

    return view('jobassignment.jobassign')->with(compact('user_array','company_array','invoice_array','location_array','to_market_array'));
  }

  public function SearchJobTransfer(Request $request){
    if(!empty($request->slISUser)){
      list($tcid,$tc_isid,$tc_shortcut) = explode('|',$request->slISUser);
    }else{
      $tcid = "";
      $tc_isid = "";
    }

    if(!empty($request->slTDUser)){
      list($tdid,$td_isid,$td_shortcut) = explode('|',$request->slTDUser);
    }else{
      $tdid = "";
      $td_isid = "";
    }

    $slTOMarket = $request->slTOMarket;
    $rdType = $request->rdType;
    $slInvoice = $request->slInvoice;
    $slCompany = $request->slCompany;

    if($rdType == "1"){

      $where="";
      if(!empty($slTOMarket))
      {
        $where.=" AND dbo.Location.TOMarketId='$slTOMarket' ";
      }

      if(!empty($tcid))
      {
        $where.=" AND tbTours.UsersId='$tcid' ";
      }

      if(!empty($td_isid))
      {
        $where.=" AND tbTours.TravelDesignerManager='$td_isid' ";
      }

      //dd($td_isid,$where);
      $tour_data = DB::select("
      SELECT
          dbo.tbContacts.ContactsId,
          TOMarketData,
          CompanyDesc,
          count(TourId) AS count
      FROM    dbo.tbContacts
          LEFT OUTER JOIN dbo.Location ON dbo.tbContacts.ContactsId = dbo.Location.ContactsId
          LEFT OUTER JOIN dbo.TOMarketMaster ON dbo.Location.TOMarketId = dbo.TOMarketMaster.TOMarketId
          INNER JOIN dbo.tbTours ON dbo.tbTours.ContactsId = dbo.tbContacts.ContactsId
      WHERE IsMainContact = 1
        $where
      GROUP BY dbo.tbContacts.ContactsId,TOMarketData,CompanyDesc
      ORDER BY TOMarketData ,CompanyDesc, count(TourId) desc
      ");

      $html = '';
      if(count($tour_data)>0){
        $html .='<table class="table" id="tb_result">';
        $html .='   <tr>';
        $html .='     <th><input type="checkbox" name="allCheck" id="allCheck" onclick="checkedAll()"></th>';
        $html .='     <th>TO Market</th>';
        $html .='     <th>Company Name</th>';
        $html .='     <th>Booking</th>';
        $html .='   </tr>';

        foreach ($tour_data as $value) {
          $html .='   <tr>';
          $html .='     <td><input type="checkbox" name="contactid[]" id="contactid[]" value="'.$value->ContactsId.'"></td>';
          $html .='     <td>'.$value->TOMarketData.'</td>';
          $html .='     <td>'.$value->CompanyDesc.'</td>';
          $html .='     <td><a href="'.url('/JobTransfer/bookingdetail/'.$value->ContactsId.'|'.$tcid.'|'.$td_isid).'" target="_blank">'.$value->count.'</a></td>';
          $html .='   </tr>';
        }

        $html .='</table>';
      }

      echo $html;
    }else if($rdType == "2"){

      if(empty($tcid)){
        echo "Please select IS User/Travel Consultant.";
        exit();
      }

      $where="";
      if(!empty($slTOMarket))
      {
        $where.=" AND dbo.Location.TOMarketId='$slTOMarket' ";
      }

      $quote_data = DB::select("
      SELECT  dbo.tbContacts.ContactsId ,
          TOMarketData ,
          CompanyDesc ,
          COUNT(QuotationCode) AS count
      FROM    dbo.tbContacts
          INNER JOIN dbo.Location ON dbo.tbContacts.ContactsId = dbo.Location.ContactsId
          INNER JOIN dbo.TOMarketMaster ON dbo.Location.TOMarketId = dbo.TOMarketMaster.TOMarketId
          INNER JOIN dbo.Quotation ON dbo.Quotation.ContactsId = dbo.tbContacts.ContactsId
      WHERE   IsMainContact = 1
          AND dbo.Quotation.UsersId = '$tcid'
          $where
      GROUP BY dbo.tbContacts.ContactsId,TOMarketData ,CompanyDesc
      ORDER BY TOMarketData , CompanyDesc,COUNT(QuotationCode) DESC
      ");

      //dd($quote_data);

      $html = '';
      if(count($quote_data)>0){
        $html .='<table class="table" id="tb_result">';
        $html .='   <tr>';
        $html .='     <th><input type="checkbox" name="allCheck" id="allCheck" onclick="checkedAll()"></th>';
        $html .='     <th>TO Market</th>';
        $html .='     <th>Company Name</th>';
        $html .='     <th>Quotation</th>';
        $html .='   </tr>';

        foreach ($quote_data as $value) {
          $html .='   <tr>';
          $html .='     <td><input type="checkbox" name="contactid[]" id="contactid[]" value="'.$value->ContactsId.'"></td>';
          $html .='     <td>'.$value->TOMarketData.'</td>';
          $html .='     <td>'.$value->CompanyDesc.'</td>';
          $html .='     <td><a href="'.url('/JobTransfer/quotedetail/'.$value->ContactsId.'|'.$tcid).'" target="_blank">'.$value->count.'</a></td>';
          $html .='   </tr>';
        }

        $html .='</table>';
      }
      echo $html;
    }else if($rdType == "3"){
      $where="";

      if(!empty($slCompany)){
        $where=" AND Location.Locationid = '$slCompany' ";
      }

      $location_data = DB::select("
      SELECT Location.Locationid,Location.Company
      	   ,(dbo.Location.Street + ' ' +dbo.Location.City + ' ' + dbo.Location.PostalCode) AS locationaddress
         ,tbContacts.CompanyDesc
         ,(dbo.tbContacts.Street  + ' ' + dbo.tbContacts.City + ' ' + dbo.tbContacts.ZipCode) AS invoiceaddress
      		FROM [IS]
      	INNER JOIN dbo.tbContacts ON [IS].ISID=dbo.tbContacts.ISInvoice
      	INNER JOIN dbo.Location ON tbContacts.LocationID = Location.LocationID
      	INNER JOIN dbo.Employee ON [IS].EmployeeID=EMPLOYEE.EmployeeID
      	WHERE 1=1
      	AND tbContacts.ISInvoice = $slInvoice
      	$where
      	ORDER BY Location.Company,tbContacts.CompanyDesc
      ");

      //dd($slCompany,$slInvoice);
      $html = '';
      if(count($location_data)>0){
        $html .='<table class="table" id="tb_result">';
        $html .='   <tr>';
        $html .='     <th>Choose</th>';
        $html .='     <th>Location Company</th>';
        $html .='     <th>Location Address</th>';
        $html .='     <th>Invoice Company</th>';
        $html .='     <th>Invoice Address</th>';
        $html .='   </tr>';

        foreach ($location_data as $value) {
          $html .='   <tr>';
          $html .='     <td><input type="checkbox" name="contactid[]" id="contactid[]" value="'.$value->Locationid.'"></td>';
          $html .='     <td>'.$value->Company.'</td>';
          $html .='     <td>'.$value->locationaddress.'</td>';
          $html .='     <td>'.$value->CompanyDesc.'</td>';
          $html .='     <td>'.$value->invoiceaddress.'</td>';
          $html .='   </tr>';
        }

        $html .='</table>';
      }
      echo $html;


    }
  }

  public function DetailTourJobTransfer($param){
    $type = "booking";
    list($contactid,$userid,$td_isid) = explode('|',$param);
    //dd($contactid,$userid,$td_isid);
    $where = "";
    if(!empty($userid)){
      $where .= " AND UsersId='$userid'";
    }
    if(!empty($td_isid)){
      $where .= " AND dbo.tbTours.TravelDesignerManager='$td_isid'";
    }
    $tour_data = DB::select("
      SELECT
          TourId ,
          Clients ,
          dbo.date_format(TourStartDate, 'yyyy-mm-dd') AS TourSDate ,
          dbo.date_format(TourEndDate, 'yyyy-mm-dd') AS TourEDate,
          NoPax
      FROM    dbo.tbTours
      WHERE ContactsId ='$contactid'
            $where
    ");

    return view('jobassignment.detail')->with(compact('type','tour_data'));
  }

  public function DetailQuoteJobTransfer($param){
    $type = "quote";
    list($contactid,$userid) = explode('|',$param);

    $quote_data = DB::select("
      SELECT
      	QuotationCode ,
      	QuotationName ,
      	dbo.date_format(ValidFrom, 'yyyy-mm-dd') AS validFrom ,
      	dbo.date_format(ValidTo, 'yyyy-mm-dd') AS validTo
      FROM    dbo.Quotation
      where ContactsId ='$contactid' AND UsersId='$userid'
    ");

    //dd($quote_data);

    return view('jobassignment.detail')->with(compact('type','quote_data'));
  }

  public function UpdateTCBooking(Request $request){
    if(empty($request->contactid)){
      echo "Please select company.";
      exit();
    }else{
      $contactid = implode("','",$request->contactid);
    }

    if(empty($request->slISUser)){
      echo "Please select IS User/Travel Consultant.";
      exit();
    }else{
      list($tcid,$tc_isid,$tc_shortcut) = explode('|',$request->slISUser);
    }

    if(empty($request->slTransferISUser)){
      echo "Please select Transfer To.";
      exit();
    }else{
      list($to_tcid,$to_tc_isid,$to_tc_shortcut) = explode('|',$request->slTransferISUser);
    }


    $sql = "
      UPDATE dbo.tbTours
      SET
      UsersId='$to_tcid',
      TravelConsultant='$to_tc_isid'
      FROM    dbo.tbContacts
          LEFT OUTER JOIN dbo.Location ON dbo.tbContacts.ContactsId = dbo.Location.ContactsId
          LEFT OUTER JOIN dbo.TOMarketMaster ON dbo.Location.TOMarketId = dbo.TOMarketMaster.TOMarketId
          INNER JOIN dbo.tbTours ON dbo.tbTours.ContactsId = dbo.tbContacts.ContactsId
      WHERE IsMainContact = 1
        AND tbTours.UsersId='$tcid'
        AND tbContacts.ContactsId in ('$contactid')
    ";
    //echo "<pre>".$sql;
    try{
      DB::statement($sql);
      echo "Save Done.";
    }catch(Exception $ex){
      echo "Error please Contact IT.";
    }
  }

  public function UpdateTDBooking(Request $request){
    if(empty($request->contactid)){
      echo "Please select company.";
      exit();
    }else{
      $contactid = implode("','",$request->contactid);
    }

    if(empty($request->slTDUser)){
      echo "Please select IS User/Travel Consultant.";
      exit();
    }else{
      list($tdid,$td_isid,$td_shortcut) = explode('|',$request->slTDUser);
    }

    if(empty($request->slTransferTDUser)){
      echo "Please select Transfer To.";
      exit();
    }else{
      list($to_tdid,$to_td_isid,$to_td_shortcut) = explode('|',$request->slTransferTDUser);
    }

    $sql = "
      UPDATE dbo.tbTours
      SET
      TravelDesignerManager='$to_td_isid'
      FROM    dbo.tbContacts
          LEFT OUTER JOIN dbo.Location ON dbo.tbContacts.ContactsId = dbo.Location.ContactsId
          LEFT OUTER JOIN dbo.TOMarketMaster ON dbo.Location.TOMarketId = dbo.TOMarketMaster.TOMarketId
          INNER JOIN dbo.tbTours ON dbo.tbTours.ContactsId = dbo.tbContacts.ContactsId
      WHERE IsMainContact = 1
        AND tbTours.TravelDesignerManager='$td_isid'
        AND tbContacts.ContactsId in ('$contactid')
    ";
    //echo "<pre>".$sql;
    try{
      DB::statement($sql);
      echo "Save Done.";
    }catch(Exception $ex){
      echo "Error please Contact IT.";
    }
  }

  public function UpdateTCQuotation(Request $request){
    if(empty($request->contactid)){
      echo "Please select company.";
      exit();
    }else{
      $contactid = implode("','",$request->contactid);
    }

    if(empty($request->slISUser)){
      echo "Please select IS User/Travel Consultant.";
      exit();
    }else{
      list($tcid,$tc_isid,$tc_shortcut) = explode('|',$request->slISUser);
    }

    if(empty($request->slTransferISUser)){
      echo "Please select Transfer To.";
      exit();
    }else{
      list($to_tcid,$to_tc_isid,$to_tc_shortcut) = explode('|',$request->slTransferISUser);
    }

    $sql = "
    UPDATE Quotation
 			SET	UserName='$to_tc_shortcut' , UsersId='$to_tcid'
				FROM    dbo.tbContacts
				LEFT OUTER JOIN dbo.Location ON dbo.tbContacts.ContactsId = dbo.Location.ContactsId
				LEFT OUTER JOIN dbo.TOMarketMaster ON dbo.Location.TOMarketId = dbo.TOMarketMaster.TOMarketId
				INNER JOIN dbo.Quotation ON dbo.Quotation.ContactsId = dbo.tbContacts.ContactsId
		WHERE   IsMainContact = 1
				AND dbo.Quotation.UsersId = '$tcid'
				AND tbContacts.ContactsId in ('$contactid')
    ";
    //echo "<pre>".$sql;
    try{
      DB::statement($sql);
      echo "Save Done.";
    }catch(Exception $ex){
      echo "Error please Contact IT.";
    }
  }

  public function UpdateInvoiceContact(Request $request){
    if(empty($request->contactid)){
      echo "Please select company.";
      exit();
    }else{
      $contactid = implode("','",$request->contactid);
    }

    if(empty($request->slInvoice)){
      echo "Please select IS User/Travel Consultant.";
      exit();
    }else{
      $invoice = $request->slInvoice;
    }

    if(empty($request->slTransferInvoice)){
      echo "Please select Transfer To.";
      exit();
    }else{
      $to_invoice = $request->slTransferInvoice;
    }

    $sql = "
    UPDATE dbo.tbContacts SET
    				ISInvoice=$to_invoice
    		 		FROM [IS]
    				INNER JOIN dbo.tbContacts ON [IS].ISID=dbo.tbContacts.ISInvoice
    				INNER JOIN dbo.Location ON tbContacts.LocationID = Location.LocationID
    				INNER JOIN dbo.Employee ON [IS].EmployeeID=EMPLOYEE.EmployeeID
    WHERE 1=1
    				AND tbContacts.ISInvoice = $invoice
    				AND dbo.Location.Locationid in ('$contactid')
    ";
    //echo "<pre>".$sql;
    try{
      DB::statement($sql);
      echo "Save Done.";
    }catch(Exception $ex){
      echo "Error please Contact IT.";
    }
  }
}
?>
