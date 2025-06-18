<?php

namespace App\Models\Generator;

use App\Models\Model;

class RandomObject extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text', 'link', 'random_generator_id',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'random_objects';
    /**
     * The primary key of the model.
     *
     * @var string
     */
    public $primaryKey = 'id';

    /**
     * Validation rules for character profile updating.
     *
     * @var array
     */
    public static $rules = [
        'text'                => 'required',
        'random_generator_id' => 'required',
    ];

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get the generator this object belongs to.
     */
    public function generator() {
        return $this->belongsTo(RandomGenerator::class, 'random_generator_id');
    }

    /**
     * Gets the model and displays it as a link if there is one.
     *
     * @return string
     */
    public function getDisplayName() {
        if ($this->link) {
            return '<a href="'.$this->link.'">'.$this->value.'</a>';
        } else {
            return $this->value;
        }

        return url('generators/'.$this->id);
    }
}
