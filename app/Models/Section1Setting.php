<?php


namespace App\Models;


class Section1Setting extends \CodeIgniter\Model
{
    protected $table = 'section1_settings';

    protected $primaryKey = 'id';

    protected $allowedFields = ['welcome_message','website_pictures','website_logo','website_address','student_description','student_required_docs','teacher_description','teacher_information','contact_logo1',
        'contact_logo2','contact_address1','contact_address2','map_address','social_links'];
}