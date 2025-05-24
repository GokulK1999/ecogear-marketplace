<template>
  <div id="app">
    <NavBar />
    <main>
      <RouterView />
    </main>
    <AppFooter />
  </div>
</template>

<script>
import NavBar from './components/NavBar.vue'
import AppFooter from './components/AppFooter.vue'

export default {
  name: 'App',
  components: {
    NavBar,
    AppFooter
  }
}
</script>

<style>
#app {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

main {
  flex: 1;
}

/* Remove default margins and ensure full width */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}
</style>