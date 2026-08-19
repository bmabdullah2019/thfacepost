import React, { useState } from 'react';
import { 
  ThumbsUp, MessageSquare, Share2, MoreHorizontal, 
  Globe, Users, CheckCircle2, Send, X, Heart
} from 'lucide-react';

const REACTION_CONFIG = {
  like: { label: 'Like', icon: '👍', color: '#1877f2', class: 'reacted-like' },
  love: { label: 'Love', icon: '❤️', color: '#f33e5b', class: 'reacted-love' },
  care: { label: 'Care', icon: '🥰', color: '#f7b125', class: 'reacted-care' },
  haha: { label: 'Haha', icon: '😆', color: '#f7b125', class: 'reacted-haha' },
  wow: { label: 'Wow', icon: '😮', color: '#f7b125', class: 'reacted-wow' },
  sad: { label: 'Sad', icon: '😢', color: '#f7b125', class: 'reacted-sad' },
  angry: { label: 'Angry', icon: '😡', color: '#e41e3f', class: 'reacted-angry' },
};

export default function PostCard({ post, onReact, onAddComment, currentUser }) {
  const [showReactionPicker, setShowReactionPicker] = useState(false);
  const [showCommentsModal, setShowCommentsModal] = useState(false);
  const [commentInput, setCommentInput] = useState('');
  const [showShareModal, setShowShareModal] = useState(false);

  const totalReactions = Object.values(post.reactions || {}).reduce((a, b) => a + b, 0) + (post.userReaction ? 1 : 0);

  const handleSelectReaction = (type) => {
    onReact(post.id, type);
    setShowReactionPicker(false);
  };

  const handleQuickLike = () => {
    if (post.userReaction) {
      onReact(post.id, null); // remove reaction
    } else {
      onReact(post.id, 'like');
    }
  };

  const handlePostComment = (e) => {
    e.preventDefault();
    if (!commentInput.trim()) return;

    const newComment = {
      id: `c_${Date.now()}`,
      author: {
        name: currentUser.name,
        avatar: currentUser.avatar
      },
      text: commentInput,
      timeAgo: 'Just now',
      likes: 0
    };

    onAddComment(post.id, newComment);
    setCommentInput('');
  };

  const currentReactionInfo = post.userReaction ? REACTION_CONFIG[post.userReaction] : null;

  return (
    <article className="post-card">
      {/* Post Header */}
      <div className="post-header">
        <div className="post-author-info">
          <img 
            src={post.author.avatar} 
            alt={post.author.name} 
            style={{ width: 40, height: 40, borderRadius: '50%', objectFit: 'cover' }} 
          />
          <div>
            <div className="post-author-name">
              <span>{post.author.name}</span>
              {post.author.verified && <CheckCircle2 size={15} color="#1877f2" fill="#1877f2" stroke="#fff" />}
              {post.author.badge && (
                <span style={{ 
                  fontSize: 10, 
                  backgroundColor: 'var(--primary-light)', 
                  color: 'var(--primary)', 
                  padding: '1px 6px', 
                  borderRadius: 6,
                  fontWeight: 600
                }}>
                  {post.author.badge}
                </span>
              )}
            </div>
            <div className="post-meta">
              <span>{post.timeAgo}</span>
              <span>•</span>
              {post.privacy === 'friends' ? <Users size={12} /> : <Globe size={12} />}
            </div>
          </div>
        </div>

        <button className="icon-btn" style={{ width: 32, height: 32 }}>
          <MoreHorizontal size={18} />
        </button>
      </div>

      {/* Post Content */}
      <div className="post-content">
        {post.content}
      </div>

      {/* Post Media */}
      {post.media && post.media.length > 0 && (
        <div className="post-media-grid">
          {post.media.map((imgUrl, i) => (
            <img key={i} src={imgUrl} alt="Post content" loading="lazy" />
          ))}
        </div>
      )}

      {/* Post Reaction & Comment Stats */}
      <div className="post-stats">
        <div className="reactions-preview">
          <div className="reaction-icons-stack">
            <span>👍</span>
            <span>❤️</span>
            {totalReactions > 5 && <span>🥰</span>}
          </div>
          <span style={{ fontWeight: 600, marginLeft: 4 }}>{totalReactions}</span>
        </div>

        <div style={{ display: 'flex', gap: 12 }}>
          <span 
            style={{ cursor: 'pointer' }} 
            onClick={() => setShowCommentsModal(true)}
          >
            {(post.comments?.length || 0) + (post.commentsCount || 0)} comments
          </span>
          <span>{post.sharesCount || 0} shares</span>
        </div>
      </div>

      {/* Reaction Hover/Floating Picker */}
      {showReactionPicker && (
        <div 
          className="reaction-floating-popup"
          onMouseLeave={() => setShowReactionPicker(false)}
        >
          {Object.entries(REACTION_CONFIG).map(([type, config]) => (
            <button
              key={type}
              className="reaction-emoji-btn"
              onClick={() => handleSelectReaction(type)}
              title={config.label}
            >
              {config.icon}
            </button>
          ))}
        </div>
      )}

      {/* Action Buttons Bar */}
      <div className="post-actions-bar">
        <button
          className={`action-bar-btn ${currentReactionInfo ? currentReactionInfo.class : ''}`}
          onClick={handleQuickLike}
          onContextMenu={(e) => {
            e.preventDefault();
            setShowReactionPicker(!showReactionPicker);
          }}
          onTouchStart={() => {
            const timer = setTimeout(() => setShowReactionPicker(true), 400);
            window._reactTimer = timer;
          }}
          onTouchEnd={() => {
            if (window._reactTimer) clearTimeout(window._reactTimer);
          }}
        >
          {currentReactionInfo ? (
            <>
              <span style={{ fontSize: 16 }}>{currentReactionInfo.icon}</span>
              <span>{currentReactionInfo.label}</span>
            </>
          ) : (
            <>
              <ThumbsUp size={18} />
              <span>Like</span>
            </>
          )}
        </button>

        <button 
          className="action-bar-btn"
          onClick={() => setShowCommentsModal(true)}
        >
          <MessageSquare size={18} />
          <span>Comment</span>
        </button>

        <button 
          className="action-bar-btn"
          onClick={() => setShowShareModal(true)}
        >
          <Share2 size={18} />
          <span>Share</span>
        </button>
      </div>

      {/* Comments Drawer Modal */}
      {showCommentsModal && (
        <div className="modal-overlay" onClick={() => setShowCommentsModal(false)}>
          <div 
            className="modal-bottom-sheet" 
            style={{ height: '70vh' }}
            onClick={(e) => e.stopPropagation()}
          >
            <div className="modal-header">
              <h3 className="modal-title">Comments ({post.comments?.length || 0})</h3>
              <button 
                className="icon-btn" 
                style={{ width: 32, height: 32 }} 
                onClick={() => setShowCommentsModal(false)}
              >
                <X size={18} />
              </button>
            </div>

            {/* Comments List */}
            <div style={{ flex: 1, overflowY: 'auto', padding: '12px 16px', display: 'flex', flexDirection: 'column', gap: 14 }}>
              {post.comments && post.comments.length > 0 ? (
                post.comments.map((comment) => (
                  <div key={comment.id} style={{ display: 'flex', gap: 10 }}>
                    <img 
                      src={comment.author.avatar} 
                      alt={comment.author.name} 
                      style={{ width: 34, height: 34, borderRadius: '50%', objectFit: 'cover' }} 
                    />
                    <div style={{ flex: 1 }}>
                      <div style={{ 
                        backgroundColor: 'var(--bg-input)', 
                        padding: '8px 12px', 
                        borderRadius: 14,
                        display: 'inline-block',
                        maxWidth: '92%'
                      }}>
                        <div style={{ fontWeight: 700, fontSize: 13 }}>{comment.author.name}</div>
                        <div style={{ fontSize: 13.5, marginTop: 2 }}>{comment.text}</div>
                      </div>
                      <div style={{ display: 'flex', gap: 14, fontSize: 11.5, color: 'var(--text-muted)', marginTop: 4, marginLeft: 8 }}>
                        <span>{comment.timeAgo}</span>
                        <span style={{ fontWeight: 600, cursor: 'pointer', color: 'var(--text-secondary)' }}>Like</span>
                        <span style={{ fontWeight: 600, cursor: 'pointer', color: 'var(--text-secondary)' }}>Reply</span>
                      </div>
                    </div>
                  </div>
                ))
              ) : (
                <div style={{ textAlign: 'center', color: 'var(--text-muted)', padding: '40px 0' }}>
                  No comments yet. Be the first to comment! 💬
                </div>
              )}
            </div>

            {/* Post Comment Input Bar */}
            <form onSubmit={handlePostComment} style={{ 
              padding: '10px 16px calc(var(--safe-bottom) + 10px) 16px', 
              borderTop: '1px solid var(--border-color)',
              display: 'flex',
              alignItems: 'center',
              gap: 8
            }}>
              <img 
                src={currentUser.avatar} 
                alt="Me" 
                style={{ width: 32, height: 32, borderRadius: '50%', objectFit: 'cover' }} 
              />
              <input
                type="text"
                placeholder="Write a comment..."
                value={commentInput}
                onChange={(e) => setCommentInput(e.target.value)}
                style={{
                  flex: 1,
                  backgroundColor: 'var(--bg-input)',
                  border: 'none',
                  borderRadius: 20,
                  padding: '8px 14px',
                  color: 'var(--text-primary)',
                  fontSize: 13.5,
                  outline: 'none'
                }}
              />
              <button 
                type="submit" 
                disabled={!commentInput.trim()}
                style={{
                  background: 'none',
                  border: 'none',
                  color: commentInput.trim() ? 'var(--primary)' : 'var(--text-muted)',
                  cursor: 'pointer',
                  padding: 6
                }}
              >
                <Send size={18} />
              </button>
            </form>
          </div>
        </div>
      )}

      {/* Share Modal */}
      {showShareModal && (
        <div className="modal-overlay" onClick={() => setShowShareModal(false)}>
          <div 
            className="modal-bottom-sheet" 
            onClick={(e) => e.stopPropagation()}
          >
            <div className="modal-header">
              <h3 className="modal-title">Share Post</h3>
              <button className="icon-btn" style={{ width: 32, height: 32 }} onClick={() => setShowShareModal(false)}>
                <X size={18} />
              </button>
            </div>
            <div style={{ padding: 16, display: 'flex', flexDirection: 'column', gap: 10 }}>
              <button 
                className="btn-primary" 
                onClick={() => {
                  alert('Shared to your Feed successfully! 🎉');
                  setShowShareModal(false);
                }}
              >
                Share to Feed Now
              </button>
              <button 
                className="composer-input-fake" 
                style={{ textAlign: 'center', color: 'var(--text-primary)' }}
                onClick={() => {
                  navigator.clipboard?.writeText(window.location.href);
                  alert('Link copied to clipboard! 📋');
                  setShowShareModal(false);
                }}
              >
                Copy Post Link
              </button>
            </div>
          </div>
        </div>
      )}
    </article>
  );
}
