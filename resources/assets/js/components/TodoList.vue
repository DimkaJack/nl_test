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

            <div class="md-layout-item md-size-100 text-center" v-if="!tasks.length">
                Добавьте
            </div>

            <md-table>
                <md-table-row>
                    <md-table-head md-numeric>ID</md-table-head>
                    <md-table-head>Задача</md-table-head>
                    <md-table-head>Дата создания</md-table-head>
                    <md-table-head>Действия</md-table-head>
                </md-table-row>

                <!--Список-->
                <md-table-row v-for="task in tasks" :key="task.id">
                    <md-table-cell md-numeric>
                        {{ task.id}}
                    </md-table-cell>
                    <md-table-cell class="task-block">
                        {{ task.task }}

                        <md-button class="btn-edit" @click="showEditDialog(task)">
                            <md-icon>create</md-icon>
                        </md-button>
                    </md-table-cell>
                    <md-table-cell>
                        {{ task.created_at }}
                    </md-table-cell>
                    <md-table-cell>
                        <md-button>
                            <md-icon class="text-danger">delete</md-icon>
                        </md-button>
                    </md-table-cell>
                </md-table-row>
            </md-table>
        </md-content>

        <md-snackbar :md-active="showSnackBar" :md-duration="3000">{{ statusMsg }}</md-snackbar>

        <div id="Loader" v-if="loaderText !== ''">
            <md-progress-spinner :md-diameter="20" md-mode="indeterminate"></md-progress-spinner>
            <div id="loaderText">{{ loaderText }}</div>
        </div>

        <md-dialog :md-active.sync="showDialog">
            <md-dialog-title>Редактирование задачи</md-dialog-title>

            <md-content>
                <form novalidate class="md-layout" @submit.prevent="validateForm">

                    <div class="md-layout-item md-size-100">
                        <md-field>
                            <label for="taskEdit">Задача</label>
                            <md-textarea name="task" id="taskEdit" v-model="formTaskEdit" :disabled="sending" />
                        </md-field>
                    </div>

                    <md-progress-bar md-mode="indeterminate" v-if="sending" />


                </form>
            </md-content>

            <md-dialog-actions>
                <md-button class="md-primary" @click="showDialog = false">
                    Отмена
                </md-button>
                <md-button type="submit" class="md-primary md-layout-item  md-size-100" :disabled="canSubmit">
                    Сохранить
                </md-button>
            </md-dialog-actions>
        </md-dialog>
    </div>
</template>

<script>
    export default {
        name: 'TodoList',
        data: function () {
            return {
                tasks: [],
                loaderText: '',
                statusMsg: '',
                formTask: '',
                formTaskEdit: '',
                formTaskEditId: '',
                showAddForm: false,
                sending: false,
                taskSaved: false,
                showSnackBar: false,
                showDialog: false,
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
            //
            showEditDialog (task) {
                this.formTaskEdit = task.task;
                this.formTaskEditId = task.id;

                this.showDialog = true;
            },
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
            //
            create () {
                let data = {
                    'task': this.formTask
                };
                this.saveTask('/api/tasks', data, 'create');
            },
            //
            update () {
                let data = {
                    'task': this.formTaskEdit
                };
                this.saveTask('/api/tasks/' + this.formTaskEditId, data, 'update');
            },
            // Сохранение задачи
            saveTask (url, data, type) {
                this.sending = true;

                window.axios.post(url, data)
                    .then(function (response) {
                        if (response.data.status === 'true') {
                            if (type === 'create') {
                                this.tasks.push(response.data.task);
                            } else {
                                this.tasks.find(function (task) {
                                    return task.id === this.formTaskEditId
                                }).task = response.data.task.task;
                            }
                            this.showAddForm = false;
                            this.formTask = '';
                            this.sending = false;
                        } else {
                            this.showSnackBar = true;
                            this.statusMsg = 'Произошла ошибка!';
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
                        this.showSnackBar = true;
                        this.statusMsg = 'Произошла ошибка!';
                    })
                    .then(function () {
                        this.loaderText = '';
                    }.bind(this));

            }
        }
    }
</script>