<script setup lang="ts">
import { onMounted, ref } from "vue";
import { useAuthStore } from "@/stores/auth";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import axios from "axios"
import {
  Card,
  CardContent,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
import { Button } from '@/components/ui/button'

const auth = useAuthStore();

const stats = ref<{
  total_users: number;
  admin_users: number;
  accountant_users: number
  total_vendors: number
  total_invoices: number
  invoices_draft: number
  invoices_sent: number
  invoices_paid: number
  invoices_overdue: number
} | null>(null)
const loading = ref(true)


onMounted(async () => {
  try {
    const res = await axios.get("/stats")
    stats.value = res.data
    console.log(res.data)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <AuthLayout>
    <div class="p-6 space-y-10">
      <h1 class="text-2xl font-bold mb-4">Dashboard for {{ auth.user ? auth.user.organization.name : "error org name" }}
      </h1>

      <div v-if="auth.user" class="space-y-2">
        <p>Welcome, <strong>{{ auth.user.name }}!</strong></p>
        <p>Role: <strong>{{ auth.user.role }}</strong></p>
      </div>

      <div v-else>
        <p class="text-gray-500">Loading user information...</p>
      </div>

      <div class="grid grid-cols-3 gap-3">
        <div v-if="auth.user && auth.user.role == 'admin'">
          <Card class="">
            <CardHeader>
              <CardTitle>User Overview</CardTitle>
            </CardHeader>
            <CardContent>
              <div>
                <span><strong>Total User: </strong> {{ stats ? stats.total_users : 'NULL' }}</span>
              </div>
              <div>
                <span>Admin: {{ stats ? stats.admin_users : 'NULL' }}</span>
              </div>
              <div>
                <span>Accountant: {{ stats ? stats.accountant_users : 'NULL' }}</span>
              </div>
            </CardContent>
            <CardFooter class="flex flex-col gap-2">
              <RouterLink to="/users"  class="w-full">
                <Button  class="w-full">
                  View
                </Button>
              </RouterLink>
            </CardFooter>
          </Card>
        </div>
        <div>
          <Card class="">
            <CardHeader>
              <CardTitle>Vendor Summary</CardTitle>
            </CardHeader>
            <CardContent>
              <div>
                <span><strong>Total Vendors: </strong> {{ stats ? stats.total_vendors : 'NULL' }}</span>
              </div>
            </CardContent>
            <CardFooter class="flex flex-col gap-2">
              <Button class="w-full">
                View
              </Button>
            </CardFooter>
          </Card>
        </div>
        <div>
          <Card class="">
            <CardHeader>
              <CardTitle>Invoice Snapshot</CardTitle>
            </CardHeader>
            <CardContent>
              <div>
                <span><strong>Total Invoices: </strong> {{ stats ? stats.total_invoices : 'NULL' }}</span>
              </div>
              <div>
                <span>Draft: {{ stats ? stats.invoices_draft : 'NULL' }}</span>
              </div>
              <div>
                <span>Sent: {{ stats ? stats.invoices_sent : 'NULL' }}</span>
              </div>
              <div>
                <span>Paid: {{ stats ? stats.invoices_paid : 'NULL' }}</span>
              </div>
              <div>
                <span>Overdue: {{ stats ? stats.invoices_overdue : 'NULL' }}</span>
              </div>
            </CardContent>
            <CardFooter class="flex flex-col gap-2">
              <Button class="w-full">
                View
              </Button>
            </CardFooter>
          </Card>
        </div>
      </div>

      <!-- <div v-if="loading">Loading tenants...</div>

      <div v-else>
        <h1 class="text-2xl font-bold mb-4">Organization Lists</h1>
        <table class="min-w-full border">
          <thead>
            <tr class="bg-gray-100">
              <th class="p-2 border">ID</th>
              <th class="p-2 border">Name</th>
              <th class="p-2 border">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="org in tenants" :key="org.id">
              <td class="p-2 border">{{ org.id }}</td>
              <td class="p-2 border">{{ org.name }}</td>
              <td class="p-2 border"></td>
            </tr>
          </tbody>
        </table>
      </div> -->


    </div>
  </AuthLayout>
</template>
