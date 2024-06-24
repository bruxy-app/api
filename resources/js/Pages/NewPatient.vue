<script setup>
import { ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import Header from "../Components/Header.vue";

const name = ref("");
const professionalUuid = localStorage.getItem("token");
const errorMessage = ref("");

const handleSubmit = () => {
    const form = useForm({
        name: name.value,
        professional_uuid: professionalUuid,
    });

    form.post("/api/patient", {
        onSuccess: () => {
            console.log("success");
            router.visit("/dashboard", {
                replace: true,
                data: { token: professionalUuid },
            });
        },
        onError: (errors) => {
            errorMessage.value = "Erro ao salvar o paciente.";
        },
    });
};
</script>

<template>
    <Header />
    <div class="form-container">
        <form @submit.prevent="handleSubmit" class="form">
            <div class="form-group">
                <label class="has-text-dark" for="nome">Nome:</label>
                <input
                    type="text"
                    id="nome"
                    v-model="name"
                    class="input-field"
                    required
                />
            </div>

            <button type="submit" class="submit-button">Salvar</button>

            <div v-if="errorMessage" style="color: red; margin-top: 20px">
                Erro: {{ errorMessage }}
            </div>
        </form>
    </div>
</template>

<style scoped>
.form-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    background-color: #f0f0f0;
}

.form {
    background: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.form-group {
    margin-bottom: 20px;
}

.input-field {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

.submit-button {
    background-color: #2176ff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

.submit-button:hover {
    background-color: #1a5dbf;
}
</style>
