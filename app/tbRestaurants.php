<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class tbRestaurants extends Model
{
  protected $table = 'tbRestaurants';
  protected $primaryKey = 'RestaurantId';

//Booking
  public function SelectRestaurants()
  {
    $sql = DB::select("SELECT * FROM (
					SELECT  RestaurantId ,
							Restaurant ,dbo.tbRestaurants.City,
							( SELECT    COUNT(*)
							  FROM      dbo.tbRestaurantBookings
							  WHERE     dbo.tbRestaurantBookings.RestaurantId = dbo.tbRestaurants.RestaurantId
							) AS ct
					FROM    dbo.tbRestaurants
          -- WHERE tbRestaurants.Activated != 0
					) AS tmp
					WHERE tmp.ct> 0
          -- AND tbRestaurants.Activated != 0
					ORDER BY tmp.Restaurant");
    return $sql;
  }

  public function SelectContry()
  {
    $sql = DB::select("
      SELECT Country FROM tbRestaurants
      GROUP BY Country
      ORDER BY Country
    ");
    return $sql;
  }

  public function SelectCity()
  {
    $sql = DB::select("
      SELECT City FROM tbRestaurants
      GROUP BY City
      ORDER BY City
    ");
    return $sql;
  }

  public function ChkSelectCity($country)
  {
    $sql = DB::select("
      SELECT City FROM tbRestaurants
        WHERE Country = '$country'
        GROUP BY City
        ORDER BY City
    ");
    return $sql;
  }

  public function ChkSelectRestaurants($city, $country)
  {
      $where = "";
      if ($city != '') {
        $where .= "AND tbRestaurants.City = '$city'";
      }

      if ($country != '') {
        $where .= "AND tbRestaurants.Country = '$country'";
      }

    $sql = DB::select("SELECT * FROM (
					SELECT  RestaurantId ,
							Restaurant ,dbo.tbRestaurants.City,
							( SELECT    COUNT(*)
							  FROM      dbo.tbRestaurantBookings
							  WHERE     dbo.tbRestaurantBookings.RestaurantId = dbo.tbRestaurants.RestaurantId
                $where
							) AS ct
					FROM    dbo.tbRestaurants
					) AS tmp
					WHERE tmp.ct> 0
					ORDER BY tmp.Restaurant");
    return $sql;
  }

  public function ReportRestaurant($Country, $City, $TORestaurant, $Restaurant, $Company, $Inbound, $Start_Date_NEW, $Start_DateEnd_NEW, $Restaurant_Date_NEW, $Restaurant_DateEnd_NEW, $Book_Date_NEW, $Book_DateEnd_NEW, $Status)
  {
    $where = "";
    if ($Country != "") { // ประเทศ
      $where .= " AND tbRestaurants.Country = '$Country'";
    }

    if ($City != "") {  // จังหวัด
      $where .= " AND tbRestaurants.City = '$City'";
    }

    if ($TORestaurant != "") {
      if ($TORestaurant == "LR") { // ประเภทห้องอาหาร
        $where .= " AND (tbRestaurants.HotelId IS NULL OR tbRestaurants.HotelId = '')";
      }else {
        $where .= " AND (tbRestaurants.HotelId IS NOT NULL OR tbRestaurants.HotelId <> '')";
      }
    }

    if ($Restaurant != "") {
      $where .= " AND  tbRestaurantBookings.RestaurantId = '$Restaurant'";
    }

    if ($Company != "") {
			$where .= " AND tbTours.ContactsId = '$Company'";
	}

	if ($Inbound != "") {
			$where .= " AND tbTours.UsersId ='$Inbound'";
	}

  //Tour Date
	if ($Start_Date_NEW != "" && $Start_DateEnd_NEW == "") {
			$Start_DateEnd_NEW = $Start_Date_NEW;
			$where .= " AND tbTours.TourStartDate >= CAST('$Start_Date_NEW' AS DATETIME)";
	}
	if ($Start_DateEnd_NEW != "" && $Start_Date_NEW != "") {
			$where .= " AND (( tbTours.TourStartDate BETWEEN CAST('$Start_Date_NEW' AS DATETIME)
      AND  CAST('$Start_DateEnd_NEW' AS DATETIME)) OR ( tbTours.TourEndDate BETWEEN CAST('$Start_Date_NEW' AS DATETIME)
      AND  CAST('$Start_DateEnd_NEW' AS DATETIME)))";
	}
	/*if ($Start_Date_NEW == "" && $Start_DateEnd_NEW == "") {
			$where .= " AND tbTours.TourStartDate >= getdate()";
	}*/

	//Restaurant Date
	if ($Restaurant_Date_NEW != "" && $Restaurant_DateEnd_NEW == "") {
			$Restaurant_DateEnd_NEW = $Restaurant_Date_NEW;
			$where .= " AND tbRestaurantBookings.OnDay >= CAST('$Restaurant_Date_NEW' AS DATETIME)";
	}
	if ($Restaurant_DateEnd_NEW != "" && $Restaurant_Date_NEW != "") {
			$where .= " AND tbRestaurantBookings.OnDay BETWEEN CAST('$Restaurant_Date_NEW' AS DATETIME)
      AND CAST('$Restaurant_DateEnd_NEW' AS DATETIME)";
	}
	/*if ($Restaurant_Date_NEW == "" && $Restaurant_DateEnd_NEW == "") {
			$where .= " AND tbRestaurantBookings.OnDay >=getdate()";
	}*/

  //Booking DateEnd
  if ($Book_Date_NEW != "" && $Book_DateEnd_NEW == "") {
			$Book_DateEnd_NEW = $Book_Date_NEW;
			$where .= " AND tbTours.BookingDate >= CAST('$Book_Date_NEW' AS DATETIME)";
	}
	if ($Book_DateEnd_NEW != "" && $Book_Date_NEW != "") {
			$where .= " AND tbTours.BookingDate BETWEEN CAST('$Book_Date_NEW' AS DATETIME)
      AND CAST('$Book_DateEnd_NEW' AS DATETIME) ";
	}

  if ($Status != "") {
    $where .= " AND tbTours.cancelled = '0'";
  }

  $sql = DB::select("SELECT
        tbTours.TourId as tid,
        tbTours.clients,
        tbTours.NoPax as pax,
        dbo.Date_Format(tbTours.TourStartDate,'dd-mmm-yy') AS stdate,
        dbo.Date_Format(tbTours.TourEndDate,'dd-mmm-yy') AS etdate,
        dbo.Date_Format(tbTours.BookingDate,'dd-mmm-yy') as bdate,
        tbContacts.CompanyDesc,
        tbContacts.msrepl_tran_version as mstcontact,
        tbISUsers.uname,
        tbRestaurantBookings.RBId as rsid,
        tbTours.msrepl_tran_version as msttour
    FROM tbTours
      INNER JOIN tbContacts ON tbContacts.ContactsId = tbTours.ContactsId
      INNER JOIN tbISUsers ON tbISUsers.UsersId = tbTours.UsersId
      INNER JOIN tbRestaurantBookings ON tbTours.TourId = tbRestaurantBookings.TourId
      INNER JOIN tbRestaurants ON tbRestaurants.RestaurantId = tbRestaurantBookings.RestaurantId
    WHERE tbTours.TourId is not null AND tbTours.TourId NOT LIKE 'BKT%' $where
    AND tbContacts.IsMainContact = 1
    GROUP BY tbTours.TourId, tbTours.clients, tbTours.NoPax, tbTours.TourStartDate, tbTours.TourEndDate, tbTours.BookingDate, tbContacts.CompanyDesc, tbISUsers.uname, tbRestaurantBookings.RBId ,tbContacts.msrepl_tran_version,tbTours.msrepl_tran_version
    ORDER BY tbTours.TourStartDate, tbTours.TourEndDate, tbTours.TourId");
//dd($sql);
    return $sql;
  }

  public function ReportRestaurantName($Restaurant)
  {
    $sql = DB::select("SELECT Restaurant
						FROM tbRestaurants
						WHERE RestaurantId = '$Restaurant'");
    return $sql;
  }

  public function ReportRestaurantData($tid, $City)
  {
    //dd($City);
    $where = "";

    if ($City != "") {
      $where .= "AND tbRestaurants.City = '$City'";
    }
    $sql = DB::select("SELECT tbRestaurants.Restaurant,
			tbRestaurants.City,
			tbRestaurantBookings.Status,
			dbo.Date_Format(tbRestaurantBookings.BookingDate,'dd-mmm-yy') AS resbdate,
			dbo.Date_Format(tbRestaurantBookings.OnDay,'dd-mmm-yy') AS resonday,
			tbRestaurantBookings.AtTime,
			tbRestaurantBookings.Pax,
			tbRestaurantBookings.LunchDinner
			 FROM tbRestaurants LEFT JOIN tbRestaurantBookings ON tbRestaurants.RestaurantId = tbRestaurantBookings.RestaurantId
			 where  tbRestaurantBookings.TourId='$tid' $where Order by OnDay
    ");
    return $sql;
  }

}
