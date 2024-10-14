<template>
  <div>
    <Head title="Create Meeting" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/meetings">Meetings</Link>
      <span class="text-indigo-400 font-medium">/</span> Create
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.title" :error="form.errors.title" class="pb-8 pr-6 w-full lg:w-1/2" label="Title" />
          <select-input v-model="form.office_id" :error="form.errors.office_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Office">
            <option :value="null" />
            <option v-for="item in offices" :value="item.id">{{item.name}}</option>
          </select-input>
          <date-input v-model="form.date" :error="form.errors.date" class="pb-8 pr-6 w-full lg:w-1/2" type="date" label="Date" />
          <file-input v-model="form.attachment_path" :error="form.errors.attachment_path" class="pb-8 pr-6 w-full lg:w-1/2" label="Attachment" />
          <textarea-input v-model="form.topic" :error="form.errors.topic" class="pb-8 pr-6 w-full lg:w-1/2" label="Topic" />
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Create Meeting</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import TextareaInput from '@/Shared/TextareaInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import FileInput from '@/Shared/FileInput.vue'
import DateInput from '@/Shared/DateInput.vue'
export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TextareaInput,
    FileInput,
    DateInput
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
        title: '',
        date: '',
        office_id: null,
        attachment_path: '',
        topic: '',
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
      this.form.post('/meetings')
    },
  },
}
</script>
