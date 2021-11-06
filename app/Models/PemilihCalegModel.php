<?php

namespace App\Models;

use CodeIgniter\Model;

class PemilihCalegModel extends Model {

    protected $table = 'pemilih_caleg';
    protected $primaryKey = 'token';

    protected $returnType = 'App\Entities\PemilihCaleg';

}

