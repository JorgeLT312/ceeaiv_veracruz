<template>
    <div class="grid grid-cols-1 gap-4">
        <form @submit.prevent="submit" class="md:px-16 py-8">
            <div>
                <p class="font-bold text-gray-dark text-xl dark:text-white">Perfil</p>
            </div>
            <div class="flex flex-col">
                <p class="text-sm text-gray">En esta interfaz podrás actualizar tu contraseña de acceso a la plataforma.</p>
            </div>
            <div class="flex md:justify-center">
                <div class="grid grid-cols-1 gap-6 mt-8 bg-gray-light dark:bg-gray-table dark:text-white text-black p-4 rounded-xl w-full md:w-1/2">
                    <div class="">
                        <label for="password" class="block">Nueva contraseña*:</label>
                        <div class="mt-2">
                            <input id="password" v-model="form.password" name="password" type="text" autocomplete="password" required
                                class="block w-full text-black rounded-full px-4 py-2 border border-gray-300 focus:outline-none focus:border-gray-normal">
                        </div>
                        <div v-if="form.errors.password" v-text="form.errors.password" class="text-red-500"></div>
                    </div>
                    <div class="">
                        <label for="password_confirmation" class="block">Repite Contraseña:</label>
                        <div class="mt-2">
                            <input id="password_confirmation" v-model="form.password_confirmation" name="password_confirmation" type="password" autocomplete="password" required
                                class="block w-full text-black rounded-full px-4 py-2 border border-gray-300 focus:outline-none focus:border-gray-normal">
                        </div>
                        <div v-if="form.errors.password_confirmation" v-text="form.errors.password_confirmation" class="text-red-500"></div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn-normal w-full sm:w-auto">Actualizar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
<script setup>
import { useForm } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

let form = useForm({
    password: '',
    password_confirmation: ''
});

let submit = () => {
    //HoldOn.open({ message: 'Procesando datos, por favor espere.'});
    form.post('/perfil/updatePassword', {
        onError: () =>{
            //HoldOn.close();
        },
        onSuccess: () => {
            form.reset();
            //HoldOn.close();
            Swal.fire("¡Éxito!", "Datos guardados correctamente.","success");
        },
    });
}
</script>

