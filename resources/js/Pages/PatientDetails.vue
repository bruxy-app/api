<script setup>
import { ref, onMounted } from "vue";
import { router } from "@inertiajs/vue3";
import Header from "../Components/Header.vue";
import axios from "axios";

const props = defineProps({
    patient: Object,
});
let treatmentUuid = ref("");
let duration = ref(0);
let questionsPerDay = ref(0);
let answeredNotifications = ref([]);

onMounted(() => {
    if (props.patient.treatment) {
        treatmentUuid.value = props.patient.treatment.uuid;
        // calculate the duration of the treatment
        duration.value = Math.round(
            (new Date(props.patient.treatment.ends_at) -
                new Date(props.patient.treatment.starts_at)) /
                (24 * 60 * 60 * 1000)
        );
        questionsPerDay.value = props.patient.treatment.questions_per_day;

        answeredNotifications.value =
            props.patient.treatment.notifications.filter(
                (notification) => !!notification.response
            );
    }

    console.log(props.patient);
});

const onStartTreatment = async () => {
    const { data } = await axios.post("/api/treatments", {
        patient_uuid: props.patient.uuid,
        user_uuid: localStorage.getItem("token"),
        duration: duration.value,
        questions_per_day: questionsPerDay.value,
    });

    treatmentUuid = data.uuid;

    router.visit("/patient/" + props.patient.uuid, {
        replace: true,
    });
};

const getResponse = (question, response) => {
    return response.response.find((resp) => {
        return question.options.find((option) => option === resp[0]);
    })[0];
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
        <div class="container" style="max-width: 900px">
            <div class="box" style="background-color: antiquewhite">
                <button class="button is-info" @click="back">Voltar</button>
                <h1 class="title has-text-dark">
                    Paciente: {{ patient.user.name }}
                </h1>
                <div v-if="treatmentUuid">
                    <span class="has-text-dark"
                        ><span style="font-weight: bold">
                            Código do tratamento:
                        </span>
                        {{ treatmentUuid }}</span
                    >

                    <div class="my-5">
                        <p
                            class="has-text-dark"
                            style="text-decoration: underline"
                        >
                            Configurações do tratamento (em andamento)
                        </p>
                        <div>
                            <div class="my-3">
                                <label class="has-text-dark"
                                    >Duração (em dias)</label
                                >
                                <div class="control mt-1" style="width: 80px">
                                    <input
                                        style="
                                            background-color: white;
                                            color: black;
                                        "
                                        class="input"
                                        disabled
                                        type="number"
                                        v-model="duration"
                                    />
                                </div>
                            </div>
                            <div>
                                <label class="has-text-dark"
                                    >Quantidade de perguntas por dia</label
                                >
                                <div class="control mt-1" style="width: 80px">
                                    <input
                                        style="
                                            background-color: white;
                                            color: black;
                                        "
                                        class="input"
                                        disabled
                                        type="number"
                                        v-model="questionsPerDay"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <p
                            class="has-text-dark"
                            style="text-decoration: underline"
                        >
                            Notificações respondidas
                        </p>

                        <div
                            class="mt-5"
                            v-for="response in answeredNotifications"
                        >
                            <div class="has-text-dark">
                                <span style="font-weight: bold"
                                    >Respondido em:
                                    {{
                                        new Date(
                                            response.response_at
                                        ).toLocaleDateString()
                                    }}
                                    às
                                    {{
                                        response.response_at
                                            .split("T")[1]
                                            .split(".")[0]
                                    }}
                                </span>

                                <div class="mt-2"></div>

                                <div
                                    class="mb-1"
                                    v-for="question in response.questions"
                                >
                                    {{ question.question }}

                                    {{ getResponse(question, response) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <p class="has-text-dark">
                        Esse paciente não possui nenhum tratamento ativo.
                    </p>

                    <div class="my-5">
                        <p
                            class="has-text-dark"
                            style="text-decoration: underline"
                        >
                            Configurações do tratamento
                        </p>
                        <div>
                            <div class="my-3">
                                <label class="has-text-dark"
                                    >Duração (em dias)</label
                                >
                                <div class="control mt-1" style="width: 80px">
                                    <input
                                        style="
                                            background-color: white;
                                            color: black;
                                        "
                                        class="input"
                                        type="number"
                                        v-model="duration"
                                    />
                                </div>
                            </div>
                            <div>
                                <label class="has-text-dark"
                                    >Quantidade de perguntas por dia</label
                                >
                                <div class="control mt-1" style="width: 80px">
                                    <input
                                        style="
                                            background-color: white;
                                            color: black;
                                        "
                                        class="input"
                                        type="number"
                                        v-model="questionsPerDay"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <button class="button is-success" @click="onStartTreatment">
                        Iniciar tratamento
                    </button>
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
