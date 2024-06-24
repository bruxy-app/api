<script setup>
import { ref, onMounted } from "vue";
import Header from "../Components/Header.vue";

const props = defineProps({
    patient: Object,
});

const onStartTreatment = () => {};
</script>

<template>
    <Header />
    <section class="section">
        <div class="container">
            <div class="box" style="background-color: antiquewhite">
                <h1 class="title has-text-dark">
                    Paciente: {{ patient.user.name }}
                </h1>
                <div v-if="patient.treatment">
                    <h2 class="subtitle">Treatments</h2>
                    <table class="table is-fullwidth is-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ patient.treatment }}</td>
                                <!-- <td>
                                    {{
                                        new Date(
                                            patient.treatment.date
                                        ).toLocaleDateString()
                                    }}
                                </td> -->
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else>
                    <div
                        class="is-flex is-align-items-center is-justify-content-space-between"
                    >
                        <p class="has-text-dark">
                            Esse paciente não possui nenhum tratamento ativo.
                        </p>

                        <button
                            class="button is-success"
                            @click="onStartTreatment"
                        >
                            Iniciar tratamento
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.section {
    padding-top: 60px;
}

.title,
.subtitle {
    text-align: center;
}

.table {
    margin-top: 20px;
}

.container {
    background-color: azure;
}

.content {
    margin-bottom: 20px;
}
</style>
