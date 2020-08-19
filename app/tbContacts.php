<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class tbContacts extends Model
{
  protected $table = 'tbContacts';
  protected $primaryKey = 'PK_ID';

//Booking
  public function SelectContacts()
  {
    $sql = DB::select("SELECT DISTINCT
						    tbContacts.Shortcut,
						    tbContacts.ContactsId,
						    tbContacts.CompanyDesc
					FROM tbContacts
						 INNER JOIN tbTours ON tbContacts.ContactsId = tbTours.ContactsId
					WHERE LEN(CompanyDesc) > 1
						  AND tbContacts.IsMainContact = 1
					ORDER BY tbContacts.CompanyDesc");
    return $sql;
  }

  public function ReportBooking($ROffice, $Operator, $Country, $City, $Hotel, $RoomCat, $Cancel, $Status, $DOS_Date_NEW, $DOS_DateEnd_NEW, $HBG_Date_NEW, $HBG_DateEnd_NEW)
  {
    $where = "";

    if($Hotel != "") {
      $where .= " and tbHotels.HotelId = '$Hotel' ";
    }

    if($RoomCat !="")
    {
      $tmproomcate = str_replace("[", "%", $RoomCat);
      $where .= " and tbHotelBookings.RoomCategory LIKE '$tmproomcate%'";
    }

    if($Operator != "") {
      $where .= " and tbTours.ContactsId = '$Operator' ";
    }

    if($Country != "") {
      $where .= " and tbHotels.country = '$Country' ";
    }

    if($Status == "X") {
      $where .= " and charindex('CXL',UPPER(tbHotelBookings.Status)) = 0 ";
    }elseif($Status == "W") {
      $where .= " and charindex('WL',UPPER(tbHotelBookings.Status)) >0 ";
    }elseif($Status == "O") {
      $where .= " and UPPER(tbHotelBookings.Status)='OK' ";
    }elseif($Status == "F") {
      $where .= " and charindex('FULL',UPPER(tbHotelBookings.Status)) >0 ";
    }elseif($Status == "C") {
      $where .= " and charindex('CXL',UPPER(tbHotelBookings.Status)) >0 ";
    }elseif($Status == "B") {
      $where .= " and charindex('BOOKED',UPPER(tbHotelBookings.Status)) > 0 ";
    }elseif($Status == "XF") {
      $where .= " and  charindex('CXL',UPPER(tbHotelBookings.Status)) = 0  and  charindex('FULL',UPPER(tbHotelBookings.Status)) =0  ";
    }elseif($Status == "N") {
      $where .= " and tbHotelBookings.Status='' ";
    }

    if($ROffice != "") {
      $where .= " and tbTours.UsersId = '$ROffice' ";
    }

    if($City != "") {
      $where .= " and (tbHotels.city = '$City') ";
    }

    if($Cancel != ""){
      $where.=" and (dbo.tbTours.Cancelled='$Cancel') ";
    }


if(strlen($DOS_Date_NEW) > 5 && strlen($DOS_DateEnd_NEW) == 0) { $DOS_DateEnd_NEW = $DOS_Date_NEW; }
if(strlen($DOS_DateEnd_NEW) > 5 && strlen($DOS_Date_NEW) == 0) { $DOS_Date_NEW = $DOS_DateEnd_NEW; }
if(strlen($DOS_DateEnd_NEW) > 5 && strlen($DOS_Date_NEW) > 5) {
$where .= " and ((tbHotelBookings.CheckIn between cast('$DOS_Date_NEW' as datetime) and cast('$DOS_DateEnd_NEW' as datetime))
  or (tbHotelBookings.CheckOut between cast('$DOS_Date_NEW' as datetime) and cast('$DOS_DateEnd_NEW' as datetime))) ";
}
//dd($where);

if(strlen($HBG_Date_NEW) > 5 && strlen($HBG_DateEnd_NEW) == 0) { $HBG_DateEnd_NEW = $HBG_Date_NEW; }
if(strlen($HBG_DateEnd_NEW) > 5 && strlen($HBG_Date_NEW) == 0) { $HBG_Date_NEW = $HBG_DateEnd_NEW; }
if(strlen($HBG_DateEnd_NEW) > 5 && strlen($HBG_Date_NEW) > 5) {
  $where .= " and ((tbHotelBookings.BookDate) >= cast('$HBG_Date_NEW' as datetime)
  and tbHotelBookings.BookDate <= (cast('$HBG_DateEnd_NEW' as datetime))) ";
}
#######################

  $sql = DB::select("SELECT
      tbContacts.msrepl_tran_version as sguid,
      tbTours.msrepl_tran_version as tguid,
      tbTours.oldid,
      tbTours.ToRef as refer,
      tbHotels.city,
      tbHotelBookings.TourId,
      tbHotelBookings.Remark as bremark,
      tbTours.clients,
      tbTours.NoPax as nopax,
      dbo.Date_Format(tbTours.TourStartDate,'dd-mmm-yy') as sdate,
      dbo.Date_Format(tbTours.TourEndDate,'dd-mmm-yy') as edate,
      dbo.Date_Format(tbTours.BookingDate,'dd-mmm-yy') as bdate,
      tbContacts.CompanyDesc,
      tbISUsers.uname,
      tbHotels.hotel,
      tbHotelBookings.Status,
      tbHotelBookings.Sgl,
      tbHotelBookings.Dbl,
      tbHotelBookings.Tpl,
      tbHotelBookings.twn,
      cast(tbHotelBookings.RoomCategory as varchar(4000)) as rtype,
      dbo.Date_Format(tbHotelBookings.CheckIn,'dd-mmm-yy') as hsdate,
      dbo.Date_Format(tbHotelBookings.CheckOut,'dd-mmm-yy') as htdate,
      dbo.Date_Format(tbHotelBookings.BookDate,'dd-mmm-yy') as hbdate,
      datediff(day, tbHotelBookings.CheckIn, tbHotelBookings.CheckOut) as nights ,
      (CASE
        WHEN tbHotelBookings.CXLFee > 0 THEN ISNULL(tbHotelBookings.CXLFee / ISNULL(c2.HotelCurrencyRate, ISNULL(c.HotelCurrencyRate, 1)), 0)
          ELSE CASE
        WHEN ISNULL(RateAdjustment, 0) > 0 THEN ISNULL(RateAdjustment / ISNULL(c2.HotelCurrencyRate, ISNULL(c.HotelCurrencyRate, 1)), 0)
          ELSE CASE
        WHEN tbHotelBookings.Status <> 'CXL' THEN (ISNULL(Sgl, 0) * ISNULL(SGLPriceConfirmedUS, 0) +(ISNULL(Dbl, 0) -
            ISNULL(Twn, 0)) * ISNULL(DBLPriceConfirmedUS, 0) + ISNULL(Tpl, 0) * ISNULL(TPLPriceConfirmedUS,
            0) + ISNULL(Twn, 0) * ISNULL(DBLPriceConfirmedUS, 0)) * CASE
        WHEN DATEDIFF(DAY, CheckIn, CheckOut) = 0 THEN 1
          ELSE DATEDIFF(DAY, CheckIn, CheckOut)
          END +(CASE
        WHEN ExtraCost IS NOT NULL THEN CAST (ExtraCost AS MONEY)
          ELSE 0
          END - CASE
        WHEN Reduction IS NOT NULL THEN CAST (Reduction AS MONEY)
          ELSE 0
          END)
          ELSE 0
        END
      END
    END) AS cost
      FROM tbContacts
      RIGHT JOIN tbISUsers
      RIGHT JOIN tbTours
      RIGHT JOIN tbHotels
      LEFT JOIN tbHotelBookings ON tbHotels.HotelId = tbHotelBookings.HotelId
      ON tbTours.TourId = tbHotelBookings.TourId
      ON tbISUsers.UsersId = tbTours.UsersId
      ON tbContacts.ContactsId = tbTours.ContactsId
      INNER JOIN dbo.HotelBookingCostPrice AS p ON p.HBId = tbHotelBookings.HBId
      LEFT JOIN Currency AS c ON c.Currency = p.DBLPriceConfirmedCurrency
      LEFT JOIN Currency AS c2 ON c2.Id = tbHotelBookings.CurrencyId
      WHERE
        tbContacts.IsMainContact = 1
        AND (PrivateBooking = 0 OR PrivateBooking IS NULL)
      $where
      order by
      -- tbISUsers.uname,
      --   tbContacts.CompanyDesc,
        tbHotelBookings.CheckIn,
        tbContacts.CompanyDesc ASC
        -- ,
        -- tbTours.TourEndDate,
        -- tbHotelBookings.CheckIn,
        -- tbHotelBookings.CheckOut

        ");

      return $sql;
  }

//Restaurant
  public function RestaurantSelectCompany(){
    $sql = DB::select("SELECT tbContacts.ContactsId,
      tbContacts.shortcut,
      tbContacts.CompanyDesc,
      tbContacts.country
      FROM tbcontacts
      WHERE (((tbContacts.KindOfContact) In ('top','own','cop')))
      AND tbContacts.IsMainContact = 1
      ORDER BY tbContacts.CompanyDesc
    ");
    return $sql;
  }


  // Flight
    public function SelectTO()
    {
      $sql = DB::select("SELECT tbContacts.ContactsId, tbContacts.CompanyDesc
			     FROM (tbContacts
			          INNER JOIN tbTours ON tbContacts.ContactsId = tbTours.ContactsId)
			          INNER JOIN tbFlightBookings ON tbTours.TourId = tbFlightBookings.TourId
			     WHERE tbContacts.IsMainContact = 1
			        GROUP BY tbContacts.ContactsId, tbContacts.CompanyDesc
			        ORDER BY tbContacts.CompanyDesc
      ");
        return $sql;
    }

    public function ChangeTO($Country, $CityArray1Post, $CityArray2Post) //คัดกรอง TO ตาม Country, City
    {
      $where = "";
      if ($Country != "") {
        $where .= " AND tbContacts.Country = '$Country'";
      }
      if ($CityArray1Post != "" && $CityArray2Post == "") {
        $where .= " AND tbContacts.City = '$CityArray1Post'";
      }else if ($CityArray1Post != "" && $CityArray2Post != "") {
        $where .= " AND (tbContacts.City = '$CityArray1Post' OR tbContacts.City = '$CityArray2Post')";
      }else if ($CityArray1Post == "" && $CityArray2Post != "") {
        $where .= " AND tbContacts.City = '$CityArray2Post'";
      }

      $sql = DB::select("SELECT tbContacts.ContactsId, tbContacts.CompanyDesc
			     FROM (tbContacts
			          INNER JOIN tbTours ON tbContacts.ContactsId = tbTours.ContactsId)
			          INNER JOIN tbFlightBookings ON tbTours.TourId = tbFlightBookings.TourId
			     WHERE tbContacts.IsMainContact = 1
                 $where
			        GROUP BY tbContacts.ContactsId, tbContacts.CompanyDesc
			        ORDER BY tbContacts.CompanyDesc ASC
      ");
        return $sql;
    }

    public function ReportFlight($Type, $Option, $Country, $CityArray1Post, $CityArray2Post, $Date_NEW, $DateEnd_NEW, $Airline, $TOperators, $Reservation, $FBBT, $CXL, $Bt)
    {
      $where = "";
        if ($CXL != 2) { $where .= "AND tbTours.Cancelled = '$CXL' "; } // CXL (Status)

        if ($Reservation != "") { $where .= " AND tbTours.UsersId = '$Reservation' "; } // User

        // if ($Date_NEW != "") { $where .= " AND tbFlightBookings.FlightDate >= cast('$Date_NEW' as datetime) "; } // Date
        // if ($DateEnd_NEW != "") { $where .= " AND tbFlightBookings.FlightDate >= cast('$DateEnd_NEW' as datetime) "; } // Date End

        if ($Date_NEW != "" && $DateEnd_NEW != "") {
          $where .= " AND tbFlightBookings.FlightDate BETWEEN cast('$Date_NEW' AS DATETIME) AND cast('$DateEnd_NEW' AS DATETIME) ";
        }elseif ($Date_NEW != "" && $DateEnd_NEW == "") {
          $where .= " AND tbFlightBookings.FlightDate >= cast('$Date_NEW' as datetime) ";
        }elseif ($Date_NEW == "" && $DateEnd_NEW != "") {
          $where .= " AND tbFlightBookings.FlightDate >= cast('$DateEnd_NEW' as datetime) ";
        }
        //and ((tbHotelBookings.CheckIn between cast('$DOS_Date_NEW' as datetime) and cast('$DOS_DateEnd_NEW' as datetime))

        if ($CityArray1Post != "") { $where .= " AND tbFlightBookings.FlightFrom = '$CityArray1Post' "; } // City1
        if ($CityArray2Post != "") { $where .= " AND tbFlightBookings.FlightTo = '$CityArray2Post' "; } // City2
        if ($TOperators != "") { $where .= " AND tbContacts.ContactsId = '$TOperators' "; } //TO

        if ($Type == 1) {
            if ($Airline != ""){
              $where .= " AND tbFlightBookings.Airline = '$Airline' ";
            }else {
              $where .= " AND lower(tbFlightBookings.Airline) <> 'boat'
              AND lower(tbFlightBookings.Airline) <> 'train'
              AND lower(tbFlightBookings.Airline) <> 'balloon'
              ";
            }
            if (strpos($Airline, "Balloon") === false){
              if ($Option == "d"){
                if ($Country != "") { $where .= " AND tbCountry.CountryDesc = '$Country' "; } // Country
                // $where .= " AND Country_1.CountryId  = tbCountry.CountryId ";
              }elseif ($Option == "i"){
                if ($Country != "") { $where .= " AND tbCountry.CountryDesc != '$Country' "; } // Country
                // $where .= " AND Country_1.CountryId  <> tbCountry.CountryId ";
              }
            }
        }elseif ($Type == 2) {
          if($Bt == ""){
			         $where .= " AND ((lower(tbFlightBookings.Airline) = 'boat'
					          OR lower(tbFlightBookings.Airline) = 'train') OR (tbFlightBookings.nf = 1) )";
		      }elseif($Bt == "boat"){
			         $where .= " AND (lower(tbFlightBookings.Airline) = 'boat' OR tbFlightBookings.nf = 1)
					          AND ((lower(tbFlightBookings.Airline) <> 'train') AND ( lower(tbFlightBookings.Airline) <> 'balloon'))";
		      }else{
			         $where .= " AND tbFlightBookings.Airline = '$Bt' ";
		      }
        }else {
          if ($Bt == ""){
              $where .= " AND ((lower(tbFlightBookings.Airline) = 'balloon') AND (tbFlightBookings.nf = 1))  ";
          }elseif ($Bt == "balloon"){
              $where .= " AND ((lower(tbFlightBookings.Airline) = 'balloon' AND  tbFlightBookings.nf = 1)
                AND ((lower(tbFlightBookings.Airline) <> 'train') AND (lower(tbFlightBookings.Airline) <>'boat')))";
          }else{
              $where .= " AND tbFlightBookings.Airline = '$Bt' ";
          }
        }

        if ($FBBT == "E") {
            $where .= " AND  lower(tbFlightBookings.IsIntlByClient) = 0 ";
        }elseif ($FBBT == "O") {
            $where .= " AND  lower(tbFlightBookings.IsIntlByClient) = 1 ";
        }

        if ($Type == 3) {
            $colReport = "c1.City AS flfrom, c2.City AS flto , ";
        }else {
            $colReport = "tbFlightBookings.FlightFrom AS flfrom , tbFlightBookings.FlightTo AS flto , ";
        }

        $sql = DB::select("SELECT tbFlightBookings.arr ,
								tbFlightBookings.dep ,
								tbFlightBookings.FlightNo ,
								tbTours.OldId AS a ,
								dbo.Date_Format(tbTours.BookingDate, 'dd-mmm-yy') AS bdate ,
								tbContacts.CompanyDesc,
								tbCountry.CountryDesc ,
								tbTours.NoPax AS pax ,
								tbISUsers.UserShortcut AS isuser ,
								tbTours.TourId AS tid ,
								tbFlightBookings.Airline ,
								tbFlightBookings.FlightNo AS fno ,
								$colReport
								tbFlightBookings.arr AS Expr1 ,
								tbFlightBookings.dep AS Expr2 ,
								dbo.Date_Format(tbFlightBookings.FlightDate, 'dd-mmm-yy') AS fdate ,
								tbFlightBookings.Status ,
								tbFlightBookings.class ,
								tbTours.ToRef AS refno ,
								tbTours.Clients AS clients,
								tbContacts.Shortcut ,
								tbTours.msrepl_tran_version AS tguid ,
								tbFlightBookings.IsIntlByClient AS clnt ,
								( SELECT TOP ( 1 )
											tbTransportPrice.EntranceFee * tt.NoPax AS Expr1
								  FROM tbConfirmations
											INNER JOIN tbTours AS tt ON tbConfirmations.TourId = tt.TourId
											INNER JOIN tbQuotationDataSub ON tbConfirmations.QuotationId = tbQuotationDataSub.QuotationId
											INNER JOIN tbTransportPrice ON tbTransportPrice.TransportPriceId = tbQuotationDataSub.TransportPriceId
											INNER JOIN tbCity ON tbTransportPrice.CityId1 = tbCity.CityId
											INNER JOIN tbCity AS Overland_2 ON tbTransportPrice.CityId2 = Overland_2.CityId
								  WHERE ( tt.TourId = tbTours.TourId )
											AND ( tbQuotationDataSub.TransportKindId = 'BKG1200800000002' )
								) AS fPrice ,
								( SELECT TOP ( 1 )
											tbTransportPrice.EntranceFee
								  FROM tbConfirmations
											INNER JOIN tbTours AS tt ON tbConfirmations.TourId = tt.TourId
											INNER JOIN tbQuotationDataSub ON tbConfirmations.QuotationId = tbQuotationDataSub.QuotationId
											INNER JOIN tbTransportPrice ON tbTransportPrice.TransportPriceId = tbQuotationDataSub.TransportPriceId
											INNER JOIN tbCity ON tbTransportPrice.CityId1 = tbCity.CityId
											INNER JOIN tbCity AS Overland_2 ON tbTransportPrice.CityId2 = Overland_2.CityId
								  WHERE ( tt.TourId = tbTours.TourId )
											AND ( tbQuotationDataSub.TransportKindId = 'BKG1200800000002' )
								) AS Real_PP ,
								tbFlightBookings.remark ,
								tbFlightBookings.FBIdUniqueId,
								ContactCompany.CompanyName
						FROM tbContacts
								INNER JOIN tbTours ON tbContacts.ContactsId = tbTours.ContactsId
								INNER JOIN tbISUsers ON tbISUsers.UsersId = tbTours.UsersId
								INNER JOIN tbFlightBookings ON tbTours.TourId = tbFlightBookings.TourId

								 LEFT  JOIN tbCity ON tbCity.City = tbFlightBookings.FlightFrom
								 LEFT  JOIN tbFlightDestination AS fd1 ON tbFlightBookings.FlightFrom = fd1.Destination
								 LEFT  JOIN tbCountry AS Country_1 ON fd1.CountryId = Country_1.CountryId

								 LEFT  JOIN tbCity AS Overland_1 ON tbFlightBookings.FlightTo = Overland_1.City
								 LEFT  JOIN tbFlightDestination AS fd2 ON tbFlightBookings.FlightTo = fd2.Destination
								 LEFT  JOIN tbCountry AS Country_2 ON fd2.CountryId = Country_2.CountryId

								 INNER JOIN dbo.BookingRateCost ON dbo.tbFlightBookings.FBIdUniqueId = dbo.BookingRateCost.ReferanceId
								 LEFT JOIN dbo.SupplierServiceRate ON dbo.SupplierServiceRate.ServiceRateId = dbo.BookingRateCost.ServiceRateId
								 INNER JOIN dbo.SupplierServiceContract ON dbo.SupplierServiceContract.ServiceContractId = dbo.SupplierServiceRate.ServiceContractId
								 INNER JOIN dbo.SupplierServiceMaster ON dbo.SupplierServiceMaster.ServiceMasterId = dbo.SupplierServiceContract.ServiceMasterId
								 INNER JOIN tbCountry ON tbCountry.CountryId = SupplierServiceMaster.FromCountryId
								 INNER JOIN dbo.ContactCompany ON dbo.SupplierServiceContract.CompanyId = dbo.ContactCompany.CompanyId

								 INNER JOIN tbCity AS c1 ON  SupplierServiceMaster.FlightFromCityId = c1.CityId
								 INNER JOIN tbCity AS c2 ON  SupplierServiceMaster.FlightToCityId = c2.CityId
						WHERE
								  UPPER(tbFlightBookings.Status) NOT IN ( 'CXL' )
								AND tbContacts.IsMainContact = 1
								AND dbo.BookingRateCost.CostTypeId = (
								  SELECT MAX(brc.CostTypeId)
								  FROM dbo.BookingRateCost brc
								  WHERE brc.ReferanceId = dbo.BookingRateCost.ReferanceId
								 )
							$where
							ORDER BY tbTours.TourId ,
								tbCountry.CountryDesc ,
								tbFlightBookings.FlightDate ASC
            ");
            //dd($sql);
                return $sql;
    }


}
