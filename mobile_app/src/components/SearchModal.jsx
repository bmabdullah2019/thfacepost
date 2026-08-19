import React, { useState, useEffect } from 'react';
import { ArrowLeft, Search, X, Clock, TrendingUp, User } from 'lucide-react';
import { searchUsersAndPosts } from '../services/api';

export default function SearchModal({ onClose, onSelectResult }) {
  const [query, setQuery] = useState('');
  const [results, setResults] = useState([]);
  const [isSearching, setIsSearching] = useState(false);

  const recentSearches = [
    'theface',
    'Saiful',
    'MohammadAli',
    'The FacePost Updates'
  ];

  const trendingTopics = [
    '#TheFacePost',
    '#TechInnovation',
    '#DhakaVibes',
    '#Community'
  ];

  useEffect(() => {
    if (!query.trim()) {
      setResults([]);
      return;
    }

    const timer = setTimeout(async () => {
      setIsSearching(true);
      try {
        const data = await searchUsersAndPosts(query);
        setResults(data || []);
      } catch (err) {
        console.warn('Search failed:', err);
      } finally {
        setIsSearching(false);
      }
    }, 300);

    return () => clearTimeout(timer);
  }, [query]);

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
              background: 'transparent',
              outline: 'none',
              color: 'var(--text-primary)',
              fontSize: 14
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

      {/* Results View */}
      <div style={{ flex: 1, overflowY: 'auto', padding: '12px 16px' }}>
        {query ? (
          <div>
            <span style={{ fontSize: 12, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase', letterSpacing: 0.5 }}>
              {isSearching ? 'Searching...' : `Search Results (${results.length})`}
            </span>

            <div style={{ marginTop: 8, display: 'flex', flexDirection: 'column', gap: 4 }}>
              {results.map((res, i) => (
                <div 
                  key={res.id || i}
                  onClick={() => onSelectResult && onSelectResult(res)}
                  style={{
                    display: 'flex',
                    alignItems: 'center',
                    gap: 12,
                    padding: '10px 12px',
                    borderRadius: 10,
                    cursor: 'pointer',
                    backgroundColor: 'var(--bg-card)',
                    border: '1px solid var(--border-color)'
                  }}
                >
                  <img
                    src={res.avatar}
                    alt={res.title}
                    style={{ width: 40, height: 40, borderRadius: '50%', objectFit: 'cover' }}
                    onError={(e) => { e.target.src = 'https://thefacepost.com/themes/flavor/images/user-red.png'; }}
                  />
                  <div>
                    <div style={{ fontWeight: 600, fontSize: 14 }}>{res.title}</div>
                    <div style={{ fontSize: 12, color: 'var(--text-muted)' }}>{res.subtitle}</div>
                  </div>
                </div>
              ))}

              {!isSearching && results.length === 0 && (
                <div style={{ textAlign: 'center', padding: '32px 0', color: 'var(--text-muted)', fontSize: 14 }}>
                  No members or posts found for "{query}"
                </div>
              )}
            </div>
          </div>
        ) : (
          <>
            {/* Recent Searches */}
            <div style={{ marginBottom: 24 }}>
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 10 }}>
                <span style={{ fontSize: 13, fontWeight: 700, color: 'var(--text-secondary)' }}>Recent</span>
                <button style={{ border: 'none', background: 'none', color: 'var(--primary)', fontSize: 12, fontWeight: 600, cursor: 'pointer' }}>
                  Edit
                </button>
              </div>

              <div style={{ display: 'flex', flexDirection: 'column', gap: 6 }}>
                {recentSearches.map((item, idx) => (
                  <div 
                    key={idx}
                    onClick={() => setQuery(item)}
                    style={{
                      display: 'flex',
                      alignItems: 'center',
                      gap: 12,
                      padding: '8px 10px',
                      borderRadius: 8,
                      cursor: 'pointer',
                      fontSize: 14,
                      color: 'var(--text-primary)'
                    }}
                  >
                    <Clock size={16} color="var(--text-muted)" />
                    <span style={{ flex: 1 }}>{item}</span>
                  </div>
                ))}
              </div>
            </div>

            {/* Trending */}
            <div>
              <span style={{ fontSize: 13, fontWeight: 700, color: 'var(--text-secondary)', display: 'block', marginBottom: 10 }}>
                Trending on FacePost
              </span>
              <div style={{ display: 'flex', flexWrap: 'wrap', gap: 8 }}>
                {trendingTopics.map((topic, i) => (
                  <div 
                    key={i}
                    onClick={() => setQuery(topic)}
                    style={{
                      display: 'flex',
                      alignItems: 'center',
                      gap: 6,
                      padding: '6px 12px',
                      borderRadius: 16,
                      backgroundColor: 'var(--bg-input)',
                      fontSize: 13,
                      fontWeight: 600,
                      color: 'var(--primary)',
                      cursor: 'pointer'
                    }}
                  >
                    <TrendingUp size={14} />
                    <span>{topic}</span>
                  </div>
                ))}
              </div>
            </div>
          </>
        )}
      </div>
    </div>
  );
}
