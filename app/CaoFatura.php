<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaoFatura extends Model
{
    protected $table = 'cao_fatura';
    protected $fillable = ['co_fatura',  'co_cliente',  'co_sistema',  'co_os',  'num_nf',  'total',  'valor',  'data_emissao',  'corpo_nf',  'comissao_cn',  'total_imp_inc'];
}
