<?php

namespace Pamo\Tag;

use Pamo\MyActiveRecord\MyActiveRecord;

/**
 * A database driven model using the Active Record design pattern.
 */
class Tag extends MyActiveRecord
{
    /**
     * @var string $tableName name of the database table.
     */
protected $tableName = "Tag";



    /**
     * Columns in the table.
     *
     * @var integer $name primary key.
     */
    public $name;
}
