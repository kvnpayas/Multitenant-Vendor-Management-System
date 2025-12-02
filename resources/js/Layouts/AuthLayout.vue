<script setup lang="ts">
import { useAuthStore } from "@/stores/auth";

const auth = useAuthStore();

function logout() {
  auth.logout();

}

</script>

<template>
  <div class="min-h-screen flex flex-col">
    <!-- Navbar -->
    <header class="bg-gray-800 text-white p-4 flex justify-between">
      <h1 class="font-bold">Vendor Management System</h1>
      <nav class="space-x-4">
        <RouterLink to="/dashboard">Dashboard</RouterLink>
        <RouterLink to="/users">Users</RouterLink>
        <RouterLink to="/vendors">Vendor</RouterLink>
        <RouterLink to="/invoices">Invoices</RouterLink>
        <button @click="logout" class="ml-4 underline">Logout</button>
      </nav>
    </header>

    <!-- Main content -->
    <main class="flex-1 p-6">
      <slot />
    </main>
  </div>
</template>
