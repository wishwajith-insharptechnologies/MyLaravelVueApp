<template>
    <NavBar/>
    <div class="dashboard-wrapper">
      <header class="dashboard-header">
        <div class="logo">
          <img src="/assets/logo.png" alt="PeacoHRM Logo" />
        </div>
        <div class="header-content">
          <h1>The Next Step</h1>
          <p>Contact PeacoTeam for the Installation</p>
        </div>
        <a-button type="primary" class="notify-button">Notify Team</a-button>
      </header>
      <a-tabs default-active-key="1">
        <a-tab-pane key="1" tab="Dashboard">
          <div class="tab-content">
            <h2>Dashboard</h2>
            <p>
              From your account dashboard you can view your
              <a href="#">license key</a>, <a href="#">recent orders</a>,
              <a href="#">edit your password and account details</a>.
            </p>
          </div>
        </a-tab-pane>
        <a-tab-pane key="2" tab="License Keys">
          <div class="tab-content">
            <!-- License Keys content goes here -->
          </div>
        </a-tab-pane>
        <a-tab-pane key="3" tab="Orders & Invoices">
          <div class="tab-content">
            <!-- Orders & Invoices content goes here -->
          </div>
        </a-tab-pane>
        <a-tab-pane key="4" tab="Profile">
          <div class="tab-content">
            <!-- Profile content goes here -->
          </div>
        </a-tab-pane>
      </a-tabs>
      <Footer/>
    </div>
  </template>

  <script setup>
  import { ref } from 'vue';
  import NavBar from "./NavBar.vue";
  import Footer from "./Footer.vue";
//   import 'ant-design-vue/dist/antd.css';
  </script>

  <style scoped>
  .dashboard-wrapper {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
  }

  .dashboard-header {
    display: flex;
    align-items: center;
    background-color: #722ed1;
    padding: 16px;
    color: white;
    position: relative;
  }

  .logo img {
    height: 40px;
    margin-right: 16px;
  }

  .header-content {
    flex-grow: 1;
  }

  .header-content h1 {
    margin: 0;
    font-size: 24px;
  }

  .header-content p {
    margin: 0;
  }

  .notify-button {
    background-color: white;
    color: #722ed1;
    border: none;
    margin-right: 16px;
  }

  .account-dropdown {
    position: absolute;
    right: 16px;
    top: 16px;
  }

  .a-tabs {
    padding: 16px;
    background-color: white;
    flex-grow: 1;
  }

  .tab-content {
    padding: 24px;
    background: #f0f2f5;
    border-radius: 8px;
  }

  .tab-content h2 {
    font-size: 24px;
    font-weight: bold;
  }

  .tab-content p {
    margin-top: 8px;
  }

  .dashboard-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    background-color: #f0f2f5;
  }

  .footer-links a {
    margin-right: 16px;
    color: #888;
  }
  </style>
