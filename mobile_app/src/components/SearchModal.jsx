import React, { useState } from 'react';
import { ArrowLeft, Search, X, Clock, TrendingUp, User } from 'lucide-react';

export default function SearchModal({ onClose, onSelectResult }) {
  const [query, setQuery] = useState('');

  const recentSearches = [
    'Tanjila Akter',
    'Tech Developers Bangladesh',
    'Old Dhaka Street Food Reel',
    'The FacePost APK Download'
  ];

  const trendingTopics = [
    '#TheFacePost',
    '#TechInnovation',
    '#DhakaVibes',
    '#ReelsViral'
  ];

  return (
    <div style={{
      position: 'fixed',
      inset: 0,
      backgroundColor: 'var(--bg-main)',
      zIndex: 160,
      display: 'flex',
      flexDirection: 'column',
      maxWidth: 480,
      margin: '0 auto'
    }}>
      {/* Top Search Bar */}
      <div style={{
        display: 'flex',
        alignItems: 'center',
        gap: 8,
        padding: 'calc(var(--safe-top) + 8px) 12px 10px 8px',
        backgroundColor: 'var(--bg-header)',
        borderBottom: '1px solid var(--border-color)'
      }}>
        <button className="icon-btn" onClick={onClose} style={{ width: 36, height: 36 }}>
          <ArrowLeft size={20} />
        </button>

        <div style={{
          flex: 1,
          display: 'flex',
          alignItems: 'center',
          gap: 8,
          backgroundColor: 'var(--bg-input)',
          borderRadius: 20,
          padding: '8px 12px'
        }}>
          <Search size={16} color="var(--text-muted)" />
          <input
            type="text"
            placeholder="Search FacePost..."
            value={query}
            onChange={(e) => setQuery(e.target.value)}
            autoFocus
            style={{
              flex: 1,
              border: 'none',
              background: 'none',
              outline: 'none',
              fontSize: 14,
              color: 'var(--text-primary)'
            }}
          />
          {query && (
            <button
              onClick={() => setQuery('')}
              style={{ background: 'none', border: 'none', color: 'var(--text-muted)', cursor: 'pointer' }}
            >
              <X size={16} />
            </button>
          )}
        </div>
      </div>

      {/* Content Area */}
      <div style={{ padding: 16, overflowY: 'auto' }}>
        {/* Recent Searches */}
        <div style={{ marginBottom: 20 }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: 10 }}>
            <span style={{ fontSize: 14, fontWeight: 700 }}>Recent</span>
            <span style={{ fontSize: 13, color: 'var(--primary)', cursor: 'pointer' }}>Edit</span>
          </div>

          <div style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
            {recentSearches.map((item, idx) => (
              <div
                key={idx}
                onClick={() => { setQuery(item); }}
                style={{
                  display: 'flex',
                  alignItems: 'center',
                  gap: 12,
                  cursor: 'pointer',
                  padding: '6px 0'
                }}
              >
                <div style={{
                  width: 32,
                  height: 32,
                  borderRadius: '50%',
                  backgroundColor: 'var(--bg-input)',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  color: 'var(--text-secondary)'
                }}>
                  <Clock size={16} />
                </div>
                <span style={{ fontSize: 14, flex: 1 }}>{item}</span>
              </div>
            ))}
          </div>
        </div>

        {/* Trending Searches */}
        <div>
          <div style={{ fontSize: 14, fontWeight: 700, marginBottom: 10, display: 'flex', alignItems: 'center', gap: 6 }}>
            <TrendingUp size={16} color="#ff2d55" />
            <span>Trending on FacePost</span>
          </div>

          <div style={{ display: 'flex', flexWrap: 'wrap', gap: 8 }}>
            {trendingTopics.map((tag, idx) => (
              <button
                key={idx}
                onClick={() => setQuery(tag)}
                style={{
                  padding: '8px 14px',
                  borderRadius: 16,
                  backgroundColor: 'var(--bg-card)',
                  border: '1px solid var(--border-color)',
                  color: 'var(--primary)',
                  fontWeight: 600,
                  fontSize: 13,
                  cursor: 'pointer'
                }}
              >
                {tag}
              </button>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
