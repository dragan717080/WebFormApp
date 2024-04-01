<template>
  <div class="flex flex-col min-h-screen">
    <MyHeader />
    <main class="flex-1 flex flex-col">
      <router-view />
    </main>
    <MyFooter />
  </div>
</template>
