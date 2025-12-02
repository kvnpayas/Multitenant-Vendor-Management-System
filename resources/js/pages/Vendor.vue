<script setup lang="ts">
import { onMounted, ref } from "vue";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { useAuthStore } from "@/stores/auth";
import axios, { AxiosError } from "axios";
import {
  Dialog,
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Button } from "@/components/ui/button";

const auth = useAuthStore();

interface VendorForm {
  name: string;
}

interface ApiError {
  message: string;
  errors?: Record<string, string[]>;
}

const vendors = ref<any[]>([]);
const loading = ref(true);
const openCreateDialog = ref(false);
const openEditDialog = ref(false);
const error = ref<ApiError | null>(null);

const form = ref<VendorForm>({
  name: "",
  contact: "",
});

const formEdit = ref<VendorForm>({
  name: "",
  contact: "",
});

const isDialogCreateOpen = () => {
  openCreateDialog.value = true;
};

onMounted(async () => {
  await fetchVendors();
});

async function fetchVendors() {
  try {
    const res = await axios.get("/vendors");
    vendors.value = res.data.data;
  } finally {
    loading.value = false;
  }
}

async function createVendor(): Promise<void> {
  error.value = null;

  try {
    await axios.post("/vendors", form.value);
    openCreateDialog.value = false;
    form.value = { name: "" };
    await fetchVendors();
  } catch (err) {
    const axiosError = err as AxiosError<ApiError>;
    if (axiosError.response) {
      error.value = axiosError.response.data;
    } else {
      error.value = { message: "Unexpected error occurred" };
    }
  }
}

const isDialogEditOpen = (id: number) => {
  const vendor = vendors.value.find((u) => u.id === id);

  if (vendor) {
    formEdit.value = { ...vendor };
    openEditDialog.value = true;
  } else {
    console.warn("Vendor not found with id:", id);
  }

  openEditDialog.value = true;
};

async function editForm(): Promise<void> {
  error.value = null;
  const data = {
    name: formEdit.value.name,
    contact: formEdit.value.contact,
  };

  try {
    await axios.put(`/vendors/${formEdit.value.id}`, data);
    openEditDialog.value = false;

    await fetchVendors();
  } catch (err) {
    const axiosError = err as AxiosError<ApiError>;
    if (axiosError.response) {
      error.value = axiosError.response.data;
    } else {
      error.value = { message: "Unexpected error occurred" };
    }
  }
}
async function deleteForm(id: number): Promise<void> {
  const vendorDelete = vendors.value.find((u) => u.id === id);

  try {
    await axios.delete(`/vendors/${vendorDelete.id}`);

    await fetchVendors();
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
        <h1 class="text-2xl font-bold mb-4">Vendor Maintenance</h1>
        <Button v-if="auth && auth.user.role == 'admin'" @click="isDialogCreateOpen"
          >Create Vendor</Button
        >
      </div>

      <div v-if="loading">Loading vendors...</div>

      <div v-else>
        <h2 class="text-xl font-bold mb-4">Vendor List</h2>
        <table class="min-w-full border">
          <thead>
            <tr class="bg-gray-100">
              <th class="p-2 border">ID</th>
              <th class="p-2 border">Name</th>
              <th class="p-2 border">Contact</th>
              <th class="p-2 border">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="vendor in vendors" :key="vendor.id">
              <td class="p-2 border">{{ vendor.id }}</td>
              <td class="p-2 border">{{ vendor.name }}</td>
              <td class="p-2 border">{{ vendor.contact }}</td>
              <td class="p-2 border">
                <div v-if="auth && auth.user.role == 'admin'" class="flex gap-4">
                  <Button @click="isDialogEditOpen(vendor.id)">Edit</Button>
                  <Button variant="destructive" @click="deleteForm(vendor.id)"
                    >Delete</Button
                  >
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Create Dialog -->
    <Dialog v-model:open="openCreateDialog">
      <DialogContent class="sm:max-w-[425px]">
        <DialogHeader>
          <DialogTitle>Create Vendor</DialogTitle>
          <DialogDescription>Fill in vendor details</DialogDescription>
        </DialogHeader>
        <form @submit.prevent="createVendor" class="grid gap-4">
          <div class="grid gap-3">
            <Label for="name">Name</Label>
            <Input v-model="form.name" />
            <p v-if="error?.errors?.name" class="text-red-500 mt-2">
              {{ error.errors.name[0] }}
            </p>
          </div>
          <div class="grid gap-3">
            <Label for="contact">Contact</Label>
            <Input v-model="form.contact" />
            <p v-if="error?.errors?.contact" class="text-red-500 mt-2">
              {{ error.errors.contact[0] }}
            </p>
          </div>
          <DialogFooter>
            <DialogClose as-child>
              <Button variant="outline">Cancel</Button>
            </DialogClose>
            <Button type="submit">Submit</Button>
          </DialogFooter>
        </form>
      </DialogContent>
    </Dialog>

    <!-- Edit Dialog -->
    <Dialog v-model:open="openEditDialog">
      <DialogContent class="sm:max-w-[425px]">
        <DialogHeader>
          <DialogTitle>Edit Vendor</DialogTitle>
          <DialogDescription> Update vendor </DialogDescription>
        </DialogHeader>
        <form @submit.prevent="editForm" class="grid gap-4">
          <div class="grid gap-3">
            <Label for="name">Name</Label>
            <Input v-model="formEdit.name" />
            <p v-if="error?.errors?.name" class="text-red-500 mt-2">
              {{ error.errors ? error.errors.name[0] : "" }}
            </p>
          </div>

          <div class="grid gap-3">
            <Label for="contact">Contact</Label>
            <Input v-model="formEdit.contact" />
            <p v-if="error?.errors?.contact" class="text-red-500 mt-2">
              {{ error.errors.contact[0] }}
            </p>
          </div>

          <DialogFooter>
            <DialogClose as-child>
              <Button variant="outline"> Cancel </Button>
            </DialogClose>
            <Button type="submit"> Submit </Button>
          </DialogFooter>
        </form>
      </DialogContent>
    </Dialog>
  </AuthLayout>
</template>
