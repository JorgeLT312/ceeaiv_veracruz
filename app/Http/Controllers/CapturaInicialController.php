<?php

namespace App\Http\Controllers;

use App\Models\TipoVictima;
use App\Models\VictimaDirecta;
use App\Models\VictimaIndirecta;
use App\Models\PrimerContacto;
use App\Models\CapturaInicial;
use App\Models\General;
use App\Models\HechosVictimizantes;
use App\Models\Solicitante;
use App\Models\DomicilioNotificaciones;
use App\Models\FactoresVulnerabilidades;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Enums\TipoVictimaEnum;
use Illuminate\Support\Facades\DB;
use Auth;


class CapturaInicialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $capturas=General::select('generales.*',
            'pc_entrevistas.gestion_medica',
            'pc_entrevistas.gestion_social',
            'pc_entrevistas.atencion_psicologica',
            'pc_entrevistas.asesoria_juridica',
            'pc_entrevistas.solicitudes_ayuda',
            'capturas_iniciales.contacto_principal_nombres',
            'capturas_iniciales.contacto_principal_primer_apellido',
            'capturas_iniciales.contacto_principal_segundo_apellido',
            'capturas_iniciales.contacto_principal_correo',
            'capturas_iniciales.contacto_principal_telefono',
            'capturas_iniciales.contacto_secundario_nombres',
            'capturas_iniciales.contacto_secundario_primer_apellido',
            'capturas_iniciales.contacto_secundario_segundo_apellido',
            'capturas_iniciales.contacto_secundario_correo',
            'capturas_iniciales.contacto_secundario_telefono',
            'capturas_iniciales.generales_id',
            'tipo_victimas.nombre as nombre_tipo_victima',
            'victimas_directas.nombres',
            'victimas_directas.primer_apellido',
            'victimas_directas.segundo_apellido',
            'victimas_indirectas.nombres as indirecta_nombres',
            'victimas_indirectas.primer_apellido as indirecta_primer_apellido',
            'victimas_indirectas.segundo_apellido as indirecta_segundo_apellido')
                ->join('capturas_iniciales', 'generales.id', '=', 'capturas_iniciales.generales_id')
                ->join('tipo_victimas', 'generales.tipo_victimas_id', '=', 'tipo_victimas.tipo_victimas_id')
                ->leftJoin('pc_entrevistas', 'generales.id', '=', 'pc_entrevistas.generales_id')
                ->leftJoin('victimas_indirectas', 'generales.id', '=', 'victimas_indirectas.generales_id')
                ->leftJoin('victimas_directas', 'generales.id', '=', 'victimas_directas.generales_id')
                ->when($request->search, function($query, $search) {
                    $query->where('generales.no_rev', 'like', "%$search%")
                    ->orWhere(DB::raw(
                            'CONCAT(TRIM(victimas_directas.nombres), " ", 
                                    TRIM(victimas_directas.primer_apellido), " ", 
                                    TRIM(victimas_directas.segundo_apellido))'), 'LIKE', "%$search%")
                        ->orWhere(DB::raw(
                            'CONCAT(TRIM(victimas_indirectas.nombres), " ", 
                                    TRIM(victimas_indirectas.primer_apellido), " ", 
                                    TRIM(victimas_indirectas.segundo_apellido))'), 'LIKE', "%$search%")
                    ->orWhere('victimas_directas.nombres', 'like', "%$search%")
                    ->orWhere('victimas_directas.primer_apellido', 'like', "%$search%")
                    ->orWhere('victimas_directas.segundo_apellido', 'like', "%$search%")
                    ->orWhere('victimas_indirectas.nombres', 'like', "%$search%")
                    ->orWhere('victimas_indirectas.primer_apellido', 'like', "%$search%")
                    ->orWhere('victimas_indirectas.segundo_apellido', 'like', "%$search%")
                    ->orWhere('tipo_victimas.nombre', 'like', "%$search%")
                    ->orWhere('capturas_iniciales.contacto_principal_nombres', 'like', "%$search%")
                    ->orWhere('capturas_iniciales.contacto_principal_primer_apellido', 'like', "%$search%")
                    ->orWhere('capturas_iniciales.contacto_principal_segundo_apellido', 'like', "%$search%");
                })
                ->latest()
                ->paginate()
                ->withQueryString();

        $tipoVictimas = TipoVictima::all();
        $filters = $request->only(['search']);

        return Inertia::render('CapturaInicial', compact('tipoVictimas', 'filters','capturas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombres' => 'required|max:150',
            'primer_apellido' => 'required|max:150',
            'segundo_apellido' => 'required|max:150',
            'tipo_victimas_id' => 'required',
            'contacto_principal_telefono' => 'sometimes|nullable|not_regex:/^([0-9])\1*$/|numeric|digits:10',
            'contacto_secundario_telefono' => 'sometimes|nullable|not_regex:/^([0-9])\1*$/|numeric|digits:10',
            'contacto_principal_correo' => 'sometimes|nullable|email',
            'contacto_secundario_correo' => 'sometimes|nullable|email',

            'contacto_principal_nombres' => 'sometimes|nullable|max:150',
            'contacto_principal_primer_apellido' => 'sometimes|nullable|max:150',
            'contacto_principal_segundo_apellido' => 'sometimes|nullable|max:150',
            'contacto_secundario_nombres' => 'sometimes|nullable|max:150',
            'contacto_secundario_primer_apellido' => 'sometimes|nullable|max:150',
            'contacto_secundario_segundo_apellido' => 'sometimes|nullable|max:150',
        ]);

        $general = new General;
        $general->no_rev = 'Sin registro';
        $general->estatus_atencion = 'Captura Inicial';
        $general->tipo_victimas_id = $request->tipo_victimas_id;
        $general->created_by =  Auth::id();
        $general->save();

        $solicitante = new Solicitante;
        $solicitante->nombres = $request->nombres;
        $solicitante->primer_apellido = $request->primer_apellido;
        $solicitante->segundo_apellido = $request->segundo_apellido;
        $solicitante->generales_id = $general->id;
        $solicitante->save();

        $hechos_victimizante = new HechosVictimizantes;
        $hechos_victimizante->generales_id = $general->id;
        $hechos_victimizante->save();

        $domicilio_notificaciones = new DomicilioNotificaciones;
        $domicilio_notificaciones->generales_id = $general->id;
        $domicilio_notificaciones->save();

        $factores_vulnerabilidad = new FactoresVulnerabilidades;
        $factores_vulnerabilidad->generales_id = $general->id;
        $factores_vulnerabilidad->save();

        $captura = new CapturaInicial;
        $captura->contacto_principal_nombres = $request->contacto_principal_nombres;
        $captura->contacto_principal_primer_apellido = $request->contacto_principal_primer_apellido;
        $captura->contacto_principal_segundo_apellido = $request->contacto_principal_segundo_apellido;
        $captura->contacto_principal_telefono = $request->contacto_principal_telefono;
        $captura->contacto_principal_correo = $request->contacto_principal_correo;
        $captura->contacto_secundario_nombres = $request->contacto_secundario_nombres;
        $captura->contacto_secundario_primer_apellido = $request->contacto_secundario_primer_apellido;
        $captura->contacto_secundario_segundo_apellido = $request->contacto_secundario_segundo_apellido;
        $captura->contacto_secundario_telefono = $request->contacto_secundario_telefono;
        $captura->contacto_secundario_correo = $request->contacto_secundario_correo;
        $captura->generales_id = $general->id;
        $captura->save();

        $pcEntrevista = new PrimerContacto;
        $pcEntrevista->generales_id = $general->id;
        $pcEntrevista->save();

        if ($request->tipo_victimas_id==TipoVictimaEnum::DIRECTA) {
            $victimaDirecta = new VictimaDirecta;
            $victimaDirecta->nombres = $request->nombres;
            $victimaDirecta->primer_apellido = $request->primer_apellido;
            $victimaDirecta->segundo_apellido = $request->segundo_apellido;
            $victimaDirecta->generales_id = $general->id;
            $victimaDirecta->save();

            CapturaInicial::where('generales_id', $general->id)
            ->update([
                'victimas_directas_id' => $victimaDirecta->victimas_directas_id,
            ]);
        }
        else{
            $victimaIndirecta = new VictimaIndirecta;
            $victimaIndirecta->nombres = $request->nombres;
            $victimaIndirecta->primer_apellido = $request->primer_apellido;
            $victimaIndirecta->segundo_apellido = $request->segundo_apellido;
            $victimaIndirecta->generales_id = $general->id;
            $victimaIndirecta->save();

            /*$captura->victimas_indirectas_id = $victimaIndirecta->victimas_indirectas_id;
            $captura->save();*/
            CapturaInicial::where('generales_id', $general->id)
            ->update([
                'victimas_indirectas_id' => $victimaIndirecta->victimas_indirectas_id,
            ]);
        }

        return to_route('captura.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $capturas=General::select('generales.*', 'capturas_iniciales.*')
                ->join('capturas_iniciales', 'generales.id', '=', 'capturas_iniciales.generales_id')
                ->find($id);
        $tipoVictimas = TipoVictima::all();
        $filters = $request->only(['search']);

        return Inertia::render('CapturaInicial', compact('tipoVictimas', 'filters','capturas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombres' => 'required|max:150',
            'primer_apellido' => 'required|max:150',
            'segundo_apellido' => 'required|max:150',
            'tipo_victimas_id' => 'required',
            'contacto_principal_telefono' => 'sometimes|nullable|not_regex:/^([0-9])\1*$/|numeric|digits:10',
            'contacto_secundario_telefono' => 'sometimes|nullable|not_regex:/^([0-9])\1*$/|numeric|digits:10',
            'contacto_principal_correo' => 'sometimes|nullable|email',
            'contacto_secundario_correo' => 'sometimes|nullable|email',
            'contacto_principal_nombres' => 'sometimes|nullable|max:150',
            'contacto_principal_primer_apellido' => 'sometimes|nullable|max:150',
            'contacto_principal_segundo_apellido' => 'sometimes|nullable|max:150',
            'contacto_secundario_nombres' => 'sometimes|nullable|max:150',
            'contacto_secundario_primer_apellido' => 'sometimes|nullable|max:150',
            'contacto_secundario_segundo_apellido' => 'sometimes|nullable|max:150',
        ]);

        $general = General::find($id);
        $general->tipo_victimas_id = $request->tipo_victimas_id;
        $general->created_by =  Auth::id();
        $general->update();

        CapturaInicial::where('generales_id', $id)
        ->update([
           'contacto_principal_nombres' => $request->contacto_principal_nombres,
            'contacto_principal_primer_apellido' => $request->contacto_principal_primer_apellido,
            'contacto_principal_segundo_apellido' => $request->contacto_principal_segundo_apellido,
            'contacto_principal_telefono' => $request->contacto_principal_telefono,
            'contacto_principal_correo' => $request->contacto_principal_correo,
            'contacto_secundario_nombres' => $request->contacto_secundario_nombres,
            'contacto_secundario_primer_apellido' => $request->contacto_secundario_primer_apellido,
            'contacto_secundario_segundo_apellido' => $request->contacto_secundario_segundo_apellido,
            'contacto_secundario_telefono' => $request->contacto_secundario_telefono,
            'contacto_secundario_correo' => $request->contacto_secundario_correo,
        ]);

        if ($request->tipo_victimas_id==TipoVictimaEnum::DIRECTA) {
            CapturaInicial::where('generales_id', $general->id)
            ->update([
                'victimas_directas_id' => $request->tipo_victimas_id,
            ]);
            $esVDirecta=VictimaDirecta::where('generales_id',$general->id)->first();
            if ($esVDirecta) {
                VictimaDirecta::where('generales_id', $id)
                ->update([
                   'nombres' => $request->nombres,
                   'primer_apellido' => $request->primer_apellido,
                   'segundo_apellido' => $request->segundo_apellido,
                ]);
            }
            else{
                $victimaDirecta = new VictimaDirecta;
                $victimaDirecta->nombres = $request->nombres;
                $victimaDirecta->primer_apellido = $request->primer_apellido;
                $victimaDirecta->segundo_apellido = $request->segundo_apellido;
                $victimaDirecta->generales_id = $general->id;
                $victimaDirecta->save();
            }
        }
        else{
            CapturaInicial::where('generales_id', $general->id)
            ->update([
                'victimas_indirectas_id' => $request->victimas_indirectas_id,
            ]);
            $esVIndirecta=victimaIndirecta::where('generales_id',$general->id)->first();
            if($esVIndirecta){
                victimaIndirecta::where('generales_id', $id)
                ->update([
                   'nombres' => $request->nombres,
                   'primer_apellido' => $request->primer_apellido,
                   'segundo_apellido' => $request->segundo_apellido,
                ]);
            }
            else{
                $victimaIndirecta = new VictimaIndirecta;
                $victimaIndirecta->nombres = $request->nombres;
                $victimaIndirecta->primer_apellido = $request->primer_apellido;
                $victimaIndirecta->segundo_apellido = $request->segundo_apellido;
                $victimaIndirecta->generales_id = $general->id;
                $victimaIndirecta->save();
            }
        }
        return to_route('captura.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $captura = VictimaIndirecta::where('generales_id', $id)->delete();
        $captura = VictimaDirecta::where('generales_id', $id)->delete();
        $captura = PrimerContacto::where('generales_id', $id)->delete();
        $captura = CapturaInicial::where('generales_id', $id)->delete();
        $general = General::where('id', $id)->delete();
        return to_route('captura.index');
    }
}
