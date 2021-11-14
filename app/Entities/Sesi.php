<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Sesi extends Entity {

    protected $datetimeFormat = 'Y-m-d H:i';

    protected $datamap = [
        'ID' => 'id',
        'Nama' => 'nama',
        'WaktuBuka' => 'waktu_buka',
        'WaktuTutup' => 'waktu_tutup'
    ];

    public function getWaktuBukaString() {
        return date($this->datetimeFormat, $this->WaktuBuka);
    }

    public function setWaktuBukaString($waktuBukaString) {
        $this->WaktuBuka = @\DateTime::createFromFormat($this->datetimeFormat, $waktuBukaString)->getTimestamp();
        return $this->WaktuBuka !== NULL;
    }

    public function getWaktuTutupString() {
        return date($this->datetimeFormat, $this->WaktuTutup);
    }

    public function setWaktuTutupString($waktuTutupString) {
        $this->WaktuTutup = @\DateTime::createFromFormat($this->datetimeFormat, $waktuTutupString)->getTimestamp();
        return $this->WaktuTutup !== NULL;
    }

}

