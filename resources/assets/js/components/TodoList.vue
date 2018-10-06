<template>
    <div class="page-container">
        <md-app md-mode="reveal">
            <md-app-toolbar class="md-primary">
                <span class="md-title">Todo List</span>
            </md-app-toolbar>

            <md-app-drawer :md-active.sync="menuVisible">
                <md-toolbar class="md-transparent" md-elevation="0">Navigation</md-toolbar>
            </md-app-drawer>

            <md-app-content></md-app-content>
        </md-app>
    </div>
</template>

<script>
    export default {
        name: 'TodoList',
        data: () => ({
            menuVisible: false
        })
    }
</script>