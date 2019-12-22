<?php

namespace Pamo\Tag;

use Pamo\MyActiveRecord\MyActiveRecord;

/**
 * A database driven model using the Active Record design pattern.
 */
class TagQuestion extends MyActiveRecord
{
    /**
     * @var string $tableName name of the database table.
     */
protected $tableName = "Tag2Question";



    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $questionid;
    public $tagname;
}
