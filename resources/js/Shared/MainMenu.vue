<template>
  <div>
    <div class="mb-4">
      <Link class="d-flex align-items-center py-3 text-decoration-none" href="/meetings">
        <i class="fa fa-home fa-lg me-2" :class="isUrl('') ? 'text-white' : 'text-secondary'"></i>
        <div :class="isUrl('') ? 'text-white' : 'text-secondary'">Meetings</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="d-flex align-items-center py-3 text-decoration-none" href="/members">
        <i class="fa fa-users fa-lg me-2" :class="isUrl('organizations') ? 'text-white' : 'text-secondary'"></i>
        <div :class="isUrl('members') ? 'text-white' : 'text-secondary'">Members</div>
      </Link>
    </div>
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
