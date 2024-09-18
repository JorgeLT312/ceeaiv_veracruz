<template>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <p class="font-bold text-gray-dark text-xl dark:text-white">
                Reporte de Víctimas
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
                    <vue-date-picker v-model="startDate" model-type="yyyy-MM-dd" :format="'dd/MM/yyyy'" :enable-time-picker="false" locale="es" cancelText="Cancelar" selectText="Aceptar"></vue-date-picker>
                </div>
                <div class="flex items-center pb-2 md:py-0">
                    <label class="mx-2 dark:text-white">Fin</label>
                    <vue-date-picker v-model="endDate" model-type="yyyy-MM-dd" :format="'dd/MM/yyyy'" :enable-time-picker="false" locale="es" cancelText="Cancelar" selectText="Aceptar"></vue-date-picker>
                </div>
            </div>
            <div class="flex items-center ml-2 min-w-[220px]">
                <Multiselect
                    v-model="estatusAtencion"
                    placeholder="Estatus de Atención"
                    mode="tags"
                    :close-on-select="false"
                    :options="estatusAtencionList"
                />
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
                            <th>Estatus atención</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-normal dark:text-white">
                        <tr class="dark:bg-gray-row" v-for="(captura, index) in capturas.data">
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
                                <div class="flex">
                                    <a href="#" v-if="checkPermisos()" class="flex items-center mx-1 p-1 rounded-full w-8 h-8 bg-gray-light hover:bg-amber-300" title="Ver" @click.prevent="showData(captura)">
                                            <img src="/images/icons/eye.png" class="object-contain" >
                                        </a>
                                    <a href="#" v-if="checkPermisos()" class="flex items-center mx-1 p-1 rounded-full w-8 h-8 bg-gray-light hover:bg-amber-300" title="Descargar PDF" @click.prevent="captura.loading != true ? printerPDF(captura) : ''">
                                        <template v-if="captura.loading == true">
                                            <div  class="border-gray-400 h-6 w-6 animate-spin rounded-full border-4 border-t-blue-normal" />
                                        </template>
                                        <template v-else>
                                            <img src="/images/icons/pdf.png" class="object-contain w-5 h-5 mx-auto">
                                        </template>
                                        </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="overflow-auto mb-8">
                <Pagination class="" :links="capturas.links"></Pagination>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3">
                <div class="lg:col-span-3 flex justify-center">
                    <span>Total de víctimas directas: {{ totalVictimasDirectas }}</span>
                    <span class="ml-2">Total de víctimas indirectas: {{ totalVictimasIndirectas }}</span>
                </div>
                <div class="max-h-[250px] text-center">
                    <Doughnut
                        class="mx-auto"
                        ref="doughnutReport"
                        id="doughnutReport"
                        :data="chartDataComputed"
                        :options="chartOptions"
                    />
                </div>
                <div>
                    <div class="rounded-xl bg-gray-light dark:bg-gray-table p-4">
                        <div class="overflow-auto">
                            <table class="table-auto w-full custom-table" cellspacing="0">
                                <thead>
                                    <tr class="text-gray-dark dark:text-white text-left">
                                        <th class="whitespace-nowrap">Estatus de Atención</th>
                                        <th>Total de Víctimas</th>
                                        <th>Adultos</th>
                                        <th>NNA</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-normal dark:text-white">
                                    <tr class="dark:bg-gray-row">
                                        <td>
                                            <div class="flex">
                                                <div class="bg-[#F7A523] rounded-full w-4 h-4 mr-2"></div> Captura Inicial
                                            </div>
                                        </td>
                                        <td>{{ totalVictimasCapturaInicial }}</td>
                                        <td>{{ adultosCI }}</td>
                                        <td>{{ NNACI }}</td>
                                    </tr>
                                    <tr class="dark:bg-gray-row">
                                        <td>
                                            <div class="flex">
                                                <div class="bg-[#3AB4B0] rounded-full w-4 h-4 mr-2"></div> Primer Contacto
                                            </div>
                                        </td>
                                        <td>{{ totalVictimasPrimerContacto }}</td>
                                        <td>{{ adultosPC }}</td>
                                        <td>{{ NNAPC }}</td>
                                    </tr>
                                    <tr class="dark:bg-gray-row">
                                        <td>
                                            <div class="flex">
                                                <div class="bg-[#9F49A2] rounded-full w-4 h-4 mr-2"></div> REV
                                            </div>
                                        </td>
                                        <td>{{ totalVictimasREV }}</td>
                                        <td>{{ adultosREV }}</td>
                                        <td>{{ NNAREV }}</td>
                                    </tr>
                                    <tr class="dark:bg-gray-row">
                                        <td>
                                            <div class="flex">
                                                <div class="bg-[#F52F69] rounded-full w-4 h-4 mr-2"></div> RENAVI
                                            </div>
                                        </td>
                                        <td>{{ totalVictimasRENAVI }}</td>
                                        <td>{{ adultosRENAVI }}</td>
                                        <td>{{ NNARENAVI }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="rounded-xl bg-gray-light dark:bg-gray-table p-4">
                        <div class="overflow-y-auto max-h-96">
                            <table class="table-auto w-full custom-table" cellspacing="0">
                                <thead>
                                    <tr class="text-gray-dark dark:text-white text-left">
                                        <th class="sticky top-0 z-10 bg-gray-light dark:bg-gray-table whitespace-nowrap">Tipo de delito</th>
                                        <th class="sticky top-0 z-10 bg-gray-light dark:bg-gray-table">Total de Víctimas</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-normal dark:text-white">
                                    <tr class="dark:bg-gray-row" v-for="delito in delitos_counts">
                                        <td>
                                            <p class="">{{ delito.nombre }}</p>
                                        </td>
                                        <td>{{ delito.count }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


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
</template>

<script setup>
import { ref, watch, onMounted, computed } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import Pagination from "../Components/Pagination.vue";
import debounce from "lodash/debounce";
import cloneDeep from "lodash/cloneDeep";
import Swal from "sweetalert2";
import moment from 'moment';
import axios from "axios";
import { Doughnut } from 'vue-chartjs'
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js'

ChartJS.register(ArcElement, Tooltip, Legend);

const modalShowData = ref(null);
const search = ref(props.filters.search);
const startDate = ref(moment().subtract(1, 'month').format('YYYY-MM-DD'));
const endDate = ref(moment().format('YYYY-MM-DD'));
const estatusAtencion = ref(null);
const doughnutReport = ref(null);
let  loadings = [];
const user_role = computed(() => usePage().props.auth.user.role_id);

let props = defineProps({
    capturas: Object,
    filters: Object,
    totalVictimasDirectas: Number,
    totalVictimasIndirectas: Number,
    totalVictimasCapturaInicial: Number,
    adultosCI: Number,
    NNACI: Number,
    totalVictimasPrimerContacto: Number,
    adultosPC: Number,
    NNAPC: Number,
    totalVictimasREV: Number,
    adultosREV: Number,
    NNAREV: Number,
    totalVictimasRENAVI: Number,
    adultosRENAVI: Number,
    NNARENAVI: Number,
    delitos_counts: Object,
});

let chartData = ref({
    labels: [ 'Captura Inicial', 'Primer Contacto', 'REV', 'RENAVI' ],
    datasets: [
        {
            backgroundColor: ['#F7A523', '#3AB4B0', '#9F49A2', '#F52F69'],
            data: [props.totalVictimasCapturaInicial, props.totalVictimasPrimerContacto, props.totalVictimasREV, props.totalVictimasRENAVI]
        }
    ]

})

const chartDataComputed = computed(() => chartData.value);

});


let chartOptions = {
    responsive: true,
    plugins: {
      legend: {
         display: false
      }
   }
}

let estatusAtencionList = ['Captura Inicial', 'Primer Contacto', 'REV', 'No Aprobado', 'RENAVI'];

let form = useForm({
    id: null,
    nombres: '',
    primer_apellido: '',
    segundo_apellido: '',
    tipo_victimas_id: null,
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

onMounted(() => {
    let searchParams = new URLSearchParams(window.location.search);

    if (searchParams.has('startDate')) {
        startDate.value = searchParams.get('startDate');
    }

    if (searchParams.has('endDate')) {
        endDate.value = searchParams.get('endDate');
    }

    setWatchers();
})

function checkPermisos() {
    if (user_role.value == 3 || user_role.value == 4 || user_role.value == 1) {
        return true;
    }

    return false;
}

function printerPDF(data) {
    let form = {};
    form.generales_id = data.id;

    if (form.generales_id != null) {
        //HoldOn.open({ message: "Procesando datos, por favor espere." });
        data.loading = true;
        axios.get(`/reportes/imprimir-reporte/${form.generales_id}`, {
            responseType: 'blob'
        })
        .then(response => {
            data.loading = false;

            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            //HoldOn.close();
            link.setAttribute('download', "reporte.pdf");
            document.body.appendChild(link);
            link.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(link);
        })
        .catch(error => {
            data.loading = false;
            console.error('Error al descargar el PDF:', error);
        });
    } else {
        console.error('ID de generales es nulo o indefinido.');
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

    if (estatusAtencion.value) {
        objFilter.estatus_atencion = estatusAtencion.value;
    }


    router.get(
        '/reportes',
        objFilter,
        {
          preserveState: true,
          replace: true,
        }
      );
}

function showData(data) {
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

function setWatchers() {

    watch(
        search,
        debounce(function (value) {
            filterIndex();
      }, 500)
    );

    watch(
        [startDate, endDate, estatusAtencion],
        (newVal) => {
            filterIndex();
        }
    );


    watch(
        [props],
        (newVal) => {
            chartData.value = {
                labels: [ 'Captura Inicial', 'Primer Contacto', 'REV', 'RENAVI' ],
                datasets: [
                    {
                        backgroundColor: ['#F7A523', '#3AB4B0', '#9F49A2', '#F52F69'],
                        data: [props.totalVictimasCapturaInicial, props.totalVictimasPrimerContacto, props.totalVictimasREV, props.totalVictimasRENAVI]
                    }
                ]
            };
        }
    );
}

watch([() => props.totalVictimasCapturaInicial, () => props.totalVictimasPrimerContacto, () => props.totalVictimasREV, () => props.totalVictimasRENAVI], (newVal) => {
    chartData.value = {
        labels: [ 'Captura Inicial', 'Primer Contacto', 'REV', 'RENAVI' ],
        datasets: [
            {
                backgroundColor: ['#F7A523', '#3AB4B0', '#9F49A2', '#F52F69'],
                data: [props.totalVictimasCapturaInicial, props.totalVictimasPrimerContacto, props.totalVictimasREV, props.totalVictimasRENAVI]
            }
        ]
    }
})

</script>
