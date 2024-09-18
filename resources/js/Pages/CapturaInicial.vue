<template>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <p class="font-bold text-gray-dark text-xl dark:text-white">Captura Inicial</p>
        </div>
        <div class="rounded-[16px] md:rounded-full bg-gray-light dark:bg-gray-table px-4 py-2 md:flex items-center justify-between text-black" >
            <div class="relative md:w-1/2">
                <input type="text" v-model="search" id="search-input" class="rounded-full block w-full ps-2 p-2" placeholder="Buscar">
                <button type="button" class="absolute inset-y-0 end-0 flex items-center pe-3">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                        <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/>
                    </svg>
                </button>
            </div>

            <button id="registrarVictimaBtn"
             class="btn-normal"
             @click="showFormData(null)"
             >
             Registrar Víctima
            </button>
        </div>
        <div class="rounded-xl bg-gray-light dark:bg-gray-table p-4">
            <div class="overflow-auto">
                <table class="table-auto w-full custom-table" cellspacing="0">
                    <thead>
                      <tr class="text-gray-dark dark:text-white text-left">
                        <th>No. de REV</th>
                        <th>Nombre Completo</th>
                        <th class="whitespace-nowrap">Tipo de Víctima</th>
                        <th>Contacto Principal</th>
                        <th class="whitespace-nowrap">Estatus atención</th>
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
                            <td>
                                {{ [((empty(captura.contacto_principal_nombres) && empty(captura.contacto_principal_primer_apellido) && empty(captura.contacto_principal_segundo_apellido)) ? '' : captura.contacto_principal_nombres+' '+captura.contacto_principal_primer_apellido+' '+captura.contacto_principal_segundo_apellido), captura.contacto_principal_telefono, captura.contacto_principal_correo].filter(n => n).join(', ') }}
                            </td>
                            <td>{{ captura.estatus_atencion }}</td>
                            <td>
                                <div class="flex">
                                    <template v-if="captura.estatus_atencion == 'Captura Inicial'">
                                        <Link v-if="checkPermisos()" :href="'/primer-contacto?data='+captura.id+'&modal=edit'"  class="flex items-center mx-1 p-1 rounded-full w-8 h-8 bg-gray-light hover:bg-amber-300" title="Agregar Entrevista Primer Contacto">
                                            <img src="/images/icons/person-red.png" class="object-contain">
                                        </Link>
                                    </template>
                                    <template v-else>
                                        <Link v-if="checkPermisos()" :href="'/primer-contacto?data='+captura.id+'&modal=show'" class="flex items-center mx-1 p-1 rounded-full w-8 h-8 bg-gray-light hover:bg-amber-300" title="Ver Entrevista Primer Contacto">
                                            <img src="/images/icons/person.png" class="object-contain" >
                                        </Link>
                                    </template>
                                    <a href="#" class="flex items-center mx-1 p-1 rounded-full w-8 h-8 bg-gray-light hover:bg-amber-300" title="Ver" @click.prevent="showData(captura)">
                                        <img src="/images/icons/eye.png" class="object-contain" >
                                    </a>
                                    <a href="#" class="flex items-center mx-1 p-2 rounded-full w-8 h-8 bg-gray-light hover:bg-amber-300" title="Editar"  @click.prevent="showFormData(captura)">
                                        <img src="/images/icons/edit.png" class="object-contain">
                                    </a>
                                    <a href="#" class="flex items-center mx-1 p-2 rounded-full w-8 h-8 bg-gray-light hover:bg-amber-300" title="Eliminar" @click.prevent="showDeleteData(captura)">
                                        <img src="/images/icons/delete.png" class="object-contain" >
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                  </table>
            </div>
        </div>

        <div class="overflow-auto">
            <Pagination :links="capturas.links"></Pagination>
        </div>

        <Modal ref="modalData">
            <template #modal-header>
                <p>{{ form.id ? 'Editar' : 'Nuevo' }} Registro de Víctima</p>
            </template>
            <template #modal-body>
                <Tabs :tabs="tabItems" @on-change-tab="changeTab">
                    <template #tab-content-0>
                        <div class="w-full max-w-md">
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Nombre(s)*:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="nombres" type="text" v-model="form.nombres"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.nombres" v-text="form.errors.nombres" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Primer Apellido*:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="PrimerApellido" type="text" v-model="form.primer_apellido"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.primer_apellido" v-text="form.errors.primer_apellido" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Segundo Apellido*:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="segundoApellido" type="text" v-model="form.segundo_apellido"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.segundo_apellido" v-text="form.errors.segundo_apellido" class="text-red-500"></div>
                                </div>
                            </div>

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
                                    <div v-if="form.errors.tipo_victimas_id" v-text="form.errors.tipo_victimas_id" class="text-red-500"></div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template #tab-content-1>
                        <div class="w-full max-w-md">
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Nombre(s):
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="contacto_principal_nombres" type="text" v-model="form.contacto_principal_nombres"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.contacto_principal_nombres" v-text="form.errors.contacto_principal_nombres" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Primer Apellido:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="contacto_principal_primer_apellido" type="text" v-model="form.contacto_principal_primer_apellido"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.contacto_principal_primer_apellido" v-text="form.errors.contacto_principal_primer_apellido" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Segundo Apellido:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="contacto_principal_segundo_apellido" type="text" v-model="form.contacto_principal_segundo_apellido"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.contacto_principal_segundo_apellido" v-text="form.errors.contacto_principal_segundo_apellido" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Correo electrónico:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="contacto_principal_correo" type="text" v-model="form.contacto_principal_correo"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.contacto_principal_correo" v-text="form.errors.contacto_principal_correo" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Teléfono:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="contacto_principal_telefono" type="text" v-model="form.contacto_principal_telefono"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.contacto_principal_telefono" v-text="form.errors.contacto_principal_telefono" class="text-red-500"></div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template #tab-content-2>
                        <div class="w-full max-w-md">
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Nombre(s):
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="contacto_secundario_nombres" type="text" v-model="form.contacto_secundario_nombres"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.contacto_secundario_nombres" v-text="form.errors.contacto_secundario_nombres" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Primer Apellido:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="contacto_secundario_primer_apellido" type="text" v-model="form.contacto_secundario_primer_apellido"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.contacto_secundario_primer_apellido" v-text="form.errors.contacto_secundario_primer_apellido" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Segundo Apellido:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="contacto_secundario_segundo_apellido" type="text" v-model="form.contacto_secundario_segundo_apellido"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.contacto_secundario_segundo_apellido" v-text="form.errors.contacto_secundario_segundo_apellido" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Correo electrónico:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="contacto_secundario_correo" type="text" v-model="form.contacto_secundario_correo"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.contacto_secundario_correo" v-text="form.errors.contacto_secundario_correo" class="text-red-500"></div>
                                </div>
                            </div>
                            <div class="md:flex md:item-center mb-2">
                                <div class="md:w-1/3 grid content-center">
                                    <label class="block mb-1 md:mb-0 pr-4">
                                        Teléfono:
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input  id="contacto_secundario_telefono" type="text" v-model="form.contacto_secundario_telefono"
                                        class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal">
                                    <div v-if="form.errors.contacto_secundario_telefono" v-text="form.errors.contacto_secundario_telefono" class="text-red-500"></div>
                                </div>
                            </div>
                        </div>
                    </template>
                </Tabs>
            </template>
            <template #modal-footer>
                <button id="guardar_captura_inicial" type="button" @click="saveData"  class="btn-normal w-full sm:w-auto">Guardar</button>
            </template>
        </Modal>

        <Modal ref="modalShowData">
            <template #modal-header>
                <p>Datos Víctima</p>
            </template>
            <template #modal-body>
                <div class="w-full max-w-md">
                    <div class="md:flex md:item-center mb-2">
                        <div class="md:w-1/3 grid content-center">
                            <label class="block mb-1 md:mb-0 pr-4" for="inline-full-name">
                                Nombre:
                            </label>
                          </div>
                          <div class="md:w-2/3">
                            <p class="font-bold">{{ form.nombres }} {{ form.primer_apellido }} {{ form.segundo_apellido }}</p>
                          </div>
                    </div>
                    <div class="md:flex md:item-center mb-2">
                        <div class="md:w-1/3 grid content-center">
                            <label class="block mb-1 md:mb-0 pr-4" for="inline-full-name">
                                Tipo de Víctima:
                            </label>
                          </div>
                          <div class="md:w-2/3">
                                <p class="font-bold">{{ form.tipo_victima }}</p>
                          </div>
                    </div>
                    <div class="md:flex md:item-center mb-2">
                        <div class="md:w-1/3 grid content-center">
                            <label class="block mb-1 md:mb-0 pr-4" for="inline-full-name">
                                Servicios Requeridos:
                            </label>
                          </div>
                          <div class="md:w-2/3">
                                <p class="font-bold" v-if="form.gestion_medica == 1">Atención Médica De Urgencia y Gestión Médica</p>
                                <p class="font-bold" v-if="form.gestion_social == 1">Trabajo Social y Gestión</p>
                                <p class="font-bold" v-if="form.atencion_psicologica == 1">Atención Psicológica</p>
                                <p class="font-bold" v-if="form.asesoria_juridica == 1">Asesoría Jurídica</p>
                                <p class="font-bold" v-if="form.solicitudes_ayuda == 1">Solicitudes de Medidas de Ayuda</p>
                          </div>
                    </div>
                    <div class="md:flex md:item-center mb-2">
                        <div class="md:w-1/3 grid content-center">
                            <label class="block mb-1 md:mb-0 pr-4" for="inline-full-name">
                                Fecha de Atención:
                            </label>
                          </div>
                          <div class="md:w-2/3">
                                <p class="font-bold"> {{ $filters.toDate(form.created_at) }}</p>
                          </div>
                    </div>
                    <br>
                    <label class="font-bold">
                        Contacto Principal:
                    </label>
                    <div class="md:flex md:item-center mb-2">
                        <div class="md:w-1/3 grid content-center">
                            <label class="block mb-1 md:mb-0 pr-4" for="inline-full-name">
                                Nombre:
                            </label>
                          </div>
                          <div class="md:w-2/3">
                                <p class="font-bold">{{form.contacto_principal_nombres}} {{form.contacto_principal_primer_apellido}} {{ form.contacto_principal_segundo_apellido }}</p>
                          </div>
                    </div>
                    <div class="md:flex md:item-center mb-2">
                        <div class="md:w-1/3 grid content-center">
                            <label class="block mb-1 md:mb-0 pr-4" for="inline-full-name">
                                Correo electrónico:
                            </label>
                          </div>
                          <div class="md:w-2/3">
                                <p class="font-bold">{{ form.contacto_principal_correo }}</p>
                          </div>
                    </div>
                    <div class="md:flex md:item-center mb-2">
                        <div class="md:w-1/3 grid content-center">
                            <label class="block mb-1 md:mb-0 pr-4" for="inline-full-name">
                                Teléfono:
                            </label>
                          </div>
                          <div class="md:w-2/3">
                                <p class="font-bold">{{ form.contacto_principal_telefono }}</p>
                          </div>
                    </div>
                    <br>
                    <label class="font-bold">
                        Contacto Secundario:
                    </label>
                    <div class="md:flex md:item-center mb-2">
                        <div class="md:w-1/3 grid content-center">
                            <label class="block mb-1 md:mb-0 pr-4" for="inline-full-name">
                                Nombre:
                            </label>
                          </div>
                          <div class="md:w-2/3">
                                <p class="font-bold">{{form.contacto_secundario_nombres}} {{form.contacto_secundario_primer_apellido}} {{ form.contacto_secundario_segundo_apellido }}</p>
                          </div>
                    </div>
                    <div class="md:flex md:item-center mb-2">
                        <div class="md:w-1/3 grid content-center">
                            <label class="block mb-1 md:mb-0 pr-4" for="inline-full-name">
                                Correo electrónico:
                            </label>
                          </div>
                          <div class="md:w-2/3">
                                <p class="font-bold">{{ form.contacto_secundario_correo }}</p>
                          </div>
                    </div>
                    <div class="md:flex md:item-center mb-2">
                        <div class="md:w-1/3 grid content-center">
                            <label class="block mb-1 md:mb-0 pr-4" for="inline-full-name">
                                Teléfono:
                            </label>
                          </div>
                          <div class="md:w-2/3">
                                <p class="font-bold">{{ form.contacto_secundario_telefono }}</p>
                          </div>
                    </div>
                </div>
            </template>
            <template #modal-footer>

            </template>
        </Modal>

        <Modal ref="modalDeleteData">
            <template #modal-header>
                <p>Eliminar Víctima</p>
            </template>
            <template #modal-body>
                <div class="w-full max-w-md">
                    <div class="md:flex md:item-center mb-2">
                      <div class="md:w-full">
                        <label class="block mb-1 md:mb-0" for="inline-full-name">
                            Seleccionaste la víctima {{ form.nombres }}. ¿Confirmas que deseas borrar dicha Víctima?
                        </label>
                      </div>
                    </div>
                  </div>
            </template>
            <template #modal-footer>
                <button type="button" class="btn-normal w-full sm:w-auto" @click="destroy">Aceptar</button>
            </template>
        </Modal>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { router, useForm, usePage } from '@inertiajs/vue3'
import Pagination from '../Components/Pagination.vue'
import debounce from 'lodash/debounce'
import { useNotification } from "@kyvg/vue3-notification";
import useErrors from '../Services/errors';

const customErrors = useErrors();
const user_role = ref(usePage().props.auth.user.role_id);
const user_area = ref(usePage().props.auth.user.area_id);
const search = ref(props.filters.search);

let props = defineProps({
    capturas: Object,
    filters: Object,
    tipoVictimas: Array,
});

const { notify }  = useNotification();
const modalData = ref(null);
const modalShowData = ref(null);
const modalDeleteData = ref(null);

const tabItems = [
    { id: 1, name: 'Víctima', status: 'block', validation: 'pending' },
    { id: 2, name: 'Contacto Principal', status: 'hidden', validation: 'pending' },
    { id: 3, name: 'Contacto Secundario', status: 'hidden', validation: 'pending' }
];

function resetTab(index = 0) {
    tabItems.every(function(item) {
        item.status = 'hidden';
        item.validation = 'pending';
        return true;
    });
    tabItems[index].status = 'block';
}

let form = useForm({
    id: null,
    nombres: '',
    primer_apellido: '',
    segundo_apellido: '',
    tipo_victimas_id: null,
    created_at: null,
    contacto_principal_nombres: '',
    contacto_principal_primer_apellido: '',
    contacto_principal_segundo_apellido: '',
    contacto_principal_correo: '',
    contacto_principal_telefono: '',
    contacto_secundario_nombres: '',
    contacto_secundario_primer_apellido: '',
    contacto_secundario_segundo_apellido: '',
    contacto_secundario_correo: '',
    contacto_secundario_telefono: '',

    gestion_medica: null,
    gestion_social: null,
    atencion_psicologica: null,
    asesoria_juridica: null,
    solicitudes_ayuda: null,
});

function checkPermisos() {
    if ((user_role.value == 3 || user_role.value == 4) && (user_area.value == 2 || user_area.value == 6)) {
        return true;
    }

    return false;
}

let saveData = () => {
  //HoldOn.open({ message: "Procesando datos, por favor espere." });
  if (form.id) {
    form.patch("/captura-inicial/" + form.id, {
      onError: (errors) => {
        validateFormStep();
        customErrors.showErrors(form);
        //HoldOn.close();
      },
      onSuccess: (data) => {
        notify({
            title: "¡Éxito!",
            text: "Datos guardados correctamente.",
            type: "success"
        });
        modalData.value.hide();
        form.reset();
        form.clearErrors()
        //HoldOn.close();
      },
    });
  } else {
    form.post("/captura-inicial", {
      onError: (errors) => {
        validateFormStep();
        customErrors.showErrors(form);
        //HoldOn.close();
      },
      onSuccess: (data) => {

        notify({
            title: "¡Éxito!",
            text: "Datos guardados correctamente.",
            type: "success"
        });
        modalData.value.hide();
        form.reset();
        form.clearErrors()
        //HoldOn.close();
      },
    });
}

}


let destroy = () => {
  //HoldOn.open({ message: "Procesando datos, por favor espere." });
  form.clearErrors();
    form.delete("captura-inicial/"+ form.id, {
      onError: () => {
        //HoldOn.close();
      },
      onSuccess: () => {
        notify({
            title: "¡Éxito!",
            text: "Dato eliminado correctamente.",
            type: "success"
        });

        modalDeleteData.value.hide();
        form.reset();
        form.clearErrors()
        //HoldOn.close();
      },
    });

};


function showFormData(data) {
    resetTab();
    form.reset();
    form.clearErrors()
    if (data) {
        if(data.tipo_victimas_id == 1) {
            form.nombres = data.nombres;
            form.primer_apellido = data.primer_apellido;
            form.segundo_apellido = data.segundo_apellido;
        } else {
            form.nombres = data.indirecta_nombres;
            form.primer_apellido = data.indirecta_primer_apellido;
            form.segundo_apellido = data.indirecta_segundo_apellido;
        }

        form.id = data.id;
        form.tipo_victimas_id = data.tipo_victimas_id;
        form.contacto_principal_nombres = data.contacto_principal_nombres;
        form.contacto_principal_primer_apellido = data.contacto_principal_primer_apellido;
        form.contacto_principal_segundo_apellido = data.contacto_principal_segundo_apellido;
        form.contacto_principal_correo = data.contacto_principal_correo;
        form.contacto_principal_telefono = data.contacto_principal_telefono;
        form.contacto_secundario_nombres = data.contacto_secundario_nombres;
        form.contacto_secundario_primer_apellido = data.contacto_secundario_primer_apellido;
        form.contacto_secundario_segundo_apellido = data.contacto_secundario_segundo_apellido;
        form.contacto_secundario_correo = data.contacto_secundario_correo;
        form.contacto_secundario_telefono = data.contacto_secundario_telefono;
    }
    validateFormStep();
    modalData.value.show();
}


function showData(data) {
    resetTab();
    form.reset();
    form.clearErrors()
    if (data) {
        if(data.tipo_victimas_id == 1) {
            form.nombres = data.nombres;
            form.primer_apellido = data.primer_apellido;
            form.segundo_apellido = data.segundo_apellido;
        } else {
            form.nombres = data.indirecta_nombres;
            form.primer_apellido = data.indirecta_primer_apellido;
            form.segundo_apellido = data.indirecta_segundo_apellido;
        }

        form.id = data.id;
        form.tipo_victima = data.nombre_tipo_victima;
        form.created_at = data.created_at;
        form.contacto_principal_nombres = data.contacto_principal_nombres;
        form.contacto_principal_primer_apellido = data.contacto_principal_primer_apellido;
        form.contacto_principal_segundo_apellido = data.contacto_principal_segundo_apellido;
        form.contacto_principal_correo = data.contacto_principal_correo;
        form.contacto_principal_telefono = data.contacto_principal_telefono;
        form.contacto_secundario_nombres = data.contacto_secundario_nombres;
        form.contacto_secundario_primer_apellido = data.contacto_secundario_primer_apellido;
        form.contacto_secundario_segundo_apellido = data.contacto_secundario_segundo_apellido;
        form.contacto_secundario_correo = data.contacto_secundario_correo;
        form.contacto_secundario_telefono = data.contacto_secundario_telefono;

        form.gestion_medica = data.gestion_medica;
        form.gestion_social = data.gestion_social;
        form.atencion_psicologica = data.atencion_psicologica;
        form.asesoria_juridica = data.asesoria_juridica;
        form.solicitudes_ayuda = data.solicitudes_ayuda;
    }
    modalShowData.value.show();
}

function showDeleteData(captura) {
    console.log(captura);
    form.id = captura.id;
    form.nombres = captura.nombres;
    if (form.nombres == null ) {
        form.nombres = captura.indirecta_nombres
    } else {
        form.nombres = captura.nombres;
    }
    modalDeleteData.value.show();
}

function changeTab(index) {
    //console.log('submit tab: '+index);
}

function validateFormStep() {
    // paso 1
    tabItems[0].validation = 'pending';
    if (!empty(form.nombres) &&
        !empty(form.primer_apellido) &&
        !empty(form.segundo_apellido) &&
        !empty(form.tipo_victimas_id)) {
        tabItems[0].validation = 'complete';
    }

    // paso 2
    tabItems[1].validation = 'pending';
    if (!empty(form.contacto_principal_nombres) &&
        !empty(form.contacto_principal_primer_apellido) &&
        !empty(form.contacto_principal_segundo_apellido) &&
        !empty(form.contacto_principal_correo) &&
        !empty(form.contacto_principal_telefono)) {
        tabItems[1].validation = 'complete';
    }

    // paso 3
    tabItems[2].validation = 'pending';
    if (!empty(form.contacto_secundario_nombres) &&
        !empty(form.contacto_secundario_primer_apellido) &&
        !empty(form.contacto_secundario_segundo_apellido) &&
        !empty(form.contacto_secundario_correo) &&
        !empty(form.contacto_secundario_telefono)) {
        tabItems[2].validation = 'complete';
    }
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

watch(
  search,
  debounce(function (value) {
    if (value) {
      router.get(
        '/captura-inicial',
        { search: value },
        {
          preserveState: true,
          replace: true,
        }
      );
    } else {
      router.get(
        '/captura-inicial',
        {},
        {
          preserveState: true,
          replace: true,
        }
      );
    }
  }, 500)
);


</script>
