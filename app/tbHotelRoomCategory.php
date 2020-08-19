<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class tbHotelRoomCategory extends Model
{
  protected $table = 'tbHotelRoomCategory';
  protected $primaryKey = 'RoomId';

  public function SelectRoomCat($hotel)
  {
    $sql = DB::select("
    SELECT DISTINCT ((CASE WHEN CHARINDEX('(', RoomCategory) > 0
      THEN SUBSTRING(RoomCategory, 1, CHARINDEX('(', RoomCategory)-1)
      ELSE RoomCategory END)) AS RoomCatData FROM tbHotelRoomCategory
      WHERE HotelId='$hotel' AND  RoomCategory <> '' AND RoomCategory IS NOT NULL
      ORDER BY (CASE WHEN CHARINDEX('(', RoomCategory) > 0
      THEN SUBSTRING(RoomCategory, 1, CHARINDEX('(', RoomCategory)-1) ELSE RoomCategory END)
      ");

      return $sql;
  }
}
