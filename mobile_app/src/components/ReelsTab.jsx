import React, { useState, useRef } from 'react';
import { Heart, MessageSquare, Share2, Music, Volume2, VolumeX, Check } from 'lucide-react';

export default function ReelsTab({ reels, onLikeReel }) {
  const [muted, setMuted] = useState(true);
  const [followingMap, setFollowingMap] = useState({});

  const toggleFollow = (creatorId) => {
    setFollowingMap(prev => ({
      ...prev,
      [creatorId]: !prev[creatorId]
    }));
  };

  return (
    <div className="reels-container">
      {reels.map((reel) => {
        const isFollowing = followingMap[reel.creator.id] ?? reel.creator.isFollowing;

        return (
          <div key={reel.id} className="reel-item">
            {/* Background Poster / Video Preview */}
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
              {/* Top Mute / Sound Toggle */}
              <div className="reel-top-bar">
                <div style={{ fontWeight: 800, fontSize: 18, textShadow: '0 2px 4px rgba(0,0,0,0.8)' }}>
                  Reels
                </div>
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

              {/* Bottom Info & Action Buttons */}
              <div className="reel-bottom-content">
                {/* Left: Creator & Description */}
                <div className="reel-info-left">
                  <div className="reel-creator">
                    <img
                      src={reel.creator.avatar}
                      alt={reel.creator.name}
                      style={{ width: 38, height: 38, borderRadius: '50%', border: '2px solid white', objectFit: 'cover' }}
                    />
                    <span style={{ fontWeight: 700, fontSize: 14 }}>{reel.creator.name}</span>
                    <button
                      className="reel-follow-btn"
                      onClick={() => toggleFollow(reel.creator.id)}
                    >
                      {isFollowing ? (
                        <span style={{ display: 'flex', alignItems: 'center', gap: 3 }}>
                          <Check size={12} /> Following
                        </span>
                      ) : (
                        'Follow'
                      )}
                    </button>
                  </div>

                  <p className="reel-desc">{reel.description}</p>

                  <div className="reel-music-pill">
                    <Music size={12} />
                    <span style={{ maxWidth: 200, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>
                      {reel.music}
                    </span>
                  </div>
                </div>

                {/* Right: Actions */}
                <div className="reel-actions-right">
                  <button
                    className="reel-action-btn"
                    onClick={() => onLikeReel(reel.id)}
                  >
                    <div style={{
                      width: 44,
                      height: 44,
                      borderRadius: '50%',
                      background: 'rgba(0,0,0,0.4)',
                      backdropFilter: 'blur(6px)',
                      display: 'flex',
                      alignItems: 'center',
                      justifyContent: 'center'
                    }}>
                      <Heart
                        size={26}
                        fill={reel.isLiked ? '#ff2d55' : 'none'}
                        color={reel.isLiked ? '#ff2d55' : 'white'}
                      />
                    </div>
                    <span>{reel.likes}</span>
                  </button>

                  <button
                    className="reel-action-btn"
                    onClick={() => alert(`Comments for ${reel.creator.name}'s reel!`)}
                  >
                    <div style={{
                      width: 44,
                      height: 44,
                      borderRadius: '50%',
                      background: 'rgba(0,0,0,0.4)',
                      backdropFilter: 'blur(6px)',
                      display: 'flex',
                      alignItems: 'center',
                      justifyContent: 'center'
                    }}>
                      <MessageSquare size={24} />
                    </div>
                    <span>{reel.comments}</span>
                  </button>

                  <button
                    className="reel-action-btn"
                    onClick={() => alert('Shared reel to your story!')}
                  >
                    <div style={{
                      width: 44,
                      height: 44,
                      borderRadius: '50%',
                      background: 'rgba(0,0,0,0.4)',
                      backdropFilter: 'blur(6px)',
                      display: 'flex',
                      alignItems: 'center',
                      justifyContent: 'center'
                    }}>
                      <Share2 size={24} />
                    </div>
                    <span>{reel.shares}</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        );
      })}
    </div>
  );
}
