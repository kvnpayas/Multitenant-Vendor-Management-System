<script setup lang="ts">
import { onMounted, ref } from "vue";
import { useAuthStore } from "@/stores/auth";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import axios from "axios"
import { AxiosError } from "axios";
import {
  Dialog,
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Button } from '@/components/ui/button'

interface LoginForm {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
  role: string;
}

interface ApiError {
  message: string;
  errors?: Record<string, string[]>;
}

const form = ref<LoginForm>({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
  role: "accountant",
});

const users = ref<any[]>([])
const loading = ref(true)
const openCreateDialog = ref(false)
const openEditDialog = ref(false)
const error = ref<ApiError | null>(null);
const selectedUser = ref<any | null>(null)

const isDialogCreateOpen = () => {
  openCreateDialog.value = true
}
const isDialogEditOpen = (id: number) => {
  const user = users.value.find(u => u.id === id)

  if (user) {
    selectedUser.value = { ...user }
    openEditDialog.value = true
  } else {
    console.warn("User not found with id:", id)
  }

  openEditDialog.value = true
}

onMounted(async () => {
  try {
    const res = await axios.get("/users")
    users.value = res.data.data

  } finally {
    loading.value = false
  }
})

async function createForm(): Promise<void> {
  error.value = null;

  try {
    await axios.post('/register', form.value);
    const res = await axios.get("/users");
    users.value = res.data.data;
    openCreateDialog.value = false
  } catch (err) {
    const axiosError = err as AxiosError<ApiError>;
    if (axiosError.response) {
      error.value = axiosError.response.data;
    } else {
      error.value = { message: "Unexpected error occurred" };
    }
  }
}
</script>

<template>
  <AuthLayout>
    <div class="p-6 space-y-10">
      <div class="flex justify-between">
        <div>
          <h1 class="text-2xl font-bold mb-4">User Maintenance
          </h1>
        </div>
        <div>
          <Button @click="isDialogCreateOpen">Create User</Button>

        </div>
      </div>


      <div v-if="loading">Loading users...</div>

      <div v-else>
        <h1 class="text-2xl font-bold mb-4">User Lists</h1>
        <table class="min-w-full border">
          <thead>
            <tr class="bg-gray-100">
              <th class="p-2 border">ID</th>
              <th class="p-2 border">Name</th>
              <th class="p-2 border">Email</th>
              <th class="p-2 border">Role</th>
              <th class="p-2 border">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users" :key="user.id">
              <td class="p-2 border">{{ user.id }}</td>
              <td class="p-2 border">{{ user.name }}</td>
              <td class="p-2 border">{{ user.email }}</td>
              <td class="p-2 border">{{ user.role }}</td>
              <td class="p-2 border"><Button @click="isDialogEditOpen(user.id)">Edit</Button></td>
            </tr>
          </tbody>
        </table>
      </div>


    </div>

    <!-- Create Dialog -->
    <Dialog v-model:open="openCreateDialog">
      <DialogContent class="sm:max-w-[425px]">
        <DialogHeader>
          <DialogTitle>Create User</DialogTitle>
          <DialogDescription>
            Create new users
          </DialogDescription>
        </DialogHeader>
        <form @submit.prevent="createForm">
          <div class="grid gap-4">
            <div class="grid gap-3">
              <Label for="name">Name</Label>
              <Input v-model="form.name" />
              <p v-if="error?.errors?.name" class="text-red-500 mt-2">{{ error.errors ? error.errors.name[0] : '' }}</p>
            </div>
            <div class="grid gap-3">
              <Label for="email">Email</Label>
              <Input v-model="form.email" />
              <p v-if="error?.errors?.email" class="text-red-500 mt-2">{{ error.errors ? error.errors.email[0] : '' }}
              </p>
            </div>
            <div class="grid gap-3">
              <Label for="password">Confirm Password</Label>
              <Input type="password" v-model="form.password" />
              <p v-if="error?.errors?.password" class="text-red-500 mt-2">{{ error.errors ? error.errors.password[0] :
                '' }}</p>
            </div>
            <div class="grid gap-3">
              <Label for="password">Password</Label>
              <Input type="password" v-model="form.password_confirmation" />
              <!-- <p v-if="error?.errors?.password" class="text-red-500 mt-2">{{ error.errors ? error.errors.password[0] : '' }}</p> -->
            </div>
            <div class="grid gap-3">
              <Label for="role">Role</Label>
              <Select v-model="form.role">
                <SelectTrigger class="w-[180px]">
                  <SelectValue placeholder="Select a role" />
                </SelectTrigger>
                <SelectContent>
                  <SelectGroup>
                    <SelectLabel>Role</SelectLabel>
                    <SelectItem value="accountant">
                      Accountant
                    </SelectItem>
                    <SelectItem value="admin">
                      Admin
                    </SelectItem>
                  </SelectGroup>
                </SelectContent>
              </Select>
              <p v-if="error?.errors?.role" class="text-red-500 mt-2">{{ error.errors ? error.errors.role[0] : '' }}</p>
            </div>
          </div>

          <DialogFooter>
            <DialogClose as-child>
              <Button variant="outline">
                Cancel
              </Button>
            </DialogClose>
            <Button type="submit">
              Submit
            </Button>
          </DialogFooter>
        </form>
      </DialogContent>
    </Dialog>

    <!-- Edit Dialog -->
    <Dialog v-model:open="openEditDialog">
      <DialogContent class="sm:max-w-[425px]">
        <DialogHeader>
          <DialogTitle>Edit User</DialogTitle>
          <DialogDescription>
            Update user
          </DialogDescription>
        </DialogHeader>
        <form @submit.prevent="createForm">
          <div class="grid gap-4">
            <div class="grid gap-3">
              <Label for="name">Name</Label>
              <Input v-model="selectedUser.name" />
              <p v-if="error?.errors?.name" class="text-red-500 mt-2">{{ error.errors ? error.errors.name[0] : '' }}</p>
            </div>
            <div class="grid gap-3">
              <Label for="email">Email</Label>
              <Input v-model="selectedUser.email" />
              <p v-if="error?.errors?.email" class="text-red-500 mt-2">{{ error.errors ? error.errors.email[0] : '' }}
              </p>
            </div>
            <div class="grid gap-3">
              <Label for="password">Confirm Password</Label>
              <Input type="password" v-model="selectedUser.password" />
              <p v-if="error?.errors?.password" class="text-red-500 mt-2">{{ error.errors ? error.errors.password[0] :
                '' }}</p>
            </div>
            <div class="grid gap-3">
              <Label for="password">Password</Label>
              <Input type="password" v-model="selectedUser.password_confirmation" />
              <!-- <p v-if="error?.errors?.password" class="text-red-500 mt-2">{{ error.errors ? error.errors.password[0] : '' }}</p> -->
            </div>
            <div class="grid gap-3">
              <Label for="role">Role</Label>
              <Select v-model="selectedUser.role">
                <SelectTrigger class="w-[180px]">
                  <SelectValue placeholder="Select a role" />
                </SelectTrigger>
                <SelectContent>
                  <SelectGroup>
                    <SelectLabel>Role</SelectLabel>
                    <SelectItem value="accountant">
                      Accountant
                    </SelectItem>
                    <SelectItem value="admin">
                      Admin
                    </SelectItem>
                  </SelectGroup>
                </SelectContent>
              </Select>
              <p v-if="error?.errors?.role" class="text-red-500 mt-2">{{ error.errors ? error.errors.role[0] : '' }}</p>
            </div>
          </div>

          <DialogFooter>
            <DialogClose as-child>
              <Button variant="outline">
                Cancel
              </Button>
            </DialogClose>
            <Button type="submit">
              Submit
            </Button>
          </DialogFooter>
        </form>
      </DialogContent>
    </Dialog>
  </AuthLayout>
</template>
