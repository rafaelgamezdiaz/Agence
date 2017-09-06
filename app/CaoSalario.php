<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaoSalario extends Model
{
    protected $table = 'cao_salario';
    protected $fillable = ['co_usuario',  'dt_alteracao',  'brut_salario',  'liq_salario'];
 }
