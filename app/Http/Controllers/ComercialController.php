<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\CaoUsuario;
use App\CaoSalario;
use View;

class ComercialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = null)
    {
        if ($id == null) {
            $consultores = DB::table('cao_usuario')->join('permissao_sistema','cao_usuario.co_usuario','=','permissao_sistema.co_usuario')
                                                   ->where('co_sistema',1)
                                                   ->where('in_ativo','S')
                                                   ->whereBetween('co_tipo_usuario',[0,2])
                                                   ->get();
            return view('comercial.index', compact('consultores'));
        }else{
            return $this->show($id);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Muestra los resultados de Relatorio para los consultores
     */
    public function relatorio(Request $request)
    {
        if ($request->ajax()) { 

            /* CONSULTORES */
            $consultores = [];

            /* INICIALIZAMOS LOS ARRAYS QUE CONTENDRAN LOS CALORES PARA CADA CONSUTOR */
            // ejemplo $receita_liquida['juan'] = [valos_mes1 , valos_mes2 , valos_mes3 , valos_mes4 ];
            // $receita_liquida = [
            //                     ['juan'  => [valos_mes1 , valos_mes2 , valos_mes3 , valos_mes4 ]],
            //                     ['maria' => [valos_mes1 , valos_mes2 , valos_mes3 , valos_mes4 ]],
            //                     ['clara' => [valos_mes1 , valos_mes2 , valos_mes3 , valos_mes4 ]]
            // 
            //                   ]
            $receita_liquida = [];      // Receita liquida de todos los consultores para cada mes
            $receita_liquida_meses = [];  // Receita liquida para todos los meses, se inicializara para cada consultor
            $custofixo = [];            // Custofixo de todos los consultores para cada mes
            $custofixo_meses = [];        // Custofixo para todos los meses, se inicializara para cada consultor
            $comissao = [];             // Comissao de todos los consultores para cada mes
            $comissao_meses = [];         // Comissao para todos los meses, se inicializara para cada consultor
            $lucro = [];
            $lucro_meses = [];

            /* Array de string, para la lista de meses en el intervalo considerado */
            $meses = []; 
            $flag_meses = 1; // Se utiliza para obtener el conjunto de meses, solo con el primer consultor, pues el intervalo de meses es comun para todos


            /* FECHA INICIAL */
            /* Considero la fecha inical contando a partir del dia 1 del mes inicial seleccionado $request->fecha */
            $fecha_inicial = explode('/',$request->fecha);
            $mes0 = $fecha_inicial[0];
            $anno0 = $fecha_inicial[1];
            $dia0 = '01';
            $d0 = 1;
            $mes = $mes0;
            $anno = $anno0;
            $dia = $dia0;
            $d = $d0;

            $fival = (($d/31)+$mes)/12+$anno; // valor comparativo de la fecha inicial
        
            /* FECHA FINAL */
            /* Considero la fecha inical como el ultimo dia del mes final seleccionado $request->fechafin */
            // En el  while que hay en el ciclo for se compara siempre con ffval que es la fecha tope
            $fecha_final = explode('/',$request->fechafin);
            $mesf = $fecha_final[0]; // mes final 
            $annof = $fecha_final[1]; // anno final
            $df = intval($this->diaf($mesf)); // dia final
            $ffval = (($df/31)+$mesf)/12+$annof; // valor comparativo de la fecha final, es fijo
        
            foreach ($request->lista as $nombre) { // Recorremos la lista de los consultores
        
                // Inicializamos $fi_mes para cada consultor de la lista, ya que su valor cambia en el ciclo while de abajo
                // $fi_mes y $ff_mes, se utilizan en la busqueda en la base de datos
                $fi_mes = $anno.'/'.$mes.'/'.$dia; 
                $ff_mes = $anno.'/'.$mes.'/'.$this->diaf($mes); 
                
                $resultados = []; // Este array contendra los valores de receita liquida, custo fixo y comissao
                
                $fival = (($d/31)+$mes)/12+$anno; // valor comparativo de la fecha inicial
                
                /* Ciclo para recorrer cada mes del intervalo seleccionado, se ejecutara un ciclo por cada consultor */
                /* $ffval es la cota maxima de la fecha */
        
                while ($fival <= $ffval) {
                    
                    // Se calcula la receita liquida para el consultor en el mes que se esta procesando
                    $resultados = $this->calculos($nombre, $fi_mes,$ff_mes);
                    
                    /* RECEITA LIQUIDA */
                    $receita_liquida_meses[] = $resultados[0];

                    /* COMISSAO */
                    $comissao_meses[] = $resultados[1];

                    /* CUSTO FIXO */
                    $custofixo_meses[] = $resultados[2];

                    /* LUCRO */
                    $lucro_meses[] = $resultados[3];

                    // Se va guargando la lista de los meses, solo es verdadero el condicional al recorrer el primer consultor
                    if ($flag_meses == 1) { // 
                        $meses[] = $this->fecha_texto($mes).' de '.$anno; // Fecha inicial en formato texto para imprimir en la tabla de resultados 
                    }
                    /* Incrementamos el mes en 1 para calcular en el mes siguiente */
                    $mes++ ;
                    if ($mes == 13) {
                        $mes = 1;
                        $anno++;
                    }
                    $fi_mes = $anno.'/'.$mes.'/'.$dia; 
                    $ff_mes = $anno.'/'.$mes.'/'.$this->diaf($mes); 

                    $fival = (($dia/31)+$mes)/12+$anno; // Se calcula el nuevo fival, que se utiliza en el ciclo while
                }
                // Almacenamos los valores del actual consultor
                $receita_liquida[$nombre] = $receita_liquida_meses;
                $custofixo[$nombre] = $custofixo_meses;
                $comissao[$nombre] = $comissao_meses;
                $lucro[$nombre] = $lucro_meses;

                // Reninicializamos para el sigueinte consultor
                $receita_liquida_meses = []; 
                $custofixo_meses = []; 
                $comissao_meses = []; 
                $lucro_meses = []; 

                $flag_meses = 0; // Despues de crear la lista con el primer consultor se pasa a 0 el marcador
                $consultores[] = CaoUsuario::where('co_usuario','=',$nombre)->get();

                /* Reiniciamos los parametros de la fecha para realizar los calculos con el siguiente consultor */
                $mes = $mes0;
                $anno = $anno0;
                $dia = $dia0;
                $d = $d0;
            }
            return json_encode(['consultores'           => $consultores,
                                'receita_liquida'       => $receita_liquida, 
                                'meses'                 => $meses, 
                                'custofixo'             => $custofixo,
                                'comissao'              => $comissao,
                                'lucro'                 => $lucro
                                ]);
        }
    }

    

    /* Funcion para determinar los valores de las barras del Grafico */
    public function grafico(Request $request)
    {
        if ($request->ajax()) {  

            /* Consultores */
            $consultores = [];
            $receita_liquida_completa = [];
            $meses = [];
            $maxy = 0;

            /* Fecha inicial */
            /* Considero la fecha inical contando a partir del dia 1 del mes seleccionado */
            $fecha_inicial = explode('/',$request->fecha);
            $mes = $fecha_inicial[0];
            $anno = $fecha_inicial[1];
            $dia = '01';
            $d = 1;

            /* Fecha final */
            /* Considero la fecha final hasta el ultimo dia del mes seleccionado */
            $fecha_final = explode('/',$request->fechafin);
            $mesf = $fecha_final[0]; // mes final 
            $annof = $fecha_final[1]; // anno final
            $df = intval($this->diaf($mesf)); // dia final

            $flag_meses = 1; // Se utiliza para obtener el conjunto de meses, solo con el primer consultor, pues el intervalo de meses es comun para todos

            $costo_medio = 0;

            foreach ($request->lista as $nombre) {
                $receita_liquida = []; // Para almacenar la de cada consultor por mes
                $finicio = $anno.'/'.$mes.'/'.$dia; // Se utiliza en la busqueda en la base de datso
                $fival = (($d/31)+$mes)/12+$anno; // valor comparativo de la fecha inicial
                $ffval = (($df/31)+$mesf)/12+$annof; // valor comparativo de la fecha final

                $dia_temp1 = intval($this->diaf($mes));

                /* Fecha inicio mas 1 mes */
                $mes_temp = $mes + 1;
                $anno_temp = $anno;
                if ($mes_temp == 13) {
                    $mes_temp = 1;
                    $anno_temp = $anno + 1;
                }
                $dia_temp = intval($this->diaf($mes_temp));


                $finicio = $anno.'/'.$mes.'/'.$dia; // Se utiliza en la busqueda en la base de datos   
                $ff_temp = $anno_temp.'/'.$mes_temp.'/'.$this->diaf($mes_temp); // Este es el utimo dia del mes temporal, que se cuenta a partir de la fecha inicial, y se va incrementando en 1
                $m = $mes;
                $c = 0;
                $costo_medio_temp = 0;
                while ($fival <= $ffval) {

                    // Se calcula la receita liquida para el consultor en el mes que se esta procesando
                    $c = $this->receita_liquida($nombre, $finicio,$ff_temp);
                    $receita_liquida[] = $c;
                    $costo_medio_temp += $c;
                    if ($flag_meses == 1) { // 
                        $meses[] = $this->fecha_texto($m);
                        $m++;
                        if ($m == 13) {
                            $m = 1;
                        }
                    }
                    
                    /* Ahora finicio es la del mes consecutivo */
                    $finicio = $anno_temp.'/'.$mes_temp.'/'.$dia_temp; // Ahora finicio se determina de los valores temporales anteriores
                    $fival = (($dia_temp/31)+$mes_temp)/12+$anno_temp; // Se calcula el nuevo fival
                    
                    /* Determinamos el maximo costo para obtener los rangos del eje y (util para el grafico del costo medio)*/
                    if ($c > $maxy) {
                        $maxy = $c;
                    }

                    /* Fecha inicio mas 1 mes (se vuelve a sumar 1 mes mas)*/
                    $mes_temp++ ;
                    if ($mes_temp == 13) {
                        $mes_temp = 1;
                        $anno_temp++;
                    }
                    $dia_temp = intval($this->diaf($mes_temp));
                    $ff_temp = $anno_temp.'/'.$mes_temp.'/'.$dia_temp; // ff_temp siempre es la suma de finicio + 1 mes
                }
                $flag_meses = 0;
                $costo_medio += $costo_medio_temp/count($receita_liquida);

                /* CONSULTORES */
                /* Lista de consultores de los consultores */
                $consultores[] = CaoUsuario::where('co_usuario','=',$nombre)->get();
                
                $receita_liquida_completa[$nombre] = $receita_liquida;
            }
            
            $costo_medio = $costo_medio/count($receita_liquida_completa);
            
            return json_encode(['consultores'           => $consultores, 
                                'receita_liquida'       => $receita_liquida_completa, 
                                'meses'                 => $meses,
                                'costo_medio'           => $costo_medio,
                                'maxy'                  => $maxy]);
        }
    }

    /* Funcion para determinar los valores de las barras del Grafico */
    public function pizza(Request $request)
    {
        if ($request->ajax()) { 

            /* Consultores */
            $consultores = [];
            $receita_liquida_completa = [];

            /* Fecha inicial */
            /* Considero la fecha inical contando a partir del dia 1 del mes seleccionado */
            $fecha_inicial = explode('/',$request->fecha);
            $mes = $fecha_inicial[0];
            $anno = $fecha_inicial[1];
            $dia = '01';
            $d = 1;
            $finicio = $anno.'/'.$mes.'/'.$dia; // Se utiliza en la busqueda en la base de datos

            /* Fecha final */
            /* Considero la fecha final hasta el ultimo dia del mes seleccionado */
            $fecha_final = explode('/',$request->fechafin);
            $mesf = $fecha_final[0]; // mes final 
            $annof = $fecha_final[1]; // anno final
            $df = (string) $this->diaf($mesf); // dia final
            $ffin = $annof.'/'.$mesf.'/'.$df; // Se utiliza en la busqueda en la base de datos

            $receita_liquida = []; // Para almacenar la de cada consultor por mes
            $rl = [];
            $rln = [];
            foreach ($request->lista as $nombre) {
                $c = $this->calculos($nombre, $finicio,$ffin);
                $receita_liquida[$nombre] = $c[0];
                $rl[] = $c[0];
                $rln[] = $nombre;

                /* CONSULTORES */
                /* Lista de consultores de los consultores */
                $consultores[] = CaoUsuario::where('co_usuario','=',$nombre)->get();
            }

            return json_encode(['consultores'           => $consultores, 
                                'receita_liquida'       => $receita_liquida,
                                'rl'       => $rl,
                                'rln'       => $rln,
                                'ffinal' => $finicio]);
        }
    }

    private function calculos($nombre, $finicio, $ffin){
        /* RECEITA LIQUIDA */
        /* Valor: de la tabla cao_fatura, se determinan de un "INNER JOIN" entre las
        tablas cao_os y cao_fatura  */
        /* En $valores obtenemos lo que se tiene de ganancias en el intervalo de tiempo seleccionado  */
        $valores = DB::table('cao_os')->join('cao_fatura','cao_os.co_os','=','cao_fatura.co_os')
                                        ->where('cao_os.co_usuario','=',$nombre)
                                        //->where('cao_os.co_sistema','=','cao_fatura.co_sistema')
                                        ->whereDate('cao_fatura.data_emissao','>=',$finicio)
                                        ->whereDate('cao_fatura.data_emissao','<',$ffin)
                                        ->get();
        /* Calculamos la receita liquida (ganancias netas) tomando en cuenta que tenemos que restarle el valor de total_imp_inc */
        /* Para el total antes de la fecha inicial */
        $receita_liquida_temp = 0;
        $comissao_temp = 0;

        /* RECEITA LIQUIDA & COMISSAO */
        if (count($valores) > 0) {
            foreach ($valores as $v) {
                $receita_liquida_temp += $v->valor - (($v->total_imp_inc)/100)*($v->valor);
                $comissao_temp += $receita_liquida_temp*($v->comissao_cn)/100;
            }
        }
                        
        /* CUSTO FIXO */
        $valorsalario = CaoSalario::where('co_usuario','=',$nombre)->get();

        if (count($valorsalario) > 0) {
            $custofixo = $valorsalario[0]->brut_salario;
        }else{
            $custofixo = 0;
        }
        $lucro = $receita_liquida_temp - ($custofixo + $comissao_temp);

        return [$receita_liquida_temp, $comissao_temp, $custofixo, $lucro];
    }

    private function receita_liquida($nombre, $finicio, $ffin){
        /* RECEITA LIQUIDA */
        /* Valor: de la tabla cao_fatura, se determinan de un "INNER JOIN" entre las
        tablas cao_os y cao_fatura  */
        /* En $valores obtenemos lo que se tiene de ganancias en el intervalo de tiempo seleccionado  */
        $valores = DB::table('cao_os')->join('cao_fatura','cao_os.co_os','=','cao_fatura.co_os')
                                        ->where('cao_os.co_usuario','=',$nombre)
                                        //->where('cao_os.co_sistema','=','cao_fatura.co_sistema')
                                        ->whereDate('cao_fatura.data_emissao','>=',$finicio)
                                        ->whereDate('cao_fatura.data_emissao','<',$ffin)
                                        ->get();
        /* Calculamos la receita liquida (ganancias netas) tomando en cuenta que tenemos que restarle el valor de total_imp_inc */
        /* Para el total antes de la fecha inicial */
        $total_temp = 0;
        if (count($valores) > 0) {
            foreach ($valores as $v) {
                $total_temp += $v->valor - (($v->total_imp_inc)/100)*($v->valor);
            }
        }
        return $total_temp;
    }

    private function diaf($mes){
        if ($mes == '01' || $mes == '03' || $mes == '05' || $mes == '07' || $mes == '08' || $mes == '10' || $mes == '12') {
            return 31;
        }elseif ($mes == '04' || $mes == '06' || $mes == '09' || $mes == '11') {
            return 30;
        }elseif($mes == '02'){
            return 28;
        }
    }

    private function fecha_texto($mes){
        $fecha = '';
        switch ($mes) {
                case 1:
                    $fecha = "Jan"; // Janeiro
                    break;
                case 2:
                    $fecha = "Fev"; //Fevereiro
                    break;
                case 3:
                    $fecha = "Mar"; // Março
                    break;
                case 4:
                    $fecha = "Abr"; // Abril
                    break;
                case 5:
                    $fecha = "Mai"; // Maio
                    break;
                case 6:
                    $fecha = "Jun"; // Junho
                    break;
                case 7:
                    $fecha = "Jul"; // Julho
                    break;
                case 8:
                    $fecha = "Ago"; // Agosto
                    break;
                case 9:
                    $fecha = "Set"; // Setembro
                    break;
                case 10:
                    $fecha = "Out"; // Outubro
                    break;
                case 11:
                    $fecha = "Nov"; // Novembro
                    break;
                case 12:
                    $fecha = "Dez"; // Dezembro
                    break;
            }
            return $fecha;
    }
}
