/**
 * The FacePost Native API Client
 * Uses CapacitorHttp for native Android HTTP requests (bypassing browser CORS sandbox)
 * and seamless fallback to standard fetch.
 */
import { CapacitorHttp } from '@capacitor/core';

const API_BASE_URL = 'https://thefacepost.com/api/v1.0';

export async function loginWithServer(username, password) {
  try {
    const options = {
      url: `${API_BASE_URL}/auth/login`,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      data: { username, password }
    };

    const response = await CapacitorHttp.post(options);
    let data = response.data;

    if (typeof data === 'string') {
      try {
        data = JSON.parse(data);
      } catch (e) {
        // Response is HTML from server
        return {
          status: 'error',
          message: 'TheFacePostApi component is not yet uploaded to the live server (thefacepost.com). Please upload the TheFacePostApi component to cPanel to enable live login.'
        };
      }
    }

    if (response.status === 200 && data && data.status === 'success') {
      return data;
    }

    return {
      status: 'error',
      message: data?.message || 'Invalid username or password. Please check your credentials.'
    };
  } catch (err) {
    console.warn('Native Login request error:', err);
    return {
      status: 'error',
      message: 'Network connection failed. Please check your internet connection.'
    };
  }
}

export async function registerWithServer(userData) {
  try {
    const options = {
      url: `${API_BASE_URL}/auth/register`,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      data: userData
    };

    const response = await CapacitorHttp.post(options);
    let data = response.data;

    if (typeof data === 'string') {
      try {
        data = JSON.parse(data);
      } catch (e) {
        return {
          status: 'error',
          message: 'TheFacePostApi is not active on the live server yet. Please upload the component to cPanel.'
        };
      }
    }

    if (response.status === 200 && data && data.status === 'success') {
      return data;
    }

    return {
      status: 'error',
      message: data?.message || 'Registration failed. Please try again.'
    };
  } catch (err) {
    console.warn('Native Registration request error:', err);
    return {
      status: 'error',
      message: 'Network connection failed. Please check your internet connection.'
    };
  }
}

export async function forgotPasswordWithServer(identifier) {
  try {
    const options = {
      url: `${API_BASE_URL}/auth/forgot_password`,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      data: { email: identifier, username: identifier }
    };

    const response = await CapacitorHttp.post(options);
    let data = response.data;

    if (typeof data === 'string') {
      try {
        data = JSON.parse(data);
      } catch (e) {
        return {
          status: 'error',
          message: 'The API is not active on the live server yet.'
        };
      }
    }

    if (response.status === 200 && data && data.status === 'success') {
      return data;
    }

    return {
      status: 'error',
      message: data?.message || 'No account found with that email or username.'
    };
  } catch (err) {
    return {
      status: 'error',
      message: 'Network connection failed. Please check your internet connection.'
    };
  }
}

export async function fetchFeedPosts() {
  try {
    const options = {
      url: `${API_BASE_URL}/feed`,
      headers: { 'Accept': 'application/json' }
    };
    const response = await CapacitorHttp.get(options);
    let data = response.data;
    if (typeof data === 'string') {
      try { data = JSON.parse(data); } catch (e) {}
    }
    if (data && data.status === 'success' && Array.isArray(data.posts)) {
      return data.posts;
    }
  } catch (err) {
    console.warn('Live feed fetch failed, utilizing cached feed:', err);
  }
  return null;
}

export async function fetchLiveStories() {
  try {
    const options = {
      url: `${API_BASE_URL}/stories`,
      headers: { 'Accept': 'application/json' }
    };
    const response = await CapacitorHttp.get(options);
    let data = response.data;
    if (typeof data === 'string') {
      try { data = JSON.parse(data); } catch (e) {}
    }
    if (data && data.status === 'success' && Array.isArray(data.stories)) {
      return data.stories;
    }
  } catch (err) {
    console.warn('Live stories fetch failed, utilizing cached stories:', err);
  }
  return null;
}

export async function fetchLiveReels() {
  try {
    const options = {
      url: `${API_BASE_URL}/reels`,
      headers: { 'Accept': 'application/json' }
    };
    const response = await CapacitorHttp.get(options);
    let data = response.data;
    if (typeof data === 'string') {
      try { data = JSON.parse(data); } catch (e) {}
    }
    if (data && data.status === 'success' && Array.isArray(data.reels)) {
      return data.reels;
    }
  } catch (err) {
    console.warn('Live reels fetch failed, utilizing cached reels:', err);
  }
  return null;
}
