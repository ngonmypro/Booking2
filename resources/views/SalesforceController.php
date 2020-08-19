<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Response;
use App\BusinessUnit;
use App\SalesforceAccount;
use App\Employee;
use App\TOMarketMaster;
use App\LocationType;
use App\Company;
use App\Country;
use App\Location;
use App\tbAuth;
use App\tbContacts;
use App\IS;
use App\LeadSourceType;
use App\LeadSourceInformation;

class SalesforceController extends Controller {

  public function MainDetail($session_id = null)
  {
    return view('SalesforceToCRM/index')->with(compact('session_id'));
  }

  public function SearchSalesforce(Request $req)
  {
    $namesf = $req->namesf;
    $session_id = $req->session_id;
    $Auth = new tbAuth();
    $AuthArray = $Auth->SelectAuth($session_id);
    // dd($AuthArray);
    $SalesforceAccount = new SalesforceAccount();
    $SalesforceAccountArray = $SalesforceAccount->SelectCountry($namesf);
    $Location = new Location();
     // dd($SalesforceAccountArray);
      $i = 0;
      $datashow = "";
      $account_id = "";
      $contact_id = "";
      $datashow = "<table align='center'class='table table-bordered'>";
      $datashow .= "<tr>";
      $datashow .= "<th style='width:50px; text-align:center;'>No.</th>";
      $datashow .= "<th>Company</th>";
      $datashow .= "<th>Name</th>";
      $datashow .= "<th>Sale</th>";
      $datashow .= "<th></th></tr>";
      foreach ($SalesforceAccountArray as $row) {
        $email = $row->emailC;
        $employee_id = $row->employee_idC;
        $account_id = $row->account_id;
        $contact_id = $row->contact_id;
        $account_owner = $row->account_owner;
        $salesforce_account_pk_idC = $row->salesforce_account_pk_idC;
        $first_nameC = $row->first_nameC;
        $last_nameC = $row->last_nameC;
        $created_atC = $row->created_atC ;
        $updated_atC = $row->updated_atC;
        $is_syncC = $row->is_syncC;
        // dd($account_id);

        if ($is_syncC == 1) {
          $i+=1;
          $datashow .= "<tr><td align='center'>$i</td>";
          $datashow .= "<td>$row->account_name</td>";
          $datashow .= "<td>$row->first_nameC $row->last_nameC</td>";
          $datashow .= "<td>$account_owner</td>";
          $datashow .= "<td align='center'><a href='http://g.icstravelgroup.com/APISF/public/ViewSalesforceMergeToCRM/$session_id|$salesforce_account_pk_idC|$first_nameC|$last_nameC|$account_id' target='_blank'>
          <button type='button' class='btn btn-flat btn-info btn-xs' name='button' style=''>Merge</button></a></td></td></tr>";
        }elseif ($is_syncC == 2) {
          $i+=1;
          if ($employee_id == '') {
            $datashow .= "<tr><td align='center'>$i</td>";
            $datashow .= "<td>$row->account_name</td>";
            $datashow .= "<td>$row->first_nameC $row->last_nameC</td>";
            $datashow .= "<td>$account_owner</td>";
            $datashow .= "<td align='center'><a href='http://g.icstravelgroup.com/APISF/public/ViewSalesforceMergeToCRM/$session_id|$salesforce_account_pk_idC|$first_nameC|$last_nameC|$account_id' target='_blank'>
            <button type='button' class='btn btn-flat btn-info btn-xs' name='button' style=''>Merge</button></a></td></td></tr>";
          }else {
            $datashow .= "<tr><td align='center'>$i</td>";
            $datashow .= "<td>$row->account_name</td>";
            $datashow .= "<td>$row->first_nameC $row->last_nameC</td>";
            $datashow .= "<td>$account_owner</td>";
            $datashow .= "<td align='center'><a href='http://g.icstravelgroup.com/APISF/public/ViewSalesforceUpdateToCRM/$session_id|$salesforce_account_pk_idC|$first_nameC|$last_nameC|$account_id|$employee_id' target='_blank'>
            <button type='button' class='btn btn-flat btn-warning btn-xs' name='button' style=''>Update</button></a></td></td></tr>";
          }
        }
        // $LocationArray = $Location->ChkLocation($account_id);
        //  // dd($LocationArray);
        //
        //  if (count($LocationArray) > 0) {
        //   foreach ($LocationArray as $rowLocation) {
        //     $Epmloyee = new Employee();
        //     $EmployeeArray = $Epmloyee->ChkEmpID($employee_id);
        //     if (count($EmployeeArray) > 0) {
        //       foreach ($EmployeeArray as $EmployeeArrayRow) {
        //         if ($updated_atC != '') {
        //           if ($updated_atC == $EmployeeArrayRow->SFDateCreated) {
        //
        //           }else {
        //             $i+=1;
        //             $datashow .= "<tr><td align='center'>$i</td>";
        //             $datashow .= "<td>$row->account_name</td>";
        //             $datashow .= "<td>$row->first_nameC $row->last_nameC</td>";
        //             $datashow .= "<td align='center'><a href='http://g.icstravelgroup.com/APISF/public/ViewSalesforceUpdateToCRM/$session_id|$salesforce_account_pk_idC|$first_nameC|$last_nameC|$rowLocation->LocationID|$EmployeeArrayRow->EmployeeID' target='_blank'>
        //             <button type='button' class='btn btn-flat btn-warning btn-sm' name='button' style='width:80%'>Update</button></a></td></td></tr>";
        //           }
        //         }else {
        //           if ($created_atC == $EmployeeArrayRow->SFDateCreated) {
        //             // code...
        //           }else {
        //             $i+=1;
        //             $datashow .= "<tr><td align='center'>$i</td>";
        //             $datashow .= "<td>$row->account_name</td>";
        //             $datashow .= "<td>$row->first_nameC $row->last_nameC</td>";
        //             $datashow .= "<td align='center'><a href='http://g.icstravelgroup.com/APISF/public/ViewSalesforceUpdateToCRM/$session_id|$salesforce_account_pk_idC|$first_nameC|$last_nameC|$rowLocation->LocationID|$EmployeeArrayRow->EmployeeID' target='_blank'>
        //             <button type='button' class='btn btn-flat btn-warning btn-sm' name='button' style='width:80%'>Update</button></a></td></td></tr>";
        //           }
        //         }
        //       }
        //     }else {
        //       $i+=1;
        //       $datashow .= "<tr><td align='center'>$i</td>";
        //         $datashow .= "<td>$row->account_name</td>";
        //         $datashow .= "<td>$row->first_nameC $row->last_nameC</td>";
        //         $datashow .= "<td align='center'><a href='http://g.icstravelgroup.com/APISF/public/ViewSalesforceMergeToCRM/$session_id|$salesforce_account_pk_idC|$first_nameC|$last_nameC|$rowLocation->LocationID' target='_blank'>
        //         <button type='button' class='btn btn-flat btn-info btn-sm' name='button' style='width:80%'>Merge</button></a></td></td></tr>";
        //     }
        //   }
        //   // $i = $i+1;
        // }
        // else {
        //   $datashow .= "<td>$row->account_name</td>";
        //   $datashow .= "<td>$row->first_nameC $row->last_nameC</td>";
        //   $datashow .= "<td align='center'><a href='http://g.icstravelgroup.com/APISF/public/ViewSalesforceCreateToCRM/$session_id|$salesforce_account_pk_idC|$first_nameC|$row->last_nameC' target='_blank'>
        //   <button type='button' class='btn btn-flat btn-success btn-sm' name='button' style='width:80%'>Create</button></a></td></tr>";
        // }


      }
      $datashow .= "</table>";


      echo $datashow;
  }

  public function SearchSalesforce2(Request $req)
  {
    $namesf = $req->namesf;
    $session_id = $req->session_id;
    $Auth = new tbAuth();
    $AuthArray = $Auth->SelectAuth($session_id);
    // dd($AuthArray);
    $SalesforceAccount = new SalesforceAccount();
    $SalesforceAccountArray = $SalesforceAccount->SelectLocation($namesf);
    $Location = new Location();
     // dd($SalesforceAccountArray);
      $i = 0;
      $datashow = "";
      $account_id = "";
      $contact_id = "";
      $datashow = "<table align='center'class='table table-bordered'>";
      $datashow .= "<tr>";
      $datashow .= "<th style='width:50px; text-align:center;'>No.</th>";
      $datashow .= "<th>Company</th>";
      $datashow .= "<th>Country</th>";
      $datashow .= "<th>Sale</th>";
      $datashow .= "<th></th></tr>";
      foreach ($SalesforceAccountArray as $row) {
        $account_id = $row->account_id;
        $created_at = $row->created_at;
        $updated_at = $row->updated_at;
        $account_owner = $row->account_owner;
        $is_sync = $row->is_sync;
        $LocationID = $row->LocationID;
        $salesforce_account_pk_id = $row->salesforce_account_pk_id;

        if ($is_sync == '1') {
          $i+=1;
          $datashow .= "<tr><td align='center'>$i</td>";
          $datashow .= "<td>$row->account_name</td>";
          $datashow .= "<td>$row->account_country</td>";
          $datashow .= "<td>$account_owner</td>";
          $datashow .= "<td align='center'><a href='http://g.icstravelgroup.com/APISF/public/ViewSalesforceCreateLocationToCRM/$session_id|$salesforce_account_pk_id' target='_blank'>
          <button type='button' class='btn btn-flat btn-success btn-xs' name='button' style=''>Create</button></a></td></tr>";
        }elseif ($is_sync == '2') {
          $i+=1;
          if ($LocationID == '') {
            $datashow .= "<tr><td align='center'>$i</td>";
            $datashow .= "<td>$row->account_name</td>";
            $datashow .= "<td>$row->account_country</td>";
            $datashow .= "<td>$account_owner</td>";
            $datashow .= "<td align='center'><a href='http://g.icstravelgroup.com/APISF/public/ViewSalesforceCreateLocationToCRM/$session_id|$salesforce_account_pk_id' target='_blank'>
            <button type='button' class='btn btn-flat btn-success btn-xs' name='button' style=''>Create</button></a></td></tr>";
          }else {
            $datashow .= "<tr><td align='center'>$i</td>";
            $datashow .= "<td>$row->account_name</td>";
            $datashow .= "<td>$row->account_country</td>";
            $datashow .= "<td>$account_owner</td>";
            $datashow .= "<td align='center'><a href='http://g.icstravelgroup.com/APISF/public/ViewSalesforceUpdateLocationToCRM/$session_id|$salesforce_account_pk_id|$account_id' target='_blank'>
            <button type='button' class='btn btn-flat btn-warning btn-xs' name='button' style=''>Update</button></a></td></td></tr>";
          }
        }

      // $LocationArray = $Location->ChkLocation($account_id/*,$created_at,$updated_at*/);
      //    // dd($LocationArray);
      //
      //   if (count($LocationArray) > 0) {
      //     foreach ($LocationArray as $rowLocation) {
      //       if ($updated_at != '') {
      //         if ($updated_at == $rowLocation->SFDateCreated) {
      //           // code...
      //         }else {
      //           $i+=1;
      //           $datashow .= "<tr><td align='center'>$i</td>";
      //           $datashow .= "<td>$row->account_name</td>";
      //           $datashow .= "<td>$row->account_country</td>";
      //           $datashow .= "<td align='center'><a href='http://g.icstravelgroup.com/APISF/public/ViewSalesforceUpdateLocationToCRM/$session_id|$row->salesforce_account_pk_id|$rowLocation->LocationID' target='_blank'>
      //           <button type='button' class='btn btn-flat btn-warning btn-sm' name='button' style='width:80%'>Update</button></a></td></td></tr>";
      //         }
      //       }else {
      //         if ($created_at == $rowLocation->SFDateCreated) {
      //           // code...
      //         }else {
      //           $i+=1;
      //           $datashow .= "<tr><td align='center'>$i</td>";
      //           $datashow .= "<td>$row->account_name</td>";
      //           $datashow .= "<td>$row->account_country</td>";
      //           $datashow .= "<td align='center'><a href='http://g.icstravelgroup.com/APISF/public/ViewSalesforceUpdateLocationToCRM/$session_id|$row->salesforce_account_pk_id|$rowLocation->LocationID' target='_blank'>
      //           <button type='button' class='btn btn-flat btn-warning btn-sm' name='button' style='width:80%'>Update</button></a></td></td></tr>";
      //         }
      //       }
      //     }
      //   }else {
      //     $i+=1;
      //     $datashow .= "<tr><td align='center'>$i</td>";
      //     $datashow .= "<td>$row->account_name</td>";
      //     $datashow .= "<td>$row->account_country</td>";
      //     $datashow .= "<td align='center'><a href='http://g.icstravelgroup.com/APISF/public/ViewSalesforceCreateLocationToCRM/$session_id|$row->salesforce_account_pk_id' target='_blank'>
      //     <button type='button' class='btn btn-flat btn-success btn-sm' name='button' style='width:80%'>Create</button></a></td></tr>";
      //   }

        // $i = $i+1;
      }
      $datashow .= "</table>";


      echo $datashow;
  }

  public function SearchICSCrm(Request $req)
  {
    $namesf = $req->namesf;
    $session_id = $req->session_id;
    $Auth = new tbAuth();
    $AuthArray = $Auth->SelectAuth($session_id);
    $Location = new Location();
    $LocationArray = $Location->SelectLocation($namesf);

      $i = 1;
      $datashow = "";
      $datashow = "<table align='center'class='table table-bordered'>";
      $datashow .= "<tr>";
      $datashow .= "<th style='width:100px; text-align:center;'>No.</th>";
      $datashow .= "<th>Name</th></tr>";
      foreach ($LocationArray as $row) {
        $datashow .= "<tr><td align='center'>$i</td>";
        $datashow .= "<td>$row->FirstName $row->LastName</td></tr>";
        $i = $i+1;
      }
      $datashow .= "</table>";

      echo $datashow;
  }

  public function ViewSalesforceCreateToCRM($param)
  {
    // dd($param);
    //$salesforce_account_pk_id = $param;
    list($session_id,$salesforce_account_pk_id,$first_nameC,$last_nameC) = explode('|',$param);
    $SalesforceAccount = new SalesforceAccount();
    $SalesforceAccountArray = $SalesforceAccount->ViewDataSalesforce($salesforce_account_pk_id);
    // dd($SalesforceAccountArray);
    $TOMarketMaster = new TOMarketMaster();
    $TOMarketMasterArray = $TOMarketMaster->SelectTOMarketMaster();
    $LocationType = new LocationType();
    $LocationTypeArray = $LocationType->SelectLocationType();
    $TO_TA = array("TO", "TA", "ONLINE" );
    $Company = new Company();
    $CompanyArray = $Company->SelectCompany();
    $BusinessUnit = new BusinessUnit();
    $BusinessUnitArray = $BusinessUnit->SelectBusinessUnit();
    $Country = new Country();
    $CountryArray = $Country->SelectCountry();

    // dd($SalesforceAccountArray,$TOMarketMasterArray,$LocationTypeArray,$TO_TA,$CompanyArray,$BusinessUnitArray,$CountryArray);
    return view('SalesforceToCRM/showsalesforcecreate')->with(compact('SalesforceAccountArray','TOMarketMasterArray','LocationTypeArray','TO_TA','CompanyArray','session_id','BusinessUnitArray','CountryArray'));
  }

  public function ViewSalesforceCreateLocationToCRM($param)
  {
    list($session_id,$salesforce_account_pk_id) = explode('|',$param);
    $SalesforceAccount = new SalesforceAccount();
    $SalesforceAccountArray = $SalesforceAccount->ViewDataSalesforceAccount($salesforce_account_pk_id);
    foreach ($SalesforceAccountArray as $row) {
      $countryA = $row->countryA;
    }
    // dd($SalesforceAccountArray);
    $TOMarketMaster = new TOMarketMaster();
    $TOMarketMasterArray = $TOMarketMaster->SelectTOMarketMaster();
    $LocationType = new LocationType();
    $LocationTypeArray = $LocationType->SelectLocationType();
    $TO_TA = array("TO", "TA", "ONLINE" );
    $Company = new Company();
    $CompanyArray = $Company->SelectCompany();
    $BusinessUnit = new BusinessUnit();
    $BusinessUnitArray = $BusinessUnit->SelectBusinessUnit();
    $Country = new Country();
    $CountryArray = $Country->SelectCountry($countryA);
      if (count($CountryArray) > 0) {
        foreach ($CountryArray as $key) {
          $CountryID = $key->CountryID;
          $Country = $key->Country;
        }
      }else {
        $CountryID = '0';
        $Country = 'No results found';
      }
      // dd($CountryID,$Country);
    // dd($SalesforceAccountArray,$TOMarketMasterArray,$LocationTypeArray,$TO_TA,$CompanyArray,$BusinessUnitArray,$CountryArray);
    return view('SalesforceToCRM/showsalesforcecreateLocation')->with(compact('SalesforceAccountArray','TOMarketMasterArray','LocationTypeArray','TO_TA','CompanyArray','session_id','BusinessUnitArray','CountryID','Country'));
  }

  public function ViewSalesforceUpdateLocationToCRM($param)
  {
    list($session_id,$salesforce_account_pk_id,$account_id) = explode('|',$param);

    $SalesforceAccount = new SalesforceAccount();
    $SalesforceAccountArray = $SalesforceAccount->ViewDataSalesforceLocationUpdate($salesforce_account_pk_id);

    foreach ($SalesforceAccountArray as $row) {
      $countryA = $row->countryA;
    }
     // dd($countryA);
    $TOMarketMaster = new TOMarketMaster();
    $TOMarketMasterArray = $TOMarketMaster->SelectTOMarketMaster();
    $LocationType = new LocationType();
    $LocationTypeArray = $LocationType->SelectLocationType();
    $Location = new Location();
    $LocationArray = $Location->ViewDataMerge($account_id);
    foreach ($LocationArray as $rowloca) {
      $locationid = $rowloca->LocationID;
    }
    // dd($locationid);
    // $Employee = new Employee();
    // $EmployeeArray = $Employee->ViewDataUpdate($employeeid);
    $TO_TA = array("TO", "TA", "ONLINE" );
    $Company = new Company();
    $CompanyArray = $Company->SelectCompany();
    $BusinessUnit = new BusinessUnit();
    $BusinessUnitArray = $BusinessUnit->SelectBusinessUnit();
    $Country = new Country();
    $CountryArray = $Country->SelectCountry($countryA);
    // dd($CountryArray);
    if (count($CountryArray) > 0) {
      foreach ($CountryArray as $key) {
      $CountryID = $key->CountryID;
      $Country = $key->Country;
      }
    }else {
      $CountryID = '0';
      $Country = 'No results found';
    }
    // dd($CountryID,$Country);

    return view('SalesforceToCRM/showsalesforUpdateLocation')->with(compact('SalesforceAccountArray','session_id','TOMarketMasterArray','LocationTypeArray','LocationArray','TO_TA','CompanyArray','BusinessUnitArray','CountryID','Country','locationid'));
  }

  public function UpdateSfLocationToCrm(Request $req)
  {
    // dd($req->account_sourceA);
    $DateTime = date('Y-m-d H:i:s');
    $session_id = $req->session_id;
    $Auth = new tbAuth();
    $AuthArray = $Auth->SelectAuth($session_id);
    foreach ($AuthArray as $AuthRow) {
      $ServerId = $AuthRow->ServerId;
      $BCode = $AuthRow->BCode;
      $ISID = $AuthRow->ISID;
    }


    $sales_officeA = isset($req->sales_officeA)? $req->sales_officeA : NULL; /* ยังไม่ได้บันทึก */
    if ($req->account_ownerA != '') {
      // dd($req->account_ownerA);
      if ($req->account_ownerA == 'Bettina Grünenberg') {
          $nameEmp = 'Bettina Gruenenberg Joseph';
      }else {
          $nameEmp = $req->account_ownerA;
      }
      $Employee = new Employee();
      $EmployeeArray = $Employee->SelectIsid($nameEmp);
      // dd($EmployeeArray);
      foreach ($EmployeeArray as $EmployeeArrayData) {
        $EmployeeID = $EmployeeArrayData->ISID;
      }

    }
    if ($req->update_date != '') {
      $dateSF = $req->update_date;
    }else {
      $dateSF = $req->created_date;
    }
    // $IS = new IS();
    // $NewIS = $IS->SelectISID($EmployeeID);
    // dd($EmployeeID);

    $sales_office = "";
    if ($sales_officeA == 'Bangkok') {
      $sales_office = "IS BKK";
    }elseif ($sales_officeA == 'Munich') {
      $sales_office = "IS MUC";
    }elseif ($sales_officeA == 'San Francisco') {
      $sales_office = "IS SFO";
    }elseif ($sales_officeA == 'Bali') {
      $sales_office = "IS BAL";
    }

    $Tclient = "";
    if ($req->Tclient == 'Travel Agency' || $req->Tclient == 'TA') {
      $Tclient = "TA";
    }elseif ($req->Tclient == 'Tour Operator' || $req->Tclient == 'TO') {
      $Tclient = "TO";
    }elseif ($req->Tclient == 'Online Travel Agent' || $req->Tclient == 'Online') {
      $Tclient = "ONLINE";
    }
    // dd($EmployeeID);

// เช็ค SalesOffice
        $SalesOffice = DB::connection("sqlsrv")->select("SELECT OfficeID FROM IS_Office WHERE OfficeCode = '$sales_office'");
        foreach ($SalesOffice as $rowsales) {
          $SalesOfficeid = $rowsales->OfficeID;
        }

        // dd($SalesOfficeid , $Tclient);
    if ($EmployeeID != '') {
    // dd($req->employeeid);
    $locationid = $req->locationid;
    $Location = Location::find($locationid);
    $Location->CompanyID = $req->GCountry;
    // $Location->OfficeCode = $SalesOfficeid;
    $Location->LocationTypeID = '1';
    $Location->Company = isset($req->account_nameA)? $req->account_nameA : NULL;
    $Location->Street = isset($req->billing_addressA)? $req->billing_addressA : NULL;
    $Location->ZipCode = isset($req->ZipCodeA)? $req->ZipCodeA : NULL;
    $Location->City = isset($req->cityA)? $req->cityA : NULL;
    $Location->CCodePhone = isset($req->phone_country_prefixA)? $req->phone_country_prefixA : NULL;
    $Location->ACodePhone = isset($req->phone_city_prefixA)? $req->phone_city_prefixA : NULL;
    $Location->Phone = isset($req->phoneA)? $req->phoneA : NULL;
    $Location->CCodeFax = isset($req->fax_country_prefixA)? $req->fax_country_prefixA : NULL;
    $Location->ACodeFax = isset($req->fax_city_prefixA)? $req->fax_city_prefixA : NULL;
    $Location->Fax = isset($req->faxA)? $req->faxA : NULL;
    $Location->CountryID = isset($req->countryA)? $req->countryA : NULL;
    $Location->Url1 = isset($req->websiteA)? $req->websiteA : NULL;
    $Location->Updated = $DateTime;
    $Location->UserBy = $ISID;
    // $Location->MICE_AGENT = $req->MICE_AGENT;
    $Location->BusinessUnitID = isset($req->business_unit_teamA)? $req->business_unit_teamA : NULL;
    // $Location->SFDateCreated = isset($req->SFCreatedTimeAcc)? $req->SFCreatedTimeAcc : NULL;
    $Location->SFUserCreated = isset($req->SFUserCreatedAcc)? $req->SFUserCreatedAcc : NULL;
    $Location->SFAccountId = isset($req->SFAccountId)? $req->SFAccountId : NULL;
    $Location->TOMarketId = $req->TOMarket;
    $Location->TO_TA = $Tclient;
    $Location->SFDateCreated = $dateSF;

    $Location->save();

    $Country = new Country;
    $CountryArray = $Country->SelectCountrytocontact($req->countryA);
    foreach ($CountryArray as $dataCountry) {
      $NameCountry = $dataCountry->Country;
    }


      $tbContacts = tbContacts::where('LocationId', '=' , $locationid)->first();
      $tbContacts->LocationID = $locationid;
      // $tbContacts->FormerClient = $req->;
      // $tbContacts->ContactsId = $ContactsId;
      $tbContacts->KindOfContact = "cop";
      $tbContacts->CompanyDesc = isset($req->account_nameA)? $req->account_nameA : NULL;
      $tbContacts->Street = isset($req->billing_addressA)? $req->billing_addressA : NULL;
      $tbContacts->City = isset($req->cityA)? $req->cityA : NULL;
      $tbContacts->ZipCode = isset($req->ZipCodeA)? $req->ZipCodeA : NULL;
      $tbContacts->ISSales = $EmployeeID;
      $tbContacts->ISOffice = $SalesOfficeid;
      $tbContacts->Country = $NameCountry;
      $tbContacts->BusinessUnitID = isset($req->business_unit_teamA)? $req->business_unit_teamA : NULL;
      $tbContacts->SalePhoto = $EmployeeID;
      $tbContacts->SaleDescription = $req->account_ownerA;

      $tbContacts->save();

      if ($req->account_sourceA != '') {
        // dd($locationid);
        $LeadSourceType = new LeadSourceType;
          $LeadSourceTypeArray = $LeadSourceType->SelectSourceType($req->account_sourceA);
          foreach ($LeadSourceTypeArray as $rowLeadSource) {
            $LeadSourcedata = $rowLeadSource->SourceId;
          }

        $LeadSourceInformation = LeadSourceInformation::where('LocationId', '=' , $locationid)->first();
        // dd($LeadSourceInformation);
        if (count($LeadSourceInformation)>0) {
          $LeadSourceInformation->LocationId = $locationid;
          $LeadSourceInformation->SourceId = $LeadSourcedata;
          $LeadSourceInformation->save();
        }else {
          $LeadSourceInformation = new LeadSourceInformation();
          $LeadSourceInformation->LocationId = $locationid;
          $LeadSourceInformation->SourceId = $LeadSourcedata;
          $LeadSourceInformation->save();
        }
      }

      $SalesforceAccount = new SalesforceAccount();
      $SalesforceAccountArray =  $SalesforceAccount->UpdateSyncAccount($req->SFAccountId);

    echo "Update data location success.";
  }else {
    echo "Name Owner does not match!! Please check in the Salesforce system.";
  }
  }

  public function ViewSalesforceUpdateToCRM($param)
  {
    // dd($param);
    list($session_id,$salesforce_account_pk_id,$first_nameC,$last_nameC,$account_id,$employeeid) = explode('|',$param);
    $SalesforceAccount = new SalesforceAccount();
    $SalesforceAccountArray = $SalesforceAccount->ViewDataSalesforceMerge($salesforce_account_pk_id,$first_nameC,$last_nameC);

    $TOMarketMaster = new TOMarketMaster();
    $TOMarketMasterArray = $TOMarketMaster->SelectTOMarketMaster();
    $LocationType = new LocationType();
    $LocationTypeArray = $LocationType->SelectLocationType();
    $Location = new Location();
    $LocationArray = $Location->ViewDataMerge($account_id);
    foreach ($LocationArray as $row) {
      $locationid = $row->LocationID;
    }
    // dd($locationid);
    $Employee = new Employee();
    $EmployeeArray = $Employee->ViewDataUpdate($employeeid);
    $TO_TA = array("TO", "TA", "ONLINE" );
    $Company = new Company();
    $CompanyArray = $Company->SelectCompany();
    $BusinessUnit = new BusinessUnit();
    $BusinessUnitArray = $BusinessUnit->SelectBusinessUnit();
    /*$Country = new Country();
    $CountryArray = $Country->SelectCountry();*/

    return view('SalesforceToCRM/showsalesforceupdate')->with(compact('SalesforceAccountArray','session_id','TOMarketMasterArray','LocationTypeArray','LocationArray','EmployeeArray','TO_TA','CompanyArray','BusinessUnitArray'/*,'CountryArray'*/,'locationid','employeeid'));
  }

  public function ViewSalesforceMergeToCRM($param)
  {
    // dd($param);
    list($session_id,$salesforce_account_pk_id,$first_nameC,$last_nameC,$account_id) = explode('|',$param);
    $SalesforceAccount = new SalesforceAccount();
    $SalesforceAccountArray = $SalesforceAccount->ViewDataSalesforceMerge($salesforce_account_pk_id,$first_nameC,$last_nameC);
    // dd($SalesforceAccountArray);
    $Location = new Location();
    $LocationArray = $Location->ViewDataMerge($account_id);
    // dd($LocationArray);
    foreach ($LocationArray as $row) {
      $locationid = $row->LocationID;
    }
    // dd($locationid);
    $TOMarketMaster = new TOMarketMaster();
    $TOMarketMasterArray = $TOMarketMaster->SelectTOMarketMaster();
    $LocationType = new LocationType();
    $LocationTypeArray = $LocationType->SelectLocationType();
    $TO_TA = array("TO", "TA", "ONLINE" );
    $Company = new Company();
    $CompanyArray = $Company->SelectCompany();
    $BusinessUnit = new BusinessUnit();
    $BusinessUnitArray = $BusinessUnit->SelectBusinessUnit();
    // $Country = new Country();
    // $CountryArray = $Country->SelectCountry();

    return view('SalesforceToCRM/showsalesforcemerge')->with(compact('SalesforceAccountArray','LocationArray','TOMarketMasterArray','LocationTypeArray','TO_TA','CompanyArray','BusinessUnitArray'/*,'CountryArray'*/,'session_id','locationid'));
  }

  public function CreateCrm(Request $req)
  {
    $DateTime = date('Y-m-d H:i:s');
    $session_id = $req->session_id;
    $Auth = new tbAuth();
    $AuthArray = $Auth->SelectAuth($session_id);
    foreach ($AuthArray as $AuthRow) {
      $ServerId = $AuthRow->ServerId;
      $BCode = $AuthRow->BCode;
      $ISID = $AuthRow->ISID;
    }
    $ContactsId = $this->getNewContactId($ServerId, $BCode);
    // dd($ContactsId);

  // To Location
    $account_typeA = isset($req->account_typeA)? $req->account_typeA : NULL;
    $sales_officeA = isset($req->sales_officeA)? $req->sales_officeA : NULL; /* ยังไม่ได้บันทึก */


// เช็ค SalesOffice
        // $SalesOffice = DB::connection("sqlsrv")->select("SELECT OfficeID FROM IS_Office WHERE OfficeCode LIKE '%$sales_officeA%'");
        // foreach ($SalesOffice as $rowsales) {
        //   $SalesOfficeid = $rowsales->OfficeID;
        // }

      $Location = new Location();
      // $Location_data = $Location->SelectLocationID($req->SFAccountId);
      // if (count($Location_data) > 0) {
      //   foreach ($Location_data as $Location_dataRow) {
      //     $LocationID_data = $Location_dataRow->LocationID;
      //   }
      // }else {
      //   $LocationID_data = 0;
      // }
      //
      // dd($LocationID_data);
      $Location->CompanyID = $req->GCountry;
      $Location->LocationTypeID = '1';
      $Location->Company = isset($req->account_descriptionA)? $req->account_descriptionA : NULL;
      $Location->Street = isset($req->billing_addressA)? $req->billing_addressA : NULL;
      $Location->City = isset($req->cityA)? $req->cityA : NULL;
      $Location->CCodePhone = isset($req->phone_country_prefixA)? $req->phone_country_prefixA : NULL;
      $Location->ACodePhone = isset($req->phone_city_prefixA)? $req->phone_city_prefixA : NULL;
      $Location->Phone = isset($req->phoneA)? $req->phoneA : NULL;
      $Location->CCodeFax = isset($req->fax_country_prefixA)? $req->fax_country_prefixA : NULL;
      $Location->ACodeFax = isset($req->fax_city_prefixA)? $req->fax_city_prefixA : NULL;
      $Location->Fax = isset($req->faxA)? $req->faxA : NULL;
      $Location->CountryID = isset($req->countryA)? $req->countryA : NULL;
      $Location->Url1 = isset($req->websiteA)? $req->websiteA : NULL;
      $Location->Created = $DateTime;
      $Location->UserBy = $ISID;
      $Location->MICE_AGENT = $req->MICE_AGENT;
      $Location->BusinessUnitID = isset($req->business_unit_teamA)? $req->business_unit_teamA : NULL;
      $Location->SFDateCreated = isset($req->SFCreatedTimeAcc)? $req->SFCreatedTimeAcc : NULL;
      $Location->SFUserCreated = isset($req->SFUserCreatedAcc)? $req->SFUserCreatedAcc : NULL;
      $Location->SFAccountId = isset($req->SFAccountId)? $req->SFAccountId : NULL;
      $Location->TOMarketId = $req->TOMarket;
      $Location->TO_TA = $req->Tclient;

      $Location->save();
      $LocationID = $Location->LocationID;

      // To Employee
      $mailing_addressC = $req->mailing_addressC;

      $Employee = new Employee();
      $Employee->LocationID = $LocationID;
      $Employee->FirstName = $req->first_nameC;
      $Employee->LastName = $req->last_nameC;
      $Employee->NickName = $req->middle_nameC;
      $Employee->Title = $req->title_nameC;
      $Employee->Birthday = isset($req->birthdateC) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $req->birthdateC))) : null;
      $Employee->ACodePhone = $req->phone_country_prefixC;
      $Employee->CCodePhone = $req->phone_city_prefixC;
      $Employee->Phone = $req->phoneC;
      $Employee->ACodeAltPhone = $req->home_country_prefixC;
      $Employee->CCodeAltPhone = $req->home_city_prefixC;
      $Employee->AltPhone = $req->homeC;
      $Employee->ACodeFax = $req->fax_country_prefixC;
      $Employee->CCodeFax = $req->fax_city_prefixC;
      $Employee->Fax = $req->faxC;
      $Employee->ACodeMobile = $req->mobile_country_prefixC;
      $Employee->CCodeMobile = $req->mobile_city_prefixC;
      $Employee->Mobile = $req->mobileC;
      $Employee->leadsource = $req->leadsourceC;
      $Employee->Email = $req->emailC;
      $Employee->SFDateCreated = $req->SFCreatedTimeCon;
      $Employee->SFUserCreated = $req->SFUserCreatedCon;
      $Employee->SFContactId = $req->SFContactId;
      $Employee->Created = $DateTime;
      $Employee->UserBy = $ISID;

      $Employee->save();

      $tbContacts = new tbContacts();
      $tbContacts->LocationID = $LocationID;
      // $tbContacts->FormerClient = $req->;
      $tbContacts->ContactsId = $ContactsId;
      $tbContacts->KindOfContact = "cop";
      $tbContacts->CompanyDesc = isset($req->account_descriptionA)? $req->account_descriptionA : NULL;
      $tbContacts->Street = isset($req->billing_addressA)? $req->billing_addressA : NULL;
      $tbContacts->City = isset($req->cityA)? $req->cityA : NULL;
      // $tbContacts->ZipCode = $req->;
      // $tbContacts->updated_at = $req->$DateTime;
      $tbContacts->save();
      // $tbContacts-> = $req->;
      // $tbContacts-> = $req->;


     echo "Create data success.";
  }

  public function CreateSfLocationToCrm(Request $req)
  {
    $EmployeeID = "";
    $DateTime = date('Y-m-d H:i:s');
    $session_id = $req->session_id;
    $Auth = new tbAuth();
    $AuthArray = $Auth->SelectAuth($session_id);
    foreach ($AuthArray as $AuthRow) {
      $ServerId = $AuthRow->ServerId;
      $BCode = $AuthRow->BCode;
      $ISID = $AuthRow->ISID;
    }
    $ContactsId = $this->getNewContactId($ServerId, $BCode);
    // dd($ContactsId);

  // To Location
    // $account_typeA = isset($req->account_typeA)? $req->account_typeA : NULL;
    $sales_officeA = isset($req->sales_officeA)? $req->sales_officeA : NULL; /* ยังไม่ได้บันทึก */
    if ($req->account_ownerA != '') {
      if ($req->account_ownerA == 'Bettina Grünenberg') {
          $nameEmp = 'Bettina Gruenenberg Joseph';
      }else {
          $nameEmp = $req->account_ownerA;
      }

      $Employee = new Employee();
      $EmployeeArray = $Employee->SelectIsid($nameEmp);
      foreach ($EmployeeArray as $EmployeeArrayData) {
        $EmployeeID = $EmployeeArrayData->ISID;
      }

    }
    // $IS = new IS();
    // $NewIS = $IS->SelectISID($EmployeeID);
    // dd($EmployeeID);

    $sales_office = "";
    if ($sales_officeA == 'Bangkok') {
      $sales_office = "IS BKK";
    }elseif ($sales_officeA == 'Munich') {
      $sales_office = "IS MUC";
    }elseif ($sales_officeA == 'San Francisco') {
      $sales_office = "IS SFO";
    }elseif ($sales_officeA == 'Bali') {
      $sales_office = "IS BAL";
    }

    $Tclient = "";

    if ($req->Tclient == 'Travel Agency' || $req->Tclient == 'TA') {
      $Tclient = "TA";
    }elseif ($req->Tclient == 'Tour Operator' || $req->Tclient == 'TO') {
      $Tclient = "TO";
    }elseif ($req->Tclient == 'Online Travel Agent' || $req->Tclient == 'Online') {
      $Tclient = "ONLINE";
    }

    // dd($EmployeeID);

// เช็ค SalesOffice
        $SalesOffice = DB::connection("sqlsrv")->select("SELECT OfficeID FROM IS_Office WHERE OfficeCode = '$sales_office'");
        foreach ($SalesOffice as $rowsales) {
          $SalesOfficeid = $rowsales->OfficeID;
        }

        // dd($SalesOfficeid , $Tclient);
        if ($req->update_date != '') {
          $dateSF = $req->update_date;
        }else {
          $dateSF = $req->created_date;
        }


        if ($EmployeeID != '') {

      $Location = new Location();
      $Location->CompanyID = $req->GCountry;
      // $Location->OfficeCode = $SalesOfficeid;
      $Location->LocationTypeID = '1';
      $Location->Company = isset($req->account_nameA)? $req->account_nameA : NULL;
      $Location->Street = isset($req->billing_addressA)? $req->billing_addressA : NULL;
      $Location->ZipCode = isset($req->ZipCodeA)? $req->ZipCodeA : NULL;
      $Location->City = isset($req->cityA)? $req->cityA : NULL;
      $Location->CCodePhone = isset($req->phone_country_prefixA)? $req->phone_country_prefixA : NULL;
      $Location->ACodePhone = isset($req->phone_city_prefixA)? $req->phone_city_prefixA : NULL;
      $Location->Phone = isset($req->phoneA)? $req->phoneA : NULL;
      $Location->CCodeFax = isset($req->fax_country_prefixA)? $req->fax_country_prefixA : NULL;
      $Location->ACodeFax = isset($req->fax_city_prefixA)? $req->fax_city_prefixA : NULL;
      $Location->Fax = isset($req->faxA)? $req->faxA : NULL;
      $Location->CountryID = isset($req->countryA)? $req->countryA : NULL;
      $Location->Url1 = isset($req->websiteA)? $req->websiteA : NULL;
      $Location->Created = $DateTime;
      $Location->UserBy = $ISID;
      // $Location->MICE_AGENT = $req->MICE_AGENT;
      $Location->BusinessUnitID = isset($req->business_unit_teamA)? $req->business_unit_teamA : NULL;
      // $Location->SFDateCreated = isset($req->SFCreatedTimeAcc)? $req->SFCreatedTimeAcc : NULL;
      $Location->SFUserCreated = isset($req->SFUserCreatedAcc)? $req->SFUserCreatedAcc : NULL;
      $Location->SFAccountId = isset($req->SFAccountId)? $req->SFAccountId : NULL;
      $Location->TOMarketId = $req->TOMarket;
      $Location->TO_TA = $Tclient;
      $Location->SFDateCreated = $dateSF;

      $Location->save();
      $LocationID = $Location->LocationID;

      $Country = new Country;
          $CountryArray = $Country->SelectCountrytocontact($req->countryA);
          foreach ($CountryArray as $dataCountry) {
            $NameCountry = $dataCountry->Country;
          }



      $tbContacts = new tbContacts();
      $tbContacts->LocationID = $LocationID;
      // $tbContacts->FormerClient = $req->;
      $tbContacts->ContactsId = $ContactsId;
      $tbContacts->KindOfContact = "cop";
      $tbContacts->CompanyDesc = isset($req->account_nameA)? $req->account_nameA : NULL;
      $tbContacts->Street = isset($req->billing_addressA)? $req->billing_addressA : NULL;
      $tbContacts->City = isset($req->cityA)? $req->cityA : NULL;
      $tbContacts->ZipCode = isset($req->ZipCodeA)? $req->ZipCodeA : NULL;
      $tbContacts->Country = $NameCountry;
      $tbContacts->ISSales = $EmployeeID;
      $tbContacts->ISOffice = $SalesOfficeid;
      $tbContacts->BusinessUnitID = isset($req->business_unit_teamA)? $req->business_unit_teamA : NULL;
      $tbContacts->SalePhoto = $EmployeeID;
      $tbContacts->SaleDescription = $req->account_ownerA;

      // $tbContacts->ZipCode = $req->;
      // $tbContacts->updated_at = $req->$DateTime;
      $tbContacts->save();
      // $tbContacts-> = $req->;
      if ($req->account_sourceA != '') {
        $LeadSourceType = new LeadSourceType;
          $LeadSourceTypeArray = $LeadSourceType->SelectSourceType($req->account_sourceA);
          foreach ($LeadSourceTypeArray as $rowLeadSource) {
            $LeadSourcedata = $rowLeadSource->SourceId;
          }
        if (count($LeadSourceTypeArray) > 0) {
          $LeadSourceInformation = new LeadSourceInformation;
          $LeadSourceInformation->LocationId = $LocationID;
          $LeadSourceInformation->SourceId = $LeadSourcedata;
          $LeadSourceInformation->save();
        }
      }

      $SalesforceAccount = new SalesforceAccount();
      $SalesforceAccountArray =  $SalesforceAccount->UpdateLocationID($req->SFAccountId,$LocationID);

     echo "Create data location success.";
   }else {
     echo "Name Owner does not match!! Please check in the Salesforce system.";
   }
  }

  public function MergeCrm(Request $req)
  {
    $DateTime = date('Y-m-d H:i:s');
    $session_id = $req->session_id;
    $Auth = new tbAuth();
    $AuthArray = $Auth->SelectAuth($session_id);
    foreach ($AuthArray as $AuthRow) {
      $ServerId = $AuthRow->ServerId;
      $BCode = $AuthRow->BCode;
      $ISID = $AuthRow->ISID;
    }
    $locationid = $req->locationid;
    // $Location = Location::find($locationid);
    // // dd($Location);
    // $Location->CompanyID = $req->GCountry;
    // $Location->LocationTypeID = $req->Tcontact;
    // $Location->Company = isset($req->account_descriptionA)? $req->account_descriptionA : NULL;
    // $Location->Street = isset($req->billing_addressA)? $req->billing_addressA : NULL;
    // $Location->City = isset($req->cityA)? $req->cityA : NULL;
    // $Location->CCodePhone = isset($req->phone_country_prefixA)? $req->phone_country_prefixA : NULL;
    // $Location->ACodePhone = isset($req->phone_city_prefixA)? $req->phone_city_prefixA : NULL;
    // $Location->Phone = isset($req->phoneA)? $req->phoneA : NULL;
    // $Location->CCodeFax = isset($req->fax_country_prefixA)? $req->fax_country_prefixA : NULL;
    // $Location->ACodeFax = isset($req->fax_city_prefixA)? $req->fax_city_prefixA : NULL;
    // $Location->Fax = isset($req->faxA)? $req->faxA : NULL;
    // $Location->CountryID = isset($req->countryA)? $req->countryA : NULL;
    // $Location->Url1 = isset($req->websiteA)? $req->websiteA : NULL;
    // $Location->Created = $DateTime;
    // $Location->UserBy = $ISID;
    // $Location->MICE_AGENT = $req->MICE_AGENT;
    // $Location->BusinessUnitID = isset($req->business_unit_teamA)? $req->business_unit_teamA : NULL;
    // $Location->SFDateCreated = isset($req->SFCreatedTimeAcc)? $req->SFCreatedTimeAcc : NULL;
    // $Location->SFUserCreated = isset($req->SFUserCreatedAcc)? $req->SFUserCreatedAcc : NULL;
    // $Location->SFAccountId = isset($req->SFAccountId)? $req->SFAccountId : NULL;
    // $Location->TOMarketId = $req->TOMarket;
    // $Location->TO_TA = $req->Tclient;
    //
    // $Location->save();

    $Employee = new Employee();
    $Employee->LocationID = $req->locationid;
    $Employee->FirstName = $req->first_nameC;
    $Employee->LastName = $req->last_nameC;
    $Employee->NickName = $req->middle_nameC;
    $Employee->Title = $req->title_nameC;
    $Employee->Birthday = isset($req->birthdateC) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $req->birthdateC))) : null;
    $Employee->ACodePhone = $req->phone_country_prefixC;
    $Employee->CCodePhone = $req->phone_city_prefixC;
    $Employee->Phone = $req->phoneC;
    $Employee->ACodeAltPhone = $req->home_country_prefixC;
    $Employee->CCodeAltPhone = $req->home_city_prefixC;
    $Employee->AltPhone = $req->homeC;
    $Employee->ACodeFax = $req->fax_country_prefixC;
    $Employee->CCodeFax = $req->fax_city_prefixC;
    $Employee->Fax = $req->faxC;
    $Employee->ACodeMobile = $req->mobile_country_prefixC;
    $Employee->CCodeMobile = $req->mobile_city_prefixC;
    $Employee->Mobile = $req->mobileC;
    $Employee->leadsource = $req->leadsourceC;
    $Employee->Email = $req->emailC;
    $Employee->SFDateCreated = $req->SFCreatedTimeCon;
    $Employee->SFUserCreated = $req->SFUserCreatedCon;
    $Employee->SFContactId = $req->SFContactId;
    $Employee->Created = $DateTime;
    $Employee->UserBy = $ISID;
    $Employee->POS = $req->PositionC;
    $Employee->Department = $req->DepartmentC;

    $Employee->save();


    $Employee = $Employee->EmployeeID;
    // dd($Employee);

    $SalesforceAccount = new SalesforceAccount();
    $SalesforceAccountArray =  $SalesforceAccount->UpdateEmpID($req->SFContactId,$Employee);

    // $SalesforceAccountArray = DB::connection('sqlsrvsf')->table('SalesforceAccount')->where('account_id', '=', $req->SFContactId)->first();

     // dd($SalesforceAccountArray);
    // $SalesforceAccountArray->employee_id = $Employee;
    // $SalesforceAccountArray->save();

    echo "Merge data emplolyee success.";
  }

  public function UpdateSfToCrm(Request $req)
  {
    $DateTime = date('Y-m-d H:i:s');
    $session_id = $req->session_id;
    $Auth = new tbAuth();
    $AuthArray = $Auth->SelectAuth($session_id);
    foreach ($AuthArray as $AuthRow) {
      $ServerId = $AuthRow->ServerId;
      $BCode = $AuthRow->BCode;
      $ISID = $AuthRow->ISID;
    }

    // dd($req->employeeid);
    // $locationid = $req->locationid;
    /*$Location = Location::find($locationid);
    $Location->CompanyID = $req->GCountry;
    $Location->LocationTypeID = $req->Tcontact;
    $Location->Company = isset($req->account_descriptionA)? $req->account_descriptionA : NULL;
    $Location->Street = isset($req->billing_addressA)? $req->billing_addressA : NULL;
    $Location->City = isset($req->cityA)? $req->cityA : NULL;
    $Location->CCodePhone = isset($req->phone_country_prefixA)? $req->phone_country_prefixA : NULL;
    $Location->ACodePhone = isset($req->phone_city_prefixA)? $req->phone_city_prefixA : NULL;
    $Location->Phone = isset($req->phoneA)? $req->phoneA : NULL;
    $Location->CCodeFax = isset($req->fax_country_prefixA)? $req->fax_country_prefixA : NULL;
    $Location->ACodeFax = isset($req->fax_city_prefixA)? $req->fax_city_prefixA : NULL;
    $Location->Fax = isset($req->faxA)? $req->faxA : NULL;
    $Location->CountryID = isset($req->countryA)? $req->countryA : NULL;
    $Location->Url1 = isset($req->websiteA)? $req->websiteA : NULL;
    $Location->Created = $DateTime;
    $Location->UserBy = $ISID;
    // $Location->MICE_AGENT = $req->MICE_AGENT;
    $Location->BusinessUnitID = isset($req->business_unit_teamA)? $req->business_unit_teamA : NULL;
    $Location->SFDateCreated = isset($req->SFCreatedTimeAcc)? $req->SFCreatedTimeAcc : NULL;
    $Location->SFUserCreated = isset($req->SFUserCreatedAcc)? $req->SFUserCreatedAcc : NULL;
    $Location->SFAccountId = isset($req->SFAccountId)? $req->SFAccountId : NULL;
    $Location->TOMarketId = $req->TOMarket;
    $Location->TO_TA = $req->Tclient;
    $Location->save();*/

    $employeeid = $req->employeeid;
    $Employee = Employee::find($employeeid);
    $Employee->LocationID = $req->locationid;
    $Employee->FirstName = $req->first_nameC;
    $Employee->LastName = $req->last_nameC;
    $Employee->NickName = $req->middle_nameC;
    $Employee->Title = $req->title_nameC;
    $Employee->Birthday = isset($req->birthdateC) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $req->birthdateC))) : null;
    $Employee->ACodePhone = $req->phone_country_prefixC;
    $Employee->CCodePhone = $req->phone_city_prefixC;
    $Employee->Phone = $req->phoneC;
    $Employee->ACodeAltPhone = $req->home_country_prefixC;
    $Employee->CCodeAltPhone = $req->home_city_prefixC;
    $Employee->AltPhone = $req->homeC;
    $Employee->ACodeFax = $req->fax_country_prefixC;
    $Employee->CCodeFax = $req->fax_city_prefixC;
    $Employee->Fax = $req->faxC;
    $Employee->ACodeMobile = $req->mobile_country_prefixC;
    $Employee->CCodeMobile = $req->mobile_city_prefixC;
    $Employee->Mobile = $req->mobileC;
    $Employee->leadsource = $req->leadsourceC;
    $Employee->Email = $req->emailC;
    $Employee->SFDateCreated = $req->SFUpdateTimeCon;
    $Employee->SFUserCreated = $req->SFUserUpdateCon;
    $Employee->SFContactId = $req->SFContactId;
    $Employee->Updated = $DateTime;
    $Employee->UserBy = $ISID;
    $Employee->Pos = $req->PositionC;
    $Employee->Department = $req->DepartmentC;
    $Employee->save();

    $SalesforceAccount = new SalesforceAccount();
    $SalesforceAccountArray =  $SalesforceAccount->UpdateSync($req->SFContactId);


    // dd($Location,$Employee);

    echo "Update data success.";
  }

  public function getNewContactId($ServerId,$BCode){
    $newContactsID = "";
    $getNewContactsId = DB::select(DB::raw("
          DECLARE @NEWContactsID varchar (16)
          EXEC dbo.uspGenAutoKey 'tbContacts',
              'ContactsId',
              '".$ServerId."',
              0,
              0,
              '".$BCode."',
              8,
              @NEWContactsID output
          SELECT @NEWContactsID as NEWContactsID"));
    if($getNewContactsId){
      $newContactsID = $getNewContactsId[0]->NEWContactsID;
    }
    return $newContactsID;
  }



}
