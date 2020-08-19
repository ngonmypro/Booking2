<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class SupplyType extends Model
{
  protected $table = 'SupplyType';
  protected $primaryKey = 'SupplyType.SupplyTypeID';


  public function ChangeSupplyType(/*$countryid*/)
  {
    // if ($countryid != '') {
    //   $where = "CountryId = '$countryid' AND";
    // }
    $sql = DB::select("SELECT [SupplyTypeID]
      ,[SupplyType]
      ,[flag]
  FROM [ICSDB].[dbo].[SupplyType]

  WHERE flag IN (1,3)
  ORDER BY SupplyType ASC
          ");

          return $sql;
  }

}
