<template>
    <div>
      <Head title="Meetings" />
      <h1 class="mb-8 text-3xl font-bold">Meetings</h1>
      <div class="flex items-center justify-between mb-6">
        <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
          <label class="block text-gray-700">Office:</label>
          <select v-model="form.office_id" class="form-select mt-1 w-full">
            <option :value="null" />
            <option v-for="item in offices" :value="item.id">{{item.name}}</option>
          </select>
          <label class="block text-gray-700 mt-3">Date:</label>
          <date-input  type="date" v-model="form.date" class="mt-1 w-full"/>
        </search-filter>
        <Link class="btn-indigo" href="/meetings/create">
          <span>New</span>
          <span class="hidden md:inline">&nbsp;Meeting</span>
        </Link>
      </div>
      <div class="bg-white rounded-md shadow overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <tr class="text-left font-bold">
            <th class="pb-4 pt-6 px-6">Title</th>
            <th class="pb-4 pt-6 px-6">Office</th>
            <th class="pb-4 pt-6 px-6">Date</th>
            <th class="pb-4 pt-6 px-6">Topic</th>
            <th class="pb-4 pt-6 px-6">Attachment</th>
          </tr>
          <tr v-for="item in meetings.data" :key="item.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
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
          <tr v-if="meetings.data.length === 0">
            <td class="px-6 py-4 border-t" colspan="4">No meetings found.</td>
          </tr>
        </table>
      </div>
      <pagination class="mt-6" :links="meetings.links" />
    </div>
  </template>
  
  <script>
  import { Head, Link } from '@inertiajs/vue3'
  import Icon from '@/Shared/Icon.vue'
  import pickBy from 'lodash/pickBy'
  import Layout from '@/Shared/Layout.vue'
  import throttle from 'lodash/throttle'
  import mapValues from 'lodash/mapValues'
  import Pagination from '@/Shared/Pagination.vue'
  import SearchFilter from '@/Shared/SearchFilter.vue'
  import DateInput from '@/Shared/DateInput.vue'
  export default {
    components: {
      Head,
      Icon,
      Link,
      Pagination,
      SearchFilter,
      DateInput
    },
    layout: Layout,
    props: {
      filters: Object,
      meetings: Object,
      offices: Array,
    },
    data() {
      return {
        form: {
          search: this.filters.search,
          date : this.filters.date,
          office_id : this.filters.office_id
        },
      }
    },
    computed: {
      constituencies() {
        return Array.from({ length: 21 }, (_, index) => index + 1);
      },
    },
    watch: {
      form: {
        deep: true,
        handler: throttle(function () {
          this.$inertia.get('/meetings', pickBy(this.form), { preserveState: true })
        }, 150),
      },
    },
    methods: {
      reset() {
        this.form = mapValues(this.form, () => null)
      },
    },
  }
  </script>
  