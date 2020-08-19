<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class tbTours extends Model
{
  protected $table = 'tbTours';
  protected $primaryKey = 'tbTours.TourId';


  public function SelectUser($bsdate, $bedate, $tomarket, $client, $country)
  {
      $where = "";

      if(trim($bsdate) != "" and trim($bedate) == ""){
					$bedate = $bsdate;
				}

				if(!empty($client)){
					$where .= " AND (tc.ContactsId = '$client') ";
				}

				if(!empty($tomarket)){
					$where .= " AND (l.TOMarketId = '$tomarket') ";
				}

        if(!empty($country)){
					$where .= " AND (l2.CountryID = '$country') ";
				}

				$where .= " AND (t.[cdate] BETWEEN '$bsdate' AND '$bedate')
        AND (ta.Actioncode = 'bc' OR ta.Actioncode = 'cf') ";

    $sql = DB::select("SELECT u.usersid as uid
								, u.uname
								, e.department
								, Ct.Country
								, l2.City
                -- ,  SUM(t.diff / CAST (t.total AS FLOAT)) / COUNT(*)  AS tot
				FROM    tbTours AS t
						INNER JOIN tbTourAction AS ta ON t.TourId = ta.TourId
						INNER JOIN tbISUsers u ON u.usersid = t.usersid
						INNER JOIN dbo.tbContacts AS tc ON tc.ContactsId = t.ContactsId
						INNER JOIN dbo.Location AS l ON l.LocationId = tc.LocationId
						INNER JOIN dbo.Employee AS e ON e.Email = u.Email
						INNER JOIN dbo.Location AS l2 ON l2.LocationId = e.LocationId
						INNER JOIN dbo.Country AS Ct ON Ct.CountryID = l2.CountryID
				WHERE   t.TourSeries = 0
						AND tc.Shortcut NOT IN('TSR', 'AOU', 'SUI', 'FLX', 'VDB', 'VSA')
						AND ( ISNULL(e.inactive, 0) NOT IN ( 1 ) )
						AND u.email = e.email
						AND tc.IsMainContact = 1
						$where
				GROUP BY u.uname
									, u.usersid
									, e.department
									, Ct.Country
									, l2.City
                  -- , SUM(t.diff / CAST (t.total AS FLOAT)) / COUNT(*)
				ORDER BY u.uname ASC ");
// dd($sql);
    // $array = [];
    // $result = [];
    //   foreach ($tbHotelsArray as $row) {
    //     $result = [ 'HotelId' => $row->HotelId ,
    //         'Hotel' => $row->Hotel ,
    //         'City' => $row->City
    //     ];
    //     array_push($data_list , $result);
    //   }
    // dd($array);
		return $sql;
  }

  public function SelectTours($bsdate, $bedate, $tomarket, $client, $country, $uid)
  {
    $where = "";

    if(trim($bsdate) != "" and trim($bedate) == ""){
        $bedate = $bsdate;
      }

      if(!empty($client)){
        $where .= " AND (tc.ContactsId = '$client') ";
      }

      if(!empty($tomarket)){
        $where .= " AND (l.TOMarketId = '$tomarket') ";
      }

      // if(!empty($country)){
      //   $where .= " AND (l.CountryID = '$country') ";
      // }

      $where .= " AND (t.[cdate] BETWEEN '$bsdate' AND '$bedate')
      AND (ta.Actioncode = 'bc' OR ta.Actioncode = 'cf') ";

      $sql = DB::select("SELECT  ( SUM(t.diff / CAST (t.total AS FLOAT)) / COUNT(*) ) AS tot
								FROM    (
											SELECT    ( CASE WHEN DATEDIFF(DAY, t.cdate, MIN(ta.ActionDate)) = 0
															THEN 1
															ELSE DATEDIFF(DAY, t.cdate, MIN(ta.ActionDate))
														END ) AS diff ,
													(
														SELECT    COUNT(*)
														FROM      dbo.tbTourAction AS tta
														WHERE     tta.TourId = t.TourId
													) AS total
											FROM      tbTours AS t
													INNER JOIN tbTourAction ta ON t.TourId = ta.TourId
													INNER JOIN dbo.tbContacts AS tc ON tc.ContactsId = t.ContactsId
													INNER JOIN dbo.Location AS l ON l.LocationId = tc.LocationId
											WHERE   t.TourSeries = 0
													AND tc.Shortcut NOT IN('TSR', 'AOU', 'SUI', 'FLX', 'VDB', 'VSA')
													AND t.usersid = '$uid'
													AND tc.IsMainContact = 1
													$where
											GROUP BY  t.TourId ,
													t.[cdate]
										) AS t");
            return $sql;
  }
}
