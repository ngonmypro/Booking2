<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Country extends Model
{
  protected $table = 'Country';
  protected $primaryKey = 'Country.CountryID';

}
