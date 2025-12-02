<script setup lang="ts">
import type { HTMLAttributes } from "vue";
import { ref } from "vue";
import { AxiosError } from "axios";
import { cn } from "@/lib/utils";
import { useRouter } from "vue-router";
import { Button } from "@/components/ui/button";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { Field, FieldDescription, FieldGroup, FieldLabel } from "@/components/ui/field";
import { Input } from "@/components/ui/input";
import { useAuthStore } from "@/stores/auth";
import GuestLayout from "@/Layouts/GuestLayout.vue"

const props = defineProps<{
  class?: HTMLAttributes["class"];
}>();

const auth = useAuthStore();
const router = useRouter();

interface LoginForm {
  email: string;
  password: string;
}

interface ApiError {
  message: string;
}

const form = ref<LoginForm>({
  email: "",
  password: "",
});

const error = ref<string | null>(null);

async function login(): Promise<void> {
  error.value = null;

  try {
    await auth.login(form.value);
    router.push("/dashboard");
  } catch (err) {
    const axiosError = err as AxiosError<ApiError>;
    if (axiosError.response) {
      error.value = axiosError.response.data.message;
    } else {
      error.value = "Unexpected error occurred";
    }
  }
}

</script>
<template>
  <GuestLayout>
    <div class="flex min-h-svh w-full items-center justify-center p-6 md:p-10">
      <div class="w-full max-w-sm">
        <div :class="cn('flex flex-col gap-6', props.class)">
          <Card>
            <CardHeader>
              <CardTitle>Login to your account</CardTitle>
              <CardDescription>
                Enter your email below to login to your account
              </CardDescription>
            </CardHeader>
            <CardContent>
              <form @submit.prevent="login">
                <FieldGroup>
                  <Field>
                    <FieldLabel for="email"> Email </FieldLabel>
                    <Input id="email" type="email" placeholder="m@example.com" required v-model="form.email" />
                    <p v-if="error" class="text-red-500 mt-2">{{ error }}</p>
                  </Field>
                  <Field>
                    <div class="flex items-center">
                      <FieldLabel for="password"> Password </FieldLabel>
                      <a href="#" class="ml-auto inline-block text-sm underline-offset-4 hover:underline">
                        Forgot your password?
                      </a>
                    </div>
                    <Input id="password" type="password" required v-model="form.password" />
                  </Field>
                  <Field>
                    <Button type="submit"> Login </Button>
                  </Field>
                </FieldGroup>
              </form>
            </CardContent>
          </Card>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>
