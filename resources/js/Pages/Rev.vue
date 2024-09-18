<template>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <p class="font-bold text-gray-dark text-xl dark:text-white">Registro Estatal de Víctimas</p>
        </div>
        <div class="rounded-[16px] md:rounded-full bg-gray-light dark:bg-gray-table px-4 py-2 md:flex items-center justify-between text-black" >
            <div class="relative md:w-1/3">
                <input type="text" v-model="search" id="search-input" class="rounded-full block w-full ps-2 p-2" placeholder="Buscar"/>
                <button type="button" class="absolute inset-y-0 end-0 flex items-center pe-3" >
                    <svg
                    class="w-4 h-4 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white"
                    xmlns="http://www.w3.org/2000/svg"
                    height="16"
                    width="16"
                    viewBox="0 0 512 512"
                    >
                    <path
                    d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"
                    />
                    </svg>
                </button>
            </div>
            <div class="sm:flex">
                <div class="flex items-center py-2 md:py-0">
                    <label class="mx-2 dark:text-white">Inicio</label>
                    <vue-date-picker v-model="startDate" model-type="yyyy-MM-dd" :format="'dd/MM/yyyy'" :enable-time-picker="false" locale="es" cancelText="Cancelar" selectText="Aceptar"></vue-date-picker>
                </div>
                <div class="flex items-center pb-2 md:py-0">
                    <label class="mx-2 dark:text-white">Fin</label>
                    <vue-date-picker v-model="endDate" model-type="yyyy-MM-dd" :format="'dd/MM/yyyy'" :enable-time-picker="false" locale="es" cancelText="Cancelar" selectText="Aceptar"></vue-date-picker>
                </div>
                <span class="w-2"></span>
                <button @click="showFormData()" class="btn-normal w-44">Registrar Víctima</button>
            </div>
        </div>
        <div class="rounded-xl bg-gray-light dark:bg-gray-table p-4">
            <div class="overflow-auto">
                <table class="table-auto w-full custom-table" cellspacing="0">
                    <thead>
                        <tr class="text-gray-dark dark:text-white text-left">
                        <th>No. de REV</th>
                        <th>No. de Expediente</th>
                        <th class="whitespace-nowrap">Nombre Completo de Víctima</th>
                        <th>Estatus atención</th>
                        <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-normal dark:text-white">
                        <tr class="dark:bg-gray-row" v-for="captura in capturas.data">
                            <td>{{ captura.no_rev }}</td>
                            <td>{{ captura.folio_expediente }}</td>
                            <td>
                                <template v-if="captura.tipo_victimas_id == 1">
                                    {{ captura.nombres }} {{ captura.primer_apellido }} {{ captura.segundo_apellido }}
                                </template>
                                <template v-else>
                                    {{ captura.indirecta_nombres }} {{ captura.indirecta_primer_apellido }} {{ captura.indirecta_segundo_apellido }}
                                </template>
                            </td>
                            <td>{{ captura.estatus_atencion }}</td>
                            <td>
                                <div class="flex">
                                    <template v-if="captura.estatus_atencion != 'Captura Inicial'">
                                        <template v-if="captura.estatus_atencion == 'REV' || captura.estatus_atencion == 'REV sin PC' || captura.estatus_atencion == 'En Espera RENAVI' || captura.estatus_atencion == 'RENAVI'">
                                            <template v-if="captura.datos_completo_renavi">
                                                <Link v-if="checkPermisos([3])"  :href="'/renavi?data='+captura.id+'&modal=show'" class="flex items-center mx-1 p-1 rounded-full w-8 h-8 bg-gray-light hover:bg-amber-300" title="Ver RENAVI">
                                                    <img src="/images/icons/person.png" class="object-contain" >
                                                </Link>
                                            </template>
                                            <template v-else>
                                                <Link v-if="checkPermisos([3])" :href="'/renavi?data='+captura.id+'&modal=edit'"  class="flex items-center mx-1 p-1 rounded-full w-8 h-8 bg-gray-light hover:bg-amber-300" title="Editar RENAVI">
                                                    <img src="/images/icons/person-red.png" class="object-contain">
                                                </Link>
                                            </template>
                                        </template>
                                        <template v-if="checkPermisos([3])">
                                            <a href="#" id="subirArchivo-REVBtn" class="flex items-center mx-1 p-1 rounded-full w-8 h-8 bg-gray-light hover:bg-amber-300" title="Subir Archivo" @click.prevent="showEscaneoRev(captura)">
                                                <img src="/images/icons/upload.png" class="mx-1 object-contain">
                                            </a>
                                        </template>
                                        <template v-if="captura.fud_completo && checkPermisos([3])">
                                            <a href="#"  id="imprimirArchivo-REVBtn" class="flex items-center mx-1 p-1 rounded-full w-8 h-8 bg-gray-light hover:bg-amber-300" title="Imprimir" @click.prevent="showPrinter(captura)">
                                                <img src="/images/icons/print.png" class="object-contain">
                                            </a>
                                        </template >
                                        <template v-if="captura.fud_completo && checkPermisos([3])">
                                            <a href="#"  id="verArchivo-REVBtn" class="flex items-center mx-1 p-1 rounded-full w-8 h-8 bg-gray-light hover:bg-amber-300" title="Ver" @click.prevent="showData(captura)">
                                                <img src="/images/icons/eye.png" class="object-contain">
                                            </a>
                                         </template >
                                       <template v-if="user_role == 4 && checkPermisos([3]) && (captura.estatus_atencion == 'Esperando Aprobación REV' || captura.estatus_atencion == 'Esperando Aprobación REV sin PC')">
                                            <a href="#" id="change-REVBtn" class="flex items-center mx-1 p-1 rounded-full w-8 h-8 bg-gray-light hover:bg-amber-300" title="Cambiar Estatus" @click.prevent="showEstatus(captura)">
                                                <img src="/images/icons/change.png" class="object-contain">
                                            </a>
                                        </template>
                                    </template>
                                    <a href="#" v-if="checkPermisos([3])" class="flex items-center mx-1 p-2 rounded-full w-8 h-8 bg-gray-light hover:bg-amber-300" title="Editar" @click.prevent="showFormData(captura)">
                                        <img src="/images/icons/edit.png" class="object-contain">
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="overflow-auto">
        <Pagination :links="capturas.links"></Pagination>
    </div>

    <Modal ref="modalRev" :size="'max-w-6xl'">
        <template #modal-header>
            <p>{{ form.generales_id ? 'Editar' : 'Nuevo' }} Registro Estatal de Víctimas</p>
        </template>
        <template #modal-body>
            <Tabs :tabs="tabItems" @on-change-tab="changeTab">
                <template #tab-content-0>
                    <div class="w-full max-w-xl mx-auto">
                        <div class="md:flex md:item-center mb-2">
                            <div class="md:w-1/3 grid content-center">
                                <label class="block mb-1 md:mb-0 pr-4">
                                    Lugar de Atención*:
                                </label>
                            </div>
                            <div class="md:w-2/3">
                                <input
                                    v-model="form.lugar"
                                    id="lugar"
                                    type="text"
                                    class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                <div v-if="form.errors.lugar" v-text="form.errors.lugar" class="text-red-500"></div>
                            </div>
                        </div>
                        <div class="md:flex md:item-center mb-2">
                            <div class="md:w-1/3 grid content-center">
                                <label class="block mb-1 md:mb-0 pr-4">
                                    Fecha de Atención*:
                                </label>
                            </div>
                            <div class="md:w-2/3">
                                <vue-date-picker :teleport-center="true" v-model="form.fecha_atencion" model-type="yyyy-MM-dd" :format="'dd/MM/yyyy'" :enable-time-picker="false" locale="es" cancelText="Cancelar" selectText="Aceptar"></vue-date-picker>
                                <div v-if="form.errors.fecha_atencion" v-text="form.errors.fecha_atencion" class="text-red-500"></div>
                            </div>
                        </div>
                        <div class="md:flex md:item-center mb-2">
                            <div class="md:w-1/3 grid content-center">
                                <label class="block mb-1 md:mb-0 pr-4">
                                    Solicitud Realizada por:
                                </label>
                            </div>
                            <div class="md:w-2/3">
                                <div class="relative">
                                    <select
                                        @change="eventQuienSolicita"
                                        v-model="form.tipo_solicitantes_id"
                                        class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                        id="quienSolicita">
                                        <option value="null" disabled>Seleccionar</option>
                                        <option v-for="quien in tiposSolicitantes" :value="quien.tipo_solicitantes_id">{{ quien.nombre }}</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                            <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div v-if="form.errors.tipo_solicitantes_id" v-text="form.errors.tipo_solicitantes_id" class="text-red-500"></div>
                            </div>
                        </div>
                        <template v-if="form.tipo_solicitantes_id != null && form.tipo_solicitantes_id != 1">
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Nombre(s)*:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="nombre" type="text"
                                        v-model="form.solicitante_nombres"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.solicitante_nombres" v-text="form.errors.solicitante_nombres" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Primer Apellido*:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="primerApellido" type="text"
                                        v-model="form.solicitante_primer_apellido"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.solicitante_primer_apellido" v-text="form.errors.solicitante_primer_apellido" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Segundo Apellido*:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="segundoApellido" type="text"
                                        v-model="form.solicitante_segundo_apellido"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.solicitante_segundo_apellido" v-text="form.errors.solicitante_segundo_apellido" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2" v-if="form.tipo_solicitantes_id == 2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Parentesco/Relación Afectiva:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="relative">
                                        <select
                                            v-model="form.parentesco_id"
                                            class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                            id="parentesco">
                                            <option value="null" disabled>Seleccionar</option>
                                            <option v-for="parentesco in parentescos" :value="parentesco.parentescos_id">{{ parentesco.nombre }}</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div v-if="form.errors.parentesco_id" v-text="form.errors.parentesco_id" class="text-red-500"></div>
                                </div>
                            </div>
                            <template v-if="form.tipo_solicitantes_id == 4 || form.tipo_solicitantes_id == 3">
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Cargo:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="cargo" type="text"
                                            v-model="form.cargo"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.cargo" v-text="form.errors.cargo" class="text-red-500"></div>
                                    </div>
                                </div>
                            </template>
                            <template v-if="form.tipo_solicitantes_id == 4">
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Dependencia o Institución:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="dependencia" type="text"
                                            v-model="form.dependencia"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.dependencia" v-text="form.errors.dependencia" class="text-red-500"></div>
                                    </div>
                                </div>
                            </template>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Nacionalidad:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="relative">
                                        <select
                                            v-model="form.territorios_id"
                                            class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                            id="nacionalidad">
                                            <option value="null" disabled>Seleccionar</option>
                                            <option v-for="nacionalidad in nacionalidades" :value="nacionalidad.territorios_id">{{ nacionalidad.nombre }}</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div v-if="form.errors.territorios_id" v-text="form.errors.territorios_id" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Situación Migratoria:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="relative">
                                        <select
                                            v-model="form.situacion_migratoria_id"
                                            class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                            id="situacion">
                                            <option value="null" disabled>Seleccionar</option>
                                            <option v-for="situacion in situacionesMigratorias" :value="situacion.situaciones_migratorias_id">{{ situacion.nombre }}</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div v-if="form.errors.situacion_migratoria_id" v-text="form.errors.situacion_migratoria_id" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Identificación presentada:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="relative">
                                        <select
                                            v-model="form.identificacion_id"
                                            class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                            id="identificacion_id">
                                            <option value="null" disabled>Seleccionar</option>
                                            <option v-for="documentoIdentidad in documentosProbatorios" :value="documentoIdentidad.documentos_probatorios_id">{{ documentoIdentidad.nombre }}</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div v-if="form.errors.identificacion_id" v-text="form.errors.identificacion_id" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Documento Probatorio de Identidad:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="relative">
                                        <select
                                            v-model="form.identidad_probatorio_documentos_id"
                                            class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                            id="situacion">
                                            <option value="null" disabled>Seleccionar</option>
                                            <option v-for="documentoIdentidadProb in documentosProbatoriosIdentidad" :value="documentoIdentidadProb.identidad_probatorio_documentos_id">{{ documentoIdentidadProb.nombre }}</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div v-if="form.errors.identidad_probatorio_documentos_id" v-text="form.errors.identidad_probatorio_documentos_id" class="text-red-500"></div>
                                </div>
                            </div>
                            <template v-if="form.tipo_solicitantes_id == 4">
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Entidad Federativa:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <div class="relative">
                                            <select
                                                v-model="form.entidades_federativas_id"
                                                class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                id="solicitanteEntidad">
                                                <option value="null" disabled>Seleccionar</option>
                                                <option v-for="entidad in entidadesFederativas" :value="entidad.entidades_federativas_id">{{ entidad.name }}</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                    <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div v-if="form.errors.entidades_federativas_id" v-text="form.errors.entidades_federativas_id" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Ambito de Dependencia:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <div class="relative">
                                            <select
                                                v-model="form.ambito_dependencias_id"
                                                class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                id="ambitosDependencias">
                                                <option value="null" disabled>Seleccionar</option>
                                                <option v-for="ambitos in ambitosDependencias" :value="ambitos.ambito_dependencias_id">{{ ambitos.nombre }}</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                    <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div v-if="form.errors.ambito_dependencias_id" v-text="form.errors.ambito_dependencias_id" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Nivel de Dependencia:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <div class="relative">
                                            <select
                                                v-model="form.nivel_dependencias_id"
                                                class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                id="ambitosDependencias">
                                                <option value="null" disabled>Seleccionar</option>
                                                <option v-for="ambitos in ambitosDependencias" :value="ambitos.ambito_dependencias_id">{{ ambitos.nombre }}</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                    <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div v-if="form.errors.nivel_dependencias_id" v-text="form.errors.nivel_dependencias_id" class="text-red-500"></div>
                                    </div>
                                </div>
                            </template>
                        </template>
                    </div>
                </template>
                <template #tab-content-1>
                    <Tabs :tabs="tabsItemVictima" :subTab="'2.'" @on-change-tab="changeTabVictima">
                        <template #tab-content-0>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div class="md:flex md:item-center mb-2">
                                        <div class="md:w-1/3 grid content-center">
                                            <label class="block mb-1 md:mb-0 pr-4">
                                                Tipo de víctima*:
                                            </label>
                                        </div>
                                        <div class="md:w-2/3">
                                            <div class="relative">
                                                <select
                                                    class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                    v-model="form.tipo_victimas_id"
                                                    @change="eventTipoVictima"
                                                    id="tipoVictima">
                                                    <option value="0" disabled>Seleccionar Tipo</option>
                                                    <option v-for="tipo in tipoVictimas" :value="tipo.tipo_victimas_id">{{ tipo.nombre }}</option>
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                        <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div v-if="form.errors.tipo_victimas_id" v-text="form.errors.tipo_victimas_id" class="text-red-500"></div>
                                        </div>
                                    </div>
                                    <div class="md:flex md:item-center mb-2">
                                        <div class="md:w-1/3 grid content-center">
                                            <label class="block mb-1 md:mb-0 pr-4">
                                                Nombre(s)*:
                                            </label>
                                        </div>
                                        <div class="md:w-2/3">
                                            <input  id="victimaNombre" type="text"
                                                v-model="form.victima_nombres"
                                                class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                                <div v-if="form.errors.victima_nombres" v-text="form.errors.victima_nombres" class="text-red-500"></div>
                                        </div>
                                    </div>
                                    <div class="md:flex md:item-center mb-2">
                                        <div class="md:w-1/3 grid content-center">
                                            <label class="block mb-1 md:mb-0 pr-4">
                                                Apellido Paterno*:
                                            </label>
                                        </div>
                                        <div class="md:w-2/3">
                                            <input  id="victimaPrimerApellido" type="text"
                                                v-model="form.victima_primer_apellido"
                                                class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                                <div v-if="form.errors.victima_primer_apellido" v-text="form.errors.victima_primer_apellido" class="text-red-500"></div>
                                        </div>
                                    </div>
                                    <div class="md:flex md:item-center mb-2">
                                        <div class="md:w-1/3 grid content-center">
                                            <label class="block mb-1 md:mb-0 pr-4">
                                                Apellido Materno*:
                                            </label>
                                        </div>
                                        <div class="md:w-2/3">
                                            <input  id="victimaSegundoApellido" type="text"
                                            v-model="form.victima_segundo_apellido"
                                                class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                                <div v-if="form.errors.victima_segundo_apellido" v-text="form.errors.victima_segundo_apellido" class="text-red-500"></div>
                                        </div>
                                    </div>
                                    <div class="md:flex md:item-center mb-2">
                                        <div class="md:w-1/3 grid content-center">
                                            <label class="block mb-1 md:mb-0 pr-4">
                                                Fecha de Nacimiento*:
                                            </label>
                                        </div>
                                        <div class="md:w-2/3">
                                            <vue-date-picker v-model="form.victima_fecha_nacimiento" model-type="yyyy-MM-dd" :format="'dd/MM/yyyy'" :enable-time-picker="false" locale="es" cancelText="Cancelar" selectText="Aceptar"></vue-date-picker>
                                            <div v-if="form.errors.victima_fecha_nacimiento" v-text="form.errors.victima_fecha_nacimiento" class="text-red-500"></div>
                                        </div>
                                    </div>
                                    <div class="md:flex md:item-center mb-2">
                                        <div class="md:w-1/3 grid content-center">
                                            <label class="block mb-1 md:mb-0 pr-4">
                                                CURP:
                                            </label>
                                        </div>
                                        <div class="md:w-2/3">
                                            <input  id="curp" type="text"
                                                v-model="form.victima_curp"
                                                class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                            <div v-if="form.errors.victima_curp" v-text="form.errors.victima_curp" class="text-red-500"></div>
                                        </div>
                                    </div>
                                    <div class="md:flex md:item-center mb-2">
                                        <div class="md:w-1/3 grid content-center">
                                            <label class="block mb-1 md:mb-0 pr-4">
                                                Sexo:
                                            </label>
                                        </div>
                                        <div class="md:w-2/3">
                                            <div class="relative">
                                                <select
                                                    v-model="form.victima_sexos_id"
                                                    class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                    id="sexo">
                                                    <option value="null" disabled>Seleccionar</option>
                                                    <option v-for="sexo in sexos" :value="sexo.sexos_id">{{ sexo.nombre }}</option>
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                        <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div v-if="form.errors.victima_sexos_id" v-text="form.errors.victima_sexos_id" class="text-red-500"></div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="md:flex md:item-center mb-2" v-if="form.tipo_victimas_id != 1">
                                        <div class="md:w-1/3 grid content-center">
                                            <label class="block mb-1 md:mb-0 pr-4">
                                                Relación con la Víctima:
                                            </label>
                                        </div>
                                        <div class="md:w-2/3">
                                            <div class="relative">
                                                <select
                                                    v-model="form.victima_parentesco_id"
                                                    class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                    id="victima_parentesco_id">
                                                    <option value="null" disabled>Seleccionar</option>
                                                    <option v-for="parentesco in parentescos" :value="parentesco.parentescos_id">{{ parentesco.nombre }}</option>
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                        <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div v-if="form.errors.victima_parentesco_id" v-text="form.errors.victima_parentesco_id" class="text-red-500"></div>
                                        </div>
                                    </div>
                                    <div class="md:flex md:item-center mb-2">
                                        <div class="md:w-1/3 grid content-center">
                                            <label class="block mb-1 md:mb-0 pr-4">
                                                Estado Civil:
                                            </label>
                                        </div>
                                        <div class="md:w-2/3">
                                            <div class="relative">
                                                <select
                                                    v-model="form.estado_civil"
                                                    class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                    id="sexo">
                                                    <option value="" disabled>Seleccionar</option>
                                                    <option v-for="estadoCivil in estadosCivil" :value="estadoCivil.id">{{ estadoCivil.nombre }}</option>
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                        <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div v-if="form.errors.estado_civil" v-text="form.errors.estado_civil" class="text-red-500"></div>
                                        </div>
                                    </div>
                                    <div class="md:flex md:item-center mb-2">
                                        <div class="md:w-1/3 grid content-center">
                                            <label class="block mb-1 md:mb-0 pr-4">
                                                Nacionalidad:
                                            </label>
                                        </div>
                                        <div class="md:w-2/3">
                                            <div class="relative">
                                                <select
                                                    v-model="form.victima_territorios_id"
                                                    class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                    id="nacionalidad">
                                                    <option value="null" disabled>Seleccionar</option>
                                                    <option v-for="nacionalidad in nacionalidades" :value="nacionalidad.territorios_id">{{ nacionalidad.nombre }}</option>
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                        <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div v-if="form.errors.victima_territorios_id" v-text="form.errors.victima_territorios_id" class="text-red-500"></div>
                                        </div>
                                    </div>
                                    <template v-if="form.victima_territorios_id == 1">
                                        <div class="md:flex md:item-center mb-2">
                                            <div class="md:w-1/3 grid content-center">
                                                <label class="block mb-1 md:mb-0 pr-4">
                                                    Entidad Federativa:
                                                </label>
                                            </div>
                                            <div class="md:w-2/3">
                                                <div class="relative">
                                                    <select
                                                        v-model="victimaMunicipio"
                                                        class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                        id="solicitanteEntidad">
                                                        <option value="null" disabled>Seleccionar</option>
                                                        <option v-for="entidad in entidadesFederativas" :value="entidad.entidades_federativas_id">{{ entidad.name }}</option>
                                                    </select>
                                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                            <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div v-if="form.errors.victima_entidades_federativas_id" v-text="form.errors.victima_entidades_federativas_id" class="text-red-500"></div>
                                            </div>
                                        </div>
                                        <div class="md:flex md:item-center mb-2">
                                            <div class="md:w-1/3 grid content-center">
                                                <label class="block mb-1 md:mb-0 pr-4">
                                                    Delegación o Municipio:
                                                </label>
                                            </div>
                                            <div class="md:w-2/3">
                                                <div class="relative">
                                                    <select
                                                        v-model="form.victima_municipios_id"
                                                        class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                        id="municipio">
                                                        <option value="null" disabled>Seleccionar</option>
                                                        <option v-for="municipio in victimaMunicipios" :value="municipio.municipios_id">{{ municipio.name }}</option>
                                                    </select>
                                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                            <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div v-if="form.errors.victima_municipios_id" v-text="form.errors.victima_municipios_id" class="text-red-500"></div>
                                            </div>
                                        </div>
                                        <div class="md:flex md:item-center mb-2">
                                            <div class="md:w-1/3 grid content-center">
                                                <label class="block mb-1 md:mb-0 pr-4">
                                                    Población o Comunidad:
                                                </label>
                                            </div>
                                            <div class="md:w-2/3">
                                                <input  id="poblacion" type="text"
                                                    v-model="form.victima_poblacion"
                                                    class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                                <div v-if="form.errors.victima_poblacion" v-text="form.errors.victima_poblacion" class="text-red-500"></div>
                                            </div>
                                        </div>
                                    </template>
                                    <div class="md:flex md:item-center mb-2" v-if="form.victima_territorios_id == 2">
                                        <div class="md:w-1/3 grid content-center">
                                            <label class="block mb-1 md:mb-0 pr-4">
                                                País:
                                            </label>
                                        </div>
                                        <div class="md:w-2/3">
                                            <div class="relative">
                                                <select
                                                    v-model="form.victima_paises_id"
                                                    class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                    id="pais">
                                                    <option value="null" disabled>Seleccionar</option>
                                                    <option v-for="pais in paises" :value="pais.paises_id">{{ pais.nombre }}</option>
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                        <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div v-if="form.errors.victima_paises_id" v-text="form.errors.victima_paises_id" class="text-red-500"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template #tab-content-1>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div class="md:flex md:item-center mb-2">
                                        <div class="md:w-1/3 grid content-center">
                                            <label class="block mb-1 md:mb-0 pr-4">
                                                Calle:
                                            </label>
                                        </div>
                                        <div class="md:w-2/3">
                                            <input  id="calle" type="text"
                                                v-model="form.calle"
                                                class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                            <div v-if="form.errors.calle" v-text="form.errors.calle" class="text-red-500"></div>
                                        </div>
                                    </div>
                                    <div class="md:flex md:item-center mb-2">
                                        <div class="md:w-1/3 grid content-center">
                                            <label class="block mb-1 md:mb-0 pr-4">
                                                Número Exterior:
                                            </label>
                                        </div>
                                        <div class="md:w-2/3">
                                            <input  id="exterior" type="text"
                                                v-model="form.no_exterior"
                                                class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                            <div v-if="form.errors.no_exterior" v-text="form.errors.no_exterior" class="text-red-500"></div>
                                        </div>
                                    </div>
                                    <div class="md:flex md:item-center mb-2">
                                        <div class="md:w-1/3 grid content-center">
                                            <label class="block mb-1 md:mb-0 pr-4">
                                                Número Interior:
                                            </label>
                                        </div>
                                        <div class="md:w-2/3">
                                            <input  id="interior" type="text"
                                                v-model="form.no_interior"
                                                class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                            <div v-if="form.errors.no_interior" v-text="form.errors.no_interior" class="text-red-500"></div>
                                        </div>
                                    </div>
                                    <div class="md:flex md:item-center mb-2">
                                        <div class="md:w-1/3 grid content-center">
                                            <label class="block mb-1 md:mb-0 pr-4">
                                                Colonia:
                                            </label>
                                        </div>
                                        <div class="md:w-2/3">
                                            <input  id="colonia" type="text"
                                                v-model="form.colonia"
                                                class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                            <div v-if="form.errors.colonia" v-text="form.errors.colonia" class="text-red-500"></div>
                                        </div>
                                    </div>
                                    <div class="md:flex md:item-center mb-2">
                                        <div class="md:w-1/3 grid content-center">
                                            <label class="block mb-1 md:mb-0 pr-4">
                                                Código Postal:
                                            </label>
                                        </div>
                                        <div class="md:w-2/3">
                                            <input  id="postal" type="text"
                                                v-model="form.codigo_postal"
                                                class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                            <div v-if="form.errors.codigo_postal" v-text="form.errors.codigo_postal" class="text-red-500"></div>
                                        </div>
                                    </div>
                                    <div class="md:flex md:item-center mb-2">
                                        <div class="md:w-1/3 grid content-center">
                                            <label class="block mb-1 md:mb-0 pr-4">
                                                Localidad:
                                            </label>
                                        </div>
                                        <div class="md:w-2/3">
                                            <input  id="localidad" type="text"
                                                v-model="form.localidad"
                                                class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                            <div v-if="form.errors.localidad" v-text="form.errors.localidad" class="text-red-500"></div>
                                        </div>
                                    </div>
                                    <div class="md:flex md:item-center mb-2">
                                        <div class="md:w-1/3 grid content-center">
                                            <label class="block mb-1 md:mb-0 pr-4">
                                                Teléfono:
                                            </label>
                                        </div>
                                        <div class="md:w-2/3">
                                            <input  id="telefono" type="text"
                                                v-model="form.telefono"
                                                class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                            <div v-if="form.errors.telefono" v-text="form.errors.telefono" class="text-red-500"></div>
                                        </div>
                                    </div>
                                    <div class="md:flex md:item-center mb-2">
                                        <div class="md:w-1/3 grid content-center">
                                            <label class="block mb-1 md:mb-0 pr-4">
                                                Tipo de Teléfono:
                                            </label>
                                        </div>
                                        <div class="md:w-2/3">
                                            <div class="relative">
                                                <select
                                                    v-model="form.tipos_telefonos_id"
                                                    class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                    id="tipoTelefono">
                                                    <option value="null" disabled>Seleccionar</option>
                                                    <option v-for="tipoTelefono in tipoTelefonos" :value="tipoTelefono.tipos_telefonos_id">{{ tipoTelefono.nombre }}</option>
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                        <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div v-if="form.errors.tipos_telefonos_id" v-text="form.errors.tipos_telefonos_id" class="text-red-500"></div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="md:flex md:item-center mb-2">
                                        <div class="md:w-1/3 grid content-center">
                                            <label class="block mb-1 md:mb-0 pr-4">
                                                Nacionalidad*:
                                            </label>
                                        </div>
                                        <div class="md:w-2/3">
                                            <div class="relative">
                                                <select
                                                    v-model="form.solicitante_territorios_id"
                                                    class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                    id="nacionalidadNotificacion">
                                                    <option value="null" disabled>Seleccionar</option>
                                                    <option v-for="nacionalidad in nacionalidades" :value="nacionalidad.territorios_id">{{ nacionalidad.nombre }}</option>
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                        <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div v-if="form.errors.solicitante_territorios_id" v-text="form.errors.solicitante_territorios_id" class="text-red-500"></div>
                                        </div>
                                    </div>
                                    <template v-if="form.solicitante_territorios_id == 1">
                                        <div class="md:flex md:item-center mb-2">
                                            <div class="md:w-1/3 grid content-center">
                                                <label class="block mb-1 md:mb-0 pr-4">
                                                    Entidad Federativa:
                                                </label>
                                            </div>
                                            <div class="md:w-2/3">
                                                <div class="relative">
                                                    <select
                                                        v-model="solicitanteMunicipio"
                                                        class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                        id="entidad">
                                                        <option value="null" disabled>Seleccionar</option>
                                                        <option v-for="entidad in entidadesFederativas" :value="entidad.entidades_federativas_id">{{ entidad.name }}</option>
                                                    </select>
                                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                            <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div v-if="form.errors.solicitante_entidades_federativas_id" v-text="form.errors.solicitante_entidades_federativas_id" class="text-red-500"></div>
                                            </div>
                                        </div>
                                        <div class="md:flex md:item-center mb-2">
                                            <div class="md:w-1/3 grid content-center">
                                                <label class="block mb-1 md:mb-0 pr-4">
                                                    Delegación o Municipio:
                                                </label>
                                            </div>
                                            <div class="md:w-2/3">
                                                <div class="relative">
                                                    <select
                                                        v-model="form.solicitante_municipios_id"
                                                        class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                        id="notificacionesNunicipio">
                                                        <option value="null" disabled>Seleccionar</option>
                                                        <option v-for="municipio in solicitanteMunicipios" :value="municipio.municipios_id">{{ municipio.name }}</option>
                                                    </select>
                                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                            <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div v-if="form.errors.solicitante_municipios_id" v-text="form.errors.solicitante_municipios_id" class="text-red-500"></div>
                                            </div>
                                        </div>
                                    </template>
                                    <div class="md:flex md:item-center mb-2" v-if="form.solicitante_territorios_id == 2">
                                        <div class="md:w-1/3 grid content-center">
                                            <label class="block mb-1 md:mb-0 pr-4">
                                                País:
                                            </label>
                                        </div>
                                        <div class="md:w-2/3">
                                            <div class="relative">
                                                <select
                                                    v-model="form.solicitante_paises_id"
                                                    class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                    id="pais">
                                                    <option value="null" disabled>Seleccionar</option>
                                                    <option v-for="pais in paises" :value="pais.paises_id">{{ pais.nombre }}</option>
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                        <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div v-if="form.errors.solicitante_paises_id" v-text="form.errors.solicitante_paises_id" class="text-red-500"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </Tabs>
                </template>
                <template #tab-content-2>
                    <div class="grid grid-cols-2 gap-4 min-h-88">
                        <div>
                            <div class="my-2">
                                <p class="font-bold">Datos Relación Víctima Indirecta con Víctima Directa</p>
                                <p class="text-sm">En caso de ser víctima indirecta, proporcione nombre completo de la víctima directa</p>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Nombre(s)*:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="victima_directa_nombres" type="text"
                                        v-model="form.victima_directa_nombres"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.victima_directa_nombres" v-text="form.errors.victima_directa_nombres" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Primer Apellido*:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="victima_directa_primer_apellido" type="text"
                                        v-model="form.victima_directa_primer_apellido"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.victima_directa_primer_apellido" v-text="form.errors.victima_directa_primer_apellido" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Segundo Apellido*:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="victima_directa_segundo_apellido" type="text"
                                        v-model="form.victima_directa_segundo_apellido"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.victima_directa_segundo_apellido" v-text="form.errors.victima_directa_segundo_apellido" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Documento Probatorio de Identidad:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <Multiselect
                                        v-model="form.victima_directa_identidad_probatorio_documentos_id"
                                        mode="tags"
                                        label="nombre"
                                        valueProp="identidad_probatorio_documentos_id"
                                        :close-on-select="false"
                                        :options="documentosProbatoriosIdentidad"
                                    />
                                </div>
                            </div>
                        </div>
                        <div>
                            <p class="font-bold my-2">Identificación de la Víctima</p>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        ¿Presenta identificación?
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="flex items-center my-2">
                                        <label for="identificacion_victima_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.identificacion_victima"
                                                id="identificacion_victima_si"
                                                name="identificacion_victima"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="identificacion_victima_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.identificacion_victima"
                                                id="identificacion_victima_no"
                                                name="identificacion_victima"
                                                type="radio"
                                                :value="0"
                                                class="w-4 h-4">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2" v-if="parseInt(form.identificacion_victima) ? true : false">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Identificación presentada:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="relative">
                                        <select
                                            v-model="form.identificacion_victima_id"
                                            class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                            id="identificacion_victima_id">
                                            <option value="null" disabled>Seleccionar</option>
                                            <option v-for="documentoIdentidad in documentosProbatorios" :value="documentoIdentidad.documentos_probatorios_id">{{ documentoIdentidad.nombre }}</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template #tab-content-3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        ¿Conoce el lugar de los hechos?
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="flex items-center my-2">
                                        <label for="lugar_hechos_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.conoce_lugar_hechos"
                                                id="lugar_hechos_si"
                                                name="lugar_hechos"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="lugar_hechos_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.conoce_lugar_hechos"
                                                id="lugar_hechos_no"
                                                name="lugar_hechos"
                                                type="radio"
                                                :value="0"
                                                class="w-4 h-4">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <template v-if="parseInt(form.conoce_lugar_hechos) ? true : false">
                            <div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Calle:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="hechos_calle" type="text"
                                            v-model="form.hechos_calle"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.hechos_calle" v-text="form.errors.hechos_calle" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Número Exterior:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="hechos_exterior" type="text"
                                            v-model="form.hechos_no_exterior"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.hechos_no_exterior" v-text="form.errors.hechos_no_exterior" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Número Interior:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="hechos_interior" type="text"
                                            v-model="form.hechos_no_interior"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.hechos_no_interior" v-text="form.errors.hechos_no_interior" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Colonia:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="hechos_colonia" type="text"
                                            v-model="form.hechos_colonia"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.hechos_colonia" v-text="form.errors.hechos_colonia" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Código Postal:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="hechos_postal" type="text"
                                            v-model="form.hechos_codigo_postal"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.hechos_codigo_postal" v-text="form.errors.hechos_codigo_postal" class="text-red-500"></div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Localidad:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="hechos_localidad" type="text"
                                            v-model="form.hechos_localidad"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.hechos_localidad" v-text="form.errors.hechos_localidad" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Nacionalidad:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <div class="relative">
                                            <select
                                                v-model="form.hechos_territorios_id"
                                                class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                id="hechos_territorios_id">
                                                <option value="null" disabled>Seleccionar</option>
                                                <option v-for="nacionalidad in nacionalidades" :value="nacionalidad.territorios_id">{{ nacionalidad.nombre }}</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                    <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <template v-if="form.hechos_territorios_id == 1">
                                    <div class="md:flex md:item-center mb-2">
                                        <div class="md:w-1/3 grid content-center">
                                            <label class="block mb-1 md:mb-0 pr-4">
                                                Entidad Federativa:
                                            </label>
                                        </div>
                                        <div class="md:w-2/3">
                                            <div class="relative">
                                                <select
                                                    v-model="hechosMunicipio"
                                                    class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                    id="hechosMunicipio">
                                                    <option value="null" disabled>Seleccionar</option>
                                                    <option v-for="entidad in entidadesFederativas" :value="entidad.entidades_federativas_id">{{ entidad.name }}</option>
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                        <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="md:flex md:item-center mb-2">
                                        <div class="md:w-1/3 grid content-center">
                                            <label class="block mb-1 md:mb-0 pr-4">
                                                Delegación o Municipio:
                                            </label>
                                        </div>
                                        <div class="md:w-2/3">
                                            <div class="relative">
                                                <select
                                                    v-model="form.hechos_municipios_id"
                                                    class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                    id="hechos_municipios_id">
                                                    <option value="null" disabled>Seleccionar</option>
                                                    <option v-for="municipio in hechosMunicipios" :value="municipio.municipios_id">{{ municipio.name }}</option>
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                        <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <div class="md:flex md:item-center mb-2" v-if="form.hechos_territorios_id == 2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            País:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <div class="relative">
                                            <select
                                                v-model="form.hechos_paises_id"
                                                class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                id="pais">
                                                <option value="null" disabled>Seleccionar</option>
                                                <option v-for="pais in paises" :value="pais.paises_id">{{ pais.nombre }}</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                    <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Fecha:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <vue-date-picker v-model="form.hechos_fecha" model-type="yyyy-MM-dd" :format="'dd/MM/yyyy'" :enable-time-picker="false" locale="es" cancelText="Cancelar" selectText="Aceptar"></vue-date-picker>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <div class="col-span-2">
                                <div class="mb-2">
                                    <div class="grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Lo que conoce del lugar de los hechos:
                                        </label>
                                    </div>
                                    <div class="">
                                        <textarea id="hechos"
                                            v-model="form.conoce_hechos_relato"
                                            rows="10"
                                            placeholder="¿Qué?, ¿Cómo?, ¿Cuándo?, Lugar de los hechos, entre otros detalles"
                                            class="bg-white rounded-2xl w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal"
                                        ></textarea>
                                        <div v-if="form.errors.conoce_hechos_relato" v-text="form.errors.conoce_hechos_relato" class="text-red-500"></div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div class="col-span-2">
                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Relato de los hechos*:
                                    </label>
                                </div>
                                <div class="">
                                    <textarea id="hechos"
                                        v-model="form.hechos_relato"
                                        rows="10"
                                        placeholder="¿Qué?, ¿Cómo?, ¿Cuándo?, Lugar de los hechos, entre otros detalles"
                                        class="bg-white rounded-2xl w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal"
                                    ></textarea>
                                    <div v-if="form.errors.hechos_relato" v-text="form.errors.hechos_relato" class="text-red-500"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template #tab-content-4>
                    <div class="grid grid-cols-2 gap-4 min-h-88">
                        <div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Tipo Daño Sufrido*:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <Multiselect
                                            v-model="form.victima_danios_sufridos_id"
                                            mode="tags"
                                            valueProp="danios_sufridos_id"
                                            label="nombre"
                                            :close-on-select="false"
                                            :options="tiposDaniosSufridos"
                                        />
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Observaciones:
                                    </label>
                                </div>
                                <div class="">
                                    <textarea id="autoridad_observaciones"
                                        v-model="form.autoridad_observaciones"
                                        rows="5"
                                        class="bg-white rounded-2xl w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal"
                                    ></textarea>
                                    <div v-if="form.errors.autoridad_observaciones" v-text="form.errors.autoridad_observaciones" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Autoridades que han conocido los hechos:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <Multiselect
                                            v-model="form.autoridades_conocen_hechos"
                                            mode="tags"
                                            :close-on-select="false"
                                            :options="tipoAutoridad"
                                        />
                                </div>
                            </div>
                        </div>
                        <div v-if="form.autoridades_conocen_hechos.includes('Investigación ministerial')">
                            <p class="my-2 font-bold">Investigación Ministerial</p>

                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        ¿Denunció ante el Ministerio Público?
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="flex items-center my-2">
                                        <label for="denuncio_ministerio_publico_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.ministerio_publico_denuncia"
                                                id="denuncio_ministerio_publico_si"
                                                name="denuncio_ministerio_publico"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="denuncio_ministerio_publico_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.ministerio_publico_denuncia"
                                                id="denuncio_ministerio_publico_no"
                                                name="denuncio_ministerio_publico"
                                                type="radio"
                                                :value="0"
                                                class="w-4 h-4">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Fecha:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <vue-date-picker v-model="form.ministerio_publico_fecha_denuncia" model-type="yyyy-MM-dd" :format="'dd/MM/yyyy'" :enable-time-picker="false" locale="es" cancelText="Cancelar" selectText="Aceptar"></vue-date-picker>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Competencia
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="flex items-center my-2">
                                        <label for="competencia_ministerio_publico_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.ministerio_publico_competencia"
                                                id="competencia_ministerio_publico_si"
                                                name="competencia_ministerio_publico"
                                                type="radio"
                                                value="Federal"
                                                class="w-4 h-4">
                                            <span class="ml-2">Federal</span>
                                        </label>
                                        <label for="competencia_ministerio_publico_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.competencia_ministerio_publico"
                                                id="competencia_ministerio_publico_no"
                                                name="competencia_ministerio_publico"
                                                type="radio"
                                                value="Local"
                                                class="w-4 h-4">
                                            <span class="ml-2">Local</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Entidad Federativa:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="relative">
                                        <select
                                            v-model="form.ministerio_publico_entidades_federativas_id"
                                            class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                            id="ministerio_publico_entidades_federativas_id">
                                            <option value="null" disabled>Seleccionar</option>
                                            <option v-for="entidad in entidadesFederativas" :value="entidad.entidades_federativas_id">{{ entidad.name }}</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Violación DDHH:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="relative">
                                        <select
                                            v-model="form.ministerio_publico_violaciones_id"
                                            class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                            id="ministerio_publico_violaciones_id">
                                            <option value="null" disabled>Seleccionar</option>
                                            <option v-for="violacion in violacionesDh" :value="violacion.violaciones_id">{{ violacion.nombre }}</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Delito:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="relative">
                                        <select
                                            v-model="form.ministerio_publico_delitos_id"
                                            class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                            id="ministerio_publico_delitos_id">
                                            <option value="null" disabled>Seleccionar</option>
                                            <option v-for="delito in delitosProv" :value="delito.delitos_id">{{ delito.nombre }}</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Agencia MP:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="ministerio_publico_agencia" type="text"
                                        v-model="form.ministerio_publico_agencia"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.ministerio_publico_agencia" v-text="form.errors.ministerio_publico_agencia" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        A. P./C. I./A. C.:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="ministerio_publico_apciac" type="text"
                                        v-model="form.ministerio_publico_apciac"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.ministerio_publico_apciac" v-text="form.errors.ministerio_publico_apciac" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Estado investigación:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="ministerio_publico_estado_investigacion" type="text"
                                        v-model="form.ministerio_publico_estado_investigacion"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.ministerio_publico_estado_investigacion" v-text="form.errors.ministerio_publico_estado_investigacion" class="text-red-500"></div>
                                </div>
                            </div>
                        </div>
                        <div v-if="form.autoridades_conocen_hechos.includes('Proceso judicial')">
                            <p class="my-2 font-bold">Proceso Judicial</p>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Fecha:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <vue-date-picker v-model="form.proceso_judicial_fecha" model-type="yyyy-MM-dd" :format="'dd/MM/yyyy'" :enable-time-picker="false" locale="es" cancelText="Cancelar" selectText="Aceptar"></vue-date-picker>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Competencia
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="flex items-center my-2">
                                        <label for="proceso_judicial_competencia_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.proceso_judicial_competencia"
                                                id="proceso_judicial_competencia_si"
                                                name="proceso_judicial_competencia"
                                                type="radio"
                                                value="Federal"
                                                class="w-4 h-4">
                                            <span class="ml-2">Federal</span>
                                        </label>
                                        <label for="proceso_judicial_competencia_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.proceso_judicial_competencia"
                                                id="proceso_judicial_competencia_no"
                                                name="proceso_judicial_competencia"
                                                type="radio"
                                                value="Local"
                                                class="w-4 h-4">
                                            <span class="ml-2">Local</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Entidad Federativa:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="relative">
                                        <select
                                            v-model="form.proceso_judicial_entidades_federativas_id"
                                            class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                            id="proceso_judicial_entidades_federativas_id">
                                            <option value="null" disabled>Seleccionar</option>
                                            <option v-for="entidad in entidadesFederativas" :value="entidad.entidades_federativas_id">{{ entidad.name }}</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Violación DDHH:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="relative">
                                        <select
                                            v-model="form.proceso_judicial_violaciones_id"
                                            class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                            id="proceso_judicial_violaciones_id">
                                            <option value="null" disabled>Seleccionar</option>
                                            <option v-for="violacion in violacionesDh" :value="violacion.violaciones_id">{{ violacion.nombre }}</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Delito:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="relative">
                                        <select
                                            v-model="form.proceso_judicial_delitos_id"
                                            class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                            id="proceso_judicial_delitos_id">
                                            <option value="null" disabled>Seleccionar</option>
                                            <option v-for="delito in delitosProv" :value="delito.delitos_id">{{ delito.nombre }}</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Número de Juzgado:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="proceso_judicial_numero_juzgado" type="text"
                                        v-model="form.proceso_judicial_numero_juzgado"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.proceso_judicial_numero_juzgado" v-text="form.errors.proceso_judicial_numero_juzgado" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Número de Proceso:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="proceso_judicial_numero_proceso" type="text"
                                        v-model="form.proceso_judicial_numero_proceso"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.proceso_judicial_numero_proceso" v-text="form.errors.proceso_judicial_numero_proceso" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Estado Proceso Judicial:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="proceso_judicial_estado_proceso" type="text"
                                        v-model="form.proceso_judicial_estado_proceso"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.proceso_judicial_estado_proceso" v-text="form.errors.proceso_judicial_estado_proceso" class="text-red-500"></div>
                                </div>
                            </div>
                        </div>
                        <div v-if="form.autoridades_conocen_hechos.includes('Procedimientos ante DDHH')">
                            <p class="my-2 font-bold">Procedimiento ante organismos nacionales e internaciones DDHH</p>
                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        ¿Presentó queja, petición u otro tipo de solicitud ante organismo de DD. HH.?
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="flex items-center my-2">
                                        <label for="ddhh_queja_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.ddhh_queja"
                                                id="ddhh_queja_si"
                                                name="ddhh_queja"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="ddhh_queja_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.ddhh_queja"
                                                id="ddhh_queja_no"
                                                name="ddhh_queja"
                                                type="radio"
                                                :value="0"
                                                class="w-4 h-4">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Fecha:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <vue-date-picker v-model="form.ddhh_fecha_queja" model-type="yyyy-MM-dd" :format="'dd/MM/yyyy'" :enable-time-picker="false" locale="es" cancelText="Cancelar" selectText="Aceptar"></vue-date-picker>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Competencia
                                    </label>
                                </div>
                                <div class="md_w-2/3">
                                    <div class="flex items-center my-2">
                                        <label for="ddhh_competencia_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.ddhh_competencia"
                                                id="ddhh_competencia_si"
                                                name="ddhh_competencia"
                                                type="radio"
                                                value="Federal"
                                                class="w-4 h-4">
                                            <span class="ml-2">Federal</span>
                                        </label>
                                        <label for="ddhh_competencia_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.ddhh_competencia"
                                                id="ddhh_competencia_no"
                                                name="ddhh_competencia"
                                                type="radio"
                                                value="Local"
                                                class="w-4 h-4">
                                            <span class="ml-2">Local</span>
                                        </label>
                                        <label for="ddhh_competencia_int" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.ddhh_competencia"
                                                id="ddhh_competencia_int"
                                                name="ddhh_competencia"
                                                type="radio"
                                                value="Internacional"
                                                class="w-4 h-4">
                                            <span class="ml-2">Internacional</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Organismo:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="ddhh_organismo" type="text"
                                        v-model="form.ddhh_organismo"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.ddhh_organismo" v-text="form.errors.ddhh_organismo" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Violación DDHH:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="relative">
                                        <select
                                            v-model="form.ddhh_violaciones_id"
                                            class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                            id="ddhh_violaciones_id">
                                            <option value="null" disabled>Seleccionar</option>
                                            <option v-for="violacion in violacionesDh" :value="violacion.violaciones_id">{{ violacion.nombre }}</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Autoridad Responsable:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="ddhh_autoridad_responsable" type="text"
                                        v-model="form.ddhh_autoridad_responsable"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.ddhh_autoridad_responsable" v-text="form.errors.ddhh_autoridad_responsable" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Tipo de Resolución:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="relative">
                                        <select
                                            v-model="form.ddhh_tipo_resolucion"
                                            class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                            id="ddhh_tipo_resolucion">
                                            <option value="null" disabled>Seleccionar</option>
                                            <option value="Recomendación">Recomendación</option>
                                            <option value="Concilación">Concilación</option>
                                            <option value="Medidas Precautorias">Medidas Precautorias</option>
                                            <option value="Otra">Otra</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Folio:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="ddhh_folio" type="text"
                                        v-model="form.ddhh_folio"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.ddhh_folio" v-text="form.errors.ddhh_folio" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Estado Actual:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="ddhh_estado_actual" type="text"
                                        v-model="form.ddhh_estado_actual"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.ddhh_estado_actual" v-text="form.errors.ddhh_estado_actual" class="text-red-500"></div>
                                </div>
                            </div>
                        </div>
                        <div v-if="form.autoridades_conocen_hechos.includes('Otra autoridad')">
                            <p class="my-2 font-bold">Otra Autoridad</p>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Nombre Autoridad:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="otra_nombre_autoridad" type="text"
                                        v-model="form.otra_nombre_autoridad"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.otra_nombre_autoridad" v-text="form.errors.otra_nombre_autoridad" class="text-red-500"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template #tab-content-5>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        ¿Es niña/o o adolescente?*
                                    </label>
                                </div>
                                <div class="">
                                    <div class="flex items-center my-2">
                                        <label for="es_adolescente_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.es_adolescente"
                                                id="es_adolescente_si"
                                                name="es_adolescente"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="es_adolescente_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.es_adolescente"
                                                id="es_adolescente_no"
                                                name="es_adolescente"
                                                type="radio"
                                                :value="0"
                                                class="w-4 h-4">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <template v-if="parseInt(form.es_adolescente) ? true: false">
                                <p class="font-bold my-2">Datos del tutor/a</p>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Nombre(s):
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="tutor_nombres" type="text"
                                            v-model="form.tutor_nombres"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.tutor_nombres" v-text="form.errors.tutor_nombres" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Primer Apellido:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="tutor_primer_apellido" type="text"
                                            v-model="form.tutor_primer_apellido"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.tutor_primer_apellido" v-text="form.errors.tutor_primer_apellido" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Segundo Apellido:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="tutor_segundo_apellido" type="text"
                                            v-model="form.tutor_segundo_apellido"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.tutor_segundo_apellido" v-text="form.errors.tutor_segundo_apellido" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Datos de contacto:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="tutor_contacto" type="text"
                                            v-model="form.tutor_contacto"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.tutor_contacto" v-text="form.errors.tutor_contacto" class="text-red-500"></div>
                                    </div>
                                </div>
                            </template>
                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        ¿Es persona adulta mayor?*
                                    </label>
                                </div>
                                <div class="">
                                    <div class="flex items-center my-2">
                                        <label for="es_adulto_mayor_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.es_adulto_mayor"
                                                id="es_adulto_mayor_si"
                                                name="es_adulto_mayor"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="es_adulto_mayor_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.es_adulto_mayor"
                                                id="es_adulto_mayor_no"
                                                name="es_adulto_mayor"
                                                type="radio"
                                                :value="0"
                                                class="w-4 h-4">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        ¿Se encuentra en situación de calle?*
                                    </label>
                                </div>
                                <div class="">
                                    <div class="flex items-center my-2">
                                        <label for="es_situacion_calle_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.es_situacion_calle"
                                                id="es_situacion_calle_si"
                                                name="es_situacion_calle"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="es_situacion_calle_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.es_situacion_calle"
                                                id="es_situacion_calle_no"
                                                name="es_situacion_calle"
                                                type="radio"
                                                :value="0"
                                                class="w-4 h-4">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        ¿Tiene condición de discapacidad?*
                                    </label>
                                </div>
                                <div class="">
                                    <div class="flex items-center my-2">
                                        <label for="es_discapacitado_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.es_discapacitado"
                                                id="es_discapacitado_si"
                                                name="es_discapacitado"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="es_discapacitado_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.es_discapacitado"
                                                id="es_discapacitado_no"
                                                name="es_discapacitado"
                                                type="radio"
                                                :value="0"
                                                class="w-4 h-4">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <template v-if="parseInt(form.es_discapacitado) ? true: false">
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Tipo:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <div class="relative">
                                            <select
                                                v-model="form.enfoques_tipos_discapacidades_id"
                                                class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                id="enfoques_tipos_discapacidades_id">
                                                <option value="null" disabled>Seleccionar</option>
                                                <option v-for="tipoDiscapacidad in tiposDiscapacidad" :value="tipoDiscapacidad.id">{{ tipoDiscapacidad.nombre }}</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                    <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Grado de Dependencia:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <div class="relative">
                                            <select
                                                v-model="form.enfoque_grado_dependencia_id"
                                                class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                id="enfoque_grado_dependencia_id">
                                                <option value="null" disabled>Seleccionar</option>
                                                <option v-for="gradoDependencia in gradosDependencia" :value="gradoDependencia.enfoque_grado_dependencia_id">{{ gradoDependencia.nombre }}</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                    <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        ¿Es migrante?*
                                    </label>
                                </div>
                                <div class="">
                                    <div class="flex items-center my-2">
                                        <label for="es_migrante_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.es_migrante"
                                                id="es_migrante_si"
                                                name="es_migrante"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="es_migrante_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.es_migrante"
                                                id="es_migrante_no"
                                                name="es_migrante"
                                                type="radio"
                                                :value="0"
                                                class="w-4 h-4">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <template v-if="parseInt(form.es_migrante) ? true: false">
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            País de origen:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <div class="relative">
                                            <select
                                                v-model="form.pais_origen_id"
                                                class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                id="pais_origen_id">
                                                <option value="null" disabled>Seleccionar</option>
                                                <option v-for="pais in paises" :value="pais.paises_id">{{ pais.nombre }}</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                    <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            País de destino:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <div class="relative">
                                            <select
                                                v-model="form.pais_destino_id"
                                                class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                id="pais_destino_id">
                                                <option value="null" disabled>Seleccionar</option>
                                                <option v-for="pais in paises" :value="pais.paises_id">{{ pais.nombre }}</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                    <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        ¿Habla español?*
                                    </label>
                                </div>
                                <div class="">
                                    <div class="flex items-center my-2">
                                        <label for="habla_espaniol_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.habla_espaniol"
                                                id="habla_espaniol_si"
                                                name="habla_espaniol"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="habla_espaniol_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.habla_espaniol"
                                                id="habla_espaniol_no"
                                                name="habla_espaniol"
                                                type="radio"
                                                :value="0"
                                                class="w-4 h-4">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <template v-if="parseInt(form.habla_espaniol) ? false : true">
                                <div class="mb-2">
                                    <div class="grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            ¿Requiere traductor/a?
                                        </label>
                                    </div>
                                    <div class="">
                                        <div class="flex items-center my-2">
                                            <label for="requiere_traductor_si" class="inline-flex items-center">
                                                <input
                                                    v-model="form.requiere_traductor"
                                                    id="requiere_traductor_si"
                                                    name="requiere_traductor"
                                                    type="radio"
                                                    :value="1"
                                                    class="w-4 h-4">
                                                <span class="ml-2">Si</span>
                                            </label>
                                            <label for="requiere_traductor_no" class="ml-4 inline-flex items-center">
                                                <input
                                                    v-model="form.requiere_traductor"
                                                    id="requiere_traductor_no"
                                                    name="requiere_traductor"
                                                    type="radio"
                                                    :value="0"
                                                    class="w-4 h-4">
                                                <span class="ml-2">No</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        ¿Pertenece a población/comunidad indígena?*
                                    </label>
                                </div>
                                <div class="">
                                    <div class="flex items-center my-2">
                                        <label for="es_indigena_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.es_indigena"
                                                id="es_indigena_si"
                                                name="es_indigena"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="es_indigena_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.es_indigena"
                                                id="es_indigena_no"
                                                name="es_indigena"
                                                type="radio"
                                                :value="0"
                                                class="w-4 h-4">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <template v-if="parseInt(form.es_indigena) ? true: false">
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            ¿A Cúal?
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="poblacion_indigena" type="text"
                                            v-model="form.poblacion_indigena"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.poblacion_indigena" v-text="form.errors.poblacion_indigena" class="text-red-500"></div>
                                    </div>
                                </div>
                            </template>
                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        ¿Refugiado/a?*
                                    </label>
                                </div>
                                <div class="">
                                    <div class="flex items-center my-2">
                                        <label for="es_refugiado_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.es_refugiado"
                                                id="es_refugiado_si"
                                                name="es_refugiado"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="es_refugiado_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.es_refugiado"
                                                id="es_refugiado_no"
                                                name="es_refugiado"
                                                type="radio"
                                                :value="0"
                                                class="w-4 h-4">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        ¿Es asilado/a político/a ?*
                                    </label>
                                </div>
                                <div class="">
                                    <div class="flex items-center my-2">
                                        <label for="es_asilado_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.es_asilado"
                                                id="es_asilado_si"
                                                name="es_asilado"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="es_asilado_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.es_asilado"
                                                id="es_asilado_no"
                                                name="es_asilado"
                                                type="radio"
                                                :value="0"
                                                class="w-4 h-4">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <template v-if="(parseInt(form.es_refugiado) || parseInt(form.es_asilado)) ? true: false">
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            ¿Ha iniciado algún trámite para obtener esta condición?
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="tramite_condicion" type="text"
                                            v-model="form.tramite_condicion"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.tramite_condicion" v-text="form.errors.tramite_condicion" class="text-red-500"></div>
                                    </div>
                                </div>
                            </template>
                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        ¿Es defensor/a de derechos humanos?*
                                    </label>
                                </div>
                                <div class="">
                                    <div class="flex items-center my-2">
                                        <label for="es_defensor_ddhh_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.es_defensor_ddhh"
                                                id="es_defensor_ddhh_si"
                                                name="es_defensor_ddhh"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="es_defensor_ddhh_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.es_defensor_ddhh"
                                                id="es_defensor_ddhh_no"
                                                name="es_defensor_ddhh"
                                                type="radio"
                                                :value="0"
                                                class="w-4 h-4">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <template v-if="parseInt(form.es_defensor_ddhh) ? true : false">
                                <div class="mb-2">
                                    <div class="grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            ¿Pertenece a una institución?
                                        </label>
                                    </div>
                                    <div class="">
                                        <div class="flex items-center my-2">
                                            <label for="pertenece_institucion_si" class="inline-flex items-center">
                                                <input
                                                    v-model="form.pertenece_institucion"
                                                    id="pertenece_institucion_si"
                                                    name="pertenece_institucion"
                                                    type="radio"
                                                    :value="1"
                                                    class="w-4 h-4">
                                                <span class="ml-2">Si</span>
                                            </label>
                                            <label for="pertenece_institucion_no" class="ml-4 inline-flex items-center">
                                                <input
                                                    v-model="form.pertenece_institucion"
                                                    id="pertenece_institucion_no"
                                                    name="pertenece_institucion"
                                                    type="radio"
                                                    :value="0"
                                                    class="w-4 h-4">
                                                <span class="ml-2">No</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <template v-if="parseInt(form.pertenece_institucion) ? true : false">
                                    <div class="md:flex md:item-center mb-2">
                                        <div class="md:w-1/3 grid content-center">
                                            <label class="block mb-1 md:mb-0 pr-4">
                                                ¿Tipo de institución?
                                            </label>
                                        </div>
                                        <div class="md:w-2/3">
                                            <div class="relative">
                                                <select
                                                    v-model="form.enfoque_tipo_inst_id"
                                                    class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                    id="enfoque_tipo_inst_id">
                                                    <option value="null" disabled>Seleccionar</option>
                                                    <option v-for="tipoInstitucion in tiposInstitucion" :value="tipoInstitucion.enfoque_tipo_inst_id">{{ tipoInstitucion.nombre }}</option>
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                        <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="md:flex md:item-center mb-2">
                                        <div class="md:w-1/3 grid content-center">
                                            <label class="block mb-1 md:mb-0 pr-4">
                                                Otra
                                            </label>
                                        </div>
                                        <div class="md:w-2/3">
                                            <input  id="pertenece_institucion_otra" type="text"
                                                v-model="form.pertenece_institucion_otra"
                                                class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                            <div v-if="form.errors.pertenece_institucion_otra" v-text="form.errors.pertenece_institucion_otra" class="text-red-500"></div>
                                        </div>
                                    </div>
                                </template>

                            </template>
                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        ¿Es periodista?*
                                    </label>
                                </div>
                                <div class="">
                                    <div class="flex items-center my-2">
                                        <label for="es_periodista_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.es_periodista"
                                                id="es_periodista_si"
                                                name="es_periodista"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="es_periodista_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.es_periodista"
                                                id="es_periodista_no"
                                                name="es_periodista"
                                                type="radio"
                                                :value="0"
                                                class="w-4 h-4">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <template v-if="parseInt(form.es_periodista) ? true : false">
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            ¿Tipo de medio informativo?
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="tipo_medio_informativo" type="text"
                                            v-model="form.tipo_medio_informativo"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.tipo_medio_informativo" v-text="form.errors.tipo_medio_informativo" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            ¿Nombre del medio informativo?
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="nombre_medio_informativo" type="text"
                                            v-model="form.nombre_medio_informativo"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.nombre_medio_informativo" v-text="form.errors.nombre_medio_informativo" class="text-red-500"></div>
                                    </div>
                                </div>
                            </template>
                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        ¿Fue desplazado/a dentro del país o estado por condiciones de violencia?*
                                    </label>
                                </div>
                                <div class="">
                                    <div class="flex items-center my-2">
                                        <label for="es_desplazado_por_hecho_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.es_desplazado_por_hecho"
                                                id="es_desplazado_por_hecho_si"
                                                name="es_desplazado_por_hecho"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="es_desplazado_por_hecho_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.es_desplazado_por_hecho"
                                                id="es_desplazado_por_hecho_no"
                                                name="es_desplazado_por_hecho"
                                                type="radio"
                                                :value="0"
                                                class="w-4 h-4">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <template v-if="parseInt(form.es_desplazado_por_hecho) ? true : false">
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Entidad de salida:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <div class="relative">
                                            <select
                                                v-model="form.entidad_salida_id"
                                                class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                id="entidad_salida_id">
                                                <option value="null" disabled>Seleccionar</option>
                                                <option v-for="entidad in entidadesFederativas" :value="entidad.entidades_federativas_id">{{ entidad.name }}</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                    <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Entidad receptora:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <div class="relative">
                                            <select
                                                v-model="form.entidad_receptora_id"
                                                class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                id="entidad_receptora_id">
                                                <option value="null" disabled>Seleccionar</option>
                                                <option v-for="entidad in entidadesFederativas" :value="entidad.entidades_federativas_id">{{ entidad.name }}</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                    <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Considera que el hecho victimizante se debió a:
                                    </label>
                                </div>
                                <div class="">
                                    <Multiselect
                                        v-model="form.es_victimizante_por"
                                        mode="tags"
                                        :close-on-select="false"
                                        :options="esVictimizantePor"
                                    />
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Información de violencia contra las mujeres:
                                    </label>
                                </div>
                                <div class="">
                                    <Multiselect
                                        v-model="form.violencia_contra_mujeres"
                                        mode="tags"
                                        :close-on-select="false"
                                        :options="violenciaContraMujeres"
                                        @open="moveScroll"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </Tabs>
        </template>
        <template #modal-footer>
            <button type="button" @click="changeTab(6)" :title="!form.saveStatus ? 'Requerido guargar todos los pasos' : ''" :disabled="!form.saveStatus"  class="btn-normal w-full sm:w-auto disabled:bg-gray-light disabled:text-black disabled:border-gray-normal disabled:border-2">Guardar</button>
        </template>
    </Modal>

    <Modal ref="modalShowData" :size="'max-w-6xl'">
        <template #modal-header>
            <div class="text-center">
                <p>Registro Estatal de Victimas</p>
            </div>
        </template>
        <template #modal-body>
            <div class="grid lg:grid-cols-2 gap-4">
                <div class="border-r border-slate-400">
                    <div class="font-bold my-2">
                        1. Datos de Atención
                    </div>
                    <div>
                        <p>Lugar de Atención: {{ form.lugar }}</p>
                        <p>Fecha de Atención: {{ form.fecha_atencion }}</p>
                        <p>Solicitud Realizada por: {{ getTipoSolicitante(form.tipo_solicitantes_id) }}</p>
                        <template v-if="form.tipo_solicitantes_id != 1">
                            <p>Nombre Completo: {{  form.solicitante_nombres }} {{  form.solicitante_primer_apellido }} {{  form.solicitante_segundo_apellido }}</p>
                        </template>
                        <template v-if="form.tipo_solicitantes_id == 2">
                            <p>Parentesco/Relación Afectiva: {{ getParentesco(form.parentesco_id) }}</p>
                        </template>
                        <template v-if="form.tipo_solicitantes_id == 4 || form.tipo_solicitantes_id == 3">
                            <p>Cargo: {{ form.cargo }}</p>
                        </template>
                        <template v-if="form.tipo_solicitantes_id == 4">
                            <p>Dependencia o Institución: {{ form.dependencia }}</p>
                        </template>
                        <template v-if="form.tipo_solicitantes_id != 1">
                            <p>Nacionalidad: {{ getNacionalidad(form.territorios_id) }}</p>
                            <p>Situación Migratorio: {{ getMigratorio(form.situacion_migratoria_id) }}</p>
                            <p>Identificación Presentada: {{ getIdentificacion(form.identificacion_id) }}</p>
                            <p>Documento Probatorio de Identidad: {{ getDocumentoIdentidad(form.identidad_probatorio_documentos_id) }}</p>
                            <template v-if="form.tipo_solicitantes_id == 4">
                                <p>Entidad Federativa: {{ getEntidadFederativa(form.entidades_federativas_id) }}</p>
                                <p>Ámbito de Dependencia: {{ getAmbitoDependencia(form.ambito_dependencias_id) }}</p>
                                <p>Nivel de Dependencia: {{ getAmbitoDependencia(form.nivel_dependencias_id) }}</p>
                            </template>
                        </template>
                    </div>
                    <div class="font-bold mb-2 mt-6">
                        2. Tipo y Datos de Víctima
                    </div>
                    <div>
                        <p>Tipo de Víctima: {{ getTipoVictima(form.tipo_victimas_id) }}</p>
                        <p>Nombre Completo: {{ form.victima_nombres }} {{ form.victima_primer_apellido }} {{ form.victima_segundo_apellido }}</p>
                        <p>Fecha de Nacimiento: {{ form.victima_fecha_nacimiento}}</p>
                        <p>CURP: {{ form.victima_curp }}</p>
                        <p>Sexo: {{ form.victima_sexos_id == 1 ? 'Hombre' : 'Mujer' }}</p>
                        <p v-if="form.tipo_victimas_id != 1">Relación con la Víctima: {{ getParentesco(form.victima_parentesco_id) }}</p>
                        <p>Estado Civil: {{ getEstadoCivil(form.estado_civil) }}</p>
                        <p>Nacionalidad: {{ getNacionalidad(form.victima_territorios_id) }}</p>
                        <template v-if="form.victima_territorios_id == 1">
                            <p>Entidad Federativa: {{ getEntidadFederativa(form.victima_entidades_federativas_id) }}</p>
                            <p>Delegación o Municipio: {{ getMunicipios(form.victima_municipios_id) }}</p>
                            <p>Población o Comunidad: {{ form.victima_poblacion }}</p>
                        </template>
                        <template v-if="form.victima_territorios_id == 2">
                            <p>País: {{ getPais(form.victima_paises_id) }}</p>
                        </template>
                        <p class="font-bold">Domicilio de Contacto del Solicitante</p>
                        <p>Nacionalidad: {{ getNacionalidad(form.solicitante_territorios_id) }}</p>
                        <template v-if="form.solicitante_territorios_id == 1">
                            <p>Entidad Federativa: {{ getEntidadFederativa(form.solicitante_entidades_federativas_id) }}</p>
                            <p>Delegación o municipio: {{ getMunicipios(form.solicitante_municipios_id) }}</p>
                        </template>
                        <template v-if="form.solicitante_territorios_id == 2">
                            <p>País: {{ getPais(form.solicitante_paises_id) }}</p>
                        </template>
                        <p>Codigo Postal: {{  form.codigo_postal  }}</p>
                        <p>Colonia: {{ form.colonia }}</p>
                        <p>Localidad: {{  form.localidad  }}</p>
                        <p>Calle: {{  form.calle }}</p>
                        <p>Número Exterior: {{ form.no_exterior }}</p>
                        <p>Número Interior: {{ form.no_interior }}</p>
                        <p>Teléfono: {{ form.telefono }}</p>
                        <p>Tipo de Teléfono: {{ getTipoTelefono(form.tipos_telefonos_id) }}</p>
                    </div>
                    <div class="font-bold mb-2 mt-6">
                        3. Relación con la Víctima Directa e identificación de la Víctima Directa
                    </div>
                    <div>
                        <p class="font-medium">Relación de la Víctima Indirecta con la Víctima Directa</p>
                        <p>Nombre Completo: {{ form.victima_directa_nombres }} {{ form.victima_directa_primer_apellido }} {{ form.victima_directa_segundo_apellido }}</p>
                        <p>Documento Probatorio de Identidad:</p>
                        <p v-for="doc in form.victima_directa_identidad_probatorio_documentos_id"> - {{ getDocumentoIdentidad(doc) }}</p>
                        <p class="font-bold">Identificación de la Víctima</p>
                        <p>¿Presenta Identificación?: {{  form.identificacion_victima == 1 ? 'Si' : 'No'  }}</p>
                        <p v-if="form.identificacion_victima == 1">Tipo de identificación: {{ getIdentificacion(form.identificacion_victima_id) }}</p>
                    </div>
                    <div class="font-bold mb-2 mt-6">
                        4. Lugar y Fecha de los Hechos
                    </div>
                    <div>
                        <p>¿Conoce el lugar de los hechos? {{ form.conoce_lugar_hechos == 1 ? 'Si' : 'No' }}</p>
                        <p v-if="form.conoce_lugar_hechos == 0">Lo que conoce del lugar de los hecho: {{ form.conoce_hechos_relato }}</p>
                        <template v-if="form.conoce_lugar_hechos == 1">
                            <p>Nacionalidad: {{ getNacionalidad(form.hechos_territorios_id) }}</p>
                            <template v-if="form.hechos_territorios_id == 1">
                                <p>Entidad Federativa: {{ getEntidadFederativa(form.hechos_entidades_federativas_id) }}</p>
                                <p>Delegación o Municipio: {{ getMunicipios(form.hechos_municipios_id) }}</p>
                            </template>
                        </template>
                        <template v-if="form.hechos_territorios_id == 2">
                            <p>País: {{ getPais(form.hechos_paises_id) }}</p>
                        </template>
                        <template v-if="form.conoce_lugar_hechos == 1">
                            <p>Codigo Postal: {{  form.hechos_codigo_postal  }}</p>
                            <p>Colonia: {{ form.hechos_colonia }}</p>
                            <p>Localidad: {{  form.hechos_localidad  }}</p>
                            <p>Calle: {{  form.hechos_calle }}</p>
                            <p>Número Exterior: {{ form.hechos_no_exterior }}</p>
                            <p>Número Interior: {{ form.hechos_no_interior }}</p>
                            <p>Fecha: {{ form.hechos_fecha }}</p>
                        </template>
                        <p>Relato de los hechos: {{ form.hechos_relato }}</p>
                    </div>
                </div>
                <div class="">
                    <div class="font-bold mb-2">
                        5. Autoridades Observaciones
                    </div>
                    <div>
                        <p>Tipo daño sufrido:</p>
                        <p v-for="danio in form.victima_danios_sufridos_id"> - {{ getTipoDanio(danio) }}</p>
                        <p>Observaciones: {{ form.autoridad_observaciones }}</p>
                        <p>Autoridades que han conocido los hechos: {{ form.autoridades_conocen_hechos.join(',') }}</p>
                        <template v-if="form.autoridades_conocen_hechos.includes('Investigación ministerial')">
                            <p class="font-bold">Investigación ministerial</p>
                            <p>¿Denunció ante el Ministerio Público?: {{ form.ministerio_publico_denuncia == 1 ? 'Si' : 'No'  }}</p>
                            <p>Fecha: {{ form.ministerio_publico_fecha_denuncia }}</p>
                            <p>Compentencia: {{ form.ministerio_publico_competencia }}</p>
                            <p>Entidad Federativa: {{ getEntidadFederativa(form.ministerio_publico_entidades_federativas_id) }}</p>
                            <p>Violación DDHH: {{ getViolacion(form.ministerio_publico_violaciones_id) }}</p>
                            <p>Delito: {{ getDelito(form.ministerio_publico_delitos_id) }}</p>
                            <p>Agencia MP: {{ form.ministerio_publico_agencia }}</p>
                            <p>A. P./C. I./A. C: {{ form.ministerio_publico_apciac }}</p>
                            <p>Estado investigación: {{ form.ministerio_publico_estado_investigacion }}</p>
                        </template>
                        <template v-if="form.autoridades_conocen_hechos.includes('Proceso judicial')">
                            <p class="font-bold">Proceso judicial</p>
                            <p>Fecha: {{ form.proceso_judicial_fecha }}</p>
                            <p>Compentencia: {{ form.proceso_judicial_competencia }}</p>
                            <p>Entidad Federativa: {{ getEntidadFederativa(form.proceso_judicial_entidades_federativas_id) }}</p>
                            <p>Violación DDHH: {{ getViolacion(form.proceso_judicial_violaciones_id) }}</p>
                            <p>Delito: {{ getDelito(form.proceso_judicial_delitos_id) }}</p>
                            <p>Número de juzgado: {{ form.proceso_judicial_numero_juzgado }}</p>
                            <p>Número de proceso: {{ form.proceso_judicial_numero_proceso }}</p>
                            <p>Estado proceso judicial: {{ form.proceso_judicial_estado_proceso }}</p>
                        </template>
                        <template v-if="form.autoridades_conocen_hechos.includes('Procedimientos ante DDHH')">
                            <p class="font-bold">Procedimientos ante DDHH</p>
                            <p>Presentó queja, petición u otro tipo de solicitud ante organismo de DD. HH?: {{ form.ddhh_queja == 1 ? 'Si' : 'No'  }}</p>
                            <p>Fecha: {{ form.ddhh_fecha_queja }}</p>
                            <p>Compentencia: {{ form.ddhh_competencia }}</p>
                            <p>Organismo: {{ form.ddhh_organismo }}</p>
                            <p>Violación DDHH: {{ getViolacion(form.ddhh_violaciones_id) }}</p>
                            <p>Autoridad responsable: {{ form.ddhh_autoridad_responsable }}</p>
                            <p>Tipo de resolución: {{ form.ddhh_tipo_resolucion }}</p>
                            <p>Folio: {{ form.ddhh_folio }}</p>
                            <p>Estado actual: {{ form.ddhh_estado_actual }}</p>
                        </template>
                        <template v-if="form.autoridades_conocen_hechos.includes('Otra autoridad')">
                            <p class="font-bold">Otra autoridad</p>
                            <p>Nombre autoridad: {{ form.otra_nombre_autoridad }}</p>
                        </template>
                    </div>
                    <div class="font-bold mb-2 mt-6">
                        6. Información Complementaria
                    </div>
                    <div>
                        <p>¿Es niña/o o adolescente? {{form.es_adolescente == 1 ? 'SI' : 'No' }}</p>
                        <template v-if="form.es_adolescente == 1">
                            <p>Nombre Completo del tutor/a: {{ form.tutor_nombres }} {{ form.tutor_primer_apellido }} {{ form.tutor_segundo_apellido }}</p>
                            <p>Datos de contacto del tutor: {{ form.tutor_contacto }}</p>
                        </template>
                        <p>¿Es persona adulta mayor? {{form.es_adulto_mayor == 1 ? 'SI' : 'No' }}</p>
                        <p>¿Se encuentra en situación de calle? {{form.es_situacion_calle == 1 ? 'SI' : 'No' }}</p>
                        <p>¿Tiene condición de discapacidad? {{form.es_discapacitado == 1 ? 'SI' : 'No' }}</p>
                        <template v-if="form.es_discapacitado == 1">
                            <p>Tipo: {{ getTipoDiscapacidad(form.enfoques_tipos_discapacidades_id) }}</p>
                            <p>Grado de dependencia: {{ getNivelDependencia(form.enfoque_grado_dependencia_id) }}</p>
                        </template>
                        <p>¿Es migrante? {{ form.es_migrante == 1 ? 'SI' : 'No' }}</p>
                        <template v-if="form.es_migrante == 1">
                            <p>País de origen: {{ getPais(form.pais_origen_id) }}</p>
                            <p>País de destino: {{ getPais(form.pais_destino_id) }}</p>
                        </template>
                        <p>¿Habla español? {{ form.habla_espaniol == 1 ? 'SI' : 'No' }}</p>
                        <p v-if="form.habla_espaniol == 0">¿Requiere traductor/a? {{ form.requiere_traductor == 1 ? 'SI' : 'No' }}</p>
                        <p>¿Pertenece a población/comunidad indígena? {{ form.es_indigena == 1 ? 'SI' : 'No' }}</p>
                        <p v-if="form.es_indigena == 1">¿Cual?: {{ form.poblacion_indigena }}</p>
                        <p>¿Es Refugiado/a? {{ form.es_refugiado == 1 ? 'SI' : 'No' }}</p>
                        <p>¿Es asilado/a político/a? {{ form.es_asilado == 1 ? 'SI' : 'No' }}</p>
                        <p v-if="form.es_refugiado == 1 || form.es_asilado == 1">Ha iniciado algún trámite para obtener esta condición: {{ form.tramite_condicion }}</p>
                        <p>¿Es defensor/a de derechos humanos? {{ form.es_defensor_ddhh == 1 ? 'SI' : 'No' }}</p>
                        <template v-if="form.es_defensor_ddhh == 1">
                            <p>¿Pertenece a una institución? {{ form.pertenece_institucion == 1 ? 'SI' : 'No' }}</p>
                            <p v-if="form.pertenece_institucion == 1">¿Tipo de institución? {{ getTipoInstitucion(form.enfoque_tipo_inst_id) }}</p>
                            <p v-if="form.pertenece_institucion_otra">Otra: {{ form.pertenece_institucion_otra }}</p>
                        </template>
                        <p>¿Es Periodista? {{ form.es_periodista == 1 ? 'SI' : 'No' }}</p>
                        <template v-if="form.es_periodista == 1">
                            <p>¿Tipo de medio informativo? {{ form.tipo_medio_informativo }}</p>
                            <p>¿Nombre del medio informativo? {{ form.nombre_medio_informativo }}</p>
                        </template>
                        <p>¿Fue desplazado/a dentro del país o estado por condiciones de violencia? {{ form.es_desplazado_por_hecho == 1 ? 'SI' : 'No' }}</p>
                        <template v-if="form.es_desplazado_por_hecho == 1">
                            <p>Entidad de salida: {{ getEntidadFederativa(form.entidad_salida_id) }}</p>
                            <p>Entidad receptora: {{ getEntidadFederativa(form.entidad_receptora_id) }}</p>
                        </template>
                        <p>Considera que el hecho victimizante se debió a: {{ form.es_victimizante_por.join(',') }}</p>
                        <p>Información de violencia contra las mujeres: {{ form.violencia_contra_mujeres.join(',') }}</p>
                    </div>
                    <div class="font-bold mb-2 mt-6">
                        Documentación
                    </div>
                    <div>
                        <p><a class="hover:underline" v-if="form.expediente_completo != null" target="blank" download :href="form.expediente_completo">- Expediente competo</a></p>
                        <p v-if="!empty(form.escaner_ingreso) || !empty(form.escaner_fud) || !empty(form.escaner_acta) || !empty(form.escaner_curp) || !empty(form.escaner_ine) || !empty(form.escaner_domicilio) || !empty(form.escaner_calidad_victima) || !empty(form.escaner_cedula) || !empty(form.escaner_noti_elect) || !empty(form.escaner_acuerdo)">
                            <a class="hover:underline" target="blank" download :href="'/rev/descarga-completo/'+form.generales_id" title="Agrupación de documentos escaneados">- Documento General</a>
                        </p>
                        <p><a class="hover:underline" v-if="form.escaner_ingreso != null" target="blank" download :href="form.escaner_ingreso">- Solicitud de ingreso</a></p>
                        <p><a class="hover:underline" v-if="form.escaner_fud != null" target="blank" download :href="form.escaner_fud">- FUD</a></p>
                        <p><a class="hover:underline" v-if="form.escaner_acta != null" target="blank" download :href="form.escaner_acta">- Acta de nacimiento</a></p>
                        <p><a class="hover:underline" v-if="form.escaner_curp != null" target="blank" download :href="form.escaner_curp">- CURP</a></p>
                        <p><a class="hover:underline" v-if="form.escaner_ine != null" target="blank" download :href="form.escaner_ine">- INE</a></p>
                        <p><a class="hover:underline" v-if="form.escaner_domicilio != null" target="blank" download :href="form.escaner_domicilio">- Domicilio</a></p>
                        <p><a class="hover:underline" v-if="form.escaner_calidad_victima != null" target="blank" download :href="form.escaner_calidad_victima">- Constancia calidad de victima</a></p>
                        <p><a class="hover:underline" v-if="form.escaner_cedula != null" target="blank" download :href="form.escaner_cedula">- Cédula</a></p>
                        <p><a class="hover:underline" v-if="form.escaner_noti_elect != null" target="blank" download :href="form.escaner_noti_elect">- Notificación Electrónica</a></p>
                        <p><a class="hover:underline" v-if="form.escaner_acuerdo != null" target="blank" download :href="form.escaner_acuerdo">- Acuerdo</a></p>
                    </div>
                </div>
            </div>
        </template>
        <template #modal-footer>
            <!-- Puedes agregar contenido al pie de la modal si es necesario -->
        </template>
    </Modal>

    <Modal ref="modalEscaneoRev">
        <template #modal-header>
            <p>{{ fileUploaded ? 'Actualizar escaneo' : 'Subir Escaneo' }}</p>
        </template>
        <template #modal-body>
            <div class="w-full max-w-md">
                <span class="relative top-0.5"><strong>Archivos subidos:</strong></span>
                <table>
                   <tr>
                     <td v-if="form.escaner_ingreso != null">-Solicitud de ingreso</td>
                     <td v-if="form.escaner_fud != null">-FUD</td>
                   </tr>
                   <tr>
                     <td v-if="form.escaner_acta != null">-Acta de nacimiento</td>
                     <td v-if="form.escaner_curp != null">-CURP</td>
                   </tr>
                   <tr>
                     <td v-if="form.escaner_ine != null">-INE</td>
                     <td v-if="form.escaner_domicilio != null">-Domicilio</td>
                   </tr>
                   <tr>
                     <td v-if="form.escaner_calidad_victima != null">-Constancia calidad de victima</td>
                    <td v-if="form.escaner_cedula != null">-Cédula</td>
                   </tr>
                   <tr>
                     <td v-if="form.escaner_noti_elect != null">-Notificación Electrónica</td>
                     <td v-if="form.escaner_acuerdo != null">-Acuerdo</td>
                   </tr>
                </table>

                <select
                id="selectFormatoRev"
                  class="block appearance-none w-full bg-white border border-white px-4 mb-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                  @change="updateTipoFormatoRev"
                  v-model="formfile.tipo_formato"
                >
                    <option value="" disabled selected>Seleccionar formato a subir o actualizar</option>
                    <option value="Solicitud de ingreso">Solicitud de ingreso</option>
                    <option value="FUD">FUD</option>
                    <option value="Acta de nacimiento">Acta de Nacimiento</option>
                    <option value="CURP">CURP</option>
                    <option value="Identificación Oficial">Identificacion Oficial</option>
                    <option value="Comprobante de domicilio">Comprobante de domicilio</option>
                    <option value="Constancia de calidad de víctima">Constancia de calidad de víctima</option>
                    <option value="Cédula">Cédula</option>
                    <option value="Notificación electrónica">Notificación electrónica</option>
                    <option value="Acuerdo">Acuerdo</option>
                </select>

                <div  :class="loadingFile ? 'hidden' : ''">
                    <div class="md:flex md:items-center mb-2">
                        <div class="md:w-2/3 flex items-center">
                            <label class="block mb-1 md:mb-0 pr-2" for="inline-full-name">
                                <span class=" relative top-0.5">Seleccionar archivo:</span>
                            </label>
                            <label for="file" class=" w-full inline-flex justify-center items-center rounded-full bg-yellow-normal px-2 py-2 hover:bg-cherry hover:text-white sm:w-auto cursor-pointer">
                                {{ fileUploaded ? 'Actualizar archivo' : 'Añadir archivo' }}
                                   <input
                                    type="file"
                                    id="file"
                                    ref="file"
                                    name="file"
                                    class="hidden"
                                   @change="onFileSelected"
                                   accept=".pdf"
                                    />
                            </label>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center">
                    <div v-if="loadingFile" class="border-gray-400 h-10 w-10 animate-spin rounded-full border-4 border-t-blue-normal" />
                </div>
            <span v-if="selectedFileName" class="ml-10">{{ selectedFileName }}</span>
           </div>
        </template>
        <template #modal-footer>
            <button type="button" @click=" fileUploaded ? confirmarDocumento() : saveFileRevData()"  class="btn-normal w-full sm:w-auto">{{ fileUploaded ? 'Actualizar' : 'Guardar' }}</button>
        </template>
    </Modal>
    <Modal ref="modalPrinter">
        <template #modal-header>
            <p>Imprimir Víctima</p>
        </template>
        <template #modal-body>
            <div class="w-full max-w-md">
                <div class="md:flex md:items-center mb-2">
                 <div class="md:w-2/3 flex items-center">
                    <label class="block mb-1 md:mb-0 pr-2" for="inline-full-name">
                        <span class=" relative top-0.5">Seleccionar el formato a imprimir:</span>
                          </label>
                 </div>
             </div>
               <select
                id="selectPrintRev"
                  class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                  @change="updateTipoFormato"
                  v-model="formPrinter.tipo_formato"
                >

                <option value="" disabled selected>Seleccionar formato</option>
                <option value="FUD" v-if="formPrinter.fud_completo ? true : false">FUD</option>
                <template v-if="formPrinter.estatus_atencion === 'REV' || formPrinter.estatus_atencion === 'REV sin PC' || formPrinter.estatus_atencion === 'RENAVI'">
                    <option value="CEDULA">Cédula</option>
                    <option value="ACUERDO">Acuerdo</option>
                </template>
                </select>
           </div>
        </template>
        <template #modal-footer>
                <button type="button" @click="printerFile(captura)"  class="btn-normal w-full sm:w-auto">Aceptar</button>
        </template>
    </Modal>
    <Modal ref="modalEstatus">
        <template #modal-header>
            <p>Cambiar Estatus</p>
        </template>
        <template #modal-body>
            <div class="w-full max-w-md">
                <div class="md:flex md:items-center mb-2">
                 <div class="md:w-2/3 flex items-center">
                    <label class="block mb-1 md:mb-0 pr-2" for="inline-full-name">
                        <span class=" relative top-0.5">Seleccionar el nuevo estatus:</span>
                          </label>
                 </div>
             </div>
               <select
                id="selectStatusRev"
                  class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                  v-model="formStatus.estatus_atencion"
                >
                <option value="" disabled selected>Seleccionar estatus</option>
                <option value="REV" v-if="formStatus.log_estatus_atencion === 'Esperando Aprobación REV'">REV</option>
                <option value="REV sin PC" v-if="formStatus.log_estatus_atencion === 'Esperando Aprobación REV sin PC'">REV sin PC</option>
                <option value="No Aprobado" v-if="formStatus.log_estatus_atencion === 'Esperando Aprobación REV'">No aprobado</option>
                </select>
           </div>
        </template>
        <template #modal-footer>
                <button type="button" @click="changeEstatus()"  class="btn-normal w-full sm:w-auto">Aceptar</button>
        </template>
    </Modal>

</template>
<style src="@vueform/multiselect/themes/default.css"></style>
<style>
  .multiselect {
    border-radius: 20px !important;
  }
</style>
<script setup>
import { ref, watch, onMounted, computed } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import Pagination from "../Components/Pagination.vue";
import debounce from "lodash/debounce";
import moment from 'moment';
import { useNotification } from "@kyvg/vue3-notification";
import Swal from 'sweetalert2'
import useErrors from '../Services/errors';

let props = defineProps({
    capturas: Object,
    filters: Object,
    tiposSolicitantes: Array,
    parentescos: Array,
    nacionalidades: Array,
    paises: Array,
    entidadesFederativas: Array,
    municipios: Array,
    situacionesMigratorias: Array,
    documentosProbatorios: Array,
    documentosProbatoriosIdentidad: Array,
    ambitosDependencias: Array,
    tipoVictimas: Array,
    sexos: Array,
    tipoTelefonos: Array,
    tiposDaniosSufridos: Array,
    tiposDiscapacidad: Array,
    gradosDependencia: Array,
    tiposInstitucion: Array,
    violacionesDh: Array,
    delitosProv: Array,
    estadosCivil: Array,
    tipoAutoridad: Array,

});

const customErrors = useErrors();
const { notify }  = useNotification();
const page = usePage();
const generalesId = computed(function () {
    return page.props.flash.generalesId;
});
const search = ref(props.filters.search);
const startDate = ref(moment().subtract(1, 'month').format('YYYY-MM-DD'));
const endDate = ref(moment().format('YYYY-MM-DD'));
const currentStep = ref(1);
const currentStepVictima = ref(1);
const modalRev = ref(null);
const modalEscaneoRev = ref(null);
const modalPrinter = ref(null);
const modalEstatus = ref(null);
const fileUploaded = ref(false);
const selectedFile = ref(null);
const selectedFileName = ref(null);
const selectedGeneralId = ref(null);
const tipo_formato = ref(null);
const modalShowData = ref(null);
const victimaMunicipio = ref(null);
let victimaMunicipios = ref([]);
const solicitanteMunicipio = ref(null);
let solicitanteMunicipios = ref([]);
const hechosMunicipio = ref(null);
let hechosMunicipios = ref([]);
const user_role = computed(() => usePage().props.auth.user.role_id);
const user_area = computed(() => usePage().props.auth.user.area_id);
const loadingFile = ref(false);

const swalWithBootstrapButtons = Swal.mixin({
  customClass: {
    confirmButton: "btn-normal text-black",
    cancelButton: "btn-close"
  },
  buttonsStyling: false,
  reverseButtons: true,
});

// computed step 6
let step6Field = computed(function() {
    return !empty(form.es_adolescente)
        && !empty(form.es_adulto_mayor)
        && !empty(form.es_situacion_calle)
        && !empty(form.es_discapacitado)
        && !empty(form.es_migrante)
        && !empty(form.habla_espaniol)
        && !empty(form.es_indigena)
        && !empty(form.es_refugiado)
        && !empty(form.es_asilado)
        && !empty(form.es_defensor_ddhh)
        && !empty(form.es_periodista)
        && !empty(form.es_desplazado_por_hecho);
});

let esVictimizantePor = ['Religión o Creencias', 'Preferencia u Orientación Sexual', 'Identidad o Expresión de Género', 'Sexo', 'Raza', 'Otro'];
let violenciaContraMujeres = ['Psicológica', 'Física', 'Económica', 'Patrimonial', 'Sexual', 'Obstétrica', 'Feminicida', 'Otro'];

let tabItems = [
    { name: 'Datos de Atención', status: 'block', validation: 'pending'  },
    { name: 'Tipo y Datos de Víctima', status: 'hidden', validation: 'pending'  },
    { name: 'Relación con VD e Identificación VD', status: 'hidden', validation: 'pending'  },
    { name: 'Lugar y Fecha de los Hechos', status: 'hidden', validation: 'pending'  },
    { name: 'Autoridades Observaciones', status: 'hidden', validation: 'pending'  },
    { name: 'Información Complementaria', status: 'hidden', validation: 'pending'  },
];

let tabsItemVictima = [
    { name: 'Víctima', status: 'block', validation: 'pending' },
    { name: 'Domicilio de Contacto', status: 'hidden', validation: 'pending' },
];

let form = useForm({
    newOpen: true,
    generales_id: null,
    numeroPaso: 1,
    numeroSubPaso: 1,

    // paso 1
    lugar: '',
    fecha_atencion: null,
    tipo_solicitantes_id: null,
    solicitante_nombres: '',
    solicitante_primer_apellido: '',
    solicitante_segundo_apellido: '',
    parentesco_id: null,

    cargo: '',
    dependencia: '',
    territorios_id: null,
    situacion_migratoria_id: null,
    identificacion_id: null,
    identidad_probatorio_documentos_id: null,
    entidades_federativas_id: null,
    ambito_dependencias_id: null,
    nivel_dependencias_id: null,

    // paso 2
    tipo_victimas_id: 0,
    victima_nombres: '',
    victima_primer_apellido: '',
    victima_segundo_apellido: '',
    victima_fecha_nacimiento: '',
    victima_curp: '',
    victima_sexos_id: null,
    estado_civil: '',
    victima_territorios_id: null,
    victima_paises_id: null,
    victima_entidades_federativas_id: null,
    victima_municipios_id: null,
    victima_poblacion: '',

    calle: '',
    no_exterior: '',
    no_interior: '',
    colonia: '',
    codigo_postal: '',
    localidad: '',
    telefono: '',
    tipos_telefonos_id: null,
    solicitante_territorios_id: null,
    solicitante_paises_id: null,
    solicitante_entidades_federativas_id: null,
    solicitante_municipios_id: null,

    // paso 3
    victima_directa_nombres: '',
    victima_directa_primer_apellido: '',
    victima_directa_segundo_apellido: '',
    victima_directa_identidad_probatorio_documentos_id: [],
    victima_parentesco_id: null,
    identificacion_victima: null,
    identificacion_victima_id: null,

    // paso 4
    conoce_lugar_hechos: null,
    hechos_calle: '',
    hechos_no_exterior: '',
    hechos_no_interior: '',
    hechos_colonia: '',
    hechos_codigo_postal: '',
    hechos_localidad: '',
    hechos_territorios_id: null,
    hechos_paises_id: null,
    hechos_entidades_federativas_id: null,
    hechos_municipios_id: null,
    hechos_fecha: '',
    hechos_relato: '',
    conoce_hechos_relato: '',

    // paso 5
    victima_danios_sufridos_id: [],
    autoridad_observaciones: '',
    autoridades_conocen_hechos: [],

    ministerio_publico_denuncia: null,
    ministerio_publico_fecha_denuncia: '',
    ministerio_publico_competencia: null,
    ministerio_publico_entidades_federativas_id: null,
    ministerio_publico_violaciones_id: null,
    ministerio_publico_delitos_id: null,
    ministerio_publico_agencia: '',
    ministerio_publico_apciac: '',
    ministerio_publico_estado_investigacion: '',

    proceso_judicial_fecha: '',
    proceso_judicial_competencia: null,
    proceso_judicial_entidades_federativas_id: null,
    proceso_judicial_violaciones_id: null,
    proceso_judicial_delitos_id: null,
    proceso_judicial_numero_juzgado: '',
    proceso_judicial_numero_proceso: '',
    proceso_judicial_estado_proceso: '',

    ddhh_queja: null,
    ddhh_fecha_queja: '',
    ddhh_competencia: null,
    ddhh_organismo: '',
    ddhh_violaciones_id: null,
    ddhh_autoridad_responsable: '',
    ddhh_tipo_resolucion: '',
    ddhh_folio: '',
    ddhh_estado_actual: '',

    otra_nombre_autoridad: '',

    // paso 6
    es_adolescente: null,
    tutor_nombres: '',
    tutor_primer_apellido: '',
    tutor_segundo_apellido: '',
    tutor_contacto: '',

    es_adulto_mayor: null,
    es_situacion_calle: null,

    es_discapacitado: null,
    enfoques_tipos_discapacidades_id: null,
    enfoque_grado_dependencia_id: null,

    es_migrante: null,
    pais_origen_id: null,
    pais_destino_id: null,

    habla_espaniol: null,
    requiere_traductor: null,

    es_indigena: null,
    poblacion_indigena: '',

    es_refugiado: null,
    es_asilado: null,
    tramite_condicion: '',

    es_defensor_ddhh: null,
    pertenece_institucion: null,
    enfoque_tipo_inst_id: null,
    pertenece_institucion_otra: '',

    es_periodista: null,
    tipo_medio_informativo: '',
    nombre_medio_informativo: '',

    es_desplazado_por_hecho: null,
    entidad_salida_id: null,
    entidad_receptora_id: null,

    es_victimizante_por:[],
    violencia_contra_mujeres:[],

    //docs
    escaner_rev: null,
    escaner_ingreso: null,
    escaner_fud: null,
    escaner_acta: null,
    escaner_curp: null,
    escaner_ine: null,
    escaner_domicilio: null,
    escaner_calidad_victima: null,
    escaner_cedula: null,
    escaner_noti_elect: null,
    escaner_acuerdo: null,
    expediente_completo: null,

    saveStatus: false,
    fud_completo: 0,
});

let formfile = useForm({
tipo_formato:  "",
file: selectedFile.value,
generales_id: selectedGeneralId,

});
let formPrinter = useForm({
        tipo_formato: "",
        generales_id: "",
        fud_completo: 0,
        estatus_atencion: ''
    });

let formStatus = useForm({
    estatus_atencion: '',
    generales_id: null,
    log_estatus_atencion: '',
});

onMounted(() => {
    let searchParams = new URLSearchParams(window.location.search);
    if (searchParams.has('modal') && searchParams.has('data')) {
        currentStep.value = 1;
        currentStepVictima.value = 1;

        if (searchParams.get('modal') == 'edit') {
            showFormData({id: searchParams.get('data')});
        }

        if (searchParams.get('modal') == 'show') {
            showData({id: searchParams.get('data')});
        }
    }

    if (searchParams.has('startDate')) {
        startDate.value = searchParams.get('startDate');
    }

    if (searchParams.has('endDate')) {
        endDate.value = searchParams.get('endDate');
    }

    setWatchers();
})


const updateTipoFormatoRev = () => {
    fileUploaded.value = false;
  const tipoFormatoSeleccionado = formfile.tipo_formato;
  hasFileForTipoFormato(tipoFormatoSeleccionado)
};

const hasFileForTipoFormato = (tipoFormato) => {
  const fieldMappings = {
    "Solicitud de ingreso": "escaner_ingreso",
    "FUD": "escaner_fud",
    "Acta de nacimiento": "escaner_acta",
    "CURP": "escaner_curp",
    "Identificación Oficial": "escaner_ine",
    "Comprobante de domicilio": "escaner_domicilio",
    "Constancia de calidad de víctima": "escaner_calidad_victima",
    "Cédula": "escaner_cedula",
    "Notificación electrónica": "escaner_noti_elect",
    "Acuerdo": "escaner_acuerdo"
  };
  const field = fieldMappings[tipoFormato];
  if (field && form[field] !== null) {
    fileUploaded.value = true;
  }
};



function changeEstatus() {
    if (!empty(formStatus.estatus_atencion)) {
        //HoldOn.open({ message: "Procesando datos, por favor espere." });

        formStatus.post("rev/status/"+formStatus.generales_id, {
            onError: (errors) => {
                notify({
                    title: "¡Error!",
                    text: "Hubo un error al cambiar el estatus.",
                    type: "error"
                });
                //HoldOn.close();
            },
            onSuccess: (data) => {
                notify({
                    title: "¡Éxito!",
                    text: "Se cambio el estatus correctamente.",
                    type: "success"
                });
              modalEstatus.value.hide();
              //HoldOn.close();
            },
        });
    } else {
        notify({
            title: "¡Estatus Vacío!",
            text: "Debe seleccionar un estatus.",
            type: "warn"
        });
    }
}

function showEstatus(data) {
    formStatus.generales_id = data.id;
    formStatus.estatus_atencion = data.estatus_atencion;
    formStatus.log_estatus_atencion = data.estatus_atencion;
    modalEstatus.value.show();
}

function confirmarDocumento() {
    swalWithBootstrapButtons.fire({
        title: 'Confirmar actualización',
        text: 'Esta acción reemplazará el documento actual.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: `Aceptar`,
        cancelButtonText: 'Cerrar',
    }).then((result) => {
      if (result.isConfirmed) {
        saveFileRevData();
      }
    });
}

function showPrinter(data) {
    modalPrinter.value.show();
    formPrinter.generales_id = data.id;
    formPrinter.fud_completo = data.fud_completo;
    formPrinter.estatus_atencion = data.estatus_atencion;
}

function printerFile() {
    if (formPrinter.tipo_formato && formPrinter.generales_id != null) {
        console.log(formPrinter.tipo_formato);
        console.log(formPrinter.generales_id);
        axios.get(`rev/imprimir-rev/${formPrinter.tipo_formato}/${formPrinter.generales_id}`, {
            responseType: 'blob'
        })
        .then(response => {
            const formatoSeleccionado = formPrinter.tipo_formato;
            const nombreArchivo = `${formatoSeleccionado}.docx`;
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', nombreArchivo);
        document.body.appendChild(link);
        link.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(link);
        modalPrinter.value.hide();
        formPrinter.reset();
        form.reset();
        form.clearErrors()
        //HoldOn.close();

    })
        .catch(error => {
            console.error(error);
            //HoldOn.close();
        });
    } else {
        notify({
            title: "¡Formato Vacío!",
            text: "Necesitas seleccionar un formato para descargarlo.",
            type: "warn"
        });
    }
}

function filterIndex() {
    let objFilter = {};

    if (search.value) {
        objFilter.search = search.value;
    }

    if (startDate.value) {
        objFilter.startDate = startDate.value;
    }

    if (endDate.value) {
        objFilter.endDate = endDate.value;
    }

    objFilter.filter = 1;

    router.get(
        '/rev',
        objFilter,
        {
          preserveState: true,
          replace: true,
        }
      );
}

let changeTab = (index) => {
    if (index == 6) {
        form.numeroPaso = index+1;
        currentStep.value = 1;
    } else {
        form.numeroPaso = currentStep.value;
        currentStep.value = index+1;
    }

    form.numeroSubPaso = currentStepVictima.value;

    if (form.tipo_solicitantes_id == 1)
    {
        form.solicitante_nombres = form.victima_nombres;
        form.solicitante_primer_apellido = form.victima_primer_apellido;
        form.solicitante_segundo_apellido = form.victima_segundo_apellido;
    }

    if (form.tipo_victimas_id == 1) {
        form.victima_directa_nombres =  form.victima_nombres;
        form.victima_directa_primer_apellido = form.victima_primer_apellido;
        form.victima_directa_segundo_apellido = form.victima_segundo_apellido;
    }

    if (form.generales_id) {
        form.patch("/rev/" + form.generales_id, {
            onError: (errors) => {
                currentStep.value = form.numeroPaso;
                validateFormStep();
                //HoldOn.close();
                customErrors.showErrors(form);
            },
            onSuccess: (data) => {
                resetTab(currentStep.value-1);
                notify({
                    title: "¡Éxito!",
                    text: "Datos guardados correctamente.",
                    type: "success"
                });

                validateFormStep();

                if (form.numeroPaso == 7) {
                    modalRev.value.hide();
                    form.reset();
                    form.clearErrors();
                }

                //HoldOn.close();
            },
            });
    } else {
        HoldOn.open({ message: "Procesando datos, por favor espere." });

        form.post("/rev", {
            wantsJson: true,
            onError: (errors) => {
                currentStep.value = form.numeroPaso;
                validateFormStep();
                customErrors.showErrors(form);
                HoldOn.close();
            },
            onSuccess: (data) => {
                resetTab(currentStep.value-1);
                form.reset();
                notify({
                    title: "¡Éxito!",
                    text: "Datos guardados correctamente.",
                    type: "success"
                });
                validateFormStep();
                HoldOn.close();
            },
            });
    }
};

let changeTabVictima = (index) => {
    form.numeroSubPaso = currentStepVictima.value;
    currentStepVictima.value = index+1;
    form.numeroPaso = 2;

    

    if (form.generales_id) {
        form.patch("/rev/" + form.generales_id, {
            onError: (errors) => {
                currentStepVictima.value = form.numeroSubPaso;
                validateFormStep();
                customErrors.showErrors(form);
                //HoldOn.close();
            },
            onSuccess: (data) => {
                resetTabVictima(currentStepVictima.value-1);
                notify({
                    title: "¡Éxito!",
                    text: "Datos guardados correctamente.",
                    type: "success"
                });
                validateFormStep();
                //HoldOn.close();
            },
            });
    } else {
        HoldOn.open({ message: "Procesando datos, por favor espere." });
        form.post("/rev", {
            wantsJson: true,
            onError: (errors) => {
                currentStepVictima.value = form.numeroSubPaso;
                validateFormStep();
                customErrors.showErrors(form);
                HoldOn.close();
            },
            onSuccess: (data) => {
                resetTabVictima(currentStepVictima.value-1);
                notify({
                    title: "¡Éxito!",
                    text: "Datos guardados correctamente.",
                    type: "success"
                });
                validateFormStep();
                HoldOn.close();
            },
            });
    }
};

function resetTab(index = 0) {
    tabItems.every(function(item) {
        item.status = 'hidden';
        item.validation = 'pending';
        return true;
    });
    tabItems[index].status = 'block';
    currentStep.value = index+1;


    tabsItemVictima.every(function(item) {
        item.status = 'hidden';
        item.validation = 'pending';
        return true;
    });
    tabsItemVictima[0].status = 'block';
    currentStepVictima.value = 1;
}

function resetTabVictima(index = 0) {
    tabsItemVictima.every(function(item) {
        item.status = 'hidden';
        item.validation = 'pending';
        return true;
    });
    tabsItemVictima[index].status = 'block';
    currentStepVictima.value = index+1;
}

function showFormData(data = null) {
    resetTab(data ? 0 : 1);
    form.reset();
    form.clearErrors()

    if (data) {
        getRev(data.id);
    }

    modalRev.value.show();
}

function showEscaneoRev(data) {
    selectedFile.value = null
    selectedFileName.value = null
    selectedGeneralId.value = data.id
    modalEscaneoRev.value.show();
    //FALTA CAMBIAR EL ENDPOINT
    axios.get('/rev/'+data.id)
        .then(res => {
            let data = res.data.data;

            form.escaner_rev= data.url_escaner_rev;
            form.escaner_ingreso = data.url_escaner_solicitud_ingreso;
            form.escaner_fud = data.url_escaner_fud;
            form.escaner_acta = data.url_escaner_acta_nacimiento;
            form.escaner_curp = data.url_escaner_curp;
            form.escaner_ine = data.url_escaner_iden_oficial;
            form.escaner_domicilio = data.url_escaner_comprobante_domicilio;
            form.escaner_calidad_victima = data.url_escaner_calidad_victima;
            form.escaner_cedula = data.url_escaner_cedula;
           form.escaner_noti_elect = data.url_escaner_noti_electronica;
           form.escaner_acuerdo = data.url_escaner_acuerdo;

        });
}

function onFileSelected(event) {
selectedFile.value = event.target.files[0]
selectedFileName.value =  selectedFile.value.name
}

function saveFileRevData() {
  if (!empty(formfile.tipo_formato) && !empty(selectedFile.value)) {
    formfile.file = selectedFile.value;
    formfile.generales_id = selectedGeneralId
    if (selectedFile.value.size > 50 * 1024 * 1024) {
      notify({
            title: "¡Archivo muy grande!",
            text: "El tamaño máximo permitido es de 8 MB.",
            type: "warn"
        });
      return;
    }

    loadingFile.value = true;

    formfile.post("/rev/file",
      {
        onCancel:() => {
            loadingFile.value = false;
        },
        onError: (errors) => {
            loadingFile.value = false;
            console.error("Error al subir el archivo:", errors);
            notify({
                title: "¡Error!",
                text: "Hubo un error al subir el archivo.",
                type: "error"
            });
            //HoldOn.close();
        },
        onSuccess: (data) => {
            loadingFile.value = false;
              notify({
                    title: "¡Éxito!",
                    text: "Archivo subido correctamente.",
                    type: "success"
                });
              modalEscaneoRev.value.hide();
            //HoldOn.close();
        },
      });
  } else {
    notify({
        title: "¡Archivo Vacío!",
        text: "Necesitas seleccionar un formato y un archivo para guardar.",
        type: "warn"
    });
  }
}

function showData(data) {
    form.reset();
    getRev(data.id);
    modalShowData.value.show();
}

function eventQuienSolicita () {
    let newVal = form.tipo_solicitantes_id;

    if (newVal == 1) {
        form.solicitante_nombres = '';
        form.solicitante_primer_apellido = '';
        form.solicitante_segundo_apellido = '';
        form.parentesco_id = '';
        form.cargo = '';
        form.dependencia = '';

        if (form.generales_id) {
            form.patch("/rev/" + form.generales_id, {
                onError: (errors) => {
                    validateFormStep();
                    customErrors.showErrors(form);
                    //HoldOn.close();
                },
                onSuccess: (data) => {
                    resetTab(1);
                    validateFormStep();
                    notify({
                        title: "¡Éxito!",
                        text: "Datos guardados correctamente.",
                        type: "success"
                    });
                    //HoldOn.close();
                },
                });
        } else {
            HoldOn.open({ message: "Procesando datos, por favor espere." });

            form.post("/rev", {
                wantsJson: true,
                onError: (errors) => {
                    validateFormStep();
                    customErrors.showErrors(form);
                    HoldOn.close();
                },
                onSuccess: (data) => {
                    resetTab(1);
                    validateFormStep();
                    notify({
                        title: "¡Éxito!",
                        text: "Datos guardados correctamente.",
                        type: "success"
                    });
                    HoldOn.close();
                },
                });
        }
    }

    if (newVal == 2) {
        form.cargo = '';
        form.dependencia = '';
    }

    if (newVal == 4) {
        form.parentesco_id = null;
    }
}

function eventTipoVictima () {
    if (form.tipo_victimas_id == 2) {
        //
    }
}


function getRev(id) {
    axios.get('/rev/'+id)
        .then(res => {
            let data = res.data.data;
            let autoridades = res.data.autoridades;
            form.generales_id = data.generales_id;
            form.numeroPaso = 1;
            form.numeroSubPaso = 1;

            // paso 1
            form.lugar = data.lugar_atencion;
            form.fecha_atencion = data.fecha_atencion;
            form.tipo_solicitantes_id = data.tipo_solicitantes_id;
            form.solicitante_nombres = data.solicitantes_nombres;
            form.solicitante_primer_apellido = data.solicitantes_primer_apellido;
            form.solicitante_segundo_apellido = data.solicitantes_segundo_apellido;
            form.parentesco_id = data.solicitante_parentescos_id;

            form.cargo = data.solicitante_cargo;
            form.dependencia = data.solicitante_dependencia;
            form.territorios_id = data.solicitantes_territorios_id;
            form.situacion_migratoria_id = data.solicitante_situaciones_migratorias_id;
            form.identificacion_id = data.solicitante_documentos_probatorios_id;
            form.identidad_probatorio_documentos_id = data.solicitante_identidad_probatorio_documentos_id;
            form.entidades_federativas_id = data.solicitantes_entidades_federativas_id;
            form.ambito_dependencias_id = data.solicitante_ambito_dependencias_id;
            form.nivel_dependencias_id = data.solicitante_nivel_dependencia;

            // paso 2
            form.tipo_victimas_id = data.tipo_victimas_id;

            if (data.tipo_victimas_id == 1) {
                form.victima_nombres = data.victimas_nombres;
                form.victima_primer_apellido = data.victimas_primer_apellido;
                form.victima_segundo_apellido = data.victimas_segundo_apellido;
                form.victima_fecha_nacimiento = data.victimas_fecha_nacimiento;
                form.victima_curp = data.curp;
                form.victima_sexos_id = data.sexos_id;
                form.estado_civil = data.victimas_estado_civil;
                form.victima_territorios_id = data.territorios_id;
                form.victima_paises_id = data.paises_id;
                victimaMunicipio.value = data.entidades_federativas_id;
                form.victima_entidades_federativas_id = data.entidades_federativas_id;
                form.victima_municipios_id = data.municipios_id;
                form.victima_poblacion = data.poblacion;
            } else {
                form.victima_nombres = data.victimas_indirectas_nombres;
                form.victima_primer_apellido = data.victimas_indirectas_primer_apellido;
                form.victima_segundo_apellido = data.victimas_indirectas_segundo_apellido;
                form.victima_fecha_nacimiento = data.victimas_indirectas_fecha_nacimiento;
                form.victima_curp = data.victimas_indirectas_curp;
                form.victima_sexos_id = data.victimas_indirectas_sexos_id;
                form.estado_civil = data.victimas_indirectas_estado_civil_id;
                form.victima_territorios_id = data.victimas_indirectas_territorios_id;
                form.victima_paises_id = data.victimas_indirectas_paises_id;
                victimaMunicipio.value = data.victimas_indirectas_entidades_federativas_id;
                form.victima_entidades_federativas_id = data.victimas_indirectas_entidades_federativas_id;
                form.victima_municipios_id = data.victimas_indirectas_municipios_id;
                form.victima_poblacion =data.victimas_indirectas_poblacion;
                form.victima_parentesco_id = data.victimas_indirectas_parentescos_id;
            }


            form.calle = data.calle;
            form.no_exterior = data.no_exterior;
            form.no_interior = data.no_interior;
            form.colonia = data.colonia;
            form.codigo_postal = data.codigo_postal;
            form.localidad = data.localidad;
            form.telefono = data.telefono;
            form.tipos_telefonos_id = data.tipos_telefonos_id;
            form.solicitante_territorios_id = data.notificaciones_territorios_id;
            form.solicitante_paises_id = data.notificaciones_paises_id;
            form.solicitante_entidades_federativas_id = data.notificaciones_entidades_federativas_id;
            solicitanteMunicipio.value = data.notificaciones_entidades_federativas_id;
            form.solicitante_municipios_id = data.notificaciones_municipios_id;

            // paso 3
            form.victima_directa_nombres = data.victimas_nombres;
            form.victima_directa_primer_apellido = data.victimas_primer_apellido;
            form.victima_directa_segundo_apellido = data.victimas_segundo_apellido;

            form.victima_directa_identidad_probatorio_documentos_id = data.identidad_probatorio_documentos_id ? JSON.parse(data.identidad_probatorio_documentos_id) : [];
            form.identificacion_victima = data.presenta_identificacion;
            form.identificacion_victima_id = data.tipo_identificacion_id;

            // paso 4
            form.conoce_lugar_hechos = data.hechos_conoces_lugar_hechos;
            form.hechos_calle = data.hechos_calle;
            form.hechos_no_exterior = data.hechos_num_exterior;
            form.hechos_no_interior = data.hechos_num_interior;
            form.hechos_colonia = data.hechos_colonia;
            form.hechos_codigo_postal = data.hechos_codigo_postal;
            form.hechos_localidad = data.hechos_localidad;
            form.hechos_territorios_id = data.hechos_territorios_id;
            form.hechos_paises_id = data.hechos_paises_id;
            hechosMunicipio.value = data.hechos_entidades_federativas_id;
            form.hechos_entidades_federativas_id = data.hechos_entidades_federativas_id;
            form.hechos_municipios_id = data.hechos_municipios_id;
            form.hechos_fecha = data.hechos_fecha_hecho_ini;
            form.hechos_relato = data.hechos_relato;
            form.conoce_hechos_relato = data.hechos_lo_que_conoces_lugar_hechos;

            // paso 5
            form.victima_danios_sufridos_id = data.hechos_tipo_danios ? JSON.parse(data.hechos_tipo_danios) : [];
            form.autoridades_conocen_hechos =  [];

            autoridades.forEach(function(autoridad) {
                form.autoridades_conocen_hechos.push(autoridad.nombre_autoridad);

                if (autoridad.nombre_autoridad == 'Investigación ministerial') {
                    form.ministerio_publico_denuncia = autoridad.denuncio_ante_ministerio;
                    form.ministerio_publico_fecha_denuncia = autoridad.fecha;
                    form.ministerio_publico_competencia = autoridad.competencia;
                    form.ministerio_publico_entidades_federativas_id = autoridad.entidades_federativas_id;
                    form.ministerio_publico_violaciones_id = autoridad.violaciones_id;
                    form.ministerio_publico_delitos_id = autoridad.delitos_id;
                    form.ministerio_publico_agencia = autoridad.agencia_mp;
                    form.ministerio_publico_apciac = autoridad.ap_ci_ac;
                    form.ministerio_publico_estado_investigacion = autoridad.estado_investigacion;
                    form.autoridad_observaciones = autoridad.observaciones;
                }

                if (autoridad.nombre_autoridad == 'Proceso judicial') {
                    form.proceso_judicial_fecha = autoridad.fecha;
                    form.proceso_judicial_competencia = autoridad.competencia;
                    form.proceso_judicial_entidades_federativas_id = autoridad.entidades_federativas_id;
                    form.proceso_judicial_violaciones_id = autoridad.violaciones_id;
                    form.proceso_judicial_delitos_id = autoridad.delitos_id;
                    form.proceso_judicial_numero_juzgado = autoridad.num_juzgado;
                    form.proceso_judicial_numero_proceso = autoridad.num_proceso;
                    form.proceso_judicial_estado_proceso = autoridad.estado_proceso_judicial;
                    form.autoridad_observaciones = autoridad.observaciones;
                }

                if (autoridad.nombre_autoridad == 'Procedimientos ante DDHH') {
                    form.ddhh_queja = autoridad.presento_queja_ddhh;
                    form.ddhh_fecha_queja = autoridad.fecha;
                    form.ddhh_competencia = autoridad.competencia;
                    form.ddhh_organismo = autoridad.organismo;
                    form.ddhh_violaciones_id = autoridad.violaciones_id;
                    form.ddhh_autoridad_responsable = autoridad.autoridad_responsable;
                    form.ddhh_tipo_resolucion = autoridad.tipo_resolucion;
                    form.ddhh_folio = autoridad.folio;
                    form.ddhh_estado_actual = autoridad.estado_actual;
                    form.autoridad_observaciones = autoridad.observaciones;
                }

                if (autoridad.nombre_autoridad == 'Otra autoridad') {
                    form.otra_nombre_autoridad = autoridad.nombre_otra_autoridad;
                    form.autoridad_observaciones = autoridad.observaciones;
                }
            });

            // paso 6
            form.es_adolescente = data.es_adolescente;
            form.tutor_nombres = data.nombre_tutor;
            form.tutor_primer_apellido = data.primer_apellido_tutor;
            form.tutor_segundo_apellido = data.segundo_apellido_tutor;
            form.tutor_contacto = data.datos_contacto_tutor;

            form.es_adulto_mayor = data.es_adulto_mayor;
            form.es_situacion_calle = data.es_situacion_calle;

            form.es_discapacitado = data.es_discapacitado;
            form.enfoques_tipos_discapacidades_id = data.tipo_discapacidad;
            form.enfoque_grado_dependencia_id = data.grado_dependencia;

            form.es_migrante = data.es_migrante;
            form.pais_origen_id = data.pais_origen;
            form.pais_destino_id = data.pais_destino;

            form.habla_espaniol = data.habla_espaniol;
            form.requiere_traductor = data.requiere_traductor;

            form.es_indigena = data.pertenece_poblacion_indigena;
            form.poblacion_indigena = data.cual_poblacion_indigena;

            form.es_refugiado = data.es_refugiado;
            form.es_asilado = data.es_asilado_politico;
            form.tramite_condicion = data.ha_iniciado_tramite_condicion;

            form.es_defensor_ddhh = data.es_defensor_ddhh;
            form.pertenece_institucion = data.pertenece_instutitucion;
            form.enfoque_tipo_inst_id = data.tipo_institucion;
            form.pertenece_institucion_otra = data.otra_institucion;

            form.es_periodista = data.es_periodista;
            form.tipo_medio_informativo = data.tipo_medio_informativo;
            form.nombre_medio_informativo = data.nombre_medio_informativo;

            form.es_desplazado_por_hecho = data.es_desplazado_por_hecho;
            form.entidad_salida_id = data.entidad_salida;
            form.entidad_receptora_id = data.entidad_receptora;

            form.es_victimizante_por = data.hecho_victimizante_se_debio ? JSON.parse(data.hecho_victimizante_se_debio) : [];
            form.violencia_contra_mujeres = data.informacion_violencia_contra_mujeres ? JSON.parse(data.informacion_violencia_contra_mujeres) : [];

            form.escaner_rev= data.url_escaner_rev;
            form.escaner_ingreso = data.url_escaner_solicitud_ingreso;
            form.escaner_fud = data.url_escaner_fud;
            form.escaner_acta = data.url_escaner_acta_nacimiento;
            form.escaner_curp = data.url_escaner_curp;
            form.escaner_ine = data.url_escaner_iden_oficial;
            form.escaner_domicilio = data.url_escaner_comprobante_domicilio;
            form.escaner_calidad_victima = data.url_escaner_calidad_victima;
            form.escaner_cedula = data.url_escaner_cedula;
            form.escaner_noti_elect = data.url_escaner_noti_electronica;
            form.escaner_acuerdo = data.url_escaner_acuerdo;

            form.fud_completo = data.fud_completo;

            form.expediente_completo = data.expediente_completo;

            validateFormStep();
        });
}

function validateFormStep() {
    let completed = 0;
    let completed2 = 0;
    form.saveStatus = false;

    tabItems[0].validation = 'pending';
    if(!empty(form.lugar) && !empty(form.fecha_atencion) && form.tipo_solicitantes_id != 0) {
        tabItems[0].validation = 'complete';
        completed++;

        if(form.tipo_solicitantes_id == 2 || form.tipo_solicitantes_id == 4) {
            if (!empty(form.solicitante_nombres) && !empty(form.solicitante_primer_apellido)) {
                //
            } else {
                tabItems[0].validation = 'pending';
                completed--;
            }
        }
    }

    tabsItemVictima[0].validation = 'pending';
    if (form.tipo_victimas_id != 0 && !empty(form.victima_nombres) && !empty(form.victima_primer_apellido) && !empty(form.victima_segundo_apellido) && !empty(form.victima_fecha_nacimiento)) {
        tabsItemVictima[0].validation = 'complete';
        completed2++;
    }

    tabsItemVictima[1].validation = 'pending';
    if (!empty(form.solicitante_territorios_id)) {
        tabsItemVictima[1].validation = 'complete';
        completed2++;
    }

    tabItems[1].validation = 'pending';
    if (completed2 == 2) {
        tabItems[1].validation = 'complete';
        completed++;
    }

    tabItems[2].validation = 'pending';
    if (!empty(form.victima_directa_nombres) && !empty(form.victima_directa_primer_apellido) && !empty(form.victima_directa_segundo_apellido)) {
        tabItems[2].validation = 'complete';
        completed++;
    }

    tabItems[3].validation = 'pending';
    if (!empty(form.hechos_relato) && !empty(form.hechos_relato)) {
        tabItems[3].validation = 'complete';
        completed++;
    }

    tabItems[4].validation = 'pending';
    if (form.victima_danios_sufridos_id.length) {
        tabItems[4].validation = 'complete';
        completed++;
    }

    tabItems[5].validation = 'pending';
    if (!empty(form.es_adolescente) && !empty(form.es_adulto_mayor) && !empty(form.es_situacion_calle) && !empty(form.es_discapacitado) && !empty(form.es_migrante) && !empty(form.habla_espaniol) && !empty(form.es_indigena) && !empty(form.es_refugiado) && !empty(form.es_asilado) && !empty(form.es_defensor_ddhh) && !empty(form.es_periodista) && !empty(form.es_desplazado_por_hecho)) {
        tabItems[5].validation = 'complete';
        completed++;
    }

    if(completed == 6) {
        form.saveStatus = true;
        form.fud_completo = 1;
    }
}

function checkPermisos(data) {
    return data.includes(user_area.value);
}

function empty(e) {
  switch (e) {
    case "":
    case null:
    case undefined:
      return true;
    default:
      return false;
  }
}

function getParentesco(id) {
    let data = props.parentescos.filter((item) => item.parentescos_id == id);

    if (data.length) {
        return data[0].nombre;
    }

    return '-';
}

function getTipoSolicitante(id) {
    let data = props.tiposSolicitantes.filter((item) => item.tipo_solicitantes_id == id);

    if (data.length) {
        return data[0].nombre;
    }

    return '-';
}

function getNacionalidad(id) {
    let data = props.nacionalidades.filter((item) => item.territorios_id == id);

    if (data.length) {
        return data[0].nombre;
    }

    return '-';
}

function getPais(id) {
    let data = props.paises.filter((item) => item.paises_id == id);

    if (data.length) {
        return data[0].nombre;
    }

    return '-';
}

function getEntidadFederativa(id) {
    let data = props.entidadesFederativas.filter((item) => item.entidades_federativas_id == id);

    if (data.length) {
        return data[0].name;
    }

    return '-';
}

function getMunicipios(id) {
    let data = props.municipios.filter((item) => item.municipios_id == id);

    if (data.length) {
        return data[0].name;
    }

    return '-';
}

function getMigratorio(id) {
    let data = props.situacionesMigratorias.filter((item) => item.situaciones_migratorias_id == id);

    if (data.length) {
        return data[0].nombre;
    }

    return '-';
}

function getIdentificacion(id) {
    let data = props.documentosProbatorios.filter((item) => item.documentos_probatorios_id == id);

    if (data.length) {
        return data[0].nombre;
    }

    return '-';
}

function getDocumentoIdentidad(id) {
    let data = props.documentosProbatoriosIdentidad.filter((item) => item.identidad_probatorio_documentos_id == id);

    if (data.length) {
        return data[0].nombre;
    }

    return '-';
}

function getAmbitoDependencia(id) {
    let data = props.ambitosDependencias.filter((item) => item.ambito_dependencias_id == id);

    if (data.length) {
        return data[0].nombre;
    }

    return '-';
}

function getNivelDependencia(id) {
    let data = props.gradosDependencia.filter((item) => item.enfoque_grado_dependencia_id == id);

    if (data.length) {
        return data[0].nombre;
    }

    return '-';
}

function getTipoVictima(id) {
    let data = props.tipoVictimas.filter((item) => item.tipo_victimas_id == id);

    if (data.length) {
        return data[0].nombre;
    }

    return '-';
}

function getTipoTelefono(id) {
    let data = props.tipoTelefonos.filter((item) => item.tipos_telefonos_id == id);

    if (data.length) {
        return data[0].nombre;
    }

    return '-';
}

function getTipoDanio(id) {
    let data = props.tiposDaniosSufridos.filter((item) => item.danios_sufridos_id == id);

    if (data.length) {
        return data[0].nombre;
    }

    return '-';
}

function getDelito(id) {
    let data = props.delitosProv.filter((item) => item.delitos_id == id);

    if (data.length) {
        return data[0].nombre;
    }

    return '-';
}

function getViolacion(id) {
    let data = props.violacionesDh.filter((item) => item.violaciones_id == id);

    if (data.length) {
        return data[0].nombre;
    }

    return '-';
}

function getTipoDiscapacidad(id) {
    let data = props.tiposDiscapacidad.filter((item) => item.id == id);

    if (data.length) {
        return data[0].nombre;
    }

    return '-';
}

function getTipoInstitucion(id) {
    let data = props.tiposInstitucion.filter((item) => item.enfoque_tipo_inst_id == id);

    if (data.length) {
        return data[0].nombre;
    }

    return '-';
}

function getEstadoCivil(id) {
    let data = props.estadosCivil.filter((item) => item.id == id);

    if (data.length) {
        return data[0].nombre;
    }

    return '-';
}

function  moveScroll(e) {
    console.log(e);
}

function setWatchers() {

    watch(
        search,
        debounce(function (value) {
            filterIndex();
      }, 500)
    );

    watch(
        startDate,
        (newVal) => {
            filterIndex();
        }
    );

    watch(
        endDate,
        (newVal) => {
            filterIndex();
        }
    );

    watch(
        victimaMunicipio,
        (newVal) => {
            form.victima_entidades_federativas_id = newVal;
            victimaMunicipios.value = props.municipios.filter((item) => item.entidades_federativas_id == newVal);
            if (!victimaMunicipios.value.some((item) => item.municipios_id == form.victima_municipios_id)) {
                form.victima_municipios_id = null;
            }
        }
    );

    watch(
        solicitanteMunicipio,
        (newVal) => {
            form.solicitante_entidades_federativas_id = newVal;
            solicitanteMunicipios.value = props.municipios.filter((item) => item.entidades_federativas_id == newVal);
            if (!solicitanteMunicipios.value.some((item) => item.municipios_id == form.solicitante_municipios_id)) {
                form.solicitante_municipios_id = null;
            }
        }
    );

    watch(
        hechosMunicipio,
        (newVal) => {
            form.hechos_entidades_federativas_id = newVal;
            hechosMunicipios.value = props.municipios.filter((item) => item.entidades_federativas_id == newVal);
            if (!hechosMunicipios.value.some((item) => item.municipios_id == form.hechos_municipios_id)) {
                form.hechos_municipios_id = null;
            }
        }
    );


    watch(generalesId, function (val) {
        if(val) {
            form.generales_id = val;
            getRev(val);
        }
    });

    watch(step6Field, function (val) {
        validateFormStep();
    });

}

</script>
