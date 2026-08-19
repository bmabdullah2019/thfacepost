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

    const text = await res.text();
    let data;
    try {
      data = JSON.parse(text);
    } catch (parseErr) {
      // Server returned HTML (e.g. redirect or 404 because API component not yet uploaded/enabled on cPanel)
      return {
        status: 'error',
        message: 'TheFacePost API is not yet activated on the live server (https://thefacepost.com). Please upload the components/TheFacePostApi folder to cPanel and enable it.'
      };
    }

    return data;
  } catch (err) {
    console.warn('Login request failed:', err);
    return {
      status: 'error',
      message: 'Network connection failed. Please check your internet connection or server availability.'
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

    const text = await res.text();
    let data;
    try {
      data = JSON.parse(text);
    } catch (parseErr) {
      return {
        status: 'error',
        message: 'TheFacePost API is not yet active on https://thefacepost.com. Please upload components/TheFacePostApi to cPanel.'
      };
    }

    return data;
  } catch (err) {
    console.warn('Registration request failed:', err);
    return {
      status: 'error',
      message: 'Network connection failed. Please check your internet connection and try again.'
    };
  }
}

export async function forgotPasswordWithServer(identifier) {
  try {
    const res = await fetch(`${API_BASE_URL}/auth/forgot_password`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({ email: identifier, username: identifier })
    });

    const text = await res.text();
    let data;
    try {
      data = JSON.parse(text);
    } catch (e) {
      return {
        status: 'error',
        message: 'The API is not reachable or responded unexpectedly.'
      };
    }
    return data;
  } catch (err) {
    return {
      status: 'error',
      message: 'Network connection failed. Please try again.'
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
      const text = await res.text();
      try {
        const data = JSON.parse(text);
        if (data && data.status === 'success' && Array.isArray(data.posts)) {
          return data.posts;
        }
      } catch (e) {}
    }
  } catch (err) {
    console.warn('Live feed fetch failed, utilizing cached feed:', err);
  }
  return null;
}

export async function fetchLiveStories() {
  try {
    const res = await fetch(`${API_BASE_URL}/stories`, {
      method: 'GET',
      headers: { 'Accept': 'application/json' }
    });
    if (res.ok) {
      const text = await res.text();
      try {
        const data = JSON.parse(text);
        if (data && data.status === 'success' && Array.isArray(data.stories)) {
          return data.stories;
        }
      } catch (e) {}
    }
  } catch (err) {
    console.warn('Live stories fetch failed, utilizing cached stories:', err);
  }
  return null;
}

export async function fetchLiveReels() {
  try {
    const res = await fetch(`${API_BASE_URL}/reels`, {
      method: 'GET',
      headers: { 'Accept': 'application/json' }
    });
    if (res.ok) {
      const text = await res.text();
      try {
        const data = JSON.parse(text);
        if (data && data.status === 'success' && Array.isArray(data.reels)) {
          return data.reels;
        }
      } catch (e) {}
    }
  } catch (err) {
    console.warn('Live reels fetch failed, utilizing cached reels:', err);
  }
  return null;
}
