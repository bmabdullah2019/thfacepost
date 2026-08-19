import React from 'react';
import { Home, Film, MessageCircle, Bell, User } from 'lucide-react';

export default function Navigation({ activeTab, setActiveTab, unreadNotifCount, unreadMsgCount, currentUser }) {
  const tabs = [
    { id: 'feed', label: 'Feed', icon: Home },
    { id: 'reels', label: 'Reels', icon: Film, badge: 'HD' },
    { id: 'messages', label: 'Chats', icon: MessageCircle, count: unreadMsgCount },
    { id: 'notifications', label: 'Alerts', icon: Bell, count: unreadNotifCount },
    { id: 'profile', label: 'Profile', icon: User, avatar: currentUser?.avatar }
  ];

  return (
    <nav className="tab-nav">
      {tabs.map((tab) => {
        const IconComponent = tab.icon;
        const isActive = activeTab === tab.id;

        return (
          <div
            key={tab.id}
            className={`nav-tab-item ${isActive ? 'active' : ''}`}
            onClick={() => setActiveTab(tab.id)}
          >
            <div style={{ position: 'relative', display: 'inline-flex' }}>
              {tab.avatar ? (
                <div style={{ 
                  width: 26, 
                  height: 26, 
                  borderRadius: '50%', 
                  overflow: 'hidden', 
                  border: isActive ? '2px solid var(--primary)' : '1.5px solid var(--text-secondary)' 
                }}>
                  <img src={tab.avatar} alt="Profile" style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                </div>
              ) : (
                <IconComponent size={24} strokeWidth={isActive ? 2.6 : 2} />
              )}

              {/* Number Badge */}
              {tab.count > 0 && (
                <span className="badge-pill" style={{ top: -6, right: -10, fontSize: 10, height: 16, minWidth: 16 }}>
                  {tab.count > 9 ? '9+' : tab.count}
                </span>
              )}

              {/* Text Badge for Reels */}
              {tab.badge && !tab.count && (
                <span style={{
                  position: 'absolute',
                  top: -6,
                  right: -12,
                  backgroundColor: '#ff2d55',
                  color: 'white',
                  fontSize: 9,
                  fontWeight: 800,
                  padding: '1px 3px',
                  borderRadius: 4
                }}>
                  {tab.badge}
                </span>
              )}
            </div>
          </div>
        );
      })}
    </nav>
  );
}
