<template>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <p class="font-bold text-gray-dark text-xl dark:text-white">Notificaciones</p>
        </div>
        <div class="rounded-[16px] md:rounded-full bg-gray-light dark:bg-gray-table px-4 py-2 lg:flex items-center justify-between text-black" >
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
            </div>
        </div>
        <div class="rounded-xl bg-gray-light dark:bg-gray-table p-4">
            <div class="overflow-auto">
                <table class="table-auto w-full custom-table" cellspacing="0">
                    <thead>
                        <tr class="text-gray-dark dark:text-white text-left">
                            <th class="whitespace-nowrap">Fecha y hora</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-normal dark:text-white">
                        <tr class="dark:bg-gray-row" v-for="captura in notificaciones.data">
                            <td>{{ $filters.toFullDate(captura.created_at) }}</td>
                            <td>
                                {{ showNotifyDescription(captura) }}
                            </td>
                            <td>
                                <div class="flex">
                                    <Link :href="captura.tipo.replace(/\s+/g, '-').toLowerCase()" class="flex items-center mx-1 p-1 rounded-full w-8 h-8 bg-gray-light hover:bg-amber-300" title="Ver">
                                        <img src="/images/icons/eye.png" class="object-contain" >
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="overflow-auto">
        <Pagination :links="notificaciones.links"></Pagination>
    </div>

</template>
<script setup>
import { ref, watch, onMounted, computed } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import Pagination from "../Components/Pagination.vue";
import moment from 'moment';

const search = ref(props.filters.search);
const startDate = ref(moment().subtract(1, 'month').format('YYYY-MM-DD'));
const endDate = ref(moment().format('YYYY-MM-DD'));

let props = defineProps({
    notificaciones: Object,
    filters: Object,
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

function showNotifyDescription(data) {
    let name = '';

    if (data.tipo_victimas_id == 1) {
        name = data.nombres+' '+data.primer_apellido+' '+data.segundo_apellido;
    } else {
        name = data.indirecta_nombres+' '+data.indirecta_primer_apellido+' '+data.indirecta_segundo_apellido;
    }

    return data.descripcion.replace("{name}", name);
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

    router.get(
        '/notificaciones',
        objFilter,
        {
          preserveState: true,
          replace: true,
        }
      );
}

function setWatchers() {

    watch(
        [search, startDate, endDate],
        (newVal) => {
            filterIndex();
        }
    );

}

</script>
