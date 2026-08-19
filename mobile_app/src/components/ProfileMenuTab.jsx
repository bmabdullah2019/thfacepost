import React from 'react';
import { 
  Bookmark, Users, Clock, Flag, Film, ShoppingBag, 
  Settings, HelpCircle, Moon, LogOut, Edit3, Camera, MapPin, Briefcase, GraduationCap 
} from 'lucide-react';

export default function ProfileMenuTab({ currentUser, isDarkMode, onToggleTheme, onAddStory }) {
  const menuItems = [
    { icon: Bookmark, label: 'Saved Posts', color: '#8a3ab9' },
    { icon: Users, label: 'Groups & Hubs', color: '#1877f2' },
    { icon: Clock, label: 'Memories', color: '#00b4d8' },
    { icon: Film, label: 'Your Reels', color: '#ff2d55' },
    { icon: ShoppingBag, label: 'Marketplace', color: '#31a24c' },
    { icon: Flag, label: 'Pages', color: '#f77737' }
  ];

  return (
    <div style={{ paddingBottom: 24 }}>
      {/* Cover & Profile Avatar */}
      <div style={{ position: 'relative', marginBottom: 54 }}>
        <img
          src={currentUser.coverPhoto}
          alt="Cover"
          style={{ width: '100%', height: 160, objectFit: 'cover' }}
        />
        
        <div style={{
          position: 'absolute',
          bottom: -46,
          left: 16
        }}>
          <div style={{ position: 'relative', display: 'inline-block' }}>
            <img
              src={currentUser.avatar}
              alt={currentUser.name}
              style={{
                width: 90,
                height: 90,
                borderRadius: '50%',
                border: '4px solid var(--bg-card)',
                objectFit: 'cover',
                boxShadow: 'var(--shadow-md)'
              }}
            />
            <button style={{
              position: 'absolute',
              bottom: 4,
              right: 4,
              width: 28,
              height: 28,
              borderRadius: '50%',
              backgroundColor: 'var(--bg-input)',
              border: '2px solid var(--bg-card)',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              cursor: 'pointer',
              color: 'var(--text-primary)'
            }}>
              <Camera size={14} />
            </button>
          </div>
        </div>
      </div>

      {/* User Info */}
      <div style={{ padding: '0 16px', marginBottom: 16 }}>
        <h2 style={{ fontSize: 22, fontWeight: 800, marginBottom: 2 }}>{currentUser.name}</h2>
        <div style={{ fontSize: 13, color: 'var(--text-secondary)', marginBottom: 8 }}>@{currentUser.username}</div>
        <p style={{ fontSize: 14, lineHeight: 1.4, marginBottom: 12 }}>{currentUser.bio}</p>

        {/* Profile Details */}
        <div style={{ display: 'flex', flexDirection: 'column', gap: 6, fontSize: 13, color: 'var(--text-secondary)', marginBottom: 16 }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
            <Briefcase size={16} /> <span>{currentUser.work}</span>
          </div>
          <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
            <GraduationCap size={16} /> <span>{currentUser.education}</span>
          </div>
          <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
            <MapPin size={16} /> <span>Lives in <b>{currentUser.livesIn}</b></span>
          </div>
        </div>

        {/* Stats Row */}
        <div style={{
          display: 'flex',
          justifyContent: 'space-around',
          backgroundColor: 'var(--bg-input)',
          borderRadius: 14,
          padding: '12px 8px',
          marginBottom: 16
        }}>
          <div style={{ textAlign: 'center' }}>
            <div style={{ fontWeight: 800, fontSize: 16 }}>{currentUser.followersCount}</div>
            <div style={{ fontSize: 12, color: 'var(--text-secondary)' }}>Followers</div>
          </div>
          <div style={{ width: 1, backgroundColor: 'var(--border-color)' }} />
          <div style={{ textAlign: 'center' }}>
            <div style={{ fontWeight: 800, fontSize: 16 }}>{currentUser.friendsCount}</div>
            <div style={{ fontSize: 12, color: 'var(--text-secondary)' }}>Friends</div>
          </div>
          <div style={{ width: 1, backgroundColor: 'var(--border-color)' }} />
          <div style={{ textAlign: 'center' }}>
            <div style={{ fontWeight: 800, fontSize: 16 }}>{currentUser.followingCount}</div>
            <div style={{ fontSize: 12, color: 'var(--text-secondary)' }}>Following</div>
          </div>
        </div>

        {/* Action Buttons */}
        <div style={{ display: 'flex', gap: 8 }}>
          <button 
            className="btn-primary" 
            style={{ flex: 1, display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 6 }}
            onClick={onAddStory}
          >
            <span>+ Add to Story</span>
          </button>
          <button style={{
            flex: 1,
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            gap: 6,
            backgroundColor: 'var(--bg-input)',
            border: 'none',
            borderRadius: 12,
            fontWeight: 700,
            fontSize: 14,
            color: 'var(--text-primary)',
            cursor: 'pointer'
          }}>
            <Edit3 size={16} />
            <span>Edit Profile</span>
          </button>
        </div>
      </div>

      <div style={{ height: 8, backgroundColor: 'var(--bg-main)' }} />

      {/* Menu Shortcuts */}
      <div style={{ padding: '16px' }}>
        <h3 style={{ fontSize: 16, fontWeight: 700, marginBottom: 12 }}>Shortcuts</h3>
        
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 10, marginBottom: 20 }}>
          {menuItems.map((item, idx) => {
            const IconComp = item.icon;
            return (
              <div
                key={idx}
                style={{
                  backgroundColor: 'var(--bg-card)',
                  border: '1px solid var(--border-color)',
                  borderRadius: 12,
                  padding: '12px 14px',
                  display: 'flex',
                  alignItems: 'center',
                  gap: 10,
                  cursor: 'pointer',
                  boxShadow: 'var(--shadow-sm)'
                }}
              >
                <div style={{
                  width: 34,
                  height: 34,
                  borderRadius: 8,
                  backgroundColor: 'var(--bg-input)',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  color: item.color
                }}>
                  <IconComp size={20} />
                </div>
                <span style={{ fontSize: 13, fontWeight: 600 }}>{item.label}</span>
              </div>
            );
          })}
        </div>

        {/* System Settings & Theme Toggle */}
        <h3 style={{ fontSize: 16, fontWeight: 700, marginBottom: 12 }}>Preferences</h3>
        
        <div style={{ display: 'flex', flexDirection: 'column', gap: 6 }}>
          <div
            onClick={onToggleTheme}
            style={{
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'space-between',
              padding: '12px',
              backgroundColor: 'var(--bg-card)',
              borderRadius: 12,
              border: '1px solid var(--border-color)',
              cursor: 'pointer'
            }}
          >
            <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
              <Moon size={20} color="var(--primary)" />
              <span style={{ fontSize: 14, fontWeight: 600 }}>Dark Mode</span>
            </div>
            <span style={{ fontSize: 13, color: 'var(--text-secondary)', fontWeight: 600 }}>
              {isDarkMode ? 'ON' : 'OFF'}
            </span>
          </div>

          <div
            style={{
              display: 'flex',
              alignItems: 'center',
              gap: 10,
              padding: '12px',
              backgroundColor: 'var(--bg-card)',
              borderRadius: 12,
              border: '1px solid var(--border-color)',
              cursor: 'pointer'
            }}
          >
            <Settings size={20} color="var(--text-secondary)" />
            <span style={{ fontSize: 14, fontWeight: 600 }}>Settings & Privacy</span>
          </div>

          <div
            style={{
              display: 'flex',
              alignItems: 'center',
              gap: 10,
              padding: '12px',
              backgroundColor: 'var(--bg-card)',
              borderRadius: 12,
              border: '1px solid var(--border-color)',
              cursor: 'pointer',
              color: '#ff2d55'
            }}
          >
            <LogOut size={20} />
            <span style={{ fontSize: 14, fontWeight: 700 }}>Log Out</span>
          </div>
        </div>
      </div>
    </div>
  );
}
