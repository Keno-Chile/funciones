function HabilesEntreFechas($inicio, $fin)
{
    if (!empty($inicio)) {
        $diasp1 = time() - filemtime(public_path('feriados.json'));
        $diasp2 = 86400;
        $dias = $diasp1 / $diasp2 ;
        $dias = abs($dias);
        $dias = floor($dias);
        $final = empty($fin)?date('Y-m-d'):$fin;
        if ($dias > 3) {
            $cont = file_get_contents("https://apis.digital.gob.cl/fl/feriados/".date('Y'));
            $fl = fopen(public_path('feriados.json'), 'w+');
            fwrite($fl, $cont);
            fclose($fl);
        }
        $start = new DateTime($inicio);
        $end = new DateTime($final);
        $interval = $end->diff($start);

        // total dias
        $days = $interval->days;

        // crea un período de fecha iterable (P1D equivale a 1 día)
        $period = new DatePeriod($start, new DateInterval('P1D'), $end);

        // almacenado como array, por lo que puede agregar más de una fecha,
        // los datos estan tomados desde un servicio oficial del gobierno
        $feriados = array();
        $file_f = file_get_contents('feriados.json');
        $feriados_full = json_decode($file_f);
        foreach ($feriados_full as $obj => $datos) {
            $feriados[] = $datos->fecha;
        }

        foreach ($period as $dt) {
            $curr = $dt->format('D');
            // obtiene si es Sábado o Domingo
            if ($curr === 'Sat' || $curr === 'Sun' || (in_array($dt->format('Y-m-d'), $feriados, true))) {
                $days--;
            }
        }
    return $days;
    }
    return 0;
}
