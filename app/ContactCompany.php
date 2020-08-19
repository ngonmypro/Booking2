<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class ContactCompany extends Model
{
  protected $table = 'ContactCompany';
  protected $primaryKey = 'ContactCompany.CompanyId';


  public function ChangeContactCompany($City,$SupplierType)
  {
    $where = "";
    if ($City != '') {
      $where .= " AND [CityId] = '$City'";
    }
    if ($SupplierType != '') {
      $where .= " AND [SupplierTypeId] = '$SupplierType'";
    }
    $sql = DB::select("SELECT [CompanyId]
            ,[CompanyName]
      FROM [ICSDB].[dbo].[ContactCompany]

      WHERE 1=1 $where
      ORDER BY [CompanyName] ASC
          ");
// dd($sql);
          return $sql;
  }

}
