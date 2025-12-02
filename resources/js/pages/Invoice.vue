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
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";

const auth = useAuthStore();

interface InvoiceForm {
  vendor_id: number | null;
  amount: number | null;
  due_date: string;
  status: string;
}

interface ApiError {
  message: string;
  errors?: Record<string, string[]>;
}

const invoices = ref<any[]>([]);
const vendors = ref<any[]>([]); // for dropdown
const loading = ref(true);
const openCreateDialog = ref(false);
const openEditDialog = ref(false);
const error = ref<ApiError | null>(null);

const form = ref<InvoiceForm>({
  vendor_id: null,
  amount: null,
  status: "pending",
});
const formEdit = ref<InvoiceForm>({
  vendor_id: null,
  amount: null,
  status: "",
});

const isDialogCreateOpen = () => {
  openCreateDialog.value = true;
};

onMounted(async () => {
  await fetchInvoices();
  await fetchVendors();
});

async function fetchInvoices() {
  try {
    const res = await axios.get("/invoices");
    invoices.value = res.data.data;
  } finally {
    loading.value = false;
  }
}

async function fetchVendors() {
  const res = await axios.get("/vendors");
  vendors.value = res.data.data;
}

async function createInvoice(): Promise<void> {
  error.value = null;
  try {
    await axios.post("/invoices", form.value);
    openCreateDialog.value = false;
    form.value = { vendor_id: null, amount: null, due_date: "", status: "pending" };
    await fetchInvoices();
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
  const invoice = invoices.value.find((u) => u.id === id);

  if (invoice) {
    formEdit.value = { ...invoice };
    openEditDialog.value = true;
  } else {
    console.warn("Vendor not found with id:", id);
  }

  openEditDialog.value = true;
};

async function editForm(): Promise<void> {
  error.value = null;
  const data = {
    vendor_id: formEdit.value.vendor_id,
    amount: formEdit.value.amount,
    status: formEdit.value.status,
  };

  try {
    await axios.put(`/invoices/${formEdit.value.id}`, data);
    openEditDialog.value = false;

    await fetchInvoices();
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
  try {
    await axios.delete(`/invoices/${id}`);

    await fetchInvoices();
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
        <h1 class="text-2xl font-bold mb-4">Invoice Maintenance</h1>
        <Button v-if="auth && auth.user.role == 'admin'" @click="isDialogCreateOpen"
          >Create Invoice</Button
        >
      </div>

      <div v-if="loading">Loading invoices...</div>

      <div v-else>
        <h2 class="text-xl font-bold mb-4">Invoice List</h2>
        <table class="min-w-full border">
          <thead>
            <tr class="bg-gray-100">
              <th class="p-2 border">ID</th>
              <th class="p-2 border">Vendor</th>
              <th class="p-2 border">Amount</th>
              <th class="p-2 border">Status</th>
              <th class="p-2 border">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="invoice in invoices" :key="invoice.id">
              <td class="p-2 border">{{ invoice.id }}</td>
              <td class="p-2 border">{{ invoice.vendor?.name }}</td>
              <td class="p-2 border">{{ invoice.amount }}</td>
              <td class="p-2 border">{{ invoice.status }}</td>
              <td class="p-2 border flex gap-4">
                <Button @click="isDialogEditOpen(invoice.id)">Edit</Button>
                <div v-if="auth && auth.user.role == 'admin'">
                  <Button variant="destructive" @click="deleteForm(invoice.id)"
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
          <DialogTitle>Create Invoice</DialogTitle>
          <DialogDescription>Fill in invoice details</DialogDescription>
        </DialogHeader>
        <form @submit.prevent="createInvoice" class="grid gap-4">
          <div class="grid gap-3">
            <Label for="vendor">Vendor</Label>
            <Select v-model="form.vendor_id">
              <SelectTrigger class="w-[180px]">
                <SelectValue placeholder="Select a vendor" />
              </SelectTrigger>
              <SelectContent>
                <SelectGroup>
                  <SelectLabel>Vendors</SelectLabel>
                  <SelectItem
                    v-for="vendor in vendors"
                    :key="vendor.id"
                    :value="vendor.id"
                  >
                    {{ vendor.name }}
                  </SelectItem>
                </SelectGroup>
              </SelectContent>
            </Select>
            <p v-if="error?.errors?.vendor_id" class="text-red-500 mt-2">
              {{ error.errors.vendor_id[0] }}
            </p>
          </div>

          <div class="grid gap-3">
            <Label for="amount">Amount</Label>
            <Input type="number" v-model="form.amount" />
            <p v-if="error?.errors?.amount" class="text-red-500 mt-2">
              {{ error.errors.amount[0] }}
            </p>
          </div>

          <div class="grid gap-3">
            <Label for="status">Status</Label>
            <Select v-model="form.status">
              <SelectTrigger class="w-[180px]">
                <SelectValue placeholder="Select status" />
              </SelectTrigger>
              <SelectContent>
                <SelectGroup>
                  <SelectLabel>Status</SelectLabel>
                  <SelectItem value="pending">Pending</SelectItem>
                  <SelectItem value="sent">Sent</SelectItem>
                  <SelectItem value="paid">Paid</SelectItem>
                  <SelectItem value="overdue">Overdue</SelectItem>
                </SelectGroup>
              </SelectContent>
            </Select>
            <p v-if="error?.errors?.status" class="text-red-500 mt-2">
              {{ error.errors.status[0] }}
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
          <DialogTitle>Update Invoice</DialogTitle>
          <DialogDescription>Fill in invoice details</DialogDescription>
        </DialogHeader>
        <form @submit.prevent="editForm" class="grid gap-4">
          <div class="grid gap-3">
            <Label for="vendor">Vendor</Label>
            <Select v-model="formEdit.vendor_id" :disabled="auth && auth.user.role === 'accountant'">
              <SelectTrigger class="w-[180px]">
                <SelectValue placeholder="Select a vendor" />
              </SelectTrigger>
              <SelectContent>
                <SelectGroup>
                  <SelectLabel>Vendors</SelectLabel>
                  <SelectItem
                    v-for="vendor in vendors"
                    :key="vendor.id"
                    :value="vendor.id"
                  >
                    {{ vendor.name }}
                  </SelectItem>
                </SelectGroup>
              </SelectContent>
            </Select>
            <p v-if="error?.errors?.vendor_id" class="text-red-500 mt-2">
              {{ error.errors.vendor_id[0] }}
            </p>
          </div>

          <div class="grid gap-3">
            <Label for="amount">Amount</Label>
            <Input type="number" v-model="formEdit.amount" :disabled="auth && auth.user.role === 'accountant'"/>
            <p v-if="error?.errors?.amount" class="text-red-500 mt-2">
              {{ error.errors.amount[0] }}
            </p>
          </div>

          <div class="grid gap-3">
            <Label for="status">Status</Label>
            <Select v-model="formEdit.status">
              <SelectTrigger class="w-[180px]">
                <SelectValue placeholder="Select status" />
              </SelectTrigger>
              <SelectContent>
                <SelectGroup>
                  <SelectLabel>Status</SelectLabel>
                  <SelectItem value="pending">Pending</SelectItem>
                  <SelectItem value="sent">Sent</SelectItem>
                  <SelectItem value="paid">Paid</SelectItem>
                  <SelectItem value="overdue">Overdue</SelectItem>
                </SelectGroup>
              </SelectContent>
            </Select>
            <p v-if="error?.errors?.status" class="text-red-500 mt-2">
              {{ error.errors.status[0] }}
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
  </AuthLayout>
</template>
