<!DOCTYPE html>
<html lang="es">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet"/>
    <head>
        <meta charset="UTF-8">
        <style>
        @page {
            margin-top: 2cm;
        }
        body{
            width: 98%;
            margin: auto;
            font-family: "Gill Sans", sans-serif;
            
        }
        .contenido{
            background-color: #f2f2f2;
            border-right: 1px solid #841530; 
            border-left: 1px solid #841530;
            border-bottom: 1px solid #841530; 
            border-top: 1px solid #841530;
        }
        img{
            margin: auto;display: block;
        }
        .title{
            background-color: #841530;
            color: #fff;
            text-align: center;
            margin-top: 30px;
            font-size: 25px;
        }
        hr{
            background-color: #841530;
        }
        .subtitle{
            font-size: 18px;
            background-color: #841530;
            color: #fff;
            padding-left: 30px;
        }
        p{
            font-size: 15px;
        }
        .contenido-texto li::before {
            content: "-";
            color: #841530;
            display: inline-block;
            width: 1em;
            margin-left: -1em;
        }
        .contenido-texto li{
            list-style: none;
            /*list-style-type: circle;*/
        }
        h2{
            text-align: center;
            background-color: #841530;
            font-family: "Gill Sans", sans-serif;
            color:#fff;
        }
    </style>
    </head>
    <body>
        <div class="row">
            <!--<div class="col">
                <img width="150" height="150"  src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/escudo_simple.png'))) }}">
                <img width="200" height="200"  src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/CEEAIV.svg'))) }}">
            </div>-->
        </div>
        <p class="title" style="background-color:#fff;color: #841530;"><strong>Reporte de Victima</strong></p><hr>
        <div id="ci" class="section">
            <p class="title"><strong>Captura Inicial</strong></p>
            <div class="contenido">
                <p class="subtitle"><strong>Datos Victima</strong></p>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>Nombre: </strong>{{$captura->victimas_nombres}} {{$captura->victimas_primer_apellido}} {{$captura->victimas_segundo_apellido}}</li>
                        <li><strong>Tipo de Víctima: </strong>{{$captura->nombre_tipo_victima}}</li>
                        <li><strong>Servicios Requeridos:</strong>
                            {{($captura->gestion_medica===1)?'Atención Médica De Urgencia y Gestión Médica':''}}
                            {{($captura->gestion_social===1)?'Trabajo Social y Gestión':''}}
                            {{($captura->atencion_psicologica===1)?'Atención Psicológica':''}}
                            {{($captura->asesoria_juridica===1)?'Asesoría Jurídica':''}}
                            {{($captura->solicitudes_ayuda===1)?'Solicitudes de Medidas de Ayuda':''}}
                        </li>
                    </ul>
                </div>
                <p class="subtitle"><strong>Contacto Principal</strong></p>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>Nombre: </strong>{{$captura->contacto_principal_nombres}} {{$captura->contacto_principal_primer_apellido}} {{$captura->contacto_principal_segundo_apellido}}</li>
                        <li><strong>Correo electronico: </strong>{{$captura->contacto_principal_correo}}</li>
                        <li><strong>Teléfono: </strong>{{$captura->contacto_principal_telefono}}</li>
                    </ul>
                </div>
                <p class="subtitle"><strong>Contacto Secundario</strong></p>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>Nombre: </strong>{{$captura->contacto_secundario_nombres}} {{$captura->contacto_secundario_primer_apellido}} {{$captura->contacto_secundario_segundo_apellido}}</li>
                        <li><strong>Correo electronico: </strong>{{$captura->contacto_secundario_correo}}</li>
                        <li><strong>Teléfono: </strong>{{$captura->contacto_secundario_telefono}}</li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="epc" class="section">
            <p class="title"><strong>Entrevista Primer Contacto</strong></p>
            <div class="contenido section">
                <p class="subtitle"><strong>Datos de Atención</strong></p>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>Modalidad de atención: </strong>{{$captura->modalidad_atencion}}</li>
                        <li><strong>Tipo de atención </strong>{{($captura->colaboracion=='1')?'Con colaboración':'Sin colaboración'}}</li>
                        <li><strong>Institución: </strong>{{$captura->institucion}}</li>
                        <li><strong>No Oficio: </strong>{{$captura->no_oficio}}</li>
                    </ul>
                </div>
                
                <p class="subtitle"><strong>Datos Solicitantes</strong></p>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>Nombre completo: </strong>{{$captura->solicitantes_nombres}} {{$captura->solicitantes_primer_apellido}} {{$captura->solicitantes_segundo_apellido}}</li>
                        <li><strong>Sexo:</strong>{{($sexo_solicitante_nombre)?$sexo_solicitante_nombre->nombre:''}}</li>
                        <li><strong>Fecha nacimiento: </strong>{{$captura->solicitante_fecha_nacimiento}}</li>
                        <li><strong>Teléfonos: </strong>{{$captura->contacto_principal_telefono}}</li>
                        <li><strong>Nacionalidad: </strong>{{ ($nacionalidad_solicitantes)?$nacionalidad_solicitantes->nombre:'' }}</li>
                        <li><strong>Entidad Federativa: </strong>{{ ($entidad_solicitantes)?$entidad_solicitantes->name:'' }}</li>
                        <li><strong>Delegación o municipio: </strong>{{ ($municipio_solicitantes)?$municipio_solicitantes->name:'' }}</li>
                        <li><strong>Población o Comunidad: </strong>{{$captura->solicitantes_poblacion}}</li>

                    </ul>
                </div>
            
                <p class="subtitle"><strong>Relato de los Hechos</strong></p>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>Nacionalidad: </strong>{{ ($nacionalidad_hechos)?$nacionalidad_hechos->nombre:'' }}</li>
                        <li><strong>Entidad Federativa: </strong>{{ ($entidad_hechos)?$entidad_hechos->name:'' }}</li>
                        <li><strong>Delegación o municipio: </strong>{{ ($municipio_hechos)?$municipio_hechos->name:'' }}</li>
                        <li><strong>Población o Comunidad: </strong>{{$captura->hechos_poblacion}}</li>
                        <li><strong>Relato de Hechos: </strong>{{$captura->relato}}</li>
                    </ul>
                </div>



                <p class="subtitle"><strong>Ambito de Competencia</strong></p>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>¿Es una competencia directa? </strong>{{($captura->es_competencia_ceeaiv==1)?'SI':'NO'}}</li>
                        <li><strong>Especificación de la orientación: </strong>{{$captura->orientacion_brindada}}</li>
                    </ul>
                </div>

                <p class="subtitle"><strong>Datos de Identificación de la Victima Directa</strong></p>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>Nombre completo: </strong>{{$captura->victimas_nombres}}</li>
                        <li><strong>Fecha de Nacimiento: </strong>{{$captura->victimas_fecha_nacimiento}}</li>
                        <li><strong>Ocupación: </strong>{{($ocupaciones_victima)?$ocupaciones_victima->nombre_ocupacion:''}}</li>
                        <li><strong>Delito provisional: </strong>{{($delito_provicional)?$delito_provicional->nombre:''}}</li>
                        <li><strong>Violación DDHH provisional: </strong>{{($violacion_provicional)?$violacion_provicional->nombre:''}}</li>
                        <li><strong>Número de carpeta: </strong>{{$captura->no_carpeta}}</li>
                        <li><strong>Fiscalía: </strong>{{$captura->fiscalia}}</li>
                    </ul>
                </div>

                <p class="subtitle"><strong>Datos de Núcleo Familiar de la Victima Directa</strong></p>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>Observaciones: </strong>{{$captura->observaciones}}</li>
                    </ul>
                    @foreach($familiares as $familiar)
                    <ul>
                        <li><strong>Nombre Completo: </strong>{{$familiar->nombre_completo}}</li>
                        <li><strong>Edad: </strong>{{$familiar->edad}}</li>
                        <li><strong>Parentesco: </strong>{{$familiar->parentesco}}</li>
                        <li><strong>Dependencia Económica: </strong>{{$familiar->dependencia_economica}}</li>
                        <li><strong>Factores de Vulneravilidad: </strong>{{$familiar->factores_vulnerabilidad}}</li>
                    </ul>
                    @endforeach
                </div>

                <p class="subtitle"><strong>Domicilio de Notificaciones</strong></p>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>Nacionalidad: </strong>{{($nacionalidad_notificaciones)?$nacionalidad_notificaciones->nombre:''}}</li>
                        <li><strong>Entidad Federativa: </strong>{{($entidad_notificaciones)?$entidad_notificaciones->name:''}}</li>
                        <li><strong>Delegación o municipio: </strong>{{($municipio_notificaciones)?$municipio_notificaciones->name:''}}</li>
                        <li><strong>Codigo Postal: </strong>{{$captura->codigo_postal}}</li>
                        <li><strong>Colonia: </strong>{{$captura->colonia}}</li>
                        <li><strong>Localidad: </strong>{{$captura->localidad}}</li>
                        <li><strong>Calle: </strong>{{$captura->calle}}</li>
                        <li><strong>Número Exterior: </strong>{{$captura->no_exterior}}</li>
                        <li><strong>Número Interior: </strong>{{$captura->no_interior}}</li>
                        <li><strong>Estado Civil: </strong>{{$captura->estado_civil}}</li>
                        <li><strong>Escolaridad: </strong>{{$captura->escolaridad}}</li>
                        <li><strong>Ocupación: </strong>{{($ocupacion_notificaciones)?$ocupacion_notificaciones->nombre_ocupacion:''}}</li>
                        <li><strong>¿Pertenece a alguna organizacion de la sociedad civil/colectivo?: </strong>{{($captura->organizacion_pertenece==1)?'SI':'NO'}}</li>
                        <li><strong>¿Cúal?: </strong>{{$captura->cual_organizacion_pertenece}}</li>
                    </ul>
                </div>

                <p class="subtitle"><strong>Datos Socioecómicos</strong></p>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>¿Realiza alguna actividad donde perciba un ingreso económico? </strong>{{($captura->actividad_ingreso==1)?'SI':'NO'}}</li>
                        <li><strong>¿Cúal? </strong>{{($captura->actividad_ingreso==1)?$captura->cual_actividad_ingreso:''}}</li>
                       
                        <li><strong>¿Pertenece a algún programa social? </strong>{{($captura->programa_social==1)?'SI':'NO'}}</li>
                        <li><strong>¿Cúal? </strong>{{($captura->programa_social==1)?$captura->cual_programa_social:''}}</li>
                        
                        <li><strong>¿Cuenta con algún apoyo que provenga de la Federación o del Estado?  </strong>{{($captura->apoyo_federacion_estado==1)?'SI':'NO'}}</li>
                        <li><strong>¿Cúal?  </strong>{{($captura->apoyo_federacion_estado==1)?$captura->cual_apoyo_federacion_estado:''}}</li>
                    </ul>
                </div>

                <p class="subtitle"><strong>Salud</strong></p>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>Derivado del hecho victimizante ¿Presenta alguna afectación de la salud? </strong>{{($captura->afectacion_salud==1)?'SI':'NO'}}</li>
                        <li><strong>¿Cúal?  </strong>{{($captura->afectacion_salud==1)?$captura->cual_afectacion_salud:''}}</li>
                        <li><strong>¿Cuenta con servicio de atención médica? </strong>{{($captura->servicio_atencion_medica)?'SI':'NO'}}</li>
                        <li><strong>¿Cúal?  </strong>{{($captura->servicio_atencion_medica==1)?$captura->cual_servicio_atencion_medica:''}}</li>
                    </ul>
                </div>

                <p class="subtitle"><strong>Factores de Vulnerabilidad</strong></p>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>Niña, Niño, Adolescente: </strong>{{($captura->es_adolescente)?'SI':'NO'}}</li>
                        <li><strong>Adulto/a mayor: </strong>{{($captura->es_adulto_mayor)?'SI':'NO'}}</li>
                        <li><strong>LGBTTTI: </strong>{{($captura->es_lgbttti)?'SI':'NO'}}</li>
                        <li><strong>Discapacidad: </strong>{{($captura->es_discapacitado)?'SI':'NO'}}</li>
                        <li><strong>Situación de calle: </strong>{{($captura->es_situacion_calle)?'SI':'NO'}}</li>
                        <li><strong>Migrante: </strong>{{($captura->es_migrante)?'SI':'NO'}}</li>
                        <li><strong>Desplazamiento por hecho victimizante: </strong>{{($captura->es_desplazado_por_hecho)?'SI':'NO'}}</li>
                        <li><strong>Periodista: </strong>{{($captura->es_periodista)?'SI':'NO'}}</li>
                        <li><strong>Defensor/a de DDHH: </strong>{{($captura->es_defensor_ddhh)?'SI':'NO'}}</li>
                        <li><strong>Violencia de género: </strong>{{($captura->es_violencia_genero)?'SI':'NO'}}</li>
                        <li><strong>Trastorno mental: </strong>{{($captura->es_trastorno_mental)?'SI':'NO'}}</li>
                        <li><strong>VIH: </strong>{{($captura->es_vih)?'SI':'NO'}}</li>
                        <li><strong>Observaciones: </strong>{{$captura->observaciones}}</li>
                    </ul>
                </div>

                <p class="subtitle"><strong>Plan de Atención Integral</strong></p>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>Atención Médica De Urgencia y Gestión Médica: </strong>{{($captura->gestion_medica=='1')?'Si':'No'}}</li>
                        <li><strong>Necesidades requeridas: </strong>{{$captura->gestion_medica_necesidades}}</li>
                        <li><strong>Derivación: </strong>{{$captura->gestion_medica_derivacion}}</li>

                        <li><strong>Trabajo Social y Gestión: </strong>{{($captura->gestion_social=='1')?'Si':'No'}}</li>
                        <li><strong>Necesidades requeridas: </strong>{{$captura->gestion_social_necesidades}}</li>
                        <li><strong>Derivación: </strong>{{$captura->gestion_social_derivacion}}</li>
                        <li><strong>Número de Seguro Social: </strong>{{$captura->gestion_social_num_seguro_social}}</li>

                        <li><strong>Atención Psicológica: </strong>{{($captura->atencion_psicologica=='1')?'Si':'No'}}</li>
                        <li><strong>Necesidades requeridas: </strong>{{$captura->atencion_psicologica_necesidades}}</li>
                        <li><strong>Derivación: </strong>{{$captura->atencion_psicologica_derivacion}}</li>
                        <li><strong>¿Es atención interna o vinculación?: </strong>{{($captura->atencion_psicologica_es_interna=='1')?'Interna':'Vinculación'}}</li>
                        <li><strong>Psicólogo a cargo: </strong>{{$captura->nombre_psicologo}}</li>

                        <li><strong>Asesoría Jurídica: </strong>{{($captura->asesoria_juridica=='1')?'Si':'No'}}</li>
                        <li><strong>Necesidades requeridas: </strong>{{$captura->asesoria_juridica_necesidades}}</li>
                        <li><strong>Derivación: </strong>{{$captura->asesoria_juridica_derivacion}}</li>
                        <li><strong>Asesoría jurídica proceso:</strong>{{$asesoria_juridica_opciones}}</li>

                        <li><strong>Solicitudes de Medidas de Ayuda: </strong>{{($captura->solicitudes_ayuda=='1')?'Si':'No'}}</li>
                        <li><strong>Solicitudes opciones de Ayuda: </strong>{{$solicitudes_ayuda_opciones}}</li>
                        

                    </ul>
                </div>

                <p class="subtitle"><strong>Registro REV</strong></p>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>¿Solicita ingreso del Registro Estatal de Víctimas?: </strong>{{($captura->registro_rev==1)?'Si':'No'}}</li>
                        <li><strong>Con relación a niñas, niños y/o adolescentes para REV, ¿usted ejerce la guardia y custodia? : </strong>{{($captura->ejerce_custodia_rev==1)?'Si':'No'}}</li>
                        <li><strong>En caso de no tener a su cargo la guardia y custodia ¿quién la ejerce?: </strong>{{$captura->quien_ejerce_custodia_rev}}</li>
                        <li><strong>Observaciones: </strong>{{$captura->observaciones_rev}}</li>
                        <li><strong>Cómo se enteró de los servicios de la CEEAIV?: </strong>{{$captura->como_se_entero_ceeaiv}}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="rev" class="section">
            <p class="title"><strong>Registro Estatal de Victima</strong></p>
            <div class="contenido section">
                <p class="subtitle"><strong>Datos de Atención</strong></p>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>Lugar de Atención: </strong>{{$captura->lugar_atencion}}</li>
                        <li><strong>Fecha de Atención: </strong>{{$captura->fecha_atencion}}</li>
                        <li><strong>Solicitud Realizada por: </strong>{{($tipo_solicitante)?$tipo_solicitante->nombre:''}}</li>
                    </ul>
                </div>

                <p class="subtitle"><strong>Tipo y Datos de Víctima</strong></p>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>Tipo de Víctima: </strong>{{$captura->nombre_tipo_victima}}</li>
                        <li><strong>Nombre Completo: </strong>{{$captura->victimas_nombres}} {{$captura->victimas_primer_apellido}} {{$captura->victimas_segundo_apellido}}</li>
                        <li><strong>Fecha de Nacimiento: </strong>{{$captura->victimas_fecha_nacimiento}}</li>
                        <li><strong>CURP: </strong>{{($captura->curp)?$captura->curp:$captura->victimas_indirectas_curp}}</li>
                        <li><strong>Sexo: </strong>{{ $captura->victima_sexos_id === 1 ? 'Hombre' : 'Mujer' }}</li>
                        <li><strong>Estado Civil: </strong>{{($estados_civil)?$estados_civil->nombre:''}}</li>

                        <li><strong>Nacionalidad: </strong>{{ ($nacionalidad_victima)?$nacionalidad_victima->nombre:'' }}</li>
                        <li><strong>Entidad Federativa: </strong>{{ ($entidad_victima)?$entidad_victima->name:'' }}</li>
                        <li><strong>Delegación o Municipio: </strong>{{ ($municipio_victima)?$municipio_victima->name:'' }}</li>
                        <li><strong>Población o Comunidad: </strong>{{ ($captura->victima_directa_poblacion)?$captura->victima_directa_poblacion:$captura->victima_indirecta_poblacion }}</li>
                    </ul>
                </div>

                <p class="subtitle"><strong>Domicilio de Contacto del Solicitante</strong></p>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>Nacionalidad: </strong>{{ ($nacionalidad_solicitantes)?$nacionalidad_solicitantes->nombre:'' }}</li>
                        <li><strong>Entidad Federativa: </strong>{{ ($entidad_solicitantes)?$entidad_solicitantes->name:'' }}</li>
                        <li><strong>Delegación o municipio: </strong>{{ ($municipio_solicitantes)?$municipio_solicitantes->name:'' }}</li>
                        <li><strong>Población o Comunidad: </strong>{{$captura->solicitantes_poblacion}}</li>
                        <li><strong>Codigo Postal: </strong>{{$captura->codigo_postal}}</li>
                        <li><strong>Colonia: </strong>{{$captura->colonia}}</li>
                        <li><strong>Localidad: </strong>{{$captura->localidad}}</li>
                        <li><strong>Calle: </strong>{{$captura->calle}}</li>
                        <li><strong>Número Exterior: </strong>{{$captura->no_exterior}}</li>
                        <li><strong>Número Interior: </strong>{{$captura->no_interior}}</li>
                        <li><strong>Teléfono: </strong>{{$captura->telefono}}</li>
                        <li><strong>Tipo de Teléfono: </strong> {{$captura->tipo_telefono}}</li>
                    </ul>
                </div>

                <p class="subtitle"><strong>Relación con la Víctima Directa e identificación de la Víctima Directa</strong></p>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>Nombre Completo: </strong>
                            {{$captura->victimas_nombres}} {{$captura->victimas_primer_apellido}} {{$captura->victimas_segundo_apellido}}</li>
                        <li><strong>Documento Probatorio de Identidad: </strong> {{ $documentos_probatorios }}</li>
                        <li><strong>¿Presenta Identificación?: </strong>{{  $captura->presenta_identificacion === 1 ? 'SI' : 'NO'  }}</li>
                        <li><strong>Tipo de identificación: </strong> {{ $captura->tipo_documento }}</li>
                    </ul>
                </div>

                <p class="subtitle"><strong>Lugar y Fecha de los Hechos</strong></p>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>¿Conoce el lugar de los hechos?: </strong>{{ $captura->conoces_lugar_hechos === 1 ? 'SI' : 'NO' }}</li>
                        <li><strong>Lo que conoce del lugar de los hecho: </strong>{{ $captura->lo_que_conoces_lugar_hechos }}</li>
                        
                        <li><strong>Nacionalidad: </strong>{{ ($nacionalidad_hechos)?$nacionalidad_hechos->nombre:'' }}</li>
                        <li><strong>Entidad Federativa: </strong>{{ ($entidad_hechos)?$entidad_hechos->name:'' }}</li>
                        <li><strong>Delegación o municipio: </strong>{{ ($municipio_hechos)?$municipio_hechos->name:'' }}</li>
                        <li><strong>Población o Comunidad: </strong>{{$captura->hechos_poblacion}}</li>
                        <li><strong>Codigo Postal: </strong>{{$captura->hecho_codigo_postal}}</li>
                        <li><strong>Colonia: </strong>{{$captura->hecho_colonia}}</li>
                        <li><strong>Localidad: </strong>{{$captura->hecho_localidad}}</li>
                        <li><strong>Calle: </strong>{{$captura->hecho_calle}}</li>
                        <li><strong>Número Exterior: </strong>{{$captura->hecho_num_exterior}}</li>
                        <li><strong>Número Interior:</strong>{{$captura->hecho_num_interior}}</li>
                        <li><strong>Fecha: </strong>{{$captura->fecha_hecho_ini}}</li>

                        <li><strong>Relato de los hechos: </strong>{{$captura->relato}}</li>
                    </ul>
                </div>

                <p class="subtitle"><strong>Autoridades Observaciones</strong></p>
                <div class="contenido-texto">
                    @foreach($autoridades as $autoridad)
                        @if($autoridad->nombre_autoridad=='Investigación ministerial')
                            <ul>
                                <li><strong>Autoridades que han conocido los hechos: </strong>{{ $autoridad->nombre_autoridad }}</li>
                                <li><strong>¿Denunció ante el Ministerio Público?: </strong>{{ ($autoridad->denuncio_ante_ministerio)?'Si':'No' }}</li>
                                <!--<li><strong>Tipo daño sufrido: </strong> {{$autoridad->danio_autoridad}}</li>-->
                                <li><strong>Observaciones: </strong>{{ $autoridad->observaciones }}</li>
                                <li><strong>Fecha: </strong>{{ $autoridad->fecha }}</li>
                                <li><strong>Compentencia: </strong>{{ $autoridad->competencia }}</li>
                                <li><strong>Violación DDHH: </strong> {{$autoridad->violaciones_autoridades}}</li>
                                <li><strong>Delito: </strong> {{$autoridad->delitos_autoridades}}</li>
                                <li><strong>Agencia MP: </strong>{{ $autoridad->agencia_mp }}</li>
                                <li><strong>A. P./C. I./A. C: </strong>{{ $autoridad->ap_ci_ac }}</li>
                                <li><strong>Estado investigación: </strong>{{ $autoridad->estado_investigacion }}</li>
                            </ul>
                            <hr>
                        @endif
                        @if($autoridad->nombre_autoridad=='Proceso judicial')
                            <ul>
                                <li><strong>Autoridades que han conocido los hechos: </strong>{{ $autoridad->nombre_autoridad }}</li>
                                <li><strong>Fecha: </strong>{{ $autoridad->fecha }}</li>
                                <li><strong>Entidad Federativa:</strong> {{$autoridad->nombre_entidades}}</li>
                                <li><strong>Competencia: </strong>{{ $autoridad->competencia }}</li>
                                <li><strong>Violación DDHH: </strong> {{$autoridad->violaciones_autoridades}}</li>
                                <li><strong>Delito: </strong> {{$autoridad->delitos_autoridades}}</li>
                                <li><strong>Número de Juzgado: </strong>{{ $autoridad->num_juzgado }}</li>
                                <li><strong>Número de Proceso: </strong>{{ $autoridad->num_proceso }}</li>
                                <li><strong>Estado Proceso Judicial: </strong>{{ $autoridad->estado_proceso_judicial }}</li>
                            </ul>
                            <hr>
                        @endif
                        @if($autoridad->nombre_autoridad=='Procedimientos ante DDHH')
                            <ul>
                                <li><strong>Autoridades que han conocido los hechos: </strong>{{ $autoridad->nombre_autoridad }}</li>
                                <li><strong>Fecha: </strong>{{ $autoridad->fecha }}</li>
                                <li><strong>Competencia: </strong>{{ $autoridad->competencia }}</li>
                                <li><strong>Organismo: </strong>{{ $autoridad->organismo }}</li>
                                <li><strong>Violación DDHH: </strong> {{$autoridad->violaciones_autoridades}}</li>
                                <li><strong>Autoridad Responsable: </strong> {{$autoridad->autoridad_responsable}}</li>
                                <li><strong>Tipo de Resolución: </strong> {{$autoridad->tipo_resolucion}}</li>
                                <li><strong>Folio: </strong> {{$autoridad->folio}}</li>
                                <li><strong>Estado Actual: </strong> {{$autoridad->estado_actual}}</li>
                            </ul>
                            <hr>
                        @endif
                        @if($autoridad->nombre_autoridad=='Otra autoridad')
                            <ul>
                                <li><strong>Autoridades que han conocido los hechos: </strong>{{ $autoridad->nombre_autoridad }}</li>
                                <li><strong>Nombre autoridad: </strong>{{$autoridad->nombre_otra_autoridad}}</li>
                            </ul>
                            <hr>
                        @endif
                    @endforeach
                </div>

                <p class="subtitle"><strong>Información Complementaria</strong></p>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>¿Es niña/o o adolescente?: </strong>{{$captura->es_adolescente == 1 ? 'SI' : 'No' }}</li>
                        <li><strong>¿Es persona adulta mayor?: </strong>{{$captura->es_adulto_mayor == 1 ? 'SI' : 'No' }}</li>
                        <li><strong>¿Se encuentra en situación de calle?: </strong>{{$captura->es_situacion_calle == 1 ? 'SI' : 'No' }}</li>
                        <li><strong>¿Tiene condición de discapacidad?: </strong>{{$captura->es_discapacitado == 1 ? 'SI' : 'No' }}</li>
                        <li><strong>¿Es migrante?: </strong>{{$captura->es_migrante == 1 ? 'SI' : 'No' }}</li>
                        <li><strong>¿Habla español?: </strong>{{$captura->habla_espaniol == 1 ? 'SI' : 'No' }}</li>
                        <li><strong>¿Requiere traductor/a?: </strong>{{$captura->requiere_traductor == 1 ? 'SI' : 'No' }}</li>
                        <li><strong>¿Pertenece a población/comunidad indígena?: </strong>{{$captura->pertenece_poblacion_indigena == 1 ? 'SI' : 'No' }}</li>
                        <li><strong>¿Es Refugiado/a?: </strong>{{$captura->es_refugiado == 1 ? 'SI' : 'No' }}</li>
                        <li><strong>¿Es asilado/a político/a?: </strong>{{$captura->es_asilado_politico == 1 ? 'SI' : 'No' }}</li>
                        <li><strong>¿Es defensor/a de derechos humanos?: </strong>{{$captura->es_defensor_ddhh == 1 ? 'SI' : 'No' }}</li>
                        <li><strong>¿Es Periodista?: </strong>{{$captura->es_periodista == 1 ? 'SI' : 'No' }}</li>
                        <li><strong>¿Fue desplazado/a dentro del país o estado por condiciones de violencia?: </strong>{{$captura->es_desplazado_por_hecho == 1 ? 'SI' : 'No' }}</li>
                        <li><strong>Considera que el hecho victimizante se debió a: </strong>{{ utf8_decode($captura->hecho_victimizante_se_debio) }}</li>
                        <li><strong>Información de violencia contra las mujeres: </strong>{{$captura->informacion_violencia_contra_mujeres }}</li>

                    </ul>
                </div>

                <p class="subtitle"><strong>Documentación</strong></p>
                <div class="contenido-texto">
                    <ul>
                        
                    </ul>
                </div>

            </div>
        </div>
        <div id="renavi" class="section">
            <p class="title"><strong>Registro Nacional de Víctimas</strong></p>
            <div class="contenido section">
                <p class="subtitle"><strong>Datos de Víctima y Autoridad</strong></p>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>No. de REV: </strong>{{ $captura->no_rev }}</li>
                        <li><strong>No. de RENAVI: </strong>{{ $captura->no_renavi }}</li>
                        <li><strong>Tipo de Víctima:</strong>{{ $captura->nombre_tipo_victima }}</li>
                        <li><strong>Nombre Completo: </strong>{{$captura->victimas_nombres}} {{$captura->victimas_primer_apellido}} {{$captura->victimas_segundo_apellido}}</li>
                        <li><strong>CURP: </strong>{{($captura->curp)?$captura->curp:$captura->victimas_indirectas_curp}}</li>
                        <li><strong>Fecha de Nacimiento: </strong>{{$captura->victimas_fecha_nacimiento}}</li>
                        <li><strong>Sexo: </strong>{{ $captura->victima_sexos_id === 1 ? 'Hombre' : 'Mujer' }}</li>
                        <li><strong>Nacionalidad: </strong> {{ ($nacionalidad_victima)?$nacionalidad_victima->nombre:'' }}</li>
                        <li><strong>Entidad Federativa: </strong> {{ ($entidad_victima)?$entidad_victima->name:'' }}</li>
                        <li><strong>Delegación o Municipio: </strong> {{ ($municipio_victima)?$municipio_victima->name:'' }}</li>
                        <li><strong>Población o Comunidad: </strong>{{ ($captura->victima_directa_poblacion)?$captura->victima_directa_poblacion:$captura->victima_indirecta_poblacion }}</li>
                        <li><strong>Fecha de Inscripción: </strong>{{ $captura->fecha_atencion }}</p>
                    </ul>
                </div>
            </div>
            <div class="contenido section">
                <p class="subtitle"><strong>Datos de la Autoridad</strong></p>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>Autoridades que han conocido los hechos: </strong>
                            @foreach($autoridades as $autoridad)
                                <br>
                                {{ $autoridad->nombre_autoridad }}
                            @endforeach
                        </li> 
                    </ul>
                </div>
            </div>
            <div class="contenido section">
                 @foreach($autoridades as $autoridad)
                <p class="subtitle"><strong>{{$autoridad->nombre_autoridad}} </strong></li>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>Nombre Completo: </strong> {{$autoridad->nombres}} {{$autoridad->primer_apellido}} {{$autoridad->segundo_apellido}}</li>
                        <li><strong>Institución: </strong> {{$autoridad->institucion}}</li>
                    </ul>
                </div>
                @endforeach
            </div>
            <div class="contenido section">
                <p class="subtitle"><strong>Hechos Victimizante</strong></li>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>Ámbito de Competencia: </strong>{{ ($ambito_competencia)?$ambito_competencia->nombre:'' }}</li>
                        <li><strong>Inicio del Hecho: </strong>{{ $captura->fecha_hecho_ini }}</li>
                        <li><strong>Fin del Hecho: </strong>{{ $captura->fecha_hecho_fin }}</li>
                        <li><strong>Nacionalidad: </strong>{{ ($nacionalidad_hechos)?$nacionalidad_hechos->nombre:'' }}</li>
                        <li><strong>Entidad Federativa: </strong>{{ ($entidad_hechos)?$entidad_hechos->name:'' }}</li>
                        <li><strong>Delegación o municipio: </strong>{{ ($municipio_hechos)?$municipio_hechos->name:'' }}</li>
                        <li><strong>Población o Comunidad: </strong>{{$captura->hechos_poblacion}}</li>
                    </ul>
                </div>
            </div>
            <div class="contenido section">
                <p class="subtitle"><strong>Familiar o Persona Quien Solicita REV</strong></p>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>Nombre Completo: </strong>
                            {{ $captura->solicitantes_nombres }} {{ $captura->solicitantes_primer_apellido }} {{ $captura->solicitantes_segundo_apellido }}
                        </li>
                        <li><strong>Parentesco/Relación Afectiva: </strong> </li>
                        <li><strong>cargo: </strong>{{ $captura->cargo }}</li>
                        <li><strong>Dependencia o Institución: </strong> </li>
                        <li><strong>Situación Migratoria: </strong> </li>
                        <li><strong>Identificación presentada: </strong> </li>
                        <li><strong>Documento Probatorio de Identidad: </strong> </li>    
                        <li><strong>Entidad Federativa: </strong> </li>
                        <li><strong>Ambito de Dependencia: </strong> </li>
                        <li><strong>Nivel de Dependencia: </strong> </li>
                        <li><strong>Nombre Completo: </strong> </li>
                        <li><strong>Fecha de Nacimiento: </strong>{{ $captura->victimas_fecha_nacimiento }}</li>
                        <li><strong>CURP: </strong>{{ $captura->curp }}</li>
                        <li><strong>Sexo: </strong>{{ $captura->victima_sexos_id === 1 ? 'Hombre' : 'Mujer' }}</li>
                        <li><strong>Relación con la Víctima: </strong> </li>
                        <li><strong>Estado Civil: </strong> </li>
                        <li><strong>Nacionalidad: </strong>{{ ($nacionalidad_solicitantes)?$nacionalidad_solicitantes->nombre:'' }}</li>
                        <li><strong>Entidad Federativa: </strong>{{ ($entidad_solicitantes)?$entidad_solicitantes->name:'' }}</li>
                        <li><strong>Delegación o municipio: </strong>{{ ($municipio_solicitantes)?$municipio_solicitantes->name:'' }}</li>
                        <li><strong>Población o Comunidad: </strong>{{$captura->solicitantes_poblacion}}</li>
                    </ul>
                </div>
            </div>
            <!--<div class="contenido section">
                <p class="subtitle"><strong>Documentos de Identidad</strong></p>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>Identificación Presentada: </strong> </li>
                        <li><strong>Documento Probatorio de Identidad: </strong> </li>
                    </ul>
                </div>
            </div>-->
            <div class="contenido section">
                <p class="subtitle"><strong>Datos Dirección de Contacto</strong></p>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>Codigo Postal: </strong>{{  $captura->codigo_postal  }}</li>
                        <li><strong>Colonia: </strong>{{ $captura->colonia }}</li>
                        <li><strong>Localidad: </strong>{{  $captura->localidad  }}</li>
                        <li><strong>Calle: </strong>{{  $captura->calle }}</li>
                        <li><strong>Número Exterior: </strong>{{ $captura->no_exterior }}</li>
                        <li><strong>Número Interior: </strong>{{ $captura->no_interior }}</li>
                        <li><strong>Teléfono: </strong>{{ $captura->telefono }}</li>
                        <li><strong>Tipo de Teléfono: </strong> </li>
                        <li><strong>Lada: </strong>{{ $captura->lada }}</li>
                        <li><strong>Extension: </strong>{{ $captura->extension }}</li>
                        <li><strong>Nacionalidad: </strong>{{ ($nacionalidad_solicitantes)?$nacionalidad_solicitantes->nombre:'' }}</li>
                        <li><strong>Entidad Federativa: </strong>{{ ($entidad_solicitantes)?$entidad_solicitantes->name:'' }}</li>
                        <li><strong>Delegación o municipio: </strong>{{ ($municipio_solicitantes)?$municipio_solicitantes->name:'' }}</li>
                        <li><strong>Población o Comunidad: </strong>{{$captura->solicitantes_poblacion}}</li>
                        <li><strong>País: </strong> </li>
                    </ul>
                </div>
            </div>
            <div class="contenido section">
                <p class="subtitle"><strong>Enfoque Diferencial</strong></p>
                <div class="contenido-texto">
                    <ul>
                        <li><strong>¿Es niña/o o adolescente? </strong>{{$captura->es_adolescente == 1 ? 'SI' : 'No' }}</li>
                        <li><strong>Nombre Completo del tutor/a:</strong> {{ $captura->nombre_tutor }} {{ $captura->primer_apellido_tutor }} {{ $captura->segundo_apellido_tutor }}</li>
                        <li><strong>Datos de contacto del tutor:</strong> {{ $captura->datos_contacto_tutor }}</li>
                        <li><strong>¿Es persona adulta mayor? </strong>{{$captura->es_adulto_mayor == 1 ? 'SI' : 'No' }}</li>
                        <li><strong>¿Se encuentra en situación de calle?</strong> {{$captura->es_situacion_calle == 1 ? 'SI' : 'No' }}</li>
                        <li><strong>¿Tiene condición de discapacidad?</strong> {{$captura->es_discapacitado == 1 ? 'SI' : 'No' }}</li>
                        <li><strong>Tipo:</strong>{{ $captura->tipo_discapacidad}}</li>
                        <li><strong>Grado de dependencia:</strong>{{$captura->grado_dependencia}} </li>
                        <li><strong>¿Es migrante?</strong> {{ $captura->es_migrante == 1 ? 'SI' : 'No' }}</li>
                        <li><strong>País de origen:</strong>{{$captura->pais_origen}}</li>
                        <li><strong>País de destino:</strong>{{ $captura->pais_destino}}</li>
                        <li><strong>¿Habla español?</strong> {{ $captura->habla_espaniol == 1 ? 'SI' : 'No' }}</li>
                        <li><strong>¿Requiere traductor/a?</strong> {{ $captura->requiere_traductor == 1 ? 'SI' : 'No' }}</li>
                        <li><strong>¿Pertenece a población/comunidad indígena?</strong> {{ $captura->pertenece_poblacion_indigena == 1 ? 'SI' : 'No' }}</li>
                        <li><strong>¿Cual?:</strong> {{ $captura->cual_poblacion_indigena }}</li>
                        <li><strong>¿Es Refugiado/a?</strong> {{ $captura->es_refugiado == 1 ? 'SI' : 'No' }}</li>
                        <li><strong>¿Es asilado/a político/a?</strong> {{ $captura->es_asilado_politico == 1 ? 'SI' : 'No' }}</li>
                        <li><strong>Ha iniciado algún trámite para obtener esta condición:</strong> {{ $captura->ha_iniciado_tramite_condicion }}</li>
                        <li><strong>¿Es defensor/a de derechos humanos?</strong> {{ $captura->es_defensor_ddhh == 1 ? 'SI' : 'No' }}</li>
                        <li><strong>¿Pertenece a una institución?</strong> {{ $captura->pertenece_instutitucion == 1 ? 'SI' : 'No' }}</li>
                        <li><strong>¿Tipo de institución?</strong>{{ $captura->tipo_institucion}}</li>
                        <li><strong>Otra:</strong> {{ $captura->otra_institucion }}</li>
                        <li><strong>¿Es Periodista?</strong> {{ $captura->es_periodista == 1 ? 'SI' : 'No' }}</li>
                        <li><strong>¿Tipo de medio informativo?</strong> {{ $captura->tipo_medio_informativo }}</li>
                        <li><strong>¿Nombre del medio informativo?</strong> {{ $captura->nombre_medio_informativo }}</li>
                        <li><strong>¿Fue desplazado/a dentro del país o estado por condiciones de violencia?</strong> {{ $captura->es_desplazado_por_hecho == 1 ? 'SI' : 'No' }}</li>
                        <li><strong>Entidad de salida:</strong>{{$captura->entidad_salida}}</li>
                        <li><strong>Entidad receptora:</strong>{{$captura->entidad_receptora}}</li>
                        <li><strong>Considera que el hecho victimizante se debió a: </strong> {{$captura->hecho_victimizante_se_debio}}</li>
                        <li><strong>Información de violencia contra las mujeres:</strong>{{ $captura->informacion_violencia_contra_mujeres}}</li>
                    </ul>
                </div>
            </div>
        </div>
    </body>
</html>