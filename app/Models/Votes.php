<?php namespace BFoxwell\Votifier\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Votes extends Model
{
	use SoftDeletingTrait;

	protected $table = 'votes';

	protected $guarded = [];

	protected $dates = ['deleted_at'];

	public $timestamps = false;
} 