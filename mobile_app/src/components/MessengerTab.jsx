import React, { useState } from 'react';
import { Search, Edit, CheckCheck } from 'lucide-react';
import ChatModal from './ChatModal';

export default function MessengerTab({ chats, onSelectChat, onSendMessage, activeChat, onCloseActiveChat }) {
  const [searchQuery, setSearchQuery] = useState('');

  const filteredChats = chats.filter(chat => 
    chat.user.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
    chat.lastMessage?.text?.toLowerCase().includes(searchQuery.toLowerCase())
  );

  return (
    <div className="messenger-container">
      {/* Messenger Header & Search */}
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: 12 }}>
        <h2 style={{ fontSize: 22, fontWeight: 800 }}>Chats</h2>
        <button className="icon-btn" style={{ width: 36, height: 36 }}>
          <Edit size={18} />
        </button>
      </div>

      <div style={{
        display: 'flex',
        alignItems: 'center',
        gap: 8,
        backgroundColor: 'var(--bg-input)',
        borderRadius: 20,
        padding: '8px 14px',
        marginBottom: 16
      }}>
        <Search size={18} color="var(--text-muted)" />
        <input
          type="text"
          placeholder="Search Messenger..."
          value={searchQuery}
          onChange={(e) => setSearchQuery(e.target.value)}
          style={{
            border: 'none',
            background: 'none',
            outline: 'none',
            color: 'var(--text-primary)',
            fontSize: 14,
            width: '100%'
          }}
        />
      </div>

      {/* Online Friends Carousel */}
      <div className="online-users-tray">
        {chats.map(chat => (
          <div 
            key={chat.id} 
            className="online-user-item"
            onClick={() => onSelectChat(chat.id)}
          >
            <div style={{ position: 'relative' }}>
              <img 
                src={chat.user.avatar} 
                alt={chat.user.name} 
                style={{ width: 50, height: 50, borderRadius: '50%', objectFit: 'cover' }} 
              />
              {chat.user.isOnline && (
                <span style={{
                  position: 'absolute',
                  bottom: 2,
                  right: 2,
                  width: 12,
                  height: 12,
                  backgroundColor: '#31a24c',
                  borderRadius: '50%',
                  border: '2px solid var(--bg-card)'
                }} />
              )}
            </div>
            <span className="online-user-name">{chat.user.name.split(' ')[0]}</span>
          </div>
        ))}
      </div>

      {/* Message Threads List */}
      <div style={{ display: 'flex', flexDirection: 'column', gap: 4 }}>
        {filteredChats.map(chat => (
          <div
            key={chat.id}
            className="chat-thread-item"
            onClick={() => onSelectChat(chat.id)}
          >
            <div style={{ position: 'relative', flexShrink: 0 }}>
              <img 
                src={chat.user.avatar} 
                alt={chat.user.name} 
                style={{ width: 54, height: 54, borderRadius: '50%', objectFit: 'cover' }} 
              />
              {chat.user.isOnline && (
                <span style={{
                  position: 'absolute',
                  bottom: 2,
                  right: 2,
                  width: 13,
                  height: 13,
                  backgroundColor: '#31a24c',
                  borderRadius: '50%',
                  border: '2px solid var(--bg-card)'
                }} />
              )}
            </div>

            <div className="chat-thread-content">
              <div className="chat-thread-header">
                <span className="chat-thread-name">{chat.user.name}</span>
                <span className="chat-thread-time">{chat.lastMessage?.timestamp}</span>
              </div>
              
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                <p className={`chat-thread-last-msg ${chat.unreadCount > 0 ? 'unread' : ''}`}>
                  {chat.lastMessage?.text}
                </p>
                {chat.unreadCount > 0 && (
                  <span className="badge-pill" style={{ position: 'static' }}>
                    {chat.unreadCount}
                  </span>
                )}
              </div>
            </div>
          </div>
        ))}
      </div>

      {/* Active Chat Window Modal */}
      {activeChat && (
        <ChatModal
          chat={activeChat}
          onClose={onCloseActiveChat}
          onSendMessage={onSendMessage}
        />
      )}
    </div>
  );
}
