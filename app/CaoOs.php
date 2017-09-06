<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaoOs extends Model
{
    protected $table = 'cao_os';
    protected $fillable = ['co_os',  'nu_os',  'co_sistema',  'co_usuario',  'co_arquitetura',  'ds_os',  'ds_caracteristica',  'ds_requisito',  'dt_inicio',  'dt_fim',  'co_status',  'diretoria_sol',  'dt_sol',  'nu_tel_sol',  'ddd_tel_sol',  'nu_tel_sol2',  'ddd_tel_sol2',  'usuario_sol',  'dt_imp',  'dt_garantia',  'co_email',  'co_os_prospect_rel'];
}
