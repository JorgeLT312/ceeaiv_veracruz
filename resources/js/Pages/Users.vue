<template>
  <div class="grid grid-cols-1 gap-4">
    <div>
      <p class="font-bold text-gray-dark text-xl dark:text-white">
        Personas Usuarias
      </p>
    </div>
    <div
      class="rounded-[16px] md:rounded-full bg-gray-light dark:bg-gray-table px-4 py-2 md:flex items-center justify-between text-black"
    >
      <div class="relative w-1/2">
        <input
          type="text"
          v-model="search"
          id="search-input"
          class="rounded-full block w-full ps-2 p-2"
          placeholder="Buscar"
        />
        <button
          type="button"
          class="absolute inset-y-0 end-0 flex items-center pe-3"
        >
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

      <button
        class="btn-normal"
        @click="showUser(null)"
      >
        Registrar Persona Usuaria
      </button>
    </div>
    <div class="rounded-xl bg-gray-light dark:bg-gray-table p-4">
      <div class="overflow-auto">
        <table class="table-auto w-full custom-table" cellspacing="0">
          <thead>
            <tr class="text-gray-dark dark:text-white text-left">
              <th>Rol</th>
              <th>Usuario</th>
              <th>Nombre Completo</th>
              <th>Correo Electrónico</th>
              <th>Área</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody class="text-gray-normal dark:text-white">
            <tr class="dark:bg-gray-row" v-for="user in users.data">
              <td>{{ getRolName(user.role_id) }}</td>
              <td>{{ user.username }}</td>
              <td>{{ user.name }}</td>
              <td>{{ user.email }}</td>
              <td>{{ getArea(user.area_id) }}</td>
              <td>
                <div class="flex">
                  <a
                    href="#"
                    class="flex items-center p-2 rounded-full w-8 h-8 bg-gray-light hover:bg-amber-300"
                    @click.prevent="showUser(user)"
                    title="Editar"
                  >
                    <img src="/images/icons/edit.png" class="object-contain" />
                  </a>
                  <a
                    href="#"
                    class="flex items-center p-2 rounded-full w-8 h-8 bg-gray-light hover:bg-amber-300"
                    @click.prevent="disableUser(user)"
                    title="Desactivar"
                  >
                    <svg
                      class="w-5 h-5"
                      height="20"
                      width="20"
                      aria-hidden="true"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="rgb(112, 112, 112)"
                      viewBox="0 0 448 512"
                    >
                      <path
                        v-if="user.active"
                        d="M144 144c0-44.2 35.8-80 80-80c31.9 0 59.4 18.6 72.3 45.7c7.6 16 26.7 22.8 42.6 15.2s22.8-26.7 15.2-42.6C331 33.7 281.5 0 224 0C144.5 0 80 64.5 80 144v48H64c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V256c0-35.3-28.7-64-64-64H144V144z"
                      />
                      <path
                        v-else
                        d="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80z"
                      />
                    </svg>
                  </a>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="overflow-auto">
      <Pagination :links="users.links"></Pagination>
    </div>
    <Modal ref="modalDeactivateUser">
      <template #modal-header>
        <p>{{
            form.active
                    ? "Desactivar acceso del Usuario"
                    : "Activar acceso del Usuario"
                 }}</p>
      </template>
      <template #modal-body>
        <div class="w-full max-w-md">
          <div class="md:flex md:item-center mb-2">
            <div class="md:w-full">
              <label class="block mb-1 md:mb-0" for="inline-full-name">
                {{
                    form.active
                    ? "Este proceso desactivará el acceso del usuario a la plataforma. ¿Estás seguro que deseas continuar?"
                    : "Este proceso activará el acceso del usuario a la plataforma. ¿Estás seguro que deseas continuar?"
                }}
              </label>
            </div>
          </div>
        </div>
      </template>
      <template #modal-footer>
        <button
          type="button"
          @click="lockUser"
          class="mx-1 mt-3 inline-flex w-full justify-center rounded-full bg-yellow-normal px-4 py-2 hover:bg-cherry hover:text-white sm:mt-0 sm:w-auto"
        >
          Aceptar
        </button>
      </template>
    </Modal>

    <Modal ref="modalUser">
      <template #modal-header>
        <p>{{ form.id ? 'Editar' : 'Nuevo' }} Usuario</p>
      </template>
      <template #modal-body>
        <div class="w-full max-w-md">
          <div class="md:flex md:item-center mb-2">
            <div class="md:w-1/3">
              <label class="block mb-1 md:mb-0 pr-4" for="inline-full-name">
                Usuario*:
              </label>
            </div>
            <div class="md:w-2/3">
              <input
                id="username"
                type="text"
                v-model="form.username"
                class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal"
              />
              <div
                v-if="form.errors.username"
                v-text="form.errors.username"
                class="text-red-500"
              ></div>
            </div>
          </div>
          <div class="md:flex md:item-center mb-2">
            <div class="md:w-1/3">
              <label class="block mb-1 md:mb-0 pr-4" for="inline-full-name">
                Nombre*:
              </label>
            </div>
            <div class="md:w-2/3">
              <input
                id="name"
                type="text"
                v-model="form.name"
                class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal"
              />
              <div
                v-if="form.errors.name"
                v-text="form.errors.name"
                class="text-red-500"
              ></div>
            </div>
          </div>
          <div class="md:flex md:item-center mb-2">
            <div class="md:w-1/3">
              <label class="block mb-1 md:mb-0 pr-4" for="inline-full-name">
                Correo*:
              </label>
            </div>
            <div class="md:w-2/3">
              <input
                id="email"
                type="text"
                v-model="form.email"
                class="bg-white rounded-full w-full py-2 px-4 border border-white focus:outline-none focus:border-gray-normal"
              />
              <div
                v-if="form.errors.email"
                v-text="form.errors.email"
                class="text-red-500"
              ></div>
            </div>
          </div>
          <div class="md:flex md:item-center mb-2">
            <div class="md:w-1/3">
              <label class="block mb-1 md:mb-0 pr-4" for="inline-full-name">
                Área*:
              </label>
            </div>
            <div class="md:w-2/3">
              <div class="relative">
                <select
                  class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                  v-model="form.area_id"
                  id="area_id"
                >
                  <option value="null" disabled>Seleccionar Área</option>
                  <option v-for="area in areas" :value="area.id">
                    {{ area.nombre }}
                  </option>
                </select>
                <div
                  class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700"
                >
                  <svg
                    class="fill-current h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    height="16"
                    width="16"
                    viewBox="0 0 512 512"
                  >
                    <path
                      d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"
                    />

                  </svg>
                </div>
              </div>
              <div  v-if="form.errors.area_id" v-text="form.errors.area_id" class="text-red-500"></div>
            </div>
          </div>
          <div class="md:flex md:item-center mb-2">
            <div class="md:w-1/3">
              <label class="block mb-1 md:mb-0 pr-4" for="inline-full-name">
                Rol*:
              </label>
            </div>
            <div class="md:w-2/3">
              <div class="relative">
                <select
                  class="block appearance-none w-full bg-white border border-white px-4 py-2 rounded-full focus:outline-none focus:border-gray-normal"
                  v-model="form.role_id"
                  id="role_id"
                >
                  <option value="null" disabled>Seleccionar Rol</option>
                  <option v-for="rol in roles" :value="rol.id">
                    {{ rol.name }}
                  </option>
                </select>
                <div
                  class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700"
                >
                  <svg
                    class="fill-current h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    height="16"
                    width="16"
                    viewBox="0 0 512 512"
                  >
                    <path
                      d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"
                    />
                  </svg>
                </div>
              </div>
              <div  v-if="form.errors.role_id" v-text="form.errors.role_id" class="text-red-500"></div>
            </div>
          </div>
        </div>
      </template>
      <template #modal-footer>
        <button
          v-if="form.id"
          @click="resetPassword"
          type="button"
          class="mx-1 mt-3 inline-flex w-full justify-center rounded-full bg-yellow-normal px-4 py-2 hover:bg-cherry hover:text-white sm:mt-0 sm:w-auto"
        >
          Actualizar Contraseña
        </button>
        <button
          type="button"
          @click="saveUser"
          class="mx-1 mt-3 inline-flex w-full justify-center rounded-full bg-yellow-normal px-4 py-2 hover:bg-cherry hover:text-white sm:mt-0 sm:w-auto"
        >
          Guardar
        </button>
      </template>
    </Modal>
  </div>
</template>

<script setup>
import { ref, watch } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import Pagination from "../Components/Pagination.vue";
import debounce from "lodash/debounce";
import { useNotification } from "@kyvg/vue3-notification";

const { notify }  = useNotification();
let modalDeactivateUser = ref(null);
const modalUser = ref(null);
const search = ref(props.filters.search);

let props = defineProps({
  users: Object,
  filters: Object,
  roles: Array,
  areas: Array,
});

let form = useForm({
  id: null,
  name: "",
  username: "",
  email: "",
  role_id: null,
  area_id: null,
});

let saveUser = () => {
  //HoldOn.open({ message: "Procesando datos, por favor espere." });
  form.clearErrors();
  if (form.id) {
    form.patch("/usuarios/" + form.id, {
      onError: (errors) => {
        //HoldOn.close();
      },
      onSuccess: (data) => {
        notify({
            title: "¡Éxito!",
            text: "Datos guardados correctamente.",
            type: "success"
        });
        modalUser.value.hide();
        form.reset();
        //HoldOn.close();
      },
    });
  } else {
    form.post("/usuarios", {
      onError: (errors) => {
        //HoldOn.close();
      },
      onSuccess: (data) => {
        notify({
            title: "¡Éxito!",
            text: "Datos guardados correctamente.",
            type: "success"
        });
        modalUser.value.hide();
        form.reset();
        //HoldOn.close();
      },
    });
  }
};


let lockUser = () => {
  //HoldOn.open({ message: "Procesando datos, por favor espere." });
  form.clearErrors();
  if (form.active) {
    form.post("/usuario/desactivar", {
      onError: () => {
        //HoldOn.close();
      },
      onSuccess: () => {
        notify({
            title: "¡Éxito!",
            text: "Se desactivó el usuario correctamente.",
            type: "success"
        });
        modalDeactivateUser.value.hide();
        form.reset();
        //HoldOn.close();
      },
    });
  } else {
    form.post("/usuario/activar", {
      onError: () => {
        //HoldOn.close();
      },
      onSuccess: () => {
        notify({
            title: "¡Éxito!",
            text: "Se activó el usuario correctamente.",
            type: "success"
        });

        modalDeactivateUser.value.hide();
        form.reset();
        //HoldOn.close();
      },
    });
  }
};

function showUser(user) {
  form.reset();
  form.clearErrors();
  if (user) {
    form.id = user.id;
    form.name = user.name;
    form.username = user.username;
    form.email = user.email;
    form.role_id = user.role_id;
    form.area_id = user.area_id;
  }

  modalUser.value.show();
}

function disableUser(user) {
    form.id = user.id;
    form.active = user.active;
    modalDeactivateUser.value.show();
}

function resetPassword() {
  //HoldOn.open({ message: "Procesando datos, por favor espere." });
  router.post(
    "/usuario/updatePassword",
    { email: form.email },
    {
      onSuccess: () => {
        //HoldOn.close();
        modalUser.value.hide();
        notify({
            title: "¡Éxito!",
            text: "Correo enviado correctamente.",
            type: "success"
        });
      },
    }
  );
}

function getRolName(id) {
  let rol = props.roles.find(function (item) {
    return item.id == id;
  });

  return rol?.name;
}

function getArea(id) {
    let data = props.areas.filter((item) => item.id == id);

    if (data.length) {
        return data[0].nombre;
    }

    return '-';
}

watch(
  search,
  debounce(function (value) {
    if (value) {
      router.get(
        "/usuarios",
        { search: value },
        {
          preserveState: true,
          replace: true,
        }
      );
    } else {
      router.get(
        "/usuarios",
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

