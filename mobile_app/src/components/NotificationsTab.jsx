import React, { useState } from 'react';
import { CheckCheck, Heart, MessageSquare, UserPlus, AtSign, Users, MoreHorizontal } from 'lucide-react';

export default function NotificationsTab({ notifications, onMarkAllAsRead, onAcceptFriendRequest }) {
  const [filter, setFilter] = useState('all');

  const filteredNotifs = notifications.filter(n => {
    if (filter === 'unread') return n.unread;
    if (filter === 'requests') return n.type === 'friend_request';
    if (filter === 'mentions') return n.type === 'mention';
    return true;
  });

  return (
    <div style={{ padding: '12px 16px' }}>
      {/* Title & Actions */}
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: 12 }}>
        <h2 style={{ fontSize: 22, fontWeight: 800 }}>Notifications</h2>
        <button
          onClick={onMarkAllAsRead}
          style={{
            background: 'none',
            border: 'none',
            color: 'var(--primary)',
            fontSize: 13,
            fontWeight: 600,
            cursor: 'pointer',
            display: 'flex',
            alignItems: 'center',
            gap: 4
          }}
        >
          <CheckCheck size={16} /> Mark all read
        </button>
      </div>

      {/* Filter Tabs */}
      <div style={{ display: 'flex', gap: 8, marginBottom: 16, overflowX: 'auto', paddingBottom: 4 }}>
        {[
          { id: 'all', label: 'All' },
          { id: 'unread', label: 'Unread' },
          { id: 'requests', label: 'Friend Requests' },
          { id: 'mentions', label: 'Mentions' }
        ].map(item => (
          <button
            key={item.id}
            onClick={() => setFilter(item.id)}
            style={{
              padding: '6px 14px',
              borderRadius: 20,
              fontSize: 13,
              fontWeight: 600,
              border: 'none',
              cursor: 'pointer',
              whiteSpace: 'nowrap',
              backgroundColor: filter === item.id ? 'var(--primary)' : 'var(--bg-input)',
              color: filter === item.id ? 'white' : 'var(--text-secondary)'
            }}
          >
            {item.label}
          </button>
        ))}
      </div>

      {/* Notifications List */}
      <div style={{ display: 'flex', flexDirection: 'column', gap: 6 }}>
        {filteredNotifs.map(notif => (
          <div
            key={notif.id}
            style={{
              display: 'flex',
              gap: 12,
              padding: '10px 12px',
              borderRadius: 12,
              backgroundColor: notif.unread ? 'var(--primary-light)' : 'transparent',
              transition: 'background-color 0.15s ease'
            }}
          >
            {/* Avatar with Type Badge */}
            <div style={{ position: 'relative', flexShrink: 0 }}>
              <img
                src={notif.user.avatar}
                alt={notif.user.name}
                style={{ width: 48, height: 48, borderRadius: '50%', objectFit: 'cover' }}
              />
              <span style={{
                position: 'absolute',
                bottom: -2,
                right: -2,
                width: 20,
                height: 20,
                borderRadius: '50%',
                backgroundColor: notif.type === 'reaction' ? '#ff2d55' : notif.type === 'friend_request' ? '#1877f2' : '#31a24c',
                color: 'white',
                fontSize: 10,
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                border: '2px solid var(--bg-card)'
              }}>
                {notif.type === 'reaction' ? '❤️' : notif.type === 'friend_request' ? '👥' : '💬'}
              </span>
            </div>

            {/* Content Area */}
            <div style={{ flex: 1 }}>
              <div style={{ fontSize: 13.5, lineHeight: 1.35 }}>
                <span style={{ fontWeight: 700 }}>{notif.user.name}</span>{' '}
                <span style={{ color: 'var(--text-secondary)' }}>{notif.text}</span>
              </div>
              <div style={{ fontSize: 11.5, color: 'var(--text-muted)', marginTop: 4 }}>
                {notif.timeAgo}
              </div>

              {/* Action buttons for Friend Request */}
              {notif.actionNeeded && (
                <div style={{ display: 'flex', gap: 8, marginTop: 8 }}>
                  <button
                    className="btn-primary"
                    style={{ padding: '6px 16px', fontSize: 13 }}
                    onClick={() => onAcceptFriendRequest(notif.id)}
                  >
                    Confirm
                  </button>
                  <button
                    style={{
                      padding: '6px 14px',
                      borderRadius: 8,
                      border: 'none',
                      backgroundColor: 'var(--bg-input)',
                      color: 'var(--text-primary)',
                      fontSize: 13,
                      fontWeight: 600,
                      cursor: 'pointer'
                    }}
                    onClick={() => onAcceptFriendRequest(notif.id)}
                  >
                    Delete
                  </button>
                </div>
              )}
            </div>

            {notif.unread && (
              <span style={{ width: 8, height: 8, borderRadius: '50%', backgroundColor: 'var(--primary)', alignSelf: 'center' }} />
            )}
          </div>
        ))}
      </div>
    </div>
  );
}
