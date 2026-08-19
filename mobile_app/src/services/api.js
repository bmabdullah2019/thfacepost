/**
 * The FacePost Native API Client
 * Uses dual-route strategy (Clean URL + Direct api.php fallback)
 * Powered by CapacitorHttp for 100% native Android HTTP execution (No CORS issues).
 */
import { CapacitorHttp } from '@capacitor/core';

const PRIMARY_API = 'https://thefacepost.com/api/v1.0';
const DIRECT_API = 'https://thefacepost.com/api.php';

async function nativePost(routePath, dataPayload) {
  // Try clean URL first
  try {
    const res = await CapacitorHttp.post({
      url: `${PRIMARY_API}/${routePath}`,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      data: dataPayload
    });

    let resData = res.data;
    if (typeof resData === 'string') {
      try { resData = JSON.parse(resData); } catch (e) { resData = null; }
    }

    if (res.status === 200 && resData && resData.status === 'success') {
      return resData;
    }
    if (resData && resData.message) {
      return resData;
    }
  } catch (err) {
    console.warn('Primary API attempt failed, trying direct api.php fallback...', err);
  }

  // Direct api.php fallback
  try {
    const res = await CapacitorHttp.post({
      url: `${DIRECT_API}?route=${routePath}`,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      data: dataPayload
    });

    let resData = res.data;
    if (typeof resData === 'string') {
      try { resData = JSON.parse(resData); } catch (e) { resData = null; }
    }

    if (resData) {
      return resData;
    }
  } catch (fallbackErr) {
    console.warn('Direct api.php fallback failed:', fallbackErr);
  }

  return {
    status: 'error',
    message: 'Could not connect to live server. Please make sure api.php or TheFacePostApi is present on the server.'
  };
}

async function nativeGet(routePath) {
  try {
    const res = await CapacitorHttp.get({
      url: `${PRIMARY_API}/${routePath}`,
      headers: { 'Accept': 'application/json' }
    });
    let resData = res.data;
    if (typeof resData === 'string') {
      try { resData = JSON.parse(resData); } catch (e) { resData = null; }
    }
    if (resData && resData.status === 'success') {
      return resData;
    }
  } catch (e) {}

  try {
    const res = await CapacitorHttp.get({
      url: `${DIRECT_API}?route=${routePath}`,
      headers: { 'Accept': 'application/json' }
    });
    let resData = res.data;
    if (typeof resData === 'string') {
      try { resData = JSON.parse(resData); } catch (e) { resData = null; }
    }
    if (resData && resData.status === 'success') {
      return resData;
    }
  } catch (e) {}

  return null;
}

export async function loginWithServer(username, password) {
  return await nativePost('auth/login', { username, password });
}

export async function registerWithServer(userData) {
  return await nativePost('auth/register', userData);
}

export async function forgotPasswordWithServer(identifier) {
  return await nativePost('auth/forgot_password', { email: identifier, username: identifier });
}

export async function fetchFeedPosts() {
  const result = await nativeGet('feed');
  if (result && Array.isArray(result.posts)) {
    return result.posts;
  }
  return null;
}

export async function fetchLiveStories() {
  const result = await nativeGet('stories');
  if (result && Array.isArray(result.stories)) {
    return result.stories;
  }
  return null;
}

export async function fetchLiveReels() {
  const result = await nativeGet('reels');
  if (result && Array.isArray(result.reels)) {
    return result.reels;
  }
  return null;
}
