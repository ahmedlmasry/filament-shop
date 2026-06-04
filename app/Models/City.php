<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class City
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class City extends Model
{
	use HasFactory;
	protected $fillable = [
		'name',
		'governorate_id',
		'shipping_cost'
	];
	public function governorate()
	{
		return $this->belongsTo(Governorate::class);
	}
}
