<template>
  <div>


    <div v-if="isAdmin" class="mb-4">
      <Link class="d-flex align-items-center py-3 text-decoration-none" href="/meetings">
        <i class="fa fa-home fa-lg me-2" :class="isUrl('meetings') ? 'text-white' : 'text-secondary'"></i>
        <div :class="isUrl('meetings') ? 'text-white' : 'text-secondary'">Meetings</div>
      </Link>
    </div>

    <div v-if="isAdmin" class="mb-4">
      <Link class="d-flex align-items-center py-3 text-decoration-none" href="/members">
        <i class="fa fa-users fa-lg me-2" :class="isUrl('members') ? 'text-white' : 'text-secondary'"></i>
        <div :class="isUrl('members') ? 'text-white' : 'text-secondary'">Members</div>
      </Link>
    </div>
<!--    <div class="mb-4">
      <Link class="d-flex align-items-center py-3 text-decoration-none" href="/posts">
        <i class="fa fa-newspaper  fa-lg me-2" :class="isUrl('posts') ? 'text-white' : 'text-secondary'"></i>
        <div :class="isUrl('posts') ? 'text-white' : 'text-secondary'">Posts</div>
      </Link>
    </div>-->
    <!-- Uncomment the sections below and update as needed -->
    <!--
    <div class="mb-4">
      <Link class="d-flex align-items-center py-3 text-decoration-none" href="/members">
        <i class="fa fa-address-book fa-lg me-2" :class="isUrl('members') ? 'text-white' : 'text-secondary'"></i>
        <div :class="isUrl('members') ? 'text-white' : 'text-secondary'">members</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="d-flex align-items-center py-3 text-decoration-none" href="/reports">
        <i class="fa fa-print fa-lg me-2" :class="isUrl('reports') ? 'text-white' : 'text-secondary'"></i>
        <div :class="isUrl('reports') ? 'text-white' : 'text-secondary'">Reports</div>
      </Link>
    </div>
    -->
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'

export default {
  components: {
    Icon,
    Link,
  },
  props: {
    auth: Object,
  },
  data() {
    return {
      //
    }
  },
  computed: {
    isAdmin() {
      // Check if 'auth.user.roles' contains the 'admin' role
      return this.auth.user && this.auth.user.roles.includes('admin');
    },
  },
  methods: {
    isUrl(...urls) {
      let currentUrl = this.$page.url.substr(1)
      if (urls[0] === '') {
        return currentUrl === ''
      }
      return urls.filter((url) => currentUrl.startsWith(url)).length
    },
  },
}
</script>
