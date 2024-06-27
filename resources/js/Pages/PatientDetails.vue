<script setup>
import { ref, onMounted, nextTick } from "vue";
import { router } from "@inertiajs/vue3";
import Header from "../Components/Header.vue";
import axios from "axios";
import ApexCharts from "apexcharts";

const props = defineProps({
    patient: Object,
});

let treatmentUuid = ref("");
let duration = ref(7);
let questionsPerDay = ref(8);
let answeredNotifications = ref([]);
let minimumPercentage = ref(70);
let errorMessage = ref(null);
let groupedQuestions = ref({});

onMounted(async () => {
    if (props.patient.treatment) {
        treatmentUuid.value = props.patient.treatment.uuid;
        duration.value = props.patient.treatment.duration;
        questionsPerDay.value = props.patient.treatment.questions_per_day;

        answeredNotifications.value =
            props.patient.treatment.notifications.filter(
                (notification) => !!notification.response
            );
        minimumPercentage.value = props.patient.treatment.minimum_percentage;

        groupQuestions();
        await nextTick();
        createCharts();
    }
});

const onStartTreatment = async () => {
    try {
        const { data } = await axios.post("/api/treatments", {
            patient_uuid: props.patient.uuid,
            user_uuid: localStorage.getItem("token"),
            duration: duration.value,
            questions_per_day: questionsPerDay.value,
            minimum_percentage: minimumPercentage.value,
        });
        treatmentUuid.value = data.uuid;

        router.visit("/patient/" + props.patient.uuid, {
            replace: true,
        });
    } catch (error) {
        errorMessage.value = error;
    }
};

const getResponse = (question, response) => {
    return response.response.find((resp) => {
        return question.options.find((option) => option === resp[0]);
    })[0];
};

const updateMinimumPercentage = (event) => {
    if (event && event.target.value > 100) {
        event.target.value = 100;
    } else if (event && event.target.value < 0) {
        event.target.value = 0;
    }

    minimumPercentage.value = parseInt(event.target.value);
};

const updateQuestionsPerDay = (event) => {
    if (event && event.target.value < 0) {
        event.target.value = 1;
    }

    questionsPerDay.value = event.target.value;
};

const back = () => {
    router.visit("/dashboard", {
        replace: true,
        data: { token: localStorage.getItem("token") },
    });
};

const groupQuestions = () => {
    groupedQuestions.value = {};

    answeredNotifications.value.forEach((response) => {
        response.questions.forEach((question) => {
            if (!groupedQuestions.value[question.question]) {
                groupedQuestions.value[question.question] = {
                    options: question.options,
                    responses: [],
                };
            }

            groupedQuestions.value[question.question].responses.push(
                getResponse(question, response)
            );
        });
    });
};

const createCharts = () => {
    Object.keys(groupedQuestions.value).forEach((question, index) => {
        const questionData = groupedQuestions.value[question];
        const seriesData = questionData.options.map((option) => {
            return questionData.responses.filter(
                (response) => response === option
            ).length;
        });

        const options = {
            chart: {
                type: "pie",
                height: 350,
            },
            series: seriesData,
            labels: questionData.options,
        };

        const chart = new ApexCharts(
            document.querySelector(`#chart-${index}`),
            options
        );
        chart.render();
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

                <div v-if="errorMessage" class="mb-5">
                    <span class="has-text-danger has-text-weight-bold is-size-5"
                        >Erro: {{ errorMessage }}</span
                    >
                </div>

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
                            <div class="mt-3">
                                <label class="has-text-dark"
                                    >Porcentagem mínima de notificações
                                    respondidas</label
                                >
                                <div
                                    class="control mt-1 percentage"
                                    style="width: 80px"
                                >
                                    <input
                                        style="
                                            background-color: white;
                                            color: black;
                                        "
                                        class="input"
                                        disabled
                                        type="number"
                                        v-model="minimumPercentage"
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
                            v-for="(
                                response, responseIndex
                            ) in answeredNotifications"
                        >
                            <div class="has-text-dark">
                                <span style="font-weight: bold"
                                    >Respondido em:
                                    {{ response.response_at }}
                                </span>

                                <div class="mt-2"></div>

                                <div
                                    class="mb-1"
                                    v-for="(
                                        question, questionIndex
                                    ) in response.questions"
                                >
                                    {{ question.question }}

                                    {{ getResponse(question, response) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <p
                            class="has-text-dark"
                            style="text-decoration: underline"
                        >
                            Gráficos de Respostas
                        </p>
                        <div
                            class="charts-container"
                            v-for="(question, index) in Object.keys(
                                groupedQuestions
                            )"
                            :key="index"
                        >
                            <h3 class="has-text-dark mb-2">{{ question }}</h3>
                            <div :id="`chart-${index}`" class="chart"></div>
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
                                        min="0"
                                        :value="questionsPerDay"
                                        @input="updateQuestionsPerDay($event)"
                                    />
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="has-text-dark"
                                    >Porcentagem mínima de notificações
                                    respondidas</label
                                >
                                <div
                                    class="control mt-1 percentage"
                                    style="width: 80px"
                                >
                                    <input
                                        style="
                                            background-color: white;
                                            color: black;
                                        "
                                        class="input"
                                        type="number"
                                        min="0"
                                        :value="minimumPercentage"
                                        @input="updateMinimumPercentage($event)"
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

.percentage::after {
    content: "%";
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: black;
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield;
}

.charts-container {
    margin-bottom: 20px;
    text-align: center;
}

.chart {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    margin: 0 auto;
}
</style>
