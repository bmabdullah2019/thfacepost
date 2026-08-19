import React, { useState } from 'react';
import { Eye, EyeOff, Lock, User, Mail, Calendar, AlertCircle, Loader2, Check } from 'lucide-react';
import { loginWithServer, registerWithServer } from '../services/api';

export default function AuthScreen({ onLoginSuccess }) {
  const [isRegistering, setIsRegistering] = useState(false);
  const [loading, setLoading] = useState(false);
  const [showPassword, setShowPassword] = useState(false);
  const [showRegPassword, setShowRegPassword] = useState(false);
  const [errorMessage, setErrorMessage] = useState('');

  // Login form state
  const [loginIdentifier, setLoginIdentifier] = useState('');
  const [loginPassword, setLoginPassword] = useState('');

  // Register form state (Matches OSSN exact schema)
  const [regFirstName, setRegFirstName] = useState('');
  const [regLastName, setRegLastName] = useState('');
  const [regUsername, setRegUsername] = useState('');
  const [regEmail, setRegEmail] = useState('');
  const [regEmailRe, setRegEmailRe] = useState('');
  const [regPassword, setRegPassword] = useState('');
  const [regBirthDay, setRegBirthDay] = useState('15');
  const [regBirthMonth, setRegBirthMonth] = useState('06');
  const [regBirthYear, setRegBirthYear] = useState('1998');
  const [regGender, setRegGender] = useState('male');

  const months = [
    { value: '01', label: 'Jan' }, { value: '02', label: 'Feb' },
    { value: '03', label: 'Mar' }, { value: '04', label: 'Apr' },
    { value: '05', label: 'May' }, { value: '06', label: 'Jun' },
    { value: '07', label: 'Jul' }, { value: '08', label: 'Aug' },
    { value: '09', label: 'Sep' }, { value: '10', label: 'Oct' },
    { value: '11', label: 'Nov' }, { value: '12', label: 'Dec' }
  ];

  const currentYear = new Date().getFullYear();
  const years = Array.from({ length: 80 }, (_, i) => currentYear - 13 - i);
  const days = Array.from({ length: 31 }, (_, i) => (i + 1).toString().padStart(2, '0'));

  const handleLoginSubmit = async (e) => {
    e.preventDefault();
    setErrorMessage('');

    if (!loginIdentifier.trim() || !loginPassword.trim()) {
      setErrorMessage('Please enter both your mobile/email/username and password.');
      return;
    }

    setLoading(true);
    const result = await loginWithServer(loginIdentifier, loginPassword);
    setLoading(false);

    if (result && result.status === 'success' && result.user) {
      onLoginSuccess(result.user, result.token);
    } else {
      setErrorMessage(result.message || 'Invalid username or password. Please check your credentials.');
    }
  };

  const handleRegisterSubmit = async (e) => {
    e.preventDefault();
    setErrorMessage('');

    if (!regFirstName.trim() || !regLastName.trim()) {
      setErrorMessage('Please enter your first and last name.');
      return;
    }

    if (!regUsername.trim()) {
      setErrorMessage('Please choose a unique username.');
      return;
    }

    if (!regEmail.trim()) {
      setErrorMessage('Please enter your email address.');
      return;
    }

    if (regEmail.trim().toLowerCase() !== regEmailRe.trim().toLowerCase()) {
      setErrorMessage('Email addresses do not match. Please re-enter your email correctly.');
      return;
    }

    if (!regPassword.trim() || regPassword.length < 6) {
      setErrorMessage('Password must be at least 6 characters long.');
      return;
    }

    const birthdateFormatted = `${regBirthYear}-${regBirthMonth}-${regBirthDay}`;

    setLoading(true);
    const result = await registerWithServer({
      firstname: regFirstName.trim(),
      lastname: regLastName.trim(),
      username: regUsername.trim(),
      email: regEmail.trim(),
      email_re: regEmailRe.trim(),
      password: regPassword,
      gender: regGender,
      birthdate: birthdateFormatted
    });
    setLoading(false);

    if (result && result.status === 'success' && result.user) {
      alert('Welcome to The FacePost! Your account has been created successfully.');
      onLoginSuccess(result.user, result.token);
    } else {
      setErrorMessage(result.message || 'Registration failed. The username or email might already be registered.');
    }
  };

  return (
    <div className="auth-wrapper">
      {/* Top Branding */}
      <div className="auth-header">
        <div style={{
          fontSize: 36,
          fontWeight: 800,
          background: 'linear-gradient(135deg, #1877f2 0%, #00d2ff 100%)',
          WebkitBackgroundClip: 'text',
          WebkitTextFillColor: 'transparent',
          letterSpacing: '-0.5px',
          display: 'inline-flex',
          alignItems: 'center',
          gap: 6
        }}>
          <span>facepost</span>
          <span style={{
            width: 9,
            height: 9,
            backgroundColor: '#31a24c',
            borderRadius: '50%',
            boxShadow: '0 0 8px #31a24c'
          }} />
        </div>
        <p style={{
          fontSize: 14,
          color: 'var(--text-secondary)',
          marginTop: 4,
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
          marginBottom: 16,
          display: 'flex',
          alignItems: 'flex-start',
          gap: 10,
          color: '#ff2d55',
          fontSize: 13.5,
          fontWeight: 600,
          animation: 'fadeIn 0.2s ease'
        }}>
          <AlertCircle size={18} style={{ flexShrink: 0, marginTop: 1 }} />
          <div style={{ flex: 1, wordBreak: 'break-word' }}>{errorMessage}</div>
        </div>
      )}

      {/* Auth Card Form */}
      <div className="auth-card">
        {!isRegistering ? (
          /* LOGIN VIEW */
          <form onSubmit={handleLoginSubmit} className="auth-form">
            <h2 style={{ fontSize: 20, fontWeight: 700, marginBottom: 2 }}>Log Into Your Account</h2>

            {/* Identifier Input */}
            <div className="auth-input-group">
              <input
                type="text"
                placeholder="Mobile number or email / username"
                value={loginIdentifier}
                onChange={(e) => setLoginIdentifier(e.target.value)}
                className="auth-input-field"
                autoCapitalize="none"
              />
            </div>

            {/* Password Input with Eye Toggle */}
            <div className="auth-input-group">
              <input
                type={showPassword ? 'text' : 'password'}
                placeholder="Password"
                value={loginPassword}
                onChange={(e) => setLoginPassword(e.target.value)}
                className="auth-input-field"
                style={{ paddingRight: 42 }}
              />
              <button
                type="button"
                onClick={() => setShowPassword(!showPassword)}
                style={{
                  position: 'absolute',
                  right: 12,
                  background: 'none',
                  border: 'none',
                  color: 'var(--text-muted)',
                  cursor: 'pointer',
                  padding: 4,
                  display: 'flex',
                  alignItems: 'center'
                }}
              >
                {showPassword ? <EyeOff size={18} /> : <Eye size={18} />}
              </button>
            </div>

            {/* Submit Button */}
            <button
              type="submit"
              className="btn-auth-primary"
              disabled={loading}
            >
              {loading ? <Loader2 size={20} className="animate-spin" /> : 'Log In'}
            </button>

            {/* Forgot Password */}
            <div style={{ textAlign: 'center', margin: '4px 0' }}>
              <a
                href="#forgot"
                onClick={(e) => { e.preventDefault(); alert('Please contact your administrator or visit https://thefacepost.com/ to reset password.'); }}
                style={{ fontSize: 13.5, color: 'var(--primary)', textDecoration: 'none', fontWeight: 600 }}
              >
                Forgotten password?
              </a>
            </div>

            {/* Divider */}
            <div style={{
              height: 1,
              backgroundColor: 'var(--border-color)',
              margin: '6px 0'
            }} />

            {/* Create New Account Button */}
            <button
              type="button"
              onClick={() => { setIsRegistering(true); setErrorMessage(''); }}
              className="btn-auth-success"
            >
              Create New Account
            </button>
          </form>
        ) : (
          /* REGISTRATION VIEW */
          <form onSubmit={handleRegisterSubmit} className="auth-form">
            <div>
              <h2 style={{ fontSize: 20, fontWeight: 700 }}>Create a New Account</h2>
              <p style={{ fontSize: 13, color: 'var(--text-secondary)', marginTop: 2 }}>
                It's quick and easy.
              </p>
            </div>

            {/* First Name & Last Name (Responsive 2-column Grid) */}
            <div className="auth-grid-2">
              <input
                type="text"
                placeholder="First name"
                value={regFirstName}
                onChange={(e) => setRegFirstName(e.target.value)}
                className="auth-input-field"
              />
              <input
                type="text"
                placeholder="Last name"
                value={regLastName}
                onChange={(e) => setRegLastName(e.target.value)}
                className="auth-input-field"
              />
            </div>

            {/* Username */}
            <input
              type="text"
              placeholder="Username (e.g. shanto123)"
              value={regUsername}
              onChange={(e) => setRegUsername(e.target.value.toLowerCase().replace(/[^a-z0-9_.]/g, ''))}
              className="auth-input-field"
              autoCapitalize="none"
            />

            {/* Email */}
            <input
              type="email"
              placeholder="Mobile number or email"
              value={regEmail}
              onChange={(e) => setRegEmail(e.target.value)}
              className="auth-input-field"
              autoCapitalize="none"
            />

            {/* Re-enter Email */}
            <input
              type="email"
              placeholder="Re-enter mobile number or email"
              value={regEmailRe}
              onChange={(e) => setRegEmailRe(e.target.value)}
              className="auth-input-field"
              autoCapitalize="none"
            />

            {/* Password */}
            <div className="auth-input-group">
              <input
                type={showRegPassword ? 'text' : 'password'}
                placeholder="New password (min 6 characters)"
                value={regPassword}
                onChange={(e) => setRegPassword(e.target.value)}
                className="auth-input-field"
                style={{ paddingRight: 42 }}
              />
              <button
                type="button"
                onClick={() => setShowRegPassword(!showRegPassword)}
                style={{
                  position: 'absolute',
                  right: 12,
                  background: 'none',
                  border: 'none',
                  color: 'var(--text-muted)',
                  cursor: 'pointer',
                  padding: 4,
                  display: 'flex',
                  alignItems: 'center'
                }}
              >
                {showRegPassword ? <EyeOff size={18} /> : <Eye size={18} />}
              </button>
            </div>

            {/* Date of Birth (OSSN Required Field) */}
            <div>
              <div style={{ fontSize: 12.5, fontWeight: 600, color: 'var(--text-secondary)', marginBottom: 5 }}>
                Date of birth:
              </div>
              <div className="auth-grid-3">
                {/* Day */}
                <select
                  value={regBirthDay}
                  onChange={(e) => setRegBirthDay(e.target.value)}
                  className="auth-select-field"
                >
                  {days.map(d => (
                    <option key={d} value={d}>{d}</option>
                  ))}
                </select>

                {/* Month */}
                <select
                  value={regBirthMonth}
                  onChange={(e) => setRegBirthMonth(e.target.value)}
                  className="auth-select-field"
                >
                  {months.map(m => (
                    <option key={m.value} value={m.value}>{m.label}</option>
                  ))}
                </select>

                {/* Year */}
                <select
                  value={regBirthYear}
                  onChange={(e) => setRegBirthYear(e.target.value)}
                  className="auth-select-field"
                >
                  {years.map(y => (
                    <option key={y} value={y.toString()}>{y}</option>
                  ))}
                </select>
              </div>
            </div>

            {/* Gender Selection (OSSN Required Field) */}
            <div>
              <div style={{ fontSize: 12.5, fontWeight: 600, color: 'var(--text-secondary)', marginBottom: 5 }}>
                Gender:
              </div>
              <div style={{ display: 'flex', gap: 10, width: '100%' }}>
                {['female', 'male'].map((g) => (
                  <div
                    key={g}
                    className={`auth-gender-option ${regGender === g ? 'selected' : ''}`}
                    onClick={() => setRegGender(g)}
                  >
                    <span style={{ textTransform: 'capitalize' }}>{g}</span>
                    <input
                      type="radio"
                      name="gender"
                      value={g}
                      checked={regGender === g}
                      onChange={() => setRegGender(g)}
                    />
                  </div>
                ))}
              </div>
            </div>

            {/* Terms notice */}
            <p style={{ fontSize: 11.5, color: 'var(--text-muted)', lineHeight: 1.35, margin: '2px 0' }}>
              By clicking Sign Up, you agree to our Terms, Privacy Policy and Cookies Policy.
            </p>

            {/* Sign Up Submit Button */}
            <button
              type="submit"
              disabled={loading}
              className="btn-auth-success"
              style={{ padding: '13px', fontSize: 16 }}
            >
              {loading ? <Loader2 size={20} className="animate-spin" /> : 'Sign Up'}
            </button>

            {/* Already have an account link */}
            <div style={{ textAlign: 'center', marginTop: 4 }}>
              <button
                type="button"
                onClick={() => { setIsRegistering(false); setErrorMessage(''); }}
                style={{
                  background: 'none',
                  border: 'none',
                  color: 'var(--primary)',
                  fontSize: 14,
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
    </div>
  );
}
