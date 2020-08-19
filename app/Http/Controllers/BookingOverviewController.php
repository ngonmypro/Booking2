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

class BookingOverviewController extends controller{
  public $arr_theme = array(
    'black-tie',
    'blitzer',
    'cupertino',
    'dark-hive',
		'dot-luv',
    'eggplant',
    'excite-bike',
    'flick',
    'hot-sneaks',
    'humanity',
		'le-frog',
    'mint-choc',
    'overcast',
    'pepper-grinder',
    'redmond',
		'smoothness',
    'south-street',
    'start',
    'sunny',
    'swanky-purse',
		'trontastic',
    'ui-darkness',
    'ui-lightness',
    'vader'
  );

  public function getBookingOverview($tourid,$ssid){
    $theme = $this->arr_theme;
    $tour = $this->getTour($tourid);
    if(count($tour)>0){
      foreach ($tour as $value) {
        $ccode = $value->Ccode;
      }
    }
    $change = $this->getChangeBox($tourid);
    $tour_country = $this->changeCcodetoCountry($ccode);
    $pax = $this->getPaxList($tourid);
    $visa = $this->getVisa($tourid);
    $flight = $this->getFlightBooking($tourid);
    $hotel = $this->getHotelBooking($tourid);
    $restaurant = $this->getRestaurantBooking($tourid);
    $boat = $this->getBoatTrainBooking($tourid);
    $package = $this->getPackageBooking($tourid);
    $guide = $this->getGuideBooking($tourid);
    $vehicle = $this->getVehicleBooking($tourid);
    $other = $this->getOtherBooking($tourid);
    $entrancefee = $this->getEntranceFee($tourid);
    //dd($change);
    return view('overview.index')->with(compact('ssid','tourid','change','tour_country','theme','tour','pax','visa','flight','hotel','restaurant','boat','package','guide','vehicle','other','entrancefee'));
    //return view('overview.index');
  }

  public function getTour($tourid){
    $sql = "
        SELECT  TourId ,
            ToRef ,
            tbtours.Ccode ,
            Clients ,
            NoPax ,
            TourStartDate ,
            TourEndDate ,
            DATEDIFF(DAY ,TourStartDate,TourEndDate) AS days,
            Services ,
            dbo.tbTours.Remarks ,
            BookingDate ,
            CompanyDesc ,
            Country,
            UName,
            TourDescription,
            tbContacts.shortcut,
            dbo.tbISUsers.Email,
            dbo.Date_Format(dbo.tbTours.CxlDate,'dd-mmm-yyyy') AS CxlDate,
            Cancelled ,
            dbo.Location.SpecialTreat ,
            dbo.Location.LocationId ,
            dbo.tbTours.cdate ,
            dbo.tbTours.THSupplierCode,
            Employee.EmployeeID,
            CASE
             WHEN Employee.EmployeeID IS NOT NULL THEN Employee.FirstName+' '+Employee.LastName
             ELSE ''
            END as TravelDesignerName,
            (
              SELECT COUNT(*)
              FROM LocationTOLogo
              WHERE LocationTOLogo.LocationId = dbo.Location.LocationId
            ) cnt_logo,
            (
              SELECT COUNT(*)
              FROM LocationTODocument
              WHERE LocationTODocument.LocationId = dbo.Location.LocationId
            ) cnt_doc
        FROM    tbtours
            INNER JOIN dbo.tbContacts ON dbo.tbTours.ContactsId = dbo.tbContacts.ContactsId
            INNER JOIN dbo.tbISUsers ON dbo.tbISUsers.UsersId = dbo.tbTours.UsersId
            LEFT JOIN dbo.Location ON dbo.Location.LocationId = dbo.tbContacts.LocationId
            LEFT JOIN [IS] ON tbtours.TravelDesignerManager = [IS].ISID
            LEFT JOIN Employee ON [IS].EmployeeID = Employee.EmployeeID
        WHERE   TourId = '$tourid'
            AND tbContacts.IsMainContact = 1
        ";
      $tour = DB::select($sql);
      if(empty($tour)){
        $tour = array();
      }
      return $tour;
  }

  public function getChangeBox($tourid){
    $sql = "
      SELECT  ChangesDate ,Changes
      FROM    dbo.tbChanges
      WHERE   TourId = '$tourid'
      ";
    $change = DB::select($sql);
    if(empty($change)){
      $change = array();
    }
    return $change;
  }

  public function getPaxList($tourid){
    $sql = "
    SELECT
       	LastName,
				FirstName,
				Mr_Mrs,
				PassportNo,
				Nationality,
				BirthDate,
				SGL_DBL,
				cdate as EnterDate,
				Remark
			FROM    tbPaxVisa
			WHERE  TourId = '$tourid'
			ORDER BY PaxNo ,LastName
    ";

    $pax = DB::select($sql);
    if(empty($pax)){
      $pax = array();
    }
    return $pax;
  }

  public function getVisa($tourid){
    $sql = "SELECT DISTINCT
          tbVisa.[VisaId],
          tbVisa.[VisaWhere],
          tbVisa.[KindOfVisa],
          IsMultipleEntry,
          tbVisa.[EntryPort],
    	Urgency,
          dbo.Date_Format(tbVisa.EnterWhen, 'dd-mmm-yy') AS EnterWhen ,
          dbo.Date_Format(tbVisa.[SubmittedOn], 'dd-mmm-yy') AS [SubmittedOn] ,
          tbPaxVisa.Appcode,
          tbVisa.Status
    FROM    tbPaxVisa
          INNER JOIN tbVisa ON tbPaxVisa.[TourId] = tbVisa.[TourId]
    WHERE   tbVisa.[TourId] = '$tourid'
          AND LEN(tbVisa.[VisaWhere]) > 0 ";
    $visa = DB::select($sql);
    if(empty($pax)){
      $visa = array();
    }
    return $visa;
  }

  public function getFlightBooking($tourid){
    $sql = "SELECT  tbFlightBookings.FBId ,
  				tbFlightBookings.TourId ,
  				tbFlightBookings.Airline ,
  				tbFlightBookings.FlightNo ,
  				tbFlightBookings.FlightFrom ,
  				tbFlightBookings.FromCode ,
  				tbFlightBookings.FlightTo ,
  				tbFlightBookings.toCode ,
  				tbFlightBookings.dep ,
  				tbFlightBookings.arr ,
  				dbo.Date_Format(tbFlightBookings.FlightDate, 'dd-mmm-yyyy') AS Fbdate ,
  				dbo.Date_Format(tbFlightBookings.bookingdate, 'dd-mmm-yyyy') AS bookingdate ,
  				tbFlightBookings.remark ,
  				tbFlightBookings.Status ,
  				tbFlightBookings.nf ,
  				tbFlightBookings.class ,
  				tbFlightBookings.cdate ,
  				tbFlightBookings.udate ,
  				tbFlightBookings.ddate ,
  				tbFlightBookings.Price ,
  				tbFlightBookings.TPId ,
  				tbFlightBookings.OldId ,
  				tbFlightBookings.Pax ,
  				tbFlightBookings.QuotationDataSubId ,
  				CAST([tbFlightBookings].msrepl_tran_version AS VARCHAR(300)) AS msrepl_tran_version ,
  				tbTours.TourId AS Expr1 ,
  				tbTours.ContactsId ,
  				tbTours.ToRef ,
  				tbTours.Ccode ,
  				tbTours.Clients ,
  				tbTours.NoPax ,
  				tbTours.Routing ,
  				tbTours.TourStartDate ,
  				tbTours.TourEndDate ,
  				tbTours.Services ,
  				tbTours.WeQuote ,
  				tbTours.WeBuy ,
  				tbTours.WeSell ,
  				tbTours.HotelRemarks ,
  				tbTours.Remarks ,
  				tbTours.TourDescription ,
  				tbTours.BookingDate AS Expr2 ,
  				tbTours.Cancelled ,
  				tbTours.TourSeries ,
  				tbTours.TourSeriesId ,
  				tbTours.CtTime ,
  				tbTours.UsersId ,
  				tbTours.cdate AS Expr3 ,
  				tbTours.Gen_remarks ,
  				tbTours.udate AS Expr4 ,
  				tbTours.ddate AS Expr5 ,
  				tbTours.OldId AS Expr6 ,
  				tbTours.IsFIT ,
  				tbTours.IsGIT ,
  				tbTours.IsConfirm ,
  				tbTours.IsOnline ,
  				CAST([tbTours].msrepl_tran_version AS VARCHAR(300)) AS msrepl_tran_version ,
  				tbTours.finalpax ,
  				CAST([tbTours].msrepl_tran_version AS VARCHAR(300)) AS Expr7 ,
  				tbFlightBookings.IsIntlByClient AS intl ,
  				--CompanyName ,
  				CASE
  					WHEN dbo.ContactCompany.CompanyId IS NULL THEN (SELECT cc.CompanyName FROM ContactCompany cc WHERE cc.CompanyId = tbFlightBookings.CompanyId)
  					ELSE dbo.ContactCompany.CompanyName
  				END as CompanyName,
  				ServiceName ,
  				IsIntlByClient ,
          ContactCompany.CountryPrefix,
          ContactCompany.CityPrefix,
          ContactCompany.Phone
  		FROM    tbFlightBookings
  				INNER JOIN tbTours ON tbFlightBookings.TourId = tbTours.TourId
  				LEFT JOIN dbo.BookingRateCost ON dbo.tbFlightBookings.FBIdUniqueId = dbo.BookingRateCost.ReferanceId
  				AND CostTypeId = (
             SELECT MAX(CostTypeId)
						 FROM dbo.BookingRateCost AS c
						 WHERE c.ReferanceId = dbo.tbFlightBookings.FBIdUniqueId
						 AND CostTypeId IN (3, 4 )
  				)
  				LEFT JOIN dbo.SupplierServiceRate ON dbo.BookingRateCost.ServiceRateId = dbo.SupplierServiceRate.ServiceRateId
  				LEFT JOIN dbo.SupplierServiceContract ON dbo.SupplierServiceContract.ServiceContractId = dbo.SupplierServiceRate.ServiceContractId
  				LEFT JOIN dbo.ContactCompany ON dbo.SupplierServiceContract.CompanyId = dbo.ContactCompany.CompanyId
  				LEFT JOIN dbo.SupplierServiceMaster ON SupplierServiceContract.ServiceMasterId = SupplierServiceMaster.ServiceMasterId
  		WHERE   ( tbTours.TourId = '$tourid' )
              AND (
              	( NOT ( tbFlightBookings.Airline LIKE '%Boat%' )
                )
                OR ( NOT ( tbFlightBookings.Airline LIKE '%Balloon%' )
              	 )
                OR ( NOT ( tbFlightBookings.Airline LIKE '%Train%' )
              	 )
                OR ( NOT ( tbFlightBookings.Airline LIKE '%Bus%' )
              	 )
               OR ( tbFlightBookings.Airline IS NULL )

              )
  				    AND ( tbFlightBookings.nf = 0 OR tbFlightBookings.nf IS NULL )
  				    AND (SupplierServiceMaster.flightforguide IS NULL OR SupplierServiceMaster.flightforguide = 0)
  		ORDER BY tbFlightBookings.FlightDate ,
  				tbFlightBookings.dep ,
  				tbFlightBookings.arr " ;

      $flight = DB::select($sql);
      if(empty($flight)){
        $flight = array();
      }
      return $flight;
  }

  public function getHotelBooking($tourid){
    $sql = "
    SELECT  tbContacts.ContactsId ,
          dbo.Date_Format(tbHotelBookings.BookDate, 'dd-mmm-yyyy') AS hotel_date ,
          tbHotelBookings.HotelId ,
          tbContacts.CompanyDesc ,
          tbContacts.Shortcut ,
          tbHotelBookings.TourId ,
          tbHotelBookings.Sgl ,
          tbHotelBookings.Dbl ,
          tbHotelBookings.Tpl ,
          tbHotelBookings.Twn ,
  		    tbHotelBookings.InclABF,
  		    tbHotelBookings.HideAlternativeRemark,
          tbHotelBookings.Status ,
          tbHotelBookings.StatusOP ,
          CAST(tbHotelBookings.Remark AS VARCHAR(MAX)) AS bk_remark ,
          tbHotelBookings.HBId ,
          dbo.Date_Format(tbHotelBookings.CheckIn, 'dd-mmm-yyyy') AS CheckIn ,
          dbo.Date_Format(tbHotelBookings.CheckOut, 'dd-mmm-yyyy') AS CheckOut ,
          tbHotels.Hotel ,
  		    tbHotels.city AS hcity ,
          tbHotelBookings.RoomId ,
          tbHotelBookings.RoomCategory AS RoomCategory,
    		  tbContacts.msrepl_tran_version,
    		  HideAlternativeRemark,
          tbHotels.ContactName,
          tbHotels.Phone
    FROM    tbHotelBookings
            INNER JOIN tbHotels ON tbHotelBookings.HotelId = tbHotels.HotelId
            INNER JOIN tbTours ON tbHotelBookings.TourId = tbTours.TourId
            INNER JOIN tbContacts ON tbTours.ContactsId = tbContacts.ContactsId
    WHERE   ( tbHotelBookings.TourId = '$tourid')
            AND tbContacts.isMainContact = 1
    ORDER BY tbHotelBookings.TourId ,
            tbHotelBookings.CheckIn
    ";
    $hotel = DB::select($sql);
    if(empty($hotel)){
      $hotel = array();
    }
    return $hotel;
  }

  public function getRestaurantBooking($tourid){
    $sql = "
    SELECT  tbRestaurants.Restaurant ,
        tbRestaurants.City ,
        tbRestaurantBookings.LunchDinner ,
        tbRestaurantBookings.MenuId ,
		    tbMenu.Menu,
        dbo.Date_Format(tbRestaurantBookings.OnDay, 'dd-mmm-yyyy') AS onday1,
        tbRestaurantBookings.Remark ,
        tbRestaurantBookings.Status ,
        tbRestaurantBookings.RestaurantId AS ResID ,
        tbRestaurants.Country ,
        tbRestaurants.Contact ,
        tbRestaurants.CityPrefix ,
        tbRestaurants.Fax ,
        tbRestaurants.Phone ,
        tbRestaurantBookings.RBId ,
        tbRestaurantBookings.Pax ,
        tbRestaurantBookings.AtTime ,
        dbo.Date_Format(tbRestaurantBookings.BookingDate, 'dd-mmm-yyyy') AS Rbdate,
				tbRestaurantBookings.MenuOrders AS MenuDescriptionRemark
    FROM    tbContacts
        INNER JOIN tbTours ON tbContacts.ContactsId = tbTours.ContactsId
        INNER JOIN tbRestaurantBookings ON tbTours.TourId = tbRestaurantBookings.TourId
        INNER JOIN tbRestaurants ON tbRestaurantBookings.RestaurantId = tbRestaurants.RestaurantId
    		LEFT JOIN tbMenu ON tbRestaurantBookings.MenuId = tbMenu.MenuId
    WHERE   tbRestaurantBookings.TourId = '$tourid'
            AND tbContacts.isMainContact = 1
    ORDER BY tbRestaurantBookings.OnDay ,
            tbRestaurantBookings.LunchDinner DESC
		";
    $restaurant = DB::select($sql);
    if(empty($restaurant)){
      $restaurant = array();
    }
    return $restaurant;
  }

  public function getBoatTrainBooking($tourid){
    $sql ="
      SELECT  dbo.tbFlightBookings.TourId ,
          		dbo.tbFlightBookings.ConfirmationsId ,
          		Airline ,
          		FlightNo ,
          		FlightFrom ,
          		FlightTo ,
          		dep ,
          		arr ,
          		FlightDate ,
          		dbo.tbTours.bookingdate ,
          		dbo.tbFlightBookings.remark ,
          		dbo.tbFlightBookings.Status ,
          		class,
  				    CompanyName,
          		ServiceName,
          		ServiceCategory_Desc as Airline,
              ContactCompany.CountryPrefix,
              ContactCompany.CityPrefix,
              ContactCompany.Phone
  			FROM    tbFlightBookings
          	INNER JOIN tbTours ON tbFlightBookings.TourId = tbTours.TourId
          	INNER JOIN dbo.BookingRateCost ON dbo.tbFlightBookings.FBIdUniqueId = dbo.BookingRateCost.ReferanceId
          	LEFT JOIN dbo.BookingRateSpecialChargeCost ON dbo.BookingRateCost.BookingRateCostId = dbo.BookingRateSpecialChargeCost.BookingRateCostId
          	LEFT JOIN dbo.SupplierServiceRate ON dbo.SupplierServiceRate.ServiceRateId = dbo.BookingRateCost.ServiceRateId
  			INNER JOIN dbo.SupplierServiceContract ON dbo.SupplierServiceContract.ServiceContractId = dbo.SupplierServiceRate.ServiceContractId
          	INNER JOIN dbo.ServiceUnitType ON dbo.ServiceUnitType.Id = dbo.SupplierServiceRate.ServiceUnitTypeId
          	INNER JOIN dbo.ServiceCategory ON dbo.ServiceCategory.Id = dbo.ServiceUnitType.ServiceCategoryId
          	INNER JOIN dbo.ContactCompany ON dbo.SupplierServiceContract.CompanyId = dbo.ContactCompany.CompanyId
  			WHERE   ( tbTours.TourId = '$tourid' )
  				AND (( [tbFlightBookings].Status  IS NULL ) OR ( [tbFlightBookings].Status NOT LIKE 'cancelled' ))
          		AND (
          			(	tbFlightBookings.Airline LIKE '%Boat%' )
                	OR ( tbFlightBookings.Airline LIKE '%Balloon%' )
                	OR ( tbFlightBookings.Airline LIKE '%Train%' )
                	OR ( tbFlightBookings.nf = 1 )
             	)
         		AND CostTypeId = ( SELECT   MAX(CostTypeId)
              FROM dbo.BookingRateCost AS c
              WHERE c.ReferanceId = dbo.tbFlightBookings.FBIdUniqueId
                  AND CostTypeId IN ( 3, 4 )
              )
  			ORDER BY tbFlightBookings.FlightDate ,
          		tbFlightBookings.dep ,
          		tbFlightBookings.arr
  	";
    $boat = DB::select($sql);
    if(empty($boat)){
      $boat = array();
    }
    return $boat;
  }

  public function getPackageBooking($tourid){
    ## PACKAGE AND ACTIVITIES

  	$sql = "
      SELECT  dbo.MisceOtherBooking.MOBId ,
                dbo.MisceOtherBooking.Tourid ,
                dbo.MisceOtherBooking.OnDay ,
                dbo.MisceOtherBooking.BookDate ,
                dbo.MisceOtherBooking.Price ,
                dbo.MisceOtherBooking.Status ,
                dbo.MisceOtherBooking.Remark ,
                ContactCompany.CompanyId ,
                ContactCompany.CompanyName ,
                CountryDesc ,
                City ,
                dbo.SupplierServiceRate.ServiceRateId ,
                dbo.MisceOtherBooking.ServiceContractId ,
                ServiceName ,
                dbo.MisceOtherBooking.Price ,
                dbo.MisceOtherBooking.Status ,
                dbo.MisceOtherBooking.Remark ,
                ServiceCategory_Desc

        		     -- DevMark
                ,dbo.SupplierServiceContract.PackageCode
                ,ContactCompany.CountryPrefix
                ,ContactCompany.CityPrefix
                ,ContactCompany.Phone
        FROM    dbo.MisceOtherBooking
                LEFT JOIN ContactCompany ON dbo.MisceOtherBooking.CompanyId = ContactCompany.CompanyId
                LEFT JOIN dbo.SupplierServiceContract ON dbo.SupplierServiceContract.ServiceContractId = dbo.MisceOtherBooking.ServiceContractId
                LEFT JOIN dbo.BookingRateCost ON dbo.MisceOtherBooking.MOBId = dbo.BookingRateCost.ReferanceId
                INNER JOIN dbo.SupplierServiceRate ON dbo.BookingRateCost.ServiceRateId = dbo.SupplierServiceRate.ServiceRateId
                LEFT JOIN ServiceUnitType ON dbo.SupplierServiceRate.ServiceUnitTypeId = ServiceUnitType.Id
                LEFT JOIN dbo.ServiceCategory ON dbo.ServiceCategory.Id = dbo.ServiceUnitType.ServiceCategoryId
                LEFT JOIN dbo.tbCountry ON dbo.tbCountry.CountryId = dbo.ContactCompany.CountryId
                LEFT JOIN dbo.tbCity ON dbo.ContactCompany.CityId = dbo.tbCity.CityId
        WHERE   ( dbo.MisceOtherBooking.Tourid = '$tourid' )
                AND CostTypeId = ( SELECT   MAX(CostTypeId)
                                   FROM     dbo.BookingRateCost AS c
                                   WHERE    c.ReferanceId = dbo.MisceOtherBooking.MOBId
                                            AND CostTypeId IN ( 3, 4 )
                                 )
                AND ServiceCategory_Desc IN ( 'Package', 'Activity' )
        ORDER BY onday ,
                ServiceName
  		";

      $package = DB::select($sql);
      if(empty($package)){
        $package = array();
      }
      return $package;
  }

  public function getGuideBooking($tourid){
    $sql = "SELECT  GBId ,
 						tourId ,
 						dbo.Date_Format(Startdate, 'dd-mmm-yyyy') AS OnDay ,
 						ISNULL(Remark, '') AS Remark ,
 						dbo.tbGuideBookings.GPId ,
 						GuidePriceDesc ,
 						ISNULL(dbo.tbGuideBookings.GuideId, '') AS GuideId ,
 						dbo.tbGuideBookings.Price ,
 						ISNULL(FirstName, '') + ' ' + ISNULL(LastName, '') AS FullName ,
 						NewGuideId ,
 						TitleMasterData ,
 						MobilePhone ,
 						dbo.tbCity.City
 				FROM    tbGuideBookings
 						LEFT JOIN dbo.tbGuidePrice ON dbo.tbGuideBookings.GPId = dbo.tbGuidePrice.GPId
 						LEFT JOIN dbo.Guide ON dbo.tbGuideBookings.NewGuideId = dbo.Guide.GuideId
 						LEFT JOIN dbo.TitleMaster ON TitleId = TitleMasterId
 						LEFT JOIN dbo.tbCity ON dbo.tbCity.CityId = dbo.guide.CityId
 				WHERE   ( TourId = '$tourid' )
 						AND (tbGuideBookings.Status != 'CXL' OR tbGuideBookings.Status  IS NULL)
 				ORDER BY Startdate ";
      $guide = DB::select($sql);
      if(empty($guide)){
        $guide = array();
      }

      $array = array();
      foreach($guide as $value){
        $onDay = $value->OnDay;
        $array[$onDay][] = array("GBId" => $value->GBId
            , "tourId" => $value->tourId
            , "Remark" => $value->Remark
            , "GuidePriceDesc" => $value->GuidePriceDesc
            , "GPId" => $value->GPId
            , "Price" => $value->Price
            , "FullName" => $value->FullName
            , "NewGuideId" => $value->NewGuideId
            , "TitleMasterData" => $value->TitleMasterData
            , "GuideId" => $value->GuideId
            , "MobilePhone" => $value->MobilePhone
            , "From" => $onDay
            , "To" => $onDay
            , "City" => $value->City
          );
      }
      //dd($this->GroupGuideByDay($array));

      return $this->GroupGuideByDay($array);
  }

  public function GroupGuideByDay($array){

  	$newArray = array();

  	foreach($array as $k => $v){
  		$cnt = count($array[$k]);
  		for($i=0;$i<$cnt;$i++){
  			$new = true;
  			for($j=0;$j<count($newArray);$j++){
  				###
  				$to = $newArray[$j]["To"];
  				$val = $newArray[$j]["FullName"];
  				$guide_price_desc = $newArray[$j]["GuidePriceDesc"];
  				###
  				if($to == $k-1 and $val == $array[$k][$i]["FullName"] and trim($array[$k][$i]["FullName"]) != "" and $guide_price_desc == $array[$k][$i]["GuidePriceDesc"]){
  					$newArray[$j]["To"] = $k;
  					$new = false;
  					break;
  				}
  			}
  			##
  			if($new){
  				$newArray[] = array("GBId" => $array[$k][$i]["GBId"]
  								, "tourId" => $array[$k][$i]["tourId"]
  								, "Remark" => $array[$k][$i]["Remark"]
  								, "GuidePriceDesc" => $array[$k][$i]["GuidePriceDesc"]
  								, "GPId" => $array[$k][$i]["GPId"]
  								, "Price" => $array[$k][$i]["Price"]
  								, "FullName" => $array[$k][$i]["FullName"]
  								, "NewGuideId" => $array[$k][$i]["NewGuideId"]
  								, "TitleMasterData" => $array[$k][$i]["TitleMasterData"]
  								, "GuideId" => $array[$k][$i]["GuideId"]
  								, "MobilePhone" => $array[$k][$i]["MobilePhone"]
  								, "From" => $k
  								, "To" => $k
  								, "City" => $array[$k][$i]["City"]
  							);
  			}
  			##
  		}
  	}

  	return $newArray;
  }

  public function getVehicleBooking($tourid){
    $sql = "SELECT  VehicleBooking.VBId ,
   			VehicleBooking.Tourid ,
   			VehicleBooking.OnDay ,
   			VehicleBooking.BookDate ,
   			VehicleBooking.Price ,
   			VehicleBooking.Status ,
   			VehicleBooking.Remark ,
   			VehicleBooking.ParentId ,
   			ContactCompany.CompanyId ,
   			ContactCompany.CompanyName ,
   			CountryDesc ,
   			dbo.SupplierServiceRate.ServiceRateId ,
   			ServiceUnitType.ServiceUnitType ,
   			dbo.VehicleBooking.ServiceContractId,
   			ServiceName ,
   			dbo.VehicleBooking.Price ,
        ContactCompany.CountryPrefix,
        ContactCompany.CityPrefix,
        ContactCompany.Phone
   	FROM    VehicleBooking
   			LEFT JOIN ContactCompany ON VehicleBooking.CompanyId = ContactCompany.CompanyId
   			LEFT JOIN dbo.SupplierServiceContract ON dbo.SupplierServiceContract.ServiceContractId = dbo.VehicleBooking.ServiceContractId
   			LEFT JOIN dbo.BookingRateCost ON dbo.VehicleBooking.VBId = dbo.BookingRateCost.ReferanceId
           	LEFT JOIN dbo.SupplierServiceRate ON dbo.BookingRateCost.ServiceRateId = dbo.SupplierServiceRate.ServiceRateId
   			LEFT JOIN ServiceUnitType ON dbo.SupplierServiceRate.ServiceUnitTypeId = ServiceUnitType.Id
   			LEFT JOIN dbo.tbCountry ON dbo.tbCountry.CountryId = dbo.ContactCompany.CountryId
   	WHERE   ( VehicleBooking.Tourid = '$tourid' )  and   VehicleBooking.isShow = 1
   	 AND CostTypeId = ( SELECT   MAX(CostTypeId)
                              FROM     dbo.BookingRateCost AS c
                              WHERE    c.ReferanceId = dbo.VehicleBooking.VBId
                                       AND CostTypeId IN ( 3, 4 )
                            )
   	ORDER BY OnDay,position ";
    $vehicle = DB::select($sql);
    if(empty($vehicle)){
      $vehicle = array();
    }
    return $vehicle;
  }

  public function getOtherBooking($tourid){
    $sql = "SELECT  dbo.MisceOtherBooking.MOBId ,
          dbo.MisceOtherBooking.Tourid ,
          dbo.MisceOtherBooking.OnDay ,
          dbo.MisceOtherBooking.BookDate ,
          dbo.MisceOtherBooking.Price ,
          dbo.MisceOtherBooking.Status ,
          dbo.MisceOtherBooking.Remark ,
          ContactCompany.CompanyId ,
          ContactCompany.CompanyName ,
          CountryDesc ,
          City ,
          dbo.SupplierServiceRate.ServiceRateId ,
          dbo.MisceOtherBooking.ServiceContractId ,
          ServiceName ,
          dbo.MisceOtherBooking.Price ,
          dbo.MisceOtherBooking.Status ,
          dbo.MisceOtherBooking.Remark ,
          ServiceCategory_Desc ,
          ContactCompany.CountryPrefix,
          ContactCompany.CityPrefix,
          ContactCompany.Phone
    FROM    dbo.MisceOtherBooking
            LEFT JOIN ContactCompany ON dbo.MisceOtherBooking.CompanyId = ContactCompany.CompanyId
            LEFT JOIN dbo.SupplierServiceContract ON dbo.SupplierServiceContract.ServiceContractId = dbo.MisceOtherBooking.ServiceContractId
            LEFT JOIN dbo.BookingRateCost ON dbo.MisceOtherBooking.MOBId = dbo.BookingRateCost.ReferanceId
            INNER JOIN dbo.SupplierServiceRate ON dbo.BookingRateCost.ServiceRateId = dbo.SupplierServiceRate.ServiceRateId
            LEFT JOIN ServiceUnitType ON dbo.SupplierServiceRate.ServiceUnitTypeId = ServiceUnitType.Id
            LEFT JOIN dbo.ServiceCategory ON dbo.ServiceCategory.Id = dbo.ServiceUnitType.ServiceCategoryId
            LEFT JOIN dbo.tbCountry ON dbo.tbCountry.CountryId = dbo.ContactCompany.CountryId
            LEFT JOIN dbo.tbCity ON dbo.ContactCompany.CityId = dbo.tbCity.CityId
    WHERE   ( dbo.MisceOtherBooking.Tourid = '$tourid' )
            AND CostTypeId = ( SELECT   MAX(CostTypeId)
                               FROM     dbo.BookingRateCost AS c
                               WHERE    c.ReferanceId = dbo.MisceOtherBooking.MOBId
                                        AND CostTypeId IN ( 3, 4 )
                             )
            AND ServiceCategory_Desc NOT IN ( 'Package', 'Activity' )
    ORDER BY onday ,ServiceName";
    $other = DB::select($sql);
    if(empty($other)){
      $other = array();
    }
    return $other;
  }

  public function getEntranceFee($tourid){
    $sql = "
      SELECT  dbo.EntranceFeeBooking.EBId ,
        dbo.EntranceFeeBooking.Tourid ,
        dbo.EntranceFeeBooking.OnDay ,
        dbo.EntranceFeeBooking.BookDate ,
        dbo.EntranceFeeBooking.Price ,
        dbo.EntranceFeeBooking.Status ,
        dbo.EntranceFeeBooking.Remark ,
        ContactCompany.CompanyId ,
        ContactCompany.CompanyName ,
        dbo.tbCountry.CountryDesc ,
        dbo.tbCity.City ,
        dbo.SupplierServiceRate.ServiceRateId ,
        dbo.EntranceFeeBooking.ServiceContractId ,
        ServiceName,
        dbo.EntranceFeeBooking.Status ,
        dbo.EntranceFeeBooking.Remark ,
        dbo.BookingRateCost.Price ,
        dbo.BookingRateCost.Pax ,
        ContactCompany.CountryPrefix,
        ContactCompany.CityPrefix,
        ContactCompany.Phone
      FROM   dbo.EntranceFeeBooking
            LEFT JOIN ContactCompany ON dbo.EntranceFeeBooking.CompanyId = ContactCompany.CompanyId
            INNER JOIN dbo.BookingRateCost ON dbo.EntranceFeeBooking.EBId = dbo.BookingRateCost.ReferanceId
            LEFT JOIN dbo.BookingRateSpecialChargeCost ON dbo.BookingRateCost.BookingRateCostId = dbo.BookingRateSpecialChargeCost.BookingRateCostId
            LEFT JOIN dbo.SupplierServiceRate ON dbo.SupplierServiceRate.ServiceRateId = dbo.BookingRateCost.ServiceRateId
            LEFT JOIN dbo.SupplierServiceContract ON dbo.SupplierServiceContract.ServiceContractId = dbo.SupplierServiceRate.ServiceContractId
            LEFT JOIN dbo.tbCountry ON dbo.tbCountry.CountryId = dbo.ContactCompany.CountryId
            LEFT JOIN dbo.tbCity ON dbo.tbCity.CityId = dbo.ContactCompany.CityId
      WHERE   ( dbo.EntranceFeeBooking.Tourid = '$tourid' )
              AND CostTypeId =
              (
                SELECT   MAX(CostTypeId)
                FROM     dbo.BookingRateCost AS c
                WHERE    c.ReferanceId = dbo.EntranceFeeBooking.EBId
                        AND CostTypeId IN ( 3, 4 )
              )
      ORDER BY onday,ServiceName
    ";

    $entrancefee = DB::select($sql);
    if(empty($entrancefee)){
      $entrancefee = array();
    }
    return $entrancefee;
  }

  public function changeCcodetoCountry($ccode)
  {
  	$arr = array();
  	for($i=0;$i< strlen($ccode);$i++)
  	{
  		$char = substr($ccode,$i,1);
  		switch (strtoupper($char))
  		{
  			case "C":
  				$arr[] = "Cambodia";
  				break;
  			case "D":
  				$arr[] = "India";
  				break;
  			case "I":
  				$arr[] = "Bali";
  				break;
  			case "L":
  				$arr[] = "Laos";
  				break;
  			case "M":
  				$arr[] = "Myanmar";
  				break;
  			case "O":
  				$arr[] = "Others";
  				break;
  			case "S":
  				$arr[] = "Singapore";
  				break;
  			case "T":
  				$arr[] = "Thailand";
  				break;
  			case "V":
  				$arr[] = "Vietnam";
  				break;
  		}

  	}

  	return $arr ;
  }

}
