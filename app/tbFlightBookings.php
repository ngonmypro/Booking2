<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class tbFlightBookings extends Model
{
  protected $table = 'tbFlightBookings';
  protected $primaryKey = 'tbFlightBookings.FBId';

  public function SelectCity()
  {
    $sql = DB::select("SELECT DISTINCT tbFlightBookings.FlightFrom,
	                   tbCountry.CountryDesc
	              FROM tbFlightBookings
	                   INNER JOIN tbTours ON tbFlightBookings.TourId = tbTours.TourId
                     INNER JOIN tbCity ON tbCity.City = tbFlightBookings.FlightFrom
                     INNER JOIN tbCountry ON tbCountry.CountryId = tbCity.CountryId
	              WHERE  tbFlightBookings.FlightFrom <> '' AND
	                     tbFlightBookings.Airline <> 'Others' AND
                       tbFlightBookings.Airline <> 'Boat' AND
                       tbFlightBookings.Airline <> 'Balloon' AND
                       tbFlightBookings.Airline <> 'Train' AND
                       tbFlightBookings.Airline <> 'Bus' AND
                       tbFlightBookings.Airline <> '0' AND
                       tbFlightBookings.Airline <> '' AND
                       tbFlightBookings.FlightFrom IS NOT NULL AND
                       tbTours.BookingVersion = 2
                ORDER BY tbFlightBookings.FlightFrom
    ");
        return $sql;
  }

  public function CheangCity($Country)
  {
    $where = "";
    if ($Country != '') {
      $where .= " AND tbCountry.CountryDesc = '$Country' ";
    }

    $sql = DB::select("SELECT DISTINCT tbFlightBookings.FlightFrom,
	                   tbCountry.CountryDesc
	              FROM tbFlightBookings
	                   INNER JOIN tbTours ON tbFlightBookings.TourId = tbTours.TourId
                     INNER JOIN tbCity ON tbCity.City = tbFlightBookings.FlightFrom
                     INNER JOIN tbCountry ON tbCountry.CountryId = tbCity.CountryId
	              WHERE  tbFlightBookings.FlightFrom <> '' AND
	                     tbFlightBookings.Airline <> 'Others' AND
                       tbFlightBookings.Airline <> 'Boat' AND
                       tbFlightBookings.Airline <> 'Balloon' AND
                       tbFlightBookings.Airline <> 'Train' AND
                       tbFlightBookings.Airline <> 'Bus' AND
                       tbFlightBookings.Airline <> '0' AND
                       tbFlightBookings.Airline <> '' AND
                       tbFlightBookings.FlightFrom IS NOT NULL AND
                       tbTours.BookingVersion = 2 $where
                ORDER BY tbFlightBookings.FlightFrom
    ");
        return $sql;
  }

  public function SelectAirline()
  {
    $sql = DB::select("SELECT DISTINCT Airline
	     FROM tbFlightBookings
	       INNER JOIN tbTours ON tbTours.TourId = tbFlightBookings.TourId
	       WHERE  tbFlightBookings.FlightDate > cast('1-Jul-04' as datetime)
            AND tbTours.Cancelled=0
            AND airline IS NOT NULL
            AND UPPER(tbFlightBookings.Status) NOT IN ('CXL')
            AND lower(airline) <> 'train'
            AND lower(airline) <> 'boat'
    ");
      return $sql;
  }

  public function CheangAirline($Country, $CityArray1Post, $CityArray2Post)
  {
    $where = "";
    if ($Country != "") {
      $where .= " AND tbCountry.CountryDesc = '$Country'";
    }
    if ($CityArray1Post != "" && $CityArray2Post == "") {
      $where .= " AND tbFlightBookings.FlightFrom = '$CityArray1Post'";
    }else if ($CityArray1Post != "" && $CityArray2Post != "") {
      $where .= " AND (tbFlightBookings.FlightFrom = '$CityArray1Post' OR tbFlightBookings.FlightFrom = '$CityArray2Post')";
    }else if ($CityArray1Post == "" && $CityArray2Post != "") {
      $where .= " AND tbFlightBookings.FlightFrom = '$CityArray2Post'";
    }

    $sql = DB::select("SELECT DISTINCT Airline
	       FROM tbFlightBookings
	         INNER JOIN tbTours ON tbTours.TourId = tbFlightBookings.TourId
		       INNER JOIN tbCity ON tbCity.City = tbFlightBookings.FlightFrom
           INNER JOIN tbCountry ON tbCountry.CountryId = tbCity.CountryId
	       WHERE  tbFlightBookings.FlightDate > cast('1-Jul-04' as datetime)
            AND tbTours.Cancelled=0
            AND Airline IS NOT NULL
            AND UPPER(tbFlightBookings.Status) NOT IN ('CXL')
            AND lower(Airline) <> 'train'
            AND lower(Airline) <> 'boat'
            $where
			    GROUP BY Airline
          ORDER BY Airline ASC
    ");
        return $sql;
  }

  public function SelectFlightBookingService($ServiceContractId,$SDStart,$SDEnd,$TDStart,$TDEnd)
  {
     $where = '';
    if ($SDStart != '' && $SDEnd != '') {
      $where .= " AND [tbFlightBookings].[FlightDate] BETWEEN '$SDStart' AND '$SDEnd'";
    }
    if ($TDStart != '' && $TDEnd != '') {
      $where .= " AND [tbFlightBookings].[bookingdate] BETWEEN '$TDStart' AND '$TDEnd'";
    }

    $sql = DB::select("SELECT [tbFlightBookings].[Pax]
          , dbo.Date_Format([tbFlightBookings].[FlightDate],'dd-mmm-yyyy') as FlightDate
          , [SupplierServiceContract].[ServiceName]
          , [tbTours].[TourId]
          , [tbTours].[Clients]
          /*, [tbContacts].**/
          , [Location].[TO_TA]
      FROM [ICSDB].[dbo].[tbFlightBookings]

        LEFT JOIN [SupplierServiceContract] ON [SupplierServiceContract].[ServiceContractId] = [tbFlightBookings].[ServiceContractId]
        LEFT JOIN [tbTours] ON [tbTours].[TourId] = [tbFlightBookings].[TourId]
        LEFT JOIN [tbContacts] ON [tbContacts].[ContactsId] = [tbTours].[ContactsId]
        LEFT JOIN [Location] ON [Location].[LocationID] = [tbContacts].[LocationID]

      WHERE [tbFlightBookings].[ServiceContractId] = '$ServiceContractId'
           $where

        ORDER BY [tbFlightBookings].[FlightDate] ASC
    ");
 // dd($sql);
    return $sql;
  }

}
