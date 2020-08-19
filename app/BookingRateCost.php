<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class BookingRateCost extends Model
{
  protected $table = 'BookingRateCost';
  protected $primaryKey = 'BookingRateCostId';

  public function getConfirmPrice($FBIdUniqueId)
  {
    $sql = DB::select("SELECT
                    CASE WHEN ISNULL(b.RateAdjustment, 0) > 0
								THEN
								  CEILING(ISNULL(b.RateAdjustment / ISNULL(brc.CurrencyRate, 1), 0))
							  ELSE
								CASE WHEN ISNULL(b.CXLFee, 0) > 0
								  THEN
									CEILING(ISNULL(b.CXLFee / ISNULL(brc.CurrencyRate, 1), 0))
								ELSE
								  CASE WHEN ISNULL(b.Status, '') <> 'CXL' AND ISNULL(b.Status, '') <> 'RCX'
									THEN
									(
										ISNULL((brc.Price / brc.CurrencyRate), 0) *
										CASE WHEN ISNULL(brc.IsPax, 0) = 0 THEN
											CASE WHEN brc.RepeatPax > 0 AND ISNULL(brc.Pax,0) > 0 THEN CEILING((brc.Pax / (brc.RepeatPax*1.00)))
											ELSE 1 * CASE WHEN ISNULL(brc.NoOfUse,0) > 1 THEN brc.NoOfUse ELSE 1 END END
										ELSE
											ISNULL(brc.Pax, 0)
										END
									)
									+ (CAST(ISNULL(b.ExtraCostUS, '0') AS MONEY) - CAST(ISNULL(b.ReductionUS, 0) AS MONEY))
								  ELSE 0 END
								END
							  END AS cost,

                        brc.Currency,
					    brc.Price,
					    brc.CurrencyRate,
					    CEILING(brc.Price/brc.CurrencyRate) AS PricePP,
					    brc.Pax ,
                       ISNULL(b.ReductionUS,0) as ReductionUS,
                       ISNULL(b.ExtraCostUS,0) as ExtraCostUS

				FROM dbo.BookingRateCost AS brc
                    INNER JOIN dbo.tbFlightBookings AS b ON  brc.ReferanceId = b.FBIdUniqueId
				WHERE brc.ReferanceId = '$FBIdUniqueId'
					   AND brc.CostTypeId  = ( SELECT MAX(CostTypeId)
								 FROM  dbo.BookingRateCost AS c
								 WHERE  c.ReferanceId =  b.FBIdUniqueId
								        AND CostTypeId IN (3,4)
					 )
    ");
      return $sql;
  }
}
