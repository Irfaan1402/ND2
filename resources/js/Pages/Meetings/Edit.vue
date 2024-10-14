<template>
  <div>
    <Head :title="`${form.title}`" />
    <h1 class="mb-8 text-3xl font-bold">
      {{ form.title }}  <i class="fa fa-lg me-2" :class="showDetails ? 'fa-chevron-up' : 'fa-chevron-down'" @click="showDetails = !showDetails" ></i>
    </h1>
    <div v-if="showDetails" class="bg-white rounded-md shadow overflow-hidden col-sm-12">
      <form @submit.prevent="update">
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
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update meeting</loading-button>
        </div>
      </form>
    </div>
  </div>
  <h1 class="mb-8 text-3xl font-bold mt-10">
      Participants <a :href="`/attendance/${meeting.id}`"><i class="fa fa-plus-circle fa-lg me-2"></i></a>
  </h1>
  <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Name</th>
          <th class="pb-4 pt-6 px-6">Email</th>
          <th class="pb-4 pt-6 px-6">Phone</th>
          <th class="pb-4 pt-6 px-6">Locality</th>
          <th class="pb-4 pt-6 px-6">Constituency</th>
        </tr>
        <tr v-for="contact in members.data" :key="contact.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/members/${contact.id}/edit`">
              {{ contact.name }}
              <icon v-if="contact.deleted_at" name="trash" class="shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <span class="flex items-center px-6 py-4">
              {{ contact.email }}
            </span>
          </td>
          <td class="border-t">
            <span class="flex items-center px-6 py-4">
              {{ contact.phone }}
            </span>
          </td>
          <td class="border-t">
            <span class="flex items-center px-6 py-4">
              {{ contact.locality }}
            </span>
          </td>
          <td class="border-t">
            <span class="flex items-center px-6 py-4">
              {{ contact.constituency }}
            </span>
          </td>
          
          <td class="w-px border-t">
            <Link class="flex items-center px-4" :href="`/members/${contact.id}/edit`" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="members.data.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No members found.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="members.links" />
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
import TrashedMessage from '@/Shared/TrashedMessage.vue'
import Pagination from '@/Shared/Pagination.vue'
export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TextareaInput,
    FileInput,
    DateInput,
    TrashedMessage,
    Pagination
  },
  layout: Layout,
  props: {
    meeting: Object,
    offices: Array,
    members: Array,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        title: this.meeting.title,
        date: this.meeting.date,
        office_id: this.meeting.office_id,
        attachment_path: this.meeting.attachment_path,
        topic: this.meeting.topic,
      }),
      showDetails : false,
    }
  },
  computed: {
  },
  methods: {
    update() {
      this.form.put(`/meetings/${this.meeting.id}`)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this meeting?')) {
        this.$inertia.delete(`/meetings/${this.meeting.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this meeting?')) {
        this.$inertia.put(`/meetings/${this.meeting.id}/restore`)
      }
    },
  },
}
</script>
