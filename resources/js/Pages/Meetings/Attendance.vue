<template>
  <div class="main-container d-flex flex-column align-items-center justify-content-center">
    <div class="bg-white rounded-md shadow overflow-hidden col-sm-12 w-full p-4">
      <p class="text-muted mb-4">Title: <b>{{ meeting.title }}</b></p>
      <p class="text-muted mb-4">Date: <b>{{ meeting.date }}</b></p>
      <p class="text-muted mb-4">Office: <b>{{ meeting.office }}</b></p>
    </div>
    <div class="content-wrapper text-center p-4 mt-10">
      <template v-if="!addNew">
        <input
          type="text"
          v-model="searchQuery"
          class="form-control form-control-lg"
          placeholder="Type your name..."
          aria-label="Search"
          aria-describedby="button-addon2"
        />
        <div class="user-list mt-4">
          <div v-for="user in filteredUsers" :key="user.id" class="user-item d-flex align-items-center mb-2">
            <label :for="`user-${user.id}`" class="form-check-label">{{ user.name }}</label>
            <button 
              :class="['btn', isUserRegistered(user.id) ? 'btn-secondary' : 'btn-success', 'ml-10']" 
              :disabled="isUserRegistered(user.id)"
              @click="openModal('confirmIdentityModal', user.id)"
            >
              <i class="fa fa-check"></i>
            </button>
          </div>
        </div>
      </template>
      <template v-else>
        <form @submit.prevent="addToMeeting">
          <input type="hidden" v-model="form.id" class="form-control form-control-lg mb-3" placeholder="id" />
          <input type="text" v-model="form.first_name" class="form-control form-control-lg mb-3" placeholder="First Name" />
          <input type="text" v-model="form.last_name" class="form-control form-control-lg mb-3" placeholder="Last Name" />
          <input type="text" v-model="form.email" class="form-control form-control-lg mb-3" placeholder="Email" />
          <input type="text" v-model="form.locality" class="form-control form-control-lg mb-3" placeholder="Locality" />
          <input type="text" v-model="form.phone" class="form-control form-control-lg mb-3" placeholder="Phone" />
          <input type="text" v-model="form.constituency" class="form-control form-control-lg mb-3" placeholder="Constituency" />
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </template>
    </div>

    <div class="button-container mt-4">
      <button v-if="!addNew" class="btn btn-primary" @click="addNew = true">New Member</button>
      <button 
        v-if="!addNew" 
        class="btn btn-success ml-3" 
        @click="() => this.$inertia.visit(`/meetings/${meeting.id}/edit`)"
      >
        End
      </button>
      <button v-if="addNew" class="btn btn-danger ml-3" @click="addNew = false">Cancel</button>
    </div>

    <!-- Bootstrap Toast -->
    <div id="toastsContainerTopRight" class="toasts-top-right fixed">
      <div class="toast bg-success fade show" role="alert" aria-live="assertive" aria-atomic="true" v-if="showToast">
        <div class="toast-body">
          {{ toastMessage }}
        </div>
      </div>
    </div>
  </div>

  <modal :modal-id="'confirmIdentityModal'" :title="'Confirm Identity'" :submit-form="addToMeeting" :cancel="'No'" :confirm="'Yes'">
    <p><b>Name</b> : {{ selectedMember.name }}</p>
    <p><b>Email</b> : {{ selectedMember.email }}</p>
    <p><b>Locality</b> : {{ selectedMember.locality }}</p>
  </modal>
</template>

<script>
import Modal from '@/Shared/Modal.vue';
import axios from 'axios';

export default {
  components: {
    Modal,
  },
  props: {
    meeting: Object, // Assuming meeting is a single object
    participants: {
      type: Array,
      default: () => []
    },
    members: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      searchQuery: '',
      addNew: false,
      form: this.$inertia.form({
        id: null,
        first_name: null,
        last_name: null,
        email: null,
        phone: null,
        locality: null,
        constituency: null,
      }),
      selectedMembers: [],
      selectedMember: {},
      showToast: false,
      toastMessage: '',
    };
  },
  computed: {
    filteredUsers() {
      const query = this.searchQuery.toLowerCase();
      return this.members.filter(user => 
        user.name.toLowerCase().includes(query) // Include all users that match the search query
      );
    }
  },
  methods: {
    async addToMeeting() {
      const data = {
        memberId: this.form.id,
        memberFirstName: this.form.first_name,
        memberLastName: this.form.last_name,
        memberEmail: this.form.email,
        memberPhone: this.form.phone,
        memberLocality: this.form.locality,
        memberConstituency: this.form.constituency,
        meetingId: this.meeting.id,
      };

      try {
        const response = await axios.post('/addToMeeting', data);
        console.log('Member added to meeting:', response.data);
        this.closeModal('confirmIdentityModal');
        this.addNew = false;

        // Update the local members array to include the newly added member
        this.members.push({
          id: this.form.id,
          name: this.form.first_name + ' ' + this.form.last_name,
          email: this.form.email,
          locality: this.form.locality,
        });

        // Update participants array locally
        this.participants.push({
          id: this.form.id,
          name: this.form.first_name + ' ' + this.form.last_name,
          email: this.form.email,
          locality: this.form.locality,
        });

        // Display the toast with the success message
        this.toastMessage = response.data.message; // Set the toast message
        this.showToast = true; // Show the toast
        setTimeout(() => {
          this.showToast = false; // Hide the toast after 3 seconds
        }, 3000);
      } catch (error) {
        console.error('Error adding member to meeting:', error.response ? error.response.data : error);
      }
    },
    closeModal(modalId) {
      const modal = document.getElementById(modalId);
      if (modal) {
        $(modal).modal('hide');
      }
    },
    openModal(modalId, memberId) {
      this.selectedMember = this.members.find(user => user.id === memberId);
      this.form.id = this.selectedMember.id;
      const modal = document.getElementById(modalId);
      if (modal) {
        $(modal).modal('show');
      }
    },
    isUserRegistered(userId) {
      return this.participants.some(participant => participant.id === userId);
    },
  },
};
</script>

<style scoped>
/* Add your styles here */
</style>



<style scoped>
/* Background gradient for the entire page */
.main-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #8a0b0b, #031934);
  padding: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

/* Content wrapper with rounded corners and shadow */
.content-wrapper {
  width: 100%;
  background: #fff; /* Add a background color for better readability */
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  padding: 20px;
  margin-left: unset !important;
  min-height: unset !important;
}

/* Header and date styling */
h1 {
  color: #333;
  font-size: 2rem;
}

p {
  font-size: 1rem;
  color: #6c757d;
}

/* Search bar customizations */
.form-control {
  border-radius: 8px;
}

/* User list styling */
.user-list {
  max-height: 300px; /* Optional: Set a max height for scrollable list */
  min-height: 300px; /* Optional: Set a max height for scrollable list */
  overflow-y: auto; /* Scroll if content exceeds max height */
}

.user-item {
  padding: 10px;
  border-bottom: 1px solid #e0e0e0;
}

.user-item:last-child {
  border-bottom: none;
}

/* Additional form styling */
.form-check-input {
  cursor: pointer;
}

.form-check-label {
  font-weight: bold;
}

/* Green button styling */
.button-container {
  margin-top: 20px;
}

.btn-success {
  background-color: #28a745;
  border-color: #28a745;
  color: #fff;
}

.btn-success:hover {
  background-color: #218838;
  border-color: #1e7e34;
}

/* Responsive adjustments */
@media (max-width: 576px) {
  .content-wrapper {
    width: 100%;
    padding: 20px;
  }
}
</style>
