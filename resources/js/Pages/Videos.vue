<template>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <p class="font-bold text-gray-dark text-xl dark:text-white">
                Videotutoriales
            </p>
        </div>
        <div class="rounded-xl bg-gray-light dark:bg-gray-table p-4">
            <div class="overflow-auto">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div v-for="video in videos" class="hover:bg-yellow-normal dark:hover:bg-gray-hoverBtn rounded-xl p-2">
                        <a class="text-center" :href="video.url" target="blank">
                            <img class="rounded-xl mx-auto" :src="getThumbnail(video.url)">
                            <p class="mt-4">{{ video.name }}</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import { ref, onMounted } from "vue";

let videos = [
        {
            name: 'Capacitación para el manejo de la plataforma de la CEEAIV – Primera Parte',
            url: 'https://www.youtube.com/watch?v=BJ8ToSL9Y9c'
        },
        {
            name: 'Capacitación para el manejo de la plataforma de la CEEAIV – Segunda Parte',
            url: 'https://www.youtube.com/watch?v=0xGMjwAFQRQ'
        },
        {
            name: 'Capacitación para el manejo de la plataforma de la CEEAIV – Carga Masiva',
            url: 'https://www.youtube.com/watch?v=VKCMPgGTKqg'
        }
    ];

onMounted(() => {
    
})

function getThumbnail(data) {
    let youtbeUrl = new URL(data);
    let urlParams = new URLSearchParams(youtbeUrl.search);

    let youtube_video_id = urlParams.get('v');

    if (youtube_video_id.length == 11) {
        return '//img.youtube.com/vi/'+youtube_video_id+'/0.jpg';
    }

    return '';
}

</script>
