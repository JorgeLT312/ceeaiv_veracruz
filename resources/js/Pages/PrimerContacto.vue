<template>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <p class="font-bold text-gray-dark text-xl dark:text-white">
                Entrevista Primer Contacto
            </p>
        </div>
        <div class="rounded-[16px] md:rounded-full bg-gray-light dark:bg-gray-table px-4 py-2 md:flex items-center justify-between text-black" >
            <div class="relative md:w-1/2">
                <input type="text" v-model="search" id="search-input" class="rounded-full block w-full ps-2 p-2" placeholder="Buscar"/>
                <button type="button" class="absolute inset-y-0 end-0 flex items-center pe-3" >
                    <svg
                        class="w-4 h-4 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white"
                        xmlns="http://www.w3.org/2000/svg"
                        height="16"
                        width="16"
                        viewBox="0 0 512 512">
                        <path
                            d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"
                        />
                    </svg>
                </button>
            </div>
            <div class="sm:flex">
                <div class="flex items-center py-2 md:py-0">
                    <label class="mx-2 dark:text-white">Inicio</label>
                    <vue-date-picker v-model="startDate" model-type="yyyy-MM-dd"  :format="'dd/MM/yyyy'" :enable-time-picker="false" locale="es" cancelText="Cancelar" selectText="Aceptar"></vue-date-picker>
                </div>
                <div class="flex items-center">
                    <label class="mx-2 dark:text-white">Fin</label>
                    <vue-date-picker v-model="endDate" model-type="yyyy-MM-dd" :format="'dd/MM/yyyy'" :enable-time-picker="false" locale="es" cancelText="Cancelar" selectText="Aceptar"></vue-date-picker>
                </div>
            </div>
        </div>
        <div class="rounded-xl bg-gray-light dark:bg-gray-table p-4">
            <div class="overflow-auto">
                <table class="table-auto w-full custom-table" cellspacing="0">
                    <thead>
                        <tr class="text-gray-dark dark:text-white text-left">
                            <th>No. de REV</th>
                            <th>Nombre Completo</th>
                            <th class="whitespace-nowrap">Tipo de Víctima</th>
                            <th>Modalidad Atención</th>
                            <th class="whitespace-nowrap">Fecha y hora atención</th>
                            <th>Estatus atención</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-normal dark:text-white">
                        <tr class="dark:bg-gray-row" v-for="captura in capturas.data">
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
                            <td>{{ captura.modalidad_atencion }}</td>
                            <td class="whitespace-nowrap">{{ $filters.toDate(captura.fecha_atencion) }}</td>
                            <td>{{ captura.estatus_atencion }}</td>
                            <td>
                                <div class="flex">
                                    <template v-if="captura.estatus_atencion != 'Captura Inicial'">
                                        <template v-if="captura.registro_rev != null && captura.registro_rev == 1 && captura.estatus_atencion == 'Esperando Aprobación REV'">
                                            <Link v-if="checkPermisos([3])" :href="'/rev?data='+captura.id+'&modal=edit'"  class="flex items-center mx-1 p-1 rounded-full w-8 h-8 bg-gray-light hover:bg-amber-300" title="Agregar REV">
                                                <img src="/images/icons/person-red.png" class="object-contain">
                                            </Link>
                                        </template>
                                        <template v-if="captura.registro_rev != null && captura.registro_rev == 1 && (captura.estatus_atencion == 'REV' || captura.estatus_atencion == 'REV')">
                                            <Link v-if="checkPermisos([3])"  :href="'/rev?data='+captura.id+'&modal=show'" class="flex items-center mx-1 p-1 rounded-full w-8 h-8 bg-gray-light hover:bg-amber-300" title="Ver REV">
                                                <img src="/images/icons/person.png" class="object-contain" >
                                            </Link>
                                        </template>
                                        <a href="#" v-if="checkPermisos([2, 6])" id="subirArchivo-PCBtn" class="flex items-center mx-1 p-1 rounded-full w-8 h-8 bg-gray-light hover:bg-amber-300" title="Subir Archivo" @click.prevent="showEscaneo(captura)">
                                            <img src="/images/icons/upload.png" class="mx-1 object-contain" >
                                        </a>
                                        <a href="#" v-if="checkPermisos([2, 6])" id="imprimirArchivo-PCBtn" class="flex items-center mx-1 p-1 rounded-full w-8 h-8 bg-gray-light hover:bg-amber-300" title="Imprimir" @click.prevent="showPrinter(captura)">
                                            <img src="/images/icons/print.png" class="object-contain" >
                                        </a>
                                        <a href="#" v-if="checkPermisos([2, 6])" id="verArchivo-PCBtn" class="flex items-center mx-1 p-1 rounded-full w-8 h-8 bg-gray-light hover:bg-amber-300" title="Ver" @click.prevent="showData(captura)">
                                            <img src="/images/icons/eye.png" class="object-contain" >
                                        </a>
                                    </template>
                                    <a href="#" v-if="checkPermisos([2, 6, 4])" id="editarArchivo-PCBtn"  class="flex items-center mx-1 p-2 rounded-full w-8 h-8 bg-gray-light hover:bg-amber-300" title="Editar" @click.prevent="showFormData(captura)">
                                        <img src="/images/icons/edit.png" class="object-contain" >
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

    <Modal ref="modalOcupaciones" :second="true">
        <template #modal-header>
            <p>Agregar Nueva Ocupación</p>
        </template>
        <template #modal-body>
            <div class="w-full max-w-md">
                <div class="md:flex md:item-center mb-2">
                  <div class="md:w-full">
                     <div class="w-auto">
                         <input  id="FolioRenavi"
                                 type="text"
                                 v-model="formOcupacion.nombre_ocupacion"
                                 placeholder="Nombre"
                                 class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                        <div v-text="" class="text-red-500"></div>
                     </div>
                  </div>
                </div>
              </div>
        </template>
        <template #modal-footer>
            <button type="button" @click="storeOcupacion()"  class="btn-normal w-full sm:w-auto">Guardar</button>
        </template>
    </Modal>

    <Modal ref="modalEntrevista" :size="'max-w-6xl'">
        <template #modal-header>
            <p>Entrevista Primer Contacto</p>
        </template>
        <template #modal-body>
            <Tabs ref="tabsPC" :tabs="tabItems" @on-change-tab="changeTab">
                <template #tab-content-0>
                    <div class="w-full max-w-xl mx-auto">
                        <div class="md:flex md:item-center mb-2">
                            <div class="md:w-1/3 grid content-center">
                                <label class="block mb-1 md:mb-0 pr-4">
                                    Modalidad de atención*:
                                </label>
                            </div>
                            <div class="md:w-2/3">
                                <div class="relative">
                                    <select
                                        v-model="form.modalidad_atencion"
                                        class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                        id="modalidad_atencion">
                                        <option value="null" disabled>Seleccionar</option>
                                        <option value="Presencial">Presencial</option>
                                        <option value="Telefónica">Telefónica</option>
                                        <option value="Electrónica">Electrónica</option>
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
                                    Tipo de atención:
                                </label>
                            </div>
                            <div class="md:w-2/3">
                                <div class="flex items-center my-2">
                                    <label for="tipo_atencion" class="inline-flex items-center">
                                        <input
                                            v-model="form.colaboracion"
                                            id="tipo_atencion"
                                            type="checkbox"
                                            :checked="form.colaboracion"
                                            class="w-4 h-4 rounded-full">
                                        <span class="ml-2">Colaboración</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <template v-if="form.colaboracion == 1">
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Institución:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input
                                        v-model="form.institucion"
                                        id="institucion"
                                        type="text"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.institucion" v-text="form.errors.institucion" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        No. de Oficio:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input
                                        v-model="form.no_oficio"
                                        id="numeroOficio"
                                        type="text"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.no_oficio" v-text="form.errors.no_oficio" class="text-red-500"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
                <template #tab-content-1>
                    <div  class="w-full">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div  class="md:flex md:item-center mb-2">
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
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Sexo:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <div class="relative">
                                            <select
                                                v-model="form.sexos_id"
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
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Fecha de Nacimiento*:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <vue-date-picker :teleport-center="true" v-model="form.fecha_nacimiento" model-type="yyyy-MM-dd" :format="'dd/MM/yyyy'" :enable-time-picker="false" locale="es" cancelText="Cancelar" selectText="Aceptar"></vue-date-picker>
                                        <div v-if="form.errors.fecha_nacimiento" v-text="form.errors.fecha_nacimiento" class="text-red-500"></div>
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
                                <template v-if="form.territorios_id == 1">
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
                                                    v-model="form.municipios_id"
                                                    class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                    id="municipio">
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
                                    <div class="md:flex md:item-center mb-2">
                                        <div class="md:w-1/3 grid content-center">
                                            <label class="block mb-1 md:mb-0 pr-4">
                                                Población o Comunidad:
                                            </label>
                                        </div>
                                        <div class="md:w-2/3">
                                            <input  id="poblacion" type="text"
                                                v-model="form.poblacion"
                                                class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                            <div v-if="form.errors.poblacion" v-text="form.errors.poblacion" class="text-red-500"></div>
                                        </div>
                                    </div>
                                </template>
                                <div class="md:flex md:item-center mb-2" v-if="form.territorios_id == 2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            País:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <div class="relative">
                                            <select
                                                v-model="form.paises_id"
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
                                            Teléfono(s):
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <template v-for="(phone, index) in form.telefonos">
                                            <div class="flex">
                                                <input  :id="'telefono'+index" type="text"
                                                    v-model="form.telefonos[index]"
                                                    class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal m-1">
                                                <button v-if="form.telefonos.length-1 == index"
                                                    title="Agregar Otro Teléfono"
                                                    class="inline-flex w-sm justify-center rounded-full bg-yellow-normal p-2 my-auto hover:bg-cherry hover:text-white" @click="form.telefonos.push('')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="16" width="14" viewBox="0 0 448 512"><path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"/></svg>
                                                </button>
                                            </div>
                                            <div v-if="form.errors['telefonos.'+index]" v-text="form.errors['telefonos.'+index]" class="text-red-500"></div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template #tab-content-2>
                    <div class="grid grid-cols-2 gap-4">
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
                                            id="nacionalidadHechos">
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

                            <div class="md:flex md:item-center mb-2" v-if="form.hechos_territorios_id == 1">
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
                                            id="entidadHechos">
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
                        </div>
                        <div>
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
                                            id="paisHechos">
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

                            <template v-if="form.hechos_territorios_id == 1">
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
                                                id="municipioHechos">
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
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Población o Comunidad:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="poblacionHechos" type="text"
                                            v-model="form.hechos_poblacion"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.hechos_poblacion" v-text="form.errors.hechos_poblacion" class="text-red-500"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
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
                <template #tab-content-3>
                    <div  class="w-full">
                        <div class="md:flex md:item-center mb-2">
                            <div class="md:w-1/3 grid content-center">
                                <label class="block mb-1 md:mb-0 pr-4">
                                    ¿Es competencia de la CEEIAV?*:
                                </label>
                            </div>
                            <div class="md:w-2/3">
                                <div class="flex items-center my-2">
                                    <label for="competencia_si" class="inline-flex items-center">
                                        <input
                                            v-model="form.es_competencia_ceeaiv"
                                            id="competencia_si"
                                            name="competencia"
                                            type="radio"
                                            :value="1"
                                            class="w-4 h-4">
                                        <span class="ml-2">Si</span>
                                    </label>
                                    <label for="competencia_no" class="ml-4 inline-flex items-center">
                                        <input
                                            v-model="form.es_competencia_ceeaiv"
                                            id="competencia_no"
                                            name="competencia"
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
                                    Especificar la orientación brindada (canalización externa):
                                </label>
                            </div>
                            <div class="">
                                <textarea id="especificacion"
                                    v-model="form.orientacion_brindada"
                                    rows="10"
                                    placeholder=""
                                    class="bg-white rounded-2xl w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal"
                                ></textarea>
                                <div v-if="form.errors.orientacion_brindada" v-text="form.errors.orientacion_brindada" class="text-red-500"></div>
                            </div>
                        </div>
                    </div>
                </template>
                <template #tab-content-4>
                    <div  class="grid grid-cols-2 gap-4">
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
                                        Fecha de Nacimiento*:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <vue-date-picker :teleport-center="true" v-model="form.victima_fecha_nacimiento" model-type="yyyy-MM-dd" :format="'dd/MM/yyyy'" :enable-time-picker="false" locale="es" cancelText="Cancelar" selectText="Aceptar"></vue-date-picker>
                                    <div v-if="form.errors.victima_fecha_nacimiento" v-text="form.errors.victima_fecha_nacimiento" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Ocupación:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="flex">
                                        <div class="relative w-full mr-2">
                                            <select
                                                v-model="form.victima_ocupacion_id"
                                                class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                id="victimaOcupacion">
                                                <option value="null" disabled>Seleccionar</option>
                                                <option :value="ocupacion.ocupaciones_id" v-for="ocupacion in ocupaciones">{{ ocupacion.nombre_ocupacion }}</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                                    <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <button
                                            @click="showOcupacion()"
                                            title="Agregar Otra Ocupación"
                                            class="inline-flex w-sm justify-center rounded-full bg-yellow-normal p-2 my-auto hover:bg-cherry hover:text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="14" viewBox="0 0 448 512"><path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Delito Provisional*:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="relative">
                                        <select
                                            v-model="form.delitos_id"
                                            class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                            id="delito">
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
                                        Violación DDHH Provisional*:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="relative">
                                        <select
                                            v-model="form.violaciones_id"
                                            class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                            id="violacion">
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
                                        Número de Carpeta:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="carpeta" type="text"
                                        v-model="form.no_carpeta"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.no_carpeta" v-text="form.errors.no_carpeta" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Físcalia:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="fiscalia" type="text"
                                        v-model="form.fiscalia"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.fiscalia" v-text="form.errors.fiscalia" class="text-red-500"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <Modal ref="modalOcupaciones" :second="true">
                        <template #modal-header>
                            <p>Agregar Nueva Ocupación</p>
                        </template>
                        <template #modal-body>
                            <div class="w-full max-w-md">
                                <div class="md:flex md:item-center mb-2">
                                  <div class="md:w-full">
                                     <div class="w-auto">
                                         <input  id="FolioRenavi"
                                                 type="text"
                                                 v-model="formOcupacion.nombre_ocupacion"
                                                 placeholder="Nombre"
                                                 class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="formOcupacion.errors.nombre_ocupacion" v-text="formOcupacion.errors.nombre_ocupacion" class="text-red-500"></div>
                                     </div>
                                  </div>
                                </div>
                              </div>
                        </template>
                        <template #modal-footer>
                            <button type="button" @click="storeOcupacion()"  class="btn-normal w-full sm:w-auto">Guardar</button>
                        </template>
                    </Modal>
    
                </template>
                <template #tab-content-5>
                    <div  class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Nombre Completo:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="nombreCompleto" type="text"
                                        v-model="formFamiliar.nombre_completo"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Edad:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="edad" type="number"
                                        v-model="formFamiliar.edad"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Parentesco:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="relative">
                                        <select
                                            v-model="formFamiliar.parentesco_id"
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
                        </div>
                        <div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Dependencia Económica:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="dependencia" type="text"
                                        v-model="formFamiliar.dependencia_economica"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Factores de Vulnerabilidad:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="factorVulneravilidad" type="text"
                                        v-model="formFamiliar.factores_vulnerabilidad"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button"
                                    @click="setFamiliar()"
                                    class="justify-center rounded-full bg-yellow-normal px-4 py-2 hover:bg-cherry hover:text-white sm:mt-0 sm:w-auto">Agregar</button>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <div class="rounded-xl bg-gray-light p-4">
                                <div class="overflow-auto">
                                    <table class="table-auto w-full custom-table" cellspacing="0">
                                        <thead>
                                            <tr class="text-gray-dark text-left">
                                            <th>Nombre Completo</th>
                                            <th>Edad</th>
                                            <th>Parentesco</th>
                                            <th>Dependencia Económica</th>
                                            <th>Factores de Vulneravilidad</th>
                                            <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-normal">
                                            <tr v-for="(familiar, index) in form.familiares">
                                                <td>{{ familiar.nombre_completo }}</td>
                                                <td>{{ familiar.edad }}</td>
                                                <td>{{ getParentesco(familiar.parentesco_id) }}</td>
                                                <td>{{ familiar.dependencia_economica }}</td>
                                                <td>{{ familiar.factores_vulnerabilidad }}</td>
                                                <td>
                                                    <div class="flex">
                                                        <a @click.prevent="removeFamiliar(index)" href="#" class="flex items-center mx-1 p-2 rounded-full w-8 h-8 bg-gray-light hover:bg-amber-300" title="Eliminar">
                                                            <img src="/images/icons/delete.png" class="object-contain">
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Observaciones
                                    </label>
                                </div>
                                <div class="">
                                    <textarea id="observaciones"
                                        v-model="form.familiar_observaciones"
                                        rows="5"
                                        placeholder=""
                                        class="bg-white rounded-2xl w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal"
                                    ></textarea>
                                    <div v-if="form.errors.familiar_observaciones" v-text="form.errors.familiar_observaciones" class="text-red-500"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template #tab-content-6>
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
                                            v-model="form.notificaciones_territorios_id"
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
                            <template v-if="form.notificaciones_territorios_id == 1">
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Entidad Federativa:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <div class="relative">
                                            <select
                                                v-model="notificacionesMunicipio"
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
                                                v-model="form.notificaciones_municipios_id"
                                                class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                id="notificacionesNunicipio">
                                                <option value="null" disabled>Seleccionar</option>
                                                <option v-for="municipio in notificacionesMunicipios" :value="municipio.municipios_id">{{ municipio.name }}</option>
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
                            <div class="md:flex md:item-center mb-2" v-if="form.notificaciones_territorios_id == 2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        País:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="relative">
                                        <select
                                            v-model="form.notificaciones_paises_id"
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
                                        Estado Civil:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="estadoCivil" type="text"
                                        v-model="form.estado_civil"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.estado_civil" v-text="form.errors.estado_civil" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Escolaridad:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="escolariodad" type="text"
                                        v-model="form.escolaridad"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.escolaridad" v-text="form.errors.escolaridad" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Ocupación:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="relative">
                                        <select
                                            v-model="form.ocupacion"
                                            class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                            id="ocupacion">
                                            <option value="null" disabled>Seleccionar</option>
                                            <option :value="ocupacion.ocupaciones_id" v-for="ocupacion in ocupaciones">{{ ocupacion.nombre_ocupacion }}</option>
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
                                        ¿Pertenece a alguna organización de la sociedad civil/colectivo?
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="flex items-center my-2">
                                        <label for="sociedad_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.organizacion_pertenece"
                                                id="sociedad_si"
                                                name="sociedad"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="sociedad_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.organizacion_pertenece"
                                                id="sociedad_no"
                                                name="sociedad"
                                                type="radio"
                                                :value="0"
                                                class="w-4 h-4">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2" v-if="isTrue(form.organizacion_pertenece)">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        ¿Cúal?:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="sociedadCivil" type="text"
                                        v-model="form.cual_organizacion_pertenece"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.cual_organizacion_pertenece" v-text="form.errors.cual_organizacion_pertenece" class="text-red-500"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template #tab-content-7>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        ¿Realiza alguna actividad donde perciba un ingreso económico?*
                                    </label>
                                </div>
                                <div class="">
                                    <div class="flex items-center my-2">
                                        <label for="ingreso_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.actividad_ingreso"
                                                id="ingreso_si"
                                                name="ingreso"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="ingreso_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.actividad_ingreso"
                                                id="ingreso_no"
                                                name="ingreso"
                                                type="radio"
                                                :value="0"
                                                class="w-4 h-4">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2" v-if="isTrue(form.actividad_ingreso)">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        ¿Cúal?:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="ingreso" type="text"
                                        v-model="form.cual_actividad_ingreso"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.cual_actividad_ingreso" v-text="form.errors.cual_actividad_ingreso" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        ¿Pertenece a algún programa social?*
                                    </label>
                                </div>
                                <div class="">
                                    <div class="flex items-center my-2">
                                        <label for="programa_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.programa_social"
                                                id="programa_si"
                                                name="programa"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="programa_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.programa_social"
                                                id="programa_no"
                                                name="programa"
                                                type="radio"
                                                :value="0"
                                                class="w-4 h-4">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2" v-if="isTrue(form.programa_social)">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        ¿Cúal?:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="programaSocial" type="text"
                                        v-model="form.cual_programa_social"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.cual_programa_social" v-text="form.errors.cual_programa_social" class="text-red-500"></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        ¿Cuenta con algún apoyo que provenga de la Federación o del Estado?*
                                    </label>
                                </div>
                                <div class="">
                                    <div class="flex items-center my-2">
                                        <label for="apoyo_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.apoyo_federacion_estado"
                                                id="apoyo_si"
                                                name="apoyo"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="apoyo_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.apoyo_federacion_estado"
                                                id="apoyo_no"
                                                name="apoyo"
                                                type="radio"
                                                :value="0"
                                                class="w-4 h-4">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2" v-if="isTrue(form.apoyo_federacion_estado)">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        ¿Cúal?:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="apoyoEstado" type="text"
                                        v-model="form.cual_apoyo_federacion_estado"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.cual_apoyo_federacion_estado" v-text="form.errors.cual_apoyo_federacion_estado" class="text-red-500"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template #tab-content-8>
                    <div  class="w-full max-w-xl mx-auto">
                        <div class="mb-2">
                            <div class="grid content-center">
                                <label class="block mb-1 md:mb-0 pr-4">
                                    Derivado del hecho victimizante ¿presenta alguna afectación de salud?*
                                </label>
                            </div>
                            <div class="">
                                <div class="flex items-center my-2">
                                    <label for="afectacion_si" class="inline-flex items-center">
                                        <input
                                            v-model="form.afectacion_salud"
                                            id="afectacion_si"
                                            name="afectacion"
                                            type="radio"
                                            :value="1"
                                            class="w-4 h-4">
                                        <span class="ml-2">Si</span>
                                    </label>
                                    <label for="afectacion_no" class="ml-4 inline-flex items-center">
                                        <input
                                            v-model="form.afectacion_salud"
                                            id="afectacion_no"
                                            name="afectacion"
                                            type="radio"
                                            :value="0"
                                            class="w-4 h-4">
                                        <span class="ml-2">No</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="md:flex md:item-center mb-2" v-if="isTrue(form.afectacion_salud)">
                            <div class="md:w-1/3 grid content-center">
                                <label class="block mb-1 md:mb-0 pr-4">
                                    ¿Cúal?:
                                </label>
                            </div>
                            <div class="md:w-2/3">
                                <input  id="afectacion" type="text"
                                    v-model="form.cual_afectacion_salud"
                                    class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                <div v-if="form.errors.cual_afectacion_salud" v-text="form.errors.cual_afectacion_salud" class="text-red-500"></div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="grid content-center">
                                <label class="block mb-1 md:mb-0 pr-4">
                                    ¿Cuenta con servicio de atención médica?*
                                </label>
                            </div>
                            <div class="">
                                <div class="flex items-center my-2">
                                    <label for="medica_si" class="inline-flex items-center">
                                        <input
                                            v-model="form.servicio_atencion_medica"
                                            id="medica_si"
                                            name="medica"
                                            type="radio"
                                            :value="1"
                                            class="w-4 h-4">
                                        <span class="ml-2">Si</span>
                                    </label>
                                    <label for="medica_no" class="ml-4 inline-flex items-center">
                                        <input
                                            v-model="form.servicio_atencion_medica"
                                            id="medica_no"
                                            name="medica"
                                            type="radio"
                                            :value="0"
                                            class="w-4 h-4">
                                        <span class="ml-2">No</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="md:flex md:item-center mb-2" v-if="isTrue(form.servicio_atencion_medica)">
                            <div class="md:w-1/3 grid content-center">
                                <label class="block mb-1 md:mb-0 pr-4">
                                    ¿Cúal?:
                                </label>
                            </div>
                            <div class="md:w-2/3">
                                <input  id="medica" type="text"
                                    v-model="form.cual_servicio_atencion_medica"
                                    class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                <div v-if="form.errors.cual_servicio_atencion_medica" v-text="form.errors.cual_servicio_atencion_medica" class="text-red-500"></div>
                            </div>
                        </div>
                    </div>
                </template>
                <template #tab-content-9>
                    <div  class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Niña, Niño, Adolescente*
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="flex items-center my-2">
                                        <label for="nino_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.es_adolescente"
                                                id="nino_si"
                                                name="nino"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="nino_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.es_adolescente"
                                                id="nino_no"
                                                name="nino"
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
                                        Adulto/a Mayor*
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="flex items-center my-2">
                                        <label for="mayor_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.es_adulto_mayor"
                                                id="mayor_si"
                                                name="mayor"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="mayor_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.es_adulto_mayor"
                                                id="mayor_no"
                                                name="mayor"
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
                                        LGBTTTI*
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="flex items-center my-2">
                                        <label for="LGBTTTI_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.es_lgbttti"
                                                id="LGBTTTI_si"
                                                name="LGBTTTI"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="LGBTTTI_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.es_lgbttti"
                                                id="LGBTTTI_no"
                                                name="LGBTTTI"
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
                                        Discapacidad*
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="flex items-center my-2">
                                        <label for="discapacidad_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.es_discapacitado"
                                                id="discapacidad_si"
                                                name="discapacidad"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="discapacidad_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.es_discapacitado"
                                                id="discapacidad_no"
                                                name="discapacidad"
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
                                        Situación de calle*
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="flex items-center my-2">
                                        <label for="calle_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.es_situacion_calle"
                                                id="calle_si"
                                                name="calle"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="calle_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.es_situacion_calle"
                                                id="calle_no"
                                                name="calle"
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
                                        Migrante*
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="flex items-center my-2">
                                        <label for="migrante_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.es_migrante"
                                                id="migrante_si"
                                                name="migrante"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="migrante_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.es_migrante"
                                                id="migrante_no"
                                                name="migrante"
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
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Desplazamiento por hecho victimizante*
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="flex items-center my-2">
                                        <label for="desplazamiento_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.es_desplazado_por_hecho"
                                                id="desplazamiento_si"
                                                name="desplazamiento"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="desplazamiento_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.es_desplazado_por_hecho"
                                                id="desplazamiento_no"
                                                name="desplazamiento"
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
                                        Periodista*
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="flex items-center my-2">
                                        <label for="periodista_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.es_periodista"
                                                id="periodista_si"
                                                name="periodista"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="periodista_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.es_periodista"
                                                id="periodista_no"
                                                name="periodista"
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
                                        Defensor/a de DDHH*
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="flex items-center my-2">
                                        <label for="defensor_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.es_defensor_ddhh"
                                                id="defensor_si"
                                                name="defensor"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="defensor_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.es_defensor_ddhh"
                                                id="defensor_no"
                                                name="defensor"
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
                                        Violencia de género*
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="flex items-center my-2">
                                        <label for="vgenero_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.es_violencia_genero"
                                                id="vgenero_si"
                                                name="vgenero"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="vgenero_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.es_violencia_genero"
                                                id="vgenero_no"
                                                name="vgenero"
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
                                        Trastorno mental*
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="flex items-center my-2">
                                        <label for="tmental_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.es_trastorno_mental"
                                                id="tmental_si"
                                                name="tmental"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="tmental_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.es_trastorno_mental"
                                                id="tmental_no"
                                                name="tmental"
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
                                        VIH*
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="flex items-center my-2">
                                        <label for="vih_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.es_vih"
                                                id="vih_si"
                                                name="vih"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="vih_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.es_vih"
                                                id="vih_no"
                                                name="vih"
                                                type="radio"
                                                :value="0"
                                                class="w-4 h-4">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Observaciones
                                    </label>
                                </div>
                                <div class="">
                                    <textarea id="observacionesVulnerables"
                                        v-model="form.vulnerabilidades_observaciones"
                                        rows="5"
                                        placeholder=""
                                        class="bg-white rounded-2xl w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal"
                                    ></textarea>
                                    <div v-if="form.errors.vulnerabilidades_observaciones" v-text="form.errors.vulnerabilidades_observaciones" class="text-red-500"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template #tab-content-10>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Atención Médica De Urgencia y Gestión Médica
                                    </label>
                                </div>
                                <div class="">
                                    <div class="flex items-center my-2">
                                        <label for="atencionMedica_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.gestion_medica"
                                                id="atencionMedica_si"
                                                name="atencionMedica"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="atencionMedica_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.gestion_medica"
                                                id="atencionMedica_no"
                                                name="atencionMedica"
                                                type="radio"
                                                :value="0"
                                                class="w-4 h-4">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <template v-if="isTrue(form.gestion_medica)">
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Necesidades requeridas:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="necesidadesAtencionMedica" type="text"
                                            v-model="form.gestion_medica_necesidades"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.gestion_medica_necesidades" v-text="form.errors.gestion_medica_necesidades" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Derivación:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="derivacionAtencionMedica" type="text"
                                            v-model="form.gestion_medica_derivacion"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.gestion_medica_derivacion" v-text="form.errors.gestion_medica_derivacion" class="text-red-500"></div>
                                    </div>
                                </div>
                            </template>

                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Trabajo Social y Gestión
                                    </label>
                                </div>
                                <div class="">
                                    <div class="flex items-center my-2">
                                        <label for="trabajoSocial_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.gestion_social"
                                                id="trabajoSocial_si"
                                                name="trabajoSocial"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="trabajoSocial_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.gestion_social"
                                                id="trabajoSocial_no"
                                                name="trabajoSocial"
                                                type="radio"
                                                :value="0"
                                                class="w-4 h-4">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <template v-if="isTrue(form.gestion_social)">
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Necesidades requeridas:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="necesidadesTrabajoSocial" type="text"
                                            v-model="form.gestion_social_necesidades"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.gestion_social_necesidades" v-text="form.errors.gestion_social_necesidades" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Derivación:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="derivacionTrabajoSocial" type="text"
                                            v-model="form.gestion_social_derivacion"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.gestion_social_derivacion" v-text="form.errors.gestion_social_derivacion" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Número de Seguro Social:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="numeroSeguroSocial" type="text"
                                            v-model="form.gestion_social_num_seguro_social"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.gestion_social_num_seguro_social" v-text="form.errors.gestion_social_num_seguro_social" class="text-red-500"></div>
                                    </div>
                                </div>
                            </template>

                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Atención Psicológica
                                    </label>
                                </div>
                                <div class="">
                                    <div class="flex items-center my-2">
                                        <label for="atencionPsicologica_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.atencion_psicologica"
                                                id="atencionPsicologica_si"
                                                name="atencionPsicologica"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="atencionPsicologica_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.atencion_psicologica"
                                                id="atencionPsicologica_no"
                                                name="atencionPsicologica"
                                                type="radio"
                                                :value="0"
                                                class="w-4 h-4">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <template v-if="isTrue(form.atencion_psicologica)">
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Necesidades requeridas:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="necesidadesAtencionPsicologica" type="text"
                                            v-model="form.atencion_psicologica_necesidades"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.atencion_psicologica_necesidades" v-text="form.errors.atencion_psicologica_necesidades" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Derivación:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="derivacionAtencionPsicologica" type="text"
                                            v-model="form.atencion_psicologica_derivacion"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.atencion_psicologica_derivacion" v-text="form.errors.atencion_psicologica_derivacion" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            ¿Es atención interna o vinculación?
                                        </label>
                                    </div>
                                    <div class="">
                                        <div class="flex items-center my-2">
                                            <label for="atencionPsicologica_interna" class="inline-flex items-center">
                                                <input
                                                    v-model="form.atencion_psicologica_es_interna"
                                                    id="atencionPsicologica_interna"
                                                    name="atencionPsicologicaTipo"
                                                    type="radio"
                                                    :value="1"
                                                    class="w-4 h-4">
                                                <span class="ml-2">Interna</span>
                                            </label>
                                            <label for="atencionPsicologica_vinculacion" class="ml-4 inline-flex items-center">
                                                <input
                                                    v-model="form.atencion_psicologica_es_interna"
                                                    id="atencionPsicologica_vinculacion"
                                                    name="atencionPsicologicaTipo"
                                                    type="radio"
                                                    :value="0"
                                                    class="w-4 h-4">
                                                <span class="ml-2">Vinculación</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2" v-if="isTrue(form.atencion_psicologica_es_interna) && user_role == 4 && user_area == 2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Psicólogo a cargo:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <div class="relative">
                                            <select
                                                v-model="form.atencion_psicologica_psicologo_id"
                                                class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                id="psicologo">
                                                <option value="null" disabled>Seleccionar</option>
                                                <option :value="psicologo.id" v-for="psicologo in psicologoCargo">{{ psicologo.name }}</option>
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
                        <div>
                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Asesoría Jurídica
                                    </label>
                                </div>
                                <div class="">
                                    <div class="flex items-center my-2">
                                        <label for="atencionJuridica_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.asesoria_juridica"
                                                id="atencionJuridica_si"
                                                name="atencionJuridica"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="atencionJuridica_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.asesoria_juridica"
                                                id="atencionJuridica_no"
                                                name="atencionJuridica"
                                                type="radio"
                                                :value="0"
                                                class="w-4 h-4">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <template v-if="isTrue(form.asesoria_juridica)">
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Necesidades requeridas:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="necesidadesAtencionJuridica" type="text"
                                            v-model="form.asesoria_juridica_necesidades"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.asesoria_juridica_necesidades" v-text="form.errors.asesoria_juridica_necesidades" class="text-red-500"></div>
                                    </div>
                                </div>
                                <div class="md:flex md:item-center mb-2">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Derivación:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <input  id="derivacionAtencionJuridica" type="text"
                                            v-model="form.asesoria_juridica_derivacion"
                                            class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                        <div v-if="form.errors.asesoria_juridica_derivacion" v-text="form.errors.asesoria_juridica_derivacion" class="text-red-500"></div>
                                    </div>
                                </div>
                                <template v-if="user_role == 4 && user_area == 4">
                                    <div class="flex justify-between my-2">
                                        <label for="atencionJuridicaInicial" class="inline-flex items-center">
                                            <input
                                                v-model="form.asesoria_juridica_opciones"
                                                id="atencionJuridicaInicial"
                                                type="checkbox"
                                                value="Inicial"
                                                class="w-4 h-4 rounded-full">
                                            <span class="ml-2">Inicial</span>
                                        </label>
                                        <label for="atencionJuridicaSeguimiento" class="inline-flex items-center">
                                            <input
                                                v-model="form.asesoria_juridica_opciones"
                                                id="atencionJuridicaSeguimiento"
                                                type="checkbox"
                                                value="Seguimiento"
                                                class="w-4 h-4 rounded-full">
                                            <span class="ml-2">Seguimiento</span>
                                        </label>
                                        <label for="atencionJuridicaProcesal" class="inline-flex items-center">
                                            <input
                                                v-model="form.asesoria_juridica_opciones"
                                                id="atencionJuridicaProcesal"
                                                type="checkbox"
                                                value="Procesal"
                                                class="w-4 h-4 rounded-full">
                                            <span class="ml-2">Procesal</span>
                                        </label>
                                        <label for="atencionJuridicaDEA" class="inline-flex items-center">
                                            <input
                                                v-model="form.asesoria_juridica_opciones"
                                                id="atencionJuridicaDEA"
                                                type="checkbox"
                                                value="DEA"
                                                class="w-4 h-4 rounded-full">
                                            <span class="ml-2">DEA</span>
                                        </label>
                                        <label for="atencionJuridicaOtros" class="inline-flex items-center">
                                            <input
                                                v-model="form.asesoria_juridica_opciones"
                                                id="atencionJuridicaOtros"
                                                type="checkbox"
                                                value="Otros"
                                                class="w-4 h-4 rounded-full">
                                            <span class="ml-2">Otros</span>
                                        </label>
                                    </div>
                                    <div class="md:flex md:item-center mb-2" v-if="form.asesoria_juridica_opciones.includes('Otros')">
                                        <div class="md:w-1/3 grid content-center">
                                            <label class="block mb-1 md:mb-0 pr-4">
                                                Otros:
                                            </label>
                                        </div>
                                        <div class="md:w-2/3">
                                            <input  id="asesoria_juridica_opciones_otros" type="text"
                                                v-model="form.asesoria_juridica_opciones_otros"
                                                class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                            <div v-if="form.errors.asesoria_juridica_opciones_otros" v-text="form.errors.asesoria_juridica_opciones_otros" class="text-red-500"></div>
                                        </div>
                                    </div>
                                </template>
                                <div class="md:flex md:item-center mb-2" v-if="user_role == 4 && user_area == 4">
                                    <div class="md:w-1/3 grid content-center">
                                        <label class="block mb-1 md:mb-0 pr-4">
                                            Asesor jurídico:
                                        </label>
                                    </div>
                                    <div class="md:w-2/3">
                                        <div class="relative">
                                            <select
                                                v-model="form.asesoria_juridica_asesor_id"
                                                class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                                                id="asesorJuridico">
                                                <option value="null" disabled>Seleccionar</option>
                                                <option :value="psicologo.id" v-for="psicologo in asesorJuridico">{{ psicologo.name }}</option>
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
                                        Solicitudes de Medidas de Ayuda
                                    </label>
                                </div>
                                <div class="">
                                    <div class="flex items-center my-2">
                                        <label for="medidasAyuda_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.solicitudes_ayuda"
                                                id="medidasAyuda_si"
                                                name="medidasAyuda"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="medidasAyuda_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.solicitudes_ayuda"
                                                id="medidasAyuda_no"
                                                name="medidasAyuda"
                                                type="radio"
                                                :value="0"
                                                class="w-4 h-4">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <template v-if="isTrue(form.solicitudes_ayuda)">
                                <div class="flex flex-wrap justify-between my-2">
                                    <label for="medidasAyudaTraslado" class="inline-flex items-center">
                                        <input
                                            v-model="form.solicitudes_ayuda_opciones"
                                            id="medidasAyudaTraslado"
                                            type="checkbox"
                                            value="Traslado"
                                            class="w-4 h-4 rounded-full">
                                        <span class="ml-2">Traslado</span>
                                    </label>
                                    <label for="medidasAyudaGeneral" class="inline-flex items-center">
                                        <input
                                            v-model="form.solicitudes_ayuda_opciones"
                                            id="medidasAyudaGeneral"
                                            type="checkbox"
                                            value="General"
                                            class="w-4 h-4 rounded-full">
                                        <span class="ml-2">General</span>
                                    </label>
                                    <label for="medidasAyudaCompensacion" class="inline-flex items-center">
                                        <input
                                            v-model="form.solicitudes_ayuda_opciones"
                                            id="medidasAyudaCompensacion"
                                            type="checkbox"
                                            value="Compensación"
                                            class="w-4 h-4 rounded-full">
                                        <span class="ml-2">Compensación</span>
                                    </label>
                                    <label for="medidasAyudaReparacion" class="inline-flex items-center">
                                        <input
                                            v-model="form.solicitudes_ayuda_opciones"
                                            id="medidasAyudaReparacion"
                                            type="checkbox"
                                            value="Reparación del daño"
                                            class="w-4 h-4 rounded-full">
                                        <span class="ml-2">Reparación del daño</span>
                                    </label>
                                    <label for="medidasAyudaVulnerabilidad" class="inline-flex items-center">
                                        <input
                                            v-model="form.solicitudes_ayuda_opciones"
                                            id="medidasAyudaVulnerabilidad"
                                            type="checkbox"
                                            value="Abasto Vulnerabilidad"
                                            class="w-4 h-4 rounded-full">
                                        <span class="ml-2">Abasto Vulnerabilidad</span>
                                    </label>
                                    <label for="medidasAyudaDesaparicion" class="inline-flex items-center">
                                        <input
                                            v-model="form.solicitudes_ayuda_opciones"
                                            id="medidasAyudaDesaparicion"
                                            type="checkbox"
                                            value="Abasto Desaparición"
                                            class="w-4 h-4 rounded-full">
                                        <span class="ml-2">Abasto Desaparición</span>
                                    </label>
                                    <label for="medidasAyudaAlojamiento" class="inline-flex items-center">
                                        <input
                                            v-model="form.solicitudes_ayuda_opciones"
                                            id="medidasAyudaAlojamiento"
                                            type="checkbox"
                                            value="Alojamiento"
                                            class="w-4 h-4 rounded-full">
                                        <span class="ml-2">Alojamiento</span>
                                    </label>
                                </div>
                            </template>

                        </div>
                    </div>
                </template>
                <template #tab-content-11>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        ¿Solicita ingreso del Registro Estatal de Víctimas?*
                                    </label>
                                </div>
                                <div class="">
                                    <div class="flex items-center my-2">
                                        <label for="ingresoRev_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.registro_rev"
                                                id="ingresoRev_si"
                                                name="ingresoRev"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="ingresoRev_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.registro_rev"
                                                id="ingresoRev_no"
                                                name="ingresoRev"
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
                                        Con relación a niñas, niños y/o adolescentes para REV, ¿usted ejerce la guardia y custodia?
                                    </label>
                                </div>
                                <div class="">
                                    <div class="flex items-center my-2">
                                        <label for="custodia_si" class="inline-flex items-center">
                                            <input
                                                v-model="form.ejerce_custodia_rev"
                                                id="custodia_si"
                                                name="custodia"
                                                type="radio"
                                                :value="1"
                                                class="w-4 h-4">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label for="custodia_no" class="ml-4 inline-flex items-center">
                                            <input
                                                v-model="form.ejerce_custodia_rev"
                                                id="custodia_no"
                                                name="custodia"
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
                                        En caso de no tener a su cargo la guardia y custodia ¿quién la ejerce?
                                    </label>
                                </div>
                                <div class="">
                                    <input  id="ejerceCustodia" type="text"
                                        v-model="form.quien_ejerce_custodia_rev"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.quien_ejerce_custodia_rev" v-text="form.errors.quien_ejerce_custodia_rev" class="text-red-500"></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Observaciones
                                    </label>
                                </div>
                                <div class="">
                                    <textarea id="observacionesRev"
                                        v-model="form.observaciones_rev"
                                        rows="3"
                                        placeholder=""
                                        class="bg-white rounded-2xl w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal"
                                    ></textarea>
                                    <div v-if="form.errors.observaciones_rev" v-text="form.errors.observaciones_rev" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        ¿Cómo se enteró de los servicios de la CEEAIV?
                                    </label>
                                </div>
                                <div class="">
                                    <textarea id="observacionesCeeaiv"
                                        v-model="form.como_se_entero_ceeaiv"
                                        rows="3"
                                        placeholder=""
                                        class="bg-white rounded-2xl w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal"
                                    ></textarea>
                                    <div v-if="form.errors.como_se_entero_ceeaiv" v-text="form.errors.como_se_entero_ceeaiv" class="text-red-500"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </Tabs>
        </template>
        <template #modal-footer>
            <button type="button" :title="!saveStatus ? 'Requerido Guargar pasos 8, 9 y 10' : ''" :disabled="!saveStatus" @click="changeTab(12)"  class="btn-normal w-full sm:w-auto disabled:bg-gray-light disabled:text-black disabled:border-gray-normal disabled:border-2">Guardar</button>
        </template>
    </Modal>

    <Modal ref="modalShowData" :size="'max-w-6xl'">
        <template #modal-header>
            <div class="text-center">
                <p>Entrevista Primer Contacto</p>
            </div>
        </template>
        <template #modal-body>
            <div class="grid lg:grid-cols-2 gap-4">
                <div class="border-r border-slate-400">
                    <div class="font-bold my-2">
                        1. Datos de Atención
                    </div>
                    <div>
                        <p>Modalidad de atención: {{ form.modalidad_atencion }}</p>
                        <p>Tipo de atención: {{ form.colaboracion == 1 ? 'Con colaboración' : 'Sin colaboración' }}</p>
                        <template v-if="form.colaboracion == 1">
                            <p>Institución: {{  form.institucion }}</p>
                            <p>No Oficio: {{form.no_oficio  }}</p>
                        </template>
                    </div>
                    <div class="font-bold mb-2 mt-6">
                        2. Datos Solicitantes
                    </div>
                    <div>
                        <p>Nombre completo: {{form.solicitante_nombres}} {{ form.solicitante_primer_apellido}} {{form.solicitante_segundo_apellido}}</p>
                        <p>Sexo: {{ form.sexos_id == 1 ? 'Hombre' : 'Mujer' }}</p>
                        <p>Fecha de Nacimiento*: {{ form.fecha_nacimiento}}</p>
                        <p>Nacionalidad: {{ getNacionalidad(form.territorios_id) }}</p>
                        <template v-if="form.territorios_id == 1">
                            <p>Entidad Federativa: {{ getEntidadFederativa(form.entidades_federativas_id) }}</p>
                            <p>Delegación o municipio: {{ getMunicipios(form.municipios_id) }}</p>
                            <p>Población o Comunidad: {{ form.poblacion }}</p>
                        </template>
                        <template v-if="form.territorios_id == 2">
                            <p>País: {{ getPais(form.paises_id) }}</p>
                        </template>
                        <p>Teléfono(s): {{ form.telefonos.join(',') }}</p>
                    </div>
                    <div class="font-bold mb-2 mt-6">
                        3. Relato de los Hechos
                    </div>
                    <div>
                        <p>Nacionalidad: {{ getNacionalidad(form.hechos_territorios_id) }}</p>
                        <template v-if="form.hechos_territorios_id == 1">
                            <p>Entidad Federativa: {{ getEntidadFederativa(form.hechos_entidades_federativas_id) }}</p>
                            <p>Delegación o municipio: {{ getMunicipios(form.hechos_municipios_id) }}</p>
                            <p>Población o Comunidad: {{ form.hechos_poblacion }}</p>
                        </template>
                        <template v-if="form.hechos_territorios_id == 2">
                            <p>País: {{ getPais(form.hechos_paises_id) }}</p>
                        </template>
                        <p>Relato de Hechos: {{form.hechos_relato}}</p>
                    </div>
                    <div class="font-bold mb-2 mt-6">
                        4. Ambito de Competencia
                    </div>
                    <div>
                        <p>¿Es una competencia directa? {{form.es_competencia_ceeaiv == 1 ? 'Si' : 'No'}}</p>
                        <p>Especificación de la orientación: {{ form.orientacion_brindada }}</p>
                    </div>
                    <div class="font-bold mb-2 mt-6">
                        5. Datos de Identificación de la Victima Directa
                    </div>
                    <div>
                        <p>Nombre completo: {{ form.victima_nombres }} {{  form.victima_primer_apellido }} {{form.victima_segundo_apellido}}</p>
                        <p>Fecha de Nacimiento: {{form.victima_fecha_nacimiento}}</p>
                        <p>Ocupación: {{ getOcupacion(form.victima_ocupacion_id) }}</p>
                        <p>Delito provisional: {{ getDelito(form.delitos_id) }}</p>
                        <p>Violación DDHH provisional: {{ getViolacion(form.violaciones_id) }}</p>
                        <p>Número de carpeta: {{ form.no_carpeta }}</p>
                        <p>Fiscalía: {{form.fiscalia}}</p>
                    </div>
                    <div class="font-bold mb-2 mt-6">
                        6. Datos de Núcleo Familiar de la Victima Directa
                    </div>
                    <div>
                        <p>Observaciones: {{ form.familiar_observaciones }}</p>
                        <div v-for="(familia, index) in form.familiares" class="border-t-2 border-gray-normal mr-2">
                            <p>Nombre Completo: {{ familia.nombre_completo }}</p>
                            <p>Edad: {{ familia.edad }}</p>
                            <p>Parentesco: {{ getParentesco(familia.parentesco_id) }}</p>
                            <p>Dependencia Económica: {{ familia.dependencia_economica }}</p>
                            <p>Factores de Vulneravilidad: {{ familia.factores_vulnerabilidad }}</p>
                        </div>
                    </div>
                </div>
                <div class="">
                    <div class="font-bold my-2">
                        7. Domicilio de Notificaciones
                    </div>
                    <div>
                        <p>Nacionalidad: {{ getNacionalidad(form.notificaciones_territorios_id) }}</p>
                        <template v-if="form.notificaciones_territorios_id == 1">
                            <p>Entidad Federativa: {{ getEntidadFederativa(form.notificaciones_entidades_federativas_id) }}</p>
                            <p>Delegación o municipio: {{ getMunicipios(form.notificaciones_municipios_id) }}</p>
                        </template>
                        <template v-if="form.notificaciones_territorios_id == 2">
                            <p>País: {{ getPais(form.notificaciones_paises_id) }}</p>
                        </template>
                        <p>Codigo Postal: {{  form.codigo_postal  }}</p>
                        <p>Colonia: {{ form.colonia }}</p>
                        <p>Localidad: {{  form.localidad  }}</p>
                        <p>Calle: {{  form.calle }}</p>
                        <p>Número Exterior: {{form.no_exterior }}</p>
                        <p>Número Interior: {{form.no_interior }}</p>
                        <p>Estado Civil: {{  form.estado_civil }}</p>
                        <p>Escolaridad: {{  form.escolaridad  }}</p>
                        <p>Ocupación: {{ getOcupacion(form.victima_ocupacion_id) }}</p>
                        <p>¿Pertenece a alguna organizacion de la sociedad civil/colectivo?: {{   form.organizacion_pertenece ? 'Si' : 'No'  }}</p>
                        <p v-if="form.organizacion_pertenece == 1">¿Cúal?: {{  form.cual_organizacion_pertenece }}</p>
                    </div>
                    <div class="font-bold mb-2 mt-6">
                        8. Datos Socioecómicos
                    </div>
                    <div>
                        <p>¿Realiza alguna actividad donde perciba un ingreso económico? {{form.actividad_ingreso == 1 ? 'SI' : 'No' }}</p>
                        <p v-if="form.actividad_ingreso == 1">¿Cúal?: {{   form.cual_actividad_ingreso }}</p>
                        <p>¿Pertenece a algún programa social? {{form.programa_social  == 1 ? 'Si' : 'No' }}</p>
                        <p v-if="form.programa_social == 1">¿Cúal?: {{ form.cual_programa_social }}</p>
                        <p>¿Cuenta con algún apoyo que provenga de la Federación o del Estado? {{form.apoyo_federacion_estado  == 1 ? 'Si' : 'No' }}</p>
                        <p v-if="form.apoyo_federacion_estado == 1">¿Cúal?: {{ form.cual_apoyo_federacion_estado }}</p>
                    </div>
                    <div class="font-bold mb-2 mt-6">
                        9. Salud
                    </div>
                    <div>
                        <p>Derivado del hecho victimizante ¿Presenta alguna afectación de la salud? {{form.afectacion_salud  == 1 ? 'Si' : 'No' }}</p>
                        <p v-if="form.afectacion_salud == 1">¿Cúal?: {{ form.cual_afectacion_salud }}</p>
                        <p>¿Cuenta con servicio de atención médica? {{form.servicio_atencion_medica  == 1 ? 'Si' : 'No' }}</p>
                        <p v-if="form.servicio_atencion_medica == 1">¿Cúal?: {{ form.cual_servicio_atencion_medica }}</p>
                    </div>
                    <div class="font-bold mb-2 mt-6">
                        10. Factores de Vulnerabilidad
                    </div>
                    <div>
                        <p>Niña, Niño, Adolescente: {{ form.es_adolescente == 1 ? 'Si' : 'No'  }}</p>
                        <p>Adulto/a mayor: {{ form.es_adulto_mayor == 1 ? 'Si' : 'No'  }}</p>
                        <p>LGBTTTI: {{ form.es_lgbttti == 1 ? 'Si' : 'No'  }}</p>
                        <p>Discapacidad: {{ form.es_discapacitado == 1 ? 'Si' : 'No'  }}</p>
                        <p>Situación de calle: {{ form.es_situacion_calle == 1 ? 'Si' : 'No'  }}</p>
                        <p>Migrante: {{ form.es_migrante == 1 ? 'Si' : 'No'  }}</p>
                        <p>Desplazamiento por hecho victimizante: {{ form.es_desplazado_por_hecho == 1 ? 'Si' : 'No'  }}</p>
                        <p>Periodista: {{ form.es_periodista == 1 ? 'Si' : 'No'  }}</p>
                        <p>Defensor/a de DDHH: {{ form.es_defensor_ddhh == 1 ? 'Si' : 'No'  }}</p>
                        <p>Violencia de género: {{ form.es_violencia_genero == 1 ? 'Si' : 'No'  }}</p>
                        <p>Trastorno mental: {{ form.es_trastorno_mental == 1 ? 'Si' : 'No'  }}</p>
                        <p>VIH: {{ form.es_vih == 1 ? 'Si' : 'No'  }}</p>
                        <p>Observaciones: {{ form.vulnerabilidades_observaciones }}</p>
                    </div>
                    <div class="font-bold mb-2 mt-6">
                        11. Plan de Atención Integral
                    </div>
                    <div>
                        <p>Atención Médica De Urgencia y Gestión Médica: {{ form.gestion_medica == 1 ? 'Si' : 'No' }}</p>
                        <template v-if="form.gestion_medica == 1">
                            <p>Necesidades requeridas: {{ form.gestion_medica_necesidades }}</p>
                            <p>Derivación: {{ form.gestion_medica_derivacion }}</p>
                        </template>
                        <p>Trabajo Social y Gestión: {{ form.gestion_social == 1 ? 'Si' : 'No' }}</p>
                        <template v-if="form.gestion_social == 1">
                            <p>Necesidades requeridas: {{ form.gestion_social_necesidades }}</p>
                            <p>Derivación: {{ form.gestion_social_derivacion }}</p>
                            <p>Número de Seguro Social: {{ form.gestion_social_num_seguro_social }}</p>
                        </template>
                        <p>Atención Psicológica: {{  form.atencion_psicologica == 1 ? 'Si' : 'No' }}</p>
                        <template v-if="form.atencion_psicologica == 1">
                            <p>Necesidades requeridas: {{ form.atencion_psicologica_necesidades }}</p>
                            <p>Derivación: {{ form.atencion_psicologica_derivacion }}</p>
                            <p>¿Es atención interna o vinculación? {{ form.atencion_psicologica_es_interna == 1 ? 'Interna' : 'Vinculación' }}</p>
                            <p v-if="form.atencion_psicologica_es_interna == 1">Psicólogo a cargo: {{ getPsicologo(form.atencion_psicologica_psicologo_id) }}</p>
                        </template>
                        <p>Asesoría Jurídica: {{ form.asesoria_juridica == 1 ? 'Si': 'No' }}</p>
                        <template v-if="form.asesoria_juridica == 1">
                            <p>Necesidades requeridas: {{ form.asesoria_juridica_necesidades }}</p>
                            <p>Derivación: {{ form.asesoria_juridica_derivacion }}</p>
                            <p>{{ form.asesoria_juridica_opciones.join(', ')}} {{ form.asesoria_juridica_opciones_otros ? ','+form.asesoria_juridica_opciones_otros : '' }}</p>
                            <p>Asesor Jurídico: {{ getJuridico(form.asesoria_juridica_asesor_id) }}</p>
                        </template>
                        <p>Solicitudes de Medidas de Ayuda: {{ form.solicitudes_ayuda == 1 ? 'Si' : 'No' }}</p>
                        <p v-if="form.solicitudes_ayuda == 1">{{ form.solicitudes_ayuda_opciones.join(', ') }}</p>
                    </div>
                    <div class="font-bold mb-2 mt-6">
                        12. Registro REV
                    </div>
                    <div>
                        <p>¿Solicita ingreso del Registro Estatal de Víctimas? {{ form.registro_rev == 1 ? 'Si' : 'No' }}</p>
                        <p>Con relación a niñas, niños y/o adolescentes para REV, ¿usted ejerce la guardia y custodia? {{ form.ejerce_custodia_rev == 1 ? 'Si' : 'No' }}</p>
                        <p>En caso de no tener a su cargo la guardia y custodia ¿quién la ejerce? {{ form.quien_ejerce_custodia_rev }}</p>
                        <p>Observaciones: {{ form.observaciones_rev }}</p>
                        <p>Cómo se enteró de los servicios de la CEEAIV? {{ form.como_se_entero_ceeaiv }}</p>
                    </div>
                    <div class="font-bold mb-2 mt-6">
                        Documentación
                    </div>
                    <div>
                        <p><a class="hover:underline" v-if="fileUploaded" target="blank" download :href="fileUploadedUrl">- Entrevista Primer Contacto</a></p>
                    </div>
                </div>
            </div>
        </template>
        <template #modal-footer>
            <!-- Puedes agregar contenido al pie de la modal si es necesario -->
        </template>
    </Modal>

    <Modal ref="modalEscaneo">
        <template #modal-header>
            <p>{{ fileUploaded ? 'Actualizar escaneo de Entrevista Primer Contacto' : 'Subir escaneo de Entrevista Primer Contacto' }}</p>
        </template>
        <template #modal-body>
            <div class="w-full max-w-md">
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
            <button type="button" @click="fileUploaded ? confirmarDocumento() : saveFileData()"  class="btn-normal w-full sm:w-auto">{{ fileUploaded ? 'Actualizar' : 'Guardar' }}</button>
        </template>
    </Modal>

    <Modal ref="modalPrinter">
        <template #modal-header>
            <p>Imprimir Datos</p>
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
                id="selectFormatoPrimerContacto"
                  class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                  @change="updateTipoFormato"
                  v-model="formPrinter.tipo_formato"
                >
                <option value="" disabled selected>Seleccionar formato</option>
                <option value="PRIMER_CONTACTO">Primer Contacto</option>
                <option value="CARTA_ADULTA_REGIONES">Carta compromiso personas adultas regiones</option>
                <option value="CARTA_ADULTA_CENTRALES">Carta compromiso NNA regiones</option>
                <option value="CARTA_NNA_CENTRALES">Carta compromiso NNA centrales</option>
                <option value="CARTA_NNA_REGIONES">Carta compromiso adulto centrales</option>
                </select>
           </div>
        </template>
        <template #modal-footer>
                <button type="button" @click="printerFile(captura)"  class="btn-normal w-full sm:w-auto">Aceptar</button>
        </template>
    </Modal>
</template>

<script setup>
import { ref, watch, onMounted, computed } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import Pagination from "../Components/Pagination.vue";
import debounce from "lodash/debounce";
import cloneDeep from "lodash/cloneDeep";
import moment from 'moment';
import axios from "axios";
import { useNotification } from "@kyvg/vue3-notification";
import Swal from 'sweetalert2'
import useErrors from '../Services/errors';

const customErrors = useErrors();
const { notify }  = useNotification();
const search = ref(props.filters.search);
const startDate = ref(moment().subtract(1, 'month').format('YYYY-MM-DD'));
const endDate = ref(moment().format('YYYY-MM-DD'));
const modalEntrevista = ref(null);
const modalEscaneo = ref(null);
const modalPrinter = ref(null);
const modalShowData = ref(null);
const modalOcupaciones = ref(null);
const fileUploaded = ref(false);
const fileUploadedUrl = ref(false);
const selectedFile = ref(null);
const selectedFileName = ref(null);
const selectedGeneralId = ref(null);
const currentStep = ref(1);
const solicitanteMunicipio = ref(null);
let solicitanteMunicipios = ref([]);
const hechosMunicipio = ref(null);
let hechosMunicipios = ref([]);
const notificacionesMunicipio = ref(null);
let notificacionesMunicipios = ref([]);
let saveStatus = ref(false);
const tabsPC = ref(null);
const user_role = computed(() => usePage().props.auth.user.role_id);
const user_area = computed(() => usePage().props.auth.user.area_id);
const loadingFile = ref(false);
let formOcupacion = useForm({
    nombre_ocupacion: '',
});

const swalWithBootstrapButtons = Swal.mixin({
  customClass: {
    confirmButton: "btn-normal text-black",
    cancelButton: "btn-close"
  },
  buttonsStyling: false,
  reverseButtons: true,
});

let props = defineProps({
    capturas: Object,
    filters: Object,
    sexos: Array,
    nacionalidades: Array,
    paises: Array,
    entidadesFederativas: Array,
    municipios: Array,
    tipoVictimas: Array,
    delitosProv: Array,
    violacionesDh: Array,
    parentescos: Array,
    ocupaciones: Array,
    psicologoCargo: Array,
    asesorJuridico: Array,
});

var tabItems =  [
    { name: 'Atención', status: 'block', validation: 'pending' },
    { name: 'Solicitante', status: 'hidden', validation: 'pending' },
    { name: 'Relato de los hechos', status: 'hidden', validation: 'pending' },
    { name: 'Ámbito de la compentencia', status: 'hidden', validation: 'pending' },
    { name: 'Identificación VD', status: 'hidden', validation: 'pending' },
    { name: 'Núcleo familiar VD', status: 'hidden', validation: 'pending' },
    { name: 'Domicilio notificaciones', status: 'hidden', validation: 'pending' },
    { name: 'Socioeconómicos', status: 'hidden', validation: 'required' },
    { name: 'Salud', status: 'hidden', validation: 'required' },
    { name: 'Factores vulnerables', status: 'hidden', validation: 'required' },
    { name: 'Plan atención integral', status: 'hidden', validation: 'pending' },
    { name: 'Registro REV', status: 'hidden', validation: 'pending' },
];

let form = useForm({
    tipo_victimas_id: null,
    generales_id: null,
    numeroPaso: 1,
    modalidad_atencion: null,
    colaboracion: 0,
    institucion: '',
    no_oficio: '',
    solicitante_nombres: '',
    solicitante_primer_apellido: '',
    solicitante_segundo_apellido: '',
    sexos_id: null,
    fecha_nacimiento: '',
    territorios_id: null,
    paises_id: null,
    entidades_federativas_id: null,
    municipios_id: null,
    poblacion: '',
    telefonos: [''],
    hechos_territorios_id: null,
    hechos_paises_id: null,
    hechos_entidades_federativas_id: null,
    hechos_municipios_id: null,
    hechos_poblacion: '',
    hechos_relato: '',
    es_competencia_ceeaiv: 0,
    orientacion_brindada: '',
    victima_nombres: '',
    victima_primer_apellido: '',
    victima_segundo_apellido: '',
    victima_fecha_nacimiento: '',
    victima_ocupacion_id: null,
    ocupaciones: [],
    violaciones_id: null,
    delitos_id: null,
    no_carpeta: '',
    fiscalia: '',
    familiares:[],
    familiar_observaciones: '',
    calle: '',
    no_exterior: '',
    no_interior: '',
    colonia: '',
    codigo_postal: '',
    localidad: '',
    notificaciones_territorios_id: null,
    notificaciones_paises_id: null,
    notificaciones_entidades_federativas_id: null,
    notificaciones_municipios_id: null,
    estado_civil: '',
    escolaridad: '',
    ocupacion: '',
    organizacion_pertenece: 0,
    cual_organizacion_pertenece: '',
    actividad_ingreso: 0,
    cual_actividad_ingreso: '',
    programa_social: 0,
    cual_programa_social: '',
    apoyo_federacion_estado: 0,
    cual_apoyo_federacion_estado: '',
    afectacion_salud: 0,
    cual_afectacion_salud: '',
    servicio_atencion_medica: 0,
    cual_servicio_atencion_medica: '',
    es_adolescente: 0,
    es_adulto_mayor: 0,
    es_lgbttti: 0,
    es_discapacitado: 0,
    es_situacion_calle: 0,
    es_migrante: 0,
    es_desplazado_por_hecho: 0,
    es_periodista: 0,
    es_defensor_ddhh: 0,
    es_violencia_genero: 0,
    es_trastorno_mental: 0,
    es_vih: 0,
    vulnerabilidades_observaciones: '',
    gestion_medica: 0,
    gestion_medica_necesidades: '',
    gestion_medica_derivacion: '',
    gestion_social: 0,
    gestion_social_necesidades: '',
    gestion_social_derivacion: '',
    gestion_social_num_seguro_social: '',
    atencion_psicologica: 0,
    atencion_psicologica_necesidades: '',
    atencion_psicologica_derivacion: '',
    atencion_psicologica_es_interna: 0,
    atencion_psicologica_psicologo_id: null,
    asesoria_juridica: 0,
    asesoria_juridica_necesidades: '',
    asesoria_juridica_derivacion: '',
    asesoria_juridica_opciones: [],
    asesoria_juridica_opciones_otros: '',
    asesoria_juridica_asesor_id: null,
    solicitudes_ayuda: 0,
    solicitudes_ayuda_opciones: [],
    registro_rev: 0,
    ejerce_custodia_rev: 0,
    quien_ejerce_custodia_rev: '',
    observaciones_rev: '',
    como_se_entero_ceeaiv: ''
});

let formFamiliar = useForm({
    nombre_completo: '',
    edad: '',
    parentesco_id: null,
    dependencia_economica: '',
    factores_vulnerabilidad: '',
    victimas_directas_id: null,
});

let formfile = useForm({
file: selectedFile.value,
generales_id: selectedGeneralId
});

let formPrinter = useForm({
        tipo_formato: "",
        generales_id: ""
    });
const updateTipoFormato = () => {  };

function showPrinter(data) {
    modalPrinter.value.show();
    formPrinter.generales_id = data.id;
    if (data) {
        getDataSelect(data.id);
    }

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
        saveFileData();
      }
    });
}

function showOcupacion(data){
    formOcupacion.reset();
    formOcupacion.clearErrors();
    modalOcupaciones.value.show();
}

function storeOcupacion() {
    formOcupacion.clearErrors();
    formOcupacion.post("ocupaciones", {
        onError: (errors) => {
            //HoldOn.close();
        },
        onSuccess: (data) => {
            //HoldOn.close();
            formOcupacion.reset();
            modalOcupaciones.value.hide();
            notify({
                title: "¡Éxito!",
                text: "Ocupación guardado correctamente.",
                type: "success"
            });
        },
    });
}

async function getDataSelect(id) {
    try {
        const res = await axios.get('/primer-contacto/' + id);
        let data = res.data.data;
        form.fecha_solicitante_nacimiento = new Date(data.solicitante_fecha_nacimiento);
        if (form.fecha_solicitante_nacimiento == null) {
            form.fecha_solicitante_nacimiento = new Date(victimas_fecha_nacimiento)
        }
        const fechaActual = new Date();
        const diferenciaTiempo = fechaActual.getTime() - form.fecha_solicitante_nacimiento.getTime();
        const edad = diferenciaTiempo / (1000 * 3600 * 24 * 365.25);
        form.atencion_psicologica = data.atencion_psicologica;
        form.es_adulto_mayor = data.es_adulto_mayor;
        form.municipios_id = data.solicitantes_municipios_id;
        let municipio = await getMunicipios(form.municipios_id);
        let select = document.getElementById('selectFormatoPrimerContacto');
        select.options.length = 0;
        let defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.text = 'Seleccionar formato';
        defaultOption.disabled = true;
        defaultOption.selected = true;
        select.add(defaultOption);
        let option = document.createElement('option');
        option.value = 'PRIMER_CONTACTO';
        option.text = 'Primer Contacto';
        select.add(option);
        if (edad >= 18 && data.atencion_psicologica == 1 && municipio != "Xalapa") {
                let option = document.createElement('option');
                option.value = 'CARTA_ADULTA_REGIONES';
                option.text = 'Carta compromiso personas adultas regiones 2023';
                select.add(option);
        }
        if (edad >= 18 && data.atencion_psicologica == 1 && municipio == "Xalapa") {
                let option = document.createElement('option');
                option.value = 'CARTA_ADULTA_CENTRALES';
                option.text = 'Carta compromiso adulto centrales';
                select.add(option);
        }
        if (edad < 18 && data.atencion_psicologica == 1 && municipio != "Xalapa") {
            let option = document.createElement('option');
                option.value = 'CARTA_ADULTA_CENTRALES';
                option.text = 'Carta compromiso NNA regiones';
                select.add(option);
        }
        if (edad < 18 && data.atencion_psicologica == 1 && municipio == "Xalapa") {
               let option = document.createElement('option');
                option.value = 'CARTA_NNA_CENTRALES';
                option.text = 'Carta compromiso NNA centrales';
                select.add(option);
        }
    } catch (error) {
        console.error("Error al obtener los datos:", error);
    }
}

function printerFile() {
    if (formPrinter.tipo_formato && formPrinter.generales_id != null) {
        axios.get(`/primer-contacto/imprimir-primer-contacto/${formPrinter.tipo_formato}/${formPrinter.generales_id}`, {
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

function onFileSelected(event) {
selectedFile.value = event.target.files[0]
selectedFileName.value =  selectedFile.value.name
}
function saveFileData() {
  if (selectedFile.value != null) {
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
    formfile.post("/primer-contacto/file",
      {
        onCancel: () => {
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

          modalEscaneo.value.hide();
          //HoldOn.close();
        },
      });
  } else {
    notify({
        title: "¡Archivo Vacío!",
        text: "Necesitas seleccionar un archivo para guardar.",
        type: "warn"
    });
  }
}

function resetTab(index = 0) {
    tabItems.every(function(item) {
        item.status = 'hidden';
        item.validation = 'pending';
        return true;
    });
    tabItems[7]['validation'] = 'required';
    tabItems[8]['validation'] = 'required';
    tabItems[9]['validation'] = 'required';
    tabItems[index].status = 'block';
    currentStep.value = index+1;
}

function showFormData(data) {
    resetTab();
    form.reset();
    form.clearErrors()
    if (data) {
        getPrimerContacto(data.id);
    }

    modalEntrevista.value.show();
}


function showEscaneo(data) {
    selectedFile.value = null
    selectedFileName.value = null
    selectedGeneralId.value = data.id
    modalEscaneo.value.show();
    axios.get('/primer-contacto/check-url/'+selectedGeneralId.value)
    .then(res => {
        if (res.data.url !== null) {
            fileUploaded.value = true;
            fileUploadedUrl.value = res.data.url;
        } else {
            fileUploaded.value = false;
        }
    })
}

function showData(data) {
    form.reset();
    getPrimerContacto(data.id);
    fileUploaded.value = false;

    axios.get('/primer-contacto/check-url/'+data.id)
    .then(res => {
        if (res.data.url !== null) {
            fileUploaded.value = true;
            fileUploadedUrl.value = res.data.url;
        }
    })

    modalShowData.value.show();
}

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

function checkPermisos(data) {
    return data.includes(user_area.value);
}

function getPrimerContacto(id) {
    axios.get('/primer-contacto/'+id)
        .then(res => {

            let data = res.data.data;
            // paso 1
            form.tipo_victimas_id = data.tipo_victimas_id;
            form.generales_id = data.generales_id;
            form.modalidad_atencion = data.modalidad_atencion;
            form.colaboracion = data.colaboracion;
            form.institucion = data.institucion;
            form.no_oficio = data.no_oficio;

            // paso 2
            form.solicitante_nombres = data.solicitantes_nombres;
            form.solicitante_primer_apellido = data.solicitantes_primer_apellido;
            form.solicitante_segundo_apellido =  data.solicitantes_segundo_apellido;
            form.fecha_nacimiento = data.solicitante_fecha_nacimiento;
            form.sexos_id = data.solicitantes_sexos_id;
            form.territorios_id = data.solicitantes_territorios_id;
            form.paises_id = data.solicitantes_paises_id;
            form.entidades_federativas_id = data.solicitantes_entidades_federativas_id;
            form.municipios_id = data.solicitantes_municipios_id;
            solicitanteMunicipio.value = data.solicitantes_entidades_federativas_id;
            form.poblacion = data.solicitantes_poblacion;

            // paso 3
            form.hechos_territorios_id = data.hechos_territorios_id;
            form.hechos_paises_id = data.hechos_paises_id;
            form.hechos_entidades_federativas_id = data.hechos_entidades_federativas_id;
            form.hechos_municipios_id = data.hechos_municipios_id;
            hechosMunicipio.value = data.hechos_entidades_federativas_id;
            form.hechos_poblacion = data.hechos_poblacion;
            form.hechos_relato = data.relato;

            // paso 4
            form.es_competencia_ceeaiv = data.es_competencia_ceeaiv;
            form.orientacion_brindada = data.orientacion_brindada;

            // paso 5
            form.victima_nombres = data.victimas_nombres;
            form.victima_primer_apellido = data.victimas_primer_apellido;
            form.victima_segundo_apellido = data.victimas_segundo_apellido;
            form.victima_fecha_nacimiento = data.victimas_fecha_nacimiento;
            form.victima_ocupacion_id = data.victimas_ocupaciones_id;
            form.delitos_id = data.delitos_id;
            form.violaciones_id = data.violaciones_id;
            form.no_carpeta = data.no_carpeta;
            form.fiscalia = data.fiscalia;

            // paso 6
            form.familiar_observaciones = data.familiar_observaciones;

            // paso 7
            form.notificaciones_territorios_id = data.notificaciones_territorios_id;
            form.notificaciones_paises_id = data.notificaciones_paises_id;
            form.notificaciones_entidades_federativas_id = data.notificaciones_entidades_federativas_id;
            form.notificaciones_municipios_id = data.notificaciones_municipios_id;
            notificacionesMunicipio.value = data.notificaciones_entidades_federativas_id;
            form.calle = data.calle;
            form.no_exterior = data.no_exterior;
            form.no_interior = data.no_interior;
            form.colonia = data.colonia;
            form.localidad = data.localidad;
            form.codigo_postal = data.codigo_postal;
            form.estado_civil = data.estado_civil;
            form.escolaridad = data.escolaridad;
            form.ocupacion = data.notificaciones_ocupaciones_id;
            form.organizacion_pertenece = data.organizacion_pertenece;
            form.cual_organizacion_pertenece = data.cual_organizacion_pertenece;

            // paso 8
            form.actividad_ingreso = data.actividad_ingreso;
            form.cual_actividad_ingreso = data.cual_actividad_ingreso;
            form.programa_social = data.programa_social;
            form.cual_programa_social = data.cual_programa_social;
            form.apoyo_federacion_estado = data.apoyo_federacion_estado;
            form.cual_apoyo_federacion_estado = data.cual_apoyo_federacion_estado;

            // paso 9
            form.afectacion_salud = data.afectacion_salud;
            form.cual_afectacion_salud = data.cual_afectacion_salud;
            form.servicio_atencion_medica = data.servicio_atencion_medica;
            form.cual_servicio_atencion_medica = data.cual_servicio_atencion_medica;

            // paso 10
            form.es_adolescente = data.es_adolescente;
            form.es_adulto_mayor = data.es_adulto_mayor;
            form.es_lgbttti = data.es_lgbttti;
            form.es_discapacitado = data.es_discapacitado;
            form.es_situacion_calle = data.es_situacion_calle;
            form.es_migrante = data.es_migrante;
            form.es_desplazado_por_hecho = data.es_desplazado_por_hecho;
            form.es_periodista = data.es_periodista;
            form.es_defensor_ddhh = data.es_defensor_ddhh;
            form.es_violencia_genero = data.es_violencia_genero;
            form.es_trastorno_mental = data.es_trastorno_mental;
            form.es_vih = data.es_vih;
            form.vulnerabilidades_observaciones = data.observaciones;

            // paso 11
            form.gestion_medica = data.gestion_medica;
            form.gestion_medica_necesidades = data.gestion_medica_necesidades;
            form.gestion_medica_derivacion = data.gestion_medica_derivacion;
            form.gestion_social = data.gestion_social;
            form.gestion_social_necesidades = data.gestion_social_necesidades;
            form.gestion_social_derivacion = data.gestion_social_derivacion;
            form.gestion_social_num_seguro_social = data.gestion_social_num_seguro_social;
            form.atencion_psicologica = data.atencion_psicologica;
            form.atencion_psicologica_necesidades = data.atencion_psicologica_necesidades;
            form.atencion_psicologica_derivacion = data.atencion_psicologica_derivacion;
            form.atencion_psicologica_es_interna = data.atencion_psicologica_es_interna;
            form.atencion_psicologica_psicologo_id = data.atencion_psicologica_psicologo_id;
            form.asesoria_juridica = data.asesoria_juridica;
            form.asesoria_juridica_necesidades = data.asesoria_juridica_necesidades;
            form.asesoria_juridica_derivacion = data.asesoria_juridica_derivacion;
            form.asesoria_juridica_opciones = data.asesoria_juridica_opciones ? JSON.parse(data.asesoria_juridica_opciones) : [];
            form.asesoria_juridica_opciones_otros = data.asesoria_juridica_opciones_otros;
            form.asesoria_juridica_asesor_id = data.asesoria_juridica_asesor_id;
            form.solicitudes_ayuda = data.solicitudes_ayuda;
            form.solicitudes_ayuda_opciones = data.solicitudes_ayuda_opciones ? JSON.parse(data.solicitudes_ayuda_opciones) : [];

            // paso 12
            form.registro_rev = data.registro_rev;
            form.ejerce_custodia_rev = data.ejerce_custodia_rev;
            form.quien_ejerce_custodia_rev = data.quien_ejerce_custodia_rev;
            form.observaciones_rev = data.observaciones_rev;
            form.como_se_entero_ceeaiv = data.como_se_entero_ceeaiv;

            checkStepRequired();
        });

    axios.get('/primer-contacto/telefonos/'+id)
        .then(res => {
            let data = res.data.telefonos;
            form.telefonos = [];
            data.forEach((item) => form.telefonos.push(item.telefono));
            if (!form.telefonos.length) {
                form.telefonos.push('');
            }

            checkStepRequired();
        });
    axios.get('/primer-contacto/familiares/'+id)
        .then(res => {
            let data = res.data.familiares;
            form.familiares= [];
            data.forEach((item) => form.familiares.push(item));

            checkStepRequired();
        });
}

function checkStepRequired() {
    let status8, status9, status10 = false;
    saveStatus.value = false;

    tabItems[0].validation = 'pending';
    if (!empty(form.modalidad_atencion)) {
        tabItems[0].validation = 'complete';
    }

    tabItems[1].validation = 'pending';
    if (!empty(form.solicitante_nombres) && !empty(form.solicitante_primer_apellido) && !empty(form.solicitante_segundo_apellido) && !empty(form.fecha_nacimiento)) {
        tabItems[1].validation = 'complete';
    }

    tabItems[2].validation = 'pending';
    if (!empty(form.hechos_relato)) {
        tabItems[2].validation = 'complete';
    }

    tabItems[3].validation = 'pending';
    if (!empty(form.es_competencia_ceeaiv)) {
        tabItems[3].validation = 'complete';
    }

    tabItems[4].validation = 'pending';
    if (!empty(form.victima_nombres) && !empty(form.victima_primer_apellido) && !empty(form.victima_segundo_apellido) && !empty(form.delitos_id) && !empty(form.violaciones_id)) {
        tabItems[4].validation = 'complete';
    }

    tabItems[5].validation = 'pending';
    if (form.familiares.length) {
        tabItems[5].validation = 'complete';
    }

    tabItems[6].validation = 'pending';
    if (!empty(form.calle) || !empty(form.no_exterior) || !empty(form.no_interior) || !empty(form.colonia) || !empty(form.localidad) || !empty(form.codigo_postal)) {
        tabItems[6].validation = 'complete';
    }

    // paso 8
    tabItems[7].validation = 'required';
    if (form.actividad_ingreso != null &&
        form.programa_social != null &&
        form.apoyo_federacion_estado != null
    ) {
        tabItems[7].validation = 'complete';
        status8 = true;
    }

    // paso 9
    tabItems[8].validation = 'required';
    if (form.afectacion_salud != null &&
        form.servicio_atencion_medica != null
    ) {
        tabItems[8].validation = 'complete';
        status9 = true;
    }

    // paso 10
    tabItems[9].validation = 'required';
    if (form.es_adolescente != null &&
        form.es_adulto_mayor != null &&
        form.es_lgbttti != null &&
        form.es_discapacitado != null &&
        form.es_situacion_calle != null &&
        form.es_migrante != null &&
        form.es_desplazado_por_hecho != null &&
        form.es_periodista != null &&
        form.es_defensor_ddhh != null &&
        form.es_violencia_genero != null &&
        form.es_trastorno_mental != null &&
        form.es_vih != null
    ) {
        tabItems[9].validation = 'complete';
        status10 = true;
    }

    tabItems[10].validation = 'pending';
    if (!empty(form.gestion_medica) && !empty(form.gestion_social) && !empty(form.atencion_psicologica) && !empty(form.asesoria_juridica) && !empty(form.solicitudes_ayuda)) {
        tabItems[10].validation = 'complete';
    }

    tabItems[11].validation = 'pending';
    if (!empty(form.registro_rev)) {
        tabItems[11].validation = 'complete';
    }

    if (status8 && status9 && status10) {
        saveStatus.value = true;
    }
}

function setFamiliar() {
    form.familiares.push(cloneDeep(formFamiliar));
    formFamiliar.reset();
}

function getParentesco(id) {
    let data = props.parentescos.filter((item) => item.parentescos_id == id);

    if (data.length) {
        return data[0].nombre;
    }

    return '-';
}

function getJuridico(id) {
    let data = props.asesorJuridico.filter((item) => item.id == id);

    if (data.length) {
        return data[0].name;
    }

    return '-';
}

function getPsicologo(id) {
    let data = props.psicologoCargo.filter((item) => item.id == id);

    if (data.length) {
        return data[0].name;
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

function getOcupacion(id) {
    let data = props.ocupaciones.filter((item) => item.ocupaciones_id == id);

    if (data.length) {
        return data[0].nombre_ocupacion;
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

function removeFamiliar(index) {
    form.familiares.splice(index, 1);
}

function isTrue(data) {
    return parseInt(data) ? true : false;
}

let changeTab = (index) => {
    if (index == 12) {
        form.numeroPaso = index+1;
        //currentStep.value = 1;
    } else {
        form.numeroPaso = currentStep.value;
        currentStep.value = index+1;
    }

    if (currentStep.value == 5 && form.tipo_victimas_id == 1)
    {
        form.victima_fecha_nacimiento = form.fecha_nacimiento;
    }

    //HoldOn.open({ message: "Procesando datos, por favor espere." });

    form.patch("/primer-contacto/" + form.generales_id, {
        onError: (errors) => {
            if (form.numeroPaso == 13) {
                form.numeroPaso = currentStep.value;
            } else {
                currentStep.value = form.numeroPaso;
            }

            checkStepRequired();
            
            customErrors.showErrors(form);
            //HoldOn.close();
        },
        onSuccess: (data) => {
            notify({
                title: "¡Éxito!",
                text: "Datos guardados correctamente.",
                type: "success"
            });
            tabsPC.value.activeTab(currentStep.value-1);
            checkStepRequired();
            if (form.numeroPaso == 13) {
                currentStep.value = 1;
                modalEntrevista.value.hide();
                form.reset();
                form.clearErrors();
            }

            //HoldOn.close();
        },
        });
};

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

    router.get(
        '/primer-contacto',
        objFilter,
        {
          preserveState: true,
          replace: true,
        }
      );
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

function setWatchers() {
    watch(
        search,
        debounce(function (value) {
            console.log(1);
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
        solicitanteMunicipio,
        (newVal) => {
            form.entidades_federativas_id = newVal;
            solicitanteMunicipios.value = props.municipios.filter((item) => item.entidades_federativas_id == newVal);
            if (!solicitanteMunicipios.value.some((item) => item.municipios_id == form.municipios_id)) {
                form.municipios_id = null;
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

    watch(
        notificacionesMunicipio,
        (newVal) => {
            form.notificaciones_entidades_federativas_id = newVal;
            notificacionesMunicipios.value = props.municipios.filter((item) => item.entidades_federativas_id == newVal);
            if (!notificacionesMunicipios.value.some((item) => item.municipios_id == form.notificaciones_municipios_id)) {
                form.notificaciones_municipios_id = null;
            }
        }
    );

    watch(() => modalEscaneo.value, (newValue) => {
    });

}
</script>

