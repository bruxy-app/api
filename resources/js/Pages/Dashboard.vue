<script setup>
import { ref } from "vue";

const props = defineProps({
    user: Object,
});
</script>

<template>
    <header class="header">
        <h1>Bruxy</h1>
    </header>
    <div class="content">
        <h2>Tratamentos de {{ user.name }}</h2>
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
                <tr v-for="treatment in user.treatments" :key="treatment.id">
                    <td>{{ treatment.patient.user.name }}</td>
                    <td>
                        {{ new Date(treatment.starts_at).toLocaleDateString() }}
                    </td>
                    <td>
                        {{ new Date(treatment.ends_at).toLocaleDateString() }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<style scoped>
.header {
    background-color: #2176ff;
    width: 100%;
    padding: 10px 0;
    text-align: center;
    color: white;
    font-size: 24px;
    font-weight: bold;
    position: fixed;
    top: 0;
    border-radius: 0 0 10px 10px;
    z-index: 10;
}

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
}

.treatment-table tr:last-child td {
    border-bottom: none;
}
</style>
