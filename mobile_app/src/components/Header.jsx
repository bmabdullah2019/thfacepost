import React from 'react';
import { Plus, Search, MessageSquare, Moon, Sun } from 'lucide-react';

export default function Header({ 
  onOpenCreatePost, 
  onOpenSearch, 
  onNavigateToMessages,
  unreadMessagesCount,
  isDarkMode, 
  onToggleTheme 
}) {
  return (
    <header className="top-header">
      <div className="brand-title" style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
        <img 
          src="/app-icon.png" 
          alt="TFP" 
          style={{ width: 28, height: 28, borderRadius: 7, objectFit: 'cover' }} 
        />
        <span>facepost</span>
        <span className="brand-dot" title="Online & Connected" />
      </div>

      <div className="header-actions">
        {/* Quick Theme Switcher */}
        <button 
          className="icon-btn" 
          onClick={onToggleTheme}
          title={isDarkMode ? "Switch to Light Mode" : "Switch to Dark Mode"}
          aria-label="Toggle Theme"
        >
          {isDarkMode ? <Sun size={20} className="text-yellow-400" /> : <Moon size={20} />}
        </button>

        {/* Quick Create Post */}
        <button 
          className="icon-btn" 
          onClick={onOpenCreatePost}
          title="Create Post"
          aria-label="Create Post"
        >
          <Plus size={22} />
        </button>

        {/* Instant Search */}
        <button 
          className="icon-btn" 
          onClick={onOpenSearch}
          title="Search"
          aria-label="Search"
        >
          <Search size={20} />
        </button>

        {/* Quick Messenger */}
        <button 
          className="icon-btn" 
          onClick={onNavigateToMessages}
          title="Messages"
          aria-label="Messages"
        >
          <MessageSquare size={20} />
          {unreadMessagesCount > 0 && (
            <span className="badge-pill">{unreadMessagesCount}</span>
          )}
        </button>
      </div>
    </header>
  );
}
