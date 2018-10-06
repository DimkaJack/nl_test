<template>
    <div>
        <md-list>


            <md-list-item v-for="task in tasks" :key="task.id">
            </md-list-item>

            <div class="md-layout-item md-size-100 text-center" v-if="!tasks.length">
                Добавьте
            </div>
        </md-list>
    </div>
</template>

<script>
    export default {
        name: 'Tasks',
        data: () => ({
            tasks: []
        })
    }
</script>