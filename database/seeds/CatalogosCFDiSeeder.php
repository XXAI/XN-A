<?php

use Illuminate\Database\Seeder;

class CatalogosCFDiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $archivos = [
            "cfdi_tipos_nomina" => "tipos_nomina.csv",
            "cfdi_tipos_contrato" => "tipos_contrato.csv",
            "cfdi_periodicidades_pago" => "periodicidades_pago.csv",
            "cfdi_origenes_recursos" => "origenes_recursos.csv",
            "cfdi_tipos_jornada" => "tipos_jornada.csv",
            "cfdi_tipos_regimen" => "tipos_regimen.csv",
            "cfdi_riesgos_puesto" => "riesgos_puesto.csv",
            "cfdi_tipos_percepcion" => "tipos_percepcion.csv",
            "cfdi_tipos_deduccion" => "tipos_deduccion.csv",
            "cfdi_tipos_horas_extra" => "tipos_horas_extra.csv",
            "cfdi_tipos_incapacidad" => "tipos_incapacidad.csv",
            "cfdi_tipos_otro_pago" => "tipos_otro_pago.csv",
            "cfdi_entidades" => "entidades.csv",
            "cfdi_bancos" => "bancos.csv"
        ];

        foreach($archivos as $tabla => $archivo){
            $archivo_csv = storage_path().'/app/seeds/cfdi/'.$archivo;
            $query = sprintf("
                LOAD DATA local INFILE '%s' 
                INTO TABLE ".$tabla."
                FIELDS TERMINATED BY ',' 
                OPTIONALLY ENCLOSED BY '\"' 
                ESCAPED BY '\"' 
                LINES TERMINATED BY '\\n' 
                IGNORE 1 LINES", addslashes($archivo_csv));
            DB::connection()->getpdo()->exec($query); 
        }
    }
}
