/**
 * The FacePost API Client
 * Dynamically communicates with the live backend (https://thefacepost.com/api/v1.0)
 * while maintaining instant native performance and offline cache.
 */

const API_BASE_URL = 'https://thefacepost.com/api/v1.0';

export async function loginWithServer(username, password) {
  try {
    const res = await fetch(`${API_BASE_URL}/auth/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({ username, password })
    });

    const data = await res.json();
    return data;
  } catch (err) {
    console.warn('Login request failed:', err);
    return {
      status: 'error',
      message: 'Network error. Please check your internet connection or try again.'
    };
  }
}

export async function registerWithServer(userData) {
  try {
    const res = await fetch(`${API_BASE_URL}/auth/register`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(userData)
    });

    const data = await res.json();
    return data;
  } catch (err) {
    console.warn('Registration request failed:', err);
    return {
      status: 'error',
      message: 'Network error. Please check your internet connection and try again.'
    };
  }
}

export async function fetchFeedPosts() {
  try {
    const res = await fetch(`${API_BASE_URL}/feed`, {
      method: 'GET',
      headers: { 'Accept': 'application/json' },
      cache: 'no-cache'
    });
    if (res.ok) {
      const data = await res.json();
      if (data && data.posts && data.posts.length > 0) {
        localStorage.setItem('cached_live_posts', JSON.stringify(data.posts));
        return data.posts;
      }
    }
  } catch (err) {
    console.warn('Live API fetch error, falling back to cache:', err);
  }

  const cached = localStorage.getItem('cached_live_posts');
  return cached ? JSON.parse(cached) : null;
}

export async function fetchLiveStories() {
  try {
    const res = await fetch(`${API_BASE_URL}/stories`, {
      method: 'GET',
      headers: { 'Accept': 'application/json' }
    });
    if (res.ok) {
      const data = await res.json();
      if (data && data.stories && data.stories.length > 0) {
        localStorage.setItem('cached_live_stories', JSON.stringify(data.stories));
        return data.stories;
      }
    }
  } catch (err) {
    console.warn('Stories fetch error:', err);
  }

  const cached = localStorage.getItem('cached_live_stories');
  return cached ? JSON.parse(cached) : null;
}

export async function fetchLiveReels() {
  try {
    const res = await fetch(`${API_BASE_URL}/reels`, {
      method: 'GET',
      headers: { 'Accept': 'application/json' }
    });
    if (res.ok) {
      const data = await res.json();
      if (data && data.reels && data.reels.length > 0) {
        return data.reels;
      }
    }
  } catch (err) {
    console.warn('Reels fetch error:', err);
  }
  return null;
}
