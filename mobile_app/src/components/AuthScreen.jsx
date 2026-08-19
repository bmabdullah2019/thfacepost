import React, { useState } from 'react';
import { Eye, EyeOff, Lock, User, Mail, Sparkles, ArrowRight, ShieldCheck, AlertCircle, Loader2 } from 'lucide-react';
import { loginWithServer, registerWithServer } from '../services/api';

export default function AuthScreen({ onLoginSuccess }) {
  const [isRegistering, setIsRegistering] = useState(false);
  const [loading, setLoading] = useState(false);
  const [showPassword, setShowPassword] = useState(false);
  const [errorMessage, setErrorMessage] = useState('');

  // Login form state
  const [loginIdentifier, setLoginIdentifier] = useState('');
  const [loginPassword, setLoginPassword] = useState('');

  // Register form state
  const [regFirstName, setRegFirstName] = useState('');
  const [regLastName, setRegLastName] = useState('');
  const [regUsername, setRegUsername] = useState('');
  const [regEmail, setRegEmail] = useState('');
  const [regPassword, setRegPassword] = useState('');
  const [regGender, setRegGender] = useState('male');

  const handleLoginSubmit = async (e) => {
    e.preventDefault();
    setErrorMessage('');

    if (!loginIdentifier.trim() || !loginPassword.trim()) {
      setErrorMessage('Please enter both your username/email and password.');
      return;
    }

    setLoading(true);
    const result = await loginWithServer(loginIdentifier, loginPassword);
    setLoading(false);

    if (result && result.status === 'success' && result.user) {
      onLoginSuccess(result.user, result.token);
    } else {
      setErrorMessage(result.message || 'Invalid username or password. Please try again.');
    }
  };

  const handleRegisterSubmit = async (e) => {
    e.preventDefault();
    setErrorMessage('');

    if (!regFirstName.trim() || !regLastName.trim() || !regEmail.trim() || !regUsername.trim() || !regPassword.trim()) {
      setErrorMessage('Please fill in all the required fields to sign up.');
      return;
    }

    setLoading(true);
    const result = await registerWithServer({
      first_name: regFirstName,
      last_name: regLastName,
      username: regUsername,
      email: regEmail,
      password: regPassword,
      gender: regGender
    });
    setLoading(false);

    if (result && result.status === 'success' && result.user) {
      alert('Account created successfully! Logging you in...');
      onLoginSuccess(result.user, result.token);
    } else {
      setErrorMessage(result.message || 'Registration failed. Username or email may already be taken.');
    }
  };

  const handleQuickDemoLogin = () => {
    // Instant demo login for testing when server is offline
    const demoUser = {
      id: "u_demo_1",
      guid: 1,
      name: "Mahidul Shanto",
      username: "mahidul.shanto",
      email: "mahidul@thefacepost.com",
      avatar: "https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&auto=format&fit=crop&q=80",
      coverPhoto: "https://images.unsplash.com/photo-1707343843437-caacff5cfa74?w=800&auto=format&fit=crop&q=80",
      bio: "🌟 Digital Creator | Passionate Developer | Living life with code ✨",
      work: "The FacePost Developer",
      education: "Computer Science",
      livesIn: "Dhaka, Bangladesh",
      followersCount: "12.4K",
      friendsCount: "1,248",
      followingCount: "420",
      verified: true
    };
    onLoginSuccess(demoUser, "demo_token_123");
  };

  return (
    <div style={{
      minHeight: '100vh',
      maxWidth: 480,
      margin: '0 auto',
      backgroundColor: 'var(--bg-main)',
      display: 'flex',
      flexDirection: 'column',
      justifyContent: 'center',
      padding: '24px 20px',
      position: 'relative'
    }}>
      {/* Top Branding */}
      <div style={{ textAlign: 'center', marginBottom: 28 }}>
        <div style={{
          fontSize: 38,
          fontWeight: 800,
          background: 'linear-gradient(135deg, #1877f2 0%, #00d2ff 100%)',
          WebkitBackgroundClip: 'text',
          WebkitTextFillColor: 'transparent',
          letterSpacing: '-1px',
          display: 'inline-flex',
          alignItems: 'center',
          gap: 6
        }}>
          <span>facepost</span>
          <span style={{
            width: 10,
            height: 10,
            backgroundColor: '#31a24c',
            borderRadius: '50%',
            boxShadow: '0 0 10px #31a24c'
          }} />
        </div>
        <p style={{
          fontSize: 14,
          color: 'var(--text-secondary)',
          marginTop: 6,
          fontWeight: 500
        }}>
          Connect with friends and the world around you.
        </p>
      </div>

      {/* Error Alert Box */}
      {errorMessage && (
        <div style={{
          backgroundColor: 'rgba(255, 45, 85, 0.12)',
          border: '1px solid #ff2d55',
          borderRadius: 12,
          padding: '12px 14px',
          marginBottom: 18,
          display: 'flex',
          alignItems: 'flex-start',
          gap: 10,
          color: '#ff2d55',
          fontSize: 13.5,
          fontWeight: 600,
          animation: 'fadeIn 0.2s ease'
        }}>
          <AlertCircle size={18} style={{ flexShrink: 0, marginTop: 1 }} />
          <div style={{ flex: 1 }}>{errorMessage}</div>
        </div>
      )}

      {/* Card Form */}
      <div style={{
        backgroundColor: 'var(--bg-card)',
        borderRadius: 20,
        padding: '24px 20px',
        boxShadow: 'var(--shadow-md)',
        border: '1px solid var(--border-color)'
      }}>
        {!isRegistering ? (
          /* LOGIN FORM */
          <form onSubmit={handleLoginSubmit} style={{ display: 'flex', flexDirection: 'column', gap: 14 }}>
            <h2 style={{ fontSize: 20, fontWeight: 700, marginBottom: 4 }}>Log Into Your Account</h2>

            {/* Identifier Input */}
            <div>
              <div style={{
                display: 'flex',
                alignItems: 'center',
                gap: 10,
                backgroundColor: 'var(--bg-input)',
                borderRadius: 12,
                padding: '12px 14px',
                border: '1px solid var(--border-color)'
              }}>
                <User size={18} color="var(--text-secondary)" />
                <input
                  type="text"
                  placeholder="Mobile number or email / username"
                  value={loginIdentifier}
                  onChange={(e) => setLoginIdentifier(e.target.value)}
                  style={{
                    border: 'none',
                    background: 'none',
                    outline: 'none',
                    width: '100%',
                    fontSize: 14.5,
                    color: 'var(--text-primary)'
                  }}
                />
              </div>
            </div>

            {/* Password Input */}
            <div>
              <div style={{
                display: 'flex',
                alignItems: 'center',
                gap: 10,
                backgroundColor: 'var(--bg-input)',
                borderRadius: 12,
                padding: '12px 14px',
                border: '1px solid var(--border-color)'
              }}>
                <Lock size={18} color="var(--text-secondary)" />
                <input
                  type={showPassword ? 'text' : 'password'}
                  placeholder="Password"
                  value={loginPassword}
                  onChange={(e) => setLoginPassword(e.target.value)}
                  style={{
                    border: 'none',
                    background: 'none',
                    outline: 'none',
                    width: '100%',
                    fontSize: 14.5,
                    color: 'var(--text-primary)'
                  }}
                />
                <button
                  type="button"
                  onClick={() => setShowPassword(!showPassword)}
                  style={{ background: 'none', border: 'none', color: 'var(--text-secondary)', cursor: 'pointer' }}
                >
                  {showPassword ? <EyeOff size={18} /> : <Eye size={18} />}
                </button>
              </div>
            </div>

            {/* Submit Button */}
            <button
              type="submit"
              className="btn-primary"
              disabled={loading}
              style={{
                width: '100%',
                padding: '13px',
                fontSize: 15,
                fontWeight: 700,
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                gap: 8,
                marginTop: 6
              }}
            >
              {loading ? <Loader2 size={20} className="animate-spin" /> : 'Log In'}
            </button>

            {/* Forgot Password */}
            <div style={{ textAlign: 'center', marginTop: 4 }}>
              <a
                href="#forgot"
                onClick={(e) => { e.preventDefault(); alert('Password reset link sent to your email.'); }}
                style={{ fontSize: 13, color: 'var(--primary)', textDecoration: 'none', fontWeight: 600 }}
              >
                Forgotten password?
              </a>
            </div>

            {/* Divider */}
            <div style={{
              height: 1,
              backgroundColor: 'var(--border-color)',
              margin: '8px 0'
            }} />

            {/* Switch to Register */}
            <button
              type="button"
              onClick={() => { setIsRegistering(true); setErrorMessage(''); }}
              style={{
                backgroundColor: '#42b72a',
                color: 'white',
                border: 'none',
                borderRadius: 12,
                padding: '12px',
                fontSize: 14.5,
                fontWeight: 700,
                cursor: 'pointer',
                transition: 'transform 0.15s ease'
              }}
            >
              Create New Account
            </button>
          </form>
        ) : (
          /* REGISTRATION FORM */
          <form onSubmit={handleRegisterSubmit} style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
            <h2 style={{ fontSize: 20, fontWeight: 700 }}>Create a New Account</h2>
            <p style={{ fontSize: 13, color: 'var(--text-secondary)', marginTop: -6, marginBottom: 4 }}>
              It's quick and easy.
            </p>

            {/* Name Fields */}
            <div style={{ display: 'flex', gap: 10 }}>
              <input
                type="text"
                placeholder="First name"
                value={regFirstName}
                onChange={(e) => setRegFirstName(e.target.value)}
                style={{
                  flex: 1,
                  backgroundColor: 'var(--bg-input)',
                  border: '1px solid var(--border-color)',
                  borderRadius: 10,
                  padding: '10px 12px',
                  color: 'var(--text-primary)',
                  fontSize: 14,
                  outline: 'none'
                }}
              />
              <input
                type="text"
                placeholder="Last name"
                value={regLastName}
                onChange={(e) => setRegLastName(e.target.value)}
                style={{
                  flex: 1,
                  backgroundColor: 'var(--bg-input)',
                  border: '1px solid var(--border-color)',
                  borderRadius: 10,
                  padding: '10px 12px',
                  color: 'var(--text-primary)',
                  fontSize: 14,
                  outline: 'none'
                }}
              />
            </div>

            {/* Username & Email */}
            <input
              type="text"
              placeholder="Choose a username (e.g. shanto123)"
              value={regUsername}
              onChange={(e) => setRegUsername(e.target.value)}
              style={{
                backgroundColor: 'var(--bg-input)',
                border: '1px solid var(--border-color)',
                borderRadius: 10,
                padding: '10px 12px',
                color: 'var(--text-primary)',
                fontSize: 14,
                outline: 'none'
              }}
            />

            <input
              type="email"
              placeholder="Mobile number or email"
              value={regEmail}
              onChange={(e) => setRegEmail(e.target.value)}
              style={{
                backgroundColor: 'var(--bg-input)',
                border: '1px solid var(--border-color)',
                borderRadius: 10,
                padding: '10px 12px',
                color: 'var(--text-primary)',
                fontSize: 14,
                outline: 'none'
              }}
            />

            {/* Password */}
            <input
              type="password"
              placeholder="New password"
              value={regPassword}
              onChange={(e) => setRegPassword(e.target.value)}
              style={{
                backgroundColor: 'var(--bg-input)',
                border: '1px solid var(--border-color)',
                borderRadius: 10,
                padding: '10px 12px',
                color: 'var(--text-primary)',
                fontSize: 14,
                outline: 'none'
              }}
            />

            {/* Gender Selection */}
            <div>
              <div style={{ fontSize: 12, fontWeight: 600, color: 'var(--text-secondary)', marginBottom: 6 }}>
                Gender:
              </div>
              <div style={{ display: 'flex', gap: 10 }}>
                {['male', 'female', 'other'].map((g) => (
                  <label
                    key={g}
                    style={{
                      flex: 1,
                      display: 'flex',
                      alignItems: 'center',
                      justifyContent: 'space-between',
                      backgroundColor: 'var(--bg-input)',
                      border: regGender === g ? '2px solid var(--primary)' : '1px solid var(--border-color)',
                      borderRadius: 8,
                      padding: '8px 12px',
                      cursor: 'pointer',
                      fontSize: 13,
                      textTransform: 'capitalize',
                      fontWeight: 600
                    }}
                  >
                    <span>{g}</span>
                    <input
                      type="radio"
                      name="gender"
                      value={g}
                      checked={regGender === g}
                      onChange={() => setRegGender(g)}
                    />
                  </label>
                ))}
              </div>
            </div>

            {/* Sign Up Submit */}
            <button
              type="submit"
              disabled={loading}
              style={{
                backgroundColor: '#00a400',
                color: 'white',
                border: 'none',
                borderRadius: 12,
                padding: '12px',
                fontSize: 15,
                fontWeight: 700,
                cursor: 'pointer',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                gap: 8,
                marginTop: 6
              }}
            >
              {loading ? <Loader2 size={20} className="animate-spin" /> : 'Sign Up'}
            </button>

            {/* Already have an account */}
            <div style={{ textAlign: 'center', marginTop: 6 }}>
              <button
                type="button"
                onClick={() => { setIsRegistering(false); setErrorMessage(''); }}
                style={{
                  background: 'none',
                  border: 'none',
                  color: 'var(--primary)',
                  fontSize: 13.5,
                  fontWeight: 600,
                  cursor: 'pointer'
                }}
              >
                Already have an account? Log in
              </button>
            </div>
          </form>
        )}
      </div>

      {/* Quick Test Demo Account Button */}
      <div style={{ textAlign: 'center', marginTop: 18 }}>
        <button
          type="button"
          onClick={handleQuickDemoLogin}
          style={{
            background: 'none',
            border: 'none',
            color: 'var(--text-secondary)',
            fontSize: 12.5,
            textDecoration: 'underline',
            cursor: 'pointer'
          }}
        >
          ⚡ Fast Demo Login (Test mode)
        </button>
      </div>
    </div>
  );
}
