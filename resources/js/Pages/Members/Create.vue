<template>
  <div>
    <Head title="Create Member" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/members">Members</Link>
      <span class="text-indigo-400 font-medium">/</span> Create
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.first_name" :error="form.errors.first_name" class="pb-8 pr-6 w-full lg:w-1/2" label="First name" />
          <text-input v-model="form.last_name" :error="form.errors.last_name" class="pb-8 pr-6 w-full lg:w-1/2" label="Last name" />
          <text-input v-model="form.email" :error="form.errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="Email" />
          <text-input v-model="form.phone" :error="form.errors.phone" class="pb-8 pr-6 w-full lg:w-1/2" label="Phone" />
          <select-input v-model="form.constituency" :error="form.errors.constituency" class="pb-8 pr-6 w-full lg:w-1/2" label="Contituency">
            <option :value="null" />
            <option v-for="constituency in constituencies" :value="constituency">{{constituency}}</option>
          </select-input>

          <text-input v-model="form.locality" :error="form.errors.locality" class="pb-8 pr-6 w-full lg:w-1/2" label="Locality" />
          <select-input v-model="form.office_id" :error="form.errors.office_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Office">
            <option :value="null" />
            <option v-for="item in offices" :value="item.id">{{item.name}}</option>
          </select-input>
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Create Member</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: Layout,
  props: {
    organizations: Array,
    offices: Array,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        first_name: '',
        last_name: '',
        constituency: null,
        email: '',
        phone: '',
        locality: '',
        office_id: '',
      }),
    }
  },
  computed: {
    constituencies() {
      return Array.from({ length: 21 }, (_, index) => index + 1);
    },
  },
  methods: {
    store() {
      this.form.post('/members')
    },
  },
}
</script>
