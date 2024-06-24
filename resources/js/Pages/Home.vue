<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { router } from "@inertiajs/vue3";
import Header from "../Components/Header.vue";

const email = ref("");
const errorMessage = ref("");

// Check for token in localStorage and redirect if present
onMounted(() => {
    const token = localStorage.getItem("token");
    if (token) {
        router.visit("/dashboard", {
            replace: true,
            data: { token },
        });
    }
});

const handleLogin = () => {
    axios
        .post("/api/login", { email: email.value })
        .then((response) => {
            localStorage.setItem("token", response.data.uuid);
            router.visit("/home");
        })
        .catch((error) => {
            console.log(error.response.data);
            errorMessage.value = error.response.data.error;
        });
};
</script>

<template>
    <Header />
    <div class="login-container">
        <div class="login-form">
            <input
                type="email"
                v-model="email"
                placeholder="Seu e-mail"
                class="input-field"
            />
            <button @click="handleLogin" class="login-button">Login</button>
        </div>

        <div v-if="errorMessage" style="color: red; margin-top: 20px">
            {{ errorMessage }}
        </div>
    </div>
</template>

<style scoped>
.login-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    background-color: #f0f0f0;
}

.login-form {
    background: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.input-field {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

.login-button {
    background-color: #2176ff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

.login-button:hover {
    background-color: #1a5dbf;
}
</style>
