<script setup>
import { watch, computed, ref } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Sidebar from '../Components/Sidebar.vue'
import Navbar from '../Components/Navbar.vue'
import Swal from 'sweetalert2'

const page = usePage()
const message = computed(function () {
    return page.props.flash.message;
});
const showSidebar = ref(false);


function toggleSidebar() {
    showSidebar.value = !showSidebar.value;
}

watch(message, function (val) {
    if (val) {
        Swal.fire('', val, 'info');
    }
});
</script>

<template>
    <notifications position="bottom left" :dangerouslySetInnerHtml="true"/>
    <main class="bg-white dark:bg-dark-fondo dark:text-white min-h-screen flex flex-col">
        <Sidebar @toggle="toggleSidebar"/>
        <section class="mb-4 mx-4" :class="showSidebar ? 'sm:ml-80' : 'sm:ml-28'">
            <Navbar/>

            <slot />
        </section>

        <footer class="bg-cherry mt-auto"  :class="showSidebar ? 'sm:ml-80' : 'sm:ml-28'">
            <div class="grid md:grid-cols-8 gap-4 p-4">
                <div class="flex flex-wrap justify-center items-center md:col-span-4 xl:col-span-3" :class="showSidebar ? '' : 'lg:flex-nowrap'">
                    <img class="max-h-[72px]" src="/images/veracruz.png">
                    <img class="ml-2 max-h-[75px]" src="/images/usaid.png">
                </div>
                <div class="md:col-span-4 xl:col-span-5 text-white text-xs">
                    <p>Plataforma de la Comisión Ejecutiva Estatal de Atención Integral a Víctimas <span>Veracruz, Gobierno del Estado</span></p>
                    <br>
                    <p>"Esta plataforma de registro es posible gracias al generoso apoyo del pueblo de los Estados Unidos a través de la Agencia de los Estados Unidos para el Desarrollo Internacional (USAID). El contenido es responsabilidad de la Comisión Ejecutiva Estatal de Atención Integral a Víctimas del estado de Veracruz y no necesariamente refleja las opiniones de USAID o del gobierno de los Estados Unidos."</p>
                </div>
            </div>
        </footer>
    </main>

</template>
