import React, { useState } from 'react';
import { Image, Video, Smile, Globe, Users, Lock, X } from 'lucide-react';

export default function PostComposer({ 
  currentUser, 
  onAddNewPost, 
  isOpenModal, 
  onOpenModal, 
  onCloseModal 
}) {
  const [postText, setPostText] = useState('');
  const [mediaUrl, setMediaUrl] = useState('');
  const [privacy, setPrivacy] = useState('public');
  const [feeling, setFeeling] = useState('');

  const handleCreatePost = (e) => {
    e.preventDefault();
    if (!postText.trim() && !mediaUrl.trim()) return;

    const newPost = {
      id: `post_${Date.now()}`,
      author: {
        id: currentUser.id,
        name: currentUser.name,
        avatar: currentUser.avatar,
        verified: true
      },
      timeAgo: 'Just now',
      privacy,
      content: feeling ? `${postText} — is feeling ${feeling}` : postText,
      media: mediaUrl ? [mediaUrl] : [],
      reactions: {
        like: 1,
        love: 0,
        care: 0,
        haha: 0,
        wow: 0,
        sad: 0,
        angry: 0
      },
      userReaction: 'like',
      commentsCount: 0,
      sharesCount: 0,
      comments: []
    };

    onAddNewPost(newPost);
    setPostText('');
    setMediaUrl('');
    setFeeling('');
    onCloseModal();
  };

  const sampleImages = [
    "https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=800&auto=format&fit=crop&q=80",
    "https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=800&auto=format&fit=crop&q=80",
    "https://images.unsplash.com/photo-1517841905240-472988babdf9?w=800&auto=format&fit=crop&q=80"
  ];

  return (
    <>
      {/* Top Composer Trigger Box */}
      <div className="composer-card">
        <div className="composer-top">
          <div className="avatar-wrapper">
            <img src={currentUser.avatar} alt="Me" className="avatar-img" />
            <span className="avatar-online-dot" />
          </div>
          <div 
            className="composer-input-fake" 
            onClick={onOpenModal}
          >
            What's on your mind, {currentUser.name.split(' ')[0]}?
          </div>
        </div>

        <div className="composer-divider" />

        <div className="composer-actions">
          <button className="composer-btn" onClick={onOpenModal}>
            <Image size={18} color="#45bd62" />
            <span>Photo</span>
          </button>
          <button className="composer-btn" onClick={onOpenModal}>
            <Video size={18} color="#f02849" />
            <span>Reel / Video</span>
          </button>
          <button className="composer-btn" onClick={onOpenModal}>
            <Smile size={18} color="#f7b125" />
            <span>Feeling</span>
          </button>
        </div>
      </div>

      {/* Full Modal for Post Creation */}
      {isOpenModal && (
        <div className="modal-overlay" onClick={onCloseModal}>
          <div 
            className="modal-bottom-sheet" 
            style={{ maxHeight: '92vh', height: '80vh' }}
            onClick={(e) => e.stopPropagation()}
          >
            <div className="modal-header">
              <div style={{ width: 32 }} />
              <h3 className="modal-title">Create Post</h3>
              <button 
                className="icon-btn" 
                style={{ width: 32, height: 32 }} 
                onClick={onCloseModal}
              >
                <X size={18} />
              </button>
            </div>

            <div style={{ padding: '16px', flex: 1, overflowY: 'auto' }}>
              {/* User Info Bar */}
              <div style={{ display: 'flex', alignItems: 'center', gap: 12, marginBottom: 14 }}>
                <img 
                  src={currentUser.avatar} 
                  alt={currentUser.name} 
                  style={{ width: 44, height: 44, borderRadius: '50%', objectFit: 'cover' }} 
                />
                <div>
                  <div style={{ fontWeight: 700, fontSize: 15 }}>{currentUser.name}</div>
                  
                  {/* Privacy Selector */}
                  <div style={{ display: 'flex', gap: 6, marginTop: 4 }}>
                    <button
                      type="button"
                      onClick={() => setPrivacy(privacy === 'public' ? 'friends' : 'public')}
                      style={{
                        display: 'inline-flex',
                        alignItems: 'center',
                        gap: 4,
                        backgroundColor: 'var(--bg-input)',
                        color: 'var(--text-secondary)',
                        border: 'none',
                        borderRadius: 6,
                        padding: '3px 8px',
                        fontSize: 12,
                        fontWeight: 600,
                        cursor: 'pointer'
                      }}
                    >
                      {privacy === 'public' ? <Globe size={12} /> : <Users size={12} />}
                      <span style={{ textTransform: 'capitalize' }}>{privacy}</span>
                    </button>
                  </div>
                </div>
              </div>

              {/* Text Input */}
              <textarea
                placeholder={`What's on your mind, ${currentUser.name.split(' ')[0]}?`}
                value={postText}
                onChange={(e) => setPostText(e.target.value)}
                autoFocus
                style={{
                  width: '100%',
                  minHeight: 120,
                  border: 'none',
                  outline: 'none',
                  backgroundColor: 'transparent',
                  color: 'var(--text-primary)',
                  fontSize: 16,
                  fontFamily: 'inherit',
                  resize: 'none'
                }}
              />

              {/* Image Preview / Selection */}
              {mediaUrl && (
                <div style={{ position: 'relative', marginBottom: 14, borderRadius: 12, overflow: 'hidden' }}>
                  <img src={mediaUrl} alt="Upload preview" style={{ width: '100%', maxHeight: 220, objectFit: 'cover' }} />
                  <button
                    type="button"
                    onClick={() => setMediaUrl('')}
                    style={{
                      position: 'absolute',
                      top: 8,
                      right: 8,
                      background: 'rgba(0,0,0,0.7)',
                      color: 'white',
                      border: 'none',
                      borderRadius: '50%',
                      width: 28,
                      height: 28,
                      display: 'flex',
                      alignItems: 'center',
                      justifyContent: 'center',
                      cursor: 'pointer'
                    }}
                  >
                    <X size={16} />
                  </button>
                </div>
              )}

              {/* Sample Photo Pickers */}
              <div style={{ marginTop: 10 }}>
                <div style={{ fontSize: 12, fontWeight: 600, color: 'var(--text-secondary)', marginBottom: 8 }}>
                  Attach Photo:
                </div>
                <div style={{ display: 'flex', gap: 8 }}>
                  {sampleImages.map((img, i) => (
                    <img
                      key={i}
                      src={img}
                      alt="Sample"
                      onClick={() => setMediaUrl(img)}
                      style={{
                        width: 60,
                        height: 60,
                        borderRadius: 8,
                        objectFit: 'cover',
                        cursor: 'pointer',
                        border: mediaUrl === img ? '2px solid var(--primary)' : '1px solid var(--border-color)'
                      }}
                    />
                  ))}
                </div>
              </div>
            </div>

            {/* Post Submit Footer */}
            <div style={{ padding: 16, borderTop: '1px solid var(--border-color)' }}>
              <button
                className="btn-primary"
                onClick={handleCreatePost}
                disabled={!postText.trim() && !mediaUrl.trim()}
                style={{
                  width: '100%',
                  padding: '12px',
                  opacity: (!postText.trim() && !mediaUrl.trim()) ? 0.5 : 1
                }}
              >
                Post
              </button>
            </div>
          </div>
        </div>
      )}
    </>
  );
}
