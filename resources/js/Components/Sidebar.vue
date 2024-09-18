<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const emit = defineEmits(['toggle']);

const showSidebar = ref(false);
const self = ref(null);

function toggleSidebar() {
    showSidebar.value = !showSidebar.value;
    emit('toggle');
}

const user_role = computed(() => usePage().props.auth.user.role_id)
const user_area = computed(() => usePage().props.auth.user.area_id)

function checkArea(data) {
    return data.includes(user_area.value);
}

</script>
<style scoped>
.hidden-on .hidden-title {
    display: none;
}
</style>
<template>
    <aside class="hidden sm:block fixed top-0 left-0 h-[96vh] z-10" :class="showSidebar ? 'w-80' : 'w-28 hidden-on'">
        <div class="h-full overflow-y-auto bg-gray-light dark:bg-dark-fondo2 m-4 rounded-[16px]" :class="showSidebar ? 'p-4' : 'p-3'">
            <ul class="space-y-2 mt-28">
                <li>
                    <button @click="toggleSidebar" type="button" class="inline-flex items-center p-2 rounded-lg hover:bg-slate-300 focus:outline-none focus:ring-2 focus:ring-slate-300">
                        <img src="/images/icons/menu.png" class="w-5 dark:hidden">
                        <img src="/images/icons/menu-white.png" class="w-5 hidden dark:block">
                    </button>
                </li>
                <li v-if="user_role===1">
                    <Link href="/usuarios" class="flex items-center btn-link" :class="$page.component === 'Users' ? 'activeLink' : ''" title="Personas Usuarias">
                        <img src="/images/icons/users.png" class="w-5 h-5 dark:hidden">
                        <img src="/images/icons/users-white.png" class="w-5 h-5 hidden dark:block">
                        <span class="ms-3 hidden-title">Personas Usuarias</span>
                    </Link>
                </li>
                <li v-if="user_role===1 || user_role===2 || user_role===3 || user_role===4">
                    <Link href="/captura-inicial" class="flex items-center  btn-link" :class="$page.component === 'CapturaInicial' ? 'activeLink' : ''" title="Captura Inicial">
                        <img src="/images/icons/capture.png" class="w-5 h-5 dark:hidden">
                        <img src="/images/icons/capture-white.png" class="w-5 h-5 hidden dark:block">
                        <span class="ms-3 hidden-title">Captura Inicial</span>
                    </Link>
                </li>
                <li v-if="(user_role===3 || user_role===4) && checkArea([2, 3, 4, 6])">
                    <Link href="/primer-contacto" class="flex items-center btn-link" :class="$page.component === 'PrimerContacto' ? 'activeLink' : ''" title="Entrevista Primer Contacto">
                        <img src="/images/icons/contact.png" class="w-5 h-5 dark:hidden">
                        <img src="/images/icons/contact-white.png" class="w-5 h-5 hidden dark:block">
                        <span class="ms-3 hidden-title ">Entrevista Primer Contacto</span>
                    </Link>
                </li>
                <li v-if="(user_role===3 || user_role===4) && checkArea([3])">
                    <Link href="/rev" class="flex items-center btn-link" :class="$page.component === 'Rev' ? 'activeLink' : ''" title="Registro Estatal de Víctimas">
                        <img src="/images/icons/rev.png" class="w-5 h-5 dark:hidden">
                        <img src="/images/icons/rev-white.png" class="w-5 h-5 hidden dark:block">
                        <span class="ms-3 hidden-title">Registro Estatal de Víctimas</span>
                    </Link>
                </li>
                <li v-if="(user_role===3 || user_role===4) && checkArea([3])">
                    <Link href="/renavi" class="flex items-center btn-link" :class="$page.component === 'Renavi' ? 'activeLink' : ''" title="RENAVI / RENADET">
                        <img src="/images/icons/renavi.png" class="w-5 h-5 dark:hidden">
                        <img src="/images/icons/renavi-white.png" class="w-5 h-5 hidden dark:block">
                        <span class="ms-3 hidden-title">RENAVI / RENADET</span>
                    </Link>
                </li>
                <li v-if="user_role===1 || user_role===3 || user_role===4">
                    <Link href="/reportes" class="flex items-center btn-link" :class="$page.component === 'Reportes' ? 'activeLink' : ''" title="Reporte de Víctimas">
                        <img src="/images/icons/report.png" class="w-5 h-5 dark:hidden">
                        <img src="/images/icons/report-white.png" class="w-5 h-5 hidden dark:block">
                        <span class="ms-3 hidden-title">Reporte de Víctimas</span>
                    </Link>
                </li>
                <li>
                    <Link href="/videotutoriales" class="flex items-center btn-link" :class="$page.component === 'Videos' ? 'activeLink' : ''" title="Videos">
                        <svg class="fill-current" width="20px" height="20px" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg">
                          <path d="M242.00781,76.54a3.99817,3.99817,0,0,0-3.99219-.0127L188,105.10767V92a36.04061,36.04061,0,0,0-36-36H24A12.01312,12.01312,0,0,0,12,68v96a36.04061,36.04061,0,0,0,36,36H176a12.01312,12.01312,0,0,0,12-12V150.89233l50.01562,28.58033A3.99931,3.99931,0,0,0,244,176V80A4.00041,4.00041,0,0,0,242.00781,76.54ZM180,188a4.004,4.004,0,0,1-4,4H48a28.03146,28.03146,0,0,1-28-28V68a4.004,4.004,0,0,1,4-4H152a28.03146,28.03146,0,0,1,28,28Zm56-18.89258L188,141.6792V114.3208l48-27.42822Z"/>
                        </svg>
                        <span class="ms-3 hidden-title">Videotutoriales</span>
                    </Link>
                </li>
            </ul>
        </div>
    </aside>
</template>
