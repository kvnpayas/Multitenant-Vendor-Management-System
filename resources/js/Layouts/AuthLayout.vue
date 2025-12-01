<script setup lang="ts">
import { useAuthStore } from "@/stores/auth";
import { router } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";

const auth = useAuthStore();

function logout() {
  auth.logout();
  router.visit("/login");
}

const ready = ref(false);

onMounted(async () => {
  if (!auth.token) {
    router.visit("/login");
    return;
  }
  try {
    await auth.fetchUser();
    ready.value = true;
  } catch {
    router.visit("/login");
  }
});
</script>

<template>
  <div class="min-h-screen flex flex-col">
    <!-- Navbar -->
    <header class="bg-gray-800 text-white p-4 flex justify-between">
      <h1 class="font-bold">Vendor Management System</h1>
      <nav class="space-x-4">
        <a href="/dashboard">Dashboard</a>
        <a href="/organizations">Organizations</a>
        <a href="/users">Users</a>
        <a href="/invoices">Invoices</a>
        <button @click="logout" class="ml-4 underline">Logout</button>
      </nav>
    </header>

    <!-- Main content -->
    <main class="flex-1 p-6">
      <slot />
    </main>
  </div>
</template>
