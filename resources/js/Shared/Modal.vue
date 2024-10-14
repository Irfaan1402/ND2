<template>
    <div class="modal fade" :id="modalId" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">{{ title }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="submitForm">
            <div class="modal-body">
              <!-- Modal form content here -->
              <slot></slot>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ cancel }}</button>
              <loading-button :loading="processing" class="btn-indigo" type="submit" :disabled="isSubmitDisabled || processing">{{ confirm }}</loading-button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </template>
  <script>
    import LoadingButton from './LoadingButton.vue';
    export default {
      components: {
        LoadingButton,
      },
      props: {
        modalId: String,
        title: String,
        processing: Boolean,
        submitForm: Function,
        isSubmitDisabled: Boolean,
        cancel: {
          type: String,
          default: 'Close'
        },
        confirm: {
          type: String,
          default: 'Save'
        },
      },
      mounted() {
      // Listen for the Bootstrap modal hide event
      $(`#${this.modalId}`).on('hide.bs.modal', () => {
        this.$emit('modal-closed');
      });
    },
      data() {
        const form = {};
        return {
          form,
        };
      }
    }
  </script>