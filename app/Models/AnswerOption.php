<?php

namespace App\Models;

use CodeIgniter\Model;

class AnswerOption extends Model
{
    protected $table = 'answer_options';
    protected $primaryKey = 'id';

    protected $allowedFields = ['name'];
}