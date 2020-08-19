<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class EntranceFeeBooking extends Model
{
  protected $table = 'EntranceFeeBooking';
  protected $primaryKey = 'EntranceFeeBooking.EBId';


  public function SelectEntranceFeeBookingService($ServiceContractId,$SDStart,$SDEnd,$TDStart,$TDEnd)
  {
     $where = '';
    if ($SDStart != '' && $SDEnd != '') {
      $where .= " AND [EntranceFeeBooking].[OnDay] BETWEEN '$SDStart' AND '$SDEnd'";
    }
    if ($TDStart != '' && $TDEnd != '') {
      $where .= " AND [EntranceFeeBooking].[BookDate] BETWEEN '$TDStart' AND '$TDEnd'";
    }

    $sql = DB::select("SELECT /*[EntranceFeeBooking].[Pax]
          , */dbo.Date_Format([EntranceFeeBooking].[OnDay],'dd-mmm-yyyy') as OnDay
          , [SupplierServiceContract].[ServiceName]
          , [tbTours].[NoPax] AS Pax
          , [tbTours].[TourId]
          , [tbTours].[Clients]
          /*, [tbContacts].**/
          , [Location].[TO_TA]
      FROM [ICSDB].[dbo].[EntranceFeeBooking]

        LEFT JOIN [SupplierServiceContract] ON [SupplierServiceContract].[ServiceContractId] = [EntranceFeeBooking].[ServiceContractId]
        LEFT JOIN [tbTours] ON [tbTours].[TourId] = [EntranceFeeBooking].[TourId]
        LEFT JOIN [tbContacts] ON [tbContacts].[ContactsId] = [tbTours].[ContactsId]
        LEFT JOIN [Location] ON [Location].[LocationID] = [tbContacts].[LocationID]

      WHERE [EntranceFeeBooking].[ServiceContractId] = '$ServiceContractId'
           $where

        ORDER BY [EntranceFeeBooking].[OnDay] ASC
    ");
  // dd($sql);
    return $sql;
  }

}
