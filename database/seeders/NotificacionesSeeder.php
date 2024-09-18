<?php

namespace Database\Seeders;

use App\Models\Notificaciones;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            ["descripcion" => "Entrevista de Primer Contacto realizada a la víctima: {name}", "tipo" => "PRIMER CONTACTO","rol" => "Nivel Superior","area" => "Primer Contacto","etiqueta" => "","etiqueta" => "lleno_entrevista_pc"],
            ["descripcion" => "Se solicito asesoría juridica", "tipo" => "PRIMER CONTACTO","rol" => "Nivel Superior","area" => "Juridico","etiqueta" => "solicito_asesoria"],
            ["descripcion" => "Se te asigno como Jurídico para víctima: {name}","tipo" => "PRIMER CONTACTO","rol" => "Operativo","area" => "Juridico","etiqueta" => "solicito_asesoria_asignado"],
            ["descripcion" => "Se solicito atención psicologica", "tipo" => "PRIMER CONTACTO","rol" => "Nivel Superior","area" => "Primer Contacto","etiqueta" => "solicito_psicologo"],
            ["descripcion" => "Se te asigno como Psicólogo para víctima: {name}", "tipo" => "PRIMER CONTACTO","rol" => "Operativo","area" => "Primer Contacto","etiqueta" => "solicito_psicologo_asignado"],
            ["descripcion" => "Se solicito registro para REV", "tipo" => "PRIMER CONTACTO","rol" => "Nivel Superior","area" => "Registro Estatal de Víctimas","etiqueta" => "solicito_registro_rev_superior"],
            ["descripcion" => "Se solicito registro para REV", "tipo" => "PRIMER CONTACTO","rol" => "Operativo","area" => "Registro Estatal de Víctimas","etiqueta" => "solicito_registro_rev_operativo"],
            ["descripcion" => "REV agrego víctima con estatus REV sin Primer Contacto para víctima: {name}", "tipo" => "REV","rol" => "Nivel Superior","area" => "Registro Estatal de Víctimas","etiqueta" => "llena_formulario_rev"],
            ["descripcion" => "REV agrego víctima con estatus REV sin Primer Contacto para víctima: {name}", "tipo" => "REV","rol" => "Nivel Superior","area" => "Registro Estatal de Víctimas","etiqueta" => "llena_formulario_rev_registro"],
            ["descripcion" => "REV aprobado solicitud de víctima: {name}", "tipo" => "REV","rol" => "Operativo ","area" => "Registro Estatal de Víctimas","etiqueta" => "asigno_estatus_rev"],
            ["descripcion" => "REV aprobado solicitud sin Primer Contacto de víctima: {name}", "tipo" => "REV","rol" => "Operativo ","area" => "Registro Estatal de Víctimas","etiqueta" => "asigno_estatus_rev_sin_pc_operativo"],
            ["descripcion" => "REV aprobado solicitud sin Primer Contacto de víctima: {name}", "tipo" => "REV","rol" => "Nivel Superior","area" => "Primer Contacto","etiqueta" => "asigno_estatus_rev_sin_pc_superior"],
            ["descripcion" => "REV agrego folio o cambio estatus de RENAVI a la víctima: {name}", "tipo" => "RENAVI","rol" => "Nivel Superior","area" => "Registro Estatal de Víctimas","etiqueta" => "asigno_status_renavi"]
        ];        

        foreach($datos as $dato)
            Notificaciones::create($dato);
    }
}
