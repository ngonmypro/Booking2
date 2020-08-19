<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class tbCountry extends Model
{
  protected $table = 'tbCountry';
  protected $primaryKey = 'tbCountry.CountryId';

  public function SelectCountry()
  {
    $sql = DB::select("SELECT * FROM tbCountry
      WHERE RERBarterMarketId IS NOT NULL
      ORDER BY CountryDesc
    ");

      return $sql;
  }

  public function SelectCountrySupplier()
  {
    $sql = DB::select("SELECT [CountryId]
	           , SUBSTRING(CountryDesc,1,1) AS CCode
             ,[CountryDesc]
              FROM [ICSDB].[dbo].[tbCountry]
              WHERE Supplier = '1'
              ORDER BY CountryDesc ASC
          ");

          return $sql;
  }

}
