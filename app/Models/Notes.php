<?php


namespace App\Models;


use CodeIgniter\Model;

class Notes extends Model
{
    protected $table = 'notes';
    protected $primaryKey = 'id';

    protected $returnType = 'App\Entities\Note';

    protected $allowedFields = ['name', 'class', 'subject', 'description', 'file','is_elibrary','book_type']; //TODO: not actually a todo, removed 'section' from array

}