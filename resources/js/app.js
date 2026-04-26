import Test from './components/Test.vue'

const app = createApp({})

app.component('test-component', Test)

app.mount('#app')