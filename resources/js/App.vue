<template>
    <a-layout>
        <NavBar/>
        <a-layout-content >
            <router-view/>
        </a-layout-content>

    </a-layout>
</template>
  <script lang="ts">
  import { defineComponent, ref } from 'vue';
  import NavBar from '/resources/js/components/layout/NavBar.vue';
  import Footer from '/resources/js/components/layout/Footer.vue';
  export default defineComponent({
    setup() {
      return {
        selectedKeys: ref<string[]>(['2']),
      };
    },
  });
  </script>
  <style>
  .site-layout-content {
    min-height: 280px;
    padding: 24px;
    background: #fff;
  }
  #components-layout-demo-top .logo {
    float: left;
    width: 120px;
    height: 31px;
    margin: 16px 24px 16px 0;
    background: rgba(255, 255, 255, 0.3);
  }
  .ant-row-rtl #components-layout-demo-top .logo {
    float: right;
    margin: 16px 0 16px 24px;
  }

  [data-theme='dark'] .site-layout-content {
    background: #141414;
  }
  </style>
