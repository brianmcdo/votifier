<?php

use Phpmig\Migration\Migration;

class CreateVotesTable extends Migration
{
	protected $tableName;

	/* @var \Illuminate\Database\Schema\Builder $schema */
	protected $schema;

	public function init()
	{
		$this->tableName = 'votes';
		$this->schema = $this->get('schema');
	}

	/**
	 * Do the migration
	 */
	public function up()
	{
		/* @var \Illuminate\Database\Schema\Blueprint $table */
		$this->schema->create($this->tableName, function ($table)
		{
			$table->increments('id');
			$table->string('player');
			$table->string('ip');
			$table->string('service_name');
			$table->timestamp('voted_at');
			$table->softDeletes();
		});
	}

	/**
	 * Undo the migration
	 */
	public function down()
	{
		$this->schema->drop($this->tableName);
	}
}
