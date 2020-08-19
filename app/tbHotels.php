<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class tbHotels extends Model
{
  protected $table = 'tbHotels';
  protected $primaryKey = 'HotelId';

  public function SelectCity()
  {
    // $sql = DB::select("
    //  SELECT Country , City FROM tbHotels WHERE City != ''
    //      GROUP BY City , Country
    //      ORDER BY City ASC
    //     ");

        $sql = DB::select("SELECT CityId
								,City
						FROM    dbo.tbCity
						JOIN 	dbo.tbCountry ON dbo.tbCity.CountryId = dbo.tbCountry.CountryId
						WHERE tbCountry.CountryDesc IN (
							  'Cambodia',
							  'Indonesia',
							  'Laos',
							  'Myanmar',
							  'Thailand',
							  'Vietnam'
							)
								order by City asc");

    return $sql;
  }

  public function SelestHotel()
  {
    $sql = DB::select("
    SELECT HotelId , Hotel , City FROM tbHotels WHERE Hotel != ''
    ORDER BY Hotel ASC
    ");
    return $sql;
  }

  public function SelectHotelChangeCity($country,$city)
  {
    $sql = DB::select("
    SELECT HotelId , Hotel , City FROM tbHotels WHERE Hotel != ''
    and Country = '$country' and City = '$city'
    ORDER BY Hotel ASC
    ");
    return $sql;
  }

  public function SelectCountryChangeCity($countrydata)
  {
    // dd($countrydata);
    // $sql = DB::select("
    // SELECT Country , City FROM tbHotels WHERE City != '' AND Country = '$country'
    //     GROUP BY City , Country
    //     ORDER BY City ASC
    // ");
    $sql = DB::select("SELECT CityId
            ,City
        FROM    dbo.tbCity
        JOIN 	dbo.tbCountry ON dbo.tbCity.CountryId = dbo.tbCountry.CountryId
        WHERE tbCountry.CountryDesc = '$countrydata'
            order by City asc
    ");
    return $sql;
  }

}
