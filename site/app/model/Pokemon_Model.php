<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Pokemon_Model extends Model {

  protected $table = 'pokemons';

  protected $fillable = ['name', 'types'];

}
