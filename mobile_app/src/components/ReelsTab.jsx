import React, { useState, useRef } from 'react';
import { Heart, MessageSquare, Share2, Music, Volume2, VolumeX, Plus, Film, CheckCircle2 } from 'lucide-react';

export default function ReelsTab({ reels, onLikeReel, onAddNewReel, currentUser }) {
  const [muted, setMuted] = useState(true);
  const [followingMap, setFollowingMap] = useState({});
  const videoInputRef = useRef(null);

  const toggleFollow = (creatorId) => {
    setFollowingMap(prev => ({
      ...prev,
      [creatorId]: !prev[creatorId]
    }));
  };

  const handleVideoSelect = (e) => {
    const file = e.target.files[0];
    if (file) {
      const videoBlobUrl = URL.createObjectURL(file);
      const newReel = {
        id: `reel_${Date.now()}`,
        videoUrl: videoBlobUrl,
        posterUrl: "https://images.unsplash.com/photo-1518770660439-4636190af475?w=800&auto=format&fit=crop&q=80",
        creator: {
          id: currentUser?.id || `u_${Date.now()}`,
          name: currentUser?.name || 'My Reel',
          username: currentUser?.username || 'user',
          avatar: currentUser?.avatar || 'https://thefacepost.com/themes/flavor/images/user-red.png',
          isFollowing: false,
          verified: true
        },
        caption: "Check out my new video reel! 🎬✨ #TheFacePost #Reels",
        audioTrack: "Original Sound • " + (currentUser?.name || 'Creator'),
        likes: '1',
        commentsCount: '0',
        sharesCount: '0',
        isLiked: true
      };

      if (onAddNewReel) {
        onAddNewReel(newReel);
      }
    }
  };

  return (
    <div className="reels-container">
      {/* Hidden Video File Picker */}
      <input 
        type="file" 
        ref={videoInputRef}
        onChange={handleVideoSelect}
        accept="video/*"
        style={{ display: 'none' }}
      />

      {reels.map((reel) => {
        const isFollowing = followingMap[reel.creator.id] ?? reel.creator.isFollowing;

        return (
          <div key={reel.id} className="reel-item">
            {/* Video Player */}
            <video
              src={reel.videoUrl}
              poster={reel.posterUrl}
              className="reel-video"
              loop
              autoPlay
              muted={muted}
              playsInline
              onClick={() => setMuted(!muted)}
            />

            {/* Overlaid UI */}
            <div className="reel-overlay">
              {/* Top Header Bar with Upload Button */}
              <div className="reel-top-bar" style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '16px 16px 0' }}>
                <div style={{ fontWeight: 800, fontSize: 20, color: 'white', textShadow: '0 2px 4px rgba(0,0,0,0.8)' }}>
                  Reels
                </div>
                
                <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                  <button
                    onClick={() => videoInputRef.current?.click()}
                    style={{
                      display: 'flex',
                      alignItems: 'center',
                      gap: 4,
                      backgroundColor: 'rgba(24, 119, 242, 0.9)',
                      border: 'none',
                      color: 'white',
                      padding: '6px 12px',
                      borderRadius: 20,
                      fontWeight: 700,
                      fontSize: 12,
                      cursor: 'pointer',
                      backdropFilter: 'blur(8px)'
                    }}
                  >
                    <Plus size={14} strokeWidth={3} />
                    <span>Upload</span>
                  </button>

                  <button
                    onClick={() => setMuted(!muted)}
                    style={{
                      background: 'rgba(0,0,0,0.5)',
                      backdropFilter: 'blur(8px)',
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
                    {muted ? <VolumeX size={18} /> : <Volume2 size={18} />}
                  </button>
                </div>
              </div>

              {/* Right Side Interaction Buttons */}
              <div className="reel-side-actions">
                <button className="reel-action-btn" onClick={() => onLikeReel(reel.id)}>
                  <div className={`reel-icon-circle ${reel.isLiked ? 'liked' : ''}`}>
                    <Heart size={26} fill={reel.isLiked ? "#ff2d55" : "none"} color={reel.isLiked ? "#ff2d55" : "white"} />
                  </div>
                  <span>{reel.likes}</span>
                </button>

                <button className="reel-action-btn">
                  <div className="reel-icon-circle">
                    <MessageSquare size={24} color="white" />
                  </div>
                  <span>{reel.commentsCount}</span>
                </button>

                <button className="reel-action-btn" onClick={() => alert('Reel link copied to clipboard! 🔗✨')}>
                  <div className="reel-icon-circle">
                    <Share2 size={24} color="white" />
                  </div>
                  <span>{reel.sharesCount}</span>
                </button>

                <div className="music-disc-spin">
                  <Music size={16} color="white" />
                </div>
              </div>

              {/* Bottom Creator Info & Caption */}
              <div className="reel-bottom-info">
                <div className="reel-creator-row">
                  <img
                    src={reel.creator.avatar}
                    alt={reel.creator.name}
                    className="reel-creator-avatar"
                    onError={(e) => { e.target.src = 'https://thefacepost.com/themes/flavor/images/user-red.png'; }}
                  />
                  <div className="reel-creator-details">
                    <div style={{ display: 'flex', alignItems: 'center', gap: 4 }}>
                      <span style={{ fontWeight: 700, fontSize: 14 }}>{reel.creator.name}</span>
                      {reel.creator.verified && <CheckCircle2 size={14} color="#1877f2" fill="#1877f2" stroke="#fff" />}
                    </div>
                    <span style={{ fontSize: 12, opacity: 0.8 }}>@{reel.creator.username}</span>
                  </div>

                  <button
                    className={`reel-follow-btn ${isFollowing ? 'following' : ''}`}
                    onClick={() => toggleFollow(reel.creator.id)}
                  >
                    {isFollowing ? 'Following' : 'Follow'}
                  </button>
                </div>

                <div className="reel-caption">
                  {reel.caption}
                </div>

                <div className="reel-audio-track">
                  <Music size={14} />
                  <span>{reel.audioTrack}</span>
                </div>
              </div>
            </div>
          </div>
        );
      })}
    </div>
  );
}
