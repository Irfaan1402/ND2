<template>
  <div>
    <Head :title="`${form.first_name} ${form.last_name}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/members">Members</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.first_name }} {{ form.last_name }}
    </h1>
    <trashed-message v-if="member.deleted_at" class="mb-6" @restore="restore"> This member has been deleted. </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.first_name" :error="form.errors.first_name" class="pb-8 pr-6 w-full lg:w-1/2" label="First name" />
          <text-input v-model="form.last_name" :error="form.errors.last_name" class="pb-8 pr-6 w-full lg:w-1/2" label="Last name" />
          <text-input v-model="form.email" :error="form.errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="Email" />
          <text-input v-model="form.phone" :error="form.errors.phone" class="pb-8 pr-6 w-full lg:w-1/2" label="Phone" />
          <select-input v-model="form.constituency" :error="form.errors.constituency" class="pb-8 pr-6 w-full lg:w-1/2" label="Constituency">
            <option :value="null" />
            <option v-for="constituency in constituencies" :value="constituency">{{constituency}}</option>
          </select-input>
          <text-input v-model="form.locality" :error="form.errors.locality" class="pb-8 pr-6 w-full lg:w-1/2" label="Locality" />
          <select-input v-model="form.office_id" :error="form.errors.office_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Office">
            <option :value="null" />
            <option v-for="item in offices" :key="item.id" :value="item.id">{{ item.name }}</option>
          </select-input>

          
         
         
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!member.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete member</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update member</loading-button>
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
import TrashedMessage from '@/Shared/TrashedMessage.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    member: Object,
    offices: Array,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        first_name: this.member.first_name,
        last_name: this.member.last_name,
        office_id: this.member.office_id,
        email: this.member.email,
        phone: this.member.phone,
        locality: this.member.locality,
        constituency: this.member.constituency,
      }),
    }
  },
  computed: {
    constituencies() {
      return Array.from({ length: 21 }, (_, index) => index + 1);
    },
  },
  methods: {
    update() {
      this.form.put(`/members/${this.member.id}`)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this member?')) {
        this.$inertia.delete(`/members/${this.member.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this member?')) {
        this.$inertia.put(`/members/${this.member.id}/restore`)
      }
    },
  },
}
</script>
