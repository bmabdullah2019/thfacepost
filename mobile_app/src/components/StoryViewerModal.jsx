import React, { useState, useEffect } from 'react';
import { X, Heart, Send, Volume2, VolumeX } from 'lucide-react';

export default function StoryViewerModal({ stories, initialStoryIndex = 0, onClose, onStoryReact }) {
  const [currentIndex, setCurrentIndex] = useState(initialStoryIndex);
  const [progress, setProgress] = useState(0);
  const [replyText, setReplyText] = useState('');
  const [liked, setLiked] = useState(false);

  const currentStory = stories[currentIndex];

  useEffect(() => {
    setProgress(0);
    setLiked(false);
    const interval = setInterval(() => {
      setProgress((prev) => {
        if (prev >= 100) {
          // Go to next story
          if (currentIndex < stories.length - 1) {
            setCurrentIndex((curr) => curr + 1);
            return 0;
          } else {
            clearInterval(interval);
            onClose();
            return 100;
          }
        }
        return prev + 2;
      });
    }, 100);

    return () => clearInterval(interval);
  }, [currentIndex, stories.length, onClose]);

  const handleNext = () => {
    if (currentIndex < stories.length - 1) {
      setCurrentIndex(currentIndex + 1);
    } else {
      onClose();
    }
  };

  const handlePrev = () => {
    if (currentIndex > 0) {
      setCurrentIndex(currentIndex - 1);
    }
  };

  const handleSendReply = (e) => {
    e.preventDefault();
    if (!replyText.trim()) return;
    alert(`Reply sent to ${currentStory.user.name}: "${replyText}"`);
    setReplyText('');
  };

  const handleLikeStory = () => {
    setLiked(!liked);
    if (onStoryReact) onStoryReact(currentStory.id);
  };

  if (!currentStory) return null;

  return (
    <div style={{
      position: 'fixed',
      inset: 0,
      backgroundColor: '#000',
      zIndex: 200,
      display: 'flex',
      flexDirection: 'column',
      maxWidth: 480,
      margin: '0 auto'
    }}>
      {/* Story Progress Bars */}
      <div style={{
        position: 'absolute',
        top: 'calc(var(--safe-top) + 10px)',
        left: 10,
        right: 10,
        display: 'flex',
        gap: 4,
        zIndex: 210
      }}>
        {stories.map((story, index) => (
          <div
            key={story.id}
            style={{
              flex: 1,
              height: 3,
              backgroundColor: 'rgba(255, 255, 255, 0.3)',
              borderRadius: 2,
              overflow: 'hidden'
            }}
          >
            <div
              style={{
                height: '100%',
                backgroundColor: '#ffffff',
                width: index < currentIndex ? '100%' : index === currentIndex ? `${progress}%` : '0%',
                transition: index === currentIndex ? 'width 0.1s linear' : 'none'
              }}
            />
          </div>
        ))}
      </div>

      {/* Story Header */}
      <div style={{
        position: 'absolute',
        top: 'calc(var(--safe-top) + 22px)',
        left: 14,
        right: 14,
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'space-between',
        color: 'white',
        zIndex: 210
      }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
          <img
            src={currentStory.user.avatar}
            alt={currentStory.user.name}
            style={{ width: 38, height: 38, borderRadius: '50%', border: '2px solid white', objectFit: 'cover' }}
          />
          <div>
            <div style={{ fontWeight: 700, fontSize: 14 }}>{currentStory.user.name}</div>
            <div style={{ fontSize: 11, opacity: 0.8 }}>{currentStory.timeAgo} ago</div>
          </div>
        </div>

        <button
          onClick={onClose}
          style={{
            background: 'rgba(0,0,0,0.5)',
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
          <X size={20} />
        </button>
      </div>

      {/* Story Media */}
      <div style={{ flex: 1, position: 'relative', overflow: 'hidden' }}>
        <img
          src={currentStory.mediaUrl}
          alt="Story content"
          style={{ width: '100%', height: '100%', objectFit: 'cover' }}
        />

        {/* Tap areas for next / prev */}
        <div
          onClick={handlePrev}
          style={{ position: 'absolute', top: 0, left: 0, width: '35%', height: '80%', zIndex: 205 }}
        />
        <div
          onClick={handleNext}
          style={{ position: 'absolute', top: 0, right: 0, width: '65%', height: '80%', zIndex: 205 }}
        />

        {/* Caption */}
        {currentStory.caption && (
          <div style={{
            position: 'absolute',
            bottom: 80,
            left: 20,
            right: 20,
            backgroundColor: 'rgba(0,0,0,0.6)',
            backdropFilter: 'blur(8px)',
            color: 'white',
            padding: '10px 16px',
            borderRadius: 12,
            fontSize: 14,
            fontWeight: 500,
            zIndex: 210
          }}>
            {currentStory.caption}
          </div>
        )}
      </div>

      {/* Story Footer / Reply & Reaction */}
      <div style={{
        padding: '12px 16px calc(var(--safe-bottom) + 12px) 16px',
        backgroundColor: 'rgba(0,0,0,0.85)',
        backdropFilter: 'blur(10px)',
        display: 'flex',
        alignItems: 'center',
        gap: 10,
        zIndex: 210
      }}>
        <form onSubmit={handleSendReply} style={{ flex: 1, display: 'flex', gap: 8 }}>
          <input
            type="text"
            placeholder={`Reply to ${currentStory.user.name}...`}
            value={replyText}
            onChange={(e) => setReplyText(e.target.value)}
            style={{
              flex: 1,
              backgroundColor: 'rgba(255, 255, 255, 0.15)',
              border: '1px solid rgba(255, 255, 255, 0.3)',
              borderRadius: 25,
              padding: '10px 16px',
              color: 'white',
              fontSize: 14,
              outline: 'none'
            }}
          />
          {replyText.trim() && (
            <button
              type="submit"
              style={{
                background: '#1877f2',
                border: 'none',
                color: 'white',
                borderRadius: '50%',
                width: 40,
                height: 40,
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                cursor: 'pointer'
              }}
            >
              <Send size={18} />
            </button>
          )}
        </form>

        <button
          onClick={handleLikeStory}
          style={{
            background: 'none',
            border: 'none',
            color: liked ? '#ff2d55' : 'white',
            cursor: 'pointer',
            padding: 6,
            transition: 'transform 0.15s ease'
          }}
        >
          <Heart size={28} fill={liked ? '#ff2d55' : 'none'} />
        </button>
      </div>
    </div>
  );
}
