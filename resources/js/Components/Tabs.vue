<template>
    <ul class="flex sm:flex-wrap mb-4 overflow-auto">
        <li :id="'btn_tab_'+index" :class="tabItems.length == 3 ? 'sm:w-1/3' : 'sm:w-1/6'" class="group cursor-pointer" @click="showTab(index)" v-for="(item, index) in tabItems">
            <div class="flex items-center">
                <div>
                    <div
                        :class="showColor(item)"
                        class="grid content-center border-4 rounded-full font-bold  w-10 h-10 text-center group-hover:border-blue-500 group-hover:text-blue-500">
                        {{ subTab }}{{ index+1 }}
                    </div>
                </div>
                <span class="ml-2 break-words">{{ item.name }}</span>
            </div>
        </li>
    </ul>
    <div>
        <div
            v-for="(item, index) in tabItems"
            :class="item.status"
        >
            <slot
                :name="'tab-content-'+index"
            ></slot>
        </div>
    </div>
</template>
<script setup>
import { ref } from 'vue'

const emit = defineEmits(['onChangeTab'])

let props = defineProps({
    tabs: Object,
    customTab: {
        default: 0,
    },
    subTab: {
        default: '',
    }
});

const tabItems = ref(props.tabs);

function showTab(index) {
    if (!props.customTab) {
        tabItems.value.every(function(item) {
            item.status = 'hidden';
            return true;
        });
        tabItems.value[index].status = 'block';
    }

    emit('onChangeTab', index);
}

function activeTab(index) {
    tabItems.value.every(function(item) {
        item.status = 'hidden';
        return true;
    });
    tabItems.value[index].status = 'block';
}

function showColor(item) {
    if (item.status == 'block') {
        return 'border-blue-500 text-blue-500';
    }

    if (item.validation == 'required') {
        return 'border-red-600 text-red-600';
    }

    if (item.validation == 'complete') {
        return 'border-green-normal text-green-normal';
    }

    return 'border-gray-normal text-gray-normal';
}

defineExpose({
  showTab,
  activeTab
});

</script>
