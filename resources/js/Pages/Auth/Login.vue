<template>
    <div class="self-center mx-auto mt-12 mb-14 max-w-[410px] rounded-xl bg-gray-light">
        <form @submit.prevent="submit" class="px-16 py-8">
          <div class="flex flex-col">
            <p class="mb-0 mr-4 text-lg font-black text-cherry">Bienvenido a la plataforma de la Comisión Ejecutiva Estatal de Atención Integral  a Víctimas (CEEAIV).</p>
            <p class="text-sm text-gray">Introduce tus credenciales para acceder con tu rol de usuario.</p>
          </div>

          <div class="grid grid-cols-1 gap-6 mt-8">
              <div class="">
                  <label for="username" class="block">Usuario:</label>
                  <div class="mt-2">
                    <input id="username" v-model="form.username" name="username" type="text" autocomplete="username" required
                        class="block w-full rounded-full px-4 py-2 border border-white focus:outline-none focus:border-gray-normal">
                  </div>
                  <div v-if="errors.username" v-text="errors.username" class="text-red-500"></div>
              </div>
              <div class="">
                  <label for="password" class="block">Contraseña:</label>
                  <div class="mt-2">
                    <input id="password" v-model="form.password" name="password" type="password" autocomplete="password" required
                        class="block w-full rounded-full px-4 py-2 border border-white focus:outline-none focus:border-gray-normal">
                  </div>
              </div>
              <div class="">
                <a href="/forgot-password" class="">¿Olvidaste tu contraseña?</a>
              </div>
              <div class="text-center">
                  <button type="submit" class="rounded-full px-4 py-2 bg-yellow-normal hover:bg-cherry hover:text-white">Acceder</button>
              </div>
          </div>
        </form>
      </div>
</template>
<script setup>
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3'

defineProps({
    errors: Object
});

let form = reactive({
    username: '',
    password: ''
});

let submit = () => {
    router.post('/login', form);
}
</script>
