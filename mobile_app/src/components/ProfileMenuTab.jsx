import React, { useState, useRef } from 'react';
import { 
  Bookmark, Users, Clock, Film, ShoppingBag, 
  Settings, HelpCircle, Moon, Sun, LogOut, Edit3, Camera, MapPin, Briefcase, GraduationCap,
  Heart, MessageSquare, Share2, Check, X
} from 'lucide-react';
import PostCard from './PostCard';

export default function ProfileMenuTab({ 
  currentUser, 
  userPosts = [], 
  isDarkMode, 
  onToggleTheme, 
  onUpdateProfile, 
  onLogout,
  onReactPost,
  onAddComment
}) {
  const [activeSubTab, setActiveSubTab] = useState('posts');
  const [isEditingBio, setIsEditingBio] = useState(false);
  const [bioText, setBioText] = useState(currentUser.bio || '');
  
  const avatarInputRef = useRef(null);
  const coverInputRef = useRef(null);

  const handleAvatarChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onloadend = () => {
        if (onUpdateProfile) {
          onUpdateProfile({ avatar: reader.result });
        }
      };
      reader.readAsDataURL(file);
    }
  };

  const handleCoverChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onloadend = () => {
        if (onUpdateProfile) {
          onUpdateProfile({ coverPhoto: reader.result });
        }
      };
      reader.readAsDataURL(file);
    }
  };

  const handleSaveBio = () => {
    if (onUpdateProfile) {
      onUpdateProfile({ bio: bioText });
    }
    setIsEditingBio(false);
  };

  const menuItems = [
    { icon: Bookmark, label: 'Saved Posts', color: '#8a3ab9', action: () => alert('You have 3 saved bookmarks 📌') },
    { icon: Users, label: 'Groups & Hubs', color: '#1877f2', action: () => alert('Groups & Community Hubs loaded 👥') },
    { icon: Clock, label: 'Memories', color: '#00b4d8', action: () => alert('No memories today. Make some new ones! 🕰️') },
    { icon: Film, label: 'Your Reels', color: '#ff2d55', action: () => alert('Showing your created video reels 🎬') },
    { icon: ShoppingBag, label: 'Marketplace', color: '#31a24c', action: () => alert('The FacePost Marketplace is coming soon! 🛍️') }
  ];

  return (
    <div style={{ paddingBottom: 32 }}>
      {/* Hidden File Pickers for Profile & Cover */}
      <input 
        type="file" 
        ref={avatarInputRef} 
        onChange={handleAvatarChange} 
        accept="image/*" 
        style={{ display: 'none' }} 
      />
      <input 
        type="file" 
        ref={coverInputRef} 
        onChange={handleCoverChange} 
        accept="image/*" 
        style={{ display: 'none' }} 
      />

      {/* Cover & Profile Avatar Section with Correct Non-cropped Spacing */}
      <div style={{ position: 'relative', marginBottom: 56, backgroundColor: 'var(--bg-card)' }}>
        {/* Cover Photo */}
        <div style={{ position: 'relative', width: '100%', height: 180, overflow: 'hidden' }}>
          <img
            src={currentUser.coverPhoto}
            alt="Cover"
            style={{ width: '100%', height: '100%', objectFit: 'cover' }}
            onError={(e) => { e.target.src = 'https://images.unsplash.com/photo-1707343843437-caacff5cfa74?w=800&auto=format&fit=crop&q=80'; }}
          />
          {/* Change Cover Button */}
          <button 
            onClick={() => coverInputRef.current?.click()}
            style={{
              position: 'absolute',
              bottom: 12,
              right: 12,
              backgroundColor: 'rgba(0,0,0,0.65)',
              backdropFilter: 'blur(6px)',
              border: 'none',
              color: 'white',
              borderRadius: 20,
              padding: '6px 12px',
              display: 'flex',
              alignItems: 'center',
              gap: 6,
              fontSize: 12,
              fontWeight: 700,
              cursor: 'pointer'
            }}
          >
            <Camera size={14} />
            <span>Edit Cover</span>
          </button>
        </div>
        
        {/* Profile Avatar Overlap */}
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
                width: 92,
                height: 92,
                borderRadius: '50%',
                border: '4px solid var(--bg-card)',
                objectFit: 'cover',
                boxShadow: 'var(--shadow-md)'
              }}
              onError={(e) => { e.target.src = 'https://thefacepost.com/themes/flavor/images/user-red.png'; }}
            />
            {/* Change Avatar Button */}
            <button 
              onClick={() => avatarInputRef.current?.click()}
              style={{
                position: 'absolute',
                bottom: 4,
                right: 4,
                width: 30,
                height: 30,
                borderRadius: '50%',
                backgroundColor: 'var(--primary)',
                border: '2px solid var(--bg-card)',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                cursor: 'pointer',
                color: 'white'
              }}
              title="Change Profile Picture"
            >
              <Camera size={14} />
            </button>
          </div>
        </div>
      </div>

      {/* User Info & Bio */}
      <div style={{ padding: '0 16px', marginBottom: 16 }}>
        <h1 style={{ fontSize: 22, fontWeight: 800, margin: 0 }}>{currentUser.name}</h1>
        <span style={{ fontSize: 13, color: 'var(--text-muted)', fontWeight: 600 }}>@{currentUser.username}</span>

        {/* Bio Section */}
        {isEditingBio ? (
          <div style={{ marginTop: 10 }}>
            <textarea
              value={bioText}
              onChange={(e) => setBioText(e.target.value)}
              placeholder="Describe yourself..."
              style={{
                width: '100%',
                padding: '8px 12px',
                borderRadius: 8,
                border: '1px solid var(--border-color)',
                backgroundColor: 'var(--bg-input)',
                color: 'var(--text-primary)',
                fontSize: 14,
                outline: 'none',
                resize: 'none'
              }}
              rows={3}
            />
            <div style={{ display: 'flex', gap: 8, marginTop: 8 }}>
              <button 
                onClick={handleSaveBio}
                className="btn btn-primary"
                style={{ padding: '6px 16px', fontSize: 13 }}
              >
                Save Bio
              </button>
              <button 
                onClick={() => setIsEditingBio(false)}
                className="btn btn-secondary"
                style={{ padding: '6px 16px', fontSize: 13 }}
              >
                Cancel
              </button>
            </div>
          </div>
        ) : (
          <div style={{ marginTop: 8, display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between' }}>
            <p style={{ margin: 0, fontSize: 14, color: 'var(--text-secondary)', lineHeight: 1.4 }}>
              {currentUser.bio || 'Active Member of The FacePost community 🌟'}
            </p>
            <button
              onClick={() => setIsEditingBio(true)}
              style={{ background: 'none', border: 'none', color: 'var(--primary)', cursor: 'pointer', padding: 4 }}
              title="Edit Bio"
            >
              <Edit3 size={16} />
            </button>
          </div>
        )}

        {/* User Details / Meta */}
        <div style={{ marginTop: 14, display: 'flex', flexDirection: 'column', gap: 6 }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: 8, fontSize: 13, color: 'var(--text-muted)' }}>
            <MapPin size={15} />
            <span>Lives in <b>{currentUser.livesIn || 'Bangladesh'}</b></span>
          </div>
          <div style={{ display: 'flex', alignItems: 'center', gap: 8, fontSize: 13, color: 'var(--text-muted)' }}>
            <Briefcase size={15} />
            <span>Works at <b>{currentUser.work || 'The FacePost'}</b></span>
          </div>
        </div>

        {/* Follower Stats Bar */}
        <div style={{
          display: 'flex',
          gap: 16,
          marginTop: 16,
          padding: '12px 16px',
          backgroundColor: 'var(--bg-card)',
          borderRadius: 12,
          border: '1px solid var(--border-color)'
        }}>
          <div>
            <span style={{ fontSize: 16, fontWeight: 800 }}>{currentUser.friendsCount || '12'}</span>
            <span style={{ fontSize: 12, color: 'var(--text-muted)', display: 'block' }}>Friends</span>
          </div>
          <div style={{ width: 1, backgroundColor: 'var(--border-color)' }} />
          <div>
            <span style={{ fontSize: 16, fontWeight: 800 }}>{currentUser.followersCount || '45'}</span>
            <span style={{ fontSize: 12, color: 'var(--text-muted)', display: 'block' }}>Followers</span>
          </div>
          <div style={{ width: 1, backgroundColor: 'var(--border-color)' }} />
          <div>
            <span style={{ fontSize: 16, fontWeight: 800 }}>{currentUser.followingCount || '20'}</span>
            <span style={{ fontSize: 12, color: 'var(--text-muted)', display: 'block' }}>Following</span>
          </div>
        </div>
      </div>

      {/* Sub Tabs: Posts vs Menu & Settings */}
      <div style={{ padding: '0 16px', marginBottom: 16 }}>
        <div style={{ display: 'flex', gap: 8, borderBottom: '1px solid var(--border-color)', paddingBottom: 8 }}>
          <button
            onClick={() => setActiveSubTab('posts')}
            style={{
              flex: 1,
              padding: '8px 0',
              borderRadius: 8,
              border: 'none',
              fontWeight: 700,
              fontSize: 14,
              cursor: 'pointer',
              backgroundColor: activeSubTab === 'posts' ? 'var(--primary)' : 'var(--bg-input)',
              color: activeSubTab === 'posts' ? 'white' : 'var(--text-secondary)'
            }}
          >
            My Wall Posts
          </button>
          <button
            onClick={() => setActiveSubTab('shortcuts')}
            style={{
              flex: 1,
              padding: '8px 0',
              borderRadius: 8,
              border: 'none',
              fontWeight: 700,
              fontSize: 14,
              cursor: 'pointer',
              backgroundColor: activeSubTab === 'shortcuts' ? 'var(--primary)' : 'var(--bg-input)',
              color: activeSubTab === 'shortcuts' ? 'white' : 'var(--text-secondary)'
            }}
          >
            Shortcuts & Settings
          </button>
        </div>
      </div>

      {/* Tab 1: User's Own Posts Feed */}
      {activeSubTab === 'posts' && (
        <div style={{ display: 'flex', flexDirection: 'column' }}>
          {userPosts.length > 0 ? (
            userPosts.map(post => (
              <PostCard
                key={post.id}
                post={post}
                currentUser={currentUser}
                onReact={onReactPost}
                onAddComment={onAddComment}
              />
            ))
          ) : (
            <div style={{ textAlign: 'center', padding: '32px 16px', color: 'var(--text-muted)' }}>
              <p style={{ margin: 0, fontSize: 14 }}>No wall posts yet. Share your first moment!</p>
            </div>
          )}
        </div>
      )}

      {/* Tab 2: Shortcuts & Settings Grid */}
      {activeSubTab === 'shortcuts' && (
        <div style={{ padding: '0 16px' }}>
          {/* Shortcuts Grid */}
          <div style={{
            display: 'grid',
            gridTemplateColumns: 'repeat(2, 1fr)',
            gap: 10,
            marginBottom: 20
          }}>
            {menuItems.map((item, idx) => {
              const IconComp = item.icon;
              return (
                <div
                  key={idx}
                  onClick={item.action}
                  style={{
                    backgroundColor: 'var(--bg-card)',
                    borderRadius: 12,
                    padding: 14,
                    border: '1px solid var(--border-color)',
                    cursor: 'pointer',
                    display: 'flex',
                    flexDirection: 'column',
                    gap: 8,
                    boxShadow: 'var(--shadow-sm)'
                  }}
                >
                  <div style={{
                    width: 36,
                    height: 36,
                    borderRadius: 10,
                    backgroundColor: `${item.color}15`,
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center'
                  }}>
                    <IconComp size={20} color={item.color} />
                  </div>
                  <span style={{ fontSize: 14, fontWeight: 700 }}>{item.label}</span>
                </div>
              );
            })}
          </div>

          {/* Quick Theme Switcher */}
          <div 
            onClick={onToggleTheme}
            style={{
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'space-between',
              padding: '14px 16px',
              backgroundColor: 'var(--bg-card)',
              borderRadius: 12,
              border: '1px solid var(--border-color)',
              marginBottom: 10,
              cursor: 'pointer'
            }}
          >
            <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
              {isDarkMode ? <Moon size={20} color="#8a3ab9" /> : <Sun size={20} color="#f7b125" />}
              <span style={{ fontSize: 15, fontWeight: 600 }}>{isDarkMode ? 'Dark Mode' : 'Light Mode'}</span>
            </div>
            <span style={{ fontSize: 13, color: 'var(--primary)', fontWeight: 700 }}>
              {isDarkMode ? 'ON' : 'OFF'}
            </span>
          </div>

          {/* Logout Button */}
          <button
            onClick={onLogout}
            style={{
              width: '100%',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              gap: 8,
              padding: '14px 0',
              backgroundColor: 'var(--bg-card)',
              border: '1px solid var(--border-color)',
              borderRadius: 12,
              color: '#e41e3f',
              fontSize: 15,
              fontWeight: 700,
              cursor: 'pointer',
              marginTop: 10
            }}
          >
            <LogOut size={18} />
            <span>Log Out</span>
          </button>
        </div>
      )}
    </div>
  );
}
