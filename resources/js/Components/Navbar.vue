<script setup>
import { ref, watch, computed, onMounted } from 'vue'
import { useDark, useToggle } from '@vueuse/core';
import { usePage } from '@inertiajs/vue3'
import axios from "axios";

const isDark = useDark();
const toggleDark = useToggle(isDark);

const user_role = computed(() => usePage().props.auth.user.role_id)
const user_area = computed(() => usePage().props.auth.user.area_id)
const page_url = computed(() => usePage().url)
let notify_count = ref(0);
let isOpen = ref(false);

onMounted(() => {
    getNotifyCount();
})

function getNotifyCount() {
    axios.get('/obtenerNotificaciones')
        .then(response => {
            notify_count.value = response.data;
        });
}

function checkArea(data) {
    return data.includes(user_area.value);
}

watch(page_url, function (newUrl, oldUrl) {
    if (newUrl != '/login') {
        getNotifyCount();
    }
  }
);

</script>
<template>
    <header class="sm:flex sm:justify-between sm:items-center h-28 mb-8">
        <div class="flex items-center justify-between px-4 py-3 sm:p-0">
            <div class="flex items-center">
                <img class="h-20 dark:hidden" src="/images/Escudo.svg">
                <img class="h-14 hidden dark:block" src="/images/veracruz.png">
                <img class="h-20 dark:hidden" src="/images/CEEAIV.svg">
                <img class="h-20 hidden dark:block" src="/images/CEEAIV-2.svg">
            </div>
            <div class="sm:hidden">
                <button @click="isOpen = !isOpen" type="button" class="block text-gray-500 hover:text-white focus:text-white focus:outline-none">
                    <img src="/images/icons/menu.png" class="w-5 dark:hidden">
                    <img src="/images/icons/menu-white.png" class="w-5 hidden dark:block">
                </button>
            </div>
        </div>
        <nav :class="isOpen ? 'block' : 'hidden'" class="px-2 pt-2 pb-4 sm:flex sm:p-0 bg-slate-300 sm:bg-inherit dark:bg-dark-fondo2 sm:dark:bg-inherit relative z-10">
            <div class="p-2">
                <label for="darkToggle" class="flex items-center cursor-pointer select-none">
                Modo Oscuro
                    <div class="ms-2 relative rounded-full">
                        <input type="checkbox" v-model="isDark" id="darkToggle" class="peer sr-only" @click="toggleDark()"/>
                        <div class="block h-7 rounded-full bg-slate-100 w-12"></div>
                        <div class="absolute bg-white w-5 h-5 transition duration-150 rounded-full dot left-1 top-1 peer-checked:translate-x-full peer-checked:bg-blue-700"></div>
                    </div>
                </label>
            </div>
            <Link type="button" href="/notificaciones" class="relative inline-flex items-center p-2 w-full sm:w-auto" as="button" @click="isOpen = false">
                <span class="sm:hidden">Notificaciones &nbsp;</span>
                <svg class="w-5 h-5 fill-yellow-normal" xmlns="http://www.w3.org/2000/svg" height="18" width="16" viewBox="0 0 448 512">
                    <path d="M224 0c-17.7 0-32 14.3-32 32V51.2C119 66 64 130.6 64 208v18.8c0 47-17.3 92.4-48.5 127.6l-7.4 8.3c-8.4 9.4-10.4 22.9-5.3 34.4S19.4 416 32 416H416c12.6 0 24-7.4 29.2-18.9s3.1-25-5.3-34.4l-7.4-8.3C401.3 319.2 384 273.9 384 226.8V208c0-77.4-55-142-128-156.8V32c0-17.7-14.3-32-32-32zm45.3 493.3c12-12 18.7-28.3 18.7-45.3H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7z"/>
                </svg>
                <div v-if="notify_count" class="absolute inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-cherry bg-white border-[1px] border-cherry rounded-full -top-0 -end-0 dark:border-gray-900">{{ notify_count }}</div>
            </Link>
            <Link href="/perfil" class="flex items-center p-2" @click="isOpen = false">
                <span class="">Mi Perfil</span>
            </Link>
            <Link href="/logout" as="button" method="post" class="flex items-center p-2 w-full sm:w-auto">
                <img src="/images/icons/logout.png" class="w-5 dark:hidden">
                <img src="/images/icons/logout-white.png" class="w-5 hidden dark:block">
                <span class="">&nbsp; Salir</span>
            </Link>

            
            <Link v-if="user_role===1" href="/usuarios" class="flex items-center p-2 sm:hidden" @click="isOpen = false">
                <span class="">Personas Usuarias</span>
            </Link>
            <Link v-if="user_role===1 || user_role===2 || user_role===3 || user_role===4" href="/captura-inicial" class="flex items-center p-2 sm:hidden"  @click="isOpen = false">
                <span class="">Captura Inicial</span>
            </Link>
            <Link v-if="(user_role===3 || user_role===4) && checkArea([2, 3, 6])" href="/primer-contacto" class="flex items-center p-2 sm:hidden"  @click="isOpen = false">
                <span class="">Entrevista Primer Contacto</span>
            </Link>
            <Link v-if="(user_role===3 || user_role===4) && checkArea([3])" href="/rev" class="flex items-center p-2 sm:hidden"  @click="isOpen = false">
                <span class="">Registro Estatal de Víctimas</span>
            </Link>
            <Link v-if="(user_role===3 || user_role===4) && checkArea([3])" href="/renavi" class="flex items-center p-2 sm:hidden"  @click="isOpen = false">
                <span class="">RENAVI / RENADET</span>
            </Link>
            <Link v-if="user_role===1 || user_role===3 || user_role===4" href="/reportes" class="flex items-center p-2 sm:hidden"  @click="isOpen = false">
                <span class="">Reporte de Víctimas</span>
            </Link>
            <Link href="/videotutoriales" class="flex items-center p-2 sm:hidden"  @click="isOpen = false">
                <span class="">Videotutoriales</span>
            </Link>
        </nav>
    </header>
</template>
