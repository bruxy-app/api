<script setup>
import { ref, onMounted } from "vue";
import { router } from "@inertiajs/vue3";
import Header from "../Components/Header.vue";
import axios from "axios";

const props = defineProps({
    patient: Object,
});
let treatmentUuid = ref("");

onMounted(() => {
    if (props.patient.treatment) {
        treatmentUuid.value = props.patient.treatment.uuid;
    }
});

const onStartTreatment = async () => {
    const { data } = await axios.post("/api/treatments", {
        patient_uuid: props.patient.uuid,
        user_uuid: localStorage.getItem("token"),
    });

    treatmentUuid = data.uuid;

    router.visit("/patient/" + props.patient.uuid, {
        replace: true,
    });
};

const back = () => {
    router.visit("/dashboard", {
        replace: true,
        data: { token: localStorage.getItem("token") },
    });
};
</script>

<template>
    <Header />
    <section class="section">
        <div class="container">
            <div class="box" style="background-color: antiquewhite">
                <button class="button is-info" @click="back">Voltar</button>
                <h1 class="title has-text-dark">
                    Paciente: {{ patient.user.name }}
                </h1>
                <div v-if="treatmentUuid">
                    <span class="has-text-dark"
                        >Código do tratamento: {{ treatmentUuid }}</span
                    >
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
