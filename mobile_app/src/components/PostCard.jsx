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

const defaultAvatar = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2394a3b8'%3E%3Cpath fill-rule='evenodd' d='M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z' clip-rule='evenodd'/%3E%3C/svg%3E";

export default function PostCard({ post, onReact, onAddComment, currentUser }) {
  const [showReactionPicker, setShowReactionPicker] = useState(false);
  const [showCommentsModal, setShowCommentsModal] = useState(false);
  const [commentInput, setCommentInput] = useState('');

  const totalReactions = Object.values(post.reactions || {}).reduce((a, b) => a + b, 0) + (post.userReaction ? 1 : 0);

  const handleSelectReaction = (type) => {
    onReact(post.id, type);
    setShowReactionPicker(false);
  };

  const handleQuickLike = () => {
    if (post.userReaction) {
      onReact(post.id, null);
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
        avatar: currentUser.avatar || defaultAvatar
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
      {/* Post Author Header */}
      <div className="post-header">
        <div className="post-author-info">
          <img 
            src={post.author.avatar || defaultAvatar} 
            alt={post.author.name} 
            style={{ width: 40, height: 40, borderRadius: '50%', objectFit: 'cover' }} 
            onError={(e) => { e.target.src = defaultAvatar; }}
          />
          <div>
            <div className="post-author-name">
              <span>{post.author.name}</span>
              {post.author.verified && <CheckCircle2 size={15} color="#1877f2" fill="#1877f2" stroke="#fff" />}
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

      {/* Post Content Text */}
      {post.content && (
        <div className="post-content">
          {post.content}
        </div>
      )}

      {/* Post Media Attachment */}
      {(post.image || (post.media && post.media.length > 0)) && (
        <div className="post-media-grid">
          {post.image ? (
            <img 
              src={post.image} 
              alt="Post attachment" 
              loading="lazy" 
              onError={(e) => { e.target.style.display = 'none'; }}
            />
          ) : (
            post.media.map((imgUrl, i) => (
              <img 
                key={i} 
                src={imgUrl} 
                alt="Post content" 
                loading="lazy" 
                onError={(e) => { e.target.style.display = 'none'; }} 
              />
            ))
          )}
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
              <span>{config.icon}</span>
            </button>
          ))}
        </div>
      )}

      {/* Post Actions Bar with action-bar-btn */}
      <div className="post-actions-bar">
        <button 
          className={`action-bar-btn ${currentReactionInfo ? currentReactionInfo.class : ''}`}
          onClick={handleQuickLike}
          onMouseEnter={() => setShowReactionPicker(true)}
          onTouchStart={() => {
            const timer = setTimeout(() => setShowReactionPicker(true), 400);
            return () => clearTimeout(timer);
          }}
        >
          {currentReactionInfo ? (
            <>
              <span style={{ fontSize: 18 }}>{currentReactionInfo.icon}</span>
              <span style={{ color: currentReactionInfo.color, fontWeight: 700 }}>
                {currentReactionInfo.label}
              </span>
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
          onClick={() => alert('Post link copied to clipboard! 🔗✨')}
        >
          <Share2 size={18} />
          <span>Share</span>
        </button>
      </div>

      {/* Embedded Quick Comments Preview */}
      {post.comments && post.comments.length > 0 && (
        <div className="post-comments-preview">
          {post.comments.slice(-2).map((c) => (
            <div key={c.id} className="comment-bubble-wrapper">
              <img 
                src={c.author?.avatar || c.avatar || defaultAvatar} 
                alt="Commenter" 
                className="comment-avatar"
                onError={(e) => { e.target.src = defaultAvatar; }}
              />
              <div className="comment-bubble">
                <span className="comment-author-name">{c.author?.name || c.user || 'Member'}</span>
                <p className="comment-text">{c.text}</p>
              </div>
            </div>
          ))}

          {post.comments.length > 2 && (
            <button 
              className="view-all-comments-btn"
              onClick={() => setShowCommentsModal(true)}
            >
              View all {post.comments.length} comments
            </button>
          )}
        </div>
      )}

      {/* Full Comments Sheet Modal */}
      {showCommentsModal && (
        <div className="modal-backdrop" onClick={() => setShowCommentsModal(false)}>
          <div className="modal-sheet" onClick={(e) => e.stopPropagation()}>
            <div className="modal-header">
              <span className="modal-title">Comments</span>
              <button className="icon-btn" onClick={() => setShowCommentsModal(false)}>
                <X size={20} />
              </button>
            </div>

            <div className="modal-body" style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
              {post.comments && post.comments.length > 0 ? (
                post.comments.map((c) => (
                  <div key={c.id} className="comment-bubble-wrapper">
                    <img 
                      src={c.author?.avatar || c.avatar || defaultAvatar} 
                      alt="Commenter" 
                      className="comment-avatar" 
                      onError={(e) => { e.target.src = defaultAvatar; }}
                    />
                    <div style={{ flex: 1 }}>
                      <div className="comment-bubble">
                        <span className="comment-author-name">{c.author?.name || c.user || 'Member'}</span>
                        <p className="comment-text">{c.text}</p>
                      </div>
                      <div className="comment-meta-actions">
                        <span>{c.timeAgo || 'Just now'}</span>
                        <button>Like</button>
                        <button>Reply</button>
                      </div>
                    </div>
                  </div>
                ))
              ) : (
                <div style={{ textAlign: 'center', padding: '32px 0', color: 'var(--text-muted)' }}>
                  No comments yet. Be the first to say something! 💬
                </div>
              )}
            </div>

            {/* Comment Input Footer */}
            <form onSubmit={handlePostComment} className="comment-composer-footer">
              <img 
                src={currentUser.avatar || defaultAvatar} 
                alt="Me" 
                style={{ width: 34, height: 34, borderRadius: '50%', objectFit: 'cover' }} 
                onError={(e) => { e.target.src = defaultAvatar; }}
              />
              <div className="comment-input-box">
                <input
                  type="text"
                  placeholder="Write a comment..."
                  value={commentInput}
                  onChange={(e) => setCommentInput(e.target.value)}
                  autoFocus
                />
                <button type="submit" disabled={!commentInput.trim()}>
                  <Send size={16} />
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </article>
  );
}
