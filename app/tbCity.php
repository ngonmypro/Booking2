<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class tbCity extends Model
{
  protected $table = 'tbCity';
  protected $primaryKey = 'tbCity.CountryId';


  public function SelectCitySupplier($EmpContryid)
  {
    if ($EmpContryid != '') {
      $where = "CountryId = '$EmpContryid' AND";
    }
    $sql = DB::select("SELECT [CityId]
      ,[CountryId]
      ,[City]
  FROM [ICSDB].[dbo].[tbCity]
  WHERE $where ddate IS NULL

  ORDER BY City ASC
          ");

          return $sql;
  }

  public function ChangeCitySupplier($countryid)
  {
    if ($countryid != '') {
      $where = "CountryId = '$countryid' AND";
    }
    $sql = DB::select("SELECT [CityId]
      ,[CountryId]
      ,[City]
  FROM [ICSDB].[dbo].[tbCity]
  WHERE $where ddate IS NULL

  ORDER BY City ASC
          ");

          return $sql;
  }

}
