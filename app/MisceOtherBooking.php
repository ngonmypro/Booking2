<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class MisceOtherBooking extends Model
{
  protected $table = 'MisceOtherBooking';
  protected $primaryKey = 'MisceOtherBooking.MOBId';


  public function SelectMisceOtherBookingService($ServiceContractId,$SDStart,$SDEnd,$TDStart,$TDEnd)
  {
     $where = '';
    if ($SDStart != '' && $SDEnd != '') {
      $where .= " AND [MisceOtherBooking].[OnDay] BETWEEN '$SDStart' AND '$SDEnd'";
    }
    if ($TDStart != '' && $TDEnd != '') {
      $where .= " AND [MisceOtherBooking].[BookDate] BETWEEN '$TDStart' AND '$TDEnd'";
    }

    $sql = DB::select("SELECT /*[MisceOtherBooking].[Pax]
          ,*/ dbo.Date_Format([MisceOtherBooking].[OnDay],'dd-mmm-yyyy') as OnDay
          , [SupplierServiceContract].[ServiceName]
          , [tbTours].[NoPax] AS Pax
          , [tbTours].[TourId]
          , [tbTours].[Clients]
          /*, [tbContacts].**/
          , [Location].[TO_TA]
      FROM [ICSDB].[dbo].[MisceOtherBooking]

        LEFT JOIN [SupplierServiceContract] ON [SupplierServiceContract].[ServiceContractId] = [MisceOtherBooking].[ServiceContractId]
        LEFT JOIN [tbTours] ON [tbTours].[TourId] = [MisceOtherBooking].[TourId]
        LEFT JOIN [tbContacts] ON [tbContacts].[ContactsId] = [tbTours].[ContactsId]
        LEFT JOIN [Location] ON [Location].[LocationID] = [tbContacts].[LocationID]

      WHERE [MisceOtherBooking].[ServiceContractId] = '$ServiceContractId'
           $where

        ORDER BY [MisceOtherBooking].[OnDay] ASC
    ");
  // dd($sql);
    return $sql;
  }

}
