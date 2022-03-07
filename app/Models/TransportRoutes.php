<?php


namespace App\Models;


use CodeIgniter\Model;

class TransportRoutes extends Model
{
    protected $table = 'transport_routes';
    protected $primaryKey = 'id';

    protected $allowedFields = ['route', 'price', 'active', 'driver_name', 'driver_phone', 'licence_plate'];

    protected $returnType = '\App\Entities\TransportRoute';
}