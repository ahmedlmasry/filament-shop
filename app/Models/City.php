<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
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
	protected $table = 'cities';

	protected $fillable = [
		'name','governorate_id'
	];
    public function governorate()
{
    return $this->belongsTo(Governorate::class);
}

}
