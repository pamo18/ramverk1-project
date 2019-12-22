<?php

namespace Pamo\Question;

use Pamo\MyActiveRecord\MyActiveRecord;

/**
 * A database driven model using the Active Record design pattern.
 */
class Question extends MyActiveRecord
{
    /**
     * @var string $tableName name of the database table.
     */
protected $tableName = "Question";



    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $title;
    public $user;
    public $text;
    public $vote;
    public $accepted;
}
