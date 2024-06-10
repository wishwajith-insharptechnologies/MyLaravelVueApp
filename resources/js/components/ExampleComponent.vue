<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        I'm an example component.
                    </div>
                    <button
                        type="button"
                        @click="counter++"
                        class="p-2 text-white bg-gray-500 rounded"
                    >
                        Counter is: {{ counter }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const counter = ref(0)
</script>

