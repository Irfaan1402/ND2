<template>
  <div class="mobile-app">
    <!-- Top Banner -->
    <div class="top-banner">
      <h1>Meetings</h1>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
      <div class="icon-section">
        <button class="filter-button shortcut-button" @click="toggleFilters">
            <i class="fas fa-filter"></i>
          </button>
          <button class="shortcut-button btn btn-success" @click="openModal('addMeetingModal')">
            New Meeting
          </button>
      </div>
      <div v-if="showFilters" class="filters">
        <div class="input-group mt5" style="flex-wrap:nowrap">
          <select2 v-model="searchProgram"  class="form-control nbr">
            <option :key="0" :value="0" class="first-option">Program</option>
              <option v-for="item in programs" :key="item.id" :value="item.id">{{ item.title }}</option>
          </select2>
          <div class="input-group-append">
          <span class="input-group-text">
            <i class="fas fa-sync-alt"></i>
          </span>
          </div>
        </div>

      </div>
      <!-- Search Bar -->
      <div class="search-bar">
        <div class="input-group mt5">
          <input type="text" v-model="searchQuery" class="form-control nbr" placeholder="Search Meeting">
          <div class="input-group-append" @click="resetSearch()">
          <span class="input-group-text">
            <i class="fas fa-sync-alt"></i>
          </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="content">

    </div>


    <!-- Bottom Menu -->
    <div class="bottom-menu">
      <div class="menu-item">
        <i class="fas fa-briefcase"></i>
        <span>Leads</span>
      </div>
      <div class="menu-item">
        <i class="fas fa-calendar"></i>
        <span>Activities</span>
      </div>
      <div class="menu-item">
        <i class="fas fa-cog"></i>
        <span>Settings</span>
      </div>
    </div>
  </div>

</template>

<script>
import axios from 'axios'


export default {
  components: {
  },
  props :{
  },
  data() {
    return {
      searchQuery: '',
      leads: [],
      showFilters: false,
      showLeads: false,
      messageTimeout: null, // To keep track of the timeout
    };
  },
  mounted() {
    //this.getData();
  },
  computed: {
    filteredLeads() {
    },
  },
  
  methods: {
    toggleFilters(){
      this.showFilters = !this.showFilters;
    },
    openModal(modalId, item) {
        this.selectedData = item; 
        this.form.lead_id = item.id;
        const modal = document.getElementById(modalId)
        if (modal) {
          // Show the modal using jQuery
          $(modal).modal('show')
        }
    },
    resetSearch(){
      this.searchQuery = '';
    },
    reloadLeadList(){
      this.showLeads = false;
      setTimeout(() => {
      this.showLeads = true;
      }, 2000);
    },
    async getData() {
      try {
        const response = await axios.get(`/leadManagement`);
        // Assigning values to variables
        this.leads = response.data.leads;
        this.owners = response.data.owners;
        this.programs = response.data.programs;
        this.customers = response.data.customers;
        this.materialSpecialist = response.data.materialSpecialist;
        this.capacitySpecialist = response.data.capacitySpecialist;
        this.reloadLeadList();
      } catch (error) {
        console.error('Error fetching leads:', error);
        console.log(error.response.status); // Log HTTP status from error object
        console.log(error.response.data); // Log response data from error object
      }
    },
    closeModal(modalId) {
      const modal = document.getElementById(modalId);
      if (modal) {
      // Hide the modal using Bootstrap Modal API
      $(modal).modal('hide');
      }
    },
  },
  watch: {

  }
};
</script>

<style scoped>
  .mobile-app {
    display: flex;
    flex-direction: column;
    height: 100vh;
    background-color: #f8f9fa;
  }

  /* Top Banner Styling */
  .top-banner {
    background-color: #173052;
    color: #ffffff;
    padding: 15px;
    text-align: center;
  }

  .top-banner h1 {
    margin: 0;
    font-size: 20px;
  }

  /* Filter Section Styling */
  .filter-section {
    padding: 10px;
    background-color: #e9ecef;
  }

  .icon-section {
    display: flex;                  /* Use Flexbox for alignment */
    justify-content: space-between; /* Equal space between buttons */
    align-items: center;            /* Center the buttons vertically */
    padding: 10px;                  /* Optional: add padding for aesthetics */
  }

  .filter-button {
    background-color: #007bff;
    color: #ffffff;     
  }

  .shortcut-button {
    border: none;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
    flex: 1;                        /* Allow buttons to grow and fill space */
    margin: 0 5px;                  /* Optional: small side margins for spacing */
    text-align: center;    
  }

  .filter-button i {
    font-size: 16px;
  }

  .filters {
    margin-top: 10px;
  }

  .form-select {
    width: 100%;
    margin-bottom: 10px;
  }

  /* Main Content Styling */
  .content {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
  }


  /* Bottom Menu Styling */
  .bottom-menu {
    display: flex;
    justify-content: space-around;
    background-color: #ffffff;
    border-top: 1px solid #ddd;
    padding: 10px 0;
    position: fixed;
    width: 100%;
    bottom: 0;
    left: 0;
    z-index: 1000; /* Ensure it stays on top of other elements */
  }

  .menu-item {
    text-align: center;
    flex: 1;
  }

  .menu-item i {
    font-size: 24px;
    color: #173052;
  }

  .menu-item span {
    display: block;
    font-size: 12px;
    color: #6c757d;
    margin-top: 5px;
  }

  .search-bar {
    margin-bottom: 15px; /* Adds space between search bar and cards */
    width: 100%;         /* Ensures it takes full width */
  }

  .search-input {
    width: 100%;         /* Full width of parent element */
    padding: 8px;        /* Adds padding for better user experience */
    border: 1px solid #ccc; /* Adds border */
    border-radius: 4px;  /* Rounded corners */
    box-sizing: border-box; /* Include padding and border in element's total width */
  }

  .input-group-text {
    padding: 10px;
    border-radius: 0px;
  }

</style>
