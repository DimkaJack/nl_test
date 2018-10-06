<template>
    <div id="todoWrapper">
        <md-toolbar class="md-primary">
            <span class="md-title">Todo List</span>
        </md-toolbar>

        <md-drawer :md-active.sync="showAddForm">
            <md-toolbar class="md-primary">
                Создание задачи
            </md-toolbar>

            <md-content>
                <form novalidate class="md-layout" @submit.prevent="validateForm">

                    <div class="md-layout-item md-size-100">
                        <md-field>
                            <label for="task">Задача</label>
                            <md-textarea name="task" id="task" v-model="formTask" :disabled="sending" />
                        </md-field>
                    </div>

                    <md-progress-bar md-mode="indeterminate" v-if="sending" />

                    <md-button type="submit" class="md-primary md-layout-item  md-size-100" :disabled="canSubmit">
                        Добавить задачу
                    </md-button>
                </form>
            </md-content>
        </md-drawer>

        <md-content>
            <md-button class="md-layout-item md-primary md-size-100" @click="showAddForm = true">
                <span v-if="!tasks.length">Cоздать первую задачу</span>
                <span v-else>Добавить задачу</span>
            </md-button>

            <tasks></tasks>
        </md-content>

        <md-snackbar :md-active="showSnackBar" :md-duration="3000">{{ statusMsg }}</md-snackbar>

        <div id="Loader" v-if="loaderText !== ''">
            <md-progress-spinner :md-diameter="20" md-mode="indeterminate"></md-progress-spinner>
            <div id="loaderText">{{ loaderText }}</div>
        </div>
    </div>
</template>

<script>
    let Tasks = require('./Tasks.vue');

    export default {
        name: 'TodoList',
        components: {
            'tasks': Tasks
        },
        data: function () {
            return {
                tasks: [],
                loaderText: '',
                statusMsg: '',
                formTask: '',
                showAddForm: false,
                sending: false,
                taskSaved: false,
                showSnackBar: false,
            }
        },
        mounted: function () {
            this.getTasks();
        },
        computed: {
            // Проверка может ли форма быть отпрвлена
            canSubmit: function () {
                return this.sending || this.formTask === '';
            },
        },
        methods: {
            // Валидация формы
            validateForm () {
                this.loaderText = 'Подождите, идёт добавление';

                if (this.formTask.search(/\!/) === -1) {
                    this.saveTask();
                } else {
                    this.loaderText = '';
                    this.showSnackBar = true;
                    this.statusMsg = 'Текст задачи не должен содержать знака "!"';
                }
            },
            // Сохранение задачи
            saveTask () {
                this.sending = true;
                let data = {
                    'task': this.formTask
                };

                window.axios.post('/api/tasks', data)
                    .then(function (response) {
                        // handle success
                        console.log(response);
                        if (response.data.status === 'true') {
                            this.tasks.push(response.data.task);
                            this.showAddForm = false;
                            this.formTask = '';
                            this.sending = false;
                        } else {
                            //
                        }
                    }.bind(this))
                    .catch(function (error) {
                        this.showSnackBar = true;
                        this.statusMsg = 'Произошла ошибка!';
                    })
                    .then(function () {
                        this.loaderText = '';
                    }.bind(this));
            },
            // Получение списка задач
            getTasks () {
                this.loaderText = 'Задачи загружаются...';

                window.axios.get('/api/tasks')
                    .then(function (response) {
                        response.data.data.forEach(task => {
                            this.tasks.push(task);
                        });
                    }.bind(this))
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        this.loaderText = '';
                    }.bind(this));

            }
        }
    }
</script>