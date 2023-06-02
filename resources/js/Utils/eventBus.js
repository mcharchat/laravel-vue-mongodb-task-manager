import { ref } from 'vue';

const eventBus = {
  events: ref({}),
  $on(event, callback) {
    if (!this.events.value[event]) {
      this.events.value[event] = [];
    }
    this.events.value[event].push(callback);
  },
  $off(event, callback) {
    if (!this.events.value[event]) return;
    const index = this.events.value[event].indexOf(callback);
    if (index > -1) {
      this.events.value[event].splice(index, 1);
    }
  },
  $emit(event, ...args) {
    if (!this.events.value[event]) return;
    this.events.value[event].forEach(callback => {
      callback(...args);
    });
  }
};

export default eventBus;