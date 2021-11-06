<?php

namespace App\Models;

use CodeIgniter\Model;

class PemilihCapresModel extends Model {

    protected $table = 'pemilih_capres';
    protected $primaryKey = 'token';

    protected $returnType = 'App\Entities\PemilihCapres';

}

