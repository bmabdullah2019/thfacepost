import React, { useState, useEffect, useRef } from 'react';
import { ArrowLeft, Phone, Video, Info, Send, Image, Mic, Smile, CheckCheck } from 'lucide-react';

export default function ChatModal({ chat, onClose, onSendMessage }) {
  const [inputText, setInputText] = useState('');
  const messagesEndRef = useRef(null);

  const scrollToBottom = () => {
    messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
  };

  useEffect(() => {
    scrollToBottom();
  }, [chat?.messages]);

  if (!chat) return null;

  const handleSend = (e) => {
    e.preventDefault();
    if (!inputText.trim()) return;

    onSendMessage(chat.id, {
      id: `msg_${Date.now()}`,
      sender: 'me',
      text: inputText,
      time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
    });

    setInputText('');

    // Simulate instant auto-reply for demo
    setTimeout(() => {
      onSendMessage(chat.id, {
        id: `msg_${Date.now() + 1}`,
        sender: 'them',
        text: "Thanks for your message! Looking forward to it! 😊👍",
        time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
      });
    }, 1500);
  };

  return (
    <div style={{
      position: 'fixed',
      inset: 0,
      backgroundColor: 'var(--bg-main)',
      zIndex: 150,
      display: 'flex',
      flexDirection: 'column',
      maxWidth: 480,
      margin: '0 auto'
    }}>
      {/* Header */}
      <div style={{
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'space-between',
        padding: 'calc(var(--safe-top) + 8px) 12px 10px 8px',
        backgroundColor: 'var(--bg-header)',
        backdropFilter: 'blur(12px)',
        borderBottom: '1px solid var(--border-color)'
      }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
          <button 
            className="icon-btn" 
            onClick={onClose}
            style={{ width: 36, height: 36 }}
          >
            <ArrowLeft size={20} />
          </button>
          
          <div style={{ position: 'relative' }}>
            <img 
              src={chat.user.avatar} 
              alt={chat.user.name} 
              style={{ width: 38, height: 38, borderRadius: '50%', objectFit: 'cover' }} 
            />
            {chat.user.isOnline && (
              <span style={{
                position: 'absolute',
                bottom: 0,
                right: 0,
                width: 10,
                height: 10,
                backgroundColor: '#31a24c',
                borderRadius: '50%',
                border: '2px solid var(--bg-card)'
              }} />
            )}
          </div>

          <div>
            <div style={{ fontWeight: 700, fontSize: 14 }}>{chat.user.name}</div>
            <div style={{ fontSize: 11, color: chat.user.isOnline ? '#31a24c' : 'var(--text-muted)' }}>
              {chat.user.lastSeen || (chat.user.isOnline ? 'Active now' : 'Offline')}
            </div>
          </div>
        </div>

        <div style={{ display: 'flex', gap: 4 }}>
          <button className="icon-btn" style={{ width: 36, height: 36 }} onClick={() => alert(`Calling ${chat.user.name}...`)}>
            <Phone size={18} color="var(--primary)" />
          </button>
          <button className="icon-btn" style={{ width: 36, height: 36 }} onClick={() => alert(`Starting video call with ${chat.user.name}...`)}>
            <Video size={18} color="var(--primary)" />
          </button>
        </div>
      </div>

      {/* Messages Stream */}
      <div style={{
        flex: 1,
        overflowY: 'auto',
        padding: '14px 16px',
        display: 'flex',
        flexDirection: 'column',
        gap: 8
      }}>
        {chat.messages?.map((msg) => {
          const isMe = msg.sender === 'me';
          return (
            <div
              key={msg.id}
              style={{
                display: 'flex',
                justifyContent: isMe ? 'flex-end' : 'flex-start',
                alignItems: 'flex-end',
                gap: 6
              }}
            >
              {!isMe && (
                <img 
                  src={chat.user.avatar} 
                  alt={chat.user.name} 
                  style={{ width: 26, height: 26, borderRadius: '50%', objectFit: 'cover' }} 
                />
              )}

              <div style={{
                maxWidth: '75%',
                backgroundColor: isMe ? 'var(--primary)' : 'var(--bg-input)',
                color: isMe ? 'white' : 'var(--text-primary)',
                padding: '10px 14px',
                borderRadius: isMe ? '18px 18px 4px 18px' : '18px 18px 18px 4px',
                fontSize: 14,
                lineHeight: 1.4,
                boxShadow: 'var(--shadow-sm)'
              }}>
                <div>{msg.text}</div>
                <div style={{
                  fontSize: 10,
                  color: isMe ? 'rgba(255,255,255,0.8)' : 'var(--text-muted)',
                  textAlign: 'right',
                  marginTop: 3,
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'flex-end',
                  gap: 3
                }}>
                  <span>{msg.time}</span>
                  {isMe && <CheckCheck size={12} />}
                </div>
              </div>
            </div>
          );
        })}
        <div ref={messagesEndRef} />
      </div>

      {/* Chat Footer Input */}
      <form
        onSubmit={handleSend}
        style={{
          padding: '8px 12px calc(var(--safe-bottom) + 8px) 12px',
          backgroundColor: 'var(--bg-header)',
          borderTop: '1px solid var(--border-color)',
          display: 'flex',
          alignItems: 'center',
          gap: 6
        }}
      >
        <button type="button" className="icon-btn" style={{ width: 34, height: 34 }}>
          <Image size={18} color="var(--primary)" />
        </button>
        <button type="button" className="icon-btn" style={{ width: 34, height: 34 }}>
          <Mic size={18} color="var(--primary)" />
        </button>

        <input
          type="text"
          placeholder="Message..."
          value={inputText}
          onChange={(e) => setInputText(e.target.value)}
          style={{
            flex: 1,
            backgroundColor: 'var(--bg-input)',
            border: 'none',
            borderRadius: 22,
            padding: '9px 14px',
            color: 'var(--text-primary)',
            fontSize: 14,
            outline: 'none'
          }}
        />

        {inputText.trim() ? (
          <button
            type="submit"
            style={{
              background: 'linear-gradient(135deg, #1877f2, #00b4d8)',
              border: 'none',
              color: 'white',
              borderRadius: '50%',
              width: 36,
              height: 36,
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              cursor: 'pointer'
            }}
          >
            <Send size={16} />
          </button>
        ) : (
          <button type="button" className="icon-btn" style={{ width: 34, height: 34 }}>
            <Smile size={18} color="var(--primary)" />
          </button>
        )}
      </form>
    </div>
  );
}
