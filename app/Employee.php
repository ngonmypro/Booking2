<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Employee extends Model
{
  protected $table = 'Employee';
  protected $primaryKey = 'Employee.EmployeeID';

  public function SelectCountryforEmployee($ISID)
  {
    $sql = DB::select("SELECT  CountryDesc,dbo.tbCountry.CountryId FROM dbo.Employee
		INNER JOIN dbo.[IS] ON dbo.Employee.EmployeeID = dbo.[IS].EmployeeID
		INNER JOIN dbo.Location ON dbo.Employee.LocationID= dbo.Location.LocationID
		INNER JOIN dbo.Country ON dbo.Location.CountryID = dbo.Country.CountryID
		INNER JOIN dbo.tbCountry ON dbo.tbCountry.CountryDesc = dbo.Country.Country
		WHERE ISID = '$ISID'
    ");
      return $sql;
  }

}
