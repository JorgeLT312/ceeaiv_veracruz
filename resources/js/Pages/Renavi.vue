<template>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <p class="font-bold text-gray-dark text-xl dark:text-white">RENAVI y RENADET</p>
        </div>
        <div class="rounded-[16px] xl:rounded-full bg-gray-light dark:bg-gray-table px-4 py-2 md:grid md:grid-cols-2 lg:grid-cols-3 xl:flex items-center justify-between text-black">
            <div class="relative xl:w-1/4 md:col-span-2 lg:col-span-1">
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
            
                <div class="flex items-center py-2 lg:py-0">
                    <label class="mx-2 dark:text-white">Inicio</label>
                    <vue-date-picker class="min-w-[160px]" v-model="startDate" model-type="yyyy-MM-dd" :format="'dd/MM/yyyy'" :enable-time-picker="false" locale="es" cancelText="Cancelar" selectText="Aceptar"></vue-date-picker>
                </div>
                <div class="flex items-center pb-2 md:py-2 lg:py-0">
                    <label class="mx-2 dark:text-white">Fin</label>
                    <vue-date-picker class="min-w-[160px]" v-model="endDate" model-type="yyyy-MM-dd" :format="'dd/MM/yyyy'" :enable-time-picker="false" locale="es" cancelText="Cancelar" selectText="Aceptar"></vue-date-picker>
                </div>
            
            
                <div class="flex items-center ml-2 min-w-[220px]">
                    <Multiselect
                        v-model="estatusAtencion"
                        placeholder="Estatus de Atención"
                        :close-on-select="false"
                        :options="estatusAtencionList"
                    />
                </div>
                <div class="flex items-center ml-2 py-2 xl:py-0 min-w-[203px]" v-if="estatusAtencion != null && estatusAtencion.includes('REV')">
                    <multiselect
                        valueProp="value"
                        label="name"
                        v-model="pendientes"
                        :options="[{ name: 'Si', value:0 }, { name: 'No', value:1 }]"
                        :searchable="false"
                        :close-on-select="false"
                        placeholder="Datos Pendientes"></multiselect>
                </div>
            
            <button id="renaviDescargar" class="ml-2 btn-normal w-fit" @click="loadingExcel != true ? downloadExcel() : ''">
                <template v-if="loadingExcel == true">
                    <div  class="border-gray-400 h-6 w-6 animate-spin rounded-full border-4 border-t-blue-normal" />
                </template>
                <template v-else>
                    Descargar
                </template>
            </button>
        </div>
        <div class="rounded-xl bg-gray-light dark:bg-gray-table p-4">
            <div class="overflow-auto">
                <table class="table-auto w-full custom-table" cellspacing="0">
                    <thead>
                        <tr class="text-gray-dark dark:text-white text-left">
                        <th>No. de RENAVI</th>
                        <th>No. de REV</th>
                        <th class="whitespace-nowrap">Nombre Completo de Víctima</th>
                        <th class="whitespace-nowrap">Tipo de Víctima</th>
                        <th>Estatus atención</th>
                        <th>Datos Pendientes</th>
                        <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-normal dark:text-white">
                        <tr class="dark:bg-gray-row" v-for="captura in capturas.data">
                            <td>{{ captura.no_renavi }}</td>
                            <td>{{ captura.no_rev }}</td>
                            <td>
                                <template v-if="captura.tipo_victimas_id == 1">
                                    {{ captura.nombres }} {{ captura.primer_apellido }} {{ captura.segundo_apellido }}
                                </template>
                                <template v-else>
                                    {{ captura.indirecta_nombres }} {{ captura.indirecta_primer_apellido }} {{ captura.indirecta_segundo_apellido }}
                                </template>
                            </td>
                            <td>{{ captura.nombre_tipo_victima }}</td>
                            <td>{{ captura.estatus_atencion }}</td>
                            <td>
                                {{ captura.datos_completo_renavi ? 'No' : 'Si' }}
                            </td>
                            <td>
                                <div class="flex">
                                    <a v-if="checkPermisos()" href="#" class="flex items-center mx-1 p-2 rounded-full w-8 h-8 bg-gray-light hover:bg-amber-300" title="Editar" @click.prevent="showFormData(captura)">
                                        <img src="/images/icons/edit.png" class="object-contain">
                                    </a>
                                    <a v-if="captura.datos_completo_renavi && checkPermisos()" href="#" class="flex items-center mx-1 p-1 rounded-full w-8 h-8 bg-gray-light hover:bg-amber-300" title="Ver" @click.prevent="showData(captura)">
                                        <img src="/images/icons/eye.png" class="object-contain" >
                                    </a>
                                    <a v-if="captura.datos_completo_renavi && checkPermisos()" href="#" class="flex items-center mx-1 p-1 rounded-full w-8 h-8 bg-gray-light hover:bg-amber-300" title="Folio RENAVI">
                                        <img src="/images/icons/check-ok.png" class="object-contain" @click.prevent="showFolio(captura)">
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

    <Modal ref="modalFolio">
        <template #modal-header>
            <p>Registrar Folio RENAVI</p>
        </template>
        <template #modal-body>
            <div class="w-full max-w-md">
                <div class="md:flex md:item-center mb-2">
                  <div class="md:w-full">
                     <div class="w-auto">
                         <input  id="FolioRenavi"
                                 type="text"
                                 v-model="formFolio.no_renavi"
                                 placeholder="Escribir folio"
                                 class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                        <div v-if="formFolio.errors.no_renavi" v-text="formFolio.errors.no_renavi" class="text-red-500"></div>
                     </div>
                  </div>
                </div>
              </div>
        </template>
        <template #modal-footer>
            <button type="button" class="btn-normal w-full sm:w-auto" @click="saveFolio()">Guardar</button>
        </template>
    </Modal>

    <Modal ref="modalRenavi" :size="'max-w-6xl'">
        <template #modal-header>
            <p>Registro Nacional de Víctimas</p>
        </template>
        <template #modal-body>
            <Tabs :tabs="tabItems" @on-change-tab="changeTab">
                <template #tab-content-0>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="my-2">
                                <p class="font-bold">Datos de la Víctima</p>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Tipo de Víctima:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="relative">
                                        <select
                                            class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                            v-model="form.tipo_victimas_id"
                                            id="tipoVictima">
                                            <option value="null" disabled>Seleccionar Tipo</option>
                                            <option v-for="tipo in tipoVictimas" :value="tipo.tipo_victimas_id">{{ tipo.nombre }}</option>
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
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Folio Estatal:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="no_rev"
                                        v-model="form.no_rev"
                                        type="text"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Fecha de Inscripción:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <vue-date-picker v-model="form.fecha_atencion"  model-type="yyyy-MM-dd" :format="'dd/MM/yyyy'" :enable-time-picker="false" locale="es" cancelText="Cancelar" selectText="Aceptar"></vue-date-picker>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="my-2">
                                <p class="font-bold">Datos de la Autoridad</p>
                            </div>
                            <div v-if="form.autoridades_conocen_hechos.includes('Investigación ministerial')">
                                <p class="my-2 font-bold">Investigación Ministerial</p>

                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Nombres:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="ministerio_publico_agencia" type="text"
                                            v-model="form.ministerio_publico_nombres"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.ministerio_publico_nombres" v-text="form.errors.ministerio_publico_nombres" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Primer Apellido:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="ministerio_publico_agencia" type="text"
                                            v-model="form.ministerio_publico_primer_apellido"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.ministerio_publico_primer_apellido" v-text="form.errors.ministerio_publico_primer_apellido" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Segundo Apellido:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="ministerio_publico_segundo_apellido" type="text"
                                            v-model="form.ministerio_publico_segundo_apellido"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.ministerio_publico_segundo_apellido" v-text="form.errors.ministerio_publico_segundo_apellido" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Institución:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="ministerio_publico_agencia" type="text"
                                            v-model="form.ministerio_publico_institucion"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.ministerio_publico_institucion" v-text="form.errors.ministerio_publico_institucion" class="text-red-500"></div>
                                    </div>
                                </div>
                            </div>
                            <div v-if="form.autoridades_conocen_hechos.includes('Proceso judicial')">
                                <p class="my-2 font-bold">Proceso Judicial</p>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Nombres:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="proceso_judicial_nombres" type="text"
                                            v-model="form.proceso_judicial_nombres"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.proceso_judicial_nombres" v-text="form.errors.proceso_judicial_nombres" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Primer Apellido:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="proceso_judicial_primer_apellido" type="text"
                                            v-model="form.proceso_judicial_primer_apellido"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.proceso_judicial_primer_apellido" v-text="form.errors.proceso_judicial_primer_apellido" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Segundo Apellido:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="proceso_judicial_segundo_apellido" type="text"
                                            v-model="form.proceso_judicial_segundo_apellido"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.proceso_judicial_segundo_apellido" v-text="form.errors.proceso_judicial_segundo_apellido" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Institución:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="proceso_judicial_institucion" type="text"
                                            v-model="form.proceso_judicial_institucion"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.proceso_judicial_institucion" v-text="form.errors.proceso_judicial_institucion" class="text-red-500"></div>
                                    </div>
                                </div>
                            </div>
                            <div v-if="form.autoridades_conocen_hechos.includes('Procedimientos ante DDHH')">
                                <p class="my-2 font-bold">Procedimiento ante organismos nacionales e internaciones DDHH</p>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Nombres:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="ddhh_nombres" type="text"
                                            v-model="form.ddhh_nombres"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.ddhh_nombres" v-text="form.errors.ddhh_nombres" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Primer Apellido:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="ddhh_primer_apellido" type="text"
                                            v-model="form.ddhh_primer_apellido"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.ddhh_primer_apellido" v-text="form.errors.ddhh_primer_apellido" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Segundo Apellido:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="ddhh_segundo_apellido" type="text"
                                            v-model="form.ddhh_segundo_apellido"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.ddhh_segundo_apellido" v-text="form.errors.ddhh_segundo_apellido" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Institución:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="ddhh_institucion" type="text"
                                            v-model="form.ddhh_institucion"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.ddhh_institucion" v-text="form.errors.ddhh_institucion" class="text-red-500"></div>
                                    </div>
                                </div>
                            </div>
                            <div v-if="form.autoridades_conocen_hechos.includes('Otra autoridad')">
                                <p class="my-2 font-bold">Otra Autoridad</p>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Nombres:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="otra_nombres" type="text"
                                            v-model="form.otra_nombres"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.otra_nombres" v-text="form.errors.otra_nombres" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Primer Apellido:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="otra_primer_apellido" type="text"
                                            v-model="form.otra_primer_apellido"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.otra_primer_apellido" v-text="form.errors.otra_primer_apellido" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Segundo Apellido:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="otra_segundo_apellido" type="text"
                                            v-model="form.otra_segundo_apellido"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.otra_segundo_apellido" v-text="form.errors.otra_segundo_apellido" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Institución:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="otra_institucion" type="text"
                                            v-model="form.otra_institucion"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.otra_institucion" v-text="form.errors.otra_institucion" class="text-red-500"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template #tab-content-1>
                    <div class="grid grid-cols-2 gap-4 min-h-72">
                        <div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Ámbito de Competencia:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="relative">
                                        <select
                                            class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                            v-model="form.hechos_ambito_competencia"
                                            id="tipoVictima">
                                            <option value="null" disabled>Seleccionar Tipo</option>
                                            <option v-for="tipo in ambitoCompetencias" :value="tipo.id">{{ tipo.nombre }}</option>
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
                                        Inicio del Hecho:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <vue-date-picker :teleport-center="true" v-model="form.hechos_inicio" model-type="yyyy-MM-dd" :format="'dd/MM/yyyy'" :enable-time-picker="false" locale="es" cancelText="Cancelar" selectText="Aceptar"></vue-date-picker>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Fin del Hecho:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <vue-date-picker :teleport-center="true" v-model="form.hechos_fin" model-type="yyyy-MM-dd" :format="'dd/MM/yyyy'" :enable-time-picker="false" locale="es" cancelText="Cancelar" selectText="Aceptar"></vue-date-picker>
                                </div>
                            </div>
                        </div>
                        <div>
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
                                        Delitos:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <Multiselect
                                        v-model="form.hechos_delitos_id"
                                        mode="tags"
                                        label="nombre"
                                        valueProp="delitos_id"
                                        :close-on-select="false"
                                        :options="delitosProv"
                                    />
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>
                </template>
                <template #tab-content-2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="my-2 col-span-2">
                            <p class="font-bold">Familiar o Persona de Confianza que solicito REV</p>
                        </div>
                        <template v-if="form.tipo_solicitantes_id != 1">
                            <div>
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
                            </div>
                            <div>
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
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template v-else>
                            <div>
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
                                            Fecha de Nacimiento:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <vue-date-picker v-model="form.victima_fecha_nacimiento" model-type="yyyy-MM-dd" :format="'dd/MM/yyyy'" :enable-time-picker="false" locale="es" cancelText="Cancelar" selectText="Aceptar"></vue-date-picker>
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
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
                <template #tab-content-3>
                    <div class="w-full max-w-xl mx-auto min-h-72">
                        <div class="md:flex md:item-center mb-2">
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
                </template>
                <template #tab-content-4>
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
                                </div>
                            </div>
                        </div>
                        <div>
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
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Lada:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="telefono" type="text"
                                        v-model="form.lada"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Extensión:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="telefono" type="text"
                                        v-model="form.extension"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
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
                                            ¿Requiere traductor/a?*
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
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </Tabs>
        </template>
        <template #modal-footer>
            <button type="button" @click="changeTab(6)"  class="btn-normal w-full sm:w-auto">Guardar</button>
        </template>
    </Modal>

    <Modal ref="modalShowData" :size="'max-w-6xl'">
        <template #modal-header>
            <div class="text-center">
                <p>Registro Nacional de Víctimas</p>
            </div>
        </template>
        <template #modal-body>
            <div class="grid lg:grid-cols-2 gap-4">
                <div class="border-r border-slate-400">
                    <div class="font-bold my-2">
                        1. Datos de Víctima y Autoridad
                    </div>
                    <div>
                        <p>No. de REV: {{ form.no_rev }}</p>
                        <p>No. de RENAVI: {{ form.no_renavi }}</p>
                        <p>Tipo de Víctima: {{ getTipoVictima(form.tipo_victimas_id) }}</p>
                        <p>Nombre Completo: {{ form.victima_nombres }} {{ form.victima_primer_apellido }} {{ form.victima_segundo_apellido }}</p>
                        <p>CURP: {{ form.victima_curp }}</p>
                        <p>Fecha de Nacimiento: {{ form.victima_fecha_nacimiento}}</p>
                        <p>Sexo: {{ form.victima_sexos_id == 1 ? 'Hombre' : 'Mujer' }}</p>
                        <p>Nacionalidad: {{ getNacionalidad(form.victima_territorios_id) }}</p>
                        <template v-if="form.victima_territorios_id == 1">
                            <p>Entidad Federativa: {{ getEntidadFederativa(form.victima_entidades_federativas_id) }}</p>
                            <p>Delegación o Municipio: {{ getMunicipios(form.victima_municipios_id) }}</p>
                            <p>Población o Comunidad: {{ form.victima_poblacion }}</p>
                        </template>
                        <template v-if="form.victima_territorios_id == 2">
                            <p>País: {{ getPais(form.victima_paises_id) }}</p>
                        </template>
                        <p>Fecha de Inscripción: {{ form.fecha_atencion }}</p>
                        <p class="font-bold">Datos de la Autoridad</p>
                        <p>Autoridades que han conocido los hechos: {{ form.autoridades_conocen_hechos.join(',') }}</p>
                        <template v-if="form.autoridades_conocen_hechos.includes('Investigación ministerial')">
                            <p class="font-bold">Investigación ministerial</p>
                            <p>Nombre Completo: {{ form.ministerio_publico_nombres }} {{ form.ministerio_publico_primer_apellido }} {{ form.ministerio_publico_segundo_apellido }}</p>
                            <p>Institución: {{ form.ministerio_publico_institucion }}</p>
                        </template>
                        <template v-if="form.autoridades_conocen_hechos.includes('Proceso judicial')">
                            <p class="font-bold">Proceso judicial</p>
                            <p>Nombre Completo: {{ form.proceso_judicial_nombres }} {{ form.proceso_judicial_primer_apellido }} {{ form.proceso_judicial_segundo_apellido }}</p>
                            <p>Institución: {{ form.proceso_judicial_institucion }}</p>
                        </template>
                        <template v-if="form.autoridades_conocen_hechos.includes('Procedimientos ante DDHH')">
                            <p class="font-bold">Procedimientos ante DDHH</p>
                            <p>Nombre Completo: {{ form.ddhh_nombres }} {{ form.ddhh_primer_apellido }} {{ form.ddhh_segundo_apellido }}</p>
                            <p>Institución: {{ form.ddhh_institucion }}</p>
                        </template>
                        <template v-if="form.autoridades_conocen_hechos.includes('Otra autoridad')">
                            <p class="font-bold">Otra autoridad</p>
                            <p>Nombre Completo: {{ form.otra_nombres }} {{ form.otra_primer_apellido }} {{ form.otra_segundo_apellido }}</p>
                            <p>Institución: {{ form.otra_institucion }}</p>
                        </template>
                    </div>
                    <div class="font-bold mb-2 mt-6">
                        2. Hechos Victimizante
                    </div>
                    <div>
                        <p>Ámbito de Competencia: {{ getAmbitoDependencia(form.hechos_ambito_competencia) }}</p>
                        <p>Inicio del Hecho: {{ form.hechos_inicio }}</p>
                        <p>Fin del Hecho: {{ form.hechos_fin }}</p>
                        <p>Nacionalidad: {{ getNacionalidad(form.hechos_territorios_id) }}</p>
                        <template v-if="form.hechos_territorios_id == 1">
                            <p>Entidad Federativa: {{ getEntidadFederativa(form.hechos_entidades_federativas_id) }}</p>
                            <p>Delegación o Municipio: {{ getMunicipios(form.hechos_municipios_id) }}</p>
                        </template>
                        <template v-if="form.hechos_territorios_id == 2">
                            <p>País: {{ getPais(form.hechos_paises_id) }}</p>
                        </template>
                    </div>
                    <div>
                        
                    </div>
                    <div class="font-bold mb-2 mt-6">
                        3. Familiar o Persona Quien Solicita REV
                    </div>
                    <div>
                        <template v-if="form.tipo_solicitantes_id != 1">
                            <p>Nombre Completo: {{ form.solicitante_nombres }} {{ form.solicitante_primer_apellido }} {{ form.solicitante_segundo_apellido }}</p>
                            <p>Parentesco/Relación Afectiva: {{ form.parentesco_id }}</p>
                            <template v-if="form.tipo_solicitantes_id == 4 || form.tipo_solicitantes_id == 3">
                                <p>cargo: {{ form.cargo }}</p>
                            </template>
                            <template v-if="form.tipo_solicitantes_id == 4">
                                <p>Dependencia o Institución: {{ form.dependencia }}</p>
                            </template>
                            <p>Nacionalidad: {{ form.territorios_id }}</p>
                            <p>Situación Migratoria: {{ form.situacion_migratoria_id }}</p>
                            <p>Identificación presentada: {{ form.identificacion_id }}</p>
                            <p>Documento Probatorio de Identidad: {{ form.identidad_probatorio_documentos_id }}</p>
                            <template v-if="form.tipo_solicitantes_id == 4">
                                <p>Entidad Federativa: {{ form.entidades_federativas_id }}</p>
                                <p>Ambito de Dependencia: {{ form.ambito_dependencias_id }}</p>
                                <p>Nivel de Dependencia: {{ form.nivel_dependencias_id }}</p>
                            </template>
                        </template>
                        <template v-else>
                            <p>Nombre Completo: {{ form.victima_nombres }} {{ form.victima_primer_apellido }} {{ form.victima_segundo_apellido }}</p>
                            <p>Fecha de Nacimiento: {{ form.victima_fecha_nacimiento }}</p>
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
                        </template>
                    </div>
                </div>
                <div class="">
                    <div class="font-bold mb-2">
                        4. Documentos de Identidad
                    </div>
                    <div>
                        <p>Identificación Presentada: {{ getIdentificacion(form.identificacion_victima_id) }}</p>
                        <p>Documento Probatorio de Identidad:</p>
                        <p v-for="doc in form.victima_directa_identidad_probatorio_documentos_id">
                            - {{ getDocumentoIdentidad(doc) }}
                        </p>
                    </div>
                    <div class="font-bold mb-2 mt-6">
                        5. Datos Dirección de Contacto
                    </div>
                    <div>
                        <p>Codigo Postal: {{  form.codigo_postal  }}</p>
                        <p>Colonia: {{ form.colonia }}</p>
                        <p>Localidad: {{  form.localidad  }}</p>
                        <p>Calle: {{  form.calle }}</p>
                        <p>Número Exterior: {{ form.no_exterior }}</p>
                        <p>Número Interior: {{ form.no_interior }}</p>
                        <p>Teléfono: {{ form.telefono }}</p>
                        <p>Tipo de Teléfono: {{ getTipoTelefono(form.tipos_telefonos_id) }}</p>
                        <p>Lada: {{ form.lada }}</p>
                        <p>Extension: {{ form.extension }}</p>
                        <p>Nacionalidad: {{ getNacionalidad(form.solicitante_territorios_id) }}</p>
                        <template v-if="form.solicitante_territorios_id == 1">
                            <p>Entidad Federativa: {{ getEntidadFederativa(form.solicitante_entidades_federativas_id) }}</p>
                            <p>Delegación o municipio: {{ getMunicipios(form.solicitante_municipios_id) }}</p>
                        </template>
                        <template v-if="form.solicitante_territorios_id == 2">
                            <p>País: {{ getPais(form.solicitante_paises_id) }}</p>
                        </template>
                    </div>
                    <div class="font-bold mb-2 mt-6">
                        6. Enfoque Diferencial
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
                </div>
            </div>
        </template>
        <template #modal-footer>
            <!-- Puedes agregar contenido al pie de la modal si es necesario -->
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
import useErrors from '../Services/errors';

let props = defineProps({
    capturas: Object,
    filters: Object,
    sexos: Array,
    ambitoCompetencias: Array,
    tipoVictimas: Array,
    delitosProv: Array,
    nacionalidades: Array,
    paises: Array,
    entidadesFederativas: Array,
    municipios: Array,
    parentescos: Array,
    documentosProbatorios: Array,
    documentosProbatoriosIdentidad: Array,
    tipoTelefonos: Array,
    situacionesMigratorias: Array,
    violacionesDh: Array,
    ambitosDependencias: Array,
    estadosCivil: Array,
    tiposDiscapacidad: Array,
    gradosDependencia: Array,
});

const customErrors = useErrors();
const { notify }  = useNotification();
const search = ref(props.filters.search);
const startDate = ref(moment().subtract(1, 'month').format('YYYY-MM-DD'));
const endDate = ref(moment().format('YYYY-MM-DD'));
const estatusAtencion = ref(null);
const pendientes = ref(null);
const modalShowData = ref(null);
const modalRenavi = ref(null);
const modalFolio = ref(null);
const user_role = computed(() => usePage().props.auth.user.role_id);
const user_area = computed(() => usePage().props.auth.user.area_id);
const currentStep = ref(1);
const victimaMunicipio = ref(null);
let victimaMunicipios = ref([]);
const solicitanteMunicipio = ref(null);
let solicitanteMunicipios = ref([]);
const hechosMunicipio = ref(null);
let hechosMunicipios = ref([]);
let esVictimizantePor = ['Religión o Creencias', 'Preferencia u Orientación Sexual', 'Identidad o Expresión de Género', 'Sexo', 'Raza', 'Otro'];
let violenciaContraMujeres = ['Psicológica', 'Física', 'Económica', 'Patrimonial', 'Sexual', 'Obstétrica', 'Feminicida', 'Otro'];
let estatusAtencionList = ['REV', 'RENAVI'];
let loadingExcel = ref(false);

let tabItems = [
    { name: 'Víctima y Autoridad', status: 'block', validation: 'pending' },
    { name: 'Hecho Victimizante', status: 'hidden', validation: 'pending' },
    { name: 'Solicitud REV', status: 'hidden', validation: 'pending' },
    { name: 'Documentos Identidad', status: 'hidden', validation: 'pending' },
    { name: 'Dirección Contacto', status: 'hidden', validation: 'pending' },
    { name: 'Enfoque Diferencial', status: 'hidden', validation: 'pending' },
];

let form = useForm({
    generales_id: null,
    numeroPaso: 1,

    // paso 1
    victima_curp: '',
    victima_nombres: '',
    victima_primer_apellido: '',
    victima_segundo_apellido: '',
    victima_fecha_nacimiento: '',
    victima_sexos_id: null,
    estado_civil: null,
    victima_territorios_id: null,
    victima_paises_id: null,
    victima_entidades_federativas_id: null,
    victima_municipios_id: null,
    victima_poblacion: '',
    fecha_atencion: null,
    no_rev: null,
    
    autoridades_conocen_hechos: [],

    ministerio_publico_nombres: '',
    ministerio_publico_primer_apellido: '',
    ministerio_publico_segundo_apellido: '',
    ministerio_publico_institucion: '',
    ministerio_publico_nombre_autoridad: '',

    proceso_judicial_nombres: '',
    proceso_judicial_primer_apellido: '',
    proceso_judicial_segundo_apellido: '',
    proceso_judicial_institucion: '',
    proceso_judicial_nombre_autoridad: '',

    ddhh_nombres: '',
    ddhh_primer_apellido: '',
    ddhh_segundo_apellido: '',
    ddhh_institucion: '',
    ddhh_nombre_autoridad: '',

    otra_nombres: '',
    otra_primer_apellido: '',
    otra_segundo_apellido: '',
    otra_institucion: '',
    otra_nombre_autoridad: '',

    // paso 2
    tipo_victimas_id: 0,
    hechos_ambito_competencia: null,
    hechos_inicio: '',
    hechos_fin: '',
    hechos_delitos_id: [],
    hechos_territorios_id: null,
    hechos_paises_id: null,
    hechos_entidades_federativas_id: null,
    hechos_municipios_id: null,
    hechos_localidad: '',
    //delitos
    
    // paso 3
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

    // paso 4
    identificacion_victima: null,
    identificacion_victima_id: null,
    victima_directa_identidad_probatorio_documentos_id: [],

    // paso 5
    calle: '',
    no_exterior: '',
    no_interior: '',
    colonia: '',
    codigo_postal: '',
    localidad: '',
    telefono: '',
    tipos_telefonos_id: null,
    lada: '',
    extension: '',
    solicitante_territorios_id: null,
    solicitante_paises_id: null,
    solicitante_entidades_federativas_id: null,
    solicitante_municipios_id: null,

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

    saveStatus: false,
    fud_completo: 0,
    datos_completo_renavi: 0,
});
let formFolio = useForm({
    generales_id: null,
    no_renavi: '',
});

onMounted(() => {
    let searchParams = new URLSearchParams(window.location.search);
    if (searchParams.has('modal') && searchParams.has('data')) {
        currentStep.value = 1;

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

function checkPermisos() {
    if ((user_role.value == 3 || user_role.value == 4)
        && (user_area.value == 3)) {
        return true;
    }

    return false;
}

let changeTab = (index) => {
    if (index == 6) {
        form.numeroPaso = index+1;
        currentStep.value = 1;
    } else {
        form.numeroPaso = currentStep.value;
        currentStep.value = index+1;
    }

    //HoldOn.open({ message: "Procesando datos, por favor espere." });

    if (form.generales_id) {
        form.patch("/renavi/" + form.generales_id, {
            onError: (errors) => {
                currentStep.value = form.numeroPaso;
                validateFormStep();
                customErrors.showErrors(form);
                //HoldOn.close();
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
                    modalRenavi.value.hide();
                    form.reset();
                    form.clearErrors();
                }

                //HoldOn.close();
            },
            });
    } else {
        form.post("/renavi/", {
            wantsJson: true,
            onError: (errors) => {
                currentStep.value = form.numeroPaso;
                customErrors.showErrors(form);
                //HoldOn.close();
            },
            onSuccess: (data) => {
                resetTab(currentStep.value-1);
                form.reset();
                notify({
                    title: "¡Éxito!",
                    text: "Datos guardados correctamente.",
                    type: "success"
                });
                //HoldOn.close();
            },
            });
    }
};

function showFolio(data){
    formFolio.reset();
    formFolio.clearErrors();
    formFolio.generales_id = data.id;
    modalFolio.value.show();
}
function saveFolio(){
    formFolio.clearErrors();
    //HoldOn.open({ message: "Procesando datos, por favor espere." });
    formFolio.patch("renavi/registrar-folio/" + formFolio.generales_id, {
        onError: (errors) => {
            //HoldOn.close();
        },
        onSuccess: (data) => {
            //HoldOn.close();
            formFolio.reset();
            modalFolio.value.hide();
            notify({
                title: "¡Éxito!",
                text: "Folio guardado correctamente.",
                type: "success"
            });
        },
    });
}

function resetTab(index = 0) {
    tabItems.every(function(item) {
        item.status = 'hidden';
        item.validation = 'pending';
        return true;
    });
    tabItems[index].status = 'block';
    currentStep.value = index+1;
}

function showFormData(data = null) {
    resetTab();
    form.reset();
    form.clearErrors()

    if (data) {
        getRenavi(data.id);
    }

    modalRenavi.value.show();
}

function showData(data) {
    form.reset();
    getRenavi(data.id);
    modalShowData.value.show();
}

function getRenavi(id) {
    axios.get('/renavi/'+id)
        .then(res => {
            let data = res.data.data;
            let autoridades = res.data.autoridades;
            
            form.generales_id = data.generales_id;
            form.numeroPaso = 1;

            // paso 1
            form.fecha_atencion = data.fecha_atencion;
            form.no_rev = data.no_rev;
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
                form.victima_poblacion =data.poblacion;
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

            form.autoridades_conocen_hechos =  [];
            autoridades.forEach(function(autoridad) {
                form.autoridades_conocen_hechos.push(autoridad.nombre_autoridad);

                if (autoridad.nombre_autoridad == 'Investigación ministerial') {
                    form.ministerio_publico_nombres = autoridad.nombres;
                    form.ministerio_publico_primer_apellido = autoridad.primer_apellido;
                    form.ministerio_publico_segundo_apellido = autoridad.segundo_apellido;
                    form.ministerio_publico_institucion = autoridad.institucion;
                    form.ministerio_publico_nombre_autoridad = autoridad.nombre_autoridad;
                }

                if (autoridad.nombre_autoridad == 'Proceso judicial') {
                    form.proceso_judicial_nombres = autoridad.nombres;
                    form.proceso_judicial_primer_apellido = autoridad.primer_apellido;
                    form.proceso_judicial_segundo_apellido = autoridad.segundo_apellido;
                    form.proceso_judicial_institucion = autoridad.institucion;
                    form.proceso_judicial_nombre_autoridad = autoridad.nombre_autoridad;
                }

                if (autoridad.nombre_autoridad == 'Procedimientos ante DDHH') {
                    form.ddhh_nombres = autoridad.nombres;
                    form.ddhh_primer_apellido = autoridad.primer_apellido;
                    form.ddhh_segundo_apellido = autoridad.segundo_apellido;
                    form.ddhh_institucion = autoridad.institucion;
                    form.ddhh_nombre_autoridad = autoridad.nombre_autoridad;
                }

                if (autoridad.nombre_autoridad == 'Otra autoridad') {
                    form.otra_nombres = autoridad.nombres;
                    form.otra_primer_apellido = autoridad.primer_apellido;
                    form.otra_segundo_apellido = autoridad.segundo_apellido;
                    form.otra_institucion = autoridad.institucion;
                    form.otra_nombre_autoridad = autoridad.nombre_autoridad;
                }
            });

            // paso 2
            form.tipo_victimas_id = data.tipo_victimas_id;
            form.hechos_ambito_competencia = data.hechos_ambito_competencia;
            form.hechos_inicio = data.hechos_fecha_hecho_ini;
            form.hechos_fin = data.hechos_fecha_hecho_fin;
            form.hechos_delitos_id = data.hechos_delitos_id ? JSON.parse(data.hechos_delitos_id) : [];
            form.hechos_territorios_id = data.hechos_territorios_id;
            form.hechos_paises_id = data.hechos_paises_id;
            hechosMunicipio.value = data.hechos_entidades_federativas_id;
            form.hechos_entidades_federativas_id = data.hechos_entidades_federativas_id;
            form.hechos_municipios_id = data.hechos_municipios_id;
            form.hechos_localidad = data.hechos_localidad;
            //delitos
            
            // paso 3
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

            // paso 4
            form.identificacion_victima = data.presenta_identificacion;
            form.identificacion_victima_id = data.tipo_identificacion_id;
            form.victima_directa_identidad_probatorio_documentos_id = data.identidad_probatorio_documentos_id ? JSON.parse(data.identidad_probatorio_documentos_id) : [];

            // paso 5
            form.calle = data.calle;
            form.no_exterior = data.no_exterior;
            form.no_interior = data.no_interior;
            form.colonia = data.colonia;
            form.codigo_postal = data.codigo_postal;
            form.localidad = data.localidad;
            form.telefono = data.telefono;
            form.tipos_telefonos_id = data.tipos_telefonos_id;
            form.lada = data.lada;
            form.extension = data.extension;
            form.solicitante_territorios_id = data.notificaciones_territorios_id;
            form.solicitante_paises_id = data.notificaciones_paises_id;
            form.solicitante_entidades_federativas_id = data.notificaciones_entidades_federativas_id;
            solicitanteMunicipio.value = data.notificaciones_entidades_federativas_id;
            form.solicitante_municipios_id = data.notificaciones_municipios_id;

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

            saveStatus: false,
            form.datos_completo_renavi = data.datos_completo_renavi;

            validateFormStep();
        });
}

function validateFormStep() {
    let completed = 0;
    let completedVD = 0;
    let completedAutoridades = form.autoridades_conocen_hechos.length;

    // paso 1
    if(!empty(form.tipo_victimas_id) && !empty(form.no_rev) && !empty(form.fecha_atencion) && !empty(form.victima_nombres) && !empty(form.victima_primer_apellido) && !empty(form.victima_segundo_apellido) && !empty(form.victima_fecha_nacimiento) && !empty(form.victima_curp) && !empty(form.victima_sexos_id)) {
        completedVD = 1;
    }

    form.autoridades_conocen_hechos.forEach(function(autoridad) {
        if (autoridad == 'Investigación ministerial') {
            if (
                !empty(form.ministerio_publico_nombres) &&
                !empty(form.ministerio_publico_primer_apellido) &&
                !empty(form.ministerio_publico_segundo_apellido) &&
                !empty(form.ministerio_publico_institucion) &&
                !empty(form.ministerio_publico_nombre_autoridad)
                ) {
                completedAutoridades--;
            }
        }

        if (autoridad == 'Proceso judicial') {
            if (
                !empty(form.proceso_judicial_nombres) &&
                !empty(form.proceso_judicial_primer_apellido) &&
                !empty(form.proceso_judicial_segundo_apellido) &&
                !empty(form.proceso_judicial_institucion) &&
                !empty(form.proceso_judicial_nombre_autoridad)
                ) {
                completedAutoridades--;
            }
        }

        if (autoridad == 'Procedimientos ante DDHH') {
            if (
                !empty(form.ddhh_nombres) &&
                !empty(form.ddhh_primer_apellido) &&
                !empty(form.ddhh_segundo_apellido) &&
                !empty(form.ddhh_institucion) &&
                !empty(form.ddhh_nombre_autoridad)
                ) {
                completedAutoridades--;
            }
        }

        if (autoridad == 'Otra autoridad') {
            if(
                !empty(form.otra_nombres) &&
                !empty(form.otra_primer_apellido) &&
                !empty(form.otra_segundo_apellido) &&
                !empty(form.otra_institucion) &&
                !empty(form.otra_nombre_autoridad)
                ) {
                completedAutoridades--;
            }
        }
    });

    if ((completedVD + completedAutoridades) == 1) {
        tabItems[0].validation = 'complete';
        completed++;
    }

    // paso 2
    if (!empty(form.hechos_ambito_competencia) && !empty(form.hechos_inicio) && !empty(form.hechos_fin) && form.hechos_delitos_id.length) {
        if (form.hechos_territorios_id == 1 && form.hechos_entidades_federativas_id != null && form.hechos_municipios_id != null) {
            tabItems[1].validation = 'complete';
            completed++;
        }

        if (form.hechos_territorios_id == 2 && form.hechos_paises_id != null) {
            tabItems[1].validation = 'complete';
            completed++;
        }

        if (form.hechos_territorios_id == 3) {
            tabItems[1].validation = 'complete';
            completed++;
        }
    }

    // paso 3
    if (form.tipo_solicitantes_id != 1) {
        if (
            !empty(form.solicitante_nombres) && 
            !empty(form.solicitante_primer_apellido) && 
            !empty(form.solicitante_segundo_apellido) && 
            form.territorios_id !=null && 
            form.situacion_migratoria_id != null && 
            form.identificacion_id != null && 
            form.identidad_probatorio_documentos_id != null) {
            
            let tempParentesco = form.tipo_solicitantes_id == 2 ? 0 : 1;
            let tempCargo = (form.tipo_solicitantes_id == 4 || form.tipo_solicitantes_id == 3) ? 0 : 1;
            let tempDependencia = form.tipo_solicitantes_id == 4 ? 0 : 1;
            let tempAmbito = form.tipo_solicitantes_id == 4 ? 0 : 1;

            if (form.tipo_solicitantes_id == 2 && form.parentesco_id != null) {
                tempParentesco++;
            }

            if ((form.tipo_solicitantes_id == 4 || form.tipo_solicitantes_id == 3) && !empty(form.cargo)) {
                tempCargo++;
            }

            if (form.tipo_solicitantes_id == 4 && !empty(form.dependencia)) {
                tempDependencia++;
            }

            if (form.tipo_solicitantes_id == 4 && form.entidades_federativas_id != null && form.ambito_dependencias_id != null && form.nivel_dependencias_id != null) {
                tempAmbito++;
            }

            if (tempParentesco && tempCargo && tempDependencia && tempAmbito) {
                tabItems[2].validation = 'complete';
                completed++;
            }
        }
    } else {
        if(
            !empty(form.victima_nombres) && 
            !empty(form.victima_primer_apellido) && 
            !empty(form.victima_segundo_apellido) && 
            !empty(form.victima_fecha_nacimiento) && 
            !empty(form.victima_curp) && 
            form.victima_sexos_id != null &&
            form.estado_civil != null
            ) {
            let tempParentesco = form.tipo_victimas_id != 1 ? 0 : 1;
            let tempTerritorio = 0;

            if (form.tipo_victimas_id != 1 && form.victima_parentesco_id != null) {
                tempParentesco++;
            }

            if (form.victima_territorios_id == 1 && form.victima_entidades_federativas_id != null && form.victima_municipios_id != null && !empty(form.victima_poblacion)) {
                tempTerritorio++;
            }

            if (form.victima_territorios_id == 2 && form.victima_paises_id != null) {
                tempTerritorio++;
            }

            if (form.victima_territorios_id == 3) {
                tempTerritorio++;
            }

            if (tempParentesco && tempTerritorio) {
                tabItems[2].validation = 'complete';
                completed++;
            }
        }
    }

    // paso 4
    if (form.victima_directa_identidad_probatorio_documentos_id.length && form.identificacion_victima_id != null) {
        tabItems[3].validation = 'complete';
        completed++;

    }

    // paso 5
    if (
        !empty(form.calle) && 
        !empty(form.no_exterior) && 
        !empty(form.colonia) && 
        !empty(form.codigo_postal) && 
        !empty(form.localidad) && 
        !empty(form.telefono) && 
        form.tipos_telefonos_id != null
        /*!empty(form.no_interior) && 
        !empty(form.lada) && 
        !empty(form.extension)*/
        ) {

        if (form.solicitante_territorios_id == 1 && form.solicitante_entidades_federativas_id != null && form.solicitante_municipios_id != null) {
            tabItems[4].validation = 'complete';
            completed++;
        }

        if (form.solicitante_territorios_id == 2 && form.solicitante_paises_id != null) {
            tabItems[4].validation = 'complete';
            completed++;
        }

        if (form.solicitante_territorios_id == 3) {
            tabItems[4].validation = 'complete';
            completed++;
        }
    }

    // paso 6
    if (
        !empty(form.es_adolescente) && 
        !empty(form.es_adulto_mayor) && 
        !empty(form.es_situacion_calle) && 
        !empty(form.es_discapacitado) && 
        !empty(form.es_migrante) && 
        !empty(form.habla_espaniol) && 
        !empty(form.es_indigena) && 
        !empty(form.es_refugiado) && 
        !empty(form.es_asilado) && 
        !empty(form.es_defensor_ddhh) && 
        !empty(form.es_periodista) && 
        !empty(form.es_desplazado_por_hecho)
        ) {
        tabItems[5].validation = 'complete';
        completed++;
    }

    if(completed == 6) {
        form.datos_completo_renavi = 1;
    }
}

function downloadExcel() {
    loadingExcel.value = true;
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

    if (estatusAtencion.value) {
        objFilter.estatus_atencion = estatusAtencion.value;
    }

    if (estatusAtencion.value == 'REV' && pendientes.value != null) {
        objFilter.datos_pendientes = pendientes.value;
    }

    let queryString = new URLSearchParams(objFilter).toString();
    queryString = queryString.replace("+", ' ')

    //renavi/excelRenavi/fechaInicio/fechaFin/search/estatus
    axios.get('/excelRenavi?'+queryString, {
            responseType: 'blob'
        })
        .then(response => {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            //HoldOn.close();
            link.setAttribute('download', "reporte.xlsx");
            document.body.appendChild(link);
            link.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(link);
            loadingExcel.value = false;
        })
        .catch(error => {
            loadingExcel.value = false;
            customErrors.showErrors(error);
            //console.error('Error al descargar el Reporte:', error);
        });
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

function getTipoVictima(id) {
    let data = props.tipoVictimas.filter((item) => item.tipo_victimas_id == id);

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

function getAmbitoDependencia(id) {
    let data = props.ambitoCompetencias.filter((item) => item.id == id);

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

function getTipoTelefono(id) {
    let data = props.tipoTelefonos.filter((item) => item.tipos_telefonos_id == id);

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

function getParentesco(id) {
    let data = props.parentescos.filter((item) => item.parentescos_id == id);

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

function getNivelDependencia(id) {
    let data = props.gradosDependencia.filter((item) => item.enfoque_grado_dependencia_id == id);

    if (data.length) {
        return data[0].nombre;
    }

    return '-';
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

    if (estatusAtencion.value) {
        objFilter.estatus_atencion = estatusAtencion.value;
    }

    if (estatusAtencion.value == 'REV' && pendientes.value != null) {
        objFilter.datos_pendientes = pendientes.value;
    }

    router.get(
        '/renavi',
        objFilter,
        {
          preserveState: true,
          replace: true,
        }
      );
}

function setWatchers() {

    watch(
        search,
        debounce(function (value) {
            filterIndex();
      }, 500)
    );

    watch(
        [startDate, endDate, estatusAtencion, pendientes],
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

}

</script>
