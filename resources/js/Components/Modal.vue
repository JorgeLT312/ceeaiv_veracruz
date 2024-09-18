<template>
    <TransitionRoot as="template" :show="open">
        <Dialog as="div" class="relative z-10" @close="hide">
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                        <DialogPanel class="relative transform overflow-hidden rounded-xl bg-gray-light text-left shadow-xl transition-all sm:my-8 sm:w-full" :class="size">
                            <div class="bg-cherry px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <DialogTitle class="text-xl font-bold text-white">
                                    <slot name="modal-header"></slot>
                                </DialogTitle>
                            </div>
                            <div class="px-4 pb-4 pt-5 sm:p-6 sm:pb-4 overflow-y-auto">
                                <slot name="modal-body"></slot>
                            </div>
                            <div class="px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 justify-center">
                                <slot name="modal-footer"></slot>
                                <button type="button" class="btn-close" @click="hide">Cerrar</button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
    import { ref } from 'vue'
    import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'

    defineProps({
        size: {
            type: String,
            default: 'max-w-lg'
        },
    });

    const open = ref(false)

    function show() {
        open.value = true;
    }

    function hide() {
        open.value = false;
    }

    defineExpose({
        show,
        hide
    });
</script>
