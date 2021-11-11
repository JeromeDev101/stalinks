<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class GenerateList extends Model
{
    use Loggable;

    protected $table = 'generate_list';

    protected $guarded = [];

    protected $appends = [
        'code1_numeral_value',
        'code2_numeral_value',
        'code3_numeral_value',
        'code4_numeral_value',
    ];

    public function getCode1NumeralValueAttribute() {
        return $this->getNumeralValue($this->code_1);
    }

    public function getCode2NumeralValueAttribute() {
        return $this->getNumeralValue($this->code_2);
    }

    public function getCode3NumeralValueAttribute() {
        return $this->getNumeralValue($this->code_3);
    }

    public function getCode4NumeralValueAttribute() {
        return $this->getNumeralValue($this->code_4);
    }

    protected function getNumeralValue($code) {
        if ($code === 'A') {
            return 1;
        } else if($code === 'B') {
            return 2;
        } else if($code === 'C') {
            return 3;
        } else if($code === 'D') {
            return 4;
        } else {
            return 5;
        }
    }
}
