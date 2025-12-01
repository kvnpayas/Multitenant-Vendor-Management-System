<script setup lang="ts">
import { onMounted, ref } from "vue";
import { useAuthStore } from "@/stores/auth";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { router } from "@inertiajs/vue3";

const auth = useAuthStore();
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
  <AuthLayout>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4">Dashboard</h1>

      <div v-if="auth.user" class="space-y-2">
        <p><strong>Email:</strong> {{ auth.user.email }}</p>
        <p><strong>Organization:</strong> {{ auth.user.organization_id }}</p>
        <p><strong>Role:</strong> {{ auth.user.role }}</p>
      </div>

      <div v-else>
        <p class="text-gray-500">Loading user information...</p>
      </div>
    </div>
  </AuthLayout>
</template>
