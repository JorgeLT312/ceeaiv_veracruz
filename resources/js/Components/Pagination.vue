<template>
    <div class="text-center my-2">
        <template v-for="link in links">
            <template v-if="link.url">
                <Link :href="link.url" class="px-1" :class="link.active ? 'font-bold' : ''" >{{ link.label }}</Link>
            </template>
            <template v-else>
                <span class="px-1 text-gray-500">{{ link.label }}</span>
            </template>

        </template>
    </div>
</template>
<script setup>
defineProps({
    links: Array,
});
</script>
