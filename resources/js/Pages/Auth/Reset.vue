<template>
    <div class="self-center mx-auto mt-12 mb-14 max-w-[410px] rounded-xl bg-gray-light">
        <form @submit.prevent="submit" class="px-16 py-8">
          <div class="flex flex-col">
            <p class="mb-0 mr-4 text-lg font-black text-cherry">Ingresa tu nueva contraseña</p>
            <p class="text-sm text-gray">Ingresa tu nueva contraseña.</p>
          </div>
          <div class="grid grid-cols-1 gap-6 mt-8">
              <div class="">
                  <label for="email" class="block">Correo electronico:</label>
                  <div class="mt-2">
                    <input id="email" v-model="form.email" name="email" type="text" autocomplete="email" required
                        class="block w-full rounded-full px-4 py-2 border border-white focus:outline-none focus:border-gray-normal">
                  </div>
                  <div v-if="errors.email" v-text="errors.email" class="text-red-500"></div>
                  <div v-if="errors.token" v-text="errors.token" class="text-red-500"></div>
              </div>
              <div class="">
                <label for="password" class="block">Contraseña:</label>
                <div class="mt-2">
                  <input id="password" v-model="form.password" name="password" type="password" required
                      class="block w-full rounded-full px-4 py-2 border border-white focus:outline-none focus:border-gray-normal">
                </div>
                <div v-if="errors.password" v-text="errors.password" class="text-red-500"></div>
            </div>
            <div class="">
                <label for="password_confirmation" class="block">Confirmar Contraseña:</label>
                <div class="mt-2">
                  <input id="password_confirmation" v-model="form.password_confirmation" name="password_confirmation" type="password" required
                      class="block w-full rounded-full px-4 py-2 border border-white focus:outline-none focus:border-gray-normal">
                </div>
                <div v-if="errors.password_confirmation" v-text="errors.password_confirmation" class="text-red-500"></div>
            </div>
            <div class="">
                <a href="/login" class="">Regresar al Login</a>
              </div>
              <div class="text-center">
                  <button type="submit" class="rounded-full px-4 py-2 bg-yellow-normal hover:bg-cherry hover:text-white">Enviar</button>
              </div>
          </div>
        </form>
      </div>
</template>
<script setup>
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3'
import { stringify } from 'postcss';

let props = defineProps({
    errors: Object,
    token: String,
});

let form = reactive({
    token: props.token,
    email: '',
    password: '',
    password_confirmation: '',
});

let submit = () => {
    router.post('/reset-password', form);
}
</script>
