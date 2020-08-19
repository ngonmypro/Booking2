<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class VehicleBooking extends Model
{
  protected $table = 'VehicleBooking';
  protected $primaryKey = 'VehicleBooking.VBId';


  public function SelectVehicleBookingService($ServiceContractId,$SDStart,$SDEnd,$TDStart,$TDEnd)
  {
     $where = '';
    if ($SDStart != '' && $SDEnd != '') {
      $where .= " AND [VehicleBooking].[OnDay] BETWEEN '$SDStart' AND '$SDEnd'";
    }
    if ($TDStart != '' && $TDEnd != '') {
      $where .= " AND [VehicleBooking].[BookDate] BETWEEN '$TDStart' AND '$TDEnd'";
    }

    $sql = DB::select("SELECT /*[VehicleBooking].[Pax]
          , */dbo.Date_Format([VehicleBooking].[OnDay],'dd-mmm-yyyy') as OnDay
          , [SupplierServiceContract].[ServiceName]
          , [tbTours].[NoPax] AS Pax
          , [tbTours].[TourId]
          , [tbTours].[Clients]
          /*, [tbContacts].**/
          , [Location].[TO_TA]
      FROM [ICSDB].[dbo].[VehicleBooking]

        LEFT JOIN [SupplierServiceContract] ON [SupplierServiceContract].[ServiceContractId] = [VehicleBooking].[ServiceContractId]
        LEFT JOIN [tbTours] ON [tbTours].[TourId] = [VehicleBooking].[TourId]
        LEFT JOIN [tbContacts] ON [tbContacts].[ContactsId] = [tbTours].[ContactsId]
        LEFT JOIN [Location] ON [Location].[LocationID] = [tbContacts].[LocationID]

      WHERE [VehicleBooking].[ServiceContractId] = '$ServiceContractId'
           $where

        ORDER BY [VehicleBooking].[OnDay] ASC
    ");
  // dd($sql);
    return $sql;
  }

}
