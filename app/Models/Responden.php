<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Responden extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Auditable, HasFactory;

    public $table = 'respondens';

    public const PERGERAKAN_RADIO = [
        '1' => 'ya',
        '2' => 'tidak',
    ];

    public const HIS_ADEKUAT_RADIO = [
        '1' => 'ya',
        '2' => 'tidak',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $attributes = [
        'his_adekuat' => '0', 
        'pergerakan' => '0',
    ];

    protected $fillable = [
        'nama',
        'kode',
        'usia',
        'his_adekuat',
        'pergerakan',
        'paritas',
        'kardiotokografi',
        'alamat',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function respondenIotReadings()
    {
        return $this->hasMany(IotReading::class, 'responden_id', 'id');
    }

    public function respondenSmReadings()
    {
        return $this->hasMany(SmReading::class, 'responden_id', 'id');
    }
}
