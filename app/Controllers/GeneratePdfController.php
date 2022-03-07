<?php


namespace App\Controllers;

use CodeIgniter\Controller;

class GeneratePdfController extends Controller
{


    public function printCert()
    {
        var_dump('Cool Pdf Stuff');
        $this->load->library('pdf');
    }

}