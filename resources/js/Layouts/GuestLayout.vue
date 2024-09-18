<script setup>
import { watch, ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const page = usePage()

const message = computed(function () {
  return page.props.flash.message;
});

watch(message, function (val) {
    if (val) {
        Swal.fire('', val, 'info');
    }
});
</script>

<template>
    <main class="bg-[#fbd9ba] min-h-screen flex flex-col md:bg-[url('/images/login.png')] bg-no-repeat bg-cover bg-right">
        <section class="">
            <div class="h-full">
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    <div class="lg:col-span-2">
                        <div class="g-6 flex flex-wrap items-center justify-center lg:justify-between">
                            <div class="mx-auto flex items-center">
                                <img class="h-20 block" src="/images/Escudo.svg">
                                <img class="h-20" src="/images/CEEAIV.svg">
                            </div>
                            <div class="grow-0 basis-auto hidden lg:block w-9/12 shrink-0 lg:w-6/12 xl:w-6/12">

                            </div>
                        </div>
                    </div>
                    <slot />

                    <div></div>
                </div>
            </div>
          </section>
          <footer class="bg-cherry mt-auto">
            <div class="grid grid-cols-8 gap-4 p-4">
                <div class="flex flex-wrap lg:flex-nowrap justify-end items-center col-span-3">
                    <img class="h-[72px]" src="/images/veracruz.png">
                    <img class="ml-2 h-[75px]" src="/images/usaid.png">
                </div>
                <div class="col-span-5 text-white text-xs">
                    <p>Plataforma de la Comisión Ejecutiva Estatal de Atención Integral a Víctimas <span>Veracruz, Gobierno del Estado</span></p>
                    <br>
                    <p>"Esta plataforma de registro es posible gracias al generoso apoyo del pueblo de los Estados Unidos a través de la Agencia de los Estados Unidos para el Desarrollo Internacional (USAID). El contenido es responsabilidad de la Comisión Ejecutiva Estatal de Atención Integral a Víctimas del estado de Veracruz y no necesariamente refleja las opiniones de USAID o del gobierno de los Estados Unidos."</p>
                </div>
            </div>
        </footer>
    </main>
</template>
