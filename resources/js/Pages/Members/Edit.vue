<template>
  <div>
    <Head :title="`${form.first_name} ${form.last_name}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/members">Members</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.first_name }} {{ form.last_name }} <i class="fa fa-pencil-square" aria-hidden="true" @click="isEditing = !isEditing"></i>
    </h1>
    <trashed-message v-if="member.deleted_at" class="mb-6" @restore="restore"> This member has been deleted. </trashed-message>
    <div v-if="isEditing" class="row bg-white shadow overflow-hidden">
      <form @submit.prevent="update" style="width:100%;">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.first_name" :error="form.errors.first_name" class="pb-8 pr-6 w-full lg:w-1/2" label="First name" />
          <text-input v-model="form.last_name" :error="form.errors.last_name" class="pb-8 pr-6 w-full lg:w-1/2" label="Last name" />
          <text-input v-model="form.email" :error="form.errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="Email" />
          <text-input v-model="form.phone" :error="form.errors.phone" class="pb-8 pr-6 w-full lg:w-1/2" label="Phone" />
          <select-input v-model="form.constituency" :error="form.errors.constituency" class="pb-8 pr-6 w-full lg:w-1/2" label="Constituency">
            <option :value="null" />
            <option v-for="constituency in constituencies" :value="constituency">{{constituency}}</option>
          </select-input>
          <text-input v-model="form.address" :error="form.errors.locality" class="pb-8 pr-6 w-full lg:w-1/2" label="Address" />
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
    <div v-if="!isEditing" class="row bg-white shadow overflow-hidden">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input disabled v-model="form.first_name" :error="form.errors.first_name" class="pb-8 pr-6 w-full lg:w-1/2" label="First name" />
          <text-input disabled v-model="form.last_name" :error="form.errors.last_name" class="pb-8 pr-6 w-full lg:w-1/2" label="Last name" />
          <text-input disabled  v-model="form.email" :error="form.errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="Email" />
          <text-input disabled v-model="form.phone" :error="form.errors.phone" class="pb-8 pr-6 w-full lg:w-1/2" label="Phone" />
          <select-input disabled v-model="form.constituency" :error="form.errors.constituency" class="pb-8 pr-6 w-full lg:w-1/2" label="Constituency">
            <option :value="null" />
            <option v-for="constituency in constituencies" :value="constituency">{{constituency}}</option>
          </select-input>
          <text-input disabled v-model="form.address" :error="form.errors.locality" class="pb-8 pr-6 w-full lg:w-1/2" label="Address" />
          <select-input disabled v-model="form.office_id" :error="form.errors.office_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Office">
            <option :value="null" />
            <option v-for="item in offices" :key="item.id" :value="item.id">{{ item.name }}</option>
          </select-input>
          <text-input disabled  v-model="attendance"  class="pb-8 pr-6 w-full lg:w-1/2" label="Attendance" />
        </div>
    </div>
  </div>
  <h4 class="mb-8 text-3xl font-bold mt-5">
    Meetings Participated
  </h4>
  <div class="row bg-white rounded-md shadow overflow-x-auto mt-3">
    <table class="w-full whitespace-nowrap">
      <tr class="text-left font-bold">
        <th class="pb-4 pt-6 px-6">Title</th>
        <th class="pb-4 pt-6 px-6">Office</th>
        <th class="pb-4 pt-6 px-6">Date</th>
        <th class="pb-4 pt-6 px-6">Topic</th>
        <th class="pb-4 pt-6 px-6">Attachment</th>
      </tr>
      <tr v-for="item in meetingsParticipated.data" :key="item.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
        <td class="border-t">
          <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/meetings/${item.id}/edit`">
            {{ item.title }}
            <icon v-if="item.deleted_at" name="trash" class="shrink-0 ml-2 w-3 h-3 fill-gray-400" />
          </Link>
        </td>
        <td class="border-t">
              <span class="flex items-center px-6 py-4">
                {{ item.office }}
              </span>
        </td>
        <td class="border-t">
              <span class="flex items-center px-6 py-4">
                {{ item.date }}
              </span>
        </td>
        <td class="border-t">
              <span class="flex items-center px-6 py-4">
                {{ item.topic }}
              </span>
        </td>
        <td class="border-t">
              <span class="flex items-center px-6 py-4">
                {{ item.attachment }}
              </span>
        </td>

        <td class="w-px border-t">
          <Link class="flex items-center px-4" :href="`/meetings/${item.id}/edit`" tabindex="-1">
            <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
          </Link>
        </td>
      </tr>
      <tr v-if="meetingsParticipated.data.length === 0">
        <td class="px-6 py-4 border-t" colspan="4">No meetings found.</td>
      </tr>
    </table>
  </div>
  <pagination class="mt-6" :links="meetingsParticipated.links" />
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'
import Meeting from '../Meetings/Index.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
    Meeting,
  },
  layout: Layout,
  props: {
    member: Object,
    offices: Array,
    filters: Array,
    meetings: Array,
    userMeetings: Array,
  },
  remember: 'form',
  data() {
    return {
      isEditing: false,
      form: this.$inertia.form({
        first_name: this.member.first_name,
        last_name: this.member.last_name,
        office_id: this.member.office_id,
        email: this.member.email,
        phone: this.member.phone,
        address: this.member.address,
        constituency: this.member.constituency,
      }),
    }
  },
  computed: {
    attendance() {
      const totalMeetings = this.meetings.data.length;
      const participatedMeetings = this.meetingsParticipated.data.length;

      // Prevent division by zero and calculate attendance with % sign
      return totalMeetings > 0
        ? `${((participatedMeetings / totalMeetings) * 100).toFixed(2)} %`
        : '0 %';
    },

    meetingsParticipated() {
      const participatedData = this.meetings.data.filter(meeting =>
        this.userMeetings.includes(meeting.id)
      );

      // Create a structure similar to meetings
      return {
        ...this.meetings,
        data: participatedData,
        total: participatedData.length,
        from: participatedData.length > 0 ? 1 : null,
        to: participatedData.length,
        last_page: 1,
        current_page: 1,
        per_page: participatedData.length,
        first_page_url: this.meetings.first_page_url,
        last_page_url: this.meetings.last_page_url,
        next_page_url: null,
        prev_page_url: null,
      };
    },
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
