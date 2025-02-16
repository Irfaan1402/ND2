<template>
  <div>
    <Head title="Members" />
    <h1 class="mb-8 text-3xl font-bold">Members</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">Constituency:</label>
        <select v-model="form.constituency" class="form-select mt-1 w-full">
          <option :value="null" />
          <option v-for="constituency in constituencies" :value="constituency">{{constituency}}</option>
        </select>
      </search-filter>
      <Link class="btn-indigo" href="/members/create">
        <span>New</span>
        <span class="hidden md:inline">&nbsp;Member</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6 cursor-pointer" @click="sortBy('first_name')">
            First Name <i :class="getSortIcon('first_name')" class="ml-2"></i>
          </th>  <th class="pb-4 pt-6 px-6 cursor-pointer" @click="sortBy('last_name')">
            Last Name <i :class="getSortIcon('last_name')" class="ml-2"></i>
          </th>
          <th class="pb-4 pt-6 px-6 cursor-pointer" @click="sortBy('email')">
            Email <i :class="getSortIcon('email')" class="ml-2"></i>
          </th>
          <th class="pb-4 pt-6 px-6 cursor-pointer" @click="sortBy('phone')">
            Phone <i :class="getSortIcon('phone')" class="ml-2"></i>
          </th>
          <th class="pb-4 pt-6 px-6 cursor-pointer" @click="sortBy('address')">
            Address <i :class="getSortIcon('address')" class="ml-2"></i>
          </th>
          <th class="pb-4 pt-6 px-6 cursor-pointer" @click="sortBy('constituency')">
            Constituency <i :class="getSortIcon('constituency')" class="ml-2"></i>
          </th>
          <th class="pb-4 pt-6 px-6 cursor-pointer" @click="sortBy('attendance')">
            Attendance <i :class="getSortIcon('attendance')" class="ml-2"></i>
          </th>
        </tr>


        <tr v-for="contact in members.data" :key="contact.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/members/${contact.id}/edit`">
              {{ contact.first_name }}
              <icon v-if="contact.deleted_at" name="trash" class="shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/members/${contact.id}/edit`">
              {{ contact.last_name }}
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
              {{ contact.address }}
            </span>
          </td>
          <td class="border-t">
            <span class="flex items-center px-6 py-4">
              {{ contact.constituency }}
            </span>
          </td>
          <td class="border-t">
            <span class="flex items-center px-6 py-4">
              {{ contact.attendance }}%
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

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
    SearchFilter,
  },
  layout: Layout,
  props: {
    filters: Object,
    members: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
        constituency : this.filters.constituency,
        sort: this.filters.sort || 'first_name',
        direction: this.filters.direction || 'asc',
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
        this.$inertia.get('/members', pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },
  methods: {
    sortBy(column) {
      if (this.form.sort === column) {
        this.form.direction = this.form.direction === 'asc' ? 'desc' : 'asc';
      } else {
        this.form.sort = column;
        this.form.direction = 'asc';
      }
      this.$inertia.get('/members', pickBy(this.form), { preserveState: true });
    },
    getSortIcon(column) {
      if (this.form.sort === column) {
        return this.form.direction === 'asc' ? 'fas fa-arrow-up' : 'fas fa-arrow-down';
      }
      return 'fas fa-sort'; // Default unsorted icon
    },
    reset() {
      this.form = mapValues(this.form, () => null)
    },
  },
}
</script>
