<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class SupplierServiceContract extends Model
{
  protected $table = 'SupplierServiceContract';
  protected $primaryKey = 'SupplierServiceContract.ServiceContractId';


  public function ChangeSupplierServiceContract($City,$Supplier)
  {
      $where = "";
    if ($Supplier != '') {
      $where .= "AND SupplierServiceContract.CompanyId = '$Supplier'";
    }
    if ($City != '') {
      $where .= "AND SupplierServiceMaster.FlightFromCityId = '$City'";
    }
    $sql = DB::select("SELECT * FROM
      ( SELECT ServiceContractId
        , SupplierServiceContract.ServiceName+'('+SupplierServiceMaster.VehicleType+')' as ServiceName
        , dbo.Date_Format(ValidFrom,'dd-mmm-yyyy') AS ValidFrom
        , dbo.Date_Format(ValidTo,'dd-mmm-yyyy') AS ValidTo
        , Combineflag
        , ServiceCategory.ServiceCode AS ServiceCode

        FROM SupplierServiceContract

        INNER JOIN SupplierServiceMaster ON SupplierServiceContract.ServiceMasterId = SupplierServiceMaster.ServiceMasterId
        LEFT JOIN ServiceCategory ON SupplierServiceMaster.ServiceCategoryId = ServiceCategory.Id

        WHERE  --Company ID
-- /*AND ServiceCategoryId = '92D2F393-942D-4437-B432-31EF282A1214' */
           Combineflag = 1
          AND (ValidFrom >= GETDATE() OR ValidTo >= DATEADD(YEAR,-1,GETDATE()))
          $where
-- /*AND CHARINDEX('Luggage Van - Use Hyundai Starex - Max. 20 luggage',SupplierServiceMaster.ServiceMasterName) > 0 */
          ) AS t

        ORDER BY t.ServiceName
          ");

          return $sql;
  }

}
