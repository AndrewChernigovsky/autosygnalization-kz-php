<script setup lang="ts">
import Navigation from './components/Navigation/Navigation.vue';
</script>

<template>
  <Navigation />
</template>

<style scoped>
.container {
  padding: 2rem;
  max-width: 1200px;
  margin: 0 auto;
  position: relative;
}

.title {
  font-size: 1.5rem;
  color: #2c3e50;
  margin-bottom: 2rem;
}

.loading-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 100;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #42b883;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.error-message {
  color: #dc3545;
  padding: 1rem;
  background: #f8d7da;
  border-radius: 4px;
  margin-bottom: 1rem;
}

.add-nav-form {
  background: #f9f9f9;
  border-radius: 8px;
  padding: 1.5rem;
  margin-bottom: 2rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
  max-width: 500px;
}
.add-nav-form h2 {
  margin-bottom: 1rem;
}
.add-nav-form .input-group {
  margin-bottom: 1rem;
}

.navigation-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1.5rem;
}

.nav-item {
  background: white;
  border-radius: 8px;
  padding: 1.5rem;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s, box-shadow 0.2s;
}

.nav-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
}

.input-group {
  display: flex;
  flex-direction: column;
  margin-bottom: 1rem;
}

.input-group label {
  font-size: 0.875rem;
  color: #666;
  margin-bottom: 0.5rem;
}

.input-group input,
.add-nav-form select {
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
  transition: border-color 0.2s;
}

.input-group input:focus,
.add-nav-form select:focus {
  border-color: #42b883;
  outline: none;
  box-shadow: 0 0 0 2px rgba(66, 184, 131, 0.2);
}

.button-group {
  display: flex;
  gap: 0.75rem;
  margin-top: 1rem;
}

.btn {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.btn.save {
  background: #42b883;
  color: white;
}

.btn.save:hover {
  background: #3aa876;
}

.btn.delete {
  background: #dc3545;
  color: white;
}

.btn.delete:hover {
  background: #c82333;
}
</style>
