<template>
  <div class="main-container d-flex flex-column">
    <h2 class="text-center mb-4">Posts</h2>

    <div class="row">
      <div
        v-for="post in paginatedPosts"
        :key="post.id"
        class="col-md-12 col-lg-6 post-container"
      >
        <div class="card h-100 shadow-lg">
          <img :src="post.image" class="card-img-top post-image" alt="Post Image" />
          <div class="card-body">
            <h4 class="card-title">{{ post.title }}</h4>
            <p class="card-text">{{ post.description }}</p>
            <p class="text-muted">{{ formatDate(post.date) }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
      <ul class="pagination justify-content-center">
        <li
          class="page-item"
          :class="{ disabled: currentPage === 1 }"
          @click="changePage(currentPage - 1)"
        >
          <a class="page-link" href="#">Previous</a>
        </li>

        <li
          class="page-item"
          v-for="page in totalPages"
          :key="page"
          :class="{ active: currentPage === page }"
          @click="changePage(page)"
        >
          <a class="page-link" href="#">{{ page }}</a>
        </li>

        <li
          class="page-item"
          :class="{ disabled: currentPage === totalPages }"
          @click="changePage(currentPage + 1)"
        >
          <a class="page-link" href="#">Next</a>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script>
export default {
  data() {
    return {
      posts: [
        // Sample posts, replace with dynamic data if needed
        {
          id: 1,
          title: "Post 1",
          image: "https://via.placeholder.com/150",
          description: "This is the description for Post 1.",
          date: "2024-10-22",
        },
        {
          id: 2,
          title: "Post 2",
          image: "https://via.placeholder.com/150",
          description: "This is the description for Post 2.",
          date: "2024-10-21",
        },
        {
          id: 3,
          title: "Post 1",
          image: "https://via.placeholder.com/150",
          description: "This is the description for Post 1.",
          date: "2024-10-22",
        },
        {
          id: 4,
          title: "Post 2",
          image: "https://via.placeholder.com/150",
          description: "This is the description for Post 2.",
          date: "2024-10-21",
        },
        {
          id: 5,
          title: "Post 1",
          image: "https://via.placeholder.com/150",
          description: "This is the description for Post 1.",
          date: "2024-10-22",
        },
        {
          id: 6,
          title: "Post 2",
          image: "https://via.placeholder.com/150",
          description: "This is the description for Post 2.",
          date: "2024-10-21",
        },
        // Add more posts as needed
      ],
      currentPage: 1,
      postsPerPage: 4,
    };
  },
  computed: {
    totalPages() {
      return Math.ceil(this.posts.length / this.postsPerPage);
    },
    paginatedPosts() {
      const start = (this.currentPage - 1) * this.postsPerPage;
      const end = start + this.postsPerPage;
      return this.posts.slice(start, end);
    },
  },
  methods: {
    changePage(page) {
      if (page > 0 && page <= this.totalPages) {
        this.currentPage = page;
      }
    },
    formatDate(date) {
      const options = { year: "numeric", month: "long", day: "numeric" };
      return new Date(date).toLocaleDateString(undefined, options);
    },
  },
};
</script>

<style scoped>
.text-center{
  color: white;
}
.main-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #8a0b0b, #031934);
  padding: 20px;
}

.card {
  transition: transform 0.3s ease-in-out;
  margin:20px;
}

.card:hover {
  transform: scale(1.05);
}

.card-title {
  font-size: 1.5rem;
}

.card-text {
  font-size: 1.1rem;
}

.post-image {
  height: 300px;
  object-fit: cover;
}

/* Add space between posts */
.post-container {
  margin-bottom: 50px; /* Increase margin to add space between posts */
}

@media (max-width: 576px) {
  .col-md-12 {
    width: 100%;
  }

  .post-image {
    height: 200px;
  }
}
</style>
