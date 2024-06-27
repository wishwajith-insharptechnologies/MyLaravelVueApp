import { ref, computed } from 'vue';
import { defineStore } from 'pinia';
import http from './../../services/axios';
import Cookies from 'js-cookie';
import router from '@/router';

export const useAuthStore = defineStore('auth', () => {
  // State
  const authenticated = ref(false);
  const user = ref(null);
  const roles = ref({
    superAdmin: false,
    admin: false,
    moderator: false,
    editor: false,
    user: false,
  });
  const token = ref(Cookies.get('token'));
  const logins = ref({
    facebook: false,
    twitter: false,
    instagram: false,
    github: false,
    youtube: false,
    google: false,
    linkedin: false,
    twitch: false,
    apple: false,
    microsoft: false,
    tiktok: false,
    zoho: false,
    stackexchange: false,
    gitlab: false,
    reddit: false,
    snapchat: false,
    meetup: false,
    bitbucket: false,
    atlassian: false,
  });
  const currentUserToken = ref(null);
  const impersonatorToken = ref(null);
  const darkMode = ref(false);

  // Getters
  const getUserInfo = computed(() => user.value);
  const getRoles = computed(() => roles.value);
  const isVerified = computed(() => user.value?.email_verified_at ?? null);
  const userId = computed(() => user.value?.id ?? null);
  const isAuthenticated = computed(() => authenticated.value);
  const getToken = computed(() => token.value);
  const getLoginDetails = computed(() => logins.value);
  const getCurrentUserToken = computed(() => currentUserToken.value);
  const getImpersonatorToken = computed(() => impersonatorToken.value);
  const isDarkMode = computed(() => darkMode.value);

  // Mutations
  const SET_USER = (userInfo) => {
    user.value = userInfo;
    if (userInfo?.id && process.env.VUE_APP_GA_ENABLED == 1) {
      gtag('set', { user_id: userInfo.id });
    }
    roles.value = {
      superAdmin: false,
      admin: false,
      moderator: false,
      editor: false,
      user: false,
    };
    if (userInfo?.roles?.length > 0) {
      userInfo.roles.forEach((role) => {
        if (role.name === 'Super Admin') roles.value.superAdmin = true;
        if (role.name === 'Admin') roles.value.admin = true;
        if (role.name === 'Moderator') roles.value.moderator = true;
        if (role.name === 'Editor') roles.value.editor = true;
        if (role.name === 'User') roles.value.user = true;
      });
    }
  };

  const SET_THEME = (themeDark) => {
    if (themeDark) {
      document.documentElement.classList.add('dark');
      darkMode.value = true;
    } else {
      document.documentElement.classList.remove('dark');
      darkMode.value = false;
    }
  };

  const SET_AUTHENTICATION = (authStatus = false) => {
    authenticated.value = authStatus;
  };

  const SET_TOKEN = ({ token, remember }) => {
    token = token;
    Cookies.set('token', token, { expires: remember ? 365 : null });
  };

  const SET_WORKING_TOKENS = ({ currentUserToken, impersonatorToken }) => {
    currentUserToken.value = currentUserToken;
    impersonatorToken.value = impersonatorToken;
  };

  const SET_LOGINS = (loginsData = null) => {
    if (loginsData) {
      logins.value = loginsData;
    }
  };

  const LOGOUT = () => {
    authenticated.value = false;
    user.value = null;
    roles.value = {
      superAdmin: false,
      admin: false,
      moderator: false,
      editor: false,
      user: false,
    };
    darkMode.value = false;
    token.value = null;
    currentUserToken.value = null;
    impersonatorToken.value = null;
    Cookies.remove('token');
    window.sessionStorage.clear();
  };

  // Actions
  const login = async (payload) => {
    // try {
      await http.get('/sanctum/csrf-cookie');
      const res = await http.post('/api/login', payload);
      if (res.status === 200) {
        SET_TOKEN(res.data.access_token, payload.remember);
        await fetchUser();
      }
    // } catch (e) {
    //   throw e;
    // }
  };

  const register = async (payload) => {
    await http.get('/sanctum/csrf-cookie');
    const res = await http.post('/api/register', payload);
    if (res.status === 201 && res.data?.user?.id) {
      SET_USER(res.data.user);
      SET_THEME(res.data.user.theme_dark);
      SET_AUTHENTICATION(true);
      return res;
    }
    LOGOUT();
    throw res.response;
  };

  const logout = async () => {
    await http.post('/api/logout');
    LOGOUT();
    router.push({ name: 'home' });
  };

  const fetchUser = async () => {
    // try {
      const res = await http.get('/api/user');
      if (res.data?.id) {
        console.log(res);
        SET_USER(res.data);
        SET_THEME(res.data.theme_dark);
        SET_AUTHENTICATION(true);
      } else {
        console.log('log out');
        LOGOUT();
      }
    // } catch (err) {
    //   LOGOUT();
    //   throw err.response;
    // }
  };

  const getUserByToken = async (payload) => {
    try {
      const res = await http.post('/api/user-by-token', { token: payload.token });
      if (res.data?.id) {
        SET_USER(res.data);
        SET_THEME(res.data.theme_dark);
        SET_AUTHENTICATION(true);
      } else {
        LOGOUT();
      }
    } catch (err) {
      LOGOUT();
      throw err.response;
    }
  };

  const profile = async (payload) => {
    const res = await http.patch('/api/profile', payload);
    if (res.status === 200 && res.data?.user?.id) {
      SET_USER(res.data.user);
      SET_THEME(res.data.user.theme_dark);
      SET_AUTHENTICATION(true);
      return res;
    }
    throw res.response;
  };

  // other actions ...

  return {
    authenticated,
    user,
    roles,
    token,
    logins,
    currentUserToken,
    impersonatorToken,
    darkMode,
    getUserInfo,
    getRoles,
    isVerified,
    userId,
    isAuthenticated,
    getToken,
    getLoginDetails,
    getCurrentUserToken,
    getImpersonatorToken,
    isDarkMode,
    login,
    register,
    logout,
    // getUser, // Add this line
    getUserByToken,
    profile,
    // theme,
    // other actions ...
    SET_USER,
    SET_THEME,
    SET_AUTHENTICATION,
    SET_TOKEN,
    SET_WORKING_TOKENS,
    SET_LOGINS,
    LOGOUT,
  };
});
