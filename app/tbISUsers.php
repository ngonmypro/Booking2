<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class tbISUsers extends Model
{
  protected $table = 'tbISUsers';
  protected $primaryKey = 'UsersId';

  public function SelectUser()
  {
    $sql = DB::select("
    SELECT UsersId, UName FROM tbISUsers ORDER BY UName ASC
    ");
    return $sql;
  }

  public function SelectInbound()
  {
    $sql = DB::select("SELECT tbISUsers.UsersId, tbISUsers.UserShortcut, tbISUsers.uname
				FROM tbISUsers
				ORDER BY tbISUsers.uname");
        return $sql;
  }

  //Flight
  public function SelectReservation()
  {
    $sql = DB::select("SELECT UsersId, UName
        FROM tbISUsers
        ORDER BY UName
        ");
        return $sql;
  }
}
