<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import Header from "../Components/Header.vue";

const props = defineProps({
    user: Object,
});

const getFormattedDate = (date) => {
    const options = { year: "numeric", month: "long", day: "numeric" };

    return new Date(date).toLocaleDateString("pt-BR", options);
};

const openPatientDetails = (patient) => {
    router.visit(`/patient/${patient.patient.uuid}`);
};

const onAddNewPatient = () => {
    router.visit("/new-patient");
};
</script>

<template>
    <Header />
    <div class="content">
        <div class="is-flex is-justify-content-space-between">
            <h2 class="title has-text-dark">Pacientes de {{ user.name }}</h2>
            <div>
                <button class="button is-info" @click="onAddNewPatient">
                    Adicionar paciente
                </button>
            </div>
        </div>
        <table class="treatment-table">
            <thead>
                <tr>
                    <th>Paciente</th>
                    <th>Data de início</th>
                    <th>Data fim</th>
                    <!-- <th>Porcentagem de conclusão</th> -->
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="patient in user.clinic.patients"
                    :key="patient.uuid"
                    @click="openPatientDetails(patient)"
                >
                    <td class="has-text-dark">
                        {{ patient.name }}
                    </td>
                    <td class="has-text-dark">
                        {{
                            patient.patient.treatment
                                ? getFormattedDate(
                                      patient.patient.treatment.starts_at
                                  )
                                : "-"
                        }}
                    </td>
                    <td class="has-text-dark">
                        {{
                            patient.patient.treatment
                                ? getFormattedDate(
                                      patient.patient.treatment.ends_at
                                  )
                                : "-"
                        }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<style scoped>
.content {
    padding: 20px;
    margin-top: 60px;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

.treatment-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0 auto;
    background-color: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
}

.treatment-table th,
.treatment-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.treatment-table th {
    background-color: #2176ff;
    color: white;
}

.treatment-table tr:hover {
    background-color: #f1f1f1;
    cursor: pointer;
}

.treatment-table tr:last-child td {
    border-bottom: none;
}
</style>
