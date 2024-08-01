/** @type {import('tailwindcss').Config} */
export default {
  content: ['./resources/**/*.{vue,js,ts,jsx,tsx,css,scss,vue} ',
    './resources/js/components/layout/AdminLayout.vue',
    './resources/js/views/pages/admin/user/UserPage.vue',
    './resources/js/components/user/UserProfileChangePassword.vue',

  ],
  theme: {
    extend: {
        colors: {
          primary: '#00b96b', // Replace with your primary color
          secondary: '#f59e0b', // Replace with your secondary color
        },

      },
  },
  plugins: [],

}

