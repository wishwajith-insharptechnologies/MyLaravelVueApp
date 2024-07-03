<template>
    <footer class="footer">
      <div class="container">
        <span>© 2024 PeacoHRM. All rights reserved.</span>
        <div class="links">
          <a href="/terms">Terms</a>
          <a href="/privacy">Privacy</a>
          <a href="/cookies">Cookies</a>
        </div>
      </div>
    </footer>
  </template>

  <script>
  export default {
    name: "Footer"
  };
  </script>

  <style scoped>
  .footer {
    border-top: 1px solid #e0e0e0;
    padding: 1em 0;
    text-align: center;
    background-color: #f8f9fa;
  }

  .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1em;
  }

  .links a {
    margin-left: 1em;
    color: #007bff;
    text-decoration: none;
  }

  .links a:hover {
    text-decoration: underline;
  }
  </style>
