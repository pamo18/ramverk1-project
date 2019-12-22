<?php

namespace Pamo\Comment;

use Pamo\MyActiveRecord\MyActiveRecord;

/**
 * A database driven model using the Active Record design pattern.
 */
class CommentA extends MyActiveRecord
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "CommentAnswer";



    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $questionid;
    public $answerid;
    public $user;
    public $text;
    public $vote;
}
